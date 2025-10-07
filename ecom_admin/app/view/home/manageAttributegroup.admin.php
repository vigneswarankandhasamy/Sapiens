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
                                        <a href="<?php echo COREPATH ?>attributegroup" class="<?php echo (($data['attributegroup_menu']=="attributegroup")?'active' : '') ?>">Attribute Group</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>attribute" class="<?php echo (($data['attribute_menu']=="attribute")?'active' : '') ?>">Attribute</a>
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
                                                        <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>productsettings">Products</a>
                                                        </li>
                                                        <li class="breadcrumb-item active"><?php echo $data['page_title'] ?></li>
                                                    </ul>
                                                </nav>
                                            </div>

                                        </div>
                                        <div class="nk-block-head-content">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAttributeGroupModal"><em class="icon ni ni-plus"></em> Add Attribute Group</button>
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
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Attribute Group Name</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Sort Order</span></th>
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
    
    <!-- Add Attribute Group Modal -->

    <div class="modal fade zoom" tabindex="-1" id="addAttributeGroupModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Add Attribute Group</h5>
                </div>
                <div class="modal-body">
                    <form id="addAttributeGroup" method="POST">
                        <?php echo $data['csrf_add_attribute_group'] ?>
                        <div class="form-group">
                            <label class="form-label">Attribute Group Name
                                <en>*</en>
                            </label>
                            <input type="text" name="attribute_group_name"  class="form-control" placeholder="Enter Attribute Group Name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sort Order<en>*</en> </label>
                            <input type="number" name="sort_order" class="form-control" value="<?php echo $data['sort_order'] ?>" placeholder="Enter Sort Order" required>
                        </div>
                        <div class="form-group">
                            <p class="float-right model_pt">
                                <button type="button" class="btn btn-light close_modal" data-modal_id="addAttributeGroupModal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Attribute Group Modal -->

    <div class="modal fade zoom" tabindex="-1" id="editAttributeGroupModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Edit Attribute Group</h5>
                </div>
                <div class="modal-body">
                   <form id="editAttributeGroup" method="POST">
                        <?php echo $data['csrf_edit_attribute_group'] ?>
                        <div class="edit_attribute_group_modal_body">
                        
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<?php require_once 'includes/bottom.php'; ?> 

<script type="text/javascript">
    
    // Add Attribute Group

    $("#addAttributeGroup").validate({
        rules: {
            group_name: {
                required: true
            },
            sort_order: {
                required: true
            },
        },
        messages: {
            group_name: {
                required: "Please Enter Group Name",
            },
            sort_order: {
                required: "Please Enter Sort Order",
            },
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "attributegroup/api/add",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "attributegroup?a=success";
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

    $(".changeAttributeGroupStatus").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "attributegroup/api/status",
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

    $(".openEditAttributeGroup").click(function() {
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "attributegroup/api/info",
            dataType: "html",
            data: { result: value },
            success: function(data) {
               $(".edit_attribute_group_modal_body").html(data);
               $("#editAttributeGroupModal").modal("show");
            }
        });
        return false;
    });


    // Edit Location Group

    $("#editAttributeGroup").validate({
        rules: {
            group_name: {
                required: true
            },
            sort_order: {
                required: true
            },
        },
        messages: {
            group_name: {
                required: "Please Enter Group Name",
            },
            sort_order: {
                required: "Please Enter Sort Order",
            },
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "attributegroup/api/update",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "attributegroup?e=success";
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

    $(".deleteAttributeGroup").click(function(e) {
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
                    url: core_path + "attributegroup/api/delete",
                    dataType: "html",
                    data: { result: value },
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            window.location = core_path + "attributegroup?d=success";
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
    NioApp.Toast('<h5>Attribute Group added successfully !!</h5>', 'success', {
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
    NioApp.Toast('<h5>Attribute Group Updated successfully !!</h5>', 'success', {
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
    NioApp.Toast('<h5>Attribute Group Deleted successfully !!</h5>', 'success', {
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