# 🐛 Bug Report: Incorrect Remaining Quantity Calculation for Purchase Orders

## Issue Summary
When creating a batch from a purchase order that has existing dispatches, the system shows an **incorrect remaining quantity** calculation. The formula is counting batches instead of dispatches.

---

## 🔍 Root Cause Analysis

### The Problem
**Location:** `app/Controllers/PurchaseOrderController.php` - Line 460-500

The `search()` method calculates remaining quantity using:
```php
$builder->select('po.id, po.po_number, po.grain_type, po.quantity_mt, po.delivered_quantity_mt, po.status, s.name as supplier_name, COALESCE(SUM(b.total_weight_mt), 0) as transferred_quantity_mt');
$builder->join('batches b', 'b.purchase_order_id = po.id', 'left');
```

**Then calculates:**
```php
$transferredQty = (float)$po['transferred_quantity_mt']; // From batches
$totalQty = (float)$po['quantity_mt'];
$po['remaining_quantity_mt'] = max(0, $totalQty - $transferredQty);
```

### Why This Is Wrong

The system has a **Batch → Dispatch** workflow:
1. **Batch** = Created from PO (e.g., 250 MT)
2. **Dispatch** = Batch sent to warehouse (e.g., 250 MT)
3. **Inspection** = Dispatch received and inspected

**The bug:** The calculation uses `SUM(b.total_weight_mt)` from **batches** table, but it should use the **dispatches** table because:

- A batch can have **multiple dispatches** (partial deliveries)
- Only **inspected/delivered dispatches** should count toward fulfillment
- Batches that are created but not yet dispatched should NOT reduce remaining quantity

### Example Scenario

**Purchase Order:** 1000 MT

**Workflow:**
1. Create Batch #1: 250 MT (status: pending)
2. Create Batch #2: 250 MT (status: approved)
3. Create Dispatch from Batch #2: 250 MT (status: delivered)

**Current (Wrong) Calculation:**
```
Transferred = Batch #1 (250) + Batch #2 (250) = 500 MT
Remaining = 1000 - 500 = 500 MT ❌ WRONG
```

**Correct Calculation Should Be:**
```
Delivered = Dispatch from Batch #2 = 250 MT
Remaining = 1000 - 250 = 750 MT ✅ CORRECT
```

---

## 📊 System Architecture

### Current Data Flow
```
Purchase Order (1000 MT)
    ↓
Batch #1 (250 MT) → [No Dispatch Yet]
Batch #2 (250 MT) → Dispatch #1 (250 MT) → Inspected ✓
Batch #3 (250 MT) → Dispatch #2 (250 MT) → In Transit
Batch #4 (250 MT) → [Not Created Yet]
```

### What Should Count
- ✅ **Dispatch #1** (250 MT) - Inspected and delivered
- ❌ **Batch #1** (250 MT) - Created but not dispatched
- ❌ **Dispatch #2** (250 MT) - In transit, not yet received

**Only inspected/delivered dispatches should reduce remaining quantity**

---

## 🔧 Affected Code Sections

### 1. PurchaseOrderController::search() ⚠️ PRIMARY BUG
**File:** `app/Controllers/PurchaseOrderController.php`
**Lines:** 460-500

**Current Code:**
```php
$builder->select('po.id, po.po_number, po.grain_type, po.quantity_mt, po.delivered_quantity_mt, po.status, s.name as supplier_name, COALESCE(SUM(b.total_weight_mt), 0) as transferred_quantity_mt');
$builder->join('batches b', 'b.purchase_order_id = po.id', 'left');
```

**Should Be:**
```php
$builder->select('po.id, po.po_number, po.grain_type, po.quantity_mt, po.delivered_quantity_mt, po.status, s.name as supplier_name, COALESCE(SUM(d.actual_weight_mt), 0) as transferred_quantity_mt');
$builder->join('batches b', 'b.purchase_order_id = po.id', 'left');
$builder->join('dispatches d', 'd.batch_id = b.id AND d.status = "delivered"', 'left');
```

### 2. PurchaseOrderModel::updateStatusBasedOnTransfers() ⚠️ SECONDARY ISSUE
**File:** `app/Models/PurchaseOrderModel.php`
**Lines:** 378-420

**Current Code:**
```php
$batchModel = new \App\Models\BatchModel();
$transferredQuery = $batchModel->db->table('batches');
$transferredQuery->selectSum('total_weight_mt', 'total_transferred');
$transferredQuery->where('purchase_order_id', $purchaseOrderId);
$transferredResult = $transferredQuery->get()->getRowArray();
$totalTransferred = $transferredResult['total_transferred'] ?? 0;
```

**Should Be:**
```php
$dispatchModel = new \App\Models\DispatchModel();
$transferredQuery = $dispatchModel->db->table('dispatches d');
$transferredQuery->join('batches b', 'b.id = d.batch_id');
$transferredQuery->selectSum('d.actual_weight_mt', 'total_transferred');
$transferredQuery->where('b.purchase_order_id', $purchaseOrderId);
$transferredQuery->where('d.status', 'delivered'); // Only count delivered dispatches
$transferredResult = $transferredQuery->get()->getRowArray();
$totalTransferred = $transferredResult['total_transferred'] ?? 0;
```

### 3. BatchReceivingController::updatePOFulfillment() ✅ CORRECT
**File:** `app/Controllers/BatchReceivingController.php`
**Lines:** 358-380

