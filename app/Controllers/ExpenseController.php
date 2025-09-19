<?php

namespace App\Controllers;

class ExpenseController extends BaseController
{
    /**
     * Display list of all expenses
     * 
     * @return string
     */
    public function index()
    {
        try {
            $expenseModel = new \App\Models\ExpenseModel();
            
            // Get expenses from database
            $builder = $expenseModel->db->table('expenses');
            $builder->orderBy('expense_date', 'DESC');
            $expenses = $builder->get()->getResultArray();
            
            // Get expense statistics
            $stats = $expenseModel->getExpenseStats();
            
            $data = [
                'expenses' => $expenses ?? [],
                'stats' => $stats ?? []
            ];
            
            return view('expenses/index', $data);
        } catch (\Exception $e) {
            log_message('error', 'Expense Index Error: ' . $e->getMessage());
            
            // Return view with empty data if there's an error
            $data = [
                'expenses' => [],
                'stats' => []
            ];
            
            return view('expenses/index', $data);
        }
    }
    
    /**
     * Display form to create a new expense
     * 
     * @return string
     */
    public function new()
    {
        return view('expenses/create');
    }
    
    /**
     * Process the expense creation form
     * Records cost details
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function log()
    {
        // Validation rules
        $rules = [
            'expense_date' => 'required|valid_date',
            'category' => 'required|max_length[100]',
            'description' => 'required|max_length[255]',
            'amount' => 'required|decimal|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $expenseModel = new \App\Models\ExpenseModel();
            
            // Generate expense number
            $expenseNumber = 'EXP-' . date('Ymd') . '-' . rand(100, 999);
            
            $expenseData = [
                'expense_number' => $expenseNumber,
                'expense_date' => $this->request->getPost('expense_date'),
                'category' => $this->request->getPost('category'),
                'description' => $this->request->getPost('description'),
                'amount' => (float)$this->request->getPost('amount'),
                'receipt_number' => $this->request->getPost('receipt_reference') ?: null,
                'vendor_name' => $this->request->getPost('vendor_supplier') ?: null,
                'reference_type' => 'general',
                'reference_id' => null,
                'notes' => $this->request->getPost('notes') ?: null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Debug: Log the data being inserted
            log_message('debug', 'Expense Data: ' . json_encode($expenseData));
            
            // Use query builder instead of model to avoid validation issues
            $builder = $expenseModel->db->table('expenses');
            $result = $builder->insert($expenseData);
            
            // Debug: Log the result
            log_message('debug', 'Insert Result: ' . ($result ? 'true' : 'false'));
            log_message('debug', 'Last Query: ' . $expenseModel->db->getLastQuery());
            
            if ($result) {
                return redirect()->to('/expenses')->with('success', 'Expense logged successfully! Expense Number: ' . $expenseNumber);
            } else {
                $error = $expenseModel->db->error();
                log_message('error', 'Database Error: ' . json_encode($error));
                return redirect()->back()->withInput()->with('error', 'Database error: ' . ($error['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            log_message('error', 'Expense Creation Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        }
    }

    /**
     * Display a specific expense
     * 
     * @param int $id
     * @return string
     */
    public function show($id)
    {
        try {
            $expenseModel = new \App\Models\ExpenseModel();
            
            // Get expense from database
            $builder = $expenseModel->db->table('expenses');
            $expense = $builder->where('id', $id)->get()->getRowArray();
            
            if (!$expense) {
                return redirect()->to('/expenses')->with('error', 'Expense not found.');
            }
            
            $data = ['expense' => $expense];
            return view('expenses/show', $data);
        } catch (\Exception $e) {
            log_message('error', 'Expense Show Error: ' . $e->getMessage());
            return redirect()->to('/expenses')->with('error', 'Error loading expense details.');
        }
    }

    /**
     * Display form to edit an expense
     * 
     * @param int $id
     * @return string
     */
    public function edit($id)
    {
        try {
            $expenseModel = new \App\Models\ExpenseModel();
            
            // Get expense from database
            $builder = $expenseModel->db->table('expenses');
            $expense = $builder->where('id', $id)->get()->getRowArray();
            
            if (!$expense) {
                return redirect()->to('/expenses')->with('error', 'Expense not found.');
            }
            
            $data = ['expense' => $expense];
            return view('expenses/edit', $data);
        } catch (\Exception $e) {
            log_message('error', 'Expense Edit Error: ' . $e->getMessage());
            return redirect()->to('/expenses')->with('error', 'Error loading expense for editing.');
        }
    }

    /**
     * Update an expense
     * 
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($id)
    {
        // Validation rules
        $rules = [
            'expense_date' => 'required|valid_date',
            'category' => 'required|max_length[100]',
            'description' => 'required|max_length[255]',
            'amount' => 'required|decimal|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $expenseModel = new \App\Models\ExpenseModel();
            
            $expenseData = [
                'expense_date' => $this->request->getPost('expense_date'),
                'category' => $this->request->getPost('category'),
                'description' => $this->request->getPost('description'),
                'amount' => (float)$this->request->getPost('amount'),
                'receipt_number' => $this->request->getPost('receipt_reference'),
                'vendor_name' => $this->request->getPost('vendor_supplier')
            ];

            // Update expense using query builder
            $builder = $expenseModel->db->table('expenses');
            $result = $builder->where('id', $id)->update($expenseData);
            
            if ($result) {
                return redirect()->to('/expenses')->with('success', 'Expense updated successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to update expense. Please try again.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Expense Update Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        }
    }

    /**
     * Delete an expense
     * 
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id)
    {
        try {
            $expenseModel = new \App\Models\ExpenseModel();
            
            // Check if expense exists
            $builder = $expenseModel->db->table('expenses');
            $expense = $builder->where('id', $id)->get()->getRowArray();
            
            if (!$expense) {
                return redirect()->to('/expenses')->with('error', 'Expense not found.');
            }
            
            // Delete expense
            $result = $builder->where('id', $id)->delete();
            
            if ($result) {
                return redirect()->to('/expenses')->with('success', 'Expense deleted successfully!');
            } else {
                return redirect()->to('/expenses')->with('error', 'Failed to delete expense. Please try again.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Expense Delete Error: ' . $e->getMessage());
            return redirect()->to('/expenses')->with('error', 'Database error: ' . $e->getMessage());
        }
    }
}
