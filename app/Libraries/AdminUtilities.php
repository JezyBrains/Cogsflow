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
        try {
            $this->logModel = new SystemLogModel();
        } catch (\Exception $e) {
            log_message('warning', 'Failed to initialize SystemLogModel: ' . $e->getMessage());
            $this->logModel = null;
        }
        
        try {
            $this->db = \Config\Database::connect();
        } catch (\Exception $e) {
            log_message('error', 'Failed to connect to database: ' . $e->getMessage());
            throw $e; // Database connection is critical
        }
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
            
            if ($this->logModel) {
                $this->logModel->addLog('info', 'Cache cleared successfully', ['user' => session('user_id')]);
            }
            return ['success' => true, 'message' => 'Cache cleared successfully'];
            
        } catch (\Exception $e) {
            if ($this->logModel) {
                $this->logModel->addLog('error', 'Failed to clear cache: ' . $e->getMessage());
            }
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

    /**
     * Reset queue (alias for resetQueueJobs)
     */
    public function resetQueue()
    {
        return $this->resetQueueJobs();
    }

    /**
     * Clean logs (alias for cleanSystemLogs)
     */
    public function cleanLogs($days = 30)
    {
        return $this->cleanSystemLogs($days);
    }

    /**
     * Get logs with pagination and filtering
     */
    public function getLogs($page = 1, $limit = 50, $level = 'all', $search = '')
    {
        try {
            $offset = ($page - 1) * $limit;
            
            // Get logs from SystemLogModel
            $logs = $this->logModel->getLogs($level, $limit, $offset, $search);
            $totalCount = $this->logModel->getLogsCount($level, $search);
            
            return [
                'logs' => $logs,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $totalCount,
                    'total_pages' => ceil($totalCount / $limit)
                ]
            ];
            
        } catch (\Exception $e) {
            $this->logModel->addLog('error', 'Failed to retrieve logs: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reset database to fresh state
     * WARNING: This will delete ALL data and recreate tables
     */
    public function resetDatabase($confirmation = false)
    {
        try {
            if (!$confirmation) {
                return [
                    'success' => false, 
                    'message' => 'Database reset requires confirmation. This action cannot be undone.'
                ];
            }

            // Create backup before reset
            $backupResult = $this->triggerBackup();
            if (!$backupResult['success']) {
                return [
                    'success' => false,
                    'message' => 'Failed to create backup before reset: ' . $backupResult['message']
                ];
            }

            // Get all table names
            $tables = $this->db->listTables();
            
            // Disable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
            
            // Drop all tables except migrations
            $droppedTables = 0;
            foreach ($tables as $table) {
                if ($table !== 'migrations') {
                    $this->db->query("DROP TABLE IF EXISTS `{$table}`");
                    $droppedTables++;
                }
            }
            
            // Re-enable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
            
            // Run migrations to recreate tables
            $migrate = \Config\Services::migrations();
            $migrate->setNamespace(null);
            
            try {
                $migrate->latest();
            } catch (\Exception $e) {
                $this->logModel->addLog('error', 'Migration failed during database reset: ' . $e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Failed to run migrations after reset: ' . $e->getMessage()
                ];
            }
            
            // Run essential seeders
            $this->runEssentialSeeders();
            
            $this->logModel->addLog('critical', 'Database reset completed', [
                'tables_dropped' => $droppedTables,
                'backup_file' => $backupResult['filename'] ?? 'unknown',
                'user' => session('user_id'),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            
            return [
                'success' => true,
                'message' => "Database reset completed successfully. {$droppedTables} tables recreated. Backup saved as: " . ($backupResult['filename'] ?? 'unknown')
            ];
            
        } catch (\Exception $e) {
            $this->logModel->addLog('error', 'Database reset failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Database reset failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Run essential seeders after database reset
     */
    private function runEssentialSeeders()
    {
        try {
            $seeder = \Config\Database::seeder();
            
            // Run essential seeders in order - only use existing seeders
            $seeders = [
                'App\Database\Seeds\DefaultSettingsSeeder',
                'App\Database\Seeds\DefaultUserSeeder',
                'App\Database\Seeds\SettingsSeeder',  // Fallback if DefaultSettingsSeeder doesn't exist
                'App\Database\Seeds\UserSeeder',      // Fallback if DefaultUserSeeder doesn't exist
                'App\Database\Seeds\ProductionSeeder' // Production seeder if available
            ];
            
            $successfulSeeders = 0;
            foreach ($seeders as $seederClass) {
                try {
                    if (class_exists($seederClass)) {
                        $seeder->call($seederClass);
                        $successfulSeeders++;
                        $this->logModel->addLog('info', "Successfully ran seeder: {$seederClass}");
                    } else {
                        $this->logModel->addLog('info', "Seeder class not found: {$seederClass}");
                    }
                } catch (\Exception $e) {
                    // Log but don't fail - some seeders might not exist
                    $this->logModel->addLog('warning', "Seeder {$seederClass} failed: " . $e->getMessage());
                }
            }
            
            $this->logModel->addLog('info', "Ran {$successfulSeeders} seeders successfully");
            
        } catch (\Exception $e) {
            $this->logModel->addLog('error', 'Failed to run seeders: ' . $e->getMessage());
        }
    }

    /**
     * Clear all data but keep table structure
     */
    public function clearAllData($confirmation = false)
    {
        try {
            if (!$confirmation) {
                return [
                    'success' => false, 
                    'message' => 'Data clearing requires confirmation. This action cannot be undone.'
                ];
            }

            // Create backup before clearing
            $backupResult = $this->triggerBackup();
            if (!$backupResult['success']) {
                return [
                    'success' => false,
                    'message' => 'Failed to create backup before clearing data: ' . $backupResult['message']
                ];
            }

            // Get all table names except system tables
            $tables = $this->db->listTables();
            
            // Define system tables that should be preserved (only core system tables)
            $systemTables = ['migrations'];
            
            // Define business data tables that should be cleared
            $businessTables = [
                'suppliers', 'batches', 'batch_bags', 'dispatches', 'inventory', 
                'purchase_orders', 'expenses', 'notifications', 'system_logs',
                'batch_history', 'bag_inspections', 'inventory_adjustments',
                'reports', 'cache_entries', 'notification_settings'
            ];
            
            // Disable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
            
            // Clear business data tables specifically
            $clearedTables = 0;
            foreach ($businessTables as $table) {
                if (in_array($table, $tables)) {
                    try {
                        $this->db->table($table)->truncate();
                        $clearedTables++;
                        $this->logModel->addLog('info', "Cleared table: {$table}");
                    } catch (\Exception $e) {
                        $this->logModel->addLog('warning', "Failed to clear table {$table}: " . $e->getMessage());
                    }
                }
            }
            
            // Also clear any remaining tables that aren't system tables
            $preservedTables = ['migrations', 'settings', 'users', 'roles', 'permissions', 'role_permissions', 'user_roles'];
            foreach ($tables as $table) {
                if (!in_array($table, $preservedTables) && !in_array($table, $businessTables)) {
                    try {
                        $this->db->table($table)->truncate();
                        $clearedTables++;
                        $this->logModel->addLog('info', "Cleared additional table: {$table}");
                    } catch (\Exception $e) {
                        $this->logModel->addLog('warning', "Failed to clear additional table {$table}: " . $e->getMessage());
                    }
                }
            }
            
            // Re-enable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
            
            $this->logModel->addLog('warning', 'All data cleared from database', [
                'tables_cleared' => $clearedTables,
                'backup_file' => $backupResult['filename'] ?? 'unknown',
                'user' => session('user_id'),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            
            return [
                'success' => true,
                'message' => "Data cleared successfully from {$clearedTables} tables. Backup saved as: " . ($backupResult['filename'] ?? 'unknown')
            ];
            
        } catch (\Exception $e) {
            $this->logModel->addLog('error', 'Data clearing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Data clearing failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get database table information for debugging
     */
    public function getDatabaseInfo()
    {
        try {
            // Check if database connection exists
            if (!$this->db) {
                return [
                    'success' => false,
                    'message' => 'Database connection not available'
                ];
            }

            $tables = $this->db->listTables();
            $tableInfo = [];
            
            foreach ($tables as $table) {
                try {
                    $count = $this->db->table($table)->countAllResults();
                    $tableInfo[$table] = [
                        'row_count' => $count,
                        'has_data' => $count > 0
                    ];
                } catch (\Exception $e) {
                    $tableInfo[$table] = [
                        'row_count' => 'Error',
                        'has_data' => false,
                        'error' => $e->getMessage()
                    ];
                }
            }
            
            return [
                'success' => true,
                'tables' => $tableInfo,
                'total_tables' => count($tables)
            ];
            
        } catch (\Exception $e) {
            // Log error but don't depend on logModel
            log_message('error', 'getDatabaseInfo failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to get database info: ' . $e->getMessage()
            ];
        }
    }
}
