<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Bag Inspection - <?= isset($dispatch['batch_number']) ? esc($dispatch['batch_number']) : 'N/A' ?><?= $this->endSection() ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/bag-inspection.css?v=6.0') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bx bx-error-circle me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><i class="bx bx-package me-2"></i>Batch <?= isset($dispatch['batch_number']) ? esc($dispatch['batch_number']) : 'N/A' ?></h5>
                <small class="text-muted">Dispatch #<?= isset($dispatch['id']) ? $dispatch['id'] : 'N/A' ?> | <?= isset($dispatch['supplier_name']) ? esc($dispatch['supplier_name']) : 'N/A' ?></small>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-info"><?= isset($dispatch['grain_type']) ? esc($dispatch['grain_type']) : 'N/A' ?></span>
                <span class="badge bg-secondary"><?= isset($dispatch['total_weight_mt']) ? number_format($dispatch['total_weight_mt'], 2) : '0.00' ?> MT</span>
            </div>
        </div>
    </div>

    <!-- Stats - Compact -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row text-center">
                <div class="col-3">
                    <h2 class="mb-0" id="total-bags"><?= isset($bag_inspections) ? count($bag_inspections) : 0 ?></h2>
                    <small class="text-muted">Total</small>
                </div>
                <div class="col-3">
                    <h2 class="mb-0 text-success" id="inspected-count"><?= isset($inspection_summary['inspected']) ? $inspection_summary['inspected'] : 0 ?></h2>
                    <small class="text-muted">Done</small>
                </div>
                <div class="col-3">
                    <h2 class="mb-0 text-warning" id="pending-count"><?= isset($inspection_summary['pending']) ? $inspection_summary['pending'] : 0 ?></h2>
                    <small class="text-muted">Pending</small>
                </div>
                <div class="col-3">
                    <h2 class="mb-0 text-danger" id="issues-count"><?= isset($inspection_summary['with_discrepancies']) ? $inspection_summary['with_discrepancies'] : 0 ?></h2>
                    <small class="text-muted">Issues</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <h6>Progress</h6>
                <span id="progress-text" class="badge bg-primary">
                    <?php 
                    $totalBags = isset($bag_inspections) ? count($bag_inspections) : 0;
                    $inspected = isset($inspection_summary['inspected']) ? $inspection_summary['inspected'] : 0;
                    $percentage = $totalBags > 0 ? round(($inspected / $totalBags) * 100, 1) : 0;
                    echo "$inspected / $totalBags ($percentage%)";
                    ?>
                </span>
            </div>
            <div class="progress" style="height: 30px;">
                <div class="progress-bar bg-success" id="progress-bar" 
                     style="width: <?= $percentage ?>%">
                </div>
            </div>
        </div>
    </div>

    <!-- Document Management -->
    <div class="card mb-4">
        <?= view('documents/upload_widget', [
            'workflow_stage' => 'receiving_inspection',
            'reference_type' => 'inspection',
            'reference_id' => $dispatch['id'],
            'document_types' => $document_types,
            'existing_documents' => $existing_documents,
            'required_documents' => $required_documents
        ]) ?>
    </div>

    <!-- Actions - Simplified -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex gap-3 flex-wrap align-items-center">
                <!-- Jump to Bag -->
                <div class="bag-search" style="flex: 0 0 200px;">
                    <input type="number" class="form-control" id="jump-to-bag" placeholder="Jump to bag #" min="1">
                </div>
                
                <!-- Filter Buttons - Minimal -->
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-outline-secondary active" onclick="filterBags('all')" data-filter="all">
                        All <span class="badge bg-secondary" id="count-all">0</span>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="filterBags('pending')" data-filter="pending">
                        Pending <span class="badge bg-warning" id="count-pending">0</span>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="filterBags('ok')" data-filter="ok">
                        OK <span class="badge bg-success" id="count-ok">0</span>
                    </button>
                </div>
                
                <!-- Main Actions -->
                <div class="ms-auto d-flex gap-2">
                    <button class="btn btn-primary" onclick="openManualEntry()">
                        <i class="bx bx-play"></i> Start
                    </button>
                    <button class="btn btn-success" onclick="completeInspection()" id="complete-btn" disabled>
                        <i class="bx bx-check"></i> Complete
                    </button>
                    <a href="<?= site_url('batch-receiving') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-x"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bag Grid -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="bx bx-grid-alt me-2"></i>Bag Status Grid</h6>
        </div>
        <div class="card-body">
            <div class="bag-grid" id="bag-grid">
                <?php if (isset($bag_inspections) && !empty($bag_inspections)): ?>
                <?php foreach ($bag_inspections as $bag): 
                    $statusClass = 'pending';
                    $icon = '⏸';
                    
                    if ($bag['inspection_status'] === 'inspected') {
                        if ($bag['condition_status'] === 'missing') {
                            $statusClass = 'missing'; $icon = '❌';
                        } elseif (in_array($bag['condition_status'], ['damaged', 'wet', 'contaminated'])) {
                            $statusClass = 'damaged'; $icon = '⚠';
                        } elseif ($bag['has_discrepancy']) {
                            $statusClass = 'warning'; $icon = '⚠';
                        } else {
                            $statusClass = 'ok'; $icon = '✓';
                        }
                    }
                ?>
                <div class="bag-card status-<?= $statusClass ?>" 
                     data-bag-id="<?= esc($bag['bag_id']) ?>"
                     data-bag-number="<?= $bag['bag_number'] ?>"
                     onclick="openBag('<?= esc($bag['bag_id']) ?>')">
                    <div class="bag-icon"><i class="bx bxs-shopping-bag"></i></div>
                    <div class="bag-num"><?= str_pad($bag['bag_number'], 2, '0', STR_PAD_LEFT) ?></div>
                    <?php if ($bag['inspection_status'] === 'inspected'): ?>
                        <div class="bag-wt"><?= number_format($bag['actual_weight_kg'], 1) ?>kg</div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="alert alert-info">
                    <i class="bx bx-info-circle me-2"></i>No bags to inspect. Initializing...
                </div>
                <?php endif; ?>
            </div>
            <div class="mt-3 text-center">
                <small class="text-muted">
                    <span class="me-3">⏸ Pending</span>
                    <span class="me-3">✓ OK</span>
                    <span class="me-3">⚠ Issue</span>
                    <span>❌ Missing</span>
                </small>
            </div>
        </div>
    </div>

    <!-- Offline Indicator -->
    <div class="offline-badge badge bg-danger" id="offline-indicator" style="display: none;">
        <i class="bx bx-wifi-off"></i> Offline Mode
    </div>

    <!-- Sync Status -->
    <div class="sync-status" id="sync-status" style="display: none;">
        <i class="bx bx-sync" id="sync-icon"></i>
        <span id="sync-text">Syncing...</span>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toast-container"></div>

