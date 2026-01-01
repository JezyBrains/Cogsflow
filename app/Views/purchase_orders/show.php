<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Purchase Order Details<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Purchase Order Details</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('purchase-orders') ?>" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="<?= site_url('purchase-orders/' . $purchaseOrder['id'] . '/edit') ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Order Information</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>PO Number:</strong><br>
                        <span class="text-primary"><?= esc($purchaseOrder['po_number']) ?></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Order Date:</strong><br>
                        <?= date('M d, Y', strtotime($purchaseOrder['order_date'])) ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Expected Delivery:</strong><br>
                        <?= date('M d, Y', strtotime($purchaseOrder['expected_delivery_date'])) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Grain Type:</strong><br>
                        <?= esc($purchaseOrder['grain_type']) ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Quantity:</strong><br>
                        <?= number_format($purchaseOrder['quantity_mt'], 2) ?> MT
                    </div>
                    <div class="col-md-6">
                        <strong>Unit Price:</strong><br>
                        <?= number_format($purchaseOrder['unit_price'], 2) ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Delivered Quantity:</strong><br>
                        <?= number_format($purchaseOrder['delivered_quantity_mt'], 2) ?> MT
                    </div>
                    <div class="col-md-6">
                        <strong>Transferred Quantity:</strong><br>
                        <span class="text-info"><?= number_format($purchaseOrder['transferred_quantity_mt'], 2) ?> MT</span>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <strong>Total Amount:</strong><br>
                        <span class="h4 text-success"><?= number_format($purchaseOrder['total_amount'], 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Supplier Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Supplier Name:</strong><br>
                    <?= esc($purchaseOrder['supplier_name']) ?>
                </div>
                
                <?php if (!empty($purchaseOrder['contact_person'])): ?>
                <div class="mb-3">
                    <strong>Contact Person:</strong><br>
                    <?= esc($purchaseOrder['contact_person']) ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($purchaseOrder['phone'])): ?>
                <div class="mb-3">
                    <strong>Phone:</strong><br>
                    <a href="tel:<?= esc($purchaseOrder['phone']) ?>"><?= esc($purchaseOrder['phone']) ?></a>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($purchaseOrder['email'])): ?>
                <div class="mb-3">
                    <strong>Email:</strong><br>
                    <a href="mailto:<?= esc($purchaseOrder['email']) ?>"><?= esc($purchaseOrder['email']) ?></a>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($purchaseOrder['address'])): ?>
                <div class="mb-3">
                    <strong>Address:</strong><br>
                    <?= nl2br(esc($purchaseOrder['address'])) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Status</h6>
            </div>
            <div class="card-body">
                <span class="badge bg-warning text-dark">Pending</span>
            </div>
        </div>
    </div>
</div>

<!-- Existing Batches Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-list-ul me-2"></i>
                    Created Batches from this Purchase Order
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($batches)): ?>
                    <div class="mb-3">
                        <strong>Total Transferred: <?= number_format($purchaseOrder['transferred_quantity_mt'], 2) ?> MT</strong>
                        <span class="text-muted ms-2">(<?= count($batches) ?> batches)</span>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Batch Number</th>
                                    <th>Status</th>
                                    <th>Weight (<?= strtoupper(get_weight_unit()) ?>)</th>
                                    <th>Bags</th>
                                    <th>Avg. Moisture</th>
                                    <th>Quality Grade</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($batches as $batch): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($batch['batch_number']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $batch['status'] === 'approved' ? 'success' : ($batch['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                            <?= ucfirst($batch['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= number_format($batch['total_weight_mt'], 3) ?></td>
                                    <td><?= $batch['total_bags'] ?></td>
                                    <td><?= number_format($batch['average_moisture'], 2) ?>%</td>
                                    <td>
                                        <span class="badge bg-<?= $batch['quality_grade'] === 'A+' ? 'success' : ($batch['quality_grade'] === 'A' ? 'primary' : 'secondary') ?>">
                                            <?= esc($batch['quality_grade']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($batch['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= site_url('batches/view/' . $batch['id']) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-info-circle me-1"></i>
                        No batches created yet for this purchase order
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
