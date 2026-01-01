# Phase 1, Feature 1.1: Database Schema for Bag Inspections ✅

## Status: COMPLETED

## What Was Implemented

### 1. Database Migration
**File**: `app/Database/Migrations/2025-01-27-150000_CreateBagInspectionTables.php`

Created two new tables:

#### Table 1: `bag_inspections`
Tracks individual bag inspection details with:
- **Identification**: dispatch_id, batch_id, bag_id, bag_number
- **Expected Values**: expected_weight_kg, expected_moisture
- **Actual Values**: actual_weight_kg, actual_moisture
- **Calculated Variances**: weight_variance_kg, weight_variance_percent, moisture_variance
- **Status**: condition_status (good/damaged/wet/contaminated/missing), inspection_status (pending/inspected/skipped)
- **Documentation**: inspection_notes, photo_path, voice_note_path
- **Audit Trail**: inspected_by, inspected_at, inspection_duration_seconds
- **QR Metadata**: qr_scanned, scan_timestamp, device_info

#### Table 2: `inspection_sessions`
Tracks inspection sessions for progress and analytics:
- **Session Info**: dispatch_id, batch_id, inspector_id
- **Timing**: started_at, completed_at, paused_at, total_duration_seconds
- **Progress**: total_bags_expected, total_bags_inspected, total_bags_skipped, total_discrepancies
- **Weight Summary**: expected_total_weight_kg, actual_total_weight_kg, weight_variance_percent
- **Status**: session_status (in_progress/completed/paused/cancelled)
- **Metadata**: device_type, inspection_mode, session_notes

### 2. Model Classes

#### BagInspectionModel
**File**: `app/Models/BagInspectionModel.php`

**Key Methods**:
- `getInspectionsByDispatch($dispatchId)` - Get all bag inspections for a dispatch
- `getInspectionSummary($dispatchId)` - Get summary statistics
- `recordInspection($data)` - Record a bag inspection with auto-variance calculation
- `getBagsWithDiscrepancies($dispatchId)` - Get bags that have issues
- `getProgressPercentage($dispatchId)` - Calculate inspection progress
- `isBagInspected($bagId, $dispatchId)` - Check if bag already inspected
- `getNextPendingBag($dispatchId)` - Get next bag to inspect

**Auto-Calculations**:
- Weight variance (kg and percentage)
- Moisture variance
- Discrepancy detection (2% weight tolerance, 1% moisture tolerance)
- Auto-sets inspection_status to 'inspected'

#### InspectionSessionModel
**File**: `app/Models/InspectionSessionModel.php`

**Key Methods**:
- `startSession($dispatchId, $batchId, $inspectorId, $deviceType)` - Start or resume session
- `updateProgress($sessionId, $progressData)` - Update session progress
- `completeSession($sessionId, $finalNotes)` - Complete session with duration calculation
- `pauseSession($sessionId)` - Pause inspection
- `resumeSession($sessionId)` - Resume paused inspection
- `cancelSession($sessionId, $reason)` - Cancel session
- `getActiveSession($dispatchId, $inspectorId)` - Get current active session
- `getSessionStats($sessionId)` - Get real-time statistics
- `getInspectorSessions($inspectorId, $limit)` - Get inspector's history
- `getSessionDuration($sessionId)` - Get human-readable duration

**Features**:
- Auto-resume existing sessions
- Duration tracking
- Progress percentage calculation
- Average time per bag estimation
- Estimated time remaining

## Database Schema Details

### bag_inspections Table Structure
```sql
CREATE TABLE bag_inspections (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dispatch_id INT NOT NULL,
    batch_id INT NOT NULL,
    bag_id VARCHAR(50) NOT NULL,
    bag_number INT NOT NULL,
    
    -- Expected values
    expected_weight_kg DECIMAL(10,2),
    expected_moisture DECIMAL(5,2),
    
    -- Actual values
    actual_weight_kg DECIMAL(10,2),
    actual_moisture DECIMAL(5,2),
    
    -- Variances
    weight_variance_kg DECIMAL(10,2),
    weight_variance_percent DECIMAL(5,2),
    moisture_variance DECIMAL(5,2),
    
    -- Status
    condition_status ENUM('good','damaged','wet','contaminated','missing'),
    has_discrepancy BOOLEAN DEFAULT FALSE,
    inspection_status ENUM('pending','inspected','skipped'),
    
    -- Documentation
    inspection_notes TEXT,
    photo_path VARCHAR(255),
    voice_note_path VARCHAR(255),
    
    -- Audit
    inspected_by INT,
    inspected_at DATETIME,
    inspection_duration_seconds INT,
    
    -- QR
    qr_scanned BOOLEAN DEFAULT FALSE,
    scan_timestamp DATETIME,
    device_info VARCHAR(255),
    
    created_at DATETIME,
    updated_at DATETIME,
    
    FOREIGN KEY (dispatch_id) REFERENCES dispatches(id),
    FOREIGN KEY (batch_id) REFERENCES batches(id),
    FOREIGN KEY (inspected_by) REFERENCES users(id)
);
```

