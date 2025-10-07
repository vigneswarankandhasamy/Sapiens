<?php require_once 'includes/top.php'; ?> 

    <!-- content @s -->
    <div class="nk-content nk-content-fluid">
        <div class="container-fluid wide-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="row">
                            <div class="col-md-2">
                                <ul class="left_menu">
                                    <li>
                                        <a href="<?php echo COREPATH ?>locationcity" class="<?php echo (($data['locationgroup_menu']=="locationcity")?'active' : '') ?>">City</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>locationgroup" class="<?php echo (($data['locationgroup_menu']=="locationgroup")?'active' : '') ?>">Location Group</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>location" class="<?php echo (($data['location_menu']=="location")?'active' : '') ?>">Location</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-10">
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

                                        </div>
                                        <div class="nk-block-head-content">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLocationModal"><em class="icon ni ni-plus"></em> Add Location</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-shadow">
                                    <div class="card-inner">
                                        <table class="datatable-init nk-tb-list nk-tb-ulist is-compact" data-auto-responsive="false">
                                            <thead>
                                                <tr class="nk-tb-item nk-tb-head">
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">UID</span></th>
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Location</span></th>
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Pincode</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Group Name</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Visibility</span></th>
                                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Action</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo $data['list'] ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content @e -->

     <div class="modal fade zoom" tabindex="-1" id="addLocationModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Add Location</h5>
                </div>
                <div class="modal-body">
                    <form id="addLocation" method="POST">
                        <?php echo $data['csrf_add_location'] ?>
                        <div class="form-group">
                            <label class="form-label">Location
                                <en>*</en>
                            </label>
                            <input type="text" name="location" class="form-control" placeholder="Enter Location" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">City <en>*</en> </label>
                            <div class="form-control-wrap">
                                <select class="form-control form-control-lg select_city"  id="selectCity" data-for="add" data-search="on"  name="city_id" required>
                                    <option value="not_selected">Select City</option>
                                    <?php echo $data['city']; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group location_group_drp display_none">
                            <label class="form-label">Location Group <en>*</en> </label>
                            <div class="form-control-wrap">
                                <select class="form-control form-control-lg add_locationgroup" data-search="on"  name="group_id" required>
                                    <?php echo $data['location_group']; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pincode
                                <en>*</en>
                            </label>
                            <input type="text" name="pincode"  class="form-control" placeholder="Enter Pincode"  autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Longitude
                                <en>*</en>
                            </label>
                            <input type="text" name="longitude" id="longitude"  class="form-control" placeholder="Enter Longitude" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Latitude
                                <en>*</en>
                            </label>
                            <input type="text" name="latitude" id="latitude"  class="form-control" placeholder="Enter Latitude" required>
                        </div>
                        <div class="form-group">
                            <p class="float-right model_pt">
                                <button type="button" class="btn btn-light close_modal" data-modal_id="addLocationModal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade zoom" tabindex="-1" id="editLocationModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Edit Location</h5>
                </div>
                <div class="modal-body">
                    <form id="editLocation" method="POST">
                        <?php echo $data['csrf_edit_location'] ?>
                        <div class="edit_location_modal_body"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<?php require_once 'includes/bottom.php'; ?> 

