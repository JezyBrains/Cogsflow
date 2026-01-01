# Fix: Dispatch Creation Error

## Problem
When trying to create a dispatch, you got the error:
> "Failed to create dispatch: Failed to create dispatch record"

## Root Cause
The `DispatchController` was trying to insert fields that don't exist in the `dispatches` table:

1. **`quantity_mt`** - This field doesn't exist in the dispatches table
   - The quantity is already stored in the linked batch (`batches.total_weight_mt`)
   - No need to duplicate this data

2. **`dispatch_date`** - This field doesn't exist in the dispatches table
   - The table uses `created_at` instead (auto-managed by CodeIgniter timestamps)

### Code Issue (Line 106-107):
```php
$dispatchData = [
    // ... other fields ...
    'quantity_mt' => $batch['total_weight_mt'],  // ❌ Column doesn't exist
    'dispatch_date' => date('Y-m-d H:i:s'),      // ❌ Column doesn't exist
    'status' => 'pending',
    'notes' => $this->request->getPost('notes')
];
```

## Solution Applied

### Removed Non-Existent Fields
Updated `app/Controllers/DispatchController.php` (lines 95-110):

```php
$dispatchData = [
    'dispatch_number' => $dispatchNumber,
    'batch_id' => $this->request->getPost('batch_id'),
    'vehicle_number' => strtoupper($this->request->getPost('vehicle_number')),
    'trailer_number' => $this->request->getPost('trailer_number') ? strtoupper($this->request->getPost('trailer_number')) : null,
    'driver_name' => $this->request->getPost('driver_name'),
    'driver_phone' => $this->request->getPost('driver_phone'),
    'driver_id_number' => $this->request->getPost('driver_id_number'),
    'dispatcher_name' => $this->request->getPost('dispatcher_name'),
    'destination' => $this->request->getPost('destination'),
    'estimated_arrival' => $this->request->getPost('estimated_arrival'),
    'status' => 'pending',
    'notes' => $this->request->getPost('notes')
    // Note: quantity_mt removed - use batch.total_weight_mt instead
    // Note: dispatch_date removed - created_at is auto-set by timestamps
];
```

## Why This Works

### 1. Quantity Information
- **Before**: Tried to store `quantity_mt` in dispatches table
- **After**: Get quantity from the linked batch when needed
- **Benefit**: Single source of truth, no data duplication

Example query to get dispatch with quantity:
```php
$dispatch = $this->dispatchModel
    ->select('dispatches.*, batches.total_weight_mt as quantity_mt')
    ->join('batches', 'batches.id = dispatches.batch_id')
    ->find($id);
```

### 2. Dispatch Date
- **Before**: Tried to manually set `dispatch_date`
- **After**: CodeIgniter automatically sets `created_at` timestamp
- **Benefit**: Consistent timestamp handling, no manual date management

## Dispatches Table Structure

### Actual Columns (from migration):
```
- id
- dispatch_number
- batch_id
- vehicle_number
- trailer_number
- driver_name
- driver_phone
- driver_id_number (added via migration)
- dispatcher_name
- destination
- estimated_arrival
- actual_departure
- actual_arrival
- status
- notes
- received_by (added via workflow migration)
- inspection_date (added via workflow migration)
- actual_bags (added via workflow migration)
- actual_weight_kg (added via workflow migration)
- actual_weight_mt (added via workflow migration)
- discrepancies (added via workflow migration)
- inspection_notes (added via workflow migration)
- created_at (auto-managed)
- updated_at (auto-managed)
```

### Fields NOT in Table:
- ❌ `quantity_mt` - Use `batches.total_weight_mt` instead
- ❌ `dispatch_date` - Use `created_at` instead
- ❌ `created_by` - Not implemented (could be added if needed)

## Testing

### Test 1: Create Dispatch ✅
1. Go to Dispatches page
2. Click "Create New Dispatch"
3. Select an approved batch
4. Fill in all required fields:
   - Vehicle number
   - Trailer number
   - Driver name
   - Driver phone
   - Driver ID number
   - Dispatcher name
   - Destination
   - Estimated arrival date
5. Submit
6. **Expected**: Dispatch created successfully

### Test 2: View Dispatch Details ✅
1. Open the created dispatch
2. **Expected**: All information displays correctly
3. **Expected**: Quantity shows from batch (via JOIN)

### Test 3: Dispatch Workflow ✅
1. Mark dispatch as "In Transit"
2. Mark dispatch as "Arrived"
3. Perform inspection
4. **Expected**: Status updates correctly through workflow

## Related Files Modified

1. **`app/Controllers/DispatchController.php`**
   - Removed `quantity_mt` from insert data
   - Removed `dispatch_date` from insert data

## Future Considerations

### If You Need Quantity in Dispatches:
Add a migration to create the column:
```php
$this->forge->addColumn('dispatches', [
    'quantity_mt' => [
        'type' => 'DECIMAL',
        'constraint' => '10,3',
        'null' => true,
        'after' => 'batch_id'
    ]
]);
```

Then add to `DispatchModel::$allowedFields`:
```php
protected $allowedFields = [
    // ... existing fields ...
    'quantity_mt',
];
```

### If You Need Created By Tracking:
Add a migration:
```php
$this->forge->addColumn('dispatches', [
    'created_by' => [
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => true,
        'null' => true,
        'after' => 'status'
    ]
]);
```

## Summary

**What was wrong**: Trying to insert non-existent columns  
**What was fixed**: Removed `quantity_mt` and `dispatch_date` from insert data  
**Result**: Dispatch creation now works!  

The quantity is available through the batch relationship, and the dispatch date is tracked via `created_at`.

---

**Date**: January 27, 2025  
**Status**: ✅ Fixed  
**File Modified**: `app/Controllers/DispatchController.php`
