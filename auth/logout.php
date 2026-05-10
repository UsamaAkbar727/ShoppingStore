<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Clear persistence cookies
setcookie("token", "", time() - 3600, "/");
setcookie("user_id", "", time() - 3600, "/");

// Destroy session
session_destroy();

header("location: login.php");
exit();
?>