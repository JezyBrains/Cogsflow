<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $title ?? 'System Logs' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-1">System Logs</h4>
            <p class="text-muted">Monitor system activities and troubleshoot issues</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?= site_url('settings') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Settings
            </a>
        </div>
    </div>

    <!-- Log Statistics -->
    <?php if (!empty($stats)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0"><i class="fas fa-chart-bar text-primary me-2"></i>Log Statistics (Last 7 Days)</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($stats as $stat): ?>
                            <div class="col-md-2 col-sm-4 col-6 mb-3">
                                <div class="text-center">
                                    <div class="h4 mb-1 text-<?= $stat['level'] === 'error' || $stat['level'] === 'critical' ? 'danger' : ($stat['level'] === 'warning' ? 'warning' : 'info') ?>">
                                        <?= $stat['count'] ?>
                                    </div>
                                    <div class="small text-muted text-uppercase">
                                        <?= ucfirst($stat['level']) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Log Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="level" class="form-label">Filter by Level</label>
                            <select class="form-select" id="level" name="level" onchange="this.form.submit()">
                                <option value="">All Levels</option>
                                <option value="debug" <?= ($level ?? '') === 'debug' ? 'selected' : '' ?>>Debug</option>
                                <option value="info" <?= ($level ?? '') === 'info' ? 'selected' : '' ?>>Info</option>
                                <option value="warning" <?= ($level ?? '') === 'warning' ? 'selected' : '' ?>>Warning</option>
                                <option value="error" <?= ($level ?? '') === 'error' ? 'selected' : '' ?>>Error</option>
                                <option value="critical" <?= ($level ?? '') === 'critical' ? 'selected' : '' ?>>Critical</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-primary" onclick="refreshLogs()">
                                <i class="fas fa-sync-alt me-2"></i>Refresh
                            </button>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-outline-danger" onclick="clearLogs()">
                                <i class="fas fa-trash me-2"></i>Clear Logs
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0">
                        <i class="fas fa-list text-primary me-2"></i>Log Entries
                        <span class="badge bg-secondary ms-2"><?= $total ?? 0 ?> total</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($logs)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="120">Level</th>
                                        <th width="180">Date/Time</th>
                                        <th>Message</th>
                                        <th width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-<?= $log['level'] === 'error' || $log['level'] === 'critical' ? 'danger' : ($log['level'] === 'warning' ? 'warning' : ($log['level'] === 'info' ? 'info' : 'secondary')) ?>">
                                                    <?= ucfirst($log['level']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('Y-m-d H:i:s', strtotime($log['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="log-message" data-bs-toggle="tooltip" title="<?= esc($log['message']) ?>">
                                                    <?= esc(strlen($log['message']) > 100 ? substr($log['message'], 0, 100) . '...' : $log['message']) ?>
                                                </div>
                                                <?php if (!empty($log['context'])): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-info mt-1" 
                                                            onclick="showLogContext(<?= $log['id'] ?>, <?= esc($log['context']) ?>)">
                                                        <i class="fas fa-info-circle me-1"></i>Context
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="showLogDetails(<?= $log['id'] ?>, '<?= esc($log['level']) ?>', '<?= esc($log['created_at']) ?>', '<?= esc($log['message']) ?>', <?= $log['context'] ? esc($log['context']) : 'null' ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if (($totalPages ?? 1) > 1): ?>
                            <div class="card-footer bg-transparent">
                                <nav aria-label="Log pagination">
                                    <ul class="pagination pagination-sm justify-content-center mb-0">
                                        <?php if (($currentPage ?? 1) > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?= ($currentPage ?? 1) - 1 ?><?= !empty($level) ? '&level=' . $level : '' ?>">Previous</a>
                                            </li>
                                        <?php endif; ?>

                                        <?php
                                        $start = max(1, ($currentPage ?? 1) - 2);
                                        $end = min(($totalPages ?? 1), ($currentPage ?? 1) + 2);
                                        ?>

                                        <?php for ($i = $start; $i <= $end; $i++): ?>
                                            <li class="page-item <?= $i === ($currentPage ?? 1) ? 'active' : '' ?>">
                                                <a class="page-link" href="?page=<?= $i ?><?= !empty($level) ? '&level=' . $level : '' ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if (($currentPage ?? 1) < ($totalPages ?? 1)): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?= ($currentPage ?? 1) + 1 ?><?= !empty($level) ? '&level=' . $level : '' ?>">Next</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No logs found</h5>
                            <p class="text-muted">There are no log entries matching your criteria.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log Entry Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Level:</strong>
                        <span id="log-level-badge" class="badge ms-2"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Date/Time:</strong>
                        <span id="log-datetime" class="ms-2"></span>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Message:</strong>
                    <div id="log-message" class="mt-2 p-3 bg-light rounded"></div>
                </div>
                <div id="log-context-section" class="mb-3" style="display: none;">
                    <strong>Context:</strong>
                    <pre id="log-context" class="mt-2 p-3 bg-light rounded"></pre>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Clear Logs Confirmation Modal -->
<div class="modal fade" id="clearLogsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clear System Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="clear_retention_days" class="form-label">Keep logs from last (days)</label>
                    <input type="number" class="form-control" id="clear_retention_days" value="30" min="1" max="365">
                    <div class="form-text">Logs older than this will be permanently deleted</div>
                </div>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. Deleted logs cannot be recovered.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmClearLogs()">Clear Logs</button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Show log details
function showLogDetails(id, level, datetime, message, context) {
    // Set level badge
    const levelBadge = document.getElementById('log-level-badge');
    levelBadge.textContent = level.charAt(0).toUpperCase() + level.slice(1);
    levelBadge.className = 'badge ms-2 bg-' + getLevelColor(level);
    
    // Set datetime
    document.getElementById('log-datetime').textContent = datetime;
    
    // Set message
    document.getElementById('log-message').textContent = message;
    
    // Set context if available
    const contextSection = document.getElementById('log-context-section');
    if (context) {
        document.getElementById('log-context').textContent = JSON.stringify(JSON.parse(context), null, 2);
        contextSection.style.display = 'block';
    } else {
        contextSection.style.display = 'none';
    }
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('logDetailsModal'));
    modal.show();
}

// Show log context
function showLogContext(id, context) {
    alert('Context: ' + JSON.stringify(context, null, 2));
}

// Get level color for badge
function getLevelColor(level) {
    switch (level) {
        case 'error':
        case 'critical':
            return 'danger';
        case 'warning':
            return 'warning';
        case 'info':
            return 'info';
        case 'debug':
            return 'secondary';
        default:
            return 'secondary';
    }
}

// Refresh logs
function refreshLogs() {
    window.location.reload();
}

// Clear logs
function clearLogs() {
    const modal = new bootstrap.Modal(document.getElementById('clearLogsModal'));
    modal.show();
}

// Confirm clear logs
function confirmClearLogs() {
    const days = document.getElementById('clear_retention_days').value;
    const modal = bootstrap.Modal.getInstance(document.getElementById('clearLogsModal'));
    modal.hide();
    
    // Show loading
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Clearing...';
    btn.disabled = true;
    
    fetch('<?= site_url('settings/adminUtility') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'action=clean_logs&days=' + days + '&<?= csrf_token() ?>=' + '<?= csrf_hash() ?>'
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => window.location.reload(), 2000);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        showAlert('danger', 'An error occurred: ' + error.message);
    });
}

// Show alert
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}
</script>

<?= $this->endSection() ?>
