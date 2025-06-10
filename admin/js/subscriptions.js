// Subscriptions Management functionality
document.addEventListener("DOMContentLoaded", function() {
    // Initialize action buttons
    initializeSubscriptionActionButtons();
    
    // Initialize add subscription plan button
    initializeAddSubscriptionPlanButton();
    
    // Initialize search and filter
    initializeSubscriptionSearch();
    
    // Initialize pagination
    initializeSubscriptionPagination();
    
    // Initialize bulk actions
    initializeSubscriptionBulkActions();
    
    // Initialize subscription stats charts
    initializeSubscriptionCharts();
});

// Function to initialize subscription action buttons
function initializeSubscriptionActionButtons() {
    // View subscription details buttons
    const viewButtons = document.querySelectorAll(".view-subscription-btn");
    if (viewButtons.length > 0) {
        viewButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const subscriptionId = this.getAttribute("data-id");
                const subscriptionName = this.closest("tr").querySelector(".subscription-name").textContent;
                viewSubscriptionDetails(subscriptionId, subscriptionName);
            });
        });
    }
    
    // Edit subscription buttons
    const editButtons = document.querySelectorAll(".edit-subscription-btn");
    if (editButtons.length > 0) {
        editButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const subscriptionId = this.getAttribute("data-id");
                const subscriptionName = this.closest("tr").querySelector(".subscription-name").textContent;
                editSubscription(subscriptionId, subscriptionName);
            });
        });
    }
    
    // Delete subscription buttons
    const deleteButtons = document.querySelectorAll(".delete-subscription-btn");
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const subscriptionId = this.getAttribute("data-id");
                const subscriptionName = this.closest("tr").querySelector(".subscription-name").textContent;
                deleteSubscription(subscriptionId, subscriptionName);
            });
        });
    }
    
    // View subscribers buttons
    const subscribersButtons = document.querySelectorAll(".view-subscribers-btn");
    if (subscribersButtons.length > 0) {
        subscribersButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const subscriptionId = this.getAttribute("data-id");
                const subscriptionName = this.closest("tr").querySelector(".subscription-name").textContent;
                viewSubscribers(subscriptionId, subscriptionName);
            });
        });
    }
}

// Function to initialize add subscription plan button
function initializeAddSubscriptionPlanButton() {
    const addBtn = document.querySelector(".add-subscription-btn");
    if (addBtn) {
        addBtn.addEventListener("click", function(e) {
            e.preventDefault();
            showAddSubscriptionModal();
        });
    }
}

// Function to initialize subscription search and filter
function initializeSubscriptionSearch() {
    // Search input
    const searchInput = document.querySelector(".search-input");
    if (searchInput) {
        searchInput.addEventListener("keyup", function(e) {
            if (e.key === "Enter") {
                searchSubscriptions(this.value);
            }
        });
    }
    
    // Search button
    const searchBtn = document.querySelector(".search-btn");
    if (searchBtn) {
        searchBtn.addEventListener("click", function() {
            const searchInput = document.querySelector(".search-input");
            if (searchInput) {
                searchSubscriptions(searchInput.value);
            }
        });
    }
    
    // Status filter
    const statusFilter = document.querySelector("#status-filter");
    if (statusFilter) {
        statusFilter.addEventListener("change", function() {
            filterSubscriptions();
        });
    }
    
    // Duration filter
    const durationFilter = document.querySelector("#duration-filter");
    if (durationFilter) {
        durationFilter.addEventListener("change", function() {
            filterSubscriptions();
        });
    }
    
    // Apply filters button
    const filterBtn = document.querySelector(".filter-btn");
    if (filterBtn) {
        filterBtn.addEventListener("click", function() {
            filterSubscriptions();
        });
    }
    
    // Reset filters button
    const resetBtn = document.querySelector(".reset-filter-btn");
    if (resetBtn) {
        resetBtn.addEventListener("click", function() {
            resetSubscriptionFilters();
        });
    }
}

