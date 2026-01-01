# Unit of Measure System - User Guide

## Overview

The CogsFlow system now supports **configurable units of measure** for all weight-related operations. This eliminates the confusion of mixing metric tonnes (MT) and kilograms (kg) across different modules.

## Problem Solved

**Before:**
- Purchase Orders used Metric Tonnes (MT)
- Batches used Kilograms (kg)
- Bag weights used Kilograms (kg)
- This created confusion when entering and viewing data

**After:**
- Single configurable unit across the entire system
- Automatic conversions when needed
- Optional display of secondary units for clarity

## Configuration

### Accessing Settings

1. Navigate to **Settings** from the main menu
2. Click on the **System** tab
3. Scroll to the **Unit of Measure Settings** section

### Available Options

#### 1. Default Weight Unit
Choose your preferred unit for all weight measurements:
- **Kilograms (kg)** - Default, suitable for smaller operations
- **Metric Tonnes (MT)** - Better for large-scale operations
- **Tonnes (ton)** - Alternative to MT
- **Pounds (lbs)** - For imperial system users
- **Grams (g)** - For very precise measurements

#### 2. Show Secondary Unit
- **Yes** - Display conversions (e.g., "1000 kg (1 MT)")
- **No** - Show only the primary unit

### Conversion Preview

The settings page shows real-time conversion examples when you change the unit:

**Example for Kilograms:**
- 1 kg = 0.001 MT
- 1000 kg = 1 MT
- 1 kg = 2.20462 lbs
- 1 kg = 1000 g

**Example for Metric Tonnes:**
- 1 MT = 1000 kg
- 1 MT = 2204.62 lbs
- 1 MT = 1,000,000 g
- 0.001 MT = 1 kg

## Usage Across Modules

### Purchase Orders
- **Quantity field** now uses your configured unit
- **Unit Price** shows "per [UNIT]" (e.g., "per KG" or "per MT")
- **Total Amount** calculated based on quantity × unit price

### Batches
- **Total Weight** displays in your configured unit
- **Individual Bag Weights** use the same unit
- **Batch Summary** shows totals in your unit

### Inventory
- **Stock Levels** displayed in your configured unit
- **Adjustments** entered in your configured unit
- **Reports** use your configured unit

### Dispatches
- **Dispatch Quantity** in your configured unit
- **Received Quantity** in your configured unit
- **Discrepancy Calculations** use your configured unit

## Helper Functions for Developers

The system provides several helper functions in `app/Helpers/unit_helper.php`:

### Basic Functions

```php
// Get current unit setting
$unit = get_weight_unit(); // Returns: 'kg', 'mt', 'ton', 'lbs', or 'g'

// Get display name
$displayName = get_weight_unit_display(); // Returns: 'Kilograms (kg)', etc.

// Format weight with unit
$formatted = format_weight(1500, 'kg'); // Returns: "1,500.00 KG"
$formatted = format_weight(1500, 'kg', 2, true, true); // Returns: "1,500.00 KG (1.50 MT)"
```

### Conversion Functions

```php
// Convert between units
$mt = convert_weight(1000, 'kg', 'mt'); // Returns: 1.0
$kg = convert_weight(1, 'mt', 'kg'); // Returns: 1000.0
$lbs = convert_weight(1, 'kg', 'lbs'); // Returns: 2.20462

// Normalize to kg for database storage
$kg = normalize_weight_to_kg(1, 'mt'); // Returns: 1000.0

// Convert from kg (database) to display unit
$displayValue = denormalize_weight_from_kg(1000); // Returns value in configured unit
```

### Label Functions

```php
// Get label for form fields
$label = get_weight_label('Quantity'); // Returns: "Quantity (KG)" or "Quantity (MT)"
$label = get_weight_label('Total Weight', false); // Returns: "Total Weight"
```

## Database Storage

**Important:** All weights are stored in the database in **kilograms (kg)** regardless of the configured display unit. This ensures:
- Data consistency
- Easy conversions
- Backward compatibility
- Future-proof design

The conversion happens automatically:
- **Input:** User enters value in their configured unit → Converted to kg → Stored in database
- **Output:** Database value in kg → Converted to configured unit → Displayed to user

## Migration

### Running the Migration

To add the unit of measure settings to your database:

```bash
php spark migrate
```

This will run the migration file:
`app/Database/Migrations/2025-02-10-000001_AddUnitOfMeasureSettings.php`

