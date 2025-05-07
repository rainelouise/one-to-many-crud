<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'one_to_many');

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if ($check->num_rows > 0) {
        echo "Username already exists.";
    } else {
        $conn->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
        header('Location: login.php');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            font-family: 'Verdana', sans-serif;
            background: #f5f5f5;
            max-width: 400px;
            margin: 80px auto;
            padding: 20px;
            color: #333;
        }
        
        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        h2 {
            color: #FB6F92;
            text-align: center;
            margin-bottom: 20px;
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
            background: #FB6F92;
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
<h2>Register</h2>
<div class="container">
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="register">Register</button>
    </form>
    <a href="login.php">Already have an account? Login</a>
</div>

</body>
</html>