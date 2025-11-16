<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../includes/config.php'); // adjust path if needed
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <title>Fumo Store</title>


    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="/fumo_store2/assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="/fumo_store2/assets/css/font-awesome.css">

    <link rel="stylesheet" href="/fumo_store2/assets/css/templatemo-hexashop.css">

    <link rel="stylesheet" href="/fumo_store2/assets/css/owl-carousel.css">

    <link rel="stylesheet" href="/fumo_store2/assets/css/custom-header.css">
    
    <link rel="stylesheet" href="/fumo_store2/assets/css/best-sellers.css">

    <!-- <link rel="stylesheet" href="/fumo_store2/assets/css/plush.css"> -->
    
    <link rel="stylesheet" href="/fumo_store2/assets/css/section-divider.css">

    <link rel="stylesheet" href="/fumo_store2/assets/css/lightbox.css">

    <link rel="stylesheet" href="/fumo_store2/assets/css/sidebar.css">
<!--

TemplateMo 571 Hexashop

https://templatemo.com/tm-571-hexashop

-->
    </head>
    
    <body>
 <!-- ignore this preloader -->   
                                <!-- ***** Preloader Start ***** -->
                                <!-- <div id="preloader">
                                    <div class="jumper">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>   -->
                                <!-- ***** Preloader End ***** -->
  <!-- loading screen problem -->   
    
     <header class="header-area header-sticky py-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav d-flex align-items-center">
                    <!-- ***** Logo Start ***** -->
                    <a href="index.php" class="logo me-3">
                        <img src="/fumo_store2/assets/images/fumo-logo.png">
                    </a>
                    <!-- ***** Logo End ***** -->
                    
                   <!-- ***** Menu Start ***** -->
                    <ul class="nav ms-auto">
                        <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li class="scroll-to-section"><a href="<?php echo $baseUrl; ?>admin/item/index.php">Products</a></li>
                            <li class="scroll-to-section"><a href="<?php echo $baseUrl; ?>admin/orders.php">Orders</a></li>
                            <li class="scroll-to-section"><a href="<?php echo $baseUrl; ?>admin/users.php">Users</a></li>
                        <?php else: ?>
                            <li class="scroll-to-section"><a href="#men">Plushie</a></li>
                            <li class="scroll-to-section"><a href="#women">Costume</a></li>
                            <li class="scroll-to-section"><a href="#kids">Collectibles</a></li>
                            <li class="scroll-to-section"><a href="#best-sellers">Best Sellers</a></li>
                        <?php endif; ?>

                        <li class="submenu">
                            <a href="#">Pages</a>
                            <ul>
                                <li><a href="/fumo_store2/user/profile.php">Profile</a></li>
                                <li><a href="products.html">About Us</a></li>
                                <li><a href="single-product.html">Products</a></li>
                                <li><a href="contact.html">Contact Us</a></li>
                            </ul>
                        </li>

                        <!-- User Section -->
                        <li class="user-section">
                            <form action="<?php echo $baseUrl; ?>search.php" method="GET" class="search-area">
                                <input type="text" name="search" placeholder="Search..." class="form-control">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </form>

                            <?php if (!isset($_SESSION['user_id'])): ?>
                                <a href="<?php echo $baseUrl; ?>user/login.php" class="login-link"><i class="fa fa-user"></i> Login</a>
                            <?php else: ?>
                                <span class="username-area"><?php echo htmlspecialchars($_SESSION['email']); ?></span>
                                <a href="<?php echo $baseUrl; ?>user/logout.php" class="login-link"><i class="fa fa-sign-out"></i> Logout</a>
                            <?php endif; ?>

                            <a href="<?php echo $baseUrl; ?>cart.php" class="cart-icon">
                                <i class="fa fa-shopping-cart"></i> <span class="cart-count">0</span>
                            </a>
                            <a href="javascript:void(0)" class="sidebar-toggle"><i class="fa fa-bars"></i></a>
                        </li>
                    </ul>
                    <a class="menu-trigger"><span>Menu</span></a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- ***** Header Area End ***** -->
     
            <!-- ***** Sidebar ***** -->
        <div class="sidebar">
            <i class="fa fa-times close-sidebar"></i>
            <ul class="sidebar-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php?category=plushies">Plushies</a></li>
                <li><a href="products.php?category=costumes">Costumes</a></li>
                <li><a href="products.php?category=collectibles">Collectibles</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </div>
        <div class="sidebar-overlay"></div>
</body>