<?php
require_once("../auth/session.php");
check_auth();
include("./product_card_user.php");
include("../configshoppingstore.php");

$page_title = "The Collection | FashionStore";
$user_id    = $_SESSION['user_id'] ?? 0;

// Handle add-to-cart action
if (isset($_GET["id"]) && isset($_GET["action"])) {
    $product_id = (int)$_GET["id"];
    if ($_GET["action"] === "cart" && $user_id > 0) {
        $stmt = $conn->prepare("INSERT IGNORE INTO `cart`(`user_id`, `product_id`) VALUES (?, ?)");
        $stmt->execute([$user_id, $product_id]);
    }
    header("Location: " . $base_url . "components/product.php");
    exit();
}

$res_arr = [];
try {
    $data = $conn->prepare("SELECT * FROM `product`");
    $data->execute();
    $res = $data->fetchAll();
    if ($res) {
        $res_arr = $res;
    }
} catch (\Throwable $th) {
    $fetch_error = $th->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/48/shopping-bag.png">
    <title>The Collection | FashionStore</title>
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
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8 animate-fade-up">
                <div class="space-y-4">
                    <span class="text-gold text-xs uppercase tracking-[0.6em] font-black">Curated Archives</span>
                    <h1 class="font-serif text-5xl md:text-7xl text-white italic">The <span class="text-gold">Collection</span></h1>
                </div>
                <div class="flex items-center gap-6 text-gray-500 uppercase tracking-widest text-[10px] font-black">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-gold rounded-full animate-pulse"></span>
                        <span><?php echo count($res_arr); ?> Rare Pieces</span>
                    </div>
                    <span class="w-1 h-1 bg-white/10 rounded-full"></span>
                    <span>Direct Atelier Access</span>
                </div>
            </div>

            <?php if (!empty($fetch_error)): ?>
                <div class="glass rounded-2xl p-12 text-center border-red-500/20">
                    <i class="fas fa-exclamation-circle text-red-500 text-3xl mb-4"></i>
                    <p class="text-gray-400">The archives are temporarily inaccessible. Our curators are restoring access.</p>
                </div>
            <?php endif; ?>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 animate-fade-up" style="animation-delay: 0.2s;">
                <?php
                foreach ($res_arr as $value) {
                    $iscarted = false;
                    if ($user_id > 0) {
                        $cartres = $conn->prepare("SELECT 1 FROM `cart` WHERE product_id = ? AND user_id = ?");
                        $cartres->execute([$value["id"], $user_id]);
                        $iscarted = (bool)$cartres->fetch();
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
                        $iscarted
                    );
                }
                ?>
            </div>
        </div>
    </main>

    <?php include("./footer.php"); ?>
</body>

</html>