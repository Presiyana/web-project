<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../app/config/config.php';


if (!isset($_SESSION['auth_user'])) {
    $currentRoute = basename($_SERVER['PHP_SELF']);

    if (!in_array($currentRoute, ['login.php', 'register.php'])) {
        header("Location: /login.php");
        exit;
    }
} else {
    $authUser = $_SESSION['auth_user'];
    $userName = $authUser['username'] ?? "User";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Project</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/header.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/form.css">
</head>

<body>
    <header>
        <h3 id="page-title">Requirements portal</h3>
        <nav>
            <a class="logged-out" href="<?= BASE_URL ?>auth/login">Login</a>
            <a class="logged-out" href="<?= BASE_URL ?>auth/register">Register</a>

            <a class="logged-in" href="<?= BASE_URL ?>requirement">Requirements</a>
            <a class="logged-in" href="<?= BASE_URL ?>requirement/add">Add Requirement</a>

            <div class="logged-in" id="userDetails">
                <span id="hello-user"></span>
                <a href="<?= BASE_URL ?>auth/logout">Logout</a>
            </div>
        </nav>
    </header>

    <script type="text/javascript">
        const BASE_URL = "<?= BASE_URL; ?>";

        const hasAuthUser = Boolean(<?= $hasAuthUser; ?>);
        const currentRoute = window.location.href.replace(BASE_URL, '');

        if (hasAuthUser) {
            document.querySelectorAll('.logged-in').forEach(el => el.style.display = 'block');
            document.querySelectorAll('.logged-out').forEach(el => el.style.display = 'none');

            const helloUserTextElement = document.getElementById('hello-user');
            helloUserTextElement.innerHTML = 'Hello <?= $authUser['username'] ?>';

            if (!currentRoute.startsWith('requirement')) {
                window.location.href = `${BASE_URL}requirement`;
            }
        } else {
            document.querySelectorAll('.logged-out').forEach(el => el.style.display = 'block');
            document.querySelectorAll('.logged-in').forEach(el => el.style.display = 'none');

            if (!currentRoute.startsWith('auth/')) {
                window.location.href = `${BASE_URL}auth/login`;
            }
        }
    </script>

    <main>