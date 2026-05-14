<?php 
$current_page = basename($_SERVER['PHP_SELF']); 
$request_uri = $_SERVER['PHP_SELF'];
$is_admin_path = (strpos($request_uri, '/admin/') !== false);
?>

<style>
    .nav-link {
        position: relative;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 500;
        color: #1a1a1a;
        transition: all 0.3s ease;
    }

    .nav-link.text-gold {
        color: #c5a059;
    }
    
    .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 1px;
        bottom: -4px;
        left: 50%;
        background-color: #c5a059;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateX(-50%);
    }
    
    .nav-link:hover::after,
    .nav-link.active::after {
        width: 100%;
    }

    .nav-link:hover,
    .nav-link.active {
        color: #c5a059 !important;
    }
    
    .sticky-nav {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.95);
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }

    .cart-badge {
        font-size: 0.6rem;
        line-height: 1;
        padding: 2px 4px;
    }

    .mobile-menu-item {
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1rem 0;
    }

    .mobile-menu-item.active {
        color: #c5a059;
        font-weight: 600;
    }
</style>

<header class="bg-white border-b border-gray-100 sticky top-0 z-50 sticky-nav transition-all duration-500">


    <?php 
    $current_page = basename($_SERVER['PHP_SELF']); 
    $request_uri = $_SERVER['PHP_SELF'];
    $is_admin_path = (strpos($request_uri, '/admin/') !== false);
    ?>
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-center h-20">
            <!-- Mobile Menu Toggle (Left) -->
            <button class="md:hidden text-luxury" id="mobile-menu-button">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <!-- Brand Logo (Center on Mobile, Left on Desktop) -->
            <div class="flex-shrink-0 absolute left-1/2 md:static transform -translate-x-1/2 md:translate-x-0">
                <a href="<?php echo $base_url; ?>index.php" class="font-serif text-2xl md:text-3xl tracking-tighter hover:text-gold transition-colors duration-300">
                    FASHION<span class="text-gold italic">STORE</span>
                </a>
            </div>

            <!-- Main Navigation (Desktop Only) -->
            <nav class="hidden md:flex items-center space-x-10">
                <a href="<?php echo $base_url; ?>index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a>
                <a href="<?php echo $base_url; ?>shop.php" class="nav-link <?php echo ($current_page == 'shop.php') ? 'active' : ''; ?>">Shop</a>
                <a href="<?php echo $base_url; ?>men.php" class="nav-link <?php echo ($current_page == 'men.php') ? 'active' : ''; ?>">Men</a>
                <a href="<?php echo $base_url; ?>women.php" class="nav-link <?php echo ($current_page == 'women.php') ? 'active' : ''; ?>">Women</a>
                <a href="<?php echo $base_url; ?>accessories.php" class="nav-link <?php echo ($current_page == 'accessories.php') ? 'active' : ''; ?>">Accessories</a>
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <a href="<?php echo $base_url; ?>admin/index.php" class="nav-link text-gold font-bold <?php echo ($is_admin_path) ? 'active' : ''; ?>">
                        <i class="fas fa-user-shield mr-1"></i> Admin
                    </a>
                <?php endif; ?>
            </nav>

            <!-- Icons (Right) -->
            <div class="flex items-center space-x-5 md:space-x-8">
                <!-- Search -->
                <button class="hover:text-gold transition-colors group">
                    <i class="fas fa-search text-lg"></i>
                </button>
                
                <!-- Account -->
                <?php if (!isset($_SESSION["user_id"])): ?>
                    <a href="<?php echo $base_url; ?>auth/login.php" class="hover:text-gold transition-colors group">
                        <i class="far fa-user text-lg"></i>
                    </a>
                <?php else: ?>
                    <a href="<?php echo $base_url; ?>auth/user-account.php" class="hover:text-gold transition-colors group">
                        <i class="fas fa-user-circle text-lg"></i>
                    </a>
                <?php endif; ?>

                <!-- Cart -->
                <?php
                $totalItems = 0;
                $user_id = $_SESSION['user_id'] ?? 0;
                if ($user_id > 0) {
                    try {
                        // Use $root_path set by session.php for a reliable absolute include
                        if (isset($root_path) && file_exists($root_path . 'configshoppingstore.php')) {
                            require_once($root_path . 'configshoppingstore.php');
                        }
                        if (isset($conn)) {
                            $stmt = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
                            $stmt->execute([$user_id]);
                            $totalItems = (int)$stmt->fetchColumn();
                        }
                    } catch (\Throwable $th) {}
                }
                ?>
                <a href="<?php echo $base_url; ?>components/cart.php" class="relative group">
                    <i class="fas fa-shopping-bag text-lg group-hover:text-gold transition-colors"></i>
                    <?php if ($totalItems > 0): ?>
                        <span class="absolute -top-2 -right-2 bg-gold text-white cart-badge rounded-full min-w-[18px] h-[18px] flex items-center justify-center font-bold">
                            <?php echo $totalItems; ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Overlay -->
    <div class="fixed inset-0 bg-white z-[60] transform translate-x-full transition-transform duration-500 ease-in-out md:hidden" id="mobile-overlay">
        <div class="p-8">
            <div class="flex justify-between items-center mb-12">
                <span class="font-serif text-2xl">MENU</span>
                <button id="close-mobile-menu">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div class="flex flex-col space-y-2">
                <a href="<?php echo $base_url; ?>index.php" class="mobile-menu-item text-lg font-light tracking-widest uppercase <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a>
                <a href="<?php echo $base_url; ?>shop.php" class="mobile-menu-item text-lg font-light tracking-widest uppercase <?php echo ($current_page == 'shop.php') ? 'active' : ''; ?>">Shop</a>
                <a href="<?php echo $base_url; ?>men.php" class="mobile-menu-item text-lg font-light tracking-widest uppercase <?php echo ($current_page == 'men.php') ? 'active' : ''; ?>">Men</a>
                <a href="<?php echo $base_url; ?>women.php" class="mobile-menu-item text-lg font-light tracking-widest uppercase <?php echo ($current_page == 'women.php') ? 'active' : ''; ?>">Women</a>
                <a href="<?php echo $base_url; ?>accessories.php" class="mobile-menu-item text-lg font-light tracking-widest uppercase <?php echo ($current_page == 'accessories.php') ? 'active' : ''; ?>">Accessories</a>
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <a href="<?php echo $base_url; ?>admin/index.php" class="mobile-menu-item text-lg font-bold tracking-widest uppercase text-gold <?php echo ($is_admin_path) ? 'active' : ''; ?>">Admin Panel</a>
                <?php endif; ?>
            </div>
            
            <div class="mt-20">
                <p class="text-xs text-gray-400 uppercase tracking-widest mb-6">Support</p>
                <div class="flex flex-col space-y-4">
                    <a href="#" class="text-sm">Client Service</a>
                    <a href="#" class="text-sm">Shipping & Returns</a>
                    <a href="#" class="text-sm">Store Locator</a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    // Mobile menu logic
    const menuBtn = document.getElementById('mobile-menu-button');
    const closeBtn = document.getElementById('close-mobile-menu');
    const overlay = document.getElementById('mobile-overlay');

    if (menuBtn && closeBtn && overlay) {
        menuBtn.addEventListener('click', () => {
            overlay.classList.remove('translate-x-full');
            document.body.style.overflow = 'hidden';
        });

        closeBtn.addEventListener('click', () => {
            overlay.classList.add('translate-x-full');
            document.body.style.overflow = 'auto';
        });
    }

    // Header scroll effect
    window.addEventListener('scroll', () => {
        const header = document.querySelector('header');
        if (window.scrollY > 50) {
            header.classList.add('py-1', 'shadow-sm');
        } else {
            header.classList.remove('py-1', 'shadow-sm');
        }
    });
</script>