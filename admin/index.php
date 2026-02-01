<?php
$page = $_GET['page'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - fashionstore</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

  <?php include 'sidebar.php'; ?>

  <div class="flex-1 p-6">
    <?php include 'header.php'; ?>
    
    <div class="mt-6 bg-white p-6 rounded shadow">
      <?php
        if ($page == 'products') include 'products.php';
        elseif ($page == 'orders') include 'orders.php';
        elseif ($page == 'users') include 'users.php';
        else if($page == 'addproduct') include 'admin-product-insert-form.php';
        else include 'dashboard.php';
      ?>
    </div>
  </div>

</body>
</html>

<?php
include("../configshoppingstore.php");
if (isset($_GET["id"])) {

    $user_id = (int)$_GET["id"];
    if ($_GET["action"] === "block") {
        $user = $conn->prepare("UPDATE user SET isBlock='1' WHERE id='" . $user_id."'");
        $res = $user->execute();
    } else {
        $user = $conn->prepare("UPDATE user SET isBlock='0' WHERE id='" . $user_id."'");
        $res = $user->execute();
    }

    header("Location: index.php?page=users");
}

?>