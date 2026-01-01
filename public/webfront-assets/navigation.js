// Navigation and Interactive Functionality for Webfront Pages

document.addEventListener('DOMContentLoaded', function() {
    
    // Navigation functionality
    function initializeNavigation() {
        // Get current page from URL
        const currentPage = window.location.pathname.split('/').pop().replace('.html', '') || 'home';
        
        // Navigation links mapping
        const navLinks = {
            'home': '/',
            'about': '/about',
            'services': '/services', 
            'products': '/products',
            'contact': '/contact'
        };
        
        // Handle navigation clicks
        document.addEventListener('click', function(e) {
            const target = e.target;
            
            // Handle navigation menu items
            if (target.matches('#homeText, .home')) {
                e.preventDefault();
                window.location.href = navLinks.home;
            }
            
            if (target.matches('#aboutText, .about')) {
                e.preventDefault();
                window.location.href = navLinks.about;
            }
            
            if (target.matches('#servicesText, .services')) {
                e.preventDefault();
                window.location.href = navLinks.services;
            }
            
            if (target.matches('#productText, .product')) {
                e.preventDefault();
                window.location.href = navLinks.products;
            }
            
            // Handle contact buttons
            if (target.matches('#frameContainer, .contact-us-wrapper, .contact-us-container')) {
                e.preventDefault();
                window.location.href = navLinks.contact;
            }
            
            // Handle logo clicks
            if (target.matches('#nipoAgroLogo4x2, .nipo-agro-logo4x-2, .logo')) {
                e.preventDefault();
                window.location.href = navLinks.home;
            }
            
            // Handle other action buttons
            if (target.matches('#frameContainer1, .send-wrapper')) {
                e.preventDefault();
                handleContactSubmit();
            }
            
            if (target.matches('#frameContainer2, .explore-products-wrapper')) {
                e.preventDefault();
                window.location.href = navLinks.products;
            }
            
            if (target.matches('#frameContainer3, .lean-more-wrapper')) {
                e.preventDefault();
                window.location.href = navLinks.about;
            }
            
            if (target.matches('#frameContainer5, .get-started-wrapper')) {
                e.preventDefault();
                window.location.href = navLinks.contact;
            }
        });
        
        // Set active navigation state
        setActiveNavigation(currentPage);
    }
    
    // Set active navigation state
    function setActiveNavigation(currentPage) {
        // Remove all active states
        document.querySelectorAll('.nav-item, .about, .services, .product, .home').forEach(item => {
            item.classList.remove('active');
            item.style.color = '#191819';
            item.style.fontWeight = '400';
        });
        
        // Set active state based on current page
        let activeSelector = '';
        switch(currentPage) {
            case 'home':
            case 'index':
            case '':
                activeSelector = '.home, #homeText';
                break;
            case 'about':
            case 'aboutus':
                activeSelector = '.about, #aboutText';
                break;
            case 'services':
            case 'service':
                activeSelector = '.services, #servicesText';
                break;
            case 'products':
                activeSelector = '.product, #productText';
                break;
            case 'contact':
            case 'contactus':
                // Contact page doesn't have nav highlighting
                break;
        }
        
        if (activeSelector) {
            document.querySelectorAll(activeSelector).forEach(item => {
                item.classList.add('active');
                item.style.color = '#2ACC32';
                item.style.fontWeight = '700';
            });
        }
    }
    
    // Handle contact form submission
    function handleContactSubmit() {
        const form = document.querySelector('form') || createContactForm();
        
        if (form) {
            // Basic form validation
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = '#ff4444';
                } else {
                    input.style.borderColor = '#ddd';
                }
            });
            
            if (isValid) {
                showNotification('Message sent successfully!', 'success');
                if (form.reset) form.reset();
            } else {
                showNotification('Please fill in all required fields.', 'error');
            }
        }
    }
    
    // Create contact form if it doesn't exist
    function createContactForm() {
        const formData = {
            fullName: document.querySelector('input[placeholder*="John"], .john-doe')?.textContent || '',
            phone: document.querySelector('input[placeholder*="080"], .john-doe')?.textContent || '',
            email: document.querySelector('input[placeholder*="@"], .john-doe')?.textContent || '',
            subject: document.querySelector('input[placeholder*="subject"], .john-doe')?.textContent || '',
            message: document.querySelector('textarea, .john-doe')?.textContent || ''
        };
        
        return null; // Return null if no proper form structure found
    }
    
    // Show notification
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existing = document.querySelector('.notification');
        if (existing) existing.remove();
        
        // Create notification
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#2ACC32' : type === 'error' ? '#ff4444' : '#333'};
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 10000;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    // Mobile menu toggle
    function initializeMobileMenu() {
        const navMenu = document.querySelector('.about-parent');
        if (navMenu && window.innerWidth <= 768) {
            navMenu.style.display = 'none';
            
            // Create mobile menu button
            const menuButton = document.createElement('button');
            menuButton.innerHTML = '☰';
            menuButton.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1001;
                background: #2ACC32;
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 5px;
                font-size: 18px;
                cursor: pointer;
            `;
            
            document.body.appendChild(menuButton);
            
            menuButton.addEventListener('click', function() {
                const isVisible = navMenu.style.display !== 'none';
                navMenu.style.display = isVisible ? 'none' : 'flex';
                menuButton.innerHTML = isVisible ? '☰' : '✕';
            });
        }
    }
    
    // Handle responsive images
    function fixImagePaths() {
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            const src = img.getAttribute('src');
            if (src && src.includes('placehold.co')) {
                // Replace placeholder images with actual images
                const imageName = getImageForPlaceholder(src);
                if (imageName) {
                    img.src = `./images/${imageName}`;
                    img.onerror = function() {
                        // Fallback to a default image or hide
                        this.style.display = 'none';
                    };
                }
            }
        });
    }
    
    // Get appropriate image for placeholder
    function getImageForPlaceholder(placeholderSrc) {
        // Map placeholder dimensions to actual images
        const imageMap = {
            '120x120': 'Nipo Agro Logo@4x 2.png',
            '655x886': 'Nipo Agro man.png',
            '413x409': 'Rectangle 7.png',
            '522x348': 'Rectangle 8.png',
            '503x438': 'Rectangle 9.png',
            '395x338': 'Rectangle 10.png',
            '252x219': 'Rectangle 11.png',
            '224x124': 'Rectangle 17.png',
            '470x348': 'Rectangle 19.png',
            '473x541': 'Rectangle 21.png',
            '543x541': 'Rectangle 23.png'
        };
        
        // Extract dimensions from placeholder URL
        const match = placeholderSrc.match(/(\d+)x(\d+)/);
        if (match) {
            const dimensions = `${match[1]}x${match[2]}`;
            return imageMap[dimensions] || null;
        }
        
        return null;
    }
    
    // Handle window resize
    function handleResize() {
        if (window.innerWidth > 768) {
            // Show navigation on desktop
            const navMenu = document.querySelector('.about-parent');
            if (navMenu) {
                navMenu.style.display = '';
            }
            
            // Remove mobile menu button
            const menuButton = document.querySelector('button');
            if (menuButton && menuButton.innerHTML === '☰' || menuButton.innerHTML === '✕') {
                menuButton.remove();
            }
        } else {
            initializeMobileMenu();
        }
    }
    
    // Initialize everything
    initializeNavigation();
    initializeMobileMenu();
    fixImagePaths();
    
    // Handle window events
    window.addEventListener('resize', handleResize);
    
    // Handle smooth scrolling for anchor links
    document.addEventListener('click', function(e) {
        const target = e.target;
        if (target.tagName === 'A' && target.getAttribute('href')?.startsWith('#')) {
            e.preventDefault();
            const targetId = target.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
    
    console.log('Webfront navigation initialized successfully');
});