### inspection_sessions Table Structure
```sql
CREATE TABLE inspection_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dispatch_id INT NOT NULL,
    batch_id INT NOT NULL,
    inspector_id INT NOT NULL,
    
    -- Timing
    started_at DATETIME,
    completed_at DATETIME,
    paused_at DATETIME,
    total_duration_seconds INT,
    
    -- Progress
    total_bags_expected INT DEFAULT 0,
    total_bags_inspected INT DEFAULT 0,
    total_bags_skipped INT DEFAULT 0,
    total_discrepancies INT DEFAULT 0,
    
    -- Weight summary
    expected_total_weight_kg DECIMAL(10,2),
    actual_total_weight_kg DECIMAL(10,2),
    weight_variance_percent DECIMAL(5,2),
    
    -- Status
    session_status ENUM('in_progress','completed','paused','cancelled'),
    
    -- Metadata
    device_type VARCHAR(50),
    inspection_mode VARCHAR(50),
    session_notes TEXT,
    
    created_at DATETIME,
    updated_at DATETIME,
    
    FOREIGN KEY (dispatch_id) REFERENCES dispatches(id),
    FOREIGN KEY (batch_id) REFERENCES batches(id),
    FOREIGN KEY (inspector_id) REFERENCES users(id)
);
```

## How to Run Migration

```bash
# Run the migration
php spark migrate

# Check migration status
php spark migrate:status

# Rollback if needed
php spark migrate:rollback
```

## Testing the Schema

### Test 1: Create Bag Inspection Record
```php
$bagInspectionModel = new \App\Models\BagInspectionModel();

$data = [
    'dispatch_id' => 1,
    'batch_id' => 1,
    'bag_id' => 'BTH-2024-001-B001',
    'bag_number' => 1,
    'expected_weight_kg' => 50.0,
    'expected_moisture' => 12.5,
    'actual_weight_kg' => 48.5,
    'actual_moisture' => 12.8,
    'condition_status' => 'good',
    'inspected_by' => 1,
    'qr_scanned' => true,
    'device_info' => 'mobile'
];

$bagInspectionModel->recordInspection($data);
// Auto-calculates variances and discrepancy flag
```

### Test 2: Start Inspection Session
```php
$sessionModel = new \App\Models\InspectionSessionModel();

$sessionId = $sessionModel->startSession(
    $dispatchId = 1,
    $batchId = 1,
    $inspectorId = 1,
    $deviceType = 'mobile'
);

// Update progress
$sessionModel->updateProgress($sessionId, [
    'total_bags_inspected' => 10,
    'total_discrepancies' => 2
]);

// Get stats
$stats = $sessionModel->getSessionStats($sessionId);
// Returns: progress_percent, bags_remaining, estimated_time_remaining
```

### Test 3: Get Inspection Summary
```php
$summary = $bagInspectionModel->getInspectionSummary($dispatchId);

// Returns:
// - total_bags
// - inspected, pending, skipped counts
// - with_discrepancies count
// - condition counts (good, damaged, wet, etc.)
// - weight totals and variance
```

## Module Communication

### How This Integrates:
1. **BatchReceivingController** will use these models
2. **Inspection Form** will call API endpoints that use these models
3. **Progress Dashboard** will query these tables for real-time stats
4. **QR Scanner** will record scans in bag_inspections table
5. **Session Tracking** will maintain state across page reloads

## Next Steps

✅ **Feature 1.1 Complete** - Database schema ready

**Next: Feature 1.2** - Build visual bag grid with status colors
- Will read from `bag_inspections` table
- Display status using `inspection_status` and `condition_status` fields
- Show progress using `getInspectionSummary()` method

## Files Created
1. ✅ `app/Database/Migrations/2025-01-27-150000_CreateBagInspectionTables.php`
2. ✅ `app/Models/BagInspectionModel.php`
3. ✅ `app/Models/InspectionSessionModel.php`
4. ✅ `PHASE1_FEATURE1.1_COMPLETE.md` (this file)

---

**Status**: ✅ READY FOR FEATURE 1.2
**Migration**: Run `php spark migrate` to apply changes
**Testing**: Use provided test examples to verify functionality
