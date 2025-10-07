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
                            <?php if(1!=1) { ?>
                            <!-- <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>orders">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-offer"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Manage Orders</h2>
                                            <p>Manage Orders</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>orders/notification/neworders">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-cart-fill"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>New Orders</h2>
                                            <p>Manage New Orders</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>orders/notification/returnedorders">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-cart"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Returned Orders</h2>
                                            <p>Manage Returned Orders</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div> -->
                            <?php } ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>orders/vendorordertab">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-cart"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Manage Orders</h2>
                                            <p>Manage Orders Tab</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    <!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>