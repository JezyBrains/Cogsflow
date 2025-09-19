<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table            = 'suppliers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'status',
        'notes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name' => 'required|min_length[2]|max_length[255]|is_unique[suppliers.name,id,{id}]',
        'contact_person' => 'permit_empty|max_length[255]',
        'phone' => 'permit_empty|max_length[20]',
        'email' => 'permit_empty|valid_email|max_length[255]',
        'address' => 'permit_empty',
        'status' => 'permit_empty|in_list[active,inactive,archived]'
    ];

    protected $validationMessages   = [
        'name' => [
            'required' => 'Supplier name is required',
            'min_length' => 'Supplier name must be at least 2 characters',
            'max_length' => 'Supplier name cannot exceed 255 characters',
            'is_unique' => 'This supplier name already exists'
        ],
        'email' => [
            'valid_email' => 'Please enter a valid email address'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get active suppliers for dropdowns
     */
    public function getActiveSuppliers()
    {
        try {
            // Check if table exists first
            if (!$this->db->tableExists('suppliers')) {
                return [];
            }
            
            // Use query builder directly to handle errors better
            $builder = $this->db->table('suppliers');
            $query = $builder->where('status', 'active')
                           ->orderBy('name', 'ASC')
                           ->get();
            
            if ($query === false) {
                return [];
            }
            
            return $query->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', 'SupplierModel::getActiveSuppliers() error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get suppliers by type
     */
    public function getSuppliersByType($type)
    {
        return $this->where('status', 'active')
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    /**
     * Search suppliers with filters
     */
    public function searchSuppliers($search = '', $type = '', $status = 'active', $limit = 20, $offset = 0)
    {
        $builder = $this->builder();
        
        if (!empty($search)) {
            $builder->groupStart()
                   ->like('name', $search)
                   ->orLike('contact_person', $search)
                   ->orLike('email', $search)
                   ->groupEnd();
        }
        
        // Type filter removed since supplier_type column doesn't exist
        
        if (!empty($status)) {
            $builder->where('status', $status);
        }
        
        return $builder->orderBy('name', 'ASC')
                      ->limit($limit, $offset)
                      ->get()
                      ->getResultArray();
    }

    /**
     * Get supplier statistics
     */
    public function getSupplierStats($supplierId)
    {
        try {
            $db = \Config\Database::connect();
            
            // Get purchase orders count (with error handling)
            $poCount = 0;
            if ($db->tableExists('purchase_orders')) {
                try {
                    $poCount = $db->table('purchase_orders')
                                 ->where('supplier_id', $supplierId)
                                 ->countAllResults();
                } catch (\Throwable $e) {
                    log_message('error', 'Error counting purchase orders: ' . $e->getMessage());
                }
            }
            
            // Get batches count  
            $batchCount = 0;
            if ($db->tableExists('batches')) {
                try {
                    $batchCount = $db->table('batches')
                                    ->where('supplier_id', $supplierId)
                                    ->countAllResults();
                } catch (\Throwable $e) {
                    log_message('error', 'Error counting batches: ' . $e->getMessage());
                }
            }
            
            // Get total volume (sum of batch weights)
            $totalVolume = 0;
            if ($db->tableExists('batches')) {
                try {
                    $query = $db->table('batches')
                               ->selectSum('total_weight_mt')
                               ->where('supplier_id', $supplierId)
                               ->get();
                    
                    if ($query !== false) {
                        $result = $query->getRow();
                        $totalVolume = $result->total_weight_mt ?? 0;
                    }
                } catch (\Throwable $e) {
                    log_message('error', 'Error getting total volume: ' . $e->getMessage());
                }
            }
            
            // Get dispatch count
            $dispatchCount = 0;
            if ($db->tableExists('dispatches') && $db->tableExists('batches')) {
                try {
                    $dispatchCount = $db->table('dispatches d')
                                       ->join('batches b', 'b.id = d.batch_id')
                                       ->where('b.supplier_id', $supplierId)
                                       ->countAllResults();
                } catch (\Throwable $e) {
                    log_message('error', 'Error counting dispatches: ' . $e->getMessage());
                }
            }
            
            return [
                'purchase_orders' => $poCount,
                'batches' => $batchCount,
                'total_volume_mt' => round($totalVolume, 2),
                'dispatches' => $dispatchCount
            ];
        } catch (\Throwable $e) {
            log_message('error', 'SupplierModel::getSupplierStats() error: ' . $e->getMessage());
            return [
                'purchase_orders' => 0,
                'batches' => 0,
                'total_volume_mt' => 0,
                'dispatches' => 0
            ];
        }
    }

    /**
     * Get supplier financial summary
     */
    public function getSupplierFinancials($supplierId)
    {
        try {
            $db = \Config\Database::connect();
            
            // Check if table exists first
            if (!$db->tableExists('purchase_orders')) {
                return [
                    'total_orders' => 0,
                    'total_paid' => 0,
                    'outstanding_balance' => 0
                ];
            }
            
            $query = $db->table('purchase_orders')
                       ->select('SUM(total_amount) as total_orders, SUM(advance_payment) as total_paid')
                       ->where('supplier_id', $supplierId)
                       ->get();
            
            if ($query === false) {
                return [
                    'total_orders' => 0,
                    'total_paid' => 0,
                    'outstanding_balance' => 0
                ];
            }
            
            $result = $query->getRow();
            
            $totalOrders = $result->total_orders ?? 0;
            $totalPaid = $result->total_paid ?? 0;
            $outstanding = $totalOrders - $totalPaid;
            
            return [
                'total_orders' => round($totalOrders, 2),
                'total_paid' => round($totalPaid, 2),
                'outstanding_balance' => round($outstanding, 2)
            ];
        } catch (\Throwable $e) {
            log_message('error', 'SupplierModel::getSupplierFinancials() error: ' . $e->getMessage());
            return [
                'total_orders' => 0,
                'total_paid' => 0,
                'outstanding_balance' => 0
            ];
        }
    }

    /**
     * Soft delete supplier (archive)
     */
    public function archiveSupplier($id)
    {
        return $this->update($id, ['status' => 'archived']);
    }

    /**
     * Restore archived supplier
     */
    public function restoreSupplier($id)
    {
        return $this->update($id, ['status' => 'active']);
    }
}
