# Phase 1 Testing Instructions

## 🚀 Quick Start

### 1. Run Migration
```bash
cd "/Users/noobmaster69/Downloads/nipo final"
php spark migrate
```

### 2. Verify Installation
```bash
php spark migrate:status
```

Should show:
```
✅ 2025-01-27-150000_CreateBagInspectionTables ... Ran
```

### 3. Check Database
```sql
-- Verify tables exist
SHOW TABLES LIKE '%inspection%';

-- Should return:
-- bag_inspections
-- inspection_sessions

-- Check structure
DESC bag_inspections;
DESC inspection_sessions;
```

## 📋 Test Scenario

### Scenario: Inspect 100-Bag Batch

**Setup:**
1. Create PO: 100 MT of Maize
2. Approve PO
3. Create Batch: 100 bags × 50kg each = 5000kg
4. Approve Batch
5. Create Dispatch
6. Mark as "In Transit"
7. Mark as "Arrived"

**Test:**
1. Navigate to `/batch-receiving`
2. Click "Inspect" on arrived dispatch
3. Visual bag grid should appear with 100 gray cards
4. Click bag #1
5. Modal opens with expected weight: 50kg
6. Enter actual weight: 49.5kg (within tolerance)
7. Save → Card turns green ✓
8. Bag #2 auto-opens
9. Enter actual weight: 47kg (outside tolerance)
10. Save → Card turns yellow ⚠
11. Continue inspecting...
12. When all done → "Complete" button enables
13. Click Complete → Inventory updates

## 🧪 Test Cases

### Test 1: Normal Bag (No Issues)
- **Input**: 50.0kg actual (expected: 50.0kg)
- **Expected**: Green card ✓
- **Variance**: 0%

### Test 2: Minor Variance (Within Tolerance)
- **Input**: 49.0kg actual (expected: 50.0kg)
- **Expected**: Green card ✓
- **Variance**: -2% (within 2% tolerance)

### Test 3: Major Variance (Outside Tolerance)
- **Input**: 47.5kg actual (expected: 50.0kg)
- **Expected**: Yellow card ⚠
- **Variance**: -5% (outside 2% tolerance)

### Test 4: Damaged Bag
- **Input**: 48kg, condition: "Damaged"
- **Expected**: Red card ⚠
- **Status**: Damaged

### Test 5: Missing Bag
- **Input**: 0kg, condition: "Missing"
- **Expected**: Faded red card ❌
- **Status**: Missing

### Test 6: Wet Bag
- **Input**: 50kg, condition: "Wet", moisture: 15%
- **Expected**: Red card ⚠
- **Status**: Wet

## 📱 Mobile Testing

### Test on Different Devices:
1. **Desktop** (>768px): 70px cards, 10 columns
2. **Tablet** (768px): 60px cards, 8 columns
3. **Mobile** (576px): 50px cards, 6 columns

### Mobile Checklist:
- [ ] Grid adjusts to screen size
- [ ] Cards are touch-friendly
- [ ] Modal is scrollable
- [ ] Buttons are large enough
- [ ] No horizontal scroll
- [ ] Text is readable

## 🔄 Real-Time Updates Test

### Test Progress Tracking:
1. Open inspection form
2. Note initial stats: 0/100 inspected
3. Inspect 1 bag
4. **Expected**: Stats update to 1/100
5. **Expected**: Progress bar moves to 1%
6. Inspect 10 more bags
7. **Expected**: Stats show 11/100
8. **Expected**: Progress bar at 11%
9. Click "Refresh"
10. **Expected**: Data reloads from server

## 🎨 Visual Tests

### Color Coding:
- [ ] Pending bags are gray with ⏸ icon
- [ ] OK bags are green with ✓ icon
- [ ] Warning bags are yellow with ⚠ icon
- [ ] Damaged bags are red with ⚠ icon
- [ ] Missing bags are faded red with ❌ icon

### Hover Effects:
- [ ] Bag cards scale up on hover
- [ ] Shadow appears on hover
- [ ] Cursor changes to pointer

### Animations:
- [ ] Progress bar animates smoothly
- [ ] Pending bags pulse gently
- [ ] Modal fades in/out

## 🔌 API Testing

### Test GET Endpoint:
```bash
# Replace dispatch_id with actual ID
curl "http://localhost:8080/batch-receiving/api/bag-inspection-data?dispatch_id=1"
```

