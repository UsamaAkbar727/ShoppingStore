<?php
require_once("auth/session.php");
check_auth();
include("configshoppingstore.php");
require 'vendor/autoload.php';

$user_id = $_SESSION['user_id'];

// Check if checking out single item or cart
$product_id = (int)($_GET['product_id'] ?? $_POST['product_id'] ?? 0);
$qty = (int)($_GET['qty'] ?? $_POST['qty'] ?? 1);
if ($qty < 1) $qty = 1;

$checkout_items = [];
$is_single = ($product_id > 0);

if ($is_single) {
    // Single product checkout
    try {
        $stmt = $conn->prepare("SELECT * FROM product WHERE id = ? LIMIT 1");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            $product['checkout_qty'] = $qty;
            $checkout_items[] = $product;
        }
    } catch (\Throwable $th) {
        error_log($th->getMessage());
    }
} else {
    // Cart checkout
    try {
        $stmt = $conn->prepare("
            SELECT p.*, c.quantity as checkout_qty 
            FROM cart c
            JOIN product p ON p.id = c.product_id
            WHERE c.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $checkout_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
        error_log($th->getMessage());
    }
}

// Redirect back if no items to checkout
if (empty($checkout_items)) {
    header("Location: shop.php");
    exit;
}

// Calculate totals
$subtotal = 0;
foreach ($checkout_items as $item) {
    $price = $item['discountedPrice'] ?: $item['price'];
    $subtotal += $price * $item['checkout_qty'];
}
$shipping = $subtotal > 1000 ? 0 : 150;
$total = $subtotal + $shipping;

$error_msg = "";

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $fullname = trim($_POST['fullname'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $payment_method = $_POST['payment_method'] ?? 'cod'; // 'cod' or 'stripe'

    if (empty($fullname) || empty($address) || empty($phone)) {
        $error_msg = "Please fill in all required fields.";
    } else {
        try {
            $conn->beginTransaction();

            // 1. Create order
            $orderStmt = $conn->prepare("
                INSERT INTO orders (user_id, fullname, address, city, postal_code, phone, payment_method, subtotal, shipping, total, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");
            $orderStmt->execute([$user_id, $fullname, $address, $city, $postal_code, $phone, $payment_method, $subtotal, $shipping, $total]);
            $order_id = $conn->lastInsertId();

            // 2. Create order items
            $itemStmt = $conn->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ");
            foreach ($checkout_items as $item) {
                $itemPrice = $item['discountedPrice'] ?: $item['price'];
                $itemStmt->execute([$order_id, $item['id'], $item['checkout_qty'], $itemPrice]);
            }

            $conn->commit();

            // 3. Process payment redirection
            if ($payment_method === 'cod') {
                header("Location: success.php?order_id=" . $order_id);
                exit;
            } else {
                // For Stripe, initialize redirect session
                $YOUR_DOMAIN = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $base_url;
                
                try {
                    $stripe_key = getenv('STRIPE_SECRET_KEY');
                    if (empty($stripe_key) || $stripe_key === 'YOUR_STRIPE_KEY') {
                        throw new \Exception("Stripe is not configured on this server yet. Please use Cash on Delivery or configure your keys.");
                    }

                    \Stripe\Stripe::setApiKey($stripe_key);
                    \Stripe\Stripe::setVerifySslCerts(false);

                    $line_items = [];
                    foreach ($checkout_items as $item) {
                        $itemPrice = $item['discountedPrice'] ?: $item['price'];
                        $line_items[] = [
                            'price_data' => [
                                'currency' => 'usd',
                                'unit_amount' => (int)round($itemPrice * 100),
                                'product_data' => [
                                    'name' => $item['productName'],
                                    'description' => $item['category'],
                                ],
                            ],
                            'quantity' => $item['checkout_qty'],
                        ];
                    }

                    // Add shipping as a line item if not complimentary
                    if ($shipping > 0) {
                        $line_items[] = [
                            'price_data' => [
                                'currency' => 'usd',
                                'unit_amount' => (int)round($shipping * 100),
                                'product_data' => [
                                    'name' => 'Shipping & Handling',
                                ],
                            ],
                            'quantity' => 1,
                        ];
                    }

                    $checkout_session = \Stripe\Checkout\Session::create([
                        'payment_method_types' => ['card'],
                        'line_items' => $line_items,
                        'mode' => 'payment',
                        'success_url' => $YOUR_DOMAIN . 'success.php?order_id=' . $order_id . '&session_id={CHECKOUT_SESSION_ID}',
                        'cancel_url' => $YOUR_DOMAIN . 'cancel.php?order_id=' . $order_id,
                    ]);

                    if ($checkout_session->url) {
                        header("Location: " . $checkout_session->url);
                        exit;
                    }
                } catch (\Exception $stripe_err) {
                    throw $stripe_err;
                }
            }
        } catch (\Throwable $err) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            $error_msg = "Transaction failed: " . $err->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Dossier | FashionStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        luxury: '#1a1a1a',
                        gold: '#c5a059',
                        silver: '#f8f9fa'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap');
        body { background-color: #0a0a0a; color: #fff; }
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .luxury-input {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white !important;
            transition: all 0.3s ease;
        }
        .luxury-input:focus {
            border-color: #c5a059;
            background: rgba(255, 255, 255, 0.08);
            outline: none;
            box-shadow: 0 0 15px rgba(197, 160, 89, 0.15);
        }
        .payment-option {
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .payment-option.active {
            border-color: #c5a059;
            background-color: rgba(197, 160, 89, 0.05);
        }
    </style>
</head>
<body class="font-sans overflow-x-hidden">
    <?php include("components/header.php"); ?>

    <main class="min-h-screen pt-32 pb-20 px-6">
        <div class="container mx-auto max-w-6xl">
            <!-- Back Button -->
            <a href="javascript:history.back()" class="inline-flex items-center gap-3 text-xs uppercase tracking-[0.3em] text-gray-400 hover:text-gold transition-colors mb-8 group">
                <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Return to Cart
            </a>
            <div class="flex flex-col lg:flex-row gap-12 items-start">
                
                <!-- Left Column: Shipping Dossier -->
                <div class="w-full lg:w-3/5 space-y-8">
                    <div class="space-y-4">
                        <span class="text-gold text-xs uppercase tracking-[0.6em] font-black">Checkout</span>
                        <h1 class="font-serif text-5xl text-white leading-tight">Complete Your <span class="italic text-gold">Acquisition</span></h1>
                    </div>

                    <?php if (!empty($error_msg)): ?>
                        <div class="p-4 bg-red-500/10 border-l-4 border-red-500 text-red-400 text-xs uppercase tracking-widest font-black">
                            ⚠ <?php echo htmlspecialchars($error_msg); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-8 glass rounded-3xl p-8 md:p-12">
                        <h3 class="font-serif text-2xl text-white border-b border-white/10 pb-4 mb-8">Shipping Dossier</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Full Name *</label>
                                <input type="text" name="fullname" required placeholder="Alexander Sterling" class="w-full px-6 py-4 rounded-xl luxury-input text-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Phone Number *</label>
                                <input type="tel" name="phone" required placeholder="+1 (555) 019-2834" class="w-full px-6 py-4 rounded-xl luxury-input text-sm">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Street Address *</label>
                            <input type="text" name="address" required placeholder="124 Luxury Blvd, Floor 7" class="w-full px-6 py-4 rounded-xl luxury-input text-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">City *</label>
                                <input type="text" name="city" required placeholder="New York" class="w-full px-6 py-4 rounded-xl luxury-input text-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Postal Code</label>
                                <input type="text" name="postal_code" placeholder="10001" class="w-full px-6 py-4 rounded-xl luxury-input text-sm">
                            </div>
                        </div>

                        <h3 class="font-serif text-2xl text-white border-b border-white/10 pb-4 pt-6 mb-8">Payment Registry</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <label class="payment-option active rounded-2xl p-6 cursor-pointer flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <input type="radio" name="payment_method" value="cod" checked class="hidden" onchange="togglePayment(this)">
                                    <div class="w-5 h-5 rounded-full border border-gold flex items-center justify-center p-1">
                                        <div class="w-full h-full bg-gold rounded-full inner-radio"></div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-widest text-white">Cash on Delivery</h4>
                                        <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1">Pay when item arrives</p>
                                    </div>
                                </div>
                                <i class="fas fa-truck text-gold text-lg"></i>
                            </label>

                            <label class="payment-option rounded-2xl p-6 cursor-pointer flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <input type="radio" name="payment_method" value="stripe" class="hidden" onchange="togglePayment(this)">
                                    <div class="w-5 h-5 rounded-full border border-white/20 flex items-center justify-center p-1">
                                        <div class="w-full h-full bg-transparent rounded-full inner-radio"></div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-widest text-white">Credit Card (Stripe)</h4>
                                        <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1">Instant secure checkout</p>
                                    </div>
                                </div>
                                <i class="fas fa-credit-card text-gold text-lg"></i>
                            </label>
                        </div>

                        <div class="pt-6">
                            <button type="submit" name="place_order" class="w-full py-6 bg-gold text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-xl hover:shadow-[0_0_30px_rgba(197,160,89,0.3)] transition-all duration-500">
                                Confirm Acquisition
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="w-full lg:w-2/5 space-y-8 sticky top-32">
                    <div class="glass rounded-3xl p-8 space-y-6">
                        <h3 class="font-serif text-2xl text-white border-b border-white/10 pb-4">Acquisition Inventory</h3>
                        
                        <div class="divide-y divide-white/5 max-h-[380px] overflow-y-auto pr-2 custom-scrollbar">
                            <?php foreach ($checkout_items as $item): 
                                $price = $item['discountedPrice'] ?: $item['price'];
                                $imgPath = (strpos($item["file"], 'http') === 0) 
                                    ? htmlspecialchars($item["file"]) 
                                    : $base_url . "admin/uploads/" . htmlspecialchars($item["file"]);
                            ?>
                                <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
                                    <div class="w-16 h-20 rounded-xl overflow-hidden bg-white/5 border border-white/10 shrink-0">
                                        <img src="<?php echo $imgPath; ?>" alt="<?php echo htmlspecialchars($item['productName']); ?>" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-grow">
                                        <span class="text-[8px] text-gold uppercase tracking-widest"><?php echo htmlspecialchars($item['category']); ?></span>
                                        <h4 class="font-serif text-base text-white line-clamp-1"><?php echo htmlspecialchars($item['productName']); ?></h4>
                                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">Qty: <?php echo $item['checkout_qty']; ?></p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="font-serif text-sm text-white">$<?php echo number_format($price * $item['checkout_qty'], 2); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="space-y-4 pt-6 border-t border-white/10 text-[10px] uppercase tracking-widest">
                            <div class="flex justify-between items-center text-gray-400">
                                <span>Subtotal</span>
                                <span class="text-white font-serif text-sm">$<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div class="flex justify-between items-center text-gray-400">
                                <span>Shipping & Handling</span>
                                <span class="text-white font-serif text-sm"><?php echo $shipping > 0 ? '$' . number_format($shipping, 2) : 'Complimentary'; ?></span>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t border-white/10 text-xs font-black">
                                <span class="text-white">Grand Investment</span>
                                <span class="text-gold font-serif text-base">$<?php echo number_format($total, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script>
        function togglePayment(elem) {
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('active');
                const inner = option.querySelector('.inner-radio');
                inner.classList.add('bg-transparent');
                inner.classList.remove('bg-gold');
                option.querySelector('input[type="radio"]').checked = false;
            });
            
            const label = elem.closest('.payment-option');
            label.classList.add('active');
            const inner = label.querySelector('.inner-radio');
            inner.classList.remove('bg-transparent');
            inner.classList.add('bg-gold');
            elem.checked = true;
        }
    </script>
    <?php include("components/footer.php"); ?>
</body>
</html>