# Enhanced JavaScript for Bag Inspection

## Replace the JavaScript section in inspection_grid.php with this:

```javascript
const DISPATCH_ID = <?= isset($dispatch['id']) ? $dispatch['id'] : 0 ?>;
let currentBag = null;
let bagModal = null;
let offlineQueue = [];
let isOnline = navigator.onLine;
let currentFilter = 'all';

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    bagModal = new bootstrap.Modal(document.getElementById('bagModal'));
    checkComplete();
    updateFilterCounts();
    document.getElementById('act-wt').addEventListener('input', calcVar);
    
    // Jump to bag on Enter
    document.getElementById('jump-to-bag').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') jumpToBag(e.target.value);
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', handleKeyboard);
    
    // Offline/Online detection
    window.addEventListener('online', handleOnline);
    window.addEventListener('offline', handleOffline);
    
    // Click outside shortcuts help to close
    document.getElementById('shortcuts-help').addEventListener('click', () => {
        document.getElementById('shortcuts-help').classList.remove('show');
    });
    
    // Load offline queue
    loadOfflineQueue();
});

// Keyboard Shortcuts
function handleKeyboard(e) {
    const modalOpen = document.getElementById('bagModal').classList.contains('show');
    
    // ? - Toggle shortcuts help
    if (e.key === '?' && !modalOpen) {
        e.preventDefault();
        toggleShortcuts();
    }
    
    // Space or Enter - Next pending (when modal closed)
    if ((e.key === ' ' || e.key === 'Enter') && !modalOpen) {
        e.preventDefault();
        openManualEntry();
    }
    
    // Esc - Close modal
    if (e.key === 'Escape' && modalOpen) {
        bagModal.hide();
    }
    
    // Ctrl+S - Save
    if (e.ctrlKey && e.key === 's' && modalOpen) {
        e.preventDefault();
        saveBag();
    }
    
    // Ctrl+F - Focus jump to bag
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        document.getElementById('jump-to-bag').focus();
    }
}

function toggleShortcuts() {
    document.getElementById('shortcuts-help').classList.toggle('show');
}

// Filter Bags
function filterBags(status) {
    currentFilter = status;
    const cards = document.querySelectorAll('.bag-card');
    
    // Update active filter button
    document.querySelectorAll('.filter-btn').forEach(btn => {
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

// Jump to Bag
function jumpToBag(number) {
    const bag = document.querySelector(`[data-bag-number="${number}"]`);
    if (bag) {
        bag.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => bag.click(), 500);
    } else {
        alert(`Bag #${number} not found`);
    }
}

// Open Bag with Active State
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
                    document.getElementById('modal-bag-num').textContent = `#${bag.bag_number}`;
                    document.getElementById('bag-id').value = bag.bag_id;
                    document.getElementById('exp-wt').textContent = `${bag.expected_weight_kg} kg`;
                    document.getElementById('exp-moist').textContent = `${bag.expected_moisture}%`;
                    
                    if (bag.inspection_status === 'inspected') {
                        document.getElementById('act-wt').value = bag.actual_weight_kg;
                        document.getElementById('act-moist').value = bag.actual_moisture || '';
                        document.querySelector(`input[value="${bag.condition_status}"]`).checked = true;
                    } else {
                        document.getElementById('inspectionForm').reset();
                        document.getElementById('bag-id').value = bag.bag_id;
                    }
                    
                    bagModal.show();
                }
            }
        });
}

// Save Bag with Offline Support
function saveBag() {
    const data = {
        dispatch_id: DISPATCH_ID,
        bag_id: document.getElementById('bag-id').value,
        actual_weight_kg: parseFloat(document.getElementById('act-wt').value),
        actual_moisture: parseFloat(document.getElementById('act-moist').value) || null,
        condition_status: document.querySelector('input[name="condition_status"]:checked').value,
        inspection_notes: document.querySelector('[name="inspection_notes"]').value,
        qr_scanned: false
    };
    
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
            bagModal.hide();
            showSyncStatus('synced');
            setTimeout(() => hideSyncStatus(), 2000);
            openNext();
        } else {
            alert('Error: ' + res.message);
            showSyncStatus('error');
        }
    })
    .catch(err => {
        console.error(err);
        saveOffline(data);
    });
}

