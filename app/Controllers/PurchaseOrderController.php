<?php

namespace App\Controllers;

class PurchaseOrderController extends BaseController
{
    /**
     * Display list of all purchase orders
     * 
     * @return string
     */
    public function index()
    {
        try {
            $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
            
            // Get purchase orders with supplier information and transferred quantities
            $builder = $purchaseOrderModel->db->table('purchase_orders po');
            $builder->select('po.*, s.name as supplier_name, s.contact_person, s.phone, COALESCE(SUM(b.total_weight_mt), 0) as transferred_quantity_mt');
            $builder->join('suppliers s', 's.id = po.supplier_id', 'left');
            $builder->join('batches b', 'b.purchase_order_id = po.id', 'left');
            $builder->groupBy('po.id');
            $builder->orderBy('po.order_date', 'DESC');
            $purchaseOrders = $builder->get()->getResultArray();
            
            // Calculate dynamic status for each PO
            foreach ($purchaseOrders as &$po) {
                $po['dynamic_status'] = $this->calculateDynamicStatus($po);
            }
            
            $data = [
                'purchaseOrders' => $purchaseOrders ?? []
            ];
            
            return view('purchase_orders/index', $data);
        } catch (\Exception $e) {
            log_message('error', 'Purchase Order Index Error: ' . $e->getMessage());
            
            // Return view with empty data if there's an error
            $data = [
                'purchaseOrders' => []
            ];
            
            return view('purchase_orders/index', $data);
        }
    }
    
    /**
     * Calculate dynamic status based on transfer progress
     */
    private function calculateDynamicStatus($po)
    {
        $transferredQty = (float)$po['transferred_quantity_mt'];
        $totalQty = (float)$po['quantity_mt'];
        
        // If no transfers yet, return original status or pending
        if ($transferredQty == 0) {
            return !empty($po['status']) ? $po['status'] : 'pending';
        }
        
        // If transferred quantity equals or exceeds total quantity, mark as completed
        if ($transferredQty >= $totalQty) {
            return 'completed';
        }
        
        // If there are transfers but not complete, mark as transferring
        return 'transferring';
    }
    
    /**
     * Display form to create a new purchase order
     * 
     * @return string
     */
    public function new()
    {
        $supplierModel = new \App\Models\SupplierModel();
        $data = [
            'suppliers' => $supplierModel->getActiveSuppliers()
        ];
        
        return view('purchase_orders/create', $data);
    }
    
