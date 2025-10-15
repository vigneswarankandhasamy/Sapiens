<?php require_once 'includes/top.php'; ?>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Email Step -->
            <div id="emailStep">
                <h1 class="auth-title">Forgot Password?</h1>
                <p class="auth-subtitle">Enter your email address and we'll send you a verification code to reset your password.</p>
                
                <div id="messageContainer"></div>
                
                <form id="forgotPassword" method="POST" action="#" enctype="multipart/form-data"">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" value="" class="form-input">
                    </div>
                    
                    <button type="submit" class="auth-btn">
                        Submit
                    </button>
                </form>
            </div>
            
            
            <div style="margin-top: 2rem;">
                <p class="auth-link">
                    Remember your password? <a href="<?php echo BASEPATH ?>login">Sign in here</a>
                </p>
                <div style="margin-top: 1rem;">
                    <a href="<?php echo BASEPATH ?>" style="color: #666; text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
    $("#forgotPassword").validate({
    rules: {
        email: {
            required: true
        }
    },
    messages: {
        email: {
            required: "Email ID cannot be empty",
        }
    },
    submitHandler: function (form) {
        var content = $(form).serialize();
        $.ajax({
            type: "POST",
            url: base_path + "login/api/forgotpassword",
            dataType: "html",
            data: content,
            beforeSend: function () {
                $(".page_loading").show();
            },
            success: function (data) {
                $(".page_loading").hide();
                if (data == 1) {
                    swal("Password reset link has been sent to your email")
                    window.setTimeout(function () {
                        window.location = base_path;
                    }, 4000);
                } else {
                    setTimeout(function() {
                    new Noty({
                        text: '<strong>'+ data +'!</strong>',
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

                    