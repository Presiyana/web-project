<?php
require_once __DIR__ . '/../../../app/services/RequirementService.php';

$requirementService = RequirementService::getInstance();

$layerFilter = $_GET['layer'] ?? null;
$priorityFilter = $_GET['priority'] ?? null;
$nonFunctionalFilter = $_GET['non_functional'] ?? null;

try {
    $requirements = $requirementService->getRequirementsByFilters($layerFilter, $priorityFilter, $nonFunctionalFilter);
} catch (Exception $e) {
    $requirementsError = $e->getMessage();
}

$params = [];
if (!empty($_GET['layer'])) {
    $params['layer'] = $_GET['layer'];
}
if (!empty($_GET['priority'])) {
    $params['priority'] = $_GET['priority'];
}
if (!empty($_GET['non_functional'])) {
    $params['non_functional'] = $_GET['non_functional'];
}

$filename = 'requirements_export_' . date('Y-m-d') . '.csv';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

fputcsv($output, ['ID', 'Title', 'Description', 'Hashtags', 'Priority', 'Layer', 'Is Non-Functional', 'Created At', 'Indicators']);

foreach ($requirements as $row) {
    $isNonFunctional = isset($row['isNonFunctional']) ? (int) $row['isNonFunctional'] : 0; 

    $indicators = [];
    if ($isNonFunctional === 1) {
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
        json_encode($indicators, JSON_UNESCAPED_UNICODE)
    ]);
}

fclose($output);
exit;
