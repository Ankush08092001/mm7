// Mock Tests page functionality
document.addEventListener("DOMContentLoaded", function() {
    // Initialize action buttons
    initializeTestActionButtons();
    
    // Initialize create test button
    initializeCreateTestButton();
    
    // Initialize search and filter
    initializeTestSearch();
    
    // Initialize pagination
    initializeTestPagination();
    
    // Initialize bulk actions
    initializeTestBulkActions();
});

// Function to initialize test action buttons
function initializeTestActionButtons() {
    // View test details buttons
    const viewButtons = document.querySelectorAll(".view-test-btn");
    if (viewButtons.length > 0) {
        viewButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const testId = this.getAttribute("data-id");
                const testTitle = this.closest("tr").querySelector(".test-title").textContent;
                viewTestDetails(testId, testTitle);
            });
        });
    }
    
    // Edit test buttons
    const editButtons = document.querySelectorAll(".edit-test-btn");
    if (editButtons.length > 0) {
        editButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const testId = this.getAttribute("data-id");
                const testTitle = this.closest("tr").querySelector(".test-title").textContent;
                editTest(testId, testTitle);
            });
        });
    }
    
    // Delete test buttons
    const deleteButtons = document.querySelectorAll(".delete-test-btn");
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const testId = this.getAttribute("data-id");
                const testTitle = this.closest("tr").querySelector(".test-title").textContent;
                deleteTest(testId, testTitle);
            });
        });
    }
    
    // Manage questions buttons
    const manageQuestionsButtons = document.querySelectorAll(".manage-questions-btn");
    if (manageQuestionsButtons.length > 0) {
        manageQuestionsButtons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const testId = this.getAttribute("data-id");
                const testTitle = this.closest("tr").querySelector(".test-title").textContent;
                manageTestQuestions(testId, testTitle);
            });
        });
    }
}

// Function to initialize create test button
function initializeCreateTestButton() {
    const createBtn = document.querySelector(".create-test-btn");
    if (createBtn) {
        createBtn.addEventListener("click", function(e) {
            e.preventDefault();
            showCreateTestModal();
        });
    }
}

// Function to initialize test search and filter
function initializeTestSearch() {
    // Search input
    const searchInput = document.querySelector(".search-input");
    if (searchInput) {
        searchInput.addEventListener("keyup", function(e) {
            if (e.key === "Enter") {
                searchTests(this.value);
            }
        });
    }
    
    // Search button
    const searchBtn = document.querySelector(".search-btn");
    if (searchBtn) {
        searchBtn.addEventListener("click", function() {
            const searchInput = document.querySelector(".search-input");
            if (searchInput) {
                searchTests(searchInput.value);
            }
        });
    }
    
    // Category filter
    const categoryFilter = document.querySelector("#category-filter");
    if (categoryFilter) {
        categoryFilter.addEventListener("change", function() {
            filterTests();
        });
    }
    
    // Apply filters button
    const filterBtn = document.querySelector(".filter-btn");
    if (filterBtn) {
        filterBtn.addEventListener("click", function() {
            filterTests();
        });
    }
    
    // Reset filters button
    const resetBtn = document.querySelector(".reset-filter-btn");
    if (resetBtn) {
        resetBtn.addEventListener("click", function() {
            resetTestFilters();
        });
    }
}

