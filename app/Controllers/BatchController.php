<?php

namespace App\Controllers;

use App\Models\BatchModel;
use App\Models\BatchBagModel;
use App\Models\SupplierModel;
use App\Models\PurchaseOrderModel;

class BatchController extends BaseController
{
    protected $batchModel;
    protected $batchBagModel;
    protected $supplierModel;
    protected $purchaseOrderModel;

    public function __construct()
    {
        $this->batchModel = new BatchModel();
        $this->batchBagModel = new BatchBagModel();
        $this->supplierModel = new SupplierModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
    }

    /**
     * Display list of all batches
     * 
     * @return string
     */
    public function index()
    {
        $data = [
            'title' => 'Batch Management',
            'batches' => $this->batchModel->getBatchesWithSuppliers(),
            'stats' => $this->batchModel->getBatchStats()
        ];
        
        return view('batches/index', $data);
    }
    
    /**
     * Display form to create a new batch
     * 
     * @return string
     */
    public function new()
    {
        try {
            $data = [
                'title' => 'Create New Batch',
                'approved_pos' => $this->purchaseOrderModel->getApprovedPOsForBatch(),
                'suppliers' => $this->supplierModel->where('status', 'active')->findAll(),
                'batch_number' => $this->batchModel->generateBatchNumber()
            ];
            
            return view('batches/create', $data);
        } catch (\Exception $e) {
            log_message('error', 'BatchController::new() error: ' . $e->getMessage());
            
            // Provide fallback data
            $data = [
                'title' => 'Create New Batch',
                'approved_pos' => [],
                'suppliers' => [],
                'batch_number' => 'B' . date('Y') . '0001'
            ];
            
            return view('batches/create', $data);
        }
    }
    
    /**
     * Process the batch creation form
     * Logs supplier batch information including weight and moisture
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function create()
    {
        $validation = \Config\Services::validation();
        
        // Validate basic batch data
        $rules = [
            'batch_number' => 'required|is_unique[batches.batch_number]',
            'purchase_order_id' => 'required|integer',
            'grain_type' => 'required',
            'batch_created_date' => 'required|valid_date',
            'bags' => 'required',
            'bags.*.bag_number' => 'required|integer|greater_than[0]',
            'bags.*.weight_kg' => 'required|decimal|greater_than[0]',
            'bags.*.moisture_percentage' => 'required|decimal|greater_than[0]|less_than[100]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Get PO details and validate
            $poId = $this->request->getPost('purchase_order_id');
            $po = $this->purchaseOrderModel->find($poId);
            
            // Allow approved, confirmed, pending, transferring, and empty status (legacy data)
            $allowedStatuses = ['approved', 'confirmed', 'pending', 'transferring', ''];
            if (!$po || !in_array($po['status'], $allowedStatuses)) {
                throw new \Exception('Selected purchase order is not available or not approved. Current status: ' . ($po['status'] ?? 'null'));
            }

            // Process bag data
            $bags = $this->request->getPost('bags');
            $totalWeight = 0;
            $totalMoisture = 0;
            $bagCount = count($bags);
            
            foreach ($bags as $bag) {
                $totalWeight += floatval($bag['weight_kg']);
                $totalMoisture += floatval($bag['moisture_percentage']);
            }
            
            $averageMoisture = $totalMoisture / $bagCount;
            $totalWeightMT = round($totalWeight / 1000, 3);
            
            // Create batch record with PO validation
            $batchData = [
                'batch_number' => $this->request->getPost('batch_number'),
                'purchase_order_id' => $poId,
                'supplier_id' => $po['supplier_id'],
                'grain_type' => $this->request->getPost('grain_type'),
                'total_bags' => $bagCount,
                'total_weight_kg' => $totalWeight,
                'total_weight_mt' => $totalWeightMT,
                'average_moisture' => round($averageMoisture, 2),
                'quality_grade' => $this->determineQualityGrade($averageMoisture),
                'status' => 'pending',
                'notes' => $this->request->getPost('notes'),
                'received_date' => $this->request->getPost('batch_created_date')
            ];

            // Validate batch against PO constraints
            $validation = $this->batchModel->validateBatchAgainstPO($batchData, $poId);
            if (!$validation['valid']) {
                throw new \Exception($validation['message']);
            }
            
            $batchId = $this->batchModel->insert($batchData);
            
            if (!$batchId) {
                throw new \Exception('Failed to create batch record');
            }
            
            // Insert bag records
            if (!$this->batchBagModel->insertBags($batchId, $bags)) {
                throw new \Exception('Failed to create bag records');
            }
            
            // Update purchase order status based on transfers
            $this->purchaseOrderModel->updateStatusBasedOnTransfers($poId);
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }
            
            // Send notification about new batch
            helper('notification');
            sendBatchNotification($batchId, $batchData['batch_number'], 'created', [
                'total_weight' => $batchData['total_weight_mt'],
                'grain_type' => $batchData['grain_type'],
                'quality_grade' => $batchData['quality_grade'],
                'po_number' => $po['po_number']
            ]);
            
            session()->setFlashdata('success', 'Batch ' . $batchData['batch_number'] . ' was successfully created with ' . $bagCount . ' bags totaling ' . $batchData['total_weight_mt'] . ' MT. Awaiting approval from PO authorizer.');
            
            return redirect()->to('/batches');
            
        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Failed to create batch: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * View batch details
     * 
     * @param int $id
     * @return string
     */
    public function view($id)
    {
        $batch = $this->batchModel->getBatchWithSupplier($id);
        
        if (!$batch) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Batch not found');
        }
        
