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
    header('Location: ../add.php?message=' . $translations['missing_required_fields']);
    die();
}

$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
$requirementsFilter = $queries['layer'] ? "?layer=" . $queries['layer'] : "";

// Ако е нефункционално, всички индикаторни полета трябва да са попълнени
$requirementService = RequirementService::getInstance();

$search = $requirementsFilter ? $requirementsFilter . "&id=" . $id : "?id=" . $id;

try {
    $requirementService->editRequirementById(
        $id,
        $title,
        $description,
        $hashtags,
        $priority,
        $layer,
        $isNonFunctional,
    );

    $indicator_name = $_POST['indicator_name'];
    $unit = $_POST['unit'];
    $value = $_POST['value'];
    $indicator_description = $_POST['indicator_description'];

    if (!empty($indicator_name) && !empty($unit) && !empty($value) && !empty($indicator_description)) {
        $requirementService->addIndicatorForRequirement(
            $id,
            $indicator_name,
            $unit,
            $value,
            $indicator_description
        );
    }

} catch (Exception $e) {
    header('Location: ../edit.php' . $search . '&message=' . $e->getMessage());
    die();
}
header('Location: ../details.php' . $search);
