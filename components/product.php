<script src="https://cdn.tailwindcss.com"></script>

<?php
include("./product_card_user.php");
include("../configshoppingstore.php");
include("./header.php");

$res_arr = [];
try {
    $data = $conn->prepare("SELECT * FROM `product`");
    $data->execute();
    $res = $data->fetchAll();
    if ($res) {
        $res_arr = $res;
    }
} catch (\Throwable $th) {
    echo "<div class='text-red-500'>Error: " . $th->getMessage() . "</div>";
}
?>

<div class="max-w-screen-xl mx-auto p-4">
    <h2 class="text-3xl font-bold mb-6 text-center mt-4 text-gray-800">
        <span class="text-red-500"><i class="fas fa-shopping-cart"></i></span>
        <span class="text-blue-500">Manage Products</span>
    </h2>


    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php
        if (isset($_COOKIE["user_id"])) {
            $user_id = (int)$_COOKIE["user_id"];


            foreach ($res_arr as $value) {
                $cartres = $conn->prepare("SELECT * FROM `cart` WHERE product_id=" . $value["id"] . " and user_id=" . $user_id);
                $cartres->execute();
                $iscarted = $cartres->fetchAll();

                print_card_user(
                    "../admin/uploads/" . htmlspecialchars($value["file"]),
                    htmlspecialchars($value["category"]),
                    htmlspecialchars($value["productName"]),
                    htmlspecialchars($value["description"]),
                    htmlspecialchars($value["price"]),
                    htmlspecialchars($value["discountedPrice"]),
                    htmlspecialchars($value["stock"]),
                    (int)$value["id"],
                    !empty($iscarted)
                );
            }
        } else {
            foreach ($res_arr as $value) {

                print_card_user(
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
        }
        ?>
    </div>
</div>

<?php
if (isset($_GET["id"])) {
    $product_id = (int)$_GET["id"];

    if ($_GET["action"] === "cart" && $user_id > 0) {
        $stmt = $conn->prepare("INSERT INTO `cart`(`id`, `user_id`, `product_id`) VALUES (null, ?, ?)");
        $stmt->execute([$user_id, $product_id]);
        echo "<script>window.location = 'product.php'</script>";
    }
}
include("./footer.php");
?>