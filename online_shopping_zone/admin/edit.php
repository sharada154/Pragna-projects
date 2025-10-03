<?php
session_start();
include '../holds/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    echo "<p style='text-align:center;color:red;'>Product not found!</p>";
    exit();
}

if (isset($_POST['update_product'])) {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/$image");
    } else {
        $image = $book['image'];
    }

    $updateStmt = $conn->prepare("UPDATE products SET name=?, price=?, description=?, image=? WHERE id=?");
    $updateStmt->bind_param("sdssi", $name, $price, $description, $image, $id);
    $updateStmt->execute();

    $successMessage = "Product updated successfully!";
    $book['name'] = $name;
    $book['price'] = $price;
    $book['description'] = $description;
    $book['image'] = $image;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Book - BookHaven</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f5f5;
    margin: 0;
    padding: 0;
       background: url('../images/white.jpg') no-repeat center center fixed;
      background-size: cover;
}

.container {
    max-width: 600px;
    margin: 50px auto;
    background: #fff;
    border-radius: 12px;
    padding: 30px 40px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
    font-weight: 700; /* bolder than before */
    letter-spacing: 0.5px;
}

label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600; /* bolder labels */
    color: #444;
    font-size: 25px;
}

input[type="text"], 
input[type="number"], 
textarea, 
input[type="file"] {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    transition: all 0.3s ease;
}

textarea {
    resize: vertical;
    height: 100px;
}

button {
    background: #4CAF50;
    color: white;
    padding: 15px;
    width: 100%;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
    font-weight: 600; /* slightly bold button text */
}

button:hover {
    background: #45a049;
}

.message {
    text-align: center;
    color: #1a7f1a;
    margin-bottom: 20px;
    font-weight: 500;
}

.back-link {
    text-align: center;
    margin-top: 20px;
}

.back-link a {
    text-decoration: none;
    color: #4CAF50;
    font-weight: 600;
}

.back-link a:hover {
    text-decoration: underline;
}

.current-img {
    display: block;
    margin: 15px 0 20px 0; /* top 15px, bottom 20px */
    max-width: 150px;
    height: auto;
    border: 1px solid #ccc;
    padding: 5px;
    border-radius: 5px;
}


</style>
</head>
<body>

<div class="container">
<h1>Edit Book</h1>

<?php if(isset($successMessage)) echo "<div class='message'>{$successMessage}</div>"; ?>

<form method="POST" enctype="multipart/form-data">
    <label for="name">Book Title</label>
    <input type="text" name="name" id="name" value="<?= htmlspecialchars($book['name']); ?>" required>

    <label for="price">Price</label>
    <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($book['price']); ?>" required>

    <label for="description">Description</label>
    <textarea name="description" id="description" required><?= htmlspecialchars($book['description']); ?></textarea>

    <?php if(!empty($book['image'])): ?>
            <label>Current Cover:</label>
            <img src="../images/<?= htmlspecialchars($book['image']); ?>" class="current-img" alt="Book Cover">
        <?php endif; ?>

        <label for="image">Upload New Cover (leave blank to keep current)</label>
        <input type="file" name="image" id="image">

    <button type="submit" name="update_product">Update Product</button>
</form>

<div class="back-link">
    <a href="manage_products.php">Back to Manage Products</a>
</div>
</div>

</body>
</html>
