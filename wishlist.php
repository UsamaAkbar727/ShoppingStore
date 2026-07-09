<?php
require_once("auth/session.php");
check_auth();
include("configshoppingstore.php");
include("components/product_card_user.php");

$user_id = $_SESSION['user_id'];

// Fetch wishlisted products
try {
    $stmt = $conn->prepare("
        SELECT p.* FROM product p
        JOIN wishlist w ON p.id = w.product_id
        WHERE w.user_id = ?
        ORDER BY w.id DESC
    ");
    $stmt->execute([$user_id]);
    $wishlist_items = $stmt->fetchAll();
} catch (\Throwable $th) {
    $error = $th->getMessage();
}

$site_title = "YOUR FAVORITES | FashionStore";
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
    </style>
</head>
<body class="font-sans">
    <?php include 'components/header.php'; ?>

    <main class="pt-32 pb-20 px-6 min-h-screen">
         <div class="container mx-auto max-w-7xl">
            <!-- Back Button -->
            <a href="javascript:history.back()" class="inline-flex items-center gap-3 text-xs uppercase tracking-[0.3em] text-gray-400 hover:text-gold transition-colors mb-12 group">
                <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Go Back
            </a>
            <div class="mb-16 space-y-4">
                <span class="text-gold text-xs uppercase tracking-[0.6em] font-black">Personal Archive</span>
                <h1 class="font-serif text-6xl text-white italic">Your <span class="text-gold">Favorites</span></h1>
            </div>

            <?php if (empty($wishlist_items)): ?>
                <div class="glass rounded-3xl p-20 text-center">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-8">
                        <i class="far fa-heart text-gold text-3xl"></i>
                    </div>
                    <p class="text-gray-400 text-lg italic mb-10">Your favorite archive is currently empty.</p>
                    <a href="shop.php" class="px-12 py-5 bg-gold text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-full hover:shadow-[0_0_40px_rgba(197,160,89,0.4)] transition-all duration-500">
                        Explore Collection
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php
                    foreach ($wishlist_items as $value) {
                        $iscarted = false;
                        if ($user_id > 0) {
                            $cartStmt = $conn->prepare("SELECT 1 FROM `cart` WHERE product_id = ? AND user_id = ?");
                            $cartStmt->execute([$value["id"], $user_id]);
                            $iscarted = (bool)$cartStmt->fetch();
                        }

                        $imgPath = (strpos($value["file"], 'http') === 0) 
                            ? htmlspecialchars($value["file"]) 
                            : $base_url . "admin/uploads/" . htmlspecialchars($value["file"]);

                        print_card_user(
                            $imgPath,
                            htmlspecialchars($value["category"]),
                            htmlspecialchars($value["productName"]),
                            htmlspecialchars($value["description"]),
                            htmlspecialchars($value["price"]),
                            htmlspecialchars($value["discountedPrice"]),
                            htmlspecialchars($value["stock"]),
                            (int)$value["id"],
                            $iscarted,
                            true // iswishlisted
                        );
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>
</html>
