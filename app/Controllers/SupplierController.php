<?php

namespace App\Controllers;

use App\Models\SupplierModel;
use CodeIgniter\HTTP\ResponseInterface;

class SupplierController extends BaseController
{
    protected $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
    }

    /**
     * Display suppliers list with search and filtering
     */
    public function index()
    {
        $search = $this->request->getGet('search') ?? '';
        $type = $this->request->getGet('type') ?? '';
        $status = $this->request->getGet('status') ?? 'active';
        $page = (int)($this->request->getGet('page') ?? 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $suppliers = $this->supplierModel->searchSuppliers($search, $type, $status, $perPage, $offset);
        
        // Get total count for pagination
        $totalSuppliers = $this->supplierModel->where('status', $status)->countAllResults();
        if (!empty($search) || !empty($type)) {
            $totalSuppliers = count($this->supplierModel->searchSuppliers($search, $type, $status, 1000, 0));
        }

        $data = [
            'suppliers' => $suppliers,
            'search' => $search,
            'type' => $type,
            'status' => $status,
            'currentPage' => $page,
            'totalPages' => ceil($totalSuppliers / $perPage),
            'totalSuppliers' => $totalSuppliers
            // Supplier types removed since supplier_type column doesn't exist
        ];

        return view('suppliers/index', $data);
    }

    /**
     * Show create supplier form
     */
    public function new()
    {
        $data = [
            // Supplier types removed since supplier_type column doesn't exist
        ];
        return view('suppliers/create', $data);
    }

    /**
     * Create new supplier
     */
    public function create()
    {
        $data = [
            'name' => trim($this->request->getPost('supplier_name')),
            'contact_person' => trim($this->request->getPost('contact_person')),
            'phone' => trim($this->request->getPost('phone')),
            'email' => trim($this->request->getPost('email')),
            'address' => trim($this->request->getPost('address')),
            'status' => 'active'
        ];

        if (!$this->supplierModel->insert($data)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->supplierModel->errors());
        }

        $supplierId = $this->supplierModel->getInsertID();
        
        // Send notification
        if (function_exists('sendNotification')) {
            sendNotification(
                session()->get('user_id') ?? 1,
                'supplier_management',
                'New Supplier Added',
                "Supplier '{$data['name']}' has been successfully registered",
                ['supplier_id' => $supplierId],
                'medium'
            );
        }

        return redirect()->to('suppliers')
                       ->with('success', 'Supplier created successfully');
    }

    /**
     * Show supplier details
     */
    public function show($id)
    {
        $supplier = $this->supplierModel->find($id);
        
        if (!$supplier) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Supplier not found');
        }

        $stats = $this->supplierModel->getSupplierStats($id);
        $financials = $this->supplierModel->getSupplierFinancials($id);

        $data = [
            'supplier' => $supplier,
            'stats' => $stats,
            'financials' => $financials
        ];

        return view('suppliers/show', $data);
    }

    /**
     * Show edit supplier form
     */
    public function edit($id)
    {
        $supplier = $this->supplierModel->find($id);
        
        if (!$supplier) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Supplier not found');
        }

        $data = [
            'supplier' => $supplier
            // Supplier types removed since supplier_type column doesn't exist
        ];

        return view('suppliers/edit', $data);
    }

    /**
     * Update supplier
     */
    public function update($id)
    {
        $supplier = $this->supplierModel->find($id);
        
        if (!$supplier) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Supplier not found');
        }

        $data = [
            'name' => trim($this->request->getPost('supplier_name')),
            'contact_person' => trim($this->request->getPost('contact_person')),
            'phone' => trim($this->request->getPost('phone')),
            'email' => trim($this->request->getPost('email')),
            'address' => trim($this->request->getPost('address')),
            'status' => $this->request->getPost('status'),
            'notes' => trim($this->request->getPost('notes'))
        ];

        // Set validation rules for update (exclude current ID from uniqueness check)
        $this->supplierModel->setValidationRules([
            'name' => "required|min_length[2]|max_length[255]|is_unique[suppliers.name,id,{$id}]",
            'contact_person' => 'permit_empty|max_length[255]',
            'phone' => 'permit_empty|max_length[20]',
            'email' => 'permit_empty|valid_email|max_length[255]',
            'address' => 'permit_empty',
            'status' => 'permit_empty|in_list[active,inactive,archived]',
            'notes' => 'permit_empty'
        ]);

        if (!$this->supplierModel->update($id, $data)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->supplierModel->errors());
        }

        // Send notification
        helper('notification');
        if (function_exists('sendNotification')) {
            sendNotification(
                session()->get('user_id') ?? 1,
                'supplier_management',
                'Supplier Updated',
                "Supplier '{$data['name']}' has been successfully updated",
                ['supplier_id' => $id],
                'low'
            );
        }

        return redirect()->to('suppliers')
                       ->with('success', 'Supplier updated successfully');
    }

    /**
     * Archive supplier (soft delete)
     */
    public function archive($id)
    {
        $supplier = $this->supplierModel->find($id);
        
        if (!$supplier) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Supplier not found');
        }

        if ($this->supplierModel->archiveSupplier($id)) {
            // Send notification
            if (function_exists('sendNotification')) {
                sendNotification([
                    'type' => 'supplier_management',
                    'title' => 'Supplier Archived',
                    'message' => "Supplier '{$supplier['name']}' has been archived",
                    'data' => ['supplier_id' => $id],
                    'user_id' => session()->get('user_id'),
                    'priority' => 'medium'
                ]);
            }

            return redirect()->to('suppliers')
                           ->with('success', 'Supplier archived successfully');
        }

        return redirect()->to('suppliers')
                       ->with('error', 'Failed to archive supplier');
    }

    /**
     * Restore archived supplier
     */
    public function restore($id)
    {
        $supplier = $this->supplierModel->withDeleted()->find($id);
        
        if (!$supplier) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Supplier not found');
        }

        if ($this->supplierModel->restoreSupplier($id)) {
            return redirect()->to('suppliers')
                           ->with('success', 'Supplier restored successfully');
        }

        return redirect()->to('suppliers')
                       ->with('error', 'Failed to restore supplier');
    }

    /**
     * Get suppliers for AJAX dropdown
     */
    public function getSuppliers()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $search = $this->request->getGet('search') ?? '';
        $type = $this->request->getGet('type') ?? '';
        
        $suppliers = $this->supplierModel->searchSuppliers($search, $type, 'active', 50, 0);

        return $this->response->setJSON([
            'success' => true,
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Search suppliers for AJAX requests
     */
    public function search()
    {
        $search = $this->request->getGet('search') ?? '';
        $type = $this->request->getGet('type') ?? '';
        
        $suppliers = $this->supplierModel->searchSuppliers($search, $type, 'active', 50, 0);

        return $this->response->setJSON([
            'success' => true,
            'data' => $suppliers
        ]);
    }

    /**
     * Create supplier via AJAX from other forms
     */
    public function createAjax(): ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $data = [
            'name' => trim((string)$this->request->getPost('supplier_name')),
            'contact_person' => trim((string)$this->request->getPost('contact_person')),
            'phone' => trim((string)$this->request->getPost('phone')),
            'email' => trim((string)$this->request->getPost('email')),
            'address' => trim((string)$this->request->getPost('address')),
            'status' => 'active',
        ];

        if (!$this->supplierModel->insert($data)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'errors' => $this->supplierModel->errors(),
            ]);
        }

        $id = (int)$this->supplierModel->getInsertID();
        
        // Send notification
        helper('notification');
        if (function_exists('sendNotification')) {
            sendNotification(
                session()->get('user_id') ?? 1,
                'supplier_management',
                'New Supplier Added',
                "Supplier '{$data['name']}' has been successfully registered via batch form",
                ['supplier_id' => $id],
                'medium'
            );
        }
        
        return $this->response->setJSON([
            'success' => true,
            'supplier' => [
                'id' => $id,
                'name' => $data['name']
            ],
        ]);
    }

    /**
     * Export suppliers to CSV
     */
    public function export()
    {
        $suppliers = $this->supplierModel->where('status !=', 'archived')->findAll();
        
        $filename = 'suppliers_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, [
            'ID', 'Name', 'Contact Person', 
            'Phone', 'Email', 'Address', 'Status', 'Created'
        ]);
        
        // CSV data
        foreach ($suppliers as $supplier) {
            fputcsv($output, [
                $supplier['id'],
                $supplier['name'],
                $supplier['contact_person'],
                $supplier['phone'],
                $supplier['email'],
                $supplier['address'],
                $supplier['status'],
                $supplier['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
}
