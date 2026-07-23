<?php
$current_page = basename($_SERVER['PHP_SELF']);
$request_uri = $_SERVER['PHP_SELF'];
$is_admin_path = (strpos($request_uri, '/admin/') !== false);
?>

<style>
    html,
    body {
        overflow-x: hidden !important;
        width: 100% !important;
    }

    .nav-link {
        position: relative;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 600;
        color: #1a1a1a;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 0.5rem 0;
    }

    .nav-link.text-gold {
        color: #c5a059;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 1.5px;
        bottom: 0;
        left: 0;
        background: linear-gradient(90deg, #c5a059, #f9dfa5);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        width: 100%;
    }

    .nav-link:hover,
    .nav-link.active {
        color: #c5a059 !important;
        text-shadow: 0 0 15px rgba(197, 160, 89, 0.2);
    }

    /* Dropdown Aesthetics */
    .nav-dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        min-width: 220px;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(197, 160, 89, 0.1);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 1.5rem 0;
        z-index: 100;
        border-radius: 4px;
    }

    .nav-dropdown:hover .dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(10px);
    }

    .dropdown-item {
        display: block;
        padding: 0.8rem 2rem;
        color: #1a1a1a;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .dropdown-item:hover {
        background: rgba(197, 160, 89, 0.05);
        color: #c5a059;
        padding-left: 2.5rem;
    }

    .sticky-nav {
        backdrop-filter: blur(15px);
        background-color: rgba(255, 255, 255, 0.85);
        border-bottom: 1px solid rgba(0, 0, 0, 0.03);
    }

    .cart-badge {
        font-size: 0.55rem;
        line-height: 1;
        padding: 0;
        box-shadow: 0 4px 10px rgba(197, 160, 89, 0.3);
    }

    .icon-btn {
        transition: all 0.3s ease;
        position: relative;
    }

    .icon-btn:hover {
        color: #c5a059;
        transform: translateY(-2px);
    }

    .mobile-menu-item {
        border-bottom: 1px solid rgba(0, 0, 0, 0.03);
        padding: 1.25rem 0;
        letter-spacing: 0.2em;
        transition: all 0.3s ease;
    }

    .mobile-menu-item.active {
        color: #c5a059;
        font-weight: 700;
    }

    /* Dark Header Dropdown Support */
    .dark-header .dropdown-menu {
        background: rgba(10, 10, 10, 0.98) !important;
        backdrop-filter: blur(20px) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5) !important;
    }

    .dark-header .dropdown-item {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .dark-header .dropdown-item:hover {
        background: rgba(197, 160, 89, 0.1) !important;
        color: #c5a059 !important;
    }

    /* Global Mobile Responsiveness Styles */
    @media (max-width: 768px) {

        /* Header modifications */
        header .h-24 {
            height: 4.5rem !important;
        }

        header .container {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        /* Keep absolute logo centered on mobile but allow click through transparent parts */
        header .flex-shrink-0.absolute {
            position: absolute !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            pointer-events: none !important;
            z-index: 5 !important;
            display: block !important;
        }

        header .flex-shrink-0.absolute a {
            pointer-events: auto !important;
        }

        header a.text-2xl {
            font-size: 1.15rem !important;
        }

        /* Hide Search and User buttons on mobile header to save space */
        header .flex.items-center.space-x-6 button.group,
        header .flex.items-center.space-x-6 a[href*="login"],
        header .flex.items-center.space-x-6 a[href*="user-account"] {
            display: none !important;
        }

        #mobile-menu-button {
            position: relative !important;
            z-index: 10 !important;
            pointer-events: auto !important;
        }

        header .flex.items-center.space-x-6 {
            position: relative !important;
            z-index: 10 !important;
            pointer-events: auto !important;
            space-x: 0 !important;
            gap: 0.75rem !important;
        }

        header .flex.items-center.space-x-6 a,
        header .flex.items-center.space-x-6 button {
            padding: 0.25rem !important;
        }

        header .flex.items-center.space-x-6 i {
            font-size: 0.95rem !important;
        }

        /* Mobile Overlay Menu Styling (Dark theme to prevent text visibility issues) */
        #mobile-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            height: 100vh !important;
            width: 100vw !important;
            background-color: #0a0a0a !important;
            color: #ffffff !important;
            z-index: 9999 !important;
        }

        #mobile-overlay a {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        #mobile-overlay a:hover {
            color: #c5a059 !important;
        }

        #mobile-overlay .mobile-menu-item {
            color: rgba(255, 255, 255, 0.9) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding: 1rem 0 !important;
            display: block !important;
        }

        #mobile-overlay .mobile-menu-item.active {
            color: #c5a059 !important;
        }

        #mobile-overlay button,
        #mobile-overlay i {
            color: #ffffff !important;
        }

        /* Hero text size scaling to prevent screen break */
        .text-7xl,
        .text-8xl,
        .text-9xl,
        h1.text-7xl,
        h1.text-8xl,
        h1.text-9xl {
            font-size: 2.3rem !important;
            line-height: 1.1 !important;
        }

        h1.text-7xl span,
        h1.text-8xl span,
        h1.text-9xl span {
            font-size: 2.3rem !important;
            margin-top: 0.25rem !important;
        }

        .text-6xl,
        h2.text-6xl {
            font-size: 1.85rem !important;
            line-height: 1.2 !important;
        }

        .text-5xl,
        h2.text-5xl {
            font-size: 1.6rem !important;
        }

        /* Reduce excessively large padding/margins on mobile */
        .py-40,
        section.py-40 {
            padding-top: 3.5rem !important;
            padding-bottom: 3.5rem !important;
        }

        .py-32,
        section.py-32 {
            padding-top: 2.5rem !important;
            padding-bottom: 2.5rem !important;
        }

        .py-24,
        section.py-24 {
            padding-top: 2rem !important;
            padding-bottom: 2rem !important;
        }

        .py-20,
        section.py-20 {
            padding-top: 2rem !important;
            padding-bottom: 2rem !important;
        }

        .my-20 {
            margin-top: 1.5rem !important;
            margin-bottom: 1.5rem !important;
        }

        .gap-32,
        .gap-20,
        .gap-16 {
            gap: 1.25rem !important;
        }

        /* Fix page container padding */
        .container {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .px-8,
        .px-20,
        .px-24,
        .px-32 {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        /* 3D Concept Slides Fixes */
        .min-h-\[750px\],
        .min-h-\[650px\],
        .min-h-\[550px\] {
            min-height: auto !important;
        }

        .concept-slide {
            position: relative !important;
            display: none !important;
            height: auto !important;
            gap: 1.5rem !important;
        }

        .concept-slide.active {
            display: flex !important;
            flex-direction: column !important;
        }

        /* 3D Hero Slider fixes: keep absolute positioning to overlap, but fix width & layout */
        section.h-screen {
            height: auto !important;
            min-height: 800px !important;
            /* Increased height to fit both text and image */
            display: block !important;
            padding-top: 5rem !important;
        }

        .hero-slide {
            position: absolute !important;
            inset: 0 !important;
            width: 100% !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            opacity: 0 !important;
            pointer-events: none !important;
            z-index: 0 !important;
        }

        .hero-slide.active {
            opacity: 1 !important;
            pointer-events: auto !important;
            z-index: 10 !important;
        }

        .hero-slide .container {
            width: 100% !important;
            max-width: 100% !important;
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .hero-slide .space-y-12 {
            display: flex !important;
            flex-direction: column !important;
            gap: 1rem !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        .hero-slide .space-y-12>* {
            margin: 0 !important;
        }

        /* Hero Section Slide Right-Side Image Show on Mobile */
        .slide-animate-5 {
            display: block !important;
            margin-top: 2rem !important;
            width: 100% !important;
            max-width: 300px !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        /* Fix the buttons wrapping and text overlapping in Hero slide */
        .hero-slide .flex.gap-8 {
            gap: 0.5rem !important;
            flex-direction: row !important;
            width: 100% !important;
            flex-wrap: nowrap !important;
            margin-top: 1rem !important;
        }

        .hero-slide .flex.gap-8 a,
        .hero-slide .flex.gap-8 button {
            padding: 12px 16px !important;
            font-size: 8px !important;
            letter-spacing: 0.1em !important;
            white-space: nowrap !important;
            flex: 1 !important;
            text-align: center !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: auto !important;
        }

        /* Main Page Products Horizontal Scroll Layout */
        #products .grid {
            display: flex !important;
            overflow-x: auto !important;
            scroll-snap-type: x mandatory !important;
            gap: 1rem !important;
            padding-bottom: 1.5rem !important;
            scrollbar-width: thin !important;
            scroll-padding: 1rem !important;
        }

        #products .grid .tilt-card {
            flex: 0 0 280px !important;
            scroll-snap-align: start !important;
        }

        /* Show small detail images inside concept slides on mobile */
        .concept-slide .absolute.hidden.md\:block {
            display: block !important;
            top: -1.5rem !important;
            left: -1.5rem !important;
            border-width: 6px !important;
            width: 40% !important;
            height: auto !important;
            aspect-ratio: 1/1 !important;
        }

        /* Trust Features section 2x2 layout on mobile */
        section.py-20.bg-white .grid {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 1.5rem 1rem !important;
        }

        section.py-20.bg-white .tilt-card {
            margin-bottom: 0 !important;
            padding: 0.5rem !important;
        }

        section.py-20.bg-white .tilt-card .w-16 {
            width: 3.5rem !important;
            height: 3.5rem !important;
        }

        section.py-20.bg-white .tilt-card h5 {
            font-size: 8px !important;
            letter-spacing: 0.1em !important;
        }

        /* General layout grid fixes */
        .grid {
            gap: 1.25rem !important;
        }
    }
</style>

<header class="bg-white sticky top-0 z-50 sticky-nav transition-all duration-500">
    <div class="container mx-auto px-8">
        <div class="flex justify-between items-center h-24">
            <!-- Mobile Menu Toggle -->
            <button class="md:hidden text-luxury icon-btn" id="mobile-menu-button">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <!-- Brand Logo -->
            <div class="flex-shrink-0 absolute left-1/2 md:static transform -translate-x-1/2 md:translate-x-0">
                <a href="<?php echo $base_url; ?>index.php"
                    class="font-serif text-2xl md:text-3xl tracking-tighter text-luxury hover:text-gold transition-all duration-500 group">
                    FASHION<span class="text-gold italic group-hover:ml-1 transition-all duration-500">STORE</span>
                </a>
            </div>

            <!-- Main Navigation -->
            <nav class="hidden md:flex items-center space-x-12">
                <a href="<?php echo $base_url; ?>index.php"
                    class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a>
                <a href="<?php echo $base_url; ?>shop.php"
                    class="nav-link <?php echo ($current_page == 'shop.php') ? 'active' : ''; ?>">Shop</a>

                <!-- Collections Dropdown -->
                <div class="nav-dropdown">
                    <a href="#" class="nav-link flex items-center">
                        Collections <i class="fas fa-chevron-down ml-2 text-[8px] opacity-50"></i>
                    </a>
                    <div class="dropdown-menu">
                        <a href="<?php echo $base_url; ?>men.php" class="dropdown-item">Men's Edition</a>
                        <a href="<?php echo $base_url; ?>women.php" class="dropdown-item">Women's Archive</a>
                        <a href="<?php echo $base_url; ?>accessories.php" class="dropdown-item">The Accessories</a>
                    </div>
                </div>

                <a href="<?php echo $base_url; ?>components/about.php"
                    class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">About</a>
                <a href="<?php echo $base_url; ?>components/contact.php"
                    class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">Contact</a>

                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <a href="<?php echo $base_url; ?>admin/index.php"
                        class="nav-link text-gold font-bold <?php echo ($is_admin_path) ? 'active' : ''; ?>">
                        <i class="fas fa-shield-halved mr-1"></i> Admin
                    </a>
                <?php endif; ?>
            </nav>

            <!-- Icons -->
            <div class="flex items-center space-x-6 md:space-x-10">
                <button class="icon-btn group text-luxury">
                    <i class="fas fa-search text-lg"></i>
                </button>

                <?php if (!isset($_SESSION["user_id"])): ?>
                    <a href="<?php echo $base_url; ?>auth/login.php" class="icon-btn text-luxury">
                        <i class="far fa-user text-lg"></i>
                    </a>
                <?php else: ?>
                    <a href="<?php echo $base_url; ?>auth/user-account.php" class="icon-btn text-luxury">
                        <i class="fas fa-user-circle text-lg"></i>
                    </a>
                <?php endif; ?>

                <?php
                $totalItems = 0;
                $wishlistCount = 0;
                $user_id = $_SESSION['user_id'] ?? 0;
                if ($user_id > 0) {
                    try {
                        if (isset($root_path) && file_exists($root_path . 'configshoppingstore.php')) {
                            require_once($root_path . 'configshoppingstore.php');
                        }
                        if (isset($conn)) {
                            $stmt = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
                            $stmt->execute([$user_id]);
                            $totalItems = (int) $stmt->fetchColumn();

                            $stmtW = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
                            $stmtW->execute([$user_id]);
                            $wishlistCount = (int) $stmtW->fetchColumn();
                        }
                    } catch (\Throwable $th) {
                    }
                }
                ?>
                <!-- Wishlist -->
                <a href="<?php echo $base_url; ?>wishlist.php" class="relative icon-btn text-luxury">
                    <i class="far fa-heart text-lg"></i>
                    <?php if ($wishlistCount > 0): ?>
                        <span
                            class="absolute -top-2 -right-2 bg-gold text-white cart-badge rounded-full w-4 h-4 flex items-center justify-center font-bold border border-white"
                            style="font-size: 0.5rem;">
                            <?php echo $wishlistCount; ?>
                        </span>
                    <?php endif; ?>
                </a>

                <!-- Cart -->
                <a href="<?php echo $base_url; ?>components/cart.php" class="relative icon-btn text-luxury">
                    <i class="fas fa-shopping-bag text-lg"></i>
                    <?php if ($totalItems > 0): ?>
                        <span
                            class="absolute -top-2 -right-2 bg-gold text-white cart-badge rounded-full w-5 h-5 flex items-center justify-center font-bold border-2 border-white">
                            <?php echo $totalItems; ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Overlay -->
    <div class="fixed inset-0 bg-white z-[60] transform translate-x-full transition-transform duration-700 cubic-bezier(0.16, 1, 0.3, 1) md:hidden"
        id="mobile-overlay">
        <div class="p-10 h-full flex flex-col">
            <div class="flex justify-between items-center mb-16">
                <span class="font-serif text-3xl tracking-tighter">FASHION<span
                        class="text-gold italic">STORE</span></span>
                <button id="close-mobile-menu" class="icon-btn">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <div class="flex flex-col space-y-4 overflow-y-auto">
                <a href="<?php echo $base_url; ?>index.php"
                    class="mobile-menu-item text-xl uppercase <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a>
                <a href="<?php echo $base_url; ?>shop.php"
                    class="mobile-menu-item text-xl uppercase <?php echo ($current_page == 'shop.php') ? 'active' : ''; ?>">Shop</a>

                <div class="py-4 space-y-4">
                    <p class="text-[10px] uppercase tracking-[0.4em] text-gray-400 font-black">Collections</p>
                    <a href="<?php echo $base_url; ?>men.php"
                        class="block text-lg uppercase tracking-widest pl-4">Men</a>
                    <a href="<?php echo $base_url; ?>women.php"
                        class="block text-lg uppercase tracking-widest pl-4">Women</a>
                    <a href="<?php echo $base_url; ?>accessories.php"
                        class="block text-lg uppercase tracking-widest pl-4">Accessories</a>
                </div>

                <a href="<?php echo $base_url; ?>components/about.php"
                    class="mobile-menu-item text-xl uppercase <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">Our
                    Odyssey</a>
                <a href="<?php echo $base_url; ?>components/contact.php"
                    class="mobile-menu-item text-xl uppercase <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">The
                    Atelier</a>

                <!-- Account / Login Link in Mobile Menu -->
                <?php if (!isset($_SESSION["user_id"])): ?>
                    <a href="<?php echo $base_url; ?>auth/login.php"
                        class="mobile-menu-item text-xl uppercase text-gold font-bold">
                        <i class="far fa-user mr-2"></i> Login / Sign Up
                    </a>
                <?php else: ?>
                    <a href="<?php echo $base_url; ?>auth/user-account.php"
                        class="mobile-menu-item text-xl uppercase text-gold font-bold">
                        <i class="fas fa-user-circle mr-2"></i> My Account
                    </a>
                <?php endif; ?>

                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <a href="<?php echo $base_url; ?>admin/index.php"
                        class="mobile-menu-item text-xl uppercase text-gold">Management</a>
                <?php endif; ?>
            </div>

            <div class="mt-auto pt-10 border-t border-gray-100">
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-gold transition-colors"><i
                            class="fab fa-instagram text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-gold transition-colors"><i
                            class="fab fa-facebook-f text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-gold transition-colors"><i
                            class="fab fa-pinterest-p text-xl"></i></a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    const menuBtn = document.getElementById('mobile-menu-button');
    const closeBtn = document.getElementById('close-mobile-menu');
    const overlay = document.getElementById('mobile-overlay');

    if (menuBtn && closeBtn && overlay) {
        menuBtn.addEventListener('click', () => {
            overlay.style.setProperty('transform', 'translateX(0)', 'important');
            overlay.style.setProperty('display', 'block', 'important');
            document.body.style.overflow = 'hidden';
        });

        closeBtn.addEventListener('click', () => {
            overlay.style.setProperty('transform', 'translateX(100%)', 'important');
            document.body.style.overflow = 'auto';
        });
    }

    window.addEventListener('scroll', () => {
        const header = document.querySelector('header');
        if (window.scrollY > 20) {
            header.classList.add('py-0', 'shadow-lg');
            header.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
        } else {
            header.classList.remove('py-0', 'shadow-lg');
            header.style.backgroundColor = 'rgba(255, 255, 255, 0.85)';
        }
    });

    // Global AJAX Functions
    function updateNavbarBadge(selector, increment) {
        let badgeContainer = document.querySelector(selector);
        if (!badgeContainer) return;

        let badge = badgeContainer.querySelector('.cart-badge');
        if (!badge) {
            // Create badge if it doesn't exist
            badge = document.createElement('span');
            badge.className = "absolute -top-2 -right-2 bg-gold text-white cart-badge rounded-full w-5 h-5 flex items-center justify-center font-bold border-2 border-white";
            badge.style.fontSize = "0.55rem";
            badge.innerText = "0";
            badgeContainer.appendChild(badge);
        }

        let currentCount = parseInt(badge.innerText) || 0;
        let newCount = currentCount + increment;

        if (newCount <= 0) {
            badge.remove();
        } else {
            badge.innerText = newCount;
            // Add a little pop animation
            badge.animate([
                { transform: 'scale(1)' },
                { transform: 'scale(1.3)' },
                { transform: 'scale(1)' }
            ], { duration: 300 });
        }
    }

    async function addToCartAjax(id) {
        const btn = document.getElementById("cart-btn-" + id);
        if (!btn) return;

        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        try {
            const res = await fetch("<?php echo $base_url; ?>add-to-cart.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id=" + id + "&qty=1"
            });
            const data = await res.json();
            if (data.success) {
                // Success: Update button state
                btn.innerHTML = "In Archive";
                btn.className = "flex-grow bg-white/10 text-gold py-4 px-4 text-[8px] font-black uppercase tracking-[0.2em] rounded-xl cursor-default backdrop-blur-md border border-white/5";
                btn.onclick = null;
                btn.disabled = false;

                // Update navbar cart count
                updateNavbarBadge('a[href*="cart.php"]', 1);
            } else {
                alert(data.message);
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        } catch (e) {
            console.error(e);
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    async function toggleWishlistAjax(id, btn) {
        const icon = btn.querySelector("i");
        if (!icon) return;

        const isAdding = icon.classList.contains("far");
        const action = isAdding ? "wishlist" : "unwishlist";

        const originalClass = icon.className;
        icon.className = "fas fa-spinner fa-spin text-[12px]";

        try {
            const res = await fetch("<?php echo $base_url; ?>toggle-wishlist.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id=" + id + "&action=" + action
            });
            const data = await res.json();
            if (data.success) {
                if (isAdding) {
                    icon.className = "fas fa-heart text-gold text-[12px]";
                    updateNavbarBadge('a[href*="wishlist.php"]', 1);
                } else {
                    icon.className = "far fa-heart text-[12px]";
                    updateNavbarBadge('a[href*="wishlist.php"]', -1);
                }
            } else {
                alert(data.message);
                icon.className = originalClass;
            }
        } catch (e) {
            console.error(e);
            icon.className = originalClass;
        }
    }
</script>