    /**
     * Process the purchase order creation form
     * Raises new purchase orders
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function create()
    {
        // Basic validation for current phase (no DB yet)
        $rules = [
            'supplier_id' => 'required|integer'
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $supplierId = (int)$this->request->getPost('supplier_id');
        
        // Get supplier info
        $supplierModel = new \App\Models\SupplierModel();
        $supplier = $supplierModel->find($supplierId);
        
        if (!$supplier) {
            return redirect()->back()->withInput()->with('error', 'Selected supplier not found');
        }

        $orderDate = (string)$this->request->getPost('order_date');
        $expectedDelivery = (string)$this->request->getPost('expected_delivery');
        $grainType = (string)$this->request->getPost('grain_type');
        $quantity = (float)$this->request->getPost('quantity');
        $unitPrice = (float)$this->request->getPost('unit_price');
        $paymentTerms = (string)$this->request->getPost('payment_terms');
        $notes = (string)$this->request->getPost('notes');
        $paidInFull = $this->request->getPost('paid_in_full') ? true : false;
        $advancePayment = (float)$this->request->getPost('advance_payment');

        $total = round($quantity * $unitPrice, 2);
        if ($paidInFull) {
            $advancePayment = $total; // enforce
        }

        // Generate PO number
        $poNumber = 'PO-' . date('Ymd') . '-' . rand(100, 999);

        // Save to database
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        
        $purchaseOrderData = [
            'po_number' => $poNumber,
            'supplier_id' => $supplierId,
            'order_date' => $orderDate,
            'expected_delivery_date' => $expectedDelivery,
            'grain_type' => $grainType,
            'quantity_mt' => $quantity,
            'unit_price' => $unitPrice,
            'total_amount' => $total,
            'status' => 'pending',
            'delivered_quantity_mt' => 0,
            'remaining_quantity_mt' => $quantity
        ];

        try {
            $purchaseOrderId = $purchaseOrderModel->insert($purchaseOrderData);
            
            if ($purchaseOrderId) {
                return redirect()->to('/purchase-orders')->with('success', 'Purchase order created successfully! PO Number: ' . $poNumber);
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to create purchase order. Please try again.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Purchase Order Creation Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        }
    }

    /**
     * Display a specific purchase order
     */
    public function show($id)
    {
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        
        // Get purchase order with supplier information
        $builder = $purchaseOrderModel->db->table('purchase_orders po');
        $builder->select('po.*, s.name as supplier_name, s.contact_person, s.phone, s.email, s.address');
        $builder->join('suppliers s', 's.id = po.supplier_id', 'left');
        $builder->where('po.id', $id);
        $purchaseOrder = $builder->get()->getRowArray();
        
        if (!$purchaseOrder) {
            return redirect()->to('/purchase-orders')->with('error', 'Purchase order not found');
        }
        
        // Calculate transferred quantity from batches and get batches list
        $batchModel = new \App\Models\BatchModel();
        $transferredQuery = $batchModel->db->table('batches');
        $transferredQuery->selectSum('total_weight_mt', 'transferred_quantity_mt');
        $transferredQuery->where('purchase_order_id', $id);
        $transferredResult = $transferredQuery->get()->getRowArray();
        $transferredQuantity = $transferredResult['transferred_quantity_mt'] ?? 0;
        
        // Get all batches for this purchase order
        $batchesQuery = $batchModel->db->table('batches b');
        $batchesQuery->select('b.*, s.name as supplier_name');
        $batchesQuery->join('suppliers s', 's.id = b.supplier_id', 'left');
        $batchesQuery->where('b.purchase_order_id', $id);
        $batchesQuery->orderBy('b.created_at', 'DESC');
        $batches = $batchesQuery->get()->getResultArray();
        
        // Add transferred quantity and batches to purchase order data
        $purchaseOrder['transferred_quantity_mt'] = $transferredQuantity;
        
        $data = [
            'purchaseOrder' => $purchaseOrder,
            'batches' => $batches
        ];
        
        return view('purchase_orders/show', $data);
    }

    /**
     * Display form to edit a purchase order
     */
    public function edit($id)
    {
        try {
            $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
            $supplierModel = new \App\Models\SupplierModel();
            
            // Use query builder instead of find() to avoid soft delete issues
            $builder = $purchaseOrderModel->db->table('purchase_orders');
            $purchaseOrder = $builder->where('id', $id)->get()->getRowArray();
            
            if (!$purchaseOrder) {
                return redirect()->to('/purchase-orders')->with('error', 'Purchase order not found');
            }
            
            $data = [
                'purchaseOrder' => $purchaseOrder,
                'suppliers' => $supplierModel->getActiveSuppliers()
            ];
            
            return view('purchase_orders/edit', $data);
        } catch (\Exception $e) {
            log_message('error', 'Purchase Order Edit Error: ' . $e->getMessage());
            return redirect()->to('/purchase-orders')->with('error', 'Error loading purchase order for editing');
        }
    }

