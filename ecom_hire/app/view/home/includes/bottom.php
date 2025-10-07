    <!-- Update Company Info Pop-up  -->
   
    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static"  id="modalTabs">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <!-- <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a> -->
                <div class="modal-body modal-body-sm">
                    <h4 class="title">Update Profile</h4>
                    <ul class="nk-nav nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link profile_info_tab active" data-toggle="tab" href="#ProfileInfo">Profle Info</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link contact_info_tab disabled" data-toggle="tab" href="#ContactInfo" >Contact Info</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane profile_info_form active" id="ProfileInfo">
                            <form method="post" class="form-validate is-alter" id="addProfileInfo" action="#">
                                <?php echo $data['user_info']['csrf_update_profile'] ?>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="form-label"> Company Name <em>*</em>
                                        </label>
                                        <input type="text" name="title_name" id="title_name" value="<?php echo $data['user_info']['company_name'] ?>" class="form-control title_name" placeholder="Enter Company Name" >
                                        <div class="form-group title_name_error col-md-12 display_none form_from_to_error">
                                            Please enter company name
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label class="form-label"> Profile Type <em>*</em>
                                        </label>
                                        <div class="row">
                                            <?php echo $data['user_info']['classified_profile']; ?>
                                        </div>
                                        <div class="form-group profile_sellect_error col-md-12 display_none form_from_to_error">
                                            Select at least one profile type 
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="form-label">Service Tags</label>
                                        <div class="form-control-wrap">
                                            <select class="form-select serviceTags"  multiple="multiple" id="serviceTags" data-placeholder="Select Service Tags" name="service_tags[]" data-search="on">
                                               <?php echo $data['user_info']['service_tags']; ?>
                                            </select>
                                            <div class="form-group servicetag_select_error col-md-12 display_none form_from_to_error">
                                                Please select service tag 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 profile_info_error display_none form_from_to_error">
                                        
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label class="form-label">Experience  <em>*</em>
                                        </label> 
                                        <input type="text" name="experience" value="<?php echo $data['user_info']['experience'] ?>"  id="experience" class="form-control" autocomplete="off" placeholder="Enter Experience" >
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-label">Month/Year  <em>*</em>
                                        </label> 
                                        <select class="form-select"   name="experience_duration_pop_up"  id="experience_duration_pop_up" data-placeholder="Select Duration" >
                                            <option value='0'>Select Duration  </option>
                                            <option value="month" id="experienceMonth" 
                                                <?php if($data['user_info']['experience_duration']=="Month" || $data['user_info']['experience_duration']=="Months" ) {
                                                    echo "selected";
                                                } ?> 
                                            >Month</option>
                                            <option value="year" id="experienceYear" 
                                                <?php if($data['user_info']['experience_duration']=="Year" || $data['user_info']['experience_duration']=="Years" ) {
                                                    echo "selected";
                                                } ?> 
                                            >Year</option>
                                        </select>
                                        <div class=" error experience_duration_error_pop_up"></div>
                                    </div>
                                     
                                    <div class="form-group col-md-12">
                                        <p class="float-right model_pt">
                                            <button type="submit" class="btn btn-primary check_profile_info " data-form="viewReview" data-formclass="view_contact_class"> Next</button>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane contact_info_form " id="ContactInfo">
                            <form method="post" class="form-validate is-alter" id="addContactInfo" action="#">
                            <?php echo $data['user_info']['csrf_update_profile'] ?>
                            <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="form-label">Address <em>*</em></label>
                                            <div class="form-control-wrap">
                                            <textarea class="form-control" rows="2"  name="address"><?php echo $data['user_info']['address'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label" >City <em>*</em>
                                        </label>
                                        <input type="text" name="city" id="city" class="form-control" data-validation="" value="<?php echo $data['user_info']['city'] ?>" placeholder="Enter City">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Select State <em>*</em></label>
                                        <div class="form-control-wrap">
                                            <select class="form-control" name="state_id" data-search="on">
                                               <?php echo $data['user_info']['state_list']; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label" >Pincode <em>*</em>
                                        </label>
                                        <input type="text" name="pincode" id="pincode" class="form-control" value="<?php echo $data['user_info']['pincode'] ?>"   data-validation="" placeholder="Enter Pincode">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label" >GST No <em>*</em>
                                        </label>
                                        <input type="text" name="gst_no" id="gst_no" class="form-control" value="<?php echo $data['user_info']['gst_no'] ?>"  data-validation="" placeholder="Enter GST No">
                                    </div>
                                    <div class="form-group col-md-6 contact_info_error display_none form_from_to_error">
                                        
                                    </div>
                                    <div class="form-group col-md-12">
                                        <p class="float-right model_pt">
                                            <button type="submit" class="btn btn-primary " data-form="viewReview" data-formclass="view_contact_class"> Submit</button>
                                        </p>
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <!--Update Company Info Pop-up End  -->

    <!-- footer @s -->
            <div class="nk-footer nk-footer-fluid bg-lighter">
                <div class="container">
                    <div class="nk-footer-wrap">
                        <div class="nk-footer-copyright"> &copy; <?php echo date("Y")." ".COMPANY_NAME ?>. All rights reserved.
                        </div>
                        <div class="nk-footer-links">
                            <ul class="nav nav-sm">
                                <li class="nav-item"><a class="nav-link" href="#">Terms</a></li>
                                <li class="nav-item"><a class="nav-link" href="#">Privacy</a></li>
                                <li class="nav-item"><a class="nav-link" href="#">Help</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- footer @e -->
        </div>
        <!-- wrap @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="<?php echo JSPATH ?>bundle.js?ver=2.2.0"></script>
    <script src="<?php echo JSPATH ?>scripts.js?ver=2.2.0"></script>
    <script src="<?php echo JSPATH ?>charts/gd-default.js?ver=2.2.0"></script>
    <script src="<?php echo JSPATH ?>charts/gd-analytics.js?ver=2.2.0"></script>
    <script src="<?php echo JSPATH ?>admin.js"></script>


    <?php if ($data['scripts']=='addmigrations' || $data['scripts']=='adduser' || $data['scripts']=='addproduct' || $data['scripts']=='addblog' || $data['scripts']=='editblog' || $data['scripts']=='addtestimonials' || $data['scripts']=='edittestimonials'): ?>
        <link rel="stylesheet" href="<?php echo CSSPATH ?>editors/summernote.css?ver=2.2.0">
        <script src="<?php echo JSPATH ?>libs/editors/summernote.js?ver=2.2.0"></script>
        <script type="text/javascript">
            var _basic = '.summernote-editor';
        if ($(_basic).exists()) {
            $(_basic).each(function(){
                $(this).summernote({
                    placeholder: '',
                    tabsize: 2,
                    height: 250,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'strikethrough', 'clear']],
                        ['font', ['superscript','subscript']],
                        ['fontsize', ['fontsize', 'height']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['view', ['fullscreen', 'codeview', 'help']],

                    ]
                });
            });
        }

        </script>
    <?php endif ?>

     <?php if ($data['scripts']=='addproduct'): ?>
        <script src="<?php echo JSPATH ?>image-uploader/image-uploader.js"></script>
        <script src="<?php echo JSPATH ?>tags/bootstrap-tagsinput.js"></script>
        <script type="text/javascript">
            $(".tags_input").tagsinput('items')
        </script>
    <?php endif ?>

    <script type="text/javascript">
    /*-------------------------------------
        Pop-up  From function
    --------------------------------------*/

    window.onload = () => {

        var company_name = "<?php echo $data['user_info']['company_name'] ?>";
        var city = "<?php echo $data['user_info']['city'] ?>";
        var state_name = "<?php echo $data['user_info']['state_name'] ?>";
        
        if(!company_name  ) {
            $("#modalTabs").modal("show");
        } else if(!city || !state_name) {
            //Tab Changes
            $(".profile_info_tab").removeClass("active");
            $(".contact_info_tab").addClass("active");
            $(".contact_info_tab").removeClass("disabled");
            
            //Tab Content changes
            $(".profile_info_form").removeClass("active");
            $(".contact_info_form").addClass("active");
            $("#modalTabs").modal("show");
        }

     }

    // Profile info form submission

    $.validator.addMethod("check_experience_duration", function (value, elem) {
            var experience_duration = $("#experience_duration").val();
            if(experience_duration == 0) {
                return false;
            } else {
                return true;
            }
    });


    $("#addProfileInfo").validate({
        rules: {
           
            title_name: {
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
           
            title_name: {
                required: "Please enter company name",
            },
            experience: {
                required:  "Please enter Work experience",
            },
            experience_duration: {
                check_experience_duration:  "Please Select Experience Duration" ,
            }
        },

        errorPlacement: function(error, element) {
            if(element.attr("name") == "experience_duration_pop_up") {
                error.appendTo(".experience_duration_error_pop_up");
            } else {
                error.insertAfter(element);
            }
        },

        submitHandler: function(form) {

            var submit_form = true;

            if(!$(".classified_profile").is(':checked')) {
               $(".profile_sellect_error").show();
               var submit_form = false;
               $(".contact_info_tab").addClass("disabled");
            } else {
                var submit_form = submit_form;
            }

            var serviceTags = $(".serviceTags").val();

            if(serviceTags=="") {
               $(".servicetag_select_error").show();
               var submit_form = false;
               $(".contact_info_tab").addClass("disabled");
            } else {
                var submit_form = submit_form;
            }

            if(submit_form) {
                var formname = document.getElementById("addProfileInfo");
                var formData = new FormData(formname);

                $.ajax({
                    url: core_path + "profile/api/updateProfileInfo",
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
                        if(data==1) {
                            //Tab Changes
                            $(".profile_info_tab").removeClass("active");
                            $(".contact_info_tab").addClass("active");
                            $(".contact_info_tab").removeClass("disabled");
                            
                            //Tab Content changes
                            $(".profile_info_form").removeClass("active");
                            $(".contact_info_form").addClass("active");
                        } else {
                            $(".profile_info_error").html(data);
                            $(".profile_info_error").show();
    
                        }
                       
                    }
                });
            }
            return false;
        }
    });
    
    // Mandatory Profile info field validations

    $(".check_profile_info").click(function() {
        
        var classified_profile = $(".classified_profile").val();
        var serviceTags        = $(".serviceTags").val();
        
        if(!$(".classified_profile").is(':checked')) {
           $(".profile_sellect_error").show();
           $(".contact_info_tab").addClass("disabled");
        } 

        if(serviceTags=="") {
           $(".servicetag_select_error").show();
           $(".contact_info_tab").addClass("disabled");
        } 
    });

    $(".classified_profile").click(function() {
        var check_profile = checkProfileCheck();
        if(check_profile) {
            $(".profile_sellect_error").hide();
        } else {
            $(".profile_sellect_error").show();
            $(".contact_info_tab").addClass("disabled");
        }
    });

    function checkProfileCheck() {
        if(!$(".classified_profile").is(':checked')) {
           return false;
        } else {
            return true;
        }
    }
    $(".serviceTags").change(function() {
        if($(this).val()!="") {
             $(".servicetag_select_error").hide();
        } else {
             $(".servicetag_select_error").show();
             $(".contact_info_tab").addClass("disabled");
        } 
    });

    // Contact Info form submission

    $("#addContactInfo").validate({
      rules: {
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
            state_id: {
                required: true
            }
        },
        messages: {
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
            state_id: {
                required: "Please select state",
            }
            
        },
        submitHandler: function(form) {
            var formname = document.getElementById("addContactInfo");
            var formData = new FormData(formname);

            $.ajax({
                url: core_path + "profile/api/updateContactInfo",
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
                    if(data==1) {
                      window.location = core_path + "project?up=success";
                    } else {
                        $(".contact_info_error").html(data);
                        $(".contact_info_error").show();
                        
                    }
                   
                }
            });
            return false;
        }
    });



    /*---------------------------END-----------------------------*/

</script>

     

</body>

</html>