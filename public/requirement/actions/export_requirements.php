<?php
require_once __DIR__ . '/../../../app/services/RequirementService.php';

$requirementService = RequirementService::getInstance();
$layerFilter = $_GET['layer'] ?? null;


if ($layerFilter) {
    $requirements = $requirementService->getRequirementsByLayer($layerFilter);
} else {
    $requirements = $requirementService->getAllRequirements();
}

if (empty($requirements)) {
    die("No requirements found. Check database query.");
}

$filename = 'requirements_export_' . ($layerFilter ? $layerFilter . '_' : '') . date('Y-m-d') . '.csv';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

fputcsv($output, ['ID', 'Title', 'Description', 'Hashtags', 'Priority', 'Layer', 'Is Non-Functional', 'Indicator Name', 'Unit', 'Value', 'Indicator Description', 'Created At']);


$currentRequirementId = null;
foreach ($requirements as $row) {

    if (!array_key_exists('indicator_name', $row)) {
        error_log("Warning: 'indicator_name' missing for requirement ID " . $row['id']);
    }

    $isNonFunctional = $row['isNonFunctional'] ?? false;
    $indicatorName = $row['indicator_name'] ?? 'N/A';
    $indicatorUnit = $row['unit'] ?? 'N/A';
    $indicatorValue = $row['value'] ?? 'N/A';
    $indicatorDescription = $row['indicator_description'] ?? 'N/A';

    fputcsv($output, [
        $row['id'],
        $row['title'],
        $row['description'],
        $row['hashtags'],
        $row['priority'],
        $row['layer'],
        $isNonFunctional ? 'Yes' : 'No', 
        $isNonFunctional ? $indicatorName : 'N/A',
        $isNonFunctional ? $indicatorUnit : 'N/A',
        $isNonFunctional ? $indicatorValue : 'N/A',
        $isNonFunctional ? $indicatorDescription : 'N/A',
        $row['created_at'] 
    ]); 
}

fclose($output);
exit;