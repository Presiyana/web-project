<?php

require_once __DIR__ . '/../app/config/config.php';

$currentRoute = basename($_SERVER['PHP_SELF']);
$isOnAuthPage = in_array($currentRoute, ['login.php', 'register.php']);
$hasAuthUser = isset($_SESSION['auth_user']);

if (!$hasAuthUser && !$isOnAuthPage) {
    header("Location: " . BASE_URL . "auth/login");
}

if ($hasAuthUser && $isOnAuthPage) {
    header("Location: " . BASE_URL . "requirement");
}