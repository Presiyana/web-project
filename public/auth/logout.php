<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['auth_user']);

header('Location: ./login.php');