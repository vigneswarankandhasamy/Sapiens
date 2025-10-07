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
                            <?php if(@$_SESSION['p_'.CUSTOMER_ORDERS] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(CUSTOMER_ORDERS,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>orders">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-offer"></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Customer Orders</h2>
                                                    <p>Manage Customer order List and Details</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.VENDOR_ORDERS] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(VENDOR_ORDERS,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>orders/vendorOrders">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-globe"></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Vendor Orders</h2>
                                                    <p>Manage Vendor order List and Details</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?>

                            <?php if(1!=1) { ?>

                            <!--  <?php if(@$_SESSION['p_'.NEW_ORDERS] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(NEW_ORDERS,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>orders/status/new">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-cart-fill"></em></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>New Orders</h2>
                                                    <p>Manage new orders</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?> -->

                            <!--   <?php if(@$_SESSION['p_'.RETURNED_ORDERS] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(RETURNED_ORDERS,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>orders/status/returned">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-cart"></em></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Returned Orders</h2>
                                                    <p>Manage return orders</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?> -->

                            <!--  <?php if(@$_SESSION['p_'.REJECTED_ORDERS] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(REJECTED_ORDERS,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>orders/rejected">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-cart-fill"></em></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Rejected Order List</h2>
                                                    <p>Manage rejected orders</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?> -->

                            <?php } ?>


                            <?php if(@$_SESSION['p_'.REJECTED_ORDERS] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(REJECTED_ORDERS,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>orders/orderstab">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-cart"></em></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Manage Orders</h2>
                                                    <p>Manage orders tab</p>
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