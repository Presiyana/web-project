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

if ($isNonFunctional) {
    if (empty($_POST['indicator_name']) || empty($_POST['unit']) || empty($_POST['value']) || empty($_POST['indicator_description'])) {
        die();
    }
}


$indicator_name = $_POST['indicator_name'] ?? null;
$unit = $_POST['unit'] ?? null;
$value = $_POST['value'] ?? null;
$indicator_description = $_POST['indicator_description'] ?? null;

$requirementService = RequirementService::getInstance();
$requirementService->editRequirementById(
    $id,
    $title,
    $description,
    $hashtags,
    $priority,
    $layer,
    $isNonFunctional
);

if(!$isNonFunctional)
{
    $indicator_name = 'N/A';
    $unit =  'N/A';
    $value = 0;
    $indicator_description = 'N/A';
}

$requirementService->editIndicatorsForRequirement(
    $id,
    $indicator_name,
    $unit,
    $value,
    $indicator_description
);

header('Location: ../index.php');