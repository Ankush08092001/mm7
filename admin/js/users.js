// Users Management page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize user action buttons
    initializeUserActionButtons();
    
    // Initialize add user button
    initializeAddUserButton();
    
    // Initialize search and filter
    initializeUserSearch();
    
    // Initialize pagination
    initializePagination();
    
    // Initialize bulk actions
    initializeBulkActions();
});

// Function to initialize user action buttons
function initializeUserActionButtons() {
    // View user details buttons
    const viewButtons = document.querySelectorAll('.view-user-btn');
    if (viewButtons.length > 0) {
        viewButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const userId = this.getAttribute('data-id');
                const userName = this.closest('tr').querySelector('.user-name').textContent;
                viewUserDetails(userId, userName);
            });
        });
    }
    
    // Edit user buttons
    const editButtons = document.querySelectorAll('.edit-user-btn');
    if (editButtons.length > 0) {
        editButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const userId = this.getAttribute('data-id');
                const userName = this.closest('tr').querySelector('.user-name').textContent;
                editUser(userId, userName);
            });
        });
    }
    
    // Delete user buttons
    const deleteButtons = document.querySelectorAll('.delete-user-btn');
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const userId = this.getAttribute('data-id');
                const userName = this.closest('tr').querySelector('.user-name').textContent;
                deleteUser(userId, userName);
            });
        });
    }
}

// Function to initialize add user button
function initializeAddUserButton() {
    const addUserBtn = document.querySelector('.add-user-btn');
    if (addUserBtn) {
        addUserBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showAddUserModal();
        });
    }
}

// Function to initialize user search and filter
function initializeUserSearch() {
    // Search input
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchUsers(this.value);
            }
        });
    }
    
    // Search button
    const searchBtn = document.querySelector('.search-btn');
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchUsers(searchInput.value);
            }
        });
    }
    
    // Role filter
    const roleFilter = document.querySelector('#role-filter');
    if (roleFilter) {
        roleFilter.addEventListener('change', function() {
            filterUsers();
        });
    }
    
    // Status filter
    const statusFilter = document.querySelector('#status-filter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            filterUsers();
        });
    }
    
    // Apply filters button
    const filterBtn = document.querySelector('.filter-btn');
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            filterUsers();
        });
    }
    
    // Reset filters button
    const resetBtn = document.querySelector('.reset-filter-btn');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            resetFilters();
        });
    }
}

