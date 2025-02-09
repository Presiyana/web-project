<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/RequirementService.php';
$requirementService = RequirementService::getInstance();
$requirementId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$id = filter_input(INPUT_GET, 'indicator_id', FILTER_SANITIZE_NUMBER_INT);

$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
$requirementsFilter = $queries['layer'] ? "?layer=" . $queries['layer'] : "";
$search = $requirementsFilter ? $requirementsFilter . "&id=" . $requirementId : "?id=" . $requirementId;

try {
    $requirementIndicator = $requirementService->getRequirementIndicator($id);
} catch (Exception $e) {
    header('Location: ./index.php' . $search . '&message=' . $translations['req_not_found'] ?? "Requirement not found");
    die();
}


?>

<?php require_once __DIR__ . '/../common/header.php'; ?>


<div class="title-container">
    <h1><?= $translations['edit_indicator']; ?><?= $requirementIndicator['indicator_name'] ?></h1>
</div>
<div class="content">
    <form class="box" class="requirement-form" action="actions/edit_requirement_indicator_action.php" method="post">
        <input type="hidden" value="<?= $requirementId ?>" name="requirement_id">
        <input type="hidden" value="<?= $id ?>" name="id">

        <label for="indicator_name"><?= $translations['indicator_name']; ?></label>
        <input type="text" id="indicator_name" name="indicator_name"
            value="<?= $requirementIndicator['indicator_name'] ?>" required>

        <label for="unit"><?= $translations['unit']; ?></label>
        <input type="text" id="unit" name="unit" required value="<?= $requirementIndicator['unit'] ?>">

        <label for="value"><?= $translations['value']; ?></label>
        <input type="number" step="0.01" id="value" name="value" required value="<?= $requirementIndicator['value'] ?>">

        <label for="indicator_description"><?= $translations['indicator_description']; ?></label>
        <textarea id="indicator_description" name="indicator_description"
            required><?= $requirementIndicator['indicator_description'] ?></textarea>

        <button type="submit"><?= $translations['submit']; ?></button>
    </form>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>
