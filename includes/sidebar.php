<?php
$sidebarRole = $sidebarRole ?? "public";
$activePage = $activePage ?? "";

$navigation = [
    "admin" => [
        ["key" => "dashboard", "label" => "Overview", "description" => "Admin dashboard", "href" => "admin_dashboard.php", "icon" => "◈"],
        ["key" => "add", "label" => "Add certificate", "description" => "Issue a new certificate", "href" => "add_certificate.php", "icon" => "+"],
        ["key" => "certificates", "label" => "All certificates", "description" => "Manage issued records", "href" => "certificates.php", "icon" => "▤"],
        ["key" => "verify", "label" => "Verify", "description" => "Check authenticity", "href" => "verify.php", "icon" => "⌕"],
        ["key" => "logout", "label" => "Sign out", "description" => "End admin session", "href" => "admin_logout.php", "icon" => "↗"],
    ],
    "student" => [
        ["key" => "dashboard", "label" => "My dashboard", "description" => "Your certificates", "href" => "student_dashboard.php", "icon" => "◈"],
        ["key" => "verify", "label" => "Verify certificate", "description" => "Check authenticity", "href" => "verify.php", "icon" => "⌕"],
        ["key" => "logout", "label" => "Sign out", "description" => "End student session", "href" => "student_logout.php", "icon" => "↗"],
    ],
    "public" => [
        ["key" => "home", "label" => "Home", "description" => "Choose a portal", "href" => "index.php", "icon" => "⌂"],
        ["key" => "verify", "label" => "Verify certificate", "description" => "Check authenticity", "href" => "verify.php", "icon" => "⌕"],
        ["key" => "admin", "label" => "Administrator", "description" => "Manage certificates", "href" => "admin_login.php", "icon" => "♙"],
        ["key" => "student", "label" => "Student portal", "description" => "View achievements", "href" => "student_login.php", "icon" => "♧"],
    ],
];

$items = $navigation[$sidebarRole] ?? $navigation["public"];
$portalLabel = $sidebarRole === "admin" ? "ADMIN PORTAL" : ($sidebarRole === "student" ? "STUDENT PORTAL" : "CERTIFICATE PORTAL");
?>
<aside class="app-sidebar">
    <a class="sidebar-brand" href="<?php echo $sidebarRole === "admin" ? "admin_dashboard.php" : ($sidebarRole === "student" ? "student_dashboard.php" : "index.php"); ?>">
        <span class="sidebar-brand-mark" aria-hidden="true">C</span>
        <span>Certi<span>fy</span></span>
    </a>

    <div class="sidebar-caption"><?php echo htmlspecialchars($portalLabel); ?></div>

    <nav class="sidebar-nav" aria-label="Main navigation">
        <?php foreach ($items as $item): ?>
            <a
                class="sidebar-link<?php echo $activePage === $item["key"] ? " is-active" : ""; ?><?php echo $item["key"] === "logout" ? " sidebar-link-logout" : ""; ?>"
                href="<?php echo htmlspecialchars($item["href"]); ?>"
                <?php echo $activePage === $item["key"] ? 'aria-current="page"' : ""; ?>
            >
                <span class="sidebar-icon" aria-hidden="true"><?php echo htmlspecialchars($item["icon"]); ?></span>
                <span class="sidebar-link-copy">
                    <strong><?php echo htmlspecialchars($item["label"]); ?></strong>
                    <small><?php echo htmlspecialchars($item["description"]); ?></small>
                </span>
                <?php if ($activePage === $item["key"]): ?>
                    <span class="sidebar-current" aria-hidden="true"></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </nav>

</aside>
