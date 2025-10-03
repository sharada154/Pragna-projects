<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../holds/database.php';
$uid = $_SESSION['user_id'];

// Fetch cart items
$cartQuery = $conn->prepare("SELECT c.product_id, c.quantity, p.price 
                             FROM cart c 
                             JOIN products p ON c.product_id = p.id 
                             WHERE c.user_id = ?");
$cartQuery->bind_param("i", $uid);
$cartQuery->execute();
$result = $cartQuery->get_result();

$total = 0;
$cartItemsArray = [];
while ($row = $result->fetch_assoc()) {
    $total += $row['price'] * $row['quantity'];
    $cartItemsArray[] = $row;
}

// If Pay Now is clicked
if (isset($_POST['payment_method'])) {
    $_SESSION['total_amount'] = $total;
    $_SESSION['payment_method'] = $_POST['payment_method'];

    // Insert into orders
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $uid, $total, $_SESSION['payment_method']);
    $stmt->execute();
    $_SESSION['order_id'] = $stmt->insert_id;

    // Insert order_items
    foreach ($cartItemsArray as $item) {
        $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmtItem->bind_param("iiid", $_SESSION['order_id'], $item['product_id'], $item['quantity'], $item['price']);
        $stmtItem->execute();
    }

    // Clear cart
    $deleteCart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $deleteCart->bind_param("i", $uid);
    $deleteCart->execute();

    header("Location: order_success.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            background: url('../images/home.png') no-repeat center center fixed; 
            background-size: cover; 
        }

        /* Header with centered logo */
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

        /* Checkout box */
        .checkout-box {
            margin: 50px auto;
            padding: 40px;
            background: url('../images/container.png') no-repeat center center fixed;
      background-size: cover;
            border-radius: 15px;
            width: 50%;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
             border: 2px solid rgba(44, 62, 80, 0.9);
        }

        h2 { color:  rgba(44, 62, 80, 0.9); }

        .amount { font-size: 22px; color:  rgba(44, 62, 80, 0.9); margin: 20px 0; }

        .btn { 
            padding: 12px 25px; 
            border: none; 
            border-radius: 8px; 
            font-size: 16px; 
            cursor: pointer; 
            margin: 10px; 
            transition: 0.3s; 
            color: white; 
        }

        .upi { background: #28a745; }
        .card { background: #007bff; }
        .btn:hover { opacity: 0.8; }

        /* Responsive */
        @media (max-width:768px) {
            .checkout-box { width: 90%; padding:30px; }
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

<div class="checkout-box">
    <h2>Checkout</h2>
    <p class="amount">Total Payable Amount: $<?= number_format($total,2); ?></p>

    <form method="POST">
        <button type="submit" name="payment_method" value="UPI" class="btn upi">Pay via UPI</button>
        <button type="submit" name="payment_method" value="Card" class="btn card">Pay via Card</button>
    </form>
</div>

</body>
</html>
