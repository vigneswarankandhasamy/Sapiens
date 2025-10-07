<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-xl">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title"><?php echo $data['page_title'] ?></h3>
                            <div class="nk-block-des">
                                <nav>
                                    <ul class="breadcrumb breadcrumb-arrow">
                                        <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a>
                                        </li>
                                        <li class="breadcrumb-item active">
                                            <?php echo $data[ 'page_title'] ?>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <!-- <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em class="icon ni ni-arrow-down"></em><span>Export</span></a>
                                        </li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- .nk-block-head-content -->
                    </div>
                    <!-- .nk-block-between -->
                </div>
                <!-- .nk-block-head -->
                <div class="nk-block nk-block-lg">
                    <div class="card card-preview">
                        <div class="card-inner">
                            <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                <thead>
                                    <tr class="nk-tb-item nk-tb-head">
                                         <th class="nk-tb-col">S.No</span>
                                        </th>
                                        <th class="nk-tb-col">Invoice No</span>
                                        </th>
                                         <th class="nk-tb-col tb-col-md">Order Date</span>
                                        </th>
                                        <th class="nk-tb-col"><span class="sub-text">Customer Name</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">Purchased</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Commission</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Total</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Stauts</span>
                                        </th>
                                        <th class="nk-tb-col nk-tb-col-tools text-right">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $data['list']; ?>
                                    
                                    <!-- .nk-tb-item  -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- .card-preview -->
                </div>
            </div>
        </div>
    </div>
</div>

 <div class="modal fade zoom" tabindex="-1" id="modalForm">
        <div class="modal-dialog modal-xl modal-dialog-top modal-header-sm" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title model_title">Order Details</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body modal-body-xl">
                    <form method="post" class="form-validate is-alter" id="orderItemStatusData" enctype="multipart/form-data">
                        <input type="hidden" id="orderId" value="<?php echo $data['order_info']['id'] ?>">
                        <input type="hidden" id="totalItems" name='total_item' value="<?php echo $data['order_item_tot'] ?>">
                        <?php echo $data['order_item_status'] ?>
                            <table class="table table_middle bg-white table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Order Details</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Remarks</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="order_pop_up_items">
                                    
                                </tbody>
                            </table>
                            <button class="btn btn-success float-right" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Update</button>
                    </form>
                </div>
                <div class="modal-footer modal-footer-sm bg-light">
                    <span class="sub-text">Order Status</span>
                </div>
            </div>
        </div>
    </div>
<!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    $(".orderDetailsTD").click(function() {
        var value = $(this).data("dycryprt_id");
        window.location = core_path + "orders/orderdetails/" + value;
    });

     $(".order_item_status_change").click(function() {
        var id = $(this).data('order_id');
        $.ajax({
            type: "POST",
            url: core_path + "orders/api/getOrderstsPopUpItems",
            dataType: "html",
            data: { result: id },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                $(".order_pop_up_items").html(data);
                $("#modalForm").modal('show');
            }
        });
    });

      $('body').on('click','.status_action', function() {
        var name                = $(this).data('action');
        var status              = $(this).data('status');
        var status_change_to    = $(this).data('change');
        var class_name          = ".status_btn_ne_" + status;
        var order_status        = "#order_status_"  + status;

        // Hide and show

        var remark_info        = ".remarks_info_" + status;
        var remark_text_input  = ".text_remarks_" + status;

        // Names and headdings 
        $(class_name).html(name);
        $(order_status).val(status_change_to);
        $(remark_text_input).removeClass('display_none');
        $(remark_info).addClass('display_none');
    });

      $("#orderItemStatusData").validate({
        rules: {
            shipping_remarks: {
                required: true,
            }
        },
        messages: {
            shipping_remarks: {
                required: "Please Shipping Remarks",
            }
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            var order_id = $("#orderId").val();

            $.ajax({
                type: "POST",
                url: core_path + "orders/api/orderItemStatusChange",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                $(".page_loading").hide();
                if (data == 1) {
                    window.location = core_path + "orders/vendoractiveorders?sh=success";
                } else if (data == 2){
                    window.location = core_path + "orders/vendoractiveorders?p=success";
                }else {
                    toastr.clear();
                    NioApp.Toast('<h5>Error Occured</h5>', 'error', {
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

    
    $('.orderStatusChange').click( function() {
        var id = $(this).data('order');
        var value = $(this).data('status');
        $.ajax({
            type: "POST",
            url: core_path + "orders/api/changeOrderStatus",
            dataType: "html",
            data: { order_id: id,status: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                if (data == 1) {
                    window.location = core_path + "orders?sh=success";
                } else if (data == 2){
                    window.location = core_path + "orders?p=success";
                }else {
                    toastr.clear();
                    NioApp.Toast('<h5>Error Occured</h5>', 'error', {
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
    });

</script>

<?php if (isset($_GET['sh'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Order shipment status updated successfully !!</h5>', 'success', {
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

<?php if (isset($_GET['p'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Order payment status updated successfully  !!</h5>', 'success', {
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

