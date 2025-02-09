<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../app/services/TaskService.php';

$authUser = $_SESSION['auth_user'];

if (!isset($authUser) || $authUser['user_group'] !== 'teacher') {
    header('Location: ../index.php');
    die();
}

$userName = $authUser['username'];
$title = $_POST['title'];
$user_group = $_POST['user_group'];

if (empty($title) || empty($user_group)) {
    die();
}

$taskService = TaskService::getInstance();
try {
    $taskId = $taskService->addTask($title, $user_group, $authUser['id']);
} catch (Exception $e) {
    header('Location: ../add.php?message=' . $translations['error_adding_task'] ?? 'Error adding task');
    die();
}

header('Location: ../index.php');