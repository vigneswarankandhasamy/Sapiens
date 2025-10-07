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
                            <?php if(@$_SESSION['p_'.CONTACT_ENQIRY] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(CONTACT_ENQIRY,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>enquiry/contact">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-building"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Contact Enquiry</h2>
                                            <p>Manage Contact Enquiry (List and Delete)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.NEWSLETTER] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(NEWSLETTER,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>enquiry/newsletter">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-user-list-fill"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>News Letter</h2>
                                            <p>Manage News Letter (List and Delete)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.PRODUCT_REVIEW] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(PRODUCT_REVIEW,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>review">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-comments"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Product Reviews</h2>
                                            <p>Manage Product Reviews (List, Delete and Admin Replay)</p>
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