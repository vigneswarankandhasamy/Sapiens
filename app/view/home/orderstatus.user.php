<?php require_once 'includes/top.php'; ?>
<div class="profile-banner otherpage-banner m-0">
    <img src="<?php echo IMGPATH ?>profile-banner.jpg" alt="">
    <div class="other-banner-title">
        <p>Manage Address</p>
        <button type="button" class="btn btn-sm banner-btn rounded-pill"><a href="<?php echo BASEPATH ?>">View more</a></button>
    </div>   
</div>  
<div class="breadcrumbs_area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="<?php echo BASEPATH ?>">home</a></li>
                        <li><a href="<?php echo BASEPATH ?>myaccount/manageaddress">Manage Address</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contact_area">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="contact_message content">
                    <ul class="edit-profile">
                        <li><a href="<?php echo BASEPATH ?>myaccount/edit">My Profile</a>
                        </li>
                        <li ><a href="<?php echo BASEPATH ?>myaccount/changepassword">Change Password</a>
                        </li>
                        <li ><a href="<?php echo BASEPATH ?>myaccount/manageaddress">Manage Address</a>
                        </li>
                        <li class="active"><a href="<?php echo BASEPATH ?>myaccount/myorders">My Orders</a>
                        </li>
                        <li ><a href="<?php echo BASEPATH ?>myaccount/wishlist">My Wishlist</a>
                        </li>
                        <li><a href="<?php echo BASEPATH ?>home/logout">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!--product items-->
            <div class="col-md-12 col-xs-12">
               
                <div class="row status">
                    <div class="col-md-4">
                        <div class="my_account_forms">
                            <h4>Order Details</h4>
                            <span>Order Id : <?php echo $data['info']['order_uid']; ?>.</span> 
                            <span>Order Date : <?php echo date("d-m-Y",strtotime($data['info']['created_at'])) ; ?>.</span> 
                            <span>Payment : COD  </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="my_account_forms">
                            <h4>Billing Address</h4>
                            <span class="name"><?php echo $data['bill']['user_name']; ?></span>
                            <span><?php echo $data['bill']['address']; ?></span>
                            <span class="name">Phone Number</span>
                            <span><?php echo $data['bill']['mobile']; ?></span>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="my_account_forms">
                            <h4>Shipping Address</h4>
                            <span class="name"><?php echo $data['ship']['user_name']; ?></span>
                            <span><?php echo $data['ship']['address']; ?></span>
                            <span class="name">Phone Number</span>
                            <span><?php echo $data['ship']['mobile']; ?></span>
                        </div>
                    </div>
                </div> 

                <?php echo $data['order_items']; ?>
                
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/bottom.php'; ?>

<script>
function myFunction() {
  setTimeout(function(){ alert("Hello"); }, 3000);
}
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