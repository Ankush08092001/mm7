// Authentication JavaScript functionality
class AuthManager {
    constructor() {
        this.backendUrl = '/backend/api.php';
        this.init();
    }

    init() {
        // Login form submission
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleLogin();
            });
        }

        // Signup form submission
        const signupForm = document.getElementById('signup-form');
        if (signupForm) {
            signupForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleSignup();
            });
        }

        // Logout button
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleLogout();
            });
        }
    }

    async handleLogin() {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        try {
            const response = await fetch(`${this.backendUrl}/auth`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Login failed');
            }

            // Redirect based on user role
            if (data.user.is_admin) {
                window.location.href = '/admin/dashboard.php';
            } else {
                window.location.href = '/dashboard.php';
            }
        } catch (error) {
            this.showError(error.message);
        }
    }

    async handleSignup() {
        const username = document.getElementById('signup-username').value;
        const email = document.getElementById('signup-email').value;
        const password = document.getElementById('signup-password').value;
        const confirmPassword = document.getElementById('signup-confirm-password').value;

        if (password !== confirmPassword) {
            this.showError('Passwords do not match');
            return;
        }

        try {
            const response = await fetch(`${this.backendUrl}/users`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, email, password })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Signup failed');
            }

            // Redirect to login page
            window.location.href = '/login.php?registered=true';
        } catch (error) {
            this.showError(error.message);
        }
    }

    async handleLogout() {
        try {
            const response = await fetch(`${this.backendUrl}/auth`, {
                method: 'DELETE'
            });

            if (!response.ok) {
                throw new Error('Logout failed');
            }

            // Redirect to login page
            window.location.href = '/login.php';
        } catch (error) {
            this.showError(error.message);
        }
    }

    showError(message) {
        const errorDiv = document.getElementById('error-message');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        } else {
            alert(message);
        }
    }
}

// Initialize auth manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AuthManager();
}); 