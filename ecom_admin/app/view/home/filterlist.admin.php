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
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addFilterModal" ><em class="icon ni ni-plus"></em> Add Filter</button>
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
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Filter Name</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Filter Group</span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></th>
                                            <!-- <th class="nk-tb-col tb-col-lg"><span class="sub-text">Action</span></th> -->
                                            
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

    <div class="modal fade zoom" tabindex="-1" id="addFilterModal">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Add Filter</h5>
                </div>
                <div class="modal-body">
                    <form id="addFilter" method="post" enctype="multipart/form-data">
                        <?php echo $data['csrf_add_filter_group'] ?>
                        <div class="form-group">
                            <label class="form-label">Filter
                                <en>*</en>
                            </label>
                            <input type="text" name="filter_value" id="filter_value"  class="form-control" placeholder="Enter Filter" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Select Filter Group <em>*</em></label>
                            <div class="form-control-wrap">
                                <select class="form-select" id="filter_group_id" name="filter_group_id" data-search="on" required>
                                   <?php echo $data['filter_groups']; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <p class="float-right model_pt">
                                <button type="button" class="btn btn-light close_modal" data-modal_id="addFilterModal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade zoom" tabindex="-1" id="editFilterModal">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Edit Filter</h5>
                </div>
                <div class="modal-body">
                    <form id="editFilter" method="post" enctype="multipart/form-data">
                        <?php echo $data['csrf_add_filter_group'] ?>
                        <div class="edit_filter_body">
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



<div class="form_panel_overlay" ></div>

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    // Open Edit Filter Pop Up

    $(".openEditFilter").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        var formclass = $(this).data("formclass");
        var form = $(this).data("form");
        $.ajax({
            type: "POST",
            url: core_path + "filter/api/info",
            dataType: "html",
            data: { result: value },
           
            success: function(data) {
                $(".edit_filter_body").html(data);
                $('.filter_group_select').select2();
                $("#editFilterModal").modal("show");
            }
        });
        return false;
    });

            
    // Add Filter

    $("#addFilter").validate({
        rules: {
            filter_value: {
                required: true
            }
        },
        messages: {
            filter_value: {
                required: "Please Enter Filter Name",
            }
        },
        submitHandler: function(form) {
            var formname = document.getElementById("addFilter");
            var formData = new FormData(formname);
            $.ajax({
                url: core_path + "filter/api/add",
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
                        window.location = core_path + "filter?a=success";
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

    // Active & Inactive Status For Filter

    $(".changeFilterStatus").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        // alert(value);
        $.ajax({
            type: "POST",
            url: core_path + "filter/api/status",
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

   


    // Edit Filter

    $("#editFilter").validate({
        rules: {
            filter_value: {
                required: true
            },
            short_description: {
                required: true
            }
        },
        messages: {
            filter_value: {
                required: "Please Enter Profile Name",
            },
            short_description: {
                required: "Please Enter Short Description",
            }
        },
        submitHandler: function(form) {
            var formname = document.getElementById("editFilter");
            var formData = new FormData(formname);
            $.ajax({
                url: core_path + "filter/api/update",
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
                        window.location = core_path + "filter?e=success";
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

    // Delete Filter 

    $(".deleteFilter").click(function(e) {
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
                    url: core_path + "filter/api/delete",
                    dataType: "html",
                    data: { result: value },
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            //alert(data);
                            window.location = core_path + "filter?d=success";
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
    NioApp.Toast('<h5>Filter added successfully !!</h5>', 'success', {
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
    NioApp.Toast('<h5>Filter Updated successfully !!</h5>', 'success', {
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
    NioApp.Toast('<h5>Filter Deleted successfully !!</h5>', 'success', {
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