<?php
require_once 'config.php';

$pdo = getDBConnection();

if (!$pdo) {
    echo "Database connection failed!\n";
    exit;
}

try {
    // Check current structure
    $columns = $pdo->query("SHOW COLUMNS FROM visitors WHERE Field = 'id'")->fetch();
    
    if ($columns && strpos($columns['Extra'], 'auto_increment') === false) {
        echo "Fixing AUTO_INCREMENT for id column...\n";
        $pdo->exec("ALTER TABLE visitors MODIFY id int(11) NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (id)");
        echo "✅ Fixed AUTO_INCREMENT\n";
    } else {
        echo "✅ AUTO_INCREMENT already set\n";
    }
    
    // Verify
    $columns = $pdo->query("SHOW COLUMNS FROM visitors WHERE Field = 'id'")->fetch();
    echo "Current id column: " . $columns['Type'] . " " . $columns['Extra'] . "\n";
    
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate') !== false || strpos($e->getMessage(), 'already exists') !== false) {
        echo "✅ Primary key already exists\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
?>
