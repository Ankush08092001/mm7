// User Dashboard Navigation and Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality for mobile
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const dashboardSidebar = document.querySelector('.dashboard-sidebar');
    
    if (sidebarToggle && dashboardSidebar) {
        sidebarToggle.addEventListener('click', function() {
            dashboardSidebar.classList.toggle('active');
            
            // Update aria attributes
            const isExpanded = dashboardSidebar.classList.contains('active');
            sidebarToggle.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                if (!event.target.closest('.dashboard-sidebar') && 
                    !event.target.closest('.sidebar-toggle') && 
                    dashboardSidebar.classList.contains('active')) {
                    dashboardSidebar.classList.remove('active');
                    sidebarToggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
    }
    
    // Make sidebar navigation items functional
    const sidebarNavLinks = document.querySelectorAll('.sidebar-nav-link');
    
    sidebarNavLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Remove active class from all links
            sidebarNavLinks.forEach(l => l.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // If it's a link to a section on the same page
            if (this.getAttribute('href').startsWith('#')) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    // Smooth scroll to the section
                    window.scrollTo({
                        top: targetElement.offsetTop - 20,
                        behavior: 'smooth'
                    });
                    
                    // Close sidebar on mobile after navigation
                    if (window.innerWidth <= 768 && dashboardSidebar.classList.contains('active')) {
                        dashboardSidebar.classList.remove('active');
                        sidebarToggle.setAttribute('aria-expanded', 'false');
                    }
                }
            }
        });
    });
    
    // Initialize progress circles
    const progressCircles = document.querySelectorAll('.progress-circle');
    
    progressCircles.forEach(circle => {
        const progressValue = parseInt(circle.getAttribute('data-progress'));
        const progressCircleElement = circle.querySelector('.progress-circle-progress');
        const radius = progressCircleElement.getAttribute('r');
        const circumference = 2 * Math.PI * radius;
        
        // Calculate the dash offset based on the progress percentage
        const dashOffset = circumference - (progressValue / 100) * circumference;
        
        // Set the stroke-dasharray and stroke-dashoffset
        progressCircleElement.style.strokeDasharray = circumference;
        progressCircleElement.style.strokeDashoffset = dashOffset;
        
        // Animate the progress text
        const progressText = circle.querySelector('.progress-circle-text');
        let currentValue = 0;
        const duration = 1000; // 1 second
        const interval = 10; // Update every 10ms
        const steps = duration / interval;
        const increment = progressValue / steps;
        
        const updateProgressText = () => {
            if (currentValue < progressValue) {
                currentValue += increment;
                if (currentValue > progressValue) {
                    currentValue = progressValue;
                }
                progressText.textContent = Math.round(currentValue) + '%';
                requestAnimationFrame(updateProgressText);
            }
        };
        
        // Start the animation when the element is in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateProgressText();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        observer.observe(circle);
    });
    
    // Make test action buttons functional
    const testActionButtons = document.querySelectorAll('.test-action');
    
    testActionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const testItem = this.closest('.test-item');
            const testTitle = testItem.querySelector('.test-title').textContent;
            
            alert(`You are viewing details for: ${testTitle}`);
        });
    });
    
    // Make content links functional
    const contentLinks = document.querySelectorAll('.content-link');
    
    contentLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.getAttribute('href') === '#') {
                e.preventDefault();
                const contentTitle = this.closest('.content-card').querySelector('.content-title').textContent;
                
                alert(`You are accessing: ${contentTitle}`);
            }
        });
    });
    
    // Make subscription buttons functional
    const subscriptionButtons = document.querySelectorAll('.subscription-btn');
    
    subscriptionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const buttonText = this.textContent.trim();
            
            if (buttonText === 'Upgrade Plan') {
                window.location.href = 'pricing.html';
            } else if (buttonText === 'Manage Subscription') {
                alert('Subscription management options will be displayed here.');
            }
        });
    });
});
