<?php
require_once("auth/session.php");
check_auth();
include("configshoppingstore.php");
include("components/product_card_user.php");

$user_id = $_SESSION['user_id'] ?? 0;
$category = "Men";

$products = [];
try {
    $stmt = $conn->prepare("SELECT * FROM `product` WHERE category = ? ORDER BY id DESC");
    $stmt->execute([$category]);
    $products = $stmt->fetchAll();
} catch (\Throwable $th) {
    $error = $th->getMessage();
}

$site_title = "MEN'S COLLECTION | FashionStore";
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
    <main class="pt-32 pb-20 px-6">
        <div class="container mx-auto max-w-7xl">
            <!-- Back Button -->
            <a href="index.php" class="inline-flex items-center gap-3 text-xs uppercase tracking-[0.3em] text-gray-400 hover:text-gold transition-colors mb-12 group">
                <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Back to Home
            </a>
            <div class="mb-16">
                <span class="text-gold text-xs uppercase tracking-[0.6em] font-black">Archive 01</span>
                <h1 class="font-serif text-6xl text-white italic">Men's <span class="text-gold">Sartorial</span></h1>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php foreach ($products as $value): 
                    $imgPath = (strpos($value["file"], 'http') === 0) ? htmlspecialchars($value["file"]) : $base_url . "admin/uploads/" . htmlspecialchars($value["file"]);
                    print_card_user($imgPath, $value["category"], $value["productName"], $value["description"], $value["price"], $value["discountedPrice"], $value["stock"], $value["id"], false);
                endforeach; ?>
            </div>
        </div>
    </main>
    <?php include 'components/footer.php'; ?>
</body>
</html>
