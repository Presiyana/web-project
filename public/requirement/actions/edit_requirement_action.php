<?php
require_once __DIR__ . '/../../../app/services/RequirementService.php';

$id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$priority = $_POST['priority'];
$layer = $_POST['layer'];
$hashtags = $_POST['hashtags'];
$isNonFunctional = isset($_POST['isNonFunctional']) ? 1 : 0;

if (empty($id) || empty($title) || empty($description) || empty($hashtags)) {
    die();
}

// Ако е нефункционално, всички индикаторни полета трябва да са попълнени
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
$requirementService->editRequirementById(
    $id,
    $title,
    $description,
    $hashtags,
    $priority,
    $layer,
    $isNonFunctional,
    [
        'indicator_name' => $indicator_name,
        'unit' => $unit,
        'value' => $value,
        'indicator_description' => $indicator_description
    ]
);


header('Location: ../details.php?id='.$id);
