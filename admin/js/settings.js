// Settings page functionality
document.addEventListener("DOMContentLoaded", function() {
    // Initialize settings form
    initializeSettingsForm();
    
    // Initialize profile settings
    initializeProfileSettings();
    
    // Initialize notification settings
    initializeNotificationSettings();
    
    // Initialize security settings
    initializeSecuritySettings();
    
    // Initialize system settings
    initializeSystemSettings();
    
    // Initialize backup and restore
    initializeBackupRestore();
});

// Function to initialize settings form
function initializeSettingsForm() {
    const settingsForm = document.querySelector("#settings-form");
    if (settingsForm) {
        settingsForm.addEventListener("submit", function(e) {
            e.preventDefault();
            saveSettings();
        });
    }
    
    // Save settings button
    const saveSettingsBtn = document.querySelector(".save-settings-btn");
    if (saveSettingsBtn) {
        saveSettingsBtn.addEventListener("click", function() {
            saveSettings();
        });
    }
    
    // Reset settings button
    const resetSettingsBtn = document.querySelector(".reset-settings-btn");
    if (resetSettingsBtn) {
        resetSettingsBtn.addEventListener("click", function() {
            resetSettings();
        });
    }
}

// Function to save settings
function saveSettings() {
    console.log("Saving settings...");
    
    // In a real implementation, this would send an API request
    // For this demo, we'll just show a notification
    
    // Show loading state
    const saveBtn = document.querySelector(".save-settings-btn");
    if (saveBtn) {
        const originalText = saveBtn.textContent;
        saveBtn.disabled = true;
        saveBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Saving...`;
        
        // Simulate API delay
        setTimeout(() => {
            saveBtn.disabled = false;
            saveBtn.textContent = originalText;
            
            // Show notification
            showNotification("Settings saved successfully!", "success");
        }, 1500);
    }
}

// Function to reset settings
function resetSettings() {
    console.log("Resetting settings...");
    
    // Show confirmation modal
    showConfirmationModal(
        "Are you sure you want to reset all settings to default values?",
        function() {
            // In a real implementation, this would send an API request
            // For this demo, we'll just reset form fields and show a notification
            
            // Reset form fields
            const settingsForm = document.querySelector("#settings-form");
            if (settingsForm) {
                settingsForm.reset();
            }
            
            // Reset toggle switches
            const toggleSwitches = document.querySelectorAll(".toggle-switch input[type='checkbox']");
            toggleSwitches.forEach(toggle => {
                toggle.checked = toggle.getAttribute("data-default") === "true";
            });
            
            // Show notification
            showNotification("Settings reset to default values", "info");
        },
        "Reset Settings",
        "Reset",
        "btn-warning"
    );
}

// Function to initialize profile settings
function initializeProfileSettings() {
    // Profile image upload
    const profileImageUpload = document.querySelector("#profile-image-upload");
    const profileImagePreview = document.querySelector(".profile-image-preview");
    
    if (profileImageUpload && profileImagePreview) {
        profileImageUpload.addEventListener("change", function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    profileImagePreview.style.backgroundImage = `url('${e.target.result}')`;
                    showNotification("Profile image updated", "success");
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Upload profile image button
    const uploadProfileImageBtn = document.querySelector(".upload-profile-image-btn");
    if (uploadProfileImageBtn && profileImageUpload) {
        uploadProfileImageBtn.addEventListener("click", function() {
            profileImageUpload.click();
        });
    }
    
    // Remove profile image button
    const removeProfileImageBtn = document.querySelector(".remove-profile-image-btn");
    if (removeProfileImageBtn && profileImagePreview) {
        removeProfileImageBtn.addEventListener("click", function() {
            profileImagePreview.style.backgroundImage = "url('https://via.placeholder.com/150')";
            
            // Reset file input
            if (profileImageUpload) {
                profileImageUpload.value = "";
            }
            
            showNotification("Profile image removed", "info");
        });
    }
}

// Function to initialize notification settings
function initializeNotificationSettings() {
    // Toggle switches
    const toggleSwitches = document.querySelectorAll(".notification-toggle");
    if (toggleSwitches.length > 0) {
        toggleSwitches.forEach(toggle => {
            toggle.addEventListener("change", function() {
                const notificationType = this.getAttribute("data-notification-type");
                const isEnabled = this.checked;
                
                console.log(`${notificationType} notifications ${isEnabled ? "enabled" : "disabled"}`);
                
                // In a real implementation, this would send an API request
                // For this demo, we'll just show a notification
                showNotification(`${notificationType} notifications ${isEnabled ? "enabled" : "disabled"}`, "info");
            });
        });
    }
    
    // Enable all button
    const enableAllBtn = document.querySelector(".enable-all-btn");
    if (enableAllBtn) {
        enableAllBtn.addEventListener("click", function() {
            const toggleSwitches = document.querySelectorAll(".notification-toggle");
            toggleSwitches.forEach(toggle => {
                toggle.checked = true;
            });
            
            showNotification("All notifications enabled", "success");
        });
    }
    
    // Disable all button
    const disableAllBtn = document.querySelector(".disable-all-btn");
    if (disableAllBtn) {
        disableAllBtn.addEventListener("click", function() {
            const toggleSwitches = document.querySelectorAll(".notification-toggle");
            toggleSwitches.forEach(toggle => {
                toggle.checked = false;
            });
            
            showNotification("All notifications disabled", "info");
        });
    }
}

// Function to initialize security settings
function initializeSecuritySettings() {
    // Change password button
    const changePasswordBtn = document.querySelector(".change-password-btn");
    if (changePasswordBtn) {
        changePasswordBtn.addEventListener("click", function() {
            showChangePasswordModal();
        });
    }
    
    // Enable 2FA button
    const enable2FABtn = document.querySelector(".enable-2fa-btn");
    if (enable2FABtn) {
        enable2FABtn.addEventListener("click", function() {
            show2FAModal();
        });
    }
    
    // Session management
    const logoutSessionBtns = document.querySelectorAll(".logout-session-btn");
    if (logoutSessionBtns.length > 0) {
        logoutSessionBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                const sessionId = this.getAttribute("data-session-id");
                const deviceName = this.closest("tr").querySelector(".device-name").textContent;
                
                showConfirmationModal(
                    `Are you sure you want to log out from ${deviceName}?`,
                    () => {
                        // In a real implementation, this would send an API request
                        // For this demo, we'll just remove the row and show a notification
                        const row = this.closest("tr");
                        if (row) {
                            row.remove();
                        }
                        
                        showNotification(`Logged out from ${deviceName}`, "success");
                    },
                    "Log Out Session",
                    "Log Out",
                    "btn-warning"
                );
            });
        });
    }
    
    // Log out all sessions button
    const logoutAllBtn = document.querySelector(".logout-all-btn");
    if (logoutAllBtn) {
        logoutAllBtn.addEventListener("click", function() {
            showConfirmationModal(
                "Are you sure you want to log out from all other devices?",
                () => {
                    // In a real implementation, this would send an API request
                    // For this demo, we'll just remove all rows except current and show a notification
                    const sessionRows = document.querySelectorAll(".session-row:not(.current-session)");
                    sessionRows.forEach(row => {
                        row.remove();
                    });
                    
                    showNotification("Logged out from all other devices", "success");
                },
                "Log Out All Sessions",
                "Log Out All",
                "btn-danger"
            );
        });
    }
}

// Function to show change password modal
function showChangePasswordModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById("change-password-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "change-password-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Change Password</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="change-password-form">
                        <div class="form-group">
                            <label for="current-password">Current Password</label>
                            <input type="password" id="current-password" name="current-password" required>
                        </div>
                        <div class="form-group">
                            <label for="new-password">New Password</label>
                            <input type="password" id="new-password" name="new-password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirm New Password</label>
                            <input type="password" id="confirm-password" name="confirm-password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                    <button type="button" class="btn btn-primary save-btn">Change Password</button>
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
            const currentPassword = document.getElementById("current-password").value;
            const newPassword = document.getElementById("new-password").value;
            const confirmPassword = document.getElementById("confirm-password").value;
            
            if (!currentPassword || !newPassword || !confirmPassword) {
                showNotification("Please fill in all fields", "error");
                return;
            }
            
            if (newPassword !== confirmPassword) {
                showNotification("New passwords do not match", "error");
                return;
            }
            
            // In a real implementation, this would send an API request
            // For this demo, we'll just show a notification
            modal.style.display = "none";
            
            // Show loading notification
            showNotification("Changing password...", "info");
            
            // Simulate API delay
            setTimeout(() => {
                showNotification("Password changed successfully!", "success");
            }, 1500);
        });
        
        // Close when clicking outside
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
    }
    
    // Reset form
    const form = document.getElementById("change-password-form");
    if (form) form.reset();
    
    // Show modal
    modal.style.display = "block";
}

// Function to show 2FA modal
function show2FAModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById("2fa-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "2fa-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Two-Factor Authentication</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="setup-steps">
                        <div class="step active" id="step-1">
                            <h3>Step 1: Scan QR Code</h3>
                            <p>Scan this QR code with your authenticator app (Google Authenticator, Authy, etc.)</p>
                            <div class="qr-code">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=otpauth://totp/MarineMonks:admin@marinemonks.com?secret=JBSWY3DPEHPK3PXP&issuer=MarineMonks" alt="QR Code">
                            </div>
                            <p>Or enter this code manually: <strong>JBSWY3DPEHPK3PXP</strong></p>
                            <div class="step-actions">
                                <button type="button" class="btn btn-primary next-step-btn" data-step="1">Next</button>
                            </div>
                        </div>
                        <div class="step" id="step-2">
                            <h3>Step 2: Verify Code</h3>
                            <p>Enter the 6-digit code from your authenticator app</p>
                            <div class="form-group">
                                <input type="text" id="verification-code" name="verification-code" maxlength="6" placeholder="000000" required>
                            </div>
                            <div class="step-actions">
                                <button type="button" class="btn btn-outline prev-step-btn" data-step="2">Back</button>
                                <button type="button" class="btn btn-primary verify-code-btn">Verify</button>
                            </div>
                        </div>
                        <div class="step" id="step-3">
                            <h3>Two-Factor Authentication Enabled!</h3>
                            <p>Your account is now more secure with 2FA.</p>
                            <div class="recovery-codes">
                                <h4>Recovery Codes</h4>
                                <p>Save these recovery codes in a safe place. If you lose your device, you can use these codes to access your account.</p>
                                <ul>
                                    <li>ABCD-EFGH-IJKL</li>
                                    <li>MNOP-QRST-UVWX</li>
                                    <li>YZ12-3456-7890</li>
                                    <li>ABCD-EFGH-1234</li>
                                    <li>5678-IJKL-MNOP</li>
                                </ul>
                                <button type="button" class="btn btn-outline download-codes-btn">Download Codes</button>
                            </div>
                            <div class="step-actions">
                                <button type="button" class="btn btn-primary finish-btn">Finish</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
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
        
        // Next step buttons
        const nextStepBtns = modal.querySelectorAll(".next-step-btn");
        nextStepBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                const currentStep = parseInt(this.getAttribute("data-step"));
                const nextStep = currentStep + 1;
                
                // Hide current step
                document.getElementById(`step-${currentStep}`).classList.remove("active");
                
                // Show next step
                document.getElementById(`step-${nextStep}`).classList.add("active");
            });
        });
        
        // Previous step buttons
        const prevStepBtns = modal.querySelectorAll(".prev-step-btn");
        prevStepBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                const currentStep = parseInt(this.getAttribute("data-step"));
                const prevStep = currentStep - 1;
                
                // Hide current step
                document.getElementById(`step-${currentStep}`).classList.remove("active");
                
                // Show previous step
                document.getElementById(`step-${prevStep}`).classList.add("active");
            });
        });
        
        // Verify code button
        const verifyCodeBtn = modal.querySelector(".verify-code-btn");
        if (verifyCodeBtn) {
            verifyCodeBtn.addEventListener("click", function() {
                const verificationCode = document.getElementById("verification-code").value;
                
                if (!verificationCode || verificationCode.length !== 6) {
                    showNotification("Please enter a valid 6-digit code", "error");
                    return;
                }
                
                // In a real implementation, this would verify the code with the server
                // For this demo, we'll just proceed to the next step
                
                // Hide step 2
                document.getElementById("step-2").classList.remove("active");
                
                // Show step 3
                document.getElementById("step-3").classList.add("active");
            });
        }
        
        // Download codes button
        const downloadCodesBtn = modal.querySelector(".download-codes-btn");
        if (downloadCodesBtn) {
            downloadCodesBtn.addEventListener("click", function() {
                // In a real implementation, this would generate and download a file
                // For this demo, we'll just show a notification
                showNotification("Recovery codes downloaded", "success");
            });
        }
        
        // Finish button
        const finishBtn = modal.querySelector(".finish-btn");
        if (finishBtn) {
            finishBtn.addEventListener("click", function() {
                modal.style.display = "none";
                
                // Update 2FA status
                const twoFAStatus = document.querySelector(".two-fa-status");
                if (twoFAStatus) {
                    twoFAStatus.textContent = "Enabled";
                    twoFAStatus.className = "two-fa-status status-active";
                }
                
                // Update button
                const enable2FABtn = document.querySelector(".enable-2fa-btn");
                if (enable2FABtn) {
                    enable2FABtn.textContent = "Disable 2FA";
                    enable2FABtn.className = "btn btn-outline disable-2fa-btn";
                    
                    // Update event listener
                    enable2FABtn.removeEventListener("click", show2FAModal);
                    enable2FABtn.addEventListener("click", function() {
                        showConfirmationModal(
                            "Are you sure you want to disable two-factor authentication? This will make your account less secure.",
                            () => {
                                // Update 2FA status
                                const twoFAStatus = document.querySelector(".two-fa-status");
                                if (twoFAStatus) {
                                    twoFAStatus.textContent = "Disabled";
                                    twoFAStatus.className = "two-fa-status status-inactive";
                                }
                                
                                // Update button
                                this.textContent = "Enable 2FA";
                                this.className = "btn btn-primary enable-2fa-btn";
                                
                                // Update event listener
                                this.removeEventListener("click", arguments.callee);
                                this.addEventListener("click", show2FAModal);
                                
                                showNotification("Two-factor authentication disabled", "info");
                            },
                            "Disable 2FA",
                            "Disable",
                            "btn-danger"
                        );
                    });
                }
                
                showNotification("Two-factor authentication enabled successfully!", "success");
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
            .setup-steps .step {
                display: none;
            }
            
            .setup-steps .step.active {
                display: block;
            }
            
            .qr-code {
                text-align: center;
                margin: 20px 0;
            }
            
            .qr-code img {
                max-width: 200px;
            }
            
            .step-actions {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }
            
            .step-actions button:only-child {
                margin-left: auto;
            }
            
            #verification-code {
                font-size: 24px;
                letter-spacing: 5px;
                text-align: center;
            }
            
            .recovery-codes {
                background-color: #f9fafb;
                border: 1px solid #e5e7eb;
                border-radius: 4px;
                padding: 15px;
                margin: 20px 0;
            }
            
            .recovery-codes ul {
                list-style: none;
                padding: 0;
                margin: 10px 0;
            }
            
            .recovery-codes li {
                font-family: monospace;
                font-size: 16px;
                margin-bottom: 5px;
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
    
    // Reset to first step
    const steps = document.querySelectorAll(".step");
    steps.forEach(step => step.classList.remove("active"));
    document.getElementById("step-1").classList.add("active");
    
    // Reset verification code
    const verificationCodeInput = document.getElementById("verification-code");
    if (verificationCodeInput) verificationCodeInput.value = "";
    
    // Show modal
    modal.style.display = "block";
}

// Function to initialize system settings
function initializeSystemSettings() {
    // Theme selector
    const themeSelector = document.querySelector("#theme-selector");
    if (themeSelector) {
        themeSelector.addEventListener("change", function() {
            const theme = this.value;
            changeTheme(theme);
        });
    }
    
    // Language selector
    const languageSelector = document.querySelector("#language-selector");
    if (languageSelector) {
        languageSelector.addEventListener("change", function() {
            const language = this.value;
            changeLanguage(language);
        });
    }
    
    // Timezone selector
    const timezoneSelector = document.querySelector("#timezone-selector");
    if (timezoneSelector) {
        timezoneSelector.addEventListener("change", function() {
            const timezone = this.value;
            changeTimezone(timezone);
        });
    }
    
    // Date format selector
    const dateFormatSelector = document.querySelector("#date-format-selector");
    if (dateFormatSelector) {
        dateFormatSelector.addEventListener("change", function() {
            const dateFormat = this.value;
            changeDateFormat(dateFormat);
        });
    }
    
    // System maintenance button
    const maintenanceBtn = document.querySelector(".maintenance-btn");
    if (maintenanceBtn) {
        maintenanceBtn.addEventListener("click", function() {
            showMaintenanceModal();
        });
    }
    
    // Clear cache button
    const clearCacheBtn = document.querySelector(".clear-cache-btn");
    if (clearCacheBtn) {
        clearCacheBtn.addEventListener("click", function() {
            showConfirmationModal(
                "Are you sure you want to clear the system cache?",
                () => {
                    // In a real implementation, this would send an API request
                    // For this demo, we'll just show a notification
                    
                    // Show loading notification
                    showNotification("Clearing cache...", "info");
                    
                    // Simulate API delay
                    setTimeout(() => {
                        showNotification("Cache cleared successfully!", "success");
                    }, 2000);
                },
                "Clear Cache",
                "Clear",
                "btn-warning"
            );
        });
    }
}

// Function to change theme
function changeTheme(theme) {
    console.log(`Changing theme to ${theme}`);
    
    // In a real implementation, this would update the theme
    // For this demo, we'll just show a notification
    showNotification(`Theme changed to ${theme}`, "success");
    
    // Simulate theme change
    document.documentElement.className = theme;
}

// Function to change language
function changeLanguage(language) {
    console.log(`Changing language to ${language}`);
    
    // In a real implementation, this would update the language
    // For this demo, we'll just show a notification
    showNotification(`Language changed to ${language}`, "success");
}

// Function to change timezone
function changeTimezone(timezone) {
    console.log(`Changing timezone to ${timezone}`);
    
    // In a real implementation, this would update the timezone
    // For this demo, we'll just show a notification
    showNotification(`Timezone changed to ${timezone}`, "success");
}

// Function to change date format
function changeDateFormat(dateFormat) {
    console.log(`Changing date format to ${dateFormat}`);
    
    // In a real implementation, this would update the date format
    // For this demo, we'll just show a notification
    showNotification(`Date format changed to ${dateFormat}`, "success");
}

// Function to show maintenance modal
function showMaintenanceModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById("maintenance-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "maintenance-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>System Maintenance</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="maintenance-options">
                        <div class="maintenance-option">
                            <h3>Database Optimization</h3>
                            <p>Optimize database tables and indexes for better performance.</p>
                            <button type="button" class="btn btn-primary optimize-db-btn">Optimize Database</button>
                        </div>
                        <div class="maintenance-option">
                            <h3>File Storage Cleanup</h3>
                            <p>Remove temporary files and optimize storage usage.</p>
                            <button type="button" class="btn btn-primary cleanup-storage-btn">Cleanup Storage</button>
                        </div>
                        <div class="maintenance-option">
                            <h3>System Updates</h3>
                            <p>Check for and install system updates.</p>
                            <button type="button" class="btn btn-primary check-updates-btn">Check for Updates</button>
                        </div>
                        <div class="maintenance-option">
                            <h3>Error Logs</h3>
                            <p>View and download system error logs.</p>
                            <button type="button" class="btn btn-primary view-logs-btn">View Logs</button>
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
        
        // Optimize database button
        const optimizeDbBtn = modal.querySelector(".optimize-db-btn");
        if (optimizeDbBtn) {
            optimizeDbBtn.addEventListener("click", function() {
                // In a real implementation, this would send an API request
                // For this demo, we'll just show a notification
                
                // Update button state
                const originalText = this.textContent;
                this.disabled = true;
                this.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Optimizing...`;
                
                // Simulate API delay
                setTimeout(() => {
                    this.disabled = false;
                    this.textContent = originalText;
                    
                    showNotification("Database optimization completed successfully!", "success");
                }, 3000);
            });
        }
        
        // Cleanup storage button
        const cleanupStorageBtn = modal.querySelector(".cleanup-storage-btn");
        if (cleanupStorageBtn) {
            cleanupStorageBtn.addEventListener("click", function() {
                // In a real implementation, this would send an API request
                // For this demo, we'll just show a notification
                
                // Update button state
                const originalText = this.textContent;
                this.disabled = true;
                this.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Cleaning...`;
                
                // Simulate API delay
                setTimeout(() => {
                    this.disabled = false;
                    this.textContent = originalText;
                    
                    showNotification("Storage cleanup completed successfully! 1.2GB freed.", "success");
                }, 2500);
            });
        }
        
        // Check for updates button
        const checkUpdatesBtn = modal.querySelector(".check-updates-btn");
        if (checkUpdatesBtn) {
            checkUpdatesBtn.addEventListener("click", function() {
                // In a real implementation, this would send an API request
                // For this demo, we'll just show a notification
                
                // Update button state
                const originalText = this.textContent;
                this.disabled = true;
                this.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Checking...`;
                
                // Simulate API delay
                setTimeout(() => {
                    this.disabled = false;
                    this.textContent = "Install Updates";
                    
                    showNotification("New updates available! Click 'Install Updates' to proceed.", "info");
                    
                    // Update event listener
                    this.removeEventListener("click", arguments.callee);
                    this.addEventListener("click", function() {
                        // Update button state
                        this.disabled = true;
                        this.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Installing...`;
                        
                        // Simulate API delay
                        setTimeout(() => {
                            this.disabled = false;
                            this.textContent = originalText;
                            
                            showNotification("System updated successfully! New version: 2.5.1", "success");
                            
                            // Reset event listener
                            this.removeEventListener("click", arguments.callee);
                            this.addEventListener("click", arguments.callee.caller);
                        }, 4000);
                    });
                }, 2000);
            });
        }
        
        // View logs button
        const viewLogsBtn = modal.querySelector(".view-logs-btn");
        if (viewLogsBtn) {
            viewLogsBtn.addEventListener("click", function() {
                // In a real implementation, this would fetch and display logs
                // For this demo, we'll show a logs modal
                showLogsModal();
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
            .maintenance-options {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }
            
            .maintenance-option {
                background-color: #f9fafb;
                border: 1px solid #e5e7eb;
                border-radius: 4px;
                padding: 15px;
            }
            
            .maintenance-option h3 {
                margin-top: 0;
            }
            
            .maintenance-option button {
                width: 100%;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Show modal
    modal.style.display = "block";
}

// Function to show logs modal
function showLogsModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById("logs-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "logs-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content large">
                <div class="modal-header">
                    <h2>System Logs</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="logs-filter">
                        <div class="form-group">
                            <label for="log-level">Log Level</label>
                            <select id="log-level">
                                <option value="all">All Levels</option>
                                <option value="error">Error</option>
                                <option value="warning">Warning</option>
                                <option value="info">Info</option>
                                <option value="debug">Debug</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="log-date">Date</label>
                            <input type="date" id="log-date">
                        </div>
                        <button type="button" class="btn btn-primary filter-logs-btn">Filter</button>
                        <button type="button" class="btn btn-outline reset-filter-btn">Reset</button>
                    </div>
                    <div class="logs-container">
                        <pre class="logs-output">
[2025-06-03 10:15:23] [INFO] System started
[2025-06-03 10:15:24] [INFO] Database connection established
[2025-06-03 10:15:25] [INFO] Cache initialized
[2025-06-03 10:15:26] [INFO] User admin logged in
[2025-06-03 10:30:45] [WARNING] High memory usage detected (85%)
[2025-06-03 10:45:12] [ERROR] Failed to connect to email server
[2025-06-03 11:05:33] [INFO] New user registered: john.doe@example.com
[2025-06-03 11:10:22] [INFO] Payment processed successfully: ORDER-12345
[2025-06-03 11:15:45] [WARNING] Slow database query detected (query_id: 5432)
[2025-06-03 11:30:18] [INFO] File uploaded: presentation.pdf
[2025-06-03 11:45:22] [INFO] User john.doe@example.com logged in
[2025-06-03 12:00:01] [INFO] Daily backup started
[2025-06-03 12:05:33] [INFO] Daily backup completed successfully
[2025-06-03 12:30:45] [ERROR] Failed to process payment: ORDER-12346
[2025-06-03 12:45:12] [INFO] User john.doe@example.com logged out
[2025-06-03 13:05:33] [INFO] New subscription created: SUB-7890
[2025-06-03 13:10:22] [WARNING] API rate limit approaching (95%)
[2025-06-03 13:15:45] [INFO] User admin logged out
[2025-06-03 13:30:18] [DEBUG] Cache hit ratio: 78.5%
[2025-06-03 13:45:22] [INFO] System health check passed
                        </pre>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline close-btn">Close</button>
                    <button type="button" class="btn btn-primary download-logs-btn">Download Logs</button>
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
        
        // Filter logs button
        const filterLogsBtn = modal.querySelector(".filter-logs-btn");
        if (filterLogsBtn) {
            filterLogsBtn.addEventListener("click", function() {
                const logLevel = document.getElementById("log-level").value;
                const logDate = document.getElementById("log-date").value;
                
                filterLogs(logLevel, logDate);
            });
        }
        
        // Reset filter button
        const resetFilterBtn = modal.querySelector(".reset-filter-btn");
        if (resetFilterBtn) {
            resetFilterBtn.addEventListener("click", function() {
                document.getElementById("log-level").value = "all";
                document.getElementById("log-date").value = "";
                
                filterLogs("all", "");
            });
        }
        
        // Download logs button
        const downloadLogsBtn = modal.querySelector(".download-logs-btn");
        if (downloadLogsBtn) {
            downloadLogsBtn.addEventListener("click", function() {
                // In a real implementation, this would generate and download a file
                // For this demo, we'll just show a notification
                showNotification("Logs downloaded successfully!", "success");
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
            
            .logs-filter {
                display: flex;
                gap: 15px;
                margin-bottom: 15px;
                align-items: flex-end;
            }
            
            .logs-container {
                background-color: #1e293b;
                border-radius: 4px;
                padding: 10px;
                max-height: 400px;
                overflow-y: auto;
            }
            
            .logs-output {
                margin: 0;
                color: #e2e8f0;
                font-family: monospace;
                font-size: 14px;
                white-space: pre-wrap;
            }
        `;
        document.head.appendChild(style);
        
        // Set default date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById("log-date").value = today;
    }
    
    // Show modal
    modal.style.display = "block";
}

// Function to filter logs
function filterLogs(level, date) {
    const logsOutput = document.querySelector(".logs-output");
    if (!logsOutput) return;
    
    // Get all log lines
    const allLogs = logsOutput.textContent.split("\n");
    
    // Filter logs
    const filteredLogs = allLogs.filter(log => {
        // Skip empty lines
        if (!log.trim()) return false;
        
        // Check level
        if (level !== "all") {
            const logLevelMatch = log.match(/\[(INFO|WARNING|ERROR|DEBUG)\]/);
            if (!logLevelMatch || logLevelMatch[1].toLowerCase() !== level.toLowerCase()) {
                return false;
            }
        }
        
        // Check date
        if (date) {
            const dateStr = date.replace(/-/g, "-");
            if (!log.includes(dateStr)) {
                return false;
            }
        }
        
        return true;
    });
    
    // Update logs display
    logsOutput.textContent = filteredLogs.join("\n");
    
    // Show notification
    showNotification(`Logs filtered: ${filteredLogs.length} entries found`, "info");
}

// Function to initialize backup and restore
function initializeBackupRestore() {
    // Create backup button
    const createBackupBtn = document.querySelector(".create-backup-btn");
    if (createBackupBtn) {
        createBackupBtn.addEventListener("click", function() {
            createBackup();
        });
    }
    
    // Restore backup button
    const restoreBackupBtn = document.querySelector(".restore-backup-btn");
    if (restoreBackupBtn) {
        restoreBackupBtn.addEventListener("click", function() {
            showRestoreBackupModal();
        });
    }
    
    // Download backup buttons
    const downloadBackupBtns = document.querySelectorAll(".download-backup-btn");
    if (downloadBackupBtns.length > 0) {
        downloadBackupBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                const backupId = this.getAttribute("data-backup-id");
                downloadBackup(backupId);
            });
        });
    }
    
    // Delete backup buttons
    const deleteBackupBtns = document.querySelectorAll(".delete-backup-btn");
    if (deleteBackupBtns.length > 0) {
        deleteBackupBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                const backupId = this.getAttribute("data-backup-id");
                const backupDate = this.closest("tr").querySelector("td:first-child").textContent;
                
                showConfirmationModal(
                    `Are you sure you want to delete the backup from ${backupDate}?`,
                    () => {
                        // In a real implementation, this would send an API request
                        // For this demo, we'll just remove the row and show a notification
                        const row = this.closest("tr");
                        if (row) {
                            row.remove();
                        }
                        
                        showNotification(`Backup from ${backupDate} deleted successfully!`, "success");
                    },
                    "Delete Backup",
                    "Delete",
                    "btn-danger"
                );
            });
        });
    }
}

