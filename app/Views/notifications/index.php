<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-bell me-2"></i>
                        Notifications
                        <?php if ($unreadCount > 0): ?>
                            <span class="badge bg-danger ms-2"><?= $unreadCount ?></span>
                        <?php endif; ?>
                    </h5>
                    <div class="btn-group">
                        <?php if ($unreadCount > 0): ?>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="markAllAsRead()">
                                <i class="bx bx-check-double me-1"></i>
                                Mark All Read
                            </button>
                        <?php endif; ?>
                        <a href="<?= site_url('notifications/settings') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-cog me-1"></i>
                            Settings
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Notification Filters -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary active" data-filter="all">
                                    All <span class="badge bg-secondary ms-1"><?= count($notifications) ?></span>
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-filter="unread">
                                    Unread <span class="badge bg-danger ms-1"><?= $unreadCount ?></span>
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-filter="critical">
                                    Critical
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-search"></i></span>
                                <input type="text" class="form-control" id="searchNotifications" placeholder="Search notifications...">
                            </div>
                        </div>
                    </div>

                    <!-- Notifications List -->
                    <div id="notificationsList">
                        <?php if (empty($notifications)): ?>
                            <div class="text-center py-5">
                                <i class="bx bx-bell-off display-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">No notifications yet</h5>
                                <p class="text-muted">You'll see notifications here when there are updates.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($notifications as $notification): ?>
                                <div class="notification-item <?= is_null($notification['read_at']) ? 'unread' : 'read' ?>" 
                                     data-id="<?= $notification['id'] ?>" 
                                     data-type="<?= $notification['type'] ?>"
                                     data-priority="<?= $notification['priority'] ?>">
                                    <div class="d-flex align-items-start">
                                        <div class="notification-icon me-3">
                                            <?php
                                            $iconClass = 'bx-bell';
                                            $iconColor = 'primary';
                                            
                                            switch ($notification['type']) {
                                                case 'batch_arrival':
                                                    $iconClass = 'bx-package';
                                                    $iconColor = 'success';
                                                    break;
                                                case 'dispatch_status':
                                                    $iconClass = 'bx-truck';
                                                    $iconColor = 'info';
                                                    break;
                                                case 'expense_alert':
                                                    $iconClass = 'bx-money';
                                                    $iconColor = 'warning';
                                                    break;
                                                case 'inventory_threshold':
                                                    $iconClass = 'bx-store';
                                                    $iconColor = 'danger';
                                                    break;
                                                case 'system_error':
                                                    $iconClass = 'bx-error';
                                                    $iconColor = 'danger';
                                                    break;
                                                case 'user_management':
                                                    $iconClass = 'bx-user';
                                                    $iconColor = 'primary';
                                                    break;
                                            }
                                            
                                            if ($notification['priority'] === 'critical') {
                                                $iconColor = 'danger';
                                            }
                                            ?>
                                            <div class="avatar avatar-sm">
                                                <span class="avatar-initial rounded-circle bg-<?= $iconColor ?>">
                                                    <i class="bx <?= $iconClass ?> text-white"></i>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1 <?= is_null($notification['read_at']) ? 'fw-bold' : '' ?>">
                                                        <?= esc($notification['title']) ?>
                                                        <?php if ($notification['priority'] === 'critical'): ?>
                                                            <span class="badge bg-danger ms-2">Critical</span>
                                                        <?php elseif ($notification['priority'] === 'high'): ?>
                                                            <span class="badge bg-warning ms-2">High</span>
                                                        <?php endif; ?>
                                                    </h6>
                                                    <p class="mb-1 text-muted"><?= esc($notification['message']) ?></p>
                                                    <small class="text-muted">
                                                        <i class="bx bx-time me-1"></i>
                                                        <?= date('M j, Y g:i A', strtotime($notification['created_at'])) ?>
                                                    </small>
                                                </div>
                                                
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle hide-arrow" 
                                                            data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <?php if (is_null($notification['read_at'])): ?>
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="markAsRead(<?= $notification['id'] ?>)">
                                                                    <i class="bx bx-check me-2"></i>
                                                                    Mark as Read
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" onclick="deleteNotification(<?= $notification['id'] ?>)">
                                                                <i class="bx bx-trash me-2"></i>
                                                                Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-3">
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.notification-item {
    padding: 1rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.notification-item.unread {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
    border-left: 4px solid var(--bs-primary);
}

.notification-item.read {
    background-color: transparent;
}

.notification-item:hover {
    background-color: rgba(var(--bs-secondary-rgb), 0.05);
}

.notification-icon .avatar-initial {
    width: 2.5rem;
    height: 2.5rem;
}

.btn-group .btn.active {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Filter functionality
    $('[data-filter]').click(function() {
        const filter = $(this).data('filter');
        $('[data-filter]').removeClass('active');
        $(this).addClass('active');
        
        filterNotifications(filter);
    });
    
    // Search functionality
    $('#searchNotifications').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        searchNotifications(searchTerm);
    });
});

