<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/lang_config.php';

$currentRoute = basename($_SERVER['PHP_SELF']);
$isOnAuthPage = in_array($currentRoute, ['login.php', 'register.php']);

$hasAuthUser = isset($_SESSION['auth_user']);

$idQueryParam = $_GET['id'] ?? null;

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


$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
$requirementsFilter = isset($queries['layer']) ? "?layer=" . $queries['layer'] : "";

?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Project</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/header.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/form.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/chart.css">
</head>

<body>
    <header>
        <h3 id="page-title"><?= $translations['page_title'] ?? 'Requirements Portal' ?></h3>
        <nav>

            <a class="logged-in" style="<?= $loggedInElementStyle ?>"
                href="<?= BASE_URL ?>requirement<?= $requirementsFilter ?>">
                <?= $translations['requirements'] ?? 'Requirements' ?>
            </a>
            <a class="logged-in" style="<?= $loggedInElementStyle ?>" href="<?= BASE_URL ?>task">
                <?= $translations['tasks'] ?? 'Tasks' ?>
            </a>

            <div class="logged-in" style="<?= $loggedInElementStyle ?>" id="userDetails">
                <span id="hello-user"><?= $translations['hello'] ?? 'Hello' ?> <?= $userName ?></span>
                <a href="<?= BASE_URL ?>auth/logout.php"><?= $translations['logout'] ?? 'Logout' ?></a>
            </div>

            <form method="GET" action="" style="display: inline;">
                <?php if ($idQueryParam): ?>
                    <input type="hidden" name="id" value="<?= $idQueryParam ?>">
                <?php endif; ?>
                <select name="lang" onchange="this.form.submit()">
                    <option value="en" <?= ($lang === 'en') ? 'selected' : '' ?>>English</option>
                    <option value="fr" <?= ($lang === 'fr') ? 'selected' : '' ?>>Français</option>
                    <option value="de" <?= ($lang === 'de') ? 'selected' : '' ?>>Deutsch</option>
                    <option value="bul" <?= ($lang === 'bul') ? 'selected' : '' ?>>Български</option>
                </select>
            </form>
        </nav>
    </header>

    <div id="message"></div>

    <script>
        const showMessage = (msg) => {
            const messageContainer = document.getElementById('message');
            if (!msg) {
                messageContainer.style.display = 'none';
                return;
            }
            messageContainer.style.display = 'block';
            messageContainer.innerHTML = decodeURIComponent(msg);

            setTimeout(() => { messageContainer.style.display = 'none'; }, 5_000)
        }

        const checkForMessage = () => {
            const queryParams = document.location.search
                .slice(1).split('&')
                .map(d => d.split('='))
                .map(([key, value]) => ({ key, value }));

            const messageData = queryParams.find(qp => qp.key === 'message');
            if (!messageData) return;
            showMessage(messageData.value)
        }
        checkForMessage();
    </script>

    <main>
