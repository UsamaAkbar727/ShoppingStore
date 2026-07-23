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
    $prod_id = (int) $_GET['id'];

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
    <?php include 'components/favicon.php'; ?>
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

        html {
            scroll-behavior: smooth;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #1a1a1a;
        }

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
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, transparent 70%);
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
            /* Default visible to prevent blank page if JS fails or observer is delayed */
            opacity: 1;
            transform: none;
        }

        /* Initial state before reveal animation */
        .section-3d:not(.active).is-revealing {
            transform: rotateX(5deg) translateY(100px) translateZ(-100px);
            opacity: 0;
        }

        .section-3d.active {
            transform: rotateX(0deg) translateY(0) translateZ(0) !important;
            opacity: 1 !important;
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

            0%,
            100% {
                transform: translateY(0) rotateY(0deg) rotateX(0deg);
            }

            33% {
                transform: translateY(-30px) rotateY(5deg) rotateX(2deg);
            }

            66% {
                transform: translateY(-15px) rotateY(-5deg) rotateX(-2deg);
            }
        }

        .text-3d {
            text-shadow: 0 1px 0 #ccc, 0 2px 0 #c9c9c9, 0 3px 0 #bbb, 0 4px 0 #b9b9b9, 0 5px 0 #aaa, 0 6px 1px rgba(0, 0, 0, .1), 0 0 5px rgba(0, 0, 0, .1), 0 1px 3px rgba(0, 0, 0, .3), 0 3px 5px rgba(0, 0, 0, .2), 0 5px 10px rgba(0, 0, 0, .25), 0 10px 10px rgba(0, 0, 0, .2), 0 20px 20px rgba(0, 0, 0, .15);
        }

        /* Slider CSS */
        .hero-slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            pointer-events: none;
            z-index: 0;
        }

        .hero-slide.active {
            opacity: 1;
            pointer-events: auto;
            z-index: 10;
        }

        /* Animation timings inside active slides */
        .hero-slide .slide-animate-1,
        .hero-slide .slide-animate-2,
        .hero-slide .slide-animate-3,
        .hero-slide .slide-animate-4,
        .hero-slide .slide-animate-5 {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .hero-slide.active .slide-animate-1 {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.2s;
        }

        .hero-slide.active .slide-animate-2 {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.4s;
        }

        .hero-slide.active .slide-animate-3 {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.6s;
        }

        .hero-slide.active .slide-animate-4 {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.8s;
        }

        .hero-slide.active .slide-animate-5 {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 1s;
        }

        .slide-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            cursor: pointer;
            transition: all 0.4s ease;
        }

        .slide-dot.active {
            background: #c5a059;
            border-color: #c5a059;
            transform: scale(1.3);
            box-shadow: 0 0 15px rgba(197, 160, 89, 0.5);
        }

        /* Concept Slider CSS */
        .concept-slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            pointer-events: none;
            z-index: 0;
        }

        .concept-slide.active {
            opacity: 1;
            pointer-events: auto;
            position: relative;
            z-index: 10;
        }

        .concept-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(26, 26, 26, 0.2);
            border: 1px solid rgba(26, 26, 26, 0.1);
            cursor: pointer;
            transition: all 0.4s ease;
        }

        .concept-dot.active {
            background: #c5a059;
            border-color: #c5a059;
            transform: scale(1.3);
            box-shadow: 0 0 15px rgba(197, 160, 89, 0.3);
        }
    </style>
</head>

