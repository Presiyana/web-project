<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../app/config/config.php';

$currentRoute = basename($_SERVER['PHP_SELF']);
$isOnAuthPage = in_array($currentRoute, ['login.php', 'register.php']);

$hasAuthUser = isset($_SESSION['auth_user']);

$userName = "";
$loggedInElementStyle = "";
$loggedOutElementStyle = "";

if (!$hasAuthUser) {
    $loggedInElementStyle = "display: none";
    $loggedOutElementStyle = "display: block";
    if (!$isOnAuthPage) {
        header("Location: " . BASE_URL . "auth/login.php");
        exit;
    }
} else {
    $userName = $_SESSION['auth_user']['username'];
    $loggedInElementStyle = "display: block";
    $loggedOutElementStyle = "display: none";
    if ($isOnAuthPage) {
        header("Location: " . BASE_URL . "requirement");
        exit;
    }
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

            <a class="logged-in" style="<?= $loggedInElementStyle ?>" href="<?= BASE_URL ?>requirement">
                Requirements
            </a>
            <a class="logged-in" style="<?= $loggedInElementStyle ?>" href="<?= BASE_URL ?>task">
                Tasks
            </a>

            <div class="logged-in" style="<?= $loggedInElementStyle ?>" id="userDetails">
                <span id="hello-user">Hello <?= $userName ?></span>
                <a href="<?= BASE_URL ?>auth/logout.php">Logout</a>
            </div>
        </nav>
    </header>

    <div id="message"></div>

    <script>
        const messageContainer = document.getElementById('message');
        messageContainer.style.display = 'none';

        const queryParams = document.location.search
            .slice(1).split('&')
            .map(d => d.split('='))
            .map(([key, value]) => ({ key, value }));

        const messageData = queryParams.find(qp => qp.key === 'message');
        if (messageData) {
            messageContainer.style.display = 'block';
            messageContainer.innerHTML = decodeURIComponent(messageData.value);

            setTimeout(() => { messageContainer.style.display = 'none'; }, 5_000)
        }
    </script>

    <main>