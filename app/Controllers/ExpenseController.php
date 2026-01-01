<?php

namespace App\Controllers;

use App\Models\ExpenseModel;
use App\Models\ExpenseCategoryModel;
use App\Models\ExpenseAuditLogModel;

class ExpenseController extends BaseController
{
    protected $expenseModel;
    protected $categoryModel;
    protected $auditModel;

    public function __construct()
    {
        $this->expenseModel = new ExpenseModel();
        $this->categoryModel = new ExpenseCategoryModel();
        $this->auditModel = new ExpenseAuditLogModel();
        helper(['currency', 'notification']);
    }
    /**
     * Display list of all expenses with filters
     */
    public function index()
    {
        try {
            // Get filter parameters
            $keyword = $this->request->getGet('keyword');
            $categoryId = $this->request->getGet('category');
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            $status = $this->request->getGet('status');
            
            // Get expenses with filters
            if ($keyword || $categoryId || $startDate || $endDate) {
                $expenses = $this->expenseModel->searchExpenses($keyword, $categoryId, $startDate, $endDate);
            } else {
                $expenses = $this->expenseModel->getAllWithDetails();
            }
            
            // Filter by approval status if specified
            if ($status) {
                $expenses = array_filter($expenses, function($expense) use ($status) {
                    return $expense['approval_status'] === $status;
                });
            }
            
            // Get statistics
            $stats = $this->expenseModel->getExpenseStats();
            
            // Get categories for filter dropdown
            $categories = $this->categoryModel->getActiveCategories();
            
            // Get category breakdown
            $categoryBreakdown = $this->expenseModel->getExpensesByCategory(date('Y'), date('m'));
            
            $data = [
                'expenses' => $expenses,
                'stats' => $stats,
                'categories' => $categories,
                'categoryBreakdown' => $categoryBreakdown,
                'filters' => [
                    'keyword' => $keyword,
                    'category' => $categoryId,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => $status,
                ],
            ];
            
            return view('expenses/index', $data);
        } catch (\Exception $e) {
            log_message('error', 'Expense Index Error: ' . $e->getMessage());
            return view('expenses/index', ['expenses' => [], 'stats' => [], 'categories' => [], 'categoryBreakdown' => [], 'filters' => []]);
        }
    }
    
    /**
     * Display form to create a new expense
     */
    public function new()
    {
        $data = [
            'categories' => $this->categoryModel->getActiveCategories(),
        ];
        
        return view('expenses/create', $data);
    }
    
