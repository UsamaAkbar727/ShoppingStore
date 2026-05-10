<?php
header('Content-Type: application/json');
require_once("auth/session.php");
include("configshoppingstore.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please sign in to add items to cart.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['id'] ?? 0;
$quantity = $_POST['qty'] ?? 1;

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
    exit();
}

try {
    // Check if product exists and has stock
    $stmt = $conn->prepare("SELECT stock FROM `product` WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found.']);
        exit();
    }

    if ($product['stock'] < $quantity) {
        echo json_encode(['success' => false, 'message' => 'Insufficient stock available.']);
        exit();
    }

    // Check if already in cart
    $stmt = $conn->prepare("SELECT id, quantity FROM `cart` WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $cartItem = $stmt->fetch();

    if ($cartItem) {
        $newQty = $cartItem['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
        $stmt->execute([$newQty, $cartItem['id']]);
    } else {
        $stmt = $conn->prepare("INSERT INTO `cart` (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);
    }

    echo json_encode(['success' => true]);
} catch (\Throwable $th) {
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
}
