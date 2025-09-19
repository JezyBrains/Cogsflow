<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Supplier Details<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-user me-2"></i>
                        <?= esc($supplier['name']) ?>
                        <span class="badge bg-<?= $supplier['status'] === 'active' ? 'success' : ($supplier['status'] === 'inactive' ? 'warning' : 'secondary') ?> ms-2">
                            <?= ucfirst($supplier['status']) ?>
                        </span>
                    </h5>
                    <div class="btn-group">
                        <a href="<?= site_url('suppliers/' . $supplier['id'] . '/edit') ?>" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i>Edit
                        </a>
                        <a href="<?= site_url('suppliers') ?>" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Back to Suppliers
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-lg-6">
                            <h6 class="text-muted mb-3">
                                <i class="bx bx-info-circle me-1"></i>Basic Information
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-medium text-muted" style="width: 40%;">Supplier Name:</td>
                                             <td><?= esc($supplier['name']) ?></td>
                                        </tr>
                                        <?php if (!empty($supplier['contact_person'])): ?>
                                        <tr>
                                            <td class="fw-medium text-muted">Contact Person:</td>
                                            <td><?= esc($supplier['contact_person']) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td class="fw-medium text-muted">Status:</td>
                                            <td>
                                                <span class="badge bg-<?= $supplier['status'] === 'active' ? 'success' : ($supplier['status'] === 'inactive' ? 'warning' : 'secondary') ?>"><?= ucfirst($supplier['status']) ?></span>
                                            </td>
                                        </tr>
                                        <?php if (!empty($supplier['phone'])): ?>
                                        <tr>
                                            <td class="fw-medium text-muted">Phone:</td>
                                            <td><?= esc($supplier['phone']) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($supplier['email'])): ?>
                                        <tr>
                                            <td class="fw-medium text-muted">Email:</td>
                                            <td><?= esc($supplier['email']) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($supplier['address'])): ?>
                                        <tr>
                                            <td class="fw-medium text-muted">Address:</td>
                                            <td><?= esc($supplier['address']) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-lg-6">
                            <h6 class="text-muted mb-3">
                                <i class="bx bx-phone me-1"></i>Contact Information
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <?php if (!empty($supplier['contact_person'])): ?>
                                        <tr>
                                            <td class="fw-medium text-muted" style="width: 40%;">Contact Person:</td>
                                            <td><?= esc($supplier['contact_person']) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($supplier['phone'])): ?>
                                        <tr>
                                            <td class="fw-medium text-muted">Phone:</td>
                                            <td>
                                                <a href="tel:<?= esc($supplier['phone']) ?>" class="text-decoration-none">
                                                    <?= esc($supplier['phone']) ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($supplier['email'])): ?>
                                        <tr>
                                            <td class="fw-medium text-muted">Email:</td>
                                            <td>
                                                <a href="mailto:<?= esc($supplier['email']) ?>" class="text-decoration-none">
                                                    <?= esc($supplier['email']) ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($supplier['address'])): ?>
                                        <tr>
                                            <td class="fw-medium text-muted">Address:</td>
                                            <td><?= nl2br(esc($supplier['address'])) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <?php if (!empty($supplier['notes'])): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-muted mb-3">
                                <i class="bx bx-note me-1"></i>Notes & Comments
                            </h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <?= nl2br(esc($supplier['notes'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Statistics Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-muted mb-3">
                                <i class="bx bx-bar-chart me-1"></i>Supplier Statistics
                            </h6>
                        </div>
                    </div>
                    
                    <div class="row" id="statisticsSection">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <i class="bx bx-package bx-lg mb-2"></i>
                                    <h4 class="mb-1" id="totalBatches">-</h4>
                                    <small>Total Batches</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="bx bx-receipt bx-lg mb-2"></i>
                                    <h4 class="mb-1" id="totalPurchaseOrders">-</h4>
                                    <small>Purchase Orders</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="bx bx-truck bx-lg mb-2"></i>
                                    <h4 class="mb-1" id="totalDispatches">-</h4>
                                    <small>Dispatches</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <i class="bx bx-money bx-lg mb-2"></i>
                                    <h4 class="mb-1" id="totalValue">-</h4>
                                    <small>Total Value (KES)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-muted mb-3">
                                <i class="bx bx-time me-1"></i>Recent Activity
                            </h6>
                            <div class="card">
                                <div class="card-body" id="recentActivity">
                                    <div class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 text-muted">Loading recent activity...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-muted mb-3">
                                <i class="bx bx-info-circle me-1"></i>Record Information
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-borderless table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="fw-medium text-muted" style="width: 20%;">Created:</td>
                                            <td><?= date('M j, Y g:i A', strtotime($supplier['created_at'])) ?></td>
                                        </tr>
                                        <?php if ($supplier['updated_at'] !== $supplier['created_at']): ?>
                                        <tr>
                                            <td class="fw-medium text-muted">Last Updated:</td>
                                            <td><?= date('M j, Y g:i A', strtotime($supplier['updated_at'])) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($supplier['deleted_at'])): ?>
                                        <tr>
                                            <td class="fw-medium text-muted">Archived:</td>
                                            <td class="text-warning"><?= date('M j, Y g:i A', strtotime($supplier['deleted_at'])) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadSupplierStatistics();
    loadRecentActivity();
});

