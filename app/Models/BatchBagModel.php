<?php

namespace App\Models;

use CodeIgniter\Model;

class BatchBagModel extends Model
{
    protected $table = 'batch_bags';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'batch_id', 'bag_number', 'weight_kg', 'moisture_percentage'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'batch_id' => 'required|integer',
        'bag_number' => 'required|integer|greater_than[0]',
        'weight_kg' => 'required|decimal|greater_than[0]',
        'moisture_percentage' => 'required|decimal|greater_than[0]|less_than[100]'
    ];

    protected $validationMessages = [
        'bag_number' => [
            'required' => 'Bag number is required',
            'greater_than' => 'Bag number must be greater than 0'
        ],
        'weight_kg' => [
            'required' => 'Weight is required',
            'greater_than' => 'Weight must be greater than 0'
        ],
        'moisture_percentage' => [
            'required' => 'Moisture percentage is required',
            'greater_than' => 'Moisture must be greater than 0%',
            'less_than' => 'Moisture must be less than 100%'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom methods
    public function getBagsByBatch($batchId)
    {
        return $this->where('batch_id', $batchId)
                   ->orderBy('bag_number', 'ASC')
                   ->findAll();
    }

    public function calculateBatchTotals($batchId)
    {
        $builder = $this->db->table('batch_bags');
        $builder->select('COUNT(*) as total_bags, SUM(weight_kg) as total_weight, AVG(moisture_percentage) as avg_moisture');
        $builder->where('batch_id', $batchId);
        
        return $builder->get()->getRowArray();
    }

    public function insertBags($batchId, $bags)
    {
        $data = [];
        foreach ($bags as $bag) {
            $data[] = [
                'batch_id' => $batchId,
                'bag_number' => $bag['bag_number'],
                'weight_kg' => $bag['weight_kg'],
                'moisture_percentage' => $bag['moisture_percentage']
            ];
        }
        
        return $this->insertBatch($data);
    }

    public function deleteBagsByBatch($batchId)
    {
        return $this->where('batch_id', $batchId)->delete();
    }
}
