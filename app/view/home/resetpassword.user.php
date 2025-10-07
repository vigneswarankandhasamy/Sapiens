    <?php require_once 'includes/top.php'; ?>
    <!-- customer login start -->
    <div class="customer_login mt-32">
        
        <div class="container-fluid">
        <?php if(isset($_GET['resend'])) { ?>
        <div class="alert alert-info">
            <p class="text-center"><strong>Success !! </strong> Your Verification Code is sent your Email. </p>
            <p class="text-danger text-center"> </p>
        </div>
        <?php } ?>
         <?php if(isset($_GET['v'])) { ?>
            <div class="alert alert-info">
                <p class="text-center"><strong>Success !! </strong> Your Account Verified Successfully. </p>
                <p class="text-danger text-center"> </p>
            </div>
        <?php } ?>
        <?php if(isset($_GET['r'])) { ?>
            <div class="alert alert-info">
                <p class="text-center"><strong>Success !! </strong> Password Rest Successfully. </p>
                <p class="text-danger text-center"> </p>
            </div>
        <?php } ?>
            <div class="row">
                <!--register area start-->
                <div class="col-lg-6 m-auto">
                    <div class="account_form register mt-4">
                        <h2>Reset Password <a href="<?php echo BASEPATH ?>login" class="float-end">Back to Login</a></h2>
                        <form id="resetPassword" method="POST" action="#" enctype="multipart/form-data">
                             <input type="hidden" value="<?php echo $_SESSION['user_reset_password'] ?>" name="fkey" id="fkey">
                            <p>
                                <label>New Password<span class="text-danger">*</span></label>
                                 <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control">
                            </p>
                            <p>
                                <label>Confirm Password<span class="text-danger">*</span></label>
                                 <input type="password" name="re_password" id="re_password" placeholder="Confirm Password" class="form-control">
                            </p>
                            <div class="text-center">
                                <button type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!--register area end-->
            </div>
        </div>
    </div>
    <!-- customer login end -->
    
     <?php require_once 'includes/bottom.php'; ?>

     <script type="text/javascript">
         // Change Password
        $("#resetPassword").validate({
            rules: {

                new_password: {
                    required: true,
                },
                re_password: {
                    required: true,
                    equalTo: "#new_password"
                }
            },
            messages: {
                new_password: {
                    required: "New Password cannot be empty",
                },
                re_password: {
                    required: "Confirm Password cannot be empty",
                }
            },
            submitHandler: function (form) {
                var content = $(form).serialize();
                $.ajax({
                    type: "POST",
                    url: base_path + "login/api/resetPassword",
                    dataType: "html",
                    data: content,
                    beforeSend: function () {
                        $(".page_loading").show();
                    },
                    success: function (data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            swal("Password has been reset successfully")
                            window.setTimeout(function () {
                                window.location = base_path + "login?r=success";
                             }, 4000);
                        } else {
                            $(".form-error").html(data);
                            $(".form-error").show();
                        }
                    }
                });
                return false;
            }
        });
     </script>