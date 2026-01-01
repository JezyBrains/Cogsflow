<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Receiving Inspection - <?= $dispatch['dispatch_number'] ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Receiving Inspection - <?= $dispatch['dispatch_number'] ?></h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('dispatches') ?>" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to Dispatches
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bx bx-error-circle me-2"></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session()->get('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bx bx-error-circle me-2"></i>
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach(session()->get('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Expected vs Actual Comparison -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bx bx-package me-2"></i>Expected (Sent)</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="fw-bold">Batch Number:</td>
                        <td><?= esc($dispatch['batch_number']) ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Grain Type:</td>
                        <td><?= esc($dispatch['grain_type']) ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Total Bags:</td>
                        <td><span class="badge bg-info fs-6"><?= number_format($dispatch['total_bags']) ?> bags</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Total Weight:</td>
                        <td><span class="badge bg-info fs-6"><?= number_format($dispatch['total_weight_kg'], 2) ?> kg</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Total Weight (MT):</td>
                        <td><span class="badge bg-info fs-6"><?= number_format($dispatch['total_weight_mt'], 3) ?> MT</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Supplier:</td>
                        <td><?= esc($dispatch['supplier_name']) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bx bx-check-circle me-2"></i>Actual (Received)</h6>
            </div>
            <div class="card-body">
                <form action="<?= site_url('dispatches/perform-inspection/' . $dispatch['id']) ?>" method="post" id="inspectionForm">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="actual_bags" class="form-label fw-bold">Actual Bags Received <span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-lg" id="actual_bags" name="actual_bags" 
                               value="<?= old('actual_bags', $dispatch['total_bags']) ?>" 
                               min="0" required>
                        <small class="text-muted">Expected: <?= number_format($dispatch['total_bags']) ?> bags</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="actual_weight_kg" class="form-label fw-bold">Actual Weight (kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control form-control-lg" id="actual_weight_kg" name="actual_weight_kg" 
                               value="<?= old('actual_weight_kg', $dispatch['total_weight_kg']) ?>" 
                               min="0" required>
                        <small class="text-muted">Expected: <?= number_format($dispatch['total_weight_kg'], 2) ?> kg</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Calculated Weight (MT)</label>
                        <input type="text" class="form-control form-control-lg bg-light" id="calculated_mt" readonly value="0.000">
                        <small class="text-muted">Auto-calculated from kg</small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Discrepancy Alert -->
<div id="discrepancyAlert" class="alert alert-warning" style="display: none;">
    <h6><i class="bx bx-error-circle me-2"></i>Discrepancies Detected</h6>
    <div id="discrepancyDetails"></div>
</div>

<!-- Inspection Notes -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0"><i class="bx bx-note me-2"></i>Inspection Notes</h6>
    </div>
    <div class="card-body">
        <form>
            <div class="mb-3">
                <label for="inspection_notes" class="form-label">Notes <span class="text-danger">*</span></label>
                <textarea class="form-control" id="inspection_notes" name="inspection_notes" rows="4" 
                          form="inspectionForm" required
                          placeholder="Enter detailed inspection notes, including any observations about bag condition, moisture, quality, etc."><?= old('inspection_notes') ?></textarea>
                <small class="text-muted">Minimum 10 characters required</small>
            </div>
        </form>
    </div>
</div>

<!-- Dispatch Information -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0"><i class="bx bx-car me-2"></i>Dispatch Information</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold">Dispatch Number:</td>
                        <td><?= esc($dispatch['dispatch_number']) ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Vehicle Number:</td>
                        <td><span class="badge bg-secondary"><?= esc($dispatch['vehicle_number']) ?></span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Driver Name:</td>
                        <td><?= esc($dispatch['driver_name']) ?></td>
                    </tr>
                    <?php if (!empty($dispatch['driver_phone'])): ?>
                    <tr>
                        <td class="fw-bold">Driver Phone:</td>
                        <td><?= esc($dispatch['driver_phone']) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold">Dispatcher:</td>
                        <td><?= esc($dispatch['dispatcher_name']) ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Destination:</td>
                        <td><?= esc($dispatch['destination']) ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Dispatch Date:</td>
                        <td><?= date('M d, Y H:i', strtotime($dispatch['created_at'])) ?></td>
                    </tr>
                    <?php if (!empty($dispatch['actual_arrival'])): ?>
                    <tr>
                        <td class="fw-bold">Arrival Time:</td>
                        <td><?= date('M d, Y H:i', strtotime($dispatch['actual_arrival'])) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?= site_url('dispatches') ?>" class="btn btn-secondary btn-lg">
                <i class="bx bx-x"></i> Cancel
            </a>
            <button type="submit" form="inspectionForm" class="btn btn-success btn-lg" id="submitBtn">
                <i class="bx bx-check-circle"></i> Complete Inspection & Update Inventory
            </button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const actualBagsInput = document.getElementById('actual_bags');
        const actualWeightInput = document.getElementById('actual_weight_kg');
        const calculatedMtInput = document.getElementById('calculated_mt');
        const discrepancyAlert = document.getElementById('discrepancyAlert');
        const discrepancyDetails = document.getElementById('discrepancyDetails');
        const inspectionForm = document.getElementById('inspectionForm');
        
        const expectedBags = <?= $dispatch['total_bags'] ?>;
        const expectedWeightKg = <?= $dispatch['total_weight_kg'] ?>;
        const tolerancePercent = 2.0; // 2% tolerance
        
        // Calculate MT and check for discrepancies
        function updateCalculations() {
            const actualWeightKg = parseFloat(actualWeightInput.value) || 0;
            const actualBags = parseInt(actualBagsInput.value) || 0;
            
            // Calculate MT
            const actualWeightMt = (actualWeightKg / 1000).toFixed(3);
            calculatedMtInput.value = actualWeightMt + ' MT';
            
            // Check for discrepancies
            const bagsDiff = actualBags - expectedBags;
            const weightDiff = actualWeightKg - expectedWeightKg;
            const weightPercentDiff = ((weightDiff / expectedWeightKg) * 100).toFixed(2);
            
            let discrepancies = [];
            
            if (bagsDiff !== 0) {
                const bagClass = bagsDiff > 0 ? 'text-success' : 'text-danger';
                discrepancies.push(`<div class="${bagClass}"><strong>Bags:</strong> Expected ${expectedBags}, Received ${actualBags} (Difference: ${bagsDiff > 0 ? '+' : ''}${bagsDiff})</div>`);
            }
            
            if (Math.abs(weightPercentDiff) > tolerancePercent) {
                const weightClass = weightDiff > 0 ? 'text-success' : 'text-danger';
                discrepancies.push(`<div class="${weightClass}"><strong>Weight:</strong> Expected ${expectedWeightKg.toFixed(2)}kg, Received ${actualWeightKg.toFixed(2)}kg (Difference: ${weightDiff > 0 ? '+' : ''}${weightDiff.toFixed(2)}kg, ${weightPercentDiff > 0 ? '+' : ''}${weightPercentDiff}%)</div>`);
            }
            
            if (discrepancies.length > 0) {
                discrepancyDetails.innerHTML = discrepancies.join('');
                discrepancyAlert.style.display = 'block';
            } else {
                discrepancyAlert.style.display = 'none';
            }
        }
        
        // Update calculations on input
        actualWeightInput.addEventListener('input', updateCalculations);
        actualBagsInput.addEventListener('input', updateCalculations);
        
        // Initial calculation
        updateCalculations();
        
        // Form validation
        inspectionForm.addEventListener('submit', function(e) {
            const inspectionNotes = document.getElementById('inspection_notes').value.trim();
            
            if (inspectionNotes.length < 10) {
                e.preventDefault();
                alert('Please provide detailed inspection notes (minimum 10 characters).');
                document.getElementById('inspection_notes').focus();
                return false;
            }
            
            if (actualBagsInput.value <= 0 || actualWeightInput.value <= 0) {
                e.preventDefault();
                alert('Please enter valid actual bags and weight values.');
                return false;
            }
            
            // Confirm submission if there are discrepancies
            if (discrepancyAlert.style.display === 'block') {
                if (!confirm('Discrepancies detected! Are you sure you want to proceed with this inspection?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            return true;
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
<?= $this->endSection() ?>
