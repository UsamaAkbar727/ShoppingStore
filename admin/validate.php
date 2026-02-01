<?php
session_start();

$adminUser = "admin";
$adminPass = "123456";

if ($_POST['username'] === $adminUser && $_POST['password'] === $adminPass) {
    $_SESSION['admin_logged_in'] = true;
    header("Location: index.php");
    exit;
} else {
    $error = "Invalid username or password.";
    header("Location: login.php?error=" . urlencode($error));
    exit;
}
