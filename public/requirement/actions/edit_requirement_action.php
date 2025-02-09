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

} catch (Exception $e) {
    header('Location: ../edit.php' . $search . '&message=' . $e->getMessage());
    die();
}
header('Location: ../details.php' . $search);
