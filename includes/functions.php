<?php
require_once __DIR__ . '/../config/database.php';

// Sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check room availability
function checkRoomAvailability($roomId, $checkinDate, $checkoutDate, $excludeBookingId = null) {
    $conn = getDBConnection();
    
    $sql = "SELECT COUNT(*) as count FROM bookings 
            WHERE room_id = ? 
            AND booking_status IN ('confirmed', 'checked_in')
            AND payment_status != 'cancelled'
            AND (
                (checkin_date <= ? AND checkout_date > ?)
                OR (checkin_date < ? AND checkout_date >= ?)
                OR (checkin_date >= ? AND checkout_date <= ?)
            )";
    
    if ($excludeBookingId) {
        $sql .= " AND id != ?";
    }
    
    $stmt = $conn->prepare($sql);
    
    if ($excludeBookingId) {
        $stmt->bind_param("issssssi", $roomId, $checkoutDate, $checkinDate, $checkoutDate, $checkinDate, $checkinDate, $checkoutDate, $excludeBookingId);
    } else {
        $stmt->bind_param("issssss", $roomId, $checkoutDate, $checkinDate, $checkoutDate, $checkinDate, $checkinDate, $checkoutDate);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
    closeDBConnection($conn);
    
    return $row['count'] == 0;
}

// Get available rooms for dates
function getAvailableRooms($checkinDate, $checkoutDate) {
    $conn = getDBConnection();
    
    $sql = "SELECT r.* FROM rooms r
            WHERE r.status = 'active'
            AND r.id NOT IN (
                SELECT DISTINCT room_id FROM bookings
                WHERE booking_status IN ('confirmed', 'checked_in')
                AND payment_status != 'cancelled'
                AND (
                    (checkin_date <= ? AND checkout_date > ?)
                    OR (checkin_date < ? AND checkout_date >= ?)
                    OR (checkin_date >= ? AND checkout_date <= ?)
                )
            )";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $checkoutDate, $checkinDate, $checkoutDate, $checkinDate, $checkinDate, $checkoutDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
    
    $stmt->close();
    closeDBConnection($conn);
    
    return $rooms;
}

// Calculate booking amount
function calculateBookingAmount($roomId, $checkinDate, $checkoutDate, $extraBed) {
    $conn = getDBConnection();
    
    $sql = "SELECT price_per_day, extra_bed_price FROM rooms WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();
    
    $stmt->close();
    closeDBConnection($conn);
    
    $checkin = new DateTime($checkinDate);
    $checkout = new DateTime($checkoutDate);
    $days = $checkin->diff($checkout)->days;
    
    if ($days == 0) $days = 1; // Minimum 1 day
    
    $total = $room['price_per_day'] * $days;
    if ($extraBed) {
        $total += $room['extra_bed_price'] * $days;
    }
    
    return $total;
}

// Get user bookings
function getUserBookings($userId) {
    $conn = getDBConnection();
    
    $sql = "SELECT b.*, r.room_number, r.room_name 
            FROM bookings b
            JOIN rooms r ON b.room_id = r.id
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    
    $stmt->close();
    closeDBConnection($conn);
    
    return $bookings;
}
?>

