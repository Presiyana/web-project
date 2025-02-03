<?php
require_once __DIR__ . '/../../app/services/UserService.php';
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>

<div class="title-container">
    <h1>Register user</h1>
</div>
<div class="content">
    <form class="box" id="registerForm" action="actions/register_user.php" method="post">
        <input type="text" name="username" placeholder="Username" required value="test">
        <input type="email" name="email" placeholder="Email" required value="test@test.com">
        <input type="password" name="password" placeholder="Password" required value="123">
        <select name="user_group" class="user_group" value="5">
            <option value="5">5 Group</option>
            <option value="6">6 Group</option>
            <option value="7">7 Group</option>
            <option value="teacher">Teacher</option>
        </select>
        <button type="submit">Register</button>
    </form>
    <div id="registerMessage"></div>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>