<?php
require_once("../auth/session.php");
check_auth();

include("./cart_product_card.php");
include("../configshoppingstore.php");

$user_id = $_SESSION['user_id'] ?? 0;
$res_arr = [];

if ($user_id > 0) {
    try {
        $stmt = $conn->prepare("
            SELECT product.*, cart.quantity as cart_qty 
            FROM cart 
            INNER JOIN product ON cart.product_id = product.id 
            WHERE cart.user_id = ?");
        $stmt->execute([$user_id]);
        $res_arr = $stmt->fetchAll();
    } catch (\Throwable $th) {
        // Do not break UI: log the real error and show safe empty-state UI.
        error_log($th->getMessage());
        $res_arr = [];
    }
} else {
    header("Location: " . $base_url . "auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'favicon.php'; ?>
    <title>Your Archive | FashionStore</title>
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
                        silver: '#f8f9fa',
                        accent: '#e5e7eb'
                    },
                    animation: {
                        'fade-up': 'fadeUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        }
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
        
        /* Seamless Dark Header override */
        .dark-header header {
            background-color: rgba(10, 10, 10, 0.9) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }
        .dark-header .nav-link, .dark-header header a, .dark-header header i {
            color: white !important;
        }
        .dark-header .nav-link:hover { color: #c5a059 !important; }
        .dark-header .sticky-nav { background-color: transparent !important; }
    </style>
</head>

<body class="font-sans overflow-x-hidden dark-header">
    <?php include("./header.php"); ?>

    <main class="min-h-screen pt-32 pb-20 px-6">
        <div class="container mx-auto max-w-7xl">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8 animate-fade-up">
                <div class="space-y-4">
                    <span class="text-gold text-xs uppercase tracking-[0.6em] font-black">Your Selection</span>
                    <h1 class="font-serif text-5xl md:text-6xl text-white">The <span class="italic text-gold">Archive</span></h1>
                </div>
                <div class="flex items-center gap-4 text-gray-500 uppercase tracking-widest text-[10px] font-bold">
                    <span><?php echo count($res_arr); ?> Pieces</span>
                    <span class="w-1 h-1 bg-gold rounded-full"></span>
                    <span>Verified Authenticity</span>
                </div>
            </div>

            <?php if (empty($res_arr)): ?>
                <div class="glass rounded-3xl p-20 text-center animate-fade-up" style="animation-delay: 0.2s;">
                    <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-8 border border-white/10">
                        <i class="fas fa-shopping-bag text-gold text-3xl"></i>
                    </div>
                    <h3 class="font-serif text-3xl text-white mb-4">Your archive is currently vacant.</h3>
                    <p class="text-gray-400 max-w-md mx-auto mb-12 font-light leading-relaxed">Discover our latest collections and start curating your personal digital atelier.</p>
                    <a href="<?php echo $base_url; ?>shop.php" class="inline-block px-12 py-5 bg-gold text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-xl hover:shadow-[0_10px_30px_rgba(197,160,89,0.3)] transition-all duration-500">
                        Explore Collection
                    </a>
                </div>
            <?php else: 
                $subtotal = 0;
                foreach ($res_arr as $value) {
                    $itemPrice = $value["discountedPrice"] ?: $value["price"];
                    $subtotal += $itemPrice * $value["cart_qty"];
                }
                $shipping = $subtotal > 1000 ? 0 : 150;
                $grandTotal = $subtotal + $shipping;
            ?>
                <div class="flex flex-col lg:flex-row gap-12 items-start animate-fade-up" style="animation-delay: 0.2s;">
                    <!-- Cart Items Grid -->
                    <div class="w-full lg:w-3/4 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                        <?php
                        foreach ($res_arr as $value) {
                            $imgPath = (strpos($value["file"], 'http') === 0) 
                                ? htmlspecialchars($value["file"]) 
                                : $base_url . "admin/uploads/" . htmlspecialchars($value["file"]);

                            print_cart_card_user(
                                $imgPath,
                                htmlspecialchars($value["category"]),
                                htmlspecialchars($value["productName"]),
                                htmlspecialchars($value["description"]),
                                htmlspecialchars($value["price"]),
                                htmlspecialchars($value["discountedPrice"]),
                                htmlspecialchars($value["stock"]),
                                (int)$value["id"],
                                (int)$value["cart_qty"]
                            );
                        }
                        ?>
                    </div>

                    <!-- Order Summary Card -->
                    <div class="w-full lg:w-1/4 glass rounded-3xl p-8 space-y-6 sticky top-32">
                        <h3 class="font-serif text-2xl text-white">Summary</h3>
                        <div class="space-y-4 pt-4 border-t border-white/10 text-xs uppercase tracking-wider">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Subtotal</span>
                                <span class="text-white">$<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Shipping</span>
                                <span class="text-white"><?php echo $shipping > 0 ? '$' . number_format($shipping, 2) : 'Complimentary'; ?></span>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t border-white/10 text-sm font-bold">
                                <span>Total</span>
                                <span class="text-gold font-serif text-lg">$<?php echo number_format($grandTotal, 2); ?></span>
                            </div>
                        </div>
                        
                        <a href="<?php echo $base_url; ?>checkout.php" class="block w-full py-5 bg-gold text-white text-[10px] font-black uppercase tracking-[0.3em] text-center rounded-xl hover:shadow-[0_10px_20px_rgba(197,160,89,0.3)] transition-all duration-500 shadow-lg">
                            Checkout Entire Archive
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php 
    if (isset($_GET["action"], $_GET["id"]) && $_GET["action"] === "uncart") {
        $product_id = (int)$_GET["id"];
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        echo "<script>window.location = 'cart.php'</script>";
    }
    ?>

    <?php include("./footer.php"); ?>
</body>

</html>