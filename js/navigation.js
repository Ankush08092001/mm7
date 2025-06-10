document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle functionality
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    const body = document.body;
    
    // Create overlay element for mobile menu
    const overlay = document.createElement('div');
    overlay.className = 'menu-overlay';
    document.body.appendChild(overlay);
    
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
            navLinks.classList.toggle('active');
            overlay.classList.toggle('active');
            body.style.overflow = expanded ? '' : 'hidden'; // Prevent scrolling when menu is open
            this.innerHTML = expanded ? '<i class="fas fa-bars"></i>' : '<i class="fas fa-times"></i>';
        });
    }
    
    // Close mobile menu when clicking on overlay
    overlay.addEventListener('click', function() {
        if (mobileMenuToggle && navLinks.classList.contains('active')) {
            mobileMenuToggle.setAttribute('aria-expanded', 'false');
            mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            navLinks.classList.remove('active');
            overlay.classList.remove('active');
            body.style.overflow = '';
        }
    });
    
    // Close mobile menu when clicking on a link
    const navItems = document.querySelectorAll('.nav-links a');
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 768 && navLinks.classList.contains('active')) {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                navLinks.classList.remove('active');
                overlay.classList.remove('active');
                body.style.overflow = '';
            }
        });
    });
    
    // Add scroll event for header shadow
    const header = document.querySelector('header');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 10) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
    
    // Set active nav link based on current page
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    const navLinksItems = document.querySelectorAll('.nav-links a');
    
    navLinksItems.forEach(link => {
        const linkHref = link.getAttribute('href');
        if (linkHref === currentPage) {
            link.classList.add('active');
        } else if (currentPage === 'index.html' && linkHref === 'index.html') {
            link.classList.add('active');
        }
    });
    
    // Show scroll indicator only on home page
    const scrollIndicator = document.querySelector('.scroll-indicator');
    if (scrollIndicator) {
        // Only show on index.html/home page
        const isHomePage = currentPage === 'index.html' || currentPage === '' || currentPage === '/';
        if (!isHomePage) {
            scrollIndicator.style.display = 'none';
        }
    }
});
