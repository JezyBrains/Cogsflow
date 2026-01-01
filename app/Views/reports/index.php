<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-bar-chart-alt-2 me-2"></i>
                        Comprehensive Reports & Analytics
                    </h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bx bx-download me-1"></i> Quick Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="exportAllReports('pdf')">
                                <i class="bx bx-file-blank me-2"></i>All Reports (PDF)
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportAllReports('excel')">
                                <i class="bx bx-spreadsheet me-2"></i>All Reports (Excel)
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Temporary Debug Info -->
    <?php if (isset($debug_info)): ?>
        <div class="alert alert-info">
            <strong>Debug Info:</strong><br>
            Reports Type: <?= $debug_info['reports_type'] ?><br>
            Reports Empty: <?= $debug_info['reports_empty'] ? 'true' : 'false' ?><br>
            Reports Count: <?= $debug_info['reports_count'] ?><br>
            Reports Keys: <?= is_array($debug_info['reports_keys']) ? implode(', ', $debug_info['reports_keys']) : $debug_info['reports_keys'] ?>
        </div>
    <?php endif; ?>

    <!-- Dynamic Report Categories -->
    <?php if (!empty($reports)): ?>
        <?php foreach ($reports as $category => $categoryReports): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="text-capitalize mb-3">
                        <i class="bx bx-folder me-2"></i>
                        <?= ucwords(str_replace('_', ' ', $category)) ?> Reports
                    </h4>
                </div>
            </div>
            
            <div class="row g-4 mb-5">
                <?php foreach ($categoryReports as $report): ?>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card h-100 report-card" data-report="<?= $report['slug'] ?>">
                            <div class="card-body text-center">
                                <div class="avatar avatar-lg mx-auto mb-3">
                                    <span class="avatar-initial rounded-circle bg-label-<?= $report['color'] ?>">
                                        <i class="bx <?= $report['icon'] ?> fs-4"></i>
                                    </span>
                                </div>
                                <h5 class="card-title"><?= $report['name'] ?></h5>
                                <p class="card-text text-muted">
                                    <?= $report['description'] ?>
                                </p>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="<?= site_url('reports/view/' . $report['slug']) ?>" class="btn btn-outline-<?= $report['color'] ?>">
                                        <i class="bx bx-show me-1"></i>View Report
                                    </a>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="bx bx-download"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="<?= site_url('reports/export-pdf/' . $report['slug']) ?>">
                                                <i class="bx bx-file-blank me-2"></i>PDF
                                            </a></li>
                                            <li><a class="dropdown-item" href="<?= site_url('reports/export-excel/' . $report['slug']) ?>">
                                                <i class="bx bx-spreadsheet me-2"></i>Excel
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bx bx-chart fs-1 text-muted mb-3"></i>
                        <h5>No Reports Available</h5>
                        <p class="text-muted">No reports are configured for your role or reports are currently unavailable.</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Quick Stats Dashboard -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Quick Statistics Dashboard</h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshStats()">
                        <i class="bx bx-refresh me-1"></i>Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div class="row g-3" id="quickStats">
                        <div class="col-md-3 col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        <i class="bx bx-package"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="small text-muted">Total Batches</div>
                                    <div class="fw-medium" id="totalBatches">Loading...</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-label-success">
                                        <i class="bx bx-truck"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="small text-muted">Active Dispatches</div>
                                    <div class="fw-medium" id="activeDispatches">Loading...</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-label-info">
                                        <i class="bx bx-store"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="small text-muted">Current Stock (<?= strtoupper(get_weight_unit()) ?>)</div>
                                    <div class="fw-medium" id="currentStock">Loading...</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-label-warning">
                                        <i class="bx bx-dollar"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="small text-muted">Monthly Revenue</div>
                                    <div class="fw-medium" id="monthlyRevenue">Loading...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Load quick statistics
function refreshStats() {
    fetch('<?= site_url('reports/quick-stats') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalBatches').textContent = data.stats.total_batches || '0';
                document.getElementById('activeDispatches').textContent = data.stats.active_dispatches || '0';
                document.getElementById('currentStock').textContent = data.stats.current_stock || '0';
                document.getElementById('monthlyRevenue').textContent = '$' + (data.stats.monthly_revenue || '0');
            }
        })
        .catch(error => console.error('Error loading stats:', error));
}

// Export all reports
function exportAllReports(format) {
    window.open('<?= site_url('reports/export-all/') ?>' + format, '_blank');
}

// Load stats on page load
document.addEventListener('DOMContentLoaded', refreshStats);
</script>

<?= $this->endSection() ?>
