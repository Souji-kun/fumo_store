<?php
require_once '../includes/config.php';
require_once '../includes/auth_functions.php';

// Check if user is logged in and is admin
if (!isAdmin()) {
    header("Location: ../index.php");
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? $_GET['id'] : null;

switch ($action) {
    case 'add':
        include 'add.php';
        break;
    case 'edit':
        include 'edit.php';
        break;
    case 'delete':
        include 'delete.php';
        break;
    default:
        include 'list.php';
}