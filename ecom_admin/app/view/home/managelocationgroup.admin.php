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
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLocationGroupModal"><em class="icon ni ni-plus"></em> Add Location Group</button>
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
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Group Name</span></th>
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">City</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Shipping Cost</span></th>
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



    <div class="modal fade zoom" tabindex="-1" id="addLocationGroupModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Add Location Group</h5>
                </div>
                <div class="modal-body">
                    <form id="addLocationGroup" method="POST">
                        <?php echo $data['csrf_add_location_group'] ?>
                            <div class="form-group">
                                <label class="form-label">Group Name
                                    <en>*</en>
                                </label>
                                <input type="text" name="group_name"  class="form-control" placeholder="Enter Group Name" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Select City<en>*</en> </label>
                                <div class="form-control-wrap">
                                    <select class="form-select form-control-lg" data-search="on"  name="city_id" required>
                                        <?php echo $data['city']; ?>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="longitude" id="longitude"  class="form-control" placeholder="Enter Longitude" required>
                            <input type="hidden" name="latitude" id="latitude"  class="form-control" placeholder="Enter Latitude" required>
                            <div class="form-group">
                                <label class="form-label">Shipping Cost <en>*</en> </label>
                                <input type="number" name="shipping_cost" class="form-control" placeholder="Enter Shipping Cost" required>
                            </div>
                            <div class="form-group">
                                <p class="float-right model_pt">
                                    <button type="button" class="btn btn-light close_modal" data-modal_id="addLocationGroupModal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </p>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade zoom" tabindex="-1" id="editLocationGroupModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Edit Location Group</h5>
                </div>
                <div class="modal-body">
                    <form id="editLocationGroup" method="POST">
                    <?php echo $data['csrf_edit_location_group'] ?>
                    <div class="edit_location_group_modal"></div>
                    </form> 
                    <div class="mapped_locations">
                       
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'includes/bottom.php'; ?> 

<script type="text/javascript">
    
    // Add Location Group

    $("#addLocationGroup").validate({
        rules: {
            group_name: {
                required: true
            },
            // longitude: {
            //     required: true
            // },
            // latitude: {
            //     required: true
            // },
            shipping_cost: {
                required: true
            }
        },
        messages: {
            group_name: {
                required: "Please Enter Group Name",
            },
            shipping_cost: {
                required: "Please Enter Shipping Cost",
            },
            longitude: {
                required: "Please Enter Longitude",
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
                url: core_path + "locationgroup/api/add",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "locationgroup?a=success";
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

    // Active & Inactive Status For Location Group

    $(".changeLocationGroupStatus").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "locationgroup/api/status",
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

    // Open Edit Location Group Pop Up

    $(".openEditLocationGroup").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        var formclass = $(this).data("formclass");
        var form = $(this).data("form");
        $.ajax({
            type: "POST",
            url: core_path + "locationgroup/api/info",
            dataType: "html",
            data: { result: value },
            success: function(data) {
               var data = JSON.parse(data);
               $(".edit_location_group_modal").html(data['layout']);
               // $(".mapped_locations").find(".data").html(data['mapped_location']);
               $(".mapped_locations").html(data['mapped_location']);
               $('.edit_location_city').select2();
               $("#editLocationGroupModal").modal("show");
            }
        });
        return false;
    });


    // Edit Location Group

    $("#editLocationGroup").validate({
        rules: {
            group_name: {
                required: true
            },
            // edit_longitude: {
            //     required: true
            // },
            // edit_latitude: {
            //     required: true
            // },
            shipping_cost: {
                required: true
            }
        },
        messages: {
            group_name: {
                required: "Please Enter Group Name",
            },
            shipping_cost: {
                required: "Please Enter Shipping Cost",
            },
            edit_longitude: {
                required: "Please Enter Longitude",
            },
            edit_latitude: {
                required: "Please Enter Latitude",
            }
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "locationgroup/api/update",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "locationgroup?e=success";
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

    // Delete Location Group

    $(".deleteLocationGroup").click(function(e) {
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
                    url: core_path + "locationgroup/api/delete",
                    dataType: "html",
                    data: { result: value },
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            window.location = core_path + "locationgroup?d=success";
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
    NioApp.Toast('<h5>Location Group Name added successfully !!</h5>', 'success', {
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
    NioApp.Toast('<h5>Location Group Name Updated successfully !!</h5>', 'success', {
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
    NioApp.Toast('<h5>Location Group Name Deleted successfully !!</h5>', 'success', {
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