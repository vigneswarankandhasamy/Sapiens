<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=500, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <?php    
    if ($data['meta']!='static'){ ?>
        <title><?php echo $data['meta_title'] ?></title>
        <meta name="description" content="<?php echo $data['meta_description'] ?>">
        <meta name="keywords" content="<?php echo $data['meta_keywords'] ?>"> 
        <meta property="og:title" content="<?php echo $data['meta_title'] ?>">
        <meta property="og:description" content="<?php echo $data['siteinfo']['seo']['meta_title'] ?>">
        <meta property="og:image" content="<?php echo $data['siteinfo']['seo']['image']!="" ? SRCIMG.$data['siteinfo']['seo']['image'] : ASSETS_PATH."logo.png" ?>">
    <?php }else{ ?>
        <title><?php echo $data['meta_title'] ?></title>
        <meta name="description" content="<?php echo $data['meta_description'] ?>">
        <meta name="keywords" content="<?php echo $data['meta_keywords'] ?>"> 
        <meta property="og:title" content="<?php echo $data['meta_title'] ?>">
        <meta property="og:description" content="<?php echo $data['meta_description'] ?>">
        <meta property="og:image" content="<?php echo ASSETS_PATH."logo.png" ?>">
    <?php } ?>

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo IMGPATH ?>favicon.png">


    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo CSSPATH ?>styles.css">
    <link rel="stylesheet" href="<?php echo PLUGINS ?>noty/noty.css">
    <script type="text/javascript">
        var base_path = "<?php echo BASEPATH ?>";
    </script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container" style="width: 100%;">   
            <div class="nav-search">
                <input type="text" id="searchInput" placeholder="Search for products..." autocomplete="off">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>

              <div class="nav-logo">
                <a href="<?php echo BASEPATH ?>"><img src="<?php echo SRCIMG.$data['siteinfo']['contact']['logo'] ?>" class="img-round" width="91" height="50"></a>
            </div>
            
            <div class="nav-menu">
                <a href="<?php echo BASEPATH ?>" class="nav-link <?php echo (($data['active_menu']=='home') ? 'active' : '') ?>">Home</a>
                <div class="dropdown">
                    <a href="<?php echo BASEPATH ?>product" class="nav-link dropdown-toggle <?php echo (($data['active_menu']=='products') ? 'active' : '') ?>" onclick="window.location.href='<?php echo BASEPATH ?>product'; return false;">Shop <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <!-- <div class="dropdown-submenu">
                            <a href="<?php echo BASEPATH ?>product" class="submenu-toggle">Shirts <i class="fas fa-chevron-right"></i></a>
                            <div class="dropdown-menu">
                                <a href="<?php echo BASEPATH ?>product">Casual Shirts</a>
                                <a href="<?php echo BASEPATH ?>product">Formal Shirts</a>
                                <a href="<?php echo BASEPATH ?>product">T-Shirts</a>
                                <a href="<?php echo BASEPATH ?>product">Polos</a>
                            </div>
                        </div>
                        <div class="dropdown-submenu">
                            <a href="<?php echo BASEPATH ?>product" class="submenu-toggle">Pants <i class="fas fa-chevron-right"></i></a>
                            <div class="dropdown-menu">
                                <a href="<?php echo BASEPATH ?>product">Jeans</a>
                                <a href="<?php echo BASEPATH ?>product">Chinos</a>
                                <a href="<?php echo BASEPATH ?>product">Trousers</a>
                                <a href="<?php echo BASEPATH ?>product">Shorts</a>
                            </div>
                        </div>
                        <a href="<?php echo BASEPATH ?>product">Jackets</a>
                        <div class="dropdown-submenu">
                            <a href="<?php echo BASEPATH ?>product" class="submenu-toggle">Accessories <i class="fas fa-chevron-right"></i></a>
                            <div class="dropdown-menu">
                                <a href="<?php echo BASEPATH ?>product">Belts</a>
                                <a href="<?php echo BASEPATH ?>product">Hats</a>
                                <a href="<?php echo BASEPATH ?>product">Bags</a>
                                <a href="<?php echo BASEPATH ?>product">Watches</a>
                            </div>
                        </div>
                        <div class="dropdown-submenu">
                            <a href="<?php echo BASEPATH ?>product" class="submenu-toggle">Shoes <i class="fas fa-chevron-right"></i></a>
                            <div class="dropdown-menu">
                                <a href="<?php echo BASEPATH ?>product">Sneakers</a>
                                <a href="<?php echo BASEPATH ?>product">Formal Shoes</a>
                                <a href="<?php echo BASEPATH ?>product">Loafers</a>
                                <a href="<?php echo BASEPATH ?>product">Sandals</a>
                            </div>
                        </div> -->
                        <?php echo $data['menu_items']['cat_and_subcat'] ?>
                    </div>
                </div>
                <!-- <a href="shop.html" class="nav-link">Shop</a> -->
                <a href="<?php echo BASEPATH ?>aboutus" class="nav-link <?php echo (($data['active_menu']=='aboutus') ? 'active' : '') ?>">About</a>
                <a href="<?php echo BASEPATH ?>contact" class="nav-link <?php echo (($data['active_menu']=='Contact') ? 'active' : '') ?>">Contact</a>
                
            </div>
            
           <div class="nav-icons">
                <?php if(isset($_SESSION['user_session_id'])){ ?>
                <div class="dropdown profile-dropdown">
                    <button class="icon-btn profile-btn dropdown-toggle" 
                            type="button" 
                            id="profileMenuButton" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                    <i class="fas fa-user"></i>
                    </button>
                    <ul class="dropdown-menu profile-menu" aria-labelledby="profileMenuButton" style="list-style:none;">
                    <!-- <li><a href="<?php echo BASEPATH ?>login" class="dropdown-item login-link">Login</a></li> -->
                    <!-- <li><a href="signup.html" class="dropdown-item">Sign Up</a></li> -->
                    <li><a href="account.html" class="dropdown-item account-link">Account Details</a></li>
                    <li><a href="<?php echo BASEPATH ?>home/logout"" class="dropdown-item logout-link">Logout</a></li>
                    </ul>
                </div>
                <?php }else{ ?>
                    <button class="icon-btn profile-btn dropdown-toggle" 
                            type="button" 
                            id="profileMenuButton" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false" onclick="window.location.href='<?php echo BASEPATH; ?>login'">
                        <i class="fas fa-user"></i>
                    </button>
                <?php } ?>

                <a href="<?php echo BASEPATH ?>myaccount/wishlist" class="icon-btn wishlist-btn">
                    <i class="fas fa-heart"></i>
                    <?php if($data['cart']['wishlist']!="0") { ?>
                    <span class="badge" id="wishlistCount"><?php echo $data['cart']['wishlist']!="0" ? $data['cart']['wishlist'] : "" ?></span>
                     <?php } ?>
                </a>

                <button class="icon-btn cart-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if($data['cart']['cart']['total_items']!="0") { ?>
                        <span class="badge" id="count_cart"><?php echo $data['cart']['cart']['total_items']!="0" ? $data['cart']['cart']['total_items'] : "" ?></span>
                    <?php } ?>
                </button>

                <button class="icon-btn cart-btn mobile-only">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <div class="mobile-menu-toggle" id="mobileToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>
    
    <!-- Slide-out Mobile Menu -->
    <div class="mobile-nav-slide" id="mobileNav">
        <span class="close-btn" id="closeMobileNav">&times;</span>
        <div class="nav-search">
            <input type="text" id="searchInput" placeholder="Search for products...">
            <button class="search-btn"><i class="fas fa-search"></i></button>
        </div>
        <a href="<?php echo BASEPATH ?>">Home</a>
        <div class="dropdown">
            <a href="#" class="nav-link dropdown-toggle">Shop<i class="fas fa-chevron-down"></i></a>
            <div class="dropdown-menu">
                <?php echo $data['menu_items']['cat_and_subcat'] ?>
                <!-- <div class="dropdown-submenu">
                    <a href="<?php echo BASEPATH ?>product" class="submenu-toggle">Shirts <i class="fas fa-chevron-right"></i></a>
                    <div class="dropdown-menu">
                        <a href="<?php echo BASEPATH ?>product">Casual Shirts</a>
                        <a href="<?php echo BASEPATH ?>product">Formal Shirts</a>
                        <a href="<?php echo BASEPATH ?>product">T-Shirts</a>
                        <a href="<?php echo BASEPATH ?>product">Polos</a>
                    </div>
                </div>
                <div class="dropdown-submenu">
                    <a href="<?php echo BASEPATH ?>product" class="submenu-toggle">Pants <i class="fas fa-chevron-right"></i></a>
                    <div class="dropdown-menu">
                        <a href="<?php echo BASEPATH ?>product">Jeans</a>
                        <a href="<?php echo BASEPATH ?>product">Chinos</a>
                        <a href="<?php echo BASEPATH ?>product">Trousers</a>
                        <a href="<?php echo BASEPATH ?>product">Shorts</a>
                    </div>
                </div>
                <a href="<?php echo BASEPATH ?>product">Jackets</a>
                <div class="dropdown-submenu">
                    <a href="<?php echo BASEPATH ?>product" class="submenu-toggle">Accessories <i class="fas fa-chevron-right"></i></a>
                    <div class="dropdown-menu">
                        <a href="<?php echo BASEPATH ?>product">Belts</a>
                        <a href="<?php echo BASEPATH ?>product">Hats</a>
                        <a href="<?php echo BASEPATH ?>product">Bags</a>
                        <a href="<?php echo BASEPATH ?>product">Watches</a>
                    </div>
                </div>
                <div class="dropdown-submenu">
                    <a href="<?php echo BASEPATH ?>product" class="submenu-toggle">Shoes <i class="fas fa-chevron-right"></i></a>
                    <div class="dropdown-menu">
                        <a href="<?php echo BASEPATH ?>product">Sneakers</a>
                        <a href="<?php echo BASEPATH ?>product">Formal Shoes</a>
                        <a href="<?php echo BASEPATH ?>product">Loafers</a>
                        <a href="<?php echo BASEPATH ?>product">Sandals</a>
                    </div>
                </div> -->
            </div>
        </div>
        <!-- <a href="shop.html">Shop</a> -->
        <a href="<?php echo BASEPATH ?>aboutus">About</a>
        <a href="<?php echo BASEPATH ?>contact">Contact</a>
        <a href="<?php echo BASEPATH ?>login">Log in / Register</a>
        <!-- <a href="#">Login</a>
        <a href="#">Sign Up</a> -->
    </div>

    <!-- Mobile Search Section -->
    <div class="mobile-search" id="mobileSearch" aria-hidden="true">
        <div class="mobile-search-container">
            <input type="text" id="mobileSearchInput" placeholder="Search for products..." autocomplete="off">
            <button class="close-search" id="closeSearch" aria-label="Close search">&times;</button>
        </div>
    </div>