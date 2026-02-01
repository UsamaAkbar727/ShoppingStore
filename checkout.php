<?php
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('YOUR_STRIPE_KEY'); 

header('Content-Type: application/json');

$totalAmount = $_COOKIE['product_price_to_buy'] * 100; 
$product_id = $_COOKIE['product_id_to_buy']*1;

$YOUR_DOMAIN = 'http://localhost:3000';

try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'unit_amount' => $totalAmount,
                'product_data' => [
                    'name' => 'Order from Online Shopping',
                ],
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/success.php?id='.urldecode($product_id),
        'cancel_url' => $YOUR_DOMAIN . '/cancel.php',
    ]);

    echo json_encode(['id' => $checkout_session->id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>

