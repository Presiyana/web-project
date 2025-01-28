<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/RequirementService.php';
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>


<div class="title">
    <h1>Create a requirement</h1>
</div>
<div class="content">
    <form class="requirement-form" action="actions/add_requirement_action.php" method="post">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description:</label>
        <input type="text" id="description" name="description" required>

        <label for="priority">Priority:</label>
        <select name="priority" id="priority">
            <option value="high">High</option>
            <option value="medium">Medium</option>
            <option value="low">Low</option>
        </select>

        <label for="layer">Layer:</label>
        <select name="layer" id="layer">
            <option value="client">Client</option>
            <option value="routing">Routing</option>
            <option value="business">Business</option>
            <option value="db">DB</option>
            <option value="test">Test</option>
        </select>

        <label for="hashtags">Hashtags:</label>
        <input type="text" id="hashtags" name="hashtags" required>

        <button type="submit">Submit</button>
    </form>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>