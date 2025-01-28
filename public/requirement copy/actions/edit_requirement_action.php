<?php

require_once __DIR__ . '/../../../app/services/RequirementService.php';

$id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$priority = $_POST['priority'];
$layer = $_POST['layer'];
$hashtags = $_POST['hashtags'];

if (empty($id) || empty($title) || empty($description) || empty($hashtags)) {
    die();
}

$requirementService = RequirementService::getInstance();
$requirementService->editRequirementById(
    $id,
    $title,
    $description,
    $hashtags,
    $priority,
    $layer
);

header('Location: ../index.php');