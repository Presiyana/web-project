<?php

require_once __DIR__ . '/../../../app/services/RequirementService.php';


$title = $_POST['title'];
$description = $_POST['description'];
$priority = $_POST['priority'];
$hashtags = $_POST['hashtags'];
$layer = $_POST['layer'];
$isNonFunctional = isset($_POST['isNonFunctional']) ? 1 : 0;

if (empty($title) || empty($description) || empty($hashtags)) {
    header('Location: ../add.php?message=' . $translations['missing_required_fields']);
    die();
}

$requirementService = RequirementService::getInstance();
try {
    $requirementId = $requirementService->addRequirement(
        $title,
        $description,
        $hashtags,
        $priority,
        $layer,
        $isNonFunctional,
    );
} catch (Exception $e) {
    header('Location: ../add.php?message=' . $translations['error_adding_requirement']);
    die();
}

header('Location: ../index.php');