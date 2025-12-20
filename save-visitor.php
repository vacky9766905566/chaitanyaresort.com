<?php
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

// Validate input
if (!isset($data['name']) || !isset($data['contact'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

// Sanitize input
$name = trim($data['name']);
$contact = trim($data['contact']);

// Validate contact number (10 digits)
if (strlen($contact) !== 10 || !preg_match('/^\d+$/', $contact)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid contact number']);
    exit;
}

// Prepare visitor data
$visitorData = [
    'timestamp' => $data['timestamp'] ?? date('c'),
    'name' => $name,
    'contact' => $contact,
    'date' => $data['date'] ?? date('d/m/Y'),
    'time' => $data['time'] ?? date('H:i:s')
];

// JSON file path
$jsonFile = 'data/visitors.json';

// Create data directory if it doesn't exist
$dataDir = dirname($jsonFile);
if (!is_dir($dataDir)) {
    if (!mkdir($dataDir, 0755, true)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to create data directory']);
        exit;
    }
}

// Read existing data
$existingData = [];
if (file_exists($jsonFile)) {
    $fileContent = file_get_contents($jsonFile);
    if ($fileContent !== false) {
        $existingData = json_decode($fileContent, true);
        if (!is_array($existingData)) {
            $existingData = [];
        }
    }
}

// Add new visitor
$existingData[] = $visitorData;

// Save to JSON file
$jsonData = json_encode($existingData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$writeResult = file_put_contents($jsonFile, $jsonData, LOCK_EX);

if ($writeResult === false) {
    http_response_code(500);
    $error = 'Failed to save data to ' . $jsonFile;
    // Check if directory is writable
    if (!is_writable(dirname($jsonFile))) {
        $error .= ' - Directory is not writable';
    } elseif (file_exists($jsonFile) && !is_writable($jsonFile)) {
        $error .= ' - File is not writable';
    }
    error_log('Save visitor error: ' . $error);
    echo json_encode(['success' => false, 'error' => $error]);
    exit;
}

// Also update visitors.js file for file:// protocol support
$jsFile = 'data/visitors.js';
$jsContent = "// Auto-generated JavaScript file from visitors.json\n";
$jsContent .= "// This file is updated automatically when visitors.json changes\n";
$jsContent .= "window.visitorsData = " . json_encode($existingData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . ";\n";
$jsWriteResult = file_put_contents($jsFile, $jsContent, LOCK_EX);

if ($jsWriteResult === false) {
    // Log warning but don't fail the request
    error_log('Warning: Failed to update visitors.js file');
}

// Return success response
echo json_encode([
    'success' => true,
    'message' => 'Visitor information saved successfully',
    'totalVisitors' => count($existingData)
]);
?>

