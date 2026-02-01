<?php
include("./product-card.php");
include("../configshoppingstore.php");

$res_arr = [];
try {
    $data = $conn->prepare("SELECT * FROM `product`");
    $data->execute();
    $res = $data->fetchAll();
    if ($res) {
        $res_arr = $res;
    }
} catch (\Throwable $th) {
    echo $th;
}
?>

<h2 class="text-xl font-bold mb-4">Manage Products</h2>
<div style="display:grid; grid-template-columns: 1fr 1fr 1fr;">
    <?php
    foreach ($res_arr as $value) {
        print_card("uploads/".$value["file"],$value["category"],$value["productName"],$value["description"],$value["price"],$value["discountedPrice"],$value["stock"],$value["id"]);
    }
    ?>
</div>