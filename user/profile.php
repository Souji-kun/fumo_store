<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');

// ✅ Guard: must be logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to view your profile.";
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// ✅ Fetch user profile details
$stmt = mysqli_prepare($conn, "SELECT username, lastname, firstname, address, city, zipcode, phone, profile_image 
                               FROM customer WHERE user_id=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $uname, $lname, $fname, $address, $city, $zipcode, $phone, $profileImage);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// fallback if no image
$imgSrc = !empty($profileImage) ? $profileImage : "../uploads/pf_img/default.jpg";

// ✅ Include header AFTER processing
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    include(BASE_PATH . 'includes/admin_header.php');
} else {
    include(BASE_PATH . 'includes/header.php');
}
?>

<style>
.img-account-profile {
    width: 265px;
    height: 265px;
    object-fit: cover;
}
</style>

<div class="container-xl px-4 mt-4">
    <?php include("../includes/alert.php"); ?>
    <nav class="nav nav-borders">
        <a class="nav-link active ms-0" href="#">Profile</a>
    </nav>
    <hr class="mt-0 mb-4">
    <div class="row">
        <!-- Profile Picture -->
        <div class="col-xl-4">
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Profile Picture</div>
                <div class="card-body text-center">
                    <!-- Form 1: Image upload -->
                    <form action="../admin/upload.php" method="POST" enctype="multipart/form-data">
                        <div class="row align-items-center">
                            <!-- Profile image preview -->
                            <div class="col-md-12 text-center">
                                <img id="profilePreview" class="img-account-profile rounded-circle mb-2"
                                    src="<?php echo htmlspecialchars($imgSrc); ?>" alt="Profile Image">
                                <div class="small font-italic text-muted mb-2">JPG or PNG no larger than 5 MB</div>
                            </div>
                          <!-- File input + button -->
                            <div class="col-md-12">
                                <label class="btn btn-primary mt-2 w-100">
                                    Choose Image
                                    <input type="file" name="image" accept="image/jpeg,image/png" hidden onchange="previewImage(this)">
                                </label>
                                <button class="btn btn-success mt-2 w-100" type="submit" name="update_image">Update Image</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account Details -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">Account Details</div>
                <div class="card-body">
                    <!-- Form 2: Customer details -->
                    <form action="../admin/upload.php" method="POST">
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1">First name</label>
                                <input class="form-control" type="text" name="firstname" value="<?php echo htmlspecialchars($fname); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1">Last name</label>
                                <input class="form-control" type="text" name="lastname" value="<?php echo htmlspecialchars($lname); ?>">
                            </div>
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1">Address</label>
                                <input class="form-control" type="text" name="address" value="<?php echo htmlspecialchars($address); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1">City</label>
                                <input class="form-control" type="text" name="city" value="<?php echo htmlspecialchars($city); ?>">
                            </div>
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1">Zip code</label>
                                <input class="form-control" type="text" name="zipcode" value="<?php echo htmlspecialchars($zipcode); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1">Username</label>
                                <input class="form-control" type="text" name="username" value="<?php echo htmlspecialchars($uname); ?>">
                            </div>
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1">Phone number</label>
                                <input class="form-control" type="tel" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                            </div>
                        </div>

                        <!-- Save button for details -->
                        <button id="saveBtnDetails" class="btn btn-success mt-2" type="submit" name="update_profile">
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include(BASE_PATH . 'includes/footer.php'); ?>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>