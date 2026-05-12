<?php
require 'configshoppingstore.php';
try {
    $stmt = $conn->query("SELECT COUNT(*) FROM product");
    echo "Product Count: " . $stmt->fetchColumn() . "\n";
    
    $stmt = $conn->query("SELECT * FROM product LIMIT 1");
    $p = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($p) {
        print_r($p);
    } else {
        echo "No products found.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
