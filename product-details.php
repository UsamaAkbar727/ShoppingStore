<?php
require_once("auth/session.php");
check_auth();
include("configshoppingstore.php");

$user_id = $_SESSION['user_id'] ?? 0;
$product_id = $_GET['id'] ?? 0;

if (!$product_id) {
    header("Location: shop.php");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT * FROM `product` WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        header("Location: shop.php");
        exit();
    }
} catch (\Throwable $th) {
    die("Error: " . $th->getMessage());
}

$site_title = $product['productName'] . " | FashionStore";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { serif: ['Playfair Display', 'serif'], sans: ['Inter', 'sans-serif'] },
                    colors: { luxury: '#1a1a1a', gold: '#c5a059', silver: '#f8f9fa' }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap');
        body { background-color: #0a0a0a; color: #fff; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="font-sans">
    <?php include 'components/header.php'; ?>

    <main class="pt-40 pb-20 px-6">
        <div class="container mx-auto max-w-7xl">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20">
                <!-- Product Image -->
                <div class="relative group">
                    <div class="aspect-[4/5] overflow-hidden rounded-3xl glass p-4">
                        <?php 
                        $imgPath = (strpos($product["file"], 'http') === 0) ? htmlspecialchars($product["file"]) : $base_url . "admin/uploads/" . htmlspecialchars($product["file"]);
                        ?>
                        <img src="<?php echo $imgPath; ?>" alt="<?php echo htmlspecialchars($product['productName']); ?>" class="w-full h-full object-cover rounded-2xl transition-transform duration-700 group-hover:scale-105">
                    </div>
                    <!-- Badge -->
                    <?php if ($product['stock'] < 5 && $product['stock'] > 0): ?>
                        <div class="absolute top-10 right-10 bg-red-600/80 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest px-6 py-3 rounded-full">Limited Stock</div>
                    <?php endif; ?>
                </div>

                <!-- Product Info -->
                <div class="flex flex-col justify-center space-y-12">
                    <div class="space-y-4">
                        <span class="text-gold text-xs uppercase tracking-[0.8em] font-black"><?php echo htmlspecialchars($product['category']); ?></span>
                        <h1 class="font-serif text-6xl text-white leading-tight"><?php echo htmlspecialchars($product['productName']); ?></h1>
                        <div class="flex items-center gap-6">
                            <?php if ($product['discountedPrice']): ?>
                                <span class="text-4xl font-serif text-gold">$<?php echo number_format($product['discountedPrice'], 2); ?></span>
                                <span class="text-2xl font-light text-gray-500 line-through">$<?php echo number_format($product['price'], 2); ?></span>
                            <?php else: ?>
                                <span class="text-4xl font-serif text-white">$<?php echo number_format($product['price'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="prose prose-invert prose-lg">
                            <p class="text-gray-400 font-light leading-relaxed">
                                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-8 text-xs uppercase tracking-widest font-black text-gray-500">
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full <?php echo $product['stock'] > 0 ? 'bg-green-500' : 'bg-red-500'; ?>"></span>
                                <span><?php echo $product['stock'] > 0 ? 'In Stock' : 'Sold Out'; ?></span>
                            </div>
                            <span class="w-1 h-1 bg-white/10 rounded-full"></span>
                            <span>SKU: FS-<?php echo str_pad($product['id'], 5, '0', STR_PAD_LEFT); ?></span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center border border-white/10 rounded-full overflow-hidden">
                                <button onclick="updateQty(-1)" class="px-6 py-4 hover:bg-white/5 transition-all text-gray-400"><i class="fas fa-minus"></i></button>
                                <input type="number" id="qty" value="1" min="1" max="<?php echo $product['stock']; ?>" class="w-16 bg-transparent text-center focus:outline-none font-bold">
                                <button onclick="updateQty(1)" class="px-6 py-4 hover:bg-white/5 transition-all text-gray-400"><i class="fas fa-plus"></i></button>
                            </div>
                            <button onclick="addToCart(<?php echo $product['id']; ?>)" class="flex-grow py-6 bg-gold text-white text-xs font-black uppercase tracking-[0.4em] rounded-full hover:shadow-[0_0_40px_rgba(197,160,89,0.3)] transition-all transform hover:-translate-y-1">
                                Add to Archive
                            </button>
                        </div>
                        <button onclick="buyNow(<?php echo $product['id']; ?>)" class="w-full py-6 border border-white/20 text-white text-xs font-black uppercase tracking-[0.4em] rounded-full hover:bg-white hover:text-black transition-all">
                            Immediate Acquisition
                        </button>
                    </div>

                    <!-- Trust Points -->
                    <div class="grid grid-cols-2 gap-8 pt-12 border-t border-white/5">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-shipping-fast text-gold"></i>
                            <span class="text-[10px] uppercase tracking-widest text-gray-400">Global Express Delivery</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <i class="fas fa-shield-alt text-gold"></i>
                            <span class="text-[10px] uppercase tracking-widest text-gray-400">Secure Encrypted Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>

    <script>
        function updateQty(val) {
            const qtyInput = document.getElementById('qty');
            let current = parseInt(qtyInput.value);
            current += val;
            if (current < 1) current = 1;
            if (current > <?php echo $product['stock']; ?>) current = <?php echo $product['stock']; ?>;
            qtyInput.value = current;
        }

        async function addToCart(id) {
            const qty = document.getElementById('qty').value;
            const res = await fetch('add-to-cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&qty=${qty}`
            });
            const data = await res.json();
            if (data.success) {
                alert('Piece added to your archive.');
                window.location.reload();
            } else {
                alert(data.message || 'Error adding to cart.');
            }
        }

        function buyNow(id) {
            const qty = document.getElementById('qty').value;
            window.location.href = `checkout.php?direct_id=${id}&qty=${qty}`;
        }
    </script>
</body>
</html>
