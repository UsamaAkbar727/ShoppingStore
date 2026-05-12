<?php
include("../configshoppingstore.php");
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM `product` ");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    echo "Total products: " . $count . "\n";
    
    $stmt = $conn->prepare("SELECT * FROM `product` LIMIT 5");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($rows);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
