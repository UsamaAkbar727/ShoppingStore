<?php
require_once("../auth/session.php");
check_auth();
include("../configshoppingstore.php");

$user_id  = $_SESSION['user_id'];
$userData = [];

try {
    $stmt = $conn->prepare("SELECT fullname, email, file FROM user WHERE id = ?");
    $stmt->execute([$user_id]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Throwable $th) {
    $error = "Could not load profile.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $file = $userData['file'] ?? '';

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $tmpName = $_FILES['profile_image']['tmp_name'];
        $newName = uniqid() . '_' . basename($_FILES['profile_image']['name']);
        $destination = $uploadDir . $newName;
        move_uploaded_file($tmpName, $destination);
        $file = $base_url . 'uploads/' . $newName; 
    }

    try {
        $update = $conn->prepare("UPDATE user SET fullname=?, email=?, file=? WHERE id=?");
        $update->execute([$fullname, $email, $file, $user_id]);
        header("Location: user-account.php");
        exit();
    } catch (\Throwable $th) {
        $error = "Failed to update profile.";
    }
}

$page_title = "Edit Profile";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../components/favicon.php'; ?>
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
  <div class="max-w-2xl mx-auto px-6">
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
            <span class="text-white font-semibold">Edit Profile</span>
          </div>
        </li>
      </ol>
    </nav>

    <div class="glass rounded-3xl overflow-hidden">
      <div class="p-8 md:p-12">
        <div class="text-center mb-10">
          <h1 class="font-serif text-3xl md:text-4xl mb-2 text-white">Profile Settings</h1>
          <p class="text-gray-400 font-light tracking-wide">Update your personal information and profile picture</p>
        </div>

        <?php if (isset($error)): ?>
          <div class="bg-red-500/10 border-l-4 border-red-500 text-red-400 p-4 rounded mb-8 flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <p class="text-sm font-medium"><?php echo htmlspecialchars($error); ?></p>
          </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-8">
          <!-- Profile Image Section -->
          <div class="flex flex-col items-center justify-center">
            <div class="relative group cursor-pointer">
              <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-luxury shadow-lg ring-1 ring-white/10 group-hover:ring-gold transition-all duration-300">
                <img
                  id="profile-preview"
                  src="<?php echo htmlspecialchars($userData['file'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($userData['fullname'] ?? 'User') . '&background=1a1a1a&color=c5a059&size=128'); ?>"
                  alt="Profile"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                >
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                  <i class="fas fa-camera text-white text-2xl"></i>
                </div>
              </div>
              <input
                type="file"
                name="profile_image"
                id="profile_image"
                accept="image/*"
                class="absolute inset-0 opacity-0 cursor-pointer z-10"
                onchange="previewImage(this)"
              >
            </div>
            <p class="mt-4 text-xs text-gray-400 uppercase tracking-[0.2em]">Click to upload new photo</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Full Name -->
            <div class="space-y-2">
              <label class="block text-[10px] uppercase tracking-widest font-semibold text-gray-400 px-1">Full Name</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                  <i class="far fa-user"></i>
                </span>
                <input
                  type="text"
                  name="fullname"
                  value="<?php echo htmlspecialchars($userData['fullname'] ?? ''); ?>"
                  required
                  placeholder="Your full name"
                  class="w-full pl-11 pr-4 py-3.5 luxury-input rounded-xl text-sm"
                >
              </div>
            </div>

            <!-- Email -->
            <div class="space-y-2">
              <label class="block text-[10px] uppercase tracking-widest font-semibold text-gray-400 px-1">Email Address</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                  <i class="far fa-envelope"></i>
                </span>
                <input
                  type="email"
                  name="email"
                  value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>"
                  required
                  placeholder="Your email address"
                  class="w-full pl-11 pr-4 py-3.5 luxury-input rounded-xl text-sm"
                >
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-white/5">
            <a href="user-account.php" class="flex-1 px-8 py-4 border border-white/10 text-white hover:bg-white/5 text-sm font-semibold rounded-xl text-center transition-colors duration-300">
              Cancel
            </a>
            <button
              type="submit"
              class="flex-grow flex-1 px-8 py-4 bg-gold text-white text-sm font-semibold rounded-xl hover:shadow-[0_10px_20px_rgba(197,160,89,0.3)] transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0"
            >
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<script>
function previewImage(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('profile-preview').src = e.target.result;
    }
    reader.readAsDataURL(input.files[0]);
  }
}
</script>

<?php include '../components/footer.php'; ?>
</body>
</html>