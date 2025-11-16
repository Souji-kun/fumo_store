<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');

if (isset($_POST['submit'])) {
    $emailInput = trim($_POST['email']);
    $passInput  = sha1(trim($_POST['password']));

    $sql = "SELECT id, email, role FROM users WHERE email=? AND password=? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $emailInput, $passInput);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $user_id, $user_email, $role);

    if (mysqli_stmt_num_rows($stmt) === 1) {
        mysqli_stmt_fetch($stmt);

        // set session variables
        $_SESSION['email']   = $email;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role']    = $role;

        // redirect so header.php sees the updated session
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['message'] = 'Wrong email or password';
    }
}

// now include header AFTER login logic
include(BASE_PATH . 'includes/header.php');
?>

<?php
// optional: include admin sidebar if logged in as admin
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $sidebarPath = BASE_PATH . 'includes/sidebar.php';
    if (file_exists($sidebarPath)) {
        include($sidebarPath);
    }
}
?>

<?php if (isset($_SESSION['email'])): ?>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?></p>
<?php endif; ?>

<div class="section-divider"></div>
<div class="container mt-5 pt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <?php include("../includes/alert.php"); ?>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <!-- Email input -->
        <div class="form-outline mb-4">
          <input type="email" id="form2Example1" class="form-control" name="email" required />
          <label class="form-label" for="form2Example1">Email address</label>
        </div>

        <!-- Password input -->
        <div class="form-outline mb-4">
          <input type="password" id="form2Example2" class="form-control" name="password" required />
          <label class="form-label" for="form2Example2">Password</label>
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-block mb-4" name="submit">Sign in</button>

        <!-- Register buttons -->
        <div class="text-center">
          <p>Not a member? <a href="register.php">Register</a></p>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
include(BASE_PATH . 'includes/footer.php');
?>