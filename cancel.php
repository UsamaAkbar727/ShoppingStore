<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'components/favicon.php'; ?>
    <title>Payment Cancelled | FashionStore</title>
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
                        silver: '#f8f9fa'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap');
        body { background-color: #0a0a0a; color: #fff; }
        .glass {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body class="font-sans min-h-screen flex items-center justify-center p-6">
    <div class="glass p-10 md:p-16 rounded-3xl w-full max-w-md text-center space-y-8">
        
        <div class="mb-6 bg-red-500/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto border border-red-500/20">
            <i class="fas fa-times text-red-500 text-3xl"></i>
        </div>

        <div class="space-y-4">
            <span class="text-red-500 text-xs uppercase tracking-[0.6em] font-black">Transaction Cancelled</span>
            <h1 class="font-serif text-4xl text-white">Payment Aborted</h1>
            <p class="text-gray-400 text-sm leading-relaxed">
                Your transaction has been cancelled. If this was unintentional, you can safely return to your cart and try again.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
            <a href="checkout.php" 
               class="px-8 py-4 bg-gold text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-xl hover:shadow-[0_10px_20px_rgba(197,160,89,0.2)] transition-all duration-500">
               💳 Return to Checkout
            </a>
            <a href="index.php" 
               class="px-8 py-4 border border-white/20 text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-xl hover:bg-white/5 transition-all duration-500">
               ⬅ Go to Store
            </a>
        </div>
    </div>
</body>
</html>