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
                            <?php if(@$_SESSION['p_'.HIRE] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(HIRE,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>hire">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-users-fill"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Expert </h2>
                                            <p>Manage Expert List (Add, Delete and Details)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.HIRE_PROFILE] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(HIRE_PROFILE,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>hireprofile">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-setting"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Expert Categories</h2>
                                            <p>Manage Expert Profile Type List (Add, Edit and Delete)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>


                            <?php if(@$_SESSION['p_'.HIRE_PROJECT] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(HIRE_PROJECT,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>hire/projects">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-property"></em></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Expert Projects </h2>
                                            <p>Manage Expert Projects List</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.HIRE_CONTACT_VIEWED] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(HIRE_CONTACT_VIEWED,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>hire/contactviewed">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-property"></em></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Expert Contact Viewed </h2>
                                            <p>Manage Expert Contact Viewed List</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.SERVICE_TAGS] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(SERVICE_TAGS,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>hire/servicetags">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-plus-fill-c"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Service Tags</h2>
                                            <p>Add, Edit and Manage Expert Service Tags </p>
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