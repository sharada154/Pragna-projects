<?php
session_start();
include '../holds/database.php'; // MySQLi connection

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // First, delete the image file from server
    $result = $conn->query("SELECT image FROM products WHERE id=$id");
    if ($result && $row = $result->fetch_assoc()) {
        $imagePath = "../images/" . $row['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // delete image
        }
    }

    // Delete product from database
    $conn->query("DELETE FROM products WHERE id=$id");
}

header("Location: manage_products.php");
exit();
?>
