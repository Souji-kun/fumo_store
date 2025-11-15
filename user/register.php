<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');

// choose header based on role
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    include(BASE_PATH . 'includes/admin_header.php');
} else {
    include(BASE_PATH . 'includes/header.php');
}
?>

<div class="section-divider"></div>
<div class="section-divider"></div>

<div class="container-fluid container-lg">
    <?php include("../includes/alert.php"); ?>
    <form action="store.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-3">
            <label for="password2" class="form-label">confirm password</label>
            <input type="password" class="form-control" id="password2" name="confirmPass" required>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>

<?php
include(BASE_PATH . 'includes/footer.php');
?>