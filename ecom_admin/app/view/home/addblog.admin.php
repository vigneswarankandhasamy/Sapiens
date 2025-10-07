<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="addBlog" enctype="multipart/form-data">
                        <?php echo $data[ 'csrf_add_blog'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>blog"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="submit_button_wrap">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input"  value="1"  id="draft_button" name="save_as_draft">
                                                <label class="custom-control-label" for="draft_button">Save as draft</label>
                                            </div>
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>blog"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Save & Publish</button>
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
                                        <input type="text" name="title_name" id="title_name" class="form-control" placeholder="Enter Blog Title" required>
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
                                            <input type="text" class="form-control date-picker" name="blog_date" value="<?php echo date('d/m/Y') ?>" data-date-format="dd/mm/yyyy" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="form-label">Short description <em>*</em>
                                        </label>
                                        <div class="form-control-wrap">
                                            <textarea class="form-control" rows="2"  name="short_description"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="form-label" >Description <!-- <em>*</em> -->
                                        </label>
                                        <textarea class="summernote-editor" name="description" id="description" required></textarea> 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Blog Image </h5>
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
                                        <input type="text" name="image_name" id="image_name" class="form-control" placeholder="Enter name of the image" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label" > Image Alt Name 
                                        </label>
                                        <input type="text" name="image_alt_name" id="image_alt_name" class="form-control" placeholder="Enter alt name for the image" >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">SEO Optimization </h5>
                                <div class="form-group">
                                    <label class="form-label" >Page URL <em>*</em>
                                    </label>
                                    <input type="text" name="page_url" id="page_url" class="form-control" data-validation="" placeholder="Enter Page URL">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" >Meta Title <em>*</em>
                                    </label>
                                    <input type="text" name="meta_title" id="meta_title" class="form-control" data-validation="" placeholder="Enter Meta Title">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" >Meta Description <em>*</em>
                                    </label>
                                    <textarea class="form-control" rows="5" name="meta_description" id="meta_description" placeholder="Enter Meta Description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" >Meta Keywords <em>*</em>
                                    </label>
                                    <textarea name="meta_keyword" id="meta_keyword" rows="6" class="form-control" data-validation="" placeholder="Enter Meta Keyword"></textarea>
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

    $("#addBlog").validate({
        rules: {
            title_name: {
                required: true
            },
            short_description: {
                required: true
            },
            description: {
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
                required: "Please Enter Blog Title",
            },
             short_description: {
                required: "Please Enter Short Description",
            },
             description: {
                required: "Please Enter Description",
            },
            blog_category: {
                required: "Required",
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
        var formname = document.getElementById("addBlog");
        var formData = new FormData(formname);
        $.ajax({
            url: core_path + "blog/api/add",
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
                    window.location = core_path + "blog?a=success";
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