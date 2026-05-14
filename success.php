<?php
require_once("auth/session.php");
include("./configshoppingstore.php");

// Decrement stock if a product id is provided
if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    try {
        $update = $conn->prepare("UPDATE `product` SET `stock` = GREATEST(stock - 1, 0) WHERE id = ?");
        $update->execute([$product_id]);
    } catch (\Throwable $th) {
        error_log('Stock update error: ' . $th->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #00c6ff, #ced9e7ff);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .success-container {
            background: rgba(165, 145, 145, 0.15);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 50px 40px;
            text-align: center;
            width: 90%;
            max-width: 420px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            color: #fff;
            animation: fadeIn 0.8s ease-in-out;
        }

        .checkmark-svg {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
        }

        .checkmark-circle {
            stroke: #4caf50;
            stroke-width: 4;
            fill: none;
            animation: circleStroke 0.6s ease forwards;
        }

        .checkmark-path {
            stroke: #4caf50;
            stroke-width: 4;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: checkStroke 0.4s ease forwards 0.6s;
        }

        @keyframes circleStroke {
            0% {
                stroke-dasharray: 0 314;
            }

            100% {
                stroke-dasharray: 314 0;
            }
        }

        @keyframes checkStroke {
            to {
                stroke-dashoffset: 0;
            }
        }

        h1 {
            font-size: 30px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        p {
            font-size: 16px;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .countdown {
            font-size: 14px;
            margin-bottom: 20px;
            opacity: 0.8;
        }

        a {
            display: inline-block;
            padding: 12px 30px;
            background: #4caf50;
            border-radius: 30px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
            transition: 0.3s;
        }

        a:hover {
            background: #45a049;
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.5);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

</head>

<body>

    <div class="success-container">
        <svg class="checkmark-svg" viewBox="0 0 52 52">
            <circle class="checkmark-circle" cx="26" cy="26" r="24" />
            <path class="checkmark-path" d="M16 26l8 8 14-14" />
        </svg>

        <h1> Payment Successful!</h1>
        <p>Thank you! Your payment has been processed securely and confirmed.</p>
        <div class="countdown">You will be redirected in <span id="countdown">5</span> seconds...</div>
        <a href="index.php">🏠 Return to Homepage</a>
    </div>

    <script>
        let count = 5;
        const countdownElement = document.getElementById('countdown');
        const timer = setInterval(() => {
            count--;
            countdownElement.textContent = count;
            if (count <= 0) {
                clearInterval(timer);
                window.location.href = 'index.php';
            }
        }, 1000);
    </script>

</body>

</html>