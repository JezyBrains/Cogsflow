<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseAuditLogModel extends Model
{
    protected $table = 'expense_audit_log';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'expense_id',
        'action',
        'user_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'expense_id' => 'required|integer',
        'action' => 'required|max_length[50]',
        'user_id' => 'required|integer',
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
     * Log an expense action
     * 
     * @param int $expenseId
     * @param string $action (created, updated, deleted, approved, rejected)
     * @param int $userId
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return bool|int
     */
    public function logAction($expenseId, $action, $userId, $oldValues = null, $newValues = null)
    {
        $request = \Config\Services::request();
        
        $data = [
            'expense_id' => $expenseId,
            'action' => $action,
            'user_id' => $userId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
        ];
        
        return $this->insert($data);
    }

    /**
     * Get audit trail for an expense
     * 
     * @param int $expenseId
     * @return array
     */
    public function getExpenseAuditTrail($expenseId)
    {
        return $this->select('expense_audit_log.*, users.username, users.email')
                    ->join('users', 'users.id = expense_audit_log.user_id', 'left')
                    ->where('expense_id', $expenseId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get recent audit logs
     * 
     * @param int $limit
     * @return array
     */
    public function getRecentLogs($limit = 50)
    {
        return $this->select('expense_audit_log.*, 
                             users.username, 
                             users.email,
                             expenses.expense_number,
                             expenses.description')
                    ->join('users', 'users.id = expense_audit_log.user_id', 'left')
                    ->join('expenses', 'expenses.id = expense_audit_log.expense_id', 'left')
                    ->orderBy('expense_audit_log.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get audit logs by user
     * 
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getUserLogs($userId, $limit = 50)
    {
        return $this->select('expense_audit_log.*, 
                             expenses.expense_number,
                             expenses.description')
                    ->join('expenses', 'expenses.id = expense_audit_log.expense_id', 'left')
                    ->where('expense_audit_log.user_id', $userId)
                    ->orderBy('expense_audit_log.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get audit statistics
     * 
     * @return array
     */
    public function getAuditStats()
    {
        $stats = [];
        
        // Total audit logs
        $stats['total_logs'] = $this->countAllResults(false);
        
        // Logs by action
        $actionStats = $this->select('action, COUNT(*) as count')
                            ->groupBy('action')
                            ->findAll();
        
        $stats['by_action'] = [];
        foreach ($actionStats as $stat) {
            $stats['by_action'][$stat['action']] = $stat['count'];
        }
        
        // Recent activity (last 7 days)
        $stats['recent_activity'] = $this->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
                                         ->countAllResults();
        
        return $stats;
    }
}
