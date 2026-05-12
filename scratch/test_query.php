<?php
include("../configshoppingstore.php");
try {
    $query = "SELECT * FROM `product` ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Success!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