// Function to create backup
function createBackup() {
    console.log("Creating backup...");
    
    // In a real implementation, this would send an API request
    // For this demo, we'll just show a notification and add a row to the table
    
    // Show loading notification
    showNotification("Creating backup...", "info");
    
    // Simulate API delay
    setTimeout(() => {
        // Add new row to table
        const backupsTable = document.querySelector(".backups-table tbody");
        if (backupsTable) {
            const now = new Date();
            const formattedDate = now.toLocaleDateString("en-US", { 
                year: "numeric", 
                month: "short", 
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit"
            });
            
            const newRow = document.createElement("tr");
            newRow.innerHTML = `
                <td>${formattedDate}</td>
                <td>admin</td>
                <td>Manual</td>
                <td>25.4 MB</td>
                <td>
                    <div class="actions">
                        <button class="action-btn download-backup-btn" data-backup-id="${Date.now()}" title="Download Backup">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="action-btn restore-from-backup-btn" data-backup-id="${Date.now()}" title="Restore from Backup">
                            <i class="fas fa-undo"></i>
                        </button>
                        <button class="action-btn delete-backup-btn" data-backup-id="${Date.now()}" title="Delete Backup">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            
            backupsTable.insertBefore(newRow, backupsTable.firstChild);
            
            // Add event listeners to new buttons
            const downloadBtn = newRow.querySelector(".download-backup-btn");
            if (downloadBtn) {
                downloadBtn.addEventListener("click", function() {
                    const backupId = this.getAttribute("data-backup-id");
                    downloadBackup(backupId);
                });
            }
            
            const restoreBtn = newRow.querySelector(".restore-from-backup-btn");
            if (restoreBtn) {
                restoreBtn.addEventListener("click", function() {
                    const backupId = this.getAttribute("data-backup-id");
                    const backupDate = this.closest("tr").querySelector("td:first-child").textContent;
                    
                    showConfirmationModal(
                        `Are you sure you want to restore from the backup created on ${backupDate}? This will replace all current data.`,
                        () => {
                            // In a real implementation, this would send an API request
                            // For this demo, we'll just show a notification
                            
                            // Show loading notification
                            showNotification("Restoring from backup...", "info");
                            
                            // Simulate API delay
                            setTimeout(() => {
                                showNotification("Backup restored successfully!", "success");
                            }, 3000);
                        },
                        "Restore Backup",
                        "Restore",
                        "btn-warning"
                    );
                });
            }
            
            const deleteBtn = newRow.querySelector(".delete-backup-btn");
            if (deleteBtn) {
                deleteBtn.addEventListener("click", function() {
                    const backupId = this.getAttribute("data-backup-id");
                    const backupDate = this.closest("tr").querySelector("td:first-child").textContent;
                    
                    showConfirmationModal(
                        `Are you sure you want to delete the backup from ${backupDate}?`,
                        () => {
                            // In a real implementation, this would send an API request
                            // For this demo, we'll just remove the row and show a notification
                            const row = this.closest("tr");
                            if (row) {
                                row.remove();
                            }
                            
                            showNotification(`Backup from ${backupDate} deleted successfully!`, "success");
                        },
                        "Delete Backup",
                        "Delete",
                        "btn-danger"
                    );
                });
            }
        }
        
        showNotification("Backup created successfully!", "success");
    }, 2000);
}

// Function to download backup
function downloadBackup(backupId) {
    console.log(`Downloading backup: ${backupId}`);
    
    // In a real implementation, this would generate and download a file
    // For this demo, we'll just show a notification
    showNotification("Backup download started...", "info");
    
    // Simulate download delay
    setTimeout(() => {
        showNotification("Backup downloaded successfully!", "success");
    }, 1500);
}

// Function to show restore backup modal
function showRestoreBackupModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById("restore-backup-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "restore-backup-modal";
        modal.className = "modal";
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Restore from Backup</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="backup-file">Upload Backup File</label>
                        <input type="file" id="backup-file" name="backup-file" accept=".zip,.sql,.gz">
                    </div>
                    <div class="restore-options">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="restore-users" name="restore-users" checked>
                                Restore Users
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="restore-content" name="restore-content" checked>
                                Restore Content
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="restore-settings" name="restore-settings" checked>
                                Restore Settings
                            </label>
                        </div>
                    </div>
                    <div class="warning-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Warning: Restoring from backup will replace all current data. This action cannot be undone.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                    <button type="button" class="btn btn-warning restore-btn">Restore</button>
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
        
        modal.querySelector(".restore-btn").addEventListener("click", function() {
            const backupFile = document.getElementById("backup-file").value;
            
            if (!backupFile) {
                showNotification("Please select a backup file", "error");
                return;
            }
            
            // In a real implementation, this would upload the file and restore from it
            // For this demo, we'll just show a notification
            modal.style.display = "none";
            
            // Show loading notification
            showNotification("Restoring from backup...", "info");
            
            // Simulate API delay
            setTimeout(() => {
                showNotification("Backup restored successfully!", "success");
            }, 3000);
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
            .restore-options {
                margin: 20px 0;
            }
            
            .warning-message {
                background-color: #fff7ed;
                border: 1px solid #ffedd5;
                border-radius: 4px;
                padding: 10px;
                display: flex;
                align-items: center;
                color: #c2410c;
            }
            
            .warning-message i {
                margin-right: 10px;
                font-size: 18px;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Reset file input
    const backupFileInput = document.getElementById("backup-file");
    if (backupFileInput) backupFileInput.value = "";
    
    // Show modal
    modal.style.display = "block";
}
