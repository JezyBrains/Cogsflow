<?php

/**
 * Currency Helper
 * 
 * Provides currency formatting functions for Tanzanian Shillings (TZS)
 */

if (!function_exists('format_currency')) {
    /**
     * Format amount in Tanzanian Shillings with thousands separator
     * 
     * @param float $amount The amount to format
     * @param bool $showSymbol Whether to show TZS symbol
     * @param int $decimals Number of decimal places
     * @return string Formatted currency string
     */
    function format_currency($amount, $showSymbol = true, $decimals = 2)
    {
        $formatted = number_format((float)$amount, $decimals, '.', ',');
        return $showSymbol ? 'TZS ' . $formatted : $formatted;
    }
}

if (!function_exists('format_currency_short')) {
    /**
     * Format large amounts with K/M/B suffixes
     * 
     * @param float $amount The amount to format
     * @param bool $showSymbol Whether to show TZS symbol
     * @return string Formatted currency string
     */
    function format_currency_short($amount, $showSymbol = true)
    {
        $amount = (float)$amount;
        
        if ($amount >= 1000000000) {
            $formatted = number_format($amount / 1000000000, 2) . 'B';
        } elseif ($amount >= 1000000) {
            $formatted = number_format($amount / 1000000, 2) . 'M';
        } elseif ($amount >= 1000) {
            $formatted = number_format($amount / 1000, 2) . 'K';
        } else {
            $formatted = number_format($amount, 2);
        }
        
        return $showSymbol ? 'TZS ' . $formatted : $formatted;
    }
}

if (!function_exists('parse_currency_input')) {
    /**
     * Parse currency input by removing thousands separators
     * 
     * @param string $input The input string with thousands separators
     * @return float The parsed numeric value
     */
    function parse_currency_input($input)
    {
        // Remove TZS prefix if present
        $input = str_replace(['TZS', 'tzs'], '', $input);
        // Remove thousands separators (commas)
        $input = str_replace(',', '', $input);
        // Remove any spaces
        $input = trim($input);
        
        return (float)$input;
    }
}

if (!function_exists('format_currency_input')) {
    /**
     * Format currency for input fields (with thousands separator)
     * 
     * @param float $amount The amount to format
     * @return string Formatted string for input
     */
    function format_currency_input($amount)
    {
        return number_format((float)$amount, 2, '.', ',');
    }
}

if (!function_exists('get_currency_symbol')) {
    /**
     * Get the currency symbol
     * 
     * @return string Currency symbol
     */
    function get_currency_symbol()
    {
        return 'TZS';
    }
}

if (!function_exists('get_currency_name')) {
    /**
     * Get the full currency name
     * 
     * @return string Currency name
     */
    function get_currency_name()
    {
        return 'Tanzanian Shillings';
    }
}
