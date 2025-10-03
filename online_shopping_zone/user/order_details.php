<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../holds/database.php';
$uid = $_SESSION['user_id'];

// Get order_id from URL
if (!isset($_GET['order_id'])) {
    header("Location: order_history.php");
    exit();
}
$order_id = $_GET['order_id'];

// Fetch order info including payment method
$orderCheck = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$orderCheck->bind_param("ii", $order_id, $uid);
$orderCheck->execute();
$orderResult = $orderCheck->get_result();

if ($orderResult->num_rows == 0) {
    echo "Invalid Order ID!";
    exit();
}
$orderData = $orderResult->fetch_assoc();
$payment_method = $orderData['payment_method'];

// Fetch order items
$itemQuery = $conn->prepare("SELECT p.name, oi.quantity, oi.price 
                             FROM order_items oi 
                             JOIN products p ON oi.product_id = p.id 
                             WHERE oi.order_id = ?");
$itemQuery->bind_param("i", $order_id);
$itemQuery->execute();
$items = $itemQuery->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin:0;
            padding:0;
            background: url("../images/home.png") no-repeat center center fixed;
            background-size: cover;
        }
        header {
            background: rgba(44, 62, 80, 0.9);
            color:white;
            padding:15px 0;
            display:flex;
            justify-content:center;
            align-items:center;
        }
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        .logo {
            height: 50px;
        }
        .site-title {
            font-size: 28px;
            font-weight: bold;
            color: white;
            letter-spacing: 2px;
        }
        .container {
            width: 85%;
            margin: 40px auto;
            background: rgba(255,255,255,0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            border: 2px solid rgba(44, 62, 80, 0.9); 
             background: url('../images/container.png') no-repeat center center fixed;
      background-size: cover;
        }
        h2 { text-align:center; color: rgba(44, 62, 80, 0.9); margin-bottom:20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 12px; text-align: center; }
        th { background: rgba(44, 62, 80, 0.9); color: white; }
        tr:hover { background: #f1f1f1; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: rgba(44, 62, 80, 0.9);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
        }
        .btn:hover { background: #0056b3; }
        .btn-container { text-align: center; }
    </style>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="../images/logo.png" alt="The BookHaven Logo" class="logo">
        <span class="site-title">THE BOOKHAVEN</span>
    </div>
</header>

<div class="container">
    <h2>Order Details - Order #<?= $order_id ?></h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
            <th>Payment Method</th>
        </tr>
        <?php
        $grandTotal = 0;
        while ($row = $items->fetch_assoc()) {
            $total = $row['quantity'] * $row['price'];
            $grandTotal += $total;
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>\${$row['price']}</td>
                    <td>\${$total}</td>
                    <td>{$payment_method}</td>
                  </tr>";
        }
        ?>
        <tr>
            <th colspan="3">Grand Total</th>
            <th colspan="2">$<?= $grandTotal ?></th>
        </tr>
    </table>
    <div class="btn-container">
        <a href="order_history.php" class="btn">Back to Orders</a>
    </div>
</div>
</body>
</html>
