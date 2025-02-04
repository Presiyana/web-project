<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$authUser = $_SESSION['auth_user'];

require_once __DIR__ . '/../../app/services/TaskService.php';
$taskService = TaskService::getInstance();
$taskId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


try {
    $task = $taskService->getTaskById($taskId);
    $taskRequirements = $taskService->getTaskRequirementsWithRequirementData($task['id'], false);
    $nonFunctionaltaskRequirements = $taskService->getTaskRequirementsWithRequirementData($task['id'], true);
} catch (Exception $e) {
    die("Task not found or invalid task ID.");
}
/*
$taskId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$task = $taskService->getTaskById($taskId);

$taskRequirements = $taskService->getTaskRequirementsWithRequirementData($task['id'], False);
$nonFunctionaltaskRequirements = $taskService->getTaskRequirementsWithRequirementData($task['id'], True);
?>

*/
require_once __DIR__ . '/../common/header.php'; 
?>


<div class="title-container">
    <h1><?= $translations['task_number']; ?><?= $task['id'] ?></h1>
    <?php if ($authUser['user_group'] === 'teacher'): ?>
        <div class="actions">
            <a class="button" href="./edit.php?id=<?= $task['id'] ?>"><?= $translations['edit']; ?></a>
        </div>
    <?php endif; ?>
</div>
<div class="content">
    <form class="box">
        <input type="hidden" name="id" value="<?= $task['id'] ?>">

        <label for="title"><?= $translations['title']; ?></label>
        <input disabled type="text" id="title" name="title" required value="<?= $task['title'] ?>">

        <label for="user_group"><?= $translations['user_group']; ?></label>
        <select disabled name="user_group" id="user_group" value="<?= $task['user_group'] ?>">
            <option value="5"><?= $translations['group_5']; ?></option>
            <option value="6"><?= $translations['group_6']; ?></option>
            <option value="7"><?= $translations['group_7']; ?></option>
        </select>
    </form>


    <div class="title-container secondary">
        <h1><?= $translations['functional']; ?></h1>
        <?php if ($authUser['user_group'] === 'teacher'): ?>
            <div class="actions">
                <a class="button" href="./add_requirement.php?id=<?= $task['id'] ?>"><?= $translations['add_requirement']; ?></a>
            </div>
        <?php endif; ?>
    </div>

    <div class="taks-sub-title">
        <h2><?= $translations['functional']; ?></h2>
    </div>
    <table class="task-table">
        <thead>
            <tr>
                <th>#</th>
                <th><?= $translations['title']; ?></th>
                <th><?= $translations['status']; ?></th>
                <th><?= $translations['actions']; ?></th>
            </tr>
        </thead>
        <tbody id="tasksBody">
            <?php foreach ($taskRequirements as $idx => $taskRequirement): ?>
                <tr class="task-requirement-entry" data-id="<?= $taskRequirement['id']; ?>">
                    <td>
                        <?= $idx + 1 ?>
                    </td>
                    <td>
                        <?= $taskRequirement['title']; ?>
                    </td>
                    <td>
                        <?= $taskRequirement['status'] === "complete" ? $translations['completed'] : $translations['in_progress']; ?>
                    </td>

                    <td>
                        <button class="small toggleCompletion" data-id="<?= $taskRequirement['requirement_id']; ?>"><?= $translations['toogle_completion']; ?></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= count($taskRequirements) ? '' : $translations['no_f_req']; ?>

    <div class="taks-sub-title">
        <h2><?= $translations['non_functional']; ?></h2>
    </div>
    <table class="task-table">
        <thead>
            <tr>
                <th>#</th>
                <th><?= $translations['title']; ?></th>
                <th><?= $translations['status']; ?></th>
                <th><?= $translations['actions']; ?></th>
            </tr>
        </thead>
        <tbody id="tasksBody">
            <?php foreach ($nonFunctionaltaskRequirements as $idx => $taskRequirement): ?>
                <tr class="task-requirement-entry" data-id="<?= $taskRequirement['id']; ?>">
                    <td>
                        <?= $idx + 1 ?>
                    </td>
                    <td>
                        <?= $taskRequirement['title']; ?>
                    </td>
                    <td>
                        <?= $taskRequirement['status'] === "complete" ? $translations['completed'] : $translations['in_progress']; ?>
                    </td>

                    <td>
                        <button class="small toggleCompletion" data-id="<?= $taskRequirement['requirement_id']; ?>"><?= $translations['toogle_completion']; ?></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= count($nonFunctionaltaskRequirements) ? '' : $translations['no_non_f_req']; ?>
</div>

<script>
    document.querySelectorAll('.toggleCompletion').forEach(item => {
        item.addEventListener('click', function (event) {
            const requirementId = this.getAttribute('data-id');
            window.location.href =
                `actions/toggle_task_requirement_completion_action.php?task_id=<?= $taskId; ?>&requirement_id=${requirementId}`;
        })
    });
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>