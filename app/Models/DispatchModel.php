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
        'dispatcher_name',
        'destination',
        'estimated_arrival',
        'actual_departure',
        'actual_arrival',
        'status',
        'notes',
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
        'driver_phone' => 'permit_empty|min_length[10]|max_length[20]',
        'dispatcher_name' => 'required|min_length[3]|max_length[255]',
        'destination' => 'required|min_length[3]|max_length[255]',
        'estimated_arrival' => 'required|valid_date',
        'status' => 'required|in_list[pending,in_transit,delivered,cancelled]',
        'notes' => 'permit_empty|max_length[500]'
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
            'in_list' => 'Status must be one of: pending, in_transit, delivered, cancelled'
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
        return $this->select('dispatches.*, batches.batch_number, batches.grain_type, batches.total_weight_mt, suppliers.name as supplier_name')
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
        return $this->select('dispatches.*, batches.batch_number, batches.grain_type, batches.total_weight_mt, batches.total_bags, suppliers.name as supplier_name, suppliers.contact_person, suppliers.phone')
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
        return $batchModel->select('batches.id, batches.batch_number, batches.grain_type, batches.total_weight_mt, suppliers.name as supplier_name')
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
        $stats = [
            'total_dispatches' => $this->countAll(),
            'pending_dispatches' => $this->where('dispatches.status', 'pending')->countAllResults(false),
            'in_transit_dispatches' => $this->where('dispatches.status', 'in_transit')->countAllResults(false),
            'delivered_dispatches' => $this->where('dispatches.status', 'delivered')->countAllResults(false),
            'cancelled_dispatches' => $this->where('dispatches.status', 'cancelled')->countAllResults(false)
        ];

        // Calculate total weight dispatched
        $totalWeight = $this->select('SUM(batches.total_weight_mt) as total_weight')
                           ->join('batches', 'batches.id = dispatches.batch_id', 'left')
                           ->where('dispatches.status !=', 'cancelled')
                           ->first();
        
        $stats['total_weight_dispatched'] = $totalWeight['total_weight'] ?? 0;

        return $stats;
    }
}
