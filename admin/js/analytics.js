// Analytics page functionality
document.addEventListener("DOMContentLoaded", function() {
    // Initialize date range picker
    initializeDateRangePicker();
    
    // Initialize filter controls
    initializeAnalyticsFilters();
    
    // Initialize export buttons
    initializeExportButtons();
    
    // Initialize analytics charts
    initializeAnalyticsCharts();
    
    // Initialize data tables
    initializeDataTables();
});

// Function to initialize date range picker
function initializeDateRangePicker() {
    const dateRangeSelect = document.querySelector("#date-range");
    const customDateContainer = document.querySelector(".custom-date-container");
    const startDateInput = document.querySelector("#start-date");
    const endDateInput = document.querySelector("#end-date");
    
    if (dateRangeSelect) {
        dateRangeSelect.addEventListener("change", function() {
            if (this.value === "custom") {
                if (customDateContainer) {
                    customDateContainer.style.display = "flex";
                }
            } else {
                if (customDateContainer) {
                    customDateContainer.style.display = "none";
                }
                
                // Set predefined date range
                const today = new Date();
                let startDate = new Date(today);
                
                switch (this.value) {
                    case "today":
                        // Start date is today
                        break;
                    case "yesterday":
                        startDate.setDate(startDate.getDate() - 1);
                        break;
                    case "last7days":
                        startDate.setDate(startDate.getDate() - 6);
                        break;
                    case "last30days":
                        startDate.setDate(startDate.getDate() - 29);
                        break;
                    case "thismonth":
                        startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                        break;
                    case "lastmonth":
                        startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                        const endOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                        today.setDate(endOfLastMonth.getDate());
                        break;
                }
                
                // Update charts with new date range
                updateChartsWithDateRange(startDate, today);
            }
        });
    }
    
    // Apply button for custom date range
    const applyDateBtn = document.querySelector(".apply-date-btn");
    if (applyDateBtn) {
        applyDateBtn.addEventListener("click", function() {
            if (startDateInput && endDateInput && startDateInput.value && endDateInput.value) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                
                if (startDate > endDate) {
                    showNotification("Start date cannot be after end date", "error");
                    return;
                }
                
                // Update charts with custom date range
                updateChartsWithDateRange(startDate, endDate);
                showNotification("Date range applied", "success");
            } else {
                showNotification("Please select both start and end dates", "error");
            }
        });
    }
    
    // Set default date values for today
    if (startDateInput && endDateInput) {
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        startDateInput.value = formattedDate;
        endDateInput.value = formattedDate;
    }
}

// Function to update charts with new date range
function updateChartsWithDateRange(startDate, endDate) {
    console.log(`Updating charts with date range: ${startDate.toDateString()} to ${endDate.toDateString()}`);
    
    // In a real implementation, this would fetch new data from API
    // For this demo, we'll just simulate updating the charts
    
    // Show loading state
    const chartContainers = document.querySelectorAll(".chart-container");
    chartContainers.forEach(container => {
        const loadingOverlay = document.createElement("div");
        loadingOverlay.className = "loading-overlay";
        loadingOverlay.innerHTML = `
            <div class="spinner"></div>
            <div>Loading data...</div>
        `;
        container.appendChild(loadingOverlay);
    });
    
    // Simulate API delay
    setTimeout(() => {
        // Remove loading overlays
        document.querySelectorAll(".loading-overlay").forEach(overlay => overlay.remove());
        
        // Reinitialize charts with "new" data
        initializeAnalyticsCharts();
        
        // Update date range display
        const dateRangeDisplay = document.querySelector(".date-range-display");
        if (dateRangeDisplay) {
            const formatDate = (date) => {
                return date.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" });
            };
            
            dateRangeDisplay.textContent = `${formatDate(startDate)} - ${formatDate(endDate)}`;
        }
        
        // Show notification
        showNotification("Analytics data updated", "success");
    }, 1500);
}

