<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in.";
    header("Location: ../user/login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Handle image upload
if (isset($_POST['update_image']) && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        if ($file['size'] <= 5*1024*1024 && in_array($file['type'], ['image/jpeg','image/png'])) {
            $src = ($file['type'] === 'image/jpeg') ? imagecreatefromjpeg($file['tmp_name']) : imagecreatefrompng($file['tmp_name']);
            $width = imagesx($src); $height = imagesy($src);
            $size = min($width,$height);
            $x = ($width-$size)/2; $y = ($height-$size)/2;
            $dst = imagecreatetruecolor($size,$size);
            imagecopy($dst,$src,0,0,$x,$y,$size,$size);

            $uploadDir = __DIR__."/../uploads/pf_img/";
            if (!is_dir($uploadDir)) mkdir($uploadDir,0755,true);

            $filename = "profile_".$userId.".jpg";
            $path = $uploadDir.$filename;
            imagejpeg($dst,$path,90);

            imagedestroy($src); imagedestroy($dst);

            $dbPath = "../uploads/pf_img/".$filename;

            $stmt = mysqli_prepare($conn,"UPDATE customer SET profile_image=? WHERE user_id=?");
            mysqli_stmt_bind_param($stmt,"si",$dbPath,$userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $_SESSION['success'] = "Profile image updated!";
        }
    }
    header("Location: ../user/profile.php");
    exit();
}

// Handle customer details update
if (isset($_POST['update_profile'])) {
    $firstname = $_POST['firstname'] ?? '';
    $lastname  = $_POST['lastname'] ?? '';
    $address   = $_POST['address'] ?? '';
    $city      = $_POST['city'] ?? '';
    $zipcode   = $_POST['zipcode'] ?? '';
    $username  = $_POST['username'] ?? '';
    $phone     = $_POST['phone'] ?? '';

    $stmt = mysqli_prepare($conn,"UPDATE customer SET firstname=?, lastname=?, address=?, city=?, zipcode=?, username=?, phone=? WHERE user_id=?");
    mysqli_stmt_bind_param($stmt,"sssssssi",$firstname,$lastname,$address,$city,$zipcode,$username,$phone,$userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $_SESSION['success'] = "Profile details updated!";
    header("Location: ../user/profile.php");
    exit();
}
?>