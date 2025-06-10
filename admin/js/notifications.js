// Notifications functionality
document.addEventListener("DOMContentLoaded", function() {
    // Initialize notification bell icon
    const notificationBell = document.querySelector(".notification-bell");
    if (notificationBell) {
        notificationBell.addEventListener("click", function() {
            toggleNotificationSidebar();
        });
    }
    
    // Initialize user profile icon
    const userProfileIcon = document.querySelector(".user-profile-icon");
    if (userProfileIcon) {
        userProfileIcon.addEventListener("click", function() {
            toggleUserProfileDropdown();
        });
    }
    
    // Create notification container if it doesn't exist
    if (!document.querySelector(".notification-container")) {
        const notificationContainer = document.createElement("div");
        notificationContainer.className = "notification-container";
        document.body.appendChild(notificationContainer);
    }
    
    // Create notification sidebar if it doesn't exist
    if (!document.querySelector(".notification-sidebar")) {
        createNotificationSidebar();
    }
    
    // Create user profile dropdown if it doesn't exist
    if (!document.querySelector(".user-profile-dropdown")) {
        createUserProfileDropdown();
    }
    
    // Close notification sidebar and user profile dropdown when clicking outside
    document.addEventListener("click", function(e) {
        const notificationSidebar = document.querySelector(".notification-sidebar");
        const notificationBell = document.querySelector(".notification-bell");
        const notificationOverlay = document.querySelector(".notification-overlay");
        const userProfileDropdown = document.querySelector(".user-profile-dropdown");
        const userProfileIcon = document.querySelector(".user-profile-icon");
        
        // Close notification sidebar when clicking outside
        if (notificationSidebar && notificationSidebar.classList.contains("open") && 
            !notificationSidebar.contains(e.target) && e.target !== notificationBell) {
            notificationSidebar.classList.remove("open");
            if (notificationOverlay) notificationOverlay.classList.remove("open");
        }
        
        // Close user profile dropdown when clicking outside
        if (userProfileDropdown && userProfileDropdown.classList.contains("open") && 
            !userProfileDropdown.contains(e.target) && e.target !== userProfileIcon) {
            userProfileDropdown.classList.remove("open");
        }
    });
});

