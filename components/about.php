<?php
require_once("../auth/session.php");
check_auth();
include("../configshoppingstore.php");

$page_title = "Our Odyssey | FashionStore";
$site_title = "Our Odyssey";
$site_description = "Discover the essence of FashionStore—where luxury meets legacy.";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'favicon.php'; ?>
    <title>Our Odyssey | FashionStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
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
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap');

        body {
            background-color: #0a0a0a;
            color: #fff;
            font-family: 'Inter', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hero-video-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.4;
        }

        .text-gold-gradient {
            background: linear-gradient(to right, #c5a059, #f9dfa5, #c5a059);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .section-3d {
            perspective: 1000px;
        }

        .tilt-card {
            transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
            transform-style: preserve-3d;
        }

        .tilt-card:hover {
            transform: rotateY(10deg) rotateX(5deg) scale(1.02);
        }

        /* Seamless Dark Header override */
        .dark-header header {
            background-color: rgba(10, 10, 10, 0.8) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        .dark-header .nav-link,
        .dark-header header a,
        .dark-header header i {
            color: white !important;
        }

        .dark-header .nav-link:hover {
            color: #c5a059 !important;
        }

        .dark-header .sticky-nav {
            background-color: transparent !important;
        }
    </style>
</head>

<body class="overflow-x-hidden dark-header">
    <?php include("header.php"); ?>

    <!-- Cinematic Hero -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden">
        <video autoplay muted loop playsinline class="hero-video-bg">
            <source
                src="https://player.vimeo.com/external/494163965.sd.mp4?s=6966cf17f7d983488820c427f7173b9875f5b66d&profile_id=164&oauth2_token_id=57447761"
                type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-[#0a0a0a]"></div>

        <div class="relative z-10 text-center px-6 max-w-5xl mx-auto" data-aos="fade-up" data-aos-duration="1500">
            <span class="text-gold text-xs uppercase tracking-[0.8em] font-black mb-6 block">Est. 2015</span>
            <h1 class="font-serif text-6xl md:text-8xl text-white mb-8 leading-tight">
                The Art of <br><span class="italic text-gold-gradient">Curation</span>
            </h1>
            <p
                class="text-lg md:text-xl text-gray-300 mb-12 max-w-2xl mx-auto font-light leading-relaxed tracking-wide">
                Beyond fashion, we craft legacies. A sanctuary for those who seek the extraordinary in every detail.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-8">
                <a href="#vision"
                    class="px-12 py-5 bg-gold text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-full hover:shadow-[0_0_40px_rgba(197,160,89,0.4)] transition-all duration-500 transform hover:-translate-y-1">
                    Discover Vision
                </a>
            </div>
        </div>

        <div class="absolute bottom-12 left-1/2 -translate-x-1/2 animate-bounce">
            <i class="fas fa-chevron-down text-gold/50 text-xl"></i>
        </div>
    </section>

    <!-- Visionary Narrative -->
    <section id="vision" class="py-32 relative overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-24 items-center">
                <div class="relative section-3d" data-aos="fade-right">
                    <div class="absolute -inset-4 border border-gold/20 rounded-2xl transform -rotate-3"></div>
                    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1200&q=80"
                        alt="Visionary"
                        class="relative z-10 w-full h-[600px] object-cover rounded-2xl shadow-2xl tilt-card">
                    <div class="absolute -bottom-12 -right-12 glass p-8 rounded-2xl z-20 hidden md:block"
                        data-aos="fade-up" data-aos-delay="400">
                        <p class="font-serif text-4xl text-gold italic mb-1">Authentic</p>
                        <p class="text-[10px] uppercase tracking-widest text-gray-400">Philosophy of Design</p>
                    </div>
                </div>

                <div class="space-y-10" data-aos="fade-left">
                    <div class="space-y-4">
                        <span class="text-gold text-xs uppercase tracking-[0.6em] font-black">Our Legacy</span>
                        <h2 class="font-serif text-5xl text-white leading-tight">Redefining the <br><span
                                class="italic text-gold">Sartorial Experience</span></h2>
                    </div>

                    <div class="space-y-6 text-gray-400 font-light leading-relaxed text-lg">
                        <p>What began as a whisper in the heart of the fashion district has evolved into a global anthem
                            of elegance. FashionStore isn't merely a destination; it's a commitment to the enduring
                            beauty of quality.</p>
                        <p>We believe that luxury is not about excess, but about the intentional selection of pieces
                            that resonate with the soul. Our curators traverse the globe to bring you an archive that
                            transcends seasons.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-12 pt-8">
                        <div>
                            <p class="text-4xl font-serif text-white mb-2">50K<span class="text-gold">+</span></p>
                            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500 font-black">Adherents</p>
                        </div>
                        <div>
                            <p class="text-4xl font-serif text-white mb-2">120<span class="text-gold">+</span></p>
                            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500 font-black">Accolades</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Pillars -->
    <section class="py-32 bg-white/5 relative">
        <div class="container mx-auto px-6 text-center">
            <span class="text-gold text-xs uppercase tracking-[0.6em] font-black mb-4 block">The DNA</span>
            <h2 class="font-serif text-5xl text-white mb-24 italic">Pillars of <span class="text-gold">Excellence</span>
            </h2>

            <div class="grid md:grid-cols-3 gap-8 max-w-7xl mx-auto">
                <div class="glass p-12 rounded-3xl hover:border-gold/30 transition-all duration-500 group"
                    data-aos="fade-up">
                    <div
                        class="w-20 h-20 bg-gold/10 rounded-full flex items-center justify-center mx-auto mb-10 group-hover:bg-gold group-hover:text-white transition-all duration-500">
                        <i class="fas fa-gem text-gold text-3xl group-hover:text-white"></i>
                    </div>
                    <h3 class="font-serif text-2xl text-white mb-6">Master Craftsmanship</h3>
                    <p class="text-gray-400 font-light leading-relaxed">Precision in every fiber. We collaborate with
                        generational artisans to ensure every garment is a masterpiece.</p>
                </div>

                <div class="glass p-12 rounded-3xl hover:border-gold/30 transition-all duration-500 group"
                    data-aos="fade-up" data-aos-delay="200">
                    <div
                        class="w-20 h-20 bg-gold/10 rounded-full flex items-center justify-center mx-auto mb-10 group-hover:bg-gold group-hover:text-white transition-all duration-500">
                        <i class="fas fa-leaf text-gold text-3xl group-hover:text-white"></i>
                    </div>
                    <h3 class="font-serif text-2xl text-white mb-6">Conscious Luxury</h3>
                    <p class="text-gray-400 font-light leading-relaxed">Fashion with a conscience. Our commitment to
                        sustainability ensures that beauty never comes at a cost to the earth.</p>
                </div>

                <div class="glass p-12 rounded-3xl hover:border-gold/30 transition-all duration-500 group"
                    data-aos="fade-up" data-aos-delay="400">
                    <div
                        class="w-20 h-20 bg-gold/10 rounded-full flex items-center justify-center mx-auto mb-10 group-hover:bg-gold group-hover:text-white transition-all duration-500">
                        <i class="fas fa-lightbulb text-gold text-3xl group-hover:text-white"></i>
                    </div>
                    <h3 class="font-serif text-2xl text-white mb-6">Bold Innovation</h3>
                    <p class="text-gray-400 font-light leading-relaxed">Blending heritage with the avant-garde. We
                        utilize cutting-edge technology to redefine traditional silhouettes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- The Collective -->
    <section class="py-32">
        <div class="container mx-auto px-6 text-center">
            <span class="text-gold text-xs uppercase tracking-[0.6em] font-black mb-4 block">The Collective</span>
            <h2 class="font-serif text-5xl text-white mb-24 italic">Architects of <span class="text-gold">Style</span>
            </h2>

            <div class="grid md:grid-cols-3 gap-12 max-w-6xl mx-auto">
                <div class="group relative overflow-hidden rounded-2xl aspect-[3/4]" data-aos="fade-up">
                    <img src="/images/146018020.png"
                        class="w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110"
                        alt="Iqbal">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-90">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 p-10 text-left w-full translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                        <h3 class="font-serif text-3xl text-white mb-1">Muhammad Iqbal</h3>
                        <p class="text-gold text-[10px] uppercase tracking-[0.3em] font-black">Creative Visionary</p>
                        <div
                            class="flex gap-4 mt-6 opacity-0 group-hover:opacity-100 transition-opacity duration-700 delay-200">
                            <a href="#" class="text-white hover:text-gold"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-white hover:text-gold"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-2xl aspect-[3/4]" data-aos="fade-up"
                    data-aos-delay="200">
                    <img src="/images/IMG_20221107_211849_611.jpg"
                        class="w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110"
                        alt="Usama">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-90">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 p-10 text-left w-full translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                        <h3 class="font-serif text-3xl text-white mb-1">Usama JuTt</h3>
                        <p class="text-gold text-[10px] uppercase tracking-[0.3em] font-black">Head of Aesthetics</p>
                        <div
                            class="flex gap-4 mt-6 opacity-0 group-hover:opacity-100 transition-opacity duration-700 delay-200">
                            <a href="#" class="text-white hover:text-gold"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-white hover:text-gold"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-2xl aspect-[3/4]" data-aos="fade-up"
                    data-aos-delay="400">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=800&q=80"
                        class="w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110"
                        alt="Emma">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-90">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 p-10 text-left w-full translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                        <h3 class="font-serif text-3xl text-white mb-1">Alexander Vance</h3>
                        <p class="text-gold text-[10px] uppercase tracking-[0.3em] font-black">Strategy Director</p>
                        <div
                            class="flex gap-4 mt-6 opacity-0 group-hover:opacity-100 transition-opacity duration-700 delay-200">
                            <a href="#" class="text-white hover:text-gold"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-white hover:text-gold"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Join the Revolution -->
    <section class="py-32 relative overflow-hidden bg-gold">
        <div class="absolute inset-0 opacity-10 flex items-center justify-center pointer-events-none">
            <span class="text-[40rem] font-serif italic text-white font-black select-none">F</span>
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h2 class="font-serif text-5xl md:text-7xl text-white mb-10">Begin Your <span
                    class="italic text-luxury">Transformation</span></h2>
            <p class="text-xl text-white/90 mb-12 max-w-2xl mx-auto font-light">Join our atelier and experience the
                future of curated fashion.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-6">
                <a href="<?php echo $base_url; ?>components/product.php"
                    class="px-12 py-5 bg-luxury text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-xl hover:shadow-[0_10px_40px_rgba(0,0,0,0.3)] transition-all duration-500">
                    Acquire Now
                </a>
            </div>
        </div>
    </section>

    <?php include './footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, duration: 1000 });
    </script>
</body>

</html>