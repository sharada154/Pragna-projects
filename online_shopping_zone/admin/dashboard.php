<?php
session_start();

// Check if admin is logged in
if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$adminName = isset($_SESSION['username']) ? $_SESSION['username'] : "Admin";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
             background: url('../images/white.jpg') no-repeat center center fixed;
      background-size: cover;
            margin: 0;
            padding: 0;
        }

        .admin-panel {
            max-width: 700px;
            margin: 60px auto;
            background: #ffffff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
            text-align: center;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 25px;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 18px;
            margin: 25px 0;
        }

        nav a {
            text-decoration: none;
            padding: 10px 22px;
            background-color: #1abc9c;
            color: white;
            font-weight: 600;
            border-radius: 8px;
            transition: transform 0.2s, background-color 0.3s;
        }

        nav a:hover {
            background-color: #16a085;
            transform: scale(1.05);
        }

        .logout {
            background-color: #e74c3c;
        }

        .logout:hover {
            background-color: #c0392b;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 13px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="admin-panel">
        <h1>Welcome, <?php echo $adminName; ?>!</h1>
        <nav>
            <a href="add_product.php">Add New Product</a>
            <a href="manage_products.php">Manage Products</a>
            <a href="logout.php" class="logout">Logout</a>
        </nav>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> My Admin Panel</p>
    </footer>
</body>
</html>
