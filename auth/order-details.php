<?php
require_once("session.php");
check_auth();
include("../configshoppingstore.php");

$order_id = (int)($_GET['order_id'] ?? 0);
$order = null;
$order_items = [];
$error_msg = "";

if ($order_id > 0) {
    try {
        // Fetch order details
        $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$order_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            // Security: Ensure logged-in user owns this order
            if ((int)$order['user_id'] !== (int)$_SESSION['user_id']) {
                header("HTTP/1.1 403 Forbidden");
                die("Access Denied: You do not own this order dossier.");
            }

            // Fetch items in the order
            $items_stmt = $conn->prepare("
                SELECT oi.*, p.productName, p.file, p.category, p.description 
                FROM order_items oi 
                JOIN product p ON oi.product_id = p.id 
                WHERE oi.order_id = ?
            ");
            $items_stmt->execute([$order_id]);
            $order_items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $error_msg = "Order not found in the archives.";
        }
    } catch (\Throwable $th) {
        $error_msg = "Error loading order: " . $th->getMessage();
    }
} else {
    $error_msg = "Invalid order identifier.";
}

// Timeline configuration
$status = $order['status'] ?? 'pending';
$steps = [
    'pending' => ['label' => 'Order Placed', 'icon' => 'fa-shopping-bag'],
    'processing' => ['label' => 'Processing', 'icon' => 'fa-cog'],
    'shipped' => ['label' => 'Shipped', 'icon' => 'fa-truck'],
    'delivered' => ['label' => 'Delivered', 'icon' => 'fa-check-circle']
];
$step_keys = array_keys($steps);
$current_index = array_search($status, $step_keys);
if ($current_index === false) {
    $current_index = -1;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acquisition Dossier Details | FashionStore</title>
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
        
        .dark-header header {
            background-color: rgba(10, 10, 10, 0.9) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }
        .dark-header .nav-link, .dark-header header a, .dark-header header i {
            color: white !important;
        }
        .dark-header .nav-link:hover { color: #c5a059 !important; }
    </style>
</head>
<body class="font-sans overflow-x-hidden dark-header">
    <?php include '../components/header.php'; ?>

    <main class="min-h-screen pt-32 pb-20 px-6">
        <div class="container mx-auto max-w-4xl">
            <!-- Return Button -->
            <a href="user-account.php" class="inline-flex items-center gap-3 text-xs uppercase tracking-[0.3em] text-gray-400 hover:text-gold transition-colors mb-8 group">
                <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Return to Atelier
            </a>

            <?php if (!empty($error_msg)): ?>
                <div class="p-8 glass rounded-3xl text-center border border-red-500/20 text-red-400">
                    <i class="fas fa-exclamation-triangle text-4xl mb-4 text-red-500"></i>
                    <p class="text-lg uppercase tracking-wider font-semibold"><?php echo htmlspecialchars($error_msg); ?></p>
                </div>
            <?php else: ?>
                
                <!-- ORDER INFORMATION AND SUMMARY -->
                <div class="glass rounded-3xl p-8 md:p-12 space-y-10 mb-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-8">
                        <div>
                            <span class="text-gold text-[10px] uppercase tracking-[0.6em] font-black block mb-2">Acquisition Record</span>
                            <h1 class="font-serif text-4xl text-white">Order #FS-<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></h1>
                            <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Registered: <?php echo htmlspecialchars($order['created_at']); ?></p>
                        </div>
                        <div class="text-left md:text-right">
                            <span class="text-[10px] uppercase tracking-widest text-gray-500 font-black block mb-2">Acquisition Status</span>
                            <span class="px-5 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-gold/15 text-gold border border-gold/30">
                                <?php echo htmlspecialchars($order['status']); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Requisition Timeline -->
                    <?php if ($status === 'cancelled'): ?>
                        <div class="p-6 bg-red-500/10 border border-red-500/20 rounded-2xl flex items-center gap-4 text-red-400 text-xs uppercase tracking-widest font-black">
                            <i class="fas fa-ban text-lg text-red-500 animate-pulse"></i> This acquisition dossier has been cancelled.
                        </div>
                    <?php else: ?>
                        <div class="py-6 border-b border-white/5">
                            <h3 class="font-serif text-lg text-white italic mb-8">Requisition Route Progress</h3>
                            <div class="relative flex items-center justify-between px-4 md:px-12">
                                <!-- Background Line -->
                                <div class="absolute left-12 right-12 top-5 h-[2px] bg-white/5 -z-10"></div>
                                <!-- Active Fill Line -->
                                <div class="absolute left-12 top-5 h-[2px] bg-gold transition-all duration-700 -z-10" 
                                     style="width: calc(<?php echo ($current_index / 3) * 100; ?>% - 24px);"></div>
                                
                                <?php foreach ($steps as $key => $step): 
                                    $idx = array_search($key, $step_keys);
                                    $is_active = $idx <= $current_index;
                                    $is_current = $idx === $current_index;
                                ?>
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all duration-500 bg-[#0a0a0a]
                                            <?php 
                                                if ($is_current) echo 'border-gold text-gold shadow-[0_0_15px_rgba(197,160,89,0.3)] scale-110';
                                                elseif ($is_active) echo 'border-gold/60 text-gold/80';
                                                else echo 'border-white/10 text-gray-600';
                                            ?>">
                                            <i class="fas <?php echo $step['icon']; ?> text-xs"></i>
                                        </div>
                                        <span class="text-[9px] uppercase tracking-widest font-black text-center transition-colors duration-500
                                            <?php echo $is_active ? 'text-gold font-bold' : 'text-gray-600'; ?>">
                                            <?php echo $step['label']; ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Client shipping details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-sm pt-4">
                        <div class="space-y-4">
                            <h3 class="font-serif text-2xl text-white italic border-b border-white/5 pb-2">Consignee Details</h3>
                            <div class="text-gray-400 space-y-1 text-xs uppercase tracking-wider leading-relaxed">
                                <p class="text-white font-bold text-sm tracking-normal capitalize mb-1"><?php echo htmlspecialchars($order['fullname']); ?></p>
                                <p><i class="fas fa-phone text-gold/70 w-5"></i> <?php echo htmlspecialchars($order['phone']); ?></p>
                                <p><i class="fas fa-map-marker-alt text-gold/70 w-5"></i> <?php echo htmlspecialchars($order['address']); ?></p>
                                <p><i class="fas fa-city text-gold/70 w-5"></i> <?php echo htmlspecialchars($order['city']) . ", " . htmlspecialchars($order['postal_code']); ?></p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <h3 class="font-serif text-2xl text-white italic border-b border-white/5 pb-2">Financial Registry</h3>
                            <div class="text-gray-400 space-y-3 text-xs uppercase tracking-widest">
                                <div class="flex justify-between border-b border-white/5 pb-2">
                                    <span>Payment Method:</span>
                                    <span class="text-white font-semibold uppercase"><?php echo htmlspecialchars($order['payment_method'] === 'cod' ? 'Cash on Delivery' : 'Credit Card (Stripe)'); ?></span>
                                </div>
                                <div class="flex justify-between border-b border-white/5 pb-2">
                                    <span>Subtotal:</span>
                                    <span class="text-white font-semibold font-serif text-sm">$<?php echo number_format($order['subtotal'], 2); ?></span>
                                </div>
                                <div class="flex justify-between border-b border-white/5 pb-2">
                                    <span>Shipping Cost:</span>
                                    <span class="text-white font-semibold font-serif text-sm">$<?php echo number_format($order['shipping'], 2); ?></span>
                                </div>
                                <div class="flex justify-between text-sm font-bold text-gold pt-2">
                                    <span class="font-sans">Grand Total:</span>
                                    <span class="font-serif text-base">$<?php echo number_format($order['total'], 2); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acquisition Items Display -->
                <div class="glass rounded-3xl p-8 md:p-12 space-y-8">
                    <h2 class="font-serif text-3xl text-white italic border-b border-white/10 pb-4">Acquisition Inventory</h2>
                    
                    <div class="grid gap-6">
                        <?php if (empty($order_items)): ?>
                            <p class="text-gray-500 italic">No items registered for this order dossier.</p>
                        <?php else: ?>
                            <?php foreach ($order_items as $item): 
                                $imgPath = (strpos($item["file"], 'http') === 0) 
                                    ? htmlspecialchars($item["file"]) 
                                    : $base_url . "admin/uploads/" . htmlspecialchars($item["file"]);
                            ?>
                                <div class="flex flex-col sm:flex-row items-center gap-6 p-6 rounded-2xl bg-white/[0.01] border border-white/5 hover:border-gold/30 transition-all duration-500 group">
                                    <!-- Image container with luxury border hover effect -->
                                    <div class="w-24 h-32 rounded-xl overflow-hidden shrink-0 border border-white/10 bg-white/5 relative">
                                        <img src="<?php echo $imgPath; ?>" alt="<?php echo htmlspecialchars($item['productName']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="flex-grow text-center sm:text-left space-y-2">
                                        <span class="text-[9px] text-gold uppercase tracking-[0.3em] font-black block"><?php echo htmlspecialchars($item['category']); ?></span>
                                        <h4 class="font-serif text-xl text-white font-bold group-hover:text-gold transition-colors duration-300"><?php echo htmlspecialchars($item['productName']); ?></h4>
                                        <p class="text-xs text-gray-500 line-clamp-1 max-w-md font-light"><?php echo htmlspecialchars($item['description'] ?? 'No detail description provided in collection archives.'); ?></p>
                                        <div class="pt-2 flex items-center justify-center sm:justify-start gap-4">
                                            <span class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[9px] uppercase tracking-wider text-gray-400 font-bold">
                                                Qty: <?php echo $item['quantity']; ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Price details -->
                                    <div class="text-center sm:text-right shrink-0 min-w-[120px] pt-4 sm:pt-0 border-t sm:border-t-0 border-white/5 w-full sm:w-auto">
                                        <p class="text-[9px] text-gray-500 uppercase tracking-widest mb-1 font-bold">Unit Investment</p>
                                        <p class="font-serif text-base text-white mb-2">$<?php echo number_format($item['price'], 2); ?></p>
                                        <p class="text-[9px] text-gold uppercase tracking-widest mb-1 font-bold">Total Details</p>
                                        <p class="font-serif text-xl text-gold italic font-bold">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>
</body>
</html>
