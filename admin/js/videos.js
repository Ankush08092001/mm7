// Videos page functionality
document.addEventListener("DOMContentLoaded", function() {
    // Initialize action buttons
    initializeVideoActionButtons();
    
    // Initialize add video button
    initializeAddVideoButton();
    
    // Initialize search and filter
    initializeVideoSearch();
    
    // Initialize pagination
    initializeVideoPagination();
    
    // Initialize bulk actions
    initializeVideoBulkActions();
});

// Function to initialize video action buttons
function initializeVideoActionButtons() {
    // View video details buttons
    const viewButtons = document.querySelectorAll(".view-video-btn");
    if (viewButtons.length > 0) {
        viewButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const videoId = this.getAttribute("data-id");
                const videoTitle = this.closest("tr").querySelector(".video-title").textContent;
                viewVideoDetails(videoId, videoTitle);
            });
        });
    }
    
    // Edit video buttons
    const editButtons = document.querySelectorAll(".edit-video-btn");
    if (editButtons.length > 0) {
        editButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const videoId = this.getAttribute("data-id");
                const videoTitle = this.closest("tr").querySelector(".video-title").textContent;
                editVideo(videoId, videoTitle);
            });
        });
    }
    
    // Delete video buttons
    const deleteButtons = document.querySelectorAll(".delete-video-btn");
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const videoId = this.getAttribute("data-id");
                const videoTitle = this.closest("tr").querySelector(".video-title").textContent;
                deleteVideo(videoId, videoTitle);
            });
        });
    }
    
    // Preview video buttons
    const previewButtons = document.querySelectorAll(".preview-video-btn");
    if (previewButtons.length > 0) {
        previewButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const videoId = this.getAttribute("data-id");
                const videoTitle = this.closest("tr").querySelector(".video-title").textContent;
                previewVideo(videoId, videoTitle);
            });
        });
    }
}

// Function to initialize add video button
function initializeAddVideoButton() {
    const addBtn = document.querySelector(".add-video-btn");
    if (addBtn) {
        addBtn.addEventListener("click", function(e) {
            e.preventDefault();
            showAddVideoModal();
        });
    }
}

// Function to initialize video search and filter
function initializeVideoSearch() {
    // Search input
    const searchInput = document.querySelector(".search-input");
    if (searchInput) {
        searchInput.addEventListener("keyup", function(e) {
            if (e.key === "Enter") {
                searchVideos(this.value);
            }
        });
    }
    
    // Search button
    const searchBtn = document.querySelector(".search-btn");
    if (searchBtn) {
        searchBtn.addEventListener("click", function() {
            const searchInput = document.querySelector(".search-input");
            if (searchInput) {
                searchVideos(searchInput.value);
            }
        });
    }
    
    // Category filter
    const categoryFilter = document.querySelector("#category-filter");
    if (categoryFilter) {
        categoryFilter.addEventListener("change", function() {
            filterVideos();
        });
    }
    
    // Apply filters button
    const filterBtn = document.querySelector(".filter-btn");
    if (filterBtn) {
        filterBtn.addEventListener("click", function() {
            filterVideos();
        });
    }
    
    // Reset filters button
    const resetBtn = document.querySelector(".reset-filter-btn");
    if (resetBtn) {
        resetBtn.addEventListener("click", function() {
            resetVideoFilters();
        });
    }
}

