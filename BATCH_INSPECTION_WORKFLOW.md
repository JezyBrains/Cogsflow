# Batch Inspection Workflow Documentation

## Overview
The batch inspection system enforces strict operational accuracy with segregation of duties for grain batch receiving and quality control.

---

## 🔄 Complete Workflow

### 1. **Purchase Order Creation**
```
Status: pending → approved
```
- PO created with grain type, quantity, supplier
- Requires approval before batches can be created
- Tracks fulfillment progress

### 2. **Batch Creation**
```
Status: pending
```
- **Must link to approved PO**
- Records bags, weights, moisture content
- Generates unique batch number
- Validates against PO constraints (grain type, quantity)
- Created by warehouse staff

### 3. **Batch Approval**
```
Status: pending → approved
```
- **Same user who approved PO must approve batch** (or admin override)
- Validates consistency with PO
- Only approved batches can be dispatched

### 4. **Dispatch Creation**
```
Status: pending → in_transit → arrived
```
- Created from approved batches only
- Records vehicle, driver, destination
- Tracks estimated/actual arrival times
- Status progression:
  - **pending**: Dispatch created, not yet departed
  - **in_transit**: Vehicle departed
  - **arrived**: Vehicle arrived at destination

### 5. **Batch Inspection** ⭐ (Current Focus)
```
Status: arrived → delivered
```
- **STRICT WORKFLOW**: Only dispatches with status 'arrived' can be inspected
- **Segregation of Duties**: Inspector ≠ Batch Creator
- Records actual quantities vs expected
- Detects and logs discrepancies
- Updates inventory automatically
- Marks batch and dispatch as 'delivered'

---

## 📋 Batch Inspection Details

### Access Points

#### Route 1: Batch Receiving Dashboard
```
URL: /batch-receiving
Controller: BatchReceivingController::index()
View: batch_receiving/index.php
```

**Shows**:
- List of arrived dispatches awaiting inspection
- Recent inspections by current user
- Statistics (pending, completed today, total)

#### Route 2: Inspection Form
```
URL: /batch-receiving/inspection/{dispatch_id}
Controller: BatchReceivingController::inspectionForm($dispatchId)
View: batch_receiving/inspection_form.php
```

**Shows**:
- Dispatch details
- Expected vs Actual comparison interface
- Bag-by-bag inspection capability (optional)
- Discrepancy detection

#### Route 3: Process Inspection
```
URL: /batch-receiving/process-inspection (POST)
Controller: BatchReceivingController::processInspection()
```

**Processes**:
- Validation of inspection data
- Discrepancy calculation
- Database updates (dispatch, batch, PO, inventory)
- Notifications

---

## 🔒 Segregation of Duties

### Rule: Inspector ≠ Creator

**Enforced at**:
1. **inspectionForm()** - Line 139
   ```php
   if (isset($dispatch['created_by']) && $dispatch['created_by'] === $currentUser) {
       return redirect()->to('/batch-receiving')
           ->with('error', 'You cannot inspect a batch you created');
   }
   ```

2. **processInspection()** - Line 193
   ```php
   if ($dispatch['created_by'] === $currentUser) {
       return redirect()->to('/batch-receiving')
           ->with('error', 'Segregation of duties violation');
   }
   ```

**Why?**: Prevents conflicts of interest and ensures independent verification.

---

## 📊 Inspection Process Flow

### Step 1: Validation Checks

```php
// 1. Dispatch exists
if (!$dispatch) {
    return error('Dispatch not found');
}

// 2. Status must be 'arrived'
if ($dispatch['status'] !== 'arrived') {
    return error('Status must be "arrived"');
}

// 3. Not already inspected
if (!empty($dispatch['received_by'])) {
    return error('Already inspected');
}

// 4. Segregation of duties
if ($dispatch['created_by'] === $currentUser) {
    return error('Cannot inspect own batch');
}
```

### Step 2: Data Collection

**Required Fields**:
- `actual_bags` - Number of bags received
- `actual_weight_kg` - Total weight in kilograms
- `inspection_notes` - Optional notes

**Calculated**:
- `actual_weight_mt` = actual_weight_kg / 1000

### Step 3: Discrepancy Detection

```php
calculateDiscrepancies($dispatch, $actualBags, $actualWeightKg)
```

**Tolerance Thresholds**:
- **Bags**: 0 tolerance (exact match required)
- **Weight**: 2% tolerance

**Returns**:
```json
{
    "has_discrepancies": true/false,
    "bags": {
        "expected": 100,
        "actual": 98,
        "difference": -2,
        "has_discrepancy": true
    },
    "weight_kg": {
        "expected": 5000.00,
        "actual": 4950.00,
        "difference": -50.00,
        "percentage_diff": -1.00,
        "has_discrepancy": false
    },
    "tolerance_thresholds": {
        "bags": 0,
        "weight_percent": 2.0
    }
}
```

### Step 4: Database Updates (Transaction)

