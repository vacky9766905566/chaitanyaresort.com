<?php
/**
 * Test script to check database connection and feedbacks table
 * Run this file in browser to diagnose database issues
 */

require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');

echo "<h1>Database Connection Test</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
    pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
</style>";

// Test 1: Database Connection
echo "<h2>Test 1: Database Connection</h2>";
$pdo = getDBConnection();

if (!$pdo) {
    echo "<p class='error'>❌ Database connection failed!</p>";
    echo "<p>Please check:</p>";
    echo "<ul>";
    echo "<li>MySQL server is running</li>";
    echo "<li>Database credentials in config.php are correct</li>";
    echo "<li>Database 'chaitanya_resort' exists</li>";
    echo "</ul>";
    echo "<pre>";
    echo "DB_HOST: " . DB_HOST . "\n";
    echo "DB_NAME: " . DB_NAME . "\n";
    echo "DB_USER: " . DB_USER . "\n";
    echo "DB_PASS: " . (DB_PASS ? '***' : '(empty)') . "\n";
    echo "</pre>";
    exit;
} else {
    echo "<p class='success'>✅ Database connection successful!</p>";
}

// Test 2: Check if visitors table exists
echo "<h2>Test 2: Visitors Table</h2>";
try {
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'visitors'");
    if ($tableCheck->rowCount() > 0) {
        echo "<p class='success'>✅ Visitors table exists</p>";
        
        // Count records
        $countStmt = $pdo->query("SELECT COUNT(*) as total FROM visitors");
        $total = $countStmt->fetch()['total'];
        echo "<p class='info'>Total visitors: $total</p>";
    } else {
        echo "<p class='error'>❌ Visitors table does not exist!</p>";
        echo "<p>Please run: <code>database.sql</code> or <code>visitors.sql</code></p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error checking visitors table: " . $e->getMessage() . "</p>";
}

// Test 3: Check if feedbacks table exists
echo "<h2>Test 3: Feedbacks Table</h2>";
try {
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'feedbacks'");
    if ($tableCheck->rowCount() > 0) {
        echo "<p class='success'>✅ Feedbacks table exists</p>";
        
        // Count records
        $countStmt = $pdo->query("SELECT COUNT(*) as total FROM feedbacks");
        $total = $countStmt->fetch()['total'];
        echo "<p class='info'>Total feedbacks: $total</p>";
        
        // Show status breakdown
        $statusStmt = $pdo->query("SELECT status, COUNT(*) as count FROM feedbacks GROUP BY status");
        $statuses = $statusStmt->fetchAll();
        echo "<p class='info'>Status breakdown:</p><ul>";
        foreach ($statuses as $status) {
            echo "<li>{$status['status']}: {$status['count']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='error'>❌ Feedbacks table does not exist!</p>";
        echo "<p>Please run: <code>feedback.sql</code></p>";
        echo "<p><a href='feedback.sql' target='_blank'>View feedback.sql</a></p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error checking feedbacks table: " . $e->getMessage() . "</p>";
}

// Test 4: List all tables
echo "<h2>Test 4: All Tables in Database</h2>";
try {
    $tables = $pdo->query("SHOW TABLES");
    $tableList = $tables->fetchAll(PDO::FETCH_COLUMN);
    if (count($tableList) > 0) {
        echo "<p class='info'>Tables found:</p><ul>";
        foreach ($tableList as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='error'>No tables found in database!</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error listing tables: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>If visitors table is missing, run <code>database.sql</code> or <code>visitors.sql</code></li>";
echo "<li>If feedbacks table is missing, run <code>feedback.sql</code></li>";
echo "<li>Check your MySQL server is running</li>";
echo "<li>Verify database credentials in <code>config.php</code></li>";
echo "</ol>";
?>
