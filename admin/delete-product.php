<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
    include("../configshoppingstore.php");
    include("./product-card.php");
    include 'sidebar.php';

    if (isset($_GET["id"])) {
        $productId = $_GET['ID'];
        $del = $conn->prepare("DELETE FROM `product` WHERE `id` = $productId");
        $res = $del->execute();

        if ($res) {
            header("Location: index.php?page=products");
            echo "Product deleted";
        }
    }
    ?>
</head>

<body>

</body>

</html>