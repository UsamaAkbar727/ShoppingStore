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
  <title><?php echo $page_title; ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<main class="py-10">
  <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-semibold text-center mb-6">Change Password</h2>

    <?php if (!empty($error)): ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <form method="POST">

    <div class="mb-4">
        <label class="block text-gray-700 mb-2">Current Password</label>
        <input
          type="password"
          name="current_password"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 mb-2">New Password</label>
        <input
          type="password"
          name="new_password"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
      </div>

      <!-- Confirm New Password -->
      <div class="mb-6">
        <label class="block text-gray-700 mb-2">Confirm New Password</label>
        <input
          type="password"
          name="confirm_password"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
      </div>

      <button
        type="submit"
        class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition"
      >
        Change Password
      </button>
    </form>
  </div>
</main>

</body>
</html>