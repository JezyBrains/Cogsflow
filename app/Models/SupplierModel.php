<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'contact_person', 'phone', 'email', 'address', 'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'phone' => 'permit_empty|min_length[10]|max_length[20]',
        'email' => 'permit_empty|valid_email|max_length[255]',
        'status' => 'required|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Supplier name is required',
            'min_length' => 'Supplier name must be at least 3 characters long'
        ],
        'phone' => [
            'min_length' => 'Phone number must be at least 10 characters long'
        ],
        'email' => [
            'valid_email' => 'Please provide a valid email address'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom methods
    public function getActiveSuppliers()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getSupplierWithBatches($id)
    {
        $builder = $this->db->table('suppliers s');
        $builder->select('s.*, COUNT(b.id) as total_batches, SUM(b.total_weight_mt) as total_weight_supplied');
        $builder->join('batches b', 'b.supplier_id = s.id', 'left');
        $builder->where('s.id', $id);
        $builder->groupBy('s.id');
        
        return $builder->get()->getRowArray();
    }

    public function getSupplierStats()
    {
        $builder = $this->db->table('suppliers s');
        $builder->select('s.*, COUNT(b.id) as batch_count, SUM(b.total_weight_mt) as total_supplied_mt');
        $builder->join('batches b', 'b.supplier_id = s.id', 'left');
        $builder->where('s.status', 'active');
        $builder->groupBy('s.id');
        $builder->orderBy('total_supplied_mt', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}
