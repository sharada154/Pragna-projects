<?php
session_start();
include 'holds/database.php';

// Logout handling
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: user/login.php");
    exit();
}

// Initialize cart count
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT SUM(quantity) as total_items FROM cart WHERE user_id=?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $cart_count = $res['total_items'] ?? 0;
}

// Handle Add to Cart
$showModal = false;
$modalMessage = '';
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    // Check if product already in cart
    $check = $conn->prepare("SELECT * FROM cart WHERE user_id=? AND product_id=?");
    $check->bind_param("ii", $user_id, $product_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $modalMessage = "Book already in cart!";
    } else {
        $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $insert->bind_param("ii", $user_id, $product_id);
        if ($insert->execute()) {
            $modalMessage = "Book added to cart successfully!";
        } else {
            $modalMessage = "Error adding book to cart!";
        }
    }
    $showModal = true;

    // Update cart count after adding
    $stmt = $conn->prepare("SELECT SUM(quantity) as total_items FROM cart WHERE user_id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $cart_count = $res['total_items'] ?? 0;
}

// Fetch products
$products = [];
$result = $conn->query("SELECT * FROM products");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>THE BOOKHAVEN</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin:0;
    padding:0;
    color:#333;
    background: url('images/home.png') no-repeat center center fixed;
    background-size: cover;
}

header {
    background: rgba(44, 62, 80, 0.9);
    color:white;
    padding:15px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

.logo {
    height: 50px;
}

.site-title {
    font-size: 26px;
    color: white;
    font-weight: bold;
    letter-spacing: 1px;
}

nav {
    display:flex;
    gap:15px;
    align-items:center;
    position:relative;
}

.nav-btn {
    display:inline-block;
    padding:8px 16px;
    background:#e67e22;
    color:white !important;
    border-radius:6px;
    text-decoration:none;
    font-size:15px;
    border:none;
    cursor:pointer;
    transition: background 0.3s ease, transform 0.2s ease;
    position:relative;
}

.nav-btn:hover { background:#d35400; transform:translateY(-2px); }
.logout { background:#c0392b; }
.logout:hover { background:#a93226; }

/* cart icon only */
.cart-icon { width:24px; vertical-align:middle; cursor:pointer; }
.cart-badge { position:absolute; top:-5px; right:-10px; background:red; color:white; font-size:12px; font-weight:bold; padding:2px 6px; border-radius:50%; }

.main-container { padding:40px; max-width:1200px; margin:auto; }
h2 { text-align:center; margin-bottom:30px; font-size:28px; color:#2c3e50; }

.product-list { display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px; }
.product-card { background:#fff; border-radius:10px; padding:20px; text-align:center; box-shadow:0 4px 10px rgba(0,0,0,0.1); transition:transform 0.2s ease; }
.product-card:hover { transform:translateY(-5px); }
.product-card h3 { margin:10px 0; color:#34495e; }
.product-card p { margin:5px 0; font-size:14px; color:#666; }
.product-image { width:100%; max-height:180px; object-fit:cover; border-radius:8px; margin:10px 0; }
.btn-add-cart { background:#27ae60; color:white; padding:10px 15px; border:none; border-radius:6px; cursor:pointer; font-size:14px; transition:background 0.3s ease; }
.btn-add-cart:hover { background:#1e8449; }

footer { background:#2c3e50; color:white; text-align:center; padding:15px; margin-top:40px; }

/* Modal */
.modal { display: <?= $showModal ? 'block' : 'none' ?>; position: fixed; z-index: 999; left:0; top:0; width:100%; height:100%; overflow:auto; background-color: rgba(0,0,0,0.4); }
.modal-content { background-color:#fff; margin:15% auto; padding:20px; border-radius:10px; width: 300px; text-align:center; font-size:16px; box-shadow:0 4px 10px rgba(0,0,0,0.2); }
.close-btn { margin-top:10px; padding:8px 16px; background:#007bff; color:white; border:none; border-radius:6px; cursor:pointer; }
.close-btn:hover { background:#0056b3; }
</style>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="images/logo.png" alt="The BookHaven Logo" class="logo">
        <span class="site-title">THE BOOKHAVEN</span>
    </div>
    <nav>
        <a href="user/login.php" class="nav-btn">Login</a>
        <a href="user/register.php" class="nav-btn">Register</a>
        
        <!-- Cart (only image clickable) -->
        <a href="user/cart.php" style="position:relative;">
            <img src="images/cart-icon.png" alt="Cart" class="cart-icon">
            <?php if($cart_count > 0): ?>
                <span class="cart-badge"><?= $cart_count ?></span>
            <?php endif; ?>
        </a>
        
        <form method="POST" style="display:inline;">
            <button type="submit" name="logout" class="nav-btn logout">Logout</button>
        </form>
    </nav>
</header>

<div class="main-container">
    <h2>Explore Our Collection</h2>
    <div class="product-list">
        <?php if (!empty($products)) : ?>
            <?php foreach ($products as $product) : ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($product['name']); ?></h3>
                    <p>Price: ₹<?= number_format($product['price'], 2); ?></p>
                    <p><?= htmlspecialchars($product['description']); ?></p>
                    <?php if (!empty($product['image']) && file_exists("images/" . $product['image'])): ?>
                        <img src="images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="product-image">
                    <?php else: ?>
                        <img src="images/no-image.png" alt="No image available" class="product-image">
                    <?php endif; ?>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                        <button type="submit" name="add_to_cart" class="btn-add-cart">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products available right now.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="cartModal">
    <div class="modal-content">
        <p><?= $modalMessage ?></p>
        <form method="POST">
            <button type="submit" class="close-btn">OK</button>
        </form>
    </div>
</div>

<footer>
    <p>&copy; <?= date('Y'); ?> THE BOOKHAVEN. All rights reserved.</p>
</footer>
</body>
</html>