<script type="text/javascript">
    
    $(".add_locationgroup").select2({
        tags: false
    });

     $(".select_city").select2({
        tags: false
    });

    $("#selectCity").on("change",function() { 
        if($(this).val()!="not_selected") {
            $(".location_group_drp").removeClass("display_none");
            $("#selectCity-error").remove();
        } else {
            $(".location_group_drp").addClass("display_none");
        }
    });

    $("body").on("change",".select_city",function() {
        var value = $(this).val();
        var drop_down = $(this).data('for');
        $.ajax({
            type: "POST",
            url: core_path + "location/api/locationgropList",
            dataType: "html",
            data: { result: value },
            success: function(data) {
                if(drop_down=='add') {
                    $(".add_locationgroup").html(data);
                } else {
                    $(".edit_locationgroup").html(data);
                }
            }
        });
        return false;
    });

    $.validator.addMethod("check_city",function(value, elem) {
        if($("#selectCity").val()=="not_selected") {
            return false;
        } else {
            return true;
        }

    });

    // Add Location 

    $("#addLocation").validate({
       rules: {
            location: {
                required: true
            },
            group_id: {
                required: true
            },
            city_id: {
                check_city: true
            },
            longitude: {
                required: true
            },
            pincode: {
                required: true,
                digits: true,
                maxlength: 6,
                minlength: 6
            },
            latitude: {
                required: true
            }
        },
        messages: {
            location: {
                required: "Please Enter Location",
            },
            group_id: {
                required: "Please Enter Group Name",
            },
            city_id: {
                check_city: "Please Select Location City ",
            },
            longitude: {
                required: "Please Enter Longitude",
            },
            pincode: {
                required    : "Please Enter Pincode",
                maxlength   : "Please enter valid 6 digit pincode",
                minlength   : "Please enter valid 6 digit pincode",
                digits      : "Please enter a valid pincode"
            },
            latitude: {
                required: "Please Enter Latitude",
            }
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "location/api/addlocation",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "location?a=success";
                    } else {
                        $(".form-error").show();
                        $(".form-error").html(data);
                        toastr.clear();
                        NioApp.Toast('<h5>'+data+'</h5>', 'error', {
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

    // Active & Inactive Status For Location

    $(".changeLocationStatus").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "location/api/locationstatus",
            dataType: "html",
            data: { result: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                if (data == 1) {
                    location.reload();
                } else {
                    toastr.clear();
                    NioApp.Toast('<h5>'+data+'</h5>', 'error', {
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
    });

    // Open Edit Location Pop Up

    $(".openEditLocation").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "location/api/locationinfo",
            dataType: "html",
            data: { result: value },
            success: function(data) {
               $(".edit_location_modal_body").html(data);
               $('.edit_city').select2();
               $('.edit_locationgroup').select2();
               $("#editLocationModal").modal("show");
            }
        });
        return false;
    });

     
    // Edit Location 

    $("#editLocation").validate({
       rules: {
            location: {
                required: true
            },
            group_id: {
                required: true
            },
            longitude: {
                required: true
            },
            pincode: {
                required: true,
                digits: true,
                maxlength: 6,
                minlength: 6
            },
            latitude: {
                required: true
            }
        },
        messages: {
            location: {
                required: "Please Enter Location",
            },
            group_id: {
                required: "Please Enter Group Name",
            },
            longitude: {
                required: "Please Enter Longitude",
            },
            pincode: {
                required: "Please Enter Pincode",
                maxlength: "Please enter valid 6 digit pincode",
                minlength: "Please enter valid 6 digit pincode",
                digits : "Please enter a valid pincode"
            },
            latitude: {
                required: "Please Enter Latitude",
            }
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "location/api/locationupdate",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "location?e=success";
                    } else {
                        $(".form-error").show();
                        $(".form-error").html(data);
                        toastr.clear();
                        NioApp.Toast('<h5>'+data+'</h5>', 'error', {
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

    // Delete Location

    $(".deleteLocation").click(function(e) {
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
                    url: core_path + "location/api/locationdelete",
                    dataType: "html",
                    data: { result: value },
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            window.location = core_path + "location?d=success";
                        } else {
                            toastr.clear();
                            NioApp.Toast('<h5>'+data+'</h5>', 'error', {
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
        });
        e.preventDefault();
        return false;
    });


</script>

<?php if (isset($_GET['a'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Location Added Successfully !!</h5>', 'success', {
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

<?php if (isset($_GET['e'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Location Updated Successfully !!</h5>', 'success', {
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


<?php if (isset($_GET['d'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Location Deleted Successfully !!</h5>', 'success', {
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