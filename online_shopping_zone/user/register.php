<?php
session_start();

// Include database connection (mysqli)
include('../holds/database.php');

// ---------------------------
// 1️⃣ Check if database connection exists
// ---------------------------
if (!$conn) {
    die("Oops! Database connection failed. Please try again later.");
}

if (isset($_POST['register'])) {

    // Get form inputs
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Default role for new users
    $role = "user";

    // ---------------------------
    // 2️⃣ Handle queries safely
    // ---------------------------
    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if ($checkEmail) {
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $checkEmail->store_result();

        if ($checkEmail->num_rows > 0) {
            echo "<script>alert('This email is already registered. Try logging in.');</script>";
        } else {
            // Insert new user
            $addUser = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
            if ($addUser) {
                $addUser->bind_param("sss", $email, $hashedPassword, $role);
                $addUser->execute();

                // Log the user in automatically
                $_SESSION['user_id'] = $conn->insert_id;

                // Redirect to homepage
               header("Location: ../home.php");
               exit();

            } else {
                echo "Error preparing insert query.";
            }
        }
    } else {
        echo "Error preparing select query.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <style>
   body {
    font-family: Arial, Helvetica, sans-serif;
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: url('../images/violet.jpg') no-repeat center center fixed;
    background-size: cover;
}

.register-container {
    background: rgba(255, 255, 255, 0.85);
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.3);
    width: 350px;
    text-align: center;
    animation: floatIn 1s ease-in-out;
}

@keyframes floatIn {
    0% { transform: translateY(-20px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}
.register-container img.logo {
    width: 100px;
    margin-bottom: 20px;
}

h2 {
    color: #333;
    margin-bottom: 20px;
    font-size: 1.8em;
    font-weight: 700;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #555;
    text-align: left;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1em;
    transition: 0.3s;
}

input:focus {
    border-color: #2575fc;
    outline: none;
    box-shadow: 0 0 8px rgba(37,117,252,0.3);
}

button {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: white;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.2s ease, background 0.3s ease;
}

button:hover {
    transform: translateY(-2px);
    background: linear-gradient(135deg, #5a0fbf, #1e63d4);
}

.error-message {
    color: #e74c3c;
    margin-top: 10px;
    font-weight: 600;
}

.login-link {
    display: block;
    margin-top: 15px;
    color: #2575fc;
    text-decoration: none;
    font-weight: 600;
}

.login-link:hover {
    text-decoration: underline;
}
      

  </style>
</head>
<body>
  <div class="register-container">
    <img src="../images/logo.png" alt="The BookHaven Logo" class="logo">
    <h2>Create Account</h2>
    <form method="POST">
      <label>Email</label>
      <input type="email" name="email" required>
      
      <label>Password</label>
      <input type="password" name="password" required>
      
      <button type="submit" name="register">Sign Up</button>
    </form>
    <?php if (isset($error_message)): ?>
      <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
      <a href="login.php" class="login-link">Already have an account? Login</a>
  </div>
</body>
</html>
