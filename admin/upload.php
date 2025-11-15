<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];

    // Validate size (≤ 5 MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        $_SESSION['message'] = "File too large. Max 5 MB.";
        header("Location: profile.php");
        exit();
    }

    // Validate type
    $allowed = ['image/jpeg', 'image/png'];
    if (!in_array($file['type'], $allowed)) {
        $_SESSION['message'] = "Invalid file type.";
        header("Location: profile.php");
        exit();
    }

    // Load image resource
    $src = ($file['type'] === 'image/jpeg')
        ? imagecreatefromjpeg($file['tmp_name'])
        : imagecreatefrompng($file['tmp_name']);

    $width  = imagesx($src);
    $height = imagesy($src);
    $size   = min($width, $height);
    $x = ($width  - $size) / 2;
    $y = ($height - $size) / 2;

    // Crop to square
    $dst = imagecreatetruecolor($size, $size);
    imagecopy($dst, $src, 0, 0, $x, $y, $size, $size);

    // Save cropped image
    $uploadDir = __DIR__ . "/../uploads/profile_images/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $filename = "profile_" . $_SESSION['user_id'] . ".jpg";
    $path = $uploadDir . $filename;
    imagejpeg($dst, $path, 90);

    imagedestroy($src);
    imagedestroy($dst);

    // Save relative path in DB
    $dbPath = "uploads/profile_images/" . $filename;
    $stmt = mysqli_prepare($conn, "UPDATE customer SET profile_image=? WHERE user_id=?");
    mysqli_stmt_bind_param($stmt, "si", $dbPath, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $_SESSION['success'] = "Profile image updated!";
    header("Location: profile.php");
    exit();
}
?>