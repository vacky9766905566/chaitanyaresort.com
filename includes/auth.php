<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Get current user
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $conn = getDBConnection();
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT id, name, email, phone, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    closeDBConnection($conn);
    
    return $user;
}

// Login user
function loginUser($email, $password) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $stmt->close();
            closeDBConnection($conn);
            return ['success' => true, 'role' => $user['role']];
        }
    }
    
    $stmt->close();
    closeDBConnection($conn);
    return ['success' => false, 'message' => 'Invalid email or password'];
}

// Register user
function registerUser($name, $email, $password, $phone = '') {
    $conn = getDBConnection();
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Email already registered'];
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashedPassword, $phone);
    
    if ($stmt->execute()) {
        $user_id = $conn->insert_id;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['role'] = 'customer';
        $stmt->close();
        closeDBConnection($conn);
        return ['success' => true];
    } else {
        $stmt->close();
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Registration failed'];
    }
}

// Logout user
function logoutUser() {
    session_unset();
    session_destroy();
}

// Require login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit();
    }
}

// Require admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: index.php');
        exit();
    }
}
?>

