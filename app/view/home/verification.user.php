<?php require_once 'includes/top.php'; ?>

<!-- Verification Page -->
<div class="verification-container" style="margin-top: 80px; min-height: 70vh;">
    <div class="verification-card">
        <div class="verification-header">
            <div class="verification-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1 class="verification-title">Verify Account</h1>
        </div>

        <div class="form-error" id="formError"></div>

        <?php if(isset($_GET['resend'])) { ?>
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            <p><strong>Success!</strong> Your verification code has been sent to your email address.</p>
        </div>
        <?php } ?>

        <div class="verification-content">
            <?php if($data['validate']=="ok") { ?>
            <form role="form" action="#" class="verification-form" name="mobile_auth" id="validateEmail">
                <div class="welcome-section">
                    <h2>Welcome, <?php echo $data['token_name']; ?>!</h2>
                    <p>Please enter the verification code sent to your email to activate your account.</p>
                </div>

                <div class="form-group">
                    <input type="hidden" name="token" value="<?php echo $_GET['t']; ?>" id="token" />
                    <div class="input-container">
                        <i class="fas fa-key input-icon"></i>
                        <input type="text" class="form-input verification-input" name="verification_code" placeholder="Enter verification code" autofocus id="verfication_code" maxlength="6">
                    </div>
                    <div class="error-message" id="verificationError"></div>
                </div>

                <button type="submit" class="verify-btn" id="send2">
                    <span class="btn-text">Verify Account</span>
                    <i class="fas fa-arrow-right btn-icon"></i>
                </button>
            </form>

            <div class="resend-section" id="resendSection">
                <div class="timer-container" id="timerContainer">
                    <p>You can resend the verification code in</p>
                    <div class="timer">
                        <span class="timer-number" id="show-time">60</span>
                        <span class="timer-text">seconds</span>
                    </div>
                </div>
                <div class="resend-container no_display" id="resendContainer">
                    <p>Didn't receive the code?</p>
                    <a href="javascript:void(0);" class="resend-link" id="resend_code">
                        <i class="fas fa-paper-plane"></i>
                        Resend Verification Code
                    </a>
                </div>
            </div>

            <?php }elseif($data['validate']=="duplicate") { ?>
            <div class="status-message success">
                <div class="status-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Account Already Verified</h2>
                <p>Your account is already verified and ready to use.</p>
                <a href="<?php echo BASEPATH?>login" class="action-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Login to Your Account
                </a>
            </div>

            <?php }elseif($data['validate']=="resend") { ?>
            <div class="status-message warning">
                <div class="status-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h2>Resend Limit Reached</h2>
                <p>You have requested to resend the verification code 5 times. Please contact our support team for assistance.</p>
                <a href="<?php echo BASEPATH?>contact" class="action-btn">
                    <i class="fas fa-headset"></i>
                    Contact Support
                </a>
            </div>

            <?php }else { ?>
            <form role="form" method="POST" action="#" name="verify_identity" class="verification-form" id="verifyIdentity">
                <div class="welcome-section">
                    <h2>Verify Your Identity</h2>
                    <p>Please enter your registered mobile number to verify your identity.</p>
                </div>

                <div class="form-group">
                    <div class="input-container">
                        <i class="fas fa-mobile-alt input-icon"></i>
                        <input type="text" class="form-input" placeholder="Enter mobile number" autofocus id="mobile_no" maxlength="10">
                    </div>
                    <div class="error-message" id="mobileError"></div>
                </div>

                <button type="submit" class="verify-btn">
                    <span class="btn-text">Verify User Identity</span>
                    <i class="fas fa-arrow-right btn-icon"></i>
                </button>
            </form>
            <?php } ?>
        </div>

        <div class="verification-footer">
            <p>Having trouble? <a href="<?php echo BASEPATH?>contact">Contact Support</a></p>
        </div>
    </div>
</div>
 <?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
