<?php
include("../configshoppingstore.php");
include '../components/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_COOKIE['user_id'])) {
    header("Location: /auth/login.php");
    exit();
}

$user_id = $_COOKIE['user_id'];
$userData = [];

$orders = [];

try {
    $stmt = $conn->prepare("SELECT fullname, email, file FROM user WHERE id = ?");
    $stmt->execute([$user_id]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    $order_stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
    $order_stmt->execute([$user_id]);
    $orders = $order_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (\Throwable $th) {
    $error = "Something went wrong.";
}


$page_title = "Account Overview";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Account<?php echo $page_title; ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<main class="py-10">
  <div class="max-w-4xl mx-auto bg-white border-4 border-pink-100 rounded-2xl shadow-xl p-8 sm:p-10">
    <div class="flex flex-col sm:flex-row items-center gap-8 sm:gap-12">
      <!-- Profile Image -->
      <div class="shrink-0 mx-auto sm:mx-0">
        <img 
          src="<?php echo htmlspecialchars($userData['file'] ?? 'https://via.placeholder.com/180'); ?>" 
          alt="Profile" 
          class="rounded-full w-36 h-36 sm:w-40 sm:h-40 object-cover border-4 border-blue-200 shadow-lg"
        >
      </div>

      <div class="flex-1 w-full text-center sm:text-left">
        <div class="space-y-2 mb-6">
          <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">
            <?php echo htmlspecialchars($userData['fullname'] ?? ''); ?>
          </h2>
          <p class="text-gray-600 text-base sm:text-lg">
            <?php echo htmlspecialchars($userData['email'] ?? ''); ?>
          </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
          <a 
            href="edit_profile.php" 
            class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-base font-medium transition flex items-center justify-center gap-2"
          >
            <span>✏️</span>
            <span>Edit Profile</span>
          </a>
          <a 
            href="change_password.php" 
            class="w-full sm:w-auto bg-gray-700 hover:bg-gray-800 text-white px-6 py-2.5 rounded-lg text-base font-medium transition flex items-center justify-center gap-2"
          >
            <span>🔐</span>
            <span>Change Password</span>
          </a>
          <a 
            href="logout.php" 
            class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg text-base font-medium transition flex items-center justify-center gap-2"
          >
            <span>🚪</span>
            <span>Logout</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</main>

<section class="max-w-4xl mx-auto mt-12 bg-white rounded-2xl shadow-xl p-10 mb-10">
  <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">🛍️ Your Orders</h2>

  <?php if (!empty($orders)): ?>
    <ul class="space-y-6">
      <?php foreach ($orders as $order): ?>
        <li class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition duration-300">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700 text-base">
            <div>
              <p><span class="font-semibold">📦 Product:</span> <?= htmlspecialchars($order['product_name']) ?></p>
              <p><span class="font-semibold">🔢 Quantity:</span> <?= (int)$order['quantity'] ?></p>
            </div>
            <div>
              <p><span class="font-semibold">💰 Total Price:</span> $<?= number_format((float)$order['total_price'], 2) ?></p>
              <p><span class="font-semibold">📅 Order Date:</span> <?= htmlspecialchars($order['order_date']) ?></p>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p class="text-gray-500 text-center text-lg">You have not placed any orders yet.</p>
  <?php endif; ?>
</section>


<?php include '../components/footer.php'; ?>
</body>
</html>
