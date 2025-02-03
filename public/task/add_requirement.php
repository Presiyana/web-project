<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/TaskService.php';
require_once __DIR__ . '/../../app/services/RequirementService.php';
$taskService = TaskService::getInstance();
$taskId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$task = $taskService->getTaskById($taskId);

$taskRequirements = $taskService->getTaskRequirements($task['id']);


function getRequirementId($taskRequirement)
{
    return $taskRequirement['requirement_id'];
}

$taskRequirementIds = array_map('getRequirementId', $taskRequirements);

$requirementService = RequirementService::getInstance();
$requirements = $requirementService->getRequirements();
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>

<div class="title-container">
    <h1>Add requirement to task #<?= $taskId; ?></h1>
</div>
<div class="content">
    <form class="box" class="task-form" action="actions/add_task_requirement_action.php" method="post">
        <input type="hidden" name="id" value="<?= $task['id'] ?>">

        <label for="requirement">Requirement</label>
        <select name="requirement" id="requirement" required>

            <?php foreach ($requirements as $idx => $requirement): ?>
                <?php
                if (in_array($requirement['id'], $taskRequirementIds)) {
                    continue;
                }
                ?>
                <option value="<?= $requirement['id']; ?>"><?= $requirement['title']; ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Submit</button>
    </form>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>