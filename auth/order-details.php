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
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .dark-header header {
            background-color: rgba(10, 10, 10, 0.9) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }
        .dark-header .nav-link, .dark-header header a, .dark-header header i {
            color: white !important;
        }
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
                <div class="p-8 glass rounded-3xl text-center border-red-500/20 text-red-400">
                    <i class="fas fa-exclamation-triangle text-4xl mb-4 text-red-500"></i>
                    <p class="text-lg uppercase tracking-wider font-semibold"><?php echo htmlspecialchars($error_msg); ?></p>
                </div>
            <?php else: ?>
                
                <!-- ORDER INFORMATION AND SUMMARY -->
                <div class="glass rounded-3xl p-8 md:p-12 space-y-8 mb-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/10 pb-6">
                        <div>
                            <span class="text-gold text-[10px] uppercase tracking-[0.6em] font-black block mb-2">Acquisition Record</span>
                            <h1 class="font-serif text-3xl md:text-4xl text-white">Order #FS-<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></h1>
                            <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider">Registered: <?php echo htmlspecialchars($order['created_at']); ?></p>
                        </div>
                        <div class="text-left md:text-right">
                            <span class="text-[10px] uppercase tracking-widest text-gray-500 font-black block mb-2">Acquisition Status</span>
                            <span class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest bg-gold/20 text-gold border border-gold/30">
                                <?php echo htmlspecialchars($order['status']); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Client shipping details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                        <div class="space-y-3">
                            <h3 class="font-serif text-xl text-white italic">Consignee Details</h3>
                            <div class="text-gray-400 space-y-1">
                                <p class="text-white font-bold"><?php echo htmlspecialchars($order['fullname']); ?></p>
                                <p><?php echo htmlspecialchars($order['phone']); ?></p>
                                <p><?php echo htmlspecialchars($order['address']); ?></p>
                                <p><?php echo htmlspecialchars($order['city']) . ", " . htmlspecialchars($order['postal_code']); ?></p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <h3 class="font-serif text-xl text-white italic">Financial Registry</h3>
                            <div class="text-gray-400 space-y-2">
                                <div class="flex justify-between border-b border-white/5 pb-2">
                                    <span>Payment Method:</span>
                                    <span class="text-white font-semibold uppercase"><?php echo htmlspecialchars($order['payment_method']); ?></span>
                                </div>
                                <div class="flex justify-between border-b border-white/5 pb-2">
                                    <span>Subtotal:</span>
                                    <span class="text-white font-semibold">$<?php echo number_format($order['subtotal'], 2); ?></span>
                                </div>
                                <div class="flex justify-between border-b border-white/5 pb-2">
                                    <span>Shipping Cost:</span>
                                    <span class="text-white font-semibold">$<?php echo number_format($order['shipping'], 2); ?></span>
                                </div>
                                <div class="flex justify-between text-base font-bold text-gold pt-2">
                                    <span>Grand Total:</span>
                                    <span>$<?php echo number_format($order['total'], 2); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==========================================
                     LEARNING ZONE (USER TASK)
                     ==========================================
                     DEAR USER: Here is where you come in! 
                     Below is the container for order items.
                     We have fetched all the order items into the array `$order_items`.
                     
                     YOUR TASK:
                     1. Write a PHP foreach loop to loop through the `$order_items` array.
                     2. Inside the loop, display each product card.
                     3. Include: Product Image, Category, Product Name, Quantity (Qty), and individual Price.
                     4. Style it using Tailwind CSS to match the luxury dark aesthetic of the store.
                     
                     HELPFUL DATA STRUCTURE:
                     Each `$item` in `$order_items` contains:
                     - `$item['productName']` (String) - Name of the product
                     - `$item['quantity']` (Int) - Quantity ordered
                     - `$item['price']` (Float) - Price at purchase time
                     - `$item['category']` (String) - Product category (Men, Women, etc.)
                     - `$item['file']` (String) - Product image filename
                     
                     Let's start your custom code block below!
                     ========================================== -->
                <div class="glass rounded-3xl p-8 md:p-12 space-y-8">
                    <h2 class="font-serif text-2xl text-white italic border-b border-white/10 pb-4">Acquisition Inventory</h2>
                    
                    <div class="grid gap-6">
                        <!-- START: USER WORKSPACE -->
                        
                        <?php if (empty($order_items)): ?>
                            <p class="text-gray-500 italic">No items found for this order.</p>
                        <?php else: ?>
                            <?php foreach ($order_items as $item): 
                                $imgPath = (strpos($item["file"], 'http') === 0) 
                                    ? htmlspecialchars($item["file"]) 
                                    : $base_url . "admin/uploads/" . htmlspecialchars($item["file"]);
                            ?>
                                <!-- Example Card (You can redesign or modify this card to practice!) -->
                                <div class="flex items-center gap-6 p-4 rounded-2xl bg-white/5 border border-white/10 hover:border-gold/30 transition-all duration-300">
                                    <div class="w-20 h-24 rounded-xl overflow-hidden shrink-0 border border-white/5">
                                        <img src="<?php echo $imgPath; ?>" alt="<?php echo htmlspecialchars($item['productName']); ?>" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-grow">
                                        <span class="text-[9px] text-gold uppercase tracking-[0.2em] font-semibold block mb-1"><?php echo htmlspecialchars($item['category']); ?></span>
                                        <h4 class="font-serif text-lg text-white font-bold leading-tight"><?php echo htmlspecialchars($item['productName']); ?></h4>
                                        <p class="text-xs text-gray-400 mt-2 uppercase tracking-wide">Quantity: <?php echo $item['quantity']; ?></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Unit Price</p>
                                        <p class="font-serif text-lg text-white">$<?php echo number_format($item['price'], 2); ?></p>
                                        <p class="text-sm text-gold font-bold mt-1">Total: $<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- END: USER WORKSPACE -->
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>
</body>
</html>
