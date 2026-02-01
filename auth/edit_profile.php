<?php
include("../configshoppingstore.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_COOKIE['user_id'])) {
    header("Location: /auth/login.php");
    exit();
}

$user_id = $_COOKIE['user_id'];
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
        $file = '/uploads/' . $newName; 
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
  <title><?php echo $page_title; ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<main class="py-10">
  <div class="max-w-xl mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-semibold text-center mb-6">Edit Profile</h2>

    <?php if (isset($error)): ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <!-- Full Name -->
      <div class="mb-4">
        <label class="block text-gray-700 mb-2">Full Name</label>
        <input
          type="text"
          name="fullname"
          value="<?php echo htmlspecialchars($userData['fullname'] ?? ''); ?>"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
      </div>

      <!-- Email -->
      <div class="mb-4">
        <label class="block text-gray-700 mb-2">Email</label>
        <input
          type="email"
          name="email"
          value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
      </div>

      <!-- Current Image -->
      <div class="mb-4 text-center">
        <img
          src="<?php echo htmlspecialchars($userData['file'] ?? 'https://via.placeholder.com/120'); ?>"
          alt="Profile"
          class="mx-auto rounded-full w-24 h-24 object-cover mb-2"
        >
        <p class="text-gray-500 text-sm">Current Profile Picture</p>
      </div>

      <!-- New Image -->
      <div class="mb-6">
        <label class="block text-gray-700 mb-2">Change Profile Picture</label>
        <input
          type="file"
          name="profile_image"
          accept="image/*"
          class="w-full"
        >
      </div>

      <!-- Submit -->
      <button
        type="submit"
        class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition"
      >
        Save Changes
      </button>
    </form>
  </div>
</main>

</body>
</html>