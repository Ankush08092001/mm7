// Dashboard page specific functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeUserGrowthChart();
    initializeSubscriptionChart();
    
    // Initialize quick action buttons
    initializeQuickActionButtons();
    
    // Initialize time period filters
    initializeTimePeriodFilters();
    
    // Initialize activity feed items
    initializeActivityFeedItems();
});

// Function to initialize user growth chart
function initializeUserGrowthChart() {
    const chartContainer = document.getElementById('user-growth-chart');
    if (!chartContainer) return;
    
    // Clear placeholder text
    chartContainer.innerHTML = '';
    
    // Create canvas for chart
    const canvas = document.createElement('canvas');
    canvas.id = 'user-growth-canvas';
    canvas.width = chartContainer.clientWidth;
    canvas.height = chartContainer.clientHeight || 300;
    chartContainer.appendChild(canvas);
    
    const ctx = canvas.getContext('2d');
    
    // Demo data for user growth
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const currentMonth = new Date().getMonth();
    const labels = [];
    
    // Get last 12 months
    for (let i = 11; i >= 0; i--) {
        const monthIndex = (currentMonth - i + 12) % 12;
        labels.push(months[monthIndex]);
    }
    
    // Generate random growth data
    const baseValue = 800;
    const data = [];
    for (let i = 0; i < 12; i++) {
        const growth = Math.floor(Math.random() * 100) + 20;
        data.push(baseValue + (i * growth));
    }
    
    // Draw chart
    drawLineChart(ctx, labels, data, 'User Growth');
}

// Function to initialize subscription chart
function initializeSubscriptionChart() {
    const chartContainer = document.getElementById('subscription-chart');
    if (!chartContainer) return;
    
    // Clear placeholder text
    chartContainer.innerHTML = '';
    
    // Create canvas for chart
    const canvas = document.createElement('canvas');
    canvas.id = 'subscription-canvas';
    canvas.width = chartContainer.clientWidth;
    canvas.height = chartContainer.clientHeight || 300;
    chartContainer.appendChild(canvas);
    
    const ctx = canvas.getContext('2d');
    
    // Demo data for subscription distribution
    const data = [55, 30, 15]; // Basic, Premium, Annual
    const labels = ['Basic Plan', 'Premium Plan', 'Annual Plan'];
    const colors = ['#3b82f6', '#10b981', '#f59e0b'];
    
    // Draw chart
    drawPieChart(ctx, labels, data, colors);
}

// Function to draw a line chart
function drawLineChart(ctx, labels, data, title) {
    // Set up chart dimensions
    const width = ctx.canvas.width;
    const height = ctx.canvas.height;
    const padding = 40;
    
    // Clear canvas
    ctx.clearRect(0, 0, width, height);
    
    // Draw title
    ctx.font = '16px Arial';
    ctx.fillStyle = '#333';
    ctx.textAlign = 'center';
    ctx.fillText(title, width / 2, padding / 2);
    
    // Draw axes
    ctx.beginPath();
    ctx.moveTo(padding, padding);
    ctx.lineTo(padding, height - padding);
    ctx.lineTo(width - padding, height - padding);
    ctx.strokeStyle = '#ccc';
    ctx.stroke();
    
    // Find max value for scaling
    const maxValue = Math.max(...data) * 1.1; // Add 10% margin
    
    // Scale data points to fit canvas
    const xScale = (width - 2 * padding) / (data.length - 1);
    const yScale = (height - 2 * padding) / maxValue;
    
    // Draw line
    ctx.beginPath();
    ctx.moveTo(
        padding,
        height - padding - data[0] * yScale
    );
    
    for (let i = 1; i < data.length; i++) {
        ctx.lineTo(
            padding + i * xScale,
            height - padding - data[i] * yScale
        );
    }
    
    ctx.strokeStyle = '#0891b2';
    ctx.lineWidth = 2;
    ctx.stroke();
    
    // Draw area under the line
    ctx.lineTo(padding + (data.length - 1) * xScale, height - padding);
    ctx.lineTo(padding, height - padding);
    ctx.closePath();
    ctx.fillStyle = 'rgba(8, 145, 178, 0.1)';
    ctx.fill();
    
    // Draw points
    for (let i = 0; i < data.length; i++) {
        ctx.beginPath();
        ctx.arc(
            padding + i * xScale,
            height - padding - data[i] * yScale,
            4,
            0,
            2 * Math.PI
        );
        ctx.fillStyle = '#0891b2';
        ctx.fill();
    }
    
    // Draw labels
    ctx.font = '12px Arial';
    ctx.fillStyle = '#666';
    ctx.textAlign = 'center';
    
    for (let i = 0; i < labels.length; i += Math.ceil(labels.length / 6)) { // Show fewer labels if many
        ctx.fillText(
            labels[i],
            padding + i * xScale,
            height - padding + 20
        );
    }
}

