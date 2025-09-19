<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Batch Receiving<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('/batches') ?>">Batch Management</a></li>
                        <li class="breadcrumb-item active">Batch Receiving</li>
                    </ol>
                </div>
                <h4 class="page-title">Batch Receiving Dashboard</h4>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-truck-delivery widget-icon bg-primary-lighten text-primary"></i>
                    </div>
                    <h5 class="text-muted font-weight-normal mt-0" title="Pending Inspections">Pending Inspections</h5>
                    <h3 class="mt-3 mb-3"><?= count($arriveddispatches ?? []) ?></h3>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">Dispatches awaiting inspection</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-clipboard-check widget-icon bg-success-lighten text-success"></i>
                    </div>
                    <h5 class="text-muted font-weight-normal mt-0" title="Recent Inspections">Recent Inspections</h5>
                    <h3 class="mt-3 mb-3"><?= count($recentInspections ?? []) ?></h3>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">Completed by you</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-account-check widget-icon bg-info-lighten text-info"></i>
                    </div>
                    <h5 class="text-muted font-weight-normal mt-0" title="Inspector">Current Inspector</h5>
                    <h3 class="mt-3 mb-3"><?= $current_user ?></h3>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">Logged in user</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-clock-outline widget-icon bg-warning-lighten text-warning"></i>
                    </div>
                    <h5 class="text-muted font-weight-normal mt-0" title="Today's Date">Today's Date</h5>
                    <h3 class="mt-3 mb-3"><?= date('M d') ?></h3>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap"><?= date('Y-m-d H:i') ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Inspections -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <h4 class="header-title">Dispatches Awaiting Inspection</h4>
                            <p class="text-muted font-13 mb-4">
                                Batches that have arrived and need quality inspection before delivery confirmation.
                            </p>
                        </div>
                        <div class="col-sm-8">
                            <div class="text-sm-end">
                                <button type="button" class="btn btn-success mb-2 me-1" onclick="refreshPage()">
                                    <i class="mdi mdi-refresh"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-striped dt-responsive nowrap w-100" id="dispatches-table">
                            <thead>
                                <tr>
                                    <th>Dispatch #</th>
                                    <th>Batch #</th>
                                    <th>Supplier</th>
                                    <th>Grain Type</th>
                                    <th>Expected Weight (MT)</th>
                                    <th>Bags</th>
                                    <th>Arrival Date</th>
                                    <th>PO Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($arriveddispatches ?? [])): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <div class="py-4">
                                                <i class="mdi mdi-truck-delivery-outline h1 text-muted"></i>
                                                <h5 class="text-muted">No dispatches awaiting inspection</h5>
                                                <p class="text-muted">All arrived dispatches have been inspected.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach (($arriveddispatches ?? []) as $dispatch): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary"><?= esc($dispatch['dispatch_number']) ?></span>
                                            </td>
                                            <td>
                                                <strong><?= esc($dispatch['batch_number']) ?></strong>
                                            </td>
                                            <td><?= esc($dispatch['supplier_name'] ?? 'N/A') ?></td>
                                            <td>
                                                <span class="badge bg-info"><?= esc($dispatch['grain_type']) ?></span>
                                            </td>
                                            <td><?= number_format($dispatch['total_weight_mt'], 3) ?> MT</td>
                                            <td><?= number_format($dispatch['total_bags']) ?></td>
                                            <td>
                                                <?php if ($dispatch['actual_arrival']): ?>
                                                    <?= date('M d, Y H:i', strtotime($dispatch['actual_arrival'])) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Not recorded</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($dispatch['po_number']): ?>
                                                    <span class="badge bg-secondary"><?= esc($dispatch['po_number']) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('batches/receiving/inspect/' . $dispatch['id']) ?>" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="mdi mdi-clipboard-check"></i> Inspect
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Inspections -->
    <?php if (!empty($recentInspections ?? [])): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Your Recent Inspections</h4>
                    <p class="text-muted font-13 mb-4">
                        Last 10 inspections completed by you.
                    </p>

                    <div class="table-responsive">
                        <table class="table table-centered table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Batch #</th>
                                    <th>Grain Type</th>
                                    <th>Inspection Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (($recentInspections ?? []) as $inspection): ?>
                                    <tr>
                                        <td><strong><?= esc($inspection['batch_number']) ?></strong></td>
                                        <td><span class="badge bg-info"><?= esc($inspection['grain_type']) ?></span></td>
                                        <td>
                                            <?php if ($inspection['inspection_date']): ?>
                                                <?= date('M d, Y H:i', strtotime($inspection['inspection_date'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Delivered</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm" 
                                                    onclick="viewBatchHistory(<?= $inspection['batch_id'] ?>)">
                                                <i class="mdi mdi-eye"></i> View Details
                                            </button>
                                        </td>
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

<!-- Batch History Modal -->
<div class="modal fade" id="batchHistoryModal" tabindex="-1" role="dialog" aria-labelledby="batchHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="batchHistoryModalLabel">Batch History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="batchHistoryContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshPage() {
    window.location.reload();
}

function viewBatchHistory(batchId) {
    $('#batchHistoryModal').modal('show');
    $('#batchHistoryContent').html(`
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `);
    
    // Load batch history via AJAX
    fetch(`<?= base_url('batches/receiving/history/') ?>${batchId}`)
        .then(response => response.json())
        .then(data => {
            if (data.batch) {
                let historyHtml = `
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Batch Information</h6>
                            <p><strong>Batch Number:</strong> ${data.batch.batch_number}</p>
                            <p><strong>Grain Type:</strong> ${data.batch.grain_type}</p>
                            <p><strong>Total Weight:</strong> ${data.batch.total_weight_mt} MT</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Status</h6>
                            <p><strong>Current Status:</strong> <span class="badge bg-success">${data.batch.status}</span></p>
                        </div>
                    </div>
                `;
                
                if (data.history && data.history.length > 0) {
                    historyHtml += `
                        <h6>History Timeline</h6>
                        <div class="timeline-alt">
                    `;
                    
                    data.history.forEach(item => {
                        historyHtml += `
                            <div class="timeline-item">
                                <i class="mdi mdi-circle bg-primary-lighten text-primary timeline-icon"></i>
                                <div class="timeline-item-info">
                                    <h5 class="mt-0 mb-1">${item.action}</h5>
                                    <p class="font-14">Performed by: ${item.performed_by}</p>
                                    <p class="text-muted font-13">${new Date(item.performed_at).toLocaleString()}</p>
                                </div>
                            </div>
                        `;
                    });
                    
                    historyHtml += `</div>`;
                } else {
                    historyHtml += `<p class="text-muted">No history records found.</p>`;
                }
                
                $('#batchHistoryContent').html(historyHtml);
            } else {
                $('#batchHistoryContent').html('<p class="text-danger">Failed to load batch history.</p>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            $('#batchHistoryContent').html('<p class="text-danger">Error loading batch history.</p>');
        });
}

// Initialize DataTable if available
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#dispatches-table').DataTable({
            "pageLength": 10,
            "ordering": true,
            "searching": true,
            "responsive": true
        });
    }
});
</script>
<?= $this->endSection() ?>
