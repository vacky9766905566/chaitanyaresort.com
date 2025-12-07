<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

require_once __DIR__ . '/../config/database.php';

$booking_id = intval($_GET['id'] ?? 0);

$conn = getDBConnection();
$stmt = $conn->prepare("
    SELECT b.*, r.room_name, r.room_number, u.name as user_name, u.email as user_email 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    LEFT JOIN users u ON b.user_id = u.id 
    WHERE b.id = ?
");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();
closeDBConnection($conn);

if (!$booking) {
    header('Location: bookings.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - Admin Panel</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .admin-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        .booking-details {
            background: var(--white);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px var(--shadow);
        }
        .detail-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            padding: 1rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-label {
            font-weight: 600;
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div style="margin-bottom: 2rem;">
            <a href="bookings.php" class="btn btn-secondary">← Back to Bookings</a>
        </div>

        <div class="booking-details">
            <h1 style="color: var(--primary-color); margin-bottom: 2rem;">Booking #<?php echo $booking['id']; ?></h1>
            
            <div class="detail-row">
                <div class="detail-label">Guest Name:</div>
                <div><?php echo htmlspecialchars($booking['guest_name']); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Guest Email:</div>
                <div><?php echo htmlspecialchars($booking['guest_email']); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Guest Phone:</div>
                <div><?php echo htmlspecialchars($booking['guest_phone']); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Room:</div>
                <div><?php echo htmlspecialchars($booking['room_name'] . ' (' . $booking['room_number'] . ')'); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Check-in Date:</div>
                <div><?php echo date('d M Y', strtotime($booking['check_in_date'])); ?> (2:00 PM)</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Check-out Date:</div>
                <div><?php echo date('d M Y', strtotime($booking['check_out_date'])); ?> (12:00 PM)</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Number of Days:</div>
                <div><?php echo $booking['number_of_days']; ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Number of Guests:</div>
                <div><?php echo $booking['number_of_guests']; ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Extra Beds:</div>
                <div><?php echo $booking['extra_beds']; ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Room Price:</div>
                <div>₹<?php echo number_format($booking['room_price'], 2); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Extra Bed Price:</div>
                <div>₹<?php echo number_format($booking['extra_bed_price'], 2); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Total Price:</div>
                <div style="font-size: 1.2rem; font-weight: bold; color: var(--primary-color);">₹<?php echo number_format($booking['total_price'], 2); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Payment Status:</div>
                <div>
                    <span class="status-badge status-<?php echo $booking['payment_status']; ?>" style="padding: 0.5rem 1rem; border-radius: 5px;">
                        <?php echo ucfirst($booking['payment_status']); ?>
                    </span>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Booking Status:</div>
                <div>
                    <span class="status-badge status-<?php echo $booking['booking_status']; ?>" style="padding: 0.5rem 1rem; border-radius: 5px;">
                        <?php echo ucfirst($booking['booking_status']); ?>
                    </span>
                </div>
            </div>
            <?php if ($booking['special_requests']): ?>
                <div class="detail-row">
                    <div class="detail-label">Special Requests:</div>
                    <div><?php echo nl2br(htmlspecialchars($booking['special_requests'])); ?></div>
                </div>
            <?php endif; ?>
            <div class="detail-row">
                <div class="detail-label">Booking Date:</div>
                <div><?php echo date('d M Y, h:i A', strtotime($booking['created_at'])); ?></div>
            </div>
            <?php if ($booking['payment_link']): ?>
                <div class="detail-row">
                    <div class="detail-label">Payment Link:</div>
                    <div><a href="<?php echo htmlspecialchars($booking['payment_link']); ?>" target="_blank"><?php echo htmlspecialchars($booking['payment_link']); ?></a></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

