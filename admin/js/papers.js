// Papers Management functionality
document.addEventListener("DOMContentLoaded", function() {
    // Initialize action buttons
    initializePaperActionButtons();
    
    // Initialize add paper button
    initializeAddPaperButton();
    
    // Initialize search and filter
    initializePaperSearch();
    
    // Initialize pagination
    initializePaperPagination();
    
    // Initialize bulk actions
    initializePaperBulkActions();
});

// Function to initialize paper action buttons
function initializePaperActionButtons() {
    // View paper details buttons
    const viewButtons = document.querySelectorAll(".view-paper-btn");
    if (viewButtons.length > 0) {
        viewButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const paperId = this.getAttribute("data-id");
                const paperTitle = this.closest("tr").querySelector(".paper-title").textContent;
                viewPaperDetails(paperId, paperTitle);
            });
        });
    }
    
    // Edit paper buttons
    const editButtons = document.querySelectorAll(".edit-paper-btn");
    if (editButtons.length > 0) {
        editButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const paperId = this.getAttribute("data-id");
                const paperTitle = this.closest("tr").querySelector(".paper-title").textContent;
                editPaper(paperId, paperTitle);
            });
        });
    }
    
    // Delete paper buttons
    const deleteButtons = document.querySelectorAll(".delete-paper-btn");
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const paperId = this.getAttribute("data-id");
                const paperTitle = this.closest("tr").querySelector(".paper-title").textContent;
                deletePaper(paperId, paperTitle);
            });
        });
    }
    
    // Download paper buttons
    const downloadButtons = document.querySelectorAll(".download-paper-btn");
    if (downloadButtons.length > 0) {
        downloadButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const paperId = this.getAttribute("data-id");
                const paperTitle = this.closest("tr").querySelector(".paper-title").textContent;
                downloadPaper(paperId, paperTitle);
            });
        });
    }
}

// Function to initialize add paper button
function initializeAddPaperButton() {
    const addBtn = document.querySelector(".add-paper-btn");
    if (addBtn) {
        addBtn.addEventListener("click", function(e) {
            e.preventDefault();
            showAddPaperModal();
        });
    }
}

// Function to initialize paper search and filter
function initializePaperSearch() {
    // Search input
    const searchInput = document.querySelector(".search-input");
    if (searchInput) {
        searchInput.addEventListener("keyup", function(e) {
            if (e.key === "Enter") {
                searchPapers(this.value);
            }
        });
    }
    
    // Search button
    const searchBtn = document.querySelector(".search-btn");
    if (searchBtn) {
        searchBtn.addEventListener("click", function() {
            const searchInput = document.querySelector(".search-input");
            if (searchInput) {
                searchPapers(searchInput.value);
            }
        });
    }
    
    // Category filter
    const categoryFilter = document.querySelector("#category-filter");
    if (categoryFilter) {
        categoryFilter.addEventListener("change", function() {
            filterPapers();
        });
    }
    
    // Year filter
    const yearFilter = document.querySelector("#year-filter");
    if (yearFilter) {
        yearFilter.addEventListener("change", function() {
            filterPapers();
        });
    }
    
    // Apply filters button
    const filterBtn = document.querySelector(".filter-btn");
    if (filterBtn) {
        filterBtn.addEventListener("click", function() {
            filterPapers();
        });
    }
    
    // Reset filters button
    const resetBtn = document.querySelector(".reset-filter-btn");
    if (resetBtn) {
        resetBtn.addEventListener("click", function() {
            resetPaperFilters();
        });
    }
}