// Function to draw a pie chart
function drawPieChart(ctx, labels, data, colors) {
    // Set up chart dimensions
    const width = ctx.canvas.width;
    const height = ctx.canvas.height;
    const centerX = width / 2;
    const centerY = height / 2;
    const radius = Math.min(width, height) / 2 - 40;
    
    // Clear canvas
    ctx.clearRect(0, 0, width, height);
    
    // Calculate total
    const total = data.reduce((sum, value) => sum + value, 0);
    
    // Draw pie slices
    let startAngle = 0;
    
    for (let i = 0; i < data.length; i++) {
        const sliceAngle = (2 * Math.PI * data[i]) / total;
        
        ctx.beginPath();
        ctx.moveTo(centerX, centerY);
        ctx.arc(centerX, centerY, radius, startAngle, startAngle + sliceAngle);
        ctx.closePath();
        
        ctx.fillStyle = colors[i];
        ctx.fill();
        
        // Draw labels
        const labelAngle = startAngle + sliceAngle / 2;
        const labelX = centerX + (radius * 0.7) * Math.cos(labelAngle);
        const labelY = centerY + (radius * 0.7) * Math.sin(labelAngle);
        
        ctx.font = '12px Arial';
        ctx.fillStyle = '#fff';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(`${data[i]}%`, labelX, labelY);
        
        startAngle += sliceAngle;
    }
    
    // Draw legend
    const legendY = height - 30;
    const legendSpacing = width / (data.length + 1);
    
    for (let i = 0; i < data.length; i++) {
        const legendX = (i + 1) * legendSpacing;
        
        // Draw color box
        ctx.fillStyle = colors[i];
        ctx.fillRect(legendX - 30, legendY, 10, 10);
        
        // Draw label
        ctx.font = '12px Arial';
        ctx.fillStyle = '#333';
        ctx.textAlign = 'left';
        ctx.textBaseline = 'middle';
        ctx.fillText(labels[i], legendX - 15, legendY + 5);
    }
}

// Function to initialize quick action buttons
function initializeQuickActionButtons() {
    // Add User button
    const addUserBtn = document.querySelector('.quick-action-btn[data-action="add-user"]');
    if (addUserBtn) {
        addUserBtn.addEventListener('click', function() {
            showAddUserModal();
        });
    }
    
    // Upload Material button
    const uploadMaterialBtn = document.querySelector('.quick-action-btn[data-action="upload-material"]');
    if (uploadMaterialBtn) {
        uploadMaterialBtn.addEventListener('click', function() {
            showUploadMaterialModal();
        });
    }
    
    // Create Test button
    const createTestBtn = document.querySelector('.quick-action-btn[data-action="create-test"]');
    if (createTestBtn) {
        createTestBtn.addEventListener('click', function() {
            showCreateTestModal();
        });
    }
    
    // Add Video button
    const addVideoBtn = document.querySelector('.quick-action-btn[data-action="add-video"]');
    if (addVideoBtn) {
        addVideoBtn.addEventListener('click', function() {
            showAddVideoModal();
        });
    }
    
    // Add Paper button
    const addPaperBtn = document.querySelector('.quick-action-btn[data-action="add-paper"]');
    if (addPaperBtn) {
        addPaperBtn.addEventListener('click', function() {
            showAddPaperModal();
        });
    }
}

// Function to initialize time period filters
function initializeTimePeriodFilters() {
    const timePeriodBtns = document.querySelectorAll('.time-period-btn');
    if (timePeriodBtns.length > 0) {
        timePeriodBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                timePeriodBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                // Update dashboard data based on selected time period
                updateDashboardData(this.getAttribute('data-period'));
            });
        });
    }
}

