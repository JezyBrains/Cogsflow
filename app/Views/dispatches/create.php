<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Create New Dispatch<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Create New Dispatch</h5>
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

<?php if (empty($available_batches)): ?>
    <div class="alert alert-warning" role="alert">
        <i class="bx bx-info-circle me-2"></i>
        <strong>No batches available for dispatch.</strong> 
        You need approved batches to create dispatches. <a href="<?= site_url('batches') ?>">Go to Batch Management</a> to approve batches first.
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bx bx-package me-2"></i>Dispatch Details</h5>
    </div>
    <div class="card-body">
        <form action="<?= site_url('dispatches/create') ?>" method="post" id="dispatchForm">
            <?= csrf_field() ?>
            
            <!-- Batch Selection -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="batch_id" class="form-label">Select Batch <span class="text-danger">*</span></label>
                    <select class="form-select <?= session()->get('errors.batch_id') ? 'is-invalid' : '' ?>" id="batch_id" name="batch_id" required>
                        <option value="">Choose an approved batch...</option>
                        <?php foreach ($available_batches as $batch): ?>
                            <option value="<?= $batch['id'] ?>" 
                                    data-grain="<?= esc($batch['grain_type']) ?>"
                                    data-weight="<?= $batch['total_weight_mt'] ?>"
                                    data-supplier="<?= esc($batch['supplier_name']) ?>"
                                    <?= old('batch_id') == $batch['id'] ? 'selected' : '' ?>>
                                <?= esc($batch['batch_number']) ?> - <?= esc($batch['grain_type']) ?> (<?= number_format($batch['total_weight_mt'], 2) ?> MT) - <?= esc($batch['supplier_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if(session()->get('errors.batch_id')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.batch_id') ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-light border">
                        <small class="text-muted">
                            <i class="bx bx-info-circle me-1"></i>
                            Select a batch to see dispatch options
                        </small>
                    </div>
                </div>
            </div>

            <!-- Batch Info Display -->
            <div id="batchInfo" class="row mb-4" style="display: none;">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <h6><i class="bx bx-info-circle me-2"></i>Selected Batch Information</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Grain Type:</strong> <span id="selectedGrain">-</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Total Weight:</strong> <span id="selectedWeight">-</span> MT
                            </div>
                            <div class="col-md-4">
                                <strong>Supplier:</strong> <span id="selectedSupplier">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Transport Details -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="dispatcher_name" class="form-label">Dispatcher Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= session()->get('errors.dispatcher_name') ? 'is-invalid' : '' ?>" 
                           id="dispatcher_name" name="dispatcher_name" 
                           value="<?= old('dispatcher_name') ?>" 
                           placeholder="Enter dispatcher name" required>
                    <?php if(session()->get('errors.dispatcher_name')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.dispatcher_name') ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="vehicle_number" class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= session()->get('errors.vehicle_number') ? 'is-invalid' : '' ?>" 
                           id="vehicle_number" name="vehicle_number" 
                           value="<?= old('vehicle_number') ?>" 
                           placeholder="e.g., ABC-123D" 
                           style="text-transform: uppercase;" required>
                    <?php if(session()->get('errors.vehicle_number')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.vehicle_number') ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Vehicle Details -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="trailer_number" class="form-label">Trailer Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= session()->get('errors.trailer_number') ? 'is-invalid' : '' ?>" 
                           id="trailer_number" name="trailer_number" 
                           value="<?= old('trailer_number') ?>" 
                           placeholder="e.g., TRL-456E" 
                           style="text-transform: uppercase;" required>
                    <?php if(session()->get('errors.trailer_number')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.trailer_number') ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="estimated_arrival" class="form-label">Estimated Arrival <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control <?= session()->get('errors.estimated_arrival') ? 'is-invalid' : '' ?>" 
                           id="estimated_arrival" name="estimated_arrival" 
                           value="<?= old('estimated_arrival', date('Y-m-d\TH:i')) ?>" 
                           min="<?= date('Y-m-d\TH:i') ?>" required>
                    <?php if(session()->get('errors.estimated_arrival')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.estimated_arrival') ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Driver Details -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="driver_name" class="form-label">Driver Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= session()->get('errors.driver_name') ? 'is-invalid' : '' ?>" 
                           id="driver_name" name="driver_name" 
                           value="<?= old('driver_name') ?>" 
                           placeholder="Enter driver full name" required>
                    <?php if(session()->get('errors.driver_name')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.driver_name') ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="driver_phone" class="form-label">Driver Phone <small class="text-muted">(Format: +255### ### ### or leave blank)</small></label>
                    <input type="tel" class="form-control <?= session()->get('errors.driver_phone') ? 'is-invalid' : '' ?>" 
                           id="driver_phone" name="driver_phone" 
                           value="<?= old('driver_phone') ?>" 
                           placeholder="e.g., +255712 345 678"
                           pattern="^\+255\d{3}\s\d{3}\s\d{3}$"
                           title="Use +255 followed by 9 digits with spaces: +255### ### ###">
                    <?php if(session()->get('errors.driver_phone')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.driver_phone') ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="driver_id_number" class="form-label">Driver ID Number</label>
                    <input type="text" class="form-control <?= session()->get('errors.driver_id_number') ? 'is-invalid' : '' ?>"
                           id="driver_id_number" name="driver_id_number"
                           value="<?= old('driver_id_number') ?>" 
                           placeholder="Enter driver ID number (license/NID)">
                    <?php if(session()->get('errors.driver_id_number')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.driver_id_number') ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Delivery Details -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="destination" class="form-label">Destination <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= session()->get('errors.destination') ? 'is-invalid' : '' ?>" 
                           id="destination" name="destination" 
                           value="<?= old('destination') ?>" 
                           placeholder="Enter delivery destination" required>
                    <?php if(session()->get('errors.destination')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.destination') ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control <?= session()->get('errors.notes') ? 'is-invalid' : '' ?>" 
                              id="notes" name="notes" rows="3" 
                              placeholder="Any additional notes or instructions..."><?= old('notes') ?></textarea>
                    <?php if(session()->get('errors.notes')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.notes') ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="d-flex justify-content-between align-items-center">
                <a href="<?= site_url('dispatches') ?>" class="btn btn-secondary">
                    <i class="bx bx-x"></i> Cancel
                </a>
                <button type="submit" class="btn btn-success" <?= empty($available_batches) ? 'disabled' : '' ?>>
                    <i class="bx bx-check"></i> Create Dispatch
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const batchSelect = document.getElementById('batch_id');
        const batchInfo = document.getElementById('batchInfo');
        const selectedGrain = document.getElementById('selectedGrain');
        const selectedWeight = document.getElementById('selectedWeight');
        const selectedSupplier = document.getElementById('selectedSupplier');
        const vehicleInput = document.getElementById('vehicle_number');
        const trailerInput = document.getElementById('trailer_number');
        
        // Show batch info when batch is selected
        batchSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                selectedGrain.textContent = selectedOption.dataset.grain || '-';
                selectedWeight.textContent = parseFloat(selectedOption.dataset.weight || 0).toFixed(2);
                selectedSupplier.textContent = selectedOption.dataset.supplier || '-';
                batchInfo.style.display = 'block';
            } else {
                batchInfo.style.display = 'none';
            }
        });
        
        // Auto-uppercase vehicle and trailer numbers
        vehicleInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
        
        trailerInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
        
        // Form validation
        document.getElementById('dispatchForm').addEventListener('submit', function(e) {
            const requiredFields = ['batch_id', 'dispatcher_name', 'vehicle_number', 'trailer_number', 'driver_name', 'destination', 'estimated_arrival'];
            let isValid = true;
            
            requiredFields.forEach(function(fieldName) {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('alert-dismissible')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
    });
</script>
<?= $this->endSection() ?>
