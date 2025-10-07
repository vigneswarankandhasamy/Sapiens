<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!--Breadcrumbs Started-->
                <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <div class="nk-block-des">
                                    <nav>
                                        <ul class="breadcrumb breadcrumb-arrow">
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a></li>
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>project">Manage Project</a>
                                            </li>
                                            <li class="breadcrumb-item active"><?php echo $data['page_title'] ?></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div>
                    </div>
                    <!--Breadcrumbs Ended--->
                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="addProjectImages" action="#">
                        <input type="hidden" name="title_name" value="<?php echo $data['info']['name'] ?>" >
                        <?php echo $data['csrf_add_project_images'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>project"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="submit_button_wrap">
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>project"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> <?php echo (($data['info']['is_draft']==1) ? "Save as Draft" : "Add") ?></button> 
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
                                        <input type="text" name="project_title" id="project_title"  class="form-control" placeholder="Enter Name" >
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
                                <h5 class="card-title">Project Images  </h5><div class="row">
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
    $("#addProjectImages").validate({
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
        var formname = document.getElementById("addProjectImages");
        var formData = new FormData(formname);

        $.ajax({
            url: core_path + "project/api/addProjectImages",
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
                    window.location = core_path + "?pa=success";
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
