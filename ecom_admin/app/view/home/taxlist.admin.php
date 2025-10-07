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
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTaxModal"><em class="icon ni ni-plus"></em> Add Tax Class</button>
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
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Tax Class</span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">CGST (%)</span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">SGST (%)</span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">IGST (%)</span></th>
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
    <div class="modal fade zoom" tabindex="-1" id="addTaxModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Add Tax Class</h5>
                </div>
                <div class="modal-body">
                    <form id="addTax" method="POST">
                        <?php echo $data['csrf_add_tax'] ?>
                        <div class="form-group">
                            <label class="form-label">Tax Class Name
                                <en>*</en>
                            </label>
                            <input type="text" name="tax_class"  class="form-control" placeholder="Enter Tax Classes Name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">CGST (in %) <en>*</en> </label>
                            <input type="text" name="cgst" class="form-control" placeholder="Enter CGST (in %)" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label"> SGST (in %)
                                <en>*</en>
                            </label>
                            <input type="text" name="sgst" class="form-control" placeholder="Enter SGST (in %)" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">IGST (in %)
                                <en>*</en>
                            </label>
                            <input type="text" name="igst" class="form-control" placeholder="Enter IGST (in %)" required>
                        </div>
                        <div class="form-group">
                            <p class="float-right model_pt">
                                <button type="button" class="btn btn-light close_modal" data-modal_id="addTax">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

     <div class="modal fade zoom" tabindex="-1" id="editTaxModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Edit Tax Class</h5>
                </div>
                <div class="modal-body">
                    <form id="editTax" method="POST">
                    <?php echo $data['csrf_edit_tax'] ?>
                    <div class="edit_tax_modal_body"></div>
                </div>
            </div>
        </div>
    </div>



<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
    
    // Add Tax

    $("#addTax").validate({
        rules: {
            tax_class: {
                required: true
            },
            cgst: {
                required: true,
                number: true
            },
            sgst: {
                required: true,
                number: true
            },
            igst: {
                required: true,
                number: true
            },
        },
        messages: {
            tax_class: {
                required: "Please Enter Tax Class Title",
            },
            cgst: {
                required: "Please Enter CGST Tax %",
            },
            sgst: {
                required: "Please Enter SGST Tax %",
            },
            igst: {
                required: "Please Enter IGST Tax %",
            },
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "tax/api/add",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "tax?a=success";
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

    // Active & Inactive Status For Category

    $(".changeTaxStatus").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        // alert(value);
        $.ajax({
            type: "POST",
            url: core_path + "tax/api/status",
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

    // Open Edit Tax Class Pop Up

    $(".openEditTax").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "tax/api/info",
            dataType: "html",
            data: { result: value },
            success: function(data) {
               $(".edit_tax_modal_body").html(data);
               $("#editTaxModal").modal("show");
            }
        });
        return false;
    });


    // Edit Tax

    $("#editTax").validate({
        rules: {
            tax_class: {
                required: true
            },
            cgst: {
                required: true,
                number: true
            },
            sgst: {
                required: true,
                number: true
            },
            igst: {
                required: true,
                number: true
            },
        },
        messages: {
            tax_class: {
                required: "Please Enter Tax Class Title",
            },
            cgst: {
                required: "Please Enter CGST Tax %",
            },
            sgst: {
                required: "Please Enter SGST Tax %",
            },
            igst: {
                required: "Please Enter IGST Tax %",
            },
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "tax/api/update",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "tax?e=success";
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

    // Delete Tax item

    $(".deleteTax").click(function(e) {
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
                    url: core_path + "tax/api/delete",
                    dataType: "html",
                    data: { result: value },
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            //alert(data);
                            window.location = core_path + "tax?d=success";
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
    NioApp.Toast('<h5>Tax added successfully !!</h5>', 'success', {
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
    NioApp.Toast('<h5>Tax Updated successfully !!</h5>', 'success', {
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
    NioApp.Toast('<h5>Tax Deleted successfully !!</h5>', 'success', {
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