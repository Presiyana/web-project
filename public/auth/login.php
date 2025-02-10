<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../app/services/UserService.php';
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>

<div class="title-container">
    <h1><?= $translations['login']; ?></h1>
</div>
<div class="content">
    <form class="box" id="registerForm" action="actions/login_user.php" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit"><?= $translations['login']; ?></button>
    </form>
</div>
<div class="secondaryContainer">
    <span><?= $translations['or']; ?></span>
    <a href="./register.php"><?= $translations['register']; ?></a>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>