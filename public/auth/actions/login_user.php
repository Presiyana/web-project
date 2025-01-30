<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../app/services/UserService.php';

$email = $_POST['email'];
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    die();
}

$userService = UserService::getInstance();
$authUser = $userService->login(
    $email,
    $password,
);

if (empty($authUser)) {
    header('Location: ../login.php');
    exit;
}

$_SESSION['auth_user'] = $authUser;

header('Location: ../../requirement');