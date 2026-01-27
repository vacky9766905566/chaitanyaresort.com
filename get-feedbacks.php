<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Get database connection
$pdo = getDBConnection();

if (!$pdo) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

try {
    // Check if feedbacks table exists
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'feedbacks'");
    if ($tableCheck->rowCount() === 0) {
        // Return empty result if table doesn't exist yet
        echo json_encode([
            'success' => true,
            'feedbacks' => [],
            'pagination' => [
                'page' => 1,
                'per_page' => 10,
                'total' => 0,
                'total_pages' => 0,
                'has_next' => false,
                'has_prev' => false
            ]
        ]);
        exit;
    }
    
    // Get pagination parameters
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $perPage = isset($_GET['per_page']) ? max(1, min(50, intval($_GET['per_page']))) : 10;
    // Show all approved feedbacks (new feedbacks are auto-approved)
    $status = isset($_GET['status']) ? $_GET['status'] : 'approved';
    
    // Calculate offset
    $offset = ($page - 1) * $perPage;
    
    // Build query
    $whereClause = "WHERE status = :status";
    $params = [':status' => $status];
    
    // Get total count
    $countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM feedbacks $whereClause");
    $countStmt->execute($params);
    $totalResult = $countStmt->fetch();
    $totalFeedbacks = (int)$totalResult['total'];
    
    // Calculate total pages
    $totalPages = ceil($totalFeedbacks / $perPage);
    
    // Get feedbacks with pagination
    // Note: LIMIT and OFFSET must be integers, not bound parameters in some MySQL versions
    $limit = (int)$perPage;
    $offset = (int)$offset;
    $stmt = $pdo->prepare("
        SELECT id, name, email, contact, rating, message, status, created_at
        FROM feedbacks
        $whereClause
        ORDER BY created_at DESC
        LIMIT $limit OFFSET $offset
    ");
    
    // Execute with status parameter
    $stmt->execute($params);
    
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format feedbacks
    $formattedFeedbacks = array_map(function($feedback) {
        return [
            'id' => (int)$feedback['id'],
            'name' => $feedback['name'],
            'email' => $feedback['email'],
            'contact' => $feedback['contact'],
            'rating' => $feedback['rating'] ? (int)$feedback['rating'] : null,
            'message' => $feedback['message'],
            'status' => $feedback['status'],
            'created_at' => $feedback['created_at']
        ];
    }, $feedbacks);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'feedbacks' => $formattedFeedbacks,
        'pagination' => [
            'page' => $page,
            'per_page' => $perPage,
            'total' => $totalFeedbacks,
            'total_pages' => $totalPages,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1
        ]
    ]);
    
} catch (PDOException $e) {
    error_log('Database error in get-feedbacks.php: ' . $e->getMessage());
    http_response_code(500);
    $errorMessage = 'Failed to fetch feedbacks from database';
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
