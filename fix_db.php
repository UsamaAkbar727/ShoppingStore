<?php
include("configshoppingstore.php");

try {
    // Check if quantity column exists in cart
    $check = $conn->query("SHOW COLUMNS FROM `cart` LIKE 'quantity'");
    $exists = $check->fetch();

    if (!$exists) {
        $conn->exec("ALTER TABLE `cart` ADD COLUMN `quantity` INT DEFAULT 1 AFTER `product_id` ");
        echo "Successfully added 'quantity' column to 'cart' table.<br>";
    } else {
        echo "'quantity' column already exists in 'cart' table.<br>";
    }

    echo "<br><a href='index.php'>Go back to Store</a>";
} catch (\Throwable $th) {
    echo "Error fixing database: " . $th->getMessage();
}