// Function to initialize analytics filters
function initializeAnalyticsFilters() {
    // Category filter
    const categoryFilter = document.querySelector("#category-filter");
    if (categoryFilter) {
        categoryFilter.addEventListener("change", function() {
            applyAnalyticsFilters();
        });
    }
    
    // Status filter
    const statusFilter = document.querySelector("#status-filter");
    if (statusFilter) {
        statusFilter.addEventListener("change", function() {
            applyAnalyticsFilters();
        });
    }
    
    // Apply filters button
    const applyFiltersBtn = document.querySelector(".apply-filters-btn");
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener("click", function() {
            applyAnalyticsFilters();
        });
    }
    
    // Reset filters button
    const resetFiltersBtn = document.querySelector(".reset-filters-btn");
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener("click", function() {
            resetAnalyticsFilters();
        });
    }
}

// Function to apply analytics filters
function applyAnalyticsFilters() {
    const categoryFilter = document.querySelector("#category-filter");
    const statusFilter = document.querySelector("#status-filter");
    
    const category = categoryFilter ? categoryFilter.value : "all";
    const status = statusFilter ? statusFilter.value : "all";
    
    console.log(`Applying analytics filters - Category: ${category}, Status: ${status}`);
    
    // In a real implementation, this would fetch filtered data from API
    // For this demo, we'll just simulate updating the charts
    
    // Show loading state
    const chartContainers = document.querySelectorAll(".chart-container");
    chartContainers.forEach(container => {
        const loadingOverlay = document.createElement("div");
        loadingOverlay.className = "loading-overlay";
        loadingOverlay.innerHTML = `
            <div class="spinner"></div>
            <div>Filtering data...</div>
        `;
        container.appendChild(loadingOverlay);
    });
    
    // Simulate API delay
    setTimeout(() => {
        // Remove loading overlays
        document.querySelectorAll(".loading-overlay").forEach(overlay => overlay.remove());
        
        // Reinitialize charts with "filtered" data
        initializeAnalyticsCharts();
        
        // Show notification
        showNotification("Filters applied successfully", "success");
    }, 1500);
}

// Function to reset analytics filters
function resetAnalyticsFilters() {
    const categoryFilter = document.querySelector("#category-filter");
    const statusFilter = document.querySelector("#status-filter");
    const dateRangeSelect = document.querySelector("#date-range");
    
    if (categoryFilter) categoryFilter.value = "all";
    if (statusFilter) statusFilter.value = "all";
    if (dateRangeSelect) dateRangeSelect.value = "last30days";
    
    // Hide custom date container
    const customDateContainer = document.querySelector(".custom-date-container");
    if (customDateContainer) {
        customDateContainer.style.display = "none";
    }
    
    // Apply reset filters
    applyAnalyticsFilters();
    
    // Show notification
    showNotification("Filters reset", "info");
}

// Function to initialize export buttons
function initializeExportButtons() {
    const exportButtons = document.querySelectorAll(".export-btn");
    if (exportButtons.length > 0) {
        exportButtons.forEach(btn => {
            btn.addEventListener("click", function() {
                const format = this.getAttribute("data-format");
                exportAnalyticsData(format);
            });
        });
    }
}

