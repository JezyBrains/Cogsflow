# Unit of Measure System - Deployment Instructions

## Quick Summary

This update adds a **configurable unit of measure system** to eliminate confusion between metric tonnes (MT) and kilograms (kg) across the application.

## What Changed

### 1. New Files Created
- ✅ `app/Database/Migrations/2025-02-10-000001_AddUnitOfMeasureSettings.php` - Database migration
- ✅ `app/Helpers/unit_helper.php` - Unit conversion helper functions
- ✅ `docs/UNIT_OF_MEASURE_GUIDE.md` - Complete user documentation

### 2. Files Modified
- ✅ `app/Views/settings/index.php` - Added unit configuration UI
- ✅ `app/Controllers/SettingsController.php` - Added validation for unit settings
- ✅ `app/Config/Autoload.php` - Auto-load unit helper
- ✅ `app/Views/purchase_orders/create.php` - Use dynamic units instead of hardcoded "kg"

## Deployment Steps

### Step 1: Upload Files to Production

Upload these files to your production server at `nipoagro.com`:

```bash
# New files
app/Database/Migrations/2025-02-10-000001_AddUnitOfMeasureSettings.php
app/Helpers/unit_helper.php
docs/UNIT_OF_MEASURE_GUIDE.md
DEPLOYMENT_INSTRUCTIONS_UNIT_SYSTEM.md

# Modified files
app/Views/settings/index.php
app/Controllers/SettingsController.php
app/Config/Autoload.php
app/Views/purchase_orders/create.php
```

### Step 2: Run Database Migration

SSH into your production server and run:

```bash
cd /path/to/nipo-final
php spark migrate
```

This will add 4 new settings to the `settings` table:
- `default_weight_unit` (default: 'kg')
- `weight_unit_display` (default: 'Kilograms (kg)')
- `enable_unit_conversion` (default: true)
- `show_secondary_unit` (default: true)

### Step 3: Configure Your Preferred Unit

1. Log in to the system as admin
2. Navigate to **Settings** → **System** tab
3. Scroll to **Unit of Measure Settings**
4. Select your preferred unit:
   - **Kilograms (kg)** - Recommended for current setup
   - **Metric Tonnes (MT)** - For large-scale operations
   - Other options available
5. Choose whether to show secondary unit conversions
6. Click **Update System Settings**

### Step 4: Verify Functionality

1. **Test Purchase Order Creation:**
   - Go to Purchase Orders → Create New
   - Verify the Quantity field shows your selected unit
   - Verify Unit Price shows "per [YOUR_UNIT]"

2. **Test Settings Page:**
   - Go to Settings → System tab
   - Change the unit dropdown
   - Verify the conversion preview updates dynamically

3. **Test Existing Data:**
   - View existing purchase orders
   - Verify quantities display correctly
   - If you changed from kg to MT, values should be converted automatically

## Configuration Recommendations

### For Your Current Setup

Based on your issue description:
- **Purchase Orders:** Currently in metric tonnes
- **Batches:** Currently in kilograms
- **Bags:** Currently in kilograms

**Recommended Configuration:**
```
Default Weight Unit: kg (Kilograms)
Show Secondary Unit: Yes
```

This will:
- Display everything in kg (consistent with batches and bags)
- Show MT conversions in parentheses for clarity
- Example: "5000 kg (5 MT)"

**Alternative Configuration (if you prefer MT):**
```
Default Weight Unit: mt (Metric Tonnes)
Show Secondary Unit: Yes
```

This will:
- Display everything in MT
- Show kg conversions in parentheses
- Example: "5 MT (5000 kg)"

## How It Works

### Data Storage
- All weights are stored in the database in **kilograms (kg)**
- This ensures data consistency regardless of display unit
- No need to migrate existing data

### Display Conversion
- When displaying data, values are automatically converted from kg to your configured unit
- When saving data, values are automatically converted from your configured unit to kg
- All conversions are handled by the `unit_helper.php` functions

### Example Flow

**User enters Purchase Order:**
1. User sees: "Quantity (MT)"
2. User enters: 5
3. System converts: 5 MT → 5000 kg
4. Database stores: 5000 kg

**User views Purchase Order:**
1. Database has: 5000 kg
2. System converts: 5000 kg → 5 MT (based on settings)
3. User sees: "5 MT (5000 kg)" (if secondary unit enabled)

## Troubleshooting

### Issue: Migration fails

**Solution:**
```bash
# Check database connection
php spark db:table settings

# If connection works, try migration again
php spark migrate

# If still fails, check migration status
php spark migrate:status
```

### Issue: Settings don't appear

**Solution:**
1. Clear cache: Settings → Admin Tools → Clear Cache
2. Refresh browser (Ctrl+F5 or Cmd+Shift+R)
3. Check browser console for JavaScript errors

### Issue: Values look wrong after changing unit

**Solution:**
1. This is normal - values are being converted
2. Example: 1000 kg becomes 1 MT when you switch to MT
3. The actual data hasn't changed, only the display

### Issue: Helper functions not found

**Solution:**
1. Verify `app/Config/Autoload.php` includes 'unit' in helpers array
2. Clear cache
3. Restart PHP-FPM if using FastCGI

## Rollback Instructions

If you need to rollback this update:

### Step 1: Rollback Database
```bash
php spark migrate:rollback -b 2025-02-10-000001
```

### Step 2: Restore Files
Replace modified files with their previous versions:
- `app/Views/settings/index.php`
- `app/Controllers/SettingsController.php`
- `app/Config/Autoload.php`
- `app/Views/purchase_orders/create.php`

### Step 3: Remove New Files
Delete:
- `app/Helpers/unit_helper.php`
- `app/Database/Migrations/2025-02-10-000001_AddUnitOfMeasureSettings.php`

## Future Updates

To update other views to use the configurable unit system:

### Batch Views
```php
// Old
<label>Total Weight (kg)</label>

// New
<label><?= get_weight_label('Total Weight') ?></label>
```

### Inventory Views
```php
// Old
<td><?= number_format($item['quantity_mt'], 2) ?> MT</td>

// New
<td><?= format_weight($item['quantity_kg'], null, 2, true, true) ?></td>
```

### Dispatch Views
```php
// Old
<label>Dispatch Quantity (MT)</label>

// New
<label><?= get_weight_label('Dispatch Quantity') ?></label>
```

## Support

For questions or issues:
1. Review the complete documentation in `docs/UNIT_OF_MEASURE_GUIDE.md`
2. Check the conversion preview in Settings → System
3. Contact the development team

---

**Deployment Date:** [To be filled]  
**Deployed By:** [To be filled]  
**Production URL:** https://nipoagro.com  
**Status:** Ready for deployment
