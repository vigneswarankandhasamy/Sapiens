<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                     <form method="post" class="form-validate is-alter" id="updatePaymentSettings" enctype="multipart/form-data">
                         <?php echo $data['csrf_edit_payment_settings'] ?>
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
                                            <h5 class="card-title">Pay by cash</h5>
                                            <p>  Use these options to alter the way this payment method is represented to customers at checkout.</p>
                                            <div class="custom-control-lg custom-switch settings_switch">
                                                <input type="checkbox" class="custom-control-input" id="pay_cash_switch" name="pay_cash_switch" value="1">
                                                <label class="custom-control-label fw-bold" for="pay_cash_switch" id="pay_cash_switch_text"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row" id="elementsToOperateOn">
                                    <div class="form-group col-md-12">
                                        <h6 class="card-title">Payment method name at checkout</h6>
                                        <p>  Enter the name under which customers see this payment method at checkout.</p>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Payment Method Name" value="<?php echo $data['cash_info']['title'] ?>">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <h6 class="card-title">Set payment instructions at checkout</h6>
                                        <p> Instructions to your customers on how the order will be processed.</p>
                                         <textarea class="summernote-editor form-control" rows="1" name="description" id="description"><?php echo $data['cash_info']['description'] ?></textarea> 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-control-wrap">
                                            <img src="<?php echo IMGPATH ?>razorpay.png" class="settings_logo">
                                            <p> Razorpay is the easiest way to accept major international credit or debit cards and Apple Pay. You don’t need a merchant account, so you can start accepting payments today.</p>
                                            <div class="custom-control-lg custom-switch settings_switch">
                                                <input type="checkbox" class="custom-control-input " id="razorpay_switch" name="razorpay_switch" value="1">
                                                <label class="custom-control-label fw-bold" for="razorpay_switch" id="razorpay_pay_cash_switch_text"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="razorpay_content">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <h6>Production Credentials</h6>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Client ID</label>
                                            <input type="text" name="prod_client_id" value="<?php echo $data['razorpay_info']['prod_client_id'] ?>" id="prod_client_id" class="form-control" placeholder="Client ID" >
                                        </div>
                                         <div class="form-group col-md-8">
                                            <label class="form-label">Client Secret Key</label>
                                            <input type="text" name="prod_client_secret" value="<?php echo $data['razorpay_info']['prod_client_secret'] ?>" id="prod_client_secret" class="form-control" placeholder="Client Secret Key" >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <h6>Dev Credentials</h6>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Client ID</label>
                                            <input type="text" name="dev_client_id" value="<?php echo $data['razorpay_info']['dev_client_id'] ?>" id="dev_client_id" class="form-control" placeholder="Client ID" >
                                        </div>
                                         <div class="form-group col-md-8">
                                            <label class="form-label">Client Secret Key</label>
                                            <input type="text" name="dev_client_secret_key" value="<?php echo $data['razorpay_info']['dev_client_secret_key'] ?>" id="dev_client_secret_key" class="form-control" placeholder="Client Secret Key" >
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
    

    // pay_cash  Textarea ENABLED and DISABLED
    $(document).ready(function() {
        $('#elementsToOperateOn').hide();
        $('#pay_cash_switch_text').text('DISABLED');
      handleStatusChanged();
    });
    function handleStatusChanged() {
        $('#pay_cash_switch').on('change', function () {
          toggleStatus();   
        });
    }
    function toggleStatus() {
        if ($('#pay_cash_switch').is(':checked')) {
            $('#elementsToOperateOn').show();
            $('#pay_cash_switch_text').text('ENABLED');
        } else {
            $('#elementsToOperateOn').hide();
            $('#pay_cash_switch_text').text('DISABLED');
        }   
    }


    // razorpay  Textarea ENABLED and DISABLED
    $(document).ready(function() {
        $('#razorpay_content').hide();
        $('#razorpay_pay_cash_switch_text').text('DISABLED');
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
            $('#razorpay_pay_cash_switch_text').text('ENABLED');
        } else {
            $('#razorpay_content').hide();
            $('#razorpay_pay_cash_switch_text').text('DISABLED');
        }   
    }

   

    // Edit Payment Settings

    $("#updatePaymentSettings").validate({
        rules: {
            type: {
                required: true
            }
        },
        messages: {
            type: {
                required: "Please Enter Type",
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
        var formname = document.getElementById("updatePaymentSettings");
        var formData = new FormData(formname);
        $.ajax({
            url: core_path + "paymentsettings/api/updatePaymentSettings",
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
                    window.location = core_path + "paymentsettings?a=success";
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
    NioApp.Toast('<h5>Payment Settings Updated successfully !!</h5>', 'success', {
        position: 'bottom-center', 
        ui: 'is-light',
        "progressBar": true,
        "showDuration": "300",
        "hideDuration": "200",
        "timeOut": "4000"
    }); 
}, 1500);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>
