<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check_in = $_POST['check_in'] ?? '';
    $check_out = $_POST['check_out'] ?? '';
    $room_id = $_POST['room_id'] ?? null;
    
    if (empty($check_in) || empty($check_out)) {
        echo json_encode(['success' => false, 'message' => 'Check-in and check-out dates are required']);
        exit();
    }
    
    $conn = getDBConnection();
    
    // Check availability for all rooms or specific room
    if ($room_id) {
        // Check specific room
        $stmt = $conn->prepare("
            SELECT r.*, 
                   CASE 
                       WHEN EXISTS (
                           SELECT 1 FROM bookings b 
                           WHERE b.room_id = r.id 
                           AND b.booking_status = 'confirmed'
                           AND (
                               (b.check_in_date <= ? AND b.check_out_date > ?) OR
                               (b.check_in_date < ? AND b.check_out_date >= ?) OR
                               (b.check_in_date >= ? AND b.check_out_date <= ?)
                           )
                       ) THEN 0 
                       ELSE 1 
                   END as is_available
            FROM rooms r
            WHERE r.id = ? AND r.is_active = 1
        ");
        $stmt->bind_param("ssssssi", $check_out, $check_in, $check_out, $check_in, $check_in, $check_out, $room_id);
    } else {
        // Check all rooms
        $stmt = $conn->prepare("
            SELECT r.*, 
                   CASE 
                       WHEN EXISTS (
                           SELECT 1 FROM bookings b 
                           WHERE b.room_id = r.id 
                           AND b.booking_status = 'confirmed'
                           AND (
                               (b.check_in_date <= ? AND b.check_out_date > ?) OR
                               (b.check_in_date < ? AND b.check_out_date >= ?) OR
                               (b.check_in_date >= ? AND b.check_out_date <= ?)
                           )
                       ) THEN 0 
                       ELSE 1 
                   END as is_available
            FROM rooms r
            WHERE r.is_active = 1
            ORDER BY r.room_number
        ");
        $stmt->bind_param("ssssss", $check_out, $check_in, $check_out, $check_in, $check_in, $check_out);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $rooms = [];
    
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
    
    $stmt->close();
    closeDBConnection($conn);
    
    echo json_encode(['success' => true, 'rooms' => $rooms]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>

