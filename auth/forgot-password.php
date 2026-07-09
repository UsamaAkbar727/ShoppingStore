<?php
require_once("session.php");
include("../mailer.php");
include("../configshoppingstore.php");

$msg_type = '';
$msg_text = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $stmt  = $conn->prepare("SELECT fullname, password, isBlock FROM user WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $data  = $stmt->fetch();

    if ($data) {
        if ($data['isBlock']) {
            $msg_type = 'error';
            $msg_text = 'Your account has been blocked. Please contact support.';
        } else {
            $token   = $data['password'];
            $subject = 'FashionStore - Reset Your Password';
            $body    = '<a href="http://' . $_SERVER['HTTP_HOST'] . $base_url . 'components/reset-password.php?email=' . urlencode($email) . '&token=' . urlencode($token) . '">Click here to reset your password</a>';

            if (sendEmail($email, $data['fullname'], $subject, $body)) {
                $msg_type = 'success';
                $msg_text = 'Reset link sent! Please check your email.';
            } else {
                $msg_type = 'error';
                $msg_text = 'Failed to send email. Please try again.';
            }
        }
    } else {
        $msg_type = 'error';
        $msg_text = 'No account found with that email address.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/48/shopping-bag.png">
    <title>Forgot Password | FashionStore</title>
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
                <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1200&q=80"
                     alt="Fashion"
                     class="absolute inset-0 w-full h-full object-cover opacity-90 scale-110 hover:scale-100 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-12">
                    <h1 class="font-serif text-4xl text-white mb-4">Recover Access.</h1>
                    <p class="text-white/70 text-sm tracking-widest uppercase">Retrieve Your Personal Luxury Archive</p>
                </div>
            </div>

            <!-- Right: Forgot Password form -->
            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white relative">
                <!-- Close Button -->
                <a href="../index.php" class="absolute top-6 right-8 text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-gold transition">
                    <i class="fas fa-times mr-1"></i> Close
                </a>
                <div class="mb-8 text-center md:text-left">
                    <h2 class="font-serif text-3xl text-luxury mb-2">Forgot Password</h2>
                    <p class="text-gray-500 text-sm">Enter your email to request a reset link</p>
                </div>

                <!-- Error alert -->
                <?php if ($msg_type === 'error'): ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg text-red-700 text-sm flex items-start gap-3">
                    <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
                    <span><?php echo htmlspecialchars($msg_text); ?></span>
                </div>
                <?php endif; ?>

                <!-- Success alert -->
                <?php if ($msg_type === 'success'): ?>
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg text-green-700 text-sm flex items-center gap-3">
                    <i class="fas fa-check-circle flex-shrink-0"></i>
                    <span><?php echo htmlspecialchars($msg_text); ?></span>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">

                    <!-- Email -->
                    <div>
                        <label for="email" class="field-label">Email Address</label>
                        <input type="email" id="email" name="email" required
                               placeholder="you@example.com"
                               class="luxury-input"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                            class="w-full py-3.5 luxury-btn font-semibold tracking-widest uppercase text-sm rounded-xl shadow-md hover:shadow-xl transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane text-xs"></i> Send Reset Link
                    </button>

                    <div class="text-center pt-3 space-y-2">
                        <p class="text-sm text-gray-500">
                            Remembered your password?
                            <a href="login.php" class="text-luxury font-bold hover:text-gold transition border-b border-gold ml-1">Login</a>
                        </p>
                        <p class="text-sm text-gray-500">
                            Don't have an account?
                            <a href="signup.php" class="text-luxury font-bold hover:text-gold transition border-b border-gold ml-1">Create one</a>
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </main>

</body>
</html>