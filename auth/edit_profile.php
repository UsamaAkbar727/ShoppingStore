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
include '../components/header.php';
?>

<main class="min-h-[calc(100vh-200px)] py-20 bg-[#fcfcfc]">
  <div class="max-w-2xl mx-auto px-6">
    <!-- Breadcrumbs -->
    <nav class="flex mb-8 text-xs uppercase tracking-widest text-gray-400" aria-label="Breadcrumb">
      <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
          <a href="../index.php" class="hover:text-gold transition-colors">Home</a>
        </li>
        <li>
          <div class="flex items-center">
            <i class="fas fa-chevron-right text-[8px] mx-2"></i>
            <a href="user-account.php" class="hover:text-gold transition-colors">Account</a>
          </div>
        </li>
        <li aria-current="page">
          <div class="flex items-center">
            <i class="fas fa-chevron-right text-[8px] mx-2"></i>
            <span class="text-luxury font-semibold">Edit Profile</span>
          </div>
        </li>
      </ol>
    </nav>

    <div class="bg-white border border-gray-100 shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-2xl overflow-hidden">
      <div class="p-8 md:p-12">
        <div class="text-center mb-10">
          <h1 class="font-serif text-3xl md:text-4xl mb-2 text-luxury">Profile Settings</h1>
          <p class="text-gray-500 font-light tracking-wide">Update your personal information and profile picture</p>
        </div>

        <?php if (isset($error)): ?>
          <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-8 flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <p class="text-sm font-medium"><?php echo htmlspecialchars($error); ?></p>
          </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-8">
          <!-- Profile Image Section -->
          <div class="flex flex-col items-center justify-center">
            <div class="relative group cursor-pointer">
              <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg ring-1 ring-gray-100 group-hover:ring-gold transition-all duration-300">
                <img
                  id="profile-preview"
                  src="<?php echo htmlspecialchars($userData['file'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($userData['fullname'] ?? 'User') . '&background=1a1a1a&color=fff&size=128'); ?>"
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
                  class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-transparent rounded-xl focus:bg-white focus:border-gold focus:outline-none transition-all duration-300 text-sm"
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
                  class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-transparent rounded-xl focus:bg-white focus:border-gold focus:outline-none transition-all duration-300 text-sm"
                >
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-50">
            <a href="user-account.php" class="flex-1 px-8 py-4 border border-gray-200 text-luxury text-sm font-semibold rounded-xl text-center hover:bg-gray-50 transition-colors duration-300">
              Cancel
            </a>
            <button
              type="submit"
              class="flex-1 px-8 py-4 bg-luxury text-white text-sm font-semibold rounded-xl hover:bg-black shadow-lg shadow-black/10 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0"
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