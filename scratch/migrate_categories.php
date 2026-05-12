<?php
include("../configshoppingstore.php");
try {
    // Update products based on keywords in their names or existing categories
    
    // Women items
    $conn->prepare("UPDATE `product` SET `category` = 'Women' WHERE `productName` LIKE '%Abaya%' OR `productName` LIKE '%Hijab%' OR `productName` LIKE '%Women%'")->execute();
    
    // Men items
    $conn->prepare("UPDATE `product` SET `category` = 'Men' WHERE `productName` LIKE '%Men%' OR `productName` LIKE '%jeans%' OR `productName` LIKE '%Trouser%' OR `category` = 'clothing'")->execute();
    
    // Anything else to Objects or Men as default
    $conn->prepare("UPDATE `product` SET `category` = 'Objects' WHERE `category` NOT IN ('Men', 'Women', 'Objects')")->execute();
    
    echo "Categories updated successfully!\n";
    
    // Check results
    $stmt = $conn->query("SELECT productName, category FROM `product` ");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