// Function to search users
function searchUsers(query) {
    if (!query) return;
    
    console.log(`Searching users with query: ${query}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we'll simulate filtering the table
    
    const userRows = document.querySelectorAll('.user-row');
    let matchCount = 0;
    
    userRows.forEach(row => {
        const userName = row.querySelector('.user-name').textContent.toLowerCase();
        const userEmail = row.querySelector('.user-email').textContent.toLowerCase();
        
        if (userName.includes(query.toLowerCase()) || userEmail.includes(query.toLowerCase())) {
            row.style.display = '';
            matchCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update results count
    updateResultsCount(matchCount);
    
    // Show notification
    showNotification(`Found ${matchCount} users matching "${query}"`, 'info');
}

// Function to filter users
function filterUsers() {
    const roleFilter = document.querySelector('#role-filter');
    const statusFilter = document.querySelector('#status-filter');
    
    if (!roleFilter || !statusFilter) return;
    
    const role = roleFilter.value;
    const status = statusFilter.value;
    
    console.log(`Filtering users - Role: ${role}, Status: ${status}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we'll simulate filtering the table
    
    const userRows = document.querySelectorAll('.user-row');
    let matchCount = 0;
    
    userRows.forEach(row => {
        const userRole = row.querySelector('.user-role').textContent;
        const userStatus = row.querySelector('.status-badge').textContent;
        
        const roleMatch = role === 'all' || userRole === role;
        const statusMatch = status === 'all' || userStatus.toLowerCase() === status.toLowerCase();
        
        if (roleMatch && statusMatch) {
            row.style.display = '';
            matchCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update results count
    updateResultsCount(matchCount);
    
    // Show notification
    showNotification(`Filters applied. Found ${matchCount} matching users.`, 'info');
}

// Function to reset filters
function resetFilters() {
    const roleFilter = document.querySelector('#role-filter');
    const statusFilter = document.querySelector('#status-filter');
    const searchInput = document.querySelector('.search-input');
    
    if (roleFilter) roleFilter.value = 'all';
    if (statusFilter) statusFilter.value = 'all';
    if (searchInput) searchInput.value = '';
    
    // Show all rows
    const userRows = document.querySelectorAll('.user-row');
    userRows.forEach(row => {
        row.style.display = '';
    });
    
    // Update results count
    updateResultsCount(userRows.length);
    
    // Show notification
    showNotification('Filters reset', 'info');
}

// Function to update results count
function updateResultsCount(count) {
    const resultsCount = document.querySelector('.results-count');
    if (resultsCount) {
        resultsCount.textContent = `Showing ${count} users`;
    }
}

// Function to initialize pagination
function initializePagination() {
    const paginationBtns = document.querySelectorAll('.pagination-btn');
    if (paginationBtns.length > 0) {
        paginationBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                paginationBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                // In a real implementation, this would load the corresponding page
                // For this demo, we'll just show a notification
                showNotification(`Navigating to page ${this.textContent}`, 'info');
            });
        });
    }
    
    // Previous page button
    const prevBtn = document.querySelector('.prev-page-btn');
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            const activePage = document.querySelector('.pagination-btn.active');
            if (activePage && activePage.previousElementSibling && activePage.previousElementSibling.classList.contains('pagination-btn')) {
                activePage.previousElementSibling.click();
            }
        });
    }
    
    // Next page button
    const nextBtn = document.querySelector('.next-page-btn');
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            const activePage = document.querySelector('.pagination-btn.active');
            if (activePage && activePage.nextElementSibling && activePage.nextElementSibling.classList.contains('pagination-btn')) {
                activePage.nextElementSibling.click();
            }
        });
    }
}

// Function to initialize bulk actions
function initializeBulkActions() {
    // Select all checkbox
    const selectAllCheckbox = document.querySelector('#select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            
            // Update bulk actions state
            updateBulkActionsState();
        });
    }
    
    // Individual checkboxes
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    if (userCheckboxes.length > 0) {
        userCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Update select all checkbox
                const selectAllCheckbox = document.querySelector('#select-all');
                const allCheckboxes = document.querySelectorAll('.user-checkbox');
                const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                }
                
                // Update bulk actions state
                updateBulkActionsState();
            });
        });
    }
    
    // Bulk action button
    const bulkActionBtn = document.querySelector('.bulk-action-btn');
    if (bulkActionBtn) {
        bulkActionBtn.addEventListener('click', function() {
            const bulkActionSelect = document.querySelector('#bulk-action');
            if (bulkActionSelect) {
                const action = bulkActionSelect.value;
                executeBulkAction(action);
            }
        });
    }
}

