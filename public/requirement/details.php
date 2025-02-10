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

$taskId = $queries['task_id'];

$editLinkSearch = $requirementsFilter ? $requirementsFilter . "&id=" . $requirement['id'] : "?id=" . $requirement['id'];
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>

<div class="title-container">
    <h1><?= $translations['req_number']; ?><?= $requirement['title'] ?></h1>
    <div class="actions">
        <?php if ($taskId): ?>
            <a class="button" href="<?= BASE_URL ?>task/details.php?id=<?= $taskId ?>"><?= $translations['back_to_task']; ?></a>
        <?php endif; ?>
        <a class="button" href="./edit.php<?= $editLinkSearch ?>"><?= $translations['edit']; ?></a>
    </div>
</div>
<div class="content">
    <form class="box" class="requirement-form" action="actions/edit_requirement_action.php" method="post">
        <input type="hidden" name="id" value="<?= $requirement['id'] ?>">

        <label for="title"><?= $translations['title']; ?></label>
        <input disabled type="text" id="title" name="title" required value="<?= $requirement['title'] ?>">

        <label for="description"><?= $translations['description']; ?></label>
        <input disabled type="text" id="description" name="description" required
            value="<?= $requirement['description'] ?>">

        <label for="priority"><?= $translations['priority']; ?></label>
        <select disabled name="priority" id="priority">
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
        <select disabled name="layer" id="layer">
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

        <label for="hashtags"><?= $translations['hashtags']; ?></label>
        <input disabled type="text" id="hashtags" name="hashtags" required value="<?= $requirement['hashtags'] ?>">

        <label for="isNonFunctional"><?= $translations['is_non_functional']; ?></label>
        <input disabled type="checkbox" id="isNonFunctional" name="isNonFunctional" value="1"
            <?= $requirement['isNonFunctional'] ? 'checked' : ''; ?>>
    </form>

    <?php if ($requirement['isNonFunctional']): ?>

        <hr>

        <div class="title-container secondary">
            <h1><?= $translations['requirement_indicators']; ?></h1>
            <div class="actions">
                <a class="button"
                    href="./add_requirement_indicator.php?id=<?= $requirement['id'] ?>"><?= $translations['add_requirement_indicator']; ?></a>
            </div>
        </div>

        <table class="task-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?= $translations['indicator_name']; ?></th>
                    <th><?= $translations['unit']; ?></th>
                    <th><?= $translations['value']; ?></th>
                    <th><?= $translations['indicator_description']; ?></th>
                    <th><?= $translations['actions']; ?></th>
                </tr>
            </thead>
            <tbody id="tasksBody">
                <?php foreach ($requirementIndicators as $idx => $requirementIndicator): ?>
                    <tr class="indicator-entry" data-id="<?= $requirementIndicator['id']; ?>">
                        <td>
                            <?= $idx + 1 ?>
                        </td>
                        <td class="title">
                            <?= $requirementIndicator['indicator_name']; ?>
                        </td>
                        <td>
                            <?= $requirementIndicator['unit']; ?>
                        </td>
                        <td>
                            <?= $requirementIndicator['value']; ?>
                        </td>
                        <td>
                            <p class="indicator-description">
                                <?= $requirementIndicator['indicator_description']; ?>
                            </p>
                        </td>
                        <td>
                            <button type="button" class="delete small"
                                onclick="deleteButtonClickHandler(<?= $requirementIndicator['id'] ?>)"><?= $translations['delete']; ?></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="no-rows">
            <?= count($requirementIndicators) ? '' : $translations['no_requirement_indicators']; ?>
        </p>
    <?php endif; ?>
</div>

<script>
    const errorMessage = "<?= $translations['requirement_deletion_failed'] ?? "Requirement deletion failed."; ?>";
    function deleteButtonClickHandler(indicatorId) {
        fetch(`actions/remove_requirement_indicator_action.php?id=${indicatorId}`, { method: 'DELETE' })
            .then((res) => {
                if (res.ok) {
                    const newQps = [];
                    const qps = (new URLSearchParams(window.location.search));
                    qps.forEach((value, key) => {
                        if (key !== 'message') {
                            newQps.push(`${key}=${value}`);
                        }
                    });
                    const baseUrl = window.location.href.replace(window.location.search, '');

                    window.location.href = `${baseUrl}?${newQps.join('&')}`;
                    return;
                }
                window.location.href = `${window.location.href}&message=${errorMessage}`;
            })
            .catch(error => {
                console.error('Error:', error)
                window.location.href = `${window.location.href}&message=${errorMessage}`;
            });
    }


    const requirementId = <?= $requirement['id'] ?>;
    document.querySelectorAll('.indicator-entry').forEach(item => {
        item.addEventListener('click', function (event) {
            if (event.target.tagName === 'BUTTON') {
                return;
            }

            const id = this.getAttribute('data-id');
            const search = `${window.location.search}&indicator_id=${id}`
            window.location.href = `edit_requirement_indicator.php${search}`;
        });
    });
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>
