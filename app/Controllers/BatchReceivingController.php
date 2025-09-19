<?php

namespace App\Controllers;

use App\Models\BatchModel;
use App\Models\DispatchModel;
use App\Models\PurchaseOrderModel;
use App\Models\InventoryModel;
use App\Models\InventoryAdjustmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class BatchReceivingController extends BaseController
{
    protected $batchModel;
    protected $dispatchModel;
    protected $purchaseOrderModel;
    protected $inventoryModel;
    protected $inventoryAdjustmentModel;

    public function __construct()
    {
        $this->batchModel = new BatchModel();
        $this->dispatchModel = new DispatchModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->inventoryModel = new InventoryModel();
        $this->inventoryAdjustmentModel = new InventoryAdjustmentModel();
    }

    /**
     * Display batch receiving dashboard
     */
    public function index()
    {
        $currentUser = session()->get('user_id') ?? session()->get('username');
        
        // Get dispatches that have arrived and need inspection
        $arriveddispatches = $this->dispatchModel
            ->select('dispatches.*, batches.batch_number, batches.grain_type, batches.supplier_id,
                     batches.total_bags, batches.total_weight_kg, batches.total_weight_mt, 
                     batches.average_moisture, suppliers.name as supplier_name,
                     purchase_orders.po_number')
            ->join('batches', 'batches.id = dispatches.batch_id')
            ->join('suppliers', 'suppliers.id = batches.supplier_id', 'left')
            ->join('purchase_orders', 'purchase_orders.id = batches.purchase_order_id', 'left')
            ->where('dispatches.status', 'arrived')
            ->where('dispatches.received_by IS NULL')
            ->orderBy('dispatches.actual_arrival', 'ASC')
            ->findAll();

        // Get recently completed inspections by current user
        $recentInspections = $this->dispatchModel
            ->select('dispatches.*, batches.batch_number, batches.grain_type')
            ->join('batches', 'batches.id = dispatches.batch_id')
            ->where('dispatches.received_by', $currentUser)
            ->where('dispatches.status', 'delivered')
            ->orderBy('dispatches.inspection_date', 'DESC')
            ->limit(10)
            ->findAll();

        // Get pending inspections statistics
        $stats = [
            'pending_inspections' => count($arriveddispatches),
            'completed_today' => $this->dispatchModel
                ->where('received_by', $currentUser)
                ->where('DATE(inspection_date)', date('Y-m-d'))
                ->countAllResults(),
            'total_completed' => $this->dispatchModel
                ->where('received_by', $currentUser)
                ->where('status', 'delivered')
                ->countAllResults()
        ];

        return view('batch_receiving/index', [
            'arriveddispatches' => $arriveddispatches ?? [],
            'recentInspections' => $recentInspections ?? [],
            'stats' => $stats,
            'current_user' => $currentUser
        ]);
    }

    /**
     * Show inspection form for a specific dispatch
     */
    public function inspectionForm($dispatchId)
    {
        $currentUser = session()->get('user_id') ?? session()->get('username');
        
        // Get dispatch with batch and PO details
        $dispatch = $this->dispatchModel
            ->select('dispatches.*, batches.*, suppliers.name as supplier_name, purchase_orders.po_number')
            ->join('batches', 'batches.id = dispatches.batch_id')
            ->join('suppliers', 'suppliers.id = batches.supplier_id', 'left')
            ->join('purchase_orders', 'purchase_orders.id = batches.purchase_order_id', 'left')
            ->where('dispatches.id', $dispatchId)
            ->first();

        if (!$dispatch) {
            return redirect()->to('/batch-receiving')->with('error', 'Dispatch not found');
        }

        // Validate dispatch status
        if ($dispatch['status'] !== 'arrived') {
            return redirect()->to('/batch-receiving')->with('error', 'This dispatch is not ready for inspection');
        }

        // Check if already inspected
        if (!empty($dispatch['received_by'])) {
            return redirect()->to('/batch-receiving')->with('error', 'This dispatch has already been inspected');
        }

        // Enforce segregation of duties - receiver cannot be batch creator
        if ($dispatch['created_by'] === $currentUser) {
            return redirect()->to('/batch-receiving')->with('error', 'You cannot inspect a batch you created. Please assign to another warehouse officer.');
        }

        // Get batch bags for detailed comparison
        $db = \Config\Database::connect();
        $batchBags = $db->table('batch_bags')
            ->where('batch_id', $dispatch['batch_id'])
            ->orderBy('bag_number', 'ASC')
            ->get()
            ->getResultArray();

        return view('batch_receiving/inspection_form', [
            'dispatch' => $dispatch,
            'batch_bags' => $batchBags,
            'current_user' => $currentUser
        ]);
    }

    /**
     * Process batch inspection and update inventory
     */
    public function processInspection()
    {
        $currentUser = session()->get('user_id') ?? session()->get('username');
        $dispatchId = $this->request->getPost('dispatch_id');
        
        // Validation rules
        $validationRules = [
            'dispatch_id' => 'required|integer',
            'actual_bags' => 'required|integer|greater_than[0]',
            'actual_weight_kg' => 'required|decimal|greater_than[0]',
            'inspection_notes' => 'permit_empty|max_length[1000]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get dispatch details
        $dispatch = $this->dispatchModel
            ->select('dispatches.*, batches.*')
            ->join('batches', 'batches.id = dispatches.batch_id')
            ->where('dispatches.id', $dispatchId)
            ->first();

        if (!$dispatch) {
            return redirect()->to('/batch-receiving')->with('error', 'Dispatch not found');
        }

        // Enforce segregation of duties
        if ($dispatch['created_by'] === $currentUser) {
            return redirect()->to('/batch-receiving')->with('error', 'Segregation of duties violation: You cannot inspect your own batch');
        }

        // Validate dispatch status
        if ($dispatch['status'] !== 'arrived' || !empty($dispatch['received_by'])) {
            return redirect()->to('/batch-receiving')->with('error', 'Invalid dispatch status for inspection');
        }

        $actualBags = (int)$this->request->getPost('actual_bags');
        $actualWeightKg = (float)$this->request->getPost('actual_weight_kg');
        $actualWeightMt = $actualWeightKg / 1000;
        $inspectionNotes = $this->request->getPost('inspection_notes') ?? '';

        // Calculate discrepancies
        $discrepancies = $this->calculateDiscrepancies($dispatch, $actualBags, $actualWeightKg);
        
        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update dispatch with inspection data
            $inspectionData = [
                'received_by' => $currentUser,
                'inspection_date' => date('Y-m-d H:i:s'),
                'actual_bags' => $actualBags,
                'actual_weight_kg' => $actualWeightKg,
                'actual_weight_mt' => $actualWeightMt,
                'discrepancies' => json_encode($discrepancies),
                'inspection_notes' => $inspectionNotes,
                'status' => 'delivered'
            ];

            $this->dispatchModel->update($dispatchId, $inspectionData);

            // Update batch status to delivered
            $this->batchModel->update($dispatch['batch_id'], [
                'status' => 'delivered',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Update PO fulfillment progress
            $this->updatePOFulfillment($dispatch['purchase_order_id'], $actualWeightMt);

            // Update inventory with batch-wise traceability
            $this->updateInventoryFromInspection($dispatch, $actualWeightMt, $discrepancies);

            // Log batch history
            $this->logBatchHistory($dispatch['batch_id'], 'delivered', $currentUser, $discrepancies);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            // Send notifications
            $this->sendInspectionNotifications($dispatch, $discrepancies, $currentUser);

            $message = 'Batch inspection completed successfully';
            if (!empty($discrepancies['has_discrepancies'])) {
                $message .= ' with discrepancies logged';
            }

            return redirect()->to('/batch-receiving')->with('success', $message);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Batch inspection failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Inspection failed: ' . $e->getMessage());
        }
    }

    /**
     * Calculate discrepancies between expected and actual values
     */
    private function calculateDiscrepancies($dispatch, $actualBags, $actualWeightKg)
    {
        $expectedBags = (int)$dispatch['total_bags'];
        $expectedWeightKg = (float)$dispatch['total_weight_kg'];
        
        $bagsDifference = $actualBags - $expectedBags;
        $weightDifference = $actualWeightKg - $expectedWeightKg;
        $weightPercentageDiff = $expectedWeightKg > 0 ? ($weightDifference / $expectedWeightKg) * 100 : 0;

        // Define tolerance thresholds
        $bagsTolerance = 0; // No tolerance for bag count
        $weightTolerancePercent = 2.0; // 2% tolerance for weight

        $hasDiscrepancies = (
            abs($bagsDifference) > $bagsTolerance || 
            abs($weightPercentageDiff) > $weightTolerancePercent
        );

        return [
            'has_discrepancies' => $hasDiscrepancies,
            'bags' => [
                'expected' => $expectedBags,
                'actual' => $actualBags,
                'difference' => $bagsDifference,
                'has_discrepancy' => abs($bagsDifference) > $bagsTolerance
            ],
            'weight_kg' => [
                'expected' => $expectedWeightKg,
                'actual' => $actualWeightKg,
                'difference' => $weightDifference,
                'percentage_diff' => round($weightPercentageDiff, 2),
                'has_discrepancy' => abs($weightPercentageDiff) > $weightTolerancePercent
            ],
            'tolerance_thresholds' => [
                'bags' => $bagsTolerance,
                'weight_percent' => $weightTolerancePercent
            ]
        ];
    }

    /**
     * Update PO fulfillment progress
     */
    private function updatePOFulfillment($purchaseOrderId, $deliveredQuantityMt)
    {
        if (!$purchaseOrderId) return;

        $po = $this->purchaseOrderModel->find($purchaseOrderId);
        if (!$po) return;

        $newDeliveredQuantity = $po['delivered_quantity_mt'] + $deliveredQuantityMt;
        $newRemainingQuantity = max(0, $po['quantity_mt'] - $newDeliveredQuantity);
        
        $status = $newRemainingQuantity <= 0.001 ? 'completed' : 'approved';

        $this->purchaseOrderModel->update($purchaseOrderId, [
            'delivered_quantity_mt' => $newDeliveredQuantity,
            'remaining_quantity_mt' => $newRemainingQuantity,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Update inventory with batch-wise traceability
     */
    private function updateInventoryFromInspection($dispatch, $actualWeightMt, $discrepancies)
    {
        $grainType = $dispatch['grain_type'];
        $batchId = $dispatch['batch_id'];
        $dispatchId = $dispatch['id'];
        $currentUser = session()->get('user_id') ?? session()->get('username');

        // Record inventory adjustment
        $adjustmentData = [
            'grain_type' => $grainType,
            'adjustment_type' => 'Batch Delivery',
            'quantity' => $actualWeightMt,
            'reference' => "Batch #{$dispatch['batch_number']}",
            'batch_id' => $batchId,
            'dispatch_id' => $dispatchId,
            'reason' => "Batch delivery inspection completed",
            'adjusted_by' => $currentUser,
            'adjustment_date' => date('Y-m-d H:i:s'),
            'discrepancy_data' => $discrepancies['has_discrepancies'] ? json_encode($discrepancies) : null
        ];

        $this->inventoryAdjustmentModel->recordAdjustment($adjustmentData);

        // Update inventory last batch reference
        $this->inventoryModel->where('grain_type', $grainType)
            ->set([
                'last_updated_by' => $currentUser,
                'last_batch_id' => $batchId,
                'updated_at' => date('Y-m-d H:i:s')
            ])
            ->update();
    }

    /**
     * Log batch history for audit trail
     */
    private function logBatchHistory($batchId, $action, $user, $discrepancies = null)
    {
        $historyData = [
            'batch_id' => $batchId,
            'action' => $action,
            'performed_by' => $user,
            'performed_at' => date('Y-m-d H:i:s'),
            'details' => $discrepancies ? json_encode($discrepancies) : null
        ];

        // Insert into batch_history table (assuming it exists or will be created)
        $db = \Config\Database::connect();
        $db->table('batch_history')->insert($historyData);
    }

    /**
     * Send notifications for inspection completion
     */
    private function sendInspectionNotifications($dispatch, $discrepancies, $inspector)
    {
        // Notify batch creator about completion
        if (function_exists('send_batch_notification')) {
            $message = "Batch #{$dispatch['batch_number']} has been inspected and delivered";
            if ($discrepancies['has_discrepancies']) {
                $message .= " with discrepancies";
            }
            
            send_batch_notification($dispatch['created_by'], 'batch_delivered', $message, [
                'batch_id' => $dispatch['batch_id'],
                'batch_number' => $dispatch['batch_number'],
                'inspector' => $inspector,
                'has_discrepancies' => $discrepancies['has_discrepancies']
            ]);
        }

        // Notify management if there are significant discrepancies
        if ($discrepancies['has_discrepancies']) {
            if (function_exists('send_system_notification')) {
                send_system_notification('admin', 'discrepancy_alert', 
                    "Discrepancies found in batch #{$dispatch['batch_number']}", [
                        'batch_id' => $dispatch['batch_id'],
                        'dispatch_id' => $dispatch['id'],
                        'discrepancies' => $discrepancies
                    ]);
            }
        }
    }

    /**
     * Get inspection history for a batch
     */
    public function batchHistory($batchId)
    {
        $batch = $this->batchModel->find($batchId);
        if (!$batch) {
            return $this->response->setJSON(['error' => 'Batch not found'])->setStatusCode(404);
        }

        // Get all dispatches for this batch
        $dispatches = $this->dispatchModel
            ->where('batch_id', $batchId)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        // Get batch history if table exists
        $history = [];
        $db = \Config\Database::connect();
        if ($db->tableExists('batch_history')) {
            $history = $db->table('batch_history')
                ->where('batch_id', $batchId)
                ->orderBy('performed_at', 'ASC')
                ->get()
                ->getResultArray();
        }

        return $this->response->setJSON([
            'batch' => $batch,
            'dispatches' => $dispatches,
            'history' => $history
        ]);
    }

    /**
     * Export inspection report
     */
    public function exportInspectionReport($dispatchId)
    {
        $dispatch = $this->dispatchModel
            ->select('dispatches.*, batches.*, purchase_orders.po_number')
            ->join('batches', 'batches.id = dispatches.batch_id')
            ->join('purchase_orders', 'purchase_orders.id = batches.purchase_order_id', 'left')
            ->where('dispatches.id', $dispatchId)
            ->first();

        if (!$dispatch || $dispatch['status'] !== 'delivered') {
            return redirect()->back()->with('error', 'Invalid dispatch for report export');
        }

        // Generate PDF report (implementation depends on your PDF library)
        // This is a placeholder for the actual PDF generation
        return view('batch_receiving/inspection_report', ['dispatch' => $dispatch]);
    }
}
