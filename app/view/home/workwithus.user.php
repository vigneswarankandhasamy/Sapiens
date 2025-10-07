<?php require_once 'includes/top.php'; ?>
<div class="profile-banner otherpage-banner m-0">
    <img src="<?php echo IMGPATH ?>profile-banner.jpg" alt="">
    <div class="other-banner-title">
        <p>Work With Us</p>
        <!-- <button type="button" class="btn btn-sm banner-btn rounded-pill"><a href="<?php echo BASEPATH ?>">View more</a></button> -->
    </div>   
</div>  
<div class="breadcrumbs_area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="<?php echo BASEPATH ?>">home</a></li>
                        <li>Work With Us</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="contact_area workus">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="contact_message account_form form">
                    <h3>Tell us about your Company</h3>
                    <form id="workWithUsInfo" method="POST" action="#">
                        <div class="row">
                            <div class="col-lg-6">
                                <p><label> Your Name <span class="text-danger">*</span></label>
                                <input name="name" placeholder="Name" type="text"></p>
                            </div>
                            <div class="col-lg-6">
                                <p><label> Company Name <span class="text-danger">*</span></label>
                                <input name="company_name" placeholder="Company Name" type="text"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <p><label> Your Email <span class="text-danger">*</span></label>
                                <input name="email" placeholder="Email" type="email"></p>
                            </div>
                            <div class="col-lg-6">
                                <p><label> Phone Number <span class="text-danger">*</span></label>
                                <input name="mobile" placeholder="Phone Number" type="text"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <p><label> Address <span class="text-danger">*</span></label>
                                <textarea placeholder="Address" name="address" class="form-control2"></textarea></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <p><label> City <span class="text-danger">*</span></label>
                                <input name="city" placeholder="City" type="text"></p>
                            </div>
                            <div class="col-lg-6">
                                <p><label> State <span class="text-danger">*</span></label>
                                <input name="state" placeholder="State " type="text"></p>
                            </div>
                            <div class="col-lg-6">
                                <p><label> Pincode <span class="text-danger">*</span></label>
                                <input name="pincode" placeholder="Pincode" type="text"></p>
                            </div>
                            <div class="col-lg-6">
                                <p><label> GST No</label>
                                <input name="gst_no" placeholder="GST No" type="text"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <p><label> Message <span class="text-danger">*</span></label>
                                <textarea placeholder="Message" name="message" class="form-control2"></textarea></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 workwitus_submit_btn_col">
                                <button class="workwitus_submit_btn" type="submit"> Submit</button>
                            </div>
                        </div>
                        
                        <p class="form-messege"></p>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>   
<?php require_once 'includes/bottom.php'; ?>
<script type="text/javascript">
    $("#workWithUsInfo").validate({
            rules: {
            name: {
                required: true
            },
            company_name: {
                required: true
            },
            city: {
                required: true
            },
            state: {
                required: true
            },
            address: {
                required: true,
                maxlength: 500,
            },
            pincode: {
                required: true,
                digits: true,
                maxlength: 6,
                minlength: 6
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
            company_name: {
                required: "Company name cannot be empty",
            },
            city: {
                required: "City cannot be empty",
            },
            state: {
                required: "State cannot be empty",
            },
            address: {
                required: "Address cannot be empty",
            },
            pincode: {
                required: "Pincode cannot be empty",
                maxlength: "Please enter valid 6 digit pincode",
                minlength: "Please enter valid 6 digit pincode",
                digits : "Please enter a valid pincode"
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
                url: base_path + "contact/api/workWithUsInfo",
                dataType: "html",
                data: content,
                beforeSend: function () {
                    $(".page_loading").show();
                },
                success: function (data) {
                    $(".page_loading").hide();
                    data  = data.split("`");
                    if (data[0] == 1) {
                        window.location = base_path + "contact/workwithus?s=success";
                    } else {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>'+ data[1] +'</strong>!',
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
                text: '<strong>Your details submitted successfully !</strong>!',
                type: 'success',
                theme: 'relax',
                layout: 'bottomCenter',
                timeout: 3000
            }).show();
        }, 1500);
        history.pushState(null, "", location.href.split("?")[0]);
    </script>
<?php endif ?>

