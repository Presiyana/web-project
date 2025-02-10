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

$isOnRequirementsPage = strpos($_SERVER['REQUEST_URI'], '/requirement/');
$isOnTasksPage = strpos($_SERVER['REQUEST_URI'], '/task/');

?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requirements Portal</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/header.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/form.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/chart.css">

    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>assets/images/favicon.ico">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dela+Gothic+One&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
</head>

<body>
    <header>
        <div id="brand">
            <img src="<?= BASE_URL ?>assets/images/logo-small.png" alt="logo">
        </div>
        <nav>

            <a class="logged-in<?= $isOnRequirementsPage ? ' active' : '' ?>" style="<?= $loggedInElementStyle ?>"
                href="<?= BASE_URL ?>requirement<?= $requirementsFilter ?>">
                <?= $translations['requirements'] ?? 'Requirements' ?>
            </a>
            <a class="logged-in<?= $isOnTasksPage ? ' active' : '' ?>" style="<?= $loggedInElementStyle ?>" href="<?= BASE_URL ?>task">
                <?= $translations['tasks'] ?? 'Tasks' ?>
            </a>


            <div class="user-menu logged-in">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="#3b3b3b" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                <div class="menu">
                    <div>
                        <span id="hello-user"><?= $translations['hello'] ?? 'Hello' ?>, <?= $userName ?></span>
                    </div>
                    <div>
                        <a href="<?= BASE_URL ?>auth/logout.php"><?= $translations['logout'] ?? 'Logout' ?></a>
                    </div>
                    <div>
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
                    </div>
                </div>
            </div>

            <!-- <div class="logged-in" style="<?= $loggedInElementStyle ?>" id="userDetails">
                <span id="hello-user"><?= $translations['hello'] ?? 'Hello' ?>, <?= $userName ?></span>
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
            </form> -->
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
