<?php
session_start();

// Function to check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && 
           $_SESSION['admin_logged_in'] === true && 
           isset($_SESSION['admin_role']) && 
           $_SESSION['admin_role'] === 'admin' &&
           isset($_SESSION['expires_at']) && 
           $_SESSION['expires_at'] > time();
}

// Function to require admin login
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: login.html');
        exit();
    }
}

// Function to get admin info
function getAdminInfo() {
    if (isAdminLoggedIn()) {
        return [
            'id' => $_SESSION['admin_id'],
            'username' => $_SESSION['admin_username'],
            'role' => $_SESSION['admin_role']
        ];
    }
    return null;
}

// Function to update session expiry
function updateSessionExpiry() {
    if (isAdminLoggedIn()) {
        $_SESSION['expires_at'] = time() + 3600; // Extend by 1 hour
    }
}

// Function to logout admin
function adminLogout() {
    session_unset();
    session_destroy();
    header('Location: login.html');
    exit();
}
?> 