// Function to update dashboard data based on time period
function updateDashboardData(period) {
    console.log(`Updating dashboard data for period: ${period}`);
    
    // Demo data for different time periods
    const periodData = {
        'day': {
            totalUsers: 1245,
            newUsers: 15,
            testAttempts: 320,
            revenue: '₹20K',
            engagement: '38 min'
        },
        'week': {
            totalUsers: 1245,
            newUsers: 85,
            testAttempts: 1850,
            revenue: '₹150K',
            engagement: '42 min'
        },
        'month': {
            totalUsers: 1245,
            newUsers: 320,
            testAttempts: 3872,
            revenue: '₹2.4L',
            engagement: '42 min'
        },
        'year': {
            totalUsers: 1245,
            newUsers: 980,
            testAttempts: 24500,
            revenue: '₹18.6L',
            engagement: '45 min'
        }
    };
    
    const data = periodData[period] || periodData['month'];
    
    // Update stats
    updateStatValue('total-users', data.totalUsers);
    updateStatTrend('total-users-trend', period === 'day' ? 5 : period === 'week' ? 8 : 12);
    
    updateStatValue('test-attempts', data.testAttempts);
    updateStatTrend('test-attempts-trend', period === 'day' ? 3 : period === 'week' ? 5 : 8);
    
    updateStatValue('monthly-revenue', data.revenue);
    updateStatTrend('monthly-revenue-trend', period === 'day' ? 10 : period === 'week' ? 12 : 15);
    
    updateStatValue('avg-engagement', data.engagement);
    updateStatTrend('avg-engagement-trend', period === 'day' ? -5 : period === 'week' ? -4 : -3);
    
    // Show notification
    showNotification(`Dashboard updated to show ${period} statistics`, 'info');
}

// Function to update stat value
function updateStatValue(id, value) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = value;
    }
}

// Function to update stat trend
function updateStatTrend(id, percentage) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = `${percentage > 0 ? '+' : ''}${percentage}% from last period`;
        element.className = percentage >= 0 ? 'stat-trend positive' : 'stat-trend negative';
    }
}

// Function to initialize activity feed items
function initializeActivityFeedItems() {
    const activityItems = document.querySelectorAll('.activity-item');
    if (activityItems.length > 0) {
        activityItems.forEach(item => {
            item.addEventListener('click', function() {
                const activityType = this.getAttribute('data-type');
                const activityUser = this.getAttribute('data-user');
                const activityContent = this.getAttribute('data-content');
                
                showActivityDetailsModal(activityType, activityUser, activityContent);
            });
        });
    }
}

