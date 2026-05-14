<?php
require 'vendor/autoload.php';
require_once("configshoppingstore.php");

// Stripe secret key should be set in environment variable:
// STRIPE_SECRET_KEY
\Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY') ?: 'YOUR_STRIPE_KEY');

header('Content-Type: application/json');

// Receive product_id via URL/POST (no cookies).
$product_id = (int) ($_GET['product_id'] ?? $_POST['product_id'] ?? 0);
$qty = (int) ($_GET['qty'] ?? $_POST['qty'] ?? 1);
if ($qty < 1)
    $qty = 1;

if ($product_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or invalid product_id']);
    exit;
}

try {
    // Fetch secure price from DB
    $stmt = $conn->prepare("SELECT id, price FROM product WHERE id = ? LIMIT 1");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
        exit;
    }

    $unitPrice = (float) $product['price'];
    // Stripe expects cents (integer)
    $unitAmountCents = (int) round($unitPrice * 100);

    $YOUR_DOMAIN = 'http://localhost:3000';

    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [
            [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $unitAmountCents,
                    'product_data' => [
                        'name' => 'Order from Online Shopping',
                    ],
                ],
                'quantity' => $qty,
            ]
        ],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/success.php?id=' . urlencode((string) $product_id),
        'cancel_url' => $YOUR_DOMAIN . '/cancel.php',
    ]);

    // Redirect to the Stripe Checkout URL directly
    if ($checkout_session->url) {
        header("Location: " . $checkout_session->url);
        exit;
    } else {
        echo json_encode(['id' => $checkout_session->id]);
    }
} catch (Exception $e) {
    // For local development without a valid Stripe key, allow a mock redirect for testing
    if (strpos($e->getMessage(), 'Invalid API Key') !== false || strpos($e->getMessage(), 'YOUR_STRIPE_KEY') !== false) {
        $mock_success_url = $YOUR_DOMAIN . '/success.php?id=' . urlencode((string) $product_id) . '&mock=true';
        header("Location: " . $mock_success_url);
        exit;
    }

    echo "
    <div style='font-family:sans-serif; text-align:center; padding: 50px;'>
        <h2 style='color: #c5a059;'>Checkout Configuration Needed</h2>
        <p>Stripe API key is not configured or invalid.</p>
        <p style='color: #666;'>Error: " . $e->getMessage() . "</p>
        <a href='index.php' style='color: #c5a059; text-decoration: none;'>Return to Store</a>
    </div>";
}
?>