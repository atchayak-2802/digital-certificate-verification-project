
<?php
session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: student_login.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

require_once "config.php";

$roll_number = $_SESSION["student_roll_number"];

$student = $students->findOne([
    "roll_number" => $roll_number
]);

$student_certificates = $certificates->find(
    ["roll_number" => $roll_number],
    ["sort" => ["_id" => -1]]
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>

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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h2 {
            margin: 0;
        }

        .logout {
            background: #d9534f;
            color: white;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 5px;
        }

        .container {
            width: 90%;
            margin: 40px auto;
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .table-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #1f4e79;
            color: white;
        }

        .view {
            background: #1f4e79;
            color: white;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 4px;
        }

        .valid {
            color: green;
            font-weight: bold;
        }

        .revoked {
            color: red;
            font-weight: bold;
        }

        .no-certificates {
            color: #666;
            text-align: center;
            padding: 20px;
        }
    </style>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="app-layout">
    <?php
    $sidebarRole = "student";
    $activePage = "dashboard";
    require "includes/sidebar.php";
    ?>
    <main class="app-main">
        <header class="app-topbar">
            <h2>Student Dashboard</h2>
        </header>

<div class="container">

    <div class="welcome">
        <h3>
            Welcome,
            <?php echo htmlspecialchars($student["student_name"]); ?>
        </h3>

        <p>
            <strong>Roll Number:</strong>
            <?php echo htmlspecialchars($roll_number); ?>
        </p>
    </div>

    <div class="table-box">

        <h3>My Certificates</h3>

        <table>

            <tr>
                <th>Certificate ID</th>
                <th>Certificate Type</th>
                <th>Event</th>
                <th>Issue Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php
            $found = false;

            foreach ($student_certificates as $certificate):
                $found = true;
            ?>

            <tr>

                <td>
                    <?php echo htmlspecialchars($certificate["certificate_id"]); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($certificate["certificate_type"]); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($certificate["event_name"]); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($certificate["issue_date"]); ?>
                </td>

                <td>
                    <?php if ($certificate["status"] == "Valid"): ?>
                        <span class="valid">Valid</span>
                    <?php else: ?>
                        <span class="revoked">Revoked</span>
                    <?php endif; ?>
                </td>

                <td>
                    <a class="view"
                       href="certificate.php?id=<?php echo $certificate["_id"]; ?>">
                        View
                    </a>
                </td>

            </tr>

            <?php endforeach; ?>

        </table>

        <?php if (!$found): ?>

            <p class="no-certificates">
                No certificates found for your roll number.
            </p>

        <?php endif; ?>

    </div>

</div>

    </main>
</div>

</body>
</html>
