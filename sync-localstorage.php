<?php
/**
 * Sync script to copy localStorage data to visitors.json
 * Run this script after saving data via file:// protocol
 * 
 * Usage: 
 * 1. Open browser console on index.html
 * 2. Run: localStorage.getItem('visitorInfo')
 * 3. Copy the JSON output
 * 4. Paste it into the textarea below and click "Sync to JSON"
 * 
 * OR use this automated version if you have access to localStorage data
 */

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sync localStorage to visitors.json</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            height: 200px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
        }
        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        button:hover {
            background: #5568d3;
        }
        .info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #2196F3;
        }
        .success {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border-left: 4px solid #4CAF50;
            display: none;
        }
        .error {
            background: #ffebee;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border-left: 4px solid #f44336;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sync localStorage to visitors.json</h1>
        
        <div class="info">
            <strong>Instructions:</strong><br>
            1. Open your browser console (F12) on index.html<br>
            2. Run: <code>localStorage.getItem('visitorInfo')</code><br>
            3. Copy the JSON output<br>
            4. Paste it in the textarea below and click "Sync to JSON"
        </div>
        
        <form method="POST" action="">
            <label for="jsonData"><strong>Paste localStorage JSON data here:</strong></label><br>
            <textarea id="jsonData" name="jsonData" placeholder='[{"timestamp":"...","name":"...","contact":"..."}]'><?php echo isset($_POST['jsonData']) ? htmlspecialchars($_POST['jsonData']) : ''; ?></textarea>
            <br>
            <button type="submit">Sync to visitors.json</button>
        </form>
        
        <div id="success" class="success"></div>
        <div id="error" class="error"></div>
    </div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jsonData'])) {
    $jsonData = $_POST['jsonData'];
    
    // Validate JSON
    $data = json_decode($jsonData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo '<div class="error">Invalid JSON: ' . json_last_error_msg() . '</div>';
    } elseif (!is_array($data)) {
        echo '<div class="error">Data must be a JSON array</div>';
    } else {
        // Save to visitors.json
        $jsonFile = 'data/visitors.json';
        $formattedJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        if (file_put_contents($jsonFile, $formattedJson, LOCK_EX) !== false) {
            // Also update visitors.js
            $jsFile = 'data/visitors.js';
            $jsContent = "// Auto-generated JavaScript file from visitors.json\n";
            $jsContent .= "// This file is updated automatically when visitors.json changes\n";
            $jsContent .= "window.visitorsData = " . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . ";\n";
            file_put_contents($jsFile, $jsContent, LOCK_EX);
            
            echo '<div class="success">Successfully synced ' . count($data) . ' entries to visitors.json and visitors.js!</div>';
        } else {
            echo '<div class="error">Failed to write to visitors.json. Check file permissions.</div>';
        }
    }
}
?>
</body>
</html>

