<?php require_once 'includes/top.php'; ?> 

    <!-- content @s -->
    <div class="nk-content nk-content-lg nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                   <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title"> <?php echo $data['page_title'] ?></h3>
                            </div>
                        </div>
                    </div>
                    <ul class="nk-nav nav nav-tabs">
                        <li class="nav-item <?php echo (($data['active_menu']=='profile') ? 'active current-page' : '') ?>">
                            <a class="nav-link" href="<?php echo COREPATH ?>profile">Profile</a>
                        </li>                     

                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo COREPATH ?>profile/security">Change Password</a>
                        </li>

                        <!-- <li class="nav-item">
                            <a class="nav-link" href="<?php echo COREPATH ?>profile#">Notifications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo COREPATH ?>profile#">Connect Social</a>
                        </li> -->
                    </ul>
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="nk-data data-list">
                                <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                    <div class="data-col">
                                        <span class="data-label">Name</span>
                                        <span class="data-value"><?php echo $data['user_info']['name'] ?></span>
                                    </div>
                                    <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                </div>
                                <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                    <div class="data-col">
                                        <span class="data-label">Email</span>
                                        <span class="data-value"><?php echo $data['user_info']['email'] ?></span>
                                    </div>
                                    <div class="data-col data-col-end"><span class="data-more"><em class="con ni ni-forward-ios"></em></span></div>
                                </div>
                                <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                    <div class="data-col">
                                        <span class="data-label">Phone Number</span>
                                        <span class="data-value"><?php echo $data['user_info']['mobile'] ?></span>
                                    </div>
                                    <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- content @e -->

<!-- @@ Profile Edit Modal @e -->
    <div class="modal fade" tabindex="-1" role="dialog" id="profile-edit">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-lg">
                    <h5 class="title">Update Profile</h5>
                    <ul class="nk-nav nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#personal">Personal</a>
                        </li>
                    </ul>
                    <form method="post" class="form-validate is-alter" id="editProfile" enctype="multipart/form-data">
                        <?php echo $data['csrf_update_profile'] ?>
                        <input type="hidden" name="profile_id" id="profile_id" value="<?php echo $data['user_info']['id'] ?>">
                        <div class="tab-content">
                            <div class="tab-pane active" id="personal">
                                <div class="row gy-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="name">Name</label>
                                            <input type="text" class="form-control form-control-lg" id="name" name="name" value="<?php echo $data['user_info']['name'] ?>" placeholder="Enter name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="text" class="form-control form-control-lg" id="email" name="email" value="<?php echo $data['user_info']['email'] ?>" placeholder="Enter email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="phone-no">Phone Number</label>
                                            <input type="text" class="form-control form-control-lg" id="mobile" name="mobile" value="<?php echo $data['user_info']['mobile'] ?>" placeholder="Enter Phone Number">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                            <li>
                                                <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Update Profile</button>
                                            </li>
                                            <li>
                                                <a href="#" data-dismiss="modal" class="link link-light">Cancel</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End modal -->

<?php require_once 'includes/bottom.php'; ?>

<?php if (isset($_GET['u'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Profile updated successfully !!</h5>', 'success', {
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

<script type="text/javascript">

    // Add Brand

    $("#editProfile").validate({
        rules: {
            name: {
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
        },
        messages: {
            name: {
                required: "Please Enter Name",
            },
            email: {
                required: "Please Enter Email",
            },
            mobile: {
                required: "Please Enter Mobile Number",
            },
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "profile/api/update",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "profile?u=success";
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
 
</script>
