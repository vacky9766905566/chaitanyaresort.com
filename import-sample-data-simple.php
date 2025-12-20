<?php
/**
 * Simple script to import sample data into chaitanya_resort database
 * Run this via command line: php import-sample-data-simple.php
 * Or via browser: http://localhost/chaitanyaresort.com/import-sample-data-simple.php
 */

require_once 'config.php';

$isCLI = php_sapi_name() === 'cli';

if (!$isCLI) {
    header('Content-Type: text/plain');
}

echo "Starting sample data import...\n\n";

$pdo = getDBConnection();

if (!$pdo) {
    echo "ERROR: Failed to connect to database. Please check config.php\n";
    exit(1);
}

echo "✓ Database connection established\n";

// Sample visitor data
$visitors = [
    ['2025-12-20T10:30:15.123Z', 'Rajesh Kumar', '9876543210', NULL, 'visitor', '20/12/2025', '16:00:15'],
    ['2025-12-20T11:15:30.456Z', 'Priya Sharma', '9876543211', NULL, 'visitor', '20/12/2025', '16:45:30'],
    ['2025-12-20T12:00:45.789Z', 'Amit Patel', '9876543212', NULL, 'visitor', '20/12/2025', '17:30:45'],
    ['2025-12-20T13:20:10.012Z', 'Sunita Desai', '9876543213', NULL, 'visitor', '20/12/2025', '18:50:10'],
    ['2025-12-20T14:45:25.345Z', 'Vikram Singh', '9876543214', NULL, 'visitor', '20/12/2025', '20:15:25'],
    ['2025-12-21T09:30:00.678Z', 'Anjali Mehta', '9876543215', NULL, 'visitor', '21/12/2025', '15:00:00'],
    ['2025-12-21T10:15:35.901Z', 'Rohit Joshi', '9876543216', NULL, 'visitor', '21/12/2025', '15:45:35'],
    ['2025-12-21T11:00:50.234Z', 'Kavita Reddy', '9876543217', NULL, 'visitor', '21/12/2025', '16:30:50'],
    ['2025-12-21T12:30:15.567Z', 'Nikhil Agarwal', '9876543218', NULL, 'visitor', '21/12/2025', '18:00:15'],
    ['2025-12-21T13:45:40.890Z', 'Meera Iyer', '9876543219', NULL, 'visitor', '21/12/2025', '19:15:40'],
    ['2025-12-22T08:20:05.123Z', 'Arjun Nair', '9876543220', NULL, 'visitor', '22/12/2025', '13:50:05'],
    ['2025-12-22T09:10:20.456Z', 'Shreya Menon', '9876543221', NULL, 'visitor', '22/12/2025', '14:40:20'],
    ['2025-12-22T10:30:45.789Z', 'Ravi Kapoor', '9876543222', NULL, 'visitor', '22/12/2025', '16:00:45'],
    ['2025-12-22T11:50:10.012Z', 'Deepa Krishnan', '9876543223', NULL, 'visitor', '22/12/2025', '17:20:10'],
    ['2025-12-22T13:15:35.345Z', 'Suresh Venkat', '9876543224', NULL, 'visitor', '22/12/2025', '18:45:35'],
];

