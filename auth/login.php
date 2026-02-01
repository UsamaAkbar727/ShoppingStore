<?php
include("../configshoppingstore.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    setcookie("email", $email, time() + 180, "/");
    if (!empty($email) && !empty($password)) {
        try {
            $user = $conn->prepare("SELECT `id`,`email`, `password` FROM `user` WHERE email='" . $email . "'");
            $user->execute();
            $res = $user->fetchAll();

            if ($res) {
                $hashedPass = $res[0]["password"];
                $checkpassword = password_verify($password, $hashedPass);
                if ($checkpassword) {
                    setcookie("email", $email, time() + 86400, "/");
                    setcookie("token", $hashedPass, time() + 86400, "/");
                    setcookie("user_id", $res[0]["id"], time() + 86400, "/");
                    $success = "Login Successful";
                    header("Location: /index.php");
                    exit();
                }
            }
        } catch (\Throwable $th) {
            echo $th;
        }
    } else {
        $error = "Please enter both email and password";
    }
}

$page_title = "Login - FashionStore";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #ffffffff, #82cceeff);
            background-size: 200% 200%;
            animation: gradientMove 8s ease infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-card {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.8);
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 2rem;
        }

        input {
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }

        input:focus {
            background: white;
            box-shadow: 0 0 8px rgba(102,126,234,0.6);
        }

        .btn-gradient {
            background: linear-gradient(90deg, #667eea, #764ba2);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            background: linear-gradient(90deg, #5a67d8, #6b46c1);
        }
    </style>
</head>

<body class="font-sans">

    <?php include '../components/header.php'; ?>

    <main class="min-h-screen flex items-center justify-center px-4">
        <section class="w-full max-w-md">
            <div class="login-card">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Login to Your Account</h2>

                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $success; ?>
                    </div>
                    <script>
                        setTimeout(function () {
                            window.location.href = "/index.php";
                        }, 12000);
                    </script>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <div class="text-right mt-2">
                            <a href="/auth/forgot-password.php" class="text-sm text-indigo-600 hover:underline">Forgot password?</a>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2 px-4 rounded font-medium btn-gradient">
                        Login
                    </button>

                    <div class="mt-4 text-center">
                        <p class="text-gray-600">Don't have an account? <a href="signup.php" class="text-indigo-600 hover:underline">Sign up</a></p>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <?php include '../components/footer.php'; ?>
</body>
</html>
