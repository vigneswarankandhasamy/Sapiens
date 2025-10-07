<?php require_once 'metacontent.php'; ?> 

<body class="nk-body npc-invest bg-lighter ">

     <div class="page_loading">
        <div class="loading_image">
            <p class="load_img"><img  src="<?php echo ASSETS_PATH?>loader.gif" width="80"></p>
            <p class="load_text">Please Wait...</p>
        </div>
    </div>

    <div class="nk-app-root">
        <!-- wrap @s -->
        <div class="nk-wrap ">
            <!-- main header @s -->
            <div class="nk-header nk-header-fluid bg-white nk-header-fixed">
                <div class="container-xl wide-xl">
                    <div class="nk-header-wrap hdeader_nav_bar">
                        <div class="nk-menu-trigger mr-sm-2 d-lg-none">
                            <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="headerNav"><em class="icon ni ni-menu"></em></a>
                        </div>
                        <div class="nk-header-brand">
                            <a href="<?php echo COREPATH ?>" class="logo-link">
                                <img class="logo-dark logo-img" src="<?php echo ASSETS_PATH ?>logo.png"  alt="">
                            </a>
                        </div><!-- .nk-header-brand -->
                        <div class="nk-header-menu" data-content="headerNav">
                            <div class="nk-header-mobile">
                                <div class="nk-header-brand">
                                    <a href="<?php echo COREPATH ?>" class="logo-link">
                                        <img class="logo-light logo-img" src="<?php echo IMGPATH ?>logo.png" srcset="<?php echo IMGPATH ?>logo2x.png 2x" alt="logo">
                                        <img class="logo-dark logo-img" src="<?php echo IMGPATH ?>logo-dark.png" srcset="<?php echo IMGPATH ?>logo-dark2x.png 2x" alt="logo-dark">
                                    </a>
                                </div>
                                <div class="nk-menu-trigger mr-n2">
                                    <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="headerNav"><em class="icon ni ni-arrow-left"></em></a>
                                </div>
                            </div>
                            <ul class="nk-menu nk-menu-main ui-s2">
                                <!--  <li class="nk-menu-item <?php echo (($data['active_menu']=='products') ? 'active current-page' : '') ?> ">
                                    <a href="<?php echo COREPATH ?>products" class="nk-menu-link">
                                        <span class="nk-menu-text">Products</span>
                                    </a>
                                </li> -->

                                <li class="nk-menu-item <?php echo (($data['active_menu']=='dashboard') ? 'active current-page' : '') ?> ">
                                    <a href="<?php echo COREPATH ?>home" class="nk-menu-link">
                                        <span class="nk-menu-text">Dashboard</span>
                                    </a>
                                </li>


                                <!--  <li class="nk-menu-item <?php echo (($data['active_menu']=='catalogue') ? 'active current-page' : '') ?> ">
                                    <a href="<?php echo COREPATH ?>products/catalogue" class="nk-menu-link">
                                        <span class="nk-menu-text">Catalogue</span>
                                    </a>
                                </li> -->


                                <li class="nk-menu-item <?php echo (($data['active_menu']=='inventorysettings') ? 'active current-page' : '') ?> ">
                                    <a href="<?php echo COREPATH ?>inventorysettings" class="nk-menu-link">
                                        <span class="nk-menu-text">Inventory</span>
                                    </a>
                                </li>


                                <li class="nk-menu-item <?php echo (($data['active_menu']=='orders') ? 'active current-page' : '') ?> ">
                                    <a href="<?php echo COREPATH ?>ordersettings" class="nk-menu-link">
                                        <span class="nk-menu-text">Orders</span>
                                    </a>
                                </li>

                                <li class="nk-menu-item <?php echo (($data['active_menu']=='reports') ? 'active current-page' : '') ?> ">
                                    <a href="<?php echo COREPATH ?>reports" class="nk-menu-link">
                                        <span class="nk-menu-text">Reports</span>
                                    </a>
                                </li>


                                <li class="nk-menu-item <?php echo (($data['active_menu']=='payoutsettings') ? 'active current-page' : '') ?> ">
                                    <a href="<?php echo COREPATH ?>payoutsettings" class="nk-menu-link">
                                        <span class="nk-menu-text">Payouts</span>
                                    </a>
                                </li>


                               <!--  <li class="nk-menu-item has-sub <?php echo (($data['active_menu']=='payouts') ? 'active current-page' : '') ?>">
                                    <a href="" class="nk-menu-link nk-menu-toggle ">
                                        <span class="nk-menu-text"> Payouts</span>
                                    </a>
                                    <ul class="nk-menu-sub">
                                        <li class="nk-menu-item">
                                            <a href="<?php echo COREPATH ?>orders/payouts/paid" class="nk-menu-link">
                                                <span class="nk-menu-text">Paid History</span>
                                            </a>
                                        </li>
                                        
                                        <li class="nk-menu-item">
                                            <a href="<?php echo COREPATH ?>orders/payouts/unpaid" class="nk-menu-link ">
                                                <span class="nk-menu-text ">Unpaid History</span> 
                                            </a>
                                        </li>
                                    </ul>
                                </li> -->

                                <li class="nk-menu-item <?php echo (($data['active_menu']=='locations') ? 'active current-page' : '') ?> ">
                                    <a href="<?php echo COREPATH ?>locations" class="nk-menu-link">
                                        <span class="nk-menu-text">Locations</span>
                                    </a>
                                </li>

                            </ul><!-- .nk-menu -->
                        </div><!-- .nk-header-menu -->

                        <div class="nk-header-tools" style="margin-left:initial !important;">
                            <ul class="nk-quick-nav">
                                <li class="dropdown notification-dropdown order-sm-last notification">
                                    <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
                                        <?php if($data['notification']['count']!=0) { ?>
                                            <span class="badge"><?php echo $data['notification']['count']; ?></span>
                                        <?php } ?>
                                            <em class="icon ni ni-bell"></em>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right noti_icon_active dropdown-menu-s1">
                                        <div class="dropdown-head">
                                            <span class="sub-title nk-dropdown-title">Notifications</span>
                                            <!-- <a href="#">Mark All as Read</a> -->
                                        </div>
                                        <div class="dropdown-body">
                                            <div class="nk-notification">
                                                <?php echo $data['notification']['layout']; ?>
                                            </div><!-- .nk-notification -->
                                        </div><!-- .nk-dropdown-body -->
                                        <div class="dropdown-foot center">
                                            <a href="<?php echo COREPATH ?>orders/notification">View All</a>
                                        </div>
                                    </div>
                                </li><!-- .dropdown --> 
                                <li class="dropdown user-dropdown order-sm-second">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <div class="user-toggle">
                                            <div class="user-avatar sm">
                                                <em class="icon ni ni-user-alt"></em>
                                            </div>
                                            <div class="user-info d-none d-xl-block">
                                                <div class="user-name dropdown-indicator"><?php echo $data['user_info']['company'] ?></div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1 is-light">
                                        <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                            <div class="user-card">
                                                <div class="user-avatar">
                                                    <span>AB</span>
                                                </div>
                                                <div class="user-info">
                                                    <span class="lead-text"><?php echo $data['user_info']['company'] ?></span>
                                                    <span class="sub-text"><?php echo $data['user_info']['email'] ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-inner">
                                            <ul class="link-list">

                                                <li><a href="<?php echo COREPATH ?>profile"><em class="icon ni ni-setting-alt"></em><span>Account Setting</span></a></li>
                                                
                                                <li><a href="<?php echo COREPATH ?>profile/loginactivity"><em class="icon ni ni-activity-alt"></em><span>Login Activity</span></a></li>


                                                <!-- <li><a href="<?php echo COREPATH ?>profile"><em class="icon ni ni-user-alt"></em><span>My Profile</span></a></li>
                                                <li><a href="<?php echo COREPATH ?>profile/security"><em class="icon ni ni-setting-alt"></em><span>Account Setting</span></a></li>
                                                <li><a href="<?php echo COREPATH ?>profile/loginactivity"><em class="icon ni ni-activity-alt"></em><span>Login Activity</span></a></li>
                                                <li><a class="dark-mode-switch" href="#"><em class="icon ni ni-moon"></em><span>Dark Mode</span></a></li> -->
                                            </ul>
                                        </div>
                                        <div class="dropdown-inner">
                                            <ul class="link-list">
                                                <li><a href="<?php echo COREPATH ?>home/logout"><em class="icon ni ni-signout"></em><span>Sign out</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li><!-- .dropdown -->
                                <li class="dropdown user-dropdown order-sm-first">
                                    <?php echo $data['vendor_active'] ?>
                                </li>
                            </ul><!-- .nk-quick-nav -->
                        </div><!-- .nk-header-tools -->
                    </div><!-- .nk-header-wrap -->
                </div><!-- .container-fliud -->
            </div>
            <!-- main header @e -->