# 🔄 Clear Server Cache - Production

## Issue
Files uploaded but changes not showing? Server-side cache needs clearing.

---

## ✅ Step 1: Upload Test File

1. **Upload** `public/test-css.php` to your server
2. **Visit**: `https://nipoagro.com/test-css.php`
3. **Check**:
   - Does it show "File exists: ✅ YES"?
   - Does it show the CSS content starting with "V6.0"?
   - Do you see the colored bag squares?

This will tell us if the CSS file is actually on the server.

---

## ✅ Step 2: Clear CodeIgniter Cache

### Via cPanel File Manager:

1. **Navigate to**: `/home8/johsport/nipoagro.com/writable/cache/`
2. **Select all files** in the cache folder
3. **Delete** them all
4. **Refresh** your browser

### Via SSH (if you have access):

```bash
# Connect to server
ssh username@nipoagro.com

# Navigate to project
cd /home8/johsport/nipoagro.com

# Clear cache
rm -rf writable/cache/*

# Clear view cache
rm -rf writable/views/*

# Set permissions
chmod -R 755 writable/
```

---

## ✅ Step 3: Clear OPcache (PHP Cache)

### Via cPanel:

1. **Go to**: cPanel → Software → Select PHP Version
2. **Click**: "Switch To PHP Options"
3. **Find**: "opcache.enable"
4. **Toggle**: OFF then ON
5. **Or**: Create a file to reset it

### Create Reset Script:

Upload this file as `public/clear-cache.php`:

```php
<?php
// Clear OPcache
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "✅ OPcache cleared!<br>";
} else {
    echo "❌ OPcache not available<br>";
}

// Clear realpath cache
clearstatcache(true);
echo "✅ Realpath cache cleared!<br>";

echo "<br><a href='/batch-receiving'>Go to Batch Receiving</a>";
?>
```

Then visit: `https://nipoagro.com/clear-cache.php`

---

## ✅ Step 4: Verify File Upload

### Check if view file was actually uploaded:

1. **In cPanel File Manager**, navigate to:
   ```
   /home8/johsport/nipoagro.com/app/Views/batch_receiving/
   ```

2. **Right-click** on `inspection_grid.php`

3. **Click "Edit"**

4. **Search for** (Ctrl+F): `bag-inspection.css`

5. **Should see**:
   ```php
   <link rel="stylesheet" href="<?= base_url('assets/css/bag-inspection.css?v=6.0') ?>">
   ```

6. **If it shows** `?v=6.0` → File uploaded correctly ✅
   **If it doesn't** → File NOT uploaded, upload again ❌

---

## ✅ Step 5: Check .htaccess Cache Headers

Your `.htaccess` might be caching CSS files. Check if this exists:

**File**: `/home8/johsport/nipoagro.com/public/.htaccess`

Look for cache headers like:
```apache
<FilesMatch "\.(css|js)$">
    Header set Cache-Control "max-age=31536000"
</FilesMatch>
```

If found, temporarily change to:
```apache
<FilesMatch "\.(css|js)$">
    Header set Cache-Control "no-cache, no-store, must-revalidate"
</FilesMatch>
```

---

## ✅ Step 6: Force Browser to Load New CSS

### Add timestamp instead of version:

Edit `inspection_grid.php` to use timestamp:

```php
<!-- Instead of ?v=6.0 -->
<link rel="stylesheet" href="<?= base_url('assets/css/bag-inspection.css?t=' . time()) ?>">
```

This forces a new URL every time, bypassing ALL caches.

---

## ✅ Step 7: Check File Permissions

Make sure the CSS file is readable:

### Via cPanel:
1. Navigate to `public/assets/css/`
2. Right-click `bag-inspection.css`
3. Click "Change Permissions"
4. Set to: **644** (rw-r--r--)

### Via SSH:
```bash
chmod 644 public/assets/css/bag-inspection.css
```

---

## 🔍 Diagnostic Checklist

Run through this checklist:

- [ ] CSS file exists on server (`test-css.php` shows ✅)
- [ ] CSS file has V6.0 header (`test-css.php` shows content)
- [ ] View file has `?v=6.0` (check in File Manager editor)
- [ ] CodeIgniter cache cleared (`writable/cache/*` deleted)
- [ ] OPcache cleared (`clear-cache.php` run)
- [ ] File permissions correct (644)
- [ ] Browser cache cleared (Cmd+Shift+R)
- [ ] Tested in Incognito mode

---

## 🎯 Quick Test

After clearing all caches:

1. **Visit**: `https://nipoagro.com/test-css.php`
2. **Should see**: Colored bag squares in a grid
3. **Then visit**: `https://nipoagro.com/batch-receiving/inspection/9`
4. **Should see**: Train seat layout

---

## 🚨 If Still Not Working

### Last Resort - Direct CSS Link Test:

Visit directly: `https://nipoagro.com/assets/css/bag-inspection.css?v=6.0`

**Should see**: CSS code starting with `/* ===== BAG INSPECTION STYLES V6.0`

**If you see old code**: File wasn't uploaded correctly
**If you see 404**: File path is wrong
**If you see V6.0**: Cache issue - try timestamp method

---

**Start with Step 1 (test-css.php) to diagnose!** 🔍