$(document).ready(function() {
    // Clear all error messages on page load
    $('.error-message').empty();
    
    // Initialize countdown timer
    let timeLeft = 60;
    let timerInterval;
    
    function startTimer() {
        timerInterval = setInterval(function() {
            timeLeft--;
            $('#show-time').text(timeLeft);
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                $('#timerContainer').addClass('no_display');
                $('#resendContainer').removeClass('no_display');
            }
        }, 1000);
    }
    
    // Start timer on page load
    startTimer();
    
    // Resend code functionality
    $("#resend_code").click(function(e) {
        e.preventDefault();
        var token = $("#token").val();
        
        $.ajax({
            type: "POST",
            url: base_path + "login/api/resendCode",
            dataType: "html",
            data: { result: token },
            beforeSend: function () {
                $(".page_loading").show();
                $("#resend_code").prop('disabled', true);
            },
            success: function (data) {
                $(".page_loading").hide();
                $("#resend_code").prop('disabled', false);
                data = data.split('`');
                if (data[0] == 1) {
                    window.location = base_path + "verification?t=" + data[1] + "&resend=success";
                } else {
                    showError(data[1]);
                }
            },
            error: function() {
                $(".page_loading").hide();
                $("#resend_code").prop('disabled', false);
                showError("An error occurred. Please try again.");
            }
        });
    });
    
    // Verification form validation
    $("#validateEmail").validate({
        rules: {
            verification_code: {
                required: true,
                minlength: 4
            }
        },
        messages: {
            verification_code: {
                required: "Please enter the verification code",
                minlength: "Verification code must be at least 4 characters"
            }
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.closest('.form-group').find('.error-message'));
        },
        success: function(label, element) {
            // Clear error message when field becomes valid
            $(element).closest('.form-group').find('.error-message').empty();
        },
        submitHandler: function (form) {
            var content = $(form).serialize();
            $.ajax({
                type: "POST",
                url: base_path + "login/api/validateEmail",
                dataType: "html",
                data: content,
                beforeSend: function () {
                    $(".page_loading").show();
                    $("#send2").prop('disabled', true);
                },
                success: function (data) {
                    $(".page_loading").hide();
                    $("#send2").prop('disabled', false);
                    if (data == 1) {
                        window.location = base_path + "login?v=success";
                    } else {
                        showError(data);
                    }
                },
                error: function() {
                    $(".page_loading").hide();
                    $("#send2").prop('disabled', false);
                    showError("An error occurred. Please try again.");
                }
            });
            return false;
        }
    });
    
    // Mobile verification form
    $("#verifyIdentity").validate({
        rules: {
            mobile_no: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            }
        },
        messages: {
            mobile_no: {
                required: "Please enter your mobile number",
                digits: "Please enter a valid mobile number",
                minlength: "Mobile number must be 10 digits",
                maxlength: "Mobile number must be 10 digits"
            }
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.closest('.form-group').find('.error-message'));
        },
        success: function(label, element) {
            // Clear error message when field becomes valid
            $(element).closest('.form-group').find('.error-message').empty();
        },
        submitHandler: function (form) {
            var mobile = $("#mobile_no").val();
            $.ajax({
                type: "POST",
                url: base_path + "login/api/verifyMobile",
                dataType: "html",
                data: { mobile: mobile },
                beforeSend: function () {
                    $(".page_loading").show();
                },
                success: function (data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = base_path + "verification?t=" + data[1];
                    } else {
                        showError(data);
                    }
                },
                error: function() {
                    $(".page_loading").hide();
                    showError("An error occurred. Please try again.");
                }
            });
            return false;
        }
    });
    
    // Show error message
    function showError(message) {
        $("#formError").html(message).addClass('show');
        setTimeout(function() {
            $("#formError").removeClass('show');
        }, 5000);
    }
    
    // Auto-format verification code input
    $("#verfication_code").on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Auto-format mobile number input
    $("#mobile_no").on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>