// Function to search tests
function searchTests(query) {
    if (!query) return;
    
    console.log(`Searching tests with query: ${query}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we"ll simulate filtering the table
    
    const testRows = document.querySelectorAll(".test-row");
    let matchCount = 0;
    
    testRows.forEach(row => {
        const testTitle = row.querySelector(".test-title").textContent.toLowerCase();
        
        if (testTitle.includes(query.toLowerCase())) {
            row.style.display = "";
            matchCount++;
        } else {
            row.style.display = "none";
        }
    });
    
    // Update results count
    updateTestResultsCount(matchCount);
    
    // Show notification
    showNotification(`Found ${matchCount} tests matching "${query}"`, "info");
}

// Function to filter tests
function filterTests() {
    const categoryFilter = document.querySelector("#category-filter");
    if (!categoryFilter) return;
    
    const category = categoryFilter.value;
    
    console.log(`Filtering tests - Category: ${category}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we"ll simulate filtering the table
    
    const testRows = document.querySelectorAll(".test-row");
    let matchCount = 0;
    
    testRows.forEach(row => {
        const testCategory = row.querySelector(".test-category").textContent;
        
        const categoryMatch = category === "all" || testCategory === category;
        
        if (categoryMatch) {
            row.style.display = "";
            matchCount++;
        } else {
            row.style.display = "none";
        }
    });
    
    // Update results count
    updateTestResultsCount(matchCount);
    
    // Show notification
    showNotification(`Filters applied. Found ${matchCount} matching tests.`, "info");
}

// Function to reset test filters
function resetTestFilters() {
    const categoryFilter = document.querySelector("#category-filter");
    const searchInput = document.querySelector(".search-input");
    
    if (categoryFilter) categoryFilter.value = "all";
    if (searchInput) searchInput.value = "";
    
    // Show all rows
    const testRows = document.querySelectorAll(".test-row");
    testRows.forEach(row => {
        row.style.display = "";
    });
    
    // Update results count
    updateTestResultsCount(testRows.length);
    
    // Show notification
    showNotification("Filters reset", "info");
}

// Function to update test results count
function updateTestResultsCount(count) {
    const resultsCount = document.querySelector(".results-count");
    if (resultsCount) {
        resultsCount.textContent = `Showing ${count} tests`;
    }
}

// Function to initialize test pagination
function initializeTestPagination() {
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

// Function to initialize test bulk actions
function initializeTestBulkActions() {
    // Select all checkbox
    const selectAllCheckbox = document.querySelector("#select-all");
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function() {
            const checkboxes = document.querySelectorAll(".test-checkbox");
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            
            // Update bulk actions state
            updateTestBulkActionsState();
        });
    }
    
    // Individual checkboxes
    const testCheckboxes = document.querySelectorAll(".test-checkbox");
    if (testCheckboxes.length > 0) {
        testCheckboxes.forEach(checkbox => {
            checkbox.addEventListener("change", function() {
                // Update select all checkbox
                const selectAllCheckbox = document.querySelector("#select-all");
                const allCheckboxes = document.querySelectorAll(".test-checkbox");
                const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                }
                
                // Update bulk actions state
                updateTestBulkActionsState();
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
                executeTestBulkAction(action);
            }
        });
    }
}

// Function to update test bulk actions state
function updateTestBulkActionsState() {
    const checkedCheckboxes = document.querySelectorAll(".test-checkbox:checked");
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

// Function to execute test bulk action
function executeTestBulkAction(action) {
    const checkedCheckboxes = document.querySelectorAll(".test-checkbox:checked");
    if (checkedCheckboxes.length === 0) return;
    
    const testIds = Array.from(checkedCheckboxes).map(cb => cb.value);
    console.log(`Executing bulk action: ${action} on tests: ${testIds.join(", ")}`);
    
    // In a real implementation, this would send an API request
    // For this demo, we"ll just show a notification
    
    let message = "";
    
    switch (action) {
        case "delete":
            message = `Deleted ${checkedCheckboxes.length} tests`;
            // Remove rows from table
            checkedCheckboxes.forEach(cb => {
                const row = cb.closest("tr");
                if (row) row.remove();
            });
            break;
        default:
            message = `Executed ${action} on ${checkedCheckboxes.length} tests`;
    }
    
    // Update results count
    const remainingRows = document.querySelectorAll(".test-row");
    updateTestResultsCount(remainingRows.length);
    
    // Reset checkboxes
    const selectAllCheckbox = document.querySelector("#select-all");
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
    }
    
    // Update bulk actions state
    updateTestBulkActionsState();
    
    // Show notification
    showNotification(message, "success");
}

