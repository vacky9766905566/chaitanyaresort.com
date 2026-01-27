<?php
/**
 * Update feedbacks table to set default status to 'approved' for instant display
 * Run this file: http://localhost:8000/update-feedback-status.php
 */

require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');

echo "<h1>Updating Feedback Status</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
</style>";

$pdo = getDBConnection();

if (!$pdo) {
    echo "<p class='error'>❌ Database connection failed!</p>";
    exit;
}

echo "<p class='success'>✅ Database connection successful!</p>";

try {
    // Update all pending feedbacks to approved
    $updateStmt = $pdo->prepare("UPDATE feedbacks SET status = 'approved' WHERE status = 'pending'");
    $updateStmt->execute();
    $updatedCount = $updateStmt->rowCount();
    
    echo "<p class='info'>Updated $updatedCount pending feedback(s) to approved</p>";
    
    // Change default status for new feedbacks
    $alterStmt = $pdo->exec("ALTER TABLE feedbacks MODIFY status enum('pending','approved','rejected') DEFAULT 'approved' COMMENT 'Moderation status - default approved for instant display'");
    
    echo "<p class='success'>✅ Changed default status to 'approved' for new feedbacks</p>";
    
    // Verify
    $columns = $pdo->query("SHOW COLUMNS FROM feedbacks WHERE Field = 'status'")->fetch();
    echo "<p class='info'>Current default: " . ($columns['Default'] ?? 'NULL') . "</p>";
    
    // Count by status
    $statusCounts = $pdo->query("SELECT status, COUNT(*) as count FROM feedbacks GROUP BY status")->fetchAll();
    echo "<p class='info'>Status breakdown:</p><ul>";
    foreach ($statusCounts as $status) {
        echo "<li>{$status['status']}: {$status['count']}</li>";
    }
    echo "</ul>";
    
    echo "<hr>";
    echo "<p class='success'><strong>✅ All feedbacks will now be displayed instantly!</strong></p>";
    echo "<p>New feedbacks will be automatically approved and visible immediately.</p>";
    
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
