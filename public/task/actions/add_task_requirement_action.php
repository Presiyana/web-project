<?php

require_once __DIR__ . '/../../../app/services/TaskService.php';

$id = $_POST['id'];
$requirement = $_POST['requirement'];

if (empty($id) || empty($requirement)) {
    die();
}

$taskService = TaskService::getInstance();
$taskService->addTaskRequirement($id, $requirement);

header('Location: ../details.php?id=' . $id);