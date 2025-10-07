<?php require_once 'includes/top.php'; ?>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Email Step -->
            <div id="emailStep">
                <h1 class="auth-title">Forgot Password?</h1>
                <p class="auth-subtitle">Enter your email address and we'll send you a verification code to reset your password.</p>
                
                <div id="messageContainer"></div>
                
                <form id="emailForm">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" class="form-input" required>
                    </div>
                    
                    <button type="submit" class="auth-btn" id="emailBtn">
                        Send Verification Code
                    </button>
                </form>
            </div>
            
            <!-- OTP Step -->
            <div id="otpStep" class="otp-container">
                <h1 class="auth-title">Enter Verification Code</h1>
                <p class="auth-subtitle">We've sent a 6-digit verification code to your email address.</p>
                
                <div id="otpMessageContainer"></div>
                
                <form id="otpForm">
                    <div class="otp-inputs">
                        <input type="text" class="otp-input" maxlength="1" data-index="0">
                        <input type="text" class="otp-input" maxlength="1" data-index="1">
                        <input type="text" class="otp-input" maxlength="1" data-index="2">
                        <input type="text" class="otp-input" maxlength="1" data-index="3">
                        <input type="text" class="otp-input" maxlength="1" data-index="4">
                        <input type="text" class="otp-input" maxlength="1" data-index="5">
                    </div>
                    
                    <button type="submit" class="auth-btn" id="otpBtn">
                        Verify Code
                    </button>
                    
                    <div class="resend-timer">
                        <span id="timerText">Resend code in <span id="countdown">60</span> seconds</span>
                        <button type="button" class="resend-btn" id="resendBtn" style="display: none;" onclick="resendCode()">
                            Resend Code
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Reset Password Step -->
            <div id="resetStep" class="reset-container">
                <h1 class="auth-title">Reset Password</h1>
                <p class="auth-subtitle">Enter your new password below.</p>
                
                <div id="resetMessageContainer"></div>
                
                <form id="resetForm">
                    <div class="form-group">
                        <label for="newPassword" class="form-label">New Password</label>
                        <div class="password-input-container">
                            <input type="password" id="newPassword" class="form-input" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('newPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                        <div class="password-input-container">
                            <input type="password" id="confirmNewPassword" class="form-input" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('confirmNewPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="auth-btn" id="resetBtn">
                        Reset Password
                    </button>
                </form>
            </div>
            
            <div style="margin-top: 2rem;">
                <p class="auth-link">
                    Remember your password? <a href="login.html">Sign in here</a>
                </p>
                <div style="margin-top: 1rem;">
                    <a href="index.html" style="color: #666; text-decoration: none;">
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

                    