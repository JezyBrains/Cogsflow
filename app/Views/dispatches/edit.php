<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Edit Dispatch - <?= $dispatch['dispatch_number'] ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Edit Dispatch - <?= $dispatch['dispatch_number'] ?></h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('dispatches/view/' . $dispatch['id']) ?>" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to Details
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

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bx bx-edit me-2"></i>Update Dispatch Details</h5>
    </div>
    <div class="card-body">
        <form action="<?= site_url('dispatches/update/' . $dispatch['id']) ?>" method="post" id="dispatchEditForm">
            <?= csrf_field() ?>
            
            <!-- Batch Info (Read-only) -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <h6><i class="bx bx-info-circle me-2"></i>Batch Information (Cannot be changed)</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Batch Number:</strong> <?= esc($dispatch['batch_number']) ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Grain Type:</strong> <?= esc($dispatch['grain_type']) ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Total Weight:</strong> <?= number_format($dispatch['total_weight_mt'], 2) ?> MT
                            </div>
                            <div class="col-md-3">
                                <strong>Supplier:</strong> <?= esc($dispatch['supplier_name']) ?>
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
                           value="<?= old('dispatcher_name', $dispatch['dispatcher_name']) ?>" 
                           placeholder="Enter dispatcher name" required>
                    <?php if(session()->get('errors.dispatcher_name')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.dispatcher_name') ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="vehicle_number" class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= session()->get('errors.vehicle_number') ? 'is-invalid' : '' ?>" 
                           id="vehicle_number" name="vehicle_number" 
                           value="<?= old('vehicle_number', $dispatch['vehicle_number']) ?>" 
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
                    <label for="trailer_number" class="form-label">Trailer Number <small class="text-muted">(Optional)</small></label>
                    <input type="text" class="form-control <?= session()->get('errors.trailer_number') ? 'is-invalid' : '' ?>" 
                           id="trailer_number" name="trailer_number" 
                           value="<?= old('trailer_number', $dispatch['trailer_number']) ?>" 
                           placeholder="e.g., TRL-456E (leave blank if no trailer)" 
                           style="text-transform: uppercase;">
                    <?php if(session()->get('errors.trailer_number')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.trailer_number') ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="estimated_arrival" class="form-label">Estimated Arrival <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control <?= session()->get('errors.estimated_arrival') ? 'is-invalid' : '' ?>" 
                           id="estimated_arrival" name="estimated_arrival" 
                           value="<?= old('estimated_arrival', date('Y-m-d\TH:i', strtotime($dispatch['estimated_arrival']))) ?>" 
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
                           value="<?= old('driver_name', $dispatch['driver_name']) ?>" 
                           placeholder="Enter driver full name" required>
                    <?php if(session()->get('errors.driver_name')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.driver_name') ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="driver_phone" class="form-label">Driver Phone <small class="text-muted">(Format: +255### ### ### or leave blank)</small></label>
                    <input type="tel" class="form-control <?= session()->get('errors.driver_phone') ? 'is-invalid' : '' ?>" 
                           id="driver_phone" name="driver_phone" 
                           value="<?= old('driver_phone', $dispatch['driver_phone']) ?>" 
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
                           value="<?= old('driver_id_number', $dispatch['driver_id_number'] ?? '') ?>" 
                           placeholder="Enter driver ID number (license/NID)">
                    <?php if(session()->get('errors.driver_id_number')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.driver_id_number') ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="destination" class="form-label">Destination <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= session()->get('errors.destination') ? 'is-invalid' : '' ?>" 
                           id="destination" name="destination" 
                           value="<?= old('destination', $dispatch['destination']) ?>" 
                           placeholder="Enter delivery destination" required>
                    <?php if(session()->get('errors.destination')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.destination') ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Notes -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control <?= session()->get('errors.notes') ? 'is-invalid' : '' ?>" 
                              id="notes" name="notes" rows="3" 
                              placeholder="Any additional notes or instructions..."><?= old('notes', $dispatch['notes']) ?></textarea>
                    <?php if(session()->get('errors.notes')): ?>
                        <div class="invalid-feedback"><?= session()->get('errors.notes') ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="d-flex justify-content-between align-items-center">
                <a href="<?= site_url('dispatches/view/' . $dispatch['id']) ?>" class="btn btn-secondary">
                    <i class="bx bx-x"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Update Dispatch
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const vehicleInput = document.getElementById('vehicle_number');
        const trailerInput = document.getElementById('trailer_number');
        
        // Auto-uppercase vehicle and trailer numbers
        vehicleInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
        
        trailerInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
        
        // Form validation
        document.getElementById('dispatchEditForm').addEventListener('submit', function(e) {
            const requiredFields = ['dispatcher_name', 'vehicle_number', 'driver_name', 'destination', 'estimated_arrival'];
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
