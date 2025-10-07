<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="editTestimonials" enctype="multipart/form-data">
                        <input type="hidden" name="session_token" id="session_token" value="<?php echo $data['token'] ?>">
                        <?php echo $data[ 'csrf_edit_testimonials'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>testimonials"><i class="icon ni ni-arrow-left"></i></a>  </h2>
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
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>testimonials"> Cancel</button>
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
                                        <label class="form-label"> Name
                                            <em>*</em>
                                        </label>
                                        <input type="text" name="name" id="name" value="<?php echo $data['info']['name'] ?>"  class="form-control" placeholder="Enter name" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-label">  Designation
                                        </label>
                                       <input type="text" name="designation" id="designation" class="form-control" value="<?php echo $data['info']['designation'] ?>" placeholder="Enter Designation">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-label">  Sort Order
                                        </label>
                                        <input type="text" name="sort_order" id="sort_order" value="<?php echo $data['info']['sort_order'] ?>" class="form-control" placeholder="Enter Short order" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="form-label" >Description <em>*</em>
                                        </label>
                                        <textarea class="summernote-editor" name="description" id="description"><?php echo $data['info']['description'] ?></textarea> 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Testimonial Image </h5>
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
                                                <p class="help_text"><?php echo BANNER_IMAGE_HELP_TEXT ?></p>
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
                                                        <p class="help_text"><?php echo BANNER_IMAGE_HELP_TEXT ?></p>
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

    
    // Add Blog

    $("#editTestimonials").validate({
        rules: {
            name: {
                required: true
            },
            description: {
                required: true
            },
        },
        messages: {
            name: {
                required: "Please Enter Name",
            },
            description: {
                required: "Please Enter Description",
            },
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
        var formname = document.getElementById("editTestimonials");
        var formData = new FormData(formname);
        $.ajax({
            url: core_path + "testimonials/api/update",
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
                    window.location = core_path + "testimonials?e=success";
                } else {
                    //$(".form-error").show();
                    //$(".form-error").html(data);
                    toastr.clear();
                    NioApp.Toast('<h5>' + data + '</h5>', 'error', {
                        position: 'top-right',
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