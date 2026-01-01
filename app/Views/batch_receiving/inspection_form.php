<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Bag-by-Bag Inspection<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
<li class="breadcrumb-item"><a href="<?= base_url('/batches') ?>">Batch Management</a></li>
<li class="breadcrumb-item"><a href="<?= base_url('/batch-receiving') ?>">Batch Receiving</a></li>
<li class="breadcrumb-item active">Bag Inspection</li>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<div class="d-flex gap-2">
    <button type="button" class="btn btn-outline-primary" onclick="scanBagQR()">
        <i class="bx bx-qr-scan me-1"></i> Scan QR Code
    </button>
    <button type="button" class="btn btn-outline-secondary" onclick="printBagLabels()">
        <i class="bx bx-printer me-1"></i> Print Labels
    </button>
    <a href="<?= base_url('/batch-receiving') ?>" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Batch Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-package me-2"></i>Batch <?= esc($dispatch['batch_number']) ?> - Individual Bag Inspection
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-info"><?= esc($dispatch['grain_type']) ?></span>
                        <span class="badge bg-secondary"><?= esc($dispatch['supplier_name'] ?? 'N/A') ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6 class="text-muted">Total Bags</h6>
                                <h4 class="text-primary"><?= number_format($dispatch['total_bags']) ?></h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6 class="text-muted">Expected Weight</h6>
                                <h4 class="text-success"><?= number_format($dispatch['total_weight_mt'], 3) ?> MT</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6 class="text-muted">Inspected</h6>
                                <h4 class="text-warning" id="inspectedCount">0</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6 class="text-muted">Progress</h6>
                                <div class="progress">
                                    <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small id="progressText">0%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bag Scanning Interface -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bx bx-qr-scan me-2"></i>Scan/Enter Bag ID
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="bagIdInput" class="form-label">Bag ID or QR Code</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="bagIdInput" placeholder="BTH-2024-001-B001 or scan QR">
                            <button class="btn btn-primary" type="button" onclick="loadBagForInspection()">
                                <i class="bx bx-search"></i> Load Bag
                            </button>
                        </div>
                        <div class="form-text">Scan QR code or manually enter bag ID</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bx bx-info-circle me-2"></i>Current Bag Info
                    </h6>
                </div>
                <div class="card-body" id="currentBagInfo">
                    <div class="text-center text-muted">
                        <i class="bx bx-package display-4"></i>
                        <p class="mt-2">Scan or enter a bag ID to start inspection</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inspection Form -->
    <div class="row mb-4" id="inspectionFormRow" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bx bx-clipboard-check me-2"></i>Bag Inspection Details
                    </h6>
                </div>
                <div class="card-body">
                    <form id="bagInspectionForm">
                        <input type="hidden" id="currentBagId" name="bag_id">
                        <input type="hidden" id="dispatchId" name="dispatch_id" value="<?= $dispatch['id'] ?>">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Expected Weight (kg)</label>
                                    <input type="number" class="form-control" id="expectedWeight" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="actualWeight" class="form-label">Actual Weight (kg) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="actualWeight" name="actual_weight" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Weight Difference</label>
                                    <input type="text" class="form-control" id="weightDifference" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Expected Moisture (%)</label>
                                    <input type="number" class="form-control" id="expectedMoisture" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="actualMoisture" class="form-label">Actual Moisture (%)</label>
                                    <input type="number" class="form-control" id="actualMoisture" name="actual_moisture" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Moisture Difference</label>
                                    <input type="text" class="form-control" id="moistureDifference" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="conditionStatus" class="form-label">Bag Condition</label>
                                    <select class="form-select" id="conditionStatus" name="condition_status" required>
                                        <option value="good">Good</option>
                                        <option value="damaged">Damaged</option>
                                        <option value="wet">Wet</option>
                                        <option value="contaminated">Contaminated</option>
                                        <option value="missing">Missing</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inspectionNotes" class="form-label">Inspection Notes</label>
                                    <textarea class="form-control" id="inspectionNotes" name="inspection_notes" rows="2" placeholder="Any observations or issues..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="clearCurrentBag()">
                                <i class="bx bx-x me-1"></i> Clear
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bx bx-check me-1"></i> Complete Bag Inspection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Inspection Progress Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="bx bx-list-check me-2"></i>Inspection Progress
                    </h6>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="exportInspectionData()">
                            <i class="bx bx-export me-1"></i> Export
                        </button>
                        <button class="btn btn-sm btn-success" onclick="completeAllInspections()" id="completeAllBtn" disabled>
                            <i class="bx bx-check-double me-1"></i> Complete All
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="inspectionTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Bag ID</th>
                                    <th>Expected Weight</th>
                                    <th>Actual Weight</th>
                                    <th>Weight Diff</th>
                                    <th>Expected Moisture</th>
                                    <th>Actual Moisture</th>
                                    <th>Moisture Diff</th>
                                    <th>Condition</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="inspectionTableBody">
                                <!-- Dynamically populated -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Original Batch Information (Collapsed) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-link p-0 text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#batchDetails">
                        <i class="bx bx-info-circle me-2"></i>Batch Details
                        <i class="bx bx-chevron-down ms-2"></i>
                    </button>
                </div>
                <div class="collapse" id="batchDetails">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Batch Information</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Batch Number</label>
                        <div class="form-control-plaintext">
                            <span class="badge bg-primary fs-6"><?= esc($dispatch['batch_number']) ?></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Supplier</label>
                        <div class="form-control-plaintext"><?= esc($dispatch['supplier_name'] ?? 'N/A') ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Grain Type</label>
                        <div class="form-control-plaintext">
                            <span class="badge bg-info"><?= esc($dispatch['grain_type']) ?></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">PO Number</label>
                        <div class="form-control-plaintext">
                            <?php if ($dispatch['po_number']): ?>
                                <span class="badge bg-secondary"><?= esc($dispatch['po_number']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Expected Weight</label>
                        <div class="form-control-plaintext">
                            <strong><?= number_format($dispatch['total_weight_mt'], 3) ?> MT</strong>
                            <small class="text-muted d-block">(<?= number_format($dispatch['total_weight_kg']) ?> kg)</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Expected Bags</label>
                        <div class="form-control-plaintext">
                            <strong><?= number_format($dispatch['total_bags']) ?></strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Average Moisture</label>
                        <div class="form-control-plaintext">
                            <?php if ($dispatch['average_moisture']): ?>
                                <?= number_format($dispatch['average_moisture'], 2) ?>%
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dispatch Number</label>
                        <div class="form-control-plaintext">
                            <span class="badge bg-warning"><?= esc($dispatch['dispatch_number']) ?></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Arrival Date</label>
                        <div class="form-control-plaintext">
                            <?php if ($dispatch['actual_arrival']): ?>
                                <?= date('M d, Y H:i', strtotime($dispatch['actual_arrival'])) ?>
                            <?php else: ?>
                                <span class="text-muted">Not recorded</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inspection Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Physical Inspection</h4>
                    <p class="text-muted mb-4">
                        Please verify the actual quantities received and record any discrepancies.
                    </p>

                    <form action="<?= base_url('batch-receiving/process-inspection') ?>" method="POST" id="inspectionForm">
                        <?= csrf_field() ?>
                        <input type="hidden" name="dispatch_id" value="<?= $dispatch['id'] ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="actual_bags" class="form-label">Actual Bags Received <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="actual_bags" name="actual_bags" 
                                           value="<?= $dispatch['total_bags'] ?>" min="0" required>
                                    <div class="form-text">Expected: <?= number_format($dispatch['total_bags']) ?> bags</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="actual_weight_kg" class="form-label">Actual Weight (kg) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="actual_weight_kg" name="actual_weight_kg" 
                                           value="<?= $dispatch['total_weight_kg'] ?>" min="0" step="0.001" required>
                                    <div class="form-text">Expected: <?= number_format($dispatch['total_weight_kg']) ?> kg</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Calculated Weight (MT)</label>
                                    <input type="text" class="form-control" id="calculated_weight_mt" readonly>
                                    <div class="form-text">Auto-calculated from kg value</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Weight Difference</label>
                                    <input type="text" class="form-control" id="weight_difference" readonly>
                                    <div class="form-text">Difference from expected weight</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="inspection_notes" class="form-label">Inspection Notes</label>
                            <textarea class="form-control" id="inspection_notes" name="inspection_notes" rows="4" 
                                      placeholder="Record any observations, quality issues, or discrepancies found during inspection..."></textarea>
                        </div>

                        <!-- Quality Assessment -->
                        <div class="mb-4">
                            <h5 class="mb-3">Quality Assessment</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="moisture_content" class="form-label">Moisture Content (%)</label>
                                        <input type="number" class="form-control" id="moisture_content" name="moisture_content" 
                                               step="0.01" min="0" max="100" placeholder="e.g., 12.5">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="quality_grade" class="form-label">Quality Grade</label>
                                        <select class="form-select" id="quality_grade" name="quality_grade">
                                            <option value="">Select Grade</option>
                                            <option value="Grade A">Grade A</option>
                                            <option value="Grade B">Grade B</option>
                                            <option value="Grade C">Grade C</option>
                                            <option value="Rejected">Rejected</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="foreign_matter" class="form-label">Foreign Matter (%)</label>
                                        <input type="number" class="form-control" id="foreign_matter" name="foreign_matter" 
                                               step="0.01" min="0" max="100" placeholder="e.g., 2.0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Discrepancy Alert -->
                        <div id="discrepancyAlert" class="alert alert-warning" style="display: none;">
                            <h6><i class="mdi mdi-alert"></i> Discrepancies Detected</h6>
                            <div id="discrepancyDetails"></div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-end">
                            <a href="<?= base_url('batch-receiving') ?>" class="btn btn-light me-2">
                                <i class="mdi mdi-arrow-left"></i> Back to Dashboard
                            </a>
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="mdi mdi-check"></i> Complete Inspection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bag Details (if available) -->
    <?php if (!empty($batch_bags)): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Bag Details</h4>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Bag #</th>
                                    <th>Weight (kg)</th>
                                    <th>Moisture %</th>
                                    <th>Grade</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($batch_bags as $bag): ?>
                                    <tr>
                                        <td><?= esc($bag['bag_number']) ?></td>
                                        <td><?= number_format($bag['weight_kg'], 2) ?></td>
                                        <td><?= number_format($bag['moisture_content'], 2) ?>%</td>
                                        <td><span class="badge bg-info"><?= esc($bag['quality_grade']) ?></span></td>
                                        <td><?= esc($bag['notes'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// Global variables
let inspectedBags = [];
let currentBagData = null;
const totalBags = <?= $dispatch['total_bags'] ?>;
const dispatchId = <?= $dispatch['id'] ?>;

$(document).ready(function() {
    // Initialize the inspection system
    initializeInspectionSystem();
    
    // Load existing inspections if any
    loadExistingInspections();
    
    // Set up event listeners
    setupEventListeners();
});

// Initialize the inspection system
function initializeInspectionSystem() {
    updateProgressDisplay();
    
    // Focus on bag ID input
    $('#bagIdInput').focus();
    
    // Enable Enter key for bag loading
    $('#bagIdInput').on('keypress', function(e) {
        if (e.which === 13) {
            loadBagForInspection();
        }
    });
}

// Set up all event listeners
function setupEventListeners() {
    // Bag inspection form submission
    $('#bagInspectionForm').on('submit', function(e) {
        e.preventDefault();
        submitBagInspection();
    });
    
    // Real-time calculation updates
    $('#actualWeight, #actualMoisture').on('input', function() {
        updateDifferenceCalculations();
    });
    
    // Condition status change
    $('#conditionStatus').on('change', function() {
        handleConditionChange();
    });
}

// Load bag for inspection
function loadBagForInspection() {
    const bagId = $('#bagIdInput').val().trim();
    
    if (!bagId) {
        showAlert('Please enter a bag ID or scan QR code', 'warning');
        return;
    }
    
    // Show loading state
    showLoadingState();
    
    // AJAX call to get bag details
    $.ajax({
        url: '<?= base_url('batch-receiving/get-bag-details') ?>',
        method: 'POST',
        data: {
            bag_id: bagId,
            dispatch_id: dispatchId,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        },
        success: function(response) {
            if (response.success) {
                loadBagData(response.bag);
            } else {
                showAlert(response.message || 'Bag not found', 'error');
                hideLoadingState();
            }
        },
        error: function() {
            showAlert('Error loading bag details', 'error');
            hideLoadingState();
        }
    });
}

// Load bag data into the form
function loadBagData(bagData) {
    currentBagData = bagData;
    
    // Update current bag info display
    updateCurrentBagInfo(bagData);
    
    // Populate inspection form
    $('#currentBagId').val(bagData.bag_id);
    $('#expectedWeight').val(bagData.weight_kg);
    $('#expectedMoisture').val(bagData.moisture_percentage || 0);
    $('#actualWeight').val(bagData.weight_kg); // Pre-fill with expected
    $('#actualMoisture').val(bagData.moisture_percentage || 0);
    
    // Clear previous values
    $('#weightDifference').val('');
    $('#moistureDifference').val('');
    $('#conditionStatus').val('good');
    $('#inspectionNotes').val('');
    
    // Show inspection form
    $('#inspectionFormRow').show();
    
    // Focus on actual weight input
    $('#actualWeight').focus().select();
    
    hideLoadingState();
}

// Update current bag info display
function updateCurrentBagInfo(bagData) {
    const html = `
        <div class="row">
            <div class="col-6">
                <h6 class="text-primary">${bagData.bag_id}</h6>
                <small class="text-muted">Bag ID</small>
            </div>
            <div class="col-6">
                <h6>${bagData.weight_kg} kg</h6>
                <small class="text-muted">Expected Weight</small>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-6">
                <h6>${bagData.moisture_percentage || 'N/A'}%</h6>
                <small class="text-muted">Expected Moisture</small>
            </div>
            <div class="col-6">
                <span class="badge bg-info">${bagData.quality_grade || 'Standard'}</span>
                <br><small class="text-muted">Quality Grade</small>
            </div>
        </div>
    `;
    $('#currentBagInfo').html(html);
}

// Update difference calculations
function updateDifferenceCalculations() {
    if (!currentBagData) return;
    
    const expectedWeight = parseFloat($('#expectedWeight').val()) || 0;
    const actualWeight = parseFloat($('#actualWeight').val()) || 0;
    const expectedMoisture = parseFloat($('#expectedMoisture').val()) || 0;
    const actualMoisture = parseFloat($('#actualMoisture').val()) || 0;
    
    // Calculate differences
    const weightDiff = actualWeight - expectedWeight;
    const moistureDiff = actualMoisture - expectedMoisture;
    
    // Update display with color coding
    updateDifferenceDisplay('#weightDifference', weightDiff, 'kg');
    updateDifferenceDisplay('#moistureDifference', moistureDiff, '%');
}

// Update difference display with color coding
function updateDifferenceDisplay(selector, difference, unit) {
    const $element = $(selector);
    const absValue = Math.abs(difference);
    const displayValue = difference >= 0 ? `+${difference.toFixed(2)}` : difference.toFixed(2);
    
    $element.val(`${displayValue} ${unit}`);
    
    // Color coding based on difference
    $element.removeClass('text-success text-warning text-danger');
    if (absValue === 0) {
        $element.addClass('text-success');
    } else if (absValue <= 0.5) {
        $element.addClass('text-warning');
    } else {
        $element.addClass('text-danger');
    }
}

// Handle condition status change
function handleConditionChange() {
    const condition = $('#conditionStatus').val();
    const $notes = $('#inspectionNotes');
    
    // Auto-suggest notes based on condition
    if (condition === 'damaged') {
        $notes.attr('placeholder', 'Describe the damage (tears, holes, etc.)');
    } else if (condition === 'wet') {
        $notes.attr('placeholder', 'Describe moisture issues (wet spots, mold, etc.)');
    } else if (condition === 'contaminated') {
        $notes.attr('placeholder', 'Describe contamination (foreign objects, pests, etc.)');
    } else if (condition === 'missing') {
        $notes.attr('placeholder', 'Bag is missing from delivery');
    } else {
        $notes.attr('placeholder', 'Any observations or issues...');
    }
}

// Submit bag inspection
function submitBagInspection() {
    if (!currentBagData) {
        showAlert('No bag loaded for inspection', 'error');
        return;
    }
    
    // Validate required fields
    const actualWeight = $('#actualWeight').val();
    const conditionStatus = $('#conditionStatus').val();
    
    if (!actualWeight || !conditionStatus) {
        showAlert('Please fill in all required fields', 'warning');
        return;
    }
    
    // Prepare inspection data
    const inspectionData = {
        bag_id: $('#currentBagId').val(),
        dispatch_id: dispatchId,
        expected_weight: $('#expectedWeight').val(),
        actual_weight: actualWeight,
        expected_moisture: $('#expectedMoisture').val(),
        actual_moisture: $('#actualMoisture').val(),
        condition_status: conditionStatus,
        inspection_notes: $('#inspectionNotes').val(),
        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
    };
    
    // Show loading
    const $submitBtn = $('#bagInspectionForm button[type="submit"]');
    const originalText = $submitBtn.html();
    $submitBtn.html('<i class="bx bx-loader-alt bx-spin me-1"></i> Processing...').prop('disabled', true);
    
    // Submit inspection
    $.ajax({
        url: '<?= base_url('batch-receiving/process-bag-inspection') ?>',
        method: 'POST',
        data: inspectionData,
        success: function(response) {
            if (response.success) {
                // Add to inspected bags list
                inspectedBags.push(response.inspection);
                
                // Update progress
                updateProgressDisplay();
                updateInspectionTable();
                
                // Clear form for next bag
                clearCurrentBag();
                
                showAlert('Bag inspection completed successfully', 'success');
                
                // Focus back to bag ID input
                $('#bagIdInput').focus();
                
            } else {
                showAlert(response.message || 'Error processing inspection', 'error');
            }
        },
        error: function() {
            showAlert('Error submitting inspection', 'error');
        },
        complete: function() {
            $submitBtn.html(originalText).prop('disabled', false);
        }
    });
}

// Clear current bag
function clearCurrentBag() {
    currentBagData = null;
    $('#bagIdInput').val('');
    $('#inspectionFormRow').hide();
    $('#currentBagInfo').html(`
        <div class="text-center text-muted">
            <i class="bx bx-package display-4"></i>
            <p class="mt-2">Scan or enter a bag ID to start inspection</p>
        </div>
    `);
}

// Update progress display
function updateProgressDisplay() {
    const inspectedCount = inspectedBags.length;
    const progressPercent = totalBags > 0 ? (inspectedCount / totalBags) * 100 : 0;
    
    $('#inspectedCount').text(inspectedCount);
    $('#progressBar').css('width', progressPercent + '%');
    $('#progressText').text(Math.round(progressPercent) + '%');
    
    // Enable complete all button if all bags inspected
    $('#completeAllBtn').prop('disabled', inspectedCount < totalBags);
}

// Update inspection table
function updateInspectionTable() {
    const tbody = $('#inspectionTableBody');
    tbody.empty();
    
    inspectedBags.forEach(function(inspection, index) {
        const weightDiff = parseFloat(inspection.actual_weight) - parseFloat(inspection.expected_weight);
        const moistureDiff = parseFloat(inspection.actual_moisture || 0) - parseFloat(inspection.expected_moisture || 0);
        
        const row = `
            <tr>
                <td><strong>${inspection.bag_id}</strong></td>
                <td>${parseFloat(inspection.expected_weight).toFixed(2)} kg</td>
                <td>${parseFloat(inspection.actual_weight).toFixed(2)} kg</td>
                <td class="${weightDiff === 0 ? 'text-success' : (Math.abs(weightDiff) <= 0.5 ? 'text-warning' : 'text-danger')}">
                    ${weightDiff >= 0 ? '+' : ''}${weightDiff.toFixed(2)} kg
                </td>
                <td>${parseFloat(inspection.expected_moisture || 0).toFixed(2)}%</td>
                <td>${parseFloat(inspection.actual_moisture || 0).toFixed(2)}%</td>
                <td class="${moistureDiff === 0 ? 'text-success' : (Math.abs(moistureDiff) <= 0.5 ? 'text-warning' : 'text-danger')}">
                    ${moistureDiff >= 0 ? '+' : ''}${moistureDiff.toFixed(2)}%
                </td>
                <td>
                    <span class="badge bg-${getConditionBadgeClass(inspection.condition_status)}">
                        ${inspection.condition_status.charAt(0).toUpperCase() + inspection.condition_status.slice(1)}
                    </span>
                </td>
                <td><span class="badge bg-success">Inspected</span></td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="editBagInspection('${inspection.bag_id}')">
                        <i class="bx bx-edit"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Get condition badge class
function getConditionBadgeClass(condition) {
    switch(condition) {
        case 'good': return 'success';
        case 'damaged': return 'warning';
        case 'wet': return 'info';
        case 'contaminated': return 'danger';
        case 'missing': return 'dark';
        default: return 'secondary';
    }
}

// QR Code scanning functionality
function scanBagQR() {
    // Check if device has camera
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        // For now, show a placeholder - would integrate with QR scanner library
        showAlert('QR Scanner would open here. For now, please enter bag ID manually.', 'info');
        $('#bagIdInput').focus();
    } else {
        showAlert('Camera not available. Please enter bag ID manually.', 'warning');
        $('#bagIdInput').focus();
    }
}

// Print bag labels
function printBagLabels() {
    window.open('<?= base_url('batch-receiving/print-labels/' . $dispatch['id']) ?>', '_blank');
}

// Load existing inspections
function loadExistingInspections() {
    $.ajax({
        url: '<?= base_url('batch-receiving/get-inspections') ?>',
        method: 'GET',
        data: { dispatch_id: dispatchId },
        success: function(response) {
            if (response.success && response.inspections) {
                inspectedBags = response.inspections;
                updateProgressDisplay();
                updateInspectionTable();
            }
        }
    });
}

// Complete all inspections
function completeAllInspections() {
    if (inspectedBags.length < totalBags) {
        showAlert('Please complete all bag inspections first', 'warning');
        return;
    }
    
    if (confirm('Are you sure you want to complete the entire batch inspection? This action cannot be undone.')) {
        window.location.href = '<?= base_url('batch-receiving/complete-inspection/' . $dispatch['id']) ?>';
    }
}

// Export inspection data
function exportInspectionData() {
    window.open('<?= base_url('batch-receiving/export-inspection/' . $dispatch['id']) ?>', '_blank');
}

// Utility functions
function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'warning' ? 'alert-warning' : 
                      type === 'error' ? 'alert-danger' : 'alert-info';
    
    const alert = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="bx bx-${type === 'success' ? 'check-circle' : type === 'warning' ? 'error-circle' : type === 'error' ? 'x-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('.container-xxl').prepend(alert);
    
    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}

function showLoadingState() {
    $('#currentBagInfo').html(`
        <div class="text-center">
            <i class="bx bx-loader-alt bx-spin display-4 text-primary"></i>
            <p class="mt-2">Loading bag details...</p>
        </div>
    `);
}

function hideLoadingState() {
    // Loading state will be replaced by bag data or error message
}

// Auto-calculate weight in MT and differences
function updateCalculations() {
        const actualWeightKg = parseFloat($('#actual_weight_kg').val()) || 0;
        const actualBags = parseInt($('#actual_bags').val()) || 0;
        
        // Calculate MT
        const actualWeightMt = actualWeightKg / 1000;
        $('#calculated_weight_mt').val(actualWeightMt.toFixed(3) + ' MT');
        
        // Calculate differences
        const weightDiff = actualWeightKg - expectedWeightKg;
        const bagsDiff = actualBags - expectedBags;
        
        let diffText = '';
        if (weightDiff !== 0) {
            diffText += (weightDiff > 0 ? '+' : '') + weightDiff.toFixed(2) + ' kg';
        }
        if (bagsDiff !== 0) {
            if (diffText) diffText += ', ';
            diffText += (bagsDiff > 0 ? '+' : '') + bagsDiff + ' bags';
        }
        if (!diffText) diffText = 'No difference';
        
        $('#weight_difference').val(diffText);
        
        // Show discrepancy alert if there are differences
        if (Math.abs(weightDiff) > 0.1 || bagsDiff !== 0) {
            let discrepancyHtml = '<ul class="mb-0">';
            if (Math.abs(weightDiff) > 0.1) {
                discrepancyHtml += `<li>Weight difference: ${weightDiff > 0 ? '+' : ''}${weightDiff.toFixed(2)} kg</li>`;
            }
            if (bagsDiff !== 0) {
                discrepancyHtml += `<li>Bag count difference: ${bagsDiff > 0 ? '+' : ''}${bagsDiff} bags</li>`;
            }
            discrepancyHtml += '</ul>';
            
            $('#discrepancyDetails').html(discrepancyHtml);
            $('#discrepancyAlert').show();
        } else {
            $('#discrepancyAlert').hide();
        }
    }

    // Bind calculation updates
    $('#actual_weight_kg, #actual_bags').on('input', updateCalculations);
    
    // Initial calculation
    updateCalculations();

    // Form validation
    $('#inspectionForm').on('submit', function(e) {
        const actualBags = parseInt($('#actual_bags').val());
        const actualWeight = parseFloat($('#actual_weight_kg').val());
        
        if (actualBags <= 0 || actualWeight <= 0) {
            e.preventDefault();
            alert('Please enter valid positive values for bags and weight.');
            return false;
        }
        
        // Confirm if there are significant discrepancies
        const weightDiff = Math.abs(actualWeight - expectedWeightKg);
        const bagsDiff = Math.abs(actualBags - expectedBags);
        
        if (weightDiff > expectedWeightKg * 0.05 || bagsDiff > expectedBags * 0.05) {
            if (!confirm('Significant discrepancies detected. Are you sure you want to proceed with this inspection?')) {
                e.preventDefault();
                return false;
            }
        }
        
        $('#submitBtn').prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Processing...');
    });
});
</script>

<style>
/* Mobile-responsive enhancements */
@media (max-width: 768px) {
    .container-xxl {
        padding: 10px;
    }
    
    .card {
        margin-bottom: 15px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    /* Make bag ID input larger on mobile */
    #bagIdInput {
        font-size: 18px;
        padding: 12px;
        height: auto;
    }
    
    /* Larger buttons for touch */
    .btn {
        padding: 12px 20px;
        font-size: 16px;
        min-height: 48px;
    }
    
    .btn-sm {
        padding: 8px 16px;
        font-size: 14px;
        min-height: 40px;
    }
    
    /* Stack form inputs vertically on mobile */
    .row .col-md-4,
    .row .col-md-6 {
        margin-bottom: 15px;
    }
    
    /* Make table responsive */
    .table-responsive {
        font-size: 14px;
    }
    
    .table th,
    .table td {
        padding: 8px 4px;
        white-space: nowrap;
    }
    
    /* Progress bar adjustments */
    .progress {
        height: 25px;
    }
    
    .progress-bar {
        font-size: 14px;
        line-height: 25px;
    }
    
    /* Current bag info adjustments */
    #currentBagInfo {
        text-align: center;
    }
    
    /* Alert adjustments */
    .alert {
        margin: 10px 0;
        padding: 12px;
    }
}

@media (max-width: 576px) {
    /* Extra small devices */
    .card-header {
        padding: 10px 15px;
    }
    
    .card-header h5,
    .card-header h6 {
        font-size: 16px;
        margin: 0;
    }
    
    .card-header small {
        font-size: 12px;
    }
    
    /* Stack statistics cards */
    .col-md-3 {
        margin-bottom: 10px;
    }
    
    /* Adjust input groups */
    .input-group .btn {
        padding: 12px 15px;
    }
    
    /* Form labels */
    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    /* Textarea adjustments */
    textarea.form-control {
        min-height: 80px;
    }
}

/* Touch-friendly enhancements */
@media (hover: none) and (pointer: coarse) {
    /* Touch devices */
    .btn:hover {
        transform: none;
    }
    
    .table-hover tbody tr:hover {
        background-color: inherit;
        transform: none;
    }
    
    /* Larger touch targets */
    .btn,
    .form-control,
    .form-select {
        min-height: 48px;
    }
    
    /* Better spacing for touch */
    .d-flex.gap-2 {
        gap: 15px !important;
    }
}

/* Landscape orientation adjustments */
@media screen and (orientation: landscape) and (max-height: 600px) {
    .container-xxl {
        padding: 5px;
    }
    
    .card {
        margin-bottom: 10px;
    }
    
    .card-body {
        padding: 10px;
    }
    
    /* Reduce vertical spacing */
    .mb-4 {
        margin-bottom: 15px !important;
    }
    
    .mb-3 {
        margin-bottom: 10px !important;
    }
}

/* Print styles for labels */
@media print {
    .no-print {
        display: none !important;
    }
    
    .bag-label {
        page-break-inside: avoid;
        margin: 5mm;
    }
}
</style>

<?= $this->endSection() ?>
