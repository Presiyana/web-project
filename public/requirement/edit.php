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

<div class="title">
    <h1>Requirement Edit</h1>
</div>
<div class="content">
    <form class="requirement-form" action="actions/edit_requirement_action.php" method="post">
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

        <label for="isNonFunctional">Is Non-Functional?</label>
        <input type="checkbox" id="isNonFunctional" name="isNonFunctional" value="1" <?= $requirement['isNonFunctional'] ? 'checked' : ''; ?>>

        <div id="nonFunctionalFields" style="display: <?= $requirement['isNonFunctional'] ? 'block' : 'none'; ?>;">
            <label for="indicator_name">Indicator Name:</label>
            <input type="text" id="indicator_name" name="indicator_name" value="<?= $requirement['indicator_name'] ?>">

            <label for="unit">Unit:</label>
            <input type="text" id="unit" name="unit" value="<?= $requirement['unit'] ?>">

            <label for="value">Value:</label>
            <input type="number" step="0.01" id="value" name="value" value="<?= $requirement['value'] ?>">

            <label for="indicator_description">Indicator Description:</label>
            <textarea id="indicator_description" name="indicator_description"><?= $requirement['indicator_description'] ?></textarea>
        </div>

        <div class="actions">
            <button class="delete" id="triggerButton" onclick="clickHandler(<?= $requirement['id'] ?>)">Delete</button>
            <button type="submit">Submit</button>
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
        console.log({ id });
        fetch('actions/remove_requirement_action.php?id=' + id, { method: 'DELETE' })
            .then(() => window.location.href = '../index.php')
            .catch(error => console.error('Error:', error));
    }
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>