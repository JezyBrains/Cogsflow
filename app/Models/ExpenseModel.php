<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseModel extends Model
{
    protected $table = 'expenses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'expense_number',
        'expense_date',
        'category',
        'description',
        'amount',
        'payment_method',
        'vendor_supplier',
        'receipt_reference',
        'recorded_by',
        'approved_by'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'expense_number' => 'required|max_length[50]|is_unique[expenses.expense_number]',
        'expense_date' => 'required|valid_date',
        'category' => 'required|max_length[100]',
        'description' => 'required|max_length[500]',
        'amount' => 'required|decimal|greater_than[0]',
        'payment_method' => 'required|max_length[50]',
        'vendor_supplier' => 'permit_empty|max_length[255]',
        'receipt_reference' => 'permit_empty|max_length[100]',
        'recorded_by' => 'required|max_length[100]',
        'approved_by' => 'permit_empty|max_length[100]'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get expense statistics
     */
    public function getExpenseStats()
    {
        $stats = [];
        
        // Total expenses
        $stats['total_expenses'] = $this->countAllResults();
        
        // Total amount
        $result = $this->selectSum('amount')->first();
        $stats['total_amount'] = $result['amount'] ?? 0;
        
        // This month's expenses
        $stats['this_month_expenses'] = $this->where('DATE_FORMAT(expense_date, "%Y-%m")', date('Y-m'))
                                           ->countAllResults();
        
        // This month's amount
        $result = $this->selectSum('amount')
                      ->where('DATE_FORMAT(expense_date, "%Y-%m")', date('Y-m'))
                      ->first();
        $stats['this_month_amount'] = $result['amount'] ?? 0;
        
        return $stats;
    }

    /**
     * Get expenses by category
     */
    public function getExpensesByCategory()
    {
        return $this->select('category, SUM(amount) as total_amount, COUNT(*) as count')
                   ->groupBy('category')
                   ->orderBy('total_amount', 'DESC')
                   ->findAll();
    }
}
