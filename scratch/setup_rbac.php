<?php
require 'configshoppingstore.php';
try {
    // Add role column if it doesn't exist
    $conn->exec("ALTER TABLE `user` ADD COLUMN IF NOT EXISTS `role` VARCHAR(20) DEFAULT 'customer' AFTER `password` ");
    
    // Check structure
    $stmt = $conn->query("DESCRIBE user");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    
    // Set a specific user as admin (e.g., the first one or a specific email if I knew it)
    // For now, I'll set all current users to customer, but I need one admin.
    // Let's see current users.
    $stmt = $conn->query("SELECT id, name, email, role FROM user");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($users);
    
    if (count($users) > 0) {
        $first_id = $users[0]['id'];
        $conn->exec("UPDATE user SET role = 'admin' WHERE id = $first_id");
        echo "User ID $first_id set as Admin.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
