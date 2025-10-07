<?php require_once 'includes/top.php'; ?>

    <!-- content @s -->
    <div class="nk-content nk-content-fluid">
        <div class="container-fluid wide-md">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title"><?php echo $data['page_title'] ?></h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="row">

                            <?php if(@$_SESSION['p_'.COMPANY_PROFILE] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(COMPANY_PROFILE,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>company/profile">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-building"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Company Profile</h2>
                                            <p>Manage Company details and vendor Charges(Edit)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.USERS] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(USERS,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>users">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-user-list-fill"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Users</h2>
                                            <p>Add, Edit and Manage user permissions for the admin</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>


                            <?php if(@$_SESSION['p_'.TAXES] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(TAXES,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>tax">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-percent"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Taxes</h2>
                                            <p>Manage how your store charges taxes</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.NOTIFICATION_EMAILS] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(NOTIFICATION_EMAILS,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>notificationemail">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-bell"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Notifications Emails</h2>
                                            <p>Manage Received Notification Emails</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>
                          

                            <?php if(@$_SESSION['p_'.PAGINATION] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(PAGINATION,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>pagination">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-list-ol"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Pagination</h2>
                                            <p>Add, Edit and Manage Page Pagination Count </p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.PRODUCT_UNIT] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(PRODUCT_UNIT,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>products/displaytag">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-tag-fill"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Product Display Tag</h2>
                                            <p>Add, Edit and Manage Product Display Tag </p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>

                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>filtergroup">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-search"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Filter Group</h2>
                                            <p>Add, Edit and filter groups </p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>filter">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-search"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Filter </h2>
                                            <p>Add, Edit and filter  </p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>

                            <?php if(@$_SESSION['p_'.TRASH] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(TRASH,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>trash">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-trash"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Trash</h2>
                                            <p>Manage Trash(Recovery deleted content)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>

                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    <!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>