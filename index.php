<?php
$site_title = "Fashion Store";
$site_description = "Premium clothing for everyone";
$current_year = date('Y');

include("./components/product_card_user.php");
include("./configshoppingstore.php");

$res_arr = [];
try {
    $data = $conn->prepare("SELECT * FROM `product`");
    $data->execute();
    $res = $data->fetchAll();
    if ($res) {
        $res_arr = $res;
    }
} catch (\Throwable $th) {
    echo "<div class='text-red-500'>Error: " . $th->getMessage() . "</div>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?></title>
    <meta name="description" content="<?php echo $site_description; ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        secondary: "#1e40af"
                    }
                }
            }
        }
    </script>

    <style>
        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }
    </style>
</head>

<body class="font-sans bg-gray-50">
    <?php include 'components/header.php'; ?>

    <main>
        <section class="relative text-white h-screen min-h-[800px] overflow-hidden">
            <!-- Background Video -->
            <div class="absolute inset-0 overflow-hidden">
                <video autoplay muted loop playsinline
                    class="w-full h-full object-cover">
                    <source src="./images/vid.mp4" type="video/mp4">
                    <!-- Fallback image if video not supported -->
                    <img src="https://heerpret.com/cdn/shop/files/Untitled_design_3.webp?v=1750930220&width=1800"
                        alt="Fashion Background"
                        class="w-full h-full object-cover">
                </video>
            </div>



            <!-- Animated Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-gray-900/90 animate-gradient-shift"></div>

            Floating Particles
            <div class="particles-container absolute inset-0 overflow-hidden">
                <div class="particle" style="top:20%; left:15%; animation-delay:0s;"></div>
                <div class="particle" style="top:70%; left:80%; animation-delay:1s;"></div>
                <div class="particle" style="top:40%; left:50%; animation-delay:2s;"></div>
            </div>

            <!-- Content Container -->
            <div class="relative h-full flex items-center justify-center">
                <div class="container mx-auto px-4 text-center">
                    <!-- Animated Headline -->
                    <div class="mb-8 overflow-hidden">
                        <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                            <span class="text-stroke animate-text-reveal inline-block">New Collection</span>
                            <span class="text-stroke bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary animate-text-reveal inline-block ml-3" style="animation-delay: 0.3s;">2025</span>
                        </h1>
                    </div>

                    <!-- Animated Subhead -->
                    <div class="mb-20 overflow-hidden max-w-3xl mx-auto">
                        <p class="text-xl md:text-2xl font-light tracking-wide opacity-0 animate-fade-in" style="animation-delay: 600ms;">
                            Experience luxury redefined with our exclusive 2025 collection - where innovation meets timeless elegance
                        </p>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row justify-center gap-6">
                        <a href="#products" class="cta-button-primary group">
                            <span>Shop Collection</span>
                            <svg class="arrow-icon" viewBox="0 0 24 24">
                                <path d="M5 12H19M19 12L12 5M19 12L12 19"></path>
                            </svg>
                            <div class="shine"></div>
                        </a>

                        <a href="#video" class="cta-button-secondary group">
                            <svg class="play-icon" viewBox="0 0 24 24">
                                <path d="M6 4v16l12-8z"></path>
                            </svg>
                            <span>Watch Story</span>
                        </a>
                    </div>

                    <!-- Stats Counter -->
                    <div class="absolute bottom-10 left-0 right-0">
                        <div class="flex justify-center gap-12">
                            <div class="stat-item">
                                <div class="counter" data-target="150">0</div>
                                <div class="stat-label">Designs</div>
                            </div>
                            <div class="stat-item">
                                <div class="counter" data-target="42">0</div>
                                <div class="stat-label">Countries</div>
                            </div>
                            <div class="stat-item">
                                <div class="counter" data-target="98">0</div>
                                <div class="stat-label">Quality Score</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="fixed top-28 left-5 transform -translate-x-1/2 animate-bounce-slow">
                <div class="mouse">
                    <div class="wheel"></div>
                </div>
            </div>
        </section>

        <style>
            /* Base Styles */
            :root {
                --primary: #39189a;
                --secondary: #82ccee;
                --accent: #ff4d6d;
            }

            /* Animations */
            @keyframes gradient-shift {
                0% {
                    background-position: 0% 50%;
                }

                50% {
                    background-position: 100% 50%;
                }

                100% {
                    background-position: 0% 50%;
                }
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0) rotate(0deg);
                }

                50% {
                    transform: translateY(-30px) rotate(5deg);
                }
            }

            @keyframes fade-in {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes text-reveal {
                from {
                    clip-path: polygon(0 0, 100% 0, 100% 0, 0 0);
                }

                to {
                    clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
                }
            }

            @keyframes particle-float {

                0%,
                100% {
                    transform: translateY(0) translateX(0);
                }

                50% {
                    transform: translateY(-50px) translateX(20px);
                }
            }

            @keyframes shine {
                0% {
                    transform: translateX(-100%) rotate(30deg);
                }

                80% {
                    transform: translateX(100%) rotate(30deg);
                }

                100% {
                    transform: translateX(100%) rotate(30deg);
                }
            }

            /* Component Styles */
            .animate-gradient-shift {
                animation: gradient-shift 15s ease infinite;
                background-size: 300% 300%;
            }

            .animate-text-reveal {
                animation: text-reveal 1.5s cubic-bezier(0.19, 1, 0.22, 1) forwards;
            }

            .animate-fade-in {
                animation: fade-in 1.2s cubic-bezier(0.39, 0.575, 0.565, 1) forwards;
            }

            .text-stroke {
                -webkit-text-stroke: 1px white;
                color: transparent;
            }

            /* Particles */
            .particle {
                position: absolute;
                width: 6px;
                height: 6px;
                background: rgba(255, 255, 255, 0.4);
                border-radius: 50%;
                animation: particle-float 8s ease-in-out infinite;
            }

            /* CTA Buttons */
            .cta-button-primary {
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
                padding: 18px 36px;
                background: linear-gradient(135deg, var(--primary), var(--secondary));
                color: white;
                font-weight: 500;
                letter-spacing: 1px;
                border-radius: 50px;
                overflow: hidden;
                transition: all 0.4s cubic-bezier(0.215, 0.61, 0.355, 1);
                box-shadow: 0 10px 30px rgba(57, 24, 154, 0.3);
            }

            .cta-button-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 40px rgba(57, 24, 154, 0.4);
            }

            .cta-button-primary .arrow-icon {
                width: 18px;
                height: 18px;
                stroke: currentColor;
                stroke-width: 2;
                transition: transform 0.3s ease;
            }

            .cta-button-primary:hover .arrow-icon {
                transform: translateX(5px);
            }

            .cta-button-primary .shine {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(to right,
                        rgba(255, 255, 255, 0) 0%,
                        rgba(255, 255, 255, 0.3) 50%,
                        rgba(255, 255, 255, 0) 100%);
                transform: translateX(-100%) rotate(30deg);
            }

            .cta-button-primary:hover .shine {
                animation: shine 1.5s ease;
            }

            /* Secondary Button */
            .cta-button-secondary {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                padding: 16px 32px;
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                color: white;
                font-weight: 500;
                letter-spacing: 1px;
                border-radius: 50px;
                border: 1px solid rgba(255, 255, 255, 0.2);
                transition: all 0.4s cubic-bezier(0.215, 0.61, 0.355, 1);
            }

            .cta-button-secondary:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: translateY(-3px);
            }

            .cta-button-secondary .play-icon {
                width: 16px;
                height: 16px;
                fill: currentColor;
                transition: transform 0.3s ease;
            }

            .cta-button-secondary:hover .play-icon {
                transform: scale(1.1);
            }

            /* Stats Counter */
            .stat-item {
                text-align: center;
            }

            .counter {
                font-size: 2.5rem;
                font-weight: 700;
                background: linear-gradient(to right, var(--primary), var(--secondary));
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
                margin-bottom: 4px;
            }

            .stat-label {
                font-size: 0.75rem;
                letter-spacing: 2px;
                text-transform: uppercase;
                opacity: 0.8;
            }

            /* Scroll Indicator */
            .mouse {
                width: 30px;
                height: 50px;
                border: 2px solid rgba(255, 255, 255, 0.6);
                border-radius: 15px;
                position: relative;
                margin: 0 auto;
            }

            .wheel {
                width: 6px;
                height: 10px;
                background: white;
                border-radius: 3px;
                position: absolute;
                top: 10px;
                left: 50%;
                transform: translateX(-50%);
                animation: scroll-wheel 2s infinite;
            }

            @keyframes scroll-wheel {
                0% {
                    top: 10px;
                    opacity: 1;
                }

                50% {
                    top: 20px;
                    opacity: 0.5;
                }

                100% {
                    top: 10px;
                    opacity: 1;
                }
            }

            .animate-bounce-slow {
                animation: bounce 2s infinite;
            }

            @keyframes bounce {

                0%,
                20%,
                50%,
                80%,
                100% {
                    transform: translateY(0) translateX(-50%);
                }

                40% {
                    transform: translateY(-20px) translateX(-50%);
                }

                60% {
                    transform: translateY(-10px) translateX(-50%);
                }
            }
        </style>

        <script>
            // Animated counter
            document.addEventListener('DOMContentLoaded', () => {
                const counters = document.querySelectorAll('.counter');
                const speed = 200;

                counters.forEach(counter => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const increment = target / speed;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment);
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = target;
                    }

                    function updateCount() {
                        const count = +counter.innerText;
                        if (count < target) {
                            counter.innerText = Math.ceil(count + increment);
                            setTimeout(updateCount, 1);
                        } else {
                            counter.innerText = target;
                        }
                    }
                });
            });
        </script>

        <!-- Featured Categories -->
     <section class="py-20 bg-gray-50">
