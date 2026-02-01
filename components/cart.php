<script src="https://cdn.tailwindcss.com"></script>

<?php
include("./cart_product_card.php");
include("../configshoppingstore.php");
include("./header.php");

$user_id = (int)$_COOKIE["user_id"];
$res_arr = [];

try {
    $stmt = $conn->prepare("
        SELECT product.* 
        FROM cart 
        INNER JOIN product ON cart.product_id = product.id 
        WHERE cart.user_id = ".$user_id);
    $stmt->execute();
    $res_arr = $stmt->fetchAll();
    
} catch (\Throwable $th) {
    echo "<div class='text-red-500'>Error: " . $th->getMessage() . "</div>";
}
?>

<div class="max-w-screen-xl mx-auto p-4 min-h-96">
    <h2 class="text-3xl font-bold mb-6 text-center mt-4 text-gray-800">
        <span class="text-red-500"><i class="fas fa-shopping-cart"></i></span>
        <span class="text-blue-500">Your Cart</span>
    </h2>

    <?php if (empty($res_arr)): ?>
        <p class="text-center text-gray-500">Your cart is empty.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            foreach ($res_arr as $value) {
                print_cart_card_user(
                    "../admin/uploads/" . htmlspecialchars($value["file"]),
                    htmlspecialchars($value["category"]),
                    htmlspecialchars($value["productName"]),
                    htmlspecialchars($value["description"]),
                    htmlspecialchars($value["price"]),
                    htmlspecialchars($value["discountedPrice"]),
                    htmlspecialchars($value["stock"]),
                    (int)$value["id"]
                );
            }
            ?>
        </div>
        
    <?php endif; ?>
</div>

<?php

if (isset($_GET["action"], $_GET["id"]) && $_GET["action"] === "uncart") {
    $product_id = (int)$_GET["id"];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    echo "<script>window.location = 'cart.php'</script>";
}
?>

<?php include("./footer.php"); ?>