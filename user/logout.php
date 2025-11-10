<?php
require_once '../includes/config.php';

session_start();
session_destroy();

// Delete remember me cookie if it exists
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

header("Location: ../index.php");
exit();