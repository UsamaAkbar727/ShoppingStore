<?php
require_once("../auth/session.php");
include("../configshoppingstore.php");

// Professional Security Guard
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Handle simple user actions (Block/Unblock) - MUST be before any HTML
if (isset($_GET["id"]) && isset($_GET["action"])) {
    $user_id = (int)$_GET["id"];
    $action = $_GET["action"];
    if ($action === "block") {
        $stmt = $conn->prepare("UPDATE user SET isBlock='1' WHERE id=?");
        $stmt->execute([$user_id]);
    } else {
        $stmt = $conn->prepare("UPDATE user SET isBlock='0' WHERE id=?");
        $stmt->execute([$user_id]);
    }
    header("Location: index.php?page=users");
    exit();
}

$page = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/48/shopping-bag.png">
    <title>Management Portal | FASHIONSTORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Inter:wght@300;400;500;600;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        luxury: '#0a0a0a',
                        gold: '#c5a059',
                        silver: '#f8f9fa'
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #050505; }
        .glass-dark {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .luxury-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #0a0a0a; }
        ::-webkit-scrollbar-thumb { background: #c5a059; }
    </style>
</head>
<body class="font-sans text-white flex overflow-hidden h-screen">

    <!-- Sidebar Component -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden bg-[#0a0a0a]">
        
        <!-- Top Navigation -->
        <header class="h-24 flex items-center justify-between px-10 border-b border-white/5 bg-luxury/50 backdrop-blur-xl">
            <div class="flex items-center gap-6">
                <a href="../index.php" class="text-gray-500 hover:text-gold transition-colors text-sm" title="Back to Shop">
                    <i class="fas fa-arrow-left mr-2"></i>
                </a>
                <div>
                    <h1 class="text-xs font-black uppercase tracking-[0.5em] text-gray-500">System <span class="text-gold">Status</span></h1>
                    <p class="font-serif text-xl italic text-white capitalize"><?php echo str_replace('addproduct', 'Add Product', $page); ?></p>
                </div>
            </div>
            
            <div class="flex items-center gap-8">
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gold">Primary Administrator</p>
                    <p class="text-sm font-bold text-white"><?php echo $_SESSION['user_name']; ?></p>
                </div>
                <div class="w-12 h-12 rounded-full border border-gold/30 p-1">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_name']); ?>&background=c5a059&color=fff" 
                         class="w-full h-full rounded-full object-cover">
                </div>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <main class="flex-1 overflow-y-auto p-10 custom-scrollbar">
            <div class="max-w-7xl mx-auto">
                <?php
                    switch($page) {
                        case 'products':
                            include 'products.php';
                            break;
                        case 'orders':
                            include 'orders.php';
                            break;
                        case 'users':
                            include 'users.php';
                            break;
                        case 'addproduct':
                            include 'admin-product-insert-form.php';
                            break;
                        case 'editproduct':
                            include 'edit-product.php';
                            break;
                        default:
                            include 'dashboard.php';
                    }
                ?>
            </div>
        </main>
    </div>

</body>
</html>