This method is **CORRECT** - it updates PO fulfillment when a dispatch is inspected:
```php
$newDeliveredQuantity = $po['delivered_quantity_mt'] + $deliveredQuantityMt;
$newRemainingQuantity = max(0, $po['quantity_mt'] - $newDeliveredQuantity);
```

### 4. PurchaseOrderModel::getApprovedPOsForBatch() ⚠️ RELIES ON DB FIELD
**File:** `app/Models/PurchaseOrderModel.php`
**Lines:** 130-161

This method uses `po.remaining_quantity_mt` from the database, which is updated by the buggy methods above.

---

## 🎯 Impact Assessment

### Critical Issues
1. **Incorrect Remaining Quantity Display** - Users see wrong values when selecting PO for batch creation
2. **Premature PO Completion** - POs marked as "completed" when batches created but not delivered
3. **Over-allocation Risk** - System may prevent creating valid batches due to incorrect remaining calculation
4. **Reporting Inaccuracy** - All reports showing PO fulfillment are incorrect

### Affected Features
- ✅ Batch creation from PO (shows wrong remaining quantity)
- ✅ PO status updates (marks completed too early)
- ✅ PO search/dropdown (displays incorrect data)
- ✅ Dashboard statistics (wrong fulfillment percentages)
- ✅ Reports (inaccurate delivery tracking)

---

## ✅ Recommended Fix

### Solution 1: Use Dispatches Instead of Batches (Recommended)

**Change the calculation to count only delivered dispatches:**

```php
// In PurchaseOrderController::search()
$builder->select('
    po.id, 
    po.po_number, 
    po.grain_type, 
    po.quantity_mt, 
    po.delivered_quantity_mt, 
    po.status, 
    s.name as supplier_name, 
    COALESCE(SUM(CASE WHEN d.status = "delivered" THEN d.actual_weight_mt ELSE 0 END), 0) as transferred_quantity_mt
');
$builder->join('suppliers s', 's.id = po.supplier_id', 'left');
$builder->join('batches b', 'b.purchase_order_id = po.id', 'left');
$builder->join('dispatches d', 'd.batch_id = b.id', 'left');
```

### Solution 2: Use Database Field (Quick Fix)

**Use the `delivered_quantity_mt` field that's already being updated correctly:**

```php
// In PurchaseOrderController::search()
$builder->select('
    po.id, 
    po.po_number, 
    po.grain_type, 
    po.quantity_mt, 
    po.delivered_quantity_mt, 
    po.remaining_quantity_mt,
    po.status, 
    s.name as supplier_name
');
// Don't calculate transferred_quantity_mt, use delivered_quantity_mt instead
```

Then in the loop:
```php
foreach ($results as $po) {
    $deliveredQty = (float)$po['delivered_quantity_mt']; // From database
    $totalQty = (float)$po['quantity_mt'];
    
    // Skip if PO is completed
    if ($deliveredQty >= $totalQty) {
        continue;
    }
    
    $po['remaining_quantity_mt'] = max(0, $totalQty - $deliveredQty);
    $filteredResults[] = $po;
}
```

---

## 🧪 Testing Scenarios

### Test Case 1: PO with Batches but No Dispatches
```
PO: 1000 MT
Batch #1: 250 MT (pending, not dispatched)
Expected Remaining: 1000 MT ✅
Current Remaining: 750 MT ❌
```

### Test Case 2: PO with Dispatches In Transit
```
PO: 1000 MT
Batch #1: 250 MT → Dispatch #1 (in_transit)
Expected Remaining: 1000 MT ✅
Current Remaining: 750 MT ❌
```

### Test Case 3: PO with Delivered Dispatches
```
PO: 1000 MT
Batch #1: 250 MT → Dispatch #1 (delivered)
Expected Remaining: 750 MT ✅
Current Remaining: 750 MT ✅ (by accident)
```

### Test Case 4: Mixed Scenario
```
PO: 1000 MT
Batch #1: 250 MT (pending)
Batch #2: 250 MT → Dispatch #1 (delivered)
Batch #3: 250 MT → Dispatch #2 (in_transit)
Expected Remaining: 750 MT ✅
Current Remaining: 250 MT ❌
```

---

## 📝 Implementation Steps

1. **Backup Database** - Before making changes
2. **Update PurchaseOrderController::search()** - Use Solution 2 (quick fix)
3. **Update PurchaseOrderModel::updateStatusBasedOnTransfers()** - Use dispatches
4. **Test All Scenarios** - Verify calculations are correct
5. **Update Frontend Display** - Ensure UI shows correct values
6. **Verify Reports** - Check dashboard and reports accuracy
7. **Deploy to Production** - After thorough testing

---

## 🔗 Related Files to Review

1. `app/Controllers/PurchaseOrderController.php` - Primary fix location
2. `app/Models/PurchaseOrderModel.php` - Secondary fix location
3. `app/Controllers/BatchReceivingController.php` - Already correct
4. `app/Views/batches/create.php` - Frontend display
5. `app/Views/dashboard/index.php` - Statistics display

---

## 📌 Conclusion

**Root Cause:** System counts **batches** instead of **delivered dispatches** when calculating PO remaining quantity.

**Impact:** High - Affects batch creation, PO status, and all reporting.

**Fix Complexity:** Low - Simple query change in 2 locations.

**Recommended Action:** Implement Solution 2 (use database field) immediately, then refactor to Solution 1 (use dispatches) for long-term accuracy.

---

**Report Generated:** 2025-02-12  
**Priority:** HIGH  
**Status:** PENDING FIX
