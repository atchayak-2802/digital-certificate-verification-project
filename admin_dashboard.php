
<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Admin Dashboard</title>

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
            width: 850px;
            margin: 50px auto;
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .cards {
            display: flex;
            gap: 20px;
        }

        .card {
            background: white;
            flex: 1;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .card h3 {
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            background: #1f4e79;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 5px;
        }

        .button:hover {
            background: #163a5c;
        }

    </style>
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="app-layout">
    <?php
    $sidebarRole = "admin";
    $activePage = "dashboard";
    require "includes/sidebar.php";
    ?>

    <main class="app-main">
        <header class="app-topbar">
            <h2>Admin Dashboard</h2>
        </header>

<div class="container admin-dashboard-content">

    <section class="dashboard-welcome">
        <div>
            <span class="dashboard-eyebrow">ADMIN WORKSPACE</span>
            <h1>Welcome back, Admin</h1>
            <p>Your certificate workspace is ready. What would you like to do today?</p>
        </div>
        <span class="dashboard-welcome-mark" aria-hidden="true">✦</span>
    </section>

    <div class="dashboard-section-heading">
        <div>
            <span class="dashboard-eyebrow">QUICK ACTIONS</span>
            <h2>Manage your certificates</h2>
        </div>
    </div>

    <div class="cards dashboard-actions">

        <div class="card dashboard-action">
            <span class="dashboard-action-icon dashboard-action-icon-add" aria-hidden="true">+</span>
            <span class="dashboard-action-label">ISSUANCE</span>

            <h3>Add Certificate</h3>

            <p>
                Issue a verified digital achievement to a student.
            </p>

            <a class="button" href="add_certificate.php">
                Create certificate <span aria-hidden="true">&rarr;</span>
            </a>

        </div>

        <div class="card dashboard-action">
            <span class="dashboard-action-icon dashboard-action-icon-manage" aria-hidden="true">▤</span>
            <span class="dashboard-action-label">RECORDS</span>

            <h3>View Certificates</h3>

            <p>
                Browse, update, and manage issued certificates.
            </p>

            <a class="button" href="certificates.php">
                Open certificate list <span aria-hidden="true">&rarr;</span>
            </a>

        </div>

    </div>

</div>

    </main>
</div>

</body>
</html>
