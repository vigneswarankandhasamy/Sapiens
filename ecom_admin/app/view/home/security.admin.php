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

                    <li class="nav-item <?php echo (($data['active_menu']=='security') ? 'active current-page' : '') ?>">
                        <a class="nav-link" href="<?php echo COREPATH ?>profile/security">Security<span class="d-none s-sm-inline"> Setting</span></a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="<?php echo COREPATH ?>profile#">Notifications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo COREPATH ?>profile#">Connect Social</a>
                    </li> -->
                </ul>
                <div class="nk-block">
                    <div class="nk-block-head">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title">Security Settings</h5>
                            <div class="nk-block-des">
                                <p>These settings are helps you keep your account secure.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card card-bordered">
                        <div class="card card-bordered">
                            <div class="card-inner-group">
                                <div class="card-inner">
                                            <div class="between-center flex-wrap flex-md-nowrap g-3">
                                                <div class="nk-block-text">
                                                    <h6>Save my Activity Logs</h6>
                                                    <p>You can save your all activity logs including unusual activity detected.</p>
                                                </div>
                                                <div class="nk-block-actions">
                                                    <ul class="align-center gx-3">
                                                        <li class="order-md-last">
                                                            <div class="custom-control custom-switch mr-n2">
                                                                <input type="checkbox" class="custom-control-input" checked="" id="activity-log">
                                                                <label class="custom-control-label" for="activity-log"></label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                <div class="card-inner">
                                    <div class="between-center flex-wrap flex-md-nowrap g-3">
                                        <div class="nk-block-text">
                                            <h6>Change Password</h6>
                                            <p>Set a unique password to protect your account.</p>
                                        </div>
                                        <div class="nk-block-actions ">
                                                <ul class="align-center flex-wrap flex-sm-nowrap gx-3 gy-2">
                                                    <li class="order-md-last">
                                                        <div class="data-col data-col-end">
                                                            <div data-toggle="modal" data-target="#profile-edit" class="btn btn-primary">Change Password</div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <em class="text-soft text-date fs-12px">Last changed: <span><?php echo $data['time'] ?></span></em>
                                                    </li>
                                                </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-inner">
                                    <div class="between-center flex-wrap flex-md-nowrap g-3">
                                        <div class="nk-block-text">
                                            <h6>2FA Authentication <span class="badge badge-success">Enabled</span></h6>
                                            <p>Secure your account with 2FA security. When it is activated you will need to enter not only your password, but also a special code using app. You can receive this code by in mobile app. </p>
                                        </div>
                                        <div class="nk-block-actions">
                                            <a href="<?php echo COREPATH ?>profile/security#" class="btn btn-primary">Disable</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-head-content">
                            <div class="nk-block-title-group">
                                <h6 class="nk-block-title title">Recent Activity</h6>
                                <a href="<?php echo COREPATH ?>profile/loginactivity" class="link">See full log</a>
                            </div>
                            <div class="nk-block-des">
                                <p>This information about the last login activity on your account.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card card-bordered">
                        <table class="table table-ulogs is-compact">
                            <thead class="thead-light">
                                <tr>
                                    <th class="tb-col-os"><span class="overline-title">Browser <span class="d-sm-none">/ IP</span></span></th>
                                    <th class="tb-col-ip"><span class="overline-title">IP</span></th>
                                    <th class="tb-col-time"><span class="overline-title">Time</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php echo $data['list']; ?>
                            </tbody>
                        </table>
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
                <h5 class="title">Change Password</h5>
                <ul class="nk-nav nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#personal">Security</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="personal">
                        <form action="#" id="changeAdminPassword" method="POST">
                            <?php echo $data[ 'csrf_change_password'] ?>
                            <div class="row gy-4">
                                <div class="col-md-12 form-error display_none" >
                                    <div class="form-error"></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="current-password">Current Password</label>
                                        <input type="password" class="form-control form-control-lg" name="password" id="password" placeholder="Current Password">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="new-password">New Password</label>
                                        <input type="password" class="form-control form-control-lg" name="new_password" id="new_password" placeholder="New Password">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="confirm-password">Confirm Password</label>
                                        <input type="password" class="form-control form-control-lg" name="re_password" id="re_password" placeholder="Confirm Password">
                                    </div>
                                </div>

                                <div class="col-12 pull-right">
                                    <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                        <li>
                                            <button type="submit" class="btn btn-lg btn-primary">Update</button>
                                        </li>
                                        <li>
                                            <a href="#" data-dismiss="modal" class="link link-light">Cancel</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- .tab-content -->
            </div>
            <!-- .modal-body -->
        </div>
        <!-- .modal-content -->
    </div>
    <!-- .modal-dialog -->
</div>
<!-- .modal -->

<?php require_once 'includes/bottom.php'; ?>

<?php if (isset($_GET[ 'u'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        toastr.clear();
        NioApp.Toast('<h5>Password changed successfully !!</h5>', 'success', {
            position: 'bottom-center',
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
    // Change Admin Password

    $("#changeAdminPassword").validate({
        rules: {
            password: {
                required: true
            },
            new_password: {
                required: true,
            },
            re_password: {
                required: true,
                equalTo: "#new_password"
            }
        },
        messages: {
            password: {
                required: "Please Enter your Current Password",
            },
            new_password: {
                required: "Please Enter New Password",
            },
            re_password: {
                required: "Please Re-type Your New Password",
            }
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "profile/api/changePassword",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "profile/security?u=success";
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
            return false;
        }
    });

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
            return false;
        }
    });
</script>