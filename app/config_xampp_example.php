<?php
/**
 * XAMPP Configuration Override
 * 
 * INSTRUKSI:
 * 1. Rename file ini menjadi 'config_xampp.php'
 * 2. Edit sesuai setup XAMPP Anda
 * 3. Backup config.php original
 * 4. Copy isi file ini ke config.php
 * 
 * ATAU
 * 
 * Edit langsung file config.php, ubah bagian:
 * define('DB_USER', getenv('DB_USER') ?: 'dvwa');
 * menjadi:
 * define('DB_USER', getenv('DB_USER') ?: 'root');
 * 
 * dan:
 * define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'dvwa123');
 * menjadi:
 * define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
 */

// Database Configuration for XAMPP
// XAMPP default: root user dengan password kosong
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');           // XAMPP default
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');       // XAMPP default: kosong
define('DB_NAME', getenv('DB_NAME') ?: 'dvwa');
define('DB_PORT', getenv('DB_PORT') ?: 3306);

// Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Connection
$mysqli = @new mysqli(
    DB_HOST,
    DB_USER,
    DB_PASSWORD,
    DB_NAME,
    DB_PORT
);

// Connection error handling
// Don't die immediately - let login.php handle the setup flow
if ($mysqli->connect_error) {
    $mysqli = null;
} else {
    // Set charset
    $mysqli->set_charset("utf8mb4");
}

// Security Headers
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
?>
