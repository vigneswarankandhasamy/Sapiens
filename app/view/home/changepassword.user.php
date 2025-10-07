<?php require_once 'includes/top.php'; ?>
<div class="profile-banner otherpage-banner m-0">
    <img src="<?php echo $data['page_banner']!="" ? SRCIMG.$data['page_banner']['file_name'] : IMGPATH."profile-banner.jpg" ?>" alt="image" class="common-banner">
    <div class="other-banner-title">
        <p>Change Password</p>
        <!-- <button type="button" class="btn btn-sm banner-btn rounded-pill"><a href="<?php echo BASEPATH ?>">View more</a></button> -->
    </div>   
</div>  
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
    <div class="container">
        <div class="row">
            <!-- <div class="col-lg-3 col-md-3">
                <div class="contact_message content">
                    <ul class="edit-profile">
                        <li><a href="<?php echo BASEPATH ?>myaccount/edit">My Profile</a>
                        </li>
                        <li class="active"><a href="<?php echo BASEPATH ?>myaccount/changepassword">Change Password</a>
                        </li>
                        <li><a href="<?php echo BASEPATH ?>myaccount/manageaddress">Manage Address</a>
                        </li>
                        <li><a href="<?php echo BASEPATH ?>myaccount/myorders">My Orders</a>
                        </li>
                        <li><a href="<?php echo BASEPATH ?>myaccount/wishlist">My Wishlist</a>
                        </li>
                        <li><a href="<?php echo BASEPATH ?>home/logout">Logout</a>
                        </li>
                    </ul>
                </div>
            </div> -->
            <!--product items-->
            <div class="col-lg-9 col-md-9">
                <div class="form-error"></div>
                <div class="contact_message form">
                            <h3>Change Password</h3>
                              <form id="changeUserPassword" method="POST" action="#" enctype="multipart/form-data">
                                <input type="hidden" value="<?php echo $_SESSION['change_password_key'] ?>" name="fkey" id="fkey">
                                <p>
                                    <label>Curent Password <span class="text-danger">*</span></label>
                                    <input type="password" id="password" name="password" placeholder="Current Password" class="form-control" type="text">
                                </p>
                                <p>
                                    <label>New Password <span class="text-danger">*</span></label>
                                    <input type="password" id="new_password" name="new_password" placeholder="New Password" class="form-control validate-email" type="text">
                                </p>
                                <p>
                                    <label>Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" id="re_password" name="re_password" placeholder="Confirm Password" class="form-control" type="text">
                                </p>
                                <div>
                                   <button type="submit" class="btn btn-hero">Update</button>
                                </div>
                            </form>
                </div>
                <!--/row-->
                <!--Pagination-->
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
    // Change Admin Password

$("#changeUserPassword").validate({
    rules: {
        password: {
            required: true
        },
        new_password: {
            required: true,
        },
        re_password: {
            required: true,
            equalTo: "#new_password"
        }
    },
    messages: {
        password: {
            required: "Password cannot be empty",
        },
        new_password: {
            required: "New Password cannot be empty",
        },
        re_password: {
            required: "New Password Again cannot be empty",
        }
    },
    submitHandler: function(form) {
        var content = $(form).serialize();
        $.ajax({
            type: "POST",
            url: base_path + "myaccount/api/changeUserPassword",
            dataType: "html",
            data: content,
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                if (data == 1) {
                    $('#changeUserPassword')[0].reset();
                    window.location = base_path + "myaccount/changepassword/?a=success";
                } else {
                      setTimeout(function() {
                        new Noty({
                            text: "<strong>"+ data +"</strong>!",
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
    }
});
</script>

<?php if (isset($_GET['a'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: 'Profile Password Updated Successfully!',
        type: 'success',
        theme: 'relax',
        layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>