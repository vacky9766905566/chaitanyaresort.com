<?php
/**
 * Test database connection and data retrieval
 * Run this to debug why data is not showing in admin.html
 */

require_once 'config.php';

header('Content-Type: application/json');

echo "Testing database connection...\n\n";

$pdo = getDBConnection();

if (!$pdo) {
    echo "ERROR: Failed to connect to database\n";
    exit(1);
}

echo "✓ Database connection successful\n\n";

// Test query
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM visitors");
    $total = $stmt->fetch()['total'];
    echo "Total records in database: $total\n\n";
    
    if ($total > 0) {
        // Get sample records
        $stmt = $pdo->query("SELECT * FROM visitors ORDER BY created_at DESC LIMIT 5");
        $records = $stmt->fetchAll();
        
        echo "Sample records:\n";
        echo json_encode($records, JSON_PRETTY_PRINT);
        echo "\n\n";
        
        // Test the same query that get-visitors.php uses
        echo "Testing get-visitors.php query:\n";
        $stmt = $pdo->query("SELECT id, timestamp, name, contact, whatsapp_number as whatsappNumber, type, date, time FROM visitors ORDER BY created_at DESC");
        $allRecords = $stmt->fetchAll();
        
        echo "Total records from query: " . count($allRecords) . "\n";
        echo "First record:\n";
        echo json_encode($allRecords[0] ?? null, JSON_PRETTY_PRINT);
        
    } else {
        echo "WARNING: No records found in database!\n";
    }
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

?>