</div>

<!-- Modal - Compact & Properly Aligned -->
<div class="modal fade" id="bagModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="mb-0"><i class="bx bx-package me-2"></i>Bag <span id="modal-bag-num"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3" style="max-height: 70vh; overflow-y: auto;">
                <form id="inspectionForm">
                    <input type="hidden" id="bag-id" name="bag_id">
                    <input type="hidden" name="dispatch_id" value="<?= isset($dispatch['id']) ? $dispatch['id'] : '' ?>">
                    
                    <!-- Expected Values - Compact -->
                    <div class="alert alert-info py-2 mb-3">
                        <div class="row text-center">
                            <div class="col-6">
                                <small class="text-muted d-block">Expected Weight</small>
                                <strong id="exp-wt" class="d-block">-</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Expected Moisture</small>
                                <strong id="exp-moist" class="d-block">-</strong>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actual Weight -->
                    <div class="mb-3">
                        <label class="form-label mb-1"><strong>Actual Weight (kg)</strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-weight"></i></span>
                            <input type="number" class="form-control" id="act-wt" name="actual_weight_kg" step="0.1" required placeholder="Enter weight">
                            <span class="input-group-text">kg</span>
                        </div>
                        <div id="wt-var" class="mt-1 small"></div>
                    </div>
                    
                    <!-- Actual Moisture -->
                    <div class="mb-3">
                        <label class="form-label mb-1"><strong>Actual Moisture (%)</strong></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-droplet"></i></span>
                            <input type="number" class="form-control" id="act-moist" name="actual_moisture" step="0.1" placeholder="Enter moisture">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    
                    <!-- Condition - Compact -->
                    <div class="mb-3">
                        <label class="form-label mb-2"><strong>Bag Condition</strong> <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="condition_status" id="cond-good" value="good" checked>
                                <label class="btn btn-outline-success w-100 py-2" for="cond-good">
                                    <i class="bx bx-check-circle fs-5"></i> Good
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="condition_status" id="cond-dmg" value="damaged">
                                <label class="btn btn-outline-warning w-100 py-2" for="cond-dmg">
                                    <i class="bx bx-error-circle fs-5"></i> Damaged
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="condition_status" id="cond-wet" value="wet">
                                <label class="btn btn-outline-info w-100 py-2" for="cond-wet">
                                    <i class="bx bx-droplet fs-5"></i> Wet
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="condition_status" id="cond-miss" value="missing">
                                <label class="btn btn-outline-danger w-100 py-2" for="cond-miss">
                                    <i class="bx bx-x-circle fs-5"></i> Missing
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes - Compact -->
                    <div class="mb-2">
                        <label class="form-label mb-1"><strong>Notes</strong></label>
                        <textarea class="form-control" name="inspection_notes" rows="2" placeholder="Optional notes..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" tabindex="-1">
                    <i class="bx bx-x"></i> Cancel
                </button>
                <button type="button" class="btn btn-success" onclick="saveBag()">
                    <i class="bx bx-check"></i> Save & Next
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal for Complete Inspection -->
<div class="modal fade" id="confirmCompleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bx bx-check-circle me-2"></i>Complete Inspection</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3">
                    <i class="bx bx-info-circle me-2"></i>
                    <strong>You are about to complete this inspection</strong>
                </div>
                
                <p class="mb-3">This action will:</p>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="bx bx-check text-success me-2"></i>Mark all <strong id="confirm-bag-count">0</strong> bags as delivered</li>
                    <li class="mb-2"><i class="bx bx-check text-success me-2"></i>Update batch status to "Delivered"</li>
                    <li class="mb-2"><i class="bx bx-check text-success me-2"></i>Add <strong id="confirm-total-weight">0</strong> kg to inventory</li>
                    <li class="mb-2"><i class="bx bx-check text-success me-2"></i>Remove batch from pending list</li>
                </ul>
                
                <div class="alert alert-warning mt-3 mb-0" id="confirm-discrepancy-warning" style="display: none;">
                    <i class="bx bx-error me-2"></i>
                    <strong>Note:</strong> Some discrepancies were detected and will be logged.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" onclick="submitCompletion()">
                    <i class="bx bx-check me-1"></i>Complete Inspection
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// ===== ENHANCED BAG INSPECTION SYSTEM V3 - Seat Booking Style =====
const DISPATCH_ID = <?= isset($dispatch['id']) ? $dispatch['id'] : 0 ?>;
let currentBag = null;
let bagModal = null;
let offlineQueue = [];
let isOnline = navigator.onLine;
let currentFilter = 'all';