// Function to search papers
function searchPapers(query) {
    if (!query) return;
    
    console.log(`Searching papers with query: ${query}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we'll simulate filtering the table
    
    const paperRows = document.querySelectorAll(".paper-row");
    let matchCount = 0;
    
    paperRows.forEach(row => {
        const paperTitle = row.querySelector(".paper-title").textContent.toLowerCase();
        
        if (paperTitle.includes(query.toLowerCase())) {
            row.style.display = "";
            matchCount++;
        } else {
            row.style.display = "none";
        }
    });
    
    // Update results count
    updatePaperResultsCount(matchCount);
    
    // Show notification
    showNotification(`Found ${matchCount} papers matching "${query}"`, "info");
}

// Function to filter papers
function filterPapers() {
    const categoryFilter = document.querySelector("#category-filter");
    const yearFilter = document.querySelector("#year-filter");
    
    if (!categoryFilter && !yearFilter) return;
    
    const category = categoryFilter ? categoryFilter.value : "all";
    const year = yearFilter ? yearFilter.value : "all";
    
    console.log(`Filtering papers - Category: ${category}, Year: ${year}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we'll simulate filtering the table
    
    const paperRows = document.querySelectorAll(".paper-row");
    let matchCount = 0;
    
    paperRows.forEach(row => {
        const paperCategory = row.querySelector(".paper-category").textContent;
        const paperYear = row.querySelector(".paper-year").textContent;
        
        const categoryMatch = category === "all" || paperCategory === category;
        const yearMatch = year === "all" || paperYear === year;
        
        if (categoryMatch && yearMatch) {
            row.style.display = "";
            matchCount++;
        } else {
            row.style.display = "none";
        }
    });
    
    // Update results count
    updatePaperResultsCount(matchCount);
    
    // Show notification
    showNotification(`Filters applied. Found ${matchCount} matching papers.`, "info");
}

// Function to reset paper filters
function resetPaperFilters() {
    const categoryFilter = document.querySelector("#category-filter");
    const yearFilter = document.querySelector("#year-filter");
    const searchInput = document.querySelector(".search-input");
    
    if (categoryFilter) categoryFilter.value = "all";
    if (yearFilter) yearFilter.value = "all";
    if (searchInput) searchInput.value = "";
    
    // Show all rows
    const paperRows = document.querySelectorAll(".paper-row");
    paperRows.forEach(row => {
        row.style.display = "";
    });
    
    // Update results count
    updatePaperResultsCount(paperRows.length);
    
    // Show notification
    showNotification("Filters reset", "info");
}

// Function to update paper results count
function updatePaperResultsCount(count) {
    const resultsCount = document.querySelector(".results-count");
    if (resultsCount) {
        resultsCount.textContent = `Showing ${count} papers`;
    }
}

// Function to initialize paper pagination
function initializePaperPagination() {
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

// Function to initialize paper bulk actions
function initializePaperBulkActions() {
    // Select all checkbox
    const selectAllCheckbox = document.querySelector("#select-all");
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function() {
            const checkboxes = document.querySelectorAll(".paper-checkbox");
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            
            // Update bulk actions state
            updatePaperBulkActionsState();
        });
    }
    
    // Individual checkboxes
    const paperCheckboxes = document.querySelectorAll(".paper-checkbox");
    if (paperCheckboxes.length > 0) {
        paperCheckboxes.forEach(checkbox => {
            checkbox.addEventListener("change", function() {
                // Update select all checkbox
                const selectAllCheckbox = document.querySelector("#select-all");
                const allCheckboxes = document.querySelectorAll(".paper-checkbox");
                const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                }
                
                // Update bulk actions state
                updatePaperBulkActionsState();
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
                executePaperBulkAction(action);
            }
        });
    }
}

