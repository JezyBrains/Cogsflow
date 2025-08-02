<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class Assets extends BaseConfig
{
    /**
     * Base URL for assets
     */
    public string $baseURL;

    /**
     * CSS Files
     */
    public array $css = [
        'adminlte' => 'https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css',
        'fontawesome' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        'custom' => 'assets/css/custom.css'
    ];

    /**
     * JavaScript Files
     */
    public array $js = [
        'jquery' => 'https://code.jquery.com/jquery-3.7.1.min.js',
        'bootstrap' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
        'adminlte' => 'https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js',
        'custom' => 'assets/js/custom.js'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->baseURL = rtrim(base_url(), '/') . '/';
    }

    /**
     * Get full asset URL
     */
    public function getAssetUrl(string $path): string
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        return $this->baseURL . ltrim($path, '/');
    }
}
