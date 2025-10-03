<?php
session_start();
include '../holds/database.php';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT * FROM users WHERE email=? AND role='admin'");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Incorrect email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - BookHaven</title>
<style>
body {
    font-family: 'Verdana', sans-serif;
    background: #e8ebf0;
    margin: 0;
    padding: 0;
     background: url('../images/white.jpg') no-repeat center center fixed;
      background-size: cover;
}
.login-box {
    max-width: 420px;
    margin: 90px auto;
    padding: 35px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}
.login-box h1 {
    text-align: center;
    color: #222;
    margin-bottom: 25px;
}
.login-box label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #444;
}
.login-box input {
    width: 100%;
    padding: 10px;
    margin-bottom: 18px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
}
.login-box button {
    width: 100%;
    padding: 12px;
    background: #007bff;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
}
.login-box button:hover {
    background: #0056b3;
}
.error-msg {
    text-align: center;
    color: #d9534f;
    margin-bottom: 15px;
}
</style>
</head>
<body>

<div class="login-box">
    <h1>Admin Sign In</h1>
    <?php if(isset($error)) echo "<p class='error-msg'>{$error}</p>"; ?>
    <form method="POST">
        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="login">Sign In</button>
    </form>
</div>

</body>
</html>