function filterNotifications(filter) {
    $('.notification-item').each(function() {
        const $item = $(this);
        let show = true;
        
        switch(filter) {
            case 'unread':
                show = $item.hasClass('unread');
                break;
            case 'critical':
                show = $item.data('priority') === 'critical';
                break;
            case 'all':
            default:
                show = true;
                break;
        }
        
        if (show) {
            $item.show();
            $item.next('hr').show();
        } else {
            $item.hide();
            $item.next('hr').hide();
        }
    });
}

function searchNotifications(searchTerm) {
    $('.notification-item').each(function() {
        const $item = $(this);
        const title = $item.find('h6').text().toLowerCase();
        const message = $item.find('p').text().toLowerCase();
        
        if (title.includes(searchTerm) || message.includes(searchTerm)) {
            $item.show();
            $item.next('hr').show();
        } else {
            $item.hide();
            $item.next('hr').hide();
        }
    });
}

function markAsRead(notificationId) {
    $.post('<?= site_url('notifications/mark-read') ?>/' + notificationId, {
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    })
    .done(function(response) {
        if (response.success) {
            $(`[data-id="${notificationId}"]`).removeClass('unread').addClass('read');
            $(`[data-id="${notificationId}"] h6`).removeClass('fw-bold');
            updateUnreadCount(response.unread_count);
            showToast('success', response.message);
        } else {
            showToast('error', response.error);
        }
    })
    .fail(function() {
        showToast('error', 'Failed to mark notification as read');
    });
}

function markAllAsRead() {
    $.post('<?= site_url('notifications/mark-read') ?>', {
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    })
    .done(function(response) {
        if (response.success) {
            $('.notification-item.unread').removeClass('unread').addClass('read');
            $('.notification-item h6').removeClass('fw-bold');
            updateUnreadCount(0);
            showToast('success', response.message);
            location.reload();
        } else {
            showToast('error', response.error);
        }
    })
    .fail(function() {
        showToast('error', 'Failed to mark all notifications as read');
    });
}

function deleteNotification(notificationId) {
    if (confirm('Are you sure you want to delete this notification?')) {
        $.ajax({
            url: '<?= site_url('notifications/delete') ?>/' + notificationId,
            type: 'DELETE',
            data: {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }
        })
        .done(function(response) {
            if (response.success) {
                $(`[data-id="${notificationId}"]`).fadeOut(300, function() {
                    $(this).next('hr').remove();
                    $(this).remove();
                });
                showToast('success', response.message);
            } else {
                showToast('error', response.error);
            }
        })
        .fail(function() {
            showToast('error', 'Failed to delete notification');
        });
    }
}

function updateUnreadCount(count) {
    const badge = $('.badge.bg-danger');
    if (count > 0) {
        badge.text(count).show();
    } else {
        badge.hide();
    }
}

function showToast(type, message) {
    // You can implement a toast notification system here
    console.log(`${type}: ${message}`);
}
</script>
<?= $this->endSection() ?>
