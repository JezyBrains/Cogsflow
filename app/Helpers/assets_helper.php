<?php

/**
 * Assets Helper for Grain Management System
 */

if (!function_exists('asset_url')) {
    /**
     * Generate asset URL
     */
    function asset_url(string $path): string
    {
        // If it's already a full URL, return as is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        
        // Return base URL + asset path
        return rtrim(base_url(), '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('css_asset')) {
    /**
     * Get CSS asset URLs
     */
    function css_asset(string $name): string
    {
        $cssAssets = [
            'adminlte' => 'https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css',
            'fontawesome' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
            'custom' => 'assets/css/custom.css'
        ];
        
        return isset($cssAssets[$name]) ? asset_url($cssAssets[$name]) : '';
    }
}

if (!function_exists('js_asset')) {
    /**
     * Get JavaScript asset URLs
     */
    function js_asset(string $name): string
    {
        $jsAssets = [
            'jquery' => 'https://code.jquery.com/jquery-3.7.1.min.js',
            'bootstrap' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
            'adminlte' => 'https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js',
            'custom' => 'assets/js/custom.js'
        ];
        
        return isset($jsAssets[$name]) ? asset_url($jsAssets[$name]) : '';
    }
}
