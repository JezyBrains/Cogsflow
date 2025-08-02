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
        'batch_number', 'supplier_id', 'grain_type', 'total_bags', 'total_weight_kg',
        'total_weight_mt', 'average_moisture', 'quality_grade', 'status', 'notes', 'received_date'
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
        $builder = $this->db->table('batches b');
        $builder->select('b.*, s.name as supplier_name, s.contact_person, s.phone');
        $builder->join('suppliers s', 's.id = b.supplier_id');
        $builder->where('b.id', $id);
        
        return $builder->get()->getRowArray();
    }

    public function getBatchesWithSuppliers($status = null)
    {
        $builder = $this->db->table('batches b');
        $builder->select('b.*, s.name as supplier_name');
        $builder->join('suppliers s', 's.id = b.supplier_id');
        
        if ($status) {
            $builder->where('b.status', $status);
        }
        
        $builder->orderBy('b.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getAvailableBatches()
    {
        return $this->where('status', 'approved')->findAll();
    }

    public function generateBatchNumber()
    {
        $prefix = 'B' . date('Y');
        $lastBatch = $this->like('batch_number', $prefix)
                         ->orderBy('id', 'DESC')
                         ->first();
        
        if ($lastBatch) {
            $lastNumber = intval(substr($lastBatch['batch_number'], -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getBatchStats()
    {
        $stats = [
            'total_batches' => $this->countAll(),
            'pending_batches' => $this->where('status', 'pending')->countAllResults(false),
            'approved_batches' => $this->where('status', 'approved')->countAllResults(false),
            'dispatched_batches' => $this->where('status', 'dispatched')->countAllResults(false),
            'delivered_batches' => $this->where('status', 'delivered')->countAllResults(false),
        ];

        // Get total weight
        $builder = $this->db->table('batches');
        $builder->selectSum('total_weight_mt', 'total_weight');
        $result = $builder->get()->getRowArray();
        $stats['total_weight_mt'] = $result['total_weight'] ?? 0;

        return $stats;
    }
}
