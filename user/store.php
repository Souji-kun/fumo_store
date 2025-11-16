<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');

$email       = trim($_POST['email']);
$password    = trim($_POST['password']);
$confirmPass = trim($_POST['confirmPass']);

if ($password !== $confirmPass) {
    $_SESSION['message'] = 'Passwords do not match';
    header("Location: register.php");
    exit();
}

// old hashing for compatibility
$passwordHash = sha1($password);
$defaultImage = "uploads/pf_img/default.png";

// check if email exists
$check = $conn->prepare("SELECT id FROM users WHERE email=?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    $_SESSION['message'] = 'Email already registered';
    header("Location: register.php");
    exit();
}
$check->close();

// insert into users only
$stmt = $conn->prepare("INSERT INTO users (email, password, profile_image) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $passwordHash, $defaultImage);

if ($stmt->execute()) {
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['message'] = 'Registration successful!';
    header("Location: profile.php");
    exit();
} else {
    $_SESSION['message'] = 'Error registering user: ' . $stmt->error;
    header("Location: register.php");
    exit();
}
?>