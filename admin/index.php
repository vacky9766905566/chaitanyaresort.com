<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

require_once __DIR__ . '/../config/database.php';

// Get statistics
$conn = getDBConnection();

// Total bookings
$totalBookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];

// Pending bookings
$pendingBookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE booking_status = 'confirmed' AND payment_status = 'pending'")->fetch_assoc()['count'];

// Total revenue
$totalRevenue = $conn->query("SELECT SUM(total_price) as total FROM bookings WHERE payment_status = 'paid'")->fetch_assoc()['total'] ?? 0;

// Today's bookings
$todayBookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['count'];

// Recent bookings
$recentBookings = $conn->query("
    SELECT b.*, r.room_name, r.room_number, u.name as user_name 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    LEFT JOIN users u ON b.user_id = u.id 
    ORDER BY b.created_at DESC 
    LIMIT 10
")->fetch_all(MYSQLI_ASSOC);

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Chaitanya Resort</title>
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
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--primary-color);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px var(--shadow);
        }
        .stat-card h3 {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        .admin-nav {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .admin-nav a {
            padding: 0.75rem 1.5rem;
            background: var(--primary-color);
            color: var(--white);
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .admin-nav a:hover {
            background: var(--secondary-color);
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
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        .status-paid {
            background: #cce5ff;
            color: #004085;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Admin Panel - Chaitanya Resort</h1>
            <div>
                <a href="../index.php" class="btn btn-secondary">Back to Website</a>
                <a href="../api/auth.php" onclick="event.preventDefault(); logout();" class="btn btn-outline" style="margin-left: 1rem;">Logout</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <div class="stat-value"><?php echo $totalBookings; ?></div>
            </div>
            <div class="stat-card">
                <h3>Pending Payments</h3>
                <div class="stat-value"><?php echo $pendingBookings; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <div class="stat-value">₹<?php echo number_format($totalRevenue, 2); ?></div>
            </div>
            <div class="stat-card">
                <h3>Today's Bookings</h3>
                <div class="stat-value"><?php echo $todayBookings; ?></div>
            </div>
        </div>

        <div class="admin-nav">
            <a href="index.php">Dashboard</a>
            <a href="bookings.php">All Bookings</a>
            <a href="rooms.php">Manage Rooms</a>
            <a href="users.php">Manage Users</a>
        </div>

        <div class="bookings-table">
            <h2 style="padding: 1.5rem; margin: 0; color: var(--primary-color);">Recent Bookings</h2>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Guest Name</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Total Price</th>
                        <th>Payment Status</th>
                        <th>Booking Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentBookings)): ?>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 2rem; color: var(--text-light);">No bookings found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentBookings as $booking): ?>
                            <tr>
                                <td>#<?php echo $booking['id']; ?></td>
                                <td><?php echo htmlspecialchars($booking['guest_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                                <td><?php echo date('d M Y', strtotime($booking['check_in_date'])); ?></td>
                                <td><?php echo date('d M Y', strtotime($booking['check_out_date'])); ?></td>
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
                                    <a href="booking_details.php?id=<?php echo $booking['id']; ?>" style="color: var(--primary-color); text-decoration: none;">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        async function logout() {
            const formData = new FormData();
            formData.append('action', 'logout');
            
            const response = await fetch('../api/auth.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.success) {
                window.location.href = '../index.php';
            }
        }
    </script>
</body>
</html>

