<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';

if (!isLoggedIn()) {
    header('Location: ../index.php');
    exit;
}

$bookingId = intval($_GET['booking_id'] ?? 0);

if (!$bookingId) {
    header('Location: ../index.php');
    exit;
}

$conn = getDBConnection();
$sql = "SELECT b.*, r.room_number, r.room_name 
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        WHERE b.id = ? AND b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $bookingId, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    $_SESSION['error'] = 'Booking not found';
    header('Location: ../index.php');
    exit;
}

$stmt->close();
closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Chaitanya Resort</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <h1>Chaitanya Resort</h1>
                </div>
                <ul class="nav-menu">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../gallery.php">Gallery</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Payment Section -->
    <section style="padding: 80px 0; background: var(--light-bg); min-height: 70vh;">
        <div class="container">
            <div style="max-width: 600px; margin: 0 auto; background: var(--white); padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px var(--shadow);">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Booking Summary</h2>
                
                <div style="margin-bottom: 2rem;">
                    <p><strong>Room:</strong> <?php echo htmlspecialchars($booking['room_name']); ?> (<?php echo htmlspecialchars($booking['room_number']); ?>)</p>
                    <p><strong>Check-in:</strong> <?php echo date('F d, Y', strtotime($booking['checkin_date'])); ?> (2:00 PM)</p>
                    <p><strong>Check-out:</strong> <?php echo date('F d, Y', strtotime($booking['checkout_date'])); ?> (12:00 PM)</p>
                    <p><strong>Guests:</strong> <?php echo $booking['num_guests']; ?></p>
                    <?php if ($booking['extra_bed']): ?>
                        <p><strong>Extra Bed:</strong> Yes</p>
                    <?php endif; ?>
                    <p style="font-size: 1.5rem; color: var(--secondary-color); margin-top: 1rem;">
                        <strong>Total Amount: ₹<?php echo number_format($booking['total_amount'], 2); ?></strong>
                    </p>
                </div>
                
                <div style="background: #fff3cd; padding: 1rem; border-radius: 5px; margin-bottom: 2rem; border-left: 4px solid #ffc107;">
                    <p style="margin: 0; font-size: 0.9rem; color: #856404;">
                        <strong>Note:</strong> Restaurant bills are separate and to be paid at the hotel.
                    </p>
                </div>
                
                <form action="https://u.payu.in/PAYUMN/Nr1i3MjpIYCb" method="POST" style="text-align: center;">
                    <input type="hidden" name="booking_id" value="<?php echo $bookingId; ?>">
                    <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.2rem; padding: 15px;">
                        Pay ₹<?php echo number_format($booking['total_amount'], 2); ?> via PayU
                    </button>
                </form>
                
                <p style="text-align: center; margin-top: 1rem; color: var(--text-light); font-size: 0.9rem;">
                    After payment, your booking will be confirmed.
                </p>
            </div>
        </div>
    </section>

    <script src="../js/script.js"></script>
</body>
</html>

