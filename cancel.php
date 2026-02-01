<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-300 to-gray-200 flex items-center justify-center min-h-screen">
    <div class="bg-white p-10 rounded-3xl shadow-2xl w-full max-w-md text-center border border-gray-200">

    <div class="mb-6 bg-red-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto shadow-inner">
            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" stroke-width="2" 
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>

        <h1 class="text-3xl font-extrabold text-gray-900 mb-3">Payment Cancelled</h1>
        <p class="text-gray-500 mb-8 leading-relaxed">
            Your transaction has been cancelled.  
            If this was unintentional, you can retry securely.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button id="checkoutBtn" 
               class="px-6 py-3 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300">
               💳 Try Again
            </button>
            <a href="index.php" 
               class="px-6 py-3 rounded-full bg-gray-200 text-gray-800 font-medium shadow hover:shadow-md hover:scale-105 transition-all duration-300">
               ⬅ Go Home
            </a>
        </div>
    </div>
</body>
</html>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("pk_test_51RvXUZFkLPP5UElNxoBaUiIwk7W66tS4ll25XIjV9tAEFbTgebr8U82CXr84pPS0cWwIgShuume5mHuHm352fHMh00QT29K9Y2");

        document.getElementById("checkoutBtn").addEventListener("click", function() {
            fetch("checkout.php", {
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
</body>

</html>