    <?php require_once 'includes/top.php'; ?>
    <!-- customer login start -->
    <div class="auth-container" style="margin-top:80px;">
        <div class="auth-card" style="max-width: 450px;">
            <h1 class="auth-title">Create Account</h1>
            <p class="auth-subtitle">Join us and start shopping</p>
            
            <div id="messageContainer"></div>
            
            <form id="signupForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" id="firstName" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" id="lastName" class="form-input" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" id="phone" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-input-container">
                        <input type="password" id="password" class="form-input" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div id="passwordStrength" class="password-strength"></div>
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <div class="password-input-container">
                        <input type="password" id="confirmPassword" class="form-input" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">
                        I agree to the <a href="#" target="_blank">Terms of Service</a> and 
                        <a href="#" target="_blank">Privacy Policy</a>
                    </label>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="newsletter">
                    <label for="newsletter">
                        Subscribe to our newsletter for updates and exclusive offers
                    </label>
                </div>
                
                <button type="submit" class="auth-btn" id="signupBtn">
                    Create Account
                </button>
            </form>
            
            <div class="auth-divider">
                <span>or</span>
            </div>
            
            <p class="auth-link">
                Already have an account? <a href="<?php echo BASEPATH ?>login">Sign in here</a>
            </p>
            
            <div style="margin-top: 2rem;">
                <a href="<?php echo BASEPATH ?>" style="color: #666; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
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
                    url: base_path + "login/api/userregister",
                    dataType: "html",
                    data: content,
                    beforeSend: function () {
                        $(".page_loading").show();
                    },
                    success: function (data) {
                        $(".page_loading").hide();
                        data = data.split('`');
                        if (data[0] == 1) {
                            window.location = base_path + "verification?t=" + data[1];
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
