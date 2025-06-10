// Quick Actions functionality
document.addEventListener("DOMContentLoaded", function() {
    // Initialize Add User button
    const addUserBtn = document.querySelector(".quick-action-add-user");
    if (addUserBtn) {
        addUserBtn.addEventListener("click", function() {
            showAddUserModal();
        });
    }

    // Initialize Upload Material button
    const uploadMaterialBtn = document.querySelector(".quick-action-upload-material");
    if (uploadMaterialBtn) {
        uploadMaterialBtn.addEventListener("click", function() {
            showUploadMaterialModal();
        });
    }

    // Initialize Create Test button
    const createTestBtn = document.querySelector(".quick-action-create-test");
    if (createTestBtn) {
        createTestBtn.addEventListener("click", function() {
            showCreateTestModal();
        });
    }

    // Initialize Add Video button
    const addVideoBtn = document.querySelector(".quick-action-add-video");
    if (addVideoBtn) {
        addVideoBtn.addEventListener("click", function() {
            showAddVideoModal();
        });
    }

    // Initialize Add Paper button
    const addPaperBtn = document.querySelector(".quick-action-add-paper");
    if (addPaperBtn) {
        addPaperBtn.addEventListener("click", function() {
            showAddPaperModal();
        });
    }
});

// Function to show Add User modal
function showAddUserModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById("add-user-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "add-user-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New User</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="add-user-form">
                        <div class="form-group">
                            <label for="user-fullname">Full Name</label>
                            <input type="text" id="user-fullname" name="user-fullname" required>
                        </div>
                        <div class="form-group">
                            <label for="user-email">Email</label>
                            <input type="email" id="user-email" name="user-email" required>
                        </div>
                        <div class="form-group">
                            <label for="user-phone">Phone Number</label>
                            <input type="tel" id="user-phone" name="user-phone">
                        </div>
                        <div class="form-group">
                            <label for="user-role">Role</label>
                            <select id="user-role" name="user-role" required>
                                <option value="">Select Role</option>
                                <option value="student">Student</option>
                                <option value="instructor">Instructor</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user-subscription">Subscription</label>
                            <select id="user-subscription" name="user-subscription">
                                <option value="">Select Subscription</option>
                                <option value="free">Free</option>
                                <option value="basic">Basic</option>
                                <option value="premium">Premium</option>
                                <option value="enterprise">Enterprise</option>
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
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="send-welcome-email" name="send-welcome-email" checked>
                                Send welcome email
                            </label>
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
        modal.querySelector(".close").addEventListener("click", function() {
            modal.style.display = "none";
        });
        
        modal.querySelector(".cancel-btn").addEventListener("click", function() {
            modal.style.display = "none";
        });
        
        modal.querySelector(".save-btn").addEventListener("click", function() {
            const form = document.getElementById("add-user-form");
            const fullname = form.querySelector("#user-fullname").value;
            const email = form.querySelector("#user-email").value;
            const password = form.querySelector("#user-password").value;
            const confirmPassword = form.querySelector("#user-confirm-password").value;
            
            if (!fullname || !email || !password || !confirmPassword) {
                showNotification("Please fill in all required fields", "error");
                return;
            }
            
            if (password !== confirmPassword) {
                showNotification("Passwords do not match", "error");
                return;
            }
            
            // In a real implementation, this would send an API request
            // For this demo, we'll just show a notification
            modal.style.display = "none";
            
            // Show success notification
            showNotification(`User ${fullname} added successfully!`, "success");
        });
        
        // Close when clicking outside
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
    }
    
    // Reset form
    const form = document.getElementById("add-user-form");
    if (form) form.reset();
    
    // Show modal
    modal.style.display = "block";
}

