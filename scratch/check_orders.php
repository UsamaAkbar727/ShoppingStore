<?php
include("../configshoppingstore.php");
try {
    $stmt = $conn->prepare("DESCRIBE orders");
    $stmt->execute();
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($cols);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
