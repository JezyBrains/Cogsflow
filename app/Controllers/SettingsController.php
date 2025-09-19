<?php

namespace App\Controllers;

use App\Models\SettingsModel;
use App\Models\SystemLogModel;
use App\Libraries\AdminUtilities;

class SettingsController extends BaseController
{
    protected $settingsModel;
    protected $systemLogModel;
    protected $adminUtilities;

    public function __construct()
    {
        try {
            $this->settingsModel = new SettingsModel();
            $this->systemLogModel = new SystemLogModel();
            $this->adminUtilities = new AdminUtilities();
        } catch (\Exception $e) {
            log_message('error', 'SettingsController initialization failed: ' . $e->getMessage());
        }
    }

    public function index()
    {
        try {
            // Test if SettingsModel exists and can be instantiated
            if (!$this->settingsModel) {
                return 'SettingsModel is null';
            }
            
            // Test basic model functionality
            $db = $this->settingsModel->db;
            if (!$db) {
                return 'Database connection in model is null';
            }
            
            // Initialize default settings if not exists
            $this->settingsModel->initializeDefaults();

            // Get all settings grouped by category
            $settings = $this->settingsModel->getAllSettingsGrouped();

            // Get system health status
            $healthStatus = $this->adminUtilities->getHealthStatus();

            $data = [
                'settings' => $settings,
                'healthStatus' => $healthStatus,
                'title' => 'System Settings & Admin Panel'
            ];

            return view('settings/index', $data);
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        }
    }
    
    public function update()
    {

        try {
            $category = $this->request->getPost('category');
            $validation = \Config\Services::validation();
            
            // Validate category
            if (!in_array($category, ['company', 'system', 'business', 'notification', 'security'])) {
                return redirect()->to('/settings')->with('error', 'Invalid settings category');
            }

            // Define validation rules based on category
            $rules = [];
            if ($category === 'company') {
                $rules = [
                    'company_name' => 'required|min_length[2]|max_length[255]',
                    'company_email' => 'required|valid_email|max_length[255]',
                    'company_phone' => 'permit_empty|max_length[20]',
                    'company_address' => 'permit_empty|max_length[500]'
                ];
            } elseif ($category === 'system') {
                $rules = [
                    'system_timezone' => 'permit_empty|max_length[50]',
                    'system_currency' => 'permit_empty|max_length[10]',
                    'system_language' => 'permit_empty|max_length[10]'
                ];
            } elseif ($category === 'business') {
                $rules = [
                    'business_name' => 'permit_empty|max_length[255]',
                    'business_type' => 'permit_empty|max_length[100]',
                    'tax_number' => 'permit_empty|max_length[50]',
                    'registration_number' => 'permit_empty|max_length[50]'
                ];
            }

            // Validate input
            if (!empty($rules) && !$validation->setRules($rules)->run($this->request->getPost())) {
                return redirect()->to('/settings')
                    ->withInput()
                    ->with('errors', $validation->getErrors());
            }

            // Get database connection
            $db = \Config\Database::connect();
            
            // Start transaction
            $db->transStart();

            // Update settings based on category
            $updated = 0;
            foreach ($this->request->getPost() as $key => $value) {
                if (in_array($key, ['category', 'csrf_token', 'csrf_hash', 'csrf_test_name'])) {
                    continue;
                }

                // Check if setting exists
                $existingSetting = $db->table('settings')
                    ->where('category', $category)
                    ->where('key', $key)
                    ->get()
                    ->getRowArray();

                if ($existingSetting) {
                    // Update existing setting
                    $updateResult = $db->table('settings')
                        ->where('id', $existingSetting['id'])
                        ->update([
                            'value' => $value,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    
                    if ($updateResult) {
                        $updated++;
                    }
                } else {
                    // Insert new setting
                    $insertResult = $db->table('settings')->insert([
                        'category' => $category,
                        'key' => $key,
                        'value' => $value,
                        'type' => 'string',
                        'description' => ucfirst(str_replace('_', ' ', $key)),
                        'is_hidden' => 0,
                        'is_sensitive' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    
                    if ($insertResult) {
                        $updated++;
                    }
                }
            }

            // Complete transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Failed to update settings');
            }

            $message = $updated > 0 
                ? "Successfully updated {$updated} " . ucfirst($category) . " setting(s)"
                : "No changes were made to " . ucfirst($category) . " settings";

            return redirect()->to('/settings')->with('success', $message);

        } catch (\Exception $e) {
            log_message('error', 'Settings update failed: ' . $e->getMessage());
            return redirect()->to('/settings')
                ->withInput()
                ->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }
    
    public function adminUtility()
    {
        try {
            $action = $this->request->getPost('action');
            
            if (!$action) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No action specified'
                ]);
            }
            
            $result = ['success' => false, 'message' => 'Unknown action'];
            
            switch ($action) {
                case 'clear_cache':
                    $result = $this->adminUtilities->clearCache();
                    break;
                    
                case 'reset_queue':
                    $result = $this->adminUtilities->resetQueue();
                    break;
                    
                case 'optimize_db':
                    $result = $this->adminUtilities->optimizeDatabase();
                    break;
                    
                case 'trigger_backup':
                    $result = $this->adminUtilities->triggerBackup();
                    break;
                    
                case 'clean_logs':
                    $days = (int)$this->request->getPost('days') ?: 30;
                    $result = $this->adminUtilities->cleanLogs($days);
                    break;
                    
                default:
                    $result = [
                        'success' => false,
                        'message' => 'Invalid action: ' . $action
                    ];
            }
            
            // Log the admin action
            if (isset($this->systemLogModel)) {
                $this->systemLogModel->logAction(
                    $action,
                    $result['success'] ? 'info' : 'error',
                    $result['message'],
                    ['user_ip' => $this->request->getIPAddress()]
                );
            }
            
            return $this->response->setJSON($result);
            
        } catch (\Exception $e) {
            log_message('error', 'Admin utility action failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Action failed: ' . $e->getMessage()
            ]);
        }
    }
    
