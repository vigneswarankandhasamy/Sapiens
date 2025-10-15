<?php require_once 'includes/top.php'; ?>

<!-- Edit Profile Page -->
<div class="edit-profile-container" style="margin-top: 80px; min-height: 70vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-icon">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h1 class="profile-title">My Account</h1>
                        <p class="profile-subtitle">Manage your profile information and security settings</p>
                    </div>

                    <div class="profile-content">
                        <!-- Edit Profile Form -->
                        <div class="profile-section" id="editProfileSection">
                            <div class="section-header">
                                <h2>Profile Information</h2>
                                <p>Update your personal details</p>
                            </div>
                            
                            <form action="#" method="POST" id="editProfile" accept-charset="utf-8">
                                <input type="hidden" value="<?php echo $_SESSION['edit_profile_key'] ?>" name="fkey" id="fkey">
                                
                                <div class="form-group">
                                    <label for="name" class="form-label">Name <span class="required">*</span></label>
                                    <input type="text" name="name" id="name" value="<?php echo($data['user']['name']) ?>" class="form-input" placeholder="Enter your full name">
                                    <div class="error-message" id="nameError"></div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                                    <input type="email" name="email" id="email" value="<?php echo($data['user']['email']) ?>" class="form-input" placeholder="Enter your email address">
                                    <div class="error-message" id="emailError"></div>
                                </div>

                                <div class="form-group">
                                    <label for="mobile" class="form-label">Mobile Number <span class="required">*</span></label>
                                    <input type="text" name="mobile" id="mobile" value="<?php echo($data['user']['mobile']) ?>" class="form-input" placeholder="Enter your mobile number" maxlength="10">
                                    <div class="error-message" id="mobileError"></div>
                                </div>

                                <div class="form-actions">
                                    <button type="button" id="changepassword" class="btn-secondary">
                                        <i class="fas fa-key"></i>
                                        Change Password
                                    </button>
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-save"></i>
                                        Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Change Password Form -->
                        <div class="profile-section display_none" id="changePasswordSection">
                            <div class="section-header">
                                <h2>Change Password</h2>
                                <p>Update your account password for better security</p>
                            </div>
                            
                            <form id="changeUserPassword" method="POST" action="#" enctype="multipart/form-data">
                                <input type="hidden" value="<?php echo $_SESSION['change_password_key'] ?>" name="fkey" id="fkey">
                                
                                <div class="form-group">
                                    <label for="password" class="form-label">Current Password <span class="required">*</span></label>
                                    <div class="input-container">
                                        <input type="password" id="password" name="password" placeholder="Enter your current password" class="form-input">
                                    </div>
                                    <div class="error-message" id="passwordError"></div>
                                </div>

                                <div class="form-group">
                                    <label for="new_password" class="form-label">New Password <span class="required">*</span></label>
                                    <div class="input-container">
                                        <input type="password" id="new_password" name="new_password" placeholder="Enter your new password" class="form-input">
                                        <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="error-message" id="new_passwordError"></div>
                                </div>

                                <div class="form-group">
                                    <label for="re_password" class="form-label">Confirm New Password <span class="required">*</span></label>
                                    <div class="input-container">
                                        <input type="password" id="re_password" name="re_password" placeholder="Confirm your new password" class="form-input">
                                        <button type="button" class="password-toggle" onclick="togglePassword('re_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="error-message" id="re_passwordError"></div>
                                </div>

                                <div class="form-actions">
                                    <button type="button" id="editprofile" class="btn-secondary">
                                        <i class="fas fa-user"></i>
                                        Back to Profile
                                    </button>
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-key"></i>
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
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
$(document).ready(function() {
    // Clear all error messages on page load
    $('.error-message').empty();
    
    // Toggle between profile and password sections
    $("#changepassword").click(function() {
        $("#editProfileSection").addClass("display_none");
        $("#changePasswordSection").removeClass("display_none");
    });

    $("#editprofile").click(function() {
        $("#changePasswordSection").addClass("display_none");
        $("#editProfileSection").removeClass("display_none");
    });

    // Password toggle functionality
    function togglePassword(fieldId) {
        var field = document.getElementById(fieldId);
        var icon = field.parentNode.querySelector('.password-toggle i');
        
        if (field.type === "password") {
            field.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            field.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
    
    // Make togglePassword globally available
    window.togglePassword = togglePassword;
    
    // Auto-format mobile number input
    $("#mobile").on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Test password validation
    $("#new_password, #re_password").on('blur', function() {
        if ($(this).attr('name') === 'new_password' && $(this).val().length > 0 && $(this).val().length < 6) {
            $("#new_passwordError").text('Password must be at least 6 characters').show();
        } else if ($(this).attr('name') === 're_password' && $(this).val() !== $("#new_password").val()) {
            $("#re_passwordError").text('Passwords do not match').show();
        } else {
            $('#' + $(this).attr('name') + 'Error').empty();
        }
    });

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
        errorPlacement: function(error, element) {
            var fieldName = $(element).attr('name');
            var errorContainer = $('#' + fieldName + 'Error');
            error.appendTo(errorContainer);
        },
        success: function(label, element) {
            var fieldName = $(element).attr('name');
            $('#' + fieldName + 'Error').empty();
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
                minlength: 6
            },
            re_password: {
                required: true,
                equalTo: "#new_password"
            }
        },
        messages: {
            password: {
                required: "Current password cannot be empty"
            },
            new_password: {
                required: "New password cannot be empty",
                minlength: "Password must be at least 6 characters"
            },
            re_password: {
                required: "Please confirm your new password",
                equalTo: "Passwords do not match"
            }
        },
        errorPlacement: function(error, element) {
            var fieldName = $(element).attr('name');
            var errorContainer = $('#' + fieldName + 'Error');
            error.appendTo(errorContainer);
        },
        success: function(label, element) {
            var fieldName = $(element).attr('name');
            $('#' + fieldName + 'Error').empty();
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