// Function to search subscriptions
function searchSubscriptions(query) {
    if (!query) return;
    
    console.log(`Searching subscriptions with query: ${query}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we'll simulate filtering the table
    
    const subscriptionRows = document.querySelectorAll(".subscription-row");
    let matchCount = 0;
    
    subscriptionRows.forEach(row => {
        const subscriptionName = row.querySelector(".subscription-name").textContent.toLowerCase();
        
        if (subscriptionName.includes(query.toLowerCase())) {
            row.style.display = "";
            matchCount++;
        } else {
            row.style.display = "none";
        }
    });
    
    // Update results count
    updateSubscriptionResultsCount(matchCount);
    
    // Show notification
    showNotification(`Found ${matchCount} subscriptions matching "${query}"`, "info");
}

// Function to filter subscriptions
function filterSubscriptions() {
    const statusFilter = document.querySelector("#status-filter");
    const durationFilter = document.querySelector("#duration-filter");
    
    if (!statusFilter && !durationFilter) return;
    
    const status = statusFilter ? statusFilter.value : "all";
    const duration = durationFilter ? durationFilter.value : "all";
    
    console.log(`Filtering subscriptions - Status: ${status}, Duration: ${duration}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we'll simulate filtering the table
    
    const subscriptionRows = document.querySelectorAll(".subscription-row");
    let matchCount = 0;
    
    subscriptionRows.forEach(row => {
        const subscriptionStatus = row.querySelector(".subscription-status").textContent;
        const subscriptionDuration = row.querySelector(".subscription-duration").textContent;
        
        const statusMatch = status === "all" || subscriptionStatus.toLowerCase().includes(status.toLowerCase());
        const durationMatch = duration === "all" || subscriptionDuration.includes(duration);
        
        if (statusMatch && durationMatch) {
            row.style.display = "";
            matchCount++;
        } else {
            row.style.display = "none";
        }
    });
    
    // Update results count
    updateSubscriptionResultsCount(matchCount);
    
    // Show notification
    showNotification(`Filters applied. Found ${matchCount} matching subscriptions.`, "info");
}

// Function to reset subscription filters
function resetSubscriptionFilters() {
    const statusFilter = document.querySelector("#status-filter");
    const durationFilter = document.querySelector("#duration-filter");
    const searchInput = document.querySelector(".search-input");
    
    if (statusFilter) statusFilter.value = "all";
    if (durationFilter) durationFilter.value = "all";
    if (searchInput) searchInput.value = "";
    
    // Show all rows
    const subscriptionRows = document.querySelectorAll(".subscription-row");
    subscriptionRows.forEach(row => {
        row.style.display = "";
    });
    
    // Update results count
    updateSubscriptionResultsCount(subscriptionRows.length);
    
    // Show notification
    showNotification("Filters reset", "info");
}

// Function to update subscription results count
function updateSubscriptionResultsCount(count) {
    const resultsCount = document.querySelector(".results-count");
    if (resultsCount) {
        resultsCount.textContent = `Showing ${count} subscription plans`;
    }
}

// Function to initialize subscription pagination
function initializeSubscriptionPagination() {
    const paginationBtns = document.querySelectorAll(".pagination-btn");
    if (paginationBtns.length > 0) {
        paginationBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                // Remove active class from all buttons
                paginationBtns.forEach(b => b.classList.remove("active"));
                // Add active class to clicked button
                this.classList.add("active");
                
                // In a real implementation, this would load the corresponding page
                // For this demo, we'll just show a notification
                showNotification(`Navigating to page ${this.textContent}`, "info");
            });
        });
    }
    
    // Previous page button
    const prevBtn = document.querySelector(".prev-page-btn");
    if (prevBtn) {
        prevBtn.addEventListener("click", function() {
            const activePage = document.querySelector(".pagination-btn.active");
            if (activePage && activePage.previousElementSibling && activePage.previousElementSibling.classList.contains("pagination-btn")) {
                activePage.previousElementSibling.click();
            }
        });
    }
    
    // Next page button
    const nextBtn = document.querySelector(".next-page-btn");
    if (nextBtn) {
        nextBtn.addEventListener("click", function() {
            const activePage = document.querySelector(".pagination-btn.active");
            if (activePage && activePage.nextElementSibling && activePage.nextElementSibling.classList.contains("pagination-btn")) {
                activePage.nextElementSibling.click();
            }
        });
    }
}

// Function to initialize subscription bulk actions
function initializeSubscriptionBulkActions() {
    // Select all checkbox
    const selectAllCheckbox = document.querySelector("#select-all");
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function() {
            const checkboxes = document.querySelectorAll(".subscription-checkbox");
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            
            // Update bulk actions state
            updateSubscriptionBulkActionsState();
        });
    }
    
    // Individual checkboxes
    const subscriptionCheckboxes = document.querySelectorAll(".subscription-checkbox");
    if (subscriptionCheckboxes.length > 0) {
        subscriptionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener("change", function() {
                // Update select all checkbox
                const selectAllCheckbox = document.querySelector("#select-all");
                const allCheckboxes = document.querySelectorAll(".subscription-checkbox");
                const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                }
                
                // Update bulk actions state
                updateSubscriptionBulkActionsState();
            });
        });
    }
    
    // Bulk action button
    const bulkActionBtn = document.querySelector(".bulk-action-btn");
    if (bulkActionBtn) {
        bulkActionBtn.addEventListener("click", function() {
            const bulkActionSelect = document.querySelector("#bulk-action");
            if (bulkActionSelect) {
                const action = bulkActionSelect.value;
                executeSubscriptionBulkAction(action);
            }
        });
    }
}

// Function to update subscription bulk actions state
function updateSubscriptionBulkActionsState() {
    const checkedCheckboxes = document.querySelectorAll(".subscription-checkbox:checked");
    const bulkActionBtn = document.querySelector(".bulk-action-btn");
    const bulkActionSelect = document.querySelector("#bulk-action");
    
    if (bulkActionBtn && bulkActionSelect) {
        if (checkedCheckboxes.length > 0) {
            bulkActionBtn.disabled = false;
            bulkActionSelect.disabled = false;
        } else {
            bulkActionBtn.disabled = true;
            bulkActionSelect.disabled = true;
        }
    }
}

