# 📤 Upload Files to Production - Manual Guide

## 🎯 Files You Need to Upload

You need to upload these 3 files to nipoagro.com:

### 1. CSS File (MOST IMPORTANT)
```
Local:  /Users/noobmaster69/Downloads/nipo final/public/assets/css/bag-inspection.css
Server: /public_html/public/assets/css/bag-inspection.css
```

### 2. View File
```
Local:  /Users/noobmaster69/Downloads/nipo final/app/Views/batch_receiving/inspection_grid.php
Server: /public_html/app/Views/batch_receiving/inspection_grid.php
```

### 3. Model File
```
Local:  /Users/noobmaster69/Downloads/nipo final/app/Models/BagInspectionModel.php
Server: /public_html/app/Models/BagInspectionModel.php
```

---

## 📋 Upload Methods

### Method 1: cPanel File Manager (Easiest)

1. **Login to cPanel**
   - Go to: https://nipoagro.com:2083
   - Or your hosting control panel

2. **Open File Manager**
   - Click "File Manager" icon
   - Navigate to `public_html`

3. **Upload CSS File**
   - Navigate to: `public_html/public/assets/css/`
   - Click "Upload" button
   - Select: `bag-inspection.css` from your computer
   - Overwrite existing file

4. **Upload View File**
   - Navigate to: `public_html/app/Views/batch_receiving/`
   - Click "Upload" button
   - Select: `inspection_grid.php`
   - Overwrite existing file

5. **Upload Model File**
   - Navigate to: `public_html/app/Models/`
   - Click "Upload" button
   - Select: `BagInspectionModel.php`
   - Overwrite existing file

---

### Method 2: FTP/SFTP (FileZilla)

1. **Open FileZilla** (or your FTP client)

2. **Connect to Server**
   ```
   Host: nipoagro.com (or ftp.nipoagro.com)
   Username: [your FTP username]
   Password: [your FTP password]
   Port: 21 (FTP) or 22 (SFTP)
   ```

3. **Navigate to Folders**
   - Left side: Your local files
   - Right side: Server files (go to `public_html`)

4. **Upload Files**
   - Drag and drop each file from left to right
   - Overwrite when prompted

---

### Method 3: SSH/SCP (Terminal)

If you have SSH access:

```bash
# Upload CSS
scp "/Users/noobmaster69/Downloads/nipo final/public/assets/css/bag-inspection.css" \
    username@nipoagro.com:/path/to/public_html/public/assets/css/

# Upload View
scp "/Users/noobmaster69/Downloads/nipo final/app/Views/batch_receiving/inspection_grid.php" \
    username@nipoagro.com:/path/to/public_html/app/Views/batch_receiving/

# Upload Model
scp "/Users/noobmaster69/Downloads/nipo final/app/Models/BagInspectionModel.php" \
    username@nipoagro.com:/path/to/public_html/app/Models/
```

---

## ✅ Verify Upload

After uploading, verify in DevTools:

1. **Open DevTools** (F12)
2. **Go to Network tab**
3. **Hard refresh**: Ctrl+Shift+R (or Cmd+Shift+R)
4. **Look for**: `bag-inspection.css?v=6.0`
5. **Click on it** → Preview tab
6. **Should see**: `/* ===== BAG INSPECTION STYLES V6.0 - Train Seat Selection Style ===== */`

---

## 🔍 Check File Paths on Server

Make sure these paths exist on your server:

```
public_html/
├── public/
│   └── assets/
│       └── css/
│           └── bag-inspection.css  ← MUST EXIST
├── app/
│   ├── Views/
│   │   └── batch_receiving/
│   │       └── inspection_grid.php
│   └── Models/
│       └── BagInspectionModel.php
```

---

## 🚨 Common Issues

### Issue 1: File Not Found (404)
**Problem**: CSS file doesn't exist on server  
**Solution**: Upload `bag-inspection.css` to correct folder

### Issue 2: Old CSS Still Loading
**Problem**: Browser cache  
**Solution**: Hard refresh (Ctrl+Shift+R)

### Issue 3: Wrong Path
**Problem**: File uploaded to wrong folder  
**Solution**: Check path matches exactly

---

## 🎯 After Upload

1. **Clear browser cache**: Ctrl+Shift+R
2. **Check DevTools Network**: Should see `bag-inspection.css?v=6.0`
3. **Navigate to**: `/batch-receiving/inspection/9`
4. **You should see**: Train seat selection layout!

---

## 📞 Need Help?

If you're not sure how to access your server:
1. Check your hosting provider's documentation
2. Look for cPanel login details in your hosting email
3. Contact your hosting support for FTP/SSH credentials

---

**Most Important: Upload the CSS file first!** 🎨
