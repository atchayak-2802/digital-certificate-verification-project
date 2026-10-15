
<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

require_once "config.php";

$message = $_SESSION["certificate_message"] ?? "";
unset($_SESSION["certificate_message"]);

$csrf_token = $_SESSION["csrf_token"] ?? bin2hex(random_bytes(32));
$_SESSION["csrf_token"] = $csrf_token;

$all_certificates = $certificates->find(
    [],
    [
        "sort" => ["_id" => -1]
    ]
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Certificates</title>

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
            width: 95%;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        h2 {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #1f4e79;
            color: white;
        }

        .edit {
            background: #f0ad4e;
            color: white;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 4px;
        }

        .delete {
            background: #d9534f;
            color: white;
            padding: 7px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .revoke {
            background: #d8f1ed;
            color: #176b64;
            padding: 7px 12px;
            border: 0;
            border-radius: 4px;
            cursor: pointer;
            font: inherit;
            font-weight: 650;
        }

        .certificate-actions {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 8px;
            white-space: nowrap;
        }

        .certificate-actions .edit {
            flex: 0 0 auto;
        }

        .valid {
            color: green;
            font-weight: bold;
        }

        .revoked {
            color: red;
            font-weight: bold;
        }

        .back {
            display: inline-block;
            margin-top: 25px;
            text-decoration: none;
            color: #1f4e79;
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
            <h2>Certificate Management</h2>
        </header>

<div class="container">

    <?php if ($message !== ""): ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <h3>All Certificates</h3>

    <table>

        <tr>
            <th>Certificate ID</th>
            <th>Student Name</th>
            <th>Roll Number</th>
            <th>Department</th>
            <th>Event</th>
            <th>Issue Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($all_certificates as $certificate): ?>

        <tr>

            <td>
                <?php echo htmlspecialchars($certificate["certificate_id"]); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($certificate["student_name"]); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($certificate["roll_number"]); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($certificate["department"]); ?>
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

                <div class="certificate-actions">
                    <a
                        class="edit"
                        href="edit_certificate.php?id=<?php echo htmlspecialchars((string) $certificate["_id"]); ?>"
                    >
                        Edit
                    </a>

                    <form class="certificate-action" method="POST" action="revoke_certificate.php"
                        onsubmit="return confirm('Revoke this certificate? It will remain in the system but will no longer be valid.');">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $certificate["_id"]); ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                        <button class="revoke" type="submit">Revoke</button>
                    </form>

                    <form class="certificate-action" method="POST" action="delete_certificate.php"
                        onsubmit="return confirm('Permanently delete this certificate? This cannot be undone.');">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $certificate["_id"]); ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                        <button class="delete" type="submit">Delete</button>
                    </form>
                </div>

            </td>

        </tr>

        <?php endforeach; ?>

    </table>

    <a class="back" href="admin_dashboard.php">
        ← Back to Dashboard
    </a>

</div>

    </main>
</div>

</body>

</html>