// Offline Support
function saveOffline(data) {
    offlineQueue.push(data);
    localStorage.setItem('inspection_queue_' + DISPATCH_ID, JSON.stringify(offlineQueue));
    
    // Update UI optimistically
    updateCardOptimistic(data);
    updateProgress();
    updateFilterCounts();
    bagModal.hide();
    
    alert('Saved offline. Will sync when connection returns.');
    openNext();
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

// Update Card
function updateCard(insp) {
    const card = document.querySelector(`[data-bag-id="${insp.bag_id}"]`);
    if (!card) return;
    
    card.classList.remove('active', 'status-pending', 'status-ok', 'status-warning', 'status-damaged', 'status-missing');
    
    if (insp.condition_status === 'missing') {
        card.classList.add('status-missing');
        card.querySelector('.bag-icon').textContent = '❌';
    } else if (['damaged','wet','contaminated'].includes(insp.condition_status)) {
        card.classList.add('status-damaged');
        card.querySelector('.bag-icon').textContent = '⚠';
    } else if (insp.has_discrepancy) {
        card.classList.add('status-warning');
        card.querySelector('.bag-icon').textContent = '⚠';
    } else {
        card.classList.add('status-ok');
        card.querySelector('.bag-icon').textContent = '✓';
    }
    
    // Add weight if not exists
    if (!card.querySelector('.bag-wt')) {
        const wtDiv = document.createElement('div');
        wtDiv.className = 'bag-wt';
        wtDiv.textContent = `${parseFloat(insp.actual_weight_kg).toFixed(1)}kg`;
        card.appendChild(wtDiv);
    }
}

function updateCardOptimistic(data) {
    const card = document.querySelector(`[data-bag-id="${data.bag_id}"]`);
    if (!card) return;
    
    card.classList.remove('active', 'status-pending');
    
    if (data.condition_status === 'missing') {
        card.classList.add('status-missing');
        card.querySelector('.bag-icon').textContent = '❌';
    } else if (['damaged','wet','contaminated'].includes(data.condition_status)) {
        card.classList.add('status-damaged');
        card.querySelector('.bag-icon').textContent = '⚠';
    } else {
        card.classList.add('status-ok');
        card.querySelector('.bag-icon').textContent = '✓';
    }
}

// Update Progress Bar
function updateProgress() {
    const total = document.querySelectorAll('.bag-card').length;
    const inspected = document.querySelectorAll('.status-ok, .status-warning, .status-damaged, .status-missing').length;
    const pct = total > 0 ? Math.round((inspected / total) * 100) : 0;
    
    document.getElementById('progress-bar').style.width = `${pct}%`;
    document.getElementById('progress-text').textContent = `${inspected} / ${total} (${pct}%)`;
    document.getElementById('inspected-count').textContent = inspected;
    document.getElementById('pending-count').textContent = total - inspected;
    
    checkComplete();
}

// Rest of functions (calcVar, openNext, refreshData, checkComplete, completeInspection, openQRScanner, openManualEntry)
// ... keep existing implementations
```

## Add this to the end of the script section:

```javascript
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
    if (next) setTimeout(() => openBag(next.dataset.bagId), 300);
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
    const pending = parseInt(document.getElementById('pending-count').textContent);
    document.getElementById('complete-btn').disabled = pending > 0;
}

function completeInspection() {
    if (confirm('Complete inspection and update inventory?')) {
        window.location.href = '<?= site_url('batch-receiving/process-inspection') ?>?dispatch_id=' + DISPATCH_ID;
    }
}

function openQRScanner() {
    alert('QR Scanner will be implemented in Phase 1.3');
}

function openManualEntry() {
    const pending = document.querySelector('.bag-card.status-pending');
    if (pending) openBag(pending.dataset.bagId);
    else alert('No pending bags');
}
```
