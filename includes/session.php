<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Get current user ID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Get current admin ID
function getCurrentAdminId() {
    return $_SESSION['admin_id'] ?? null;
}

// Logout function
function logout() {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}
?>

