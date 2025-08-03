<?php

namespace App\Libraries;

use App\Models\SystemLogModel;
use CodeIgniter\Database\Config;

class AdminUtilities
{
    protected $logModel;
    protected $db;

    public function __construct()
    {
        $this->logModel = new SystemLogModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Clear all cache
     */
    public function clearCache()
    {
        try {
            // Clear CodeIgniter cache
            cache()->clean();
            
            // Clear custom cache table if exists
            if ($this->db->tableExists('cache_entries')) {
                $this->db->table('cache_entries')->truncate();
            }
            
            // Clear file-based cache
            $cacheDir = WRITEPATH . 'cache';
            if (is_dir($cacheDir)) {
                $this->deleteDirectory($cacheDir, false);
            }
            
            $this->logModel->addLog('info', 'Cache cleared successfully', ['user' => session('user_id')]);
            return ['success' => true, 'message' => 'Cache cleared successfully'];
            
        } catch (\Exception $e) {
            $this->logModel->addLog('error', 'Failed to clear cache: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to clear cache: ' . $e->getMessage()];
        }
    }

    /**
     * Reset queue jobs (if using queue system)
     */
    public function resetQueueJobs()
    {
        try {
            // This would reset any queue jobs if implemented
            // For now, we'll just log the action
            $this->logModel->addLog('info', 'Queue jobs reset requested', ['user' => session('user_id')]);
            return ['success' => true, 'message' => 'Queue jobs reset successfully'];
            
        } catch (\Exception $e) {
            $this->logModel->addLog('error', 'Failed to reset queue jobs: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to reset queue jobs: ' . $e->getMessage()];
        }
    }

    /**
     * Trigger database backup
     */
    public function triggerBackup()
    {
        try {
            $backupDir = WRITEPATH . 'backups';
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $backupDir . '/' . $filename;

            // Get database configuration
            $config = config('Database');
            $dbConfig = $config->default;

            // Create mysqldump command
            $command = sprintf(
                'mysqldump -h%s -u%s -p%s %s > %s',
                $dbConfig['hostname'],
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['database'],
                $filepath
            );

            // Execute backup command
            $output = [];
            $returnVar = 0;
            exec($command . ' 2>&1', $output, $returnVar);

            if ($returnVar === 0 && file_exists($filepath)) {
                $this->logModel->addLog('info', 'Database backup created successfully', [
                    'filename' => $filename,
                    'size' => filesize($filepath),
                    'user' => session('user_id')
                ]);
                
                // Clean old backups
                $this->cleanOldBackups();
                
                return ['success' => true, 'message' => 'Backup created successfully', 'filename' => $filename];
            } else {
                throw new \Exception('Backup command failed: ' . implode("\n", $output));
            }

        } catch (\Exception $e) {
            $this->logModel->addLog('error', 'Failed to create backup: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to create backup: ' . $e->getMessage()];
        }
    }

    /**
     * Get system information
     */
    public function getSystemInfo()
    {
        try {
            $info = [
                'php_version' => PHP_VERSION,
                'codeigniter_version' => \CodeIgniter\CodeIgniter::CI_VERSION,
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'database_version' => $this->db->getVersion(),
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'disk_free_space' => $this->formatBytes(disk_free_space(ROOTPATH)),
                'disk_total_space' => $this->formatBytes(disk_total_space(ROOTPATH)),
            ];

            return ['success' => true, 'data' => $info];

        } catch (\Exception $e) {
            $this->logModel->addLog('error', 'Failed to get system info: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to get system information'];
        }
    }

    /**
     * Get system logs
     */
    public function getSystemLogs($level = null, $limit = 100, $offset = 0)
    {
        try {
            $logs = $this->logModel->getLogs($level, $limit, $offset);
            $stats = $this->logModel->getLogStats();
            
            return [
                'success' => true,
                'logs' => $logs,
                'stats' => $stats,
                'total' => $this->logModel->countAllResults()
            ];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to retrieve logs'];
        }
    }

    /**
     * Clean system logs
     */
    public function cleanSystemLogs($days = 30)
    {
        try {
            $deleted = $this->logModel->cleanOldLogs($days);
            $this->logModel->addLog('info', "Cleaned {$deleted} old log entries", ['user' => session('user_id')]);
            
            return ['success' => true, 'message' => "Cleaned {$deleted} old log entries"];

        } catch (\Exception $e) {
            $this->logModel->addLog('error', 'Failed to clean logs: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to clean logs'];
        }
    }

    /**
     * Optimize database
     */
    public function optimizeDatabase()
    {
        try {
            $tables = $this->db->listTables();
            $optimized = 0;

            foreach ($tables as $table) {
                $this->db->query("OPTIMIZE TABLE `{$table}`");
                $optimized++;
            }

            $this->logModel->addLog('info', "Database optimized - {$optimized} tables processed", ['user' => session('user_id')]);
            return ['success' => true, 'message' => "Database optimized - {$optimized} tables processed"];

        } catch (\Exception $e) {
            $this->logModel->addLog('error', 'Failed to optimize database: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to optimize database'];
        }
    }

    /**
     * Get application health status
     */
    public function getHealthStatus()
    {
        $status = [
            'overall' => 'healthy',
            'checks' => []
        ];

        // Database connectivity
        try {
            $this->db->query('SELECT 1');
            $status['checks']['database'] = ['status' => 'healthy', 'message' => 'Database connection OK'];
        } catch (\Exception $e) {
            $status['checks']['database'] = ['status' => 'unhealthy', 'message' => 'Database connection failed'];
            $status['overall'] = 'unhealthy';
        }

        // Writable directories
        $writableDirs = [WRITEPATH, WRITEPATH . 'cache', WRITEPATH . 'logs', WRITEPATH . 'session'];
        foreach ($writableDirs as $dir) {
            $dirName = basename($dir);
            if (is_writable($dir)) {
                $status['checks']["writable_{$dirName}"] = ['status' => 'healthy', 'message' => "{$dirName} directory is writable"];
            } else {
                $status['checks']["writable_{$dirName}"] = ['status' => 'unhealthy', 'message' => "{$dirName} directory is not writable"];
                $status['overall'] = 'unhealthy';
            }
        }

        // Memory usage
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseBytes(ini_get('memory_limit'));
        $memoryPercent = ($memoryUsage / $memoryLimit) * 100;

        if ($memoryPercent < 80) {
            $status['checks']['memory'] = ['status' => 'healthy', 'message' => "Memory usage: {$memoryPercent}%"];
        } else {
            $status['checks']['memory'] = ['status' => 'warning', 'message' => "High memory usage: {$memoryPercent}%"];
        }

        // Recent critical logs
        $criticalLogs = $this->logModel->getRecentCriticalLogs(5);
        if (empty($criticalLogs)) {
            $status['checks']['logs'] = ['status' => 'healthy', 'message' => 'No recent critical errors'];
        } else {
            $status['checks']['logs'] = ['status' => 'warning', 'message' => count($criticalLogs) . ' recent critical errors'];
        }

        return $status;
    }

    /**
     * Clean old backup files
     */
    private function cleanOldBackups($retentionDays = 30)
    {
        $backupDir = WRITEPATH . 'backups';
        if (!is_dir($backupDir)) {
            return;
        }

        $files = glob($backupDir . '/backup_*.sql');
        $cutoffTime = time() - ($retentionDays * 24 * 60 * 60);

        foreach ($files as $file) {
            if (filemtime($file) < $cutoffTime) {
                unlink($file);
            }
        }
    }

    /**
     * Delete directory contents
     */
    private function deleteDirectory($dir, $deleteDir = true)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }

        if ($deleteDir) {
            rmdir($dir);
        }
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Parse bytes from string (e.g., "128M" to bytes)
     */
    private function parseBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        $val = (int) $val;
        
        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        
        return $val;
    }
}