// Function to update paper bulk actions state
function updatePaperBulkActionsState() {
    const checkedCheckboxes = document.querySelectorAll(".paper-checkbox:checked");
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

// Function to execute paper bulk action
function executePaperBulkAction(action) {
    const checkedCheckboxes = document.querySelectorAll(".paper-checkbox:checked");
    if (checkedCheckboxes.length === 0) return;
    
    const paperIds = Array.from(checkedCheckboxes).map(cb => cb.value);
    console.log(`Executing bulk action: ${action} on papers: ${paperIds.join(", ")}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we'll just show a notification
    
    let message = "";
    
    switch (action) {
        case "delete":
            message = `Deleted ${checkedCheckboxes.length} papers`;
            // Remove rows from table
            checkedCheckboxes.forEach(cb => {
                const row = cb.closest("tr");
                if (row) row.remove();
            });
            break;
        case "download":
            message = `Downloaded ${checkedCheckboxes.length} papers`;
            break;
        default:
            message = `Executed ${action} on ${checkedCheckboxes.length} papers`;
    }
    
    // Update results count
    const remainingRows = document.querySelectorAll(".paper-row");
    updatePaperResultsCount(remainingRows.length);
    
    // Reset checkboxes
    const selectAllCheckbox = document.querySelector("#select-all");
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
    }
    
    // Update bulk actions state
    updatePaperBulkActionsState();
    
    // Show notification
    showNotification(message, "success");
}

// Function to view paper details
function viewPaperDetails(paperId, paperTitle) {
    console.log(`Viewing details for paper: ${paperTitle} (ID: ${paperId})`);
    
    // Create modal if it doesn't exist
    let modal = document.getElementById("paper-details-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "paper-details-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Paper Details</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="paper-details">
                        <div class="paper-icon">
                            <i class="fas fa-file-pdf fa-3x"></i>
                        </div>
                        <h3 id="detail-paper-title">${paperTitle}</h3>
                        <div class="detail-group">
                            <div class="detail-label">Category:</div>
                            <div class="detail-value" id="detail-paper-category">Function 1</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Year:</div>
                            <div class="detail-value" id="detail-paper-year">2024</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Pages:</div>
                            <div class="detail-value" id="detail-paper-pages">15</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">File Size:</div>
                            <div class="detail-value" id="detail-paper-size">2.5 MB</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Uploaded By:</div>
                            <div class="detail-value" id="detail-paper-uploader">Admin User</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Uploaded On:</div>
                            <div class="detail-value" id="detail-paper-date">May 15, 2025</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Downloads:</div>
                            <div class="detail-value" id="detail-paper-downloads">245</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Description:</div>
                            <div class="detail-value" id="detail-paper-description">Comprehensive exam paper covering key concepts of Function 1.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline close-btn">Close</button>
                    <button type="button" class="btn btn-primary download-btn">Download</button>
                    <button type="button" class="btn btn-secondary edit-btn">Edit Paper</button>
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
            editPaper(paperId, paperTitle);
        });
        
        modal.querySelector(".download-btn").addEventListener("click", function() {
            modal.style.display = "none";
            downloadPaper(paperId, paperTitle);
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
            .paper-details {
                padding: 10px;
            }
            
            .paper-icon {
                margin-bottom: 15px;
                text-align: center;
                color: #e53e3e;
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
        // Update modal content with paper data
        modal.querySelector("#detail-paper-title").textContent = paperTitle;
        
        // In a real implementation, we would fetch paper data from API
        // For this demo, we'll use mock data
        const mockPaperData = {
            category: "Function 1",
            year: "2024",
            pages: "15",
            size: "2.5 MB",
            uploader: "Admin User",
            date: "May 15, 2025",
            downloads: "245",
            description: "Comprehensive exam paper covering key concepts of Function 1."
        };
        
        modal.querySelector("#detail-paper-category").textContent = mockPaperData.category;
        modal.querySelector("#detail-paper-year").textContent = mockPaperData.year;
        modal.querySelector("#detail-paper-pages").textContent = mockPaperData.pages;
        modal.querySelector("#detail-paper-size").textContent = mockPaperData.size;
        modal.querySelector("#detail-paper-uploader").textContent = mockPaperData.uploader;
        modal.querySelector("#detail-paper-date").textContent = mockPaperData.date;
        modal.querySelector("#detail-paper-downloads").textContent = mockPaperData.downloads;
        modal.querySelector("#detail-paper-description").textContent = mockPaperData.description;
    }
    
    // Show modal
    modal.style.display = "block";
}

// Function to edit paper
function editPaper(paperId, paperTitle) {
    console.log(`Editing paper: ${paperTitle} (ID: ${paperId})`);
    
    // Create modal if it doesn't exist
    let modal = document.getElementById("edit-paper-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "edit-paper-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Edit Paper</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="edit-paper-form">
                        <div class="form-group">
                            <label for="edit-paper-title">Title</label>
                            <input type="text" id="edit-paper-title" name="edit-paper-title" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-paper-category">Category</label>
                            <select id="edit-paper-category" name="edit-paper-category">
                                <option value="Function 1">Function 1</option>
                                <option value="Function 2">Function 2</option>
                                <option value="Function 3">Function 3</option>
                                <option value="Function 4">Function 4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-paper-year">Year</label>
                            <select id="edit-paper-year" name="edit-paper-year">
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                                <option value="2021">2021</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-paper-description">Description</label>
                            <textarea id="edit-paper-description" name="edit-paper-description" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit-paper-file">Replace File (Optional)</label>
                            <div class="file-upload">
                                <input type="file" id="edit-paper-file" name="edit-paper-file" accept=".pdf">
                                <label for="edit-paper-file" class="file-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Choose PDF</span>
                                </label>
                                <div class="file-name">No file chosen</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Current File</label>
                            <div class="current-file">
                                <i class="fas fa-file-pdf"></i>
                                <span id="current-file-name">${paperTitle}.pdf</span>
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
            const form = document.getElementById("edit-paper-form");
            const title = form.querySelector("#edit-paper-title").value;
            
            if (title) {
                modal.style.display = "none";
                showNotification(`Paper "${title}" updated successfully!`, "success");
                
                // Update paper row in table if it exists
                const paperRow = document.querySelector(`.paper-row[data-id="${paperId}"]`);
                if (paperRow) {
                    paperRow.querySelector(".paper-title").textContent = title;
                    paperRow.querySelector(".paper-category").textContent = form.querySelector("#edit-paper-category").value;
                    paperRow.querySelector(".paper-year").textContent = form.querySelector("#edit-paper-year").value;
                }
            } else {
                showNotification("Please fill in all required fields", "error");
            }
        });
        
        // File input change event
        const fileInput = modal.querySelector("#edit-paper-file");
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
            .current-file {
                display: flex;
                align-items: center;
                padding: 10px;
                background-color: #f3f4f6;
                border-radius: 4px;
            }
            
            .current-file i {
                margin-right: 10px;
                color: #e53e3e;
            }
        `;
        document.head.appendChild(style);
    }
    
    // In a real implementation, we would fetch paper data from API
    // For this demo, we'll use mock data
    const mockPaperData = {
        title: paperTitle,
        category: "Function 1",
        year: "2024",
        description: "Comprehensive exam paper covering key concepts of Function 1."
    };
    
    // Update form fields with paper data
    document.getElementById("edit-paper-title").value = mockPaperData.title;
    document.getElementById("edit-paper-category").value = mockPaperData.category;
    document.getElementById("edit-paper-year").value = mockPaperData.year;
    document.getElementById("edit-paper-description").value = mockPaperData.description;
    document.getElementById("edit-paper-file").value = ""; // Clear file input
    document.querySelector("#edit-paper-modal .file-name").textContent = "No file chosen";
    document.getElementById("current-file-name").textContent = `${paperTitle}.pdf`;
    
    // Show modal
    modal.style.display = "block";
}

// Function to delete paper
function deletePaper(paperId, paperTitle) {
    console.log(`Deleting paper: ${paperTitle} (ID: ${paperId})`);
    
    // Show confirmation modal
    showConfirmationModal(
        `Are you sure you want to delete paper "${paperTitle}"?`,
        function() {
            // In a real implementation, this would send an API request
            // For this demo, we'll just remove the row from the table
            
            const paperRow = document.querySelector(`.paper-row[data-id="${paperId}"]`);
            if (paperRow) {
                paperRow.remove();
                
                // Update results count
                const remainingRows = document.querySelectorAll(".paper-row");
                updatePaperResultsCount(remainingRows.length);
                
                // Show notification
                showNotification(`Paper ${paperTitle} deleted successfully!`, "success");
            }
        },
        "Delete Paper",
        "Delete",
        "btn-danger"
    );
}

// Function to download paper
function downloadPaper(paperId, paperTitle) {
    console.log(`Downloading paper: ${paperTitle} (ID: ${paperId})`);
    
    // In a real implementation, this would trigger a file download
    // For this demo, we'll just show a notification
    
    showNotification(`Downloading paper: ${paperTitle}.pdf`, "success");
    
    // Simulate download progress
    let progress = 0;
    const interval = setInterval(function() {
        progress += 20;
        if (progress >= 100) {
            clearInterval(interval);
            showNotification(`Paper ${paperTitle}.pdf downloaded successfully!`, "success");
        } else {
            showNotification(`Downloading paper: ${paperTitle}.pdf (${progress}%)`, "info");
        }
    }, 500);
}

// Function to show add paper modal
function showAddPaperModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById("add-paper-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "add-paper-modal";
        modal.className = "modal";
        
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
                                <option value="Function 1">Function 1</option>
                                <option value="Function 2">Function 2</option>
                                <option value="Function 3">Function 3</option>
                                <option value="Function 4">Function 4</option>
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
                                    <span>Choose PDF</span>
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
        modal.querySelector(".close").addEventListener("click", function() {
            modal.style.display = "none";
        });
        
        modal.querySelector(".cancel-btn").addEventListener("click", function() {
            modal.style.display = "none";
        });
        
        modal.querySelector(".save-btn").addEventListener("click", function() {
            const form = document.getElementById("add-paper-form");
            const title = form.querySelector("#paper-title").value;
            const fileInput = form.querySelector("#paper-file");
            
            if (title && fileInput.files.length > 0) {
                modal.style.display = "none";
                showNotification(`Paper "${title}" added successfully!`, "success");
                
                // Add new row to table (demo)
                const papersTable = document.querySelector(".papers-table tbody");
                if (papersTable) {
                    const newRow = document.createElement("tr");
                    newRow.className = "paper-row";
                    newRow.setAttribute("data-id", Date.now().toString());
                    
                    const category = form.querySelector("#paper-category").value;
                    const year = form.querySelector("#paper-year").value;
                    
                    newRow.innerHTML = `
                        <td>
                            <input type="checkbox" class="paper-checkbox" value="${Date.now()}">
                        </td>
                        <td>
                            <div class="paper-info">
                                <i class="fas fa-file-pdf paper-icon"></i>
                                <div class="paper-title">${title}</div>
                            </div>
                        </td>
                        <td class="paper-category">${category}</td>
                        <td class="paper-year">${year}</td>
                        <td>15</td>
                        <td>Admin User</td>
                        <td>Just now</td>
                        <td>0</td>
                        <td>
                            <div class="actions">
                                <button class="action-btn download-paper-btn" data-id="${Date.now()}" title="Download Paper">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="action-btn view-paper-btn" data-id="${Date.now()}" title="View Paper Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit-paper-btn" data-id="${Date.now()}" title="Edit Paper">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete-paper-btn" data-id="${Date.now()}" title="Delete Paper">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    
                    papersTable.insertBefore(newRow, papersTable.firstChild);
                    
                    // Add event listeners to the new buttons
                    initializePaperActionButtons();
                    
                    // Update results count
                    const allRows = document.querySelectorAll(".paper-row");
                    updatePaperResultsCount(allRows.length);
                }
            } else {
                showNotification("Please fill in all required fields", "error");
            }
        });
        
        // File input change event
        const fileInput = modal.querySelector("#paper-file");
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
                
                .paper-icon {
                    color: #e53e3e;
                    margin-right: 10px;
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    // Reset form
    const form = document.getElementById("add-paper-form");
    if (form) form.reset();
    
    // Reset file input display
    const fileName = modal.querySelector(".file-name");
    if (fileName) fileName.textContent = "No file chosen";
    
    // Show modal
    modal.style.display = "block";
}
