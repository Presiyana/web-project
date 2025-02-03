<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../app/services/UserService.php';
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>

<div class="title-container">
    <h1>Login</h1>
</div>
<div class="content">
    <form class="box" id="registerForm" action="actions/login_user.php" method="post">
        <input type="email" name="email" placeholder="Email" required value="test@test.com">
        <input type="password" name="password" placeholder="Password" required value="123">
        <button type="submit">Login</button>
    </form>
</div>
<div class="secondaryContainer">
    <span>or</span>
    <a href="./register.php">Register</a>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>