// ===== TOAST NOTIFICATIONS =====
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `custom-toast ${type}`;
    
    const icons = {
        success: 'bx-check-circle',
        error: 'bx-x-circle',
        warning: 'bx-error-circle',
        info: 'bx-info-circle'
    };
    
    toast.innerHTML = `
        <i class="bx ${icons[type]}"></i>
        <span>${message}</span>
    `;
    
    container.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('hiding');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// ===== MODAL MANAGEMENT =====
function closeModal() {
    if (bagModal) {
        bagModal.hide();
        
        // Remove backdrop manually if it exists
        setTimeout(() => {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            // Remove modal-open class from body
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }, 100);
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('bagModal');
    bagModal = new bootstrap.Modal(modalElement, {
        backdrop: 'static',
        keyboard: true
    });
    
    // Clean up on modal hidden
    modalElement.addEventListener('hidden.bs.modal', function() {
        // Remove any lingering backdrops
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    });
    
    checkComplete();
    updateFilterCounts();
    document.getElementById('act-wt').addEventListener('input', calcVar);
    
    // Jump to bag on Enter
    document.getElementById('jump-to-bag').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') jumpToBag(e.target.value);
    });
    
    // Offline/Online detection
    window.addEventListener('online', handleOnline);
    window.addEventListener('offline', handleOffline);
    
    // Load offline queue
    loadOfflineQueue();
});

