// Study Materials page functionality
document.addEventListener("DOMContentLoaded", function() {
    // Initialize action buttons
    initializeMaterialActionButtons();
    
    // Initialize upload button
    initializeUploadMaterialButton();
    
    // Initialize search and filter
    initializeMaterialSearch();
    
    // Initialize pagination
    initializeMaterialPagination();
    
    // Initialize bulk actions
    initializeMaterialBulkActions();
});

// Function to initialize material action buttons
function initializeMaterialActionButtons() {
    // View material details buttons
    const viewButtons = document.querySelectorAll(".view-material-btn");
    if (viewButtons.length > 0) {
        viewButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const materialId = this.getAttribute("data-id");
                const materialTitle = this.closest("tr").querySelector(".material-title").textContent;
                viewMaterialDetails(materialId, materialTitle);
            });
        });
    }
    
    // Edit material buttons
    const editButtons = document.querySelectorAll(".edit-material-btn");
    if (editButtons.length > 0) {
        editButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const materialId = this.getAttribute("data-id");
                const materialTitle = this.closest("tr").querySelector(".material-title").textContent;
                editMaterial(materialId, materialTitle);
            });
        });
    }
    
    // Delete material buttons
    const deleteButtons = document.querySelectorAll(".delete-material-btn");
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const materialId = this.getAttribute("data-id");
                const materialTitle = this.closest("tr").querySelector(".material-title").textContent;
                deleteMaterial(materialId, materialTitle);
            });
        });
    }
}

// Function to initialize upload material button
function initializeUploadMaterialButton() {
    const uploadBtn = document.querySelector(".upload-material-btn");
    if (uploadBtn) {
        uploadBtn.addEventListener("click", function(e) {
            e.preventDefault();
            showUploadMaterialModal();
        });
    }
}

// Function to initialize material search and filter
function initializeMaterialSearch() {
    // Search input
    const searchInput = document.querySelector(".search-input");
    if (searchInput) {
        searchInput.addEventListener("keyup", function(e) {
            if (e.key === "Enter") {
                searchMaterials(this.value);
            }
        });
    }
    
    // Search button
    const searchBtn = document.querySelector(".search-btn");
    if (searchBtn) {
        searchBtn.addEventListener("click", function() {
            const searchInput = document.querySelector(".search-input");
            if (searchInput) {
                searchMaterials(searchInput.value);
            }
        });
    }
    
    // Category filter
    const categoryFilter = document.querySelector("#category-filter");
    if (categoryFilter) {
        categoryFilter.addEventListener("change", function() {
            filterMaterials();
        });
    }
    
    // Apply filters button
    const filterBtn = document.querySelector(".filter-btn");
    if (filterBtn) {
        filterBtn.addEventListener("click", function() {
            filterMaterials();
        });
    }
    
    // Reset filters button
    const resetBtn = document.querySelector(".reset-filter-btn");
    if (resetBtn) {
        resetBtn.addEventListener("click", function() {
            resetMaterialFilters();
        });
    }
}

