<?php

namespace App\Controllers;

use App\Models\DispatchModel;
use App\Models\BatchModel;

class DispatchController extends BaseController
{
    protected $dispatchModel;
    protected $batchModel;

    public function __construct()
    {
        $this->dispatchModel = new DispatchModel();
        $this->batchModel = new BatchModel();
    }

    /**
     * Display list of all dispatches
     * 
     * @return string
     */
    public function index()
    {
        $data = [
            'dispatches' => $this->dispatchModel->getDispatchesWithBatchInfo(),
            'stats' => $this->dispatchModel->getDispatchStats()
        ];
        
        return view('dispatches/index', $data);
    }
    
    /**
     * Display form to create a new dispatch
     * 
     * @return string
     */
    public function new()
    {
        $data = [
            'available_batches' => $this->dispatchModel->getAvailableBatches()
        ];
        
        return view('dispatches/create', $data);
    }
    
    /**
     * Process the dispatch creation form
     * Registers transporter & batch dispatch details
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function create()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'batch_id' => 'required|integer',
            'vehicle_number' => 'required|min_length[3]|max_length[20]',
            'trailer_number' => 'required|min_length[3]|max_length[20]',
            'driver_name' => 'required|min_length[3]|max_length[255]',
            'driver_phone' => 'permit_empty|regex_match[/^\\+255\\d{3}\\s\\d{3}\\s\\d{3}$/]',
            'dispatcher_name' => 'required|min_length[3]|max_length[255]',
            'estimated_arrival' => 'required|valid_date',
            'destination' => 'required|min_length[3]|max_length[255]',
            'notes' => 'permit_empty|max_length[500]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Check if batch is still available
            $batch = $this->batchModel->find($this->request->getPost('batch_id'));
            if (!$batch || $batch['status'] !== 'approved') {
                throw new \Exception('Selected batch is not available for dispatch');
            }
            
            // Check if batch is already dispatched
            $existingDispatch = $this->dispatchModel->where('batch_id', $batch['id'])
                                                   ->where('status !=', 'cancelled')
                                                   ->first();
            if ($existingDispatch) {
                throw new \Exception('This batch has already been dispatched');
            }
            
            // Generate dispatch number
            $dispatchNumber = 'DSP-' . date('Y') . '-' . str_pad($this->dispatchModel->countAll() + 1, 4, '0', STR_PAD_LEFT);
            
            $dispatchData = [
                'dispatch_number' => $dispatchNumber,
                'batch_id' => $this->request->getPost('batch_id'),
                'vehicle_number' => strtoupper($this->request->getPost('vehicle_number')),
                'trailer_number' => $this->request->getPost('trailer_number') ? strtoupper($this->request->getPost('trailer_number')) : null,
                'driver_name' => $this->request->getPost('driver_name'),
                'driver_phone' => $this->request->getPost('driver_phone'),
                'dispatcher_name' => $this->request->getPost('dispatcher_name'),
                'destination' => $this->request->getPost('destination'),
                'estimated_arrival' => $this->request->getPost('estimated_arrival'),
                'quantity_mt' => $batch['total_weight_mt'],
                'dispatch_date' => date('Y-m-d H:i:s'),
                'status' => 'pending',
                'notes' => $this->request->getPost('notes')
            ];

            // Append Driver ID Number to notes if provided (temporary until DB migration adds dedicated field)
            $driverId = trim((string)$this->request->getPost('driver_id_number'));
            if (!empty($driverId)) {
                $existingNotes = (string)($dispatchData['notes'] ?? '');
                $prefix = $existingNotes ? "\n" : '';
                $dispatchData['notes'] = $existingNotes . $prefix . 'Driver ID: ' . $driverId;
            }
            
            $dispatchId = $this->dispatchModel->insert($dispatchData);
            
            if (!$dispatchId) {
                throw new \Exception('Failed to create dispatch record');
            }
            
            // Update batch status to dispatched
            $this->batchModel->update($batch['id'], ['status' => 'dispatched']);
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }
            
            // Send notification about new dispatch
            helper('notification');
            sendDispatchNotification(
                $dispatchId,
                $dispatchData['dispatch_number'],
                'dispatch_created',
                ['batch_number' => $batch['batch_number'], 'vehicle_number' => $dispatchData['vehicle_number']]
            );
        
            session()->setFlashdata('success', 'Dispatch created successfully for batch ' . $batch['batch_number'] . '. Dispatch #: ' . $dispatchData['dispatch_number'] . ', Vehicle: ' . $dispatchData['vehicle_number']);
            return redirect()->to('/dispatches');
            
        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Failed to create dispatch: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * View dispatch details
     * 
     * @param int $id The dispatch ID
     * @return string
     */
    public function view($id)
    {
        $dispatch = $this->dispatchModel->getDispatchWithBatchInfo($id);
        
        if (!$dispatch) {
            session()->setFlashdata('error', 'Dispatch not found');
            return redirect()->to('/dispatches');
        }
        
        $data = ['dispatch' => $dispatch];
        return view('dispatches/view', $data);
    }
    
