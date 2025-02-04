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
    <h1><?= $translations['edit_req']; ?><?= $requirement['id'] ?></h1>
</div>
<div class="content">
    <form class="box" class="requirement-form" action="actions/edit_requirement_action.php" method="post">
        <input type="hidden" name="id" value="<?= $requirement['id'] ?>">

        <label for="title"><?= $translations['title']; ?></label>
        <input type="text" id="title" name="title" required value="<?= $requirement['title'] ?>">

        <label for="description"><?= $translations['description']; ?></label>
        <input type="text" id="description" name="description" required value="<?= $requirement['description'] ?>">

        <label for="priority"><?= $translations['priority']; ?></label>
        <select name="priority" id="priority">
            <option value="high" <?= ($requirement['priority'] === "high") ? "selected" : ""; ?>><?= $translations['high']; ?></option>
            <option value="medium" <?= ($requirement['priority'] === "medium") ? "selected" : ""; ?>><?= $translations['medium']; ?></option>
            <option value="low" <?= ($requirement['priority'] === "low") ? "selected" : ""; ?>><?= $translations['low']; ?></option>
        </select>

        <label for="layer"><?= $translations['layer']; ?></label>
        <select name="layer" id="layer">
            <option value="client" <?= ($requirement['layer'] === "client") ? "selected" : ""; ?>><?= $translations['client']; ?></option>
            <option value="routing" <?= ($requirement['layer'] === "routing") ? "selected" : ""; ?>><?= $translations['routing']; ?></option>
            <option value="business" <?= ($requirement['layer'] === "business") ? "selected" : ""; ?>><?= $translations['business']; ?></option>
            <option value="db" <?= ($requirement['layer'] === "db") ? "selected" : ""; ?>><?= $translations['db']; ?></option>
            <option value="test" <?= ($requirement['layer'] === "test") ? "selected" : ""; ?>><?= $translations['test']; ?></option>
        </select>

        <label for="hashtags"><?= $translations['hashtags']; ?></label>
        <input type="text" id="hashtags" name="hashtags" required value="<?= $requirement['hashtags'] ?>">

        <label for="isNonFunctional"><?= $translations['is_non_functional']; ?></label>
        <input type="checkbox" id="isNonFunctional" name="isNonFunctional" value="1" <?= $requirement['isNonFunctional'] ? 'checked' : ''; ?>>

        <div id="nonFunctionalFields" style="display: <?= $requirement['isNonFunctional'] ? 'block' : 'none'; ?>;">
            <label for="indicator_name"><?= $translations['indicator_name']; ?></label>
            <input type="text" id="indicator_name" name="indicator_name" value="<?= $requirement['indicator_name'] ?>">

            <label for="unit"><?= $translations['unit']; ?></label>
            <input type="text" id="unit" name="unit" value="<?= $requirement['unit'] ?>">

            <label for="value"><?= $translations['value']; ?></label>
            <input type="number" step="0.01" id="value" name="value" value="<?= $requirement['value'] ?>">

            <label for="indicator_description"><?= $translations['indicator_description']; ?></label>
            <textarea id="indicator_description" name="indicator_description"><?= $requirement['indicator_description'] ?></textarea>
        </div>

        <div class="actions">
            <button type="submit"><?= $translations['submit']; ?></button>
            <button class="delete" id="triggerButton" onclick="clickHandler(<?= $requirement['id'] ?>)"><?= $translations['delete']; ?></button>
        </div>
    </form>
    <br>

</div>

<script>
    const isNonFunctionalCheckbox = document.getElementById('isNonFunctional');
    const nonFunctionalFields = document.getElementById('nonFunctionalFields');
    
    isNonFunctionalCheckbox.addEventListener('change', () => {
        if (isNonFunctionalCheckbox.checked) {
            nonFunctionalFields.style.display = 'block';
        } else {
            nonFunctionalFields.style.display = 'none';
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('.requirement-form');
        const isNonFunctionalCheckbox = document.getElementById('isNonFunctional');
        const nonFunctionalFields = document.getElementById('nonFunctionalFields');

        form.addEventListener('submit', function (event) {
            if (isNonFunctionalCheckbox.checked) {
                const indicatorName = document.getElementById('indicator_name').value.trim();
                const unit = document.getElementById('unit').value.trim();
                const value = document.getElementById('value').value.trim();
                const indicatorDescription = document.getElementById('indicator_description').value.trim();

                if (!indicatorName || !unit || !value || !indicatorDescription) {
                    alert('Please fill in all non-functional requirement fields.');
                    event.preventDefault();
                }
            }
        });

        isNonFunctionalCheckbox.addEventListener('change', () => {
            nonFunctionalFields.style.display = isNonFunctionalCheckbox.checked ? 'block' : 'none';
        });
    });
</script>


<script>
    function clickHandler(id) {
        fetch('actions/remove_requirement_action.php?id=' + id, { method: 'DELETE' })
            .then(() => window.location.href = '../index.php')
            .catch(error => console.error('Error:', error));
    }
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>