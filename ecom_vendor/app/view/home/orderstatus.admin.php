<?php require_once 'includes/top.php'; ?> 
    
    <!-- content @s -->
    <div class="nk-content nk-content-fluid non-printable">
        <div class="container-xl wide-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <form method="post" class="form-validate is-alter" id="orderItemStatusData" enctype="multipart/form-data">
                        <input type="hidden" id="orderId" value="<?php echo $data['order_info']['id'] ?>">
                        <input type="hidden" id="totalItems" name='total_item' value="<?php echo $data['order_item_tot'] ?>">
                        <?php echo $data['order_item_status'] ?> 
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>orders/orderdetails/<?php echo $data['order_info']['id'] ?>"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="submit_button_wrap">
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>orders/orderdetails/<?php echo $data['order_info']['id'] ?>"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card card-shadow">
                                <div class="card-inner">
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
                                        <tbody>
                                            <?php echo  $data['ord_sts_items'] ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- .card-preview -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    
    <!-- print vendor invoice end -->
     <div class="modal fade" tabindex="-1" id="OrderItemStatus">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title model_title">Customer Info</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form method="post" id="orderItemStatusData" class="form-validate is-alter">
                        <input type="hidden" id="order_status" name="order_status">
                        <input type="hidden" id="item_id" name="item_id">
                        <div class="form-group">
                            <label class="form-label remarks_head" for="pay-amount"> </label>
                            <div class="form-control-wrap">
                                <textarea type="text" class="form-control" name="remarks" id="remarks"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary">Update</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <span class="sub-text">Order Status</span>
                </div>
            </div>
        </div>
    </div>
    <!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>

 <script>
    $('.printPage').click(function(){
         window.print();
    });
</script>

<script type="text/javascript">
    

    $('.status_action').click( function() {
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
            remarks: {
                required: true,
            }
        },
        messages: {
            remarks: {
                required: "Please Remarks",
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
                    window.location = core_path + "orders/orderdetails/" + order_id + "?sh=success";
                } else if (data == 2){
                    window.location = core_path + "orders/orderdetails/" + order_id + "?p=success";
                }else {
                    toastr.clear();
                    console.log(data);
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



</script>

<?php if (isset($_GET['sh'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Product shipment status updated successfully !!</h5>', 'success', {
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
    NioApp.Toast('<h5>Product payment status updated successfully  !!</h5>', 'success', {
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