<?php
session_start();

require_once __DIR__ . '/../../app/services/RequirementService.php';
$requirementService = RequirementService::getInstance();
$requirementId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$requirement = $requirementService->getRequirementById($requirementId);
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>

<div class="title">
    <h1>Requirement Edit</h1>
</div>

<div class="content">

    <form class="requirement-form" action="../actions/edit_requirement_action.php" method="post">
        <input type="hidden" name="id" value="<?= $requirement['id'] ?>">

        <label for="title">Title</label>
        <input type="text" id="title" name="title" required value="<?= $requirement['title'] ?>">

        <label for="description">Description:</label>
        <input type="text" id="description" name="description" required value="<?= $requirement['description'] ?>">

        <label for="priority">Priority:</label>
        <select name="priority" id="priority">
            <option value="high" <?= ($requirement['priority'] === "high") ? "selected" : ""; ?>>High</option>
            <option value="medium" <?= ($requirement['priority'] === "medium") ? "selected" : ""; ?>>Medium</option>
            <option value="low" <?= ($requirement['priority'] === "low") ? "selected" : ""; ?>>Low</option>
        </select>

        <label for="layer">Layer:</label>
        <select name="layer" id="layer">
            <option value="client" <?= ($requirement['layer'] === "client") ? "selected" : ""; ?>>Client</option>
            <option value="routing" <?= ($requirement['layer'] === "routing") ? "selected" : ""; ?>>Routing</option>
            <option value="business" <?= ($requirement['layer'] === "business") ? "selected" : ""; ?>>Business</option>
            <option value="db" <?= ($requirement['layer'] === "db") ? "selected" : ""; ?>>DB</option>
            <option value="test" <?= ($requirement['layer'] === "test") ? "selected" : ""; ?>>Test</option>
        </select>

        <label for="hashtags">Hashtags:</label>
        <input type="text" id="hashtags" name="hashtags" required value="<?= $requirement['hashtags'] ?>">

        <br>

        <div class="actions">
            <button class="delete" id="triggerButton" onclick="clickHandler(<?= $requirement['id'] ?>)">Delete</button>
            <button type="submit">Submit</button>
        </div>
    </form>
    <br>

</div>

<script>
    function clickHandler(id) {
        console.log({ id });
        fetch('../actions/remove_requirement_action.php?id=' + id, { method: 'DELETE' })
            .then(() => window.location.href = '../index.php')
            .catch(error => console.error('Error:', error));
    }
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>