<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?></title>
    <meta name="description" content="<?php echo $site_description; ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        secondary: "#1e40af",
                        accent: "#f43f5e",
                        dark: "#1f2937",
                        light: "#f9fafb"
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    boxShadow: {
                        'nav': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                        'float': '0 10px 25px -5px rgba(0, 0, 0, 0.1)'
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #3b82f6;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover:after {
            width: 100%;
        }
        
        .cart-badge {
            transition: transform 0.2s ease, background-color 0.2s ease;
        }
        
        .cart-icon:hover .cart-badge {
            transform: scale(1.1);
            background-color: #f43f5e;
        }
        
        .mobile-menu {
            transition: max-height 0.3s ease-out;
            overflow: hidden;
        }
    </style>
</head>

<body>
<header class="bg-white shadow-nav sticky top-0 z-50 text-dark">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo with improved styling -->
            <a href="../index.php" class="flex items-center text-2xl font-bold">
                <span class="bg-primary p-2 rounded-lg text-white mr-2 transform hover:rotate-12 transition">
                    <i class="fas fa-tshirt"></i>
                </span>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-accent">
                    FashionStore
                </span>
            </a>

            <!-- Main Navigation -->
            <nav class="hidden md:flex space-x-8 items-center">
                <a href="/index.php" class="nav-link font-medium hover:text-primary">Home</a>
                <a href="/components/product.php" class="nav-link font-medium hover:text-primary">Shop</a>
                <a href="/components/about.php" class="nav-link font-medium hover:text-primary">About</a>
                <a href="/components/contact.php" class="nav-link font-medium hover:text-primary">Contact</a>
                
                <!-- Search bar integrated into nav -->
                <div class="relative ml-4">
                    <input type="text" placeholder="Search products..." 
                           class="pl-10 pr-4 py-2 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm w-64">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </nav>

            <!-- Action Icons -->
            <div class="flex items-center space-x-6">
                <a href="/search" class="hover:text-primary transform hover:scale-110 transition">
                    <i class="fas fa-search text-xl md:hidden"></i>
                </a>
                
                <?php if (!isset($_COOKIE["token"])): ?>
                    <a href="/auth/login.php" class="hover:text-primary transform hover:scale-110 transition group relative">
                        <i class="fas fa-user text-xl"></i>
                        <span class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 text-xs bg-dark text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                            Login
                        </span>
                    </a>
                <?php else: ?>
                    <a href="/auth/user-account.php" class="hover:text-primary transform hover:scale-110 transition group relative">
                        <i class="fas fa-user-circle text-xl"></i>
                        <span class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 text-xs bg-dark text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                            Account
                        </span>
                    </a>
                <?php endif; ?>

                <?php
                $totalItems = 0;
                $userLoggedIn = false;
                if (isset($_COOKIE['user_id'])) {
                    $userLoggedIn = true;
                    $user_id = (int) $_COOKIE['user_id'];
                    try {
                        $stmt = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
                        $stmt->execute([$user_id]);
                        $totalItems = (int) $stmt->fetchColumn();
                    } catch (\Throwable $th) {
                        $totalItems = 0;
                    }
                }
                ?>
                
                <a href="<?php echo $userLoggedIn ? '/components/cart.php' : '/auth/login.php'; ?>" 
                   class="hover:text-primary transform hover:scale-110 transition relative cart-icon group">
                    <i class="fas fa-shopping-bag text-xl"></i>
                    <?php if ($totalItems > 0): ?>
                        <span class="cart-badge absolute -top-2 -right-2 bg-accent text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            <?php echo $totalItems; ?>
                        </span>
                    <?php endif; ?>
                    <span class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 text-xs bg-dark text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                        Cart
                    </span>
                </a>

                <button class="md:hidden focus:outline-none ml-2" id="mobile-menu-button">
                    <i class="fas fa-bars text-2xl hover:text-primary transition"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu with animation -->
        <div class="md:hidden hidden mobile-menu max-h-0" id="mobile-menu">
            <div class="flex flex-col space-y-4 py-4 border-t mt-2">
                <a href="/index.php" class="nav-link font-medium hover:text-primary px-2 py-1 rounded hover:bg-gray-50">Home</a>
                <a href="/components/product.php" class="nav-link font-medium hover:text-primary px-2 py-1 rounded hover:bg-gray-50">Shop</a>
                <a href="/components/about.php" class="nav-link font-medium hover:text-primary px-2 py-1 rounded hover:bg-gray-50">About</a>
                <a href="/components/contact.php" class="nav-link font-medium hover:text-primary px-2 py-1 rounded hover:bg-gray-50">Contact</a>
                
                <div class="relative mt-2">
                    <input type="text" placeholder="Search..." 
                           class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    // Enhanced mobile menu toggle with animation
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
        menu.classList.toggle('max-h-0');
        
        if (!menu.classList.contains('hidden')) {
            menu.style.maxHeight = menu.scrollHeight + 'px';
        } else {
            menu.style.maxHeight = '0';
        }
        
        // Toggle hamburger icon
        const icon = this.querySelector('i');
        if (icon.classList.contains('fa-bars')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    });
    
    // Close mobile menu when clicking on a link
    document.querySelectorAll('#mobile-menu a').forEach(link => {
        link.addEventListener('click', () => {
            const menu = document.getElementById('mobile-menu');
            menu.classList.add('hidden');
            menu.classList.add('max-h-0');
            document.querySelector('#mobile-menu-button i').classList.remove('fa-times');
            document.querySelector('#mobile-menu-button i').classList.add('fa-bars');
        });
    });
</script>