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
$update_result = $certificates->updateOne(
    ["_id" => $id],
    ['$set' => ["status" => "Revoked"]]
);

if ($update_result->getMatchedCount() === 1) {
    $_SESSION["certificate_message"] = "Certificate revoked. It remains in the system but is no longer valid.";
} else {
    $_SESSION["certificate_message"] = "Certificate not found; no changes were made.";
}

header("Location: certificates.php");
exit;
?>
