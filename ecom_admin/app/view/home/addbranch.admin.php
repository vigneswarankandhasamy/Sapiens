<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="addBranch" enctype="multipart/form-data">
                        <?php echo $data[ 'csrf_add_branch'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>settings"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="submit_button_wrap">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input"  value="1"  id="draft_button" name="save_as_draft">
                                                <label class="custom-control-label" for="draft_button">Save as draft</label>
                                            </div>
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>settings"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Save & Publish</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-error"></div>
                        <div class="branch_row">
                             <div class="card card-shadow">
                                <div class="card-inner">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="form-label"> Branch Name
                                                <em>*</em>
                                            </label>
                                            <input type="text" name="branch_name"  id="branch_name" class="form-control" placeholder="Branch Name" >
                                        </div>
                                        
                                       
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> GST Number
                                                <em>*</em>
                                            </label>
                                            <input type="text" name="gst_no"  id="gst_no" class="form-control" placeholder="Email One" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Registered State
                                            </label>
                                            <select class="form-control form-control-lg " data-search="on" name="reg_state" required>
                                                <?php echo $data[ 'state']; ?>
                                            </select>
                                        </div>
                                         
                                         <div class="form-group col-md-6">
                                            <label class="form-label">Address One<em>*</em>
                                            </label>
                                            <div class="form-control-wrap">
                                                <textarea class="form-control" rows="2"  name="address_one"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label">Address Two 
                                            </label>
                                            <div class="form-control-wrap">
                                                <textarea class="form-control" rows="2"  name="address_two"></textarea>
                                            </div>
                                        </div>

                                         <div class="form-group col-md-6">
                                            <label class="form-label"> Email One
                                                <em>*</em>
                                            </label>
                                            <input type="text" name="email_one" id="email_one" class="form-control" placeholder="Email One" >
                                        </div>
                                         <div class="form-group col-md-6">
                                            <label class="form-label"> Email Two
                                            </label>
                                            <input type="text" name="email_two"  id="email_two" class="form-control" placeholder="Email Two" >
                                        </div>
                                         <div class="form-group col-md-6">
                                            <label class="form-label"> Contact Number One
                                                <em>*</em>
                                            </label>
                                            <input type="text" name="contact_no_one"  id="contact_no_one" class="form-control" placeholder="Contact Number One" >
                                        </div>
                                         <div class="form-group col-md-6">
                                            <label class="form-label"> Contact Number Two
                                            </label>
                                            <input type="text" name="contact_no_two"  id="contact_no_two" class="form-control" placeholder="Contact Number Two" >
                                        </div>
                                         <div class="form-group col-md-6">
                                            <label class="form-label"> Whats App Number One
                                                <em>*</em>
                                            </label>
                                            <input type="text" name="whatsapp_one"  id="whatsapp_one" class="form-control" placeholder="Whats App Number One" >
                                        </div>
                                         <div class="form-group col-md-6">
                                            <label class="form-label"> Whats App Number Two
                                            </label>
                                            <input type="text" name="whatsapp_two"  id="whatsapp_two" class="form-control" placeholder="Whats App Number Two" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Facebook
                                            </label>
                                            <input type="text" name="facebook"  id="facebook" class="form-control" placeholder="Facebook" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Twitter
                                            </label>
                                            <input type="text" name="twitter"  id="twitter" class="form-control" placeholder="Twitter" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Google
                                            </label>
                                            <input type="text" name="googleplus"  id="googleplus" class="form-control" placeholder="Google" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> RSS
                                            </label>
                                            <input type="text" name="rss"  id="rss" class="form-control" placeholder="RSS" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Pinterest
                                            </label>
                                            <input type="text" name="pinterest"  id="pinterest" class="form-control" placeholder="Pinterest" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Linkedin
                                            </label>
                                            <input type="text" name="linkedin"  id="linkedin" class="form-control" placeholder="Linkedin" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Youtube
                                            </label>
                                            <input type="text" name="youtube"  id="youtube" class="form-control" placeholder="Youtube" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Instagram
                                            </label>
                                            <input type="text" name="instagram"  id="instagram" class="form-control" placeholder="Instagram" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-shadow">
                            <div class="card-inner">
                            <h5 class="card-title">Logo </h5>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="form-label" >Image</label>
                                        <div class="form-control-wrap">
                                            <div class="custom-file">
                                                <input type="file" multiple class="custom-file-input" name="file" id="file"  >
                                                <label class="custom-file-label" for="file">Choose file</label>
                                                <input type="hidden" name="file_type" value="image" >
                                            </div>
                                            <p class="help_text"><?php echo BRANCH_IMAGE_HELP_TEXT ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div >
                        </div>
                    </form>
                </div>
                <!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

<!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    // Add Branch

    $("#addBranch").validate({
       rules: {
            branch_name: {
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
            branch_name: {
                required: "Please Enter Branch Name",
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
            if ($("#draft_button").prop("checked") == false) {
                Swal.fire({
                    title: "Are you sure to Save and Publish?",
                    text: "Once published the item will not be deleted permanently. However move to trash option is available !!",
                    icon: 'warning',
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if (result.value) {
                        saveForm();
                    }
                });
            }else{
                saveForm();
            }
            return false;
        }
    });

    // Save Form

    function saveForm() {
        var formname = document.getElementById("addBranch");
        var formData = new FormData(formname);
        $.ajax({
            url: core_path + "company/api/add",
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
                    window.location = core_path + "company/branch?a=success";
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