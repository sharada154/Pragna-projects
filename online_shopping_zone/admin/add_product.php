<?php
session_start();
include '../holds/database.php'; // Make sure this path is correct

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$successMessage = "";

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];

    if (!empty($image)) {
        move_uploaded_file($tmp_name, "../images/$image");

        $stmt = $conn->prepare("INSERT INTO products (name, price, description, image, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("sdss", $name, $price, $description, $image); // s=string, d=double/number
        $stmt->execute();

        $successMessage = "Product added successfully!";
    } else {
        $successMessage = "Please select an image!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Product - BookHaven</title>
<style>
body { font-family: Arial, sans-serif; background-color: #111010ff; margin:0; padding:0;   background: url('../images/white.jpg') no-repeat center center fixed;
      background-size: cover; }
.container { width: 50%; margin: 50px auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
h2 { text-align: center; color: #0c0c0cff; }
form { display: flex; flex-direction: column; }
label { margin: 10px 0 5px; font-weight: bold; color: #161515ff; }
input[type="text"], input[type="number"], textarea, input[type="file"] { padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
textarea { resize: vertical; height: 100px; }
button { background-color: #2b27f8ff; color: white; padding: 15px; font-size: 16px; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s ease; }
button:hover { background-color: #380ef3ff; }
.message { color: green; text-align: center; margin-top: 20px; font-size: 18px; }
.back-link { display: block; text-align: center; margin-top: 20px; font-size: 14px; }
.back-link a { color: #701ae0ff; text-decoration: none; }
.back-link a:hover { text-decoration: underline; }
</style>
</head>
<body>
<div class="container">
<h2>Add Product - BookHaven</h2>

<form method="POST" enctype="multipart/form-data">
    <label for="name">Book Title:</label>
    <input type="text" name="name" id="name" required>

    <label for="price">Price:</label>
    <input type="number" step="0.01" name="price" id="price" required>

    <label for="description">Author / Description:</label>
    <textarea name="description" id="description" required></textarea>

    <label for="image">Cover Image:</label>
    <input type="file" name="image" id="image" required>

    <button type="submit" name="add_product">Add Book</button>
</form>

<?php if ($successMessage != ""): ?>
    <div class="message"><?php echo $successMessage; ?></div>
<?php endif; ?>

<div class="back-link">
    <a href="manage_products.php">Back to Manage Products</a>
</div>
</div>
</body>
</html>
