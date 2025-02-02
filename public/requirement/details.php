<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/RequirementService.php';
$requirementService = RequirementService::getInstance();
$requirementId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$requirement = $requirementService->getRequirementById($requirementId);
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>

<div class="title-container">
    <h1>Requirement #<?= $requirement['id'] ?></h1>
    <div class="actions">
        <a class="button" href="./edit.php?id=<?= $requirement['id'] ?>">Edit</a>
    </div>
</div>
<div class="content">
    <form class="box" class="requirement-form" action="actions/edit_requirement_action.php" method="post">
        <input type="hidden" name="id" value="<?= $requirement['id'] ?>">

        <label for="title">Title</label>
        <input disabled type="text" id="title" name="title" required value="<?= $requirement['title'] ?>">

        <label for="description">Description:</label>
        <input disabled type="text" id="description" name="description" required
            value="<?= $requirement['description'] ?>">

        <label for="priority">Priority:</label>
        <select disabled name="priority" id="priority">
            <option value="high" <?= ($requirement['priority'] === "high") ? "selected" : ""; ?>>High</option>
            <option value="medium" <?= ($requirement['priority'] === "medium") ? "selected" : ""; ?>>Medium</option>
            <option value="low" <?= ($requirement['priority'] === "low") ? "selected" : ""; ?>>Low</option>
        </select>

        <label for="layer">Layer:</label>
        <select disabled name="layer" id="layer">
            <option value="client" <?= ($requirement['layer'] === "client") ? "selected" : ""; ?>>Client</option>
            <option value="routing" <?= ($requirement['layer'] === "routing") ? "selected" : ""; ?>>Routing</option>
            <option value="business" <?= ($requirement['layer'] === "business") ? "selected" : ""; ?>>Business</option>
            <option value="db" <?= ($requirement['layer'] === "db") ? "selected" : ""; ?>>DB</option>
            <option value="test" <?= ($requirement['layer'] === "test") ? "selected" : ""; ?>>Test</option>
        </select>

        <label for="hashtags">Hashtags:</label>
        <input disabled type="text" id="hashtags" name="hashtags" required value="<?= $requirement['hashtags'] ?>">

        <label for="isNonFunctional">Is Non-Functional?</label>
        <input disabled type="checkbox" id="isNonFunctional" name="isNonFunctional" value="1"
            <?= $requirement['isNonFunctional'] ? 'checked' : ''; ?>>

        <div id="nonFunctionalFields" style="display: <?= $requirement['isNonFunctional'] ? 'block' : 'none'; ?>;">
            <label for="indicator_name">Indicator Name:</label>
            <input disabled type="text" id="indicator_name" name="indicator_name"
                value="<?= $requirement['indicator_name'] ?>">

            <label for="unit">Unit:</label>
            <input disabled type="text" id="unit" name="unit" value="<?= $requirement['unit'] ?>">

            <label for="value">Value:</label>
            <input disabled type="number" step="0.01" id="value" name="value" value="<?= $requirement['value'] ?>">

            <label for="indicator_description">Indicator Description:</label>
            <textarea id="indicator_description"
                name="indicator_description"><?= $requirement['indicator_description'] ?></textarea>
        </div>
    </form>
    <br>

</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>