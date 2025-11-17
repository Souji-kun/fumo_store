<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in.";
    header("Location: ../user/login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// -----------------------------
// Handle image upload
// -----------------------------
if (isset($_POST['update_image']) && isset($_FILES['image'])) {
    $file = $_FILES['image'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        // Validate size
        if ($file['size'] <= 5 * 1024 * 1024) {
            // Detect actual MIME type
            $fileType = mime_content_type($file['tmp_name']);

            if ($fileType === 'image/jpeg') {
                $src = imagecreatefromjpeg($file['tmp_name']);
                $ext = ".jpg";
            } elseif ($fileType === 'image/png') {
                $src = imagecreatefrompng($file['tmp_name']);
                $ext = ".png";
            } else {
                $_SESSION['message'] = "Invalid file type. Only JPG or PNG allowed.";
                header("Location: ../user/profile.php");
                exit();
            }

            if ($src === false) {
                $_SESSION['message'] = "Failed to process image.";
                header("Location: ../user/profile.php");
                exit();
            }

            // Crop to square
            $width  = imagesx($src);
            $height = imagesy($src);
            $size   = min($width, $height);
            $x = ($width - $size) / 2;
            $y = ($height - $size) / 2;

            $dst = imagecreatetruecolor($size, $size);
            imagecopy($dst, $src, 0, 0, $x, $y, $size, $size);

            // Save cropped image
            $uploadDir = __DIR__ . "/../uploads/pf_img/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // ✅ Generate unique filename per upload
            $filename = "profile_" . $userId . "_" . time() . ".jpg";
            $path = $uploadDir . $filename;
            imagejpeg($dst, $path, 90);

            imagedestroy($src);
            imagedestroy($dst);

            // Path for DB (web-accessible)
            $dbPath = "/fumo_store2/uploads/pf_img/" . $filename;

            // Ensure customer row exists
            $check = mysqli_prepare($conn, "SELECT user_id FROM customer WHERE user_id=?");
            mysqli_stmt_bind_param($check, "i", $userId);
            mysqli_stmt_execute($check);
            mysqli_stmt_store_result($check);

            if ($check->num_rows > 0) {
                mysqli_stmt_close($check);
                $stmt = mysqli_prepare($conn, "UPDATE customer SET profile_image=? WHERE user_id=?");
                mysqli_stmt_bind_param($stmt, "si", $dbPath, $userId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else {
                mysqli_stmt_close($check);
                $stmt = mysqli_prepare($conn, "INSERT INTO customer (user_id, profile_image) VALUES (?, ?)");
                mysqli_stmt_bind_param($stmt, "is", $userId, $dbPath);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }

            // Sync users table image
            $stmt2 = mysqli_prepare($conn, "UPDATE users SET profile_image=? WHERE id=?");
            mysqli_stmt_bind_param($stmt2, "si", $dbPath, $userId);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);

            $_SESSION['success'] = "Profile image updated!";
        } else {
            $_SESSION['message'] = "File too large. Max 5 MB.";
        }
    } else {
        $_SESSION['message'] = "File upload error.";
    }

    header("Location: ../user/profile.php");
    exit();
}

// -----------------------------
// Handle customer details update
// -----------------------------
if (isset($_POST['update_profile'])) {
    $firstname = $_POST['firstname'] ?? '';
    $lastname  = $_POST['lastname'] ?? '';
    $address   = $_POST['address'] ?? '';
    $city      = $_POST['city'] ?? '';
    $zipcode   = $_POST['zipcode'] ?? '';
    $username  = $_POST['username'] ?? '';
    $phone     = $_POST['phone'] ?? '';

    // Ensure customer row exists
    $check = mysqli_prepare($conn, "SELECT user_id FROM customer WHERE user_id=?");
    mysqli_stmt_bind_param($check, "i", $userId);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if ($check->num_rows > 0) {
        mysqli_stmt_close($check);
        $stmt = mysqli_prepare($conn, "UPDATE customer 
                                       SET firstname=?, lastname=?, address=?, city=?, zipcode=?, username=?, phone=? 
                                       WHERE user_id=?");
        mysqli_stmt_bind_param($stmt, "sssssssi", $firstname, $lastname, $address, $city, $zipcode, $username, $phone, $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        mysqli_stmt_close($check);
        $stmt = mysqli_prepare($conn, "INSERT INTO customer (user_id, firstname, lastname, address, city, zipcode, username, phone) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "isssssss", $userId, $firstname, $lastname, $address, $city, $zipcode, $username, $phone);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    $_SESSION['success'] = "Profile details updated!";
    header("Location: ../user/profile.php");
    exit();
}
?>