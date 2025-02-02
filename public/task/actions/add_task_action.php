<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../app/services/TaskService.php';

$authUser = $_SESSION['auth_user'];

if (!isset($authUser)) {
    die();
}

$userName = $authUser['username'];
$title = $_POST['title'];
$user_group = $_POST['user_group'];

if (empty($title) || empty($user_group)) {
    die();
}

$taskService = TaskService::getInstance();
$taskId = $taskService->addTask($title, $user_group, $authUser['id']);

header('Location: ../index.php');