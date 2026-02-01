<?php
include("../configshoppingstore.php");
// include '../components/header.php';

if (!isset($_COOKIE['user_id'])) {
    header("Location: /auth/login.php");
    exit();
}

$user_id = $_COOKIE['user_id'];
$userData = [];
$orders = [];

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
    <title>Purchase Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
</head>

<body class="bg-gradient-to-b from-gray-100 to-gray-200 font-sans min-h-screen">
    <section class="max-w-4xl mx-auto bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl p-10 mt-12 border border-gray-200 mb-10">
        <h2 class="text-4xl font-extrabold text-center text-gray-800 mb-12 tracking-tight">🧾 Purchase Details</h2>

        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <div class="hidden" id="product_id_to_buy"> <?= $order['id'] ?></div>
                <div class="mb-10 border border-gray-100 rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300 bg-gradient-to-br from-white to-gray-50">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Product Information</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-800 mb-6">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                <span class="flex items-center font-medium text-gray-600">
                                    📦 Product
                                </span>
                                <span class="text-gray-900 font-semibold"><?= htmlspecialchars($order['productName']) ?></span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="flex items-center font-medium text-gray-600">
                                    🔢 Stock
                                </span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full <?= (int)$order['stock'] > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= (int)$order['stock'] ?> Available
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                <span class="flex items-center font-medium text-gray-600">
                                    💰 Price
                                </span>
                                RS 
                                <span id="product_price" class="text-green-600 font-mono font-bold">
                                    <?= $order['price'] ?>
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="flex items-center font-medium text-gray-600">
                                    📅 Order Date
                                </span>
                                <span class="text-gray-900"><?= date("d M Y") ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information Form -->
                <div class="mb-10 border border-gray-100 rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300 bg-gradient-to-br from-white to-gray-50">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Shipping Information</h3>
                    
                    <?php if (isset($formError)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?= htmlspecialchars($formError) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="fullname" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" id="fullname" name="fullname" required
                                    value="<?= htmlspecialchars($userData['fullname'] ?? '') ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" id="email" name="email" required
                                    value="<?= htmlspecialchars($userData['email'] ?? '') ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required
                                value="<?= htmlspecialchars($userData['phone'] ?? '') ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Street Address *</label>
                            <textarea id="address" name="address" rows="2" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?= htmlspecialchars($userData['address'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" id="city" name="city"
                                    value="<?= htmlspecialchars($userData['city'] ?? '') ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                                <input type="text" id="state" name="state"
                                    value="<?= htmlspecialchars($userData['state'] ?? '') ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            
                            <div>
                                <label for="zip" class="block text-sm font-medium text-gray-700 mb-1">ZIP/Postal Code</label>
                                <input type="text" id="zip" name="zip"
                                    value="<?= htmlspecialchars($userData['zip'] ?? '') ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                        
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <select id="country" name="country" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="US" <?= ($userData['country'] ?? '') === 'US' ? 'selected' : '' ?>>United States</option>
                                <option value="PK" <?= ($userData['country'] ?? '') === 'PK' ? 'selected' : '' ?>>Pakistan</option>
                                <option value="UK" <?= ($userData['country'] ?? '') === 'UK' ? 'selected' : '' ?>>United Kingdom</option>
                                <option value="CA" <?= ($userData['country'] ?? '') === 'CA' ? 'selected' : '' ?>>Canada</option>
                                <option value="AU" <?= ($userData['country'] ?? '') === 'AU' ? 'selected' : '' ?>>Australia</option>
                                <!-- Add more countries as needed -->
                            </select>
                        </div>
                        
                        <div class="flex justify-center pt-4">
                            <button id="checkoutBtn" type="submit" class="inline-block px-6 py-3 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300">
                                💳 Proceed to Payment
                            </button>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-gray-500 text-lg">No product found.</p>
        <?php endif; ?>
    </section>

    <script>
        // Initialize international telephone input
        const phoneInputField = document.querySelector("#phone");
        const phoneInput = window.intlTelInput(phoneInputField, {
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            preferredCountries: ['us', 'gb', 'in', 'ca', 'au'],
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
            if (priceElement) {
                setCookie("product_price_to_buy", priceElement.innerHTML, 1);
                setCookie("product_id_to_buy", productElement.innerHTML, 1);
            }
        });
    </script>

     <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("pk_test_51RvXUZFkLPP5UElNxoBaUiIwk7W66tS4ll25XIjV9tAEFbTgebr8U82CXr84pPS0cWwIgShuume5mHuHm352fHMh00QT29K9Y2");

        document.getElementById("checkoutBtn").addEventListener("click", function() {
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
                        alert("Error: " + data.error);
                    }
                });
        });
    </script>

    <?php include '../components/footer.php'; ?>
</body>

</html>