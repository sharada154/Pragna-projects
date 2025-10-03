<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$total_amount = isset($_SESSION['total_amount']) ? $_SESSION['total_amount'] : "0.00";
$order_id = isset($_SESSION['order_id']) ? $_SESSION['order_id'] : "ORD" . rand(1000,9999);
$payment_method = isset($_SESSION['payment_method']) ? $_SESSION['payment_method'] : 'UPI';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin:0; 
            padding:0; 
            background: url('../images/home.png') no-repeat center center fixed; 
            background-size: cover; 
        }

        /* Header */
        header {
            background: rgba(44, 62, 80, 0.9);
            color:white;
            padding:15px 0;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .logo-container {
            display:flex;
            align-items:center;
            gap:10px;
        }

        .logo {
            height:50px;
        }

        .site-title {
            font-size:26px;
            color:white;
            font-weight:bold;
            letter-spacing:1px;
        }

        /* Success box */
        .success-box { 
             background: url('../images/container.png') no-repeat center center fixed;
      background-size: cover;
            margin: 100px auto; 
            padding: 40px;  
            border-radius: 15px; 
            width: 50%; 
            text-align: center; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
             border: 2px solid rgba(44, 62, 80, 0.9);
        }

        h1 { color: #28a745; }
        p { font-size: 18px; color: #555; }

        .btn { 
            display: inline-block; 
            margin: 10px; 
            padding: 12px 25px; 
            background: #007bff; 
            color: white; 
            text-decoration: none; 
            border-radius: 8px; 
            transition: 0.3s; 
        }

        .btn:hover { background: #0056b3; }

        /* Responsive */
        @media (max-width:768px) {
            .success-box { width: 90%; padding:30px; }
            .site-title { font-size:22px; }
            .logo { height:40px; }
        }
    </style>
</head>
<body>

<header>
    <div class="logo-container">
        <img src="../images/logo.png" alt="The BookHaven Logo" class="logo">
        <span class="site-title">THE BOOKHAVEN</span>
    </div>
</header>

<div class="success-box">
    <h1>🎉 Order Successful!</h1>
    <p>Thank you for shopping with <strong>THE BOOKHAVEN</strong>.</p>
    <p><strong>Order ID:</strong> <?= $order_id ?></p>
    <p><strong>Total Paid:</strong> $<?= $total_amount ?></p>
    <p><strong>Payment Method:</strong> <?= $payment_method ?></p>

    <a href="/online_shopping_zone/home.php" class="btn">Continue Shopping</a>
    <a href="order_history.php" class="btn">View My Orders</a>
</div>

</body>
</html>
