<?php

namespace App\Controllers;

use App\Models\BatchModel;
use App\Models\BatchBagModel;
use App\Models\SupplierModel;

class BatchController extends BaseController
{
    protected $batchModel;
    protected $batchBagModel;
    protected $supplierModel;

    public function __construct()
    {
        $this->batchModel = new BatchModel();
        $this->batchBagModel = new BatchBagModel();
        $this->supplierModel = new SupplierModel();
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
        $data = [
            'title' => 'Create New Batch',
            'suppliers' => $this->supplierModel->getActiveSuppliers(),
            'batch_number' => $this->batchModel->generateBatchNumber()
        ];
        
        return view('batches/create', $data);
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
            'supplier_id' => 'required|integer',
            'grain_type' => 'required',
            'received_date' => 'required|valid_date',
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
            
            // Create batch record
            $batchData = [
                'batch_number' => $this->request->getPost('batch_number'),
                'supplier_id' => $this->request->getPost('supplier_id'),
                'grain_type' => $this->request->getPost('grain_type'),
                'total_bags' => $bagCount,
                'total_weight_kg' => $totalWeight,
                'total_weight_mt' => round($totalWeight / 1000, 3),
                'average_moisture' => round($averageMoisture, 2),
                'quality_grade' => $this->determineQualityGrade($averageMoisture),
                'status' => 'pending',
                'notes' => $this->request->getPost('notes'),
                'received_date' => $this->request->getPost('received_date')
            ];
            
            $batchId = $this->batchModel->insert($batchData);
            
            if (!$batchId) {
                throw new \Exception('Failed to create batch record');
            }
            
            // Insert bag records
            if (!$this->batchBagModel->insertBags($batchId, $bags)) {
                throw new \Exception('Failed to create bag records');
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }
            
            // Send notification about new batch
            sendBatchNotification($batchId, $batchData['batch_number'], 'created', [
                'total_weight' => $batchData['total_weight_mt'],
                'grain_type' => $batchData['grain_type'],
                'quality_grade' => $batchData['quality_grade']
            ]);
            
            session()->setFlashdata('success', 'Batch ' . $batchData['batch_number'] . ' was successfully created with ' . $bagCount . ' bags totaling ' . $batchData['total_weight_mt'] . ' MT.');
            
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
        $batch = $this->batchModel->find($id);
        
        if (!$batch) {
            session()->setFlashdata('error', 'Batch not found');
            return redirect()->to('/batches');
        }
        
        if ($batch['status'] !== 'pending') {
            session()->setFlashdata('error', 'Only pending batches can be approved');
            return redirect()->back();
        }
        
        $this->batchModel->update($id, ['status' => 'approved']);
        
        // Send notification about batch approval
        sendBatchNotification($id, $batch['batch_number'], 'arrived', [
            'status' => 'approved',
            'grain_type' => $batch['grain_type']
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
        $batch = $this->batchModel->find($id);
        
        if (!$batch) {
            session()->setFlashdata('error', 'Batch not found');
            return redirect()->to('/batches');
        }
        
        if ($batch['status'] !== 'pending') {
            session()->setFlashdata('error', 'Only pending batches can be rejected');
            return redirect()->back();
        }
        
        $this->batchModel->update($id, [
            'status' => 'rejected',
            'notes' => $batch['notes'] . '\n[REJECTED] ' . $this->request->getPost('rejection_reason')
        ]);
        
        session()->setFlashdata('success', 'Batch ' . $batch['batch_number'] . ' has been rejected.');
        
        return redirect()->back();
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
