<?php require_once 'includes/top.php'; ?> 

    <!-- content @s -->
    <div class="nk-content nk-content-fluid">
        <div class="container-fluid wide-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title"> <?php echo $data['page_title'] ?></h3>
                                <div class="nk-block-des">
                                    <nav>
                                        <ul class="breadcrumb breadcrumb-arrow">
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a></li>
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>hiresettings">Expert</a></li>
                                            <li class="breadcrumb-item active"><?php echo $data['page_title'] ?></li>
                                        </ul>
                                    </nav>
                                </div>

                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addClassifiedProfileModal" ><em class="icon ni ni-plus"></em> Add Expert Category</button>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <table class="datatable-init nk-tb-list nk-tb-ulist is-compact" data-auto-responsive="false">
                                    <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">UID</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Expert Category</span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></th>
                                            <th class="nk-tb-col tb-col-lg"><span class="sub-text">Action</span></th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $data['list'] ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content @e -->

    <div class="modal fade zoom" tabindex="-1" id="addClassifiedProfileModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Add Expert Category</h5>
                </div>
                <div class="modal-body">
                    <form id="addClassifiedProfile" method="post" enctype="multipart/form-data">
                        <?php echo $data['csrf_add_cont_profile'] ?>
                        <div class="form-group">
                            <label class="form-label">Expert Category
                                <en>*</en>
                            </label>
                            <input type="text" name="profile" id="title_name"  class="form-control" placeholder="Enter Expert Category" required>
                        </div>
                        <div class="form-group">
                                <label class="form-label">Short Description <em>*</em></label>
                            <textarea class="form-control" rows="2"  name="short_description" placeholder="Short Description"></textarea>
                        </div>
                        <div class="form-group">
                            <p class="float-right model_pt">
                                <button type="button" class="btn btn-light close_modal" data-modal_id="addClassifiedProfileModal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade zoom" tabindex="-1" id="editClassifiedProfileModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Edit Expert Category</h5>
                </div>
                <div class="modal-body">
                    <form id="editClassifiedProfile" method="post" enctype="multipart/form-data">
                        <?php echo $data['csrf_edit_cont_profile'] ?>
                            <div class="edit_classified_profile_modal_body">
                                <div class='form-group'>
                                    <label class='form-label'>Category
                                        <en>*</en>
                                    </label>
                                    <input type="hidden" name="contractor_id" id="contractor_id">
                                    <input type="hidden" name="token" id="contractor_token">
                                    <input type='text' name='profile' id='title_name_edit' value='' class='form-control' placeholder='Enter Expert Category' required>
                                </div>
                                 <div class='form-group'>
                                    <label class='form-label'>Short Description <em>*</em></label>
                                <textarea class='form-control' rows='2'  name='short_description' id="short_description_edit" placeholder='Short Description'></textarea>
                            </div>
                            <div class='row upload_image display_none'>
                                <div class='form-group col-md-12'>
                                    <label class='form-label' >Image</label>
                                    <div class='form-control-wrap'>
                                        <div class='custom-file'>
                                            <input type='file'  class='custom-file-input' name='file' id='file'  >
                                            <label class='custom-file-label' for='file'>Choose file</label>
                                            <input type='hidden' name='file_type' value='image' >
                                        </div>
                                        <p class='help_text'><?php echo HIRE_PROFILE_BANNER_IMAGE_HELP_TEXT ?></p>
                                    </div>
                                </div>
                                <div class='form-group col-md-6'>
                                    <label class='form-label' > Image Name 
                                    </label>
                                    <input type='text' name='image_name'  id='image_name' class='form-control' placeholder='Enter name of the image' >
                                </div>
                                <div class='form-group col-md-6'>
                                    <label class='form-label' > Image Alt Name 
                                    </label>
                                    <input type='text' name='image_alt_name'  id='image_alt_name' class='form-control' placeholder='Enter alt name for the image' >
                                </div>
                            </div>
                            <div class='row update_image display_none'>
                                <div class='form-group col-md-4'>
                                    <div class='form-control-wrap'>
                                        <img src="" class='img-thumbnail img-responsive' id="uploaed_image" >
                                    </div>
                                </div>
                                <div class='col-md-8'>
                                    <div class='row'>
                                        <div class='form-group col-md-12'>
                                            <label class='form-label' >Update Image</label>
                                            <div class='form-control-wrap'>
                                                <div class='custom-file'>
                                                    <input type='file'  class='custom-file-input' name='file' id='file'  >
                                                    <label class='custom-file-label' for='file'>Choose file</label>
                                                    <input type='hidden' name='file_type' value='image' >
                                                </div>
                                                <p class='help_text'><?php echo HIRE_PROFILE_BANNER_IMAGE_HELP_TEXT ?></p>
                                            </div>
                                        </div>
                                        <div class='form-group col-md-12'>
                                            <label class='form-label' >New Image Name 
                                            </label>
                                            <input type='text' name='image_name' id='image_name' class='form-control' placeholder='Enter name of the image'  >
                                        </div>
                                        <div class='form-group col-md-6'>
                                            <label class='form-label' > Current Image Name 
                                            </label>
                                            <input type='text' id="current_img_name"   class='form-control' placeholder='Enter name of the image' disabled >
                                        </div>
                                        <div class='form-group col-md-6'>
                                            <label class='form-label' > Image Alt Name 
                                            </label>
                                            <input type='text' name='image_alt_name'  id='image_alt_name_update' class='form-control' placeholder='Enter alt name for the image' >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group'>
                                <p class='float-right model_pt'>
                                    <button type='button' class='btn btn-light close_modal' data-modal_id='editClassifiedProfileModal'>Cancel</button>
                                    <button type='submit' class='btn btn-primary'>Update</button>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



