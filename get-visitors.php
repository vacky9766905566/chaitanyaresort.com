<?php
// Suppress any output before headers
ob_start();
require_once 'config.php';
ob_end_clean();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Get database connection
$pdo = getDBConnection();

if (!$pdo) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed', 'details' => 'Could not establish connection to database']);
    exit;
}

try {
    // Check if visitors table exists
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'visitors'");
    if ($tableCheck->rowCount() === 0) {
        // Return empty array if table doesn't exist
        echo json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Get optional query parameters
    $type = isset($_GET['type']) ? $_GET['type'] : null;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
    $orderBy = isset($_GET['order']) && $_GET['order'] === 'asc' ? 'ASC' : 'DESC';
    
    // Build query
    $sql = "SELECT id, timestamp, name, contact, whatsapp_number as whatsappNumber, type, date, time 
            FROM visitors";
    
    $params = [];
    
    if ($type !== null) {
        $sql .= " WHERE type = :type";
        $params[':type'] = $type;
    }
    
    $sql .= " ORDER BY created_at " . $orderBy;
    
    if ($limit !== null && $limit > 0) {
        $sql .= " LIMIT :limit";
    }
    
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    if ($type !== null) {
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
    }
    if ($limit !== null && $limit > 0) {
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    }
    
    $stmt->execute();
    $visitors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // If no visitors found, return empty array
    if (empty($visitors)) {
        echo json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Convert to the format expected by frontend (remove id and created_at, keep original format)
    $result = array_map(function($visitor) {
        $data = [
            'timestamp' => $visitor['timestamp'] ?? null,
            'date' => $visitor['date'] ?? null,
            'time' => $visitor['time'] ?? null
        ];
        
        // Add name and contact if not null (for regular visitors)
        if (isset($visitor['name']) && $visitor['name'] !== null) {
            $data['name'] = $visitor['name'];
        }
        if (isset($visitor['contact']) && $visitor['contact'] !== null) {
            $data['contact'] = $visitor['contact'];
        }
        
        // Add WhatsApp fields if present
        if (isset($visitor['whatsappNumber']) && $visitor['whatsappNumber'] !== null) {
            $data['whatsappNumber'] = $visitor['whatsappNumber'];
        }
        if (isset($visitor['type']) && $visitor['type'] !== null) {
            $data['type'] = $visitor['type'];
        }
        
        return $data;
    }, $visitors);
    
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    error_log('Database error in get-visitors.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to retrieve data from database',
        'message' => $e->getMessage(),
        'sql' => $sql ?? 'N/A'
    ]);
} catch (Exception $e) {
    error_log('General error in get-visitors.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'An error occurred',
        'message' => $e->getMessage()
    ]);
}
?>

