<?php require_once 'includes/top.php'; ?>
<!-- ========================  404 ======================== -->

 <div class="customer_login mt-32">
    <div class="container-fluid">
        <div class="form-error"></div>
        <span class="clearfix"></span>
        <?php if(isset($_GET['resend'])) { ?>
        <div class="alert alert-info">
            <p class="text-center"><strong>Success !! </strong> Your verification code is sent to your email address. </p>
            <p class="text-danger text-center"> </p>
        </div>
        <?php } ?>
        <div class="row clearfix text-center">
                <div class="col-md-12">
                    <div class="mobile_verification_code">
                        <?php if($data['validate']=="ok") { ?>
                        <form role="form" action="#" class="mobile_validation" name="mobile_auth" id="validateEmail">
                            <h3>Welcome
                                <?php echo $data['token_name']; ?>, </h3>
                            <?php  $token = $_GET['t']; ?>
                            <p>Enter the Verification Code sent your email to activate your account</p>
                            <div class="verification_page">
                                <div class="form-group">
                                    <input type="hidden" name="token" value="<?php echo $token; ?>" id="token" />
                                    <input type="text" class="form-control validation" name="verification_code" placeholder="Verification Code" autofocus id="verfication_code">
                                </div>
                                <div class="errcode"></div>
                                <div class="buttons-set">
                                    <button type="submit" class="btn botton-btn" title="Login" name="send" id="send2"><span>Verify Account</span></button>
                                </div>
                            </div>
                        </form>
                        <?php }elseif($data['validate']=="duplicate") { ?>
                        <div class="mobile_dup">
                            <h2>Dear User,</h2>
                            <p>Your Mobile Number is already verified.</p>
                            <p class="text-center"><a href="<?php echo BASEPATH?>login" class="btn btn-warning"> Login to your Account</a></p>
                        </div>
                        <?php }elseif($data['validate']=="resend") { ?>
                        <div class="mobile_dup">
                            <h2>Dear User,</h2>
                            <p>You Have requested for Resend Code for 5 times. You can't verify your mobile now. Kindly contact the admin. </p>
                            <p class="text-center"><a href="<?php echo BASEPATH?>contact" class="btn btn-warning"> Contact Admin</a></p>
                        </div>
                        <?php }else { ?>
                        <form role="form" method="POST" action="#" name="verify_identity" class="invalid_details">
                            <h3 class="text-danger text-center"> </h3>
                            <h3>Invalid User Details</h3>.
                            <h4 class="m_bottom_5">Dear User,</h4>
                            <p class="m_bottom_15">Please enter your registered Mobile number to verify your identity.</p>
                            <div class="form-group has-feedback lg left-feedback no-label m_bottom_15">
                                <input type="text" class="form-control input-lg rounded invalid_users" placeholder="Mobile Number" autofocus id="mobile_no">
                                <span class="fas fa-lock form-control-feedback "></span>
                            </div>
                            <div class="errcode"></div>
                            <div class="user_login_submit">
                                <button class="btn botton-btn">Verify User Identity</button>
                            </div>
                        </form>
                        <?php } ?>
                        <?php if($data['validate']=="ok") { ?>
                        <div class="mobile_validation">
                            <div class="new_user_registration" id='my-timer'>
                                <p class='' id='msg_send'>You can Resend the verification Code in <br /> <b class="text-danger" id='show-time'>60</b> seconds.</p>
                                <div class="no_display" id='msg_sent'>
                                    <p class='successmsg'><strong class="text-danger"><a href="javascript:void();" id="resend_code">Resend Verification Code?</a></strong></p>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
    </div>
</div>
 <?php require_once 'includes/bottom.php'; ?>

 <script type="text/javascript">
     $("#resend_code").click(function () {
    var token = $("#token").val();
    $.ajax({
        type: "POST",
        url: base_path + "login/api/resendCode",
        dataType: "html",
        data: { result: token },
        beforeSend: function () {
            $(".page_loading").show();
        },
        success: function (data) {
            $(".page_loading").hide();
            data = data.split('`');
            if (data[0] == 1) {
                window.location = base_path + "verification?t=" + data[1] + "&resend=success";
            } else {
                $(".form-error").html(data[1]);
            }
        }
    });
});

$("#validateEmail").validate({
    rules: {
        verification_code: {
            required: true,
        }
    },
    messages: {
        verification_code: {
            required: "Please Enter Verification Code"
        }
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
            },
            success: function (data) {
                $(".page_loading").hide();
                if (data == 1) {
                    window.location = base_path + "login?v=success";
                } else {
                    $(".form-error").html(data);
                }
            }
        });
        return false;
    }
});
 </script>