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
include '../components/header.php';
?>

<main class="py-10">
  <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-semibold text-center mb-6 text-luxury">Change Password</h2>

    <?php if (!empty($error)): ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">

      <div>
        <label class="block text-gray-700 mb-2">Current Password</label>
        <input
          type="password"
          name="current_password"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gold"
        >
      </div>

      <div>
        <label class="block text-gray-700 mb-2">New Password</label>
        <input
          type="password"
          name="new_password"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gold"
        >
      </div>

      <!-- Confirm New Password -->
      <div class="mb-6">
        <label class="block text-gray-700 mb-2">Confirm New Password</label>
        <input
          type="password"
          name="confirm_password"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gold"
        >
      </div>

      <div class="flex gap-4 pt-2">
          <a href="user-account.php" class="flex-1 bg-gray-200 text-gray-800 text-center py-2 rounded transition hover:bg-gray-300">Cancel</a>
          <button
            type="submit"
            class="flex-1 bg-luxury hover:bg-black text-white py-2 px-4 rounded transition"
          >
            Update
          </button>
      </div>
    </form>
  </div>
</main>

<?php include '../components/footer.php'; ?>
</body>
</html>