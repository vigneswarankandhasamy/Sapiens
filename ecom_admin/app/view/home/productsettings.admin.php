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
                            <?php if(@$_SESSION['p_'.'p_'.PRODUCTS] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(PRODUCTS,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>products">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-offer"></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>All Products</h2>
                                                    <p>Manage Product (Add, Edit and View)</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.'p_'.CATEGORY] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(CATEGORY,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>category">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-globe"></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Category</h2>
                                                    <p>Manage Main Category (Add, Edit and View)</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.SUB_CATEGORY] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(SUB_CATEGORY,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>subcategory">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-img"></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Sub Category</h2>
                                                    <p>Manage Subcategory (Add, Edit and View)</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.ATTRIBUTES] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(ATTRIBUTES,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>attribute">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-list-thumb-fill"></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Attributes</h2>
                                                    <p>Manage Attribute and Attribute Group (Add, Edit and View)</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.COUPONS] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(COUPONS,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>coupon">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-list-thumb-fill"></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Coupons</h2>
                                                    <p>Manage Coupons (Add, Edit and View)</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?>


                            <?php if(@$_SESSION['p_'.STOCK_INVENTORY] || @$_SESSION['is_super_admin']==1) { ?>
                                <?php if (in_array(STOCK_INVENTORY,($data['sitesettings']))): ?>
                                    <div class="col-md-4">
                                        <div class="settings_block">
                                            <a href="<?php echo COREPATH ?>products/stockinventor">
                                                <div class="icon">
                                                    <h1><em class="icon ni ni-masonry-fill"></em></h1>
                                                </div>
                                                <div class="content">
                                                    <h2>Stock/ Inventory</h2>
                                                    <p>Manage Vendor Product Stock List</p>
                                                </div>
                                                <span class="clearfix"></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.BRAND] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(BRAND,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>brand">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-tag"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Brand</h2>
                                            <p>Manage Brands(Add, Edit and Delete)</p>
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
                                    <a href="<?php echo COREPATH ?>products/unit">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-ticket"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Product Unit</h2>
                                            <p>Add, Edit and Manage Page Unit </p>
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