<?php
/**
 * Script to import sample data into chaitanya_resort database
 * Run this script in your browser or via command line
 * 
 * Usage via browser: http://localhost/chaitanyaresort.com/import-sample-data.php
 * Usage via command line: php import-sample-data.php
 */

require_once 'config.php';

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Sample Data</title>
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
        <h1>Import Sample Data to Database</h1>

<?php
// Check if running via command line or web browser
$isCLI = php_sapi_name() === 'cli';

if (isset($_POST['import']) || $isCLI) {
    // Get database connection
    $pdo = getDBConnection();
    
    if (!$pdo) {
        echo '<div class="error">Failed to connect to database. Please check config.php</div>';
        if ($isCLI) {
            echo "Failed to connect to database.\n";
            exit(1);
        }
        exit;
    }
    
    // Read sample data SQL file
    $sqlFile = 'sample-data.sql';
    if (!file_exists($sqlFile)) {
        echo '<div class="error">Sample data file not found: ' . $sqlFile . '</div>';
        if ($isCLI) {
            echo "Sample data file not found: $sqlFile\n";
            exit(1);
        }
        exit;
    }
    
    $sqlContent = file_get_contents($sqlFile);
    
    // Remove USE statement as we'll use the connection's database
    $sqlContent = preg_replace('/USE\s+`?chaitanya_resort`?;?/i', '', $sqlContent);
    
    // Split SQL statements (handle both ; and \n as separators)
    $statements = array_filter(
        array_map('trim', preg_split('/;(?=(?:[^\'"]*[\'"][^\'"]*[\'"])*[^\'"]*$)/', $sqlContent)),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^--/', $stmt) && strtoupper(trim($stmt)) !== 'USE';
        }
    );
    
    $inserted = 0;
    $errors = 0;
    $errorMessages = [];
    
    try {
        // Execute each SQL statement
        foreach ($statements as $statement) {
            if (empty(trim($statement)) || preg_match('/^--/', $statement)) {
                continue;
            }
            
            try {
                $pdo->exec($statement);
                
                // Check if it was an INSERT statement
                if (preg_match('/^INSERT\s+INTO/i', $statement)) {
                    $rowsAffected = $pdo->query("SELECT ROW_COUNT()")->fetchColumn();
                    // MySQL doesn't return affected rows this way in PDO, so we estimate
                    if (preg_match('/VALUES\s*\(/i', $statement)) {
                        $inserted++;
                    }
                }
            } catch (PDOException $e) {
                $errors++;
                $errorMessages[] = "Error: " . $e->getMessage();
            }
        }
        
        // Get actual count from database
        $countStmt = $pdo->query("SELECT COUNT(*) as total FROM visitors");
        $totalRecords = $countStmt->fetch()['total'];
        
        $visitorCountStmt = $pdo->query("SELECT COUNT(*) as count FROM visitors WHERE type = 'visitor'");
        $visitorCount = $visitorCountStmt->fetch()['count'];
        
        $whatsappCountStmt = $pdo->query("SELECT COUNT(*) as count FROM visitors WHERE type = 'whatsapp'");
        $whatsappCount = $whatsappCountStmt->fetch()['count'];
        
        if ($errors > 0) {
            echo '<div class="error">';
            echo '<strong>Completed with errors:</strong><br>';
            echo "Inserted statements: $inserted<br>";
            echo "Errors: $errors<br>";
            if (!empty($errorMessages)) {
                echo '<pre>' . htmlspecialchars(implode("\n", array_slice($errorMessages, 0, 5))) . '</pre>';
            }
            echo '</div>';
        } else {
            echo '<div class="success">';
            echo '<strong>✓ Sample data imported successfully!</strong><br><br>';
            echo "Total records in database: <strong>$totalRecords</strong><br>";
            echo "Regular visitors: <strong>$visitorCount</strong><br>";
            echo "WhatsApp clicks: <strong>$whatsappCount</strong><br>";
            echo '</div>';
            
            if ($isCLI) {
                echo "Sample data imported successfully!\n";
                echo "Total records: $totalRecords\n";
                echo "Visitors: $visitorCount, WhatsApp clicks: $whatsappCount\n";
                exit(0);
            }
        }
        
    } catch (PDOException $e) {
        echo '<div class="error">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        if ($isCLI) {
            echo "Database error: " . $e->getMessage() . "\n";
            exit(1);
        }
    }
    
} else {
    // Show import form
    ?>
    <div class="info">
        This script will import sample data from <code>sample-data.sql</code> into your database.
        <br><br>
        <strong>What will be imported:</strong>
        <ul>
            <li>15 regular visitor records (with names and contact numbers)</li>
            <li>16 WhatsApp click tracking records</li>
        </ul>
        <br>
        <strong>Note:</strong> If data already exists, some records may be duplicated.
    </div>
    
    <form method="POST" action="">
        <button type="submit" name="import">Import Sample Data</button>
    </form>
    <?php
}
?>

    </div>
</body>
</html>