// Function to view test details
function viewTestDetails(testId, testTitle) {
    console.log(`Viewing details for test: ${testTitle} (ID: ${testId})`);
    
    // Create modal if it doesn"t exist
    let modal = document.getElementById("test-details-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "test-details-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Test Details</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="test-details">
                        <h3 id="detail-test-title">${testTitle}</h3>
                        <div class="detail-group">
                            <div class="detail-label">Category:</div>
                            <div class="detail-value" id="detail-test-category">Function 1</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Duration:</div>
                            <div class="detail-value" id="detail-test-duration">60 minutes</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Questions:</div>
                            <div class="detail-value" id="detail-test-questions">25</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Passing Score:</div>
                            <div class="detail-value" id="detail-test-passing">70%</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Created On:</div>
                            <div class="detail-value" id="detail-test-date">May 15, 2025</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Description:</div>
                            <div class="detail-value" id="detail-test-description">Mock test covering key concepts of Function 1.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline close-btn">Close</button>
                    <button type="button" class="btn btn-primary manage-questions-btn">Manage Questions</button>
                    <button type="button" class="btn btn-secondary edit-btn">Edit Test</button>
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
            editTest(testId, testTitle);
        });
        
        modal.querySelector(".manage-questions-btn").addEventListener("click", function() {
            modal.style.display = "none";
            manageTestQuestions(testId, testTitle);
        });
        
        // Close when clicking outside
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
    } else {
        // Update modal content with test data
        modal.querySelector("#detail-test-title").textContent = testTitle;
        
        // In a real implementation, we would fetch test data from API
        // For this demo, we"ll use mock data
        const mockTestData = {
            category: "Function 1",
            duration: "60 minutes",
            questions: 25,
            passing: "70%",
            date: "May 15, 2025",
            description: "Mock test covering key concepts of Function 1."
        };
        
        modal.querySelector("#detail-test-category").textContent = mockTestData.category;
        modal.querySelector("#detail-test-duration").textContent = mockTestData.duration;
        modal.querySelector("#detail-test-questions").textContent = mockTestData.questions;
        modal.querySelector("#detail-test-passing").textContent = mockTestData.passing;
        modal.querySelector("#detail-test-date").textContent = mockTestData.date;
        modal.querySelector("#detail-test-description").textContent = mockTestData.description;
    }
    
    // Show modal
    modal.style.display = "block";
}

