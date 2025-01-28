<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../app/services/UserService.php';
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>

<div class="title">
    <h1>Login</h1>
</div>
<div class="content">
    <form id="registerForm" action="actions/login_user.php" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
<div id="registerMessage"></div>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>