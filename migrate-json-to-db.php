<?php
/**
 * Migration Script: Import JSON data to MySQL Database
 * 
 * This script reads the existing visitors.json file and imports all data
 * into the MySQL database.
 * 
 * Usage:
 * 1. Make sure database.sql has been executed in phpMyAdmin
 * 2. Update config.php with your database credentials
 * 3. Run this script in a browser or via command line
 * 4. Check the output for success/error messages
 */

require_once 'config.php';

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Migrate JSON to Database</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .success {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #4CAF50;
            color: #2e7d32;
        }
        .error {
            background: #ffebee;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #f44336;
            color: #c62828;
        }
        .info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #2196F3;
            color: #1565c0;
        }
        .warning {
            background: #fff3e0;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #ff9800;
            color: #e65100;
        }
        pre {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        button:hover {
            background: #5568d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Migrate JSON Data to Database</h1>

<?php
$jsonFile = 'data/visitors.json';

// Check if JSON file exists
if (!file_exists($jsonFile)) {
    echo '<div class="warning">JSON file not found: ' . $jsonFile . '</div>';
    echo '<div class="info">If you don\'t have existing JSON data, you can skip this migration.</div>';
    exit;
}

// Read JSON file
$fileContent = file_get_contents($jsonFile);
if ($fileContent === false) {
    echo '<div class="error">Failed to read JSON file</div>';
    exit;
}

$jsonData = json_decode($fileContent, true);
if ($jsonData === null) {
    echo '<div class="error">Invalid JSON format in file</div>';
    echo '<div class="info">JSON Error: ' . json_last_error_msg() . '</div>';
    exit;
}

if (!is_array($jsonData)) {
    echo '<div class="error">JSON data is not an array</div>';
    exit;
}

$totalRecords = count($jsonData);
echo '<div class="info">Found ' . $totalRecords . ' records in JSON file</div>';

// Get database connection
$pdo = getDBConnection();
if (!$pdo) {
    echo '<div class="error">Failed to connect to database. Please check config.php</div>';
    exit;
}

// Check if table exists
try {
    $checkTable = $pdo->query("SHOW TABLES LIKE 'visitors'");
    if ($checkTable->rowCount() === 0) {
        echo '<div class="error">Table \'visitors\' does not exist. Please run database.sql in phpMyAdmin first.</div>';
        exit;
    }
} catch (PDOException $e) {
    echo '<div class="error">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    exit;
}

// Check how many records already exist
try {
    $countStmt = $pdo->query("SELECT COUNT(*) as count FROM visitors");
    $existingCount = $countStmt->fetch()['count'];
    
    if ($existingCount > 0) {
        echo '<div class="warning">Database already contains ' . $existingCount . ' records.</div>';
        echo '<div class="info">This migration will add new records. Duplicate prevention is based on exact timestamp matching.</div>';
    }
} catch (PDOException $e) {
    echo '<div class="error">Error checking existing records: ' . htmlspecialchars($e->getMessage()) . '</div>';
    exit;
}

// Process migration
if (isset($_POST['migrate'])) {
    $inserted = 0;
    $skipped = 0;
    $errors = 0;
    $errorMessages = [];
    
    // Prepare insert statement
    $stmt = $pdo->prepare("
        INSERT INTO visitors (timestamp, name, contact, whatsapp_number, type, date, time)
        VALUES (:timestamp, :name, :contact, :whatsapp_number, :type, :date, :time)
    ");
    
    foreach ($jsonData as $index => $record) {
        try {
            // Check if record already exists (by timestamp)
            $checkStmt = $pdo->prepare("SELECT id FROM visitors WHERE timestamp = :timestamp LIMIT 1");
            $checkStmt->execute([':timestamp' => $record['timestamp']]);
            
            if ($checkStmt->rowCount() > 0) {
                $skipped++;
                continue;
            }
            
            // Prepare data for insertion
            $data = [
                ':timestamp' => $record['timestamp'] ?? date('c'),
                ':name' => $record['name'] ?? null,
                ':contact' => $record['contact'] ?? null,
                ':whatsapp_number' => $record['whatsappNumber'] ?? null,
                ':type' => $record['type'] ?? null,
                ':date' => $record['date'] ?? date('d/m/Y'),
                ':time' => $record['time'] ?? date('H:i:s')
            ];
            
            // Insert record
            $stmt->execute($data);
            $inserted++;
            
        } catch (PDOException $e) {
            $errors++;
            $errorMessages[] = "Record " . ($index + 1) . ": " . $e->getMessage();
        }
    }
    
    // Display results
    echo '<div class="success"><strong>Migration Complete!</strong></div>';
    echo '<div class="info">';
    echo '<strong>Results:</strong><br>';
    echo '✓ Inserted: ' . $inserted . ' records<br>';
    echo '⊘ Skipped (duplicates): ' . $skipped . ' records<br>';
    if ($errors > 0) {
        echo '✗ Errors: ' . $errors . ' records<br>';
    }
    echo '</div>';
    
    if ($errors > 0 && !empty($errorMessages)) {
        echo '<div class="error">';
        echo '<strong>Error Details:</strong><br>';
        echo '<pre>' . htmlspecialchars(implode("\n", array_slice($errorMessages, 0, 10))) . '</pre>';
        if (count($errorMessages) > 10) {
            echo '<p>... and ' . (count($errorMessages) - 10) . ' more errors</p>';
        }
        echo '</div>';
    }
    
    // Show final count
    try {
        $finalCountStmt = $pdo->query("SELECT COUNT(*) as count FROM visitors");
        $finalCount = $finalCountStmt->fetch()['count'];
        echo '<div class="success">Total records in database: ' . $finalCount . '</div>';
    } catch (PDOException $e) {
        // Ignore
    }
    
} else {
    // Show migration form
    echo '<form method="POST" action="">';
    echo '<div class="info">Ready to migrate ' . $totalRecords . ' records from JSON to database.</div>';
    echo '<button type="submit" name="migrate">Start Migration</button>';
    echo '</form>';
}
?>

    </div>
</body>
</html>

