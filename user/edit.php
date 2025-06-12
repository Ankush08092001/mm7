<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

// Get user data
$user = getRow("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
if (!$user) {
    session_destroy();
    header('Location: /login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $errors = [];

    // Validate name
    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }

    // Check if email is already taken by another user
    $existing_user = getRow("SELECT id FROM users WHERE email = ? AND id != ?", [$email, $_SESSION['user_id']]);
    if ($existing_user) {
        $errors[] = 'Email is already taken';
    }

    // If changing password
    if (!empty($current_password)) {
        if (empty($new_password) || empty($confirm_password)) {
            $errors[] = 'New password and confirmation are required';
        } elseif ($new_password !== $confirm_password) {
            $errors[] = 'New passwords do not match';
        } elseif (strlen($new_password) < 8) {
            $errors[] = 'New password must be at least 8 characters long';
        } elseif (!password_verify($current_password, $user['password_hash'])) {
            $errors[] = 'Current password is incorrect';
        }
    }

    if (empty($errors)) {
        // Update user data
        $params = [$name, $email, $_SESSION['user_id']];
        $sql = "UPDATE users SET name = ?, email = ?";

        // If changing password, add password update
        if (!empty($current_password)) {
            $sql .= ", password_hash = ?";
            $params[] = password_hash($new_password, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = ?";
        
        if (executeQuery($sql, $params)) {
            $_SESSION['success'] = 'Profile updated successfully';
            header('Location: /user/dashboard.php');
            exit();
        } else {
            $errors[] = 'Failed to update profile';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Exam Prep</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .edit-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
        }
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .error-list {
            color: #dc3545;
            margin-bottom: 20px;
            padding: 10px;
            background: #f8d7da;
            border-radius: 4px;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <div class="form-card">
            <h1>Edit Profile</h1>
            
            <?php if (!empty($errors)): ?>
                <div class="error-list">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="editProfileForm">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <h2>Change Password (Optional)</h2>
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password">
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="/user/dashboard.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('editProfileForm').addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            // If any password field is filled, validate all
            if (currentPassword || newPassword || confirmPassword) {
                if (!currentPassword) {
                    e.preventDefault();
                    alert('Please enter your current password');
                    return;
                }
                if (!newPassword) {
                    e.preventDefault();
                    alert('Please enter a new password');
                    return;
                }
                if (!confirmPassword) {
                    e.preventDefault();
                    alert('Please confirm your new password');
                    return;
                }
                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('New passwords do not match');
                    return;
                }
                if (newPassword.length < 8) {
                    e.preventDefault();
                    alert('New password must be at least 8 characters long');
                    return;
                }
            }
        });
    </script>
</body>
</html> 