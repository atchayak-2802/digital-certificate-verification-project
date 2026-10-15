
<?php
session_start();

require_once "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $student = $students->findOne([
        "username" => $username
    ]);

    if ($student && password_verify($password, $student["password"])) {

        $_SESSION["student_id"] = (string) $student["_id"];
        $_SESSION["student_username"] = $student["username"];
        $_SESSION["student_roll_number"] = $student["roll_number"];

        header("Location: student_dashboard.php");
        exit;

    } else {
        $message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Login</title>

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

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            display: block;
            margin: 8px 0;
            text-decoration: none;
            color: #1f4e79;
        }
    </style>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="app-layout">
    <?php
    $sidebarRole = "public";
    $activePage = "student";
    require "includes/sidebar.php";
    ?>
    <main class="app-main auth-main">
        <header class="app-topbar">
            <h2>Student access</h2>
        </header>

<div class="login-box">

    <h2>Student Login</h2>

    <?php if ($message != ""): ?>
        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>

    </form>

    <div class="links">
        <a href="student_register.php">New student? Register</a>
        <a href="index.php">← Back to Home</a>
    </div>

</div>

    </main>
</div>

</body>
</html>
