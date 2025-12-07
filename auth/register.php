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
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone = sanitizeInput($_POST['phone'] ?? '');
    
    if (empty($name) || empty($email) || empty($password)) {
        $response = ['success' => false, 'message' => 'All fields are required'];
    } else {
        $conn = getDBConnection();
        
        // Check if email already exists
        $checkSql = "SELECT id FROM users WHERE email = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            $response = ['success' => false, 'message' => 'Email already registered'];
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $phone);
            
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                
                $response = ['success' => true, 'message' => 'Registration successful'];
            } else {
                $response = ['success' => false, 'message' => 'Registration failed'];
            }
            
            $stmt->close();
        }
        
        $checkStmt->close();
        closeDBConnection($conn);
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

header('Location: ../index.php');
?>

