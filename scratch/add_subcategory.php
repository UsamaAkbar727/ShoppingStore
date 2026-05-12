<?php
include("../configshoppingstore.php");
try {
    $conn->exec("ALTER TABLE product ADD COLUMN subcategory VARCHAR(100) DEFAULT NULL AFTER category");
    echo "Subcategory column added successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
