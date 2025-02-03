<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/TaskService.php';
$taskService = TaskService::getInstance();
$taskId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$task = $taskService->getTaskById($taskId);

$taskRequirements = $taskService->getTaskRequirementsWithRequirementData($task['id']);
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>


<div class="title-container">
    <h1>Task #<?= $task['id'] ?></h1>
    <div class="actions">
        <a class="button" href="./edit.php?id=<?= $task['id'] ?>">Edit</a>
    </div>
</div>
<div class="content">
    <form class="box">
        <input type="hidden" name="id" value="<?= $task['id'] ?>">

        <label for="title">Title</label>
        <input disabled type="text" id="title" name="title" required value="<?= $task['title'] ?>">

        <label for="user_group">User Group:</label>
        <select disabled name="user_group" id="user_group" value="<?= $task['user_group'] ?>">
            <option value="5">5 Group</option>
            <option value="6">6 Group</option>
            <option value="7">7 Group</option>
        </select>
    </form>


    <div class="title-container">
        <h1>Task requirements</h1>
        <div class="actions">
            <a class="button" href="./add_requirement.php?id=<?= $task['id'] ?>">Add requirement</a>
        </div>
    </div>
    <table class="task-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Status</th>
                <th>Actions</th>
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
                        <?= $taskRequirement['status'] === "complete" ? "Completed" : "In progress"; ?>
                    </td>

                    <td>
                        <button class="small toggleCompletion" data-id="<?= $taskRequirement['requirement_id']; ?>">Toggle
                            completion</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= count($taskRequirements) ? '' : 'No task requirements' ?>
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