/**
 * GrainFlow - Modern Admin Template JavaScript
 * Enhanced functionality for Sneat design system
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
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});

/**
 * Initialize layout functionality
 */
function initializeLayout() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutMenuToggle = document.querySelector('.layout-menu-toggle');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
    // Mobile menu toggle
    if (layoutMenuToggle) {
        layoutMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            toggleMobileMenu();
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 1200) {
            const isClickInsideMenu = layoutMenu && layoutMenu.contains(e.target);
            const isClickOnToggle = e.target.closest('.layout-menu-toggle');
            
            if (!isClickInsideMenu && !isClickOnToggle && layoutMenu && layoutMenu.classList.contains('show')) {
                closeMobileMenu();
            }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
    
    // Handle window resize
    window.addEventListener('resize', function() {
        handleWindowResize();
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    if (layoutMenu) {
        layoutMenu.classList.toggle('show');
        document.body.classList.toggle('layout-menu-expanded');
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
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
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
    
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
    
    // Initialize dropdowns
    const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    dropdownElementList.map(function(dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
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
                }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
            }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
    
    // Card hover effects
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
    
    // Search functionality
    const searchInput = document.querySelector('input[placeholder="Search..."]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            // Add search functionality here
            console.log('Searching for:', searchTerm);
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}

/**
 * Auto-hide alerts after specified time
 */
function autoHideAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    
    alerts.forEach(alert => {
        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (alert && alert.parentNode) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}, 5000);
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}

/**
 * Enhance forms with loading states and validation
 */
function enhanceForms() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const submitBtn = form.querySelector('button[type="submit"]');
        
        if (submitBtn) {
            // Store original button content
            const originalContent = submitBtn.innerHTML;
            
            form.addEventListener('submit', function(e) {
                // Add loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>Processing...';
                submitBtn.classList.add('loading');
                
                // Reset button after 3 seconds (fallback)
                setTimeout(() => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalContent;
                        submitBtn.classList.remove('loading');
                    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
                }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}, 3000);
            }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
        
        // Enhanced form validation
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
            
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
            }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}

/**
 * Validate individual form field
 */
function validateField(field) {
    const value = field.value.trim();
    const isRequired = field.hasAttribute('required');
    const type = field.type;
    
    // Remove existing validation classes
    field.classList.remove('is-valid', 'is-invalid');
    
    // Check if required field is empty
    if (isRequired && !value) {
        field.classList.add('is-invalid');
        showFieldError(field, 'This field is required');
        return false;
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
    
    // Email validation
    if (type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            field.classList.add('is-invalid');
            showFieldError(field, 'Please enter a valid email address');
            return false;
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
    
    // Number validation
    if (type === 'number' && value) {
        const min = field.getAttribute('min');
        const max = field.getAttribute('max');
        const numValue = parseFloat(value);
        
        if (min && numValue < parseFloat(min)) {
            field.classList.add('is-invalid');
            showFieldError(field, `Value must be at least ${min}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}`);
            return false;
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
        
        if (max && numValue > parseFloat(max)) {
            field.classList.add('is-invalid');
            showFieldError(field, `Value must not exceed ${max}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}`);
            return false;
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
    
    // If we get here, field is valid
    if (value) {
        field.classList.add('is-valid');
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
    hideFieldError(field);
    return true;
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}

/**
 * Show field error message
 */
function showFieldError(field, message) {
    hideFieldError(field); // Remove existing error
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}

/**
 * Hide field error message
 */
function hideFieldError(field) {
    const existingError = field.parentNode.querySelector('.invalid-feedback');
    if (existingError) {
        existingError.remove();
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}

/**
 * Utility function to show notifications
 */
function showNotification(message, type = 'info', duration = 5000) {
    const container = document.getElementById('notifications-container');
    if (!container) return;
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
} alert-dismissible fade show`;
    alertDiv.setAttribute('role', 'alert');
    
    const iconMap = {
        success: 'bx-check-circle',
        danger: 'bx-error-circle',
        warning: 'bx-error',
        info: 'bx-info-circle'
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
};
    
    alertDiv.innerHTML = `
        <i class="bx ${iconMap[type]}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
} me-2"></i>
        ${message}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    container.appendChild(alertDiv);
    
    // Auto-hide after specified duration
    setTimeout(() => {
        if (alertDiv && alertDiv.parentNode) {
            const bsAlert = new bootstrap.Alert(alertDiv);
            bsAlert.close();
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}, duration);
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}

/**
 * Utility function for AJAX requests
 */
function makeRequest(url, options = {}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
    }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
};
    
    const finalOptions = { ...defaultOptions, ...options }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
};
    
    return fetch(url, finalOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}`);
            }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}
            return response.json();
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
})
        .catch(error => {
            console.error('Request failed:', error);
            showNotification('An error occurred. Please try again.', 'danger');
            throw error;
        }

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
});
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
}

// Export functions for global use
window.GrainFlow = {
    showNotification,
    makeRequest,
    validateField,
    toggleMobileMenu
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    const layoutMenu = document.getElementById('layout-menu');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const layoutWrapper = document.querySelector('.layout-wrapper');
    
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
    const navbarRight = document.querySelector('.navbar-nav-right');
    const searchInput = document.querySelector('.navbar-nav-right .form-control');
    
    if (window.innerWidth <= 575) {
        // Very small screens - hide some elements
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach((item, index) => {
            if (index > 0 && index < navItems.length - 1) {
                item.style.display = 'none';
            }
        });
        
        if (searchInput) {
            searchInput.style.width = '100px';
        }
    } else if (window.innerWidth <= 767) {
        // Small screens - show all but adjust sizes
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '120px';
        }
    } else {
        // Larger screens - reset to default
        const navItems = document.querySelectorAll('.navbar-nav-right .nav-item');
        navItems.forEach(item => {
            item.style.display = '';
        });
        
        if (searchInput) {
            searchInput.style.width = '';
        }
    }
};
