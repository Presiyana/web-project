<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../app/services/TaskService.php';

$id = $_POST['id'];
$title = $_POST['title'];
$user_group = $_POST['user_group'];

if (empty($title) || empty($user_group)) {
    die();
}

$taskService = TaskService::getInstance();
$taskService->editTask($id, $title, $user_group);

header('Location: ../details.php?id='.$id);