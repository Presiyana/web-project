<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/TaskService.php';
$taskService = TaskService::getInstance();
$tasks = $taskService->getTasks();

?>

<?php require_once __DIR__ . '/../common/header.php'; ?>

<div class="title-container">
    <h1>Tasks</h1>
    <div class="actions">
        <a class="button" href="./add.php">Add Task</a>
    </div>
</div>

<div class="content">
    <table class="task-table">
        <thead>
            <tr>
                <th>#</th>
                <th>title</th>
                <th>User Group</th>
                <th>Creator</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="tasksBody">
            <?php foreach ($tasks as $idx => $task): ?>
                <tr class="task-entry" data-id="<?= $task['id']; ?>">
                    <td>
                        <?= $idx + 1 ?>
                    </td>
                    <td>
                        <?= $task['title']; ?>
                    </td>
                    <td>
                        <?= $task['user_group']; ?>
                    </td>
                    <td>
                        <?= $task['email']; ?>
                    </td>
                    <td>
                        <?= $task['status'] === "complete" ? "Completed" : "In progress"; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= count($tasks) ? '' : 'No tasks' ?>
</div>

<script>
    document.querySelectorAll('.task-entry').forEach(item => {
        item.addEventListener('click', function (event) {
            const id = this.getAttribute('data-id');
            window.location.href = `details.php?id=${id}`;
        });
    });
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>