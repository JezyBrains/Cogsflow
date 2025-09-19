<?php

namespace App\Models;

use CodeIgniter\Model;

class BatchModel extends Model
{
    protected $table = 'batches';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'batch_number', 'supplier_id', 'purchase_order_id', 'grain_type', 'total_bags', 'total_weight_kg',
        'total_weight_mt', 'average_moisture', 'quality_grade', 'status', 'notes', 'received_date',
        'approved_by', 'approved_at', 'rejection_reason'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'batch_number' => 'required|is_unique[batches.batch_number,id,{id}]|max_length[50]',
        'supplier_id' => 'required|integer',
        'purchase_order_id' => 'required|integer',
        'grain_type' => 'required|max_length[100]',
        'total_bags' => 'required|integer|greater_than[0]',
        'total_weight_kg' => 'required|decimal|greater_than[0]',
        'average_moisture' => 'required|decimal|greater_than[0]|less_than[100]',
        'received_date' => 'required|valid_date'
    ];

    protected $validationMessages = [
        'batch_number' => [
            'required' => 'Batch number is required',
            'is_unique' => 'This batch number already exists'
        ],
        'supplier_id' => [
            'required' => 'Supplier is required',
            'integer' => 'Invalid supplier selected'
        ],
        'total_bags' => [
            'required' => 'Total bags is required',
            'greater_than' => 'Total bags must be greater than 0'
        ],
        'average_moisture' => [
            'required' => 'Average moisture is required',
            'greater_than' => 'Moisture must be greater than 0%',
            'less_than' => 'Moisture must be less than 100%'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['calculateTotalWeight'];
    protected $beforeUpdate = ['calculateTotalWeight'];

    protected function calculateTotalWeight(array $data)
    {
        if (isset($data['data']['total_weight_kg'])) {
            $data['data']['total_weight_mt'] = round($data['data']['total_weight_kg'] / 1000, 3);
        }
        return $data;
    }

    // Custom methods
    public function getBatchWithSupplier($id)
    {
        try {
            // Check if required tables exist first
            if (!$this->db->tableExists('batches')) {
                return null;
            }
            
            $builder = $this->db->table('batches b');
            $builder->select('b.*, s.name as supplier_name, s.contact_person, s.phone, po.po_number, po.grain_type as po_grain_type, po.approved_by as po_approved_by');
            $builder->join('suppliers s', 's.id = b.supplier_id', 'left');
            $builder->join('purchase_orders po', 'po.id = b.purchase_order_id', 'left');
            $builder->where('b.id', $id);
            
            $query = $builder->get();
            
            if ($query === false) {
                log_message('error', 'BatchModel::getBatchWithSupplier() - Query failed for ID: ' . $id);
                return null;
            }
            
            return $query->getRowArray();
            
        } catch (\Throwable $e) {
            log_message('error', 'BatchModel::getBatchWithSupplier() error: ' . $e->getMessage());
            return null;
        }
    }

    public function getBatchesWithSuppliers($status = null)
    {
        try {
            // Check if required tables exist first
            if (!$this->db->tableExists('batches')) {
                return [];
            }
            
            $builder = $this->db->table('batches b');
            $builder->select('b.*, s.name as supplier_name, po.po_number');
            $builder->join('suppliers s', 's.id = b.supplier_id', 'left');
            $builder->join('purchase_orders po', 'po.id = b.purchase_order_id', 'left');
            
            if ($status) {
                $builder->where('b.status', $status);
            }
            
            $builder->orderBy('b.created_at', 'DESC');
            
            $query = $builder->get();
            
            if ($query === false) {
                log_message('error', 'BatchModel::getBatchesWithSuppliers() - Query failed');
                return [];
            }
            
            return $query->getResultArray();
            
        } catch (\Throwable $e) {
            log_message('error', 'BatchModel::getBatchesWithSuppliers() error: ' . $e->getMessage());
            return [];
        }
    }

    public function getAvailableBatches()
    {
        return $this->where('status', 'approved')->findAll();
    }

    /**
     * Validate batch against PO constraints
     */
    public function validateBatchAgainstPO($batchData, $poId)
    {
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $po = $purchaseOrderModel->find($poId);
        
        if (!$po) {
            return ['valid' => false, 'message' => 'Purchase order not found'];
        }

        // Allow approved, confirmed, pending, transferring, and empty status (legacy data)
        $allowedStatuses = ['approved', 'confirmed', 'pending', 'transferring', ''];
        if (!in_array($po['status'], $allowedStatuses)) {
            return ['valid' => false, 'message' => 'Purchase order must be approved before creating batches. Current status: ' . $po['status']];
        }

        if ($po['supplier_id'] != $batchData['supplier_id']) {
            return ['valid' => false, 'message' => 'Batch supplier must match purchase order supplier'];
        }

        if ($po['grain_type'] !== $batchData['grain_type']) {
            return ['valid' => false, 'message' => 'Batch grain type must match purchase order grain type'];
        }

        // Check if batch quantity exceeds remaining PO quantity
        if ($batchData['total_weight_mt'] > $po['remaining_quantity_mt']) {
            return ['valid' => false, 'message' => 'Batch quantity (' . $batchData['total_weight_mt'] . ' MT) exceeds remaining PO quantity (' . $po['remaining_quantity_mt'] . ' MT)'];
        }

        return ['valid' => true, 'message' => 'Batch is valid against purchase order'];
    }

    /**
     * Check if user can approve batch (must be same as PO approver)
     */
    public function canUserApproveBatch($batchId, $userId)
    {
        $batch = $this->getBatchWithSupplier($batchId);
        
        if (!$batch) {
            return ['can_approve' => false, 'message' => 'Batch not found'];
        }

        if ($batch['status'] !== 'pending') {
            return ['can_approve' => false, 'message' => 'Only pending batches can be approved'];
        }

        if ($batch['po_approved_by'] != $userId) {
            return ['can_approve' => false, 'message' => 'Only the user who approved the original purchase order can approve this batch'];
        }

        return ['can_approve' => true, 'message' => 'User can approve this batch'];
    }

    /**
     * Get batches pending approval by specific user
     */
    public function getBatchesPendingApprovalByUser($userId)
    {
        try {
            // Check if required tables exist first
            if (!$this->db->tableExists('batches')) {
                return [];
            }
            
            $builder = $this->db->table('batches b');
            $builder->select('b.*, s.name as supplier_name, po.po_number');
            $builder->join('suppliers s', 's.id = b.supplier_id', 'left');
            $builder->join('purchase_orders po', 'po.id = b.purchase_order_id', 'left');
            $builder->where('b.status', 'pending');
            $builder->where('po.approved_by', $userId);
            $builder->orderBy('b.created_at', 'ASC');
            
            $query = $builder->get();
            
            if ($query === false) {
                log_message('error', 'BatchModel::getBatchesPendingApprovalByUser() - Query failed for user ID: ' . $userId);
                return [];
            }
            
            return $query->getResultArray();
            
        } catch (\Throwable $e) {
            log_message('error', 'BatchModel::getBatchesPendingApprovalByUser() error: ' . $e->getMessage());
            return [];
        }
    }

    public function generateBatchNumber()
    {
        $prefix = 'B' . date('Y');
        
        try {
            // Check if table exists first
            if (!$this->db->tableExists('batches')) {
                return $prefix . '0001';
            }
            
            // Use query builder directly to handle errors better
            $builder = $this->db->table('batches');
            $query = $builder->like('batch_number', $prefix)
                           ->orderBy('id', 'DESC')
                           ->limit(1)
                           ->get();
            
            if ($query === false) {
                return $prefix . '0001';
            }
            
            $lastBatch = $query->getRowArray();
            
            if ($lastBatch) {
                $lastNumber = intval(substr($lastBatch['batch_number'], -4));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
        } catch (\Throwable $e) {
            log_message('error', 'BatchModel::generateBatchNumber() error: ' . $e->getMessage());
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getBatchStats()
    {
        // Use fresh queries for each count to avoid query builder state issues
        $stats = [
            'total_batches' => $this->countAll(),
            'pending_batches' => $this->where('status', 'pending')->countAllResults(),
            'approved_batches' => $this->where('status', 'approved')->countAllResults(),
            'dispatched_batches' => $this->where('status', 'dispatched')->countAllResults(),
            'delivered_batches' => $this->where('status', 'delivered')->countAllResults(),
        ];

        // Get total weight using fresh query
        $builder = $this->db->table('batches');
        $builder->selectSum('total_weight_mt', 'total_weight');
        $result = $builder->get()->getRowArray();
        $stats['total_weight_mt'] = $result['total_weight'] ?? 0;

        return $stats;
    }

    /**
     * Get recent batches for dashboard
     */
    public function getRecentBatches($limit = 5)
    {
        try {
            // Check if table exists first
            if (!$this->db->tableExists('batches')) {
                return [];
            }
            
            $builder = $this->db->table('batches b');
            $query = $builder->select('b.*, s.name as supplier_name')
                           ->join('suppliers s', 's.id = b.supplier_id', 'left')
                           ->orderBy('b.created_at', 'DESC')
                           ->limit($limit)
                           ->get();
            
            if ($query === false) {
                return [];
            }
            
            return $query->getResultArray();
            
        } catch (\Throwable $e) {
            log_message('error', 'BatchModel::getRecentBatches() error: ' . $e->getMessage());
            return [];
        }
    }
}
