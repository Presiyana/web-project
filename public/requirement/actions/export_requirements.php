<?php
require_once __DIR__ . '/../../../app/services/RequirementService.php';

$requirementService = RequirementService::getInstance();
$layerFilter = $_GET['layer'] ?? null;


if ($layerFilter) {
    $requirements = $requirementService->getRequirementsByLayer($layerFilter);
} else {
    $requirements = $requirementService->getAllRequirements();
}

$filename = 'requirements_export_' . ($layerFilter ? $layerFilter . '_' : '') . date('Y-m-d') . '.csv';
// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Open output stream
$output = fopen('php://output', 'w');

// Add CSV headers
fputcsv($output, ['ID', 'Title', 'Description', 'Hashtags', 'Priority', 'Layer', 'Created At']);

// Add data rows
foreach ($requirements as $requirement) {
    fputcsv($output, [
        $requirement['id'],
        $requirement['title'],
        $requirement['description'],
        $requirement['hashtags'],
        $requirement['priority'],
        $requirement['layer'],
        $requirement['created_at']
    ]);
}

// Close output stream
fclose($output);
exit;