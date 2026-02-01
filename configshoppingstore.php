<?php

$hostname = 'localhost';
$username = 'root';
$passward = null;
$dbname   = 'fashionstore';

try {
    $conn = new PDO("mysql:host=" . $hostname . ";dbname=" . $dbname, $username, $passward);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err) {
    die($err);
}