**Expected Response:**
```json
{
  "success": true,
  "bag_inspections": [
    {
      "id": 1,
      "bag_id": "BTH-2024-001-B001",
      "bag_number": 1,
      "expected_weight_kg": "50.00",
      "inspection_status": "pending",
      "condition_status": "good"
    }
  ],
  "summary": {
    "total_bags": 100,
    "inspected": 0,
    "pending": 100,
    "with_discrepancies": 0
  }
}
```

### Test POST Endpoint:
```bash
curl -X POST http://localhost:8080/batch-receiving/api/record-bag-inspection \
  -H "Content-Type: application/json" \
  -d '{
    "dispatch_id": 1,
    "bag_id": "BTH-2024-001-B001",
    "actual_weight_kg": 49.5,
    "actual_moisture": 12.5,
    "condition_status": "good",
    "inspection_notes": "Test inspection"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Bag inspection recorded successfully",
  "inspection": {
    "id": 1,
    "bag_id": "BTH-2024-001-B001",
    "actual_weight_kg": "49.50",
    "weight_variance_percent": "-1.00",
    "has_discrepancy": false,
    "inspection_status": "inspected"
  }
}
```

## ⚠️ Error Scenarios

### Test Error Handling:

**1. Invalid Dispatch ID:**
```
URL: /batch-receiving/inspection/999999
Expected: Error message "Dispatch not found"
```

**2. Wrong Status:**
```
Dispatch status: "pending"
Expected: Error "Status must be arrived"
```

**3. Already Inspected:**
```
Dispatch with received_by set
Expected: Error "Already inspected"
```

**4. Segregation Violation:**
```
Same user created batch and tries to inspect
Expected: Error "Cannot inspect own batch"
```

**5. Missing Required Field:**
```
POST without actual_weight_kg
Expected: Error "Missing required field"
```

## 📊 Performance Testing

### Load Test:
1. Create batch with 500 bags
2. Open inspection form
3. **Expected**: Grid loads in <2 seconds
4. **Expected**: Scrolling is smooth
5. **Expected**: Modal opens instantly

### Memory Test:
1. Inspect 100 bags continuously
2. **Expected**: No memory leaks
3. **Expected**: Browser remains responsive

## ✅ Acceptance Criteria

### Phase 1 is successful if:
- [x] Migration runs without errors
- [x] Tables created correctly
- [x] Visual bag grid displays
- [x] Bags are color-coded by status
- [x] Click bag opens modal
- [x] Save updates bag card
- [x] Progress bar updates in real-time
- [x] Stats dashboard shows correct counts
- [x] Mobile layout is responsive
- [x] API endpoints return correct data
- [x] Variance calculation is accurate
- [x] Complete button enables when done
- [x] No JavaScript errors in console
- [x] No PHP errors in logs

## 🐛 Known Issues / Limitations

### Phase 1 Limitations:
1. **QR Scanner**: Placeholder only (Phase 2)
2. **Photo Capture**: Not implemented (Phase 2)
3. **Voice Notes**: Not implemented (Phase 2)
4. **Offline Mode**: Not implemented (Phase 2)
5. **Bulk Scanning**: Not implemented (Phase 3)

### Expected Behavior:
- Clicking "Scan QR" shows alert (Phase 1.3 placeholder)
- Session tracking is basic (enhanced in Phase 2.4)

## 📝 Test Report Template

```
# Phase 1 Test Report

Date: ___________
Tester: ___________
Environment: ___________

## Database
- [ ] Migration successful
- [ ] Tables created
- [ ] Sample data inserted

## Visual Grid
- [ ] Grid displays correctly
- [ ] Color coding works
- [ ] Hover effects work
- [ ] Mobile responsive

## Inspection Flow
- [ ] Modal opens on click
- [ ] Form validation works
- [ ] Save updates card
- [ ] Auto-advance works

## Progress Tracking
- [ ] Stats update correctly
- [ ] Progress bar animates
- [ ] Refresh works

## API
- [ ] GET endpoint works
- [ ] POST endpoint works
- [ ] Error handling works

## Issues Found:
1. ___________
2. ___________
3. ___________

## Overall Status: PASS / FAIL

Notes:
___________
```

## 🎯 Next Steps After Testing

### If Tests Pass:
1. ✅ Mark Phase 1 as complete
2. 🚀 Begin Phase 2 implementation
3. 📸 Add photo capture
4. 💾 Implement offline mode
5. 🎤 Add voice notes

### If Tests Fail:
1. 🐛 Document all issues
2. 🔧 Fix critical bugs first
3. ✅ Re-test
4. 📝 Update documentation

---

**Ready to test Phase 1!** 🎉

Start with the migration and work through the test scenarios.
