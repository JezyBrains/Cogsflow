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
            'trailer_number' => 'permit_empty|min_length[3]|max_length[20]',
            'driver_name' => 'required|min_length[3]|max_length[255]',
            'driver_phone' => 'permit_empty|min_length[10]|max_length[20]',
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
                'status' => 'pending',
                'notes' => $this->request->getPost('notes')
            ];
            
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
        $validStatuses = ['pending', 'in_transit', 'delivered', 'cancelled'];
        
        if (!in_array($newStatus, $validStatuses)) {
            session()->setFlashdata('error', 'Invalid status provided');
            return redirect()->back();
        }
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Update dispatch status
            $this->dispatchModel->update($id, ['status' => $newStatus]);
            
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
                'delivered' => 'Dispatch marked as delivered',
                'cancelled' => 'Dispatch cancelled and batch returned to available pool'
            ];
            
            session()->setFlashdata('success', $statusMessages[$newStatus]);
            return redirect()->to('/dispatches');
            
        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Failed to update dispatch status: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
