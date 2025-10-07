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
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>orders/payouts/paid">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-offer"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Paid History</h2>
                                            <p>Manage paid payouts</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>orders/payouts/unpaid">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-globe"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Unpaid History</h2>
                                            <p>Manage unpaid payouts</p>
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