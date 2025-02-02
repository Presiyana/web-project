<?php

require_once __DIR__ . '/../../../app/services/TaskService.php';

$task_id = $_GET['task_id'];
$requirement_id = $_GET['requirement_id'];


if (empty($task_id) || empty($requirement_id)) {
    die();
}

$taskService = TaskService::getInstance();
$taskService->toggleTaskRequirementCompletion($task_id, $requirement_id);

header('Location: ../details.php?id=' . $task_id);