    /**
     * Update a purchase order
     */
    public function update($id)
    {
        try {
            $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
            
            // Use query builder instead of find() to avoid soft delete issues
            $builder = $purchaseOrderModel->db->table('purchase_orders');
            $purchaseOrder = $builder->where('id', $id)->get()->getRowArray();
            
            if (!$purchaseOrder) {
                return redirect()->to('/purchase-orders')->with('error', 'Purchase order not found');
            }

        // Validation
        $rules = [
            'supplier_id' => 'required|integer',
            'order_date' => 'required|valid_date',
            'expected_delivery_date' => 'required|valid_date',
            'grain_type' => 'required|max_length[100]',
            'quantity_mt' => 'required|decimal|greater_than[0]',
            'unit_price' => 'required|decimal|greater_than[0]',
            'total_amount' => 'required|decimal|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'supplier_id' => (int)$this->request->getPost('supplier_id'),
            'order_date' => $this->request->getPost('order_date'),
            'expected_delivery_date' => $this->request->getPost('expected_delivery_date'),
            'grain_type' => $this->request->getPost('grain_type'),
            'quantity_mt' => (float)$this->request->getPost('quantity_mt'),
            'unit_price' => (float)$this->request->getPost('unit_price'),
            'total_amount' => (float)$this->request->getPost('total_amount')
        ];

            // Use query builder for update to avoid model issues
            $builder = $purchaseOrderModel->db->table('purchase_orders');
            $result = $builder->where('id', $id)->update($updateData);
            
            if ($result) {
                return redirect()->to('/purchase-orders')->with('success', 'Purchase order updated successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to update purchase order');
            }
        } catch (\Exception $e) {
            log_message('error', 'Purchase Order Update Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        }
    }

    /**
     * Delete a purchase order
     */
    public function delete($id)
    {
        try {
            $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
            
            // Use query builder to check if record exists
            $builder = $purchaseOrderModel->db->table('purchase_orders');
            $purchaseOrder = $builder->where('id', $id)->get()->getRowArray();
            
            if (!$purchaseOrder) {
                return redirect()->to('/purchase-orders')->with('error', 'Purchase order not found');
            }

            // Use query builder for delete to avoid model issues
            $result = $builder->where('id', $id)->delete();
            
            if ($result) {
                return redirect()->to('/purchase-orders')->with('success', 'Purchase order deleted successfully!');
            } else {
                return redirect()->to('/purchase-orders')->with('error', 'Failed to delete purchase order');
            }
        } catch (\Exception $e) {
            log_message('error', 'Purchase Order Delete Error: ' . $e->getMessage());
            return redirect()->to('/purchase-orders')->with('error', 'Failed to delete purchase order');
        }
    }

    /**
     * Approve a purchase order
     */
    public function approve($id)
    {
        try {
            $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
            $purchaseOrder = $purchaseOrderModel->find($id);
            
            if (!$purchaseOrder) {
                return redirect()->to('/purchase-orders')->with('error', 'Purchase order not found');
            }

            if ($purchaseOrder['status'] !== 'pending') {
                return redirect()->back()->with('error', 'Only pending purchase orders can be approved');
            }

            // Get current user ID (assuming session contains user info)
            $session = session();
            $userId = $session->get('user_id');

            $updateData = [
                'status' => 'approved',
                'approved_by' => $userId,
                'approved_at' => date('Y-m-d H:i:s')
            ];

            $result = $purchaseOrderModel->update($id, $updateData);
            
            if ($result) {
                // Send notification about PO approval
                helper('notification');
                sendSystemNotification(
                    'Purchase Order Approved',
                    'PO #' . $purchaseOrder['po_number'] . ' has been approved and is ready for batch creation.',
                    'po_approved',
                    ['po_id' => $id, 'po_number' => $purchaseOrder['po_number']]
                );

                return redirect()->to('/purchase-orders')->with('success', 'Purchase order ' . $purchaseOrder['po_number'] . ' has been approved successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to approve purchase order');
            }
        } catch (\Exception $e) {
            log_message('error', 'Purchase Order Approval Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Reject a purchase order
     */
    public function reject($id)
    {
        try {
            $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
            $purchaseOrder = $purchaseOrderModel->find($id);
            
            if (!$purchaseOrder) {
                return redirect()->to('/purchase-orders')->with('error', 'Purchase order not found');
            }

            if ($purchaseOrder['status'] !== 'pending') {
                return redirect()->back()->with('error', 'Only pending purchase orders can be rejected');
            }

            $rejectionReason = $this->request->getPost('rejection_reason');
            if (empty($rejectionReason)) {
                return redirect()->back()->with('error', 'Rejection reason is required');
            }

            // Get current user ID
            $session = session();
            $userId = $session->get('user_id');

            $updateData = [
                'status' => 'rejected',
                'approved_by' => $userId,
                'approved_at' => date('Y-m-d H:i:s'),
                'rejection_reason' => $rejectionReason
            ];

            $result = $purchaseOrderModel->update($id, $updateData);
            
            if ($result) {
                // Send notification about PO rejection
                helper('notification');
                sendSystemNotification(
                    'Purchase Order Rejected',
                    'PO #' . $purchaseOrder['po_number'] . ' has been rejected. Reason: ' . $rejectionReason,
                    'po_rejected',
                    ['po_id' => $id, 'po_number' => $purchaseOrder['po_number'], 'reason' => $rejectionReason]
                );

                return redirect()->to('/purchase-orders')->with('success', 'Purchase order ' . $purchaseOrder['po_number'] . ' has been rejected.');
            } else {
                return redirect()->back()->with('error', 'Failed to reject purchase order');
            }
        } catch (\Exception $e) {
            log_message('error', 'Purchase Order Rejection Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reject purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Get PO fulfillment progress (AJAX endpoint)
     */
    public function getFulfillmentProgress($id)
    {
        try {
            $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
            $progress = $purchaseOrderModel->getPOFulfillmentProgress($id);
            
            if ($progress === null) {
                return $this->response->setJSON(['error' => 'Purchase order not found']);
            }

            return $this->response->setJSON($progress);
        } catch (\Exception $e) {
            log_message('error', 'PO Fulfillment Progress Error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to get fulfillment progress']);
        }
    }

    /**
     * Search purchase orders for batch creation (AJAX endpoint)
     */
    public function search()
    {
        $query = $this->request->getGet('q');
        
        if (empty($query)) {
            return $this->response->setJSON([]);
        }
        
        try {
            $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
            
            // Search purchase orders with supplier information and transferred quantities
            $builder = $purchaseOrderModel->db->table('purchase_orders po');
            $builder->select('po.id, po.po_number, po.grain_type, po.quantity_mt, po.delivered_quantity_mt, po.status, s.name as supplier_name, COALESCE(SUM(b.total_weight_mt), 0) as transferred_quantity_mt');
            $builder->join('suppliers s', 's.id = po.supplier_id', 'left');
            $builder->join('batches b', 'b.purchase_order_id = po.id', 'left');
            
            // Allow multiple statuses for search - include approved, confirmed, pending, transferring, and empty
            $allowedStatuses = ['approved', 'confirmed', 'pending', 'transferring', ''];
            $builder->groupStart();
            foreach ($allowedStatuses as $status) {
                if ($status === '') {
                    $builder->orWhere('po.status IS NULL');
                    $builder->orWhere('po.status', '');
                } else {
                    $builder->orWhere('po.status', $status);
                }
            }
            $builder->groupEnd();
            
            // Search by PO number or supplier name
            $builder->groupStart();
            $builder->like('po.po_number', $query);
            $builder->orLike('s.name', $query);
            $builder->groupEnd();
            
            $builder->groupBy('po.id');
            $builder->orderBy('po.order_date', 'DESC');
            $builder->limit(20); // Increased limit to account for filtering
            
            $results = $builder->get()->getResultArray();
            
            // Filter out completed POs and calculate remaining quantity
            $filteredResults = [];
            foreach ($results as $po) {
                $transferredQty = (float)$po['transferred_quantity_mt'];
                $totalQty = (float)$po['quantity_mt'];
                
                // Skip if PO is completed (transferred quantity >= total quantity)
                if ($transferredQty >= $totalQty) {
                    continue;
                }
                
                $po['remaining_quantity_mt'] = max(0, $totalQty - $transferredQty);
                $filteredResults[] = $po;
            }
            
            // Limit to 10 results after filtering
            $filteredResults = array_slice($filteredResults, 0, 10);
            
            return $this->response->setJSON($filteredResults);
            
        } catch (\Exception $e) {
            log_message('error', 'PO Search Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Failed to search purchase orders: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Test endpoint to get all available purchase orders (for debugging)
     */
    public function testSearch()
    {
        try {
            $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
            
            $builder = $purchaseOrderModel->db->table('purchase_orders po');
            $builder->select('po.*, s.name as supplier_name, s.contact_person, s.phone');
            $builder->join('suppliers s', 's.id = po.supplier_id', 'left');
            $builder->where('po.remaining_quantity_mt >', 0);
            
            $purchaseOrders = $builder->get()->getResultArray();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $purchaseOrders,
                'count' => count($purchaseOrders)
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get batches by purchase order ID (AJAX endpoint)
     */
    public function getBatches($poId)
    {
        try {
            $batchModel = new \App\Models\BatchModel();
            
            $builder = $batchModel->db->table('batches b');
            $builder->select('b.*, s.name as supplier_name');
            $builder->join('suppliers s', 's.id = b.supplier_id', 'left');
            $builder->where('b.purchase_order_id', $poId);
            $builder->orderBy('b.created_at', 'DESC');
            
            $batches = $builder->get()->getResultArray();
            
            // Calculate total transferred quantity
            $totalTransferred = 0;
            foreach ($batches as $batch) {
                $totalTransferred += $batch['total_weight_mt'];
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $batches,
                'total_transferred_mt' => $totalTransferred
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get PO Batches Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Failed to get batches for purchase order'
            ]);
        }
    }
}
