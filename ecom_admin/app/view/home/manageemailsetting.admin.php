<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                     <form method="post" class="form-validate is-alter" id="updateEmailSettings" enctype="multipart/form-data">
                         <?php echo $data['csrf_edit_email_settings'] ?>
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
                                <h6>SMTP Production Credentials</h6>
                                <p>Instructions to your customers on how the order will be processed</p>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="form-label">Vendor</label>
                                        <input type="text" name="pro_vendor" value="<?php echo $data['info']['pro_vendor'] ?>" id="pro_vendor" class="form-control" placeholder="SMTP Vendor" >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-label">SMTP Server Host</label>
                                        <input type="text" name="pro_smtp_server" value="<?php echo $data['info']['pro_smtp_server'] ?>" id="pro_smtp_server" class="form-control" placeholder="SMTP Server Host" >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-label">SMTP Username</label>
                                        <input type="text" name="pro_smtp_username" value="<?php echo $data['info']['pro_smtp_username'] ?>" id="pro_smtp_username" class="form-control" placeholder="SMTP Username " >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-label">SMTP Password</label>
                                        <input type="text" name="pro_smtp_password" value="<?php echo $data['info']['pro_smtp_password'] ?>" id="pro_smtp_password" class="form-control" placeholder="SMTP Password " >
                                    </div>
                                    
                                    <div class="form-group col-md-4">
                                        <label class="form-label">SMTP Port</label>
                                        <input type="text" name="pro_smtp_port" value="<?php echo $data['info']['pro_smtp_port'] ?>" id="pro_smtp_port" class="form-control" placeholder="SMTP Port" >
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label class="form-label">SMTP Security (SSL or TLS)</label>
                                        <div class="preview-block">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="customRadio1" name="pro_ssl_tls" class="custom-control-input" value="ssl" <?php echo $data['info']['pro_ssl_tls']=="ssl" ? "checked" : "" ?>>
                                                <label class="custom-control-label" for="customRadio1">SSL</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="customRadio2" name="pro_ssl_tls" class="custom-control-input" value="tls" <?php echo $data['info']['pro_ssl_tls']=="tls" ? "checked" : "" ?>>
                                                <label class="custom-control-label" for="customRadio2">TLS</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h6>SMTP Dev Credentials</h6>
                                <p>Instructions to your customers on how the order will be processed</p>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="form-label">Vendor</label>
                                        <input type="text" name="dev_vendor" value="<?php echo $data['info']['dev_vendor'] ?>" id="dev_vendor" class="form-control" placeholder="SMTP Vendor" >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-label">SMTP Server Host</label>
                                        <input type="text" name="dev_smtp_server" value="<?php echo $data['info']['dev_smtp_server'] ?>" id="dev_smtp_server" class="form-control" placeholder="SMTP Server Host" >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-label">SMTP Username</label>
                                        <input type="text" name="dev_smtp_username" value="<?php echo $data['info']['dev_smtp_username'] ?>" id="dev_smtp_username" class="form-control" placeholder="SMTP Username " >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-label">SMTP Password</label>
                                        <input type="text" name="dev_smtp_password" value="<?php echo $data['info']['dev_smtp_password'] ?>" id="dev_smtp_password" class="form-control" placeholder="SMTP Password " >
                                    </div>
                                    
                                    <div class="form-group col-md-4">
                                        <label class="form-label">SMTP Port</label>
                                        <input type="text" name="dev_smtp_port" value="<?php echo $data['info']['dev_smtp_port'] ?>" id="dev_smtp_port" class="form-control" placeholder="SMTP Port" >
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label class="form-label">SMTP Security (SSL or TLS)</label>
                                        <div class="preview-block">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="customRadio3" name="dev_ssl_tls" class="custom-control-input" value="ssl" <?php echo $data['info']['dev_ssl_tls']=="ssl" ? "checked" : "" ?>>
                                                <label class="custom-control-label" for="customRadio3">SSL</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="customRadio4" name="dev_ssl_tls" class="custom-control-input" value="tls" <?php echo $data['info']['dev_ssl_tls']=="tls" ? "checked" : "" ?>>
                                                <label class="custom-control-label" for="customRadio4">TLS</label>
                                            </div>
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


    // Edit Email Settings

    $("#updateEmailSettings").validate({
        rules: {
            pro_vendor: {
                required: true
            }
        },
        messages: {
            pro_vendor: {
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
        var formname = document.getElementById("updateEmailSettings");
        var formData = new FormData(formname);
        $.ajax({
            url: core_path + "emailsettings/api/updateEmailSettings",
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
                    window.location = core_path + "emailsettings?a=success";
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
    NioApp.Toast('<h5>Email Settings Updated successfully !!</h5>', 'success', {
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
