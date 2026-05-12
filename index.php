<?php
require_once("auth/session.php");
check_auth();

$site_title = "FASHIONSTORE | Haute Couture & Ready-to-Wear";
$site_description = "Discover the epitome of luxury and timeless elegance. Curated collections for the modern connoisseur.";
$current_year = date('Y');

include("./components/product_card_user.php");
include("./configshoppingstore.php");

// User ID from session
$user_id = $_SESSION['user_id'];

// Handle Actions (Cart & Wishlist)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $prod_id = (int)$_GET['id'];

    if ($action === 'cart') {
        $stmt = $conn->prepare("INSERT IGNORE INTO `cart`(`user_id`, `product_id`) VALUES (?, ?)");
        $stmt->execute([$user_id, $prod_id]);
    } elseif ($action === 'wishlist') {
        $stmt = $conn->prepare("INSERT IGNORE INTO `wishlist`(`user_id`, `product_id`) VALUES (?, ?)");
        $stmt->execute([$user_id, $prod_id]);
    } elseif ($action === 'unwishlist') {
        $stmt = $conn->prepare("DELETE FROM `wishlist` WHERE `user_id` = ? AND `product_id` = ?");
        $stmt->execute([$user_id, $prod_id]);
    }
    header("Location: " . $base_url . "index.php");
    exit();
}

$res_arr = [];
try {
    $stmt = $conn->prepare("SELECT * FROM `product` ORDER BY id DESC");
    $stmt->execute();
    $res_arr = $stmt->fetchAll();
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
    <title><?php echo $site_title; ?></title>
    <meta name="description" content="<?php echo $site_description; ?>">
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
                        'slow-zoom': 'slowZoom 30s ease-in-out infinite alternate',
                        'fade-up': 'fadeUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        slowZoom: {
                            '0%': { transform: 'scale(1)' },
                            '100%': { transform: 'scale(1.2)' }
                        },
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(40px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap');
        
        html { scroll-behavior: smooth; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #1a1a1a; }
        
        .hero-overlay {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.9) 0%, rgba(26, 26, 26, 0.4) 100%);
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .category-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, transparent 70%);
            transition: opacity 0.5s ease;
        }

        .ken-burns {
            animation: slowZoom 40s ease-in-out infinite alternate;
        }

        /* 3D Scroll Perspective */
        .perspective-container {
            perspective: 2000px;
        }

        .section-3d {
            transform-style: preserve-3d;
            transition: transform 1.2s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 1.2s ease;
            transform: rotateX(5deg) translateY(100px) translateZ(-100px);
            opacity: 0;
        }

        .section-3d.active {
            transform: rotateX(0deg) translateY(0) translateZ(0);
            opacity: 1;
        }

        /* Card Tilt Effect */
        .tilt-card {
            transition: transform 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
            transform-style: preserve-3d;
        }
        
        .tilt-card:hover {
            transform: perspective(1000px) rotateX(2deg) rotateY(2deg) scale(1.02);
        }

        /* Floating Animation */
        .float-3d {
            animation: float3D 8s ease-in-out infinite;
        }

        @keyframes float3D {
            0%, 100% { transform: translateY(0) rotateY(0deg) rotateX(0deg); }
            33% { transform: translateY(-30px) rotateY(5deg) rotateX(2deg); }
            66% { transform: translateY(-15px) rotateY(-5deg) rotateX(-2deg); }
        }

        .text-3d {
            text-shadow: 0 1px 0 #ccc, 0 2px 0 #c9c9c9, 0 3px 0 #bbb, 0 4px 0 #b9b9b9, 0 5px 0 #aaa, 0 6px 1px rgba(0,0,0,.1), 0 0 5px rgba(0,0,0,.1), 0 1px 3px rgba(0,0,0,.3), 0 3px 5px rgba(0,0,0,.2), 0 5px 10px rgba(0,0,0,.25), 0 10px 10px rgba(0,0,0,.2), 0 20px 20px rgba(0,0,0,.15);
        }
    </style>
</head>

