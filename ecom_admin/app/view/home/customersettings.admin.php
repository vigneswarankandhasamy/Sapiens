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
                            <?php if(@$_SESSION['p_'.CUSTOMERS] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(PRODUCTS,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>customers">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-offer"></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>All Customers</h2>
                                                    <p>Manage Customers</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.ABANDONED_CHECKOUTS] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(ABANDONED_CHECKOUTS,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>abandonedcheckouts">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-img"></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Abandoned checkouts</h2>
                                                    <p>Manage Customer added cart items without order placed</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.CUSTOMER_WISHLIST] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(CUSTOMER_WISHLIST,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>wishlist">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-heart"></em></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Customer Wishlist</h2>
                                                    <p>Manage customer wishlist</p>
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