// Sample WhatsApp click data
$whatsappClicks = [
    ['2025-12-20T10:35:20.123Z', NULL, NULL, '919421297851', 'whatsapp', '20/12/2025', '16:05:20'],
    ['2025-12-20T10:36:45.456Z', NULL, NULL, '919421297851', 'whatsapp', '20/12/2025', '16:06:45'],
    ['2025-12-20T11:20:10.789Z', NULL, NULL, '918390347209', 'whatsapp', '20/12/2025', '16:50:10'],
    ['2025-12-20T12:05:30.012Z', NULL, NULL, '919112680201', 'whatsapp', '20/12/2025', '17:35:30'],
    ['2025-12-20T13:25:55.345Z', NULL, NULL, '919421297851', 'whatsapp', '20/12/2025', '18:55:55'],
    ['2025-12-20T14:50:20.678Z', NULL, NULL, '918390347209', 'whatsapp', '20/12/2025', '20:20:20'],
    ['2025-12-21T09:35:05.901Z', NULL, NULL, '919112680201', 'whatsapp', '21/12/2025', '15:05:05'],
    ['2025-12-21T10:20:40.234Z', NULL, NULL, '919421297851', 'whatsapp', '21/12/2025', '15:50:40'],
    ['2025-12-21T11:05:15.567Z', NULL, NULL, '918390347209', 'whatsapp', '21/12/2025', '16:35:15'],
    ['2025-12-21T12:35:50.890Z', NULL, NULL, '919112680201', 'whatsapp', '21/12/2025', '18:05:50'],
    ['2025-12-21T13:50:25.123Z', NULL, NULL, '919421297851', 'whatsapp', '21/12/2025', '19:20:25'],
    ['2025-12-22T08:25:00.456Z', NULL, NULL, '918390347209', 'whatsapp', '22/12/2025', '13:55:00'],
    ['2025-12-22T09:15:30.789Z', NULL, NULL, '919112680201', 'whatsapp', '22/12/2025', '14:45:30'],
    ['2025-12-22T10:35:55.012Z', NULL, NULL, '919421297851', 'whatsapp', '22/12/2025', '16:05:55'],
    ['2025-12-22T11:55:20.345Z', NULL, NULL, '918390347209', 'whatsapp', '22/12/2025', '17:25:20'],
    ['2025-12-22T13:20:45.678Z', NULL, NULL, '919112680201', 'whatsapp', '22/12/2025', '18:50:45'],
];

$stmt = $pdo->prepare("
    INSERT INTO visitors (timestamp, name, contact, whatsapp_number, type, date, time)
    VALUES (:timestamp, :name, :contact, :whatsapp_number, :type, :date, :time)
");

$inserted = 0;
$errors = 0;

// Insert visitor data
echo "Inserting visitor records...\n";
foreach ($visitors as $visitor) {
    try {
        $stmt->execute([
            ':timestamp' => $visitor[0],
            ':name' => $visitor[1],
            ':contact' => $visitor[2],
            ':whatsapp_number' => $visitor[3],
            ':type' => $visitor[4],
            ':date' => $visitor[5],
            ':time' => $visitor[6]
        ]);
        $inserted++;
    } catch (PDOException $e) {
        $errors++;
        echo "Error inserting visitor: " . $e->getMessage() . "\n";
    }
}

// Insert WhatsApp click data
echo "Inserting WhatsApp click records...\n";
foreach ($whatsappClicks as $click) {
    try {
        $stmt->execute([
            ':timestamp' => $click[0],
            ':name' => $click[1],
            ':contact' => $click[2],
            ':whatsapp_number' => $click[3],
            ':type' => $click[4],
            ':date' => $click[5],
            ':time' => $click[6]
        ]);
        $inserted++;
    } catch (PDOException $e) {
        $errors++;
        echo "Error inserting WhatsApp click: " . $e->getMessage() . "\n";
    }
}

// Get counts
$countStmt = $pdo->query("SELECT COUNT(*) as total FROM visitors");
$totalRecords = $countStmt->fetch()['total'];

$visitorCountStmt = $pdo->query("SELECT COUNT(*) as count FROM visitors WHERE type = 'visitor'");
$visitorCount = $visitorCountStmt->fetch()['count'];

$whatsappCountStmt = $pdo->query("SELECT COUNT(*) as count FROM visitors WHERE type = 'whatsapp'");
$whatsappCount = $whatsappCountStmt->fetch()['count'];

echo "\n";
echo "========================================\n";
if ($errors > 0) {
    echo "Import completed with $errors error(s)\n";
} else {
    echo "✓ Import completed successfully!\n";
}
echo "========================================\n";
echo "Total records inserted: $inserted\n";
echo "Total records in database: $totalRecords\n";
echo "Regular visitors: $visitorCount\n";
echo "WhatsApp clicks: $whatsappCount\n";
echo "========================================\n";

if (!$isCLI) {
    echo "<br><br>";
    echo "<a href='admin.html'>Go to Admin Panel</a>";
}
?>

