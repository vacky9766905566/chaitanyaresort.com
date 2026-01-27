<?php
/**
 * Database Configuration File
 * Update these values according to your MySQL setup
 */

// Database configuration
define('DB_HOST', '127.0.0.1');        // Database server (127.0.0.1 or localhost)
define('DB_NAME', 'chaitanya_resort'); // Database name
define('DB_USER', 'root');             // Your MySQL username (default is 'root' for local)
define('DB_PASS', '');                 // Your MySQL password (leave empty if no password)
define('DB_CHARSET', 'utf8mb4');

/**
 * Get database connection
 * @return PDO|null Returns PDO connection or null on failure
 */
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        // Return more detailed error for debugging (remove in production)
        if (defined('DEBUG') && DEBUG) {
            error_log("DB_HOST: " . DB_HOST . ", DB_NAME: " . DB_NAME . ", DB_USER: " . DB_USER);
        }
        return null;
    }
}
