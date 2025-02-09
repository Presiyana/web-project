<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../app/services/UserService.php';
require_once __DIR__ . '/../../../app/config/lang_config.php';

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$user_group = $_POST['user_group'];

if (empty($username) || empty($password) || empty($email)) {
    $message = $translations['missing_required_fields']; // ?? "Missing required fields";
    header('Location: ../register.php?message=' . $message);
    die();
}

$userService = UserService::getInstance();

try {
    
    $userService->register(
        $username,
        $email,
        $password,
        $user_group
    );
} catch (Exception $e) {
    header('Location: ../register.php?message=' . $e->getMessage());
    die();
}

header('Location: ../login.php');