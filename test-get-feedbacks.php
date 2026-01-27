<?php
require_once 'config.php';

// Simulate GET request
$_GET['page'] = 1;
$_GET['per_page'] = 6;
$_GET['status'] = 'approved';

header('Content-Type: text/plain');

$pdo = getDBConnection();

if (!$pdo) {
    echo "Database connection FAILED\n";
    exit;
}

echo "Database connection OK\n\n";

try {
    // Check if feedbacks table exists
    echo "Checking if feedbacks table exists...\n";
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'feedbacks'");
    if ($tableCheck->rowCount() === 0) {
        echo "ERROR: Feedbacks table does not exist!\n";
        exit;
    }
    echo "✓ Feedbacks table exists\n\n";
    
    // Get pagination parameters
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $perPage = isset($_GET['per_page']) ? max(1, min(50, intval($_GET['per_page']))) : 10;
    $status = isset($_GET['status']) ? $_GET['status'] : 'approved';
    
    echo "Parameters: page=$page, per_page=$perPage, status=$status\n\n";
    
    // Calculate offset
    $offset = ($page - 1) * $perPage;
    
    // Build query
    $whereClause = "WHERE status = :status";
    $params = [':status' => $status];
    
    // Get total count
    echo "Executing COUNT query...\n";
    $countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM feedbacks $whereClause");
    $countStmt->execute($params);
    $totalResult = $countStmt->fetch();
    $totalFeedbacks = (int)$totalResult['total'];
    echo "✓ Total feedbacks: $totalFeedbacks\n\n";
    
    // Calculate total pages
    $totalPages = ceil($totalFeedbacks / $perPage);
    
    // Get feedbacks with pagination
    // Note: LIMIT and OFFSET must be integers, not bound parameters in some MySQL versions
    $limit = (int)$perPage;
    $offset = (int)$offset;
    echo "Executing SELECT query with LIMIT...\n";
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
    echo "✓ Retrieved " . count($feedbacks) . " feedbacks\n\n";
    
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
    
    echo "✓ All queries successful!\n";
    echo "\nSample output:\n";
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
    ], JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString();
}
?>