    public function systemInfo()
    {
        try {
            // Get basic system information without relying on AdminUtilities
            $systemInfo = [
                'php_version' => PHP_VERSION,
                'codeigniter_version' => \CodeIgniter\CodeIgniter::CI_VERSION,
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'operating_system' => PHP_OS,
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'timezone' => date_default_timezone_get(),
                'current_time' => date('Y-m-d H:i:s'),
                'disk_free_space' => $this->getDiskSpace('free'),
                'disk_total_space' => $this->getDiskSpace('total'),
            ];
            
            // Add database information
            try {
                $db = \Config\Database::connect();
                $systemInfo['database_version'] = $db->getVersion();
                $systemInfo['database_platform'] = $db->getPlatform();
            } catch (\Exception $e) {
                $systemInfo['database_version'] = 'Connection failed';
                $systemInfo['database_platform'] = 'Unknown';
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $systemInfo
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'System info retrieval failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to retrieve system information'
            ]);
        }
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($size, $precision = 2)
    {
        if ($size <= 0) {
            return '0 B';
        }
        
        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];
        $index = min(floor($base), count($suffixes) - 1);
        
        return round(pow(1024, $base - $index), $precision) . ' ' . $suffixes[$index];
    }
    
    /**
     * Get disk space information safely
     */
    private function getDiskSpace($type = 'free')
    {
        try {
            if ($type === 'free') {
                $space = disk_free_space('.');
            } else {
                $space = disk_total_space('.');
            }
            
            if ($space === false || $space === null) {
                return 'Unknown';
            }
            
            return $this->formatBytes($space);
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
    
    public function logs()
    {
        try {
            $page = (int)$this->request->getGet('page') ?: 1;
            $limit = (int)$this->request->getGet('limit') ?: 50;
            $level = $this->request->getGet('level') ?: 'all';
            $search = $this->request->getGet('search') ?: '';
            
            // Get logs from AdminUtilities
            $logsData = $this->adminUtilities->getLogs($page, $limit, $level, $search);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $logsData
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Logs retrieval failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to retrieve logs: ' . $e->getMessage()
            ]);
        }
    }
    
    public function exportSettings()
    {
        try {
            $format = $this->request->getGet('format') ?? 'json';
            
            // Get all settings
            $settings = $this->settingsModel->getAllSettingsGrouped();
            
            // Get system information for reports
            $systemInfo = [
                'export_date' => date('Y-m-d H:i:s'),
                'system_name' => $settings['company']['company_name'] ?? 'System',
                'php_version' => PHP_VERSION,
                'ci_version' => \CodeIgniter\CodeIgniter::CI_VERSION,
                'database_version' => $this->settingsModel->db->getVersion()
            ];
            
            switch ($format) {
                case 'pdf':
                    return $this->exportToPDF($settings, $systemInfo);
                    
                case 'excel':
                    return $this->exportToExcel($settings, $systemInfo);
                    
                case 'backup':
                    return $this->exportBackup($settings, $systemInfo);
                    
                case 'json':
                default:
                    return $this->exportToJSON($settings, $systemInfo);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Export failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ]);
        }
    }
    
    private function exportToJSON($settings, $systemInfo)
    {
        $data = [
            'export_info' => $systemInfo,
            'settings' => $settings
        ];
        
        $filename = 'settings_export_' . date('Y-m-d_H-i-s') . '.json';
        
        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody(json_encode($data, JSON_PRETTY_PRINT));
    }
    
    private function exportToPDF($settings, $systemInfo)
    {
        // For now, create a simple HTML-to-PDF export
        // In production, you might want to use a library like TCPDF or mPDF
        $html = $this->generatePDFContent($settings, $systemInfo);
        
        $filename = 'settings_report_' . date('Y-m-d_H-i-s') . '.html';
        
        return $this->response
            ->setHeader('Content-Type', 'text/html')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($html);
    }
    
    private function exportToExcel($settings, $systemInfo)
    {
        // Create CSV format for Excel compatibility
        $csv = $this->generateCSVContent($settings, $systemInfo);
        
        $filename = 'settings_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }
    
    private function exportBackup($settings, $systemInfo)
    {
        // Create a comprehensive backup with settings and metadata
        $backup = [
            'backup_info' => array_merge($systemInfo, [
                'backup_type' => 'full',
                'includes' => ['settings', 'system_info']
            ]),
            'settings' => $settings,
            'system_health' => $this->adminUtilities->getHealthStatus()
        ];
        
        $filename = 'system_backup_' . date('Y-m-d_H-i-s') . '.json';
        
        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody(json_encode($backup, JSON_PRETTY_PRINT));
    }
    
    private function generatePDFContent($settings, $systemInfo)
    {
        $html = '<!DOCTYPE html><html><head><title>System Settings Report</title>';
        $html .= '<style>body{font-family:Arial,sans-serif;margin:20px;}table{width:100%;border-collapse:collapse;}th,td{border:1px solid #ddd;padding:8px;text-align:left;}th{background-color:#f2f2f2;}</style>';
        $html .= '</head><body>';
        $html .= '<h1>System Settings Report</h1>';
        $html .= '<p>Generated on: ' . $systemInfo['export_date'] . '</p>';
        
        foreach ($settings as $category => $categorySettings) {
            $html .= '<h2>' . ucfirst($category) . ' Settings</h2>';
            $html .= '<table><tr><th>Setting</th><th>Value</th></tr>';
            
            foreach ($categorySettings as $setting) {
                $value = $setting['is_sensitive'] ? '***HIDDEN***' : $setting['value'];
                $html .= '<tr><td>' . htmlspecialchars($setting['key']) . '</td><td>' . htmlspecialchars($value) . '</td></tr>';
            }
            
            $html .= '</table><br>';
        }
        
        $html .= '</body></html>';
        return $html;
    }
    
    private function generateCSVContent($settings, $systemInfo)
    {
        $csv = "Category,Setting Key,Value,Description\n";
        
        foreach ($settings as $category => $categorySettings) {
            foreach ($categorySettings as $setting) {
                $value = $setting['is_sensitive'] ? '***HIDDEN***' : $setting['value'];
                $csv .= '"' . $category . '","' . $setting['key'] . '","' . $value . '","' . ($setting['description'] ?? '') . '"' . "\n";
            }
        }
        
        return $csv;
    }
    
    public function importSettings()
    {
        try {
            $file = $this->request->getFile('settings_file');
            
            if (!$file || !$file->isValid()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No valid file uploaded'
                ]);
            }
            
            // Check file type
            $allowedTypes = ['application/json', 'text/plain'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid file type. Only JSON files are allowed.'
                ]);
            }
            
            // Read and parse file content
            $content = file_get_contents($file->getTempName());
            $data = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid JSON format: ' . json_last_error_msg()
                ]);
            }
            
            // Validate data structure
            if (!isset($data['settings']) || !is_array($data['settings'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid settings format. Expected "settings" array.'
                ]);
            }
            
            $imported = 0;
            $errors = [];
            
            // Import settings
            foreach ($data['settings'] as $category => $settings) {
                foreach ($settings as $setting) {
                    try {
                        // Skip sensitive settings or validate them
                        if (isset($setting['value']) && $setting['value'] === '***HIDDEN***') {
                            continue;
                        }
                        
                        $result = $this->settingsModel->updateSetting(
                            $setting['key'],
                            $setting['value'],
                            $setting['category'] ?? $category
                        );
                        
                        if ($result) {
                            $imported++;
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Failed to import {$setting['key']}: " . $e->getMessage();
                    }
                }
            }
            
            // Log the import action
            if (isset($this->systemLogModel)) {
                $this->systemLogModel->logAction(
                    'import_settings',
                    'info',
                    "Imported {$imported} settings",
                    [
                        'imported_count' => $imported,
                        'errors_count' => count($errors),
                        'user_ip' => $this->request->getIPAddress()
                    ]
                );
            }
            
            $message = "Successfully imported {$imported} settings";
            if (!empty($errors)) {
                $message .= " with " . count($errors) . " errors";
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => $message,
                'imported' => $imported,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Settings import failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ]);
        }
    }
}
