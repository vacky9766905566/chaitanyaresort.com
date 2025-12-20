<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// JSON file path
$jsonFile = 'data/visitors.json';

// Check if file exists
if (!file_exists($jsonFile)) {
    echo json_encode([]);
    exit;
}

// Read and return JSON file content
$fileContent = file_get_contents($jsonFile);
if ($fileContent === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to read file']);
    exit;
}

// Return the JSON data
echo $fileContent;
?>