// Function to show activity details modal
function showActivityDetailsModal(type, user, content) {
    // Create modal if it doesn't exist
    let modal = document.getElementById('activity-details-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'activity-details-modal';
        modal.className = 'modal';
        
        modal.innerHTML = `
            <div class="modal-content small">
                <div class="modal-header">
                    <h2>Activity Details</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="activity-details">
                        <div class="detail-group">
                            <div class="detail-label">User:</div>
                            <div class="detail-value" id="activity-user"></div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Type:</div>
                            <div class="detail-value" id="activity-type"></div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Content:</div>
                            <div class="detail-value" id="activity-content"></div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Date & Time:</div>
                            <div class="detail-value" id="activity-datetime"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline close-btn">Close</button>
                    <button type="button" class="btn btn-primary view-btn">View Details</button>
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
        
        modal.querySelector('.view-btn').addEventListener('click', function() {
            modal.style.display = 'none';
            showNotification(`Viewing details for ${content}`, 'info');
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
            .activity-details {
                padding: 10px;
            }
            
            .detail-group {
                display: flex;
                margin-bottom: 15px;
            }
            
            .detail-label {
                font-weight: 600;
                width: 100px;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Update modal content
    document.getElementById('activity-user').textContent = user;
    document.getElementById('activity-type').textContent = type;
    document.getElementById('activity-content').textContent = content;
    document.getElementById('activity-datetime').textContent = new Date().toLocaleString();
    
    // Show modal
    modal.style.display = 'block';
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
            
            if (name && email) {
                modal.style.display = 'none';
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
    
    // Show modal
    modal.style.display = 'block';
}

// Function to show upload material modal
function showUploadMaterialModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById('upload-material-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'upload-material-modal';
        modal.className = 'modal';
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Upload Study Material</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="upload-material-form">
                        <div class="form-group">
                            <label for="material-title">Title</label>
                            <input type="text" id="material-title" name="material-title" required>
                        </div>
                        <div class="form-group">
                            <label for="material-category">Category</label>
                            <select id="material-category" name="material-category">
                                <option value="function-1">Function 1</option>
                                <option value="function-2">Function 2</option>
                                <option value="function-3">Function 3</option>
                                <option value="function-4">Function 4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="material-description">Description</label>
                            <textarea id="material-description" name="material-description" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="material-file">File</label>
                            <div class="file-upload">
                                <input type="file" id="material-file" name="material-file" required>
                                <label for="material-file" class="file-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Choose File</span>
                                </label>
                                <div class="file-name">No file chosen</div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                    <button type="button" class="btn btn-primary save-btn">Upload</button>
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
            const form = document.getElementById('upload-material-form');
            const title = form.querySelector('#material-title').value;
            const file = form.querySelector('#material-file').value;
            
            if (title && file) {
                modal.style.display = 'none';
                showNotification(`Study material "${title}" uploaded successfully!`, 'success');
            } else {
                showNotification('Please fill in all required fields', 'error');
            }
        });
        
        // File input change event
        const fileInput = modal.querySelector('#material-file');
        const fileName = modal.querySelector('.file-name');
        
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
            } else {
                fileName.textContent = 'No file chosen';
            }
        });
        
        // Close when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
        
        // Add styles for file upload
        const style = document.createElement('style');
        style.textContent = `
            .file-upload {
                position: relative;
                display: flex;
                flex-direction: column;
            }
            
            .file-upload input[type="file"] {
                position: absolute;
                left: 0;
                top: 0;
                opacity: 0;
                width: 100%;
                height: 100%;
                cursor: pointer;
            }
            
            .file-label {
                display: flex;
                align-items: center;
                padding: 10px 15px;
                background-color: #f3f4f6;
                border: 1px solid #d1d5db;
                border-radius: 4px;
                cursor: pointer;
            }
            
            .file-label i {
                margin-right: 10px;
            }
            
            .file-name {
                margin-top: 5px;
                font-size: 14px;
                color: #6b7280;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Show modal
    modal.style.display = 'block';
}

// Function to show create test modal
function showCreateTestModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById('create-test-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'create-test-modal';
        modal.className = 'modal';
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Create New Test</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="create-test-form">
                        <div class="form-group">
                            <label for="test-title">Test Title</label>
                            <input type="text" id="test-title" name="test-title" required>
                        </div>
                        <div class="form-group">
                            <label for="test-category">Category</label>
                            <select id="test-category" name="test-category">
                                <option value="function-1">Function 1</option>
                                <option value="function-2">Function 2</option>
                                <option value="function-3">Function 3</option>
                                <option value="function-4">Function 4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="test-duration">Duration (minutes)</label>
                            <input type="number" id="test-duration" name="test-duration" min="1" value="60" required>
                        </div>
                        <div class="form-group">
                            <label for="test-passing-score">Passing Score (%)</label>
                            <input type="number" id="test-passing-score" name="test-passing-score" min="1" max="100" value="70" required>
                        </div>
                        <div class="form-group">
                            <label for="test-description">Description</label>
                            <textarea id="test-description" name="test-description" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Questions</label>
                            <div class="questions-info">
                                <p>You can add questions after creating the test.</p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                    <button type="button" class="btn btn-primary save-btn">Create Test</button>
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
            const form = document.getElementById('create-test-form');
            const title = form.querySelector('#test-title').value;
            
            if (title) {
                modal.style.display = 'none';
                showNotification(`Test "${title}" created successfully!`, 'success');
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
    
    // Show modal
    modal.style.display = 'block';
}

// Function to show add video modal
function showAddVideoModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById('add-video-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'add-video-modal';
        modal.className = 'modal';
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New Video</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="add-video-form">
                        <div class="form-group">
                            <label for="video-title">Title</label>
                            <input type="text" id="video-title" name="video-title" required>
                        </div>
                        <div class="form-group">
                            <label for="video-category">Category</label>
                            <select id="video-category" name="video-category">
                                <option value="function-1">Function 1</option>
                                <option value="function-2">Function 2</option>
                                <option value="function-3">Function 3</option>
                                <option value="function-4">Function 4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="video-description">Description</label>
                            <textarea id="video-description" name="video-description" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="video-type">Video Type</label>
                            <select id="video-type" name="video-type">
                                <option value="upload">Upload Video</option>
                                <option value="youtube">YouTube URL</option>
                                <option value="vimeo">Vimeo URL</option>
                            </select>
                        </div>
                        <div class="form-group video-upload-group">
                            <label for="video-file">Video File</label>
                            <div class="file-upload">
                                <input type="file" id="video-file" name="video-file" accept="video/*">
                                <label for="video-file" class="file-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Choose Video</span>
                                </label>
                                <div class="file-name">No file chosen</div>
                            </div>
                        </div>
                        <div class="form-group video-url-group" style="display: none;">
                            <label for="video-url">Video URL</label>
                            <input type="url" id="video-url" name="video-url" placeholder="e.g., https://www.youtube.com/watch?v=...">
                        </div>
                        <div class="form-group">
                            <label for="video-thumbnail">Thumbnail</label>
                            <div class="file-upload">
                                <input type="file" id="video-thumbnail" name="video-thumbnail" accept="image/*">
                                <label for="video-thumbnail" class="file-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Choose Thumbnail</span>
                                </label>
                                <div class="thumbnail-name">No file chosen</div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                    <button type="button" class="btn btn-primary save-btn">Add Video</button>
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
            const form = document.getElementById('add-video-form');
            const title = form.querySelector('#video-title').value;
            const videoType = form.querySelector('#video-type').value;
            const videoFile = form.querySelector('#video-file').value;
            const videoUrl = form.querySelector('#video-url').value;
            
            if (title && ((videoType === 'upload' && videoFile) || (videoType !== 'upload' && videoUrl))) {
                modal.style.display = 'none';
                showNotification(`Video "${title}" added successfully!`, 'success');
            } else {
                showNotification('Please fill in all required fields', 'error');
            }
        });
        
        // Video type change event
        const videoTypeSelect = modal.querySelector('#video-type');
        const videoUploadGroup = modal.querySelector('.video-upload-group');
        const videoUrlGroup = modal.querySelector('.video-url-group');
        
        videoTypeSelect.addEventListener('change', function() {
            if (this.value === 'upload') {
                videoUploadGroup.style.display = '';
                videoUrlGroup.style.display = 'none';
            } else {
                videoUploadGroup.style.display = 'none';
                videoUrlGroup.style.display = '';
            }
        });
        
        // File input change events
        const videoFileInput = modal.querySelector('#video-file');
        const videoFileName = modal.querySelector('.file-name');
        
        videoFileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                videoFileName.textContent = this.files[0].name;
            } else {
                videoFileName.textContent = 'No file chosen';
            }
        });
        
        const thumbnailInput = modal.querySelector('#video-thumbnail');
        const thumbnailName = modal.querySelector('.thumbnail-name');
        
        thumbnailInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                thumbnailName.textContent = this.files[0].name;
            } else {
                thumbnailName.textContent = 'No file chosen';
            }
        });
        
        // Close when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
    
    // Show modal
    modal.style.display = 'block';
}