// Function to search videos
function searchVideos(query) {
    if (!query) return;
    
    console.log(`Searching videos with query: ${query}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we'll simulate filtering the table
    
    const videoRows = document.querySelectorAll(".video-row");
    let matchCount = 0;
    
    videoRows.forEach(row => {
        const videoTitle = row.querySelector(".video-title").textContent.toLowerCase();
        
        if (videoTitle.includes(query.toLowerCase())) {
            row.style.display = "";
            matchCount++;
        } else {
            row.style.display = "none";
        }
    });
    
    // Update results count
    updateVideoResultsCount(matchCount);
    
    // Show notification
    showNotification(`Found ${matchCount} videos matching "${query}"`, "info");
}

// Function to filter videos
function filterVideos() {
    const categoryFilter = document.querySelector("#category-filter");
    if (!categoryFilter) return;
    
    const category = categoryFilter.value;
    
    console.log(`Filtering videos - Category: ${category}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we'll simulate filtering the table
    
    const videoRows = document.querySelectorAll(".video-row");
    let matchCount = 0;
    
    videoRows.forEach(row => {
        const videoCategory = row.querySelector(".video-category").textContent;
        
        const categoryMatch = category === "all" || videoCategory === category;
        
        if (categoryMatch) {
            row.style.display = "";
            matchCount++;
        } else {
            row.style.display = "none";
        }
    });
    
    // Update results count
    updateVideoResultsCount(matchCount);
    
    // Show notification
    showNotification(`Filters applied. Found ${matchCount} matching videos.`, "info");
}

// Function to reset video filters
function resetVideoFilters() {
    const categoryFilter = document.querySelector("#category-filter");
    const searchInput = document.querySelector(".search-input");
    
    if (categoryFilter) categoryFilter.value = "all";
    if (searchInput) searchInput.value = "";
    
    // Show all rows
    const videoRows = document.querySelectorAll(".video-row");
    videoRows.forEach(row => {
        row.style.display = "";
    });
    
    // Update results count
    updateVideoResultsCount(videoRows.length);
    
    // Show notification
    showNotification("Filters reset", "info");
}

// Function to update video results count
function updateVideoResultsCount(count) {
    const resultsCount = document.querySelector(".results-count");
    if (resultsCount) {
        resultsCount.textContent = `Showing ${count} videos`;
    }
}

// Function to initialize video pagination
function initializeVideoPagination() {
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

// Function to initialize video bulk actions
function initializeVideoBulkActions() {
    // Select all checkbox
    const selectAllCheckbox = document.querySelector("#select-all");
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function() {
            const checkboxes = document.querySelectorAll(".video-checkbox");
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            
            // Update bulk actions state
            updateVideoBulkActionsState();
        });
    }
    
    // Individual checkboxes
    const videoCheckboxes = document.querySelectorAll(".video-checkbox");
    if (videoCheckboxes.length > 0) {
        videoCheckboxes.forEach(checkbox => {
            checkbox.addEventListener("change", function() {
                // Update select all checkbox
                const selectAllCheckbox = document.querySelector("#select-all");
                const allCheckboxes = document.querySelectorAll(".video-checkbox");
                const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                }
                
                // Update bulk actions state
                updateVideoBulkActionsState();
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
                executeVideoBulkAction(action);
            }
        });
    }
}