    /**
     * Update dispatch status
     * 
     * @param int $id The dispatch ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function updateStatus($id)
    {
        $dispatch = $this->dispatchModel->find($id);
        
        if (!$dispatch) {
            session()->setFlashdata('error', 'Dispatch not found');
            return redirect()->to('/dispatches');
        }
        
        $newStatus = $this->request->getPost('status');
        $validStatuses = ['pending', 'in_transit', 'arrived', 'delivered', 'cancelled'];
        
        if (!in_array($newStatus, $validStatuses)) {
            session()->setFlashdata('error', 'Invalid status provided');
            return redirect()->back();
        }
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            $updateData = ['status' => $newStatus];
            
            // Add arrival time for arrived status
            if ($newStatus === 'arrived') {
                $updateData['actual_arrival'] = date('Y-m-d H:i:s');
            }
            
            // Update dispatch status
            $this->dispatchModel->update($id, $updateData);
            
            // If cancelled, update batch status back to approved
            if ($newStatus === 'cancelled') {
                $this->batchModel->update($dispatch['batch_id'], ['status' => 'approved']);
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }
            
            $statusMessages = [
                'pending' => 'Dispatch status updated to pending',
                'in_transit' => 'Dispatch marked as in transit',
                'arrived' => 'Dispatch has arrived and is awaiting inspection',
                'delivered' => 'Dispatch marked as delivered',
                'cancelled' => 'Dispatch cancelled and batch returned to available pool'
            ];
            
            // Send notification about status change
            helper('notification');
            sendDispatchNotification(
                $id,
                $dispatch['dispatch_number'],
                'dispatch_status_changed',
                ['old_status' => $dispatch['status'], 'new_status' => $newStatus]
            );
        
            session()->setFlashdata('success', $statusMessages[$newStatus]);
            return redirect()->to('/dispatches');
            
        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Failed to update dispatch status: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Mark dispatch as arrived (for drivers/dispatchers)
     */
    public function markAsArrived($id)
    {
        try {
            $dispatch = $this->dispatchModel->find($id);
            
            if (!$dispatch) {
                return redirect()->to('/dispatches')->with('error', 'Dispatch not found');
            }

            if ($dispatch['status'] !== 'in_transit') {
                return redirect()->back()->with('error', 'Only in-transit dispatches can be marked as arrived');
            }

            $updateData = [
                'status' => 'arrived',
                'actual_arrival' => date('Y-m-d H:i:s')
            ];

            $this->dispatchModel->update($id, $updateData);

            // Send notification to receiving officers
            helper('notification');
            sendDispatchNotification(
                $id,
                $dispatch['dispatch_number'],
                'dispatch_arrived',
                ['arrival_time' => $updateData['actual_arrival']]
            );

            return redirect()->to('/dispatches')->with('success', 'Dispatch marked as arrived. Awaiting receiving officer inspection.');
            
        } catch (\Exception $e) {
            log_message('error', 'Mark Dispatch Arrived Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to mark dispatch as arrived: ' . $e->getMessage());
        }
    }