// Function to execute subscription bulk action
function executeSubscriptionBulkAction(action) {
    const checkedCheckboxes = document.querySelectorAll(".subscription-checkbox:checked");
    if (checkedCheckboxes.length === 0) return;
    
    const subscriptionIds = Array.from(checkedCheckboxes).map(cb => cb.value);
    console.log(`Executing bulk action: ${action} on subscriptions: ${subscriptionIds.join(", ")}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we'll just show a notification
    
    let message = "";
    
    switch (action) {
        case "delete":
            message = `Deleted ${checkedCheckboxes.length} subscription plans`;
            // Remove rows from table
            checkedCheckboxes.forEach(cb => {
                const row = cb.closest("tr");
                if (row) row.remove();
            });
            break;
        case "activate":
            message = `Activated ${checkedCheckboxes.length} subscription plans`;
            // Update status in table
            checkedCheckboxes.forEach(cb => {
                const row = cb.closest("tr");
                if (row) {
                    const statusCell = row.querySelector(".subscription-status");
                    if (statusCell) {
                        statusCell.textContent = "Active";
                        statusCell.className = "subscription-status status-active";
                    }
                }
            });
            break;
        case "deactivate":
            message = `Deactivated ${checkedCheckboxes.length} subscription plans`;
            // Update status in table
            checkedCheckboxes.forEach(cb => {
                const row = cb.closest("tr");
                if (row) {
                    const statusCell = row.querySelector(".subscription-status");
                    if (statusCell) {
                        statusCell.textContent = "Inactive";
                        statusCell.className = "subscription-status status-inactive";
                    }
                }
            });
            break;
        default:
            message = `Executed ${action} on ${checkedCheckboxes.length} subscription plans`;
    }
    
    // Update results count
    const remainingRows = document.querySelectorAll(".subscription-row");
    updateSubscriptionResultsCount(remainingRows.length);
    
    // Reset checkboxes
    const selectAllCheckbox = document.querySelector("#select-all");
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
    }
    
    // Update bulk actions state
    updateSubscriptionBulkActionsState();
    
    // Show notification
    showNotification(message, "success");
}

// Function to view subscription details
function viewSubscriptionDetails(subscriptionId, subscriptionName) {
    console.log(`Viewing details for subscription: ${subscriptionName} (ID: ${subscriptionId})`);
    
    // Create modal if it doesn't exist
    let modal = document.getElementById("subscription-details-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "subscription-details-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Subscription Plan Details</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="subscription-details">
                        <h3 id="detail-subscription-name">${subscriptionName}</h3>
                        <div class="detail-group">
                            <div class="detail-label">Status:</div>
                            <div class="detail-value" id="detail-subscription-status">Active</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Price:</div>
                            <div class="detail-value" id="detail-subscription-price">$99.99</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Duration:</div>
                            <div class="detail-value" id="detail-subscription-duration">3 months</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Subscribers:</div>
                            <div class="detail-value" id="detail-subscription-subscribers">245</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Created On:</div>
                            <div class="detail-value" id="detail-subscription-date">May 15, 2025</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Features:</div>
                            <div class="detail-value" id="detail-subscription-features">
                                <ul>
                                    <li>Access to all study materials</li>
                                    <li>Unlimited mock tests</li>
                                    <li>Video tutorials</li>
                                    <li>Exam papers archive</li>
                                </ul>
                            </div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Description:</div>
                            <div class="detail-value" id="detail-subscription-description">Comprehensive subscription plan with access to all learning resources.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline close-btn">Close</button>
                    <button type="button" class="btn btn-primary subscribers-btn">View Subscribers</button>
                    <button type="button" class="btn btn-secondary edit-btn">Edit Plan</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Add event listeners
        modal.querySelector(".close").addEventListener("click", function() {
            modal.style.display = "none";
        });
        
        modal.querySelector(".close-btn").addEventListener("click", function() {
            modal.style.display = "none";
        });
        
        modal.querySelector(".edit-btn").addEventListener("click", function() {
            modal.style.display = "none";
            editSubscription(subscriptionId, subscriptionName);
        });
        
        modal.querySelector(".subscribers-btn").addEventListener("click", function() {
            modal.style.display = "none";
            viewSubscribers(subscriptionId, subscriptionName);
        });
        
        // Close when clicking outside
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
        
        // Add styles
        const style = document.createElement("style");
        style.textContent = `
            .subscription-details {
                padding: 10px;
            }
            
            .detail-group {
                display: flex;
                margin-bottom: 15px;
            }
            
            .detail-label {
                font-weight: 600;
                width: 120px;
            }
            
            .detail-value ul {
                margin: 0;
                padding-left: 20px;
            }
        `;
        document.head.appendChild(style);
    } else {
        // Update modal content with subscription data
        modal.querySelector("#detail-subscription-name").textContent = subscriptionName;
        
        // In a real implementation, we would fetch subscription data from API
        // For this demo, we'll use mock data
        const mockSubscriptionData = {
            status: "Active",
            price: "$99.99",
            duration: "3 months",
            subscribers: "245",
            date: "May 15, 2025",
            features: [
                "Access to all study materials",
                "Unlimited mock tests",
                "Video tutorials",
                "Exam papers archive"
            ],
            description: "Comprehensive subscription plan with access to all learning resources."
        };
        
        modal.querySelector("#detail-subscription-status").textContent = mockSubscriptionData.status;
        modal.querySelector("#detail-subscription-price").textContent = mockSubscriptionData.price;
        modal.querySelector("#detail-subscription-duration").textContent = mockSubscriptionData.duration;
        modal.querySelector("#detail-subscription-subscribers").textContent = mockSubscriptionData.subscribers;
        modal.querySelector("#detail-subscription-date").textContent = mockSubscriptionData.date;
        modal.querySelector("#detail-subscription-description").textContent = mockSubscriptionData.description;
        
        // Update features list
        const featuresList = modal.querySelector("#detail-subscription-features ul");
        featuresList.innerHTML = mockSubscriptionData.features.map(feature => `<li>${feature}</li>`).join("");
    }
    
    // Show modal
    modal.style.display = "block";
}

// Function to edit subscription
function editSubscription(subscriptionId, subscriptionName) {
    console.log(`Editing subscription: ${subscriptionName} (ID: ${subscriptionId})`);
    
    // Create modal if it doesn't exist
    let modal = document.getElementById("edit-subscription-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "edit-subscription-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Edit Subscription Plan</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="edit-subscription-form">
                        <div class="form-group">
                            <label for="edit-subscription-name">Plan Name</label>
                            <input type="text" id="edit-subscription-name" name="edit-subscription-name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-subscription-price">Price</label>
                            <div class="price-input">
                                <span class="currency">$</span>
                                <input type="number" id="edit-subscription-price" name="edit-subscription-price" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit-subscription-duration">Duration</label>
                            <div class="duration-input">
                                <input type="number" id="edit-subscription-duration-value" name="edit-subscription-duration-value" min="1" required>
                                <select id="edit-subscription-duration-unit" name="edit-subscription-duration-unit">
                                    <option value="days">Days</option>
                                    <option value="months">Months</option>
                                    <option value="years">Years</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit-subscription-status">Status</label>
                            <select id="edit-subscription-status" name="edit-subscription-status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Features</label>
                            <div class="features-list">
                                <div class="feature-item">
                                    <input type="checkbox" id="edit-feature-1" name="edit-feature-1" checked>
                                    <label for="edit-feature-1">Access to all study materials</label>
                                </div>
                                <div class="feature-item">
                                    <input type="checkbox" id="edit-feature-2" name="edit-feature-2" checked>
                                    <label for="edit-feature-2">Unlimited mock tests</label>
                                </div>
                                <div class="feature-item">
                                    <input type="checkbox" id="edit-feature-3" name="edit-feature-3" checked>
                                    <label for="edit-feature-3">Video tutorials</label>
                                </div>
                                <div class="feature-item">
                                    <input type="checkbox" id="edit-feature-4" name="edit-feature-4" checked>
                                    <label for="edit-feature-4">Exam papers archive</label>
                                </div>
                                <div class="feature-item">
                                    <input type="checkbox" id="edit-feature-5" name="edit-feature-5">
                                    <label for="edit-feature-5">One-on-one tutoring</label>
                                </div>
                                <div class="feature-item">
                                    <input type="checkbox" id="edit-feature-6" name="edit-feature-6">
                                    <label for="edit-feature-6">Priority support</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit-subscription-description">Description</label>
                            <textarea id="edit-subscription-description" name="edit-subscription-description" rows="4"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                    <button type="button" class="btn btn-primary save-btn">Save Changes</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Add event listeners
        modal.querySelector(".close").addEventListener("click", function() {
            modal.style.display = "none";
        });
        
        modal.querySelector(".cancel-btn").addEventListener("click", function() {
            modal.style.display = "none";
        });
        
        modal.querySelector(".save-btn").addEventListener("click", function() {
            const form = document.getElementById("edit-subscription-form");
            const name = form.querySelector("#edit-subscription-name").value;
            
            if (name) {
                modal.style.display = "none";
                showNotification(`Subscription plan "${name}" updated successfully!`, "success");
                
                // Update subscription row in table if it exists
                const subscriptionRow = document.querySelector(`.subscription-row[data-id="${subscriptionId}"]`);
                if (subscriptionRow) {
                    subscriptionRow.querySelector(".subscription-name").textContent = name;
                    
                    const price = form.querySelector("#edit-subscription-price").value;
                    const durationValue = form.querySelector("#edit-subscription-duration-value").value;
                    const durationUnit = form.querySelector("#edit-subscription-duration-unit").value;
                    const status = form.querySelector("#edit-subscription-status").value;
                    
                    subscriptionRow.querySelector(".subscription-price").textContent = `$${price}`;
                    subscriptionRow.querySelector(".subscription-duration").textContent = `${durationValue} ${durationUnit}`;
                    
                    const statusCell = subscriptionRow.querySelector(".subscription-status");
                    statusCell.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                    statusCell.className = `subscription-status status-${status}`;
                }
            } else {
                showNotification("Please fill in all required fields", "error");
            }
        });
        
        // Close when clicking outside
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
        
        // Add styles
        const style = document.createElement("style");
        style.textContent = `
            .price-input {
                display: flex;
                align-items: center;
            }
            
            .price-input .currency {
                margin-right: 5px;
                font-weight: 600;
            }
            
            .duration-input {
                display: flex;
                align-items: center;
            }
            
            .duration-input input {
                width: 80px;
                margin-right: 10px;
            }
            
            .features-list {
                border: 1px solid #e5e7eb;
                border-radius: 4px;
                padding: 10px;
                max-height: 200px;
                overflow-y: auto;
            }
            
            .feature-item {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
            }
            
            .feature-item:last-child {
                margin-bottom: 0;
            }
            
            .feature-item input[type="checkbox"] {
                margin-right: 10px;
            }
        `;
        document.head.appendChild(style);
    }
    
    // In a real implementation, we would fetch subscription data from API
    // For this demo, we'll use mock data
    const mockSubscriptionData = {
        name: subscriptionName,
        price: "99.99",
        durationValue: "3",
        durationUnit: "months",
        status: "active",
        features: [
            "Access to all study materials",
            "Unlimited mock tests",
            "Video tutorials",
            "Exam papers archive"
        ],
        description: "Comprehensive subscription plan with access to all learning resources."
    };
    
    // Update form fields with subscription data
    document.getElementById("edit-subscription-name").value = mockSubscriptionData.name;
    document.getElementById("edit-subscription-price").value = mockSubscriptionData.price;
    document.getElementById("edit-subscription-duration-value").value = mockSubscriptionData.durationValue;
    document.getElementById("edit-subscription-duration-unit").value = mockSubscriptionData.durationUnit;
    document.getElementById("edit-subscription-status").value = mockSubscriptionData.status;
    document.getElementById("edit-subscription-description").value = mockSubscriptionData.description;
    
    // Update feature checkboxes
    document.getElementById("edit-feature-1").checked = mockSubscriptionData.features.includes("Access to all study materials");
    document.getElementById("edit-feature-2").checked = mockSubscriptionData.features.includes("Unlimited mock tests");
    document.getElementById("edit-feature-3").checked = mockSubscriptionData.features.includes("Video tutorials");
    document.getElementById("edit-feature-4").checked = mockSubscriptionData.features.includes("Exam papers archive");
    document.getElementById("edit-feature-5").checked = mockSubscriptionData.features.includes("One-on-one tutoring");
    document.getElementById("edit-feature-6").checked = mockSubscriptionData.features.includes("Priority support");
    
    // Show modal
    modal.style.display = "block";
}

// Function to delete subscription
function deleteSubscription(subscriptionId, subscriptionName) {
    console.log(`Deleting subscription: ${subscriptionName} (ID: ${subscriptionId})`);
    
    // Show confirmation modal
    showConfirmationModal(
        `Are you sure you want to delete subscription plan "${subscriptionName}"?`,
        function() {
            // In a real implementation, this would send an API request
            // For this demo, we'll just remove the row from the table
            
            const subscriptionRow = document.querySelector(`.subscription-row[data-id="${subscriptionId}"]`);
            if (subscriptionRow) {
                subscriptionRow.remove();
                
                // Update results count
                const remainingRows = document.querySelectorAll(".subscription-row");
                updateSubscriptionResultsCount(remainingRows.length);
                
                // Show notification
                showNotification(`Subscription plan ${subscriptionName} deleted successfully!`, "success");
            }
        },
        "Delete Subscription Plan",
        "Delete",
        "btn-danger"
    );
}

// Function to view subscribers
function viewSubscribers(subscriptionId, subscriptionName) {
    console.log(`Viewing subscribers for subscription: ${subscriptionName} (ID: ${subscriptionId})`);
    
    // Create modal if it doesn't exist
    let modal = document.getElementById("subscribers-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "subscribers-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content large">
                <div class="modal-header">
                    <h2>Subscribers for <span id="subscribers-plan-name">${subscriptionName}</span></h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="subscribers-list">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>john.doe@example.com</td>
                                    <td>May 1, 2025</td>
                                    <td>Aug 1, 2025</td>
                                    <td><span class="status-active">Active</span></td>
                                    <td>
                                        <div class="actions">
                                            <button class="action-btn view-user-btn" title="View User">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn cancel-subscription-btn" title="Cancel Subscription">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>jane.smith@example.com</td>
                                    <td>Apr 15, 2025</td>
                                    <td>Jul 15, 2025</td>
                                    <td><span class="status-active">Active</span></td>
                                    <td>
                                        <div class="actions">
                                            <button class="action-btn view-user-btn" title="View User">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn cancel-subscription-btn" title="Cancel Subscription">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Robert Johnson</td>
                                    <td>robert.johnson@example.com</td>
                                    <td>Mar 10, 2025</td>
                                    <td>Jun 10, 2025</td>
                                    <td><span class="status-active">Active</span></td>
                                    <td>
                                        <div class="actions">
                                            <button class="action-btn view-user-btn" title="View User">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn cancel-subscription-btn" title="Cancel Subscription">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Emily Davis</td>
                                    <td>emily.davis@example.com</td>
                                    <td>Feb 20, 2025</td>
                                    <td>May 20, 2025</td>
                                    <td><span class="status-expired">Expired</span></td>
                                    <td>
                                        <div class="actions">
                                            <button class="action-btn view-user-btn" title="View User">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn renew-subscription-btn" title="Renew Subscription">
                                                <i class="fas fa-sync"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline close-btn">Close</button>
                    <button type="button" class="btn btn-primary export-btn">Export List</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Add event listeners
        modal.querySelector(".close").addEventListener("click", function() {
            modal.style.display = "none";
        });
        
        modal.querySelector(".close-btn").addEventListener("click", function() {
            modal.style.display = "none";
        });
        
        modal.querySelector(".export-btn").addEventListener("click", function() {
            showNotification(`Exporting subscribers list for ${subscriptionName}...`, "info");
            setTimeout(() => {
                showNotification(`Subscribers list exported successfully!`, "success");
            }, 1500);
        });
        
        // View user buttons
        const viewUserBtns = modal.querySelectorAll(".view-user-btn");
        viewUserBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                const userName = this.closest("tr").querySelector("td:first-child").textContent;
                showNotification(`Viewing user profile: ${userName}`, "info");
            });
        });
        
        // Cancel subscription buttons
        const cancelSubscriptionBtns = modal.querySelectorAll(".cancel-subscription-btn");
        cancelSubscriptionBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                const userName = this.closest("tr").querySelector("td:first-child").textContent;
                showConfirmationModal(
                    `Are you sure you want to cancel the subscription for ${userName}?`,
                    () => {
                        const row = this.closest("tr");
                        const statusCell = row.querySelector("td:nth-child(5) span");
                        statusCell.textContent = "Cancelled";
                        statusCell.className = "status-cancelled";
                        
                        // Update actions
                        const actionsCell = row.querySelector("td:last-child .actions");
                        actionsCell.innerHTML = `
                            <button class="action-btn view-user-btn" title="View User">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn renew-subscription-btn" title="Renew Subscription">
                                <i class="fas fa-sync"></i>
                            </button>
                        `;
                        
                        // Add event listener to new button
                        actionsCell.querySelector(".view-user-btn").addEventListener("click", function() {
                            showNotification(`Viewing user profile: ${userName}`, "info");
                        });
                        
                        actionsCell.querySelector(".renew-subscription-btn").addEventListener("click", function() {
                            showNotification(`Renewing subscription for ${userName}...`, "info");
                            setTimeout(() => {
                                statusCell.textContent = "Active";
                                statusCell.className = "status-active";
                                showNotification(`Subscription renewed for ${userName}!`, "success");
                            }, 1500);
                        });
                        
                        showNotification(`Subscription cancelled for ${userName}`, "success");
                    },
                    "Cancel Subscription",
                    "Cancel",
                    "btn-danger"
                );
            });
        });
        
        // Renew subscription buttons
        const renewSubscriptionBtns = modal.querySelectorAll(".renew-subscription-btn");
        renewSubscriptionBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                const userName = this.closest("tr").querySelector("td:first-child").textContent;
                showNotification(`Renewing subscription for ${userName}...`, "info");
                
                setTimeout(() => {
                    const row = this.closest("tr");
                    const statusCell = row.querySelector("td:nth-child(5) span");
                    statusCell.textContent = "Active";
                    statusCell.className = "status-active";
                    
                    // Update dates
                    const today = new Date();
                    const startDate = new Date(today);
                    const endDate = new Date(today);
                    endDate.setMonth(endDate.getMonth() + 3);
                    
                    row.querySelector("td:nth-child(3)").textContent = startDate.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" });
                    row.querySelector("td:nth-child(4)").textContent = endDate.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" });
                    
                    // Update actions
                    const actionsCell = row.querySelector("td:last-child .actions");
                    actionsCell.innerHTML = `
                        <button class="action-btn view-user-btn" title="View User">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn cancel-subscription-btn" title="Cancel Subscription">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    // Add event listeners to new buttons
                    actionsCell.querySelector(".view-user-btn").addEventListener("click", function() {
                        showNotification(`Viewing user profile: ${userName}`, "info");
                    });
                    
                    actionsCell.querySelector(".cancel-subscription-btn").addEventListener("click", function() {
                        showConfirmationModal(
                            `Are you sure you want to cancel the subscription for ${userName}?`,
                            () => {
                                statusCell.textContent = "Cancelled";
                                statusCell.className = "status-cancelled";
                                showNotification(`Subscription cancelled for ${userName}`, "success");
                            },
                            "Cancel Subscription",
                            "Cancel",
                            "btn-danger"
                        );
                    });
                    
                    showNotification(`Subscription renewed for ${userName}!`, "success");
                }, 1500);
            });
        });
        
        // Close when clicking outside
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
        
        // Add styles
        const style = document.createElement("style");
        style.textContent = `
            .modal-content.large {
                max-width: 800px;
            }
            
            .subscribers-list {
                max-height: 400px;
                overflow-y: auto;
            }
            
            .status-active {
                color: #10b981;
                font-weight: 600;
            }
            
            .status-expired {
                color: #f59e0b;
                font-weight: 600;
            }
            
            .status-cancelled {
                color: #ef4444;
                font-weight: 600;
            }
        `;
        document.head.appendChild(style);
    } else {
        // Update modal title with subscription name
        modal.querySelector("#subscribers-plan-name").textContent = subscriptionName;
    }
    
    // Show modal
    modal.style.display = "block";
}

