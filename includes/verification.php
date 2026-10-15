<?php

function buildCertificateVerificationUrl(string $certificateId): string
{
    $configuredBaseUrl = getenv("CERTIFICATE_VERIFY_BASE_URL");

    if ($configuredBaseUrl !== false && trim($configuredBaseUrl) !== "") {
        $baseUrl = rtrim(trim($configuredBaseUrl), "/");
        $parsedUrl = parse_url($baseUrl);

        if (
            filter_var($baseUrl, FILTER_VALIDATE_URL) === false
            || !in_array($parsedUrl["scheme"] ?? "", ["http", "https"], true)
            || empty($parsedUrl["host"])
            || isset($parsedUrl["user"])
            || isset($parsedUrl["pass"])
            || isset($parsedUrl["query"])
            || isset($parsedUrl["fragment"])
        ) {
            throw new RuntimeException("CERTIFICATE_VERIFY_BASE_URL must be a valid HTTP or HTTPS base URL without credentials, query, or fragment.");
        }
    } else {
        $host = $_SERVER["SERVER_NAME"] ?? "";

        if (!preg_match('/\A(?:[A-Za-z0-9.-]+|\[[0-9A-Fa-f:]+\])\z/', $host)) {
            throw new RuntimeException("The server name is invalid. Configure CERTIFICATE_VERIFY_BASE_URL with the public verification address.");
        }

        $isHttps = isset($_SERVER["HTTPS"]) && strtolower((string) $_SERVER["HTTPS"]) !== "off";
        $scheme = $isHttps ? "https" : "http";
        $port = (int) ($_SERVER["SERVER_PORT"] ?? ($isHttps ? 443 : 80));
        $defaultPort = $isHttps ? 443 : 80;
        $portSuffix = $port !== $defaultPort ? ":" . $port : "";
        $scriptDirectory = str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"] ?? "/"));
        $scriptDirectory = $scriptDirectory === "/" || $scriptDirectory === "." ? "" : "/" . trim($scriptDirectory, "/");

        $baseUrl = $scheme . "://" . $host . $portSuffix . $scriptDirectory;
    }

    return $baseUrl . "/verify.php?certificate_id=" . rawurlencode($certificateId);
}
