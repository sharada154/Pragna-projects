<?php
session_start();
include '../holds/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];

// Handle single order delete
if (isset($_GET['delete_order'])) {
    $delete_id = (int)$_GET['delete_order'];

    // Delete order items first
    $stmt2 = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt2->bind_param("i", $delete_id);
    $stmt2->execute();

    // Delete order
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $uid);
    $stmt->execute();

    header("Location: order_history.php");
    exit();
}

// Handle clear all orders
if (isset($_POST['clear_orders'])) {
    // Delete order items first
    $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id IN (SELECT id FROM orders WHERE user_id = ?)");
    $stmt->bind_param("i", $uid);
    $stmt->execute();

    // Then delete orders
    $stmt2 = $conn->prepare("DELETE FROM orders WHERE user_id = ?");
    $stmt2->bind_param("i", $uid);
    $stmt2->execute();

    header("Location: order_history.php");
    exit();
}

// Fetch all orders
$stmt = $conn->prepare("SELECT id, total_amount, order_date, payment_method FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders</title>
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
    position: relative;
    border: 2px solid rgba(44, 62, 80, 0.9);
     background: url('../images/container.png') no-repeat center center fixed;
      background-size: cover;
}
h1 { text-align:center; color: rgba(44, 62, 80, 0.9) ; margin-bottom:20px; }
table { width:100%; border-collapse:collapse; margin-top:30px; }
th, td { padding:12px; text-align:center; border-bottom:1px solid #ccc; }
th { background:rgba(44, 62, 80, 0.9);; color:white; }
tr:hover { background:#f1f1f1; }
.btn { display:inline-block; padding:8px 16px; background:#28a745; color:white; text-decoration:none; border-radius:8px; }
.btn:hover { background:#218838; }
.btn-delete { background:#c0392b; }
.btn-delete:hover { background:#a93226; }
.clear-btn {
    display:inline-block;
    padding:10px 20px;
    background:#e67e22;
    border-radius:8px;
    color:white;
    text-decoration:none;
    border:none;
    cursor:pointer;
}
.clear-btn:hover { background:#d35400; }
.clear-container {
    position: absolute;
    top: 20px;
    right: 20px;
}
.continue { display:inline-block; padding:10px 20px; background:#007bff; margin-top:20px; border-radius:8px; color:white; text-decoration:none; }
.continue:hover { background:#0056b3; }
</style>
<script>
function confirmAction(message) {
    return confirm(message);
}
</script>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="../images/logo.png" alt="The BookHaven Logo" class="logo">
        <span class="site-title">THE BOOKHAVEN</span>
    </div>
</header>

<div class="container">
    <h1>My Orders</h1>

    <?php if(count($orders) > 0): ?>
        <div class="clear-container">
            <form method="POST" onsubmit="return confirmAction('Are you sure you want to clear all orders?');">
                <button type="submit" name="clear_orders" class="clear-btn">Clear All Orders</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total Amount ($)</th>
                    <th>Order Date</th>
                    <th>Payment Method</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $order): ?>
                <tr>
                    <td><?= $order['id']; ?></td>
                    <td><?= $order['total_amount']; ?></td>
                    <td><?= $order['order_date']; ?></td>
                    <td><?= $order['payment_method']; ?></td>
                    <td>
                        <a href="order_details.php?order_id=<?= $order['id']; ?>" class="btn">View Details</a>
                        <a href="order_history.php?delete_order=<?= $order['id']; ?>" 
                           class="btn btn-delete" 
                           onclick="return confirmAction('Are you sure you want to delete this order?');">
                           Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center; font-size:18px; margin-top:30px;">You have no orders yet.</p>
    <?php endif; ?>

    <div style="text-align:center;">
        <a href="/online_shopping_zone/home.php" class="continue">Continue Shopping</a>
    </div>
</div>
</body>
</html>
