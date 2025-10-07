<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="editVendor" >
                        <input type="hidden" name="session_token" id="session_token" value="<?php echo $data['token'] ?>">
                        <?php echo $data['csrf_edit_classified'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>hire"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="submit_button_wrap">
                                            <?php if (($data['info']['is_draft']==1)): ?>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"  value="1"  id="draft_button" name="save_as_draft" <?php echo (($data['info']['is_draft']==1) ? 'checked' : '') ?>>
                                                    <label class="custom-control-label" for="draft_button">Save as draft</label>
                                                </div>
                                            <?php endif ?>
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>hire"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> <?php echo (($data['info']['is_draft']==1) ? "Save as Draft" : "Update") ?></button> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-error"></div>
                       <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">General Info</h5>
                                 <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Name <em>*</em> 
                                        </label>
                                        <input type="text" name="name" id="name" value="<?php echo $data['info']['name'] ?>" class="form-control" placeholder="Enter Name" >
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Company Name <em>*</em>
                                        </label>
                                        <input type="text" name="title_name" id="title_name" value="<?php echo $data['info']['company_name'] ?>" class="form-control" placeholder="Enter Company Name" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Email <em>*</em>  
                                            </label> 
                                            <input type="email" name="email" value="<?php echo $data['info']['email'] ?>" id="email" class="form-control" placeholder="Enter email">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Mobile <em>*</em> 
                                        </label> 
                                        <input type="text" name="mobile" value="<?php echo $data['info']['mobile'] ?>" id="mobile" class="form-control" autocomplete="off" placeholder="Enter Mobile Number" >
                                    </div>
                                     <div class="form-group col-md-6">
                                        <label class="form-label">Phone 
                                        </label> 
                                        <input type="text" name="phone" value="<?php echo $data['info']['phone'] ?>" id="phone" class="form-control" autocomplete="off" placeholder="Enter Phone Number" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Whatsapp 
                                        </label> 
                                        <input type="text" name="whatsapp" value="<?php echo $data['info']['whatsapp'] ?>" id="whatsapp" class="form-control" autocomplete="off" placeholder="Enter Whatsapp Number" >
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-label">Experience  <em>*</em>
                                        </label> 
                                        <input type="text" name="experience" value="<?php echo $data['info']['experience'] ?>"  id="experience" class="form-control" autocomplete="off" placeholder="Enter Experience" >
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-label">Month/Year  <em>*</em>
                                        </label> 
                                        <select class="form-select"   name="experience_duration"  id="experience_duration" data-placeholder="Select Duration" >
                                            <option value='0'>Select Duration  </option>
                                            <option value="month" id="experienceMonth" 
                                                <?php if($data['info']['experience_duration']=="Month" || $data['info']['experience_duration']=="Months" ) {
                                                    echo "selected";
                                                } ?> 
                                            >Month</option>
                                            <option value="year" id="experienceYear" 
                                                <?php if($data['info']['experience_duration']=="Year" || $data['info']['experience_duration']=="Years" ) {
                                                    echo "selected";
                                                } ?> 
                                            >Year</option>
                                        </select>
                                        <div class=" error experience_duration_error_update"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Profile Type</h5>
                                <div class="row">
                                    
                                        
                                        <!-- <label class="form-label">  Profile -->
                                        <!-- </label> -->
                                        <!-- <select class="form-select  profile_type" id="profile_type" data-search="off" name="profile_type" required> -->
                                            <!-- <div class="row"> -->
                                                <?php echo $data['classified_profile']; ?>
                                            <!-- </div> -->
                                        <!-- </select> -->
                                        
                                   
                                </div>
                            </div>
                        </div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Contact Info </h5>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="form-label">Address <em>*</em></label>
                                            <div class="form-control-wrap">
                                            <textarea class="form-control" rows="2"  name="address"><?php echo $data['info']['address'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label" >City <em>*</em>
                                        </label>
                                        <input type="text" name="city" id="city" class="form-control" value="<?php echo $data['info']['city'] ?>" data-validation="" placeholder="Enter City">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Select State <em>*</em></label>
                                        <div class="form-control-wrap">
                                            <select class="form-select" name="state_id" data-search="on">
                                               <?php echo $data['state_list']; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label" >Pincode <em>*</em>
                                        </label>
                                        <input type="text" name="pincode" id="pincode" class="form-control" value="<?php echo $data['info']['pincode'] ?>" data-validation="" placeholder="Enter Pincode">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label" >GST No <em>*</em>
                                        </label>
                                        <input type="text" name="gst_no" id="gst_no" class="form-control" value="<?php echo $data['info']['gst_no'] ?>" data-validation="" placeholder="Enter GST No">
                                    </div>
                                </div>
                            </div>
                        </div>


                        
                         <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Other Info </h5>
                                <div class="row" >
                                   
                                <div class="form-group col-md-6">
                                    <label class="form-label">Total Projects 
                                    </label> 
                                    <!-- <input type="text" name="total_projects" value="<?php echo $data['info']['total_projects'] ?>" id="total_projects" class="form-control" autocomplete="off" placeholder="Enter Total Projects" > -->

                                    <span class="form-control">
                                        <?php echo $data['project']['id'] ?>
                                    </span>
                                        
                                </div>
                                  <div class="form-group col-md-6 ">
                                     <label class="form-label">Service Tags</label>
                                        <div class="form-control-wrap">
                                            <select class="form-select" multiple="multiple"  data-placeholder="Select Service Tags" name="service_tags[]" data-search="on">
                                               <?php echo $data['service_tags']; ?>
                                            </select>
                                        </div>
                                </div>
                                <div class="form-group col-md-12">
                                        <label class="form-label" >Description 
                                        </label>
                                        <textarea class="summernote-editor" name="description" id="description"><?php echo $data['info']['description'] ?></textarea> 
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Banner Image </h5>
                                <?php if ($data['info']['file_name']==''){ ?>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="form-label" >Image</label>
                                            <div class="form-control-wrap">
                                                <div class="custom-file">
                                                    <input type="file" multiple class="custom-file-input" name="file" id="file"  >
                                                    <label class="custom-file-label" for="file">Choose file</label>
                                                    <input type="hidden" name="file_type" value="image" >
                                                </div>
                                                <p class="help_text"><?php echo HIRE_EMP_PROFILE_IMAGE_HELP_TEXT ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" > Image Name 
                                            </label>
                                            <input type="text" name="image_name" value="<?php echo $data['info']['file_alt_name'] ?>" id="image_name" class="form-control" placeholder="Enter name of the image" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" > Image Alt Name 
                                            </label>
                                            <input type="text" name="image_alt_name" value="<?php echo $data['info']['file_alt_name'] ?>" id="image_alt_name" class="form-control" placeholder="Enter alt name for the image" >
                                        </div>
                                    </div>

                                <?php }else{ ?>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <div class="form-control-wrap">
                                                <img src="<?php echo UPLOADS.$data['info']['file_name'] ?>" class="img-thumbnail img-responsive" >
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label class="form-label" >Update Image</label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" multiple class="custom-file-input" name="file" id="file"  >
                                                            <label class="custom-file-label" for="file">Choose file</label>
                                                            <input type="hidden" name="file_type" value="image" >
                                                        </div>
                                                        <p class="help_text"><?php echo HIRE_EMP_PROFILE_IMAGE_HELP_TEXT ?></p>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label class="form-label" >New Image Name 
                                                    </label>
                                                    <input type="text" name="image_name" id="image_name" class="form-control" placeholder="Enter name of the image"  >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="form-label" > Current Image Name 
                                                    </label>
                                                    <input type="text" value="<?php echo $data['info']['file_name'] ?>"  class="form-control" placeholder="Enter name of the image" disabled >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="form-label" > Image Alt Name 
                                                    </label>
                                                    <input type="text" name="image_alt_name" value="<?php echo $data['info']['file_alt_name'] ?>" id="image_alt_name" class="form-control" placeholder="Enter alt name for the image" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
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

     $.validator.addMethod("check_experience_duration", function (value, elem) {
            var experience_duration = $("#experience_duration").val();
            if(experience_duration == 0) {
                return false;
            } else {
                return true;
            }
    });

    // Add hire

    $("#editVendor").validate({
          rules: {
            name: {
                required: true
            },
            title_name: {
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
            phone: {
                digits: true,
                maxlength: 10,
                minlength: 10
            },
            whatsapp: {
                digits: true,
                maxlength: 10,
                minlength: 10
            },
            address: {
                required: true
            },
            city: {
                required: true
            },
            pincode: {
                required: true
            },
            gst_no: {
                required: true
            },
            experience: {
                required: true
            },
            experience_duration: {
                check_experience_duration: true,
            }
            
        },
        messages: {
            name: {
                required: "Please Enter Name",
            },
            title_name: {
                required: "Please Enter Company",
            },
            email: {
                required: "Please Enter Email",
            },
            mobile: {
                required: "Please Enter your Mobile Number",
                maxlength: "Please enter valid 10 digit mobile number",
                minlength: "Please enter valid 10 digit mobile number",
                digits : "Please enter a valid mobile number"
            },
            phone: {
                maxlength: "Please enter valid 10 digit Phone number",
                minlength: "Please enter valid 10 digit Phone number",
                digits : "Please enter a valid Phone number"
            },
            whatsapp: {
                maxlength: "Please enter valid 10 digit Whatsapp number",
                minlength: "Please enter valid 10 digit Whatsapp number",
                digits : "Please enter a valid Whatsapp number"
            },
            address: {
                required: "Please enter address ",
            },
            city: {
                required: "Please enter city ",
            },
            pincode: {
                required: "Please enter pincode",
            },
            gst_no: {
                required: "Please enter GST invoice number",
            },
            experience: {
                required:  "Please enter Work experience",
            },
            experience_duration: {
                check_experience_duration:  "Please Select Experience Duration" ,
            }
            
        },

        errorPlacement: function(error, element) {
            if(element.attr("name") == "experience_duration") {
                error.appendTo(".experience_duration_error_update");
            } else {
                error.insertAfter(element);
            }
        },

        submitHandler: function(form) {
            var check = null;
            toastr.clear();
            if ($("#draft_button").prop("checked") == false) {
                Swal.fire({
                    title: "Are you sure to Save and Publish?",
                    text: "Once published the item will be available for permenant delete. However shall be moved to trash !!",
                    icon: 'warning',
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if (result.value) {
                        saveform();
                    }
                });
            }else{
                saveform();
            }
            return false;
        }
    });

    // Save form

    function saveform() {
        var formname = document.getElementById("editVendor");
        var formData = new FormData(formname);
        $.ajax({
            url: core_path + "hire/api/update",
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
                    window.location = core_path + "hire?e=success";
                } else {
                    //$(".form-error").show();
                    //$(".form-error").html(data);
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