// ===== FILTER BAGS =====
function filterBags(status) {
    currentFilter = status;
    const cards = document.querySelectorAll('.bag-card');
    
    // Update active filter button
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.filter === status) btn.classList.add('active');
    });
    
    // Show/hide cards
    cards.forEach(card => {
        if (status === 'all') {
            card.style.display = 'flex';
        } else {
            card.style.display = card.classList.contains(`status-${status}`) ? 'flex' : 'none';
        }
    });
}

// Update filter counts
function updateFilterCounts() {
    const counts = {
        all: 0,
        pending: 0,
        ok: 0,
        warning: 0,
        damaged: 0
    };
    
    document.querySelectorAll('.bag-card').forEach(card => {
        counts.all++;
        if (card.classList.contains('status-pending')) counts.pending++;
        if (card.classList.contains('status-ok')) counts.ok++;
        if (card.classList.contains('status-warning')) counts.warning++;
        if (card.classList.contains('status-damaged')) counts.damaged++;
    });
    
    Object.keys(counts).forEach(key => {
        const el = document.getElementById(`count-${key}`);
        if (el) el.textContent = counts[key];
    });
}

// ===== JUMP TO BAG =====
function jumpToBag(number) {
    const bag = document.querySelector(`[data-bag-number="${number}"]`);
    if (bag) {
        bag.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => bag.click(), 500);
        document.getElementById('jump-to-bag').value = '';
    } else {
        showToast(`Bag #${number} not found`, 'warning');
    }
}

// ===== OPEN BAG WITH ACTIVE STATE =====
function openBag(bagId) {
    // Remove active class from all bags
    document.querySelectorAll('.bag-card').forEach(card => card.classList.remove('active'));
    
    // Add active class to clicked bag
    const clickedCard = document.querySelector(`[data-bag-id="${bagId}"]`);
    if (clickedCard) clickedCard.classList.add('active');
    
    fetch(`<?= site_url('batch-receiving/api/bag-inspection-data') ?>?dispatch_id=${DISPATCH_ID}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const bag = data.bag_inspections.find(b => b.bag_id === bagId);
                if (bag) {
                    currentBag = bag;
                    
                    // Update modal elements with null checks
                    const modalBagNum = document.getElementById('modal-bag-num');
                    const bagIdInput = document.getElementById('bag-id');
                    const expWt = document.getElementById('exp-wt');
                    const expMoist = document.getElementById('exp-moist');
                    const actWt = document.getElementById('act-wt');
                    const actMoist = document.getElementById('act-moist');
                    
                    if (modalBagNum) modalBagNum.textContent = `#${bag.bag_number}`;
                    if (bagIdInput) bagIdInput.value = bag.bag_id;
                    if (expWt) expWt.textContent = `${bag.expected_weight_kg} kg`;
                    if (expMoist) expMoist.textContent = `${bag.expected_moisture}%`;
                    
                    if (bag.inspection_status === 'inspected') {
                        if (actWt) actWt.value = bag.actual_weight_kg;
                        if (actMoist) actMoist.value = bag.actual_moisture || '';
                        const conditionInput = document.querySelector(`input[value="${bag.condition_status}"]`);
                        if (conditionInput) conditionInput.checked = true;
                    } else {
                        const form = document.getElementById('inspectionForm');
                        if (form) form.reset();
                        if (bagIdInput) bagIdInput.value = bag.bag_id;
                    }
                    
                    if (bagModal) bagModal.show();
                }
            }
        })
        .catch(err => {
            console.error('Error loading bag data:', err);
            showToast('Error loading bag data', 'error');
        });
}

