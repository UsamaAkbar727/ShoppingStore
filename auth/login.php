<?php
require_once("session.php");
include("../configshoppingstore.php");

check_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = $_POST['identifier']; // Can be email or username
    $password = $_POST['password'];

    if (!empty($identifier) && !empty($password)) {
        try {
            // Use SELECT * so optional columns (role, etc.) don't break the query
            $stmt = $conn->prepare("SELECT * FROM `user` WHERE email = ? LIMIT 1");
            $stmt->execute([$identifier]);

            $user_data = $stmt->fetch();

            if ($user_data) {
                if (password_verify($password, $user_data["password"])) {
                    $_SESSION['user_id']   = $user_data['id'];
                    $_SESSION['user_name'] = $user_data['fullname'];
                    $_SESSION['user_role'] = $user_data['role'] ?? 'user'; 
                    
                    if ($_SESSION['user_role'] === 'admin') {
                        $_SESSION['admin_logged_in'] = true;
                    }
                    
                    setcookie("token",   $user_data["password"], time() + 86400, "/");
                    setcookie("user_id", $user_data["id"],       time() + 86400, "/");

                    $success = "Welcome back, " . explode(' ', $user_data['fullname'])[0] . "!";
                    header("Location: " . $base_url . "index.php");
                    exit();
                } else {
                    $error = "Invalid password. Please try again.";
                }
            } else {
                $error = "No account found with that email.";
            }
        } catch (\Throwable $th) {
            $error = "System Error: " . $th->getMessage();
            error_log($th->getMessage());
        }
    } else {
        $error = "Please fill in all fields.";
    }
}

$page_title = "Login | FashionStore";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/48/shopping-bag.png">
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                        sans:  ['Inter', 'sans-serif'],
                    },
                    colors: {
                        luxury: '#1a1a1a',
                        gold:   '#c5a059',
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
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.04);
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

        /* password wrapper with eye icon */
        .pw-wrap { position: relative; }
        .pw-wrap .luxury-input { padding-right: 44px; }
        .eye-btn {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            font-size: 0.95rem;
            line-height: 1;
            padding: 2px;
            transition: color 0.2s;
        }
        .eye-btn:hover { color: #c5a059; }

        /* Luxury CTA button */
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
            bottom: 0; left: 0;
            width: 100%; height: 0;
            background: #c5a059;
            transition: height 0.3s ease;
            z-index: -1;
        }
        .luxury-btn:hover::after { height: 100%; }
    </style>
</head>

<body class="font-sans min-h-screen flex flex-col">

    <main class="flex-grow flex items-center justify-center p-6">
        <div class="max-w-4xl w-full flex flex-col md:flex-row luxury-card rounded-2xl overflow-hidden">

            <!-- Left: Fashion image -->
            <div class="hidden md:block w-1/2 bg-luxury relative overflow-hidden">
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1200&q=80"
                     alt="Fashion"
                     class="absolute inset-0 w-full h-full object-cover opacity-90 scale-110 hover:scale-100 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-12">
                    <h1 class="font-serif text-4xl text-white mb-4">Elevate Your Style.</h1>
                    <p class="text-white/70 text-sm tracking-widest uppercase">Experience Premium Fashion</p>
                </div>
            </div>

            <!-- Right: Login form -->
            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white">
                <div class="mb-8 text-center md:text-left">
                    <h2 class="font-serif text-3xl text-luxury mb-2">Welcome Back</h2>
                    <p class="text-gray-500 text-sm">Please enter your details to sign in</p>
                </div>

                <!-- Error alert -->
                <?php if (isset($error)): ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg text-red-700 text-sm flex items-start gap-3">
                    <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
                <?php endif; ?>

                <!-- Success alert -->
                <?php if (isset($success)): ?>
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg text-green-700 text-sm flex items-center gap-3">
                    <i class="fas fa-check-circle flex-shrink-0"></i>
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">

                    <!-- Email -->
                    <div>
                        <label for="identifier" class="field-label">Email Address</label>
                        <input type="email" id="identifier" name="identifier" required
                               placeholder="you@example.com"
                               class="luxury-input"
                               value="<?php echo isset($_POST['identifier']) ? htmlspecialchars($_POST['identifier']) : ''; ?>">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="field-label">Password</label>
                        <div class="pw-wrap">
                            <input type="password" id="password" name="password" required
                                   placeholder="••••••••"
                                   class="luxury-input">
                            <button type="button" id="togglePw" class="eye-btn" aria-label="Show / hide password">
                                <i id="eyeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember / Forgot -->
                    <div class="flex items-center justify-between text-xs text-gray-500 pt-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" class="accent-gold w-3.5 h-3.5">
                            <span class="group-hover:text-gold transition">Remember me</span>
                        </label>
                        <a href="forgot-password.php" class="hover:text-gold transition font-medium">Forgot Password?</a>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                            class="w-full py-3.5 luxury-btn font-semibold tracking-widest uppercase text-sm rounded-xl shadow-md hover:shadow-xl transition-all">
                        Sign In
                    </button>

                    <div class="text-center pt-1">
                        <p class="text-sm text-gray-500">
                            Don't have an account?
                            <a href="signup.php" class="text-luxury font-bold hover:text-gold transition border-b border-gold ml-1">Create one</a>
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <script>
        const togglePw = document.getElementById('togglePw');
        const pwInput  = document.getElementById('password');
        const eyeIcon  = document.getElementById('eyeIcon');

        togglePw.addEventListener('click', () => {
            const show = pwInput.type === 'password';
            pwInput.type = show ? 'text' : 'password';
            eyeIcon.classList.toggle('fa-eye',       !show);
            eyeIcon.classList.toggle('fa-eye-slash',  show);
        });
    </script>

</body>
</html>
