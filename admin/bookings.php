<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

require_once __DIR__ . '/../config/database.php';

$conn = getDBConnection();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $booking_id = intval($_POST['booking_id']);
    $payment_status = $_POST['payment_status'];
    $booking_status = $_POST['booking_status'];
    
    $stmt = $conn->prepare("UPDATE bookings SET payment_status = ?, booking_status = ? WHERE id = ?");
    $stmt->bind_param("ssi", $payment_status, $booking_status, $booking_id);
    $stmt->execute();
    $stmt->close();
    
    header('Location: bookings.php?updated=1');
    exit();
}

// Get all bookings
$bookings = $conn->query("
    SELECT b.*, r.room_name, r.room_number, u.name as user_name 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    LEFT JOIN users u ON b.user_id = u.id 
    ORDER BY b.created_at DESC
")->fetch_all(MYSQLI_ASSOC);

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Bookings - Admin Panel</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .bookings-table {
            background: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px var(--shadow);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        th {
            background: var(--light-bg);
            font-weight: 600;
            color: var(--primary-color);
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .status-confirmed { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .status-paid { background: #cce5ff; color: #004085; }
        .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); }
        .modal.active { display: flex; align-items: center; justify-content: center; }
        .modal-content { background: var(--white); padding: 2rem; border-radius: 10px; max-width: 500px; width: 90%; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>All Bookings</h1>
            <div>
                <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
                <a href="../index.php" class="btn btn-outline" style="margin-left: 1rem;">Back to Website</a>
            </div>
        </div>

        <?php if (isset($_GET['updated'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                Booking status updated successfully!
            </div>
        <?php endif; ?>

        <div class="bookings-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Guest</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Days</th>
                        <th>Guests</th>
                        <th>Extra Beds</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td>#<?php echo $booking['id']; ?></td>
                            <td><?php echo htmlspecialchars($booking['guest_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                            <td><?php echo date('d M Y', strtotime($booking['check_in_date'])); ?></td>
                            <td><?php echo date('d M Y', strtotime($booking['check_out_date'])); ?></td>
                            <td><?php echo $booking['number_of_days']; ?></td>
                            <td><?php echo $booking['number_of_guests']; ?></td>
                            <td><?php echo $booking['extra_beds']; ?></td>
                            <td>₹<?php echo number_format($booking['total_price'], 2); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $booking['payment_status']; ?>">
                                    <?php echo ucfirst($booking['payment_status']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $booking['booking_status']; ?>">
                                    <?php echo ucfirst($booking['booking_status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="#" onclick="openUpdateModal(<?php echo $booking['id']; ?>, '<?php echo $booking['payment_status']; ?>', '<?php echo $booking['booking_status']; ?>'); return false;" style="color: var(--primary-color);">Update</a> |
                                <a href="booking_details.php?id=<?php echo $booking['id']; ?>" style="color: var(--primary-color);">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <h2>Update Booking Status</h2>
            <form method="POST">
                <input type="hidden" name="booking_id" id="update_booking_id">
                <div class="form-group">
                    <label>Payment Status</label>
                    <select name="payment_status" id="update_payment_status" class="form-group input">
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Booking Status</label>
                    <select name="booking_status" id="update_booking_status" class="form-group input">
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                <button type="button" onclick="closeUpdateModal()" class="btn btn-secondary">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function openUpdateModal(id, paymentStatus, bookingStatus) {
            document.getElementById('update_booking_id').value = id;
            document.getElementById('update_payment_status').value = paymentStatus;
            document.getElementById('update_booking_status').value = bookingStatus;
            document.getElementById('updateModal').classList.add('active');
        }
        function closeUpdateModal() {
            document.getElementById('updateModal').classList.remove('active');
        }
    </script>
</body>
</html>

