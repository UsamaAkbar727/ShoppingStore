<?php
require_once("../auth/session.php");
check_auth();
include("../configshoppingstore.php");

// Flash message helper
function set_flash($type, $text) {
    $_SESSION['flash'] = ['type' => $type, 'text' => $text];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname       = trim($_POST['fullname'] ?? '');
    $address        = trim($_POST['address'] ?? '');
    $city           = trim($_POST['city'] ?? '');
    $postal_code    = trim($_POST['postal'] ?? $_POST['postal_code'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');
    $payment_method = $_POST['payment_method'] ?? 'cod';

    // Validation
    if (empty($fullname) || empty($address) || empty($phone)) {
        set_flash("danger", "⚠ Please fill in all required fields.");
        header("Location: ../checkout.php");
        exit;
    }

    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        set_flash("danger", "🔑 Please log in to place an order.");
        header("Location: ../auth/login.php");
        exit;
    }

    // Fetch cart
    $stmt = $conn->prepare("
        SELECT c.product_id, c.quantity as qty, p.price
        FROM cart c
        JOIN product p ON p.id = c.product_id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cartItems)) {
        set_flash("danger", "🛒 Your cart is empty.");
        header("Location: cart.php");
        exit;
    }

    // Totals
    $subtotal = array_sum(array_map(fn($item) => $item['qty'] * $item['price'], $cartItems));
    $shipping = $subtotal > 1000 ? 0 : 150;
    $total    = $subtotal + $shipping;

    // Create order
    $orderStmt = $conn->prepare("
        INSERT INTO orders (user_id, fullname, address, city, postal_code, phone, payment_method, subtotal, shipping, total, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
    ");
    $orderStmt->execute([$user_id, $fullname, $address, $city, $postal_code, $phone, $payment_method, $subtotal, $shipping, $total]);
    $order_id = $conn->lastInsertId();

    // Order items
    $itemStmt = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ");
    foreach ($cartItems as $item) {
        $itemStmt->execute([$order_id, $item['product_id'], $item['qty'], $item['price']]);
        
        // Update stock
        $conn->prepare("UPDATE product SET stock = GREATEST(stock - ?, 0) WHERE id = ?")
             ->execute([$item['qty'], $item['product_id']]);
    }

    // Clear cart
    $conn->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);

    // Success message
    set_flash("success", "✅ Your order has been placed successfully!");
    header("Location: ../success.php?order_id=" . $order_id);
    exit;

} else {
    set_flash("danger", "❌ Invalid request method.");
    header("Location: ../checkout.php");
    exit;
}