// ===== SAVE BAG WITH OFFLINE SUPPORT =====
function saveBag() {
    // Get form elements with null checks
    const bagIdInput = document.getElementById('bag-id');
    const actWtInput = document.getElementById('act-wt');
    const actMoistInput = document.getElementById('act-moist');
    const conditionInput = document.querySelector('input[name="condition_status"]:checked');
    const notesInput = document.querySelector('[name="inspection_notes"]');
    
    // Validate required fields exist
    if (!bagIdInput || !actWtInput || !conditionInput) {
        showToast('Error: Form elements not found', 'error');
        return;
    }
    
    const data = {
        dispatch_id: DISPATCH_ID,
        bag_id: bagIdInput.value,
        actual_weight_kg: parseFloat(actWtInput.value),
        actual_moisture: actMoistInput ? (parseFloat(actMoistInput.value) || null) : null,
        condition_status: conditionInput.value,
        inspection_notes: notesInput ? notesInput.value : '',
        qr_scanned: false
    };
    
    // Validate weight
    if (!data.actual_weight_kg || data.actual_weight_kg <= 0) {
        showToast('Please enter a valid weight', 'error');
        return;
    }
    
    if (isOnline) {
        saveToServer(data);
    } else {
        saveOffline(data);
    }
}

function saveToServer(data) {
    showSyncStatus('syncing');
    
    fetch('<?= site_url('batch-receiving/api/record-bag-inspection') ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            updateCard(res.inspection);
            updateProgress();
            updateFilterCounts();
            
            // Properly close modal and clean up
            closeModal();
            
            showSyncStatus('synced');
            setTimeout(() => hideSyncStatus(), 2000);
            
            // Open next bag after a short delay
            setTimeout(() => openNext(), 300);
        } else {
            showToast('Error: ' + res.message, 'error');
            showSyncStatus('error');
        }
    })
    .catch(err => {
        console.error(err);
        saveOffline(data);
    });
}

// ===== OFFLINE SUPPORT =====
function saveOffline(data) {
    offlineQueue.push(data);
    localStorage.setItem('inspection_queue_' + DISPATCH_ID, JSON.stringify(offlineQueue));
    
    // Update UI optimistically
    updateCardOptimistic(data);
    updateProgress();
    updateFilterCounts();
    
    // Properly close modal
    closeModal();
    
    showToast('Saved offline. Will sync when connection returns.', 'info');
    
    // Open next bag after a short delay
    setTimeout(() => openNext(), 300);
}

function loadOfflineQueue() {
    const stored = localStorage.getItem('inspection_queue_' + DISPATCH_ID);
    if (stored) {
        offlineQueue = JSON.parse(stored);
        if (offlineQueue.length > 0 && isOnline) {
            syncOfflineQueue();
        }
    }
}

