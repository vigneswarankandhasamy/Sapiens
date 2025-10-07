<?php require_once 'includes/top.php'; ?> 

    <!-- content @s -->
    <div class="nk-content nk-content-fluid">
        <div class="container-fluid wide-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="row">
                            <div class="col-md-2">
                                <ul class="left_menu">
                                    <!-- <li>
                                        <a href="<?php echo COREPATH ?>trash/" class="<?php echo (($data['trash_menu']=="attribute")?'active' : '') ?>">Attribute</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/" class="<?php echo (($data['trash_menu']=="attribute_groups")?'active' : '') ?>">Attribute Groups</a>
                                    </li>
                                    
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/" class="<?php echo (($data['trash_menu']=="sub_category")?'active' : '') ?>">Sub Category</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/" class="<?php echo (($data['trash_menu']=="products")?'active' : '') ?>">Products</a>
                                    </li> -->
                                   
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash" class="<?php echo (($data['trash_menu']=="users")?'active' : '') ?>">Users</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/attribute" class="<?php echo (($data['trash_menu']=="attribute")?'active' : '') ?>">Attribute</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/attributegroup" class="<?php echo (($data['trash_menu']=="attributegroup")?'active' : '') ?>">Attribute Group</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/blog" class="<?php echo (($data['trash_menu']=="blog")?'active' : '') ?>">Blog</a>
                                    </li>
                                     <li>
                                        <a href="<?php echo COREPATH ?>trash/blogcategory" class="<?php echo (($data['trash_menu']=="blog_category")?'active' : '') ?>">Blog Category</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/brand" class="<?php echo (($data['trash_menu']=="brand")?'active' : '') ?>">Brand </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/category" class="<?php echo (($data['trash_menu']=="category")?'active' : '') ?>">Category </a>
                                    </li>
                                     <li>
                                        <a href="<?php echo COREPATH ?>trash/subcategory" class="<?php echo (($data['trash_menu']=="subcategory")?'active' : '') ?>">Subcategory </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/coupon" class="<?php echo (($data['trash_menu']=="coupon")?'active' : '') ?>">Coupons </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/homeslider" class="<?php echo (($data['trash_menu']=="homeslider")?'active' : '') ?>">Home Sliders</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/offerbanner" class="<?php echo (($data['trash_menu']=="offerbanner")?'active' : '') ?>">Offer Banner</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/legalpages" class="<?php echo (($data['trash_menu']=="legalpages")?'active' : '') ?>">Legal Pages </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/product" class="<?php echo (($data['trash_menu']=="product")?'active' : '') ?>">Products</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/productreview" class="<?php echo (($data['trash_menu']=="productreview")?'active' : '') ?>">Product Reviews</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/seo" class="<?php echo (($data['trash_menu']=="seo")?'active' : '') ?>">SEO Content </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/testimonials" class="<?php echo (($data['trash_menu']=="testimonials")?'active' : '') ?>">Testimonials</a>
                                    </li>
                                     <li>
                                        <a href="<?php echo COREPATH ?>trash/locationcity" class="<?php echo (($data['trash_menu']=="locationcity")?'active' : '') ?>">City</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/locationgroup" class="<?php echo (($data['trash_menu']=="locationgroup")?'active' : '') ?>">Location Group</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/location" class="<?php echo (($data['trash_menu']=="location")?'active' : '') ?>">Location</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/pagebanner" class="<?php echo (($data['trash_menu']=="pagebanner")?'active' : '') ?>">Page Banner</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/state" class="<?php echo (($data['trash_menu']=="state")?'active' : '') ?>">State</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/branch" class="<?php echo (($data['trash_menu']=="branch")?'active' : '') ?>">Branch</a>
                                    </li>
                                     <li>
                                        <a href="<?php echo COREPATH ?>trash/vendor" class="<?php echo (($data['trash_menu']=="vendor")?'active' : '') ?>">Vendor</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/notificationemail" class="<?php echo (($data['trash_menu']=="notificationemail")?'active' : '') ?>">Notification Email</a>
                                    </li> 
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/newsletter" class="<?php echo (($data['trash_menu']=="newsletter")?'active' : '') ?>">Newsletter</a>
                                    </li> 
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/returnreason" class="<?php echo (($data['trash_menu']=="returnreason")?'active' : '') ?>">Return Reason</a>
                                    </li>
                                     <li>
                                        <a href="<?php echo COREPATH ?>trash/returnsettings" class="<?php echo (($data['trash_menu']=="returnsettings")?'active' : '') ?>">Return Duration Setting</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/classifiedprofile" class="<?php echo (($data['trash_menu']=="classifiedprofile")?'active' : '') ?>">Classified Profile</a>
                                    </li> 
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/classifiedproject" class="<?php echo (($data['trash_menu']=="classifiedproject")?'active' : '') ?>">Classified Projects</a>
                                    </li> 
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/workwithusreuest" class="<?php echo (($data['trash_menu']=="workwithusreuest")?'active' : '') ?>">Work With Us Request</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/productrequeststatus" class="<?php echo (($data['trash_menu']=="productrequeststatus")?'active' : '') ?>">Product Request Status</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/orderresponsestatus" class="<?php echo (($data['trash_menu']=="orderresponsestatus")?'active' : '') ?>">Order Response Status</a>
                                    </li> 
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/productdisplaytag" class="<?php echo (($data['trash_menu']=="productdisplaytag")?'active' : '') ?>">Product Display Tag</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>trash/productunit" class="<?php echo (($data['trash_menu']=="productunit")?'active' : '') ?>">Product Unit</a>
                                    </li> 
                                     
                                    
                                </ul>

                                </ul>
                            </div>
                            <div class="col-md-10">
                                <div class="settings_section_wrap">
                                    <div class="nk-block-head-content">
                                        <h3 class="nk-block-title page-title"> <?php echo $data['page_title'] ?></h3>
                                        <div class="nk-block-des">
                                            <nav>
                                                <ul class="breadcrumb breadcrumb-arrow">
                                                    <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a></li>
                                                    <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>settings">Settings</a></li>
                                                    <li class="breadcrumb-item active">Trash</li>
                                                    <li class="breadcrumb-item active"><?php echo $data['page_title'] ?></li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-shadow">
                                    <div class="card-inner">
                                        <table class="datatable-init nk-tb-list nk-tb-ulist is-compact" data-auto-responsive="false">
                                                <?php echo $data['list'] ?>
                                        </table>
                                    </div>
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

