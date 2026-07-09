<?php
require_once("../auth/session.php");
check_auth();
include("../configshoppingstore.php");

$user_id = $_SESSION['user_id'];
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $error = "All fields are required.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "New passwords do not match.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT `password` FROM `user` WHERE `id` = ?");
            $stmt->execute([$user_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row || !password_verify($currentPassword, $row['password'])) {
                $error = "Current password is incorrect.";
            } else {
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE `user` SET `password` = ? WHERE `id` = ?");
                $update->execute([$newHashedPassword, $user_id]);

                header("Location: user-account.php");
                exit();
            }
        } catch (\Throwable $th) {
            $error = "Something went wrong.";
        }
    }
}

$page_title = "Change Password";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/48/shopping-bag.png">
    <title><?php echo $page_title; ?> | FashionStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        silver: '#f8f9fa',
                        accent: '#e5e7eb'
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
        
        /* Seamless Dark Header override */
        .dark-header header {
            background-color: rgba(10, 10, 10, 0.9) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }
        .dark-header .nav-link, .dark-header header a, .dark-header header i {
            color: white !important;
        }
        .dark-header .nav-link:hover { color: #c5a059 !important; }
        .dark-header .sticky-nav { background-color: transparent !important; }

        .luxury-input {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white !important;
            transition: all 0.3s ease;
        }
        .luxury-input:focus {
            border-color: #c5a059;
            background: rgba(255, 255, 255, 0.08);
            outline: none;
            box-shadow: 0 0 15px rgba(197, 160, 89, 0.15);
        }
    </style>
</head>

<body class="font-sans overflow-x-hidden dark-header">
    <?php include '../components/header.php'; ?>

<main class="min-h-[calc(100vh-200px)] py-32 bg-[#0a0a0a]">
  <div class="max-w-md mx-auto px-6">
    <!-- Breadcrumbs -->
    <nav class="flex mb-8 text-xs uppercase tracking-widest text-gray-500" aria-label="Breadcrumb">
      <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
          <a href="../index.php" class="hover:text-gold transition-colors">Home</a>
        </li>
        <li>
          <div class="flex items-center">
            <i class="fas fa-chevron-right text-[8px] mx-2 text-gold/30"></i>
            <a href="user-account.php" class="hover:text-gold transition-colors">Account</a>
          </div>
        </li>
        <li aria-current="page">
          <div class="flex items-center">
            <i class="fas fa-chevron-right text-[8px] mx-2 text-gold/30"></i>
            <span class="text-white font-semibold">Change Password</span>
          </div>
        </li>
      </ol>
    </nav>

    <div class="glass rounded-3xl overflow-hidden">
      <div class="p-8 md:p-12">
        <div class="text-center mb-8">
          <h2 class="font-serif text-3xl mb-2 text-white">Change Password</h2>
          <p class="text-gray-400 font-light tracking-wide text-xs">Secure your account with a new credentials passcode</p>
        </div>

        <?php if (!empty($error)): ?>
          <div class="bg-red-500/10 border-l-4 border-red-500 text-red-400 p-4 rounded mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <p class="text-sm font-medium"><?php echo htmlspecialchars($error); ?></p>
          </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
          <div class="space-y-2">
            <label class="block text-[10px] uppercase tracking-widest font-semibold text-gray-400 px-1">Current Password</label>
            <input
              type="password"
              name="current_password"
              required
              class="w-full px-4 py-3.5 luxury-input rounded-xl text-sm"
            >
          </div>

          <div class="space-y-2">
            <label class="block text-[10px] uppercase tracking-widest font-semibold text-gray-400 px-1">New Password</label>
            <input
              type="password"
              name="new_password"
              required
              class="w-full px-4 py-3.5 luxury-input rounded-xl text-sm"
            >
          </div>

          <!-- Confirm New Password -->
          <div class="space-y-2">
            <label class="block text-[10px] uppercase tracking-widest font-semibold text-gray-400 px-1">Confirm New Password</label>
            <input
              type="password"
              name="confirm_password"
              required
              class="w-full px-4 py-3.5 luxury-input rounded-xl text-sm"
            >
          </div>

          <div class="flex gap-4 pt-4 border-t border-white/5">
              <a href="user-account.php" class="flex-1 px-6 py-4 border border-white/10 text-white hover:bg-white/5 text-xs font-semibold rounded-xl text-center transition-colors duration-300">Cancel</a>
              <button
                type="submit"
                class="flex-1 px-6 py-4 bg-gold text-white text-xs font-semibold rounded-xl hover:shadow-[0_10px_20px_rgba(197,160,89,0.3)] transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0"
              >
                Update
              </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php include '../components/footer.php'; ?>
</body>
</html>