**1. Update Dispatch**:
```php
[
    'received_by' => $currentUser,
    'inspection_date' => now(),
    'actual_bags' => $actualBags,
    'actual_weight_kg' => $actualWeightKg,
    'actual_weight_mt' => $actualWeightMt,
    'discrepancies' => json_encode($discrepancies),
    'inspection_notes' => $inspectionNotes,
    'status' => 'delivered'  // ✅ Status changes here
]
```

**2. Update Batch**:
```php
[
    'status' => 'delivered',
    'updated_at' => now()
]
```

**3. Update Purchase Order Fulfillment**:
```php
[
    'delivered_quantity_mt' => $po['delivered_quantity_mt'] + $actualWeightMt,
    'remaining_quantity_mt' => $po['quantity_mt'] - $newDeliveredQuantity,
    'status' => $remaining <= 0 ? 'completed' : 'approved'
]
```

**4. Update Inventory**:
```php
// Record adjustment
InventoryAdjustmentModel::recordAdjustment([
    'grain_type' => $grainType,
    'adjustment_type' => 'Batch Delivery',
    'quantity' => $actualWeightMt,
    'reference' => "Batch #{$batchNumber}",
    'batch_id' => $batchId,
    'dispatch_id' => $dispatchId,
    'reason' => "Batch delivery inspection completed",
    'adjusted_by' => $currentUser,
    'discrepancy_data' => $discrepancies
]);

// Update inventory record
InventoryModel::update([
    'last_updated_by' => $currentUser,
    'last_batch_id' => $batchId
]);
```

**5. Log Batch History**:
```php
batch_history table:
[
    'batch_id' => $batchId,
    'action' => 'delivered',
    'performed_by' => $currentUser,
    'performed_at' => now(),
    'details' => json_encode($discrepancies)
]
```

### Step 5: Notifications

**1. Notify Batch Creator**:
```
"Batch #{batch_number} has been inspected and delivered"
+ " with discrepancies" (if applicable)
```

**2. Notify Management** (if discrepancies):
```
"Discrepancies found in batch #{batch_number}"
```

---

## 🎯 Key Features

### 1. **Strict Status Workflow**
```
Cannot skip steps:
✅ pending → in_transit → arrived → [INSPECTION] → delivered
❌ pending → delivered (blocked)
❌ arrived → delivered (must use inspection)
```

### 2. **Automatic Inventory Updates**
- Inventory adjusted based on **actual** quantities (not expected)
- Batch-wise traceability maintained
- Discrepancies logged for audit

### 3. **Multi-Dispatch Support**
- Large POs can be split across multiple trucks
- Each dispatch inspected separately
- Cumulative fulfillment tracked under original PO

### 4. **Discrepancy Management**
- Automatic detection with tolerance thresholds
- Logged in JSON format for analysis
- Alerts sent to management
- Full audit trail

### 5. **Bag-by-Bag Inspection** (Optional)
```
Routes:
- POST /batch-receiving/get-bag-details
- POST /batch-receiving/process-bag-inspection
- GET /batch-receiving/get-inspections
```

**Allows**:
- Individual bag condition assessment
- Weight/moisture verification per bag
- Damage/contamination tracking

---

## 📁 Database Tables Involved

### 1. **dispatches**
```sql
Columns used in inspection:
- id
- batch_id
- status (arrived → delivered)
- received_by (inspector user ID)
- inspection_date
- actual_bags
- actual_weight_kg
- actual_weight_mt
- discrepancies (JSON)
- inspection_notes
```

### 2. **batches**
```sql
Columns used:
- id
- batch_number
- status (approved → delivered)
- total_bags (expected)
- total_weight_kg (expected)
- total_weight_mt (expected)
- grain_type
- supplier_id
- purchase_order_id
```

### 3. **purchase_orders**
```sql
Columns updated:
- delivered_quantity_mt
- remaining_quantity_mt
- status (approved → completed when fulfilled)
```

### 4. **inventory**
```sql
Columns updated:
- quantity (increased by actual_weight_mt)
- last_updated_by
- last_batch_id
```

### 5. **inventory_adjustments**
```sql
New record created:
- grain_type
- adjustment_type: 'Batch Delivery'
- quantity
- reference: "Batch #{batch_number}"
- batch_id
- dispatch_id
- reason
- adjusted_by
- discrepancy_data (JSON)
```

### 6. **batch_history**
```sql
New record created:
- batch_id
- action: 'delivered'
- performed_by
- performed_at
- details (JSON with discrepancies)
```

---

## 🔍 Inspection Form Fields

### Display Fields (Read-only)
- Dispatch Number
- Batch Number
- Grain Type
- Supplier Name
- PO Number
- Vehicle Number
- Driver Name
- Arrival Date/Time
- **Expected Bags**
- **Expected Weight (kg)**
- **Expected Weight (MT)**

### Input Fields (Editable)
- **Actual Bags** (required, integer > 0)
- **Actual Weight (kg)** (required, decimal > 0)
- **Inspection Notes** (optional, max 1000 chars)

