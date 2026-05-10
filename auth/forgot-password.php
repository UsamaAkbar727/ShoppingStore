<?php
require_once("session.php");
include("../mailer.php");
include("../configshoppingstore.php");

$msg_type = '';
$msg_text = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $stmt  = $conn->prepare("SELECT fullname, password, isBlock FROM user WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $data  = $stmt->fetch();

    if ($data) {
        if ($data['isBlock']) {
            $msg_type = 'error';
            $msg_text = 'Your account has been blocked. Please contact support.';
        } else {
            $token   = $data['password'];
            $subject = 'FashionStore - Reset Your Password';
            $body    = '<a href="http://' . $_SERVER['HTTP_HOST'] . $base_url . 'components/reset-password.php?email=' . urlencode($email) . '&token=' . urlencode($token) . '">Click here to reset your password</a>';

            if (sendEmail($email, $data['fullname'], $subject, $body)) {
                $msg_type = 'success';
                $msg_text = 'Reset link sent! Please check your email.';
            } else {
                $msg_type = 'error';
                $msg_text = 'Failed to send email. Please try again.';
            }
        }
    } else {
        $msg_type = 'error';
        $msg_text = 'No account found with that email address.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - FashionStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: "#3b82f6", secondary: "#1e40af" } } }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<section class="py-16 w-full">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-primary py-5 px-6">
                <h1 class="text-white text-2xl font-semibold">Forgot Password</h1>
            </div>

            <div class="p-6">
                <?php if ($msg_type === 'error'): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($msg_text); ?>
                    </div>
                <?php elseif ($msg_type === 'success'): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($msg_text); ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <button type="submit"
                        class="w-full bg-primary hover:bg-secondary text-white py-2 px-4 rounded font-medium transition duration-300">
                        <i class="fas fa-paper-plane mr-2"></i>Send Reset Link
                    </button>

                    <div class="mt-4 text-center">
                        <p class="text-gray-600">Remembered your password?
                            <a href="login.php" class="text-primary hover:underline font-medium">Login</a>
                        </p>
                        <p class="text-gray-600 mt-2">Don't have an account?
                            <a href="signup.php" class="text-primary hover:underline font-medium">Sign up</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

</body>
</html>