// Function to show Upload Material modal
function showUploadMaterialModal() {
    // Create modal if it doesn't exist
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
                            <select id="material-category" name="material-category" required>
                                <option value="">Select Category</option>
                                <option value="navigation">Navigation</option>
                                <option value="engineering">Engineering</option>
                                <option value="safety">Safety</option>
                                <option value="regulations">Regulations</option>
                                <option value="meteorology">Meteorology</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="material-description">Description</label>
                            <textarea id="material-description" name="material-description" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="material-file">File</label>
                            <input type="file" id="material-file" name="material-file" required>
                            <small>Supported formats: PDF, DOCX, PPTX (Max size: 50MB)</small>
                        </div>
                        <div class="form-group">
                            <label for="material-thumbnail">Thumbnail Image (Optional)</label>
                            <input type="file" id="material-thumbnail" name="material-thumbnail" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="material-access-level">Access Level</label>
                            <select id="material-access-level" name="material-access-level" required>
                                <option value="free">Free</option>
                                <option value="basic">Basic Subscription</option>
                                <option value="premium">Premium Subscription</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="material-featured" name="material-featured">
                                Featured Material
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="material-publish" name="material-publish" checked>
                                Publish Immediately
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                    <button type="button" class="btn btn-primary save-btn">Upload Material</button>
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
            const category = form.querySelector("#material-category").value;
            const file = form.querySelector("#material-file").value;
            
            if (!title || !category || !file) {
                showNotification("Please fill in all required fields", "error");
                return;
            }
            
            // In a real implementation, this would send an API request
            // For this demo, we'll just show a notification
            modal.style.display = "none";
            
            // Show loading notification
            showNotification("Uploading material...", "info");
            
            // Simulate upload delay
            setTimeout(() => {
                showNotification(`Study material "${title}" uploaded successfully!`, "success");
            }, 2000);
        });
        
        // Close when clicking outside
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
    }
    
    // Reset form
    const form = document.getElementById("upload-material-form");
    if (form) form.reset();
    
    // Show modal
    modal.style.display = "block";
}

