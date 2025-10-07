<?php require_once 'includes/top.php'; ?>
    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">Sign in to your account</p>
            
            <div id="messageContainer"></div>
            
            <form class="m-0" action="#" method="POST" id="userLogin" accept-charset="utf-8">
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="text" value="" name="email" id="email" class="form-input" placeholder="Email">
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-input-container">
                        <input type="password" name="password" id="password" class="form-input" placeholder="Password">
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="auth-btn">
                    Sign In
                </button>
                
                <div style="text-align: center; margin-bottom: 1rem;">
                    <a href="<?php echo BASEPATH ?>login/forgotpassword" class="forgot-password">Forgot your password?</a>
                </div>
            </form>
            
            <div class="auth-divider">
                <span>or</span>
            </div>
            
            <p class="auth-link">
                Don't have an account? <a href="<?php echo BASEPATH ?>login/register">Sign up here</a>
            </p>
            
            <div style="margin-top: 2rem;">
                <a href="<?php echo BASEPATH ?>" style="color: #666; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
<?php require_once 'includes/bottom.php'; ?>
<script type="text/javascript">
    $("#userLogin").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            }

        },
        messages: {
            email: {
                required: "Email ID cannot be empty"
            },
            password: {
                required: "Password cannot be empty"
            }
        },
        submitHandler: function (form) {
            var content = $(form).serialize();
            $.ajax({
                type: "POST",
                url: base_path + "login/api/userlogin",
                dataType: "html",
                data: content,
                beforeSend: function () {
                    $(".page_loading").show();
                },
                success: function (data) {
                    $(".page_loading").hide();
                    console.log(data);
                        data = data.split('`');
                    if (data == 1) {
                        window.location = base_path + "?s=success";
                        // window.location = base_path;
                    } else if (data[0]==1) { 
                            $(".verify_false").hide();
                            $("#user_mail").prop('disabled', true);
                            $(".Verify_block").show();
                            $(".form-error").show();
                            $(".form-error").html(data[1]);
                            setTimeout(function() {
                            new Noty({
                                text: '<strong>'+ data[1] +'</strong>!',
                                type: 'error',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    } else {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>'+ data[1] +'</strong>!',
                                type: 'error',
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

    $(".verify_account").click(function() {
        var token = $("#user_mail").val();
        $.ajax({
            type: "POST",
            url: base_path + "login/api/verifyUserAccount",
            dataType: "html",
            data: { result: token },
            beforeSend: function () {
                $(".page_loading").show();
            },
            success: function (data) {
                $(".page_loading").hide();
                console.log(data);
                data = data.split('`');
                if (data[0] == 1) {
                    window.location = base_path + "verification?t=" + data[1] + "&resend=success";
                } else {
                    $(".form-error").html(data[1]);
                }
            }
        });
    });
</script>

<?php if (isset($_GET['v'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: 'Your Account verified Successfully!',
        type: 'success',
        theme: 'relax',
        layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>  
<?php if (isset($_GET['r'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: 'Password Updated Successfully!',
        type: 'success',
        theme: 'relax',
        layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>

<?php endif ?>