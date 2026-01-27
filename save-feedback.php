<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Get JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
    exit;
}

// Validate required fields
if (!isset($data['name']) || !isset($data['message'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields: name and message']);
    exit;
}

// Sanitize input
$name = trim($data['name']);
$email = isset($data['email']) ? trim($data['email']) : null;
$contact = isset($data['contact']) ? trim($data['contact']) : null;
$rating = isset($data['rating']) ? intval($data['rating']) : null;
$message = trim($data['message']);

// Validate rating (1-5)
if ($rating !== null && ($rating < 1 || $rating > 5)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Rating must be between 1 and 5']);
    exit;
}

// Validate email if provided
if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid email address']);
    exit;
}

// Validate contact if provided (10 digits)
if ($contact && (strlen($contact) !== 10 || !preg_match('/^\d+$/', $contact))) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid contact number. Must be 10 digits']);
    exit;
}

// Get database connection
$pdo = getDBConnection();

if (!$pdo) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

try {
    // Insert feedback into database with 'approved' status for instant display
    $stmt = $pdo->prepare("
        INSERT INTO feedbacks (name, email, contact, rating, message, status)
        VALUES (:name, :email, :contact, :rating, :message, 'approved')
    ");
    
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':contact' => $contact,
        ':rating' => $rating,
        ':message' => $message
    ]);
    
    // Get total count
    $countStmt = $pdo->query("SELECT COUNT(*) as total FROM feedbacks WHERE status = 'approved'");
    $totalResult = $countStmt->fetch();
    $totalFeedbacks = $totalResult['total'];
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Feedback submitted successfully. Thank you!',
        'totalFeedbacks' => (int)$totalFeedbacks,
        'id' => $pdo->lastInsertId()
    ]);
    
} catch (PDOException $e) {
    error_log('Database error in save-feedback.php: ' . $e->getMessage());
    http_response_code(500);
    $errorMessage = 'Failed to save feedback to database';
    // Include more details in development
    if (isset($_SERVER['SERVER_NAME']) && ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1')) {
        $errorMessage .= ': ' . $e->getMessage();
    }
    echo json_encode([
        'success' => false,
        'error' => $errorMessage
    ]);
}
?>
