<?php
/**
 * Execute feedback.sql to create the feedbacks table
 * Run this file in browser: http://localhost:8000/execute-feedback-sql.php
 */

require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');

echo "<h1>Executing feedback.sql</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
    pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
</style>";

// First, try to connect without database to create it if needed
try {
    $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $pdoTemp = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    // Check if database exists
    $dbCheck = $pdoTemp->query("SHOW DATABASES LIKE '" . DB_NAME . "'");
    if ($dbCheck->rowCount() === 0) {
        echo "<p class='info'>Database '" . DB_NAME . "' does not exist. Creating it...</p>";
        $pdoTemp->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "<p class='success'>✅ Database created successfully!</p>";
    } else {
        echo "<p class='success'>✅ Database '" . DB_NAME . "' exists</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error connecting to MySQL server: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please check:</p>";
    echo "<ul>";
    echo "<li>MySQL server is running</li>";
    echo "<li>Database credentials in config.php are correct</li>";
    echo "</ul>";
    exit;
}

// Now get database connection with database name
$pdo = getDBConnection();

if (!$pdo) {
    echo "<p class='error'>❌ Database connection failed!</p>";
    echo "<p>Please check your database configuration in config.php</p>";
    exit;
}

echo "<p class='success'>✅ Database connection successful!</p>";

// Read SQL file
$sqlFile = __DIR__ . '/feedback.sql';
if (!file_exists($sqlFile)) {
    echo "<p class='error'>❌ feedback.sql file not found!</p>";
    exit;
}

$sql = file_get_contents($sqlFile);

if (empty($sql)) {
    echo "<p class='error'>❌ feedback.sql file is empty!</p>";
    exit;
}

// Remove comments and split into individual statements
$sql = preg_replace('/--.*$/m', '', $sql); // Remove single-line comments
$sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // Remove multi-line comments
$statements = array_filter(
    array_map('trim', explode(';', $sql)),
    function($stmt) {
        return !empty($stmt);
    }
);

echo "<p class='info'>Found " . count($statements) . " SQL statement(s) to execute</p>";

$successCount = 0;
$errorCount = 0;

foreach ($statements as $index => $statement) {
    if (empty(trim($statement))) {
        continue;
    }
    
    try {
        $pdo->exec($statement);
        $successCount++;
        echo "<p class='success'>✅ Statement " . ($index + 1) . " executed successfully</p>";
    } catch (PDOException $e) {
        $errorCount++;
        echo "<p class='error'>❌ Error executing statement " . ($index + 1) . ": " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars(substr($statement, 0, 200)) . "...</pre>";
    }
}

echo "<hr>";
echo "<h2>Summary</h2>";
echo "<p class='info'>Successfully executed: $successCount statement(s)</p>";
if ($errorCount > 0) {
    echo "<p class='error'>Failed: $errorCount statement(s)</p>";
}

// Verify table was created
echo "<h2>Verification</h2>";
try {
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'feedbacks'");
    if ($tableCheck->rowCount() > 0) {
        echo "<p class='success'>✅ Feedbacks table exists!</p>";
        
        // Show table structure
        $columns = $pdo->query("DESCRIBE feedbacks")->fetchAll(PDO::FETCH_ASSOC);
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
    } else {
        echo "<p class='error'>❌ Feedbacks table was not created!</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error verifying table: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>If the table was created successfully, you can now use the feedback system</li>";
echo "<li>Test by submitting a feedback at <a href='index.php#feedback'>Feedback Section</a></li>";
echo "<li>You can delete this file (execute-feedback-sql.php) after successful execution</li>";
echo "</ol>";
?>