function syncOfflineQueue() {
    if (offlineQueue.length === 0) return;
    
    showSyncStatus('syncing');
    
    const promises = offlineQueue.map(data => 
        fetch('<?= site_url('batch-receiving/api/record-bag-inspection') ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        }).then(r => r.json())
    );
    
    Promise.all(promises)
        .then(() => {
            offlineQueue = [];
            localStorage.removeItem('inspection_queue_' + DISPATCH_ID);
            showSyncStatus('synced');
            setTimeout(() => hideSyncStatus(), 3000);
            refreshData();
        })
        .catch(err => {
            console.error('Sync failed:', err);
            showSyncStatus('error');
        });
}

function handleOnline() {
    isOnline = true;
    document.getElementById('offline-indicator').style.display = 'none';
    syncOfflineQueue();
}

function handleOffline() {
    isOnline = false;
    document.getElementById('offline-indicator').style.display = 'block';
}

function showSyncStatus(status) {
    const el = document.getElementById('sync-status');
    const icon = document.getElementById('sync-icon');
    const text = document.getElementById('sync-text');
    
    el.style.display = 'flex';
    el.className = 'sync-status ' + status;
    
    if (status === 'syncing') {
        icon.className = 'bx bx-sync bx-spin';
        text.textContent = 'Syncing...';
    } else if (status === 'synced') {
        icon.className = 'bx bx-check-circle';
        text.textContent = 'Synced!';
    } else if (status === 'error') {
        icon.className = 'bx bx-error-circle';
        text.textContent = 'Sync failed';
    }
}

function hideSyncStatus() {
    document.getElementById('sync-status').style.display = 'none';
}

// ===== UPDATE CARD =====
function updateCard(insp) {
    const card = document.querySelector(`[data-bag-id="${insp.bag_id}"]`);
    if (!card) return;
    
    // Remove all status classes
    card.classList.remove('active', 'status-pending', 'status-ok', 'status-warning', 'status-damaged', 'status-missing');
    
    // Add appropriate status class (color changes automatically via CSS)
    if (insp.condition_status === 'missing') {
        card.classList.add('status-missing');
    } else if (['damaged','wet','contaminated'].includes(insp.condition_status)) {
        card.classList.add('status-damaged');
    } else if (insp.has_discrepancy) {
        card.classList.add('status-warning');
    } else {
        card.classList.add('status-ok');
    }
    
    // Add weight if not exists
    let wtDiv = card.querySelector('.bag-wt');
    if (!wtDiv) {
        wtDiv = document.createElement('div');
        wtDiv.className = 'bag-wt';
        card.appendChild(wtDiv);
    }
    wtDiv.textContent = `${parseFloat(insp.actual_weight_kg).toFixed(1)}kg`;
    
    showToast(`Bag #${insp.bag_number} saved successfully`, 'success');
}

function updateCardOptimistic(data) {
    const card = document.querySelector(`[data-bag-id="${data.bag_id}"]`);
    if (!card) return;
    
    // Remove status classes
    card.classList.remove('active', 'status-pending');
    
    // Add appropriate status class
    if (data.condition_status === 'missing') {
        card.classList.add('status-missing');
    } else if (['damaged','wet','contaminated'].includes(data.condition_status)) {
        card.classList.add('status-damaged');
    } else {
        card.classList.add('status-ok');
    }
    
    // Add weight
    let wtDiv = card.querySelector('.bag-wt');
    if (!wtDiv) {
        wtDiv = document.createElement('div');
        wtDiv.className = 'bag-wt';
        card.appendChild(wtDiv);
    }
    wtDiv.textContent = `${parseFloat(data.actual_weight_kg).toFixed(1)}kg`;
}

// ===== UPDATE PROGRESS BAR =====
function updateProgress() {
    const total = document.querySelectorAll('.bag-card').length;
    const inspected = document.querySelectorAll('.status-ok, .status-warning, .status-damaged, .status-missing').length;
    const pct = total > 0 ? Math.round((inspected / total) * 100) : 0;
    
    // Update progress bar
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const inspectedCount = document.getElementById('inspected-count');
    const pendingCount = document.getElementById('pending-count');
    
    if (progressBar) progressBar.style.width = `${pct}%`;
    if (progressText) progressText.textContent = `${inspected} / ${total} (${pct}%)`;
    if (inspectedCount) inspectedCount.textContent = inspected;
    if (pendingCount) pendingCount.textContent = total - inspected;
    
    checkComplete();
}

// ===== UTILITY FUNCTIONS =====
function calcVar() {
    if (!currentBag) return;
    const actual = parseFloat(document.getElementById('act-wt').value) || 0;
    const expected = parseFloat(currentBag.expected_weight_kg) || 0;
    
    if (actual > 0 && expected > 0) {
        const diff = actual - expected;
        const pct = (diff / expected) * 100;
        const el = document.getElementById('wt-var');
        
        if (Math.abs(pct) > 2) {
            el.innerHTML = `<span class="text-danger">⚠ ${pct > 0 ? '+' : ''}${pct.toFixed(1)}%</span>`;
        } else {
            el.innerHTML = `<span class="text-success">✓ ${pct > 0 ? '+' : ''}${pct.toFixed(1)}%</span>`;
        }
    }
}

function openNext() {
    const next = document.querySelector('.bag-card.status-pending');
    if (next) {
        setTimeout(() => {
            document.querySelectorAll('.bag-card').forEach(c => c.classList.remove('active'));
            openBag(next.dataset.bagId);
        }, 300);
    }
}

function refreshData() {
    fetch(`<?= site_url('batch-receiving/api/bag-inspection-data') ?>?dispatch_id=${DISPATCH_ID}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                updateProgress();
                updateFilterCounts();
            }
        });
}

function checkComplete() {
    const pendingEl = document.getElementById('pending-count');
    const completeBtn = document.getElementById('complete-btn');
    
    if (pendingEl && completeBtn) {
        const pending = parseInt(pendingEl.textContent) || 0;
        completeBtn.disabled = pending > 0;
    }
}

function completeInspection() {
    // Calculate totals from inspected bags
    const inspectedBags = document.querySelectorAll('.status-ok, .status-warning, .status-damaged, .status-missing');
    const totalBags = inspectedBags.length;
    let totalWeight = 0;
    let hasDiscrepancies = false;
    
    inspectedBags.forEach(card => {
        const weightText = card.querySelector('.bag-wt')?.textContent || '0kg';
        const weight = parseFloat(weightText.replace('kg', ''));
        totalWeight += weight;
        
        // Check if bag has warning or damaged status
        if (card.classList.contains('status-warning') || card.classList.contains('status-damaged')) {
            hasDiscrepancies = true;
        }
    });
    
    // Update modal content
    document.getElementById('confirm-bag-count').textContent = totalBags;
    document.getElementById('confirm-total-weight').textContent = totalWeight.toFixed(2);
    
    // Show/hide discrepancy warning
    const discrepancyWarning = document.getElementById('confirm-discrepancy-warning');
    if (hasDiscrepancies) {
        discrepancyWarning.style.display = 'block';
    } else {
        discrepancyWarning.style.display = 'none';
    }
    
    // Show confirmation modal
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmCompleteModal'));
    confirmModal.show();
}

function submitCompletion() {
    // Close confirmation modal
    const confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmCompleteModal'));
    if (confirmModal) confirmModal.hide();
    
    // Show loading message
    showToast('Processing inspection...', 'info');
    
    // Create form and submit as POST
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= site_url('batch-receiving/complete-inspection') ?>';
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'dispatch_id';
    input.value = DISPATCH_ID;
    
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

function openQRScanner() {
    showToast('QR Scanner will be implemented in Phase 1.3', 'info');
}

function openManualEntry() {
    const pending = document.querySelector('.bag-card.status-pending');
    if (pending) openBag(pending.dataset.bagId);
    else showToast('No pending bags to inspect', 'info');
}
</script>
<?= $this->endSection() ?>
