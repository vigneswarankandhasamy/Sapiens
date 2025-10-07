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
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>settings">Settings</a></li>
                                            <li class="breadcrumb-item active"><?php echo $data['page_title'] ?></li>
                                        </ul>
                                    </nav>
                                </div>

                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#addReturnSettingModal"></em> Add Return Setting</button>
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
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Return Duration Type</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Duration</span></th>
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
    <div class="modal fade zoom" tabindex="-1" id="addReturnSettingModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Add Return Setting</h5>
                </div>
                <div class="modal-body">
                    <form id="addReturnSetting" method="POST">
                        <?php echo $data['csrf_add_return_settings'] ?>
                        <div class="form-group">
                            <label class="form-label">Return Duration Type
                                <en>*</en>
                            </label>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" class="custom-control-input return_setting_type" name="returnSetttingType" value="days" id="customRadio7" checked="">
                                        <label class="custom-control-label" for="customRadio7">Days</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" class="custom-control-input return_setting_type" name="returnSetttingType" value="hours" id="customRadio6">
                                        <label class="custom-control-label" for="customRadio6">Hours</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="form-label">Duration
                                <en>*</en>
                            </label>
                             <div class="row">
                                <div class="col-md-6 days_input">
                                    <input type="text" name="days_count"  id="days_count" class="form-control" placeholder="Enter Days">
                                </div>
                                <div class="col-md-2 hours_input display_none">
                                    <label class="form-label">Hours</label>
                                    <input type="text" name="hours"  id="hours" class="form-control" value="0"  placeholder="HH">
                                </div>
                                <div class="col-md-2 hours_input display_none">
                                    <label class="form-label">Minutes</label>
                                    <input type="text" name="minutes" id="minutes"  class="form-control" value="0"  placeholder="MM">
                                </div>
                            </div>
                             
                        </div>
                        <div class="form-group">
                            <p class="float-right model_pt">
                                <button type="button" class="btn btn-light close_modal" data-modal_id="addReturnSettingModal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

     <div class="modal fade zoom" tabindex="-1" id="editReturnSettingModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title"> Edit Return Setting Modal</h5>
                </div>
                <div class="modal-body">
                    <form id="editReturnSetting" method="POST">
                    <?php echo $data['csrf_edit_return_settings'] ?>
                        <div class="edit_return_setting_modal_body"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>



<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">


    // Hours and days inputs hide/show

    $("body").on("click",".return_setting_type",function() {
        var value = $(this).val();
        if(value=="hours") {
            $(".hours_input").removeClass("display_none");
            $(".days_input").addClass("display_none");
        } else {
            $(".hours_input").addClass("display_none");
            $(".days_input").removeClass("display_none");;
        }
    });

     // Hours and days inputs hide/show while edit

    $("body").on("click",".edit_return_setting_type",function() {
        var value = $(this).val();
        if(value=="hours") {
            $(".edit_hours_input").removeClass("display_none");
            $(".edit_days_input").addClass("display_none");
        } else {
            $(".edit_hours_input").addClass("display_none");
            $(".edit_days_input").removeClass("display_none");;
        }
    });

    

    $.validator.addMethod("hours_minmum", function (value, elem) {
        if($("#minutes").val()==0 || $("#minutes").val()=="" ){
            if($("#hours").val() < 1) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    });
    
    // Add Return Setting

    $("#addReturnSetting").validate({
        rules: {
            days_count: {
                required: true,
                digits: true,
                min: 1
            },
            hours: {
                required: function(element) {
                    if($("#minutes").val()==0 || $("#minutes").val()=="" ){
                        return true;
                    } else {
                        return false;
                    }
                },
                hours_minmum : true,
                digits: true,
            },
            minutes: {
                digits: true,
                max:59
            },
        },
        messages: {
            days_count: {
                required: "Duration day can't be empty",
                digits : "Enter a valid days",
                min : "Duration must be grater than or equal to 1 day ",
            },
            hours: {
                required: "Hours can't be empty",
                digits : "Enter a valid hours",
                hours_minmum : "hours must be grater than or equal to 1 hour ",
            },
            minutes: {
                digits : "Enter a valid minutes",
                max : "Minutes must be less than or equal to 59. ",
            }
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "returnsettings/api/add",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    console.log(data);
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "returnsettings?a=success";
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

    // Active & Inactive Status For Return Setting

    $(".changeReturnSettingStatus").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        // alert(value);
        $.ajax({
            type: "POST",
            url: core_path + "returnsettings/api/status",
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

    // Open Edit Return Setting Pop Up

    $(".openEditReturnSetting").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "returnsettings/api/info",
            dataType: "html",
            data: { result: value },
            success: function(data) {

                var data = $.parseJSON(data)

                $(".edit_return_setting_modal_body").html(data['layout']);
                if(data['return_setting']=="days") {
                    $("#customRadio8").prop("checked",true);
                    $(".edit_hours_input").addClass("display_none");
                    $(".edit_days_input").removeClass("display_none");
                } else {
                    $("#customRadio9").prop("checked",true);
                    $(".edit_hours_input").removeClass("display_none");
                    $(".edit_days_input").addClass("display_none");
                }
                $("#editReturnSettingModal").modal("show");
            }
        });
        return false;
    });

    $.validator.addMethod("hours_minmum_edit", function (value, elem) {
        if($("#edit_minutes").val()==0 || $("#edit_minutes").val()=="" ){
            if($("#edit_hours").val() < 1) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    });


    // Edit Return Setting

    $("#editReturnSetting").validate({
        rules: {
            days_count: {
                required: true,
                digits: true,
                min: 1
            },
            hours: {
                required: function(element) {
                    if($("#minutes").val()==0 || $("#minutes").val()=="" ){
                        return true;
                    } else {
                        return false;
                    }
                },
                hours_minmum_edit : true,
                digits: true,
            },
            minutes: {
                digits: true,
                max:59
            },
        },
        messages: {
            days_count: {
                required: "Duration day can't be empty",
                digits : "Enter a valid days",
                min : "Duration must be grater than or equal to 1 day ",
            },
            hours: {
                required: "Hours can't be empty",
                digits : "Enter a valid hours",
                hours_minmum : "hours must be grater than or equal to 1 hour ",
            },
            minutes: {
                digits : "Enter a valid minutes",
                max : "Minutes must be less than or equal to 59. ",
            }
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "returnsettings/api/update",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    console.log(data);
                    if (data == 1) {
                        window.location = core_path + "returnsettings?e=success";
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

    // Delete Return Setting 

    $(".deleteReturnSetting").click(function(e) {
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
                    url: core_path + "returnsettings/api/delete",
                    dataType: "html",
                    data: { result: value },
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            //alert(data);
                            window.location = core_path + "returnsettings?d=success";
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
    NioApp.Toast('<h5>Return Setting added successfully !!</h5>', 'success', {
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
    NioApp.Toast('<h5>Return Setting Updated successfully !!</h5>', 'success', {
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
    NioApp.Toast('<h5>Return Setting Deleted successfully !!</h5>', 'success', {
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