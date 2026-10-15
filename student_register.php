
<?php
require_once "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_name = trim($_POST["student_name"]);
    $roll_number = trim($_POST["roll_number"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Check whether username already exists
    $existing_student = $students->findOne([
        "username" => $username
    ]);

    if ($existing_student) {
        $message = "Username already exists.";
    } else {

        $student = [
            "student_name" => $student_name,
            "roll_number" => $roll_number,
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ];

        $students->insertOne($student);

        $message = "Registration successful! You can now login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }

        .register-box {
            width: 400px;
            margin: 70px auto;
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
            margin-top: 12px;
        }

        input {
            width: 100%;
            padding: 11px;
            margin-top: 7px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 25px;
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
            text-align: center;
            color: #2e7d32;
            margin-bottom: 15px;
        }

        .back {
            text-align: center;
            margin-top: 20px;
        }

        .back a {
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
            <h2>Student registration</h2>
        </header>

<div class="register-box">

    <h2>Student Registration</h2>

    <?php if ($message != ""): ?>
        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>Student Name</label>
        <input type="text" name="student_name" required>

        <label>Roll Number</label>
        <input type="text" name="roll_number" required>

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Register</button>

    </form>

    <div class="back">
        <a href="student_login.php">Already registered? Login</a>
    </div>

    <div class="back">
        <a href="index.php">← Back to Home</a>
    </div>

</div>

    </main>
</div>

</body>
</html>
