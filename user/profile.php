<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');

// ✅ Guard: must be logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to view your profile.";
    header("Location: login.php");
    exit();
}

// ✅ Fetch user profile details
$stmt = mysqli_prepare($conn, "SELECT username, lastname, firstname, address, city, zipcode, phone, profile_image 
                               FROM customer WHERE user_id=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $uname, $lname, $fname, $address, $city, $zipcode, $phone, $profileImage);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// fallback if no image
$imgSrc = $profileImage ? $profileImage : "http://bootdey.com/img/Content/avatar/avatar1.png";

// ✅ Include header AFTER processing
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    include(BASE_PATH . 'includes/admin_header.php');
} else {
    include(BASE_PATH . 'includes/header.php');
}
?>

<style>
/* ✅ Ensure profile image fits container */
.img-account-profile {
    width: 265px;   /* match default avatar size */
    height: 265px;
    object-fit: cover; /* crop/scale to fit */
}

/* ✅ Readonly inputs styled like normal */
.readonly-input[readonly] {
    background-color: #fff;
    opacity: 1;
    cursor: default;
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
                    <img id="profilePreview" class="img-account-profile rounded-circle mb-2"
                         src="<?php echo htmlspecialchars($imgSrc); ?>" alt="Profile Image">
                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                    
                    <!-- ✅ Image upload with preview -->
                    <form action="upload.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="type" value="profile">
                        <label class="btn btn-primary mt-2">
                            Choose Image
                            <input type="file" name="image" accept="image/jpeg,image/png" hidden
                                   onchange="previewImage(this)">
                        </label>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account Details -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header mt-8 mb-10">Account Details</div>
                <div class="card-body">
                    <!-- ✅ Display user info in readonly inputs -->
                    <form>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1">First name</label>
                                <input class="form-control readonly-input" type="text" 
                                       value="<?php echo htmlspecialchars($fname); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1">Last name</label>
                                <input class="form-control readonly-input" type="text" 
                                       value="<?php echo htmlspecialchars($lname); ?>" readonly>
                            </div>
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1">Address</label>
                                <input class="form-control readonly-input" type="text" 
                                       value="<?php echo htmlspecialchars($address); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1">City</label>
                                <input class="form-control readonly-input" type="text" 
                                       value="<?php echo htmlspecialchars($city); ?>" readonly>
                            </div>
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1">Zip code</label>
                                <input class="form-control readonly-input" type="text" 
                                       value="<?php echo htmlspecialchars($zipcode); ?>" readonly>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="small mb-1">Username</label>
                            <input class="form-control readonly-input" type="text" 
                                   value="<?php echo htmlspecialchars($uname); ?>" readonly>
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1">Phone number</label>
                                <input class="form-control readonly-input" type="tel" 
                                       value="<?php echo htmlspecialchars($phone); ?>" readonly>
                            </div>
                        </div>
                         <button id="saveBtn" class="btn btn-success mt-2" type="submit" style="display:none;">
                            Save Changes
                        </button>
                        <!-- keep them here -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include(BASE_PATH . 'includes/footer.php');
?>

<!-- ✅ JS for live image preview -->
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
            document.getElementById('saveBtn').style.display = 'inline-block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>