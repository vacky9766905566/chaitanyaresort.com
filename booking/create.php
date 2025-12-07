<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

if (!isLoggedIn()) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = getCurrentUserId();
    $roomId = intval($_POST['room_id'] ?? 0);
    $checkinDate = $_POST['checkin_date'] ?? '';
    $checkoutDate = $_POST['checkout_date'] ?? '';
    $numGuests = intval($_POST['num_guests'] ?? 2);
    $extraBed = isset($_POST['extra_bed']) ? 1 : 0;
    $specialRequests = sanitizeInput($_POST['special_requests'] ?? '');
    
    // Validate dates
    $checkin = new DateTime($checkinDate);
    $checkout = new DateTime($checkoutDate);
    $today = new DateTime();
    
    if ($checkin < $today || $checkout <= $checkin) {
        $_SESSION['error'] = 'Invalid dates selected';
        header('Location: ../index.php');
        exit;
    }
    
    // Check room availability
    if (!checkRoomAvailability($roomId, $checkinDate, $checkoutDate)) {
        $_SESSION['error'] = 'Room is no longer available for the selected dates';
        header('Location: ../index.php?checkin=' . urlencode($checkinDate) . '&checkout=' . urlencode($checkoutDate));
        exit;
    }
    
    // Calculate total amount
    $totalAmount = calculateBookingAmount($roomId, $checkinDate, $checkoutDate, $extraBed);
    
    // Create booking
    $conn = getDBConnection();
    $sql = "INSERT INTO bookings (user_id, room_id, checkin_date, checkout_date, num_guests, extra_bed, total_amount, special_requests, payment_status, booking_status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'confirmed')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissiids", $userId, $roomId, $checkinDate, $checkoutDate, $numGuests, $extraBed, $totalAmount, $specialRequests);
    
    if ($stmt->execute()) {
        $bookingId = $stmt->insert_id;
        $stmt->close();
        closeDBConnection($conn);
        
        // Redirect to payment
        header('Location: payment.php?booking_id=' . $bookingId);
        exit;
    } else {
        $_SESSION['error'] = 'Booking failed. Please try again.';
        header('Location: ../index.php');
        exit;
    }
}

header('Location: ../index.php');
?>

