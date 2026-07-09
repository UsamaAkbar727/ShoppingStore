<?php
require_once("auth/session.php");
include("./configshoppingstore.php");

$order_id = (int)($_GET['order_id'] ?? 0);
$order = null;
$order_items = [];

if ($order_id > 0) {
    try {
        // Fetch order
        $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$order_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            // If the order is pending, process the successful acquisition (stock & cart)
            if ($order['status'] === 'pending') {
                $conn->beginTransaction();

                // 1. Update order status to 'processing'
                $update = $conn->prepare("UPDATE orders SET status = 'processing' WHERE id = ?");
                $update->execute([$order_id]);
                $order['status'] = 'processing'; // Update local array as well

                // 2. Fetch order items
                $itemsStmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
                $itemsStmt->execute([$order_id]);
                $order_items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

                // 3. Update stock for each product
                $stockUpdate = $conn->prepare("UPDATE product SET stock = GREATEST(stock - ?, 0) WHERE id = ?");
                foreach ($order_items as $item) {
                    $stockUpdate->execute([$item['quantity'], $item['product_id']]);
                }

                // 4. Clear user's cart (since they have placed a successful order)
                // Note: We clear the entire cart because they just checked out
                $clearCart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $clearCart->execute([$order['user_id']]);

                $conn->commit();
            } else {
                // Fetch items for rendering anyway
                $itemsStmt = $conn->prepare("
                    SELECT oi.*, p.productName, p.file, p.category 
                    FROM order_items oi
                    JOIN product p ON p.id = oi.product_id
                    WHERE oi.order_id = ?
                ");
                $itemsStmt->execute([$order_id]);
                $order_items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    } catch (\Throwable $th) {
        if (isset($conn) && $conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log('Success confirmation error: ' . $th->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acquisition Successful | FashionStore</title>
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
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .checkmark-circle {
            stroke: #c5a059;
            stroke-width: 2;
            fill: none;
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-linecap: round;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        .checkmark-check {
            transform-origin: 50% 50%;
            stroke: #c5a059;
            stroke-width: 2;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            stroke-linecap: round;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards;
        }
        @keyframes stroke {
            100% { stroke-dashoffset: 0; }
        }
    </style>
</head>
<body class="font-sans overflow-x-hidden">
    <?php include("components/header.php"); ?>

    <main class="min-h-screen pt-40 pb-20 px-6 flex items-center justify-center">
        <div class="container mx-auto max-w-2xl text-center">
            
            <div class="glass rounded-3xl p-8 md:p-16 space-y-10">
                <!-- Checkmark Animation -->
                <div class="flex justify-center">
                    <svg class="w-24 h-24" viewBox="0 0 52 52">
                        <circle class="checkmark-circle" cx="26" cy="26" r="25" />
                        <path class="checkmark-check" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                    </svg>
                </div>

                <div class="space-y-4">
                    <span class="text-gold text-xs uppercase tracking-[0.6em] font-black">Acquisition Confirmed</span>
                    <h1 class="font-serif text-5xl text-white">Payment Successful</h1>
                    <p class="text-gray-400 text-sm max-w-md mx-auto leading-relaxed">
                        Thank you for your investment. Your dynamic requisition order has been securely registered in the atelier archive.
                    </p>
                </div>

                <?php if ($order): ?>
                    <!-- Order Dossier Detail Box -->
                    <div class="text-left border-t border-b border-white/10 py-6 my-8 space-y-4 text-xs uppercase tracking-widest text-gray-400">
                        <div class="flex justify-between">
                            <span>Order Identifier</span>
                            <span class="text-white font-bold">#FS-<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Payment Method</span>
                            <span class="text-white font-bold"><?php echo htmlspecialchars($order['payment_method']); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Investment</span>
                            <span class="text-gold font-serif text-sm font-bold">$<?php echo number_format($order['total'], 2); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping Consignee</span>
                            <span class="text-white font-bold"><?php echo htmlspecialchars($order['fullname']); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="index.php" class="px-10 py-5 bg-gold text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-xl hover:shadow-[0_10px_20px_rgba(197,160,89,0.2)] transition-all duration-500">
                        Return to Atelier
                    </a>
                    <a href="auth/user-account.php" class="px-10 py-5 border border-white/20 text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-xl hover:bg-white/5 transition-all duration-500">
                        View Order Dossier
                    </a>
                </div>
            </div>

        </div>
    </main>

    <?php include("components/footer.php"); ?>
</body>
</html>