// Function to update bulk actions state
function updateBulkActionsState() {
    const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
    const bulkActionBtn = document.querySelector('.bulk-action-btn');
    const bulkActionSelect = document.querySelector('#bulk-action');
    
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

// Function to execute bulk action
function executeBulkAction(action) {
    const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
    if (checkedCheckboxes.length === 0) return;
    
    const userIds = Array.from(checkedCheckboxes).map(cb => cb.value);
    console.log(`Executing bulk action: ${action} on users: ${userIds.join(', ')}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we'll just show a notification
    
    let message = '';
    
    switch (action) {
        case 'activate':
            message = `Activated ${checkedCheckboxes.length} users`;
            break;
        case 'deactivate':
            message = `Deactivated ${checkedCheckboxes.length} users`;
            break;
        case 'delete':
            message = `Deleted ${checkedCheckboxes.length} users`;
            // Remove rows from table
            checkedCheckboxes.forEach(cb => {
                const row = cb.closest('tr');
                if (row) row.remove();
            });
            break;
        default:
            message = `Executed ${action} on ${checkedCheckboxes.length} users`;
    }
    
    // Update results count
    const remainingRows = document.querySelectorAll('.user-row');
    updateResultsCount(remainingRows.length);
    
    // Reset checkboxes
    const selectAllCheckbox = document.querySelector('#select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
    }
    
    // Update bulk actions state
    updateBulkActionsState();
    
    // Show notification
    showNotification(message, 'success');
}

// Function to view user details
function viewUserDetails(userId, userName) {
    console.log(`Viewing details for user: ${userName} (ID: ${userId})`);
    
    // Create modal if it doesn't exist
    let modal = document.getElementById('user-details-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'user-details-modal';
        modal.className = 'modal';
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>User Details</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="user-profile">
                        <div class="user-avatar">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=0D8ABC&color=fff&size=100" alt="${userName}">
                        </div>
                        <div class="user-info">
                            <h3 id="detail-user-name">${userName}</h3>
                            <p id="detail-user-role">Student</p>
                        </div>
                    </div>
                    <div class="user-details">
                        <div class="detail-group">
                            <div class="detail-label">Email:</div>
                            <div class="detail-value" id="detail-user-email">user@example.com</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Phone:</div>
                            <div class="detail-value" id="detail-user-phone">+91 98765 43210</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Joined:</div>
                            <div class="detail-value" id="detail-user-joined">Jan 15, 2025</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Status:</div>
                            <div class="detail-value" id="detail-user-status"><span class="status-badge active">Active</span></div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Subscription:</div>
                            <div class="detail-value" id="detail-user-subscription">Premium Plan (Valid till Dec 31, 2025)</div>
                        </div>
                    </div>
                    
                    <div class="user-activity">
                        <h3>Recent Activity</h3>
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-date">Jun 1, 2025</div>
                                <div class="activity-content">Completed Mock Test: Function 1</div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-date">May 28, 2025</div>
                                <div class="activity-content">Watched Video: Marine Engineering Basics</div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-date">May 25, 2025</div>
                                <div class="activity-content">Downloaded Study Material: MARPOL Annex VI</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline close-btn">Close</button>
                    <button type="button" class="btn btn-primary edit-btn">Edit User</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Add event listeners
        modal.querySelector('.close').addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        modal.querySelector('.close-btn').addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        modal.querySelector('.edit-btn').addEventListener('click', function() {
            modal.style.display = 'none';
            editUser(userId, userName);
        });
        
        // Close when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
        
        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .user-profile {
                display: flex;
                align-items: center;
                margin-bottom: 20px;
            }
            
            .user-profile .user-avatar {
                margin-right: 20px;
            }
            
            .user-profile .user-avatar img {
                width: 100px;
                height: 100px;
                border-radius: 50%;
            }
            
            .user-profile .user-info h3 {
                margin: 0 0 5px 0;
            }
            
            .user-profile .user-info p {
                margin: 0;
                color: #6b7280;
            }
            
            .user-details {
                margin-bottom: 30px;
            }
            
            .detail-group {
                display: flex;
                margin-bottom: 15px;
            }
            
            .detail-label {
                font-weight: 600;
                width: 120px;
            }
            
            .user-activity h3 {
                margin-top: 0;
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .activity-item {
                display: flex;
                margin-bottom: 10px;
                padding-bottom: 10px;
                border-bottom: 1px solid #f3f4f6;
            }
            
            .activity-date {
                width: 100px;
                color: #6b7280;
                font-size: 14px;
            }
        `;
        document.head.appendChild(style);
    } else {
        // Update modal content with user data
        modal.querySelector('#detail-user-name').textContent = userName;
        
        // In a real implementation, we would fetch user data from API
        // For this demo, we'll use mock data
        const mockUserData = {
            email: `${userName.toLowerCase().replace(/\s+/g, '.')}@example.com`,
            phone: '+91 98765 43210',
            joined: 'Jan 15, 2025',
            status: 'Active',
            subscription: 'Premium Plan (Valid till Dec 31, 2025)',
            role: 'Student'
        };
        
        modal.querySelector('#detail-user-email').textContent = mockUserData.email;
        modal.querySelector('#detail-user-phone').textContent = mockUserData.phone;
        modal.querySelector('#detail-user-joined').textContent = mockUserData.joined;
        modal.querySelector('#detail-user-status').innerHTML = `<span class="status-badge ${mockUserData.status.toLowerCase()}">${mockUserData.status}</span>`;
        modal.querySelector('#detail-user-subscription').textContent = mockUserData.subscription;
        modal.querySelector('#detail-user-role').textContent = mockUserData.role;
        
        // Update avatar
        const avatarImg = modal.querySelector('.user-avatar img');
        avatarImg.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=0D8ABC&color=fff&size=100`;
        avatarImg.alt = userName;
    }
    
    // Show modal
    modal.style.display = 'block';
}

// Function to edit user
function editUser(userId, userName) {
    console.log(`Editing user: ${userName} (ID: ${userId})`);
    
    // Create modal if it doesn't exist
    let modal = document.getElementById('edit-user-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'edit-user-modal';
        modal.className = 'modal';
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Edit User</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="edit-user-form">
                        <div class="form-group">
                            <label for="edit-user-name">Full Name</label>
                            <input type="text" id="edit-user-name" name="edit-user-name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-user-email">Email</label>
                            <input type="email" id="edit-user-email" name="edit-user-email" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-user-phone">Phone</label>
                            <input type="tel" id="edit-user-phone" name="edit-user-phone">
                        </div>
                        <div class="form-group">
                            <label for="edit-user-role">Role</label>
                            <select id="edit-user-role" name="edit-user-role">
                                <option value="student">Student</option>
                                <option value="instructor">Instructor</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-user-status">Status</label>
                            <select id="edit-user-status" name="edit-user-status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-user-subscription">Subscription</label>
                            <select id="edit-user-subscription" name="edit-user-subscription">
                                <option value="none">None</option>
                                <option value="basic">Basic Plan</option>
                                <option value="premium">Premium Plan</option>
                                <option value="annual">Annual Plan</option>
                            </select>
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
        modal.querySelector('.close').addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        modal.querySelector('.cancel-btn').addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        modal.querySelector('.save-btn').addEventListener('click', function() {
            const form = document.getElementById('edit-user-form');
            const name = form.querySelector('#edit-user-name').value;
            const email = form.querySelector('#edit-user-email').value;
            
            if (name && email) {
                modal.style.display = 'none';
                showNotification(`User ${name} updated successfully!`, 'success');
                
                // Update user row in table if it exists
                const userRow = document.querySelector(`.user-row[data-id="${userId}"]`);
                if (userRow) {
                    userRow.querySelector('.user-name').textContent = name;
                    userRow.querySelector('.user-email').textContent = email;
                    userRow.querySelector('.user-role').textContent = form.querySelector('#edit-user-role').value;
                    
                    const statusBadge = userRow.querySelector('.status-badge');
                    const newStatus = form.querySelector('#edit-user-status').value;
                    statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                    statusBadge.className = `status-badge ${newStatus}`;
                }
            } else {
                showNotification('Please fill in all required fields', 'error');
            }
        });
        
        // Close when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
    
    // In a real implementation, we would fetch user data from API
    // For this demo, we'll use mock data
    const mockUserData = {
        name: userName,
        email: `${userName.toLowerCase().replace(/\s+/g, '.')}@example.com`,
        phone: '+91 98765 43210',
        role: 'student',
        status: 'active',
        subscription: 'premium'
    };
    
    // Update form fields with user data
    document.getElementById('edit-user-name').value = mockUserData.name;
    document.getElementById('edit-user-email').value = mockUserData.email;
    document.getElementById('edit-user-phone').value = mockUserData.phone;
    document.getElementById('edit-user-role').value = mockUserData.role;
    document.getElementById('edit-user-status').value = mockUserData.status;
    document.getElementById('edit-user-subscription').value = mockUserData.subscription;
    
    // Show modal
    modal.style.display = 'block';
}

// Function to delete user
function deleteUser(userId, userName) {
    console.log(`Deleting user: ${userName} (ID: ${userId})`);
    
    // Show confirmation modal
    showConfirmationModal(
        `Are you sure you want to delete user "${userName}"?`,
        function() {
            // In a real implementation, this would send an API request
            // For this demo, we'll just remove the row from the table
            
            const userRow = document.querySelector(`.user-row[data-id="${userId}"]`);
            if (userRow) {
                userRow.remove();
                
                // Update results count
                const remainingRows = document.querySelectorAll('.user-row');
                updateResultsCount(remainingRows.length);
                
                // Show notification
                showNotification(`User ${userName} deleted successfully!`, 'success');
            }
        },
        'Delete User',
        'Delete',
        'btn-danger'
    );
}

// Function to show add user modal
function showAddUserModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById('add-user-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'add-user-modal';
        modal.className = 'modal';
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New User</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="add-user-form">
                        <div class="form-group">
                            <label for="user-name">Full Name</label>
                            <input type="text" id="user-name" name="user-name" required>
                        </div>
                        <div class="form-group">
                            <label for="user-email">Email</label>
                            <input type="email" id="user-email" name="user-email" required>
                        </div>
                        <div class="form-group">
                            <label for="user-phone">Phone</label>
                            <input type="tel" id="user-phone" name="user-phone">
                        </div>
                        <div class="form-group">
                            <label for="user-role">Role</label>
                            <select id="user-role" name="user-role">
                                <option value="student">Student</option>
                                <option value="instructor">Instructor</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user-status">Status</label>
                            <select id="user-status" name="user-status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user-password">Password</label>
                            <input type="password" id="user-password" name="user-password" required>
                        </div>
                        <div class="form-group">
                            <label for="user-confirm-password">Confirm Password</label>
                            <input type="password" id="user-confirm-password" name="user-confirm-password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                    <button type="button" class="btn btn-primary save-btn">Add User</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Add event listeners
        modal.querySelector('.close').addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        modal.querySelector('.cancel-btn').addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        modal.querySelector('.save-btn').addEventListener('click', function() {
            const form = document.getElementById('add-user-form');
            const name = form.querySelector('#user-name').value;
            const email = form.querySelector('#user-email').value;
            const password = form.querySelector('#user-password').value;
            const confirmPassword = form.querySelector('#user-confirm-password').value;
            
            if (name && email && password) {
                if (password !== confirmPassword) {
                    showNotification('Passwords do not match', 'error');
                    return;
                }
                
                modal.style.display = 'none';
                
                // In a real implementation, this would send an API request
                // For this demo, we'll just add a new row to the table
                
                const usersTable = document.querySelector('.users-table tbody');
                if (usersTable) {
                    const newRow = document.createElement('tr');
                    newRow.className = 'user-row';
                    newRow.setAttribute('data-id', Date.now().toString());
                    
                    const role = form.querySelector('#user-role').value;
                    const status = form.querySelector('#user-status').value;
                    
                    newRow.innerHTML = `
                        <td>
                            <input type="checkbox" class="user-checkbox" value="${Date.now()}">
                        </td>
                        <td>
                            <div class="user-info">
                                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=0D8ABC&color=fff" alt="${name}" class="user-avatar">
                                <div>
                                    <div class="user-name">${name}</div>
                                    <div class="user-email">${email}</div>
                                </div>
                            </div>
                        </td>
                        <td class="user-role">${role.charAt(0).toUpperCase() + role.slice(1)}</td>
                        <td>
                            <span class="status-badge ${status}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>
                        </td>
                        <td>Just now</td>
                        <td>
                            <div class="actions">
                                <button class="action-btn view-user-btn" data-id="${Date.now()}" title="View User">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit-user-btn" data-id="${Date.now()}" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete-user-btn" data-id="${Date.now()}" title="Delete User">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    
                    usersTable.insertBefore(newRow, usersTable.firstChild);
                    
                    // Add event listeners to the new buttons
                    initializeUserActionButtons();
                    
                    // Update results count
                    const allRows = document.querySelectorAll('.user-row');
                    updateResultsCount(allRows.length);
                }
                
                showNotification(`User ${name} added successfully!`, 'success');
            } else {
                showNotification('Please fill in all required fields', 'error');
            }
        });
        
        // Close when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
    
    // Reset form
    const form = document.getElementById('add-user-form');
    if (form) form.reset();
    
    // Show modal
    modal.style.display = 'block';
}
