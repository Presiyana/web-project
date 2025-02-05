<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$authUser = $_SESSION['auth_user'];

if (!isset($authUser) || $authUser['user_group'] !== 'teacher') {
    header('Location: ./index.php');
    die();
}

require_once __DIR__ . '/../../app/services/TaskService.php';
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>


<div class="title-container">
    <h1><?= $translations['create_task']; ?></h1>
</div>
<div class="content">
    <form class="box" class="task-form" action="actions/add_task_action.php" method="post">
        <label for="title"><?= $translations['title']; ?></label>
        <input type="text" id="title" name="title" required>

        <label for="user_group"><?= $translations['user_group']; ?></label>
        <select name="user_group" id="user_group">
            <option value="5"><?= $translations['group_5']; ?></option>
            <option value="6"><?= $translations['group_6']; ?></option>
            <option value="7"><?= $translations['group_7']; ?></option>
        </select>

        <button type="submit"><?= $translations['submit']; ?></button>
    </form>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>