<?php
include("../configshoppingstore.php");
try {
    // Update existing Men products with subcategories
    $conn->prepare("UPDATE product SET subcategory='Jeans' WHERE productName LIKE '%jeans%' AND category='Men'")->execute();
    $conn->prepare("UPDATE product SET subcategory='Trousers' WHERE productName LIKE '%Trouser%' OR productName LIKE '%Pants%' AND category='Men'")->execute();
    $conn->prepare("UPDATE product SET subcategory='T-Shirts' WHERE productName LIKE '%T-shirt%' AND category='Men'")->execute();
    $conn->prepare("UPDATE product SET subcategory='Hoodies' WHERE productName LIKE '%Hoodie%' AND category='Men'")->execute();
    $conn->prepare("UPDATE product SET subcategory='Shirts' WHERE productName LIKE '%Shirt%' AND category='Men'")->execute();
    $conn->prepare("UPDATE product SET subcategory='Winter Wear' WHERE productName LIKE '%Winter%' AND category='Men'")->execute();
    $conn->prepare("UPDATE product SET subcategory='Trousers' WHERE productName LIKE '%Kids Trouser%'")->execute();
    // Default remaining Men
    $conn->prepare("UPDATE product SET subcategory='Clothing' WHERE category='Men' AND (subcategory IS NULL OR subcategory='')")->execute();

    // Update existing Women products
    $conn->prepare("UPDATE product SET subcategory='Abayas' WHERE productName LIKE '%Abaya%' AND category='Women'")->execute();
    $conn->prepare("UPDATE product SET subcategory='Hijabs' WHERE productName LIKE '%Hijab%' AND category='Women'")->execute();
    // Default remaining Women
    $conn->prepare("UPDATE product SET subcategory='Clothing' WHERE category='Women' AND (subcategory IS NULL OR subcategory='')")->execute();

    // Update Accessories with subcategories
    $conn->prepare("UPDATE product SET subcategory='Watches' WHERE productName LIKE '%Watch%' AND category='Accessories'")->execute();
    $conn->prepare("UPDATE product SET subcategory='Bags' WHERE productName LIKE '%Bag%' OR productName LIKE '%Handbag%' AND category='Accessories'")->execute();
    $conn->prepare("UPDATE product SET subcategory='Sunglasses' WHERE productName LIKE '%Sunglass%' AND category='Accessories'")->execute();
    $conn->prepare("UPDATE product SET subcategory='Perfumes' WHERE productName LIKE '%Perfume%' AND category='Accessories'")->execute();
    $conn->prepare("UPDATE product SET subcategory='Jewelry' WHERE productName LIKE '%Bracelet%' OR productName LIKE '%Jewel%' OR productName LIKE '%Ring%' AND category='Accessories'")->execute();
    $conn->prepare("UPDATE product SET subcategory='Belts' WHERE productName LIKE '%Belt%' AND category='Accessories'")->execute();
    // Default remaining Accessories
    $conn->prepare("UPDATE product SET subcategory='Other' WHERE category='Accessories' AND (subcategory IS NULL OR subcategory='')")->execute();

    echo "All subcategories updated!\n";

    // Print result
    $stmt = $conn->query("SELECT productName, category, subcategory FROM product ORDER BY category");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
