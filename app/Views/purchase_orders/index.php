<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Purchase Orders<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Purchase Order List</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('purchase-orders/new') ?>" class="btn btn-dark">
            <i class="fas fa-plus"></i> New Purchase Order
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>PO #</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Delivery Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($purchaseOrders)): ?>
                        <?php foreach ($purchaseOrders as $po): ?>
                            <tr>
                                <td><?= esc($po['po_number']) ?></td>
                                <td>
                                    <div><?= esc($po['supplier_name']) ?></div>
                                    <?php if (!empty($po['contact_person'])): ?>
                                        <div class="text-muted small"><?= esc($po['contact_person']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($po['order_date'])) ?></td>
                                <td><?= date('M d, Y', strtotime($po['expected_delivery_date'])) ?></td>
                                <td>
                                    <div><strong>Total:</strong> <?= number_format($po['total_amount'], 2) ?></div>
                                    <div class="text-muted small"><?= esc($po['grain_type']) ?> - <?= number_format($po['quantity_mt'], 2) ?> MT</div>
                                </td>
                                <td>
                                    <?php
                                    $status = $po['dynamic_status'];
                                    $badgeClass = 'bg-secondary';
                                    switch($status) {
                                        case 'approved':
                                        case 'confirmed':
                                            $badgeClass = 'bg-success';
                                            break;
                                        case 'pending':
                                            $badgeClass = 'bg-warning text-dark';
                                            break;
                                        case 'transferring':
                                            $badgeClass = 'bg-info';
                                            break;
                                        case 'completed':
                                            $badgeClass = 'bg-primary';
                                            break;
                                        case 'rejected':
                                            $badgeClass = 'bg-danger';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                                    <?php if ($po['transferred_quantity_mt'] > 0): ?>
                                        <div class="small text-muted mt-1">
                                            <?= number_format($po['transferred_quantity_mt'], 2) ?> / <?= number_format($po['quantity_mt'], 2) ?> MT
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= site_url('purchase-orders/' . $po['id']) ?>" class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= site_url('purchase-orders/' . $po['id'] . '/edit') ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deletePurchaseOrder(<?= $po['id'] ?>, '<?= esc($po['po_number']) ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No purchase orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function deletePurchaseOrder(id, poNumber) {
    if (confirm(`Are you sure you want to delete Purchase Order ${poNumber}?`)) {
        // Create a form to submit DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('purchase-orders') ?>/${id}`;
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);
        
        // Add method override for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?= $this->endSection() ?>