// Function to show add subscription modal
function showAddSubscriptionModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById("add-subscription-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "add-subscription-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New Subscription Plan</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="add-subscription-form">
                        <div class="form-group">
                            <label for="subscription-name">Plan Name</label>
                            <input type="text" id="subscription-name" name="subscription-name" required>
                        </div>
                        <div class="form-group">
                            <label for="subscription-price">Price</label>
                            <div class="price-input">
                                <span class="currency">$</span>
                                <input type="number" id="subscription-price" name="subscription-price" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subscription-duration">Duration</label>
                            <div class="duration-input">
                                <input type="number" id="subscription-duration-value" name="subscription-duration-value" min="1" required>
                                <select id="subscription-duration-unit" name="subscription-duration-unit">
                                    <option value="days">Days</option>
                                    <option value="months" selected>Months</option>
                                    <option value="years">Years</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subscription-status">Status</label>
                            <select id="subscription-status" name="subscription-status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Features</label>
                            <div class="features-list">
                                <div class="feature-item">
                                    <input type="checkbox" id="feature-1" name="feature-1" checked>
                                    <label for="feature-1">Access to all study materials</label>
                                </div>
                                <div class="feature-item">
                                    <input type="checkbox" id="feature-2" name="feature-2" checked>
                                    <label for="feature-2">Unlimited mock tests</label>
                                </div>
                                <div class="feature-item">
                                    <input type="checkbox" id="feature-3" name="feature-3" checked>
                                    <label for="feature-3">Video tutorials</label>
                                </div>
                                <div class="feature-item">
                                    <input type="checkbox" id="feature-4" name="feature-4" checked>
                                    <label for="feature-4">Exam papers archive</label>
                                </div>
                                <div class="feature-item">
                                    <input type="checkbox" id="feature-5" name="feature-5">
                                    <label for="feature-5">One-on-one tutoring</label>
                                </div>
                                <div class="feature-item">
                                    <input type="checkbox" id="feature-6" name="feature-6">
                                    <label for="feature-6">Priority support</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subscription-description">Description</label>
                            <textarea id="subscription-description" name="subscription-description" rows="4"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                    <button type="button" class="btn btn-primary save-btn">Add Plan</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Add event listeners
        modal.querySelector(".close").addEventListener("click", function() {
            modal.style.display = "none";
        });
        
        modal.querySelector(".cancel-btn").addEventListener("click", function() {
            modal.style.display = "none";
        });
        
        modal.querySelector(".save-btn").addEventListener("click", function() {
            const form = document.getElementById("add-subscription-form");
            const name = form.querySelector("#subscription-name").value;
            const price = form.querySelector("#subscription-price").value;
            const durationValue = form.querySelector("#subscription-duration-value").value;
            const durationUnit = form.querySelector("#subscription-duration-unit").value;
            
            if (name && price && durationValue) {
                modal.style.display = "none";
                showNotification(`Subscription plan "${name}" added successfully!`, "success");
                
                // Add new row to table (demo)
                const subscriptionsTable = document.querySelector(".subscriptions-table tbody");
                if (subscriptionsTable) {
                    const newRow = document.createElement("tr");
                    newRow.className = "subscription-row";
                    newRow.setAttribute("data-id", Date.now().toString());
                    
                    const status = form.querySelector("#subscription-status").value;
                    
                    newRow.innerHTML = `
                        <td>
                            <input type="checkbox" class="subscription-checkbox" value="${Date.now()}">
                        </td>
                        <td class="subscription-name">${name}</td>
                        <td class="subscription-price">$${price}</td>
                        <td class="subscription-duration">${durationValue} ${durationUnit}</td>
                        <td><span class="subscription-status status-${status}">${status.charAt(0).toUpperCase() + status.slice(1)}</span></td>
                        <td>0</td>
                        <td>Just now</td>
                        <td>
                            <div class="actions">
                                <button class="action-btn view-subscription-btn" data-id="${Date.now()}" title="View Plan Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn view-subscribers-btn" data-id="${Date.now()}" title="View Subscribers">
                                    <i class="fas fa-users"></i>
                                </button>
                                <button class="action-btn edit-subscription-btn" data-id="${Date.now()}" title="Edit Plan">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete-subscription-btn" data-id="${Date.now()}" title="Delete Plan">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    
                    subscriptionsTable.insertBefore(newRow, subscriptionsTable.firstChild);
                    
                    // Add event listeners to the new buttons
                    initializeSubscriptionActionButtons();
                    
                    // Update results count
                    const allRows = document.querySelectorAll(".subscription-row");
                    updateSubscriptionResultsCount(allRows.length);
                }
            } else {
                showNotification("Please fill in all required fields", "error");
            }
        });
        
        // Close when clicking outside
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
        
        // Add styles for price input and duration input if not already present
        if (!document.getElementById("subscription-form-styles")) {
            const style = document.createElement("style");
            style.id = "subscription-form-styles";
            style.textContent = `
                .price-input {
                    display: flex;
                    align-items: center;
                }
                
                .price-input .currency {
                    margin-right: 5px;
                    font-weight: 600;
                }
                
                .duration-input {
                    display: flex;
                    align-items: center;
                }
                
                .duration-input input {
                    width: 80px;
                    margin-right: 10px;
                }
                
                .features-list {
                    border: 1px solid #e5e7eb;
                    border-radius: 4px;
                    padding: 10px;
                    max-height: 200px;
                    overflow-y: auto;
                }
                
                .feature-item {
                    display: flex;
                    align-items: center;
                    margin-bottom: 10px;
                }
                
                .feature-item:last-child {
                    margin-bottom: 0;
                }
                
                .feature-item input[type="checkbox"] {
                    margin-right: 10px;
                }
                
                .status-active {
                    color: #10b981;
                    font-weight: 600;
                }
                
                .status-inactive {
                    color: #6b7280;
                    font-weight: 600;
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    // Reset form
    const form = document.getElementById("add-subscription-form");
    if (form) form.reset();
    
    // Set default values
    document.getElementById("subscription-duration-value").value = "1";
    document.getElementById("subscription-duration-unit").value = "months";
    document.getElementById("subscription-status").value = "active";
    
    // Check default features
    document.getElementById("feature-1").checked = true;
    document.getElementById("feature-2").checked = true;
    document.getElementById("feature-3").checked = true;
    document.getElementById("feature-4").checked = true;
    document.getElementById("feature-5").checked = false;
    document.getElementById("feature-6").checked = false;
    
    // Show modal
    modal.style.display = "block";
}

// Function to initialize subscription charts
function initializeSubscriptionCharts() {
    // Check if Chart.js is loaded
    if (typeof Chart === "undefined") {
        // Load Chart.js if not already loaded
        const script = document.createElement("script");
        script.src = "https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js";
        script.onload = function() {
            createSubscriptionCharts();
        };
        document.head.appendChild(script);
    } else {
        createSubscriptionCharts();
    }
}

// Function to create subscription charts
function createSubscriptionCharts() {
    // Revenue chart
    const revenueChartCanvas = document.getElementById("revenue-chart");
    if (revenueChartCanvas) {
        const revenueChart = new Chart(revenueChartCanvas, {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                datasets: [{
                    label: "Revenue",
                    data: [5000, 7500, 10000, 9000, 12000, 15000],
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
    
    // Subscribers chart
    const subscribersChartCanvas = document.getElementById("subscribers-chart");
    if (subscribersChartCanvas) {
        const subscribersChart = new Chart(subscribersChartCanvas, {
            type: "bar",
            data: {
                labels: ["Basic", "Standard", "Premium", "Enterprise"],
                datasets: [{
                    label: "Subscribers",
                    data: [120, 85, 45, 15],
                    backgroundColor: [
                        "rgba(59, 130, 246, 0.7)",
                        "rgba(16, 185, 129, 0.7)",
                        "rgba(245, 158, 11, 0.7)",
                        "rgba(139, 92, 246, 0.7)"
                    ],
                    borderColor: [
                        "rgba(59, 130, 246, 1)",
                        "rgba(16, 185, 129, 1)",
                        "rgba(245, 158, 11, 1)",
                        "rgba(139, 92, 246, 1)"
                    ],
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
    
    // Subscription status chart
    const statusChartCanvas = document.getElementById("status-chart");
    if (statusChartCanvas) {
        const statusChart = new Chart(statusChartCanvas, {
            type: "doughnut",
            data: {
                labels: ["Active", "Expired", "Cancelled"],
                datasets: [{
                    data: [65, 25, 10],
                    backgroundColor: [
                        "rgba(16, 185, 129, 0.7)",
                        "rgba(245, 158, 11, 0.7)",
                        "rgba(239, 68, 68, 0.7)"
                    ],
                    borderColor: [
                        "rgba(16, 185, 129, 1)",
                        "rgba(245, 158, 11, 1)",
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
}
