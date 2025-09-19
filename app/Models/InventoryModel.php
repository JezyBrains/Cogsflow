<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'item_code',
        'grain_type', 
        'description',
        'current_stock_mt',
        'minimum_level_mt',
        'unit_cost',
        'location',
        'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'item_code' => 'required|max_length[50]',
        'grain_type' => 'required|max_length[50]',
        'current_stock_mt' => 'required|decimal',
        'minimum_level_mt' => 'permit_empty|decimal',
        'unit_cost' => 'permit_empty|decimal',
        'location' => 'permit_empty|max_length[100]',
        'status' => 'permit_empty|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'item_code' => [
            'required' => 'Item code is required',
            'max_length' => 'Item code cannot exceed 50 characters'
        ],
        'grain_type' => [
            'required' => 'Grain type is required',
            'max_length' => 'Grain type cannot exceed 50 characters'
        ],
        'current_stock_mt' => [
            'required' => 'Current stock is required',
            'decimal' => 'Current stock must be a valid number'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

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
     * Get inventory item by grain type
     */
    public function getByGrainType($grainType)
    {
        return $this->where('grain_type', $grainType)
                   ->where('status', 'active')
                   ->first();
    }

    /**
     * Update stock quantity for a grain type
     */
    public function updateStock($grainType, $quantity, $adjustmentType = 'Stock In')
    {
        $existingItem = $this->getByGrainType($grainType);
        
        if ($existingItem) {
            // Update existing item
            $newStock = $existingItem['current_stock_mt'];
            
            if ($adjustmentType === 'Stock In' || $adjustmentType === 'Stock Correction') {
                $newStock += $quantity;
            } elseif ($adjustmentType === 'Stock Out' || $adjustmentType === 'Damage/Loss') {
                $newStock -= $quantity;
                $newStock = max(0, $newStock); // Don't allow negative stock
            }
            
            return $this->update($existingItem['id'], [
                'current_stock_mt' => $newStock
            ]);
        } else {
            // Create new inventory item
            $itemCode = strtoupper(substr($grainType, 0, 3)) . date('Ymd') . rand(100, 999);
            
            return $this->insert([
                'item_code' => $itemCode,
                'grain_type' => $grainType,
                'description' => $grainType . ' inventory',
                'current_stock_mt' => max(0, $quantity),
                'minimum_level_mt' => 10.0, // Default minimum level
                'unit_cost' => 0.0,
                'location' => 'Main Warehouse',
                'status' => 'active'
            ]);
        }
    }

    /**
     * Get low stock items
     */
    public function getLowStockItems()
    {
        return $this->where('current_stock_mt <=', 'minimum_level_mt', false)
                   ->where('status', 'active')
                   ->findAll();
    }

    /**
     * Get inventory summary with grain types breakdown
     */
    public function getInventorySummary()
    {
        // Get basic summary stats
        $totalItems = $this->where('status', 'active')->countAllResults();
        
        $builder = $this->db->table('inventory');
        $builder->selectSum('current_stock_mt', 'total_stock');
        $builder->where('status', 'active');
        $totalStock = $builder->get()->getRowArray()['total_stock'] ?? 0;
        
        $lowStockCount = $this->where('current_stock_mt <=', 'minimum_level_mt', false)
                             ->where('status', 'active')
                             ->countAllResults();
        
        // Get grain types breakdown
        $grainTypesBuilder = $this->db->table('inventory');
        $grainTypesBuilder->select('grain_type, SUM(current_stock_mt) as total_stock_mt, COUNT(*) as item_count');
        $grainTypesBuilder->where('status', 'active');
        $grainTypesBuilder->groupBy('grain_type');
        $grainTypesBuilder->orderBy('total_stock_mt', 'DESC');
        $grainTypes = $grainTypesBuilder->get()->getResultArray();
        
        // Get low stock items by grain type
        $lowStockBuilder = $this->db->table('inventory');
        $lowStockBuilder->select('grain_type, COUNT(*) as low_stock_count');
        $lowStockBuilder->where('current_stock_mt <=', 'minimum_level_mt', false);
        $lowStockBuilder->where('status', 'active');
        $lowStockBuilder->groupBy('grain_type');
        $lowStockByType = $lowStockBuilder->get()->getResultArray();
        
        // Merge low stock data with grain types
        $lowStockMap = [];
        foreach ($lowStockByType as $item) {
            $lowStockMap[$item['grain_type']] = $item['low_stock_count'];
        }
        
        foreach ($grainTypes as &$grainType) {
            $grainType['low_stock_count'] = $lowStockMap[$grainType['grain_type']] ?? 0;
        }
        
        return [
            'total_items' => $totalItems,
            'total_stock_mt' => $totalStock,
            'low_stock_count' => $lowStockCount,
            'grain_types' => $grainTypes
        ];
    }

    /**
     * Get all active inventory items
     */
    public function getActiveItems()
    {
        return $this->where('status', 'active')
                   ->orderBy('grain_type', 'ASC')
                   ->findAll();
    }
}