// Function to show a notification
function showNotification(message, type = "info") {
    const notificationContainer = document.querySelector(".notification-container");
    if (!notificationContainer) return;
    
    const notification = document.createElement("div");
    notification.className = `notification ${type}`;
    
    let icon = "";
    switch (type) {
        case "success":
            icon = "✓";
            break;
        case "error":
            icon = "✕";
            break;
        case "warning":
            icon = "⚠";
            break;
        default:
            icon = "ℹ";
            break;
    }
    
    notification.innerHTML = `
        <div class="notification-icon">${icon}</div>
        <div class="notification-message">${message}</div>
        <div class="notification-close">✕</div>
    `;
    
    notificationContainer.appendChild(notification);
    
    // Add event listener to close button
    const closeBtn = notification.querySelector(".notification-close");
    if (closeBtn) {
        closeBtn.addEventListener("click", function() {
            notification.remove();
        });
    }
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Function to toggle notification sidebar
function toggleNotificationSidebar() {
    const notificationSidebar = document.querySelector(".notification-sidebar");
    const notificationOverlay = document.querySelector(".notification-overlay");
    
    if (notificationSidebar) {
        notificationSidebar.classList.toggle("open");
        
        if (notificationOverlay) {
            notificationOverlay.classList.toggle("open");
        }
    }
}

// Function to toggle user profile dropdown
function toggleUserProfileDropdown() {
    const userProfileDropdown = document.querySelector(".user-profile-dropdown");
    
    if (userProfileDropdown) {
        userProfileDropdown.classList.toggle("open");
    }
}

// Function to create notification sidebar
function createNotificationSidebar() {
    const notificationSidebar = document.createElement("div");
    notificationSidebar.className = "notification-sidebar";
    
    notificationSidebar.innerHTML = `
        <div class="notification-sidebar-header">
            <h2>Notifications</h2>
            <div class="notification-sidebar-close">&times;</div>
        </div>
        <div class="notification-sidebar-body">
            <div class="notification-item unread">
                <div class="notification-title">New User Registration</div>
                <div class="notification-message">Rahul Sharma has registered and purchased a Premium subscription.</div>
                <div class="notification-time">10 minutes ago</div>
                <div class="notification-actions">
                    <button class="mark-read">Mark as Read</button>
                    <button class="view-details">View Details</button>
                </div>
            </div>
            <div class="notification-item unread">
                <div class="notification-title">Mock Test Completed</div>
                <div class="notification-message">Priya Patel has completed the Marine Diesel Engine mock test with a score of 85%.</div>
                <div class="notification-time">25 minutes ago</div>
                <div class="notification-actions">
                    <button class="mark-read">Mark as Read</button>
                    <button class="view-details">View Details</button>
                </div>
            </div>
            <div class="notification-item">
                <div class="notification-title">New Study Material</div>
                <div class="notification-message">Admin User has added new study material: MARPOL Annex VI.</div>
                <div class="notification-time">1 hour ago</div>
                <div class="notification-actions">
                    <button class="mark-read">Mark as Read</button>
                    <button class="view-details">View Details</button>
                </div>
            </div>
            <div class="notification-item">
                <div class="notification-title">New Video Uploaded</div>
                <div class="notification-message">Admin User has uploaded a new video: Centrifugal Pump Operation.</div>
                <div class="notification-time">2 hours ago</div>
                <div class="notification-actions">
                    <button class="mark-read">Mark as Read</button>
                    <button class="view-details">View Details</button>
                </div>
            </div>
            <div class="notification-item unread">
                <div class="notification-title">Payment Failed</div>
                <div class="notification-message">Subscription payment failed for user Vikram Singh. Please check payment details.</div>
                <div class="notification-time">3 hours ago</div>
                <div class="notification-actions">
                    <button class="mark-read">Mark as Read</button>
                    <button class="view-details">View Details</button>
                </div>
            </div>
            <div class="notification-item">
                <div class="notification-title">System Update</div>
                <div class="notification-message">The system will undergo maintenance on June 10, 2025 from 2:00 AM to 4:00 AM UTC.</div>
                <div class="notification-time">5 hours ago</div>
                <div class="notification-actions">
                    <button class="mark-read">Mark as Read</button>
                    <button class="view-details">View Details</button>
                </div>
            </div>
            <div class="notification-item">
                <div class="notification-title">New Comment</div>
                <div class="notification-message">Ankit Verma has commented on the study material: Marine Engine Cooling Systems.</div>
                <div class="notification-time">1 day ago</div>
                <div class="notification-actions">
                    <button class="mark-read">Mark as Read</button>
                    <button class="view-details">View Details</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(notificationSidebar);
    
    // Create overlay
    const notificationOverlay = document.createElement("div");
    notificationOverlay.className = "notification-overlay";
    document.body.appendChild(notificationOverlay);
    
    // Add event listeners
    const closeBtn = notificationSidebar.querySelector(".notification-sidebar-close");
    if (closeBtn) {
        closeBtn.addEventListener("click", function() {
            notificationSidebar.classList.remove("open");
            notificationOverlay.classList.remove("open");
        });
    }
    
    const markReadBtns = notificationSidebar.querySelectorAll(".mark-read");
    markReadBtns.forEach(btn => {
        btn.addEventListener("click", function() {
            const notificationItem = this.closest(".notification-item");
            notificationItem.classList.remove("unread");
            this.textContent = "Marked as Read";
            this.disabled = true;
            this.style.opacity = "0.5";
        });
    });
    
    const viewDetailsBtns = notificationSidebar.querySelectorAll(".view-details");
    viewDetailsBtns.forEach(btn => {
        btn.addEventListener("click", function() {
            const notificationTitle = this.closest(".notification-item").querySelector(".notification-title").textContent;
            showNotification(`Viewing details for: ${notificationTitle}`, "info");
        });
    });
}

// Function to create user profile dropdown
function createUserProfileDropdown() {
    const userProfileDropdown = document.createElement("div");
    userProfileDropdown.className = "user-profile-dropdown";
    
    userProfileDropdown.innerHTML = `
        <div class="user-profile-header">
            <div class="user-avatar">AU</div>
            <div class="user-info">
                <div class="user-name">Admin User</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <div class="user-profile-menu">
            <div class="menu-item">
                <i class="fas fa-user"></i>
                My Profile
            </div>
            <div class="menu-item">
                <i class="fas fa-cog"></i>
                Account Settings
            </div>
            <div class="menu-item">
                <i class="fas fa-bell"></i>
                Notification Preferences
            </div>
            <div class="menu-divider"></div>
            <div class="menu-item">
                <i class="fas fa-question-circle"></i>
                Help & Support
            </div>
            <div class="menu-item">
                <i class="fas fa-moon"></i>
                Dark Mode
            </div>
            <div class="menu-divider"></div>
            <div class="menu-item logout-item">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </div>
        </div>
    `;
    
    document.body.appendChild(userProfileDropdown);
    
    // Add event listeners
    const menuItems = userProfileDropdown.querySelectorAll(".menu-item");
    menuItems.forEach(item => {
        item.addEventListener("click", function() {
            const itemText = this.textContent.trim();
            
            if (itemText === "Logout") {
                showNotification("You have been logged out successfully.", "success");
                setTimeout(() => {
                    window.location.href = "login.html";
                }, 2000);
            } else if (itemText === "Dark Mode") {
                showNotification("Dark mode is not available in this demo.", "info");
            } else {
                showNotification(`Navigating to: ${itemText}`, "info");
            }
            
            userProfileDropdown.classList.remove("open");
        });
    });
}
