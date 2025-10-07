<?php require_once 'includes/top.php'; ?> 

        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title"><?php echo $data['page_title'] ?></h3>
                                    <div class="nk-block-des">
                                        <nav>
                                            <ul class="breadcrumb breadcrumb-arrow">
                                                <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a></li>
                                                    <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>products/inventory">Inventory</a></li>
                                                    <li class="breadcrumb-item active">Inventory Stock</li>
                                            </ul>
                                        </nav>
                                    </div>
                                    <div class="nk-block-des text-soft">
                                        <p>You have total <?php echo $data['inventory_list']['total_stock'] ?> Instock.</p>
                                    </div>
                                </div>
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                        <div class="toggle-expand-content" data-content="pageMenu">
                                            <ul class="nk-block-tools g-3">
                                                <li><a href="<?php echo COREPATH ?>products/inventory" class="btn btn-white btn-outline-light"><em class="icon ni ni-arrow-left"></em><span>Back</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="row g-gs">
                               <?php echo $data['inventory_list']['layout'] ?>
                            </div>  
                            <?php if ($data['count'] > $data['item_per_page']) {?>                                  
                                <nav class="mt-3">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item "><a class="page-link" href="<?php echo $data['previous'] ?>"  aria-disabled="true">Prev</a></li>
                                            <?php echo $data['page']['layout'] ?>
                                        <li class="page-item"><a class="page-link" href="<?php echo $data['next'] ?>">Next</a></li>
                                    </ul>
                                </nav>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Content Code -->
        <div class="modal fade zoom" tabindex="-1" id="updateProductInventory">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Details</h5>
                    </div>
                    <div class="modal-body">
                        <form id="updateProductInventoryDetails" class="form-validate is-alter" method="POST">
                            <div id="modalContent">
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    // Open inventory detail modal
    
    $(".edit_inventory_details").click(function() {
        var item_id = $(this).data("option");
        var variant = $(this).data("variant");

        $.ajax({
            type: "POST",
            url : core_path + "products/api/getProductInventoryinfo",
            dataType : "html",
            data : {
                result : {item_id : item_id, variant_id : variant  }
            },
            beforeSend: function() {

            },
            success: function(data) {
                console.log(data);
                $("#modalContent").html(data);
                $("#updateProductInventory").modal("show");
            }
        });

    });

    $("body").on("click", "#stockAvailability", function(){
        if($(this).prop('checked')) {
            $(".stock_availability_label").html("Available");
        } else {
            $(".stock_availability_label").html("Not Available");
        }
    });

    $.validator.addMethod("max_check", function (value, elem) {
        var min_qty = $("#min_qty").val() ;
        var max_qty = $("#max_qty").val() ;
        if( parseInt(max_qty) < parseInt(min_qty) ){
            return false;
        } else {
            return true;
        }
    });

    $.validator.addMethod("min_check", function (value, elem) {
        var min_qty = $("#min_qty").val() ;
        var max_qty = $("#max_qty").val() ;
        if(max_qty > 0) {
            if( parseInt(max_qty) < parseInt(min_qty) ){
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
        
    });

    // Update Product Inventory 

     $("#updateProductInventoryDetails").validate({
        rules: {
            selling_price: {
                required: true
            },
            stock: {
                required: true
            },
            min_qty: {
                min_check : true,
                required : true
            },
            max_qty: {
                max_check : true,
                required : true
            }
        },
        messages: {
            selling_price: {
                required: "Please enter product selling price",
            },
            stock: {
                required: "Please enter product stock quantity",
            },
            min_qty: {
                required: "Please enter product minimum order quantity",
                min_check : "Minimum order quantity can't be grater than Maximum order quantity",
            },
            max_qty: {
                required: "Please enter product maximum order quantity",
                max_check : "Maximum order quantity can't be less than Minimum order quantity",
            }
        },
        submitHandler: function(form) {
            var formname = document.getElementById("updateProductInventoryDetails");
            var formData = new FormData(formname);
            $.ajax({
                url: core_path + "products/api/updateProductInventoryinfo",
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
                        var url =  window.location.href;
                        window.location = url + "?ui=success";
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

<?php if (isset($_GET['ui'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Product Inventory info updated successfully !!</h5>', 'success', {
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

