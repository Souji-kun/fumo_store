<?php
require_once(__DIR__ . '/config.php'); // same folder
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="includes/style/style.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Fumo Store</title>

  <!-- CSS -->
  <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/font-awesome.css">
  <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/templatemo-hexashop.css">
  <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/owl-carousel.css">
  <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/custom-header.css">
  <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/best-sellers.css">
  <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/section-divider.css">
  <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/lightbox.css">
  <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/sidebar.css">
</head>
<body>

</head>
    
    <body>
    
    <!-- ***** Preloader Start ***** -->
    <!-- <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>   -->
    <!-- ***** Preloader End ***** -->
    
    
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="index.php" class="logo">
                            <img src="../assets/images/fumo-logo.png">
                        </a>
                        <!-- ***** Logo End ***** -->
                        
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#top" class="active">Dashboard</a></li>
                            <li class="scroll-to-section"><a href="../user/login.php">Users</a></li>
                            <li class="scroll-to-section"><a href="#women">Orders</a></li>
                            <li class="scroll-to-section"><a href="#kids">products</a></li>
                            <li class="submenu">
                                <a href="javascript:;">others</a>
                                <ul>
                                    <li><a href="about.html">Mails</a></li>
                                    <li><a href="products.html">Inventory</a></li>
                                    <li><a href="single-product.html">Single Product</a></li>
                                    <li><a href="contact.html">Contact Us</a></li>
                                </ul>
                            </li>
                            <li class="user-section">
                                <div class="search-area">
                                    <input type="text" placeholder="Search...">
                                    <button><i class="fa fa-search"></i></button>
                                </div>
                                <a href="login.php" class="login-link"><i class="fa fa-user"></i></a>
                                <span class="username-area"></span>
                                <a href="cart.php" class="cart-icon"><i class="fa fa-shopping-cart"></i> <span class="cart-count">0</span></a>
                                <a href="javascript:void(0)" class="sidebar-toggle"><i class="fa fa-bars"></i></a>
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