<?php

require_once __DIR__ . '/../../../app/services/TaskService.php';

$id = $_POST['id'];
$requirement = $_POST['requirement'];

if (empty($id) || empty($requirement)) {
    die();
}

$taskService = TaskService::getInstance();

try {
    $taskService->addTaskRequirement(
        $id,
        $requirement
    );
} catch (Exception $e) {
    header('Location: ../edit.php?message=' . $e->getMessage());
    die();
}

header('Location: ../details.php?id=' . $id);