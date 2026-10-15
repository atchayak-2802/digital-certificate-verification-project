
<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit;
}

require_once "config.php";

if (!isset($_GET["id"])) {
    header("Location: certificates.php");
    exit;
}

$id = new MongoDB\BSON\ObjectId($_GET["id"]);

$certificate = $certificates->findOne([
    "_id" => $id
]);

if (!$certificate) {
    echo "Certificate not found.";
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_name = trim($_POST["student_name"]);
    $roll_number = trim($_POST["roll_number"]);
    $department = trim($_POST["department"]);
    $certificate_type = trim($_POST["certificate_type"]);
    $event_name = trim($_POST["event_name"]);
    $issue_date = $_POST["issue_date"];

    $update_result = $certificates->updateOne(
        ["_id" => $id],
        [
            '$set' => [
                "student_name" => $student_name,
                "roll_number" => $roll_number,
                "department" => $department,
                "certificate_type" => $certificate_type,
                "event_name" => $event_name,
                "issue_date" => $issue_date
            ]
        ]
    );

    if ($update_result->getMatchedCount() === 0) {
        $message = "The certificate could not be found, so no changes were saved.";
    } else {
        $_SESSION["certificate_message"] = "Certificate updated successfully.";
        header("Location: certificates.php");
        exit;
    }

    $certificate = $certificates->findOne([
        "_id" => $id
    ]);
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Certificate</title>

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

        .message {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 12px;
            border-radius: 5px;
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
    $activePage = "certificates";
    require "includes/sidebar.php";
    ?>
    <main class="app-main">
        <header class="app-topbar">
            <h2>Edit Certificate</h2>
        </header>

<div class="container">

    <?php if ($message != ""): ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <p>
        <strong>Certificate ID:</strong>
        <?php echo htmlspecialchars($certificate["certificate_id"]); ?>
    </p>

    <form method="POST">

        <label>Student Name</label>

        <input
            type="text"
            name="student_name"
            value="<?php echo htmlspecialchars($certificate["student_name"]); ?>"
            required
        >

        <label>Roll Number</label>

        <input
            type="text"
            name="roll_number"
            value="<?php echo htmlspecialchars($certificate["roll_number"]); ?>"
            required
        >

        <label>Department</label>

        <input
            type="text"
            name="department"
            value="<?php echo htmlspecialchars($certificate["department"]); ?>"
            required
        >

        <label>Certificate Type</label>

        <select name="certificate_type" required>

            <option value="Workshop"
                <?php if ($certificate["certificate_type"] == "Workshop") echo "selected"; ?>>
                Workshop
            </option>

            <option value="Seminar"
                <?php if ($certificate["certificate_type"] == "Seminar") echo "selected"; ?>>
                Seminar
            </option>

            <option value="Hackathon"
                <?php if ($certificate["certificate_type"] == "Hackathon") echo "selected"; ?>>
                Hackathon
            </option>

            <option value="Training"
                <?php if ($certificate["certificate_type"] == "Training") echo "selected"; ?>>
                Training
            </option>

            <option value="Participation"
                <?php if ($certificate["certificate_type"] == "Participation") echo "selected"; ?>>
                Participation
            </option>

        </select>

        <label>Event Name</label>

        <input
            type="text"
            name="event_name"
            value="<?php echo htmlspecialchars($certificate["event_name"]); ?>"
            required
        >

        <label>Issue Date</label>

        <input
            type="date"
            name="issue_date"
            value="<?php echo htmlspecialchars($certificate["issue_date"]); ?>"
            required
        >

        <button type="submit">
            Save Changes
        </button>

    </form>

    <a class="back" href="certificates.php">
        ← Back to Certificates
    </a>

</div>

    </main>
</div>

</body>

</html>
