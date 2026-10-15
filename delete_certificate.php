
<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: certificates.php");
    exit;
}

if (
    !isset($_POST["csrf_token"], $_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])
) {
    http_response_code(403);
    exit("Invalid request token. Refresh the certificate list and try again.");
}

require_once "config.php";

if (
    !isset($_POST["id"]) ||
    !is_string($_POST["id"]) ||
    !preg_match('/^[a-fA-F0-9]{24}$/D', $_POST["id"])
) {
    $_SESSION["certificate_message"] = "The certificate ID is invalid.";
    header("Location: certificates.php");
    exit;
}

$id = new MongoDB\BSON\ObjectId($_POST["id"]);

$delete_result = $certificates->deleteOne(["_id" => $id]);

if ($delete_result->getDeletedCount() === 1) {
    $_SESSION["certificate_message"] = "Certificate permanently deleted.";
} else {
    $_SESSION["certificate_message"] = "Certificate not found; nothing was deleted.";
}

header("Location: certificates.php");
exit;
?>
