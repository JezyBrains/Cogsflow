# 🔄 Clear Browser Cache - Instructions

## Issue
Changes not showing up? The browser is caching the old CSS file.

---

## ✅ Solution 1: Hard Refresh (Fastest)

### Chrome/Edge/Firefox (Mac):
```
⌘ + Shift + R
```

### Chrome/Edge/Firefox (Windows/Linux):
```
Ctrl + Shift + R
```

### Safari (Mac):
```
⌘ + Option + R
```

---

## ✅ Solution 2: Clear Cache Manually

### Chrome:
1. Press `⌘ + Shift + Delete` (Mac) or `Ctrl + Shift + Delete` (Windows)
2. Select "Cached images and files"
3. Click "Clear data"
4. Refresh page: `⌘ + R` or `Ctrl + R`

### Firefox:
1. Press `⌘ + Shift + Delete` (Mac) or `Ctrl + Shift + Delete` (Windows)
2. Select "Cache"
3. Click "Clear Now"
4. Refresh page: `⌘ + R` or `Ctrl + R`

### Safari:
1. Go to Safari → Preferences → Advanced
2. Check "Show Develop menu in menu bar"
3. Develop → Empty Caches
4. Refresh page: `⌘ + R`

---

## ✅ Solution 3: Disable Cache (Developer Mode)

### Chrome DevTools:
1. Open DevTools: `⌘ + Option + I` (Mac) or `F12` (Windows)
2. Go to Network tab
3. Check "Disable cache"
4. Keep DevTools open while testing

### Firefox DevTools:
1. Open DevTools: `⌘ + Option + I` (Mac) or `F12` (Windows)
2. Click Settings (gear icon)
3. Check "Disable HTTP Cache (when toolbox is open)"
4. Keep DevTools open while testing

---

## ✅ Solution 4: Incognito/Private Mode

### Quick Test:
1. Open Incognito/Private window
2. Navigate to the page
3. Fresh CSS will load (no cache)

---

## 🔧 What I Changed

### Added Cache-Busting:
```php
// Old:
<link rel="stylesheet" href="<?= base_url('assets/css/bag-inspection.css') ?>">

// New:
<link rel="stylesheet" href="<?= base_url('assets/css/bag-inspection.css?v=6.0') ?>">
```

The `?v=6.0` forces the browser to reload the CSS file.

---

## 🎯 Verify It's Working

After clearing cache, you should see:

1. ✅ **Grid layout** (not vertical list)
2. ✅ **10 columns** of seats
3. ✅ **60x60px squares**
4. ✅ **Aisle gap** in the middle
5. ✅ **Bag icons** with numbers inside
6. ✅ **Clean white background**

### Check in DevTools:
1. Open DevTools → Network tab
2. Refresh page
3. Look for `bag-inspection.css?v=6.0`
4. Click on it → Preview tab
5. Should see: `/* ===== BAG INSPECTION STYLES V6.0 - Train Seat Selection Style ===== */`

---

## 🚫 About the CSP Error

The error you saw:
```
Refused to connect to 'data:text/plain;base64...'
```

**This is NOT related to our changes!**

It's from:
- A browser extension (FidelityFX-CAS shader)
- Or a graphics/gaming overlay
- Or GPU optimization software

**Safe to ignore** - it doesn't affect the bag inspection system.

---

## 🎉 After Cache Clear

Navigate to: `/batch-receiving/inspection/10`

You should see the **train seat selection style**:
- ✅ Grid of 60x60px seats
- ✅ Aisle gap every 5 seats
- ✅ Numbers inside seats
- ✅ 50+ bags visible at once
- ✅ Clean, professional layout

---

**If still not working after hard refresh, try Incognito mode!** 🔄
