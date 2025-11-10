<?php
if (!defined('INCLUDED_FROM_INDEX')) {
    require_once __DIR__ . '/config.php';
    require_once __DIR__ . '/auth_functions.php';
}

// Initialize variables for asset paths
$root_path = rtrim(dirname($_SERVER['PHP_SELF']), '/includes');
$asset_path = $root_path . '/assets';
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
    <link rel="stylesheet" type="text/css" href="<?php echo $asset_path; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $asset_path; ?>/css/font-awesome.css">
    <link rel="stylesheet" href="<?php echo $asset_path; ?>/css/templatemo-hexashop.css">
    <link rel="stylesheet" href="<?php echo $asset_path; ?>/css/owl-carousel.css">
    <link rel="stylesheet" href="<?php echo $asset_path; ?>/css/custom-header.css">
    <link rel="stylesheet" href="<?php echo $asset_path; ?>/css/best-sellers.css">
    <link rel="stylesheet" href="<?php echo $asset_path; ?>/css/section-divider.css">
    <link rel="stylesheet" href="<?php echo $asset_path; ?>/css/lightbox.css">
    <link rel="stylesheet" href="<?php echo $asset_path; ?>/css/sidebar.css">
<!--

TemplateMo 571 Hexashop

https://templatemo.com/tm-571-hexashop

-->
    </head>
    
    <body>
    
    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>  
    <!-- ***** Preloader End ***** -->
    
    
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="index.php" class="logo">
                            <img src="<?php echo $asset_path; ?>/images/fumo-logo.png">
                        </a>
                        <!-- ***** Logo End ***** -->
                        
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
                            <li class="scroll-to-section"><a href="#men">Men's</a></li>
                            <li class="scroll-to-section"><a href="#women">Women's</a></li>
                            <li class="scroll-to-section"><a href="#kids">Kid's</a></li>
                            <li class="scroll-to-section"><a href="#best-sellers">Best Sellers</a></li>
                            <li class="submenu">
                                <a href="javascript:;">Pages</a>
                                <ul>
                                    <li><a href="about.html">About Us</a></li>
                                    <li><a href="products.html">Products</a></li>
                                    <li><a href="single-product.html">Single Product</a></li>
                                    <li><a href="contact.html">Contact Us</a></li>
                                </ul>
                            </li>
                            <li class="user-section">
                                <div class="search-area">
                                    <input type="text" placeholder="Search...">
                                    <button><i class="fa fa-search"></i></button>
                                </div>
                                <?php if (isLoggedIn()): ?>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-user"></i> <?php echo getUserName(); ?>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="<?php echo SITE_URL; ?>user/profile.php">My Profile</a>
                                            <a class="dropdown-item" href="<?php echo SITE_URL; ?>user/orders.php">My Orders</a>
                                            <?php if (isAdmin()): ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="<?php echo SITE_URL; ?>admin/">Admin Dashboard</a>
                                                <a class="dropdown-item" href="<?php echo SITE_URL; ?>product/">Manage Products</a>
                                                <a class="dropdown-item" href="<?php echo SITE_URL; ?>admin/orders.php">Manage Orders</a>
                                            <?php endif; ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?php echo SITE_URL; ?>user/logout.php">Logout</a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <a href="<?php echo SITE_URL; ?>user/login.php" class="login-link">
                                        <i class="fa fa-user"></i> Login
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo SITE_URL; ?>cart/" class="cart-icon">
                                    <i class="fa fa-shopping-cart"></i>
                                    <?php
                                    if (isLoggedIn()) {
                                        $user_id = $_SESSION['user_id'];
                                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM cart_items WHERE user_id = ?");
                                        $stmt->execute([$user_id]);
                                        $cart_count = $stmt->fetchColumn();
                                        echo '<span class="cart-count">' . $cart_count . '</span>';
                                    }
                                    ?>
                                </a>
                            </li>
                        </ul>        
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <!-- jQuery -->
    <script src="<?php echo $asset_path; ?>/js/jquery-2.1.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="<?php echo $asset_path; ?>/js/popper.js"></script>
    <script src="<?php echo $asset_path; ?>/js/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="<?php echo $asset_path; ?>/js/owl-carousel.js"></script>
    <script src="<?php echo $asset_path; ?>/js/accordions.js"></script>
    <script src="<?php echo $asset_path; ?>/js/datepicker.js"></script>
    <script src="<?php echo $asset_path; ?>/js/scrollreveal.min.js"></script>
    <script src="<?php echo $asset_path; ?>/js/waypoints.min.js"></script>
    <script src="<?php echo $asset_path; ?>/js/jquery.counterup.min.js"></script>
    <script src="<?php echo $asset_path; ?>/js/imgfix.min.js"></script> 
    <script src="<?php echo $asset_path; ?>/js/slick.js"></script> 
    <script src="<?php echo $asset_path; ?>/js/lightbox.js"></script> 
    <script src="<?php echo $asset_path; ?>/js/isotope.js"></script> 
    
    <!-- Global Init -->
    <script src="<?php echo $asset_path; ?>/js/custom.js"></script>
    <script src="<?php echo $asset_path; ?>/js/quantity.js"></script>
    
    <?php include 'includes/sidebar.php'; ?>
    
