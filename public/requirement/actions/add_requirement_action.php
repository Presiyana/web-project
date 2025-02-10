<?php

require_once __DIR__ . '/../../../app/services/RequirementService.php';


$title = $_POST['title'];
$description = $_POST['description'];
$priority = $_POST['priority'];
$hashtags = $_POST['hashtags'];
$layer = $_POST['layer'];
$isNonFunctional = isset($_POST['isNonFunctional']) ? 1 : 0;

if (
    empty($title) || empty($description) || empty($hashtags) ||
    $isNonFunctional && (empty($_POST['indicator_name']) || empty($_POST['unit']) || empty($_POST['value']) || empty($_POST['indicator_description']))
) {
    header('Location: ../add.php?message=' . $translations['missing_required_fields']);
    die();
}

$indicators = [];
if ($isNonFunctional) {
    $indicator_name = $_POST['indicator_name'];
    $unit = $_POST['unit'];
    $value = $_POST['value'];
    $indicator_description = $_POST['indicator_description'];
    array_push(
        $indicators,
        [
            'indicator_name' => $indicator_name,
            'unit' => $unit,
            'value' => $value,
            'indicator_description' => $indicator_description
        ]
    );
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
        $indicators
    );
} catch (Exception $e) {
    header('Location: ../add.php?message=' . $translations['error_adding_requirement']);
    die();
}

header('Location: ../index.php');