<body class="font-sans text-luxury bg-[#0a0a0a] overflow-x-hidden">
    <?php include 'components/header.php'; ?>

    <main class="perspective-container">
        <!-- Enhanced 3D Hero Section -->
        <section class="relative h-screen min-h-[900px] flex items-center overflow-hidden bg-black">
            <!-- 3D Parallax Video/Image Layers -->
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&q=80" 
                     alt="Luxury Fashion" 
                     class="w-full h-full object-cover ken-burns opacity-40">
                
                <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-screen">
                    <source src="https://player.vimeo.com/external/370331493.hd.mp4?s=ca0968797da0076f477018987ec98da018d41334&profile_id=164" type="video/mp4">
                </video>
                <div class="absolute inset-0 hero-overlay"></div>
            </div>

            <div class="container mx-auto px-6 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-20">
                    <div class="space-y-12">
                        <div class="inline-block overflow-hidden">
                            <span class="inline-block text-gold text-xs uppercase tracking-[0.8em] font-bold animate-fade-up opacity-0" style="animation-delay: 0.2s;">
                                Future of Couture
                            </span>
                        </div>
                        
                        <div class="relative">
                            <h1 class="font-serif text-7xl md:text-9xl text-white leading-none animate-fade-up opacity-0" style="animation-delay: 0.4s;">
                                Digital <br><span class="italic text-gold block mt-4">Royalty</span>
                            </h1>
                            <!-- 3D Light Streak -->
                            <div class="absolute -left-20 top-1/2 w-40 h-[1px] bg-gradient-to-r from-transparent via-gold to-transparent animate-[shimmer_3s_infinite]"></div>
                        </div>

                        <div class="max-w-md animate-fade-up opacity-0" style="animation-delay: 0.6s;">
                            <p class="text-gray-400 text-lg font-light leading-relaxed">
                                Enter a multi-dimensional shopping experience where every thread is rendered in absolute perfection. Welcome to the new age of digital luxury.
                            </p>
                        </div>
                        
                        <div class="flex gap-8 animate-fade-up opacity-0" style="animation-delay: 0.8s;">
                            <a href="#collections" class="group relative px-12 py-6 bg-gold text-white text-[10px] font-bold uppercase tracking-[0.3em] transition-all duration-500 hover:shadow-[0_0_30px_rgba(197,160,89,0.4)] overflow-hidden">
                                <span class="relative z-10">Enter Atelier</span>
                                <div class="absolute inset-0 bg-white translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                                <span class="absolute inset-0 z-0 bg-white opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            </a>
                            <button class="px-12 py-6 border border-white/20 text-white text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-white hover:text-black transition-all">
                                Watch Film
                            </button>
                        </div>
                    </div>

                    <!-- 3D Decorative Model Frame -->
                    <div class="relative hidden lg:block animate-fade-up opacity-0" style="animation-delay: 1s;">
                        <div class="relative w-full aspect-[4/5] glass rounded-xl overflow-hidden float-3d p-4">
                            <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                 alt="3D Model" 
                                 class="w-full h-full object-cover rounded-lg shadow-2xl">
                            <!-- Overlay UI elements -->
                            <div class="absolute bottom-10 left-10 right-10 bg-black/40 backdrop-blur-md p-6 border border-white/10">
                                <div class="flex justify-between items-center text-white">
                                    <div>
                                        <p class="text-[8px] uppercase tracking-widest text-gold mb-1">Item Preview</p>
                                        <h4 class="font-serif text-xl">Silk Velvet Gown</h4>
                                    </div>
                                    <span class="font-serif text-lg">$4,200</span>
                                </div>
                            </div>
                        </div>
                        <!-- Floating particles around the frame -->
                        <div class="absolute -top-10 -right-10 w-20 h-20 bg-gold/20 rounded-full blur-2xl animate-pulse"></div>
                        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-4 text-white/30">
                <span class="text-[8px] uppercase tracking-[0.5em]">Explore</span>
                <div class="w-[1px] h-20 bg-gradient-to-b from-gold to-transparent"></div>
            </div>
        </section>

        <!-- 3D Section: Trust Features -->
        <section class="py-20 bg-white relative z-20 section-3d">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-16">
                    <div class="group text-center space-y-6 tilt-card">
                        <div class="w-16 h-16 mx-auto bg-silver rounded-2xl flex items-center justify-center transition-all group-hover:bg-gold group-hover:-rotate-12">
                            <i class="fas fa-cube text-luxury text-xl group-hover:text-white"></i>
                        </div>
                        <h5 class="text-[10px] uppercase tracking-[0.2em] font-black">Dimensionless Shipping</h5>
                        <p class="text-xs text-gray-400">Fast, secure global logistics</p>
                    </div>
                    <div class="group text-center space-y-6 tilt-card">
                        <div class="w-16 h-16 mx-auto bg-silver rounded-2xl flex items-center justify-center transition-all group-hover:bg-gold group-hover:rotate-12">
                            <i class="fas fa-fingerprint text-luxury text-xl group-hover:text-white"></i>
                        </div>
                        <h5 class="text-[10px] uppercase tracking-[0.2em] font-black">Digital DNA Audit</h5>
                        <p class="text-xs text-gray-400">100% Authenticity Verified</p>
                    </div>
                    <div class="group text-center space-y-6 tilt-card">
                        <div class="w-16 h-16 mx-auto bg-silver rounded-2xl flex items-center justify-center transition-all group-hover:bg-gold group-hover:-rotate-12">
                            <i class="fas fa-gem text-luxury text-xl group-hover:text-white"></i>
                        </div>
                        <h5 class="text-[10px] uppercase tracking-[0.2em] font-black">High-Net-Worth Perks</h5>
                        <p class="text-xs text-gray-400">Exclusive member rewards</p>
                    </div>
                    <div class="group text-center space-y-6 tilt-card">
                        <div class="w-16 h-16 mx-auto bg-silver rounded-2xl flex items-center justify-center transition-all group-hover:bg-gold group-hover:rotate-12">
                            <i class="fas fa-headset text-luxury text-xl group-hover:text-white"></i>
                        </div>
                        <h5 class="text-[10px] uppercase tracking-[0.2em] font-black">24/7 Virtual Stylist</h5>
                        <p class="text-xs text-gray-400">Personalized fashion advice</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3D Section: Brand Story -->
        <section class="py-40 bg-[#f8f8f8] section-3d">
            <div class="container mx-auto px-6">
                <div class="flex flex-col lg:flex-row items-center gap-32">
                    <div class="w-full lg:w-1/2">
                        <div class="relative group">
                            <!-- Background 3D shape -->
                            <div class="absolute -inset-10 bg-gold/5 rounded-full blur-3xl group-hover:bg-gold/10 transition-all duration-1000"></div>
                            
                            <div class="relative aspect-[4/5] overflow-hidden rounded-lg shadow-[0_50px_100px_rgba(0,0,0,0.1)]">
                                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" alt="Brand Story" class="w-full h-full object-cover scale-110 group-hover:scale-100 transition-transform duration-[3s]">
                            </div>
                            
                            <!-- Floating 3D Detail -->
                            <div class="absolute -top-12 -left-12 w-1/2 aspect-square border-[15px] border-white shadow-2xl overflow-hidden hidden md:block group-hover:-translate-y-4 group-hover:-translate-x-4 transition-transform duration-1000">
                                <img src="https://images.unsplash.com/photo-1558769132-cb1aea458c5e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Detail" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-full lg:w-1/2 space-y-12">
                        <div class="space-y-4">
                            <span class="text-gold text-xs uppercase tracking-[0.6em] font-black">The Atelier Concept</span>
                            <h2 class="font-serif text-6xl text-luxury leading-[1.1]">Where Craft <br>Meets <span class="italic text-gold">Code</span></h2>
                        </div>
                        
                        <div class="space-y-8 text-gray-600 text-lg font-light leading-relaxed">
                            <p>
                                At FASHIONSTORE, we don't just design garments; we architect experiences. Our atelier combines centuries-old tailoring techniques with cutting-edge 3D modeling to ensure a fit that defies physical limitations.
                            </p>
                            <p class="border-l-4 border-gold pl-8 italic">
                                "The garment is no longer a fabric; it's a digital masterpiece you wear in the physical world."
                            </p>
                        </div>
                        
                        <div class="pt-6">
                            <a href="components/about.php" class="inline-flex items-center gap-6 group">
                                <span class="text-[10px] uppercase tracking-[0.4em] font-black group-hover:text-gold transition-colors">Enter the Atelier</span>
                                <div class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center group-hover:bg-gold group-hover:border-gold transition-all duration-500">
                                    <i class="fas fa-arrow-right text-[10px] group-hover:text-white transition-colors"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3D Section: Categories -->
        <section id="collections" class="py-40 bg-white section-3d">
            <div class="container mx-auto px-6">
                <div class="flex justify-between items-end mb-32">
                    <div class="space-y-6">
                        <span class="text-gray-400 text-[10px] uppercase tracking-[0.8em]">Curated Worlds</span>
                        <h2 class="font-serif text-5xl text-luxury">Signature Archives</h2>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-20 h-20 border border-gold rounded-full flex items-center justify-center animate-spin-slow">
                            <i class="fas fa-plus text-gold"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                    <!-- 3D Category Card -->
                    <a href="men.php" class="group relative aspect-[3/4] overflow-hidden rounded-2xl shadow-2xl tilt-card">
                        <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" alt="Menswear" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s]">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-700"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-end p-16 text-white category-card">
                            <h3 class="font-serif text-4xl mb-4 group-hover:translate-y-[-20px] transition-transform duration-700">Men</h3>
                            <div class="h-[1px] w-0 bg-gold group-hover:w-full transition-all duration-700"></div>
                        </div>
                    </a>
                    <!-- 3D Category Card -->
                    <a href="women.php" class="group relative aspect-[3/4] overflow-hidden rounded-2xl shadow-2xl md:mt-24 tilt-card">
                        <img src="https://images.unsplash.com/photo-1581044777550-4cfa60707c03?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" alt="Womenswear" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s]">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-700"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-end p-16 text-white category-card">
                            <h3 class="font-serif text-4xl mb-4 group-hover:translate-y-[-20px] transition-transform duration-700">Women</h3>
                            <div class="h-[1px] w-0 bg-gold group-hover:w-full transition-all duration-700"></div>
                        </div>
                    </a>
                    <!-- 3D Category Card -->
                    <a href="accessories.php" class="group relative aspect-[3/4] overflow-hidden rounded-2xl shadow-2xl tilt-card">
                        <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" alt="Accessories" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s]">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-700"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-end p-16 text-white category-card">
                            <h3 class="font-serif text-4xl mb-4 group-hover:translate-y-[-20px] transition-transform duration-700">Accessories</h3>
                            <div class="h-[1px] w-0 bg-gold group-hover:w-full transition-all duration-700"></div>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- 3D Section: Products -->
        <section id="products" class="py-40 bg-[#0a0a0a] text-white section-3d">
            <div class="container mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-end mb-32 gap-8">
                    <div class="space-y-6">
                        <span class="text-gold text-[10px] uppercase tracking-[0.8em] font-black">Digital Shop</span>
                        <h2 class="font-serif text-6xl">Latest Drops</h2>
                    </div>
                    <a href="shop.php" class="px-10 py-4 border border-white/10 text-[10px] uppercase tracking-[0.4em] font-bold hover:bg-white hover:text-black transition-all rounded-full">View All</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
                    <?php
                    $i = 0;
                    foreach ($res_arr as $value) {
                        $iscarted = false;
                        $iswishlisted = false;

                        if ($user_id > 0) {
                            $cartStmt = $conn->prepare("SELECT 1 FROM `cart` WHERE product_id = ? AND user_id = ?");
                            $cartStmt->execute([$value["id"], $user_id]);
                            $iscarted = (bool)$cartStmt->fetch();

                            $wishStmt = $conn->prepare("SELECT 1 FROM `wishlist` WHERE product_id = ? AND user_id = ?");
                            $wishStmt->execute([$value["id"], $user_id]);
                            $iswishlisted = (bool)$wishStmt->fetch();
                        }

                        $imgPath = (strpos($value["file"], 'http') === 0) 
                            ? htmlspecialchars($value["file"]) 
                            : $base_url . "admin/uploads/" . htmlspecialchars($value["file"]);

                        // Wrap product card in tilt-card for consistency
                        echo '<div class="tilt-card">';
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
                            $iswishlisted
                        );
                        echo '</div>';

                        $i++;
                        if ($i == 8) break;
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Newsletter Section with 3D Depth -->
        <section class="py-60 relative overflow-hidden bg-black section-3d">
            <div class="absolute inset-0 opacity-20 pointer-events-none float-3d" style="background-image: url('https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&q=80'); background-size: cover; background-position: center;"></div>
            
            <div class="container mx-auto px-6 relative z-10 text-center">
                <div class="max-w-4xl mx-auto glass p-20 rounded-3xl space-y-16">
                    <div class="space-y-6">
                        <span class="text-gold text-xs uppercase tracking-[1em] font-black">Digital Membership</span>
                        <h2 class="font-serif text-7xl text-white">Join the <span class="italic text-gold">Elite</span></h2>
                        <p class="text-gray-400 text-xl font-light leading-relaxed">
                            Be the first to experience our next digital drop.
                        </p>
                    </div>
                    
                    <form class="flex flex-col md:flex-row gap-0 max-w-2xl mx-auto bg-black/50 backdrop-blur-xl border border-white/10 group focus-within:border-gold transition-all duration-700">
                        <input 
                            type="email" 
                            placeholder="Your encrypted email" 
                            required
                            class="flex-grow px-12 py-8 bg-transparent text-white placeholder-gray-600 focus:outline-none text-sm"
                        >
                        <button 
                            type="submit" 
                            class="px-16 py-8 bg-gold text-white text-[10px] font-black uppercase tracking-[0.5em] hover:bg-white hover:text-black transition-all duration-500"
                        >
                            Sign Up
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <?php include 'components/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observerOptions = {
                threshold: 0.05,
                rootMargin: '0px 0px -100px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    } else {
                        // Optional: remove class to animate again on scroll up
                        // entry.target.classList.remove('active');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.section-3d').forEach(el => observer.observe(el));

            // Simple parallax effect for floating model
            window.addEventListener('scroll', () => {
                const scroll = window.pageYOffset;
                const float = document.querySelector('.float-3d');
                if (float) {
                    float.style.transform = `translateY(${scroll * 0.05}px) rotateY(${scroll * 0.02}deg)`;
                }
            });
        });
    </script>
</body>
</html>