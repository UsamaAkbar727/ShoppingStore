<?php
include("../configshoppingstore.php");

if (isset($_SESSION['user_id'])) {
    header("Location: /account.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        die("Password does not match");
    } else {
        $hashedPass = password_hash($password, PASSWORD_BCRYPT);
    }

    try {
        $user = $conn->prepare("INSERT INTO `user`(`id`, `fullname`, `email`, `password`) VALUES (null,?,?,?)");
        $res = $user->execute([$name, $email, $hashedPass]);
    } catch (\Throwable $th) {
        throw $th;
    } finally {
        header("location: login.php");
    }
}

$page_title = "Sign Up - FashionStore";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#5A189A",
                        secondary: "#F59E0B",
                        accent: "#FAFAFA"
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans min-h-screen flex flex-col" style="background: linear-gradient(135deg, #ffffff, #82ccee);">

    <!-- Navbar -->
    <?php include '../components/header.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center px-4 py-12">
<div class="max-w-md w-full bg-gradient-to-br from-[#f4f9ff] via-[#edf4ff] to-[#e8eaff] rounded-2xl shadow-2xl overflow-hidden border border-[#cfdaf5]">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#5a7bd8] to-[#7c5ac9] py-6 px-6 text-center shadow-md">
                <h2 class="text-white text-3xl font-bold tracking-wide">Create Your Account</h2>
                <p class="text-white/90 text-sm mt-1">Join FashionStore Today</p>
            </div>
            
            <!-- Form -->
            <div class="p-6">
                <form method="POST" class="space-y-5">
                    
                    <!-- Name -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Full Name</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" name="name" required
                                class="w-full pl-10 pr-4 py-3 bg-white/90 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#7c5ac9] focus:border-transparent transition">
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" required
                                class="w-full pl-10 pr-4 py-3 bg-white/90 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#7c5ac9] focus:border-transparent transition">
                        </div>
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password" minlength="6" required
                                class="w-full pl-10 pr-4 py-3 bg-white/90 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#7c5ac9] focus:border-transparent transition">
                        </div>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="confirm_password" minlength="6" required
                                class="w-full pl-10 pr-4 py-3 bg-white/90 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#7c5ac9] focus:border-transparent transition">
                        </div>
                    </div>
                    
                    <!-- Submit -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#5a7bd8] to-[#7c5ac9] hover:opacity-90 text-white py-3 px-4 rounded-lg font-semibold shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition duration-300">
                        Create Account
                    </button>
                    
                    <p class="text-center text-gray-600 mt-4">
                        Already have an account? 
                        <a href="login.php" class="text-[#5a7bd8] font-medium hover:underline">Login</a>
                    </p>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include '../components/footer.php'; ?>

</body>
</html>
