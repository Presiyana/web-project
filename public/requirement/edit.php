<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/RequirementService.php';
$requirementService = RequirementService::getInstance();
$requirementId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$requirement = $requirementService->getRequirementById($requirementId);

$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
$requirementsFilter = $queries['layer'] ? "?layer=" . $queries['layer'] : "";
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>

<div class="title-container">
    <h1><?= $translations['edit_req']; ?><?= $requirement['title'] ?></h1>
</div>
<div class="content">
    <form class="box" class="requirement-form" action="actions/edit_requirement_action.php<?= $requirementsFilter ?>"
        method="post">
        <input type="hidden" name="id" value="<?= $requirement['id'] ?>">

        <label for="title"><?= $translations['title']; ?></label>
        <input type="text" id="title" name="title" required value="<?= $requirement['title'] ?>">

        <label for="description"><?= $translations['description']; ?></label>
        <input type="text" id="description" name="description" required value="<?= $requirement['description'] ?>">

        <label for="priority"><?= $translations['priority']; ?></label>
        <select name="priority" id="priority">
            <option value="high" <?= ($requirement['priority'] === "high") ? "selected" : ""; ?>>
                <?= $translations['high']; ?>
            </option>
            <option value="medium" <?= ($requirement['priority'] === "medium") ? "selected" : ""; ?>>
                <?= $translations['medium']; ?>
            </option>
            <option value="low" <?= ($requirement['priority'] === "low") ? "selected" : ""; ?>><?= $translations['low']; ?>
            </option>
        </select>

        <label for="layer"><?= $translations['layer']; ?></label>
        <select name="layer" id="layer">
            <option value="client" <?= ($requirement['layer'] === "client") ? "selected" : ""; ?>>
                <?= $translations['client']; ?>
            </option>
            <option value="routing" <?= ($requirement['layer'] === "routing") ? "selected" : ""; ?>>
                <?= $translations['routing']; ?>
            </option>
            <option value="business" <?= ($requirement['layer'] === "business") ? "selected" : ""; ?>>
                <?= $translations['business']; ?>
            </option>
            <option value="db" <?= ($requirement['layer'] === "db") ? "selected" : ""; ?>><?= $translations['db']; ?>
            </option>
            <option value="test" <?= ($requirement['layer'] === "test") ? "selected" : ""; ?>><?= $translations['test']; ?>
            </option>
        </select>
        <div class="hashtag-search-container">
            <label for="hashtags"><?= $translations['hashtags']; ?></label>
            <input type="text" id="hashtags" name="hashtags" required value="<?= $requirement['hashtags'] ?>">
        </div>

        <label for="isNonFunctional"><?= $translations['is_non_functional']; ?></label>
        <input type="checkbox" id="isNonFunctional" name="isNonFunctional" value="1" <?= $requirement['isNonFunctional'] ? 'checked' : ''; ?>>


        <?php if (!$requirement['isNonFunctional']): ?>
            <div id="nonFunctionalFields" style="display:none; margin-top: 20px;">
                <h3>First indicator details</h3>

                <label for="indicator_name"><?= $translations['indicator_name']; ?></label>
                <input type="text" id="indicator_name" name="indicator_name">

                <label for="unit"><?= $translations['unit']; ?></label>
                <input type="text" id="unit" name="unit">

                <label for="value"><?= $translations['value']; ?></label>
                <input type="number" step="0.01" max="10000" id="value" name="value">

                <label for="indicator_description"><?= $translations['indicator_description']; ?></label>
                <textarea id="indicator_description" name="indicator_description"></textarea>
            </div>
        <?php endif; ?>

        <div class="actions">
            <button type="submit"><?= $translations['submit']; ?></button>
            <button type="button" class="delete" id="triggerButton"
                onclick="deleteButtonClickHandler(<?= $requirement['id'] ?>)"><?= $translations['delete']; ?></button>
        </div>
    </form>
    <br>

</div>

<script>
    const qps = (new URLSearchParams(window.location.search));
    const id = qps.get('id');
    const layerFilter = qps.get('layer') ? `?layer=${qps.get('layer')}` : '';


    const currentBaseUrl = window.location.href.replace(window.location.search, "");
    const errorMessage = "<?= $translations['requirement_deletion_failed'] ?? "Requirement deletion failed."; ?>";

    const targetUrlBase = currentBaseUrl.replace("edit", "index");
    const targetUrl = `${targetUrlBase}${layerFilter}`;

    function deleteButtonClickHandler(id) {
        fetch('actions/remove_requirement_action.php?id=' + id, {
                method: 'DELETE'
            })
            .then((res) => {
                if (res.ok) {
                    window.location.href = targetUrl;
                    return;
                }
                window.location.href = `${currentBaseUrl}${layerFilter}${layerFilter ? '&' : '?'}id=${id}&message=${errorMessage}`;
            })
            .catch(error => {
                console.error('Error:', error)
                window.location.href = `${currentBaseUrl}${layerFilter}&id=${id}&message=${errorMessage}`;
            });
    }

    const isNonFunctionalCheckbox = document.getElementById('isNonFunctional');
    const nonFunctionalFields = document.getElementById('nonFunctionalFields');

    isNonFunctionalCheckbox.addEventListener('change', () => {
        if (isNonFunctionalCheckbox.checked) {
            nonFunctionalFields.style.display = 'block';
            nonFunctionalFields.querySelectorAll('input, textarea').forEach((element) => {
                element.required = true;
            });
        } else {
            nonFunctionalFields.style.display = 'none';
            nonFunctionalFields.querySelectorAll('input, textarea').forEach((element) => {
                element.required = false;
            });
        }
    });
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>