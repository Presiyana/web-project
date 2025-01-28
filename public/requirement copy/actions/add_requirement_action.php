<?php

require_once __DIR__ . '/../../../app/services/RequirementService.php';

$title = $_POST['title'];
$description = $_POST['description'];
$priority = $_POST['priority'];
$hashtags = $_POST['hashtags'];
$layer = $_POST['layer'];

if (empty($title) || empty($description) || empty($hashtags)) {
    die();
}

$requirementService = RequirementService::getInstance();
$requirementService->addRequirement(
    $title,
    $description,
    $hashtags,
    $priority,
    $layer,
);

header('Location: ../index.php');