<?php
include('../holds/database.php');  // mysqli connection
session_start();

if (!$conn) {
    die("Database connection failed!");
}

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $hashed_password);

        if ($stmt->num_rows == 1) {
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                header("Location: ../home.php");
                exit;
            } else {
                $error_message = "Incorrect password!";
            }
        } else {
            $error_message = "Email not registered!";
        }
    } else {
        $error_message = "Error preparing query!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The BookHaven</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('../images/violet.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: relative;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 25px auto;
            background: url('../images/logo.png') no-repeat center center;
            background-size: contain;
            border-radius: 50%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        h2 {
            font-family: 'Georgia', 'Garamond', 'Times New Roman', Times, serif;
            color: #0d1b2a;
            margin-bottom: 25px;
            font-size: 1.9em;
            letter-spacing: 1px;
        }

        label {
            font-size: 1em;
            margin-bottom: 8px;
            display: block;
            color: #1b262c;
            font-weight: 600;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border: 1px solid #aaa;
            border-radius: 10px;
            font-size: 1em;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #3A8DFF;
            box-shadow: 0 0 8px rgba(58, 141, 255, 0.5);
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #3A8DFF;
            color: white;
            font-size: 1.1em;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        button:hover {
            background-color: #6CC1FF;
            transform: scale(1.03);
            box-shadow: 0 5px 15px rgba(58, 141, 255, 0.4);
        }

        .error-message {
            color: #e63946;
            font-size: 0.95em;
            margin-top: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo"></div>
        <h2>Welcome to The BookHaven</h2>
        <form method="POST">
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
