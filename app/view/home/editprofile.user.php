<?php require_once 'includes/top.php'; ?>
<style>
.field-icon {
  float: right;
  margin-right: 30px;
  margin-top: -28px;
  position: relative;
  z-index: 2;
  cursor: pointer;
}
</style>
<!-- <div class="profile-banner otherpage-banner m-0">
    <img src="<?php echo $data['page_banner']!="" ? SRCIMG.$data['page_banner']['file_name'] : IMGPATH."profile-banner.jpg" ?>" alt="image" class="common-banner">
    <div class="other-banner-title">
        <p>My Profile</p>
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
        <div class="row justify-content-center">
            <!-- <div class="col-lg-3 col-md-3">
                <div class="contact_message content">
                    <ul class="edit-profile">
                        <li class="active"><a href="<?php echo BASEPATH ?>myaccount/edit">My Profile</a>
                        </li>
                        <li><a href="<?php echo BASEPATH ?>myaccount/changepassword">Change Password</a>
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
                <div class="contact_message form">
                    <div class="edit_profile_form">
                        <h3>My Profile</h3>
                        <form action="#" method="POST" id="editProfile" accept-charset="utf-8">
                            <input type="hidden" value="<?php echo $_SESSION['edit_profile_key'] ?>" name="fkey" id="fkey">
                            <p>
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" value="<?php echo($data['user']['name']) ?>" class="form-control" placeholder="Name: *">
                            </p>
                            <p>
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="text" name="email" id="email" value="<?php echo($data['user']['email']) ?>" class="form-control" placeholder="Email: *">
                            </p>
                            <p>
                                <label>Mobile Number <span class="text-danger">*</span></label>
                                 <input type="text" name="mobile" id="mobile" value="<?php echo($data['user']['mobile']) ?>" class="form-control" placeholder="Mobile Number: *">
                            </p>
                            <div>
                                <button type="button" id="changepassword" class="btn btn-hero">Change Password</button>
                               <button type="submit" class="btn btn-hero">Update</button>
                            </div>
                        </form>
                    </div>
                    <div class="change_password_form display_none">
                        <h3>Change Password</h3>
                        <form id="changeUserPassword" method="POST" action="#" enctype="multipart/form-data">
                            <input type="hidden" value="<?php echo $_SESSION['change_password_key'] ?>" name="fkey" id="fkey">
                            <p>
                                <label>Current Password <span class="text-danger">*</span></label>
                                <input type="password" id="password" name="password" placeholder="Current Password" class="form-control" type="text">
                            </p>
                            <p>
                                <label>New Password <span class="text-danger">*</span></label>
                                <input type="password" id="new_password" name="new_password" placeholder="New Password" class="form-control validate-email" type="text">
                                <span class="fa fa-fw fa-eye field-icon new-password" onclick="new_password_display()"></span>
                            </p>
                            <p>
                                <label>Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" id="re_password" name="re_password" placeholder="Confirm Password" class="form-control" type="text">
                                <span class="fa fa-fw fa-eye field-icon re-password" onclick="confirm_password_display()"></span>
                            </p>
                            <div>
                                <button type="button" id="editprofile" class="btn btn-hero">Edit Profile</button>
                                <button type="submit" class="btn btn-hero">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

   
<?php require_once 'includes/bottom.php'; ?>
<?php if (isset($_GET['s'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: 'Profile Details Updated Successfully',
        type: 'success',
        theme: 'relax',
        layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>

<script type="text/javascript">

    $("#changepassword").click(function() {
        $(".change_password_form").removeClass("display_none");
        $(".edit_profile_form").addClass("display_none");
    });

     $("#editprofile").click(function() {
        $(".change_password_form").addClass("display_none");
        $(".edit_profile_form").removeClass("display_none");
    });

    function new_password_display(){
       var x = document.getElementById("new_password");
       $(".new-password").toggleClass("fa-eye fa-eye-slash");
        if (x.type === "password") {
        x.type = "text";
        } else {
        x.type = "password";
        }
    }

    function confirm_password_display(){
       var x = document.getElementById("re_password");
       $(".re-password").toggleClass("fa-eye fa-eye-slash");
        if (x.type === "password") {
        x.type = "text";
        } else {
        x.type = "password";
        }
    }

    $("#editProfile").validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            mobile: {
                required: true,
                digits: true,
                maxlength: 10,
                minlength: 10
            }

        },
        messages: {
            name: {
                required: "Name cannot be empty"
            },
            email: {
                required: "Email ID cannot be empty",
                email:"Please enter a valid Email ID"
            },
            mobile: {
                required: "Mobile number cannot be empty",
                minlength: "Please enter a valid mobile number.",
                maxlength: "Please enter a valid mobile number.",
                digits: "Please enter a valid mobile number."
            }
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            $.ajax({
                type: "POST",
                url: base_path + "myaccount/api/editUserProfile",
                dataType: "html",
                data: content,
                beforeSend: function () {
                    $(".page_loading").show();
                },
                success: function (data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = base_path + "myaccount/edit/?s=success";
                    } else {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>'+ data +'</strong>!',
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
                    window.location = base_path + "myaccount/edit?a=success";
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