// Function to update video bulk actions state
function updateVideoBulkActionsState() {
    const checkedCheckboxes = document.querySelectorAll(".video-checkbox:checked");
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

// Function to execute video bulk action
function executeVideoBulkAction(action) {
    const checkedCheckboxes = document.querySelectorAll(".video-checkbox:checked");
    if (checkedCheckboxes.length === 0) return;
    
    const videoIds = Array.from(checkedCheckboxes).map(cb => cb.value);
    console.log(`Executing bulk action: ${action} on videos: ${videoIds.join(", ")}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we'll just show a notification
    
    let message = "";
    
    switch (action) {
        case "delete":
            message = `Deleted ${checkedCheckboxes.length} videos`;
            // Remove rows from table
            checkedCheckboxes.forEach(cb => {
                const row = cb.closest("tr");
                if (row) row.remove();
            });
            break;
        default:
            message = `Executed ${action} on ${checkedCheckboxes.length} videos`;
    }
    
    // Update results count
    const remainingRows = document.querySelectorAll(".video-row");
    updateVideoResultsCount(remainingRows.length);
    
    // Reset checkboxes
    const selectAllCheckbox = document.querySelector("#select-all");
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
    }
    
    // Update bulk actions state
    updateVideoBulkActionsState();
    
    // Show notification
    showNotification(message, "success");
}

// Function to view video details
function viewVideoDetails(videoId, videoTitle) {
    console.log(`Viewing details for video: ${videoTitle} (ID: ${videoId})`);
    
    // Create modal if it doesn't exist
    let modal = document.getElementById("video-details-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "video-details-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Video Details</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="video-details">
                        <div class="video-thumbnail">
                            <img id="detail-video-thumbnail" src="https://via.placeholder.com/320x180.png?text=Video+Thumbnail" alt="Video Thumbnail">
                        </div>
                        <h3 id="detail-video-title">${videoTitle}</h3>
                        <div class="detail-group">
                            <div class="detail-label">Category:</div>
                            <div class="detail-value" id="detail-video-category">Function 1</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Duration:</div>
                            <div class="detail-value" id="detail-video-duration">15:30</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Uploaded By:</div>
                            <div class="detail-value" id="detail-video-uploader">Admin User</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Uploaded On:</div>
                            <div class="detail-value" id="detail-video-date">May 20, 2025</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Views:</div>
                            <div class="detail-value" id="detail-video-views">1,245</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Description:</div>
                            <div class="detail-value" id="detail-video-description">Comprehensive guide to engine room safety procedures.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline close-btn">Close</button>
                    <button type="button" class="btn btn-primary preview-btn">Preview</button>
                    <button type="button" class="btn btn-secondary edit-btn">Edit Video</button>
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
            editVideo(videoId, videoTitle);
        });
        
        modal.querySelector(".preview-btn").addEventListener("click", function() {
            modal.style.display = "none";
            previewVideo(videoId, videoTitle);
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
            .video-details {
                padding: 10px;
            }
            
            .video-thumbnail {
                margin-bottom: 15px;
                text-align: center;
            }
            
            .video-thumbnail img {
                max-width: 100%;
                height: auto;
                border-radius: 4px;
            }
            
            .detail-group {
                display: flex;
                margin-bottom: 15px;
            }
            
            .detail-label {
                font-weight: 600;
                width: 120px;
            }
        `;
        document.head.appendChild(style);
    } else {
        // Update modal content with video data
        modal.querySelector("#detail-video-title").textContent = videoTitle;
        
        // In a real implementation, we would fetch video data from API
        // For this demo, we'll use mock data
        const mockVideoData = {
            category: "Function 1",
            duration: "15:30",
            uploader: "Admin User",
            date: "May 20, 2025",
            views: "1,245",
            description: "Comprehensive guide to engine room safety procedures."
        };
        
        modal.querySelector("#detail-video-category").textContent = mockVideoData.category;
        modal.querySelector("#detail-video-duration").textContent = mockVideoData.duration;
        modal.querySelector("#detail-video-uploader").textContent = mockVideoData.uploader;
        modal.querySelector("#detail-video-date").textContent = mockVideoData.date;
        modal.querySelector("#detail-video-views").textContent = mockVideoData.views;
        modal.querySelector("#detail-video-description").textContent = mockVideoData.description;
    }
    
    // Show modal
    modal.style.display = "block";
}

// Function to edit video
function editVideo(videoId, videoTitle) {
    console.log(`Editing video: ${videoTitle} (ID: ${videoId})`);
    
    // Create modal if it doesn't exist
    let modal = document.getElementById("edit-video-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "edit-video-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Edit Video</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="edit-video-form">
                        <div class="form-group">
                            <label for="edit-video-title">Title</label>
                            <input type="text" id="edit-video-title" name="edit-video-title" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-video-category">Category</label>
                            <select id="edit-video-category" name="edit-video-category">
                                <option value="Function 1">Function 1</option>
                                <option value="Function 2">Function 2</option>
                                <option value="Function 3">Function 3</option>
                                <option value="Function 4">Function 4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-video-description">Description</label>
                            <textarea id="edit-video-description" name="edit-video-description" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit-video-thumbnail">Replace Thumbnail (Optional)</label>
                            <div class="file-upload">
                                <input type="file" id="edit-video-thumbnail" name="edit-video-thumbnail" accept="image/*">
                                <label for="edit-video-thumbnail" class="file-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Choose Image</span>
                                </label>
                                <div class="file-name">No file chosen</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Current Thumbnail</label>
                            <div class="current-thumbnail">
                                <img src="https://via.placeholder.com/320x180.png?text=Video+Thumbnail" alt="Current Thumbnail">
                            </div>
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
            const form = document.getElementById("edit-video-form");
            const title = form.querySelector("#edit-video-title").value;
            
            if (title) {
                modal.style.display = "none";
                showNotification(`Video "${title}" updated successfully!`, "success");
                
                // Update video row in table if it exists
                const videoRow = document.querySelector(`.video-row[data-id="${videoId}"]`);
                if (videoRow) {
                    videoRow.querySelector(".video-title").textContent = title;
                    videoRow.querySelector(".video-category").textContent = form.querySelector("#edit-video-category").value;
                }
            } else {
                showNotification("Please fill in all required fields", "error");
            }
        });
        
        // File input change event
        const fileInput = modal.querySelector("#edit-video-thumbnail");
        const fileName = modal.querySelector(".file-name");
        
        fileInput.addEventListener("change", function() {
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
            } else {
                fileName.textContent = "No file chosen";
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
            .current-thumbnail {
                margin-top: 10px;
            }
            
            .current-thumbnail img {
                max-width: 200px;
                height: auto;
                border-radius: 4px;
            }
        `;
        document.head.appendChild(style);
    }
    
    // In a real implementation, we would fetch video data from API
    // For this demo, we'll use mock data
    const mockVideoData = {
        title: videoTitle,
        category: "Function 1",
        description: "Comprehensive guide to engine room safety procedures."
    };
    
    // Update form fields with video data
    document.getElementById("edit-video-title").value = mockVideoData.title;
    document.getElementById("edit-video-category").value = mockVideoData.category;
    document.getElementById("edit-video-description").value = mockVideoData.description;
    document.getElementById("edit-video-thumbnail").value = ""; // Clear file input
    document.querySelector("#edit-video-modal .file-name").textContent = "No file chosen";
    
    // Show modal
    modal.style.display = "block";
}

// Function to delete video
function deleteVideo(videoId, videoTitle) {
    console.log(`Deleting video: ${videoTitle} (ID: ${videoId})`);
    
    // Show confirmation modal
    showConfirmationModal(
        `Are you sure you want to delete video "${videoTitle}"?`,
        function() {
            // In a real implementation, this would send an API request
            // For this demo, we'll just remove the row from the table
            
            const videoRow = document.querySelector(`.video-row[data-id="${videoId}"]`);
            if (videoRow) {
                videoRow.remove();
                
                // Update results count
                const remainingRows = document.querySelectorAll(".video-row");
                updateVideoResultsCount(remainingRows.length);
                
                // Show notification
                showNotification(`Video ${videoTitle} deleted successfully!`, "success");
            }
        },
        "Delete Video",
        "Delete",
        "btn-danger"
    );
}

// Function to preview video
function previewVideo(videoId, videoTitle) {
    console.log(`Previewing video: ${videoTitle} (ID: ${videoId})`);
    
    // Create modal if it doesn't exist
    let modal = document.getElementById("preview-video-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "preview-video-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content large">
                <div class="modal-header">
                    <h2 id="preview-video-title">${videoTitle}</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="video-player">
                        <div class="video-container">
                            <iframe id="video-iframe" width="100%" height="400" src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <div class="video-info">
                            <div class="video-description" id="preview-video-description">
                                Comprehensive guide to engine room safety procedures.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline close-btn">Close</button>
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
        
        // Close when clicking outside
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
        
        // Add styles
        const style = document.createElement("style");
        style.textContent = `
            .modal-content.large { max-width: 800px; }
            .video-container { margin-bottom: 20px; }
            .video-description { margin-top: 15px; }
        `;
        document.head.appendChild(style);
    } else {
        // Update modal content with video data
        modal.querySelector("#preview-video-title").textContent = videoTitle;
        
        // In a real implementation, we would fetch video data from API
        // For this demo, we'll use mock data
        const mockVideoData = {
            description: "Comprehensive guide to engine room safety procedures."
        };
        
        modal.querySelector("#preview-video-description").textContent = mockVideoData.description;
    }
    
    // Show modal
    modal.style.display = "block";
}

// Function to show add video modal (re-using from dashboard.js)
// Ensure showAddVideoModal is defined globally or imported if needed
// If not, copy the function from dashboard.js here
if (typeof showAddVideoModal === "undefined") {
    function showAddVideoModal() {
        // Create modal if it doesn't exist
        let modal = document.getElementById("add-video-modal");
        if (!modal) {
            modal = document.createElement("div");
            modal.id = "add-video-modal";
            modal.className = "modal";
            
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
                                    <option value="Function 1">Function 1</option>
                                    <option value="Function 2">Function 2</option>
                                    <option value="Function 3">Function 3</option>
                                    <option value="Function 4">Function 4</option>
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
            modal.querySelector(".close").addEventListener("click", function() {
                modal.style.display = "none";
            });
            
            modal.querySelector(".cancel-btn").addEventListener("click", function() {
                modal.style.display = "none";
            });
            
            modal.querySelector(".save-btn").addEventListener("click", function() {
                const form = document.getElementById("add-video-form");
                const title = form.querySelector("#video-title").value;
                const videoType = form.querySelector("#video-type").value;
                const videoFile = form.querySelector("#video-file").value;
                const videoUrl = form.querySelector("#video-url").value;
                
                if (title && ((videoType === "upload" && videoFile) || (videoType !== "upload" && videoUrl))) {
                    modal.style.display = "none";
                    showNotification(`Video "${title}" added successfully!`, "success");
                    
                    // Add new row to table (demo)
                    const videosTable = document.querySelector(".videos-table tbody");
                    if (videosTable) {
                        const newRow = document.createElement("tr");
                        newRow.className = "video-row";
                        newRow.setAttribute("data-id", Date.now().toString());
                        
                        const category = form.querySelector("#video-category").value;
                        
                        newRow.innerHTML = `
                            <td>
                                <input type="checkbox" class="video-checkbox" value="${Date.now()}">
                            </td>
                            <td>
                                <div class="video-info">
                                    <img src="https://via.placeholder.com/120x68.png?text=Thumbnail" alt="Video Thumbnail" class="video-thumbnail">
                                    <div class="video-title">${title}</div>
                                </div>
                            </td>
                            <td class="video-category">${category}</td>
                            <td>15:30</td>
                            <td>Admin User</td>
                            <td>Just now</td>
                            <td>0</td>
                            <td>
                                <div class="actions">
                                    <button class="action-btn preview-video-btn" data-id="${Date.now()}" title="Preview Video">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    <button class="action-btn edit-video-btn" data-id="${Date.now()}" title="Edit Video">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn delete-video-btn" data-id="${Date.now()}" title="Delete Video">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        `;
                        
                        videosTable.insertBefore(newRow, videosTable.firstChild);
                        
                        // Add event listeners to the new buttons
                        initializeVideoActionButtons();
                        
                        // Update results count
                        const allRows = document.querySelectorAll(".video-row");
                        updateVideoResultsCount(allRows.length);
                    }
                } else {
                    showNotification("Please fill in all required fields", "error");
                }
            });
            
            // Video type change event
            const videoTypeSelect = modal.querySelector("#video-type");
            const videoUploadGroup = modal.querySelector(".video-upload-group");
            const videoUrlGroup = modal.querySelector(".video-url-group");
            
            videoTypeSelect.addEventListener("change", function() {
                if (this.value === "upload") {
                    videoUploadGroup.style.display = "";
                    videoUrlGroup.style.display = "none";
                } else {
                    videoUploadGroup.style.display = "none";
                    videoUrlGroup.style.display = "";
                }
            });
            
            // File input change events
            const videoFileInput = modal.querySelector("#video-file");
            const videoFileName = modal.querySelector(".file-name");
            
            videoFileInput.addEventListener("change", function() {
                if (this.files.length > 0) {
                    videoFileName.textContent = this.files[0].name;
                } else {
                    videoFileName.textContent = "No file chosen";
                }
            });
            
            const thumbnailInput = modal.querySelector("#video-thumbnail");
            const thumbnailName = modal.querySelector(".thumbnail-name");
            
            thumbnailInput.addEventListener("change", function() {
                if (this.files.length > 0) {
                    thumbnailName.textContent = this.files[0].name;
                } else {
                    thumbnailName.textContent = "No file chosen";
                }
            });
            
            // Close when clicking outside
            window.addEventListener("click", function(e) {
                if (e.target === modal) {
                    modal.style.display = "none";
                }
            });
            
            // Add styles for file upload if not already present
            if (!document.getElementById("file-upload-styles")) {
                const style = document.createElement("style");
                style.id = "file-upload-styles";
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
                    
                    .file-name, .thumbnail-name {
                        margin-top: 5px;
                        font-size: 14px;
                        color: #6b7280;
                    }
                `;
                document.head.appendChild(style);
            }
        }
        
        // Reset form
        const form = document.getElementById("add-video-form");
        if (form) form.reset();
        
        // Reset file input displays
        const videoFileName = modal.querySelector(".file-name");
        if (videoFileName) videoFileName.textContent = "No file chosen";
        
        const thumbnailName = modal.querySelector(".thumbnail-name");
        if (thumbnailName) thumbnailName.textContent = "No file chosen";
        
        // Reset video type display
        const videoTypeSelect = modal.querySelector("#video-type");
        if (videoTypeSelect) {
            videoTypeSelect.value = "upload";
            videoTypeSelect.dispatchEvent(new Event("change"));
        }
        
        // Show modal
        modal.style.display = "block";
    }
}
