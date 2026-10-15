
<?php
session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: student_login.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

require_once "config.php";
require_once "includes/verification.php";

if (!isset($_GET["id"])) {
    header("Location: student_dashboard.php");
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

$verificationUrl = buildCertificateVerificationUrl((string) $certificate["certificate_id"]);
$qrCode = new Endroid\QrCode\QrCode(
    $verificationUrl,
    errorCorrectionLevel: Endroid\QrCode\ErrorCorrectionLevel::High,
    size: 240,
    margin: 8
);
$qrSvg = (new Endroid\QrCode\Writer\SvgWriter())->write($qrCode)->getString();
$qrDataUri = "data:image/svg+xml;base64," . base64_encode($qrSvg);
?>

<!DOCTYPE html>
<html>
<head>

    <title>Digital Certificate</title>

    <style>

        body {
            font-family: Georgia, serif;
            background: #f4f6f8;
            margin: 0;
        }

        .certificate {
            position: relative;
            display: flex;
            width: min(1000px, calc(100% - 48px));
            min-height: 660px;
            flex-direction: column;
            justify-content: center;
            margin: 42px auto;
            background: white;
            padding: 58px 74px 46px;
            border: 12px double #b58a43;
            box-sizing: border-box;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .certificate::before {
            position: absolute;
            inset: 10px;
            border: 1px solid rgba(181, 138, 67, 0.28);
            content: "";
            pointer-events: none;
        }

        .certificate h1 {
            margin: 0;
            color: #12465f;
            font-size: clamp(2rem, 5vw, 2.7rem);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .certificate h2 {
            font-size: 28px;
            margin: 20px 0;
        }

        .subtitle {
            font-size: 18px;
            color: #555;
        }

        .student-name {
            font-size: clamp(2rem, 5vw, 2.65rem);
            font-weight: bold;
            color: #17627b;
            margin: 20px 0 8px;
        }

        .details {
            font-size: 1rem;
            line-height: 1.8;
            margin: 16px 0 20px;
        }

        .status {
            margin-top: auto;
            font-weight: bold;
        }

        .valid {
            color: green;
        }

        .revoked {
            color: red;
        }

        .certificate-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 22px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #e8e0d1;
            text-align: left;
        }

        .certificate-note {
            max-width: 440px;
            color: #69798a;
            font-size: 0.9rem;
            line-height: 1.65;
        }

        .certificate-note strong {
            display: block;
            margin-bottom: 5px;
            color: #24445d;
            font-size: 0.98rem;
        }

        .certificate-number {
            margin-bottom: 10px;
            color: #17627b;
            font-family: Arial, sans-serif;
            font-size: 1.08rem;
            font-weight: 750;
            letter-spacing: 0.04em;
            overflow-wrap: anywhere;
        }

        .certificate-note-copy {
            color: #69798a;
            font-size: 0.82rem;
        }

        .certificate-qr {
            display: grid;
            flex: 0 0 128px;
            justify-items: center;
            gap: 5px;
            color: #617184;
            font-family: Arial, sans-serif;
            font-size: 0.68rem;
            text-align: center;
        }

        .certificate-qr img {
            display: block;
            width: 112px;
            height: 112px;
            padding: 5px;
            border: 1px solid #e2e8ee;
            border-radius: 8px;
            background: #fff;
        }

        .buttons {
            text-align: center;
            margin: 25px;
        }

        .buttons a,
        .buttons button {
            display: inline-block;
            padding: 10px 18px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            background: #1f4e79;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 15px;
        }

        @media print {

            body {
                background: white;
            }

            .app-layout {
                display: block;
            }

            .app-main {
                padding: 0;
            }

            .certificate {
                width: 100%;
                min-height: 0;
                margin: 0 auto;
                box-shadow: none;
            }

            .buttons {
                display: none;
            }

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
            <h2>My Certificate</h2>
        </header>

<div class="certificate">

    <h1>Digital Certificate</h1>

    <p class="subtitle">
        This certificate is proudly presented to
    </p>

    <div class="student-name">
        <?php echo htmlspecialchars($certificate["student_name"]); ?>
    </div>

    <p class="details">
        for successfully completing/participating in
        <br>

        <strong>
            <?php echo htmlspecialchars($certificate["event_name"]); ?>
        </strong>

        <br>

        <?php echo htmlspecialchars($certificate["certificate_type"]); ?>
        Certificate

        <br>

        from the Department of
        <?php echo htmlspecialchars($certificate["department"]); ?>

        <br>

        Issue Date:
        <?php echo htmlspecialchars($certificate["issue_date"]); ?>
    </p>

    <div class="status">

        Status:

        <?php if ($certificate["status"] == "Valid"): ?>

            <span class="valid">Valid</span>

        <?php else: ?>

            <span class="revoked">Revoked</span>

        <?php endif; ?>

    </div>

    <div class="certificate-bottom">
        <div class="certificate-note">
            <strong>Certificate Number</strong>
            <div class="certificate-number">
                <?php echo htmlspecialchars((string) $certificate["certificate_id"]); ?>
            </div>
            <div class="certificate-note-copy">
                Enter this number on the verifier page or scan the QR code to check authenticity and current status.
            </div>
        </div>
        <div class="certificate-qr">
            <img src="<?php echo htmlspecialchars($qrDataUri); ?>" alt="QR code to verify this certificate">
            <span>SCAN TO VERIFY</span>
        </div>
    </div>

</div>

<div class="buttons">

    <button onclick="window.print()">Print / Save as PDF</button>

    <a href="student_dashboard.php">← Back to Dashboard</a>

</div>

    </main>
</div>

</body>
</html>
