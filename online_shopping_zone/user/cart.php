<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../holds/database.php';
$uid = $_SESSION['user_id'];

// Handle add/update/remove cart items
if (isset($_POST['add_to_cart'])) {
    $pid = $_POST['product_id'];
    $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    $check = $conn->prepare("SELECT * FROM cart WHERE user_id=? AND product_id=?");
    $check->bind_param("ii", $uid, $pid);
    $check->execute();
    $result = $check->get_result();
    $existing = $result->fetch_assoc();

    if ($existing) {
        $newQty = $existing['quantity'] + $qty;
        $update = $conn->prepare("UPDATE cart SET quantity=? WHERE user_id=? AND product_id=?");
        $update->bind_param("iii", $newQty, $uid, $pid);
        $update->execute();
    } else {
        $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $uid, $pid, $qty);
        $insert->execute();
    }
}

if (isset($_POST['remove_item'])) {
    $pid = $_POST['product_id'];
    $del = $conn->prepare("DELETE FROM cart WHERE user_id=? AND product_id=?");
    $del->bind_param("ii", $uid, $pid);
    $del->execute();
}

if (isset($_POST['change_qty'])) {
    $pid = $_POST['product_id'];
    $qty = (int)$_POST['quantity'];
    $upd = $conn->prepare("UPDATE cart SET quantity=? WHERE user_id=? AND product_id=?");
    $upd->bind_param("iii", $qty, $uid, $pid);
    $upd->execute();
}

// Fetch cart items
$cartQuery = $conn->prepare("SELECT * FROM cart WHERE user_id=?");
$cartQuery->bind_param("i", $uid);
$cartQuery->execute();
$result = $cartQuery->get_result();
$cartList = $result->fetch_all(MYSQLI_ASSOC);

$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shopping Cart</title>
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    margin:0; padding:0; color:#222;
    background: url('../images/home.png') no-repeat center center fixed;
    background-size: cover;
}
header {
    background: rgba(44, 62, 80, 0.9);
    color:white;
    padding:15px 0;
    display:flex;
    justify-content:center;  /* center horizontally */
    align-items:center;      /* center vertically */
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




/* Cart wrapper */
.cart-wrapper {
    max-width:1100px;
    margin:40px auto;
    padding:20px;
    background:rgba(255,255,255,0.95);
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
     border: 2px solid rgba(44, 62, 80, 0.9);
     background: url('../images/container.png') no-repeat center center fixed;
      background-size: cover;
}

.cart-wrapper h2 {
    text-align:center;
    font-size:2rem;
    margin-bottom:25px;
    color: rgba(44, 62, 80, 0.9);
}

.cart-item {
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:15px;
    border-bottom:1px solid #ddd;
}

.cart-item img { width:100px; height:100px; object-fit:cover; border-radius:8px; margin-right:15px; }

.item-info { flex:1; text-align:left; }
.item-info h4 { margin:0 0 8px 0; color:#333; }
.item-info p { margin:0; color:#555; }

.item-actions { display:flex; align-items:center; gap:10px; }
.item-actions input[type="number"] { width:60px; padding:5px; border-radius:5px; border:1px solid #ccc; }
.item-actions button { padding:8px 12px; border:none; border-radius:5px; background-color: rgba(44, 62, 80, 0.9); color:white; font-weight:bold; cursor:pointer; transition:0.3s; }
.item-actions button:hover { background-color: rgba(44, 62, 80, 0.9); }

.cart-total { text-align:center; font-size:1.6rem; margin-top:20px; color: rgba(44, 62, 80, 0.9); }
.cart-footer { display:flex; justify-content:space-between; margin-top:30px; }
.cart-footer a { text-decoration:none; padding:12px 20px; border-radius:5px; background-color:#28a745; color:white; font-weight:bold; transition:0.3s; }
.cart-footer a:hover { background-color:#218838; }
.empty-msg { text-align:center; font-size:1.2rem; color: rgba(44, 62, 80, 0.9); margin:40px 0; }

/* Pay button */
.pay-btn {
    padding:12px 20px; border:none; border-radius:5px;
    background:#ff6600; color:white; font-weight:bold; cursor:pointer;
    transition:0.3s;
}
.pay-btn:hover { background:#e65c00; }
</style>
</head>
<body>

<header>
    <div class="logo-container">
        <img src="../images/logo.png" alt="The BookHaven Logo" class="logo">
        <span class="site-title">THE BOOKHAVEN</span>
    </div>
</header>

<div class="cart-wrapper">
<h2>Your Shopping Cart</h2>
<?php if (empty($cartList)) : ?>
    <p class="empty-msg">Your cart is currently empty.</p>
<?php else : ?>
    <?php
    // Fetch product details
    $ids = array_column($cartList, 'product_id');
    $idStr = implode(',', array_map('intval', $ids));
    $result = $conn->query("SELECT * FROM products WHERE id IN ($idStr)");
    $products = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($products as $prod) {
        $qty = 0;
        foreach ($cartList as $ci) {
            if ($ci['product_id'] == $prod['id']) {
                $qty = $ci['quantity'];
                break;
            }
        }
        $total += $prod['price'] * $qty;
    ?>
    <div class="cart-item">
        <img src="../images/<?= htmlspecialchars($prod['image']); ?>" alt="<?= htmlspecialchars($prod['name']); ?>">
        <div class="item-info">
            <h4><?= htmlspecialchars($prod['name']); ?></h4>
            <p>Price: $<?= number_format($prod['price'],2); ?> x <?= $qty ?></p>
        </div>
        <div class="item-actions">
            <form method="POST" style="display:inline;">
                <input type="hidden" name="product_id" value="<?= $prod['id']; ?>">
                <input type="number" name="quantity" value="<?= $qty; ?>" min="1">
                <button type="submit" name="change_qty">Update</button>
            </form>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="product_id" value="<?= $prod['id']; ?>">
                <button type="submit" name="remove_item">Remove</button>
            </form>
        </div>
    </div>
    <?php } ?>
    <div class="cart-total">
        Total: $<?= number_format($total, 2); ?>
    </div>
  
    <div class="cart-footer">
        <a href="../home.php">Continue Shopping</a>
        <form method="POST" action="checkout.php" style="display:inline;">
            <input type="hidden" name="total" value="<?= $total; ?>">
            <button type="submit" class="pay-btn">Pay Now</button>
        </form>
    </div>

<?php endif; ?>
</div>
</body>
</html>
