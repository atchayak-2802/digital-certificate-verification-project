
<?php

session_start();

$message = "";

$username = "admin";
$password = "Admin@123";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $entered_username = $_POST["username"];
    $entered_password = $_POST["password"];

    if ($entered_username === $username && $entered_password === $password) {

        $_SESSION["admin"] = true;

        header("Location: admin_dashboard.php");
        exit;

    } else {

        $message = "Invalid username or password.";

    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Admin Login</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }

        .login-box {
            width: 380px;
            margin: 100px auto;
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0 18px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #1f4e79;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #163a5c;
        }

        .message {
            color: red;
            text-align: center;
        }

        .back {
            text-align: center;
            margin-top: 20px;
        }

        .back a {
            text-decoration: none;
        }

    </style>
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="app-layout">
    <?php
    $sidebarRole = "public";
    $activePage = "admin";
    require "includes/sidebar.php";
    ?>
    <main class="app-main auth-main">
        <header class="app-topbar">
            <h2>Administrator access</h2>
        </header>

<div class="login-box">

    <h2>Admin Login</h2>

    <?php if ($message != ""): ?>

        <p class="message">
            <?php echo $message; ?>
        </p>

    <?php endif; ?>

    <form method="POST">

        <label>Username</label>

        <input
            type="text"
            name="username"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            required
        >

        <button type="submit">
            Login
        </button>

    </form>

    <div class="back">

        <a href="index.php">
            ← Back to Home
        </a>

    </div>

</div>

    </main>
</div>

</body>
</html>
