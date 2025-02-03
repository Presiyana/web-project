<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/TaskService.php';
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>


<div class="title-container">
    <h1>Create a task</h1>
</div>
<div class="content">
    <form class="box" class="task-form" action="actions/add_task_action.php" method="post">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" required>

        <label for="user_group">User Group:</label>
        <select name="user_group" id="user_group">
            <option value="5">5 Group</option>
            <option value="6">6 Group</option>
            <option value="7">7 Groupo</option>
        </select>

        <button type="submit">Submit</button>
    </form>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>