    /**
     * Store new expense
     */
    public function store()
    {
        // Validation rules
        $rules = [
            'expense_date' => 'required|valid_date',
            'category_id' => 'required|integer',
            'description' => 'required|max_length[500]',
            'amount' => 'required|decimal|greater_than[0]',
            'payment_method' => 'required|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            // Parse currency input (remove thousands separators)
            $amount = parse_currency_input($this->request->getPost('amount'));
            
            // Generate expense number
            $expenseNumber = $this->expenseModel->generateExpenseNumber();
            
            // Get current user ID
            $userId = session()->get('user_id');
            
            $expenseData = [
                'expense_number' => $expenseNumber,
                'expense_date' => $this->request->getPost('expense_date'),
                'category_id' => $this->request->getPost('category_id'),
                'description' => $this->request->getPost('description'),
                'amount' => $amount,
                'payment_method' => $this->request->getPost('payment_method'),
                'vendor_name' => $this->request->getPost('vendor_name') ?: null,
                'receipt_number' => $this->request->getPost('receipt_number') ?: null,
                'notes' => $this->request->getPost('notes') ?: null,
                'recorded_by' => $userId,
                'approval_status' => 'pending',
            ];
            
            $expenseId = $this->expenseModel->insert($expenseData);
            
            if ($expenseId) {
                // Create notification for admins
                create_notification(
                    null, // All admins
                    'expense_created',
                    'New Expense Recorded',
                    "Expense {$expenseNumber} for " . format_currency($amount) . " has been recorded and is pending approval.",
                    ['expense_id' => $expenseId]
                );
                
                return redirect()->to('/expenses')->with('success', 'Expense recorded successfully! Expense Number: ' . $expenseNumber);
            }
            
            return redirect()->back()->withInput()->with('error', 'Failed to record expense. Please try again.');
        } catch (\Exception $e) {
            log_message('error', 'Expense Creation Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display expense details
     */
    public function show($id)
    {
        try {
            $expense = $this->expenseModel->getExpenseWithDetails($id);
            
            if (!$expense) {
                return redirect()->to('/expenses')->with('error', 'Expense not found.');
            }
            
            // Get audit trail
            $auditTrail = $this->auditModel->getExpenseAuditTrail($id);
            
            $data = [
                'expense' => $expense,
                'auditTrail' => $auditTrail,
            ];
            
            return view('expenses/show', $data);
        } catch (\Exception $e) {
            log_message('error', 'Expense Show Error: ' . $e->getMessage());
            return redirect()->to('/expenses')->with('error', 'Error loading expense details.');
        }
    }

    /**
     * Display form to edit expense
     */
    public function edit($id)
    {
        try {
            $expense = $this->expenseModel->find($id);
            
            if (!$expense) {
                return redirect()->to('/expenses')->with('error', 'Expense not found.');
            }
            
            // Check if user can edit (only pending expenses can be edited)
            if ($expense['approval_status'] !== 'pending') {
                return redirect()->to('/expenses')->with('error', 'Cannot edit approved or rejected expenses.');
            }
            
            $data = [
                'expense' => $expense,
                'categories' => $this->categoryModel->getActiveCategories(),
            ];
            
            return view('expenses/edit', $data);
        } catch (\Exception $e) {
            log_message('error', 'Expense Edit Error: ' . $e->getMessage());
            return redirect()->to('/expenses')->with('error', 'Error loading expense for editing.');
        }
    }

    /**
     * Update expense
     */
    public function update($id)
    {
        $expense = $this->expenseModel->find($id);
        
        if (!$expense) {
            return redirect()->to('/expenses')->with('error', 'Expense not found.');
        }
        
        // Check if user can edit
        if ($expense['approval_status'] !== 'pending') {
            return redirect()->to('/expenses')->with('error', 'Cannot edit approved or rejected expenses.');
        }
        
        // Validation rules
        $rules = [
            'expense_date' => 'required|valid_date',
            'category_id' => 'required|integer',
            'description' => 'required|max_length[500]',
            'amount' => 'required|decimal|greater_than[0]',
            'payment_method' => 'required|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            // Parse currency input
            $amount = parse_currency_input($this->request->getPost('amount'));
            
            $expenseData = [
                'expense_date' => $this->request->getPost('expense_date'),
                'category_id' => $this->request->getPost('category_id'),
                'description' => $this->request->getPost('description'),
                'amount' => $amount,
                'payment_method' => $this->request->getPost('payment_method'),
                'vendor_name' => $this->request->getPost('vendor_name') ?: null,
                'receipt_number' => $this->request->getPost('receipt_number') ?: null,
                'notes' => $this->request->getPost('notes') ?: null,
            ];
            
            if ($this->expenseModel->update($id, $expenseData)) {
                return redirect()->to('/expenses')->with('success', 'Expense updated successfully!');
            }
            
            return redirect()->back()->withInput()->with('error', 'Failed to update expense. Please try again.');
        } catch (\Exception $e) {
            log_message('error', 'Expense Update Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Delete expense (soft delete)
     */
    public function delete($id)
    {
        try {
            // Check permissions
            if (!isAdmin()) {
                return redirect()->to('/expenses')->with('error', 'You do not have permission to delete expenses.');
            }
            
            $expense = $this->expenseModel->find($id);
            
            if (!$expense) {
                return redirect()->to('/expenses')->with('error', 'Expense not found.');
            }
            
            // Soft delete
            if ($this->expenseModel->delete($id)) {
                return redirect()->to('/expenses')->with('success', 'Expense deleted successfully!');
            }
            
            return redirect()->to('/expenses')->with('error', 'Failed to delete expense. Please try again.');
        } catch (\Exception $e) {
            log_message('error', 'Expense Delete Error: ' . $e->getMessage());
            return redirect()->to('/expenses')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Approve expense
     */
    public function approve($id)
    {
        try {
            // Check permissions
            if (!isAdmin()) {
                return redirect()->to('/expenses')->with('error', 'You do not have permission to approve expenses.');
            }
            
            $expense = $this->expenseModel->find($id);
            
            if (!$expense) {
                return redirect()->to('/expenses')->with('error', 'Expense not found.');
            }
            
            if ($expense['approval_status'] !== 'pending') {
                return redirect()->to('/expenses')->with('error', 'This expense has already been processed.');
            }
            
            $userId = session()->get('user_id');
            $notes = $this->request->getPost('approval_notes');
            
            if ($this->expenseModel->approveExpense($id, $userId, $notes)) {
                // Log audit
                $this->auditModel->logAction($id, 'approved', $userId, null, ['notes' => $notes]);
                
                // Notify the user who created the expense
                create_notification(
                    $expense['recorded_by'],
                    'expense_approved',
                    'Expense Approved',
                    "Your expense {$expense['expense_number']} has been approved.",
                    ['expense_id' => $id]
                );
                
                return redirect()->to('/expenses')->with('success', 'Expense approved successfully!');
            }
            
            return redirect()->to('/expenses')->with('error', 'Failed to approve expense.');
        } catch (\Exception $e) {
            log_message('error', 'Expense Approve Error: ' . $e->getMessage());
            return redirect()->to('/expenses')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Reject expense
     */
    public function reject($id)
    {
        try {
            // Check permissions
            if (!isAdmin()) {
                return redirect()->to('/expenses')->with('error', 'You do not have permission to reject expenses.');
            }
            
            $expense = $this->expenseModel->find($id);
            
            if (!$expense) {
                return redirect()->to('/expenses')->with('error', 'Expense not found.');
            }
            
            if ($expense['approval_status'] !== 'pending') {
                return redirect()->to('/expenses')->with('error', 'This expense has already been processed.');
            }
            
            $userId = session()->get('user_id');
            $notes = $this->request->getPost('rejection_notes');
            
            if (!$notes) {
                return redirect()->back()->with('error', 'Please provide a reason for rejection.');
            }
            
            if ($this->expenseModel->rejectExpense($id, $userId, $notes)) {
                // Log audit
                $this->auditModel->logAction($id, 'rejected', $userId, null, ['notes' => $notes]);
                
                // Notify the user who created the expense
                create_notification(
                    $expense['recorded_by'],
                    'expense_rejected',
                    'Expense Rejected',
                    "Your expense {$expense['expense_number']} has been rejected. Reason: {$notes}",
                    ['expense_id' => $id]
                );
                
                return redirect()->to('/expenses')->with('success', 'Expense rejected.');
            }
            
            return redirect()->to('/expenses')->with('error', 'Failed to reject expense.');
        } catch (\Exception $e) {
            log_message('error', 'Expense Reject Error: ' . $e->getMessage());
            return redirect()->to('/expenses')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Export expenses to CSV
     */
    public function export()
    {
        try {
            $expenses = $this->expenseModel->getAllWithDetails();
            
            $filename = 'expenses_' . date('Y-m-d_His') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($output, [
                'Expense Number',
                'Date',
                'Category',
                'Description',
                'Amount (TZS)',
                'Payment Method',
                'Vendor',
                'Receipt Number',
                'Recorded By',
                'Status',
                'Created At'
            ]);
            
            // CSV data
            foreach ($expenses as $expense) {
                fputcsv($output, [
                    $expense['expense_number'],
                    $expense['expense_date'],
                    $expense['category_name'] ?? 'N/A',
                    $expense['description'],
                    $expense['amount'],
                    $expense['payment_method'],
                    $expense['vendor_name'] ?? 'N/A',
                    $expense['receipt_number'] ?? 'N/A',
                    $expense['recorded_by_name'] ?? 'N/A',
                    ucfirst($expense['approval_status']),
                    $expense['created_at']
                ]);
            }
            
            fclose($output);
            exit;
        } catch (\Exception $e) {
            log_message('error', 'Expense Export Error: ' . $e->getMessage());
            return redirect()->to('/expenses')->with('error', 'Failed to export expenses.');
        }
    }

    /**
     * Get expense analytics
     */
    public function analytics()
    {
        try {
            $year = $this->request->getGet('year') ?? date('Y');
            
            $data = [
                'stats' => $this->expenseModel->getExpenseStats(),
                'monthlyData' => $this->expenseModel->getMonthlyExpenseSummary($year),
                'categoryData' => $this->expenseModel->getExpensesByCategory($year),
                'year' => $year,
            ];
            
            return view('expenses/analytics', $data);
        } catch (\Exception $e) {
            log_message('error', 'Expense Analytics Error: ' . $e->getMessage());
            return redirect()->to('/expenses')->with('error', 'Error loading analytics.');
        }
    }
}
