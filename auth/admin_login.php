<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $conn = getDBConnection();
    
    $sql = "SELECT id, username, password FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            
            $response = ['success' => true, 'message' => 'Login successful'];
        } else {
            $response = ['success' => false, 'message' => 'Invalid password'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Invalid username'];
    }
    
    $stmt->close();
    closeDBConnection($conn);
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

header('Location: ../admin/index.php');
?>

