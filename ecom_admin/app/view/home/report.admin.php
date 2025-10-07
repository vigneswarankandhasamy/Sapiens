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
                            <?php if(@$_SESSION['p_order_report'] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array('reports',($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>reports/order">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-file-docs"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Order Report </h2>
                                            <p>Manage Orders Report(From and To date)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>
                

                            <?php if(@$_SESSION['p_product_report'] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array('reports',($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>reports/product">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-offer"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Product Report </h2>
                                            <p>Manage Product Report(Vendor, From and To date)</p>
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