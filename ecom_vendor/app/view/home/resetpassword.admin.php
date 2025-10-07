<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <meta charset="utf-8">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="<?php echo ASSETS_PATH ?>favicon.png">
    <!-- Page Title  -->
    <title><?php echo COMPANY_NAME ?> | Reset Password</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="<?php echo CSSPATH ?>core.css?ver=2.2.0">
    <link id="skin-default" rel="stylesheet" href="<?php echo CSSPATH ?>theme.css?ver=2.2.0">
    <script type="text/javascript">
        var core_path = "<?php echo COREPATH ?>";
    </script>
</head>

<body class="nk-body bg-white npc-default pg-auth">
    <div class="page_loading">
        <div class="loading_image">
            <p class="load_img"><img  src="<?php echo ASSETS_PATH?>loader.gif" width="80"></p>
            <p class="load_text">Please Wait...</p>
        </div>
    </div>
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="nk-block nk-block-middle nk-auth-body  wide-xs">
                        <div class="brand-logo pb-4 text-center">
                            <a href="<?php echo COREPATH ?>" class="logo-link">
                                <img class="logo-light logo-img logo-img-lg" src="<?php echo ASSETS_PATH ?>logo.png" srcset="<?php echo ASSETS_PATH ?>logo.png" alt="logo">
                                <img class="logo-dark logo-img logo-img-lg" src="<?php echo ASSETS_PATH ?>logo.png" srcset="<?php echo ASSETS_PATH ?>logo.png" alt="logo-dark">
                            </a>
                        </div>
                        <div class="card">
                            <div class="card-inner card-inner-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h4 class="nk-block-title">Reset Password</h4>
                                    </div>
                                </div>
                                <div class="form-group form-error"></div>
                                <form method="POST" id="resetPassword">
                                    <input type="hidden" value="<?php echo $_SESSION['user_reset_password'] ?>" name="fkey" id="fkey">
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="password">Password <em>*</em></label>
                                        </div>
                                        <div class="form-control-wrap">
                                            <a href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="New password" required="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="password">Confirm Password <em>*</em></label>
                                        </div>
                                        <div class="form-control-wrap">
                                            <a href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" name="confirm_password" class="form-control form-control-lg" id="confirm_password" placeholder="Confirm password" required="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript -->
    <script src="<?php echo JSPATH ?>bundle.js?ver=2.0.0"></script>
    <script src="<?php echo JSPATH ?>scripts.js?ver=2.0.0"></script>

    <script type="text/javascript">
        
        // Login Auth

        $("#resetPassword").validate({
            rules: {
                password: {
                    required: true,
                },
                confirm_password: {
                    required: true,
                    equalTo : "#password"
                }
            },
            messages: {
                
                password: {
                    required: "Please Enter your Password",
                },
                confirm_password: {
                    required: "Please Enter your Password",
                    equalTo : "Please Enter the same password as above"
                }
            },
            submitHandler: function(form) {
                var content = $(form).serialize();
                $.ajax({
                    type: "POST",
                    url: core_path + "resource/ajax_redirect.php?page=resetpassword",
                    dataType: "html",
                    data: content,
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            window.location = core_path + "login";
                        } else {
                            toastr.clear();
                            NioApp.Toast('<h5>'+data+'</h5>', 'error', {
                                position: 'top-center',
                                ui: 'is-light',
                                "progressBar": true,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "4000"
                            });
                            $(".form-error").show();
                            $(".form-error").html(data);
                        }
                    }
                });
                return false;
            }
        });

    </script>

</html>