<div class="form_panel_overlay" ></div>

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    // Open Edit Hire Category Pop Up

    $(".openEditClassifiedProfile").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        var formclass = $(this).data("formclass");
        var form = $(this).data("form");
        $.ajax({
            type: "POST",
            url: core_path + "hireprofile/api/info",
            dataType: "html",
            data: { result: value },
           
            success: function(data) {
                var data = JSON.parse(data);
                $("#contractor_id").val(data['id']);
                $("#title_name_edit").val(data['profile']);
                $("#contractor_token").val(data['token']);
                $("textarea#short_description_edit").val(data['short_description']);
                if(data['file_name']=="") {
                    $(".upload_image").removeClass("display_none");
                    $(".update_image").html("");
                } else {
                    $(".update_image").removeClass("display_none");
                    $(".upload_image").html("");
                    $("#image_alt_name_update").val(data['file_alt_name']);
                    $("#current_img_name").val(data['file_name']);
                    $("#uploaed_image").attr("src","<?php echo UPLOADS ?>"+data['file_name']);
                }
                $("#editClassifiedProfileModal").modal("show");
            }
        });
        return false;
    });

            
    // Add Hire Category

    $("#addClassifiedProfile").validate({
        rules: {
            profile: {
                required: true
            },
            short_description: {
                required: true
            }
        },
        messages: {
            profile: {
                required: "Please Enter Expert Category",
            },
            short_description: {
                required: "Please Enter Short Description",
            }
        },
        submitHandler: function(form) {
            var formname = document.getElementById("addClassifiedProfile");
            var formData = new FormData(formname);
            $.ajax({
                url: core_path + "hireprofile/api/add",
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
                        window.location = core_path + "hireprofile?a=success";
                    } else {
                        $(".form-error").show();
                        $(".form-error").html(data);
                        toastr.clear();
                        NioApp.Toast('<h5>'+data+'</h5>', 'error', {
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
            return false;
        }
    });

    // Active & Inactive Status For Hire Category

    $(".changeClassifiedProfileStatus").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        // alert(value);
        $.ajax({
            type: "POST",
            url: core_path + "hireprofile/api/status",
            dataType: "html",
            data: { result: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                if (data == 1) {
                    //alert(data);
                    location.reload();
                } else {
                    toastr.clear();
                    NioApp.Toast('<h5>'+data+'</h5>', 'error', {
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
        return false;
    });

   


    // Edit Hire Category

    $("#editClassifiedProfile").validate({
        rules: {
            profile: {
                required: true
            },
            short_description: {
                required: true
            }
        },
        messages: {
            profile: {
                required: "Please Enter Expert Category",
            },
            short_description: {
                required: "Please Enter Short Description",
            }
        },
        submitHandler: function(form) {
            var formname = document.getElementById("editClassifiedProfile");
            var formData = new FormData(formname);
            $.ajax({
                url: core_path + "hireprofile/api/update",
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
                        window.location = core_path + "hireprofile?e=success";
                    } else {
                        $(".form-error").show();
                        $(".form-error").html(data);
                        toastr.clear();
                        NioApp.Toast('<h5>'+data+'</h5>', 'error', {
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
            return false;
        }
    });

    // Delete Hire Category 

    $(".deleteClassifiedProfile").click(function(e) {
        toastr.clear();
        var formclass = $(this).data("formclass");
        var form = $(this).data("form");
        Swal.fire({
            title: "Are you sure to delete?",
            text: "Once deleted the item cannot be retrieved",
            icon: 'warning',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.value) {
                var value = $(this).data("option");
                $.ajax({
                    type: "POST",
                    url: core_path + "hireprofile/api/delete",
                    dataType: "html",
                    data: { result: value },
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            //alert(data);
                            window.location = core_path + "hireprofile?d=success";
                        } else {
                            toastr.clear();
                            NioApp.Toast('<h5>'+data+'</h5>', 'error', {
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
        });
        e.preventDefault();
        return false;
    });


</script>

<?php if (isset($_GET['a'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Expert Category added successfully !!</h5>', 'success', {
        position: 'top-right', 
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

<?php if (isset($_GET['e'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Expert Category Updated successfully !!</h5>', 'success', {
        position: 'top-right', 
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


<?php if (isset($_GET['d'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Expert Category Deleted successfully !!</h5>', 'success', {
        position: 'top-right', 
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