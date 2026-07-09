<?php
require_once("../auth/session.php");
check_auth();

include("../mailer.php");
include("../configshoppingstore.php");

$page_title       = "Contact the Atelier | FashionStore";
$site_title       = "Contact Us";
$site_description = "Connect with the FashionStore atelier for bespoke assistance and inquiries.";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars($_POST['name']);
    $email   = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $to      = "support@yourshop.com";
    $subject = "Contact Form Submission from $name";
    $body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    $headers = "From: $email";

    if (sendEmail($to, $subject, $body, $headers)) {
        echo "<script>alert('Your message has been archived by our team.'); window.location='" . $base_url . "index.php';</script>";
    } else {
        echo "<script>alert('Connection failed. Please try again.'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'favicon.php'; ?>
    <title>Contact the Atelier | FashionStore</title>
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
        
        .luxury-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .luxury-input:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #c5a059;
            box-shadow: 0 0 20px rgba(197, 160, 89, 0.1);
            outline: none;
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
    <?php include("header.php"); ?>

    <main class="min-h-screen pt-32 pb-20 px-6">
        <div class="container mx-auto max-w-7xl">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-20 gap-8 animate-fade-up">
                <div class="space-y-4">
                    <span class="text-gold text-xs uppercase tracking-[0.6em] font-black">Direct Access</span>
                    <h1 class="font-serif text-5xl md:text-7xl text-white italic">Contact the <span class="text-gold">Atelier</span></h1>
                </div>
                <p class="text-gray-400 max-w-md text-sm font-light leading-relaxed tracking-wide">
                    Our team is available for bespoke consultations, order inquiries, and professional guidance.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                <!-- Contact Info Side -->
                <div class="space-y-8 animate-fade-up" style="animation-delay: 0.2s;">
                    <div class="glass rounded-3xl p-10 md:p-12 space-y-10">
                        <div class="flex items-start gap-8 group">
                            <div class="w-14 h-14 bg-gold/10 rounded-2xl flex items-center justify-center text-gold group-hover:bg-gold group-hover:text-white transition-all duration-500">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-serif text-xl text-white mb-2">Grand Atelier</h3>
                                <p class="text-gray-400 text-sm font-light">123 Boulevard of Excellence, Suite 500<br>Lahore, Pakistan</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-8 group">
                            <div class="w-14 h-14 bg-gold/10 rounded-2xl flex items-center justify-center text-gold group-hover:bg-gold group-hover:text-white transition-all duration-500">
                                <i class="fas fa-phone-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-serif text-xl text-white mb-2">Concierge Line</h3>
                                <p class="text-gray-400 text-sm font-light">+92 (300) 123 4567</p>
                                <p class="text-[10px] text-gold uppercase tracking-widest mt-2">Mon — Fri, 9am — 6pm</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-8 group">
                            <div class="w-14 h-14 bg-gold/10 rounded-2xl flex items-center justify-center text-gold group-hover:bg-gold group-hover:text-white transition-all duration-500">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-serif text-xl text-white mb-2">Digital Inquiry</h3>
                                <p class="text-gray-400 text-sm font-light">concierge@fashionstore.com</p>
                                <p class="text-[10px] text-gold uppercase tracking-widest mt-2">24/7 Digital Intake</p>
                            </div>
                        </div>
                    </div>

                    <!-- Map Glass Card -->
                    <div class="glass rounded-3xl overflow-hidden p-4 group">
                        <div class="rounded-2xl overflow-hidden grayscale contrast-125 opacity-70 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-1000">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3403.828535472855!2d73.0941809743002!3d31.44638705079584!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3922696ac9459bed%3A0x4d837d0ef403530e!2sCareer%20Institute%20-%20Millat%20Chowk%20Branch%20Faisalabad!5e0!3m2!1sen!2s!4v1755324965190!5m2!1sen!2s"
                                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>

                <!-- Form Side -->
                <div class="glass rounded-3xl p-10 md:p-12 animate-fade-up" style="animation-delay: 0.4s;">
                    <div class="mb-10">
                        <h2 class="font-serif text-3xl text-white mb-4 italic">Send a <span class="text-gold">Message</span></h2>
                        <p class="text-gray-500 text-xs uppercase tracking-widest font-black">Bespoke Correspondence</p>
                    </div>

                    <form action="" method="POST" class="space-y-8">
                        <div class="space-y-4">
                            <label for="name" class="text-[10px] uppercase tracking-[0.4em] text-gray-500 font-black">Your Name</label>
                            <input type="text" id="name" name="name" required placeholder="GIOVANNI VANCE"
                                class="luxury-input w-full px-6 py-5 rounded-xl font-serif text-lg italic placeholder:text-white/10">
                        </div>

                        <div class="space-y-4">
                            <label for="email" class="text-[10px] uppercase tracking-[0.4em] text-gray-500 font-black">Electronic Mail</label>
                            <input type="email" id="email" name="email" required placeholder="VANCE@ARCHIVE.COM"
                                class="luxury-input w-full px-6 py-5 rounded-xl font-serif text-lg italic placeholder:text-white/10">
                        </div>

                        <div class="space-y-4">
                            <label for="message" class="text-[10px] uppercase tracking-[0.4em] text-gray-500 font-black">Inquiry Details</label>
                            <textarea id="message" name="message" rows="5" required placeholder="YOUR MESSAGE HERE..."
                                class="luxury-input w-full px-6 py-5 rounded-xl font-serif text-lg italic placeholder:text-white/10 resize-none"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-gold text-white py-6 rounded-xl font-black text-[10px] uppercase tracking-[0.6em] hover:shadow-[0_10px_40px_rgba(197,160,89,0.3)] transition-all duration-500 flex items-center justify-center gap-4">
                            Dispatch Message <i class="fas fa-paper-plane text-[12px]"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include("footer.php"); ?>
</body>

</html>