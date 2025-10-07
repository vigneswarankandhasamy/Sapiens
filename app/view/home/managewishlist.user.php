<?php require_once 'includes/top.php'; ?>
<!-- <div class="profile-banner otherpage-banner m-0">
    <img src="<?php echo $data['page_banner']!="" ? SRCIMG.$data['page_banner']['file_name'] : IMGPATH."profile-banner.jpg" ?>" alt="image" class="common-banner">
    <div class="other-banner-title">
        <p>Manage Wishlist</p>
    </div>   
</div>   -->
<div class="breadcrumbs_area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <!-- this menu items get in top.php (My Account Breadcrum Menus) -->
                    <?php echo $myaccount_breadcurm; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contact_area manageaddress">
    <div class="container-lg">
        <div class="row">
           <!--  <div class="col-lg-3 col-md-3">
                <div class="contact_message content">
                    <ul class="edit-profile">
                        <li><a href="<?php echo BASEPATH ?>myaccount/edit">My Profile</a>
                        </li>
                        <li ><a href="<?php echo BASEPATH ?>myaccount/changepassword">Change Password</a>
                        </li>
                        <li ><a href="<?php echo BASEPATH ?>myaccount/manageaddress">Manage Address</a>
                        </li>
                        <li><a href="<?php echo BASEPATH ?>myaccount/myorders">My Orders</a>
                        </li>
                        <li class="active"><a href="<?php echo BASEPATH ?>myaccount/wishlist">My Wishlist</a>
                        </li>
                        <li><a href="<?php echo BASEPATH ?>home/logout">Logout</a>
                        </li>
                    </ul>
                </div>
            </div> -->
            <!--product items-->
            <div class="col-md-12 col-xs-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="list_wrap">
                              <div class="row">
                                 <?php echo $data['list'] ?>     
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

$('.addToWishList').click(function() {
        
        var id          = $(this).data("id");
        var vendor_id   = $(this).data("vendor_id");
        var variant_id  = $(this).data("variant_id");

        $.ajax({
                type: "POST",
                url: base_path + "product/api/addToWishList",
                dataType: "html",
                data: { 
                        product_id  : id,
                        vendor_id   : vendor_id,
                        variant_id  : variant_id
                      },
                    success: function (data) {
                        $(".page_loading").hide();
                        console.log(data);
                        data  = data.split("`");
                        if (data[0] == 0) {
                            window.location = base_path + "myaccount/wishlist?r=success";
                        } else {
                              setTimeout(function() {
                              new Noty({
                                  text: '<strong>Error Occurred</strong>!',
                                  type: 'warning',
                                  theme: 'relax',
                                  layout: 'bottomCenter',
                                  timeout: 3000
                              }).show();
                          }, 300);
                        }
                    }
                });
        return false;
    });

 

</script>


 <?php if (isset($_GET['r'])): ?>
    <script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Product has been Removed from wishlist!</strong>!',
            type: 'warning',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
    </script>
    <?php endif ?>