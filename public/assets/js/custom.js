/**
 * Grain Management System - Custom JavaScript
 */

$(document).ready(function() {
    'use strict';

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Initialize popovers
    $('[data-bs-toggle="popover"]').popover();

    // Auto-hide alerts after 5 seconds
    $('.alert').each(function() {
        const alert = $(this);
        if (!alert.hasClass('alert-permanent')) {
            setTimeout(function() {
                alert.fadeOut('slow');
            }, 5000);
        }
    });

    // Form validation enhancement
    $('form').on('submit', function() {
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        
        // Add loading state
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        
        // Re-enable after 3 seconds (fallback)
        setTimeout(function() {
            submitBtn.prop('disabled', false);
            submitBtn.html(submitBtn.data('original-text') || 'Submit');
        }, 3000);
    });

    // Store original button text
    $('button[type="submit"]').each(function() {
        $(this).data('original-text', $(this).html());
    });

    // Sidebar toggle for mobile
    $('.nav-link[data-widget="pushmenu"]').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-collapse');
    });

    // Active navigation highlighting
    function setActiveNavigation() {
        const currentPath = window.location.pathname;
        $('.nav-sidebar .nav-link').removeClass('active');
        
        $('.nav-sidebar .nav-link').each(function() {
            const link = $(this);
            const href = link.attr('href');
            
            if (href && currentPath.includes(href.split('/').pop())) {
                link.addClass('active');
            }
        });
    }
    
    setActiveNavigation();

    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });

    // Table row hover effects
    $('.table tbody tr').hover(
        function() {
            $(this).addClass('table-hover-effect');
        },
        function() {
            $(this).removeClass('table-hover-effect');
        }
    );

    // Auto-calculate totals in forms
    $('.auto-calculate').on('input', function() {
        calculateFormTotals();
    });

    function calculateFormTotals() {
        const quantity = parseFloat($('#quantity').val()) || 0;
        const unitPrice = parseFloat($('#unit_price').val()) || 0;
        const total = quantity * unitPrice;
        
        $('#total_amount').val(total.toFixed(2));
    }

    // Confirmation dialogs for delete actions
    $('.btn-delete, .delete-action').on('click', function(e) {
        e.preventDefault();
        const action = $(this).attr('href') || $(this).data('action');
        
        if (confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
            if (action) {
                window.location.href = action;
            }
        }
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('.searchable-table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Date picker initialization (if needed)
    if ($.fn.datepicker) {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    }

    // Number formatting
    $('.currency').on('blur', function() {
        const value = parseFloat($(this).val());
        if (!isNaN(value)) {
            $(this).val(value.toFixed(2));
        }
    });

    // Card animations
    $('.card').addClass('fade-in');

    // Sidebar menu collapse/expand
    $('.nav-item.has-treeview > .nav-link').on('click', function(e) {
        const parent = $(this).parent();
        const siblings = parent.siblings('.has-treeview.menu-open');
        
        siblings.removeClass('menu-open');
        siblings.find('.nav-treeview').slideUp();
        
        if (!parent.hasClass('menu-open')) {
            parent.addClass('menu-open');
            parent.find('.nav-treeview').slideDown();
        } else {
            parent.removeClass('menu-open');
            parent.find('.nav-treeview').slideUp();
        }
    });

    // Print functionality
    $('.btn-print').on('click', function(e) {
        e.preventDefault();
        window.print();
    });

    // Export functionality placeholder
    $('.btn-export').on('click', function(e) {
        e.preventDefault();
        const format = $(this).data('format') || 'csv';
        showNotification('Export functionality will be implemented in Phase 2', 'info');
    });

    // Notification system
    function showNotification(message, type = 'success') {
        const alertClass = `alert-${type}`;
        const notification = $(`
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas fa-${getIconForType(type)}"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('#notifications-container').prepend(notification);
        
        setTimeout(function() {
            notification.fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }

    function getIconForType(type) {
        const icons = {
            'success': 'check-circle',
            'error': 'exclamation-circle',
            'warning': 'exclamation-triangle',
            'info': 'info-circle'
        };
        return icons[type] || 'info-circle';
    }

    // Responsive table wrapper
    function makeTablesResponsive() {
        $('.table').each(function() {
            if (!$(this).parent().hasClass('table-responsive')) {
                $(this).wrap('<div class="table-responsive"></div>');
            }
        });
    }
    
    makeTablesResponsive();

    // Loading overlay
    function showLoading() {
        $('body').addClass('loading');
    }

    function hideLoading() {
        $('body').removeClass('loading');
    }

    // AJAX setup
    $.ajaxSetup({
        beforeSend: function() {
            showLoading();
        },
        complete: function() {
            hideLoading();
        },
        error: function(xhr, status, error) {
            showNotification('An error occurred: ' + error, 'error');
        }
    });

    // Initialize dashboard charts (placeholder)
    if (typeof Chart !== 'undefined' && $('#dashboardChart').length) {
        initializeDashboardCharts();
    }

    function initializeDashboardCharts() {
        // This will be implemented in Phase 2 with actual data
        console.log('Dashboard charts will be implemented in Phase 2');
    }

    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + S to save forms
        if ((e.ctrlKey || e.metaKey) && e.which === 83) {
            e.preventDefault();
            $('form:visible').first().submit();
        }
        
        // Escape to close modals
        if (e.which === 27) {
            $('.modal.show').modal('hide');
        }
    });

    // Initialize everything
    console.log('Grain Management System initialized successfully');
});

// Utility functions
window.GrainMS = {
    showNotification: function(message, type = 'success') {
        // Implementation moved to main scope
    },
    
    formatCurrency: function(amount, currency = 'KES') {
        return new Intl.NumberFormat('en-KE', {
            style: 'currency',
            currency: currency
        }).format(amount);
    },
    
    formatDate: function(date) {
        return new Date(date).toLocaleDateString('en-KE');
    },
    
    validateForm: function(formSelector) {
        const form = $(formSelector);
        let isValid = true;
        
        form.find('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        return isValid;
    }
};
