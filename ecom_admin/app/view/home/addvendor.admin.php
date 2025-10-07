 <?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="addVendor" action="#">
                        <?php echo $data['csrf_add_vendor'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>vendor"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="submit_button_wrap">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input"  value="1"  id="draft_button" name="save_as_draft">
                                                <label class="custom-control-label" for="draft_button">Save as draft</label>
                                            </div>
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>vendor"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Save & Publish</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-error"> </div>
                        
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">General Info</h5>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Name
                                            <em>*</em>
                                        </label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Company
                                            <em>*</em>
                                        </label>
                                        <input type="text" name="company" id="company" class="form-control" placeholder="Enter Company" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Email  <em>*</em>
                                            </label> 
                                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter email">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Mobile <em>*</em>
                                        </label> 
                                        <input type="text" name="mobile" id="mobile" class="form-control" autocomplete="off" placeholder="Enter Mobile Number" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Valid From <em>*</em>
                                        </label> 
                                        <div class="form-control-wrap">
                                            <?php if(1 != 1) { ?>
                                            <!-- <div class="form-icon form-icon-left">
                                                <em class="icon ni ni-calendar"></em>
                                            </div> -->
                                            <?php  } ?>
                                            <input type="text" name="valid_from"  id="valid_from" autocomplete="off" class="form-control date-picker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Validity Days <em>*</em>
                                        </label> 
                                        <input type="text" name="validity_days" id="validity_days" class="form-control" autocomplete="off" placeholder="Enter Validity Days" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">  Vendor Location  
                                        </label>
                                         <select class="form-control form-control-lg vendor_location" id="vendor_location" data-search="off" name="vendor_location" required>
                                            <?php echo $data['vendor_location']; ?>
                                        </select>
                                    </div>
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
                                            <textarea class="form-control" rows="2"  name="address"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label" >City <em>*</em>
                                        </label>
                                        <input type="text" name="city" id="city" class="form-control" data-validation="" placeholder="Enter City">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label" >Pincode <em>*</em>
                                        </label>
                                        <input type="text" name="pincode" id="pincode" class="form-control" data-validation="" placeholder="Enter Pincode">
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
                                        <label class="form-label" >GST No <em>*</em>
                                        </label>
                                        <input type="text" name="gst_no" id="gst_no" class="form-control" data-validation="" placeholder="Enter GST No">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <!-- <h5 class="card-title">Product Varants info </h5> -->
                                <div class="app-heading app-heading-small">
                                    <div class="title">
                                        <label class='checkbox-inline checkbox-success'>
                                            <input class='styled' type='checkbox' id="selectAllPost"> Select All
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <?php echo $data['locations'] ?>
                                    <span class="clearfix"></span>
                                </div>
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

    $(".vendor_location").select2({
        tags: false
    });

    // Select All Categories

    $('#selectAllPost').click(function() {
        $('.post_permission').prop('checked', this.checked);
    });

    // Select all variants while selecting category
    $('.main_permission').click(function() {
        var option = $(this).data("option");
        $('.sub_permission_' + option).prop('checked', this.checked);
    });

    // Select category  while selecting variants
    $('.sub_menu_permission').click(function() {
        var option = $(this).data("option");
        $('#main_' + option).prop('checked', 'true');
        valthis(option);
    });

    function valthis(option) {
    var checkBoxes = document.getElementsByClassName( 'sub_permission_'+option );
    var isChecked = false;
        for (var i = 0; i < checkBoxes.length; i++) {
            if ( checkBoxes[i].checked ) {
                isChecked = true;
            };
        };
        if (! isChecked ) {
                $('#main_' + option).prop('checked', false);
            }   
    }



    // Add
    $("#addVendor").validate({
       rules: {
            name: {
                required: true
            },
            company: {
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
            valid_from: {
                required: true
            },
            validity_days: {
                required: true,
                digits: true,

            },
            address: {
                required: true
            },
            city: {
                required: true
            },
            pincode: {
                required: true,
                digits: true,

            },
            gst_no: {
                required: true
            },
            
        },
        messages: {
            name: {
                required: "Please Enter Name",
            },
            company: {
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
            valid_from: {
                required: "Please Enter Valid Form Date",
            },
            validity_days: {
                required: "Please Enter Validity Days",
                digits : "Please enter a valid Days"

            },
            address: {
                required: "Please Enter Address",
            },
            city: {
                required: "Please Enter City",
            },
            pincode: {
                required: "Please Enter Pincode",
                digits : "Please enter a valid Pincode"

            },
            gst_no: {
                required: "Please Enter GST No",
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
        var formname = document.getElementById("addVendor");
        var formData = new FormData(formname);
        
        $.ajax({
            url: core_path + "vendor/api/add",
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
                    window.location = core_path + "vendor?a=success";
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