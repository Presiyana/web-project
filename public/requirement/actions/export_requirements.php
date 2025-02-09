<?php
require_once __DIR__ . '/../../../app/services/RequirementService.php';

$requirementService = RequirementService::getInstance();
$layerFilter = $_GET['layer'] ?? null;
$requirementsFilter = $layerFilter ? "?layer=" . $layerFilter : "";

if ($layerFilter) {
    $requirements = $requirementService->getRequirementsByLayer($layerFilter);
} else {
    $requirements = $requirementService->getAllRequirements();
}

if (empty($requirements)) {
    $message = $translations['requirement_export_failed'] ?? "Requirement export failed";
    header('Location: ../index.php' . $requirementsFilter . '&message=' . $message);
    die();
}

$filename = 'requirements_export_' . ($layerFilter ? $layerFilter . '_' : '') . date('Y-m-d') . '.csv';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

fputcsv($output, ['ID', 'Title', 'Description', 'Hashtags', 'Priority', 'Layer', 'Is Non-Functional', 'Created At', 'Indicators']);


$currentRequirementId = null;
foreach ($requirements as $row) {

    if (!array_key_exists('indicator_name', $row)) {
        error_log("Warning: 'indicator_name' missing for requirement ID " . $row['id']);
    }

    $isNonFunctional = $row['isNonFunctional'] ?? false;

    $indicators = array();
    if ($isNonFunctional) {
        $indicators = $requirementService->getRequirementIndicators($row['id']);
    }

    fputcsv($output, [
        $row['id'],
        $row['title'],
        $row['description'],
        $row['hashtags'],
        $row['priority'],
        $row['layer'],
        $isNonFunctional ? 'Yes' : 'No',
        $row['created_at'],
        json_encode($indicators)
    ]);
}

fclose($output);
exit;