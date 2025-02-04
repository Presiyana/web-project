<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$authUser = $_SESSION['auth_user'];

if (!isset($authUser) || $authUser['user_group'] !== 'teacher') {
    header('Location: ./index.php');
    die();
}

require_once __DIR__ . '/../../app/config/lang_config.php';
require_once __DIR__ . '/../../app/services/TaskService.php';
$taskService = TaskService::getInstance();
$taskId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$task = $taskService->getTaskById($taskId);
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>


<div class="title-container">
    <h1><?= $translations['edit_task']; ?> #<?= $task['id'] ?></h1>
</div>
<div class="content">
    <form class="box" class="task-form" action="actions/edit_task_action.php" method="post">
        <input type="hidden" name="id" value="<?= $task['id'] ?>">

        <label for="title"><?= $translations['title']; ?></label>
        <input type="text" id="title" name="title" required value="<?= $task['title'] ?>">

        <label for="user_group"><?= $translations['user_group']; ?></label>
        <select name="user_group" id="user_group" value="<?= $task['user_group'] ?>">
            <option value="5"><?= $translations['group_5']; ?></option>
            <option value="6"><?= $translations['group_6']; ?></option>
            <option value="7"><?= $translations['group_7']; ?></option>
        </select>

        <div class="actions">
            <button type="submit"><?= $translations['submit']; ?></button>
            <button class="delete" id="triggerButton" onclick="clickHandler(<?= $requirement['id'] ?>)"><?= $translations['delete']; ?></button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>