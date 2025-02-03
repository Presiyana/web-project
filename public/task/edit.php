<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/TaskService.php';
$taskService = TaskService::getInstance();
$taskId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$task = $taskService->getTaskById($taskId);
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>


<div class="title-container">
    <h1>Edit task #<?= $task['id'] ?></h1>
</div>
<div class="content">
    <form class="box" class="task-form" action="actions/edit_task_action.php" method="post">
        <input type="hidden" name="id" value="<?= $task['id'] ?>">

        <label for="title">Title</label>
        <input type="text" id="title" name="title" required value="<?= $task['title'] ?>">

        <label for="user_group">User Group:</label>
        <select name="user_group" id="user_group" value="<?= $task['user_group'] ?>">
            <option value="5">5 Group</option>
            <option value="6">6 Group</option>
            <option value="7">7 Group</option>
        </select>

        <div class="actions">
            <button type="submit">Submit</button>
            <button class="delete" id="triggerButton" onclick="clickHandler(<?= $requirement['id'] ?>)">Delete</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>