<?php
require_once 'config.php';

header('Content-Type: text/plain');

$pdo = getDBConnection();

if (!$pdo) {
    echo "Database connection FAILED\n";
    exit;
}

echo "Database connection OK\n\n";

try {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . count($tables) . "\n";
    foreach ($tables as $table) {
        echo "  - $table\n";
    }
    
    echo "\n";
    
    // Check visitors table
    if (in_array('visitors', $tables)) {
        $count = $pdo->query("SELECT COUNT(*) FROM visitors")->fetchColumn();
        echo "Visitors table: EXISTS ($count records)\n";
    } else {
        echo "Visitors table: NOT FOUND\n";
    }
    
    // Check feedbacks table
    if (in_array('feedbacks', $tables)) {
        $count = $pdo->query("SELECT COUNT(*) FROM feedbacks")->fetchColumn();
        echo "Feedbacks table: EXISTS ($count records)\n";
    } else {
        echo "Feedbacks table: NOT FOUND\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
