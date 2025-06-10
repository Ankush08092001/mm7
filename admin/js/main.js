// Main JavaScript for Admin Dashboard
document.addEventListener("DOMContentLoaded", function() {
    // Initialize chart filter buttons
    const filterBtns = document.querySelectorAll(".filter-btn");
    if (filterBtns.length > 0) {
        filterBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                // Remove active class from all buttons
                filterBtns.forEach(b => b.classList.remove("active"));
                
                // Add active class to clicked button
                this.classList.add("active");
                
                // Get period from data attribute
                const period = this.getAttribute("data-period");
                
                // Show notification
                showNotification(`Showing data for ${period} period`, "info");
            });
        });
    }
    
    // Create notification container if it doesn't exist
    if (!document.querySelector(".notification-container")) {
        const notificationContainer = document.createElement("div");
        notificationContainer.className = "notification-container";
        document.body.appendChild(notificationContainer);
    }
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
