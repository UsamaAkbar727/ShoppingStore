<?php
include("../configshoppingstore.php");
try {
    $stmt = $conn->query("DESCRIBE product");
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $col) {
        if ($col['Field'] == 'category') {
            echo "Type: " . $col['Type'] . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
