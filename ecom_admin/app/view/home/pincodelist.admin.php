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
                                        <a href="<?php echo COREPATH ?>state" class="<?php echo (($data['page_menu']=="state")?'active' : '') ?>">State</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>city" class="<?php echo (($data['page_menu']=="city")?'active' : '') ?>">City</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo COREPATH ?>pincode" class="<?php echo (($data['page_menu']=="pincode")?'active' : '') ?>">Pincode</a>
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
                                                        <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>store">Settings</a></li>
                                                        <li class="breadcrumb-item active"><?php echo $data['page_title'] ?></li>
                                                    </ul>
                                                </nav>
                                            </div>

                                        </div>
                                        <div class="nk-block-head-content">
                                            <button type="button" class="btn btn-primary openFormPanel" data-formclass='add_city' data-form='addPincode'><em class="icon ni ni-plus"></em> Add Pincode</button>
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
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Pincode</span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></th>
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

    <div class="form_panel_warp add_city" >
        <form id="addPincode" method="POST">
            <?php echo $data['csrf_add_pincode'] ?>
            <div class="form_panel_head">
                <h6 class="mb-0">Add Pincode </h6><a class="nk-demo-close toggle btn btn-icon btn-trigger revarse mr-n2 active closeFormPanel" data-formclass='add_city' data-form='addPincode'  href="javascript:void();"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="form_panel_content" >
                <div class="form-group">
                    <label class="form-label">Pincode
                        <en>*</en>
                    </label>
                    <input type="text" name="pincode"  class="form-control" placeholder="Enter Pincode" required>
                </div>
                <div class="form-error"></div>
            
                <div class="form-group">
                    <label class="form-label" >City</label>
                    <div class="form-control-wrap">
                        <select class="form-select" id="addCity" name="city"  data-search="on">
                            <?php echo $data['cities']; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form_panel_footer">
                <div class="row">
                    <div class="col-md-12">
                        <p class="pull-right">
                            <button type="button" class="btn btn-light closeFormPanel" data-form='addPincode' data-formclass="add_city"> Cancel</button>
                            <button type="submit" class="btn btn-success pull-right"><em class="icon ni ni-check-thick"></em> Save</button>
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    

    <div class="form_panel_overlay" ></div>


<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
    
    // Add Tax

    $("#addPincode").validate({
        rules: {
            pincode: {
                required: true,
                digits: true,
                maxlength: 6,
                minlength: 6
            },
        },
        messages: {
            pincode: {
                required: "Please Enter Pincode ",
                digits: "Please Enter a Vlaid pincode",
                maxlength: "Please enter valid 6 digit pincode",
                minlength: "Please enter valid 6 digit pincode"
            },
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "pincode/api/addPincode",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "pincode?a=success";
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
                            "timeOut": "6000"
                        });
                    }
                }
            });
            return false;
        }
    });

    // Active & Inactive Status For Category

    $(".changePincodeStatus").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        // alert(value);
        $.ajax({
            type: "POST",
            url: core_path + "pincode/api/statusPincode",
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

    // Open Edit Pincode Pop Up

    $(".openEditPincode").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        var formclass = $(this).data("formclass");
        var form = $(this).data("form");
        $.ajax({
            type: "POST",
            url: core_path + "pincode/api/infoPincode",
            dataType: "html",
            data: { result: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                var json = $.parseJSON(data);
                $("#token").val(value);
                $("#name").val(json['pincode']);
                var state = json['state_id'];
                document.getElementById("editState").value = state;
                toggleFormPanel(formclass,form,type="retain");
            }
        });
        return false;
    });


    // Edit Tax

    $("#editPincode").validate({
        rules: {
            name: {
                required: true
            },
        },
        messages: {
            name: {
                required: "Please Enter Pincode Name",
            },
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "pincode/api/updatePincode",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "pincode?e=success";
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

    // Delete Tax item

    $(".deletePincode").click(function(e) {
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
                    url: core_path + "pincode/api/deletePincode",
                    dataType: "html",
                    data: { result: value },
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            window.location = core_path + "pincode?d=success";
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
    NioApp.Toast('<h5>Pincode added successfully !!</h5>', 'success', {
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
    NioApp.Toast('<h5>Pincode Updated successfully !!</h5>', 'success', {
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
    NioApp.Toast('<h5>Pincode Deleted successfully !!</h5>', 'success', {
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