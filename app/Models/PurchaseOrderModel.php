<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table = 'purchase_orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'po_number',
        'supplier_id',
        'order_date',
        'expected_delivery_date',
        'grain_type',
        'quantity_mt',
        'unit_price',
        'total_amount',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'delivered_quantity_mt',
        'remaining_quantity_mt'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'po_number' => 'required|max_length[50]|is_unique[purchase_orders.po_number]',
        'supplier_id' => 'required|integer',
        'order_date' => 'required|valid_date',
        'expected_delivery_date' => 'required|valid_date',
        'grain_type' => 'required|max_length[100]',
        'quantity_mt' => 'required|decimal|greater_than[0]',
        'unit_price' => 'required|decimal|greater_than[0]',
        'total_amount' => 'required|decimal|greater_than[0]',
        'status' => 'permit_empty|in_list[pending,confirmed,approved,transferring,completed,delivered,cancelled,rejected]'
    ];

    protected $validationMessages = [];
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
     * Get purchase orders with supplier information
     */
    public function getPurchaseOrdersWithSuppliers($limit = null)
    {
        try {
            // Check if required tables exist first
            if (!$this->db->tableExists('purchase_orders')) {
                return [];
            }
            
            $builder = $this->db->table($this->table . ' po');
            $builder->select('po.*, s.name as supplier_name, s.contact_person, s.phone');
            $builder->join('suppliers s', 's.id = po.supplier_id', 'left');
            $builder->orderBy('po.order_date', 'DESC');
            
            if ($limit) {
                $builder->limit($limit);
            }
            
            $query = $builder->get();
            
            if ($query === false) {
                log_message('error', 'PurchaseOrderModel::getPurchaseOrdersWithSuppliers() - Query failed');
                return [];
            }
            
            return $query->getResultArray();
            
        } catch (\Throwable $e) {
            log_message('error', 'PurchaseOrderModel::getPurchaseOrdersWithSuppliers() error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get purchase order statistics
     */
    public function getPurchaseOrderStats()
    {
        $stats = [];
        
        // Total orders
        $stats['total_orders'] = $this->countAllResults();
        
        // Total value
        $result = $this->selectSum('total_amount')->first();
        $stats['total_value'] = $result['total_amount'] ?? 0;
        
        // This month's orders
        $stats['this_month_orders'] = $this->where('DATE_FORMAT(order_date, "%Y-%m")', date('Y-m'))
                                          ->countAllResults();
        
        // Status breakdown
        $stats['pending_orders'] = $this->where('status', 'pending')->countAllResults(false);
        $stats['approved_orders'] = $this->where('status', 'approved')->countAllResults(false);
        $stats['completed_orders'] = $this->where('status', 'completed')->countAllResults(false);
        
        return $stats;
    }

    /**
     * Get approved purchase orders available for batch creation
     */
    public function getApprovedPOsForBatch()
    {
        try {
            // Check if required tables exist first
            if (!$this->db->tableExists('purchase_orders')) {
                return [];
            }
            
            $builder = $this->db->table($this->table . ' po');
            $builder->select('po.*, s.name as supplier_name, s.contact_person');
            $builder->join('suppliers s', 's.id = po.supplier_id', 'left');
            $builder->where('po.status', 'approved');
            $builder->where('po.remaining_quantity_mt >', 0);
            $builder->orderBy('po.order_date', 'ASC');
            
            $query = $builder->get();
            
            if ($query === false) {
                log_message('error', 'PurchaseOrderModel::getApprovedPOsForBatch() - Query failed');
                return [];
            }
            
            return $query->getResultArray();
            
        } catch (\Throwable $e) {
            log_message('error', 'PurchaseOrderModel::getApprovedPOsForBatch() error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Update delivery progress for a PO
     */
    public function updateDeliveryProgress($poId, $deliveredQuantity)
    {
        $po = $this->find($poId);
        if (!$po) {
            return false;
        }

        $newDeliveredQuantity = $po['delivered_quantity_mt'] + $deliveredQuantity;
        $newRemainingQuantity = $po['quantity_mt'] - $newDeliveredQuantity;
        
        $updateData = [
            'delivered_quantity_mt' => $newDeliveredQuantity,
            'remaining_quantity_mt' => max(0, $newRemainingQuantity)
        ];

        // Mark as completed if fully delivered
        if ($newRemainingQuantity <= 0) {
            $updateData['status'] = 'completed';
        }

        return $this->update($poId, $updateData);
    }

    /**
     * Get PO fulfillment progress
     */
    public function getPOFulfillmentProgress($poId)
    {
        $po = $this->find($poId);
        if (!$po) {
            return null;
        }

        $deliveredPercentage = ($po['delivered_quantity_mt'] / $po['quantity_mt']) * 100;
        
        return [
            'total_quantity' => $po['quantity_mt'],
            'delivered_quantity' => $po['delivered_quantity_mt'],
            'remaining_quantity' => $po['remaining_quantity_mt'],
            'completion_percentage' => round($deliveredPercentage, 2)
        ];
    }

    /**
     * Get PO fulfillment history with batch and dispatch details
     */
    public function getPOFulfillmentHistory($purchaseOrderId)
    {
        return $this->db->table('batches')
            ->select('batches.id as batch_id, batches.batch_number, batches.total_weight_mt as batch_weight,
                     batches.status as batch_status, batches.created_at as batch_created,
                     batches.approved_at, batches.approved_by,
                     dispatches.id as dispatch_id, dispatches.vehicle_number, dispatches.status as dispatch_status,
                     dispatches.actual_weight_mt, dispatches.discrepancies, dispatches.inspection_date,
                     dispatches.received_by, dispatches.created_at as dispatch_created')
            ->join('dispatches', 'dispatches.batch_id = batches.id', 'left')
            ->where('batches.purchase_order_id', $purchaseOrderId)
            ->orderBy('batches.created_at', 'ASC')
            ->orderBy('dispatches.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get PO fulfillment summary with discrepancy analysis
     */
    public function getPOFulfillmentSummary($purchaseOrderId)
    {
        $po = $this->find($purchaseOrderId);
        if (!$po) return null;

        // Get all batches for this PO
        $batches = $this->db->table('batches')
            ->select('batches.*, 
                     COUNT(dispatches.id) as dispatch_count,
                     SUM(CASE WHEN dispatches.status = "delivered" THEN dispatches.actual_weight_mt ELSE 0 END) as delivered_weight,
                     SUM(CASE WHEN dispatches.discrepancies IS NOT NULL AND JSON_EXTRACT(dispatches.discrepancies, "$.has_discrepancies") = true THEN 1 ELSE 0 END) as discrepancy_count')
            ->join('dispatches', 'dispatches.batch_id = batches.id', 'left')
            ->where('batches.purchase_order_id', $purchaseOrderId)
            ->groupBy('batches.id')
            ->get()
            ->getResultArray();

        $totalBatches = count($batches);
        $approvedBatches = 0;
        $deliveredBatches = 0;
        $totalDiscrepancies = 0;
        $totalDeliveredWeight = 0;

        foreach ($batches as $batch) {
            if ($batch['status'] === 'approved' || $batch['status'] === 'delivered') {
                $approvedBatches++;
            }
            if ($batch['status'] === 'delivered') {
                $deliveredBatches++;
            }
            $totalDiscrepancies += (int)$batch['discrepancy_count'];
            $totalDeliveredWeight += (float)$batch['delivered_weight'];
        }

        return [
            'po' => $po,
            'batches' => $batches,
            'summary' => [
                'total_batches' => $totalBatches,
                'approved_batches' => $approvedBatches,
                'delivered_batches' => $deliveredBatches,
                'pending_batches' => $totalBatches - $deliveredBatches,
                'total_discrepancies' => $totalDiscrepancies,
                'total_delivered_weight' => $totalDeliveredWeight,
                'fulfillment_percentage' => $po['quantity_mt'] > 0 ? 
                    round(($totalDeliveredWeight / $po['quantity_mt']) * 100, 2) : 0,
                'discrepancy_rate' => $totalBatches > 0 ? 
                    round(($totalDiscrepancies / $totalBatches) * 100, 2) : 0
            ]
        ];
    }

    /**
     * Get detailed discrepancy analysis for a PO
     */
    public function getPODiscrepancyAnalysis($purchaseOrderId)
    {
        $dispatches = $this->db->table('dispatches')
            ->select('dispatches.*, batches.batch_number, batches.total_weight_kg as expected_weight')
            ->join('batches', 'batches.id = dispatches.batch_id')
            ->where('batches.purchase_order_id', $purchaseOrderId)
            ->where('dispatches.status', 'delivered')
            ->where('dispatches.discrepancies IS NOT NULL')
            ->get()
            ->getResultArray();

        $discrepancies = [];
        $totalWeightVariance = 0;
        $totalBagVariance = 0;

        foreach ($dispatches as $dispatch) {
            $discrepancyData = json_decode($dispatch['discrepancies'], true);
            if ($discrepancyData && isset($discrepancyData['has_discrepancies']) && $discrepancyData['has_discrepancies']) {
                $weightVariance = $discrepancyData['weight_kg']['difference'] ?? 0;
                $bagVariance = $discrepancyData['bags']['difference'] ?? 0;
                
                $totalWeightVariance += $weightVariance;
                $totalBagVariance += $bagVariance;

                $discrepancies[] = [
                    'dispatch_id' => $dispatch['id'],
                    'batch_number' => $dispatch['batch_number'],
                    'inspection_date' => $dispatch['inspection_date'],
                    'weight_variance_kg' => $weightVariance,
                    'bag_variance' => $bagVariance,
                    'weight_percentage' => $discrepancyData['weight_kg']['percentage_diff'] ?? 0,
                    'received_by' => $dispatch['received_by']
                ];
            }
        }

        return [
            'discrepancies' => $discrepancies,
            'summary' => [
                'total_discrepant_dispatches' => count($discrepancies),
                'total_weight_variance_kg' => $totalWeightVariance,
                'total_bag_variance' => $totalBagVariance,
                'avg_weight_variance_kg' => count($discrepancies) > 0 ? 
                    round($totalWeightVariance / count($discrepancies), 2) : 0
            ]
        ];
    }

    /**
     * Check if PO supports multi-dispatch (large batches)
     */
    public function supportsMultiDispatch($purchaseOrderId)
    {
        $po = $this->find($purchaseOrderId);
        if (!$po) return false;

        // Consider POs over 500MT as candidates for multi-dispatch
        return $po['quantity_mt'] >= 500;
    }

    /**
     * Get recommended batch sizes for multi-dispatch
     */
    public function getRecommendedBatchSizes($purchaseOrderId)
    {
        $po = $this->find($purchaseOrderId);
        if (!$po) return [];

        $totalQuantity = $po['quantity_mt'];
        $recommendations = [];

        // Standard truck capacity scenarios
        $truckCapacities = [250, 300, 350, 400]; // MT per truck

        foreach ($truckCapacities as $capacity) {
            if ($totalQuantity > $capacity) {
                $numBatches = ceil($totalQuantity / $capacity);
                $avgBatchSize = $totalQuantity / $numBatches;
                
                $recommendations[] = [
                    'truck_capacity_mt' => $capacity,
                    'recommended_batches' => $numBatches,
                    'avg_batch_size_mt' => round($avgBatchSize, 2),
                    'last_batch_size_mt' => round($totalQuantity - (($numBatches - 1) * $avgBatchSize), 2)
                ];
            }
        }

        return $recommendations;
    }

    /**
     * Update PO status based on batch transfers
     */
    public function updateStatusBasedOnTransfers($purchaseOrderId)
    {
        $po = $this->find($purchaseOrderId);
        if (!$po) {
            return false;
        }

        // Calculate total transferred quantity from batches
        $batchModel = new \App\Models\BatchModel();
        $transferredQuery = $batchModel->db->table('batches');
        $transferredQuery->selectSum('total_weight_mt', 'total_transferred');
        $transferredQuery->where('purchase_order_id', $purchaseOrderId);
        $transferredResult = $transferredQuery->get()->getRowArray();
        $totalTransferred = $transferredResult['total_transferred'] ?? 0;

        $newStatus = $po['status'];
        
        // Determine new status based on transfer progress
        if ($totalTransferred > 0 && $totalTransferred < $po['quantity_mt']) {
            // Some quantity transferred but not all - set to transferring
            $newStatus = 'transferring';
        } elseif ($totalTransferred >= $po['quantity_mt']) {
            // All quantity transferred - set to completed
            $newStatus = 'completed';
        }

        // Update status if it changed
        if ($newStatus !== $po['status']) {
            $updateData = [
                'status' => $newStatus,
                'delivered_quantity_mt' => $totalTransferred,
                'remaining_quantity_mt' => max(0, $po['quantity_mt'] - $totalTransferred)
            ];
            
            return $this->update($purchaseOrderId, $updateData);
        }

        return true;
    }

    /**
     * Track PO completion timeline
     */
    public function getPOCompletionTimeline($purchaseOrderId)
    {
        // Get batch history for timeline
        $timeline = $this->db->table('batch_history')
            ->select('batch_history.*, batches.batch_number')
            ->join('batches', 'batches.id = batch_history.batch_id')
            ->where('batch_history.purchase_order_id', $purchaseOrderId)
            ->orderBy('batch_history.performed_at', 'ASC')
            ->get()
            ->getResultArray();

        // Group by action type for analysis
        $timelineByAction = [];
        foreach ($timeline as $event) {
            $timelineByAction[$event['action']][] = $event;
        }

        return [
            'timeline' => $timeline,
            'by_action' => $timelineByAction,
            'key_dates' => [
                'po_created' => $this->find($purchaseOrderId)['created_at'] ?? null,
                'first_batch_created' => $timelineByAction['created'][0]['performed_at'] ?? null,
                'first_batch_approved' => $timelineByAction['approved'][0]['performed_at'] ?? null,
                'first_dispatch' => $timelineByAction['dispatched'][0]['performed_at'] ?? null,
                'last_delivery' => end($timelineByAction['delivered'] ?? [])['performed_at'] ?? null
            ]
        ];
    }
}
