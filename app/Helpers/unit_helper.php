<?php

if (!function_exists('get_weight_unit')) {
    /**
     * Get the configured default weight unit
     * 
     * @return string
     */
    function get_weight_unit(): string
    {
        $settingsModel = new \App\Models\SettingsModel();
        return $settingsModel->getSetting('default_weight_unit', 'kg');
    }
}

if (!function_exists('get_weight_unit_display')) {
    /**
     * Get the display name for the weight unit
     * 
     * @return string
     */
    function get_weight_unit_display(): string
    {
        $settingsModel = new \App\Models\SettingsModel();
        return $settingsModel->getSetting('weight_unit_display', 'Kilograms (kg)');
    }
}

if (!function_exists('convert_weight')) {
    /**
     * Convert weight from one unit to another
     * 
     * @param float $value The weight value
     * @param string $from Source unit (kg, mt, ton, lbs)
     * @param string $to Target unit (kg, mt, ton, lbs)
     * @return float
     */
    function convert_weight(float $value, string $from, string $to): float
    {
        // Conversion factors to kilograms
        $toKg = [
            'kg' => 1,
            'mt' => 1000,
            'ton' => 1000,
            'tonne' => 1000,
            'lbs' => 0.453592,
            'lb' => 0.453592,
            'g' => 0.001,
            'gram' => 0.001
        ];

        // Normalize unit names
        $from = strtolower($from);
        $to = strtolower($to);

        // If same unit, return as is
        if ($from === $to) {
            return $value;
        }

        // Convert to kg first, then to target unit
        if (!isset($toKg[$from]) || !isset($toKg[$to])) {
            return $value; // Unknown unit, return original
        }

        $valueInKg = $value * $toKg[$from];
        return $valueInKg / $toKg[$to];
    }
}

if (!function_exists('format_weight')) {
    /**
     * Format weight with the configured unit
     * 
     * @param float $value Weight value
     * @param string|null $unit Unit to display (null = use system default)
     * @param int $decimals Number of decimal places
     * @param bool $showUnit Whether to show the unit suffix
     * @param bool $showSecondary Whether to show secondary unit conversion
     * @return string
     */
    function format_weight(
        float $value, 
        ?string $unit = null, 
        int $decimals = 2, 
        bool $showUnit = true,
        bool $showSecondary = false
    ): string {
        $settingsModel = new \App\Models\SettingsModel();
        
        // Get default unit if not specified
        if ($unit === null) {
            $unit = $settingsModel->getSetting('default_weight_unit', 'kg');
        }

        // Format the number
        $formatted = number_format($value, $decimals);
        
        // Add unit suffix if requested
        if ($showUnit) {
            $formatted .= ' ' . strtoupper($unit);
        }

        // Add secondary unit conversion if enabled
        if ($showSecondary && $settingsModel->getSetting('show_secondary_unit', true)) {
            $secondaryUnit = ($unit === 'kg') ? 'mt' : 'kg';
            $convertedValue = convert_weight($value, $unit, $secondaryUnit);
            
            if ($convertedValue > 0.01) { // Only show if meaningful
                $formatted .= ' (' . number_format($convertedValue, 2) . ' ' . strtoupper($secondaryUnit) . ')';
            }
        }

        return $formatted;
    }
}

if (!function_exists('get_unit_options')) {
    /**
     * Get available unit options for dropdowns
     * 
     * @return array
     */
    function get_unit_options(): array
    {
        return [
            'kg' => 'Kilograms (kg)',
            'mt' => 'Metric Tonnes (MT)',
            'ton' => 'Tonnes (ton)',
            'lbs' => 'Pounds (lbs)',
            'g' => 'Grams (g)'
        ];
    }
}

if (!function_exists('normalize_weight_to_kg')) {
    /**
     * Normalize any weight value to kilograms for database storage
     * 
     * @param float $value Weight value
     * @param string $unit Source unit
     * @return float Weight in kilograms
     */
    function normalize_weight_to_kg(float $value, string $unit): float
    {
        return convert_weight($value, $unit, 'kg');
    }
}

if (!function_exists('denormalize_weight_from_kg')) {
    /**
     * Convert weight from kilograms (database) to display unit
     * 
     * @param float $valueInKg Weight in kilograms
     * @param string|null $targetUnit Target unit (null = use system default)
     * @return float
     */
    function denormalize_weight_from_kg(float $valueInKg, ?string $targetUnit = null): float
    {
        if ($targetUnit === null) {
            $settingsModel = new \App\Models\SettingsModel();
            $targetUnit = $settingsModel->getSetting('default_weight_unit', 'kg');
        }
        
        return convert_weight($valueInKg, 'kg', $targetUnit);
    }
}

if (!function_exists('get_weight_label')) {
    /**
     * Get a label for weight input fields
     * 
     * @param string $fieldName Field name (e.g., "Quantity", "Total Weight")
     * @param bool $includeUnit Whether to include the unit in parentheses
     * @return string
     */
    function get_weight_label(string $fieldName, bool $includeUnit = true): string
    {
        if (!$includeUnit) {
            return $fieldName;
        }

        $unit = get_weight_unit();
        return $fieldName . ' (' . strtoupper($unit) . ')';
    }
}

if (!function_exists('validate_weight_unit')) {
    /**
     * Validate if a unit is supported
     * 
     * @param string $unit Unit to validate
     * @return bool
     */
    function validate_weight_unit(string $unit): bool
    {
        $validUnits = ['kg', 'mt', 'ton', 'tonne', 'lbs', 'lb', 'g', 'gram'];
        return in_array(strtolower($unit), $validUnits);
    }
}
