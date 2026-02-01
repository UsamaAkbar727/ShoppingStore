<?php
include("../configshoppingstore.php");
include("header.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About Us - FashionStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#39189a",
                        secondary: "#82ccee",
                        accent: "#FAFAFA",
                        dark: "#1a1a2e",
                        light: "#f8f9fa",
                        neon: "#00f0ff"
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 10px 30px -15px rgba(0, 0, 0, 0.1)',
                        'glow': '0 0 20px 5px rgba(130, 204, 238, 0.5)',
                        'neon': '0 0 15px 3px rgba(0, 240, 255, 0.7)'
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'spin-slow': 'spin 8s linear infinite',
                        'border-glow': 'border-glow 3s ease-in-out infinite alternate'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }
        
        .hero-overlay {
            background: linear-gradient(135deg, rgba(57, 24, 154, 0.85) 0%, rgba(130, 204, 238, 0.85) 100%);
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        
        @keyframes border-glow {
            0% { box-shadow: 0 0 10px 2px rgba(0, 240, 255, 0.3); }
            100% { box-shadow: 0 0 20px 5px rgba(0, 240, 255, 0.7); }
        }
        
        .icon-hover {
            transition: all 0.3s ease;
        }
        
        .icon-hover:hover {
            transform: scale(1.2);
            filter: drop-shadow(0 0 12px rgba(0, 240, 255, 0.8));
        }
        
        .team-card:hover img {
            transform: scale(1.1);
        }
        
        .neon-text {
            text-shadow: 0 0 8px rgba(0, 240, 255, 0.7);
        }
        
        .gradient-text {
            background: linear-gradient(45deg, #39189a, #82ccee);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>

<body class="overflow-x-hidden">

    <!-- Hero Section with Particles -->
    <section class="relative h-screen min-h-[700px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 hero-overlay"></div>
        
        <!-- Animated particles -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-8 h-8 rounded-full bg-white/30 animate-float" style="animation-delay: 0.5s;"></div>
            <div class="absolute top-1/3 right-1/4 w-12 h-12 rounded-full bg-secondary/40 animate-float" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-1/4 left-1/3 w-10 h-10 rounded-full bg-white/20 animate-float" style="animation-delay: 1.5s;"></div>
            <div class="absolute bottom-1/3 right-1/3 w-6 h-6 rounded-full bg-secondary/30 animate-float" style="animation-delay: 2s;"></div>
        </div>
        
        <div class="relative z-10 text-center px-6 max-w-5xl mx-auto" data-aos="zoom-in" data-aos-duration="1500">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-8 leading-tight">
                <span class="gradient-text">Fashion Revolution</span> <br> Starts Here
            </h1>
            <p class="text-xl md:text-2xl text-white/90 mb-10 max-w-3xl mx-auto neon-text">
                Where every stitch tells a story and every design empowers
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#our-story" class="bg-white text-primary px-10 py-4 rounded-full font-bold shadow-xl hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105 inline-flex items-center">
                    EXPLORE OUR STORY <i class="fas fa-arrow-down ml-3 animate-bounce"></i>
                </a>
                <a href="/components/product.php" class="bg-transparent border-2 border-white text-white px-10 py-4 rounded-full font-bold hover:bg-white hover:text-primary transition-all duration-300 transform hover:scale-105 inline-flex items-center">
                    SHOP NOW <i class="fas fa-bolt ml-3"></i>
                </a>
            </div>
        </div>
        
        <div class="absolute bottom-10 left-0 right-0 text-center">
            <a href="#our-story" class="inline-block animate-bounce">
                <i class="fas fa-chevron-down text-white text-2xl"></i>
            </a>
        </div>
    </section>

    <!-- Our Story Section with Parallax -->
    <section id="our-story" class="py-28 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1920&q=80')] parallax-bg opacity-10"></div>
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center relative z-10">
            <div class="relative" data-aos="fade-right" data-aos-duration="1200">
                <div class="absolute -inset-2 bg-gradient-to-r from-primary to-secondary rounded-2xl blur-lg opacity-75 animate-pulse-slow"></div>
                <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=800&q=80" 
                     alt="Our Story" 
                     class="relative z-10 w-full h-auto rounded-xl shadow-2xl transform hover:scale-[1.02] transition duration-500 border-4 border-white">
            </div>
            <div data-aos="fade-left" data-aos-duration="1200">
                <span class="text-secondary font-bold mb-4 inline-block text-lg tracking-widest">OUR LEGACY</span>
                <h2 class="text-4xl md:text-5xl font-bold text-primary mb-8 leading-tight">
                    Redefining Fashion <br> <span class="text-secondary">Since 2015</span>
                </h2>
                <p class="mb-6 text-gray-700 leading-relaxed text-lg">
                    What began as a small boutique with a passion for authentic style has blossomed into a movement. 
                    FashionStore isn't just about clothing—it's about crafting confidence and celebrating individuality.
                </p>
                <p class="mb-10 text-gray-700 leading-relaxed text-lg">
                    Our journey from a single storefront to an international brand has been fueled by innovation, 
                    quality, and an unwavering commitment to our customers' self-expression.
                </p>
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-secondary">
                        <div class="text-4xl font-bold text-primary mb-2">50K+</div>
                        <div class="text-gray-600">Happy Customers</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-primary">
                        <div class="text-4xl font-bold text-secondary mb-2">120+</div>
                        <div class="text-gray-600">Awards Won</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Values Section -->
    <section class="py-28 bg-[url('https://images.unsplash.com/photo-1467043198406-dc953a3defa0?auto=format&fit=crop&w=1920&q=80')] bg-fixed bg-cover bg-center relative">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="container mx-auto px-6 text-center relative z-10">
            <span class="text-neon font-bold mb-4 inline-block text-lg tracking-widest">OUR DNA</span>
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-20 leading-tight">
                Core Values That <span class="gradient-text">Illuminate</span>
            </h2>
            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <div class="p-10 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-2xl hover:shadow-neon transition-all duration-500" data-aos="flip-up">
                    <div class="bg-gradient-to-r from-primary to-secondary w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-8">
                        <i class="fas fa-gem text-white text-4xl icon-hover"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Exceptional Craftsmanship</h3>
                    <p class="text-white/80">
                        Precision in every detail, excellence in every stitch. We obsess over quality so you don't have to.
                    </p>
                </div>
                <div class="p-10 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-2xl hover:shadow-neon transition-all duration-500" data-aos="flip-up" data-aos-delay="200">
                    <div class="bg-gradient-to-r from-primary to-secondary w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-8">
                        <i class="fas fa-globe text-white text-4xl icon-hover"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Sustainable Future</h3>
                    <p class="text-white/80">
                        Eco-conscious from fabric to finish. We're committed to fashion that loves the planet back.
                    </p>
                </div>
                <div class="p-10 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-2xl hover:shadow-neon transition-all duration-500" data-aos="flip-up" data-aos-delay="400">
                    <div class="bg-gradient-to-r from-primary to-secondary w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-8">
                        <i class="fas fa-lightbulb text-white text-4xl icon-hover"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Bold Innovation</h3>
                    <p class="text-white/80">
                        Pushing boundaries daily. We blend tradition with cutting-edge design for looks that turn heads.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-28 bg-gradient-to-br from-[#f0f9ff] to-[#e6f4ff]">
        <div class="container mx-auto px-6 text-center">
            <span class="text-secondary font-bold mb-4 inline-block text-lg tracking-widest">CREATIVE MINDS</span>
            <h2 class="text-4xl md:text-5xl font-bold text-primary mb-20 leading-tight">
                Meet The <span class="gradient-text">Visionaries</span>
            </h2>
            <div class="grid md:grid-cols-3 gap-10 max-w-6xl mx-auto">
                <div class="group bg-white rounded-2xl shadow-xl overflow-hidden team-card transition-all duration-500 hover:shadow-2xl" data-aos="fade-up">
                    <div class="relative overflow-hidden h-80">
                        <img src="/images/146018020.png" 
                             class="w-full h-full object-cover transition duration-700 group-hover:scale-110" 
                             alt="Muhammad Iqbal">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6 text-left">
                            <h3 class="text-2xl font-bold text-white">Muhammad Iqbal</h3>
                            <p class="text-secondary font-medium">Creative Director</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-center space-x-5">
                            <a href="#" class="text-gray-400 hover:text-primary text-xl transition transform hover:-translate-y-1"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-gray-400 hover:text-secondary text-xl transition transform hover:-translate-y-1"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-gray-400 hover:text-primary text-xl transition transform hover:-translate-y-1"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                <div class="group bg-white rounded-2xl shadow-xl overflow-hidden team-card transition-all duration-500 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative overflow-hidden h-80">
                        <img src="/images/IMG_20221107_211849_611.jpg" 
                             class="w-full h-full object-cover transition duration-700 group-hover:scale-110" 
                             alt="Usama JuTt">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6 text-left">
                            <h3 class="text-2xl font-bold text-white">Usama JuTt</h3>
                            <p class="text-secondary font-medium">Head of Design</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-center space-x-5">
                            <a href="#" class="text-gray-400 hover:text-primary text-xl transition transform hover:-translate-y-1"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-gray-400 hover:text-secondary text-xl transition transform hover:-translate-y-1"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-gray-400 hover:text-primary text-xl transition transform hover:-translate-y-1"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                <div class="group bg-white rounded-2xl shadow-xl overflow-hidden team-card transition-all duration-500 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="400">
                    <div class="relative overflow-hidden h-80">
                        <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=800&q=80" 
                             class="w-full h-full object-cover transition duration-700 group-hover:scale-110" 
                             alt="Emma Brown">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6 text-left">
                            <h3 class="text-2xl font-bold text-white">Emma Brown</h3>
                            <p class="text-secondary font-medium">Marketing Lead</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-center space-x-5">
                            <a href="#" class="text-gray-400 hover:text-primary text-xl transition transform hover:-translate-y-1"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-gray-400 hover:text-secondary text-xl transition transform hover:-translate-y-1"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-gray-400 hover:text-primary text-xl transition transform hover:-translate-y-1"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-28 text-center bg-gradient-to-r from-primary to-secondary relative overflow-hidden" data-aos="zoom-in">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-20 w-40 h-40 rounded-full bg-white animate-float"></div>
            <div class="absolute bottom-10 right-20 w-60 h-60 rounded-full bg-white animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/3 right-1/4 w-20 h-20 rounded-full bg-neon/30 animate-float" style="animation-delay: 1s;"></div>
        </div>
        <div class="relative z-10 max-w-5xl mx-auto px-6">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-8 leading-tight">
                Ready For Your <span class="neon-text">Style Transformation</span>?
            </h2>
            <p class="text-white/90 mb-10 text-xl max-w-3xl mx-auto">
                Join our fashion revolution and experience clothing that makes you feel unstoppable.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-6">
                <a href="contact.php" class="bg-white text-primary px-12 py-5 rounded-full font-bold shadow-2xl hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105 flex items-center justify-center text-lg">
                    CONNECT WITH US <i class="fas fa-arrow-right ml-3"></i>
                </a>
                <a href="/components/product.php" class="bg-transparent border-3 border-white text-white px-12 py-5 rounded-full font-bold hover:bg-white hover:text-primary transition-all duration-300 transform hover:scale-105 flex items-center justify-center text-lg">
                    SHOP THE COLLECTION <i class="fas fa-bolt ml-3"></i>
                </a>
            </div>
        </div>
    </section>

    <?php include '../components/footer.php'; ?>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            once: false,
            duration: 1000,
            easing: 'ease-in-out-quad'
        });
    </script>
</body>

</html>