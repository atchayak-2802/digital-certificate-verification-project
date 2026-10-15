<?php
session_start();
require_once "config.php";

$certificateId = trim((string) (
    $_GET["certificate_id"]
    ?? $_GET["id"]
    ?? $_POST["certificate_id"]
    ?? ""
));
$attemptedVerification = isset($_GET["certificate_id"])
    || isset($_GET["id"])
    || $_SERVER["REQUEST_METHOD"] === "POST";
$result = "";
$certificate = null;

if ($attemptedVerification) {
    $certificate = $certificates->findOne([
        "certificate_id" => $certificateId
    ]);

    if (!$certificate) {
        $result = "Not Found";
    } elseif (($certificate["status"] ?? "") === "Revoked") {
        $result = "Revoked";
    } else {
        $result = "Valid";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Certificate</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
<div class="app-layout">
    <?php
    $sidebarRole = isset($_SESSION["admin"]) ? "admin" : (isset($_SESSION["student_id"]) ? "student" : "public");
    $activePage = "verify";
    require "includes/sidebar.php";
    ?>

    <main class="app-main">
        <header class="app-topbar">
            <h2>Certificate Verification</h2>
        </header>

        <section class="verify-container">
            <div class="verify-heading">
                <span class="verify-eyebrow">TRUST, MADE SIMPLE</span>
                <h1>Verify a certificate</h1>
                <p>Choose how you would like to check its authenticity.</p>
            </div>

            <div class="verify-methods">
                <section class="verify-method">
                    <div class="verify-method-heading">
                        <span class="verify-method-icon" aria-hidden="true">#</span>
                        <span>
                            <span class="verify-method-label">OPTION 1</span>
                            <h2>Certificate number</h2>
                        </span>
                    </div>
                    <p>Enter the certificate number shown in your records.</p>

                    <form class="verify-id-form" method="POST" action="verify.php">
                        <label for="certificate-id">Certificate number</label>
                        <input
                            id="certificate-id"
                            type="text"
                            name="certificate_id"
                            placeholder="Example: CERT-2026-1234"
                            value="<?php echo htmlspecialchars($certificateId, ENT_QUOTES, "UTF-8"); ?>"
                            autocomplete="off"
                            required
                        >
                        <button type="submit">Verify number <span aria-hidden="true">&rarr;</span></button>
                    </form>
                </section>

                <section class="verify-method verify-qr-method">
                    <div class="verify-method-heading">
                        <span class="verify-method-icon verify-method-icon-qr" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h3v3h-3zM19 14h2v2h-2zM19 19h2v2h-2zM14 19h3v2h-3z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span>
                            <span class="verify-method-label">OPTION 2</span>
                            <h2>Scan QR code</h2>
                        </span>
                    </div>
                    <p>Use your device camera to scan the QR printed on the certificate.</p>

                    <div class="verify-scanner">
                        <video id="qr-video" playsinline muted aria-label="QR code camera preview" hidden></video>
                        <div id="scanner-placeholder" class="scanner-placeholder">
                            <span aria-hidden="true">▦</span>
                            <strong>Ready when you are</strong>
                            <small>Your camera will only be used to scan the QR.</small>
                        </div>
                    </div>
                    <button id="start-scanner" class="verify-scan-button" type="button">Open camera to scan</button>
                    <p id="scanner-message" class="scanner-message" role="status" aria-live="polite"></p>
                </section>
            </div>

            <?php if ($result === "Valid"): ?>
                <div class="verify-result verify-result-valid" role="status">
                    <span aria-hidden="true">✓</span> Certificate is valid and active
                </div>
            <?php elseif ($result === "Revoked"): ?>
                <div class="verify-result verify-result-revoked" role="status">
                    <span aria-hidden="true">!</span> This certificate has been revoked
                </div>
            <?php elseif ($result === "Not Found"): ?>
                <div class="verify-result verify-result-not-found" role="status">
                    <span aria-hidden="true">?</span> No certificate matched that number
                </div>
            <?php endif; ?>

            <?php if ($certificate): ?>
                <section class="verify-details" aria-label="Certificate details">
                    <div class="verify-details-heading">
                        <div>
                            <span class="verify-eyebrow">CERTIFICATE RECORD</span>
                            <h2>Verification details</h2>
                        </div>
                        <span class="verify-status-pill<?php echo $result === "Valid" ? " is-valid" : " is-revoked"; ?>">
                            <?php echo htmlspecialchars($result, ENT_QUOTES, "UTF-8"); ?>
                        </span>
                    </div>
                    <dl>
                        <div><dt>Certificate number</dt><dd><?php echo htmlspecialchars((string) $certificate["certificate_id"], ENT_QUOTES, "UTF-8"); ?></dd></div>
                        <div><dt>Recipient</dt><dd><?php echo htmlspecialchars((string) $certificate["student_name"], ENT_QUOTES, "UTF-8"); ?></dd></div>
                        <div><dt>Department</dt><dd><?php echo htmlspecialchars((string) $certificate["department"], ENT_QUOTES, "UTF-8"); ?></dd></div>
                        <div><dt>Certificate type</dt><dd><?php echo htmlspecialchars((string) $certificate["certificate_type"], ENT_QUOTES, "UTF-8"); ?></dd></div>
                        <div><dt>Event</dt><dd><?php echo htmlspecialchars((string) $certificate["event_name"], ENT_QUOTES, "UTF-8"); ?></dd></div>
                        <div><dt>Issue date</dt><dd><?php echo htmlspecialchars((string) $certificate["issue_date"], ENT_QUOTES, "UTF-8"); ?></dd></div>
                    </dl>
                </section>
            <?php endif; ?>
        </section>
    </main>
</div>

<script src="assets/js/jsQR.js"></script>
<script>
(() => {
    const startButton = document.getElementById("start-scanner");
    const video = document.getElementById("qr-video");
    const placeholder = document.getElementById("scanner-placeholder");
    const message = document.getElementById("scanner-message");
    const scanCanvas = document.createElement("canvas");
    const scanContext = scanCanvas.getContext("2d", { willReadFrequently: true });
    let stream;
    let scanning = false;
    let lastScanAt = 0;

    function setMessage(text, isError = false) {
        message.textContent = text;
        message.classList.toggle("is-error", isError);
    }

    function stopCamera() {
        scanning = false;
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
            stream = undefined;
        }
        video.srcObject = null;
        video.hidden = true;
        placeholder.hidden = false;
        startButton.textContent = "Open camera to scan";
    }

    function openScannedVerification(value) {
        let scannedUrl;

        try {
            scannedUrl = new URL(value);
        } catch {
            setMessage("This QR code is not a recognized certificate verification link.", true);
            return false;
        }

        const expectedPath = new URL("verify.php", window.location.href).pathname;
        if (scannedUrl.origin !== window.location.origin || scannedUrl.pathname !== expectedPath) {
            setMessage("This QR code does not belong to this certificate verifier.", true);
            return false;
        }

        const scannedId = scannedUrl.searchParams.get("certificate_id");
        if (!scannedId) {
            setMessage("The QR code does not contain a certificate number.", true);
            return false;
        }

        stopCamera();
        window.location.assign(scannedUrl.href);
        return true;
    }

    async function scanFrame(detector) {
        if (!scanning) {
            return;
        }

        try {
            if (detector) {
                const codes = await detector.detect(video);
                if (codes.length > 0 && openScannedVerification(codes[0].rawValue)) {
                    return;
                }
            } else if (
                video.readyState >= HTMLMediaElement.HAVE_CURRENT_DATA
                && video.videoWidth > 0
                && performance.now() - lastScanAt >= 140
            ) {
                lastScanAt = performance.now();
                const scale = Math.min(1, 640 / video.videoWidth);
                scanCanvas.width = Math.round(video.videoWidth * scale);
                scanCanvas.height = Math.round(video.videoHeight * scale);
                scanContext.drawImage(video, 0, 0, scanCanvas.width, scanCanvas.height);
                const image = scanContext.getImageData(0, 0, scanCanvas.width, scanCanvas.height);
                const code = window.jsQR(image.data, image.width, image.height, {
                    inversionAttempts: "dontInvert"
                });

                if (code && openScannedVerification(code.data)) {
                    return;
                }
            }
        } catch {
            stopCamera();
            setMessage("The QR code could not be read. Try again or enter the certificate number.", true);
            return;
        }

        requestAnimationFrame(() => scanFrame(detector));
    }

    startButton.addEventListener("click", async () => {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            setMessage("Camera access requires HTTPS or localhost. Enter the certificate number instead.", true);
            return;
        }

        try {
            const detector = "BarcodeDetector" in window
                ? new BarcodeDetector({ formats: ["qr_code"] })
                : null;
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: { ideal: "environment" } },
                audio: false
            });
            video.srcObject = stream;
            video.hidden = false;
            placeholder.hidden = true;
            await video.play();
            scanning = true;
            startButton.textContent = "Camera is scanning…";
            setMessage("Point your camera at the certificate QR code.");
            scanFrame(detector);
        } catch (error) {
            stopCamera();
            const reason = error.name === "NotAllowedError"
                ? "Camera permission was denied."
                : "Could not start the camera.";
            setMessage(reason + " Enter the certificate number instead.", true);
        }
    });

    window.addEventListener("pagehide", stopCamera);
})();
</script>
</body>
</html>
