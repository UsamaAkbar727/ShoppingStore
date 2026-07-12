<?php
require_once(__DIR__ . '/config/load_env.php');

$hostname = safe_getenv('DB_HOST', 'localhost');
$username = safe_getenv('DB_USER', 'root');
$passward = safe_getenv('DB_PASS') !== null ? safe_getenv('DB_PASS') : null;
$dbname   = safe_getenv('DB_NAME', 'fashionstore');

try {
    $conn = new PDO("mysql:host=" . $hostname . ";dbname=" . $dbname, $username, $passward);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err) {
    // Log the error details safely on the server
    error_log("Database connection failed: " . $err->getMessage());

    // Enable detailed errors if APP_DEBUG is true, otherwise show generic message
    if (safe_getenv('APP_DEBUG') === 'true') {
        die("Database Connection Error: " . $err->getMessage());
    } else {
        die("Database Connection Error. Please verify your environment configuration and credentials.");
    }
}

