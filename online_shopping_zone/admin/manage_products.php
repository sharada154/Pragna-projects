<?php
include '../holds/database.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>
body {             
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;             
    background-color: #eef2f7;             
    margin: 0;             
    padding: 0;        
       background: url('../images/white.jpg') no-repeat center center fixed;
      background-size: cover; 
}         

.container {             
    width: 85%;             
    margin: 40px auto;             
    padding: 25px;             
    background: #ffffff;             
    border-radius: 10px;             
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);         
    overflow-x: auto; 
}         

h2 {             
    text-align: center;             
    color: #222;             
    margin-bottom: 25px; 
    font-size: 26px; 
    font-weight: 600;         
}         

table {             
    width: 100%;             
    border-collapse: collapse;             
    margin-top: 15px;         
    font-size: 15px;
}         

th, td {             
    padding: 12px 15px;             
    border: 1px solid #e0e0e0;         
}         

th {             
    background: linear-gradient(135deg, #1a010fff, #1a010fff);             
    color: white;             
    font-weight: 600; 
    letter-spacing: 0.5px;
}         

td img {             
    width: 55px;             
    height: auto;             
    border-radius: 6px;         
}         

tr:nth-child(even) {             
    background-color: #f8fdf9;         
}         

tr:hover {             
    background-color: #e9f7ef;         
    transition: 0.2s ease-in-out;
}         

.actions { 
    text-align: center;
}

 
/* Common button style */
.actions a {
    display: inline-block;
    margin: 0 6px;
    padding: 6px 12px;
    font-size: 14px;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
    white-space: nowrap;
}

/* Edit button (green) */
.edit-btn {
    color: #28a745;
    border: 1px solid #28a745;
    background: #fff;
}
.edit-btn:hover {
    background: #28a745;
    color: white;
}

/* Delete button (red) */
.delete-btn {
    color: #dc3545;
    border: 1px solid #dc3545;
    background: #fff;
}
.delete-btn:hover {
    background: #dc3545;
    color: white;
}


.btn-back {             
    display: block;             
    width: 180px;             
    margin: 35px auto;             
    padding: 12px 18px;             
    background: linear-gradient(135deg, #1b0113ff, );             
    color: #1b0113ff;             
    font-size: 15px;             
    font-weight: 500;             
    border: 1px solid #1b0113ff;            
    border-radius: 8px;             
    text-align: center;             
    text-decoration: none; 
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    transition: background 0.3s ease, transform 0.2s;        
}         

.btn-back:hover {             
    background: linear-gradient(135deg, #0056b3, #003f7f);         
    transform: translateY(-2px); 
}         

/* Column widths */
table th:nth-child(1), table td:nth-child(1) { width: 6%; text-align: center; }  /* Serial No */
table th:nth-child(2), table td:nth-child(2) { width: 20%; }  /* Name */
table th:nth-child(3), table td:nth-child(3) { width: 10%; }  /* Price */
table th:nth-child(4), table td:nth-child(4) { width: 34%; }  /* Description */
table th:nth-child(5), table td:nth-child(5) { width: 12%; text-align: center; }  /* Image */
table th:nth-child(6), table td:nth-child(6) { width: 18%; text-align: center; }  /* Actions */

</style>
</head>
<body>

<div class="container">
    <h2>Manage Products</h2>

    <table>
        <tr>
            <th>Serial No.</th>
            <th>Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
         <?php $serial = 1; 
         foreach ($products as $product) : ?>
            <tr>
                 <td><?= $serial++; ?></td>
                <td><?= htmlspecialchars($product['name']); ?></td>
                <td>$<?= number_format($product['price'], 2); ?></td>
                <td><?= htmlspecialchars($product['description']); ?></td>
                <td><img src="../images/<?= htmlspecialchars($product['image']); ?>" alt="Product Image"></td>
            
                <td class="actions">
    <a href="edit.php?id=<?= $product['id']; ?>" class="edit-btn">Edit</a>
    <a href="delete.php?id=<?= $product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
</td>

            </tr>
        <?php endforeach; ?>

    </table>

    <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
</div>

</body>
</html>