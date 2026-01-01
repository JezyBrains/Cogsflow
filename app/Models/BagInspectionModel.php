<?php

namespace App\Models;

use CodeIgniter\Model;

class BagInspectionModel extends Model
{
    protected $table = 'bag_inspections';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'dispatch_id',
        'batch_id',
        'bag_id',
        'bag_number',
        'expected_weight_kg',
        'expected_moisture',
        'actual_weight_kg',
        'actual_moisture',
        'weight_variance_kg',
        'weight_variance_percent',
        'moisture_variance',
        'condition_status',
        'has_discrepancy',
        'inspection_status',
        'inspection_notes',
        'photo_path',
        'voice_note_path',
        'inspected_by',
        'inspected_at',
        'inspection_duration_seconds',
        'qr_scanned',
        'scan_timestamp',
        'device_info'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'dispatch_id' => 'required|integer',
        'batch_id' => 'required|integer',
        'bag_id' => 'required|max_length[50]',
        'bag_number' => 'required|integer',
        'actual_weight_kg' => 'permit_empty|decimal',
        'actual_moisture' => 'permit_empty|decimal',
        'condition_status' => 'permit_empty|in_list[good,damaged,wet,contaminated,missing]',
        'inspection_status' => 'permit_empty|in_list[pending,inspected,skipped]'
    ];

    protected $validationMessages = [
        'dispatch_id' => [
            'required' => 'Dispatch ID is required'
        ],
        'bag_id' => [
            'required' => 'Bag ID is required'
        ]
    ];

    /**
     * Get all inspections for a dispatch
     */
    public function getInspectionsByDispatch($dispatchId)
    {
        return $this->where('dispatch_id', $dispatchId)
                    ->orderBy('bag_number', 'ASC')
                    ->findAll();
    }

    /**
     * Get inspection summary for a dispatch
     */
    public function getInspectionSummary($dispatchId)
    {
        $inspections = $this->where('dispatch_id', $dispatchId)->findAll();
        
        $summary = [
            'total_bags' => count($inspections),
            'inspected' => 0,
            'pending' => 0,
            'skipped' => 0,
            'with_discrepancies' => 0,
            'good_condition' => 0,
            'damaged' => 0,
            'wet' => 0,
            'contaminated' => 0,
            'missing' => 0,
            'total_expected_weight' => 0,
            'total_actual_weight' => 0,
            'weight_variance_percent' => 0
        ];

        foreach ($inspections as $inspection) {
            // Count by status
            if ($inspection['inspection_status'] === 'inspected') {
                $summary['inspected']++;
            } elseif ($inspection['inspection_status'] === 'skipped') {
                $summary['skipped']++;
            } else {
                $summary['pending']++;
            }

            // Count discrepancies
            if ($inspection['has_discrepancy']) {
                $summary['with_discrepancies']++;
            }

            // Count by condition
            switch ($inspection['condition_status']) {
                case 'good':
                    $summary['good_condition']++;
                    break;
                case 'damaged':
                    $summary['damaged']++;
                    break;
                case 'wet':
                    $summary['wet']++;
                    break;
                case 'contaminated':
                    $summary['contaminated']++;
                    break;
                case 'missing':
                    $summary['missing']++;
                    break;
            }

            // Sum weights
            $summary['total_expected_weight'] += (float)$inspection['expected_weight_kg'];
            $summary['total_actual_weight'] += (float)$inspection['actual_weight_kg'];
        }

        // Calculate overall variance
        if ($summary['total_expected_weight'] > 0) {
            $variance = $summary['total_actual_weight'] - $summary['total_expected_weight'];
            $summary['weight_variance_percent'] = round(($variance / $summary['total_expected_weight']) * 100, 2);
        }

        return $summary;
    }

    /**
     * Record bag inspection
     */
    public function recordInspection($data)
    {
        $id = $data['id'] ?? null;
        
        // Get existing record to retrieve expected values
        $existing = null;
        if ($id) {
            $existing = $this->find($id);
        }
        
        // Use expected values from existing record if not provided
        $expectedWeight = $data['expected_weight_kg'] ?? ($existing['expected_weight_kg'] ?? null);
        $expectedMoisture = $data['expected_moisture'] ?? ($existing['expected_moisture'] ?? null);
        
        // Calculate variances
        if ($expectedWeight && isset($data['actual_weight_kg'])) {
            $data['weight_variance_kg'] = $data['actual_weight_kg'] - $expectedWeight;
            
            if ($expectedWeight > 0) {
                $data['weight_variance_percent'] = round(
                    ($data['weight_variance_kg'] / $expectedWeight) * 100, 
                    2
                );
            }
        }

        if ($expectedMoisture && isset($data['actual_moisture'])) {
            $data['moisture_variance'] = $data['actual_moisture'] - $expectedMoisture;
        }

        // Determine if there's a discrepancy (tolerance: 2% for weight, 1% for moisture)
        $weightTolerance = 2.0;
        $moistureTolerance = 1.0;
        
        $hasDiscrepancy = false;
        
        // Check weight discrepancy
        if (isset($data['weight_variance_percent']) && abs($data['weight_variance_percent']) > $weightTolerance) {
            $hasDiscrepancy = true;
        }
        
        // Check moisture discrepancy
        if (isset($data['moisture_variance']) && abs($data['moisture_variance']) > $moistureTolerance) {
            $hasDiscrepancy = true;
        }
        
        // Check condition status - only mark as discrepancy if NOT good
        if (isset($data['condition_status']) && $data['condition_status'] !== 'good') {
            $hasDiscrepancy = true;
        }

        $data['has_discrepancy'] = $hasDiscrepancy;
        $data['inspection_status'] = 'inspected';
        $data['inspected_at'] = date('Y-m-d H:i:s');

        // Update if ID exists, otherwise insert
        if ($id) {
            return $this->update($id, $data);
        } else {
            return $this->insert($data);
        }
    }

    /**
     * Get bags with discrepancies
     */
    public function getBagsWithDiscrepancies($dispatchId)
    {
        return $this->where('dispatch_id', $dispatchId)
                    ->where('has_discrepancy', true)
                    ->orderBy('bag_number', 'ASC')
                    ->findAll();
    }

    /**
     * Get inspection progress percentage
     */
    public function getProgressPercentage($dispatchId)
    {
        $total = $this->where('dispatch_id', $dispatchId)->countAllResults(false);
        $inspected = $this->where('inspection_status', 'inspected')->countAllResults();

        if ($total === 0) {
            return 0;
        }

        return round(($inspected / $total) * 100, 1);
    }

    /**
     * Check if bag already inspected
     */
    public function isBagInspected($bagId, $dispatchId)
    {
        $inspection = $this->where('bag_id', $bagId)
                          ->where('dispatch_id', $dispatchId)
                          ->first();

        return $inspection && $inspection['inspection_status'] === 'inspected';
    }

    /**
     * Get next pending bag for inspection
     */
    public function getNextPendingBag($dispatchId)
    {
        return $this->where('dispatch_id', $dispatchId)
                    ->where('inspection_status', 'pending')
                    ->orderBy('bag_number', 'ASC')
                    ->first();
    }
}
