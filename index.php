<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f8fc">
    <title>Certify | Digital Certificate Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="landing-page portal-page">
    <div class="portal-shell">
        <header class="portal-header">
            <a class="portal-brand" href="index.php" aria-label="Certify home">
                <span class="portal-brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M12 3 20 6v5c0 5-3.4 8.6-8 10-4.6-1.4-8-5-8-10V6l8-3Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                        <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span>certi<span class="portal-brand-accent">fy</span></span>
            </a>

        </header>

        <main class="portal-main">
            <section class="portal-choices" aria-labelledby="portal-choices-title">
                <div class="portal-choices-heading">
                    <h1 id="portal-choices-title">Choose your portal</h1>
                </div>

                <div class="portal-role-grid">
                    <a class="portal-role-card portal-role-admin" href="admin_login.php">
                        <span class="portal-role-top">
                            <span class="portal-role-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M12 3 20 6v5c0 5-3.4 8.6-8 10-4.6-1.4-8-5-8-10V6l8-3Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                                    <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="portal-role-arrow" aria-hidden="true">&rarr;</span>
                        </span>
                        <span class="portal-role-label">FOR YOUR ORGANIZATION</span>
                        <strong>Administrator</strong>
                        <span class="portal-role-description">Create and manage certificates with ease.</span>
                        <span class="portal-role-action">Enter admin portal <span aria-hidden="true">&rarr;</span></span>
                    </a>

                    <a class="portal-role-card portal-role-student" href="student_login.php">
                        <span class="portal-role-top">
                            <span class="portal-role-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="8" r="3.4" stroke="currentColor" stroke-width="1.7"/>
                                    <path d="M5 20v-1.2a7 7 0 0 1 14 0V20H5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="portal-role-arrow" aria-hidden="true">&rarr;</span>
                        </span>
                        <span class="portal-role-label">YOUR ACHIEVEMENTS</span>
                        <strong>Student</strong>
                        <span class="portal-role-description">Find and share your earned certificates.</span>
                        <span class="portal-role-action">Enter student portal <span aria-hidden="true">&rarr;</span></span>
                    </a>

                    <a class="portal-role-card portal-role-verifier" href="verify.php">
                        <span class="portal-role-top">
                            <span class="portal-role-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <circle cx="10.8" cy="10.8" r="6.3" stroke="currentColor" stroke-width="1.7"/>
                                    <path d="m15.5 15.5 4.2 4.2m-11.5-9 1.8 1.8 3.3-3.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="portal-role-arrow" aria-hidden="true">&rarr;</span>
                        </span>
                        <span class="portal-role-label">QUICK AUTHENTICITY CHECK</span>
                        <strong>Verifier</strong>
                        <span class="portal-role-description">Confirm a certificate is genuine and active.</span>
                        <span class="portal-role-action">Verify a certificate <span aria-hidden="true">&rarr;</span></span>
                    </a>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
