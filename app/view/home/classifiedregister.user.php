    <?php require_once 'includes/top.php'; ?>
    <!-- customer login start -->
    <div class="customer_login mt-32">
        <div class="container-fluid">
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
            <div class="form-error"></div>
            <div class="row">
                <!--register area start-->
                <div class="col-lg-6 m-auto">
                    <div class="account_form register mt-4 Sign_up">
                        <div class="login_links">
                            <h4 class="fs-4 mb-3">Expert Register</h4> 
                            <a href="<?php echo BASEPATH ?>login/register" id="inner1" class="float-end">Register as customer ? </a>
                            <a href="<?php echo HIRE_PATH ?>login" id="inner2"> Expert Login ?</a>
                        </div>
                            <form action="#" method="POST" id="register_form" accept-charset="utf-8" class="mt-4">
                            <p>
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" value="" name="name" class="form-control" placeholder="Name">
                            </p>
                            <p>
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="text" value="" name="email" class="form-control" placeholder="Email">
                            </p>
                            <p>
                                <label>Phone <span class="text-danger">*</span></label>
                                <input type="text" value="" name="mobile" class="form-control" maxlength="10" placeholder="Phone">
                            </p>
                            <p>
                                <label>Password<span class="text-danger">*</span></label>
                                <input type="password" value="" name="password" class="form-control" placeholder="Password">
                            </p>
                            <div class="text-end lh-lg">
                                <a href="<?php echo BASEPATH ?>login" class="float-start">Already have an account? Login</a>
                                <button type="submit">Register</button>
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
        $("#register_form").validate({
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
                    minlength: 10,
                    maxlength: 10
                },
                password: {
                    required: true
                },

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
                },
                password: {
                    required: "Password cannot be empty"
                }
            },
            submitHandler: function (form) {
                var content = $(form).serialize();
                $.ajax({
                    type: "POST",
                    url: base_path + "login/api/classifiedregister",
                    dataType: "html",
                    data: content,
                    beforeSend: function () {
                        $(".page_loading").show();
                    },
                    success: function (data) {
                        $(".page_loading").hide();
                        data = data.split('`');
                        if (data[0] == 1) {
                            window.location = base_path + "?cr=success";
                        } else {
                            setTimeout(function() {
                            new Noty({
                                text: '<strong>'+data+'</strong>',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 1000);
                        }
                    }
                });
                return false;
            }
        });
    </script>
