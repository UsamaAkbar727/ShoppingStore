<?php
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="bg-white p-8 rounded shadow-md w-full max-w-sm">
    <h2 class="text-2xl font-bold mb-6 text-center">Admin Login</h2>

    <?php if (isset($_GET['error'])): ?>
      <p class="bg-red-100 text-red-700 p-2 rounded mb-4"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form action="validate.php" method="post">
      <div class="mb-4">
        <label class="block mb-1 text-sm">Username</label>
        <input type="text" name="username" class="w-full border p-2 rounded" required>
      </div>
      <div class="mb-4">
        <label class="block mb-1 text-sm">Password</label>
        <input type="password" name="password" class="w-full border p-2 rounded" required>
      </div>
      <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
        Login
      </button>
    </form>
  </div>

</body>
</html>