// Function to show Create Test modal
function showCreateTestModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById("create-test-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "create-test-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content large">
                <div class="modal-header">
                    <h2>Create Mock Test</h2>
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
                            <select id="test-category" name="test-category" required>
                                <option value="">Select Category</option>
                                <option value="navigation">Navigation</option>
                                <option value="engineering">Engineering</option>
                                <option value="safety">Safety</option>
                                <option value="regulations">Regulations</option>
                                <option value="meteorology">Meteorology</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="test-description">Description</label>
                            <textarea id="test-description" name="test-description" rows="3"></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="test-duration">Duration (minutes)</label>
                                <input type="number" id="test-duration" name="test-duration" min="5" value="60" required>
                            </div>
                            <div class="form-group half">
                                <label for="test-passing-score">Passing Score (%)</label>
                                <input type="number" id="test-passing-score" name="test-passing-score" min="1" max="100" value="70" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="test-access-level">Access Level</label>
                                <select id="test-access-level" name="test-access-level" required>
                                    <option value="free">Free</option>
                                    <option value="basic">Basic Subscription</option>
                                    <option value="premium">Premium Subscription</option>
                                </select>
                            </div>
                            <div class="form-group half">
                                <label for="test-attempts">Max Attempts</label>
                                <input type="number" id="test-attempts" name="test-attempts" min="1" value="3">
                            </div>
                        </div>
                        
                        <div class="section-divider">
                            <h3>Questions</h3>
                            <button type="button" class="btn btn-outline btn-sm add-question-btn">
                                <i class="fas fa-plus"></i> Add Question
                            </button>
                        </div>
                        
                        <div class="questions-container">
                            <div class="question-item">
                                <div class="question-header">
                                    <h4>Question 1</h4>
                                    <div class="question-actions">
                                        <button type="button" class="action-btn delete-question-btn" title="Delete Question">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="question-1-text">Question Text</label>
                                    <textarea id="question-1-text" name="question-1-text" rows="2" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="question-1-type">Question Type</label>
                                    <select id="question-1-type" name="question-1-type" class="question-type-select" required>
                                        <option value="multiple-choice">Multiple Choice</option>
                                        <option value="true-false">True/False</option>
                                        <option value="short-answer">Short Answer</option>
                                    </select>
                                </div>
                                <div class="question-options">
                                    <div class="form-group">
                                        <label>Options</label>
                                        <div class="option-item">
                                            <input type="radio" name="question-1-correct" value="0" checked>
                                            <input type="text" name="question-1-option-1" placeholder="Option 1" required>
                                        </div>
                                        <div class="option-item">
                                            <input type="radio" name="question-1-correct" value="1">
                                            <input type="text" name="question-1-option-2" placeholder="Option 2" required>
                                        </div>
                                        <div class="option-item">
                                            <input type="radio" name="question-1-correct" value="2">
                                            <input type="text" name="question-1-option-3" placeholder="Option 3" required>
                                        </div>
                                        <div class="option-item">
                                            <input type="radio" name="question-1-correct" value="3">
                                            <input type="text" name="question-1-option-4" placeholder="Option 4" required>
                                        </div>
                                        <button type="button" class="btn btn-outline btn-sm add-option-btn">
                                            <i class="fas fa-plus"></i> Add Option
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="question-1-points">Points</label>
                                    <input type="number" id="question-1-points" name="question-1-points" min="1" value="1" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="test-randomize" name="test-randomize" checked>
                                Randomize Question Order
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="test-publish" name="test-publish" checked>
                                Publish Immediately
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                    <button type="button" class="btn btn-secondary preview-btn">Preview Test</button>
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
        
        modal.querySelector(".preview-btn").addEventListener("click", function() {
            const title = document.getElementById("test-title").value;
            
            if (!title) {
                showNotification("Please fill in the test title before previewing", "error");
                return;
            }
            
            showNotification(`Preview mode for test "${title}" - This would show a preview of the test`, "info");
        });
        
        modal.querySelector(".save-btn").addEventListener("click", function() {
            const form = document.getElementById("create-test-form");
            const title = form.querySelector("#test-title").value;
            const category = form.querySelector("#test-category").value;
            const questionText = form.querySelector("#question-1-text").value;
            
            if (!title || !category || !questionText) {
                showNotification("Please fill in all required fields", "error");
                return;
            }
            
            // In a real implementation, this would send an API request
            // For this demo, we'll just show a notification
            modal.style.display = "none";
            
            // Show loading notification
            showNotification("Creating test...", "info");
            
            // Simulate API delay
            setTimeout(() => {
                showNotification(`Mock test "${title}" created successfully!`, "success");
            }, 2000);
        });
        
        // Add Question button
        const addQuestionBtn = modal.querySelector(".add-question-btn");
        if (addQuestionBtn) {
            addQuestionBtn.addEventListener("click", function() {
                const questionsContainer = modal.querySelector(".questions-container");
                const questionCount = questionsContainer.querySelectorAll(".question-item").length + 1;
                
                const newQuestion = document.createElement("div");
                newQuestion.className = "question-item";
                newQuestion.innerHTML = `
                    <div class="question-header">
                        <h4>Question ${questionCount}</h4>
                        <div class="question-actions">
                            <button type="button" class="action-btn delete-question-btn" title="Delete Question">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="question-${questionCount}-text">Question Text</label>
                        <textarea id="question-${questionCount}-text" name="question-${questionCount}-text" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="question-${questionCount}-type">Question Type</label>
                        <select id="question-${questionCount}-type" name="question-${questionCount}-type" class="question-type-select" required>
                            <option value="multiple-choice">Multiple Choice</option>
                            <option value="true-false">True/False</option>
                            <option value="short-answer">Short Answer</option>
                        </select>
                    </div>
                    <div class="question-options">
                        <div class="form-group">
                            <label>Options</label>
                            <div class="option-item">
                                <input type="radio" name="question-${questionCount}-correct" value="0" checked>
                                <input type="text" name="question-${questionCount}-option-1" placeholder="Option 1" required>
                            </div>
                            <div class="option-item">
                                <input type="radio" name="question-${questionCount}-correct" value="1">
                                <input type="text" name="question-${questionCount}-option-2" placeholder="Option 2" required>
                            </div>
                            <div class="option-item">
                                <input type="radio" name="question-${questionCount}-correct" value="2">
                                <input type="text" name="question-${questionCount}-option-3" placeholder="Option 3" required>
                            </div>
                            <div class="option-item">
                                <input type="radio" name="question-${questionCount}-correct" value="3">
                                <input type="text" name="question-${questionCount}-option-4" placeholder="Option 4" required>
                            </div>
                            <button type="button" class="btn btn-outline btn-sm add-option-btn">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="question-${questionCount}-points">Points</label>
                        <input type="number" id="question-${questionCount}-points" name="question-${questionCount}-points" min="1" value="1" required>
                    </div>
                `;
                
                questionsContainer.appendChild(newQuestion);
                
                // Add event listeners for new question
                const deleteQuestionBtn = newQuestion.querySelector(".delete-question-btn");
                if (deleteQuestionBtn) {
                    deleteQuestionBtn.addEventListener("click", function() {
                        if (questionsContainer.querySelectorAll(".question-item").length > 1) {
                            newQuestion.remove();
                            
                            // Update question numbers
                            const questionItems = questionsContainer.querySelectorAll(".question-item");
                            questionItems.forEach((item, index) => {
                                item.querySelector("h4").textContent = `Question ${index + 1}`;
                            });
                        } else {
                            showNotification("You must have at least one question", "error");
                        }
                    });
                }
                
                const addOptionBtn = newQuestion.querySelector(".add-option-btn");
                if (addOptionBtn) {
                    addOptionBtn.addEventListener("click", function() {
                        const optionsContainer = this.closest(".question-options").querySelector(".form-group");
                        const optionCount = optionsContainer.querySelectorAll(".option-item").length + 1;
                        
                        const newOption = document.createElement("div");
                        newOption.className = "option-item";
                        newOption.innerHTML = `
                            <input type="radio" name="question-${questionCount}-correct" value="${optionCount - 1}">
                            <input type="text" name="question-${questionCount}-option-${optionCount}" placeholder="Option ${optionCount}" required>
                            <button type="button" class="action-btn delete-option-btn" title="Delete Option">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        
                        optionsContainer.insertBefore(newOption, this);
                        
                        // Add event listener for delete option button
                        const deleteOptionBtn = newOption.querySelector(".delete-option-btn");
                        if (deleteOptionBtn) {
                            deleteOptionBtn.addEventListener("click", function() {
                                if (optionsContainer.querySelectorAll(".option-item").length > 2) {
                                    newOption.remove();
                                } else {
                                    showNotification("You must have at least two options", "error");
                                }
                            });
                        }
                    });
                }
                
                const questionTypeSelect = newQuestion.querySelector(".question-type-select");
                if (questionTypeSelect) {
                    questionTypeSelect.addEventListener("change", function() {
                        const questionOptions = this.closest(".question-item").querySelector(".question-options");
                        
                        if (this.value === "true-false") {
                            // Set up true/false options
                            questionOptions.innerHTML = `
                                <div class="form-group">
                                    <label>Options</label>
                                    <div class="option-item">
                                        <input type="radio" name="question-${questionCount}-correct" value="0" checked>
                                        <input type="text" name="question-${questionCount}-option-1" value="True" readonly>
                                    </div>
                                    <div class="option-item">
                                        <input type="radio" name="question-${questionCount}-correct" value="1">
                                        <input type="text" name="question-${questionCount}-option-2" value="False" readonly>
                                    </div>
                                </div>
                            `;
                        } else if (this.value === "short-answer") {
                            // Set up short answer
                            questionOptions.innerHTML = `
                                <div class="form-group">
                                    <label for="question-${questionCount}-answer">Correct Answer</label>
                                    <input type="text" id="question-${questionCount}-answer" name="question-${questionCount}-answer" placeholder="Enter correct answer" required>
                                </div>
                            `;
                        } else {
                            // Reset to multiple choice
                            questionOptions.innerHTML = `
                                <div class="form-group">
                                    <label>Options</label>
                                    <div class="option-item">
                                        <input type="radio" name="question-${questionCount}-correct" value="0" checked>
                                        <input type="text" name="question-${questionCount}-option-1" placeholder="Option 1" required>
                                    </div>
                                    <div class="option-item">
                                        <input type="radio" name="question-${questionCount}-correct" value="1">
                                        <input type="text" name="question-${questionCount}-option-2" placeholder="Option 2" required>
                                    </div>
                                    <div class="option-item">
                                        <input type="radio" name="question-${questionCount}-correct" value="2">
                                        <input type="text" name="question-${questionCount}-option-3" placeholder="Option 3" required>
                                    </div>
                                    <div class="option-item">
                                        <input type="radio" name="question-${questionCount}-correct" value="3">
                                        <input type="text" name="question-${questionCount}-option-4" placeholder="Option 4" required>
                                    </div>
                                    <button type="button" class="btn btn-outline btn-sm add-option-btn">
                                        <i class="fas fa-plus"></i> Add Option
                                    </button>
                                </div>
                            `;
                            
                            // Add event listener for add option button
                            const newAddOptionBtn = questionOptions.querySelector(".add-option-btn");
                            if (newAddOptionBtn) {
                                newAddOptionBtn.addEventListener("click", function() {
                                    const optionsContainer = this.closest(".question-options").querySelector(".form-group");
                                    const optionCount = optionsContainer.querySelectorAll(".option-item").length + 1;
                                    
                                    const newOption = document.createElement("div");
                                    newOption.className = "option-item";
                                    newOption.innerHTML = `
                                        <input type="radio" name="question-${questionCount}-correct" value="${optionCount - 1}">
                                        <input type="text" name="question-${questionCount}-option-${optionCount}" placeholder="Option ${optionCount}" required>
                                        <button type="button" class="action-btn delete-option-btn" title="Delete Option">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    `;
                                    
                                    optionsContainer.insertBefore(newOption, this);
                                    
                                    // Add event listener for delete option button
                                    const deleteOptionBtn = newOption.querySelector(".delete-option-btn");
                                    if (deleteOptionBtn) {
                                        deleteOptionBtn.addEventListener("click", function() {
                                            if (optionsContainer.querySelectorAll(".option-item").length > 2) {
                                                newOption.remove();
                                            } else {
                                                showNotification("You must have at least two options", "error");
                                            }
                                        });
                                    }
                                });
                            }
                        }
                    });
                }
            });
        }
        
        // Delete Question button for first question
        const deleteQuestionBtn = modal.querySelector(".delete-question-btn");
        if (deleteQuestionBtn) {
            deleteQuestionBtn.addEventListener("click", function() {
                showNotification("You must have at least one question", "error");
            });
        }
        
        // Add Option button for first question
        const addOptionBtn = modal.querySelector(".add-option-btn");
        if (addOptionBtn) {
            addOptionBtn.addEventListener("click", function() {
                const optionsContainer = this.closest(".question-options").querySelector(".form-group");
                const optionCount = optionsContainer.querySelectorAll(".option-item").length + 1;
                
                const newOption = document.createElement("div");
                newOption.className = "option-item";
                newOption.innerHTML = `
                    <input type="radio" name="question-1-correct" value="${optionCount - 1}">
                    <input type="text" name="question-1-option-${optionCount}" placeholder="Option ${optionCount}" required>
                    <button type="button" class="action-btn delete-option-btn" title="Delete Option">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                optionsContainer.insertBefore(newOption, this);
                
                // Add event listener for delete option button
                const deleteOptionBtn = newOption.querySelector(".delete-option-btn");
                if (deleteOptionBtn) {
                    deleteOptionBtn.addEventListener("click", function() {
                        if (optionsContainer.querySelectorAll(".option-item").length > 2) {
                            newOption.remove();
                        } else {
                            showNotification("You must have at least two options", "error");
                        }
                    });
                }
            });
        }
        
        // Question type change for first question
        const questionTypeSelect = modal.querySelector(".question-type-select");
        if (questionTypeSelect) {
            questionTypeSelect.addEventListener("change", function() {
                const questionOptions = this.closest(".question-item").querySelector(".question-options");
                
                if (this.value === "true-false") {
                    // Set up true/false options
                    questionOptions.innerHTML = `
                        <div class="form-group">
                            <label>Options</label>
                            <div class="option-item">
                                <input type="radio" name="question-1-correct" value="0" checked>
                                <input type="text" name="question-1-option-1" value="True" readonly>
                            </div>
                            <div class="option-item">
                                <input type="radio" name="question-1-correct" value="1">
                                <input type="text" name="question-1-option-2" value="False" readonly>
                            </div>
                        </div>
                    `;
                } else if (this.value === "short-answer") {
                    // Set up short answer
                    questionOptions.innerHTML = `
                        <div class="form-group">
                            <label for="question-1-answer">Correct Answer</label>
                            <input type="text" id="question-1-answer" name="question-1-answer" placeholder="Enter correct answer" required>
                        </div>
                    `;
                } else {
                    // Reset to multiple choice
                    questionOptions.innerHTML = `
                        <div class="form-group">
                            <label>Options</label>
                            <div class="option-item">
                                <input type="radio" name="question-1-correct" value="0" checked>
                                <input type="text" name="question-1-option-1" placeholder="Option 1" required>
                            </div>
                            <div class="option-item">
                                <input type="radio" name="question-1-correct" value="1">
                                <input type="text" name="question-1-option-2" placeholder="Option 2" required>
                            </div>
                            <div class="option-item">
                                <input type="radio" name="question-1-correct" value="2">
                                <input type="text" name="question-1-option-3" placeholder="Option 3" required>
                            </div>
                            <div class="option-item">
                                <input type="radio" name="question-1-correct" value="3">
                                <input type="text" name="question-1-option-4" placeholder="Option 4" required>
                            </div>
                            <button type="button" class="btn btn-outline btn-sm add-option-btn">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                        </div>
                    `;
                    
                    // Add event listener for add option button
                    const newAddOptionBtn = questionOptions.querySelector(".add-option-btn");
                    if (newAddOptionBtn) {
                        newAddOptionBtn.addEventListener("click", function() {
                            const optionsContainer = this.closest(".question-options").querySelector(".form-group");
                            const optionCount = optionsContainer.querySelectorAll(".option-item").length + 1;
                            
                            const newOption = document.createElement("div");
                            newOption.className = "option-item";
                            newOption.innerHTML = `
                                <input type="radio" name="question-1-correct" value="${optionCount - 1}">
                                <input type="text" name="question-1-option-${optionCount}" placeholder="Option ${optionCount}" required>
                                <button type="button" class="action-btn delete-option-btn" title="Delete Option">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                            
                            optionsContainer.insertBefore(newOption, this);
                            
                            // Add event listener for delete option button
                            const deleteOptionBtn = newOption.querySelector(".delete-option-btn");
                            if (deleteOptionBtn) {
                                deleteOptionBtn.addEventListener("click", function() {
                                    if (optionsContainer.querySelectorAll(".option-item").length > 2) {
                                        newOption.remove();
                                    } else {
                                        showNotification("You must have at least two options", "error");
                                    }
                                });
                            }
                        });
                    }
                }
            });
        }
        
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
            
            .form-row {
                display: flex;
                gap: 20px;
            }
            
            .form-group.half {
                width: 50%;
            }
            
            .section-divider {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin: 20px 0;
                padding-bottom: 10px;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .section-divider h3 {
                margin: 0;
            }
            
            .question-item {
                background-color: #f9fafb;
                border: 1px solid #e5e7eb;
                border-radius: 4px;
                padding: 15px;
                margin-bottom: 20px;
            }
            
            .question-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
            }
            
            .question-header h4 {
                margin: 0;
            }
            
            .option-item {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
            }
            
            .option-item input[type="radio"] {
                margin-right: 10px;
            }
            
            .option-item input[type="text"] {
                flex-grow: 1;
            }
            
            .option-item .delete-option-btn {
                margin-left: 10px;
            }
            
            .btn-sm {
                padding: 5px 10px;
                font-size: 14px;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Reset form
    const form = document.getElementById("create-test-form");
    if (form) form.reset();
    
    // Show modal
    modal.style.display = "block";
}

// Function to show Add Video modal
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
                    <h2>Add Video</h2>
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
                            <select id="video-category" name="video-category" required>
                                <option value="">Select Category</option>
                                <option value="navigation">Navigation</option>
                                <option value="engineering">Engineering</option>
                                <option value="safety">Safety</option>
                                <option value="regulations">Regulations</option>
                                <option value="meteorology">Meteorology</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="video-description">Description</label>
                            <textarea id="video-description" name="video-description" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Upload Method</label>
                            <div class="upload-method-tabs">
                                <button type="button" class="tab-btn active" data-tab="file-upload">File Upload</button>
                                <button type="button" class="tab-btn" data-tab="youtube-link">YouTube Link</button>
                                <button type="button" class="tab-btn" data-tab="vimeo-link">Vimeo Link</button>
                            </div>
                            <div class="tab-content active" id="file-upload">
                                <div class="form-group">
                                    <label for="video-file">Video File</label>
                                    <input type="file" id="video-file" name="video-file" accept="video/*" required>
                                    <small>Supported formats: MP4, WebM, MOV (Max size: 500MB)</small>
                                </div>
                            </div>
                            <div class="tab-content" id="youtube-link">
                                <div class="form-group">
                                    <label for="youtube-url">YouTube URL</label>
                                    <input type="url" id="youtube-url" name="youtube-url" placeholder="https://www.youtube.com/watch?v=...">
                                </div>
                            </div>
                            <div class="tab-content" id="vimeo-link">
                                <div class="form-group">
                                    <label for="vimeo-url">Vimeo URL</label>
                                    <input type="url" id="vimeo-url" name="vimeo-url" placeholder="https://vimeo.com/...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="video-thumbnail">Thumbnail Image (Optional)</label>
                            <input type="file" id="video-thumbnail" name="video-thumbnail" accept="image/*">
                            <small>If not provided, a thumbnail will be generated automatically</small>
                        </div>
                        <div class="form-group">
                            <label for="video-duration">Duration (minutes)</label>
                            <input type="number" id="video-duration" name="video-duration" min="1" step="0.1">
                            <small>Optional - will be detected automatically for uploaded files</small>
                        </div>
                        <div class="form-group">
                            <label for="video-access-level">Access Level</label>
                            <select id="video-access-level" name="video-access-level" required>
                                <option value="free">Free</option>
                                <option value="basic">Basic Subscription</option>
                                <option value="premium">Premium Subscription</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="video-featured" name="video-featured">
                                Featured Video
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="video-publish" name="video-publish" checked>
                                Publish Immediately
                            </label>
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
            const category = form.querySelector("#video-category").value;
            
            // Check active tab
            const activeTab = modal.querySelector(".tab-btn.active").getAttribute("data-tab");
            let isValid = false;
            
            if (activeTab === "file-upload") {
                isValid = title && category && form.querySelector("#video-file").value;
            } else if (activeTab === "youtube-link") {
                isValid = title && category && form.querySelector("#youtube-url").value;
            } else if (activeTab === "vimeo-link") {
                isValid = title && category && form.querySelector("#vimeo-url").value;
            }
            
            if (!isValid) {
                showNotification("Please fill in all required fields", "error");
                return;
            }
            
            // In a real implementation, this would send an API request
            // For this demo, we'll just show a notification
            modal.style.display = "none";
            
            // Show loading notification
            showNotification("Uploading video...", "info");
            
            // Simulate upload delay
            setTimeout(() => {
                showNotification(`Video "${title}" added successfully!`, "success");
            }, 3000);
        });
        
        // Tab buttons
        const tabBtns = modal.querySelectorAll(".tab-btn");
        if (tabBtns.length > 0) {
            tabBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                    // Remove active class from all buttons and content
                    tabBtns.forEach(b => b.classList.remove("active"));
                    modal.querySelectorAll(".tab-content").forEach(c => c.classList.remove("active"));
                    
                    // Add active class to clicked button and corresponding content
                    this.classList.add("active");
                    const tabId = this.getAttribute("data-tab");
                    modal.querySelector(`#${tabId}`).classList.add("active");
                    
                    // Update required fields
                    if (tabId === "file-upload") {
                        modal.querySelector("#video-file").required = true;
                        modal.querySelector("#youtube-url").required = false;
                        modal.querySelector("#vimeo-url").required = false;
                    } else if (tabId === "youtube-link") {
                        modal.querySelector("#video-file").required = false;
                        modal.querySelector("#youtube-url").required = true;
                        modal.querySelector("#vimeo-url").required = false;
                    } else if (tabId === "vimeo-link") {
                        modal.querySelector("#video-file").required = false;
                        modal.querySelector("#youtube-url").required = false;
                        modal.querySelector("#vimeo-url").required = true;
                    }
                });
            });
        }
        
        // Close when clicking outside
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
        
        // Add styles
        const style = document.createElement("style");
        style.textContent = `
            .upload-method-tabs {
                display: flex;
                margin-bottom: 15px;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .tab-btn {
                padding: 8px 15px;
                background: none;
                border: none;
                border-bottom: 2px solid transparent;
                cursor: pointer;
                font-weight: 500;
                color: #6b7280;
            }
            
            .tab-btn:hover {
                color: #3b82f6;
            }
            
            .tab-btn.active {
                color: #3b82f6;
                border-bottom-color: #3b82f6;
            }
            
            .tab-content {
                display: none;
            }
            
            .tab-content.active {
                display: block;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Reset form
    const form = document.getElementById("add-video-form");
    if (form) form.reset();
    
    // Reset tabs
    const tabBtns = modal.querySelectorAll(".tab-btn");
    tabBtns.forEach(btn => btn.classList.remove("active"));
    modal.querySelector('[data-tab="file-upload"]').classList.add("active");
    
    modal.querySelectorAll(".tab-content").forEach(content => content.classList.remove("active"));
    modal.querySelector("#file-upload").classList.add("active");
    
    // Show modal
    modal.style.display = "block";
}

// Function to show Add Paper modal
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
                    <h2>Add Exam Paper</h2>
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
                            <select id="paper-category" name="paper-category" required>
                                <option value="">Select Category</option>
                                <option value="navigation">Navigation</option>
                                <option value="engineering">Engineering</option>
                                <option value="safety">Safety</option>
                                <option value="regulations">Regulations</option>
                                <option value="meteorology">Meteorology</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="paper-exam-board">Exam Board</label>
                            <select id="paper-exam-board" name="paper-exam-board" required>
                                <option value="">Select Exam Board</option>
                                <option value="imu">International Maritime University</option>
                                <option value="mca">Maritime and Coastguard Agency</option>
                                <option value="stcw">STCW</option>
                                <option value="uscg">US Coast Guard</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="paper-year">Year</label>
                            <select id="paper-year" name="paper-year" required>
                                <option value="">Select Year</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                                <option value="2021">2021</option>
                                <option value="2020">2020</option>
                                <option value="2019">2019</option>
                                <option value="2018">2018</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="paper-description">Description</label>
                            <textarea id="paper-description" name="paper-description" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="paper-file">Paper File</label>
                            <input type="file" id="paper-file" name="paper-file" accept=".pdf,.doc,.docx" required>
                            <small>Supported formats: PDF, DOC, DOCX (Max size: 50MB)</small>
                        </div>
                        <div class="form-group">
                            <label for="paper-answer-file">Answer Key File (Optional)</label>
                            <input type="file" id="paper-answer-file" name="paper-answer-file" accept=".pdf,.doc,.docx">
                        </div>
                        <div class="form-group">
                            <label for="paper-access-level">Access Level</label>
                            <select id="paper-access-level" name="paper-access-level" required>
                                <option value="free">Free</option>
                                <option value="basic">Basic Subscription</option>
                                <option value="premium">Premium Subscription</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="paper-featured" name="paper-featured">
                                Featured Paper
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="paper-publish" name="paper-publish" checked>
                                Publish Immediately
                            </label>
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
            const category = form.querySelector("#paper-category").value;
            const examBoard = form.querySelector("#paper-exam-board").value;
            const year = form.querySelector("#paper-year").value;
            const file = form.querySelector("#paper-file").value;
            
            if (!title || !category || !examBoard || !year || !file) {
                showNotification("Please fill in all required fields", "error");
                return;
            }
            
            // In a real implementation, this would send an API request
            // For this demo, we'll just show a notification
            modal.style.display = "none";
            
            // Show loading notification
            showNotification("Uploading exam paper...", "info");
            
            // Simulate upload delay
            setTimeout(() => {
                showNotification(`Exam paper "${title}" added successfully!`, "success");
            }, 2000);
        });
        
        // Close when clicking outside
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
    }
    
    // Reset form
    const form = document.getElementById("add-paper-form");
    if (form) form.reset();
    
    // Show modal
    modal.style.display = "block";
}
