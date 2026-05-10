<?php
require_once("../auth/session.php");
include("../configshoppingstore.php");

// var_dump($_GET);
// echo "<br>";
// $email = $_GET["email"];
// $password =  $_GET["token"];


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $email = $_GET["email"] ?? null;
    $token = $_GET["token"] ?? null;

    if ($email && $token) {
        setcookie("email", $email, time() + 180, "/");
        try {
            $stmt = $conn->prepare("SELECT password FROM user WHERE email = ?");
            $stmt->execute([$email]);
            $res = $stmt->fetch();

            if ($res) {
                $dbPass = $res["password"];

                if ($dbPass !== $token) {
                    header("Location: " . $base_url . "auth/login.php");
                    exit();
                }
            } else {
                header("Location: " . $base_url . "auth/login.php");
                exit();
            }
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            $error = "Server error";
        }
    } else {
        // If parameters are missing, we might want to redirect or show an error
        // For now, let's just ensure we don't process further if they are missing
        if (!isset($_GET['email']) || !isset($_GET['token'])) {
            // Optional: header("Location: " . $base_url . "auth/login.php");
            // exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/48/shopping-bag.png">
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
                <input type="password" name="new_password" required
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-4">
                <label class="block mb-1 text-sm font-semibold text-gray-600">Confirm New Password</label>
                <input type="password" name="confirm_password" required
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md transition">Reset
                Password</button>
        </form>
    </div>

</body>

</html>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST["new_password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';

    if ($new_password !== $confirm_password) {
        echo '<script>
            document.getElementById("message").classList.remove("invisible");
            document.getElementById("message").innerHTML = "Passwords do not match";
            document.getElementById("message").classList.replace("bg-blue-100", "bg-red-100");
            document.getElementById("message").classList.replace("text-blue-700", "text-red-700");
        </script>';
    } else {
        $hashedPass = password_hash($new_password, PASSWORD_BCRYPT);
        $email = $_COOKIE["email"] ?? null;

        if ($email) {
            $user = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
            $res = $user->execute([$hashedPass, $email]);

            if ($res) {
                echo '<script>
                document.getElementById("message").classList.remove("invisible");
                document.getElementById("message").innerHTML = "Password successfully updated";
            </script>';

                echo '<script>
                    setTimeout(() => {
                        window.location = "' . $base_url . 'auth/login.php";
                    }, 2000);
                </script>';
            }
        } else {
            echo '<script>
                document.getElementById("message").classList.remove("invisible");
                document.getElementById("message").innerHTML = "Session expired. Please try again.";
                document.getElementById("message").classList.replace("bg-blue-100", "bg-red-100");
                document.getElementById("message").classList.replace("text-blue-700", "text-red-700");
            </script>';
        }
    }
}

?>