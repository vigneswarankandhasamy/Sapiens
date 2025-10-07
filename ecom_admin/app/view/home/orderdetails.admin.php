<?php require_once 'includes/top.php'; ?> 

    <!-- content @s -->
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-xl">
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
                                                <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>orders">Orders</a></li>
                                                <li class="breadcrumb-item active"><?php echo $data['page_title'] ?></li>
                                            </ul>
                                        </nav>
                                    </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt"><a href="<?php echo COREPATH ?>orders/orderstatus/<?php echo $data['order_info']['id'] ?>" data-target="#modalForm" data-toggle="modal"   class="btn btn-warning"><em class="icon ni ni-update"></em><span>Order Status</span></a>
                                            </li>
                                            <li class="nk-block-tools-opt"><a href="<?php echo COREPATH ?>orders/previewcustomerinvoice/<?php echo $data['order_info']['id'] ?>" class="btn btn-success" target="_blank"><em class="icon ni ni-file"></em><span>Order Invoice</span></a>
                                            </li>
                                            <li class="nk-block-tools-opt"><a href="<?php echo COREPATH ?>orders/downloadInvoice/<?php echo $data['order_info']['id'] ?>"  class="btn btn-primary "><em class="icon ni ni-printer"></em><span> Invoice Download</span></a> <!-- printPage -->
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="row gy-5">
                            <div class="col-lg-4">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title title">Customer Information</h5>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="card card-bordered">
                                    <ul class="data-list is-compact">
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Name</div>
                                                <div class="data-value"><?php echo $data['order_user_info']['name']; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Mobile Number</div>
                                                <div class="data-value"><?php echo $data['order_user_info']['mobile']; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Email</div>
                                                <div class="data-value"><?php echo $data['order_user_info']['email']; ?></div>
                                            </div>
                                        </li>
                                        <?php if($data['address_info']['gst_name']!="") { ?>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">GST Name</div>
                                                <div class="data-value"><?php echo $data['address_info']['gst_name']; ?></div>
                                            </div>
                                        </li>
                                        <?php } ?>
                                        <?php if($data['address_info']['gstin_number']!="") { ?>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">GSTIN Number</div>
                                                <div class="data-value"><?php echo $data['address_info']['gstin_number']; ?></div>
                                            </div>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div><!-- .card -->
                            </div>
                            <div class="col-lg-4">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title title">Order Information</h5>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="card card-bordered">
                                    <ul class="data-list is-compact">
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Order Id</div>
                                                <div class="data-value"><?php echo $data['order_info']['order_uid']; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Order Date</div>
                                                <div class="data-value"><?php echo date('d/m/Y',strtotime($data['order_info']['created_at'])) ; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Payment Method</div>
                                                <div class="data-value"><?php echo ($data['order_info']['payment_type']=='cod')? "Cash On Delivery" : "Online Payment"; ?></div>
                                            </div>
                                        </li>
                                       <!--  <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Status</div>
                                                <div class="data-value">
                                                    <?php if($data['inprocess']==true ) { ?>
                                                        Inprocess
                                                    <?php } elseif ($data['shipped']==true ) { ?>
                                                        Shipment Out for delivery
                                                    <?php } elseif ( $data['delivered']==true) { ?>
                                                        Delivered
                                                    <?php } elseif ( $data['returned']==true) { ?>
                                                        Returned
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </li> -->
                                       <!--  <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Delivery Status</div>
                                                <div class="data-value"><?php echo ($data['delivered'])? "Delivered" : 'Inprocess'  ?></div>
                                            </div>
                                        </li> -->
                                    </ul>
                                </div><!-- .card -->
                            </div>
                            <div class="col-lg-4">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title title">Address Details</h5>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="card card-bordered">
                                    <ul class="data-list is-compact">
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Address</div>
                                                <div class="data-value"><?php echo $data['address_info']['address']; ?></div>
                                            </div>
                                        </li>
                                       <!--  <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Landmark</div>
                                                <div class="data-value"><?php echo $data['address_info']['landmark']; ?></div>
                                            </div>
                                        </li> -->
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">City</div>
                                                <div class="data-value"><?php echo $data['address_info']['city']; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">State</div>
                                                <div class="data-value"><?php echo $data['address_info']['state_name']; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Pincode</div>
                                                <div class="data-value"><?php echo $data['address_info']['pincode']; ?></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div><!-- .card -->
                            </div>
                            </div><!-- .col -->
                        </div><!-- .row -->
                    </div>

                    <div class="nk-block">
                        <div class="row gy-5">
                            <div class="col-12">
                                 <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title title">Order Details</h5>
                                    </div>
                                </div>
                                <div class="card card-bordered">
                                    <div class="card-inner">
                                        <table class="table table-bordered is-compact">
                                            <thead class="details">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product Name</th>
                                                    <th>Status</th>
                                                    <th>Price</th>
                                                    <th>Qty</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="details">
                                            <?php echo $data["order_items"]; ?>
                                                <tr>
                                                    <td class="text-right" colspan="5">Sub Total:</td>
                                                <td class="text-right">₹ <?php echo  number_format($data['order_total'],2)?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="5">Discount  <?php if($data['order_info']['coupon_code']!="") {?>(Coupon Code : <?php echo $data['order_info']['coupon_code']; ?> Applied) <?php }?> :</td>
                                                    <td class="text-right">₹ <?php echo number_format($data['coupon_total'],2);?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="5">Shipment Fee: (shipping charge : ₹ <?php echo number_format($data['order_info']['shipping_value'],2) ?> + SGST : <?php echo $data['order_info']['shipping_tax']/2 ?>% (₹ <?php echo number_format($data['order_info']['shipping_tax_value']/2,2) ?>)+ CGST: <?php echo $data['order_info']['shipping_tax']/2 ?>% (₹ <?php echo number_format($data['order_info']['shipping_tax_value']/2,2) ?>))</td>
                                                    
                                                    <td class="text-right">₹ <?php echo  $data['inr_format']['shipping_cost'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="5">Shipping Type:</td>
                                                    <td class="text-right">Standard</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="5">Total:</td>
                                                    <td class="text-right">₹  <?php echo number_format($data['order_total'] + $data['order_info']['shipping_cost'] - $data['coupon_total'],2)  ?>
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    <!-- content @e -->


     <!-- print vendor invoice end -->
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
                                        <th scope="col" width="20%">Product</th>
                                        <th scope="col" width="20%">Order Details</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" width="20%">Remarks</th>
                                        <th scope="col" width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo  $data['orderstatus_pop_up'] ?>
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

<script>
    $('.printPage').click(function(){
         window.print();
    });
</script>

<script type="text/javascript">
    
    $('.orderItemStatusChange').click( function() {
        var item_id         = $(this).data('item_id');
        var value           = $(this).data('status');
        var remarks_head    = $(this).data('remarks_hd');
        var order_id        = $("#orderId").val();
        var md_name         = $(this).data('md_name');

        // Names and headdings 
        $(".model_title").html(md_name);
        $(".remarks_head").html(remarks_head);

        // Assign Values 
        $("#item_id").val(item_id);
        $("#order_status").val(value);
    });

    


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

                // console.log(data);
                // return false;

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

<!-- star rating script start -->
<script type="text/javascript">
    $(".star-rating").starRating({
        totalStars: 5,
        starShape: 'rounded',
        starSize: 20,
        emptyColor: '#ddd',
        hoverColor: '#ffc107',
        activeColor: '#ffc107',
        initialRating: 5,
        useGradient: false,
        useFullStars: true,
        disableAfterRate: false,
        onHover: function(currentIndex, currentRating, $el) {
            //$('.live-rating').text(currentIndex);
        },
        onLeave: function(currentIndex, currentRating, $el) {
            //$('.live-rating').text(currentRating);
        },
        callback: function(currentRating, $el) {
            //alert('rated '+currentRating);

            $("#rating_input").val(currentRating);
        }
    });

    $(".yellow-rating").starRating({
        totalStars: 5,
        starShape: 'rounded',
        starSize: 20,
        emptyColor: '#ccc',
        hoverColor: '#ffc107',
        activeColor: '#ffc107',
        useGradient: false,
        readOnly: true
    });

    var setRating = function() {
        Array.from($('.rating_data')).forEach((ele, index) => {
            let star_elem = $(".my-rating-7")[index];

            $(star_elem).starRating({
                readOnly: true,
                totalStars: 5,
                starShape: 'rounded',
                starSize: 20,
                emptyColor: '#ddd',
                hoverColor: '#ffc107',
                activeColor: '#ffc107',
                initialRating: ele.value,
                useGradient: false,
                disableAfterRate: false,
                callback: function(currentRating, $el) {
                    //alert('rated '+currentRating);
                    $("#rating_data").val(currentRating);
                }
            });
        })
    }

    setRating();

    var setRatingCount = function() {
        var tot = $('#total_cot').val();
        $(".my-rating-9").starRating({
            readOnly: true,
            initialRating: $('#total_cot').val(),
            starShape: 'rounded',
            starSize: 20,
            emptyColor: '#ddd',
            hoverColor: '#ffc107',
            activeColor: '#ffc107',
            disableAfterRate: false
        });
        $('.live-rating').text(tot);
    }

    setRatingCount();
</script>
<!-- star rating script end -->