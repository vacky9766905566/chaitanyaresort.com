<?php
// Start output buffering to catch any unexpected output
ob_start();

header('Content-Type: application/json');
session_start();

try {
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../includes/auth.php';
} catch (Exception $e) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Server configuration error: ' . $e->getMessage()]);
    exit();
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'create') {
            if (!isLoggedIn()) {
                ob_clean();
                echo json_encode(['success' => false, 'message' => 'Please login to book']);
                exit();
            }
            
            $room_id = intval($_POST['room_id'] ?? 0);
            $check_in = $_POST['check_in'] ?? '';
            $check_out = $_POST['check_out'] ?? '';
            $number_of_guests = intval($_POST['number_of_guests'] ?? 2);
            $extra_beds = intval($_POST['extra_beds'] ?? 0);
            $guest_name = $_POST['guest_name'] ?? '';
            $guest_email = $_POST['guest_email'] ?? '';
            $guest_phone = $_POST['guest_phone'] ?? '';
            $special_requests = '';
            
            // Validate required fields
            if (empty($room_id) || empty($check_in) || empty($check_out) || empty($guest_name) || empty($guest_email) || empty($guest_phone)) {
                ob_clean();
                echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
                exit();
            }
            
            // Validate dates
            try {
                $check_in_date = new DateTime($check_in);
                $check_out_date = new DateTime($check_out);
                $number_of_days = $check_in_date->diff($check_out_date)->days;
            } catch (Exception $e) {
                ob_clean();
                echo json_encode(['success' => false, 'message' => 'Invalid date format']);
                exit();
            }
            
            if ($number_of_days <= 0) {
                ob_clean();
                echo json_encode(['success' => false, 'message' => 'Check-out date must be after check-in date']);
                exit();
            }
            
            $conn = getDBConnection();
            
            // Get room details
            $stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ? AND is_active = 1");
            if (!$stmt) {
                closeDBConnection($conn);
                ob_clean();
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
                exit();
            }
            
            $stmt->bind_param("i", $room_id);
            $stmt->execute();
            $room = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            
            if (!$room) {
                closeDBConnection($conn);
                ob_clean();
                echo json_encode(['success' => false, 'message' => 'Room not found']);
                exit();
            }
            
            // Check availability
            $stmt = $conn->prepare("
                SELECT COUNT(*) as count FROM bookings 
                WHERE room_id = ? 
                AND booking_status = 'confirmed'
                AND (
                    (check_in_date <= ? AND check_out_date > ?) OR
                    (check_in_date < ? AND check_out_date >= ?) OR
                    (check_in_date >= ? AND check_out_date <= ?)
                )
            ");
            
            if (!$stmt) {
                closeDBConnection($conn);
                ob_clean();
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
                exit();
            }
            
            $stmt->bind_param("issssss", $room_id, $check_out, $check_in, $check_out, $check_in, $check_in, $check_out);
            $stmt->execute();
            $result = $stmt->get_result();
            $booking_count = $result->fetch_assoc()['count'];
            $stmt->close();
            
            if ($booking_count > 0) {
                closeDBConnection($conn);
                ob_clean();
                echo json_encode(['success' => false, 'message' => 'Room is not available for selected dates']);
                exit();
            }
            
            // Calculate prices
            $room_price = $room['price_per_day'] * $number_of_days;
            $extra_bed_price = $extra_beds > 0 ? ($room['extra_bed_price'] * $extra_beds * $number_of_days) : 0;
            $total_price = $room_price + $extra_bed_price;
            
            // Create booking
            $user_id = $_SESSION['user_id'];
            $payment_link = 'https://u.payu.in/PAYUMN/Nr1i3MjpIYCb';
            
            $stmt = $conn->prepare("
                INSERT INTO bookings 
                (user_id, room_id, check_in_date, check_out_date, number_of_days, number_of_guests, 
                 extra_beds, room_price, extra_bed_price, total_price, guest_name, guest_email, 
                 guest_phone, special_requests, payment_link) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            if (!$stmt) {
                closeDBConnection($conn);
                ob_clean();
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
                exit();
            }
            
            $stmt->bind_param("iissiiidddsssss", 
                $user_id, $room_id, $check_in, $check_out, $number_of_days, 
                $number_of_guests, $extra_beds, $room_price, $extra_bed_price, 
                $total_price, $guest_name, $guest_email, $guest_phone, 
                $special_requests, $payment_link
            );
            
            if ($stmt->execute()) {
                $booking_id = $conn->insert_id;
                $stmt->close();
                closeDBConnection($conn);
                ob_clean();
                echo json_encode([
                    'success' => true, 
                    'booking_id' => $booking_id,
                    'total_price' => $total_price,
                    'payment_link' => $payment_link
                ]);
            } else {
                $error = $conn->error;
                $stmt->close();
                closeDBConnection($conn);
                ob_clean();
                echo json_encode(['success' => false, 'message' => 'Failed to create booking: ' . $error]);
            }
        } else {
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } else {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    }
} catch (Exception $e) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>

