<footer class="bg-black text-white pt-16 pb-12 overflow-hidden relative">
    <!-- Decorative 3D Background Element -->
    <div
        class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-gold to-transparent opacity-50">
    </div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-gold/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 mb-12">
            <!-- Brand & Manifesto -->
            <div class="lg:col-span-5 space-y-10">
                <div class="space-y-4">
                    <h3 class="font-serif text-3xl md:text-4xl tracking-tight">FASHION<span
                            class="text-gold italic">STORE</span></h3>
                    <div class="w-12 h-[1px] bg-gold"></div>
                </div>
                <p class="text-gray-400 text-lg font-light leading-relaxed max-w-md">
                    Architects of the digital sartorial experience. We redefine luxury through the lens of innovation
                    and timeless craftsmanship.
                </p>
                <!-- Social Links with 3D Float -->
                <div class="flex space-x-6">
                    <a href="#"
                        class="w-12 h-12 rounded-full glass border border-white/10 flex items-center justify-center transition-all duration-500 hover:bg-gold hover:border-gold group">
                        <i class="fab fa-instagram text-sm group-hover:scale-110 transition-transform"></i>
                    </a>
                    <a href="#"
                        class="w-12 h-12 rounded-full glass border border-white/10 flex items-center justify-center transition-all duration-500 hover:bg-gold hover:border-gold group">
                        <i class="fab fa-facebook-f text-sm group-hover:scale-110 transition-transform"></i>
                    </a>
                    <a href="#"
                        class="w-12 h-12 rounded-full glass border border-white/10 flex items-center justify-center transition-all duration-500 hover:bg-gold hover:border-gold group">
                        <i class="fab fa-pinterest-p text-sm group-hover:scale-110 transition-transform"></i>
                    </a>
                    <a href="#"
                        class="w-12 h-12 rounded-full glass border border-white/10 flex items-center justify-center transition-all duration-500 hover:bg-gold hover:border-gold group">
                        <i class="fab fa-x-twitter text-sm group-hover:scale-110 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Navigation Columns -->
            <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-3 gap-12">
                <!-- Shop Edit -->
                <div class="space-y-8">
                    <h4 class="text-[10px] uppercase tracking-[0.5em] font-black text-gold">The Edit</h4>
                    <ul class="space-y-4">
                        <li><a href="<?php echo $base_url; ?>components/product.php?category=Men"
                                class="text-gray-400 hover:text-white text-sm font-light transition-all hover:translate-x-2 inline-block">New
                                Arrivals</a></li>
                        <li><a href="<?php echo $base_url; ?>components/product.php?category=Women"
                                class="text-gray-400 hover:text-white text-sm font-light transition-all hover:translate-x-2 inline-block">Womenswear</a>
                        </li>
                        <li><a href="<?php echo $base_url; ?>components/product.php?category=Men"
                                class="text-gray-400 hover:text-white text-sm font-light transition-all hover:translate-x-2 inline-block">Menswear</a>
                        </li>
                        <li><a href="<?php echo $base_url; ?>components/product.php?category=Accessories"
                                class="text-gray-400 hover:text-white text-sm font-light transition-all hover:translate-x-2 inline-block">Accessories</a>
                        </li>
                    </ul>
                </div>

                <!-- Client Service -->
                <div class="space-y-8">
                    <h4 class="text-[10px] uppercase tracking-[0.5em] font-black text-gold">Atelier Care</h4>
                    <ul class="space-y-4">
                        <li><a href="#"
                                class="text-gray-400 hover:text-white text-sm font-light transition-all hover:translate-x-2 inline-block">Shipping
                                & Logistics</a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white text-sm font-light transition-all hover:translate-x-2 inline-block">Private
                                Appointments</a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white text-sm font-light transition-all hover:translate-x-2 inline-block">Returns
                                Policy</a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white text-sm font-light transition-all hover:translate-x-2 inline-block">Size
                                Consultation</a></li>
                    </ul>
                </div>

                <!-- Contact & Legal -->
                <div class="space-y-8">
                    <h4 class="text-[10px] uppercase tracking-[0.5em] font-black text-gold">Connect</h4>
                    <ul class="space-y-4">
                        <li><a href="<?php echo $base_url; ?>components/contact.php"
                                class="text-gray-400 hover:text-white text-sm font-light transition-all hover:translate-x-2 inline-block">Contact
                                Us</a></li>
                        <li><a href="<?php echo $base_url; ?>components/about.php"
                                class="text-gray-400 hover:text-white text-sm font-light transition-all hover:translate-x-2 inline-block">Our
                                Story</a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white text-sm font-light transition-all hover:translate-x-2 inline-block">Privacy
                                Protocol</a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white text-sm font-light transition-all hover:translate-x-2 inline-block">Terms
                                of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="pt-8 border-t border-white/5 flex flex-col items-center gap-8">
            <!-- Signature / Credit - Centered -->
            <div class="flex flex-col items-center gap-4">
                <div class="text-center">
                    <p class="text-[11px] uppercase tracking-[0.4em] text-gray-400 font-medium">
                        Developed <span class="text-red-500 mx-1 animate-pulse">❤️</span> by USAMA JATT
                    </p>
                    <p class="text-[8px] uppercase tracking-[0.2em] text-gray-700 mt-1 font-light">
                        &copy; <?php echo date('Y'); ?> FASHIONSTORE <a href="<?php echo $base_url; ?>admin/login.php" class="cursor-default hover:text-gold transition-colors">|</a> ALL RIGHTS RESERVED
                    </p>
                </div>
            </div>

            <!-- Payment Systems (Minimalist) -->
            <div
                class="flex items-center space-x-8 opacity-20 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-700">
                <i class="fab fa-cc-visa text-2xl"></i>
                <i class="fab fa-cc-mastercard text-2xl"></i>
                <i class="fab fa-cc-apple-pay text-2xl"></i>
                <i class="fab fa-bitcoin text-2xl"></i>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Ensure glassmorphism is consistent across all components */
    .glass {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>