<!-- Full Background Section -->
<div class="w-full bg-gradient-to-br from-indigo-900 via-black to-black py-20">
  <div class="container mx-auto px-4">
    
    <!-- Heading -->
    <div class="mb-16 animate-fade-in">
      <h2 class="text-5xl md:text-6xl font-extrabold text-center mb-4 text-white tracking-tight drop-shadow-lg">
        Shop by Category
      </h2>
      <p class="text-xl text-center text-gray-100 mb-6 max-w-2xl mx-auto opacity-90">
        Discover our curated collections tailored to your lifestyle and needs
      </p>
      <div class="flex justify-center">
        <div class="w-24 h-1 bg-gradient-to-r from-pink-400 to-indigo-400 rounded-full"></div>
      </div>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
      <!-- Category Card 1 -->
      <div class="group relative overflow-hidden rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-2 bg-white">
        <div class="h-80 overflow-hidden">
          <img src="https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
               alt="Electronics"
               class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent transition-all duration-500"></div>
        <div class="absolute bottom-0 left-0 p-6 w-full z-10 space-y-2">
          <h3 class="text-2xl font-bold text-white mb-1">Electronics</h3>
          <p class="text-sm text-gray-200 mb-4">Latest gadgets and devices</p>
          <a href="#" class="inline-flex items-center px-6 py-2.5 bg-white text-indigo-600 font-semibold rounded-full shadow-lg hover:bg-gray-100 transition-all duration-300 transform group-hover:scale-105">
            Shop Now
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 -mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </a>
        </div>
        <div class="absolute top-4 right-4 bg-indigo-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
          New Arrivals
        </div>
      </div>

      <!-- Category Card 2 -->
      <div class="group relative overflow-hidden rounded-3xl shadow-2=xl hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-2 bg-white">
        <adiv class="h-80 overflow-hidden">
          <img src="https://images.unsplash.com/photo-1551232864-3f0890e580d9?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
               alt="Fashion"
               class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out">
        </adiv>
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent transition-all duration-500"></div>
        <div class="absolute bottom-0 left-0 p-6 w-full z-10 space-y-2">
          <h3 class="text-2xl font-bold text-white mb-1">Fashion</h3>
          <p class="text-sm text-gray-200 mb-4">Trendy styles for everyone</p>
          <a href="#" class="inline-flex items-center px-6 py-2.5 bg-white text-pink-600 font-semibold rounded-full shadow-lg hover:bg-gray-100 transition-all duration-300 transform group-hover:scale-105">
            Shop Now
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 -mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </a>
        </div>
        <div class="absolute top-4 right-4 bg-pink-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
          Popular
        </div>
      </div>

      <!-- Category Card 3 -->
      <div class="group relative overflow-hidden rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-2 bg-white">
        <div class="h-80 overflow-hidden">
          <img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
               alt="Home & Living"
               class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent transition-all duration-500"></div>
        <div class="absolute bottom-0 left-0 p-6 w-full z-10 space-y-2">
          <h3 class="text-2xl font-bold text-white mb-1">Home & Living</h3>
          <p class="text-sm text-gray-200 mb-4">Comfort for your space</p>
          <a href="#" class="inline-flex items-center px-6 py-2.5 bg-white text-green-600 font-semibold rounded-full shadow-lg hover:bg-gray-100 transition-all duration-300 transform group-hover:scale-105">
            Shop Now
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 -mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </a>
        </div>
        <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
          Sale
        </div>
      </div>

      <!-- Category Card 4 -->
      <div class="group relative overflow-hidden rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-2 bg-white">
        <div class="h-80 overflow-hidden">
          <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
               alt="Accessories"
               class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent transition-all duration-500"></div>
        <div class="absolute bottom-0 left-0 p-6 w-full z-10 space-y-2">
          <h3 class="text-2xl font-bold text-white mb-1">Accessories</h3>
          <p class="text-sm text-gray-200 mb-4">Complete your look</p>
          <a href="#" class="inline-flex items-center px-6 py-2.5 bg-white text-yellow-600 font-semibold rounded-full shadow-lg hover:bg-gray-100 transition-all duration-300 transform group-hover:scale-105">
            Shop Now
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 -mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </a>
        </div>
        <div class="absolute top-4 right-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
          Limited
        </div>
      </div>
    </div>

    <!-- Button -->
    <div class="text-center mt-20 animate-fade-in">
      <a href="#" class="inline-flex items-center px-12 py-3.5 border-2 border-white/80 text-white font-bold rounded-full hover:bg-white hover:text-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl bg-white/10 backdrop-blur-sm hover:bg-white/90">
        View All Categories
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 -mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </a>
    </div>

  </div>
