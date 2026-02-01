<?php
include("../components/header.php");
?>


<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: "#3b82f6",
                    secondary: "#1e40af"
                }
            }
        }
    }
</script>

<section class="py-16 bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-primary py-5 px-6">
                <h2 class="text-white text-2xl font-semibold">Forgot Password</h2>
            </div>

            <div class="p-6">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded -mb-8 invisible" id="error">

                </div>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 invisible" id="success">

                </div>

                <form method="POST">
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <button type="submit"
                        class="w-full bg-primary hover:bg-secondary text-white py-2 px-4 rounded font-medium transition duration-300">
                        Send Reset Link
                    </button>

                    <div class="mt-4 text-center">
                        <p class="text-gray-600">Remembered your password?
                            <a href="login.php" class="text-primary hover:underline">Login</a>
                        </p>
                        <p class="text-gray-600 mt-2">Don’t have an account?
                            <a href="signup.php" class="text-primary hover:underline">Sign up</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
include("../components/footer.php");
include("../mailer.php");
include("../configshoppingstore.php");
if ($_POST && isset($_POST["email"])) {
    $email = $_POST["email"];
    $res = $conn->prepare("SELECT `fullname`, `password`, `isBlock` FROM `user` WHERE email='" . $email . "'");
    $res->execute();
    $data = $res->fetchAll();

    if (!empty($data)) {
        $fullname = $data[0]["fullname"];
        $token = $data[0]["password"];
        $isblock = $data[0]["isBlock"];

        $subject = "FashionStore - Forget Password.";

        $body = '<a href="http://localhost:3000/components/reset-password.php?email=' . $email . '&token=' . $token . '">Reset Password</a>';


        if ($isblock) {
            echo '<script>
        document.getElementById("error").classList.toggle("invisible");
        document.getElementById("error").innerHTML = "You are blocked.";
    </script>';
        } else {
            if (sendEmail($email, $fullname, $subject, $body)) {
                echo '<script>
        document.getElementById("success").classList.toggle("invisible");
        document.getElementById("success").innerHTML = "Email sent successfully.";
        </script>';

                echo '<script>
            setTimeout(() => {
                window.location = "/auth/login.php"
            }, 400);
        </script>';
            }
        }
    } else {
        echo '<script>
        document.getElementById("error").classList.toggle("invisible");
        document.getElementById("error").innerHTML = "Invalid email address.";
    </script>';
    }
}

?>