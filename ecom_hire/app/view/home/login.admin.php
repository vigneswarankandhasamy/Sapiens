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
    <title><?php echo COMPANY_NAME ?> | Login</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="<?php echo CSSPATH ?>core.css?ver=2.2.0">
    <link id="skin-default" rel="stylesheet" href="<?php echo CSSPATH ?>theme.css?ver=2.2.0">
    <link rel="stylesheet" href="<?php echo CSSPATH ?>custom.css">
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
                                <img class="logo-light logo-img logo-img-xl" src="<?php echo ASSETS_PATH ?>login_page_logo.png" srcset="<?php echo ASSETS_PATH ?>login_page_logo.png" alt="logo">
                                <img class="logo-dark logo-img logo-img-xl" src="<?php echo ASSETS_PATH ?>login_page_logo.png" srcset="<?php echo ASSETS_PATH ?>login_page_logo.png" alt="logo-dark">
                            </a>
                        </div>
                        <div class="card">
                            <div class="card-inner card-inner-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h4 class="nk-block-title">Sign-In</h4>
                                    </div>
                                </div>
                                <div class="form-group form-error"></div>
                                <form method="POST" id="login_auth">
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="default-01">Email Address <em>*</em></label>
                                        </div>
                                        <input type="text" name="email"  class="form-control form-control-lg" id="default-01" placeholder="Enter your email address " required="">
                                    </div>
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="password">Password <em>*</em></label>
                                        </div>
                                        <div class="form-control-wrap">
                                            <a href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="Enter your password" required="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block">Sign in</button>
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

        $("#login_auth").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                }
            },
            messages: {
                email: {
                    required: "Please Enter your Email Address",
                },
                password: {
                    required: "Please Enter your Password",
                }
            },
            submitHandler: function(form) {
                var content = $(form).serialize();
                $.ajax({
                    type: "POST",
                    url: core_path + "resource/ajax_redirect.php?page=userLogin",
                    dataType: "html",
                    data: content,
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            location.reload();
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