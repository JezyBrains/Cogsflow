<?php

namespace App\Controllers;

use App\Models\BatchModel;
use App\Models\DispatchModel;
use App\Models\PurchaseOrderModel;
use App\Models\InventoryModel;
use App\Models\InventoryAdjustmentModel;
use App\Models\BagInspectionModel;
use App\Models\InspectionSessionModel;
use App\Models\BatchBagModel;
use CodeIgniter\HTTP\ResponseInterface;

class BatchReceivingController extends BaseController
{
    protected $batchModel;
    protected $dispatchModel;
    protected $purchaseOrderModel;
    protected $inventoryModel;
    protected $inventoryAdjustmentModel;
    protected $bagInspectionModel;
    protected $inspectionSessionModel;
    protected $batchBagModel;

    public function __construct()
    {
        $this->batchModel = new BatchModel();
        $this->dispatchModel = new DispatchModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->inventoryModel = new InventoryModel();
        $this->inventoryAdjustmentModel = new InventoryAdjustmentModel();
        $this->bagInspectionModel = new BagInspectionModel();
        $this->inspectionSessionModel = new InspectionSessionModel();
        
        // BatchBagModel is optional - only if table exists
        try {
            $this->batchBagModel = new BatchBagModel();
        } catch (\Exception $e) {
            log_message('info', 'BatchBagModel not available: ' . $e->getMessage());
            $this->batchBagModel = null;
        }
    }

