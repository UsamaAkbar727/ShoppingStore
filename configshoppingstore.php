<?php
require_once(__DIR__ . '/config/load_env.php');

$hostname = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$passward = getenv('DB_PASS') !== false ? getenv('DB_PASS') : null;
$dbname   = getenv('DB_NAME') ?: 'fashionstore';

try {
    $conn = new PDO("mysql:host=" . $hostname . ";dbname=" . $dbname, $username, $passward);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err) {
    die($err);
}