function loadSupplierStatistics() {
    fetch('<?= site_url('suppliers/' . $supplier['id'] . '/statistics') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalBatches').textContent = data.data.total_batches || 0;
                document.getElementById('totalPurchaseOrders').textContent = data.data.total_purchase_orders || 0;
                document.getElementById('totalDispatches').textContent = data.data.total_dispatches || 0;
                document.getElementById('totalValue').textContent = formatCurrency(data.data.total_value || 0);
            }
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
            document.getElementById('totalBatches').textContent = '0';
            document.getElementById('totalPurchaseOrders').textContent = '0';
            document.getElementById('totalDispatches').textContent = '0';
            document.getElementById('totalValue').textContent = 'KES 0';
        });
}

function loadRecentActivity() {
    fetch('<?= site_url('suppliers/' . $supplier['id'] . '/activity') ?>')
        .then(response => response.json())
        .then(data => {
            const activityContainer = document.getElementById('recentActivity');
            
            if (data.success && data.data.length > 0) {
                let activityHtml = '<div class="timeline">';
                
                data.data.forEach(activity => {
                    const iconClass = getActivityIcon(activity.type);
                    const badgeClass = getActivityBadge(activity.type);
                    
                    activityHtml += `
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <span class="badge ${badgeClass} p-2">
                                        <i class="${iconClass}"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">${activity.title}</h6>
                                    <p class="text-muted mb-1">${activity.description}</p>
                                    <small class="text-muted">
                                        <i class="bx bx-time me-1"></i>
                                        ${formatTimeAgo(activity.created_at)}
                                    </small>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                activityHtml += '</div>';
                activityContainer.innerHTML = activityHtml;
            } else {
                activityContainer.innerHTML = `
                    <div class="text-center text-muted">
                        <i class="bx bx-info-circle bx-lg mb-2"></i>
                        <p>No recent activity found for this supplier.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading activity:', error);
            document.getElementById('recentActivity').innerHTML = `
                <div class="text-center text-muted">
                    <i class="bx bx-error bx-lg mb-2"></i>
                    <p>Unable to load recent activity.</p>
                </div>
            `;
        });
}

function getActivityIcon(type) {
    const icons = {
        'batch': 'bx bx-package',
        'purchase_order': 'bx bx-receipt',
        'dispatch': 'bx bx-truck',
        'payment': 'bx bx-money',
        'default': 'bx bx-info-circle'
    };
    return icons[type] || icons.default;
}

function getActivityBadge(type) {
    const badges = {
        'batch': 'bg-primary',
        'purchase_order': 'bg-info',
        'dispatch': 'bg-success',
        'payment': 'bg-warning',
        'default': 'bg-secondary'
    };
    return badges[type] || badges.default;
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-KE', {
        style: 'currency',
        currency: 'KES',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return 'Just now';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
    if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)} days ago`;
    
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}
</script>
<?= $this->endSection() ?>
