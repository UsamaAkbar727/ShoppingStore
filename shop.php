<?php
require_once("auth/session.php");
check_auth();
include("configshoppingstore.php");
include("components/product_card_user.php");

$user_id = $_SESSION['user_id'] ?? 0;
$category_filter = $_GET['category'] ?? 'All';

$query = "SELECT * FROM `product`";
$params = [];
if ($category_filter !== 'All') {
    $query .= " WHERE category = ?";
    $params[] = $category_filter;
}
$query .= " ORDER BY id DESC";

try {
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
} catch (\Throwable $th) {
    $error = $th->getMessage();
}

$site_title = "SHOP ALL | FashionStore";
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

        body {
            background-color: #0a0a0a;
            color: #fff;
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .filter-btn.active {
            background-color: #c5a059;
            color: white;
            border-color: #c5a059;
        }
    </style>
</head>

<body class="font-sans">
    <?php include 'components/header.php'; ?>

    <main class="pt-32 pb-20 px-6">
        <div class="container mx-auto max-w-7xl">
            <!-- Back Button -->
            <a href="index.php"
                class="inline-flex items-center gap-3 text-xs uppercase tracking-[0.3em] text-gray-400 hover:text-gold transition-colors mb-12 group">
                <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Back to Home
            </a>
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
                <div class="space-y-4">
                    <span class="text-gold text-xs uppercase tracking-[0.6em] font-black">Digital Boutique</span>
                    <h1 class="font-serif text-6xl text-white italic">Shop <span class="text-gold">Archives</span></h1>
                </div>

                <!-- Category Filter -->
                <div class="flex flex-wrap gap-4">
                    <a href="shop.php?category=All"
                        class="filter-btn px-8 py-3 rounded-full border border-white/10 text-[10px] uppercase tracking-widest font-bold transition-all <?php echo $category_filter === 'All' ? 'active' : 'hover:bg-white/5'; ?>">All
                        Pieces</a>
                    <a href="shop.php?category=Men"
                        class="filter-btn px-8 py-3 rounded-full border border-white/10 text-[10px] uppercase tracking-widest font-bold transition-all <?php echo $category_filter === 'Men' ? 'active' : 'hover:bg-white/5'; ?>">Men</a>
                    <a href="shop.php?category=Women"
                        class="filter-btn px-8 py-3 rounded-full border border-white/10 text-[10px] uppercase tracking-widest font-bold transition-all <?php echo $category_filter === 'Women' ? 'active' : 'hover:bg-white/5'; ?>">Women</a>
                    <a href="shop.php?category=Accessories"
                        class="filter-btn px-8 py-3 rounded-full border border-white/10 text-[10px] uppercase tracking-widest font-bold transition-all <?php echo $category_filter === 'Accessories' ? 'active' : 'hover:bg-white/5'; ?>">Accessories</a>
                </div>
            </div>

            <?php if (empty($products)): ?>
                <div class="glass rounded-3xl p-20 text-center">
                    <p class="text-gray-400 text-lg italic">The archive is currently being updated. No pieces found in this
                        category.</p>
                </div>
            <?php else: ?>
                <!-- Product Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php
                    foreach ($products as $value) {
                        $iscarted = false;
                        if ($user_id > 0) {
                            $cartStmt = $conn->prepare("SELECT 1 FROM `cart` WHERE product_id = ? AND user_id = ?");
                            $cartStmt->execute([$value["id"], $user_id]);
                            $iscarted = (bool) $cartStmt->fetch();
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
                            (int) $value["id"],
                            $iscarted
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