// Function to search materials
function searchMaterials(query) {
    if (!query) return;
    
    console.log(`Searching materials with query: ${query}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we"ll simulate filtering the table
    
    const materialRows = document.querySelectorAll(".material-row");
    let matchCount = 0;
    
    materialRows.forEach(row => {
        const materialTitle = row.querySelector(".material-title").textContent.toLowerCase();
        
        if (materialTitle.includes(query.toLowerCase())) {
            row.style.display = "";
            matchCount++;
        } else {
            row.style.display = "none";
        }
    });
    
    // Update results count
    updateMaterialResultsCount(matchCount);
    
    // Show notification
    showNotification(`Found ${matchCount} materials matching "${query}"`, "info");
}

// Function to filter materials
function filterMaterials() {
    const categoryFilter = document.querySelector("#category-filter");
    if (!categoryFilter) return;
    
    const category = categoryFilter.value;
    
    console.log(`Filtering materials - Category: ${category}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we"ll simulate filtering the table
    
    const materialRows = document.querySelectorAll(".material-row");
    let matchCount = 0;
    
    materialRows.forEach(row => {
        const materialCategory = row.querySelector(".material-category").textContent;
        
        const categoryMatch = category === "all" || materialCategory === category;
        
        if (categoryMatch) {
            row.style.display = "";
            matchCount++;
        } else {
            row.style.display = "none";
        }
    });
    
    // Update results count
    updateMaterialResultsCount(matchCount);
    
    // Show notification
    showNotification(`Filters applied. Found ${matchCount} matching materials.`, "info");
}

// Function to reset material filters
function resetMaterialFilters() {
    const categoryFilter = document.querySelector("#category-filter");
    const searchInput = document.querySelector(".search-input");
    
    if (categoryFilter) categoryFilter.value = "all";
    if (searchInput) searchInput.value = "";
    
    // Show all rows
    const materialRows = document.querySelectorAll(".material-row");
    materialRows.forEach(row => {
        row.style.display = "";
    });
    
    // Update results count
    updateMaterialResultsCount(materialRows.length);
    
    // Show notification
    showNotification("Filters reset", "info");
}

// Function to update material results count
function updateMaterialResultsCount(count) {
    const resultsCount = document.querySelector(".results-count");
    if (resultsCount) {
        resultsCount.textContent = `Showing ${count} materials`;
    }
}

// Function to initialize material pagination
function initializeMaterialPagination() {
    const paginationBtns = document.querySelectorAll(".pagination-btn");
    if (paginationBtns.length > 0) {
        paginationBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                // Remove active class from all buttons
                paginationBtns.forEach(b => b.classList.remove("active"));
                // Add active class to clicked button
                this.classList.add("active");
                
                // In a real implementation, this would load the corresponding page
                // For this demo, we"ll just show a notification
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

// Function to initialize material bulk actions
function initializeMaterialBulkActions() {
    // Select all checkbox
    const selectAllCheckbox = document.querySelector("#select-all");
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function() {
            const checkboxes = document.querySelectorAll(".material-checkbox");
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            
            // Update bulk actions state
            updateMaterialBulkActionsState();
        });
    }
    
    // Individual checkboxes
    const materialCheckboxes = document.querySelectorAll(".material-checkbox");
    if (materialCheckboxes.length > 0) {
        materialCheckboxes.forEach(checkbox => {
            checkbox.addEventListener("change", function() {
                // Update select all checkbox
                const selectAllCheckbox = document.querySelector("#select-all");
                const allCheckboxes = document.querySelectorAll(".material-checkbox");
                const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                }
                
                // Update bulk actions state
                updateMaterialBulkActionsState();
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
                executeMaterialBulkAction(action);
            }
        });
    }
}

// Function to update material bulk actions state
function updateMaterialBulkActionsState() {
    const checkedCheckboxes = document.querySelectorAll(".material-checkbox:checked");
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

// Function to execute material bulk action
function executeMaterialBulkAction(action) {
    const checkedCheckboxes = document.querySelectorAll(".material-checkbox:checked");
    if (checkedCheckboxes.length === 0) return;
    
    const materialIds = Array.from(checkedCheckboxes).map(cb => cb.value);
    console.log(`Executing bulk action: ${action} on materials: ${materialIds.join(", ")}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we"ll just show a notification
    
    let message = "";
    
    switch (action) {
        case "delete":
            message = `Deleted ${checkedCheckboxes.length} materials`;
            // Remove rows from table
            checkedCheckboxes.forEach(cb => {
                const row = cb.closest("tr");
                if (row) row.remove();
            });
            break;
        default:
            message = `Executed ${action} on ${checkedCheckboxes.length} materials`;
    }
    
    // Update results count
    const remainingRows = document.querySelectorAll(".material-row");
    updateMaterialResultsCount(remainingRows.length);
    
    // Reset checkboxes
    const selectAllCheckbox = document.querySelector("#select-all");
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
    }
    
    // Update bulk actions state
    updateMaterialBulkActionsState();
    
    // Show notification
    showNotification(message, "success");
}

// Function to view material details
function viewMaterialDetails(materialId, materialTitle) {
    console.log(`Viewing details for material: ${materialTitle} (ID: ${materialId})`);
    
    // Create modal if it doesn"t exist
    let modal = document.getElementById("material-details-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "material-details-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Material Details</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="material-details">
                        <h3 id="detail-material-title">${materialTitle}</h3>
                        <div class="detail-group">
                            <div class="detail-label">Category:</div>
                            <div class="detail-value" id="detail-material-category">Function 1</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Uploaded By:</div>
                            <div class="detail-value" id="detail-material-uploader">Admin User</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Uploaded On:</div>
                            <div class="detail-value" id="detail-material-date">May 20, 2025</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">File Type:</div>
                            <div class="detail-value" id="detail-material-type">PDF</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">File Size:</div>
                            <div class="detail-value" id="detail-material-size">2.5 MB</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Description:</div>
                            <div class="detail-value" id="detail-material-description">Comprehensive guide to MARPOL Annex VI regulations.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline close-btn">Close</button>
                    <button type="button" class="btn btn-primary download-btn">Download</button>
                    <button type="button" class="btn btn-secondary edit-btn">Edit Material</button>
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
            editMaterial(materialId, materialTitle);
        });
        
        modal.querySelector(".download-btn").addEventListener("click", function() {
            showNotification(`Downloading "${materialTitle}"... (simulation)`, "info");
        });
        
        // Close when clicking outside
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
    } else {
        // Update modal content with material data
        modal.querySelector("#detail-material-title").textContent = materialTitle;
        
        // In a real implementation, we would fetch material data from API
        // For this demo, we"ll use mock data
        const mockMaterialData = {
            category: "Function 1",
            uploader: "Admin User",
            date: "May 20, 2025",
            type: "PDF",
            size: "2.5 MB",
            description: "Comprehensive guide to MARPOL Annex VI regulations."
        };
        
        modal.querySelector("#detail-material-category").textContent = mockMaterialData.category;
        modal.querySelector("#detail-material-uploader").textContent = mockMaterialData.uploader;
        modal.querySelector("#detail-material-date").textContent = mockMaterialData.date;
        modal.querySelector("#detail-material-type").textContent = mockMaterialData.type;
        modal.querySelector("#detail-material-size").textContent = mockMaterialData.size;
        modal.querySelector("#detail-material-description").textContent = mockMaterialData.description;
    }
    
    // Show modal
    modal.style.display = "block";
}

// Function to edit material
function editMaterial(materialId, materialTitle) {
    console.log(`Editing material: ${materialTitle} (ID: ${materialId})`);
    
    // Create modal if it doesn"t exist
    let modal = document.getElementById("edit-material-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "edit-material-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Edit Study Material</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="edit-material-form">
                        <div class="form-group">
                            <label for="edit-material-title">Title</label>
                            <input type="text" id="edit-material-title" name="edit-material-title" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-material-category">Category</label>
                            <select id="edit-material-category" name="edit-material-category">
                                <option value="Function 1">Function 1</option>
                                <option value="Function 2">Function 2</option>
                                <option value="Function 3">Function 3</option>
                                <option value="Function 4">Function 4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-material-description">Description</label>
                            <textarea id="edit-material-description" name="edit-material-description" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit-material-file">Replace File (Optional)</label>
                            <div class="file-upload">
                                <input type="file" id="edit-material-file" name="edit-material-file">
                                <label for="edit-material-file" class="file-label">
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
            const form = document.getElementById("edit-material-form");
            const title = form.querySelector("#edit-material-title").value;
            
            if (title) {
                modal.style.display = "none";
                showNotification(`Material "${title}" updated successfully!`, "success");
                
                // Update material row in table if it exists
                const materialRow = document.querySelector(`.material-row[data-id="${materialId}"]`);
                if (materialRow) {
                    materialRow.querySelector(".material-title").textContent = title;
                    materialRow.querySelector(".material-category").textContent = form.querySelector("#edit-material-category").value;
                }
            } else {
                showNotification("Please fill in all required fields", "error");
            }
        });
        
        // File input change event
        const fileInput = modal.querySelector("#edit-material-file");
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
    }
    
    // In a real implementation, we would fetch material data from API
    // For this demo, we"ll use mock data
    const mockMaterialData = {
        title: materialTitle,
        category: "Function 1",
        description: "Comprehensive guide to MARPOL Annex VI regulations."
    };
    
    // Update form fields with material data
    document.getElementById("edit-material-title").value = mockMaterialData.title;
    document.getElementById("edit-material-category").value = mockMaterialData.category;
    document.getElementById("edit-material-description").value = mockMaterialData.description;
    document.getElementById("edit-material-file").value = ""; // Clear file input
    document.querySelector("#edit-material-modal .file-name").textContent = "No file chosen";
    
    // Show modal
    modal.style.display = "block";
}

// Function to delete material
function deleteMaterial(materialId, materialTitle) {
    console.log(`Deleting material: ${materialTitle} (ID: ${materialId})`);
    
    // Show confirmation modal
    showConfirmationModal(
        `Are you sure you want to delete material "${materialTitle}"?`,
        function() {
            // In a real implementation, this would send an API request
            // For this demo, we"ll just remove the row from the table
            
            const materialRow = document.querySelector(`.material-row[data-id="${materialId}"]`);
            if (materialRow) {
                materialRow.remove();
                
                // Update results count
                const remainingRows = document.querySelectorAll(".material-row");
                updateMaterialResultsCount(remainingRows.length);
                
                // Show notification
                showNotification(`Material ${materialTitle} deleted successfully!`, "success");
            }
        },
        "Delete Material",
        "Delete",
        "btn-danger"
    );
}

// Function to show upload material modal (re-using from dashboard.js)
// Ensure showUploadMaterialModal is defined globally or imported if needed
// If not, copy the function from dashboard.js here
if (typeof showUploadMaterialModal === "undefined") {
    function showUploadMaterialModal() {
        // Create modal if it doesn"t exist
        let modal = document.getElementById("upload-material-modal");
        if (!modal) {
            modal = document.createElement("div");
            modal.id = "upload-material-modal";
            modal.className = "modal";
            
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
                                    <option value="Function 1">Function 1</option>
                                    <option value="Function 2">Function 2</option>
                                    <option value="Function 3">Function 3</option>
                                    <option value="Function 4">Function 4</option>
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
            modal.querySelector(".close").addEventListener("click", function() {
                modal.style.display = "none";
            });
            
            modal.querySelector(".cancel-btn").addEventListener("click", function() {
                modal.style.display = "none";
            });
            
            modal.querySelector(".save-btn").addEventListener("click", function() {
                const form = document.getElementById("upload-material-form");
                const title = form.querySelector("#material-title").value;
                const file = form.querySelector("#material-file").value;
                
                if (title && file) {
                    modal.style.display = "none";
                    showNotification(`Study material "${title}" uploaded successfully!`, "success");
                    
                    // Add new row to table (demo)
                    const materialsTable = document.querySelector(".materials-table tbody");
                    if (materialsTable) {
                        const newRow = document.createElement("tr");
                        newRow.className = "material-row";
                        newRow.setAttribute("data-id", Date.now().toString());
                        
                        const category = form.querySelector("#material-category").value;
                        
                        newRow.innerHTML = `
                            <td>
                                <input type="checkbox" class="material-checkbox" value="${Date.now()}">
                            </td>
                            <td>
                                <div class="material-info">
                                    <i class="fas fa-file-pdf material-icon"></i>
                                    <div class="material-title">${title}</div>
                                </div>
                            </td>
                            <td class="material-category">${category}</td>
                            <td>Admin User</td>
                            <td>Just now</td>
                            <td>2.5 MB</td>
                            <td>
                                <div class="actions">
                                    <button class="action-btn view-material-btn" data-id="${Date.now()}" title="View Material">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn edit-material-btn" data-id="${Date.now()}" title="Edit Material">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn delete-material-btn" data-id="${Date.now()}" title="Delete Material">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        `;
                        
                        materialsTable.insertBefore(newRow, materialsTable.firstChild);
                        
                        // Add event listeners to the new buttons
                        initializeMaterialActionButtons();
                        
                        // Update results count
                        const allRows = document.querySelectorAll(".material-row");
                        updateMaterialResultsCount(allRows.length);
                    }
                } else {
                    showNotification("Please fill in all required fields", "error");
                }
            });
            
            // File input change event
            const fileInput = modal.querySelector("#material-file");
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
                    
                    .file-name {
                        margin-top: 5px;
                        font-size: 14px;
                        color: #6b7280;
                    }
                `;
                document.head.appendChild(style);
            }
        }
        
        // Reset form
        const form = document.getElementById("upload-material-form");
        if (form) form.reset();
        const fileName = modal.querySelector(".file-name");
        if (fileName) fileName.textContent = "No file chosen";
        
        // Show modal
        modal.style.display = "block";
    }
}
