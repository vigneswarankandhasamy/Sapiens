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
                            <?php if(@$_SESSION['p_'.SEO] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(SEO,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>seo">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-globe"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>SEO </h2>
                                            <p>Manage SEO content List(Add, Edit and Delete)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>
                            <?php if(@$_SESSION['p_'.HOME_SLIDERS] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(HOME_SLIDERS,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>homeslider">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-img"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Home Sliders</h2>
                                            <p>Manage Home Page Banner List(Add, Edit and Delete)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>
                            <?php if(@$_SESSION['p_'.OFFER_BANNER] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(OFFER_BANNER,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>offerbanner">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-list-thumb-fill"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Offer Banners</h2>
                                            <p>Manage Offer Banner List(Add, Edit and Delete)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.PAGE_BANNER] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(PAGE_BANNER,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>pagebanner">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-list-thumb-fill"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Page Banners</h2>
                                            <p>Manage Page Banner List(Add, Edit and Delete)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>

                            <?php if(@$_SESSION['p_'.SPECIAL_BANNER] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(SPECIAL_BANNER,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>specialofferbanner">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-list-thumb-fill"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Special Offer Banners</h2>
                                            <p>Manage Special Offer Page Banner List(Edit and status)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>


                            <?php if(@$_SESSION['p_'.BLOG] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(BLOG,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>blog">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-masonry-fill"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Blog </h2>
                                            <p>Manage Blog List(Add, Edit and Delete)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>
                            <?php if(@$_SESSION['p_'.BLOG_CATEGORIES] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(BLOG_CATEGORIES,($data['sitesettings']))): ?>
                             <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>blog/category">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-align-left"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Blog Categories</h2>
                                            <p>Manage Blog Categories(Add, Edit and Delete)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>
                            <?php if(@$_SESSION['p_'.LEGAL_PAGES] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(LEGAL_PAGES,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>legalpages">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-file-docs"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Legal pages </h2>
                                            <p>Manage Legal Pages(Add, Edit and Delete)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>
                            <?php if(@$_SESSION['p_'.MANAGE_TESTIMONIALS] || @$_SESSION['is_super_admin']==1) { ?>
                            <?php if (in_array(MANAGE_TESTIMONIALS,($data['sitesettings']))): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>testimonials">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-user-check"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Testimonials</h2>
                                            <p>Manage Testimonial(Add, Edit and Delete)</p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php } ?>
                            
                            <?php if (in_array('navigation',($data['sitesettings']))): ?>
                            <?php if (1!=1): ?>
                            <div class="col-md-4">
                                <div class="settings_block">
                                    <a href="<?php echo COREPATH ?>">
                                        <div class="icon">
                                            <h1><em class="icon ni ni-network"></em></h1>
                                        </div>
                                        <div class="content">
                                            <h2>Navigation</h2>
                                            <p>Add, Edit and Manage menu items </p>
                                        </div>
                                        <span class="clearfix"></span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php endif ?>
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    <!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>