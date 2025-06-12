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

    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(tooltip => {
        tooltip.addEventListener('mouseenter', showTooltip);
        tooltip.addEventListener('mouseleave', hideTooltip);
    });

    // Initialize file upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', handleFileSelect);
    });

    // Initialize test timer if present
    const testTimer = document.getElementById('test-timer');
    if (testTimer) {
        initializeTestTimer(testTimer);
    }

    // Initialize progress bars
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        animateProgressBar(bar);
    });

    // Handle study material preview
    const previewButtons = document.querySelectorAll('.preview-btn');
    previewButtons.forEach(button => {
        button.addEventListener('click', handlePreview);
    });

    // Handle mock test submission
    const testForm = document.getElementById('mock-test-form');
    if (testForm) {
        testForm.addEventListener('submit', handleTestSubmission);
    }
});

// Tooltip functionality
function showTooltip(e) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = this.getAttribute('data-tooltip');
    document.body.appendChild(tooltip);

    const rect = this.getBoundingClientRect();
    tooltip.style.top = rect.bottom + 5 + 'px';
    tooltip.style.left = rect.left + (rect.width - tooltip.offsetWidth) / 2 + 'px';
}

function hideTooltip() {
    const tooltip = document.querySelector('.tooltip');
    if (tooltip) {
        tooltip.remove();
    }
}

// File upload preview
function handleFileSelect(e) {
    const file = e.target.files[0];
    if (!file) return;

    const preview = document.getElementById('file-preview');
    if (!preview) return;

    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = `<p>Selected file: ${file.name}</p>`;
    }
}

// Test timer functionality
function initializeTestTimer(timerElement) {
    const duration = parseInt(timerElement.getAttribute('data-duration'));
    let timeLeft = duration;

    function updateTimer() {
        const hours = Math.floor(timeLeft / 3600);
        const minutes = Math.floor((timeLeft % 3600) / 60);
        const seconds = timeLeft % 60;

        timerElement.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            document.getElementById('mock-test-form').submit();
        }
        timeLeft--;
    }

    const timerInterval = setInterval(updateTimer, 1000);
    updateTimer();
}

// Progress bar animation
function animateProgressBar(bar) {
    const target = parseInt(bar.getAttribute('data-progress'));
    let current = 0;
    const increment = target / 50; // 50 steps animation

    const interval = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(interval);
        }
        bar.style.width = `${current}%`;
        bar.textContent = `${Math.round(current)}%`;
    }, 20);
}

// Study material preview
function handlePreview(e) {
    e.preventDefault();
    const materialId = this.getAttribute('data-id');
    const previewModal = document.getElementById('preview-modal');
    const previewFrame = document.getElementById('preview-frame');

    if (previewModal && previewFrame) {
        previewFrame.src = `/backend/study_materials.php/preview/${materialId}`;
        previewModal.style.display = 'block';
    }
}

// Mock test submission
async function handleTestSubmission(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error('Failed to submit test');
        }

        const result = await response.json();
        if (result.success) {
            showNotification('Test submitted successfully', 'success');
            setTimeout(() => {
                window.location.href = '/user/dashboard.php';
            }, 2000);
        } else {
            showNotification(result.message || 'Failed to submit test', 'error');
        }
    } catch (error) {
        console.error('Error submitting test:', error);
        showNotification('Failed to submit test. Please try again.', 'error');
    }
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Add show class after a small delay for animation
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);

    // Remove notification after 5 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
}

// Add CSS for notifications
const style = document.createElement('style');
style.textContent = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 4px;
        color: white;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
        z-index: 1000;
    }
    .notification.show {
        opacity: 1;
        transform: translateY(0);
    }
    .notification.success { background: #28a745; }
    .notification.error { background: #dc3545; }
    .notification.info { background: #17a2b8; }
    .notification.warning { background: #ffc107; color: #000; }
`;
document.head.appendChild(style);
