<?php

namespace App\Models;

use CodeIgniter\Model;

class SystemLogModel extends Model
{
    protected $table = 'system_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['level', 'message', 'context'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = false;

    // Validation
    protected $validationRules = [
        'level' => 'required|max_length[20]',
        'message' => 'required'
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Log levels
     */
    const LEVEL_DEBUG = 'debug';
    const LEVEL_INFO = 'info';
    const LEVEL_WARNING = 'warning';
    const LEVEL_ERROR = 'error';
    const LEVEL_CRITICAL = 'critical';

    /**
     * Add a log entry
     */
    public function addLog($level, $message, $context = null)
    {
        return $this->insert([
            'level' => $level,
            'message' => $message,
            'context' => $context ? json_encode($context) : null
        ]);
    }

    /**
     * Log an admin action
     */
    public function logAction($action, $level, $message, $context = null)
    {
        $logMessage = "Admin action '{$action}': {$message}";
        return $this->addLog($level, $logMessage, $context);
    }

    /**
     * Get logs with pagination and filtering
     */
    public function getLogs($level = null, $limit = 100, $offset = 0, $search = '')
    {
        $builder = $this->builder();
        
        if ($level && $level !== 'all') {
            $builder->where('level', $level);
        }
        
        if (!empty($search)) {
            $builder->like('message', $search);
        }
        
        return $builder->orderBy('created_at', 'DESC')
                      ->limit($limit, $offset)
                      ->get()
                      ->getResultArray();
    }

    /**
     * Get total count of logs with filtering
     */
    public function getLogsCount($level = null, $search = '')
    {
        $builder = $this->builder();
        
        if ($level && $level !== 'all') {
            $builder->where('level', $level);
        }
        
        if (!empty($search)) {
            $builder->like('message', $search);
        }
        
        return $builder->countAllResults();
    }

    /**
     * Get log statistics
     */
    public function getLogStats($days = 7)
    {
        $builder = $this->builder();
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        return $builder->select('level, COUNT(*) as count')
                      ->where('created_at >=', $date)
                      ->groupBy('level')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Clean old logs
     */
    public function cleanOldLogs($days = 30)
    {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->where('created_at <', $date)->delete();
    }

    /**
     * Get recent critical/error logs
     */
    public function getRecentCriticalLogs($limit = 10)
    {
        return $this->whereIn('level', ['error', 'critical'])
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}
