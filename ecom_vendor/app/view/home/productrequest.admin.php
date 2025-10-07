
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
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>inventorysettings">Inventory</a></li>
                                            <li class="breadcrumb-item active"><?php echo $data['page_title'] ?></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <button  class="btn btn-primary" data-toggle="modal" data-backdrop="static" data-target="#addProductRequestModal" ><em class="icon ni ni-plus"></em> Add Request </button>
                            </div>
                           
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    


                    <div class="nk-block">
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <table id="table_detail" class="datatable-init nk-tb-list nk-tb-ulist is-compact" data-auto-responsive="false">
                                    <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span></th>
                                            <!-- <th class="nk-tb-col"><span class="sub-text">Image</span></th> -->
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Product name</span></th>
                                            <th class="nk-tb-col tb-col-mb request_desc_width" ><span class="sub-text">Description </span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Request Date</span></th>
                                            <!-- <th class="nk-tb-col tb-col-md"><span class="sub-text">Selling Price</span></th> -->
                                            <!-- <th class="nk-tb-col tb-col-mb"><span class="sub-text">Inventory </span></th> -->
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Request Status </span></th>
                                            <!-- <th class="nk-tb-col tb-col-mb"><span class="sub-text">Action </span></th> -->
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
    <!--List Item Details Model -->

    <div class="modal fade zoom" tabindex="-1" id="ListDataModel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Product Request</h5>
                </div>
                <div class="modal-body" id="listContent">
                    
                </div>
                
            </div>
        </div>
    </div>

    <!-- Add Request Model -->

    <div class="modal fade zoom" tabindex="-1" id="addProductRequestModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Product Request</h5>
                </div>
                <div class="modal-body" id="addContent">
                    <form method="post" class="form-validate is-alter" id="addProductRequest" enctype="multipart/form-data">
                    <?php echo $data['csrf_add_prd_request'] ?>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-label"> Product Name <em>*</em>
                            </label>
                            <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter Product Name" >
                        </div>
                        <div class="form-group col-md-12">
                            <label class="form-label" >Description 
                            </label>
                            <textarea class="summernote-editor" name="description" id="description"></textarea> 
                        </div>
                        <div class="form-group col-md-12">
                              <p class="float-right model_pt">
                            <button type="button" class="btn btn-light close_add_request" data-form="viewReview" data-formclass="view_contact_class"> Cancel</button>
                            <button type="submit" class="btn btn-primary " data-form="viewReview" data-formclass="view_contact_class"> Submit</button>
                        </p>
                        </div>
                    </div>
                   
                    </form>
                </div>
                
            </div>
        </div>
    </div>

    <!-- End -->

    <!-- Edit Request Model -->

    <div class="modal fade zoom" tabindex="-1" id="editProductRequestModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Product Request</h5>
                </div>
                <div class="modal-body" id="addContent">
                    <form method="post" class="form-validate is-alter" id="editProductRequest" enctype="multipart/form-data">
                    <input type="hidden" name="token" id="token">
                    <?php echo $data['csrf_edit_prd_request'] ?>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-label"> Product Name <em>*</em>
                            </label>
                            <input type="text" name="product_name" id="edit_product_name" class="form-control" placeholder="Enter Product Name" >
                        </div>
                        <div class="form-group col-md-12">
                            <label class="form-label" >Description 
                            </label>
                            <textarea class="summernote-editor" name="description" id="edit_description"></textarea> 
                        </div>
                        <div class="form-group col-md-12">
                              <p class="float-right model_pt">
                            <button type="button" class="btn btn-light close_edit_request"> Cancel</button>
                            <button type="submit" class="btn btn-primary"> Submit</button>
                        </p>
                        </div>
                    </div>
                   
                    </form>
                </div>
                
            </div>
        </div>
    </div>

    <!-- End -->

     

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

     // List Item Details Model 

    $(".open_data_model").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "products/api/getproductRequestDetails",
            dataType: "html",
            data: {
                result: value
            },
            beforeSend: function() {
            },
            success: function(data) {
                $("#listContent").html(data);
                $('#ListDataModel').modal('show');
            }
        });

    });
     // Close modal

    $("body").on("click", ".close_add_request", function(){
        $('#description').summernote('reset');
        $('#addProductRequest').trigger("reset");
        $('#addProductRequestModal').modal('hide');
    });
    $("body").on("click", ".close_edit_request", function(){
        $('#editProductRequestModal').modal('hide');
    });

    // Add Product request

    $("#addProductRequest").validate({
        rules: {
            product_name: {
                required: true
            },
            description: {
                required: true
            },
        },
        messages: {
            product_name: {
                required: "Please Enter Product name",
            },
            description: {
                required: "Please Enter Product Description",
            },
           
        },
        submitHandler: function(form) {
            var formname = document.getElementById("addProductRequest");
            var formData = new FormData(formname);
            $.ajax({
                url: core_path + "products/api/addProductRequest",
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
                        window.location = core_path + "products/request?a=success";
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

    $("body").on("click",".edit_product_request", function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "products/api/getproductRequestData",
            dataType: "html",
            data: {
                result: value
            },
            beforeSend: function() {
            },
            success: function(data) {
                var data = JSON.parse(data);
                $('#ListDataModel').modal('hide');
                $('#token').val(data['token']);
                $('#edit_product_name').val(data['product_name']);
                $("#edit_description").summernote("code", data['description']);
                $('#editProductRequestModal').modal('show');
            }
        });

    });

    // Edit Product Request

    $("#editProductRequest").validate({
        rules: {
            product_name: {
                required: true
            },
            description: {
                required: true
            },
        },
        messages: {
            product_name: {
                required: "Please Enter Product name",
            },
            description: {
                required: "Please Enter Product Description",
            },
           
        },
        submitHandler: function(form) {
            var formname = document.getElementById("editProductRequest");
            var formData = new FormData(formname);
            $.ajax({
                url: core_path + "products/api/editProductRequest",
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
                        window.location = core_path + "products/request?a=success";
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
<?php if (isset($_GET['a'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Product Request added successfully !!</h5>', 'success', {
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

