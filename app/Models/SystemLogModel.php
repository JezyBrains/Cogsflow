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
     * Get logs with pagination and filtering
     */
    public function getLogs($level = null, $limit = 100, $offset = 0)
    {
        $builder = $this->builder();
        
        if ($level) {
            $builder->where('level', $level);
        }
        
        return $builder->orderBy('created_at', 'DESC')
                      ->limit($limit, $offset)
                      ->get()
                      ->getResultArray();
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
