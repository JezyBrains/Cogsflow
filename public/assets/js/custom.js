/**
 * GrainFlow - Modern Admin Template JavaScript
 * Enhanced functionality for Sneat design system with full responsive support
 */

'use strict';

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    
    // ===== LAYOUT INITIALIZATION =====
    initializeLayout();
    
    // ===== COMPONENT INITIALIZATION =====
    initializeComponents();
    
    // ===== EVENT LISTENERS =====
    setupEventListeners();
    
    // ===== AUTO-HIDE ALERTS =====
    autoHideAlerts();
    
    // ===== FORM ENHANCEMENTS =====
    enhanceForms();
    
    // ===== RESPONSIVE INITIALIZATION =====
    adjustNavbarForMobile();
});

/**
 * Initialize layout functionality
 */
function initializeLayout() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutMenuToggle = document.querySelector('.layout-menu-toggle');
    
    // Mobile menu toggle
    if (layoutMenuToggle) {
        layoutMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            toggleMobileMenu();
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 1200) {
            const isClickInsideMenu = layoutMenu && layoutMenu.contains(e.target);
            const isClickOnToggle = e.target.closest('.layout-menu-toggle');
            
            if (!isClickInsideMenu && !isClickOnToggle && layoutMenu && layoutMenu.classList.contains('show')) {
                closeMobileMenu();
            }
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        handleWindowResize();
    });
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    
    if (layoutMenu) {
        if (layoutMenu.classList.contains('show')) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    }
}

/**
 * Open mobile menu
 */
function openMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    
    if (layoutMenu) {
        layoutMenu.classList.add('show');
        document.body.classList.add('layout-menu-expanded');
        
        // Add overlay
        if (!document.querySelector('.layout-overlay')) {
            const overlay = document.createElement('div');
            overlay.className = 'layout-overlay';
            overlay.addEventListener('click', closeMobileMenu);
            document.body.appendChild(overlay);
        }
    }
}

/**
 * Close mobile menu
 */
function closeMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const overlay = document.querySelector('.layout-overlay');
    
    if (layoutMenu) {
        layoutMenu.classList.remove('show');
        document.body.classList.remove('layout-menu-expanded');
        
        // Remove overlay
        if (overlay) {
            overlay.remove();
        }
    }
}

/**
 * Handle window resize for responsive behavior
 */
function handleWindowResize() {
    const layoutMenu = document.getElementById('layout-menu');
    
    // Close mobile menu on desktop
    if (window.innerWidth >= 1200) {
        if (layoutMenu && layoutMenu.classList.contains('show')) {
            closeMobileMenu();
        }
    }
    
    // Adjust navbar on mobile
    adjustNavbarForMobile();
}

/**
 * Adjust navbar elements for mobile screens
 */
function adjustNavbarForMobile() {
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements and adjust search
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
            searchInput.placeholder = 'Search...';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}

/**
 * Initialize Bootstrap components
 */
function initializeComponents() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Initialize dropdowns
    const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    dropdownElementList.map(function(dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Card hover effects
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Search functionality
    const searchInput = document.querySelector('input[placeholder*="Search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            // Add search functionality here
            console.log('Searching for:', searchTerm);
        });
    }
}

/**
 * Auto-hide alerts after 5 seconds
 */
function autoHideAlerts() {
    document.querySelectorAll('.alert:not(.alert-permanent)').forEach(alert => {
        setTimeout(() => {
            if (alert && alert.parentNode) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });
}

/**
 * Enhance forms with loading states and validation
 */
function enhanceForms() {
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalContent = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                // Re-enable after 3 seconds (adjust based on your needs)
                setTimeout(() => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalContent;
                        submitBtn.classList.remove('loading');
                    }
                }, 3000);
            }
        });
    });
}

/**
 * Show notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification && notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

/**
 * AJAX Helper function
 */
function ajaxRequest(url, options = {}) {
    const defaults = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };
    
    const config = Object.assign(defaults, options);
    
    return fetch(url, config)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            console.error('AJAX request failed:', error);
            showNotification('Request failed. Please try again.', 'danger');
            throw error;
        });
}

/**
 * Format numbers with commas
 */
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 * Debounce function for performance optimization
 */
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// Export functions for use in other scripts if needed
window.GrainFlow = {
    showNotification,
    ajaxRequest,
    formatNumber,
    debounce,
    toggleMobileMenu,
    closeMobileMenu,
    openMobileMenu
};
