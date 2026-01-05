<?php

namespace App\Models;

use CodeIgniter\Model;

class DispatchModel extends Model
{
    protected $table = 'dispatches';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'dispatch_number',
        'batch_id',
        'vehicle_number',
        'trailer_number',
        'driver_name',
        'driver_phone',
        'driver_id_number',
        'dispatcher_name',
        'destination',
        'estimated_arrival',
        'actual_departure',
        'actual_arrival',
        'status',
        'notes',
        'received_by',
        'inspection_date',
        'actual_bags',
        'actual_weight_kg',
        'actual_weight_mt',
        'discrepancies',
        'inspection_notes',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'dispatch_number' => 'permit_empty|min_length[3]|max_length[50]',
        'batch_id' => 'required|integer',
        'vehicle_number' => 'required|min_length[3]|max_length[20]',
        'trailer_number' => 'permit_empty|min_length[3]|max_length[20]',
        'driver_name' => 'required|min_length[3]|max_length[255]',
        'driver_phone' => 'permit_empty|regex_match[/^(\\+255|0)?\\s?[67]\\d{2}\\s?\\d{3}\\s?\\d{3}$/]',
        'driver_id_number' => 'permit_empty|min_length[3]|max_length[50]',
        'dispatcher_name' => 'required|min_length[3]|max_length[255]',
        'destination' => 'required|min_length[3]|max_length[255]',
        'estimated_arrival' => 'required|valid_date',
        'status' => 'required|in_list[pending,in_transit,arrived,delivered,cancelled]',
        'notes' => 'permit_empty|max_length[500]',
        'actual_bags' => 'permit_empty|integer|greater_than[0]',
        'actual_weight_kg' => 'permit_empty|decimal|greater_than[0]',
        'discrepancies' => 'permit_empty|max_length[1000]',
        'inspection_notes' => 'permit_empty|max_length[1000]'
    ];

    protected $validationMessages = [
        'batch_id' => [
            'required' => 'Batch ID is required',
            'integer' => 'Batch ID must be a valid number'
        ],
        'vehicle_number' => [
            'required' => 'Vehicle number is required',
            'min_length' => 'Vehicle number must be at least 3 characters',
            'max_length' => 'Vehicle number cannot exceed 20 characters'
        ],
        'driver_name' => [
            'required' => 'Driver name is required',
            'min_length' => 'Driver name must be at least 3 characters',
            'max_length' => 'Driver name cannot exceed 255 characters'
        ],
        'dispatcher_name' => [
            'required' => 'Dispatcher name is required',
            'min_length' => 'Dispatcher name must be at least 3 characters',
            'max_length' => 'Dispatcher name cannot exceed 255 characters'
        ],
        'estimated_arrival' => [
            'required' => 'Estimated arrival is required',
            'valid_date' => 'Please provide a valid estimated arrival date'
        ],
        'destination' => [
            'required' => 'Destination is required',
            'min_length' => 'Destination must be at least 3 characters',
            'max_length' => 'Destination cannot exceed 255 characters'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be one of: pending, in_transit, arrived, delivered, cancelled'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get dispatches with batch and supplier information
     *
     * @return array
     */
    public function getDispatchesWithBatchInfo()
    {
        return $this->select('dispatches.*, batches.batch_number, batches.grain_type, batches.total_weight_kg, suppliers.name as supplier_name')
                    ->join('batches', 'batches.id = dispatches.batch_id', 'left')
                    ->join('suppliers', 'suppliers.id = batches.supplier_id', 'left')
                    ->orderBy('dispatches.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get dispatch by ID with batch information
     *
     * @param int $id
     * @return array|null
     */
    public function getDispatchWithBatchInfo($id)
    {
        return $this->select('dispatches.*, batches.batch_number, batches.grain_type, batches.total_weight_kg, batches.total_bags, suppliers.name as supplier_name, suppliers.contact_person, suppliers.phone')
                    ->join('batches', 'batches.id = dispatches.batch_id', 'left')
                    ->join('suppliers', 'suppliers.id = batches.supplier_id', 'left')
                    ->where('dispatches.id', $id)
                    ->first();
    }

    /**
     * Get available batches for dispatch (approved batches only)
     *
     * @return array
     */
    public function getAvailableBatches()
    {
        $batchModel = new \App\Models\BatchModel();
        return $batchModel->select('batches.id, batches.batch_number, batches.grain_type, batches.total_weight_kg, suppliers.name as supplier_name')
                         ->join('suppliers', 'suppliers.id = batches.supplier_id', 'left')
                         ->where('batches.status', 'approved')
                         ->whereNotIn('batches.id', function($builder) {
                             return $builder->select('batch_id')->from('dispatches')->where('status !=', 'cancelled');
                         })
                         ->orderBy('batches.created_at', 'DESC')
                         ->findAll();
    }

    /**
     * Get dispatch statistics
     *
     * @return array
     */
    public function getDispatchStats()
    {
        // Use fresh queries for each count to avoid query builder state issues
        $stats = [
            'total_dispatches' => $this->countAll(),
            'pending_dispatches' => $this->where('status', 'pending')->countAllResults(),
            'in_transit_dispatches' => $this->where('status', 'in_transit')->countAllResults(),
            'arrived_dispatches' => $this->where('status', 'arrived')->countAllResults(),
            'delivered_dispatches' => $this->where('status', 'delivered')->countAllResults(),
            'cancelled_dispatches' => $this->where('status', 'cancelled')->countAllResults()
        ];

        // Calculate total weight dispatched using fresh query
        $builder = $this->db->table('dispatches d');
        $builder->select('SUM(b.total_weight_kg) as total_weight');
        $builder->join('batches b', 'b.id = d.batch_id', 'left');
        $builder->where('d.status !=', 'cancelled');
        $query = $builder->get();
        
        if ($query === false) {
            $stats['total_weight_dispatched'] = 0;
        } else {
            $result = $query->getRowArray();
            $stats['total_weight_dispatched'] = ($result['total_weight'] ?? 0) / 1000; // Convert kg to MT
        }

        return $stats;
    }

    /**
     * Get dispatches awaiting inspection (arrived status)
     */
    public function getDispatchesAwaitingInspection()
    {
        return $this->select('dispatches.*, batches.batch_number, batches.grain_type, batches.total_weight_kg, batches.total_bags, suppliers.name as supplier_name')
                    ->join('batches', 'batches.id = dispatches.batch_id', 'left')
                    ->join('suppliers', 'suppliers.id = batches.supplier_id', 'left')
                    ->where('dispatches.status', 'arrived')
                    ->orderBy('dispatches.actual_arrival', 'ASC')
                    ->findAll();
    }

    /**
     * Calculate discrepancies between sent and received quantities
     */
    public function calculateDiscrepancies($dispatchId, $actualBags, $actualWeightKg)
    {
        $dispatch = $this->getDispatchWithBatchInfo($dispatchId);
        if (!$dispatch) {
            return null;
        }

        $expectedBags = $dispatch['total_bags'];
        $expectedWeightKg = $dispatch['total_weight_kg'];
        $actualWeightMt = round($actualWeightKg / 1000, 3);

        $bagDiscrepancy = $actualBags - $expectedBags;
        $weightDiscrepancyKg = $actualWeightKg - $expectedWeightKg;
        $weightDiscrepancyMt = round($weightDiscrepancyKg / 1000, 3);
        $weightDiscrepancyPercentage = round(($weightDiscrepancyKg / $expectedWeightKg) * 100, 2);

        // Standardized tolerance thresholds (matching BatchReceivingController)
        $bagsTolerance = 0; // No tolerance for bag count
        $weightTolerancePercent = 2.0; // 2% tolerance for weight

        $discrepancies = [];
        
        if (abs($bagDiscrepancy) > $bagsTolerance) {
            $discrepancies[] = "Bag count: Expected {$expectedBags}, Received {$actualBags} (Difference: {$bagDiscrepancy})";
        }
        
        if (abs($weightDiscrepancyPercentage) > $weightTolerancePercent) {
            $discrepancies[] = "Weight: Expected {$expectedWeightKg}kg, Received {$actualWeightKg}kg (Difference: {$weightDiscrepancyKg}kg, {$weightDiscrepancyPercentage}%)";
        }

        return [
            'has_discrepancies' => !empty($discrepancies),
            'discrepancy_list' => $discrepancies,
            'discrepancy_summary' => implode('; ', $discrepancies),
            'expected_bags' => $expectedBags,
            'actual_bags' => $actualBags,
            'bag_difference' => $bagDiscrepancy,
            'expected_weight_kg' => $expectedWeightKg,
            'actual_weight_kg' => $actualWeightKg,
            'actual_weight_mt' => $actualWeightMt,
            'weight_difference_kg' => $weightDiscrepancyKg,
            'weight_difference_mt' => $weightDiscrepancyMt,
            'weight_difference_percentage' => $weightDiscrepancyPercentage
        ];
    }

    /**
     * Check if user can perform receiving inspection (different from batch creator)
     */
    public function canUserInspectDispatch($dispatchId, $userId)
    {
        $dispatch = $this->select('dispatches.*, batches.created_by as batch_creator')
                         ->join('batches', 'batches.id = dispatches.batch_id', 'left')
                         ->where('dispatches.id', $dispatchId)
                         ->first();
        
        if (!$dispatch) {
            return ['can_inspect' => false, 'message' => 'Dispatch not found'];
        }

        if ($dispatch['status'] !== 'arrived') {
            return ['can_inspect' => false, 'message' => 'Only arrived dispatches can be inspected'];
        }

        // Ensure receiving officer is different from batch creator
        if ($dispatch['batch_creator'] == $userId) {
            return ['can_inspect' => false, 'message' => 'Batch creator cannot perform receiving inspection. A different officer must conduct the inspection.'];
        }

        return ['can_inspect' => true, 'message' => 'User can perform receiving inspection'];
    }
}