// Function to show add paper modal
function showAddPaperModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById('add-paper-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'add-paper-modal';
        modal.className = 'modal';
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New Paper</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="add-paper-form">
                        <div class="form-group">
                            <label for="paper-title">Title</label>
                            <input type="text" id="paper-title" name="paper-title" required>
                        </div>
                        <div class="form-group">
                            <label for="paper-category">Category</label>
                            <select id="paper-category" name="paper-category">
                                <option value="function-1">Function 1</option>
                                <option value="function-2">Function 2</option>
                                <option value="function-3">Function 3</option>
                                <option value="function-4">Function 4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="paper-year">Year</label>
                            <select id="paper-year" name="paper-year">
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                                <option value="2021">2021</option>
                                <option value="2020">2020</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="paper-description">Description</label>
                            <textarea id="paper-description" name="paper-description" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="paper-file">Paper File (PDF)</label>
                            <div class="file-upload">
                                <input type="file" id="paper-file" name="paper-file" accept=".pdf" required>
                                <label for="paper-file" class="file-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Choose File</span>
                                </label>
                                <div class="file-name">No file chosen</div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                    <button type="button" class="btn btn-primary save-btn">Add Paper</button>
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
            const form = document.getElementById('add-paper-form');
            const title = form.querySelector('#paper-title').value;
            const file = form.querySelector('#paper-file').value;
            
            if (title && file) {
                modal.style.display = 'none';
                showNotification(`Paper "${title}" added successfully!`, 'success');
            } else {
                showNotification('Please fill in all required fields', 'error');
            }
        });
        
        // File input change event
        const fileInput = modal.querySelector('#paper-file');
        const fileName = modal.querySelector('.file-name');
        
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
            } else {
                fileName.textContent = 'No file chosen';
            }
        });
        
        // Close when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
    
    // Show modal
    modal.style.display = 'block';
}
