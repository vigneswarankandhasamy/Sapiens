<?php require_once 'includes/top.php'; ?> 
    
    <!-- content @s -->
    <div class="nk-content nk-content-fluid non-printable">
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
                                                <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>orders/notification">Notification</a></li>
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
                                            <?php if($data['respone_status']) { ?>
                                            <li class="nk-block-tools-opt"><button class="btn btn-primary" data-toggle="modal" data-target="#modalForm"><em class="icon ni ni-list-check"></em><span> order Response</span></button> <!-- printPage -->
                                            </li>
                                            <?php } ?>
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
                                                <div class="data-value"><?php echo $data['cus_info']['name']; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Address</div>
                                                <div class="data-value"><?php echo $data['address_info']['address']; ?></div>
                                            </div>
                                        </li>
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

                                    </ul>
                                </div><!-- .card -->
                               
                            </div>

                            <?php 
                               $total = $data['ven_order_info']['subTotal'] + $data['ven_order_info']['IGST_AMT'] - $data['order_discount_info']['couponValue']; 
                                $total_charges = $data['ven_order_info']['vendorCommissionAmt'] + $data['ven_order_info']['vendorPaymentChargeAmt'] + $data['ven_order_info']['vendorShippingChargeAmt'];
                                $total_without_discount = $data['ven_order_info']['subTotal'] + $data['ven_order_info']['IGST_AMT'] - $data['order_discount_info']['couponValue'];

                            ?>
                                                
                            <div class="col-lg-4">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title title">Payment Information</h5>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="card card-bordered">
                                    <ul class="data-list is-compact">
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Total Items</div>
                                                <div class="data-value"><?php echo $data['order_item_tot']['count']; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Sub Total</div>
                                                <div class="data-value">₹ <?php echo number_format($data['ven_order_info']['totalAMT'],2); ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Total Commission</div>
                                                <div class="data-value">₹ <?php echo number_format( $data['order_item_info']['vendor_commission_amt']+$data['order_item_info']['vendor_payment_charge_amt']+$data['order_item_info']['vendor_shipping_charge_amt'],2) ; ?></div>
                                            </div>
                                        </li>

                                         

                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Net Payable</div>
                                                <div class="data-value">₹ <?php echo number_format($total,2); ?></div>
                                            </div>
                                        </li>
                                         <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Vendor Payable</div>
                                                <div class="data-value">₹ <?php echo number_format($total - $total_charges,2) ?></div>
                                            </div>
                                        </li>

                                    </ul>
                                </div><!-- .card -->
                               
                            </div><!-- .col -->
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
                                                <div class="data-label">Sapiens Invoice Number</div>
                                                <div class="data-value"><?php echo $data['order_info']['order_uid']; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Payment Method</div>
                                                <div class="data-value"><?php echo ($data['order_info']['payment_type']=='cod')? "Cash On Delivery" : "Online Payment"; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Order Status</div>
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
                                        </li>
                                        <?php if($data['v_order_info']['vendor_response']==1) { ?>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Order Response</div>
                                                <div class="data-value"><?php echo ($data['v_order_info']['vendor_accept_status']==1)? "Accepted" : "Rejected"; ?></div>
                                            </div>
                                        </li>
                                        <?php } ?>

                                        <?php if ($data['v_order_info']['vendor_accept_status']==0 && $data['v_order_info']['vendor_response']==1 ) { ?>
                                             <li class="data-item">
                                                <div class="data-col">
                                                    <div class="data-label">Reason</div>
                                                    <div class="data-value"><?php echo $data['v_order_info']['response_notes'] ?></div>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div><!-- .card -->
                            </div><!-- .col -->
                        </div><!-- .row -->
                    </div><!-- .nk-block -->
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
                                                    <td class="text-right">₹ <?php echo number_format($data['ven_order_info']['subTotal']+$data['ven_order_info']['totalTax'] ,2)  ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="5">Discount: <?php if($data['order_info']['coupon_code']!="") {?>(Coupon Code : <?php echo $data['order_info']['coupon_code']; ?> Applied) <?php }?></td>
                                                    <td class="text-right">₹ <?php echo (($data['order_info']['coupon_value']!='') ? round($data['order_discount_info']['couponValue']) : 0.00) ; ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="5">Shipment Fee:</td>
                                                    <td class="text-right">₹ <?php echo $data['order_info']['shipping_cost'] ?></td>
                                                </tr>
                                                 <!-- <tr>
                                                    <td class="text-right" colspan="5">Total Tax:</td>
                                                    <td class="text-right">₹ <?php echo $data['ven_order_info_inr']['IGST_AMT'] ?></td>
                                                </tr> -->

                                                <?php 
                                                   $total         = $data['ven_order_info']['subTotal'] + $data['ven_order_info']['IGST_AMT'] - $data['order_discount_info']['couponValue']; 
                                                    $total_charges = $data['ven_order_info']['vendorCommissionAmt'] + $data['ven_order_info']['vendorPaymentChargeAmt'] + $data['ven_order_info']['vendorShippingChargeAmt'];
                                                    $total_without_discount = $data['ven_order_info']['subTotal'] + $data['ven_order_info']['IGST_AMT'] - $data['order_discount_info']['couponValue'];

                                                ?>
                                                <tr>
                                                    <td class="text-right" colspan="5">Total:</td>
                                                    <td class="text-right">₹ <?php echo number_format($total_without_discount,2)  ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left" colspan="5">Commission & Charges :</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="5">Sapiens Commission:</td>
                                                    <td class="text-right">₹ <?php echo $data['ven_order_info_inr']['vendorCommissionAmt'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="5">Payment Gateway Charge:</td>
                                                    <td class="text-right">₹ <?php echo $data['ven_order_info_inr']['vendorPaymentChargeAmt'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="5">Shipping Charge:</td>
                                                    <td class="text-right">₹ <?php echo $data['ven_order_info_inr']['vendorShippingChargeAmt'] ?></td>
                                                </tr>
                                                
                                                 <tr>
                                                    <td class="text-right" colspan="5">Sub Total:</td>
                                                    <td class="text-right">₹ <?php echo number_format($total,2);    ?></td>
                                                </tr>

                                                <tr>
                                                    <td class="text-right" colspan="5">Total Charge:</td>
                                                    <td class="text-right">- ₹ <?php echo number_format($total_charges,2) ?></td>
                                                </tr>
                                                <tr>    
                                                    <td class="text-right" colspan="5">Vendor Payable:</td>
                                                    <td class="text-right"> ₹ <?php echo number_format($total - $total_charges,2) ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- .col -->
        
                        </div><!-- .row -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    

    <div class="modal fade" tabindex="-1" id="modalForm">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header modal-header-sm">
                    <h5 class="modal-title">Order Response</h5>
                </div>
                <div class="modal-body ">
                    <div class="nk-block">
                        <div class="profile-ud-list">
                            <div class="profile-ud-item">
                                <div class="profile-ud wider enq_name_field">
                                    <span class="profile-ud-label">Invoice Number</span>
                                    <span class="profile-ud-value enq_name_align"><?php echo $data['order_info']['order_uid']; ?></span>
                                </div>
                            </div>
                            <div class="profile-ud-item">
                                 <div class="profile-ud wider enq_name_field">
                                    <span class="profile-ud-label">Date</span>
                                    <span class="profile-ud-value enq_name_align"><?php echo date("d-m-y  " ,strtotime($data['order_info']['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="profile-ud-list">
                            
                        </div>
                        <form id="orderResponseStatus" method="POST">
                            <input type="hidden" name="vendor_order_id" value="<?php echo $data['v_order_info']['id'] ?>">
                                <?php echo $data['order_response_prd']; ?>
                            <div  class="nk-block nk_block_pt response_reject_notes display_none">
                                <div class="nk-block-head nk-block-head-line">
                                    <h6 class="title overline-title text-base">Status </h6>
                                </div>
                                <div class="reject_notes ">
                                    <select class="form-select" name="order_response_status_id" id="order_response_status_id" data-placeholder="Select Location Groups" data-search="on">
                                            <?php echo $data['ord_response_sts']; ?>
                                        </select>
                                </div>
                            </div>
                            <div  class="nk-block nk_block_pt">
                            <p class="float-right model_pt">
                                    <button type="submit" class="btn btn-primary " data-form="viewReview" data-formclass="view_contact_class"> Submit</button>
                                    <button type="button" class="btn btn-light close_response_model" data-form="viewReview" data-formclass="view_contact_class"> Cancel</button>
                                </p>
                            </div>
                        </form>

                    </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>


<?php require_once 'includes/bottom.php'; ?>

<script>
    $('.order_response_status').click(function(){
        var checked  = $(this).is(":checked");

        var item_id  = $(this).data("item_id");
        var response_title = ".response_title_"+item_id;

        if(checked) {
            $("#item_response_"+item_id).val(1);
            $(".response_title_"+item_id).html("Accept");
        } else {
            $("#item_response_"+item_id).val(0);
            $(".response_title_"+item_id).html("Reject");
        }

        var checkBoxes = document.getElementsByClassName( 'order_response_status' );
        var unChecked = false;
        for (var i = 0; i < checkBoxes.length; i++) {
            if (! checkBoxes[i].checked ) {
                unChecked = true;
            };
        };
        if ( unChecked ) {
            $(".response_reject_notes").removeClass("display_none");
        }  else {
            $(".response_reject_notes").addClass("display_none");
        }
    });

     // Close modal

    $("body").on("click", ".close_response_model", function(){
        $('#modalForm').modal('hide');
    });
</script>

<script type="text/javascript">

     $("#orderResponseStatus").validate({ 
        rules: {
            order_response_status_id: {
                required: function(element){
                    if( $('.order_response_status').is(":checked")){
                        return 0;
                    } else {
                        return true;
                    }
                },
            },
           
        },
        messages: {
            order_response_status_id: {
                required: "Please select response status",
            },
           
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            var order_id        = "<?php echo $data['order_info']['id'] ?>";
            var vendor_order_id = "<?php echo $data['v_order_info']['id'] ?>";

            $.ajax({
                type: "POST",
                url: core_path + "orders/api/orderNotificationResponse",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                $(".page_loading").hide();
                
                if(data==1) {
                    window.location = core_path+"orders/rejectedorderdetail/"+order_id+"?or=success";
                } else if(data==2) {
                    window.location = core_path+"orders/neworderdetail/"+vendor_order_id+"?or=success";
                } else {
                    window.location = core_path+"orders/neworderdetail/"+vendor_order_id+"?ore=success";
                }
            }
            });
            return false;
        }
    });

</script>

<?php if (isset($_GET['ore'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Sorry!! order response status already changed !!</h5>', 'error', {
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


<?php if (isset($_GET['or'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Order response updated successfully  !!</h5>', 'success', {
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