</div>
</section>

        <!-- Featured Products -->
        <section id="products" class="py-16 bg-white ">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12">Featured Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    if (isset($_COOKIE["user_id"])) {
                        $user_id = $_COOKIE["user_id"];
                        $i = 0;
                        foreach ($res_arr as $value) {
                            $cartres = $conn->prepare("SELECT * FROM `cart` WHERE product_id=" . $value["id"] . " and user_id=" . $user_id);
                            $cartres->execute();
                            $iscarted = $cartres->fetchAll();
                            print_card_user(
                                "./admin/uploads/" . htmlspecialchars($value["file"]),
                                htmlspecialchars($value["category"]),
                                htmlspecialchars($value["productName"]),
                                htmlspecialchars($value["description"]),
                                htmlspecialchars($value["price"]),
                                htmlspecialchars($value["discountedPrice"]),
                                htmlspecialchars($value["stock"]),
                                (int)$value["id"],
                                $iscarted
                            );
                            $i++;
                            if ($i == 3) {
                                break;
                            }
                        }
                    } else {
                        $i = 0;

                        foreach ($res_arr as $value) {
                            print_card_user(
                                "./admin/uploads/" . htmlspecialchars($value["file"]),
                                htmlspecialchars($value["category"]),
                                htmlspecialchars($value["productName"]),
                                htmlspecialchars($value["description"]),
                                htmlspecialchars($value["price"]),
                                htmlspecialchars($value["discountedPrice"]),
                                htmlspecialchars($value["stock"]),
                                (int)$value["id"]
                            );
                            $i++;
                            if ($i == 4) {
                                break;
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section class="py-20 bg-black relative overflow-hidden">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-64 h-64 bg-primary rounded-full filter blur-3xl opacity-20 animate-float"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-secondary rounded-full filter blur-3xl opacity-20 animate-float-delay"></div>
    </div>
    
    <div class="container mx-auto px-4 text-center relative z-10">
        <div class="max-w-2xl mx-auto">
            <!-- Glowing heading -->
            <h2 class="text-5xl font-extrabold mb-6 text-white">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">JOIN THE CLUB</span>
            </h2>
            
            <!-- Animated subtitle -->
            <p class="text-xl mb-10 text-gray-300 animate-pulse">
                🔥 Get <span class="font-bold text-white">exclusive deals</span> before anyone else!
            </p>
            
            <!-- Neon glow form -->
            <div class="relative max-w-lg mx-auto">
                <div class="absolute -inset-1 bg-gradient-to-r from-primary to-secondary rounded-lg blur opacity-75 animate-glow"></div>
                <form class="relative flex flex-col sm:flex-row gap-3 bg-black p-1 rounded-lg">
                    <input 
                        type="email" 
                        placeholder="Enter your email..." 
                        required
                        class="flex-grow px-6 py-4 bg-gray-900 text-white placeholder-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary border border-gray-800"
                    >
                    <button 
                        type="submit" 
                        class="px-8 py-4 bg-gradient-to-r from-primary to-secondary text-white font-bold rounded-lg hover:shadow-lg hover:shadow-primary/30 transition-all duration-300 transform hover:scale-105"
                    >
                        GET ACCESS →
                    </button>
                </form>
            </div>
            
            <!-- Animated bonus text -->
            <div class="mt-8 animate-bounce">
                <p class="text-sm font-mono text-gray-400">
                    ⚡ Bonus: <span class="text-primary">Free shipping</span> on first order
                </p>
            </div>
            
            <!-- Social proof -->
            <div class="mt-12 flex items-center justify-center space-x-4">
                <div class="flex -space-x-2">
                    <img class="w-10 h-10 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/women/44.jpg" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/men/32.jpg" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/women/68.jpg" alt="User">
                </div>
                <p class="text-gray-300 text-sm">
                    Join <span class="text-white font-bold">10,000+</span> members
                </p>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        @keyframes float-delay {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(20px); }
        }
        @keyframes glow {
            0%, 100% { opacity: 0.75; }
            50% { opacity: 0.9; }
        }
        .animate-float { animation: float 8s ease-in-out infinite; }
        .animate-float-delay { animation: float-delay 8s ease-in-out infinite; }
        .animate-glow { animation: glow 3s ease-in-out infinite; }
        .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    </style>
</section>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>