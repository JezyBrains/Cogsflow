<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseModel extends Model
{
    protected $table = 'expenses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'expense_number',
        'expense_date',
        'category_id',
        'description',
        'amount',
        'payment_method',
        'vendor_name',
        'receipt_number',
        'reference_type',
        'reference_id',
        'notes',
        'recorded_by',
        'approved_by',
        'approval_status',
        'approval_date',
        'approval_notes',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'amount' => 'float',
        'recorded_by' => 'int',
        'approved_by' => 'int',
        'category_id' => 'int',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'expense_number' => 'required|max_length[50]|is_unique[expenses.expense_number,id,{id}]',
        'expense_date' => 'required|valid_date',
        'category_id' => 'required|integer',
        'description' => 'required|max_length[500]',
        'amount' => 'required|decimal|greater_than[0]',
        'payment_method' => 'required|max_length[50]',
        'vendor_name' => 'permit_empty|max_length[255]',
        'receipt_number' => 'permit_empty|max_length[100]',
        'recorded_by' => 'required|integer',
    ];

    protected $validationMessages = [
        'expense_number' => [
            'required' => 'Expense number is required',
            'is_unique' => 'This expense number already exists',
        ],
        'category_id' => [
            'required' => 'Please select an expense category',
        ],
        'amount' => [
            'required' => 'Amount is required',
            'greater_than' => 'Amount must be greater than zero',
        ],
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['logAudit'];
    protected $afterInsert = ['afterInsertAudit'];
    protected $beforeUpdate = ['logAuditUpdate'];
    protected $afterUpdate = ['afterUpdateAudit'];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = ['logAuditDelete'];
    protected $afterDelete = [];

    /**
     * Generate unique expense number
     */
    public function generateExpenseNumber()
    {
        $date = date('Ymd');
        $count = $this->where('expense_number LIKE', "EXP-{$date}-%")->countAllResults() + 1;
        return sprintf('EXP-%s-%04d', $date, $count);
    }

    /**
     * Get expense with related data
     */
    public function getExpenseWithDetails($id)
    {
        return $this->select('expenses.*, 
                             expense_categories.name as category_name,
                             u1.username as recorded_by_name,
                             u1.email as recorded_by_email,
                             u2.username as approved_by_name,
                             u2.email as approved_by_email')
                    ->join('expense_categories', 'expense_categories.id = expenses.category_id', 'left')
                    ->join('users u1', 'u1.id = expenses.recorded_by', 'left')
                    ->join('users u2', 'u2.id = expenses.approved_by', 'left')
                    ->where('expenses.id', $id)
                    ->first();
    }

    /**
     * Get all expenses with details
     */
    public function getAllWithDetails()
    {
        return $this->select('expenses.*, 
                             expense_categories.name as category_name,
                             users.username as recorded_by_name')
                    ->join('expense_categories', 'expense_categories.id = expenses.category_id', 'left')
                    ->join('users', 'users.id = expenses.recorded_by', 'left')
                    ->orderBy('expenses.expense_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get expense statistics
     */
    public function getExpenseStats()
    {
        $stats = [];
        
        // Total expenses (all time)
        $stats['total_expenses'] = $this->countAllResults(false);
        
        // Total amount (all time)
        $result = $this->selectSum('amount')->first();
        $stats['total_amount'] = $result['amount'] ?? 0;
        
        // This month's expenses
        $stats['this_month_expenses'] = $this->where('MONTH(expense_date)', date('m'))
                                           ->where('YEAR(expense_date)', date('Y'))
                                           ->countAllResults(false);
        
        // This month's amount
        $result = $this->selectSum('amount')
                      ->where('MONTH(expense_date)', date('m'))
                      ->where('YEAR(expense_date)', date('Y'))
                      ->first();
        $stats['this_month_amount'] = $result['amount'] ?? 0;
        
        // This year's expenses
        $stats['this_year_expenses'] = $this->where('YEAR(expense_date)', date('Y'))
                                          ->countAllResults(false);
        
        // This year's amount
        $result = $this->selectSum('amount')
                      ->where('YEAR(expense_date)', date('Y'))
                      ->first();
        $stats['this_year_amount'] = $result['amount'] ?? 0;
        
        // Pending approval count
        $stats['pending_approval'] = $this->where('approval_status', 'pending')
                                        ->countAllResults(false);
        
        return $stats;
    }

    /**
     * Get expenses by category
     */
    public function getExpensesByCategory($year = null, $month = null)
    {
        $builder = $this->select('expense_categories.name as category, 
                                 SUM(expenses.amount) as total_amount, 
                                 COUNT(expenses.id) as count')
                       ->join('expense_categories', 'expense_categories.id = expenses.category_id', 'left')
                       ->groupBy('expenses.category_id');
        
        if ($year) {
            $builder->where('YEAR(expenses.expense_date)', $year);
        }
        if ($month) {
            $builder->where('MONTH(expenses.expense_date)', $month);
        }
        
        return $builder->orderBy('total_amount', 'DESC')->findAll();
    }

    /**
     * Get expenses by date range
     */
    public function getExpensesByDateRange($startDate, $endDate)
    {
        return $this->select('expenses.*, 
                             expense_categories.name as category_name,
                             users.username as recorded_by_name')
                    ->join('expense_categories', 'expense_categories.id = expenses.category_id', 'left')
                    ->join('users', 'users.id = expenses.recorded_by', 'left')
                    ->where('expenses.expense_date >=', $startDate)
                    ->where('expenses.expense_date <=', $endDate)
                    ->orderBy('expenses.expense_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get monthly expense summary
     */
    public function getMonthlyExpenseSummary($year = null)
    {
        $year = $year ?? date('Y');
        
        return $this->select('MONTH(expense_date) as month, 
                             SUM(amount) as total_amount, 
                             COUNT(id) as count')
                    ->where('YEAR(expense_date)', $year)
                    ->groupBy('MONTH(expense_date)')
                    ->orderBy('month', 'ASC')
                    ->findAll();
    }

    /**
     * Get expenses by user
     */
    public function getExpensesByUser($userId, $limit = null)
    {
        $builder = $this->select('expenses.*, expense_categories.name as category_name')
                       ->join('expense_categories', 'expense_categories.id = expenses.category_id', 'left')
                       ->where('expenses.recorded_by', $userId)
                       ->orderBy('expenses.expense_date', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }

    /**
     * Get pending approval expenses
     */
    public function getPendingApproval()
    {
        return $this->select('expenses.*, 
                             expense_categories.name as category_name,
                             users.username as recorded_by_name')
                    ->join('expense_categories', 'expense_categories.id = expenses.category_id', 'left')
                    ->join('users', 'users.id = expenses.recorded_by', 'left')
                    ->where('expenses.approval_status', 'pending')
                    ->orderBy('expenses.created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Approve expense
     */
    public function approveExpense($id, $userId, $notes = null)
    {
        $data = [
            'approval_status' => 'approved',
            'approved_by' => $userId,
            'approval_date' => date('Y-m-d H:i:s'),
            'approval_notes' => $notes,
        ];
        
        return $this->update($id, $data);
    }

    /**
     * Reject expense
     */
    public function rejectExpense($id, $userId, $notes)
    {
        $data = [
            'approval_status' => 'rejected',
            'approved_by' => $userId,
            'approval_date' => date('Y-m-d H:i:s'),
            'approval_notes' => $notes,
        ];
        
        return $this->update($id, $data);
    }

    /**
     * Search expenses
     */
    public function searchExpenses($keyword, $categoryId = null, $startDate = null, $endDate = null)
    {
        $builder = $this->select('expenses.*, 
                                 expense_categories.name as category_name,
                                 users.username as recorded_by_name')
                       ->join('expense_categories', 'expense_categories.id = expenses.category_id', 'left')
                       ->join('users', 'users.id = expenses.recorded_by', 'left');
        
        if ($keyword) {
            $builder->groupStart()
                   ->like('expenses.expense_number', $keyword)
                   ->orLike('expenses.description', $keyword)
                   ->orLike('expenses.vendor_name', $keyword)
                   ->orLike('expenses.receipt_number', $keyword)
                   ->groupEnd();
        }
        
        if ($categoryId) {
            $builder->where('expenses.category_id', $categoryId);
        }
        
        if ($startDate) {
            $builder->where('expenses.expense_date >=', $startDate);
        }
        
        if ($endDate) {
            $builder->where('expenses.expense_date <=', $endDate);
        }
        
        return $builder->orderBy('expenses.expense_date', 'DESC')->findAll();
    }

    // Audit Callbacks
    protected function logAudit(array $data)
    {
        $data['_audit_action'] = 'created';
        return $data;
    }

    protected function afterInsertAudit(array $data)
    {
        if (isset($data['id'])) {
            $auditModel = new ExpenseAuditLogModel();
            $userId = session()->get('user_id') ?? $data['data']['recorded_by'];
            $auditModel->logAction($data['id'], 'created', $userId, null, $data['data']);
        }
        return $data;
    }

    protected function logAuditUpdate(array $data)
    {
        if (isset($data['id'])) {
            $oldData = $this->find($data['id'][0]);
            $data['_old_data'] = $oldData;
        }
        return $data;
    }

    protected function afterUpdateAudit(array $data)
    {
        if (isset($data['id']) && isset($data['_old_data'])) {
            $auditModel = new ExpenseAuditLogModel();
            $userId = session()->get('user_id');
            $auditModel->logAction($data['id'][0], 'updated', $userId, $data['_old_data'], $data['data']);
        }
        return $data;
    }

    protected function logAuditDelete(array $data)
    {
        if (isset($data['id'])) {
            $oldData = $this->find($data['id'][0]);
            $auditModel = new ExpenseAuditLogModel();
            $userId = session()->get('user_id');
            $auditModel->logAction($data['id'][0], 'deleted', $userId, $oldData, null);
        }
        return $data;
    }
}
