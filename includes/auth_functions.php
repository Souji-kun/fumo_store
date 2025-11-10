<?php
function checkSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function isLoggedIn() {
    checkSession();
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    checkSession();
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function getUserName() {
    checkSession();
    return isset($_SESSION['username']) ? $_SESSION['username'] : '';
}

function getUserProfileImage() {
    checkSession();
    return isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/images/default-profile.png';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php?message=" . urlencode("Please login to access this page"));
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header("Location: index.php?message=" . urlencode("Access denied. Admin privileges required."));
        exit();
    }
}
?>