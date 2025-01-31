<?php

require_once __DIR__ . '/../../../app/services/RequirementService.php';


$title = $_POST['title'];
$description = $_POST['description'];
$priority = $_POST['priority'];
$hashtags = $_POST['hashtags'];
$layer = $_POST['layer'];
$isNonFunctional = isset($_POST['isNonFunctional']) ? 1 : 0;


if (empty($title) || empty($description) || empty($hashtags)) {
    die();
}

if ($isNonFunctional) {
    if (empty($_POST['indicator_name']) || empty($_POST['unit']) || empty($_POST['value']) || empty($_POST['indicator_description'])) {
        die();
    }
}


$indicator_name = $isNonFunctional ? ($_POST['indicator_name'] ?? 'N/A') : null;
$unit = $isNonFunctional ? ($_POST['unit'] ?? 'N/A') : null;
// $value = $isNonFunctional ? ($_POST['value'] ?? 0) : null;
$value = $isNonFunctional ? ($_POST['value'] ?? 'N/A') : null;
$indicator_description = $isNonFunctional ? ($_POST['indicator_description'] ?? 'N/A') : null;


$requirementService = RequirementService::getInstance();
$requirementId = $requirementService->addRequirement($title, $description, $hashtags, $priority, $layer, $isNonFunctional, [
    'indicator_name' => $indicator_name,
    'unit' => $unit,
    'value' => $value,
    'indicator_description' => $indicator_description
]);


header('Location: ../index.php');