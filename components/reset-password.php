<?php
include("../configshoppingstore.php");

// var_dump($_GET);
// echo "<br>";
// $email = $_GET["email"];
// $password =  $_GET["token"];


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $email = $_GET["email"];
    $password =  $_GET["token"];

    setcookie("email", $email, time() + 180, "/");
    if (!empty($email) && !empty($password)) {

        try {
            $user = $conn->prepare("SELECT  password FROM user WHERE email='" . $email . "'");
            $user->execute();
            $res = $user->fetchAll();

            if ($res) {
                $dbPass = $res[0]["password"];

                if ($dbPass !== $password) {
                    header("Location: /auth/login.php");
                }
            }
        } catch (\Throwable $th) {
            echo $th;
        }
    } else {
        $error = "Server error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Reset Your Password</h2>

        <div class="mb-1 -mt-5 p-3 bg-blue-100 text-blue-700 rounded invisible" id="message">
        </div>

        <form method="POST">
            <div class="mb-4">
                <label class="block mb-1 text-sm font-semibold text-gray-600">New Password</label>
                <input type="password" name="new_password" required class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-4">
                <label class="block mb-1 text-sm font-semibold text-gray-600">Confirm New Password</label>
                <input type="password" name="confirm_password" required class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md transition">Reset Password</button>
        </form>
    </div>

</body>

</html>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST["new_password"];
    $hashedPass = password_hash($new_password, PASSWORD_BCRYPT);

    $email = $_COOKIE["email"];

    $user = $conn->prepare("UPDATE user SET password='" . $hashedPass . "' WHERE email='" . $email . "'");
    $res = $user->execute();

    if ($res) {
        echo '<script>
        document.getElementById("message").classList.toggle("invisible");
        document.getElementById("message").innerHTML = "Password sexfully updated";
    </script>';

        echo '<script>
            setTimeout(() => {
                window.location = "/auth/login.php"
            }, 400);
        </script>';
    }
}

?>