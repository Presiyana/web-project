<?php
session_start();

unset($_SESSION['auth_user']);

header('Location: ./login');