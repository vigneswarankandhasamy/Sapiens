<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="addProductRequest" enctype="multipart/form-data">
                        <?php echo $data['csrf_add_prd_request'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>products/request"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="submit_button_wrap">
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>products/request"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Add Request</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-error"></div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">General Info</h5>
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
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

<!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    
    // Add Seo Content

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
            saveForm();
            return false;
        }
    });

    // Save Form

    function saveForm() {
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
    }

</script>

