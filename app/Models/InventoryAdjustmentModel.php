<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryAdjustmentModel extends Model
{
    protected $table = 'inventory_adjustments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'grain_type',
        'adjustment_type',
        'quantity',
        'reference',
        'reason',
        'adjusted_by',
        'adjustment_date',
        'previous_stock',
        'new_stock'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'grain_type' => 'required|max_length[50]',
        'adjustment_type' => 'required|in_list[Stock In,Stock Out,Stock Transfer,Stock Correction,Damage/Loss]',
        'quantity' => 'required|decimal|greater_than[0]',
        'reason' => 'required|max_length[500]',
        'adjusted_by' => 'required|max_length[100]',
        'adjustment_date' => 'required|valid_date'
    ];

    protected $validationMessages = [
        'grain_type' => [
            'required' => 'Grain type is required'
        ],
        'adjustment_type' => [
            'required' => 'Adjustment type is required',
            'in_list' => 'Invalid adjustment type selected'
        ],
        'quantity' => [
            'required' => 'Quantity is required',
            'decimal' => 'Quantity must be a valid number',
            'greater_than' => 'Quantity must be greater than 0'
        ],
        'reason' => [
            'required' => 'Reason for adjustment is required'
        ],
        'adjusted_by' => [
            'required' => 'Adjusted by field is required'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Record an inventory adjustment
     */
    public function recordAdjustment($data)
    {
        // Load inventory model to get current stock
        $inventoryModel = new InventoryModel();
        $currentItem = $inventoryModel->getByGrainType($data['grain_type']);
        
        $previousStock = $currentItem ? $currentItem['current_stock_mt'] : 0;
        
        // Calculate new stock based on adjustment type
        $newStock = $previousStock;
        $quantity = floatval($data['quantity']);
        
        switch ($data['adjustment_type']) {
            case 'Stock In':
            case 'Stock Correction':
                $newStock += $quantity;
                break;
            case 'Stock Out':
            case 'Damage/Loss':
                $newStock -= $quantity;
                $newStock = max(0, $newStock); // Don't allow negative stock
                break;
            case 'Stock Transfer':
                // For transfers, quantity could be positive or negative
                $newStock += $quantity;
                break;
        }
        
        // Prepare adjustment record
        $adjustmentData = [
            'grain_type' => $data['grain_type'],
            'adjustment_type' => $data['adjustment_type'],
            'quantity' => $quantity,
            'reference' => $data['reference'] ?? '',
            'reason' => $data['reason'],
            'adjusted_by' => $data['adjusted_by'],
            'adjustment_date' => $data['adjustment_date'],
            'previous_stock' => $previousStock,
            'new_stock' => $newStock
        ];
        
        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Insert adjustment record
            $adjustmentId = $this->insert($adjustmentData);
            
            if (!$adjustmentId) {
                throw new \Exception('Failed to record adjustment');
            }
            
            // Update inventory stock
            $stockUpdated = $inventoryModel->updateStock($data['grain_type'], $quantity, $data['adjustment_type']);
            
            if (!$stockUpdated) {
                throw new \Exception('Failed to update inventory stock');
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }
            
            return $adjustmentId;
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Inventory adjustment failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get adjustment history for a grain type
     */
    public function getAdjustmentHistory($grainType = null, $limit = 50)
    {
        $builder = $this->orderBy('adjustment_date', 'DESC')
                       ->orderBy('created_at', 'DESC');
        
        if ($grainType) {
            $builder->where('grain_type', $grainType);
        }
        
        return $builder->limit($limit)->findAll();
    }

    /**
     * Get adjustments by date range
     */
    public function getAdjustmentsByDateRange($startDate, $endDate)
    {
        return $this->where('adjustment_date >=', $startDate)
                   ->where('adjustment_date <=', $endDate)
                   ->orderBy('adjustment_date', 'DESC')
                   ->findAll();
    }

    /**
     * Get adjustment summary statistics
     */
    public function getAdjustmentSummary($period = '30 days')
    {
        $builder = $this->builder();
        
        $startDate = date('Y-m-d', strtotime("-{$period}"));
        
        return [
            'total_adjustments' => $builder->where('adjustment_date >=', $startDate)->countAllResults(false),
            'stock_in_total' => $builder->where('adjustment_type', 'Stock In')
                                      ->where('adjustment_date >=', $startDate)
                                      ->selectSum('quantity')
                                      ->get()->getRow()->quantity ?? 0,
            'stock_out_total' => $builder->where('adjustment_type', 'Stock Out')
                                       ->where('adjustment_date >=', $startDate)
                                       ->selectSum('quantity')
                                       ->get()->getRow()->quantity ?? 0,
            'damage_loss_total' => $builder->where('adjustment_type', 'Damage/Loss')
                                         ->where('adjustment_date >=', $startDate)
                                         ->selectSum('quantity')
                                         ->get()->getRow()->quantity ?? 0
        ];
    }
}
