<?php

namespace App\Controllers;

use App\Models\SettingsModel;
use App\Models\SystemLogModel;
use App\Libraries\AdminUtilities;

class SettingsController extends BaseController
{
    protected $settingsModel;
    protected $logModel;
    protected $adminUtils;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
        $this->logModel = new SystemLogModel();
        $this->adminUtils = new AdminUtilities();
    }

    /**
     * Display system settings page
     * 
     * @return string
     */
    public function index()
    {
        // Check if user has admin privileges (implement your auth logic here)
        if (!$this->hasAdminAccess()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        // Initialize default settings if not exists
        $this->settingsModel->initializeDefaults();

        // Get all settings grouped by category
        $settings = $this->settingsModel->getAllSettingsGrouped();

        // Get system health status
        $healthStatus = $this->adminUtils->getHealthStatus();

        $data = [
            'settings' => $settings,
            'healthStatus' => $healthStatus,
            'title' => 'System Settings & Admin Panel'
        ];

        return view('settings/index', $data);
    }

    /**
     * Update settings
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update()
    {
        if (!$this->hasAdminAccess()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $input = $this->request->getPost();
        $category = $input['category'] ?? 'general';
        
        try {
            $updated = 0;
            
            foreach ($input as $key => $value) {
                if (in_array($key, ['category', 'csrf_test_name'])) {
                    continue;
                }

                // Determine the type based on the value
                $type = $this->determineType($value);
                
                // Update the setting
                if ($this->settingsModel->setSetting($key, $value, $type, $category)) {
                    $updated++;
                }
            }

            $this->logModel->addLog('info', "Settings updated in category: {$category}", [
                'category' => $category,
                'updated_count' => $updated,
                'user' => session('user_id') ?? 'unknown'
            ]);

            session()->setFlashdata('success', "{$updated} settings updated successfully.");
            
        } catch (\Exception $e) {
            $this->logModel->addLog('error', 'Failed to update settings: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to update settings: ' . $e->getMessage());
        }

        return redirect()->to('/settings');
    }

    /**
     * Admin utilities endpoint
     */
    public function adminUtility()
    {
        if (!$this->hasAdminAccess()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $action = $this->request->getPost('action');
        
        switch ($action) {
            case 'clear_cache':
                $result = $this->adminUtils->clearCache();
                break;
                
            case 'reset_queue':
                $result = $this->adminUtils->resetQueueJobs();
                break;
                
            case 'trigger_backup':
                $result = $this->adminUtils->triggerBackup();
                break;
                
            case 'optimize_database':
                $result = $this->adminUtils->optimizeDatabase();
                break;
                
            case 'clean_logs':
                $days = (int) $this->request->getPost('days') ?: 30;
                $result = $this->adminUtils->cleanSystemLogs($days);
                break;
                
            default:
                $result = ['success' => false, 'message' => 'Invalid action'];
        }

        return $this->response->setJSON($result);
    }

    /**
     * Get system information
     */
    public function systemInfo()
    {
        if (!$this->hasAdminAccess()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $result = $this->adminUtils->getSystemInfo();
        return $this->response->setJSON($result);
    }

    /**
     * Get system logs
     */
    public function logs()
    {
        if (!$this->hasAdminAccess()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $level = $this->request->getGet('level');
        $page = (int) $this->request->getGet('page') ?: 1;
        $limit = 50;
        $offset = ($page - 1) * $limit;

        $result = $this->adminUtils->getSystemLogs($level, $limit, $offset);
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        $data = [
            'logs' => $result['logs'] ?? [],
            'stats' => $result['stats'] ?? [],
            'total' => $result['total'] ?? 0,
            'currentPage' => $page,
            'totalPages' => ceil(($result['total'] ?? 0) / $limit),
            'title' => 'System Logs'
        ];

        return view('settings/logs', $data);
    }

    /**
     * Export settings
     */
    public function exportSettings()
    {
        if (!$this->hasAdminAccess()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        try {
            $settings = $this->settingsModel->getAllSettingsGrouped(true);
            
            $export = [
                'exported_at' => date('Y-m-d H:i:s'),
                'version' => '1.0',
                'settings' => $settings
            ];

            $filename = 'settings_export_' . date('Y-m-d_H-i-s') . '.json';
            
            return $this->response
                ->setHeader('Content-Type', 'application/json')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setBody(json_encode($export, JSON_PRETTY_PRINT));
                
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to export settings: ' . $e->getMessage());
            return redirect()->to('/settings');
        }
    }

    /**
     * Import settings
     */
    public function importSettings()
    {
        if (!$this->hasAdminAccess()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $file = $this->request->getFile('settings_file');
        
        if (!$file || !$file->isValid()) {
            session()->setFlashdata('error', 'Please select a valid settings file.');
            return redirect()->to('/settings');
        }

        try {
            $content = file_get_contents($file->getTempName());
            $data = json_decode($content, true);
            
            if (!$data || !isset($data['settings'])) {
                throw new \Exception('Invalid settings file format.');
            }

            $imported = 0;
            foreach ($data['settings'] as $category => $categorySettings) {
                foreach ($categorySettings as $key => $setting) {
                    if ($this->settingsModel->setSetting(
                        $key, 
                        $setting['value'], 
                        $setting['type'], 
                        $category, 
                        $setting['description'] ?? null,
                        $setting['is_sensitive'] ?? false
                    )) {
                        $imported++;
                    }
                }
            }

            $this->logModel->addLog('info', "Settings imported: {$imported} settings", [
                'imported_count' => $imported,
                'user' => session('user_id') ?? 'unknown'
            ]);

            session()->setFlashdata('success', "Successfully imported {$imported} settings.");
            
        } catch (\Exception $e) {
            $this->logModel->addLog('error', 'Failed to import settings: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to import settings: ' . $e->getMessage());
        }

        return redirect()->to('/settings');
    }

    /**
     * Check if user has admin access
     * TODO: Implement proper role-based access control
     */
    private function hasAdminAccess()
    {
        // For now, return true. In a real application, implement proper RBAC
        // Example: return session('user_role') === 'admin';
        return true;
    }

    /**
     * Determine data type from value
     */
    private function determineType($value)
    {
        if (is_numeric($value) && strpos($value, '.') === false) {
            return 'integer';
        }
        
        if (filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null) {
            return 'boolean';
        }
        
        if (is_array($value) || (is_string($value) && json_decode($value) !== null)) {
            return 'json';
        }
        
        if (strlen($value) > 255) {
            return 'text';
        }
        
        return 'string';
    }
}
