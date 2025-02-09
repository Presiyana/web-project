<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/RequirementService.php';
$requirementService = RequirementService::getInstance();
$requirementId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

try {
    $requirement = $requirementService->getRequirementById($requirementId);
    $requirementIndicators = $requirementService->getRequirementIndicators($requirementId);
} catch (Exception $e) {
    header('Location: ./index.php?message=' . $translations['req_not_found'] ?? "Requirement not found");
    die();
}

$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
$requirementsFilter = $queries['layer'] ? "?layer=" . $queries['layer'] : "";

?>

<?php require_once __DIR__ . '/../common/header.php'; ?>


<div class="title-container">
    <h1><?= $translations['add_requirement_indicator_to_req']; ?><?= $requirement['title'] ?></h1>
</div>
<div class="content">
    <form class="box" class="requirement-form" action="actions/add_requirement_indicator_action.php" method="post">
        <input type="hidden" value="<?= $requirementId ?>" name="id">

        <label for="indicator_name"><?= $translations['indicator_name']; ?></label>
        <input type="text" id="indicator_name" name="indicator_name" required>

        <label for="unit"><?= $translations['unit']; ?></label>
        <input type="text" id="unit" name="unit" required>

        <label for="value"><?= $translations['value']; ?></label>
        <input type="number" step="0.01" id="value" name="value" required>

        <label for="indicator_description"><?= $translations['indicator_description']; ?></label>
        <textarea id="indicator_description" name="indicator_description" required></textarea>

        <button type="submit"><?= $translations['submit']; ?></button>
    </form>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>
