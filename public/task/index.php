<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$authUser = $_SESSION['auth_user'];

require_once __DIR__ . '/../../app/services/TaskService.php';
$taskService = TaskService::getInstance();
$tasks = $taskService->getTasks(
    $authUser['user_group'] === 'teacher' ? '': $authUser['user_group'],
);

?>

<?php require_once __DIR__ . '/../common/header.php'; ?>

<div class="title-container">
    <h1>Tasks</h1>

    <?php if ($authUser['user_group'] === 'teacher'): ?>
        <div class="actions">
            <a class="button" href="./add.php">Add Task</a>
        </div>
    <?php endif; ?>
</div>

<div class="content">
    <table class="task-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <?php if ($authUser['user_group'] === 'teacher'): ?>
                    <th>User Group</th>
                <?php endif; ?>
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
                    <?php if ($authUser['user_group'] === 'teacher'): ?>
                        <td>
                            <?= $task['user_group']; ?>
                        </td>
                    <?php endif; ?>
                    <td>
                        <?= $task['email']; ?>
                    </td>
                    <td>
                        <?= $task['pendingCount'] === 0 && $task['completedCount'] > 0 ? "Completed" : ""; ?>
                        <?= $task['pendingCount'] > 0 ? "In progress" : ""; ?>
                        <?= $task['pendingCount'] === 0 && $task['completedCount'] === 0 ? "No requirements" : ""; ?>
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