    /**
     * Perform receiving inspection
     */
    public function performInspection($id)
    {
        try {
            $session = session();
            $userId = $session->get('user_id');
            
            // Check if user can perform inspection
            $canInspect = $this->dispatchModel->canUserInspectDispatch($id, $userId);
            if (!$canInspect['can_inspect']) {
                return redirect()->back()->with('error', $canInspect['message']);
            }

            $dispatch = $this->dispatchModel->find($id);
            
            if (!$dispatch) {
                return redirect()->to('/dispatches')->with('error', 'Dispatch not found');
            }

            // Validate inspection data
            $rules = [
                'actual_bags' => 'required|integer|greater_than[0]',
                'actual_weight_kg' => 'required|decimal|greater_than[0]',
                'inspection_notes' => 'required|min_length[10]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $actualBags = (int)$this->request->getPost('actual_bags');
            $actualWeightKg = (float)$this->request->getPost('actual_weight_kg');
            $inspectionNotes = $this->request->getPost('inspection_notes');
            $actualWeightMt = round($actualWeightKg / 1000, 3);

            // Calculate discrepancies
            $discrepancyAnalysis = $this->dispatchModel->calculateDiscrepancies($id, $actualBags, $actualWeightKg);
            
            $db = \Config\Database::connect();
            $db->transStart();

            // Update dispatch with inspection results
            $updateData = [
                'status' => 'delivered',
                'received_by' => $userId,
                'inspection_date' => date('Y-m-d H:i:s'),
                'actual_bags' => $actualBags,
                'actual_weight_kg' => $actualWeightKg,
                'actual_weight_mt' => $actualWeightMt,
                'discrepancies' => $discrepancyAnalysis['discrepancy_summary'],
                'inspection_notes' => $inspectionNotes
            ];

            $this->dispatchModel->update($id, $updateData);

            // Update batch status to delivered
            $this->batchModel->update($dispatch['batch_id'], ['status' => 'delivered']);

            // Update PO fulfillment progress
            $batch = $this->batchModel->find($dispatch['batch_id']);
            if ($batch && $batch['purchase_order_id']) {
                $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
                $purchaseOrderModel->updateDeliveryProgress($batch['purchase_order_id'], $actualWeightMt);
            }

            // Update inventory with batch-wise traceability
            $this->updateInventoryFromDelivery($dispatch['batch_id'], $actualWeightMt, $discrepancyAnalysis);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            // Send notifications
            helper('notification');
            if ($discrepancyAnalysis['has_discrepancies']) {
                sendSystemNotification(
                    'Delivery Discrepancy Detected',
                    'Dispatch ' . $dispatch['dispatch_number'] . ' has discrepancies: ' . $discrepancyAnalysis['discrepancy_summary'],
                    'delivery_discrepancy',
                    ['dispatch_id' => $id, 'discrepancies' => $discrepancyAnalysis]
                );
            }

            sendDispatchNotification(
                $id,
                $dispatch['dispatch_number'],
                'dispatch_delivered',
                ['actual_weight' => $actualWeightMt, 'has_discrepancies' => $discrepancyAnalysis['has_discrepancies']]
            );

            $message = 'Inspection completed successfully. Dispatch marked as delivered.';
            if ($discrepancyAnalysis['has_discrepancies']) {
                $message .= ' Discrepancies have been logged and flagged for review.';
            }

            return redirect()->to('/dispatches')->with('success', $message);
            
        } catch (\Exception $e) {
            if (isset($db)) {
                $db->transRollback();
            }
            log_message('error', 'Dispatch Inspection Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to complete inspection: ' . $e->getMessage());
        }
    }

    /**
     * Update inventory from delivery with batch-wise traceability
     */
    private function updateInventoryFromDelivery($batchId, $actualWeightMt, $discrepancyAnalysis)
    {
        $batch = $this->batchModel->find($batchId);
        if (!$batch) {
            throw new \Exception('Batch not found for inventory update');
        }

        $inventoryModel = new \App\Models\InventoryModel();
        
        // Check if inventory record exists for this grain type
        $existingInventory = $inventoryModel->where('grain_type', $batch['grain_type'])->first();
        
        if ($existingInventory) {
            // Update existing inventory
            $newQuantity = $existingInventory['quantity_mt'] + $actualWeightMt;
            $inventoryModel->update($existingInventory['id'], [
                'quantity_mt' => $newQuantity,
                'last_updated' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Create new inventory record
            $inventoryModel->insert([
                'grain_type' => $batch['grain_type'],
                'quantity_mt' => $actualWeightMt,
                'last_updated' => date('Y-m-d H:i:s')
            ]);
        }

        // Log inventory adjustment for traceability
        $adjustmentModel = new \App\Models\InventoryAdjustmentModel();
        $adjustmentModel->insert([
            'grain_type' => $batch['grain_type'],
            'adjustment_type' => 'batch_delivery',
            'quantity_change_mt' => $actualWeightMt,
            'reference_id' => $batchId,
            'reference_type' => 'batch',
            'notes' => 'Batch delivery - ' . $batch['batch_number'] . 
                      ($discrepancyAnalysis['has_discrepancies'] ? ' (With discrepancies: ' . $discrepancyAnalysis['discrepancy_summary'] . ')' : ''),
            'created_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get inspection form for arrived dispatch
     */
    public function inspectionForm($id)
    {
        try {
            $session = session();
            $userId = $session->get('user_id');
            
            // Check if user can perform inspection
            $canInspect = $this->dispatchModel->canUserInspectDispatch($id, $userId);
            if (!$canInspect['can_inspect']) {
                return redirect()->back()->with('error', $canInspect['message']);
            }

            $dispatch = $this->dispatchModel->getDispatchWithBatchInfo($id);
            
            if (!$dispatch) {
                return redirect()->to('/dispatches')->with('error', 'Dispatch not found');
            }

            $data = [
                'dispatch' => $dispatch,
                'title' => 'Receiving Inspection - ' . $dispatch['dispatch_number']
            ];
            
            return view('dispatches/inspection', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Dispatch Inspection Form Error: ' . $e->getMessage());
            return redirect()->to('/dispatches')->with('error', 'Failed to load inspection form');
        }
    }
}
