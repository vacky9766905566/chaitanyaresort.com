<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $conn = getDBConnection();
    
    // Get all active rooms
    $result = $conn->query("SELECT * FROM rooms WHERE is_active = 1 ORDER BY room_number");
    $rooms = [];
    
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
    
    closeDBConnection($conn);
    echo json_encode(['success' => true, 'rooms' => $rooms]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>