### Default Settings

The migration creates these default settings:
- `default_weight_unit`: 'kg'
- `weight_unit_display`: 'Kilograms (kg)'
- `enable_unit_conversion`: true
- `show_secondary_unit`: true

## Best Practices

### For Administrators

1. **Choose Once:** Select your unit when setting up the system and stick with it
2. **Train Users:** Ensure all users understand which unit is being used
3. **Enable Secondary Display:** Keep "Show Secondary Unit" enabled during transition period
4. **Document:** Note your chosen unit in your organization's procedures

### For Users

1. **Check Settings:** Always verify which unit is configured before entering data
2. **Use Consistent Units:** Enter all weights in the configured unit
3. **Review Conversions:** If secondary units are shown, verify conversions make sense
4. **Report Issues:** If you see unexpected values, check the unit settings first

### For Developers

1. **Use Helper Functions:** Always use `format_weight()` and `convert_weight()` instead of manual calculations
2. **Store in KG:** Always normalize to kg before saving to database
3. **Display in Configured Unit:** Always convert from kg when displaying to users
4. **Validate Units:** Use `validate_weight_unit()` before accepting user input

## Troubleshooting

### Issue: Values seem too large or too small

**Solution:** Check your configured unit in Settings → System → Unit of Measure Settings

### Issue: Conversions don't match expectations

**Solution:** 
1. Verify the configured unit is correct
2. Check if "Show Secondary Unit" is enabled
3. Review the conversion preview in settings

### Issue: Old data shows wrong values

**Solution:** 
1. Old data is stored in kg
2. Changing the display unit will automatically convert old data
3. No data migration needed

### Issue: Different users see different units

**Solution:** 
1. Unit settings are system-wide, not per-user
2. All users see the same unit
3. Check if users are looking at different environments (dev vs production)

## Examples

### Example 1: Small Operation (Kilograms)

**Configuration:**
- Default Weight Unit: kg
- Show Secondary Unit: Yes

**Purchase Order:**
- Quantity: 5000 kg (5 MT)
- Unit Price: 1500 TSH per kg
- Total: 7,500,000 TSH

### Example 2: Large Operation (Metric Tonnes)

**Configuration:**
- Default Weight Unit: mt
- Show Secondary Unit: No

**Purchase Order:**
- Quantity: 5 MT
- Unit Price: 1,500,000 TSH per MT
- Total: 7,500,000 TSH

### Example 3: Mixed Display

**Configuration:**
- Default Weight Unit: kg
- Show Secondary Unit: Yes

**Batch Display:**
- Total Weight: 1,250 kg (1.25 MT)
- Bag Count: 25 bags
- Average Bag Weight: 50 kg (0.05 MT)

## Technical Details

### Supported Units

| Unit Code | Display Name | Conversion to KG |
|-----------|--------------|------------------|
| kg | Kilograms | 1 |
| mt | Metric Tonnes | 1000 |
| ton | Tonnes | 1000 |
| lbs | Pounds | 0.453592 |
| g | Grams | 0.001 |

### Files Modified

1. **Migration:** `app/Database/Migrations/2025-02-10-000001_AddUnitOfMeasureSettings.php`
2. **Helper:** `app/Helpers/unit_helper.php`
3. **Settings View:** `app/Views/settings/index.php`
4. **Settings Controller:** `app/Controllers/SettingsController.php`
5. **Autoload Config:** `app/Config/Autoload.php`
6. **Purchase Order View:** `app/Views/purchase_orders/create.php`

### Database Tables Affected

- **settings:** New rows for unit configuration
- **purchase_orders:** quantity_mt field (will be renamed in future update)
- **batches:** total_weight_kg field
- **inventory:** quantity_mt field

## Future Enhancements

Planned improvements:
1. **Per-Module Units:** Different units for different modules (if needed)
2. **Custom Units:** Add support for custom unit definitions
3. **Bulk Conversion:** Tool to convert historical data between units
4. **Unit Validation:** Stricter validation on data entry
5. **API Support:** Unit conversion in REST API responses

## Support

For questions or issues:
1. Check this documentation first
2. Review the conversion preview in settings
3. Contact your system administrator
4. Report bugs through your issue tracking system

---

**Last Updated:** February 2025  
**Version:** 1.0  
**Author:** CogsFlow Development Team
