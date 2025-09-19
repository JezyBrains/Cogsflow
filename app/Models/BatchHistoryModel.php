<?php

namespace App\Models;

use CodeIgniter\Model;

class BatchHistoryModel extends Model
{
    protected $table = 'batch_history';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'batch_id',
        'purchase_order_id',
        'dispatch_id',
        'action',
        'performed_by',
        'performed_at',
        'previous_status',
        'new_status',
        'details',
        'notes',
        'ip_address',
        'user_agent'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null; // History records should not be updated

    protected $validationRules = [
        'batch_id' => 'required|integer',
        'action' => 'required|in_list[created,approved,rejected,dispatched,arrived,inspected,delivered,cancelled]',
        'performed_by' => 'required|max_length[100]',
        'performed_at' => 'required|valid_date'
    ];

    protected $validationMessages = [
        'batch_id' => [
            'required' => 'Batch ID is required',
            'integer' => 'Batch ID must be a valid integer'
        ],
        'action' => [
            'required' => 'Action is required',
            'in_list' => 'Invalid action specified'
        ],
        'performed_by' => [
            'required' => 'Performed by field is required'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Log a batch lifecycle event
     */
    public function logBatchEvent($batchId, $action, $performedBy, $details = null, $notes = null, $dispatchId = null, $purchaseOrderId = null, $previousStatus = null, $newStatus = null)
    {
        $request = \Config\Services::request();
        
        $historyData = [
            'batch_id' => $batchId,
            'purchase_order_id' => $purchaseOrderId,
            'dispatch_id' => $dispatchId,
            'action' => $action,
            'performed_by' => $performedBy,
            'performed_at' => date('Y-m-d H:i:s'),
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'details' => $details ? json_encode($details) : null,
            'notes' => $notes,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString()
        ];

        return $this->insert($historyData);
    }

    /**
     * Get complete history for a batch
     */
    public function getBatchHistory($batchId, $limit = null)
    {
        $builder = $this->where('batch_id', $batchId)
                       ->orderBy('performed_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }

    /**
     * Get history with related data (batch, PO, dispatch info)
     */
    public function getBatchHistoryWithDetails($batchId)
    {
        return $this->select('batch_history.*, batches.batch_number, batches.grain_type, 
                             purchase_orders.po_number, dispatches.vehicle_number')
                   ->join('batches', 'batches.id = batch_history.batch_id')
                   ->join('purchase_orders', 'purchase_orders.id = batch_history.purchase_order_id', 'left')
                   ->join('dispatches', 'dispatches.id = batch_history.dispatch_id', 'left')
                   ->where('batch_history.batch_id', $batchId)
                   ->orderBy('batch_history.performed_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get history by action type
     */
    public function getHistoryByAction($action, $limit = 50)
    {
        return $this->select('batch_history.*, batches.batch_number, batches.grain_type')
                   ->join('batches', 'batches.id = batch_history.batch_id')
                   ->where('batch_history.action', $action)
                   ->orderBy('batch_history.performed_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get history by user
     */
    public function getHistoryByUser($userId, $limit = 50)
    {
        return $this->select('batch_history.*, batches.batch_number, batches.grain_type')
                   ->join('batches', 'batches.id = batch_history.batch_id')
                   ->where('batch_history.performed_by', $userId)
                   ->orderBy('batch_history.performed_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get recent activity across all batches
     */
    public function getRecentActivity($limit = 20)
    {
        return $this->select('batch_history.*, batches.batch_number, batches.grain_type, 
                             purchase_orders.po_number')
                   ->join('batches', 'batches.id = batch_history.batch_id')
                   ->join('purchase_orders', 'purchase_orders.id = batch_history.purchase_order_id', 'left')
                   ->orderBy('batch_history.performed_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get audit trail for a specific time period
     */
    public function getAuditTrail($startDate, $endDate, $batchId = null)
    {
        $builder = $this->select('batch_history.*, batches.batch_number, batches.grain_type, 
                                 purchase_orders.po_number, dispatches.vehicle_number')
                       ->join('batches', 'batches.id = batch_history.batch_id')
                       ->join('purchase_orders', 'purchase_orders.id = batch_history.purchase_order_id', 'left')
                       ->join('dispatches', 'dispatches.id = batch_history.dispatch_id', 'left')
                       ->where('batch_history.performed_at >=', $startDate)
                       ->where('batch_history.performed_at <=', $endDate);
        
        if ($batchId) {
            $builder->where('batch_history.batch_id', $batchId);
        }
        
        return $builder->orderBy('batch_history.performed_at', 'DESC')->findAll();
    }

    /**
     * Get statistics for batch lifecycle events
     */
    public function getBatchLifecycleStats($period = '30 days')
    {
        $startDate = date('Y-m-d', strtotime("-{$period}"));
        
        $stats = [];
        $actions = ['created', 'approved', 'rejected', 'dispatched', 'arrived', 'inspected', 'delivered', 'cancelled'];
        
        foreach ($actions as $action) {
            $stats[$action] = $this->where('action', $action)
                                  ->where('performed_at >=', $startDate)
                                  ->countAllResults();
        }
        
        // Get completion rate (delivered / created)
        $stats['completion_rate'] = $stats['created'] > 0 ? 
            round(($stats['delivered'] / $stats['created']) * 100, 2) : 0;
        
        // Get approval rate (approved / created)
        $stats['approval_rate'] = $stats['created'] > 0 ? 
            round(($stats['approved'] / $stats['created']) * 100, 2) : 0;
        
        return $stats;
    }

    /**
     * Get discrepancy summary from inspection history
     */
    public function getDiscrepancySummary($period = '30 days')
    {
        $startDate = date('Y-m-d', strtotime("-{$period}"));
        
        $inspections = $this->select('details')
                           ->where('action', 'inspected')
                           ->where('performed_at >=', $startDate)
                           ->findAll();
        
        $totalInspections = count($inspections);
        $inspectionsWithDiscrepancies = 0;
        $totalBagDiscrepancies = 0;
        $totalWeightDiscrepancies = 0;
        
        foreach ($inspections as $inspection) {
            if ($inspection['details']) {
                $details = json_decode($inspection['details'], true);
                if (isset($details['has_discrepancies']) && $details['has_discrepancies']) {
                    $inspectionsWithDiscrepancies++;
                    
                    if (isset($details['bags']['has_discrepancy']) && $details['bags']['has_discrepancy']) {
                        $totalBagDiscrepancies++;
                    }
                    
                    if (isset($details['weight_kg']['has_discrepancy']) && $details['weight_kg']['has_discrepancy']) {
                        $totalWeightDiscrepancies++;
                    }
                }
            }
        }
        
        return [
            'total_inspections' => $totalInspections,
            'inspections_with_discrepancies' => $inspectionsWithDiscrepancies,
            'discrepancy_rate' => $totalInspections > 0 ? 
                round(($inspectionsWithDiscrepancies / $totalInspections) * 100, 2) : 0,
            'bag_discrepancies' => $totalBagDiscrepancies,
            'weight_discrepancies' => $totalWeightDiscrepancies
        ];
    }

    /**
     * Get workflow efficiency metrics
     */
    public function getWorkflowEfficiencyMetrics($period = '30 days')
    {
        $startDate = date('Y-m-d', strtotime("-{$period}"));
        
        // Get average time between key workflow stages
        $sql = "
            SELECT 
                AVG(TIMESTAMPDIFF(HOUR, created.performed_at, approved.performed_at)) as avg_approval_time_hours,
                AVG(TIMESTAMPDIFF(HOUR, dispatched.performed_at, delivered.performed_at)) as avg_delivery_time_hours,
                AVG(TIMESTAMPDIFF(HOUR, arrived.performed_at, inspected.performed_at)) as avg_inspection_time_hours
            FROM batch_history created
            LEFT JOIN batch_history approved ON created.batch_id = approved.batch_id AND approved.action = 'approved'
            LEFT JOIN batch_history dispatched ON created.batch_id = dispatched.batch_id AND dispatched.action = 'dispatched'
            LEFT JOIN batch_history delivered ON created.batch_id = delivered.batch_id AND delivered.action = 'delivered'
            LEFT JOIN batch_history arrived ON created.batch_id = arrived.batch_id AND arrived.action = 'arrived'
            LEFT JOIN batch_history inspected ON created.batch_id = inspected.batch_id AND inspected.action = 'inspected'
            WHERE created.action = 'created' AND created.performed_at >= ?
        ";
        
        $result = $this->db->query($sql, [$startDate])->getRowArray();
        
        return [
            'avg_approval_time_hours' => round($result['avg_approval_time_hours'] ?? 0, 2),
            'avg_delivery_time_hours' => round($result['avg_delivery_time_hours'] ?? 0, 2),
            'avg_inspection_time_hours' => round($result['avg_inspection_time_hours'] ?? 0, 2)
        ];
    }
}
