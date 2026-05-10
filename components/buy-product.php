<?php
require_once("../auth/session.php");
check_auth();
include("../configshoppingstore.php");
// include './header.php'; // Uncomment if you want the navbar here

$user_id  = $_SESSION['user_id'];
$userData = [];
$orders   = [];

// Try to get user data if available
try {
    $user_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $user_stmt->execute([$user_id]);
    $userData = $user_stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Silently continue even if user data fetch fails
}

$id = $_GET["id"];
try {
    $order_stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
    $order_stmt->execute([$id]);
    $orders = $order_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\Throwable $th) {
    $error = "Something went wrong.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $zip = $_POST['zip'] ?? '';
    $country = $_POST['country'] ?? '';
    
    // Validate required fields
    if (!empty($fullname) && !empty($email) && !empty($phone) && !empty($address)) {
        // Set cookies for shipping info
        setcookie('shipping_fullname', $fullname, time() + 86400, '/');
        setcookie('shipping_email', $email, time() + 86400, '/');
        setcookie('shipping_phone', $phone, time() + 86400, '/');
        setcookie('shipping_address', $address, time() + 86400, '/');
        setcookie('shipping_city', $city, time() + 86400, '/');
        setcookie('shipping_state', $state, time() + 86400, '/');
        setcookie('shipping_zip', $zip, time() + 86400, '/');
        setcookie('shipping_country', $country, time() + 86400, '/');
        
        // Redirect to checkout
        header("Location: ../checkout.php");
        exit();
    } else {
        $formError = "Please fill in all required shipping information.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/48/shopping-bag.png">
    <title>Complete Your Acquisition | FashionStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
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
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white !important;
            transition: all 0.3s ease;
        }
        .luxury-input:focus {
            border-color: #c5a059;
            background: rgba(255, 255, 255, 0.08);
            outline: none;
            box-shadow: 0 0 15px rgba(197, 160, 89, 0.1);
        }
        .iti { width: 100%; }
        .iti__country-list { background-color: #1a1a1a; border: 1px solid #c5a059; color: white; }
        .iti__country:hover { background-color: #2a2a2a; }
        .luxury-input option {
            background-color: #1a1a1a;
            color: white;
        }
        
        /* Ensure navbar is seamless with the luxury dark theme */
        .dark-header header {
            background-color: rgba(10, 10, 10, 0.9) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            backdrop-filter: blur(15px) !important;
        }
        .dark-header .nav-link {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        .dark-header .nav-link:hover {
            color: #c5a059 !important;
        }
        .dark-header header a, 
        .dark-header header button, 
        .dark-header header i {
            color: white !important;
        }
        .dark-header header .text-gold {
            color: #c5a059 !important;
        }
        .dark-header .sticky-nav {
            background-color: transparent !important;
        }
        /* Top bar adjustment */
        .dark-header header > div:first-child {
            background-color: #000 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="font-sans overflow-x-hidden dark-header">
    <?php include 'header.php'; ?>

    <main class="min-h-screen pt-32 pb-20 px-6">
        <div class="container mx-auto max-w-6xl">
            <div class="flex flex-col md:flex-row gap-12 items-start">
                
                <!-- Left Column: Product Summary -->
                <div class="w-full md:w-1/2 space-y-8 animate-fade-up" style="animation-delay: 0.2s;">
                    <div class="space-y-4">
                        <span class="text-gold text-xs uppercase tracking-[0.6em] font-black">Checkout</span>
                        <h1 class="font-serif text-5xl md:text-6xl text-white leading-tight">Complete Your <br><span class="italic text-gold">Acquisition</span></h1>
                    </div>

                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <div class="hidden" id="product_id_to_buy"><?= $order['id'] ?></div>
                            <div class="glass rounded-2xl p-8 space-y-6">
                                <div class="flex items-center gap-6">
                                    <div class="w-24 h-24 rounded-xl overflow-hidden bg-white/5 border border-white/10">
                                        <?php 
                                            $imgPath = (strpos($order["file"], 'http') === 0) 
                                                ? htmlspecialchars($order["file"]) 
                                                : $base_url . "admin/uploads/" . htmlspecialchars($order["file"]);
                                        ?>
                                        <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($order['productName']) ?>" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-grow">
                                        <h3 class="font-serif text-2xl text-white"><?= htmlspecialchars($order['productName']) ?></h3>
                                        <p class="text-gray-400 text-sm mt-1"><?= htmlspecialchars($order['category']) ?></p>
                                    </div>
                                </div>

                                <div class="space-y-4 pt-6 border-t border-white/10">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-400 uppercase tracking-widest">Inventory Status</span>
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest <?= (int)$order['stock'] > 0 ? 'bg-gold/20 text-gold' : 'bg-red-500/20 text-red-500' ?>">
                                            <?= (int)$order['stock'] > 0 ? 'In Stock' : 'Out of Stock' ?>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-end">
                                        <span class="text-gray-400 uppercase tracking-widest text-sm">Investment</span>
                                        <div class="text-right">
                                            <p class="text-[10px] text-gold uppercase tracking-widest mb-1">Inclusive of Taxes</p>
                                            <p class="text-3xl font-serif text-white">RS <span id="product_price"><?= $order['price'] ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="glass rounded-2xl p-12 text-center">
                            <i class="fas fa-search text-gold text-4xl mb-6"></i>
                            <p class="text-gray-400">Our archives could not locate the requested piece.</p>
                        </div>
                    <?php endif; ?>

                    <div class="p-8 border border-white/5 rounded-2xl bg-white/5">
                        <div class="flex gap-4 items-center">
                            <div class="w-10 h-10 rounded-full bg-gold/10 flex items-center justify-center text-gold">
                                <i class="fas fa-shield-halved"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-widest text-white">Secure Encrypted Checkout</h4>
                                <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-widest">Your data is protected by military-grade encryption.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Shipping Form -->
                <div class="w-full md:w-1/2 animate-fade-up" style="animation-delay: 0.4s;">
                    <div class="glass rounded-2xl p-8 md:p-12">
                        <h3 class="font-serif text-3xl text-white mb-8">Shipping Dossier</h3>

                        <?php if (isset($formError)): ?>
                            <div class="mb-8 p-4 bg-red-500/10 border-l-4 border-red-500 text-red-500 text-xs uppercase tracking-widest font-bold">
                                <?= htmlspecialchars($formError) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Full Name</label>
                                    <input type="text" name="fullname" required value="<?= htmlspecialchars($userData['fullname'] ?? '') ?>"
                                           placeholder="Alexander Sterling"
                                           class="w-full px-6 py-4 rounded-xl luxury-input text-sm">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Email Address</label>
                                    <input type="email" name="email" required value="<?= htmlspecialchars($userData['email'] ?? '') ?>"
                                           placeholder="sterling@luxury.com"
                                           class="w-full px-6 py-4 rounded-xl luxury-input text-sm">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Phone Number</label>
                                <input type="tel" id="phone" name="phone" required value="<?= htmlspecialchars($userData['phone'] ?? '') ?>"
                                       class="w-full px-6 py-4 rounded-xl luxury-input text-sm">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Street Residence</label>
                                <textarea name="address" rows="3" required placeholder="124 Luxury Blvd, Floor 7"
                                          class="w-full px-6 py-4 rounded-xl luxury-input text-sm resize-none"><?= htmlspecialchars($userData['address'] ?? '') ?></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">City</label>
                                    <input type="text" name="city" value="<?= htmlspecialchars($userData['city'] ?? '') ?>"
                                           placeholder="New York"
                                           class="w-full px-6 py-4 rounded-xl luxury-input text-sm">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">State</label>
                                    <input type="text" name="state" value="<?= htmlspecialchars($userData['state'] ?? '') ?>"
                                           placeholder="NY"
                                           class="w-full px-6 py-4 rounded-xl luxury-input text-sm">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Zip Code</label>
                                    <input type="text" name="zip" value="<?= htmlspecialchars($userData['zip'] ?? '') ?>"
                                           placeholder="10001"
                                           class="w-full px-6 py-4 rounded-xl luxury-input text-sm">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Country of Origin</label>
                                <select name="country" class="w-full px-6 py-4 rounded-xl luxury-input text-sm cursor-pointer">
                                    <option value="US" <?= ($userData['country'] ?? '') === 'US' ? 'selected' : '' ?>>United States</option>
                                    <option value="PK" <?= ($userData['country'] ?? '') === 'PK' ? 'selected' : '' ?>>Pakistan</option>
                                    <option value="UK" <?= ($userData['country'] ?? '') === 'UK' ? 'selected' : '' ?>>United Kingdom</option>
                                    <option value="CA" <?= ($userData['country'] ?? '') === 'CA' ? 'selected' : '' ?>>Canada</option>
                                    <option value="AU" <?= ($userData['country'] ?? '') === 'AU' ? 'selected' : '' ?>>Australia</option>
                                </select>
                            </div>

                            <div class="pt-6">
                                <button id="checkoutBtn" type="submit" class="w-full py-6 bg-gold text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-xl hover:shadow-[0_0_30px_rgba(197,160,89,0.3)] transition-all duration-500 transform hover:-translate-y-1">
                                    <span class="flex items-center justify-center gap-4">
                                        Proceed to Secure Payment
                                        <i class="fas fa-arrow-right"></i>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Initialize international telephone input
        const phoneInputField = document.querySelector("#phone");
        const phoneInput = window.intlTelInput(phoneInputField, {
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            preferredCountries: ['us', 'gb', 'pk', 'ca', 'au'],
            separateDialCode: true,
            initialCountry: "auto",
            geoIpLookup: function(callback) {
                fetch("https://ipapi.co/json")
                    .then(function(res) { return res.json(); })
                    .then(function(data) { callback(data.country_code); })
                    .catch(function() { callback('us'); });
            }
        });

        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                let date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/";
        }

        // Set product price cookie when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const priceElement = document.getElementById("product_price");
            const productElement = document.getElementById("product_id_to_buy");
            if (priceElement && productElement) {
                setCookie("product_price_to_buy", priceElement.innerText.trim(), 1);
                setCookie("product_id_to_buy", productElement.innerText.trim(), 1);
            }
        });
    </script>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("pk_test_51RvXUZFkLPP5UElNxoBaUiIwk7W66tS4ll25XIjV9tAEFbTgebr8U82CXr84pPS0cWwIgShuume5mHuHm352fHMh00QT29K9Y2");

        document.getElementById("checkoutBtn").addEventListener("click", function(e) {
            // Only proceed if form is valid
            const form = this.closest('form');
            if (!form.checkValidity()) return;

            e.preventDefault();
            
            // First submit form data to cookies (handled by PHP on refresh, 
            // but for immediate stripe redirect we need it now or just let form submit normally)
            // Actually, the original code had a fetch to checkout.php. 
            // Let's keep the fetch logic but ensure it happens after form validation.
            
            fetch("../checkout.php", {
                method: "POST"
            })
            .then(res => res.json())
            .then(data => {
                if (data.id) {
                    stripe.redirectToCheckout({
                        sessionId: data.id
                    });
                } else {
                    alert("Acquisition Error: " + data.error);
                }
            })
            .catch(err => {
                console.error("Stripe Redirect Error:", err);
            });
        });
    </script>

    <?php include 'footer.php'; ?>
</body>

</html>