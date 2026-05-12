<?php
require 'configshoppingstore.php';
try {
    $email = 'Sultanjutt@gmail.com';
    $newPassword = password_hash('admin123', PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("UPDATE user SET password = ?, role = 'admin' WHERE email = ?");
    if ($stmt->execute([$newPassword, $email])) {
        echo "Password for $email has been reset to: admin123\n";
    } else {
        echo "Failed to update password.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
