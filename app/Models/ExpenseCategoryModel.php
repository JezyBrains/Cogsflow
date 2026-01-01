<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseCategoryModel extends Model
{
    protected $table = 'expense_categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'description',
        'is_active',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_active' => 'boolean',
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
        'name' => 'required|max_length[100]|is_unique[expense_categories.name,id,{id}]',
        'description' => 'permit_empty|max_length[500]',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Category name is required',
            'is_unique' => 'This category name already exists',
        ],
    ];
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
     * Get all active categories
     */
    public function getActiveCategories()
    {
        return $this->where('is_active', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get category with expense count and total
     */
    public function getCategoryStats()
    {
        return $this->select('expense_categories.*, 
                             COUNT(expenses.id) as expense_count,
                             COALESCE(SUM(expenses.amount), 0) as total_amount')
                    ->join('expenses', 'expenses.category_id = expense_categories.id', 'left')
                    ->groupBy('expense_categories.id')
                    ->orderBy('expense_categories.name', 'ASC')
                    ->findAll();
    }

    /**
     * Toggle category active status
     */
    public function toggleStatus($id)
    {
        $category = $this->find($id);
        if (!$category) {
            return false;
        }
        
        $newStatus = $category['is_active'] ? 0 : 1;
        
        // Skip validation for status toggle
        $this->skipValidation(true);
        $result = $this->update($id, ['is_active' => $newStatus]);
        $this->skipValidation(false);
        
        return $result;
    }

    /**
     * Check if category can be deleted
     */
    public function canDelete($id)
    {
        $expenseModel = new \App\Models\ExpenseModel();
        $count = $expenseModel->where('category_id', $id)->countAllResults();
        return $count === 0;
    }
}
