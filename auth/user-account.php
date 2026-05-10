<?php
require_once("session.php");
check_auth();

include("../configshoppingstore.php");

$user_id = $_SESSION['user_id'];
$userData = [];
$orders = [];

try {
    $stmt = $conn->prepare("SELECT fullname, email, file FROM user WHERE id = ?");
    $stmt->execute([$user_id]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    $order_stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
    $order_stmt->execute([$user_id]);
    $orders = $order_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\Throwable $th) {
    error_log($th->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/48/shopping-bag.png">
    <title><?php echo htmlspecialchars($userData['fullname'] ?? 'Profile'); ?> | FashionStore</title>
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
    <?php include '../components/header.php'; ?>

    <main class="min-h-screen pt-32 pb-20 px-6">
        <div class="container mx-auto max-w-5xl">
            <!-- Breadcrumbs -->
            <nav class="flex mb-12 text-[10px] uppercase tracking-[0.4em] text-gray-500 animate-fade-up" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-4">
                    <li><a href="../index.php" class="hover:text-gold transition-colors">Home</a></li>
                    <li><span class="mx-2 text-gold/30">/</span></li>
                    <li class="text-white font-black tracking-[0.6em]">Atelier</li>
                </ol>
            </nav>

            <!-- Profile Dossier Card -->
            <div class="glass rounded-3xl overflow-hidden mb-20 animate-fade-up" style="animation-delay: 0.2s;">
                <div class="p-8 md:p-16">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-12">
                        <!-- Profile Image with Aura -->
                        <div class="shrink-0 relative">
                            <div class="absolute -inset-4 bg-gold/10 rounded-full blur-2xl animate-pulse"></div>
                            <div class="w-40 h-40 md:w-48 md:h-48 rounded-full overflow-hidden border border-white/10 p-2 glass relative z-10">
                                <img 
                                    src="<?php echo htmlspecialchars($userData['file'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($userData['fullname'] ?? 'User') . '&background=c5a059&color=fff&size=200'); ?>" 
                                    alt="Profile" 
                                    class="w-full h-full object-cover rounded-full"
                                >
                            </div>
                        </div>

                        <div class="flex-1 text-center md:text-left space-y-8">
                            <div>
                                <span class="text-gold text-[10px] uppercase tracking-[0.6em] font-black mb-4 block">Identity Dossier</span>
                                <h1 class="font-serif text-4xl md:text-5xl text-white mb-4">
                                    <?php echo htmlspecialchars($userData['fullname'] ?? 'Anonymous Aristocrat'); ?>
                                </h1>
                                <p class="text-gray-400 font-light tracking-[0.2em] text-sm uppercase">
                                    <?php echo htmlspecialchars($userData['email'] ?? ''); ?>
                                </p>
                            </div>

                            <div class="flex flex-wrap justify-center md:justify-start gap-6 pt-4">
                                <a href="edit_profile.php" class="px-8 py-4 bg-white text-luxury text-[10px] font-black uppercase tracking-[0.3em] rounded-xl hover:bg-gold hover:text-white transition-all duration-500 flex items-center gap-3">
                                    <i class="fas fa-edit text-[12px]"></i> Edit Identity
                                </a>
                                <a href="change_password.php" class="px-8 py-4 border border-white/10 text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-xl hover:bg-white/5 transition-all duration-500 flex items-center gap-3">
                                    <i class="fas fa-shield-alt text-[12px]"></i> Security Archives
                                </a>
                                <a href="logout.php" class="px-8 py-4 border border-red-500/20 text-red-500 text-[10px] font-black uppercase tracking-[0.3em] rounded-xl hover:bg-red-500/10 transition-all duration-500 flex items-center gap-3">
                                    <i class="fas fa-power-off text-[12px]"></i> Terminate Session
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acquisition History -->
            <div class="space-y-12 animate-fade-up" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between border-b border-white/5 pb-8">
                    <div class="space-y-1">
                        <span class="text-gold text-[10px] uppercase tracking-[0.6em] font-black block">History</span>
                        <h2 class="font-serif text-3xl text-white italic">Past <span class="text-gold">Acquisitions</span></h2>
                    </div>
                    <span class="px-4 py-2 bg-white/5 rounded-full text-[10px] uppercase tracking-[0.4em] text-gray-400 font-black"><?php echo count($orders); ?> Records</span>
                </div>

                <?php if (!empty($orders)): ?>
                    <div class="grid gap-6">
                        <?php foreach ($orders as $order): ?>
                            <div class="glass rounded-2xl p-8 hover:border-gold/30 transition-all duration-500 group">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                                    <div class="flex items-center gap-8">
                                        <div class="w-20 h-20 bg-white/5 rounded-2xl flex items-center justify-center text-gold group-hover:bg-gold group-hover:text-white transition-all duration-500 border border-white/5">
                                            <i class="fas fa-shopping-bag text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-serif text-2xl text-white group-hover:text-gold transition-colors duration-300 mb-2"><?= htmlspecialchars($order['product_name'] ?? 'Luxury Artifact') ?></h3>
                                            <div class="flex items-center gap-4">
                                                <span class="text-[10px] text-gray-500 uppercase tracking-widest"><?= htmlspecialchars($order['order_date'] ?? 'Ancient Era') ?></span>
                                                <span class="w-1 h-1 bg-gold/30 rounded-full"></span>
                                                <span class="text-[10px] text-gold uppercase tracking-widest font-black">Verified Purchase</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between md:justify-end gap-16 border-t md:border-t-0 pt-6 md:pt-0 border-white/5">
                                        <div class="text-right">
                                            <p class="text-[10px] uppercase tracking-widest text-gray-500 mb-2 font-black">Quantity</p>
                                            <p class="text-xl font-serif text-white"><?= (int)($order['quantity'] ?? 1) ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] uppercase tracking-widest text-gray-500 mb-2 font-black">Investment</p>
                                            <p class="font-serif text-2xl text-white italic">RS <?= number_format($order['total_amount'] ?? 0) ?></p>
                                        </div>
                                        <div class="hidden md:block">
                                            <div class="w-12 h-12 rounded-full border border-white/10 flex items-center justify-center group-hover:bg-gold group-hover:border-gold transition-all duration-500">
                                                <i class="fas fa-chevron-right text-xs group-hover:text-white"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-32 glass rounded-3xl border-dashed border-white/10">
                        <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-10 border border-white/5">
                            <i class="fas fa-archive text-gold/30 text-3xl"></i>
                        </div>
                        <h3 class="font-serif text-3xl text-white mb-4">The archives are vacant</h3>
                        <p class="text-gray-500 text-sm mb-12 max-w-xs mx-auto font-light leading-relaxed">Begin your sartorial journey by exploring our curated collections of excellence.</p>
                        <a href="<?php echo $base_url; ?>components/product.php" class="inline-block px-12 py-5 bg-gold text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-xl hover:shadow-[0_10px_40px_rgba(197,160,89,0.3)] transition-all duration-500">
                            Discover Collection
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>
</body>
</html>