<body class="font-sans text-luxury bg-[#0a0a0a] overflow-x-hidden">
    <?php include 'components/header.php'; ?>

    <main class="perspective-container">
        <!-- Enhanced 3D Hero Section -->
        <section class="relative h-screen min-h-[900px] flex items-center overflow-hidden bg-black">

            <!-- Slide 1 -->
            <div class="hero-slide active">
                <div class="absolute inset-0 z-0">
                    <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&q=80"
                        alt="Luxury Fashion" class="w-full h-full object-cover ken-burns opacity-40">

                    <video autoplay muted loop playsinline
                        class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-screen">
                        <source
                            src="https://player.vimeo.com/external/370331493.hd.mp4?s=ca0968797da0076f477018987ec98da018d41334&profile_id=164"
                            type="video/mp4">
                    </video>
                    <div class="absolute inset-0 hero-overlay"></div>
                </div>

                <div class="container mx-auto px-6 relative z-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-20">
                        <div class="space-y-12 text-left">
                            <div class="inline-block overflow-hidden">
                                <span
                                    class="inline-block text-gold text-xs uppercase tracking-[0.8em] font-bold slide-animate-1">
                                    Future of Couture
                                </span>
                            </div>

                            <div class="relative">
                                <h1 class="font-serif text-7xl md:text-9xl text-white leading-none slide-animate-2">
                                    Digital <br><span class="italic text-gold block mt-4">Royalty</span>
                                </h1>
                                <div
                                    class="absolute -left-20 top-1/2 w-40 h-[1px] bg-gradient-to-r from-transparent via-gold to-transparent animate-[shimmer_3s_infinite]">
                                </div>
                            </div>

                            <div class="max-w-md slide-animate-3">
                                <p class="text-gray-400 text-lg font-light leading-relaxed">
                                    Enter a multi-dimensional shopping experience where every thread is rendered in
                                    absolute perfection. Welcome to the new age of digital luxury.
                                </p>
                            </div>

                            <div class="flex gap-8 slide-animate-4">
                                <a href="#collections"
                                    class="group relative px-12 py-6 bg-gold text-white text-[10px] font-bold uppercase tracking-[0.3em] transition-all duration-500 hover:shadow-[0_0_30px_rgba(197,160,89,0.4)] overflow-hidden">
                                    <span class="relative z-10">Enter Atelier</span>
                                    <div
                                        class="absolute inset-0 bg-white translate-y-full group-hover:translate-y-0 transition-transform duration-500">
                                    </div>
                                    <span
                                        class="absolute inset-0 z-0 bg-white opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                </a>
                                <button
                                    class="px-12 py-6 border border-white/20 text-white text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-white hover:text-black transition-all">
                                    Watch Film
                                </button>
                            </div>
                        </div>

                        <div class="relative hidden lg:block slide-animate-5">
                            <div class="relative w-full aspect-[4/5] glass rounded-xl overflow-hidden float-3d p-4">
                                <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                                    alt="3D Model" class="w-full h-full object-cover rounded-lg shadow-2xl">
                                <div
                                    class="absolute bottom-10 left-10 right-10 bg-black/40 backdrop-blur-md p-6 border border-white/10">
                                    <div class="flex justify-between items-center text-white">
                                        <div>
                                            <p class="text-[8px] uppercase tracking-widest text-gold mb-1">Item Preview
                                            </p>
                                            <h4 class="font-serif text-xl">Silk Velvet Gown</h4>
                                        </div>
                                        <span class="font-serif text-lg">$4,200</span>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute -top-10 -right-10 w-20 h-20 bg-gold/20 rounded-full blur-2xl animate-pulse">
                            </div>
                            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white/10 rounded-full blur-3xl animate-pulse"
                                style="animation-delay: 1s;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="hero-slide">
                <div class="absolute inset-0 z-0">
                    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&q=80"
                        alt="Luxury Summer Wear" class="w-full h-full object-cover ken-burns opacity-40">
                    <div class="absolute inset-0 hero-overlay"></div>
                </div>

                <div class="container mx-auto px-6 relative z-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-20">
                        <div class="space-y-12 text-left">
                            <div class="inline-block overflow-hidden">
                                <span
                                    class="inline-block text-gold text-xs uppercase tracking-[0.8em] font-bold slide-animate-1">
                                    Summer Archive 2026
                                </span>
                            </div>

                            <div class="relative">
                                <h1 class="font-serif text-7xl md:text-9xl text-white leading-none slide-animate-2">
                                    Elysian <br><span class="italic text-gold block mt-4">Summer</span>
                                </h1>
                                <div
                                    class="absolute -left-20 top-1/2 w-40 h-[1px] bg-gradient-to-r from-transparent via-gold to-transparent animate-[shimmer_3s_infinite]">
                                </div>
                            </div>

                            <div class="max-w-md slide-animate-3">
                                <p class="text-gray-400 text-lg font-light leading-relaxed">
                                    Breathable luxury meets structural precision. A curated collection of lightweight
                                    garments designed to command absolute presence.
                                </p>
                            </div>

                            <div class="flex gap-8 slide-animate-4">
                                <a href="shop.php?category=Women"
                                    class="group relative px-12 py-6 bg-gold text-white text-[10px] font-bold uppercase tracking-[0.3em] transition-all duration-500 hover:shadow-[0_0_30px_rgba(197,160,89,0.4)] overflow-hidden">
                                    <span class="relative z-10">Explore Line</span>
                                    <div
                                        class="absolute inset-0 bg-white translate-y-full group-hover:translate-y-0 transition-transform duration-500">
                                    </div>
                                    <span
                                        class="absolute inset-0 z-0 bg-white opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                </a>
                                <button
                                    class="px-12 py-6 border border-white/20 text-white text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-white hover:text-black transition-all">
                                    Watch Film
                                </button>
                            </div>
                        </div>

                        <div class="relative hidden lg:block slide-animate-5">
                            <div class="relative w-full aspect-[4/5] glass rounded-xl overflow-hidden float-3d p-4">
                                <img src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                                    alt="3D Model" class="w-full h-full object-cover rounded-lg shadow-2xl">
                                <div
                                    class="absolute bottom-10 left-10 right-10 bg-black/40 backdrop-blur-md p-6 border border-white/10">
                                    <div class="flex justify-between items-center text-white">
                                        <div>
                                            <p class="text-[8px] uppercase tracking-widest text-gold mb-1">Item Preview
                                            </p>
                                            <h4 class="font-serif text-xl">Linen Safari Coat</h4>
                                        </div>
                                        <span class="font-serif text-lg">$2,850</span>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute -top-10 -right-10 w-20 h-20 bg-gold/20 rounded-full blur-2xl animate-pulse">
                            </div>
                            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white/10 rounded-full blur-3xl animate-pulse"
                                style="animation-delay: 1s;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="hero-slide">
                <div class="absolute inset-0 z-0">
                    <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&q=80"
                        alt="Avant-Garde Tailoring" class="w-full h-full object-cover ken-burns opacity-40">
                    <div class="absolute inset-0 hero-overlay"></div>
                </div>

                <div class="container mx-auto px-6 relative z-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-20">
                        <div class="space-y-12 text-left">
                            <div class="inline-block overflow-hidden">
                                <span
                                    class="inline-block text-gold text-xs uppercase tracking-[0.8em] font-bold slide-animate-1">
                                    The Avant-Garde
                                </span>
                            </div>

                            <div class="relative">
                                <h1 class="font-serif text-7xl md:text-9xl text-white leading-none slide-animate-2">
                                    Modern <br><span class="italic text-gold block mt-4">Legacy</span>
                                </h1>
                                <div
                                    class="absolute -left-20 top-1/2 w-40 h-[1px] bg-gradient-to-r from-transparent via-gold to-transparent animate-[shimmer_3s_infinite]">
                                </div>
                            </div>

                            <div class="max-w-md slide-animate-3">
                                <p class="text-gray-400 text-lg font-light leading-relaxed">
                                    Daring shapes, asymmetric cuts, and premium tailoring. Discover statement pieces
                                    created for those who dictate the future of style.
                                </p>
                            </div>

                            <div class="flex gap-8 slide-animate-4">
                                <a href="shop.php?category=Men"
                                    class="group relative px-12 py-6 bg-gold text-white text-[10px] font-bold uppercase tracking-[0.3em] transition-all duration-500 hover:shadow-[0_0_30px_rgba(197,160,89,0.4)] overflow-hidden">
                                    <span class="relative z-10">Enter Atelier</span>
                                    <div
                                        class="absolute inset-0 bg-white translate-y-full group-hover:translate-y-0 transition-transform duration-500">
                                    </div>
                                    <span
                                        class="absolute inset-0 z-0 bg-white opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                </a>
                                <button
                                    class="px-12 py-6 border border-white/20 text-white text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-white hover:text-black transition-all">
                                    Watch Film
                                </button>
                            </div>
                        </div>

                        <div class="relative hidden lg:block slide-animate-5">
                            <div class="relative w-full aspect-[4/5] glass rounded-xl overflow-hidden float-3d p-4">
                                <img src="https://images.unsplash.com/photo-1509631179647-0177331693ae?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                                    alt="3D Model" class="w-full h-full object-cover rounded-lg shadow-2xl">
                                <div
                                    class="absolute bottom-10 left-10 right-10 bg-black/40 backdrop-blur-md p-6 border border-white/10">
                                    <div class="flex justify-between items-center text-white">
                                        <div>
                                            <p class="text-[8px] uppercase tracking-widest text-gold mb-1">Item Preview
                                            </p>
                                            <h4 class="font-serif text-xl">Asymmetric Wool Blazer</h4>
                                        </div>
                                        <span class="font-serif text-lg">$3,100</span>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute -top-10 -right-10 w-20 h-20 bg-gold/20 rounded-full blur-2xl animate-pulse">
                            </div>
                            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white/10 rounded-full blur-3xl animate-pulse"
                                style="animation-delay: 1s;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide Navigation Indicators -->
            <div class="absolute bottom-10 right-10 flex gap-3 z-30">
                <button onclick="goToSlide(0)" class="slide-dot active"></button>
                <button onclick="goToSlide(1)" class="slide-dot"></button>
                <button onclick="goToSlide(2)" class="slide-dot"></button>
            </div>

            <!-- Scroll Indicator -->
            <div
                class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-4 text-white/30 z-30">
                <span class="text-[8px] uppercase tracking-[0.5em]">Explore</span>
                <div class="w-[1px] h-20 bg-gradient-to-b from-gold to-transparent"></div>
            </div>
        </section>

        <!-- 3D Section: Trust Features -->
        <section class="py-20 bg-white relative z-20 section-3d">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-16">
                    <div class="group text-center space-y-6 tilt-card">
                        <div
                            class="w-16 h-16 mx-auto bg-silver rounded-2xl flex items-center justify-center transition-all group-hover:bg-gold group-hover:-rotate-12">
                            <i class="fas fa-cube text-luxury text-xl group-hover:text-white"></i>
                        </div>
                        <h5 class="text-[10px] uppercase tracking-[0.2em] font-black">Dimensionless Shipping</h5>
                        <p class="text-xs text-gray-400">Fast, secure global logistics</p>
                    </div>
                    <div class="group text-center space-y-6 tilt-card">
                        <div
                            class="w-16 h-16 mx-auto bg-silver rounded-2xl flex items-center justify-center transition-all group-hover:bg-gold group-hover:rotate-12">
                            <i class="fas fa-fingerprint text-luxury text-xl group-hover:text-white"></i>
                        </div>
                        <h5 class="text-[10px] uppercase tracking-[0.2em] font-black">Digital DNA Audit</h5>
                        <p class="text-xs text-gray-400">100% Authenticity Verified</p>
                    </div>
                    <div class="group text-center space-y-6 tilt-card">
                        <div
                            class="w-16 h-16 mx-auto bg-silver rounded-2xl flex items-center justify-center transition-all group-hover:bg-gold group-hover:-rotate-12">
                            <i class="fas fa-gem text-luxury text-xl group-hover:text-white"></i>
                        </div>
                        <h5 class="text-[10px] uppercase tracking-[0.2em] font-black">High-Net-Worth Perks</h5>
                        <p class="text-xs text-gray-400">Exclusive member rewards</p>
                    </div>
                    <div class="group text-center space-y-6 tilt-card">
                        <div
                            class="w-16 h-16 mx-auto bg-silver rounded-2xl flex items-center justify-center transition-all group-hover:bg-gold group-hover:rotate-12">
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
                <div class="relative min-h-[750px] lg:min-h-[550px] w-full">

                    <!-- Concept Slide 1 -->
                    <div class="concept-slide active flex flex-col lg:flex-row items-center gap-32">
                        <div class="w-full lg:w-1/2">
                            <div class="relative group">
                                <div
                                    class="absolute -inset-10 bg-gold/5 rounded-full blur-3xl group-hover:bg-gold/10 transition-all duration-1000">
                                </div>
                                <div
                                    class="relative aspect-[4/5] overflow-hidden rounded-lg shadow-[0_50px_100px_rgba(0,0,0,0.1)]">
                                    <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=1000&q=80"
                                        alt="Brand Story"
                                        class="w-full h-full object-cover scale-110 group-hover:scale-100 transition-transform duration-[3s]">
                                </div>
                                <div
                                    class="absolute -top-12 -left-12 w-1/2 aspect-square border-[15px] border-white shadow-2xl overflow-hidden hidden md:block group-hover:-translate-y-4 group-hover:-translate-x-4 transition-transform duration-1000">
                                    <img src="https://images.unsplash.com/photo-1558769132-cb1aea458c5e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                                        alt="Detail" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>

                        <div class="w-full lg:w-1/2 space-y-12">
                            <div class="space-y-4">
                                <span class="text-gold text-xs uppercase tracking-[0.6em] font-black">The Atelier
                                    Concept</span>
                                <h2 class="font-serif text-6xl text-luxury leading-[1.1]">Where Craft <br>Meets <span
                                        class="italic text-gold">Code</span></h2>
                            </div>

                            <div class="space-y-8 text-gray-600 text-lg font-light leading-relaxed">
                                <p>
                                    At FASHIONSTORE, we don't just design garments; we architect experiences. Our
                                    atelier combines centuries-old tailoring techniques with cutting-edge 3D modeling to
                                    ensure a fit that defies physical limitations.
                                </p>
                                <p class="border-l-4 border-gold pl-8 italic">
                                    "The garment is no longer a fabric; it's a digital masterpiece you wear in the
                                    physical world."
                                </p>
                            </div>

                            <div class="pt-6">
                                <a href="components/about.php" class="inline-flex items-center gap-6 group">
                                    <span
                                        class="text-[10px] uppercase tracking-[0.4em] font-black group-hover:text-gold transition-colors">Enter
                                        the Atelier</span>
                                    <div
                                        class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center group-hover:bg-gold group-hover:border-gold transition-all duration-500">
                                        <i
                                            class="fas fa-arrow-right text-[10px] group-hover:text-white transition-colors"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Concept Slide 2 -->
                    <div class="concept-slide flex flex-col lg:flex-row items-center gap-32">
                        <div class="w-full lg:w-1/2">
                            <div class="relative group">
                                <div
                                    class="absolute -inset-10 bg-gold/5 rounded-full blur-3xl group-hover:bg-gold/10 transition-all duration-1000">
                                </div>
                                <div
                                    class="relative aspect-[4/5] overflow-hidden rounded-lg shadow-[0_50px_100px_rgba(0,0,0,0.1)]">
                                    <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80"
                                        alt="Precision Engineering"
                                        class="w-full h-full object-cover scale-110 group-hover:scale-100 transition-transform duration-[3s]">
                                </div>
                                <div
                                    class="absolute -top-12 -left-12 w-1/2 aspect-square border-[15px] border-white shadow-2xl overflow-hidden hidden md:block group-hover:-translate-y-4 group-hover:-translate-x-4 transition-transform duration-1000">
                                    <img src="https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=800&q=80"
                                        alt="Precision Detail" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>

                        <div class="w-full lg:w-1/2 space-y-12">
                            <div class="space-y-4">
                                <span class="text-gold text-xs uppercase tracking-[0.6em] font-black">Precision
                                    Engineering</span>
                                <h2 class="font-serif text-6xl text-luxury leading-[1.1]">Sartorial <br>Laser <span
                                        class="italic text-gold">Precision</span></h2>
                            </div>

                            <div class="space-y-8 text-gray-600 text-lg font-light leading-relaxed">
                                <p>
                                    Every pattern is generated through algorithms to maximize comfort and reduce fabric
                                    waste to zero. Our tailoring is mathematically optimized to drape perfectly on any
                                    silhouette.
                                </p>
                                <p class="border-l-4 border-gold pl-8 italic">
                                    "We replace traditional measuring tapes with high-precision scans to craft your
                                    digital identity."
                                </p>
                            </div>

                            <div class="pt-6">
                                <a href="components/about.php" class="inline-flex items-center gap-6 group">
                                    <span
                                        class="text-[10px] uppercase tracking-[0.4em] font-black group-hover:text-gold transition-colors">Enter
                                        the Atelier</span>
                                    <div
                                        class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center group-hover:bg-gold group-hover:border-gold transition-all duration-500">
                                        <i
                                            class="fas fa-arrow-right text-[10px] group-hover:text-white transition-colors"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Concept Slide 3 -->
                    <div class="concept-slide flex flex-col lg:flex-row items-center gap-32">
                        <div class="w-full lg:w-1/2">
                            <div class="relative group">
                                <div
                                    class="absolute -inset-10 bg-gold/5 rounded-full blur-3xl group-hover:bg-gold/10 transition-all duration-1000">
                                </div>
                                <div
                                    class="relative aspect-[4/5] overflow-hidden rounded-lg shadow-[0_50px_100px_rgba(0,0,0,0.1)]">
                                    <img src="https://images.unsplash.com/photo-1485230895905-ec40ba36b9bc?auto=format&fit=crop&w=1000&q=80"
                                        alt="Sustainable Luxury"
                                        class="w-full h-full object-cover scale-110 group-hover:scale-100 transition-transform duration-[3s]">
                                </div>
                                <div
                                    class="absolute -top-12 -left-12 w-1/2 aspect-square border-[15px] border-white shadow-2xl overflow-hidden hidden md:block group-hover:-translate-y-4 group-hover:-translate-x-4 transition-transform duration-1000">
                                    <img src="https://images.unsplash.com/photo-1509319117193-57bab727e09d?auto=format&fit=crop&w=800&q=80"
                                        alt="Sustainable Detail" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>

                        <div class="w-full lg:w-1/2 space-y-12">
                            <div class="space-y-4">
                                <span class="text-gold text-xs uppercase tracking-[0.6em] font-black">Ethical
                                    Atelier</span>
                                <h2 class="font-serif text-6xl text-luxury leading-[1.1]">Conscious <br>High <span
                                        class="italic text-gold">Couture</span></h2>
                            </div>

                            <div class="space-y-8 text-gray-600 text-lg font-light leading-relaxed">
                                <p>
                                    Our supply chain is fully audited. We utilize organically grown long-staple fibers,
                                    circular materials, and print-on-demand techniques to ensure our carbon footprint is
                                    completely offset.
                                </p>
                                <p class="border-l-4 border-gold pl-8 italic">
                                    "True luxury is not just defined by raw beauty, but by the clean conscience of its
                                    creation."
                                </p>
                            </div>

                            <div class="pt-6">
                                <a href="components/about.php" class="inline-flex items-center gap-6 group">
                                    <span
                                        class="text-[10px] uppercase tracking-[0.4em] font-black group-hover:text-gold transition-colors">Enter
                                        the Atelier</span>
                                    <div
                                        class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center group-hover:bg-gold group-hover:border-gold transition-all duration-500">
                                        <i
                                            class="fas fa-arrow-right text-[10px] group-hover:text-white transition-colors"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Slide Navigation Dots -->
                    <div class="absolute bottom-[-40px] left-1/2 -translate-x-1/2 flex gap-3 z-30">
                        <button onclick="goToConceptSlide(0)" class="concept-dot active"></button>
                        <button onclick="goToConceptSlide(1)" class="concept-dot"></button>
                        <button onclick="goToConceptSlide(2)" class="concept-dot"></button>
                    </div>

                </div>
            </div>
        </section>

        <!-- Curated Seasonal Aesthetics (Browse by Style Vibe) -->
        <section class="py-36 bg-[#080808] text-white section-3d border-t border-white/10">
            <div class="container mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-end mb-20 gap-6">
                    <div class="space-y-4">
                        <span class="text-gold text-xs uppercase tracking-[0.8em] font-black">Style DNA Matrix</span>
                        <h2 class="font-serif text-5xl md:text-6xl text-white">Curated <span class="italic text-gold">Aesthetics</span></h2>
                    </div>
                    <p class="text-gray-400 text-sm font-light max-w-md">Discover hand-picked collections aligned with your personal fashion persona.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Vibe 1 -->
                    <a href="shop.php?category=All" class="group relative aspect-[3/4] rounded-3xl overflow-hidden glass p-2 border border-white/10 tilt-card-3d" data-tilt-3d="true">
                        <div class="relative w-full h-full rounded-2xl overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=800&q=80" alt="Minimalist Chic" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s]">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 right-6 space-y-2 tilt-child-3d">
                                <span class="text-gold text-[8px] uppercase tracking-[0.3em] font-black">Vibe 01</span>
                                <h3 class="font-serif text-2xl text-white">Minimalist Chic</h3>
                                <p class="text-xs text-gray-400 font-light">Neutral palettes, effortless silhouettes & clean tailoring.</p>
                                <span class="inline-flex items-center gap-2 text-[9px] uppercase tracking-widest text-gold font-bold pt-2 group-hover:translate-x-2 transition-transform">Explore Line <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </div>
                    </a>

                    <!-- Vibe 2 -->
                    <a href="shop.php?category=Men" class="group relative aspect-[3/4] rounded-3xl overflow-hidden glass p-2 border border-white/10 tilt-card-3d" data-tilt-3d="true">
                        <div class="relative w-full h-full rounded-2xl overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=800&q=80" alt="Old Money Prestige" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s]">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 right-6 space-y-2 tilt-child-3d">
                                <span class="text-gold text-[8px] uppercase tracking-[0.3em] font-black">Vibe 02</span>
                                <h3 class="font-serif text-2xl text-white">Old Money Prestige</h3>
                                <p class="text-xs text-gray-400 font-light">Double-face cashmere, silk scarves & tailored coats.</p>
                                <span class="inline-flex items-center gap-2 text-[9px] uppercase tracking-widest text-gold font-bold pt-2 group-hover:translate-x-2 transition-transform">Explore Line <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </div>
                    </a>

                    <!-- Vibe 3 -->
                    <a href="shop.php?category=Women" class="group relative aspect-[3/4] rounded-3xl overflow-hidden glass p-2 border border-white/10 tilt-card-3d" data-tilt-3d="true">
                        <div class="relative w-full h-full rounded-2xl overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1518895949257-7621c3c786d7?auto=format&fit=crop&w=800&q=80" alt="Haute Gala Evening" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s]">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 right-6 space-y-2 tilt-child-3d">
                                <span class="text-gold text-[8px] uppercase tracking-[0.3em] font-black">Vibe 03</span>
                                <h3 class="font-serif text-2xl text-white">Haute Gala Evening</h3>
                                <p class="text-xs text-gray-400 font-light">Silk floor-length gowns & tuxedo tailoring.</p>
                                <span class="inline-flex items-center gap-2 text-[9px] uppercase tracking-widest text-gold font-bold pt-2 group-hover:translate-x-2 transition-transform">Explore Line <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </div>
                    </a>

                    <!-- Vibe 4 -->
                    <a href="accessories.php" class="group relative aspect-[3/4] rounded-3xl overflow-hidden glass p-2 border border-white/10 tilt-card-3d" data-tilt-3d="true">
                        <div class="relative w-full h-full rounded-2xl overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=800&q=80" alt="Fine Jewelry & Vault" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s]">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 right-6 space-y-2 tilt-child-3d">
                                <span class="text-gold text-[8px] uppercase tracking-[0.3em] font-black">Vibe 04</span>
                                <h3 class="font-serif text-2xl text-white">Aureate Fine Vault</h3>
                                <p class="text-xs text-gray-400 font-light">24K gold hardware, Italian leather & diamond cuts.</p>
                                <span class="inline-flex items-center gap-2 text-[9px] uppercase tracking-widest text-gold font-bold pt-2 group-hover:translate-x-2 transition-transform">Explore Line <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </div>
                    </a>
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
                        <div
                            class="w-20 h-20 border border-gold rounded-full flex items-center justify-center animate-spin-slow">
                            <i class="fas fa-plus text-gold"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                    <!-- 3D Category Card -->
                    <a href="men.php"
                        class="group relative aspect-[3/4] overflow-hidden rounded-2xl shadow-2xl tilt-card">
                        <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80"
                            alt="Menswear"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s]">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-700">
                        </div>
                        <div
                            class="absolute inset-0 flex flex-col items-center justify-end p-16 text-white category-card">
                            <h3
                                class="font-serif text-4xl mb-4 group-hover:translate-y-[-20px] transition-transform duration-700">
                                Men</h3>
                            <div class="h-[1px] w-0 bg-gold group-hover:w-full transition-all duration-700"></div>
                        </div>
                    </a>
                    <!-- 3D Category Card -->
                    <a href="women.php"
                        class="group relative aspect-[3/4] overflow-hidden rounded-2xl shadow-2xl md:mt-24 tilt-card">
                        <img src="https://images.unsplash.com/photo-1581044777550-4cfa60707c03?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80"
                            alt="Womenswear"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s]">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-700">
                        </div>
                        <div
                            class="absolute inset-0 flex flex-col items-center justify-end p-16 text-white category-card">
                            <h3
                                class="font-serif text-4xl mb-4 group-hover:translate-y-[-20px] transition-transform duration-700">
                                Women</h3>
                            <div class="h-[1px] w-0 bg-gold group-hover:w-full transition-all duration-700"></div>
                        </div>
                    </a>
                    <!-- 3D Category Card -->
                    <a href="accessories.php"
                        class="group relative aspect-[3/4] overflow-hidden rounded-2xl shadow-2xl tilt-card">
                        <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80"
                            alt="Accessories"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s]">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-700">
                        </div>
                        <div
                            class="absolute inset-0 flex flex-col items-center justify-end p-16 text-white category-card">
                            <h3
                                class="font-serif text-4xl mb-4 group-hover:translate-y-[-20px] transition-transform duration-700">
                                Accessories</h3>
                            <div class="h-[1px] w-0 bg-gold group-hover:w-full transition-all duration-700"></div>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- Interactive Style Lookbook (Shop The Look) -->
        <section class="py-32 bg-[#0d0d0d] text-white section-3d border-t border-b border-white/10">
            <div class="container mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-end mb-20 gap-6">
                    <div class="space-y-4">
                        <span class="text-gold text-xs uppercase tracking-[0.8em] font-black">Editorial Showcase</span>
                        <h2 class="font-serif text-5xl md:text-6xl text-white">Shop The <span class="italic text-gold">Lookbook</span></h2>
                    </div>
                    <p class="text-gray-400 text-sm font-light max-w-md">Hover over the interactive hotspots on the model below to explore individual couture pieces from the runway.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    <!-- Editorial Image with Hotspots -->
                    <div class="lg:col-span-7 relative rounded-3xl overflow-hidden glass p-3 border border-white/10 group">
                        <div class="relative aspect-[4/5] w-full rounded-2xl overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" alt="Lookbook Model" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>

                            <!-- Hotspot 1: Blazer -->
                            <div class="absolute top-[32%] left-[48%] group/pin">
                                <span class="w-8 h-8 rounded-full bg-gold/30 border border-gold flex items-center justify-center animate-ping absolute inset-0 pointer-events-none"></span>
                                <button class="w-8 h-8 rounded-full bg-gold text-luxury font-black flex items-center justify-center text-xs shadow-lg relative z-10 hover:scale-125 transition-transform">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <!-- Hotspot Tooltip -->
                                <div class="absolute left-10 top-1/2 -translate-y-1/2 w-64 glass p-4 rounded-2xl border border-gold/40 shadow-2xl opacity-0 group-hover/pin:opacity-100 transition-all duration-300 pointer-events-none group-hover/pin:pointer-events-auto z-30">
                                    <span class="text-[8px] uppercase tracking-widest text-gold font-black block">Featured Blazer</span>
                                    <h4 class="font-serif text-white text-base">Asymmetric Silk Blazer</h4>
                                    <p class="text-xs text-gold font-bold mt-1">$3,100</p>
                                    <a href="shop.php" class="inline-block mt-2 text-[9px] uppercase tracking-widest bg-gold text-white px-4 py-1.5 rounded-full font-black hover:bg-white hover:text-black transition-colors">Shop Piece</a>
                                </div>
                            </div>

                            <!-- Hotspot 2: Leather Tote Bag -->
                            <div class="absolute top-[60%] left-[30%] group/pin">
                                <span class="w-8 h-8 rounded-full bg-gold/30 border border-gold flex items-center justify-center animate-ping absolute inset-0 pointer-events-none"></span>
                                <button class="w-8 h-8 rounded-full bg-gold text-luxury font-black flex items-center justify-center text-xs shadow-lg relative z-10 hover:scale-125 transition-transform">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <!-- Hotspot Tooltip -->
                                <div class="absolute left-10 top-1/2 -translate-y-1/2 w-64 glass p-4 rounded-2xl border border-gold/40 shadow-2xl opacity-0 group-hover/pin:opacity-100 transition-all duration-300 pointer-events-none group-hover/pin:pointer-events-auto z-30">
                                    <span class="text-[8px] uppercase tracking-widest text-gold font-black block">Luxury Accessory</span>
                                    <h4 class="font-serif text-white text-base">Aureate Leather Bag</h4>
                                    <p class="text-xs text-gold font-bold mt-1">$2,450</p>
                                    <a href="accessories.php" class="inline-block mt-2 text-[9px] uppercase tracking-widest bg-gold text-white px-4 py-1.5 rounded-full font-black hover:bg-white hover:text-black transition-colors">Shop Piece</a>
                                </div>
                            </div>

                            <!-- Hotspot 3: Trousers -->
                            <div class="absolute top-[75%] left-[62%] group/pin">
                                <span class="w-8 h-8 rounded-full bg-gold/30 border border-gold flex items-center justify-center animate-ping absolute inset-0 pointer-events-none"></span>
                                <button class="w-8 h-8 rounded-full bg-gold text-luxury font-black flex items-center justify-center text-xs shadow-lg relative z-10 hover:scale-125 transition-transform">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <!-- Hotspot Tooltip -->
                                <div class="absolute right-10 top-1/2 -translate-y-1/2 w-64 glass p-4 rounded-2xl border border-gold/40 shadow-2xl opacity-0 group-hover/pin:opacity-100 transition-all duration-300 pointer-events-none group-hover/pin:pointer-events-auto z-30">
                                    <span class="text-[8px] uppercase tracking-widest text-gold font-black block">Runway Bottoms</span>
                                    <h4 class="font-serif text-white text-base">Obsidian Tailored Trousers</h4>
                                    <p class="text-xs text-gold font-bold mt-1">$1,800</p>
                                    <a href="shop.php" class="inline-block mt-2 text-[9px] uppercase tracking-widest bg-gold text-white px-4 py-1.5 rounded-full font-black hover:bg-white hover:text-black transition-colors">Shop Piece</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lookbook Description & Quick Breakdown -->
                    <div class="lg:col-span-5 space-y-10">
                        <div class="space-y-4">
                            <span class="text-gold text-[10px] uppercase tracking-[0.5em] font-black">Runway Ensemble #04</span>
                            <h3 class="font-serif text-4xl text-white">The Midnight Monolith</h3>
                            <p class="text-gray-400 text-sm font-light leading-relaxed">Curated for high-profile evening events and winter galas. Precision tailored in Milan with organic double-face cashmere and 24K gold plated hardware.</p>
                        </div>

                        <div class="space-y-6">
                            <div class="glass p-6 rounded-2xl flex items-center justify-between border border-white/10 hover:border-gold/30 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gold/10 flex items-center justify-center text-gold font-serif font-bold">01</div>
                                    <div>
                                        <h4 class="font-serif text-white text-lg">Asymmetric Silk Blazer</h4>
                                        <p class="text-[10px] uppercase tracking-widest text-gray-400">Pure Aureate Weave</p>
                                    </div>
                                </div>
                                <span class="font-serif text-gold text-lg">$3,100</span>
                            </div>

                            <div class="glass p-6 rounded-2xl flex items-center justify-between border border-white/10 hover:border-gold/30 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gold/10 flex items-center justify-center text-gold font-serif font-bold">02</div>
                                    <div>
                                        <h4 class="font-serif text-white text-lg">Aureate Leather Bag</h4>
                                        <p class="text-[10px] uppercase tracking-widest text-gray-400">Handcrafted Italian Calfskin</p>
                                    </div>
                                </div>
                                <span class="font-serif text-gold text-lg">$2,450</span>
                            </div>

                            <div class="glass p-6 rounded-2xl flex items-center justify-between border border-white/10 hover:border-gold/30 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gold/10 flex items-center justify-center text-gold font-serif font-bold">03</div>
                                    <div>
                                        <h4 class="font-serif text-white text-lg">Obsidian Tailored Trousers</h4>
                                        <p class="text-[10px] uppercase tracking-widest text-gray-400">Pleated Wool Blend</p>
                                    </div>
                                </div>
                                <span class="font-serif text-gold text-lg">$1,800</span>
                            </div>
                        </div>

                        <a href="shop.php" class="inline-block w-full py-5 bg-gold text-white text-center text-[10px] font-black uppercase tracking-[0.5em] rounded-2xl hover:bg-white hover:text-black transition-all duration-500 shadow-xl">
                            Explore Complete Runway Outfits
                        </a>
                    </div>
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
                    <a href="shop.php"
                        class="px-10 py-4 border border-white/10 text-[10px] uppercase tracking-[0.4em] font-bold hover:bg-white hover:text-black transition-all rounded-full">View
                        All</a>
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
                            $iscarted = (bool) $cartStmt->fetch();

                            $wishStmt = $conn->prepare("SELECT 1 FROM `wishlist` WHERE product_id = ? AND user_id = ?");
                            $wishStmt->execute([$value["id"], $user_id]);
                            $iswishlisted = (bool) $wishStmt->fetch();
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
                            (int) $value["id"],
                            $iscarted,
                            $iswishlisted
                        );
                        echo '</div>';

                        $i++;
                        if ($i == 8)
                            break;
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Connoisseur Reviews & Wall of Trust -->
        <section class="py-32 bg-black text-white section-3d border-t border-white/10">
            <div class="container mx-auto px-6">
                <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                    <span class="text-gold text-xs uppercase tracking-[0.8em] font-black">Client Acclaim</span>
                    <h2 class="font-serif text-5xl md:text-6xl">Words from the <span class="italic text-gold">Elite</span></h2>
                    <p class="text-gray-400 font-light text-sm">Read verified feedback from our global clientele across Paris, Milan, London, and New York.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Review 1 -->
                    <div class="glass p-10 rounded-3xl space-y-8 border border-white/10 hover:border-gold/40 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex text-gold text-sm gap-1">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <span class="text-[9px] uppercase tracking-widest text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20 font-bold">Verified Buyer</span>
                        </div>
                        <p class="text-gray-300 font-light italic leading-relaxed text-sm">
                            "The precision of the tailoring and quality of the silk is unprecedented. The garment arrived in luxury custom packaging within 48 hours."
                        </p>
                        <div class="flex items-center gap-4 pt-4 border-t border-white/10">
                            <div class="w-12 h-12 rounded-full overflow-hidden border border-gold/40">
                                <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=80" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-serif text-white text-base">Lady Genevieve Vance</h4>
                                <span class="text-[10px] text-gray-400 uppercase tracking-widest">London, United Kingdom</span>
                            </div>
                        </div>
                    </div>

                    <!-- Review 2 -->
                    <div class="glass p-10 rounded-3xl space-y-8 border border-white/10 hover:border-gold/40 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex text-gold text-sm gap-1">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <span class="text-[9px] uppercase tracking-widest text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20 font-bold">VIP Collector</span>
                        </div>
                        <p class="text-gray-300 font-light italic leading-relaxed text-sm">
                            "FashionStore has set a new benchmark for online digital luxury. The piece fits like bespoke couture made directly in Paris."
                        </p>
                        <div class="flex items-center gap-4 pt-4 border-t border-white/10">
                            <div class="w-12 h-12 rounded-full overflow-hidden border border-gold/40">
                                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=80" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-serif text-white text-base">Julian K. Sterling</h4>
                                <span class="text-[10px] text-gray-400 uppercase tracking-widest">Milan, Italy</span>
                            </div>
                        </div>
                    </div>

                    <!-- Review 3 -->
                    <div class="glass p-10 rounded-3xl space-y-8 border border-white/10 hover:border-gold/40 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex text-gold text-sm gap-1">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <span class="text-[9px] uppercase tracking-widest text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20 font-bold">Verified Buyer</span>
                        </div>
                        <p class="text-gray-300 font-light italic leading-relaxed text-sm">
                            "Extremely fast concierge shipping and exceptional customer service. The leather finish is soft, rich, and timeless."
                        </p>
                        <div class="flex items-center gap-4 pt-4 border-t border-white/10">
                            <div class="w-12 h-12 rounded-full overflow-hidden border border-gold/40">
                                <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=80" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-serif text-white text-base">Sophia Chen</h4>
                                <span class="text-[10px] text-gray-400 uppercase tracking-widest">New York, USA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter Section with 3D Depth -->
        <section class="py-60 relative overflow-hidden bg-black section-3d">
            <div class="absolute inset-0 opacity-20 pointer-events-none float-3d"
                style="background-image: url('https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&q=80'); background-size: cover; background-position: center;">
            </div>

            <div class="container mx-auto px-6 relative z-10 text-center">
                <div class="max-w-4xl mx-auto glass p-20 rounded-3xl space-y-16">
                    <div class="space-y-6">
                        <span class="text-gold text-xs uppercase tracking-[1em] font-black">Digital Membership</span>
                        <h2 class="font-serif text-7xl text-white">Join the <span class="italic text-gold">Elite</span>
                        </h2>
                        <p class="text-gray-400 text-xl font-light leading-relaxed">
                            Be the first to experience our next digital drop.
                        </p>
                    </div>

                    <form
                        class="flex flex-col md:flex-row gap-0 max-w-2xl mx-auto bg-black/50 backdrop-blur-xl border border-white/10 group focus-within:border-gold transition-all duration-700">
                        <input type="email" placeholder="Your encrypted email" required
                            class="flex-grow px-12 py-8 bg-transparent text-white placeholder-gray-600 focus:outline-none text-sm">
                        <button type="submit"
                            class="px-16 py-8 bg-gold text-white text-[10px] font-black uppercase tracking-[0.5em] hover:bg-white hover:text-black transition-all duration-500">
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
                threshold: 0.1,
                rootMargin: '0px'
            };

            const sections = document.querySelectorAll('.section-3d');

            // Add revealing class via JS to enable animation
            // This ensures content is visible if JS is disabled
            sections.forEach(el => el.classList.add('is-revealing'));

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, observerOptions);

            sections.forEach(el => observer.observe(el));

            // Simple parallax effect for floating model
            window.addEventListener('scroll', () => {
                const scroll = window.pageYOffset;
                const float = document.querySelector('.float-3d');
                if (float) {
                    float.style.transform = `translateY(${scroll * 0.05}px) rotateY(${scroll * 0.02}deg)`;
                }
            });

            // Slider Logic
            let currentSlide = 0;
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.slide-dot');
            let slideInterval = setInterval(nextSlide, 7000);

            window.goToSlide = function (index) {
                // Clear auto rotation interval
                clearInterval(slideInterval);

                // Remove active classes
                slides[currentSlide].classList.remove('active');
                dots[currentSlide].classList.remove('active');

                // Add active classes to new index
                currentSlide = index;
                slides[currentSlide].classList.add('active');
                dots[currentSlide].classList.add('active');

                // Restart auto rotation
                slideInterval = setInterval(nextSlide, 7000);
            };

            function nextSlide() {
                const nextIndex = (currentSlide + 1) % slides.length;
                window.goToSlide(nextIndex);
            }

            // Concept Slider Logic
            let currentConcept = 0;
            const conceptSlides = document.querySelectorAll('.concept-slide');
            const conceptDots = document.querySelectorAll('.concept-dot');
            let conceptInterval = setInterval(nextConcept, 9000);

            window.goToConceptSlide = function (index) {
                // Clear auto rotation interval
                clearInterval(conceptInterval);

                // Remove active classes
                conceptSlides[currentConcept].classList.remove('active');
                conceptDots[currentConcept].classList.remove('active');

                // Add active classes to new index
                currentConcept = index;
                conceptSlides[currentConcept].classList.add('active');
                conceptDots[currentConcept].classList.add('active');

                // Restart auto rotation
                conceptInterval = setInterval(nextConcept, 9000);
            };

            function nextConcept() {
                const nextIndex = (currentConcept + 1) % conceptSlides.length;
                window.goToConceptSlide(nextIndex);
            }
        });
    </script>
</body>

</html>