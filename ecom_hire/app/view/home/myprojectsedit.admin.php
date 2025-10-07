<?php require_once 'includes/top.php'; 
?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="editProjectImages" action="#">
                        <input type="hidden" name="title_name" value="<?php echo $data['info']['name'] ?>" >
                        <?php echo $data['csrf_add_project_images'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="submit_button_wrap">
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>project"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> <?php echo (($data['info']['is_draft']==1) ? "Save as Draft" : "Update") ?></button> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-shadow">
                            <div class="card-inner">
                                 <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Project Title <em>*</em> 
                                        </label>
                                        <input type="text" name="project_title" id="project_title" value="<?php echo $data['editDetail']['project_title'] ?>" class="form-control" >
                                        <input name="project_id" id="project_id" value="<?php echo $data['editDetail']['id'] ?>" style="display:none;" >
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="form-label" >Description <!-- <em>*</em> -->
                                        </label>
                                        <textarea class="summernote-editor" name="description" id="description" required><?php echo $data['editDetail']['description'];?></textarea> 
                                    </div>
                                </div>
                            </div>
                        </div>                  

                       <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Project Images  </h5><div class="row">

                                <div class="previous_images_wrap" id="previous_images_wrap">
                                            <?php echo $data['editImgDetail']?>
                                </div>
                                    <div class="form-group col-md-12">
                                        <div class="input-images"></div>
                                        <div class="form-error text-danger"></div>
                                        <p class="help_text"><?php echo PRODUCT_IMAGE_HELP_TEST ?></p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </form>
                    <!-- <div class="card card-shadow">
                        <div class="card-inner">
                            <h5 class="card-title">Added Images</h5>
                            <div class="row col-md-12" >
                                <?php echo $data['added_images']; ?>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
                <!-- .nk-block -->
        </div>
    </div>
</div>

<!-- content @e -->

<div class="image_viewer_wrap">
    <form method="POST" id="image_form">
        <div class="image_viewer_head">
            <h6 class="mb-0">Image Preview </h6>
            <div class="col-md-7">
                <p style="float:right;" class="text-left"><button type="button" class="btn btn-outline-danger pull-left delete_image" > <em class="icon ni ni-trash-fill"></em> &nbsp; Delete Image</button></p>
            </div>
            <a class="nk-demo-close toggle btn btn-icon btn-trigger revarse mr-n2 active closeImageViewer"  href="javascript:void();"><em class="icon ni ni-cross"></em></a>
            <input type="hidden" name="image_id" id="image_id" value="">
            <input type="hidden" id="image_option" value="">
        </div>
        <div class="image_viewer_content" id="image_viewer_content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="image_preview" id="image_preview">
                            <p><img id="image_object" src=""></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label"> Current Image Name 
                            </label>
                            <input type="text" class="form-control" id="edit_image_name" placeholder="Enter image name" readonly="" >
                        </div>
                        <?php if ($data['info']['is_draft']==1): ?>
                            <div class="form-group">
                                <label class="form-label"> New Image Name 
                                </label>
                                <input type="text" class="form-control" name="image_name"  placeholder="Enter new image name"  >
                            </div>
                        <?php endif ?>
                        
                        <div class="form-group">
                            <label class="form-label"> Alt Name 
                            </label>
                            <input type="text" class="form-control" id="edit_image_alt_name" name="alt_name"  placeholder="Enter Alt name" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="image_viewer_footer">
            <div class="container">
                <div class="row">
                    
                    <div class="col-md-12">
                        <p class="pull-right">
                            
                            <button type="button" class="btn btn-light closeImageViewer"> Cancel</button>
                            <button type="submit" class="btn btn-success pull-right"><em class="icon ni ni-check-thick"></em> Update</button>
                        </p>
                    </div>
                </div>
            </div>
            
        </div>
    </form>
</div>

<div class="image_viewer_overlay" >
    
    
</div>

<?php require_once 'includes/bottom.php'; ?>


<script type="text/javascript">

    $(".removeProjectImage").click(function() {
        var id = $(this).data("id");
         $.ajax({
            type: "POST",
            url: core_path + "project/api/removeImg",
            dataType: "html",
            data: { result: id },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                location.reload();
            }
        });
        return false;
    });
    // Add
    $("#editProjectImages").validate({
       rules: {
            project_title: {
                required: true
            },
            images: {
                required: true
            }
            
        },
        messages: {
            project_title: {
                required: "Please Enter Project Title",
            },
            images: {
                required: "Please Enter Name",
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
        var formname = document.getElementById("editProjectImages");
        var formData = new FormData(formname);
        
        $.ajax({
            url: core_path + "project/api/editProjectImages",
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
                    window.location = core_path + "project?a=success";
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

<script src="<?php echo JSPATH ?>editvariant.js"></script>

<script type="text/javascript">
    $('.input-images').imageUploader();
</script>

<?php if (isset($_GET[ 'up'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        toastr.clear();
        NioApp.Toast('<h5>Your Company Profile Details Updated successfully. Add your project hear !</h5>', 'success', {
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
