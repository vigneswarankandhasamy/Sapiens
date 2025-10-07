<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                     <form method="post" class="form-validate is-alter" id="updateCompanyInfo" enctype="multipart/form-data">
                         <?php echo $data['csrf_edit_profile'] ?>
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
                                <h5 class="card-title">Company Name and GST</h5>
                                The official name of your business. If you don't have a registered business yet, specify your store name as the company name.
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="form-label"> Company Name
                                            <em>*</em>
                                        </label>
                                        <input type="text" name="company_name" value="<?php echo $data['info']['company_name'] ?>" id="company_name" class="form-control" placeholder="Company Name" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> GST Number
                                            <em>*</em>
                                        </label>
                                        <input type="text" name="gst_no" value="<?php echo $data['info']['gst_no'] ?>" id="gst_no" class="form-control" placeholder="Email One" >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Company Address</h5>
                                The physical address of your store or place of business. If you don't have a business address, enter the address from which you ship orders.
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Registered State
                                                
                                            </label>
                                             <select class="form-control form-control-lg" id="reg_state" data-search="off" name="reg_state" required>
                                                <?php echo $data['state']; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                     <div class="form-group col-md-6">
                                        <label class="form-label">Address One<em>*</em>
                                        </label>
                                        <div class="form-control-wrap">
                                            <textarea class="form-control" rows="2"  name="address_one"><?php echo $data['info']['address_one'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Address Two 
                                        </label>
                                        <div class="form-control-wrap">
                                            <textarea class="form-control" rows="2"  name="address_two"><?php echo $data['info']['address_two'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Company Contact Details</h5>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Email One
                                            <em>*</em>
                                        </label>
                                        <input type="text" name="email_one" value="<?php echo $data['info']['email_one'] ?>" id="email_one" class="form-control" placeholder="Email One" >
                                    </div>
                                     <div class="form-group col-md-6">
                                        <label class="form-label"> Email Two
                                        </label>
                                        <input type="text" name="email_two" value="<?php echo $data['info']['email_two'] ?>" id="email_two" class="form-control" placeholder="Email Two" >
                                    </div>
                                     <div class="form-group col-md-6">
                                        <label class="form-label"> Contact Number One
                                            <em>*</em>
                                        </label>
                                        <input type="text" name="contact_no_one" value="<?php echo $data['info']['contact_no_one'] ?>" id="contact_no_one" class="form-control" placeholder="Contact Number One" >
                                    </div>
                                     <div class="form-group col-md-6">
                                        <label class="form-label"> Contact Number Two
                                        </label>
                                        <input type="text" name="contact_no_two" value="<?php echo $data['info']['contact_no_two'] ?>" id="contact_no_two" class="form-control" placeholder="Contact Number Two" >
                                    </div>
                                     <div class="form-group col-md-6">
                                        <label class="form-label"> Whats App Number One
                                            <em>*</em>
                                        </label>
                                        <input type="text" name="whatsapp_one" value="<?php echo $data['info']['whatsapp_one'] ?>" id="whatsapp_one" class="form-control" placeholder="Whats App Number One" >
                                    </div>
                                     <div class="form-group col-md-6">
                                        <label class="form-label"> Whats App Number Two
                                        </label>
                                        <input type="text" name="whatsapp_two" value="<?php echo $data['info']['whatsapp_two'] ?>" id="whatsapp_two" class="form-control" placeholder="Whats App Number Two" >
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Social Media Accounts</h5>
                                Specify your social media accounts to encourage customers to connect with you. These accounts will be displayed in email notifications sent by your store.
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Facebook
                                        </label>
                                        <input type="text" name="facebook" value="<?php echo $data['info']['facebook'] ?>" id="facebook" class="form-control" placeholder="Facebook" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Twitter
                                        </label>
                                        <input type="text" name="twitter" value="<?php echo $data['info']['twitter'] ?>" id="twitter" class="form-control" placeholder="Twitter" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Google
                                        </label>
                                        <input type="text" name="googleplus" value="<?php echo $data['info']['googleplus'] ?>" id="googleplus" class="form-control" placeholder="Google" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> RSS
                                        </label>
                                        <input type="text" name="rss" value="<?php echo $data['info']['rss'] ?>" id="rss" class="form-control" placeholder="RSS" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Pinterest
                                        </label>
                                        <input type="text" name="pinterest" value="<?php echo $data['info']['pinterest'] ?>" id="pinterest" class="form-control" placeholder="Pinterest" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Linkedin
                                        </label>
                                        <input type="text" name="linkedin" value="<?php echo $data['info']['linkedin'] ?>" id="linkedin" class="form-control" placeholder="Linkedin" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Youtube
                                        </label>
                                        <input type="text" name="youtube" value="<?php echo $data['info']['youtube'] ?>" id="youtube" class="form-control" placeholder="Youtube" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Instagram
                                        </label>
                                        <input type="text" name="instagram" value="<?php echo $data['info']['instagram'] ?>" id="instagram" class="form-control" placeholder="Instagram" >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Vendor Charges</h5>
                                The physical address of your store or place of business. If you don't have a business address, enter the address from which you ship orders.
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Vendor Payment Gateway Charges
                                            </label>
                                            <input type="text" name="vendor_payment_charges" value="<?php echo $data['info']['vendor_payment_charges'] ?>" id="vendor_payment_charges" class="form-control" placeholder="Vendor Payment Gateway Charges" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label">  Payment Gateway Tax
                                                
                                            </label>
                                             <select class="form-control form-control-lg" id="vendor_payment_tax" data-search="off" name="vendor_payment_tax" required>
                                                <?php echo $data['payment_gateway_tax']; ?>
                                            </select>
                                        </div>
                                    </div>
                                     <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Vendor Shipping Charges
                                            </label>
                                            <input type="text" name="vendor_shipping_charges" value="<?php echo $data['info']['vendor_shipping_charges'] ?>" id="vendor_shipping_charges" class="form-control" placeholder="Vendor Shipping Charges" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Shipping Tax
                                                
                                            </label>
                                             <select class="form-control form-control-lg" id="vendor_shipping_tax" data-search="off" name="vendor_shipping_tax" required>
                                                <?php echo $data['shipping_tax']; ?>
                                            </select>
                                        </div>
                                    </div>
                            </div>
                        </div>

                         <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Shipping Cost</h5>
                                The physical address of your store or place of business. If you don't have a business address, enter the address from which you ship orders.
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Minimum Free Shipping Order Value
                                            </label>
                                            <input type="text" name="minimum_order_value" value="<?php echo $data['info']['maximum_order_value'] ?>" id="minimum_order_value" class="form-control" placeholder="Maximum Order Value" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Shipping Tax
                                                
                                            </label>
                                             <select class="form-control form-control-lg" id="order_shipping_tax" data-search="off" name="order_shipping_tax" required>
                                                <?php echo $data['order_shipping_tax']; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Shipping Cost
                                            </label>
                                            <input type="text" name="single_vendor_shipping_cost" value="<?php echo $data['info']['single_vendor_shipping_cost'] ?>" id="single_vendor_shipping_cost" class="form-control" placeholder="Single Vendor Shipping Cost" >
                                        </div>
                                        <div class="form-group col-md-6 display_none">
                                            <label class="form-label"> Multi Vendor Shipping Cost
                                            </label>
                                            <input type="text" name="multi_vendor_shipping_cost" value="0" id="multi_vendor_shipping_cost" class="form-control" placeholder="Multi Vendor Shipping Cost" >
                                        </div>
                                    </div>
                            </div>
                        </div>

                        <div class="card card-shadow">
                                <div class="card-inner">
                                    <h5 class="card-title">Logo </h5>
                                   
                                    <?php if ($data['info']['logo']==''){ ?>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label class="form-label" >Header Logo</label>
                                                <div class="form-control-wrap">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="header_logo" id="header_logo"  >
                                                        <label class="custom-file-label" for="file">Choose file</label>
                                                        <input type="hidden" name="file_type" value="image" >
                                                    </div>
                                                    <p class="help_text"><?php echo BRANCH_IMAGE_HELP_TEXT ?></p>
                                                </div>
                                            </div>
                                        </div>

                                    <?php }else{ ?>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label class="form-label" >Header Logo</label>
                                                <div class="form-control-wrap">
                                                    <img src="<?php echo UPLOADS.$data['info']['logo'] ?>" class="img-thumbnail img-responsive" >
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="form-label" >Update Header Logo</label>
                                                        <div class="form-control-wrap">
                                                            <div class="custom-file">
                                                                <input type="file"  class="custom-file-input" name="header_logo" id="header_logo"  >
                                                                <label class="custom-file-label" for="file">Choose file</label>
                                                                <input type="hidden" name="file_type" value="image" >
                                                            </div>
                                                            <p class="help_text"><?php echo BRANCH_IMAGE_HELP_TEXT ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($data['info']['footer_logo']==''){ ?>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label class="form-label" >Footer Logo</label>
                                                <div class="form-control-wrap">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="footer_logo" id="footer_logo"  >
                                                        <label class="custom-file-label" for="file">Choose file</label>
                                                        <input type="hidden" name="file_type" value="image" >
                                                    </div>
                                                    <p class="help_text"><?php echo BRANCH_IMAGE_HELP_TEXT ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }else{ ?>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label class="form-label" >Footer Logo</label>
                                                <div class="form-control-wrap">
                                                    <img src="<?php echo UPLOADS.$data['info']['footer_logo'] ?>" class="img-thumbnail img-responsive" >
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="form-label" >Update Footer Logo</label>
                                                        <div class="form-control-wrap">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" name="footer_logo" id="footer_logo"  >
                                                                <label class="custom-file-label" for="file">Choose file</label>
                                                                <input type="hidden" name="file_type" value="image" >
                                                            </div>
                                                            <p class="help_text"><?php echo BRANCH_IMAGE_HELP_TEXT ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

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
<?php if (isset($_GET['a'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Company info updated successfully !!</h5>', 'success', {
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

<script type="text/javascript">

    $("#reg_state").select2({
        tags: false
    });

    $("#vendor_payment_tax").select2({
        tags: false
    });

    $("#vendor_shipping_tax").select2({
        tags: false
    });

    $("#order_shipping_tax").select2({
        tags: false
    });

    // Add Company

    $("#updateCompanyInfo").validate({
        rules: {
            company_name: {
                required: true
            },
            gst_no: {
                required: true
            },
             address_one: {
                required: true
            },
             email_one: {
                required: true
            },
             contact_no_one: {
                required: true
            },
             whatsapp_one: {
                required: true
            }
        },
        messages: {
            company_name: {
                required: "Please Enter Company Name",
            },
            gst_no: {
                required: "Please Enter GST Number",
            },
            address_one: {
                required: "Please Enter Address One",
            },
             email_one: {
                required: "Please Enter Emai One",
            },
             contact_no_one: {
                required: "Please Enter Contact No One",
            },
             whatsapp_one: {
                required: "Please Enter Whatsapp One",
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
        var formname = document.getElementById("updateCompanyInfo");
        var formData = new FormData(formname);
        $.ajax({
            url: core_path + "company/api/updateCompanyInfo",
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

                var data = JSON.parse(data);

                if (data['data_stored'] == 1) {
                    window.location = core_path + "company/profile?a=success";
                } else {
                    $(".form-error").show();
                    $(".form-error").html(data['error']);
                    toastr.clear();
                    NioApp.Toast('<h5>' + data['error'] + '</h5>', 'error', {
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
