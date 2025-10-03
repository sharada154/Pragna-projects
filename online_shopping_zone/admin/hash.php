<?php
include '../holds/database.php';
 // your DB connection

$username = "Admin";
$email = "admin2000@gmail.com";
$password = "admin123"; // plain password
$role = "admin";

// Check if an admin already exists
$check = mysqli_query($conn, "SELECT * FROM users WHERE role='admin'");

if(mysqli_num_rows($check) == 0){
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert admin
    mysqli_query($conn, "INSERT INTO users (username,email,password,role,created_at)
                         VALUES ('$username','$email','$hashedPassword','$role',NOW())");
    echo "Admin created successfully!";
}else{
    // Prevent any further execution
    exit("Admin already exists! Cannot create again.");
}
?>
