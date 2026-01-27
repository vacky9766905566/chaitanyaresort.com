<?php
/**
 * Execute visitors.sql to create the visitors table
 * Run this file in browser: http://localhost:8000/execute-visitors-sql.php
 */

require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');

echo "<h1>Executing visitors.sql</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
    pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
</style>";

// Get database connection
$pdo = getDBConnection();

if (!$pdo) {
    echo "<p class='error'>❌ Database connection failed!</p>";
    echo "<p>Please check your database configuration in config.php</p>";
    exit;
}

echo "<p class='success'>✅ Database connection successful!</p>";

// Read SQL file
$sqlFile = __DIR__ . '/visitors.sql';
if (!file_exists($sqlFile)) {
    echo "<p class='error'>❌ visitors.sql file not found!</p>";
    exit;
}

$sql = file_get_contents($sqlFile);

if (empty($sql)) {
    echo "<p class='error'>❌ visitors.sql file is empty!</p>";
    exit;
}

// Extract CREATE TABLE statement (skip INSERT statements for now)
$createTablePattern = '/CREATE TABLE[^;]+;/is';
preg_match($createTablePattern, $sql, $matches);

if (empty($matches)) {
    echo "<p class='error'>❌ Could not find CREATE TABLE statement in visitors.sql!</p>";
    exit;
}

$createTableSQL = $matches[0];
// Replace CREATE TABLE with CREATE TABLE IF NOT EXISTS
$createTableSQL = preg_replace('/CREATE TABLE\s+`?visitors`?/i', 'CREATE TABLE IF NOT EXISTS `visitors`', $createTableSQL);

echo "<p class='info'>Found CREATE TABLE statement</p>";

try {
    // Execute CREATE TABLE
    $pdo->exec($createTableSQL);
    echo "<p class='success'>✅ Visitors table created successfully!</p>";
} catch (PDOException $e) {
    // Check if table already exists
    if (strpos($e->getMessage(), 'already exists') !== false) {
        echo "<p class='info'>ℹ️ Visitors table already exists</p>";
    } else {
        echo "<p class='error'>❌ Error creating table: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($createTableSQL) . "</pre>";
        exit;
    }
}

// Verify table was created
echo "<h2>Verification</h2>";
try {
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'visitors'");
    if ($tableCheck->rowCount() > 0) {
        echo "<p class='success'>✅ Visitors table exists!</p>";
        
        // Show table structure
        $columns = $pdo->query("DESCRIBE visitors")->fetchAll(PDO::FETCH_ASSOC);
        echo "<p class='info'>Table structure:</p>";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Default'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Count records
        $count = $pdo->query("SELECT COUNT(*) FROM visitors")->fetchColumn();
        echo "<p class='info'>Total records: $count</p>";
    } else {
        echo "<p class='error'>❌ Visitors table was not created!</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error verifying table: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>If the table was created successfully, the visitor tracking system should now work</li>";
echo "<li>Test by clicking WhatsApp buttons or submitting visitor info</li>";
echo "<li>You can delete this file (execute-visitors-sql.php) after successful execution</li>";
echo "</ol>";
?>