// Function to export analytics data
function exportAnalyticsData(format) {
    console.log(`Exporting analytics data as ${format}`);
    
    // In a real implementation, this would generate and download a file
    // For this demo, we'll just show a notification
    
    // Show loading state
    const exportBtn = document.querySelector(`.export-btn[data-format="${format}"]`);
    if (exportBtn) {
        const originalText = exportBtn.textContent;
        exportBtn.disabled = true;
        exportBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Exporting...`;
        
        // Simulate export delay
        setTimeout(() => {
            exportBtn.disabled = false;
            exportBtn.textContent = originalText;
            
            // Show notification
            showNotification(`Analytics data exported as ${format.toUpperCase()} successfully!`, "success");
        }, 2000);
    }
}

// Function to initialize analytics charts
function initializeAnalyticsCharts() {
    // Check if Chart.js is loaded
    if (typeof Chart === "undefined") {
        // Load Chart.js if not already loaded
        const script = document.createElement("script");
        script.src = "https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js";
        script.onload = function() {
            createAnalyticsCharts();
        };
        document.head.appendChild(script);
    } else {
        createAnalyticsCharts();
    }
}

// Function to create analytics charts
function createAnalyticsCharts() {
    // Destroy existing charts to prevent duplicates
    Chart.helpers.each(Chart.instances, function(instance) {
        instance.destroy();
    });
    
    // User activity chart
    const userActivityCanvas = document.getElementById("user-activity-chart");
    if (userActivityCanvas) {
        const userActivityChart = new Chart(userActivityCanvas, {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                datasets: [{
                    label: "Active Users",
                    data: [120, 190, 300, 250, 400, 380],
                    backgroundColor: "rgba(59, 130, 246, 0.2)",
                    borderColor: "rgba(59, 130, 246, 1)",
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    // Content engagement chart
    const contentEngagementCanvas = document.getElementById("content-engagement-chart");
    if (contentEngagementCanvas) {
        const contentEngagementChart = new Chart(contentEngagementCanvas, {
            type: "bar",
            data: {
                labels: ["Study Materials", "Mock Tests", "Videos", "Papers"],
                datasets: [{
                    label: "Views",
                    data: [1200, 1900, 3000, 1500],
                    backgroundColor: "rgba(59, 130, 246, 0.7)",
                    borderColor: "rgba(59, 130, 246, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    // Revenue chart
    const revenueCanvas = document.getElementById("revenue-chart");
    if (revenueCanvas) {
        const revenueChart = new Chart(revenueCanvas, {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                datasets: [{
                    label: "Revenue",
                    data: [5000, 7500, 10000, 9000, 12000, 15000],
                    backgroundColor: "rgba(16, 185, 129, 0.2)",
                    borderColor: "rgba(16, 185, 129, 1)",
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `$${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return "$" + value;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // User demographics chart
    const demographicsCanvas = document.getElementById("demographics-chart");
    if (demographicsCanvas) {
        const demographicsChart = new Chart(demographicsCanvas, {
            type: "doughnut",
            data: {
                labels: ["18-24", "25-34", "35-44", "45-54", "55+"],
                datasets: [{
                    data: [25, 40, 20, 10, 5],
                    backgroundColor: [
                        "rgba(59, 130, 246, 0.7)",
                        "rgba(16, 185, 129, 0.7)",
                        "rgba(245, 158, 11, 0.7)",
                        "rgba(139, 92, 246, 0.7)",
                        "rgba(239, 68, 68, 0.7)"
                    ],
                    borderColor: [
                        "rgba(59, 130, 246, 1)",
                        "rgba(16, 185, 129, 1)",
                        "rgba(245, 158, 11, 1)",
                        "rgba(139, 92, 246, 1)",
                        "rgba(239, 68, 68, 1)"
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "bottom"
                    }
                }
            }
        });
    }
    
    // Test performance chart
    const testPerformanceCanvas = document.getElementById("test-performance-chart");
    if (testPerformanceCanvas) {
        const testPerformanceChart = new Chart(testPerformanceCanvas, {
            type: "radar",
            data: {
                labels: ["Function 1", "Function 2", "Function 3", "Function 4", "Function 5"],
                datasets: [{
                    label: "Average Score",
                    data: [75, 65, 80, 70, 85],
                    backgroundColor: "rgba(59, 130, 246, 0.2)",
                    borderColor: "rgba(59, 130, 246, 1)",
                    borderWidth: 2,
                    pointBackgroundColor: "rgba(59, 130, 246, 1)",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgba(59, 130, 246, 1)"
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 20
                        }
                    }
                }
            }
        });
    }
    
    // Add styles for loading overlay if not already present
    if (!document.getElementById("chart-loading-styles")) {
        const style = document.createElement("style");
        style.id = "chart-loading-styles";
        style.textContent = `
            .chart-container {
                position: relative;
            }
            
            .loading-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(255, 255, 255, 0.8);
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                z-index: 10;
            }
            
            .spinner {
                border: 4px solid rgba(0, 0, 0, 0.1);
                border-left-color: #3b82f6;
                border-radius: 50%;
                width: 30px;
                height: 30px;
                animation: spin 1s linear infinite;
                margin-bottom: 10px;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
}

// Function to initialize data tables
function initializeDataTables() {
    // Top content table
    initializeTopContentTable();
    
    // User activity table
    initializeUserActivityTable();
}

// Function to initialize top content table
function initializeTopContentTable() {
    const topContentTable = document.querySelector(".top-content-table");
    if (!topContentTable) return;
    
    // Add sort functionality to table headers
    const headers = topContentTable.querySelectorAll("th");
    headers.forEach(header => {
        if (header.getAttribute("data-sortable") === "true") {
            header.addEventListener("click", function() {
                const column = this.getAttribute("data-column");
                const currentDirection = this.getAttribute("data-direction") || "asc";
                const newDirection = currentDirection === "asc" ? "desc" : "asc";
                
                // Reset all headers
                headers.forEach(h => {
                    h.removeAttribute("data-direction");
                    h.querySelector("i")?.remove();
                });
                
                // Set direction on current header
                this.setAttribute("data-direction", newDirection);
                
                // Add sort icon
                const icon = document.createElement("i");
                icon.className = `fas fa-sort-${newDirection === "asc" ? "up" : "down"} ml-1`;
                this.appendChild(icon);
                
                // Sort table
                sortTable(topContentTable, column, newDirection);
            });
        }
    });
}

// Function to initialize user activity table
function initializeUserActivityTable() {
    const userActivityTable = document.querySelector(".user-activity-table");
    if (!userActivityTable) return;
    
    // Add sort functionality to table headers
    const headers = userActivityTable.querySelectorAll("th");
    headers.forEach(header => {
        if (header.getAttribute("data-sortable") === "true") {
            header.addEventListener("click", function() {
                const column = this.getAttribute("data-column");
                const currentDirection = this.getAttribute("data-direction") || "asc";
                const newDirection = currentDirection === "asc" ? "desc" : "asc";
                
                // Reset all headers
                headers.forEach(h => {
                    h.removeAttribute("data-direction");
                    h.querySelector("i")?.remove();
                });
                
                // Set direction on current header
                this.setAttribute("data-direction", newDirection);
                
                // Add sort icon
                const icon = document.createElement("i");
                icon.className = `fas fa-sort-${newDirection === "asc" ? "up" : "down"} ml-1`;
                this.appendChild(icon);
                
                // Sort table
                sortTable(userActivityTable, column, newDirection);
            });
        }
    });
}

// Function to sort table
function sortTable(table, column, direction) {
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));
    
    // Sort rows
    rows.sort((a, b) => {
        const aValue = a.querySelector(`td[data-column="${column}"]`).textContent.trim();
        const bValue = b.querySelector(`td[data-column="${column}"]`).textContent.trim();
        
        // Check if values are numbers
        const aNum = parseFloat(aValue.replace(/[^0-9.-]+/g, ""));
        const bNum = parseFloat(bValue.replace(/[^0-9.-]+/g, ""));
        
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return direction === "asc" ? aNum - bNum : bNum - aNum;
        }
        
        // Sort as strings
        return direction === "asc" ? 
            aValue.localeCompare(bValue) : 
            bValue.localeCompare(aValue);
    });
    
    // Reorder rows in the table
    rows.forEach(row => tbody.appendChild(row));
    
    // Show notification
    showNotification(`Table sorted by ${column} (${direction === "asc" ? "ascending" : "descending"})`, "info");
}