    /**
     * Display batch receiving dashboard
     */
    public function index()
    {
        $currentUser = session()->get('user_id') ?? session()->get('username');
        
        // Get dispatches that have arrived and need inspection
        // STRICT WORKFLOW: Only show dispatches with status 'arrived' that haven't been inspected yet
        // This enforces the proper workflow: pending → in_transit → arrived → (inspection) → delivered
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
            ->where('dispatches.inspection_date IS NULL')
            ->orderBy('dispatches.actual_arrival', 'ASC')
            ->findAll();

        // Get recently completed inspections by current user
        $recentInspections = $this->dispatchModel
            ->select('dispatches.*, batches.batch_number, batches.grain_type')
            ->join('batches', 'batches.id = dispatches.batch_id')
            ->where('dispatches.received_by', $currentUser)
            ->where('dispatches.status', 'delivered')
            ->where('dispatches.inspection_date IS NOT NULL')  // Only properly inspected
            ->orderBy('dispatches.inspection_date', 'DESC')
            ->limit(10)
            ->findAll();

        // Get pending inspections statistics
        $stats = [
            'pending_inspections' => count($arriveddispatches),
            'completed_today' => $this->dispatchModel
                ->where('received_by', $currentUser)
                ->where('DATE(inspection_date)', date('Y-m-d'))
                ->where('inspection_date IS NOT NULL')  // Only properly inspected
                ->countAllResults(),
            'total_completed' => $this->dispatchModel
                ->where('received_by', $currentUser)
                ->where('status', 'delivered')
                ->where('inspection_date IS NOT NULL')  // Only properly inspected
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
     * Test method to verify basic functionality
     */
    public function testInspection($dispatchId)
    {
        return "Test successful for dispatch ID: $dispatchId. Current user: " . (session()->get('user_id') ?? session()->get('username'));
    }

    /**
     * Show inspection form for a specific dispatch
     */
    public function inspectionForm($dispatchId)
    {
        // Step 1: Basic validation
        if (!$dispatchId || !is_numeric($dispatchId)) {
            return redirect()->to('/batch-receiving')->with('error', 'Invalid dispatch ID provided');
        }

        try {
            $currentUser = session()->get('user_id') ?? session()->get('username');
            log_message('info', "Starting inspection form for dispatch ID: $dispatchId, User: $currentUser");
            
            // Step 2: Get dispatch data
            $dispatch = $this->dispatchModel
                ->select('dispatches.*, batches.batch_number, batches.grain_type, batches.supplier_id,
                         batches.total_bags, batches.total_weight_kg, batches.total_weight_mt, 
                         batches.average_moisture, suppliers.name as supplier_name,
                         purchase_orders.po_number')
                ->join('batches', 'batches.id = dispatches.batch_id')
                ->join('suppliers', 'suppliers.id = batches.supplier_id', 'left')
                ->join('purchase_orders', 'purchase_orders.id = batches.purchase_order_id', 'left')
                ->where('dispatches.id', $dispatchId)
                ->first();

            if (!$dispatch) {
                log_message('error', "Dispatch not found for ID: $dispatchId");
                return redirect()->to('/batch-receiving')->with('error', 'Dispatch not found');
            }

            log_message('info', "Dispatch found: " . json_encode($dispatch));

            // Step 3: Validate dispatch status - STRICT: Only 'arrived' status allowed
            if ($dispatch['status'] !== 'arrived') {
                return redirect()->to('/batch-receiving')->with('error', 'This dispatch is not ready for inspection. Status must be "arrived". Current status: ' . $dispatch['status']);
            }

            // Step 4: Check if already inspected
            if (!empty($dispatch['received_by'])) {
                return redirect()->to('/batch-receiving')->with('error', 'This dispatch has already been inspected');
            }

            // Step 5: Enforce segregation of duties
            if (isset($dispatch['created_by']) && $dispatch['created_by'] === $currentUser) {
                return redirect()->to('/batch-receiving')->with('error', 'You cannot inspect a batch you created. Please assign to another warehouse officer.');
            }

            // Step 6: Check if new inspection tables exist
            try {
                // Try to get or create inspection session
                $session = $this->inspectionSessionModel->getActiveSession($dispatchId, $currentUser);
                if (!$session) {
                    $sessionId = $this->inspectionSessionModel->startSession(
                        $dispatchId,
                        $dispatch['batch_id'],
                        $currentUser,
                        $this->request->getUserAgent()->isMobile() ? 'mobile' : 'desktop'
                    );
                    $session = $this->inspectionSessionModel->find($sessionId);
                }

                // Step 7: Initialize bag inspection records if not exists
                $existingInspections = $this->bagInspectionModel->getInspectionsByDispatch($dispatchId);
                if (empty($existingInspections)) {
                    $this->initializeBagInspections($dispatch);
                }

                // Step 8: Get bag inspections with status
                $bagInspections = $this->bagInspectionModel->getInspectionsByDispatch($dispatchId);
                $inspectionSummary = $this->bagInspectionModel->getInspectionSummary($dispatchId);
            } catch (\Exception $e) {
                // Tables don't exist yet - show error message
                log_message('error', 'Bag inspection tables not found: ' . $e->getMessage());
                return redirect()->to('/batch-receiving')->with('error', 
                    'Bag inspection system not initialized. Please run: php spark migrate');
            }

            // Step 9: Load document data for the widget
            $documentModel = new \App\Models\DocumentModel();
            $documentTypeModel = new \App\Models\DocumentTypeModel();
            
            $documentTypes = $documentTypeModel->getDocumentTypesForStage('receiving_inspection');
            $existingDocuments = $documentModel->getDocumentsByReference('inspection', $dispatchId);
            $requiredDocuments = $documentModel->getRequiredDocumentsForStage('receiving_inspection', $dispatchId, 'inspection');

            // Step 10: Prepare view data
            $viewData = [
                'dispatch' => $dispatch,
                'bag_inspections' => $bagInspections,
                'inspection_summary' => $inspectionSummary,
                'session' => $session,
                'current_user' => $currentUser ?? 'Unknown',
                'document_types' => $documentTypes,
                'existing_documents' => $existingDocuments,
                'required_documents' => $requiredDocuments
            ];

            log_message('info', "Attempting to load view with data: " . json_encode(array_keys($viewData)));

            return view('batch_receiving/inspection_grid', $viewData);

        } catch (\Exception $e) {
            $errorMsg = 'Error in inspectionForm: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine() . ' | Trace: ' . $e->getTraceAsString();
            log_message('error', $errorMsg);
            return redirect()->to('/batch-receiving')->with('error', 'Detailed error: ' . $e->getMessage() . ' at line ' . $e->getLine());
        }
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

        // Validate dispatch status - STRICT: Only 'arrived' status allowed
        if ($dispatch['status'] !== 'arrived' || !empty($dispatch['received_by'])) {
            return redirect()->to('/batch-receiving')->with('error', 'Invalid dispatch status for inspection. Status must be "arrived". Current status: ' . $dispatch['status'] . ', Already inspected: ' . (!empty($dispatch['received_by']) ? 'Yes' : 'No'));
        }

        // DOCUMENT VALIDATION: Check if required documents are uploaded before completing inspection
        $documentModel = new \App\Models\DocumentModel();
        $documentCheck = $documentModel->areRequiredDocumentsUploaded('receiving_inspection', 'inspection', $dispatchId);
        
        if (!$documentCheck['satisfied']) {
            return redirect()->back()->withInput()->with('error', 'Cannot complete inspection: ' . $documentCheck['message'] . '. Please upload all required documents before completing inspection.');
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

        // Get bag inspection data if available
        $bagInspections = [];
        if ($db->tableExists('bag_inspections')) {
            // Get bag inspections for all dispatches of this batch
            $dispatchIds = array_column($dispatches, 'id');
            if (!empty($dispatchIds)) {
                $bagInspections = $db->table('bag_inspections')
                    ->whereIn('dispatch_id', $dispatchIds)
                    ->orderBy('bag_number', 'ASC')
                    ->get()
                    ->getResultArray();
            }
        }

        return $this->response->setJSON([
            'batch' => $batch,
            'dispatches' => $dispatches,
            'history' => $history,
            'bag_inspections' => $bagInspections
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

    /**
     * Get bag details for inspection
     */
    public function getBagDetails()
    {
        $bagId = $this->request->getPost('bag_id');
        $dispatchId = $this->request->getPost('dispatch_id');
        
        if (!$bagId || !$dispatchId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Bag ID and Dispatch ID are required'
            ]);
        }
        
        try {
            // Get bag details from batch_bags table
            $db = \Config\Database::connect();
            $bag = $db->table('batch_bags bb')
                ->select('bb.*, b.batch_number, b.grain_type')
                ->join('batches b', 'b.id = bb.batch_id')
                ->join('dispatches d', 'd.batch_id = b.id')
                ->where('bb.bag_id', $bagId)
                ->where('d.id', $dispatchId)
                ->get()
                ->getRowArray();
            
            if (!$bag) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Bag not found or does not belong to this dispatch'
                ]);
            }
            
            // Check if already inspected
            if ($db->tableExists('bag_inspections')) {
                $existingInspection = $db->table('bag_inspections')
                    ->where('bag_id', $bagId)
                    ->where('dispatch_id', $dispatchId)
                    ->get()
                    ->getRowArray();
                
                if ($existingInspection) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'This bag has already been inspected'
                    ]);
                }
            }
            
            return $this->response->setJSON([
                'success' => true,
                'bag' => $bag
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting bag details: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading bag details'
            ]);
        }
    }
    
    /**
     * Process individual bag inspection
     */
    public function processBagInspection()
    {
        $data = $this->request->getPost();
        
        // Validation rules
        $validationRules = [
            'bag_id' => 'required',
            'dispatch_id' => 'required|integer',
            'expected_weight' => 'required|decimal',
            'actual_weight' => 'required|decimal',
            'condition_status' => 'required|in_list[good,damaged,wet,contaminated,missing]'
        ];
        
        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }
        
        try {
            $db = \Config\Database::connect();
            $currentUser = session()->get('user_id') ?? session()->get('username');
            
            // Get batch_bag_id
            $batchBag = $db->table('batch_bags')
                ->where('bag_id', $data['bag_id'])
                ->get()
                ->getRowArray();
            
            if (!$batchBag) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Bag not found in system'
                ]);
            }
            
            // Calculate differences
            $weightDiff = (float)$data['actual_weight'] - (float)$data['expected_weight'];
            $moistureDiff = ((float)($data['actual_moisture'] ?? 0)) - ((float)($data['expected_moisture'] ?? 0));
            
            // For now, store in a simple inspection log (can be enhanced with proper table)
            $inspectionData = [
                'bag_id' => $data['bag_id'],
                'dispatch_id' => $data['dispatch_id'],
                'expected_weight' => $data['expected_weight'],
                'actual_weight' => $data['actual_weight'],
                'expected_moisture' => $data['expected_moisture'] ?? 0,
                'actual_moisture' => $data['actual_moisture'] ?? 0,
                'weight_difference' => $weightDiff,
                'moisture_difference' => $moistureDiff,
                'condition_status' => $data['condition_status'],
                'inspection_notes' => $data['inspection_notes'] ?? '',
                'inspected_by' => $currentUser,
                'inspection_date' => date('Y-m-d H:i:s')
            ];
            
            // Store in session for now (would be database in production)
            $sessionKey = 'bag_inspections_' . $data['dispatch_id'];
            $existingInspections = session()->get($sessionKey) ?? [];
            $existingInspections[] = $inspectionData;
            session()->set($sessionKey, $existingInspections);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Bag inspection completed successfully',
                'inspection' => $inspectionData
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error processing bag inspection: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error processing inspection'
            ]);
        }
    }
    
    /**
     * Get existing inspections for a dispatch
     */
    public function getInspections()
    {
        $dispatchId = $this->request->getGet('dispatch_id');
        
        if (!$dispatchId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Dispatch ID is required'
            ]);
        }
        
        try {
            // Get from session for now (would be database in production)
            $sessionKey = 'bag_inspections_' . $dispatchId;
            $inspections = session()->get($sessionKey) ?? [];
            
            return $this->response->setJSON([
                'success' => true,
                'inspections' => $inspections
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting inspections: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading inspections'
            ]);
        }
    }
    
    /**
     * Print bag labels for a dispatch
     */
    public function printLabels($dispatchId)
    {
        try {
            // Get dispatch and batch details
            $dispatch = $this->dispatchModel
                ->select('dispatches.*, batches.*, suppliers.name as supplier_name')
                ->join('batches', 'batches.id = dispatches.batch_id')
                ->join('suppliers', 'suppliers.id = batches.supplier_id', 'left')
                ->where('dispatches.id', $dispatchId)
                ->first();
            
            if (!$dispatch) {
                throw new \Exception('Dispatch not found');
            }
            
            // Get bag details
            $db = \Config\Database::connect();
            $bags = $db->table('batch_bags')
                ->where('batch_id', $dispatch['batch_id'])
                ->orderBy('bag_number', 'ASC')
                ->get()
                ->getResultArray();
            
            // Generate bag IDs if not already set
            $qrGenerator = new \App\Libraries\QRCodeGenerator();
            foreach ($bags as &$bag) {
                if (empty($bag['bag_id'])) {
                    $bag['bag_id'] = $qrGenerator->generateBagId($dispatch['batch_number'], $bag['bag_number']);
                    // Update database with generated bag ID
                    $db->table('batch_bags')
                        ->where('id', $bag['id'])
                        ->update(['bag_id' => $bag['bag_id']]);
                }
                $bag['batch_number'] = $dispatch['batch_number'];
                $bag['supplier_name'] = $dispatch['supplier_name'];
                $bag['grain_type'] = $dispatch['grain_type'];
            }
            
            // Generate labels HTML
            $labelsHTML = $qrGenerator->generateBatchLabels($dispatch, $bags);
            
            return $this->response->setContentType('text/html')->setBody($labelsHTML);
            
        } catch (\Exception $e) {
            log_message('error', 'Error generating labels: ' . $e->getMessage());
            return redirect()->to('/batch-receiving')->with('error', 'Error generating labels');
        }
    }
    
    /**
     * Print bag labels directly from batch (not dispatch)
     */
    public function printLabelsFromBatch($batchId)
    {
        try {
            // Get batch details
            $batch = $this->batchModel
                ->select('batches.*, suppliers.name as supplier_name')
                ->join('suppliers', 'suppliers.id = batches.supplier_id', 'left')
                ->where('batches.id', $batchId)
                ->first();
            
            if (!$batch) {
                throw new \Exception('Batch not found');
            }
            
            // Get bag details
            $db = \Config\Database::connect();
            
            // Check what columns exist first
            $fields = $db->getFieldData('batch_bags');
            $availableColumns = [];
            foreach ($fields as $field) {
                $availableColumns[] = $field->name;
            }
            
            // Build select query with only available columns
            $selectColumns = ['id', 'batch_id', 'bag_number', 'weight_kg'];
            
            // Add optional columns if they exist
            if (in_array('moisture_percentage', $availableColumns)) {
                $selectColumns[] = 'moisture_percentage';
            }
            if (in_array('bag_id', $availableColumns)) {
                $selectColumns[] = 'bag_id';
            }
            if (in_array('qr_code', $availableColumns)) {
                $selectColumns[] = 'qr_code';
            }
            if (in_array('quality_grade', $availableColumns)) {
                $selectColumns[] = 'quality_grade';
            }
            if (in_array('notes', $availableColumns)) {
                $selectColumns[] = 'notes';
            }
            if (in_array('loading_date', $availableColumns)) {
                $selectColumns[] = 'loading_date';
            }
            if (in_array('loaded_by', $availableColumns)) {
                $selectColumns[] = 'loaded_by';
            }
            
            $bags = $db->table('batch_bags')
                ->select(implode(', ', $selectColumns))
                ->where('batch_id', $batchId)
                ->orderBy('bag_number', 'ASC')
                ->get()
                ->getResultArray();
            
            // Generate bag IDs if not already set
            $qrGenerator = new \App\Libraries\QRCodeGenerator();
            
            // Check if bag_id column exists (we already have this info)
            $bagIdColumnExists = in_array('bag_id', $availableColumns);
            
            foreach ($bags as &$bag) {
                // Generate bag_id if column exists and is empty, or if column doesn't exist
                if (!$bagIdColumnExists || empty($bag['bag_id'])) {
                    $bag['bag_id'] = $qrGenerator->generateBagId($batch['batch_number'], $bag['bag_number']);
                    
                    // Only update database if bag_id column exists
                    if ($bagIdColumnExists) {
                        try {
                            $db->table('batch_bags')
                                ->where('id', $bag['id'])
                                ->update(['bag_id' => $bag['bag_id']]);
                        } catch (\Exception $e) {
                            log_message('error', 'Failed to update bag_id: ' . $e->getMessage());
                        }
                    }
                }
                
                // Ensure required fields are present with fallbacks
                $bag['batch_number'] = $batch['batch_number'];
                $bag['supplier_name'] = $batch['supplier_name'];
                $bag['grain_type'] = $batch['grain_type'];
                
                // Handle moisture field - CRITICAL FIX
                // Check if moisture_content exists and copy it to moisture_percentage for compatibility
                if (isset($bag['moisture_content']) && $bag['moisture_content'] > 0) {
                    $bag['moisture_percentage'] = $bag['moisture_content'];
                    if (ENVIRONMENT === 'development') {
                        log_message('debug', "Copying moisture_content {$bag['moisture_content']} to moisture_percentage for bag {$bag['id']}");
                    }
                } elseif (!isset($bag['moisture_percentage'])) {
                    $bag['moisture_percentage'] = 0; // Default value
                }
                
                // Ensure weight field exists
                if (!isset($bag['weight_kg'])) {
                    $bag['weight_kg'] = 0; // Default value
                }
                
                // Debug log for this specific bag
                if (ENVIRONMENT === 'development') {
                    log_message('debug', "Bag {$bag['id']} final data: weight_kg={$bag['weight_kg']}, moisture_percentage={$bag['moisture_percentage']}, moisture_content=" . ($bag['moisture_content'] ?? 'not set'));
                }
            }
            
            // Generate labels HTML
            $labelsHTML = $qrGenerator->generateBatchLabels($batch, $bags);
            
            return $this->response->setContentType('text/html')->setBody($labelsHTML);
            
        } catch (\Exception $e) {
            log_message('error', 'Error generating batch labels: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // For debugging, show the actual error in development
            if (ENVIRONMENT === 'development') {
                return $this->response->setStatusCode(500)->setBody(
                    '<h1>Label Generation Error</h1>' .
                    '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>' .
                    '<p><strong>File:</strong> ' . $e->getFile() . ' (Line: ' . $e->getLine() . ')</p>' .
                    '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>'
                );
            }
            
            return redirect()->to('/batches')->with('error', 'Error generating labels: ' . $e->getMessage());
        }
    }
    
    /**
     * Debug labels generation
     */
    public function debugLabels($batchId)
    {
        try {
            $output = "<h1>Debug: Label Generation for Batch ID: $batchId</h1>";
            
            // Test 1: Get batch details
            $output .= "<h2>1. Batch Details</h2>";
            $batch = $this->batchModel
                ->select('batches.*, suppliers.name as supplier_name')
                ->join('suppliers', 'suppliers.id = batches.supplier_id', 'left')
                ->where('batches.id', $batchId)
                ->first();
            
            if (!$batch) {
                $output .= "<p style='color: red;'>❌ Batch not found!</p>";
                return $this->response->setBody($output);
            }
            
            $output .= "<pre>" . print_r($batch, true) . "</pre>";
            
            // Test 2: Get bag details
            $output .= "<h2>2. Bag Details</h2>";
            $db = \Config\Database::connect();
            $bags = $db->table('batch_bags')
                ->where('batch_id', $batchId)
                ->orderBy('bag_number', 'ASC')
                ->get()
                ->getResultArray();
            
            $output .= "<p>Found " . count($bags) . " bags</p>";
            $output .= "<pre>" . print_r($bags, true) . "</pre>";
            
            // Test 3: Check table structure
            $output .= "<h2>3. Table Structure</h2>";
            $fields = $db->getFieldData('batch_bags');
            $output .= "<p>batch_bags columns:</p><ul>";
            $hasRequiredFields = true;
            $requiredFields = ['moisture_percentage', 'weight_kg', 'bag_number'];
            
            foreach ($fields as $field) {
                $isRequired = in_array($field->name, $requiredFields);
                $status = $isRequired ? '✅' : '';
                $output .= "<li>{$status} {$field->name} ({$field->type})</li>";
            }
            $output .= "</ul>";
            
            // Check for required fields
            $availableColumns = array_column($fields, 'name');
            foreach ($requiredFields as $required) {
                if (!in_array($required, $availableColumns)) {
                    $output .= "<p style='color: red;'>❌ Missing required field: <strong>$required</strong></p>";
                    $hasRequiredFields = false;
                }
            }
            
            if ($hasRequiredFields) {
                $output .= "<p style='color: green;'>✅ All required fields are present</p>";
            }
            
            // Test 4: Test QR Generator
            $output .= "<h2>4. QR Generator Test</h2>";
            $qrGenerator = new \App\Libraries\QRCodeGenerator();
            $testBagId = $qrGenerator->generateBagId($batch['batch_number'], 1);
            $output .= "<p>Generated bag ID: <strong>$testBagId</strong></p>";
            
            // Test 5: Moisture value analysis
            if (!empty($bags)) {
                $output .= "<h2>5. Moisture Value Analysis</h2>";
                
                // Show all bags, not just first one
                foreach ($bags as $index => $bag) {
                    $output .= "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0; background: #f9f9f9;'>";
                    $output .= "<h4>Bag #" . ($index + 1) . " (ID: " . ($bag['id'] ?? 'unknown') . ")</h4>";
                    
                    $output .= "<p><strong>All fields:</strong></p>";
                    $output .= "<ul style='columns: 2;'>";
                    foreach ($bag as $key => $value) {
                        $highlight = (stripos($key, 'moisture') !== false) ? 'style="background: yellow; font-weight: bold;"' : '';
                        $weightHighlight = (stripos($key, 'weight') !== false) ? 'style="background: lightblue; font-weight: bold;"' : '';
                        $style = $highlight ?: $weightHighlight;
                        $output .= "<li $style><strong>$key:</strong> " . (is_null($value) ? 'NULL' : $value) . "</li>";
                    }
                    $output .= "</ul>";
                    
                    // Test moisture detection for this bag
                    $moistureContent = 0;
                    if (isset($bag['moisture_percentage']) && $bag['moisture_percentage'] > 0) {
                        $moistureContent = $bag['moisture_percentage'];
                        $output .= "<p style='color: green;'>✅ Found moisture_percentage: <strong>$moistureContent</strong></p>";
                    } elseif (isset($bag['moisture_content']) && $bag['moisture_content'] > 0) {
                        $moistureContent = $bag['moisture_content'];
                        $output .= "<p style='color: blue;'>✅ Found moisture_content: <strong>$moistureContent</strong></p>";
                    } else {
                        $output .= "<p style='color: red;'>❌ No valid moisture value found</p>";
                        
                        // Check if any field contains moisture data
                        foreach ($bag as $key => $value) {
                            if (stripos($key, 'moisture') !== false) {
                                $output .= "<p style='color: orange;'>⚠️ Found moisture field '$key' with value: '$value'</p>";
                            }
                        }
                    }
                    
                    $output .= "</div>";
                    
                    // Only show first 3 bags to avoid overwhelming output
                    if ($index >= 2) {
                        $output .= "<p><em>... showing first 3 bags only</em></p>";
                        break;
                    }
                }
            }
            
            // Test 6: Test label generation for first bag
            if (!empty($bags)) {
                $output .= "<h2>6. Test Label Generation</h2>";
                $testBag = $bags[0];
                $testBag['bag_id'] = $testBagId;
                $testBag['batch_number'] = $batch['batch_number'];
                $testBag['supplier_name'] = $batch['supplier_name'];
                $testBag['grain_type'] = $batch['grain_type'];
                
                // Debug: Show what moisture value we're passing to the label generator
                $output .= "<div style='background: #fff3cd; padding: 10px; margin: 10px 0; border: 1px solid #ffeaa7;'>";
                $output .= "<h4>🔍 Moisture Debug Before Label Generation:</h4>";
                $output .= "<p><strong>Raw moisture_content:</strong> " . ($testBag['moisture_content'] ?? 'NOT SET') . "</p>";
                $output .= "<p><strong>Raw moisture_percentage:</strong> " . ($testBag['moisture_percentage'] ?? 'NOT SET') . "</p>";
                
                // Test the moisture detection logic manually
                $moistureContent = 0;
                if (isset($testBag['moisture_percentage']) && $testBag['moisture_percentage'] > 0) {
                    $moistureContent = $testBag['moisture_percentage'];
                    $output .= "<p style='color: green;'>✅ Using moisture_percentage: $moistureContent</p>";
                } elseif (isset($testBag['moisture_content']) && $testBag['moisture_content'] > 0) {
                    $moistureContent = $testBag['moisture_content'];
                    $output .= "<p style='color: green;'>✅ Using moisture_content: $moistureContent</p>";
                } else {
                    $output .= "<p style='color: red;'>❌ No valid moisture found, will default to 0</p>";
                }
                $output .= "<p><strong>Final moisture value:</strong> $moistureContent</p>";
                $output .= "</div>";
                
                // Test QR code generation specifically
                $output .= "<h4>🔍 QR Code Generation Test:</h4>";
                $qrData = $testBag['bag_id'] . '|' . $testBag['weight_kg'] . 'kg|' . $moistureContent . '%|' . date('Y-m-d');
                
                $qrUrl1 = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrData);
                $qrDataUrl = $qrGenerator->generateQRDataURL($qrData, 150);
                
                $output .= "<p><strong>QR Data:</strong> " . htmlspecialchars($qrData) . "</p>";
                $output .= "<p><strong>External QR URL:</strong> <a href='{$qrUrl1}' target='_blank'>Test External QR</a></p>";
                $output .= "<p><strong>Data URL QR:</strong> <img src='{$qrDataUrl}' style='width: 100px; height: 100px; border: 1px solid #000;'></p>";
                
                $labelHTML = $qrGenerator->createBagLabelHTML($testBag);
                $output .= "<p>✅ Label HTML generated successfully</p>";
                $output .= "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0; background: white;'>";
                $output .= $labelHTML;
                $output .= "</div>";
            }
            
            return $this->response->setBody($output);
            
        } catch (\Exception $e) {
            return $this->response->setBody(
                "<h1>Debug Error</h1>" .
                "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>" .
                "<p><strong>File:</strong> " . $e->getFile() . " (Line: " . $e->getLine() . ")</p>" .
                "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>"
            );
        }
    }
    
    /**
     * Generate QR code image server-side to bypass CSP
     */
    public function generateQRCode($data)
    {
        try {
            // Decode the data
            $decodedData = urldecode($data);
            
            // Generate SVG QR code
            $qrGenerator = new \App\Libraries\QRCodeGenerator();
            $svgContent = $qrGenerator->generateSVGQRCode($decodedData, 150);
            
            // Return SVG with proper headers
            return $this->response
                ->setContentType('image/svg+xml')
                ->setBody($svgContent);
                
        } catch (\Exception $e) {
            // Return a simple error image
            $errorSvg = '<svg width="150" height="150" xmlns="http://www.w3.org/2000/svg">
                <rect width="150" height="150" fill="#f0f0f0" stroke="#ccc"/>
                <text x="75" y="75" text-anchor="middle" font-family="Arial" font-size="12">QR Error</text>
            </svg>';
            
            return $this->response
                ->setContentType('image/svg+xml')
                ->setBody($errorSvg);
        }
    }

    /**
     * Initialize bag inspection records from batch bags
     */
    private function initializeBagInspections($dispatch)
    {
        try {
            $bags = [];
            
            // Try to get bags from batch_bags table if it exists
            try {
                if ($this->batchBagModel) {
                    $bags = $this->batchBagModel->where('batch_id', $dispatch['batch_id'])
                                                ->orderBy('bag_number', 'ASC')
                                                ->findAll();
                }
            } catch (\Exception $e) {
                log_message('info', 'batch_bags table not available or error: ' . $e->getMessage());
                $bags = [];
            }

            // Always create placeholder records based on total_bags from dispatch
            $totalBags = (int)$dispatch['total_bags'];
            $avgWeightKg = $totalBags > 0 ? $dispatch['total_weight_kg'] / $totalBags : 50;
            $avgMoisture = $dispatch['average_moisture'] ?? 12.5;

            for ($i = 1; $i <= $totalBags; $i++) {
                $bagId = $dispatch['batch_number'] . '-B' . str_pad($i, 3, '0', STR_PAD_LEFT);
                
                // Check if we have specific bag data
                $bagData = null;
                if (!empty($bags)) {
                    foreach ($bags as $bag) {
                        if ($bag['bag_number'] == $i) {
                            $bagData = $bag;
                            break;
                        }
                    }
                }
                
                $this->bagInspectionModel->insert([
                    'dispatch_id' => $dispatch['id'],
                    'batch_id' => $dispatch['batch_id'],
                    'bag_id' => $bagData['bag_id'] ?? $bagId,
                    'bag_number' => $i,
                    'expected_weight_kg' => $bagData['weight_kg'] ?? round($avgWeightKg, 2),
                    'expected_moisture' => $bagData['moisture_percentage'] ?? $bagData['moisture_content'] ?? round($avgMoisture, 2),
                    'inspection_status' => 'pending',
                    'condition_status' => 'good'
                ]);
            }

            // Update session with expected bag count
            $session = $this->inspectionSessionModel->getActiveSession($dispatch['id']);
            if ($session) {
                $this->inspectionSessionModel->updateProgress($session['id'], [
                    'total_bags_expected' => $totalBags,
                    'expected_total_weight_kg' => $dispatch['total_weight_kg']
                ]);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Failed to initialize bag inspections: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * API: Get bag inspection data
     */
    public function getBagInspectionData()
    {
        $dispatchId = $this->request->getGet('dispatch_id');
        
        if (!$dispatchId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Dispatch ID required'
            ]);
        }

        try {
            $bagInspections = $this->bagInspectionModel->getInspectionsByDispatch($dispatchId);
            $summary = $this->bagInspectionModel->getInspectionSummary($dispatchId);
            $session = $this->inspectionSessionModel->getActiveSession($dispatchId);

            return $this->response->setJSON([
                'success' => true,
                'bag_inspections' => $bagInspections,
                'summary' => $summary,
                'session' => $session
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading inspection data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Record single bag inspection
     */
    public function recordBagInspection()
    {
        $data = $this->request->getJSON(true);
        
        // Validation
        $required = ['dispatch_id', 'bag_id', 'actual_weight_kg', 'condition_status'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Missing required field: $field"
                ]);
            }
        }

        try {
            $currentUser = session()->get('user_id') ?? session()->get('username');
            
            // Get existing inspection record
            $inspection = $this->bagInspectionModel
                ->where('dispatch_id', $data['dispatch_id'])
                ->where('bag_id', $data['bag_id'])
                ->first();

            if (!$inspection) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Bag inspection record not found'
                ]);
            }

            // Update inspection record
            $updateData = [
                'actual_weight_kg' => $data['actual_weight_kg'],
                'actual_moisture' => $data['actual_moisture'] ?? null,
                'condition_status' => $data['condition_status'],
                'inspection_notes' => $data['inspection_notes'] ?? null,
                'inspected_by' => $currentUser,
                'qr_scanned' => $data['qr_scanned'] ?? false,
                'scan_timestamp' => $data['qr_scanned'] ? date('Y-m-d H:i:s') : null,
                'device_info' => $this->request->getUserAgent()->isMobile() ? 'mobile' : 'desktop'
            ];

            $this->bagInspectionModel->recordInspection(array_merge(['id' => $inspection['id']], $updateData));

            // Update session progress
            $session = $this->inspectionSessionModel->getActiveSession($data['dispatch_id'], $currentUser);
            if ($session) {
                $summary = $this->bagInspectionModel->getInspectionSummary($data['dispatch_id']);
                $this->inspectionSessionModel->updateProgress($session['id'], [
                    'total_bags_inspected' => $summary['inspected'],
                    'total_discrepancies' => $summary['with_discrepancies'],
                    'actual_total_weight_kg' => $summary['total_actual_weight']
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Bag inspection recorded successfully',
                'inspection' => $this->bagInspectionModel->find($inspection['id'])
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Failed to record bag inspection: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error recording inspection: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Complete bag-by-bag inspection and update inventory
     */
    public function completeBagInspection()
    {
        $dispatchId = $this->request->getPost('dispatch_id');
        
        if (!$dispatchId) {
            return redirect()->back()->with('error', 'Dispatch ID is required');
        }

        try {
            // Get dispatch details
            $dispatch = $this->dispatchModel->find($dispatchId);
            
            if (!$dispatch) {
                return redirect()->to('/batch-receiving')->with('error', 'Dispatch not found');
            }

            // Get all bag inspections for this dispatch
            $bagInspections = $this->bagInspectionModel
                ->where('dispatch_id', $dispatchId)
                ->findAll();

            if (empty($bagInspections)) {
                return redirect()->back()->with('error', 'No bag inspections found');
            }

            // Check if all bags are inspected
            $totalBags = count($bagInspections);
            $inspectedBags = 0;
            $totalActualWeight = 0;
            $hasDiscrepancies = false;

            foreach ($bagInspections as $bag) {
                if ($bag['inspection_status'] === 'inspected') {
                    $inspectedBags++;
                    $totalActualWeight += $bag['actual_weight_kg'];
                    
                    // Check for discrepancies
                    if ($bag['has_discrepancy']) {
                        $hasDiscrepancies = true;
                    }
                }
            }

            if ($inspectedBags < $totalBags) {
                return redirect()->back()->with('error', "Only $inspectedBags out of $totalBags bags have been inspected. Please complete all inspections.");
            }

            // Start transaction
            $db = \Config\Database::connect();
            $db->transStart();

            // Update dispatch status
            $this->dispatchModel->update($dispatchId, [
                'status' => 'delivered',
                'received_by' => session()->get('user_id') ?? session()->get('username'),
                'inspection_date' => date('Y-m-d H:i:s'),
                'actual_bags' => $totalBags,
                'actual_weight_kg' => $totalActualWeight,
                'actual_weight_mt' => $totalActualWeight / 1000,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Update batch status
            $this->batchModel->update($dispatch['batch_id'], [
                'status' => 'delivered',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Update inventory
            $batch = $this->batchModel->find($dispatch['batch_id']);
            if ($batch) {
                // Check if inventory record exists
                $inventory = $this->inventoryModel
                    ->where('grain_type', $batch['grain_type'])
                    ->first();

                $weightToAdd = $totalActualWeight / 1000; // Convert kg to MT

                if ($inventory) {
                    // Update existing inventory - use current_stock_mt field
                    $currentStock = $inventory['current_stock_mt'] ?? 0;
                    
                    $this->inventoryModel->update($inventory['id'], [
                        'current_stock_mt' => $currentStock + $weightToAdd,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    // Create new inventory record
                    $this->inventoryModel->insert([
                        'grain_type' => $batch['grain_type'],
                        'description' => $batch['grain_type'] . ' from Batch ' . $batch['batch_number'],
                        'current_stock_mt' => $weightToAdd,
                        'minimum_level_mt' => 0,
                        'unit_cost' => 0,
                        'location' => 'Main Warehouse',
                        'status' => 'active',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            // Log inspection completion in history (after transaction completes)
            try {
                $historyModel = new \App\Models\BatchHistoryModel();
                $historyModel->logBatchEvent(
                    $dispatch['batch_id'],
                    'inspected',
                    session()->get('username') ?? 'Inspector',
                    [
                        'total_bags_inspected' => $inspectedBags,
                        'actual_weight_kg' => $totalActualWeight,
                        'actual_weight_mt' => $totalActualWeight / 1000,
                        'has_discrepancies' => $hasDiscrepancies,
                        'good_bags' => $goodBags,
                        'damaged_bags' => $damagedBags,
                        'missing_bags' => $missingBags
                    ],
                    $hasDiscrepancies ? 'Inspection completed with discrepancies' : 'Inspection completed successfully',
                    $dispatchId,
                    $batch['purchase_order_id'] ?? null,
                    'dispatched',
                    'delivered'
                );
            } catch (\Exception $e) {
                // Log error but don't fail the inspection
                log_message('error', 'Failed to log batch history: ' . $e->getMessage());
            }

            $message = 'Inspection completed successfully! ';
            $message .= "$inspectedBags bags inspected. ";
            $message .= "Total weight: " . number_format($totalActualWeight, 2) . " kg. ";
            
            if ($hasDiscrepancies) {
                $message .= "⚠️ Some discrepancies were found and logged.";
            }

            return redirect()->to('/batch-receiving')->with('success', $message);

        } catch (\Exception $e) {
            log_message('error', 'Error completing inspection: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error completing inspection: ' . $e->getMessage());
        }
    }
}
