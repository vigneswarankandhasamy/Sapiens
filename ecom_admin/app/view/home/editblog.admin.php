<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="editBlog" enctype="multipart/form-data">
                        <input type="hidden" name="session_token" id="session_token" value="<?php echo $data['token'] ?>">
                        <?php echo $data[ 'csrf_edit_blog'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>blog"><i class="icon ni ni-arrow-left"></i></a>  </h2>
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
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>blog"> Cancel</button>
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
                                        <label class="form-label"> Title
                                            <em>*</em>
                                        </label>
                                        <input type="text" name="title_name" id="title_name" value="<?php echo $data['info']['title'] ?>"  class="form-control" placeholder="Enter Blog Title" required <?php echo (($data['info']['is_draft']==1)? "" : "disabled") ;?> >
                                    </div>
                                    <div class="form-group col-md-3 select_item_wrap">
                                        <label class="form-label"> Category<em>*</em>
                                        </label>
                                        <div class="form-control-wrap">
                                            <select class="form-control form-control-lg add_blog_category" data-search="on" name="blog_category" required>
                                                <?php echo $data[ 'category']; ?>
                                            </select>
                                            <p class="help_text">* Type to add category</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-label">Blog Date <em>*</em>
                                        </label>
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-right">
                                                <em class="icon ni ni-calendar-alt"></em>
                                            </div>
                                            <input type="text" class="form-control date-picker" name="blog_date" value="<?php echo date('d/m/Y',strtotime($data['info']['blog_date'])) ?>" data-date-format="dd/mm/yyyy" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="form-label">Short description <em>*</em>
                                        </label>
                                        <div class="form-control-wrap">
                                            <textarea class="form-control" rows="2"  name="short_description"><?php echo $data['info']['short_description'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="form-label" >Description <!-- <em>*</em> -->
                                        </label>
                                        <textarea class="summernote-editor" name="description" id="description" required=""><?php echo $data['info']['description'] ?></textarea> 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Blog Image </h5>
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
                                                <p class="help_text"><?php echo BLOG_IMAGE_HELP_TEXT ?></p>
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
                                                        <p class="help_text"><?php echo BLOG_IMAGE_HELP_TEXT ?></p>
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

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">SEO Content </h5>
                                <?php if ($data['info']['is_draft']==1){ ?>
                                    <div class="form-group">
                                        <label class="form-label" >Page URL <em>*</em>
                                        </label>
                                        <input type="text" name="page_url" id="page_url" value="<?php echo $data['info']['page_url'] ?>"  class="form-control" data-validation="" placeholder="Enter Page URL" >
                                    </div>
                                <?php }else{ ?>
                                     <div class="form-group">
                                        <label class="form-label" >Page URL <em>*</em>
                                        </label>
                                        <input type="text" value="<?php echo $data['info']['page_url'] ?>"  required="" class="form-control" data-validation="" placeholder="Enter Page URL" disabled >
                                        <input type="hidden" value="<?php echo $data['info']['page_url'] ?>" name="page_url">
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label class="form-label" for="default-01">Meta Title <em>*</em>
                                    </label>
                                    <input type="text" name="meta_title" id="meta_title" value="<?php echo $data['info']['meta_title'] ?>" class="form-control" data-validation="" placeholder="Enter Title">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="default-01">Meta Description <em>*</em>
                                    </label>
                                    <textarea class="form-control" rows="5" name="meta_description"  placeholder="Enter Meta Description"><?php echo $data['info']['meta_description'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="default-01">Meta Keywords <em>*</em>
                                    </label>
                                    <textarea name="meta_keyword" id="meta_keyword"  rows="6" class="form-control" data-validation="" placeholder="Enter Meta Keyword"><?php echo $data['info']['meta_keyword'] ?></textarea>
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

    // Select2 Option

    $(".add_blog_category").select2({
        tags: true
    });
    
    // Add Blog

    $("#editBlog").validate({
        rules: {
            title_name: {
                required: true
            },
            blog_category: {
                required: true
            },
             page_url: {
                required: true
            },
            meta_title: {
                required: true
            },
            meta_description: {
                required: true
            },
            meta_keyword: {
                required: true
            }
        },
        messages: {
            title_name: {
                required: "Please Enter Blog Name",
            },
            blog_category: {
                required: "Please Select Blog Category",
            },
            page_url: {
                required: "Please Enter Page URL",
            },
            meta_title: {
                required: "Please Enter Meta Title",
            },
            meta_description: {
                required: "Please Enter Meta Description",
            },
            meta_keyword: {
                required: "Please Enter Meta Keyword",
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
        var formname = document.getElementById("editBlog");
        var formData = new FormData(formname);
        $.ajax({
            url: core_path + "blog/api/update",
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
                    window.location = core_path + "blog?e=success";
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