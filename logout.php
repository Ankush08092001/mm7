<?php
require_once 'config/db.php';

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to home page
header('Location: index.html');
exit();
?> 