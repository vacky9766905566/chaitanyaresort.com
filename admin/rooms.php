<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

require_once __DIR__ . '/../config/database.php';

$conn = getDBConnection();

// Handle room update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_room'])) {
    $room_id = intval($_POST['room_id']);
    $room_name = $_POST['room_name'];
    $price_per_day = floatval($_POST['price_per_day']);
    $extra_bed_price = floatval($_POST['extra_bed_price']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $stmt = $conn->prepare("UPDATE rooms SET room_name = ?, price_per_day = ?, extra_bed_price = ?, is_active = ? WHERE id = ?");
    $stmt->bind_param("sddii", $room_name, $price_per_day, $extra_bed_price, $is_active, $room_id);
    $stmt->execute();
    $stmt->close();
    
    header('Location: rooms.php?updated=1');
    exit();
}

// Get all rooms
$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_number")->fetch_all(MYSQLI_ASSOC);

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms - Admin Panel</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }
        .room-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px var(--shadow);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div style="margin-bottom: 2rem;">
            <a href="index.php" class="btn btn-secondary">← Back to Dashboard</a>
        </div>

        <?php if (isset($_GET['updated'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                Room updated successfully!
            </div>
        <?php endif; ?>

        <h1 style="color: var(--primary-color); margin-bottom: 2rem;">Manage Rooms</h1>

        <div class="rooms-grid">
            <?php foreach ($rooms as $room): ?>
                <div class="room-card">
                    <form method="POST">
                        <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                        <h3 style="color: var(--primary-color); margin-bottom: 1rem;"><?php echo htmlspecialchars($room['room_number']); ?></h3>
                        <div class="form-group">
                            <label>Room Name</label>
                            <input type="text" name="room_name" value="<?php echo htmlspecialchars($room['room_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Price per Day (₹)</label>
                            <input type="number" name="price_per_day" step="0.01" value="<?php echo $room['price_per_day']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Extra Bed Price (₹)</label>
                            <input type="number" name="extra_bed_price" step="0.01" value="<?php echo $room['extra_bed_price']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_active" <?php echo $room['is_active'] ? 'checked' : ''; ?>>
                                Active
                            </label>
                        </div>
                        <button type="submit" name="update_room" class="btn btn-primary" style="width: 100%;">Update Room</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

