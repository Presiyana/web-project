<?php
require_once __DIR__ . '/../../app/services/UserService.php';
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>

<div class="title">
    <h1>Register user</h1>
</div>
<div class="content">
    <form id="registerForm" action="actions/register_user.php" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <select class="user_group">
                <option value="5"> 5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="teacher">teacher</option>
        </select> 
        <button type="submit">Register</button>
    </form>
<div id="registerMessage"></div>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>