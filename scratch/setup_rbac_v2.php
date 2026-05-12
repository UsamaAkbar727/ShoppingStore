<?php
require 'configshoppingstore.php';
try {
    $stmt = $conn->query("SELECT id, fullname, email, role FROM user");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($users);
    
    // Set the first user as admin if none exist
    $hasAdmin = false;
    foreach($users as $u) {
        if ($u['role'] === 'admin') $hasAdmin = true;
    }
    
    if (!$hasAdmin && count($users) > 0) {
        $first_id = $users[0]['id'];
        $conn->exec("UPDATE user SET role = 'admin' WHERE id = $first_id");
        echo "User ID $first_id ($users[0][email]) set as Admin.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