        $data = [
            'title' => 'Batch Details - ' . $batch['batch_number'],
            'batch' => $batch,
            'bags' => $this->batchBagModel->getBagsByBatch($id)
        ];
        
        return view('batches/view', $data);
    }
    
    /**
     * Approve a batch
     * 
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function approve($id)
    {
        $session = session();
        $userId = $session->get('user_id');
        
        // Check if user can approve this batch
        $canApprove = $this->batchModel->canUserApproveBatch($id, $userId);
        if (!$canApprove['can_approve']) {
            session()->setFlashdata('error', $canApprove['message']);
            return redirect()->back();
        }

        $batch = $this->batchModel->find($id);
        
        if (!$batch) {
            session()->setFlashdata('error', 'Batch not found');
            return redirect()->to('/batches');
        }
        
        if ($batch['status'] !== 'pending') {
            session()->setFlashdata('error', 'Only pending batches can be approved');
            return redirect()->back();
        }
        
        $updateData = [
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => date('Y-m-d H:i:s')
        ];
        
        $this->batchModel->update($id, $updateData);
        
        // Send notification about batch approval
        helper('notification');
        sendBatchNotification($id, $batch['batch_number'], 'approved', [
            'status' => 'approved',
            'grain_type' => $batch['grain_type'],
            'approved_by' => $userId
        ]);
        
        session()->setFlashdata('success', 'Batch ' . $batch['batch_number'] . ' has been approved and is ready for dispatch.');
        
        return redirect()->back();
    }
    
    /**
     * Reject a batch
     * 
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function reject($id)
    {
        $session = session();
        $userId = $session->get('user_id');
        
        // Check if user can approve/reject this batch
        $canApprove = $this->batchModel->canUserApproveBatch($id, $userId);
        if (!$canApprove['can_approve']) {
            session()->setFlashdata('error', $canApprove['message']);
            return redirect()->back();
        }

        $batch = $this->batchModel->find($id);
        
        if (!$batch) {
            session()->setFlashdata('error', 'Batch not found');
            return redirect()->to('/batches');
        }
        
        if ($batch['status'] !== 'pending') {
            session()->setFlashdata('error', 'Only pending batches can be rejected');
            return redirect()->back();
        }

        $rejectionReason = $this->request->getPost('rejection_reason');
        if (empty($rejectionReason)) {
            session()->setFlashdata('error', 'Rejection reason is required');
            return redirect()->back();
        }
        
        $updateData = [
            'status' => 'rejected',
            'approved_by' => $userId,
            'approved_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $rejectionReason
        ];
        
        $this->batchModel->update($id, $updateData);
        
        session()->setFlashdata('success', 'Batch ' . $batch['batch_number'] . ' has been rejected.');
        
        return redirect()->back();
    }

    /**
     * Get PO details for batch creation (AJAX endpoint)
     */
    public function getPODetails($poId)
    {
        try {
            $po = $this->purchaseOrderModel->find($poId);
            
            if (!$po || $po['status'] !== 'approved') {
                return $this->response->setJSON(['error' => 'Purchase order not found or not approved']);
            }

            $supplier = $this->supplierModel->find($po['supplier_id']);
            
            return $this->response->setJSON([
                'po' => $po,
                'supplier' => $supplier,
                'fulfillment_progress' => $this->purchaseOrderModel->getPOFulfillmentProgress($poId)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'BatchController::getPODetails() error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to get PO details']);
        }
    }
    
    /**
     * Determine quality grade based on moisture content
     * 
     * @param float $moisture
     * @return string
     */
    private function determineQualityGrade($moisture)
    {
        if ($moisture <= 12) {
            return 'A+';
        } elseif ($moisture <= 14) {
            return 'A';
        } elseif ($moisture <= 16) {
            return 'B+';
        } elseif ($moisture <= 18) {
            return 'B';
        } else {
            return 'C';
        }
    }
}
