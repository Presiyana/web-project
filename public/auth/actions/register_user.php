<?php

session_start();
require_once __DIR__ . '/../../../app/services/UserService.php';

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$user_group = $_POST['user_group'];

if (empty($username) || empty($password) || empty($email)) {
    die();
}

$userService = UserService::getInstance();
$userService->register(
    $username,
    $email,
    $password,
    $user_group
);

header('Location: ../login');