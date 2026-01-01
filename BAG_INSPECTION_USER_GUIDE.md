# 🎯 Bag Inspection System - User Guide

## ✅ How It Works

### **1. View the Bag Grid**

When you open the inspection page, you'll see:
- **Train seat-style grid** with 10 columns
- **Bag icons** with numbers (like seat numbers)
- **Color-coded status**:
  - 🤍 **White/Gray** = Pending (not inspected)
  - 💚 **Green** = Good (inspected, no issues)
  - 💛 **Yellow** = Warning (has discrepancy)
  - ❤️ **Red** = Damaged/Wet
  - ⚫ **Gray Dashed** = Missing

---

### **2. Click a Bag to Inspect**

**Click any bag** in the grid → A modal pops up with:

#### **Expected Values** (shown at top):
- Expected Weight (from batch data)
- Expected Moisture (from batch data)

#### **Fields to Fill**:
1. **Actual Weight (kg)** - Required ⭐
   - Enter the actual weight you measured
   - System calculates variance automatically
   
2. **Actual Moisture (%)** - Optional
   - Enter moisture content if measured
   
3. **Bag Condition** - Required ⭐
   - ✅ **Good** - Bag is in perfect condition
   - ⚠️ **Damaged** - Bag has tears or damage
   - 💧 **Wet** - Bag is wet or damp
   - ❌ **Missing** - Bag is not present
   
4. **Additional Notes** - Optional
   - Add any observations or issues

---

### **3. Save the Inspection**

Click **"Save & Next"** button:
- ✅ Data is saved to database
- ✅ Bag color updates in grid
- ✅ Progress bar updates
- ✅ Modal automatically opens next pending bag
- ✅ Toast notification confirms save

**Cancel button**: Closes modal without saving

---

### **4. Automatic Features**

#### **Discrepancy Detection**:
- If weight variance > 2% → Bag marked as **Warning (Yellow)**
- If moisture variance > 1% → Bag marked as **Warning (Yellow)**
- If condition is NOT "Good" → Bag marked as **Damaged (Red)**

#### **Progress Tracking**:
- **Total**: Total bags in batch
- **Done**: Bags inspected
- **Pending**: Bags not inspected
- **Issues**: Bags with discrepancies

#### **Auto-Navigation**:
- After saving, automatically opens next pending bag
- Saves time during inspection

---

### **5. Quick Actions**

#### **Jump to Bag**:
- Type bag number in "Jump to bag #" field
- Press Enter
- Scrolls to that bag and opens it

#### **Filter Bags**:
- **All**: Show all bags
- **Pending**: Show only uninspected bags
- **OK**: Show only good bags

#### **Start Button**:
- Opens first pending bag
- Quick way to begin inspection

#### **Complete Button**:
- Enabled when all bags inspected
- Finalizes inspection and updates inventory

---

## 🎨 Visual Guide

### **Grid Layout**:
```
Row 1:  [🛍️1] [🛍️2] [🛍️3] [🛍️4] [🛍️5]  AISLE  [🛍️6] [🛍️7] [🛍️8] [🛍️9] [🛍️10]
Row 2:  [🛍️11] [🛍️12] [🛍️13] [🛍️14] [🛍️15]  AISLE  [🛍️16] [🛍️17] [🛍️18] [🛍️19] [🛍️20]
```

### **Bag States**:
- **Pending**: White with gray icon
- **Inspected (Good)**: Green with green icon
- **Inspected (Issue)**: Yellow/Red with colored icon
- **Active (Selected)**: Blue border, highlighted

### **Modal Layout**:
```
┌─────────────────────────────────┐
│ Bag #05 Inspection         [X] │
├─────────────────────────────────┤
│ Expected Values:                │
│ Weight: 50.0 kg | Moisture: 12% │
│                                 │
│ Actual Weight: [____] kg        │
│ Actual Moisture: [____] %       │
│                                 │
│ Condition:                      │
│ [Good] [Damaged] [Wet] [Missing]│
│                                 │
│ Notes: [________________]       │
├─────────────────────────────────┤
│        [Cancel] [Save & Next]   │
└─────────────────────────────────┘
```

---

## 🚀 Workflow Example

### **Inspecting 50 Bags**:

1. **Open inspection page**
   - See grid of 50 bags (all white/pending)

2. **Click "Start" or click Bag #1**
   - Modal opens with Bag #1 details

3. **Fill in data**:
   - Actual Weight: 49.8 kg
   - Condition: Good
   - Click "Save & Next"

4. **Bag #1 turns green**
   - Modal automatically opens Bag #2

5. **Continue for all bags**
   - System tracks progress
   - Can see at a glance which bags are done

6. **When all done**:
   - Progress shows 50/50
   - Click "Complete" to finalize

---

## 💡 Tips

### **Fast Inspection**:
- Use "Save & Next" to auto-advance
- Skip optional fields if not needed
- Use keyboard: Tab to move between fields, Enter to save

### **Finding Bags**:
- Use "Jump to bag #" for quick access
- Filter by "Pending" to see only uninspected bags
- Scroll through grid to see all at once

### **Handling Issues**:
- Mark damaged bags immediately
- Add notes for any unusual observations
- Yellow/red bags are flagged for review

### **Offline Support**:
- System saves data even if connection drops
- Auto-syncs when connection returns
- Toast notifications keep you informed

---

## 🎯 Summary

**Click bag → Fill data → Save → Next bag → Repeat**

Simple, fast, and efficient! 🚀
