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
                               
                                <?php if(@$_SESSION['p_'.DASHBOARD] || @$_SESSION['is_super_admin']==1) { ?>
                                <li class="nk-menu-item <?php echo (($data['active_menu']=='dashboard') ? 'active current-page' : '') ?> ">
                                    <a href="<?php echo COREPATH ?>" class="nk-menu-link">
                                        <span class="nk-menu-text"> Home</span>
                                    </a>
                                </li>
                                <?php } ?>


                                <?php if(@$_SESSION['p_'.PRODUCT_MENU] || @$_SESSION['is_super_admin']==1) { ?>
                                    <?php if (in_array(PRODUCT_MENU,($data['sitesettings']))): ?>
                                        <li class="nk-menu-item <?php echo (($data['active_menu']=='productsettings') ? 'active current-page' : '') ?>">
                                            <a href="<?php echo COREPATH ?>productsettings" class="nk-menu-link">
                                                <span class="nk-menu-text"> Products</span>
                                            </a>
                                        </li>
                                    <?php endif ?>
                                <?php } ?>


                                <?php if(@$_SESSION['p_'.CUSTOMERS] || @$_SESSION['is_super_admin']==1) { ?>
                                    <?php if (in_array(CUSTOMERS,($data['sitesettings']))): ?>
                                        <li class="nk-menu-item <?php echo (($data['active_menu']=='customers') ? 'active current-page' : '') ?>">
                                            <a href="<?php echo COREPATH ?>customersettings" class="nk-menu-link">
                                                <span class="nk-menu-text"> Customers</span>
                                            </a>
                                        </li>
                                    <?php endif ?>
                                <?php } ?>


                                <?php if(@$_SESSION['p_'.ORDERS] || @$_SESSION['is_super_admin']==1) { ?>
                                    <?php if (in_array(ORDERS,($data['sitesettings']))): ?>
                                        <li class="nk-menu-item <?php echo (($data['active_menu']=='ordersettings') ? 'active current-page' : '') ?>">
                                            <a href="<?php echo COREPATH ?>ordersettings" class="nk-menu-link">
                                                <span class="nk-menu-text">Orders</span>
                                            </a>
                                        </li>
                                    <?php endif ?>
                                <?php } ?>


                                <?php if(@$_SESSION['p_'.MARKETING] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(MARKETING,($data['sitesettings']))): ?>
                                <li class="nk-menu-item <?php echo (($data['active_menu']=='marketing') ? 'active current-page' : '') ?>">
                                    <a href="<?php echo COREPATH ?>marketing" class="nk-menu-link">
                                        <span class="nk-menu-text">Marketing</span>
                                    </a>
                                </li>
                                <?php endif ?>
                                <?php } ?>

                                <?php if(@$_SESSION['p_'.STORE] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(STORE,($data['sitesettings']))): ?>
                                <li class="nk-menu-item <?php echo (($data['active_menu']=='store') ? 'active current-page' : '') ?>">
                                    <a href="<?php echo COREPATH ?>store" class="nk-menu-link">
                                        <span class="nk-menu-text"> Store</span>
                                    </a>
                                </li>
                                <?php endif ?>
                                <?php } ?>
                                <?php if(@$_SESSION['p_'.REPORTS] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(REPORTS,($data['sitesettings']))): ?>
                                <li class="nk-menu-item <?php echo (($data['active_menu']=='reports') ? 'active current-page' : '') ?>">
                                    <a href="<?php echo COREPATH ?>reports" class="nk-menu-link">
                                        <span class="nk-menu-text"> Reports</span>
                                    </a>
                                </li>
                                <?php endif ?>
                                <?php } ?>
                                <?php if(@$_SESSION['p_'.SETTINGS] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(SETTINGS,($data['sitesettings']))): ?>
                                <li class="nk-menu-item <?php echo (($data['active_menu']=='settings') ? 'active current-page' : '') ?>">
                                    <a href="<?php echo COREPATH ?>settings" class="nk-menu-link">
                                        <span class="nk-menu-text"> Settings</span>
                                    </a>
                                </li>
                                <?php endif ?>
                                <?php } ?>
    
                               
                            </ul><!-- .nk-menu -->
                        </div><!-- .nk-header-menu -->
                        <div class="nk-header-tools" style="margin-left:initial !important;">
                            <ul class="nk-quick-nav">
                                <li class="dropdown user-dropdown order-sm-first">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <div class="user-toggle">
                                            <div class="user-avatar sm">
                                                <em class="icon ni ni-user-alt"></em>
                                            </div>
                                            <div class="user-info d-none d-xl-block">
                                                <div class="user-name dropdown-indicator"><?php echo $data['user_info']['name'] ?></div>
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
                                                    <span class="lead-text"><?php echo $data['user_info']['name'] ?></span>
                                                    <span class="sub-text"><?php echo $data['user_info']['email'] ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-inner">
                                            <ul class="link-list">
                                                <li><a href="<?php echo COREPATH ?>profile"><em class="icon ni ni-user-alt"></em><span>My Profile</span></a></li>
                                                <li><a href="<?php echo COREPATH ?>profile/security"><em class="icon ni ni-setting-alt"></em><span>Account Setting</span></a></li>
                                                <li><a href="<?php echo COREPATH ?>profile/loginactivity"><em class="icon ni ni-activity-alt"></em><span>Login Activity</span></a></li>
                                                 <?php if(@$_SESSION['is_super_admin']==1) { ?>
                                                    <li><a href="<?php echo COREPATH ?>sitesettings"><em class="icon ni ni-user-alt"></em><span>Site Settings</span></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="dropdown-inner">
                                            <ul class="link-list">
                                                <li><a href="<?php echo COREPATH ?>home/logout"><em class="icon ni ni-signout"></em><span>Sign out</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li><!-- .dropdown -->
                            </ul><!-- .nk-quick-nav -->
                        </div><!-- .nk-header-tools -->
                    </div><!-- .nk-header-wrap -->
                </div><!-- .container-fliud -->
            </div>
            <!-- main header @e -->