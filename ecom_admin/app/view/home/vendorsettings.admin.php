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
                            <?php if(@$_SESSION['p_'.VENDORS] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(VENDORS,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>vendor">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-users"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Vendors</h2>
                                            <p>Manage Vendors List (Add, Delete and Details)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.VENDORS] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(VENDORS,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>vendor/ratings">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-star-fill"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Vendors Rating</h2>
                                            <p>Vendors Ratings List<p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>

                                                        <?php if(@$_SESSION['p_'.VENDOR_PAYOUTS] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(VENDOR_PAYOUTS,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>orders/vendorpayouts">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-list-thumb-fill"></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Vendor Payouts</h2>
                                                    <p>Manage Vendor Charges List</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.VENDOR_PAIED_HISTORY] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(VENDOR_PAIED_HISTORY,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>orders/payouthistory/paid">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-list-thumb-fill"></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Vendor Paid History</h2>
                                                    <p>Manage Vendor Paid Charges List</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?>


                            <?php if(@$_SESSION['p_'.VENDOR_UNPAIED_HISTORY] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(VENDOR_UNPAIED_HISTORY,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>orders/payouthistory/unpaid">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-masonry-fill"></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Vendor Unpaid History</h2>
                                                    <p>Manage Vendor Unpaid Charges List</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.VENDOR_UNPAIED_HISTORY] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(VENDOR_UNPAIED_HISTORY,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>vendor/expired">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-report"></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Expired Vendor List</h2>
                                                    <p>Manage Expired Vendor List</p>
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