// Function to edit test
function editTest(testId, testTitle) {
    console.log(`Editing test: ${testTitle} (ID: ${testId})`);
    
    // Create modal if it doesn"t exist
    let modal = document.getElementById("edit-test-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "edit-test-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Edit Test</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="edit-test-form">
                        <div class="form-group">
                            <label for="edit-test-title">Test Title</label>
                            <input type="text" id="edit-test-title" name="edit-test-title" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-test-category">Category</label>
                            <select id="edit-test-category" name="edit-test-category">
                                <option value="Function 1">Function 1</option>
                                <option value="Function 2">Function 2</option>
                                <option value="Function 3">Function 3</option>
                                <option value="Function 4">Function 4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-test-duration">Duration (minutes)</label>
                            <input type="number" id="edit-test-duration" name="edit-test-duration" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-test-passing-score">Passing Score (%)</label>
                            <input type="number" id="edit-test-passing-score" name="edit-test-passing-score" min="1" max="100" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-test-description">Description</label>
                            <textarea id="edit-test-description" name="edit-test-description" rows="4"></textarea>
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
            const form = document.getElementById("edit-test-form");
            const title = form.querySelector("#edit-test-title").value;
            
            if (title) {
                modal.style.display = "none";
                showNotification(`Test "${title}" updated successfully!`, "success");
                
                // Update test row in table if it exists
                const testRow = document.querySelector(`.test-row[data-id="${testId}"]`);
                if (testRow) {
                    testRow.querySelector(".test-title").textContent = title;
                    testRow.querySelector(".test-category").textContent = form.querySelector("#edit-test-category").value;
                    testRow.querySelector(".test-duration").textContent = `${form.querySelector("#edit-test-duration").value} min`;
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
    }
    
    // In a real implementation, we would fetch test data from API
    // For this demo, we"ll use mock data
    const mockTestData = {
        title: testTitle,
        category: "Function 1",
        duration: 60,
        passing: 70,
        description: "Mock test covering key concepts of Function 1."
    };
    
    // Update form fields with test data
    document.getElementById("edit-test-title").value = mockTestData.title;
    document.getElementById("edit-test-category").value = mockTestData.category;
    document.getElementById("edit-test-duration").value = mockTestData.duration;
    document.getElementById("edit-test-passing-score").value = mockTestData.passing;
    document.getElementById("edit-test-description").value = mockTestData.description;
    
    // Show modal
    modal.style.display = "block";
}

// Function to delete test
function deleteTest(testId, testTitle) {
    console.log(`Deleting test: ${testTitle} (ID: ${testId})`);
    
    // Show confirmation modal
    showConfirmationModal(
        `Are you sure you want to delete test "${testTitle}"?`,
        function() {
            // In a real implementation, this would send an API request
            // For this demo, we"ll just remove the row from the table
            
            const testRow = document.querySelector(`.test-row[data-id="${testId}"]`);
            if (testRow) {
                testRow.remove();
                
                // Update results count
                const remainingRows = document.querySelectorAll(".test-row");
                updateTestResultsCount(remainingRows.length);
                
                // Show notification
                showNotification(`Test ${testTitle} deleted successfully!`, "success");
            }
        },
        "Delete Test",
        "Delete",
        "btn-danger"
    );
}

// Function to manage test questions
function manageTestQuestions(testId, testTitle) {
    console.log(`Managing questions for test: ${testTitle} (ID: ${testId})`);
    
    // Create modal if it doesn"t exist
    let modal = document.getElementById("manage-questions-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "manage-questions-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content large">
                <div class="modal-header">
                    <h2>Manage Questions for <span id="manage-test-title">${testTitle}</span></h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="questions-list">
                        <!-- Questions will be loaded here -->
                    </div>
                    <button type="button" class="btn btn-primary add-question-btn">Add New Question</button>
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
        
        modal.querySelector(".add-question-btn").addEventListener("click", function() {
            showAddQuestionModal(testId);
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
            .questions-list { margin-bottom: 20px; max-height: 400px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 4px; padding: 10px; }
            .question-item { padding: 15px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; }
            .question-item:last-child { border-bottom: none; }
            .question-text { flex: 1; margin-right: 15px; }
            .question-actions button { margin-left: 5px; }
        `;
        document.head.appendChild(style);
    }
    
    // Update modal title
    modal.querySelector("#manage-test-title").textContent = testTitle;
    
    // Load questions (demo)
    loadTestQuestions(testId);
    
    // Show modal
    modal.style.display = "block";
}

// Function to load test questions (demo)
function loadTestQuestions(testId) {
    const questionsList = document.querySelector("#manage-questions-modal .questions-list");
    if (!questionsList) return;
    
    // In a real implementation, fetch questions from API
    const demoQuestions = [
        { id: 1, text: "What is the primary purpose of MARPOL Annex VI?" },
        { id: 2, text: "Define the term "Gross Tonnage"." },
        { id: 3, text: "Explain the difference between SOLAS and MARPOL." },
        { id: 4, text: "What are the main components of a ship"s propulsion system?" },
        { id: 5, text: "Describe the function of a bilge pump." }
    ];
    
    questionsList.innerHTML = demoQuestions.map(q => `
        <div class="question-item" data-id="${q.id}">
            <div class="question-text">${q.text}</div>
            <div class="question-actions">
                <button class="action-btn edit-question-btn" title="Edit Question"><i class="fas fa-edit"></i></button>
                <button class="action-btn delete-question-btn" title="Delete Question"><i class="fas fa-trash"></i></button>
            </div>
        </div>
    `).join("");
    
    // Add event listeners to new buttons
    questionsList.querySelectorAll(".edit-question-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const questionItem = this.closest(".question-item");
            const questionId = questionItem.getAttribute("data-id");
            const questionText = questionItem.querySelector(".question-text").textContent;
            showEditQuestionModal(questionId, questionText);
        });
    });
    
    questionsList.querySelectorAll(".delete-question-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const questionItem = this.closest(".question-item");
            const questionId = questionItem.getAttribute("data-id");
            const questionText = questionItem.querySelector(".question-text").textContent;
            deleteQuestion(questionId, questionText);
        });
    });
}

// Function to show add question modal
function showAddQuestionModal(testId) {
    console.log(`Adding question to test ID: ${testId}`);
    
    // Create modal if it doesn"t exist
    let modal = document.getElementById("add-question-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "add-question-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New Question</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="add-question-form">
                        <div class="form-group">
                            <label for="question-text">Question Text</label>
                            <textarea id="question-text" name="question-text" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="question-type">Question Type</label>
                            <select id="question-type" name="question-type">
                                <option value="multiple-choice">Multiple Choice</option>
                                <option value="true-false">True/False</option>
                                <option value="short-answer">Short Answer</option>
                            </select>
                        </div>
                        <div class="options-container">
                            <label>Options</label>
                            <div class="option-item">
                                <input type="radio" name="correct-option" value="0" checked>
                                <input type="text" class="option-input" placeholder="Option 1" required>
                            </div>
                            <div class="option-item">
                                <input type="radio" name="correct-option" value="1">
                                <input type="text" class="option-input" placeholder="Option 2" required>
                            </div>
                            <div class="option-item">
                                <input type="radio" name="correct-option" value="2">
                                <input type="text" class="option-input" placeholder="Option 3">
                            </div>
                            <div class="option-item">
                                <input type="radio" name="correct-option" value="3">
                                <input type="text" class="option-input" placeholder="Option 4">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="question-explanation">Explanation (Optional)</label>
                            <textarea id="question-explanation" name="question-explanation" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                    <button type="button" class="btn btn-primary save-btn">Add Question</button>
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
            const form = document.getElementById("add-question-form");
            const questionText = form.querySelector("#question-text").value;
            
            if (questionText) {
                modal.style.display = "none";
                showNotification(`Question added successfully!`, "success");
                // Reload questions in the manage questions modal
                loadTestQuestions(testId);
            } else {
                showNotification("Please enter the question text", "error");
            }
        });
        
        // Question type change handler
        modal.querySelector("#question-type").addEventListener("change", function() {
            const optionsContainer = modal.querySelector(".options-container");
            if (this.value === "multiple-choice") {
                optionsContainer.style.display = "";
            } else {
                optionsContainer.style.display = "none";
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
            .options-container { margin-top: 15px; }
            .option-item { display: flex; align-items: center; margin-bottom: 10px; }
            .option-item input[type="radio"] { margin-right: 10px; }
            .option-item input[type="text"] { flex: 1; }
        `;
        document.head.appendChild(style);
    }
    
    // Reset form
    const form = document.getElementById("add-question-form");
    if (form) form.reset();
    modal.querySelector("#question-type").dispatchEvent(new Event("change")); // Trigger change to show/hide options
    
    // Show modal
    modal.style.display = "block";
}

// Function to show edit question modal
function showEditQuestionModal(questionId, questionText) {
    console.log(`Editing question: ${questionText} (ID: ${questionId})`);
    
    // Create modal if it doesn"t exist
    let modal = document.getElementById("edit-question-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "edit-question-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Edit Question</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="edit-question-form">
                        <input type="hidden" id="edit-question-id" name="edit-question-id">
                        <div class="form-group">
                            <label for="edit-question-text">Question Text</label>
                            <textarea id="edit-question-text" name="edit-question-text" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit-question-type">Question Type</label>
                            <select id="edit-question-type" name="edit-question-type">
                                <option value="multiple-choice">Multiple Choice</option>
                                <option value="true-false">True/False</option>
                                <option value="short-answer">Short Answer</option>
                            </select>
                        </div>
                        <div class="edit-options-container">
                            <label>Options</label>
                            <div class="option-item">
                                <input type="radio" name="edit-correct-option" value="0">
                                <input type="text" class="option-input" placeholder="Option 1" required>
                            </div>
                            <div class="option-item">
                                <input type="radio" name="edit-correct-option" value="1">
                                <input type="text" class="option-input" placeholder="Option 2" required>
                            </div>
                            <div class="option-item">
                                <input type="radio" name="edit-correct-option" value="2">
                                <input type="text" class="option-input" placeholder="Option 3">
                            </div>
                            <div class="option-item">
                                <input type="radio" name="edit-correct-option" value="3">
                                <input type="text" class="option-input" placeholder="Option 4">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit-question-explanation">Explanation (Optional)</label>
                            <textarea id="edit-question-explanation" name="edit-question-explanation" rows="2"></textarea>
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
            const form = document.getElementById("edit-question-form");
            const questionText = form.querySelector("#edit-question-text").value;
            const questionId = form.querySelector("#edit-question-id").value;
            
            if (questionText) {
                modal.style.display = "none";
                showNotification(`Question updated successfully!`, "success");
                // Reload questions in the manage questions modal
                const manageModal = document.getElementById("manage-questions-modal");
                if (manageModal && manageModal.style.display === "block") {
                    const testId = manageModal.getAttribute("data-test-id"); // Assuming we store testId here
                    loadTestQuestions(testId);
                }
            } else {
                showNotification("Please enter the question text", "error");
            }
        });
        
        // Question type change handler
        modal.querySelector("#edit-question-type").addEventListener("change", function() {
            const optionsContainer = modal.querySelector(".edit-options-container");
            if (this.value === "multiple-choice") {
                optionsContainer.style.display = "";
            } else {
                optionsContainer.style.display = "none";
            }
        });
        
        // Close when clicking outside
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
    }
    
    // In a real implementation, fetch question data from API
    // For this demo, use mock data
    const mockQuestionData = {
        text: questionText,
        type: "multiple-choice",
        options: ["Prevent air pollution", "Prevent water pollution", "Manage ballast water", "Ensure ship safety"],
        correctOption: 0,
        explanation: "MARPOL Annex VI focuses on regulations for the prevention of air pollution from ships."
    };
    
    // Update form fields
    document.getElementById("edit-question-id").value = questionId;
    document.getElementById("edit-question-text").value = mockQuestionData.text;
    document.getElementById("edit-question-type").value = mockQuestionData.type;
    document.getElementById("edit-question-explanation").value = mockQuestionData.explanation;
    
    const optionInputs = modal.querySelectorAll(".edit-options-container .option-input");
    const correctOptionRadios = modal.querySelectorAll("input[name=\"edit-correct-option\"]");
    
    optionInputs.forEach((input, index) => {
        input.value = mockQuestionData.options[index] || "";
    });
    
    correctOptionRadios.forEach((radio, index) => {
        radio.checked = (index === mockQuestionData.correctOption);
    });
    
    modal.querySelector("#edit-question-type").dispatchEvent(new Event("change")); // Trigger change to show/hide options
    
    // Show modal
    modal.style.display = "block";
}

// Function to delete question
function deleteQuestion(questionId, questionText) {
    console.log(`Deleting question: ${questionText} (ID: ${questionId})`);
    
    // Show confirmation modal
    showConfirmationModal(
        `Are you sure you want to delete this question?`,
        function() {
            // In a real implementation, this would send an API request
            // For this demo, remove the item from the list
            const questionItem = document.querySelector(`#manage-questions-modal .question-item[data-id="${questionId}"]`);
            if (questionItem) {
                questionItem.remove();
                showNotification(`Question deleted successfully!`, "success");
            }
        },
        "Delete Question",
        "Delete",
        "btn-danger"
    );
}

// Function to show create test modal (re-using from dashboard.js)
// Ensure showCreateTestModal is defined globally or imported if needed
// If not, copy the function from dashboard.js here
if (typeof showCreateTestModal === "undefined") {
    function showCreateTestModal() {
        // Create modal if it doesn"t exist
        let modal = document.getElementById("create-test-modal");
        if (!modal) {
            modal = document.createElement("div");
            modal.id = "create-test-modal";
            modal.className = "modal";
            
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
                                    <option value="Function 1">Function 1</option>
                                    <option value="Function 2">Function 2</option>
                                    <option value="Function 3">Function 3</option>
                                    <option value="Function 4">Function 4</option>
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
            modal.querySelector(".close").addEventListener("click", function() {
                modal.style.display = "none";
            });
            
            modal.querySelector(".cancel-btn").addEventListener("click", function() {
                modal.style.display = "none";
            });
            
            modal.querySelector(".save-btn").addEventListener("click", function() {
                const form = document.getElementById("create-test-form");
                const title = form.querySelector("#test-title").value;
                
                if (title) {
                    modal.style.display = "none";
                    showNotification(`Test "${title}" created successfully!`, "success");
                    
                    // Add new row to table (demo)
                    const testsTable = document.querySelector(".tests-table tbody");
                    if (testsTable) {
                        const newRow = document.createElement("tr");
                        newRow.className = "test-row";
                        newRow.setAttribute("data-id", Date.now().toString());
                        
                        const category = form.querySelector("#test-category").value;
                        const duration = form.querySelector("#test-duration").value;
                        
                        newRow.innerHTML = `
                            <td>
                                <input type="checkbox" class="test-checkbox" value="${Date.now()}">
                            </td>
                            <td class="test-title">${title}</td>
                            <td class="test-category">${category}</td>
                            <td>0</td>
                            <td class="test-duration">${duration} min</td>
                            <td>Just now</td>
                            <td>
                                <div class="actions">
                                    <button class="action-btn view-test-btn" data-id="${Date.now()}" title="View Test">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn edit-test-btn" data-id="${Date.now()}" title="Edit Test">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn manage-questions-btn" data-id="${Date.now()}" title="Manage Questions">
                                        <i class="fas fa-list-ul"></i>
                                    </button>
                                    <button class="action-btn delete-test-btn" data-id="${Date.now()}" title="Delete Test">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        `;
                        
                        testsTable.insertBefore(newRow, testsTable.firstChild);
                        
                        // Add event listeners to the new buttons
                        initializeTestActionButtons();
                        
                        // Update results count
                        const allRows = document.querySelectorAll(".test-row");
                        updateTestResultsCount(allRows.length);
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
        }
        
        // Reset form
        const form = document.getElementById("create-test-form");
        if (form) form.reset();
        
        // Show modal
        modal.style.display = "block";
    }
}
