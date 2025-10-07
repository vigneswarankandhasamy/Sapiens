<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                     <form method="post" class="form-validate is-alter" id="updateSmsSettings" enctype="multipart/form-data">
                         <?php echo $data['csrf_edit_sms_settings'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>settings"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="submit_button_wrap">
                                            
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>settings"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-error"></div>
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-control-wrap">
                                            <img src="<?php echo IMGPATH ?>razorpay.png" class="settings_logo">
                                            <p> Razorpay is the easiest way to accept major international credit or debit cards and Apple Pay. You don’t need a merchant account, so you can start accepting payments today.</p>
                                            <div class="custom-control-lg custom-switch settings_switch">
                                                <input type="checkbox" class="custom-control-input " id="razorpay_switch" name="razorpay_switch" value="1">
                                                <label class="custom-control-label fw-bold" for="razorpay_switch" id="razorpay_switch_text"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="razorpay_content">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Sender ID</label>
                                            <input type="text" name="sender_id" value="<?php echo $data['info']['sender_id'] ?>" id="sender_id" class="form-control" placeholder="Sender ID" >
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">User Name</label>
                                            <input type="text" name="username" value="<?php echo $data['info']['username'] ?>" id="username" class="form-control" placeholder="User Name" >
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Password</label>
                                            <input type="text" name="password" value="<?php echo $data['info']['password'] ?>" id="password" class="form-control" placeholder="Password" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                 </div>
            </div>
                <!-- .nk-block -->
        </div>
    </div>
</div>

<!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>
<script type="text/javascript">

    // GDPR  Textarea ENABLED and DISABLED
    $(document).ready(function() {
        $('#razorpay_content').hide();
        $('#razorpay_switch_text').text('DISABLED');
      handleRazorpayStatusChanged();
    });
    function handleRazorpayStatusChanged() {
        $('#razorpay_switch').on('change', function () {
          toggleRazorpayStatus();   
        });
    }
    function toggleRazorpayStatus() {
        if ($('#razorpay_switch').is(':checked')) {
            $('#razorpay_content').show();
            $('#razorpay_switch_text').text('ENABLED');
        } else {
            $('#razorpay_content').hide();
            $('#razorpay_switch_text').text('DISABLED');
        }   
    }

   
   // Edit Payment Settings

    $("#updateSmsSettings").validate({
        rules: {
            sender_id: {
                required: true
            },
             username: {
                required: true
            },
             password: {
                required: true
            }
        },
        messages: {
            sender_id: {
                required: "Please Enter Sender ID",
            },
            username: {
                required: "Please Enter Username",
            },
            password: {
                required: "Please Enter Password",
            }
        },
        submitHandler: function(form) {
            toastr.clear();
                Swal.fire({
                    title: "Are you sure to save this changes?",
                    text: "So that changes will be reflected in your sites !!",
                    icon: 'warning',
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if (result.value) {
                        saveForm();
                    }
                });
           
            return false;
        }
    });

    // Save Form

    function saveForm() {
        var formname = document.getElementById("updateSmsSettings");
        var formData = new FormData(formname);
        $.ajax({
            url: core_path + "smssettings/api/updateSmsSettings",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                if (data == 1) {
                    window.location = core_path + "smssettings?a=success";
                } else {
                    $(".form-error").show();
                    $(".form-error").html(data);
                    toastr.clear();
                    NioApp.Toast('<h5>' + data + '</h5>', 'error', {
                        position: 'bottom-center',
                        ui: 'is-light',
                        "progressBar": true,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "4000"
                    });
                }
            }
        });
    }
    


</script>

<?php if (isset($_GET['a'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>SMS Settings Updated successfully !!</h5>', 'success', {
        position: 'bottom-center', 
        ui: 'is-light',
        "progressBar": true,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "4000"
    }); 
}, 1500);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>

