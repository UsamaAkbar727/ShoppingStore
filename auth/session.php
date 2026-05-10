<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------------------------------------------
// ROOT PATH – used for filesystem require/include statements
// Points to the absolute path of the project root on the disk.
// -------------------------------------------------------
// We are in auth/session.php, so the root is one level up.
$root_path = rtrim(str_replace('\\', '/', dirname(__DIR__)), '/') . '/';

// -------------------------------------------------------
// BASE URL – used for all <a href>, <img> src, and header() redirects
// Dynamically detects the URL path from the domain root.
// -------------------------------------------------------
// Normalize paths for comparison
$normalized_root = str_replace('\\', '/', $root_path);
$normalized_doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);

// Find the relative path from document root by removing the doc root from the root path
$base_url = str_ireplace($normalized_doc_root, '', $normalized_root);

// Ensure the base_url starts and ends with a single slash
$base_url = '/' . trim($base_url, '/') . '/';

// If the project is at the root of the domain, ensure it's just a single slash
if ($base_url === '//') {
    $base_url = '/';
}

/**
 * Auth guard — call at the top of every protected page.
 */
function check_auth() {
    global $base_url;
    $current_page = basename($_SERVER['PHP_SELF']);
    $public_pages = ['login.php', 'signup.php', 'forgot-password.php', 'reset-password.php'];

    if (!isset($_SESSION['user_id']) && !in_array($current_page, $public_pages)) {
        header('Location: ' . $base_url . 'auth/login.php');
        exit();
    }

    if (isset($_SESSION['user_id']) && in_array($current_page, $public_pages)) {
        header('Location: ' . $base_url . 'index.php');
        exit();
    }
}
