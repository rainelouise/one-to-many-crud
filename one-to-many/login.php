<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'one_to_many');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: 'Verdana', sans-serif;
            background: #f5f5f5;
            max-width: 400px;
            margin: 80px auto;
            padding: 20px;
            color: #333;
        }

        h2 {
            color: #FB6F92;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            width: 100%; 
            max-width: 400px; 
            margin: 0 auto; 
            box-sizing: border-box; 
        }

        input {
            width: 100%;
            padding: 10px; 
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box; 
        }

        button {
            background: #FF8FAB;
            color: #fff;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #FF8FAB;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #FF8FAB;
            text-decoration: none;
        }
        
        a:hover {
            color: #FB6F92;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Login</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="login">Login</button>
</form>
<a href="register.php">Don't have an account? Register</a>

</body>
</html>