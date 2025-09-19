<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Batch Inspection Form<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('/batches') ?>">Batch Management</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('/batches/receiving') ?>">Batch Receiving</a></li>
                        <li class="breadcrumb-item active">Inspection Form</li>
                    </ol>
                </div>
                <h4 class="page-title">Batch Inspection Form</h4>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Batch Information -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Batch Information</h4>
                    
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

                    <form action="<?= base_url('batches/receiving/process-inspection') ?>" method="POST" id="inspectionForm">
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
                            <a href="<?= base_url('batches/receiving') ?>" class="btn btn-light me-2">
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
$(document).ready(function() {
    const expectedBags = <?= $dispatch['total_bags'] ?>;
    const expectedWeightKg = <?= $dispatch['total_weight_kg'] ?>;
    const expectedWeightMt = <?= $dispatch['total_weight_mt'] ?>;

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
<?= $this->endSection() ?>
