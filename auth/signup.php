<?php
require_once("session.php");
include("../configshoppingstore.php");

check_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $hashedPass = password_hash($password, PASSWORD_BCRYPT);
        try {
            // Check if email already exists
            $check = $conn->prepare("SELECT id FROM `user` WHERE email = ?");
            $check->execute([$email]);
            if ($check->fetch()) {
                $error = "Email already registered. Please login.";
            } else {
                $user = $conn->prepare("INSERT INTO `user`(`fullname`, `email`, `password`) VALUES (?,?,?)");
                $res = $user->execute([$name, $email, $hashedPass]);
                
                if ($res) {
                    $new_user_id = $conn->lastInsertId();
                    
                    // Auto-login after signup
                    $_SESSION['user_id'] = $new_user_id;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_role'] = 'user';
                    
                    // Set cookies for persistence
                    setcookie("user_id", $new_user_id, time() + 86400, "/");

                    $success = "Account created! Redirecting to home...";
                    header("Location: " . $base_url . "index.php");
                    exit();
                }
            }
        } catch (\Throwable $th) {
            $error = "System Error: " . $th->getMessage();
            error_log($th->getMessage());
        }
    }
}

$page_title = "Join FashionStore | Luxury Experience";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../components/favicon.php'; ?>
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
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
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f8f8f8;
            background-image: radial-gradient(#e5e5e5 0.5px, transparent 0.5px);
            background-size: 20px 20px;
        }

        .luxury-card {
            background: white;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.04);
        }

        /* ── Standard labelled inputs ── */
        .field-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 7px;
        }

        .luxury-input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.93rem;
            background: #fafafa;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
            outline: none;
            color: #1a1a1a;
        }
        .luxury-input:focus {
            border-color: #c5a059;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(197,160,89,0.13);
        }
        .luxury-input::placeholder { color: #c0c0c0; }

        .luxury-btn {
            background: #1a1a1a;
            color: white;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .luxury-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0;
            background: #c5a059;
            transition: height 0.3s ease;
            z-index: -1;
        }

        .luxury-btn:hover::after {
            height: 100%;
        }
    </style>
</head>

<body class="font-sans min-h-screen flex flex-col">

    <!-- Navbar Hidden for Guest View -->

    <main class="flex-grow flex items-center justify-center p-6 bg-transparent">
        <div class="max-w-4xl w-full flex flex-col md:flex-row-reverse luxury-card rounded-2xl overflow-hidden">
            <!-- Left Side: Aesthetic Image -->
            <div class="hidden md:block w-1/2 bg-luxury relative overflow-hidden">
                <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1200&q=80"
                    alt="Fashion Collection"
                    class="absolute inset-0 w-full h-full object-cover opacity-90 hover:scale-105 transition-transform duration-[2000ms]">
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-12">
                    <h1 class="font-serif text-4xl text-white mb-4">Timeless Elegance.</h1>
                    <p class="text-white/70 text-sm tracking-widest uppercase">Start Your Journey With Us</p>
                </div>
            </div>

            <!-- Right Side: Signup Form -->
            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white relative">
                <!-- Close Button -->
                <a href="../index.php" class="absolute top-6 right-8 text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-gold transition">
                    <i class="fas fa-times mr-1"></i> Close
                </a>
                <div class="mb-8 text-center md:text-left">
                    <h2 class="font-serif text-3xl text-luxury mb-2">Create Account</h2>
                    <p class="text-gray-500 text-sm">Join the elite world of FashionStore</p>
                </div>

                <?php if (isset($error)): ?>
                    <div
                        class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm flex items-center animate-pulse">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="field-label">Full Name</label>
                        <input type="text" id="name" name="name" required placeholder="John Doe"
                            class="luxury-input"
                            value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="field-label">Email Address</label>
                        <input type="email" id="email" name="email" required placeholder="you@example.com"
                            class="luxury-input"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="field-label">Password</label>
                        <input type="password" id="password" name="password" required minlength="6" placeholder="••••••••"
                            class="luxury-input">
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full py-3.5 luxury-btn font-semibold tracking-widest uppercase text-sm rounded-xl shadow-md hover:shadow-xl transition-all">
                            Create Account
                        </button>
                    </div>

                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-500">
                            Already have an account?
                            <a href="login.php"
                                class="text-luxury font-bold hover:text-gold transition border-b border-gold ml-1">Sign In</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer Hidden for Guest View -->
</body>

</html>