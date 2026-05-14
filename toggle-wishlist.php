<?php
header('Content-Type: application/json');
require_once("auth/session.php");
include("configshoppingstore.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please sign in to manage your favorites.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['id'] ?? 0;
$action = $_POST['action'] ?? 'wishlist';

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid product.']);
    exit();
}

try {
    if ($action === 'wishlist') {
        $stmt = $conn->prepare("INSERT IGNORE INTO `wishlist`(`user_id`, `product_id`) VALUES (?, ?)");
        $stmt->execute([$user_id, $product_id]);
        echo json_encode(['success' => true, 'action' => 'added']);
    } else {
        $stmt = $conn->prepare("DELETE FROM `wishlist` WHERE `user_id` = ? AND `product_id` = ?");
        $stmt->execute([$user_id, $product_id]);
        echo json_encode(['success' => true, 'action' => 'removed']);
    }
} catch (\Throwable $th) {
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
}
