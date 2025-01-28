<?php

require_once __DIR__ . '/../../../app/services/RequirementService.php';

$id = $_GET['id'];

if (empty($id)) {
    die();
}

$requirementService = RequirementService::getInstance();
$requirementService->removeRequirementById($id);

echo json_encode($id);