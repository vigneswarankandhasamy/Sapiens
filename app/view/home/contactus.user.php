<?php require_once 'includes/top.php'; ?>
<!-- Backdrop (ensure only one exists on page) -->
<div class="nav-backdrop" id="navBackdrop"></div>
<!-- <div class="nav-backdrop"></div> -->

<!-- Hero Section -->
<section class="contact-hero">
    <div class="container">
        <h1>Get in Touch</h1>
        <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible. Our team is here to help with any questions or concerns you may have.</p>
    </div>
</section>

<!-- Contact Content -->
<section class="ms-contact-section">
    <div class="ms-contact-container">
    <div class="ms-contact-grid">
        <!-- Contact Form -->
        <div class="ms-contact-form">
        <h2 class="ms-contact-title">Send us a Message</h2>
        <p class="ms-contact-subtitle">
            Fill out the form below and we'll get back to you within 24 hours.
        </p>

        <div class="ms-contact-alert-success" id="successMessage">
            Thank you for your message! We'll get back to you soon.
        </div>

        <div class="ms-contact-alert-error" id="errorMessage">
            Please fill in all required fields correctly.
        </div>

        <form id="contactUsInfo" method="POST" action="#" autocomplete="off">
            <div class="ms-contact-group">
                <label for="name" class="ms-contact-label">Name *</label>
                <input type="text" name="name" class="ms-contact-input" />
            </div>

            <div class="ms-contact-group">
            <label for="email" class="ms-contact-label">Email Address *</label>
            <input type="email" name="email" class="ms-contact-input" />
            </div>

            <div class="ms-contact-group">
            <label for="mobile" class="ms-contact-label">Phone Number *</label>
            <input type="mobile" name="mobile" class="ms-contact-input" />
            </div>

            <div class="ms-contact-group">
            <label for="subject" class="ms-contact-label">Subject</label>
            <input type="text" name="subject" class="ms-contact-input" />
            </div>

            <div class="ms-contact-group">
            <label for="message" class="ms-contact-label">Message *</label>
            <textarea name="message" rows="5" class="ms-contact-input"></textarea>
            </div>

            <button type="submit" class="ms-contact-button">Send Message</button>
        </form>
        </div>

        <!-- Contact Info -->
        <div class="ms-contact-info">
        <!-- <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?fit=crop&w=600&q=80" alt="Contact Us"
            class="ms-contact-img" /> -->
        <div class="ms-contact-details">
            <h3>Contact Information</h3>
            <p><strong>Email:</strong> support@menstyle.com</p>
            <p><strong>Phone:</strong> +1 (555) 123-4567</p>
            <p><strong>Address:</strong> 123 Fashion Ave, New York, NY 10001</p>
            <p><strong>Hours:</strong> Mon - Fri, 9am - 6pm</p>
        </div>
        </div>
    </div>
    </div>
</section>
<?php require_once 'includes/bottom.php'; ?>
<script type="text/javascript">
$("#contactUsInfo").validate({
        rules: {
        name: {
            required: true
        },
        subject: {
            required: true
        },
        email: {
            required: true
        },
        mobile: {
            required: true,
            digits: true,
            maxlength: 10,
            minlength: 10
        },
        message: {
            required: true,
            maxlength: 500,
        }
        
    },
    messages: {
        name: {
            required: "Name cannot be empty",
        },
        subject: {
            required: "Subject name cannot be empty",
        },
        email: {
            required: "Email ID cannot be empty",
        },
        mobile: {
            required: "Mobile number cannot be empty",
            maxlength: "Please enter valid 10 digit mobile number",
            minlength: "Please enter valid 10 digit mobile number",
            digits : "Please enter a valid mobile number"
        },
            message: {
            required: "Message cannot be empty",
        }
        
    },
    
    submitHandler: function (form) {
        var content = $(form).serialize();
        $.ajax({
            type: "POST",
            url: base_path + "contact/api/contactUsInfo",
            dataType: "html",
            data: content,
            beforeSend: function () {
                $(".page_loading").show();
            },
            success: function (data) {
                $(".page_loading").hide();
                if (data == 1) {
                    window.location = base_path + "contact?s=success";
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
</script>
<?php if (isset($_GET['s'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: '<strong>Thanks for contacting us!</strong>!',
        type: 'success',
        theme: 'relax',
        layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1500);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>

