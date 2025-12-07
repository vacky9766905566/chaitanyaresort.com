<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $conn = getDBConnection();
    
    $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            $response = ['success' => true, 'message' => 'Login successful'];
        } else {
            $response = ['success' => false, 'message' => 'Invalid password'];
        }
    } else {
        $response = ['success' => false, 'message' => 'User not found'];
    }
    
    $stmt->close();
    closeDBConnection($conn);
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

header('Location: ../index.php');
?>