### Calculated/Displayed
- Actual Weight (MT) - auto-calculated
- Bag Difference
- Weight Difference (kg)
- Weight Difference (%)
- Discrepancy Status (within/outside tolerance)

---

## 🚨 Error Handling

### Common Errors

**1. Invalid Dispatch ID**
```
Error: "Invalid dispatch ID provided"
Redirect: /batch-receiving
```

**2. Dispatch Not Found**
```
Error: "Dispatch not found"
Redirect: /batch-receiving
```

**3. Wrong Status**
```
Error: "This dispatch is not ready for inspection. 
       Status must be 'arrived'. Current status: {status}"
Redirect: /batch-receiving
```

**4. Already Inspected**
```
Error: "This dispatch has already been inspected"
Redirect: /batch-receiving
```

**5. Segregation Violation**
```
Error: "You cannot inspect a batch you created. 
       Please assign to another warehouse officer."
Redirect: /batch-receiving
```

**6. Validation Errors**
```
Error: "Validation failed"
Fields: actual_bags, actual_weight_kg
Redirect: back with input
```

**7. Transaction Failed**
```
Error: "Inspection failed: {exception message}"
Action: Rollback all changes
Redirect: back with input
```

---

## 📱 User Interface

### Dashboard (`/batch-receiving`)

**Sections**:
1. **Statistics Cards**
   - Pending Inspections
   - Completed Today
   - Total Completed

2. **Pending Inspections Table**
   - Dispatch #
   - Batch #
   - Grain Type
   - Supplier
   - Vehicle
   - Arrived At
   - Actions: [Inspect] button

3. **Recent Inspections Table**
   - Dispatch #
   - Batch #
   - Inspected At
   - Discrepancies (Yes/No)
   - Actions: [View Report]

### Inspection Form (`/batch-receiving/inspection/{id}`)

**Layout**:
1. **Header**: Batch & Dispatch Info
2. **Expected Values** (left column)
3. **Actual Values** (right column - input fields)
4. **Discrepancy Indicators** (real-time)
5. **Notes Section**
6. **Action Buttons**: [Cancel] [Submit Inspection]

---

## 🔐 Permissions

### Required Role
```php
Filter: 'role:admin,warehouse_staff'
```

**Allowed Roles**:
- `admin` - Full access
- `warehouse_staff` - Full access

**Blocked Roles**:
- `standard_user` - No access
- Other roles - No access

---

## 📊 Reporting

### Available Reports

**1. Inspection Report**
```
URL: /batch-receiving/export-report/{dispatch_id}
Format: PDF/HTML
Includes: All inspection details, discrepancies, signatures
```

**2. Batch History**
```
URL: /batch-receiving/batch-history/{batch_id}
Format: JSON
Includes: All dispatches, inspections, timeline
```

**3. Bag Labels**
```
URL: /batch-receiving/print-labels/{dispatch_id}
Format: HTML (printable)
Includes: QR codes, bag IDs, weights
```

---

## 🧪 Testing Checklist

### Test 1: Normal Inspection (No Discrepancies)
- [ ] Create dispatch with status 'arrived'
- [ ] Go to /batch-receiving
- [ ] Click "Inspect" on dispatch
- [ ] Enter actual values matching expected
- [ ] Submit
- [ ] **Expected**: Status → 'delivered', inventory updated

### Test 2: Inspection with Discrepancies
- [ ] Create dispatch with status 'arrived'
- [ ] Enter actual values different from expected
- [ ] Submit
- [ ] **Expected**: Discrepancies logged, notifications sent

### Test 3: Segregation of Duties
- [ ] Create batch as User A
- [ ] Try to inspect as User A
- [ ] **Expected**: Error - cannot inspect own batch
- [ ] Inspect as User B
- [ ] **Expected**: Success

### Test 4: Wrong Status
- [ ] Try to inspect dispatch with status 'pending'
- [ ] **Expected**: Error - status must be 'arrived'

### Test 5: Already Inspected
- [ ] Inspect a dispatch
- [ ] Try to inspect again
- [ ] **Expected**: Error - already inspected

### Test 6: PO Fulfillment
- [ ] Create PO for 1000 MT
- [ ] Create 4 batches of 250 MT each
- [ ] Inspect all 4 dispatches
- [ ] **Expected**: PO status → 'completed'

---

## 🎯 Summary

**Inspection Workflow**:
1. Dispatch arrives → Status: 'arrived'
2. Inspector opens inspection form
3. Records actual quantities
4. System calculates discrepancies
5. Updates: dispatch, batch, PO, inventory
6. Status → 'delivered'
7. Notifications sent

**Key Principles**:
- ✅ Strict workflow enforcement
- ✅ Segregation of duties
- ✅ Automatic discrepancy detection
- ✅ Complete audit trail
- ✅ Batch-wise traceability
- ✅ Real-time inventory updates

**Current State**: Fully implemented and operational ✅
