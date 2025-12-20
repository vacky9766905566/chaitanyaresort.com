<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Get JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
    exit;
}

// Determine if this is a WhatsApp click or regular visitor
$isWhatsApp = isset($data['type']) && $data['type'] === 'whatsapp' && isset($data['whatsappNumber']);

if ($isWhatsApp) {
    // Handle WhatsApp click tracking
    $whatsappNumber = trim($data['whatsappNumber']);
    
    if (empty($whatsappNumber)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'WhatsApp number is required']);
        exit;
    }
    
    // Get visitor info if available (from welcome popup form)
    $visitorName = isset($data['name']) ? trim($data['name']) : null;
    $visitorContact = isset($data['contact']) ? trim($data['contact']) : null;
    
    $visitorData = [
        'timestamp' => $data['timestamp'] ?? date('c'),
        'name' => $visitorName,
        'contact' => $visitorContact,
        'whatsapp_number' => $whatsappNumber,
        'type' => 'whatsapp',
        'date' => $data['date'] ?? date('d/m/Y'),
        'time' => $data['time'] ?? date('H:i:s')
    ];
} else {
    // Handle regular visitor
    if (!isset($data['name']) || !isset($data['contact'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Missing required fields: name and contact']);
        exit;
    }
    
    // Sanitize input
    $name = trim($data['name']);
    $contact = trim($data['contact']);
    
    // Validate contact number (10 digits)
    if (strlen($contact) !== 10 || !preg_match('/^\d+$/', $contact)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid contact number. Must be 10 digits']);
        exit;
    }
    
    $visitorData = [
        'timestamp' => $data['timestamp'] ?? date('c'),
        'name' => $name,
        'contact' => $contact,
        'whatsapp_number' => null,
        'type' => 'visitor',
        'date' => $data['date'] ?? date('d/m/Y'),
        'time' => $data['time'] ?? date('H:i:s')
    ];
}

// Get database connection
$pdo = getDBConnection();

if (!$pdo) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

try {
    // Insert visitor data into database
    $stmt = $pdo->prepare("
        INSERT INTO visitors (timestamp, name, contact, whatsapp_number, type, date, time)
        VALUES (:timestamp, :name, :contact, :whatsapp_number, :type, :date, :time)
    ");
    
    $stmt->execute([
        ':timestamp' => $visitorData['timestamp'],
        ':name' => $visitorData['name'],
        ':contact' => $visitorData['contact'],
        ':whatsapp_number' => $visitorData['whatsapp_number'],
        ':type' => $visitorData['type'],
        ':date' => $visitorData['date'],
        ':time' => $visitorData['time']
    ]);
    
    // Get total count
    $countStmt = $pdo->query("SELECT COUNT(*) as total FROM visitors");
    $totalResult = $countStmt->fetch();
    $totalVisitors = $totalResult['total'];
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => $isWhatsApp ? 'WhatsApp click tracked successfully' : 'Visitor information saved successfully',
        'totalVisitors' => (int)$totalVisitors,
        'id' => $pdo->lastInsertId()
    ]);
    
} catch (PDOException $e) {
    error_log('Database error in save-visitor.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to save data to database'
    ]);
}
?>

