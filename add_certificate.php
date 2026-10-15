
<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit;
}

require_once "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_name = trim($_POST["student_name"]);
    $roll_number = trim($_POST["roll_number"]);
    $department = trim($_POST["department"]);
    $certificate_type = trim($_POST["certificate_type"]);
    $event_name = trim($_POST["event_name"]);
    $issue_date = $_POST["issue_date"];

    // Generate unique Certificate ID
    $certificate_id = "CERT-" . date("Y") . "-" . rand(1000, 9999);

    $certificate = [
        "certificate_id" => $certificate_id,
        "student_name" => $student_name,
        "roll_number" => $roll_number,
        "department" => $department,
        "certificate_type" => $certificate_type,
        "event_name" => $event_name,
        "issue_date" => $issue_date,
        "status" => "Valid"
    ];

    $certificates->insertOne($certificate);

    $message = "Certificate created successfully! Certificate ID: " . $certificate_id;
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Certificate</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
        }

        .header {
            background: #1f4e79;
            color: white;
            padding: 20px 40px;
        }

        .container {
            width: 600px;
            margin: 40px auto;
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        }

        h2 {
            margin-top: 0;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 6px;
        }

        input,
        select {
            width: 100%;
            padding: 11px;
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
            background: #e8f5e9;
            color: #2e7d32;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
        }

    </style>
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="app-layout">
    <?php
    $sidebarRole = "admin";
    $activePage = "add";
    require "includes/sidebar.php";
    ?>
    <main class="app-main">
        <header class="app-topbar">
            <h2>Add Certificate</h2>
        </header>

<div class="container">

    <?php if ($message != ""): ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <label>Student Name</label>
        <input type="text" name="student_name" required>

        <label>Roll Number</label>
        <input type="text" name="roll_number" required>

        <label>Department</label>
        <input type="text" name="department" required>

        <label>Certificate Type</label>

        <select name="certificate_type" required>

            <option value="">Select Type</option>
            <option value="Workshop">Workshop</option>
            <option value="Seminar">Seminar</option>
            <option value="Hackathon">Hackathon</option>
            <option value="Training">Training</option>
            <option value="Participation">Participation</option>

        </select>

        <label>Event Name</label>
        <input type="text" name="event_name" required>

        <label>Issue Date</label>
        <input type="date" name="issue_date" required>

        <button type="submit">
            Create Certificate
        </button>

    </form>

    <a class="back" href="admin_dashboard.php">
        ← Back to Dashboard
    </a>

</div>

    </main>
</div>

</body>

</html>