<script type="text/javascript">
    
    // Restore Item

    $(".restoreItem").click(function(e) {
        toastr.clear();
        var value = $(this).data("option");
        var form  = $(this).data("form");
        Swal.fire({
            title: "Are you sure to restore?",
            text: "Once restored the item will be published with the last Visibility status.",
            icon: 'warning',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.value) {
                
                var item_id          = $(this).data("item_id");
                var controller_name  = $(this).data("controller_name");
                var api_case         = $(this).data("api_case");
                var redirect_link    = $(this).data("redirect_link");
                $.ajax({
                    type: "POST",
                    url: core_path + controller_name + "/api/" + api_case,
                    dataType: "html",
                    data: { result: item_id },
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            if (redirect_link =='users') {
                                 redirect_link = '';
                            }
                            window.location = core_path + "trash/"+ redirect_link +"?r=success";
                        } else {
                            toastr.clear();
                            NioApp.Toast('<h5>'+data+'</h5>', 'error', {
                                position: 'bottom-center', 
                                ui: 'is-light',
                                "progressBar": true,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "4000"
                            });
                        }
                    }
                });
            }
        });
        e.preventDefault();
        return false;
    });


</script>

<?php if (isset($_GET['r'])): ?>
    <script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        toastr.clear();
        NioApp.Toast('<h5>Item restored successfully !!</h5>', 'success', {
            position: 'bottom-center', 
            ui: 'is-light',
            "progressBar": true,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "4000"
        }); 
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
    </script>
<?php endif ?>