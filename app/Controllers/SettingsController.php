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
        return 'Update method - Phase 5 implementation in progress.';
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
            $systemInfo = $this->adminUtilities->getSystemInfo();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $systemInfo
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'System info retrieval failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to retrieve system information: ' . $e->getMessage()
            ]);
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
