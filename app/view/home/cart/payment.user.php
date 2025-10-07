<?php require_once 'header.php'; ?>
<section class="payment">
    <div class="container-fluid">
        <div class="pb-lg-0 pb-5">
            <div class="row g-4 mt-4 mb-5">
                <div class="col-lg-8 col-sm-12">
                    <div class="p-4 rounded-3 shadow">
                    <div class="d-flex justify-content-start align-items-center mb-3">
                        <h3 class="h6 pt-3 text-sm-start w-75 text-muted">Payment Option</h3>
                    </div>
                <?php if (isset($_SESSION[ "user_session_id"])) { ?>
                    <div class="payment_view ">
                        <!-- <div class="card p-4">
                            <div class="row gx-2 gy-2">
                                <div class="form-check">
                                    <span class="checkbox">
                                        <input type="radio" class="form-check-input paymentMethodCheckBox" id="onlinePaymentCheckBox" name="payment_method" value="online"  class="payment_method " checked>
                                        <label class="form-check-label" for="onlinePaymentCheckBox">Online</label>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card p-4 mt-2">
                            <div class="row gx-2 gy-2">
                                <div class="form-check">
                                    <span class="checkbox">
                                        <input type="radio" class="form-check-input paymentMethodCheckBox" id="cashOnDeliveryCheckBox" name="payment_method" value="cod" <?php echo $data['info']['payment_method']=="cod" ? "checked" : "" ?> class="payment_method">
                                        <label class="form-check-label" for="cashOnDeliveryCheckBox">Cash on Delivery</label>
                                    </span>
                                </div>
                            </div>
                        </div> -->
                        <div class="card p-4 mt-2">
                            <div class="row gx-2 gy-2">
                                <div class="form-check">
                                    <span class="checkbox">
                                        <input type="radio" class="form-check-input paymentMethodCheckBox" id="cashOnDeliveryCheckBox" name="payment_method" value="cod" class="payment_method" checked>
                                        <label class="form-check-label" for="cashOnDeliveryCheckBox">Cash on Delivery</label>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="addressData" data-vendor_status="<?php echo $data['vendor_status_check'] ?>" data-address_status="<?php echo $data['check_address']['status'] ?>" data-product="<?php echo $data['check_address']['product'] ?>" data-vendor="<?php echo $data['check_address']['vendor'] ?>" data-location_group="<?php echo $data['ship_address_info']['city'] ?>"data-location_area="<?php echo $data['ship_address_info']['area_name'] ?>">

                        <!-- <?php if ($data[ 'info'][ 'payment_method']=="cod" ){ ?> -->
                                    
                                   <!--  <?php if ($data[ 'info'][ 'shipping_status']=='0' ){ ?>
                                        <div class="pt-2">
                                            <button class="btn btn-primary d-block w-100" id="cartShippingRestrict" type="submit">Proceed to Checkout</button>
                                        </div>
                                        <p class="ship_error"></p>
                                    <?php }else{ ?> -->
                                        
                                    <!-- <?php } ?> -->

                        <!-- <?php } else { ?> -->

                                    <!-- <?php if ($data[ 'info'][ 'shipping_id']!='0' && $data[ 'info'][ 'shipping_status']==1){ ?> -->

                                    <input type="hidden" id="paymentTotalAmount" value="<?php echo number_format($data['payment_total'],2) ?>">

                                    <div class="proceedToOnlinePayment display_none">
                                        <form action="<?php echo BASEPATH ?>cartdetails/online" method="POST">
                                            <input  type='hidden' value='<?php echo $_SESSION[' online_method_key '] ?>' name='fkey' id='fkey'>
                                            <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="<?php echo PAYMENT_GATEWAY_CLIENT_ID ?>" data-amount="<?php echo ($data['payment_total']*100) ?>" data-name="<?php echo $data['user']['name'] ?>" data-description="Online Payment for Rs. <?php echo $data['payment_total'] ?>"
                                            data-image="<?php echo ASSETS_PATH ?>logo.png" data-netbanking="true" data-description="<?php echo $data['user']['name'] ?> - E wallet Payment Addtion" data-prefill.name="<?php echo $data['user']['name'] ?>" data-prefill.email="<?php echo $data['user']['email'] ?>"
                                            data-prefill.contact="<?php echo $data['user']['mobile'] ?>" data-notes.shopping_order_id="<?php echo $_SESSION['user_cart_id'] ?>">
                                            </script>
                                            <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
                                            <input type="hidden" id="shopping_order_id" name="shopping_order_id" value="<?php echo $_SESSION['user_cart_id'] ?>">
                                        </form>
                                    </div>

                                    <div class="proceedToCashOnDelivery">
                                        <form id="cashOndelivery" method="POST" action="#">
                                            <input type="hidden" name="paymentmethod" value="cod">
                                            <div class="pt-2 procees_to_checkout_btn">
                                                <button class="btn btn-primary d-block w-100 rounded-pill" type="submit">Proceed to Checkout</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- <?php } else { ?> -->

                                       <!--  <div class="pt-2">
                                            <button class="btn btn-primary d-block w-100" id="cartShippingRestrict" type="submit">Proceed to Checkout</button>
                                        </div>
                                        <p class="ship_error"></p>
                                    <?php } ?> -->

                        <!-- <?php } ?> -->

                <?php } else { ?>

                        <div class="pt-2 login-order-confirmation">
                            <a href="<?php echo BASEPATH ?>login" class="btn btn-primary d-block w-100 rounded-pill">Login for Order Confirmation</a>
                        </div>

                <?php } ?>
                    </div>
                </div>
                </div>


                <div class="col-lg-4 col-sm-12">
                    <div class="rounded-3 shadow p-4 mb-lg-0 mb-md-0 mb-5">
                        <div class="d-sm-flex">
                            <h1 class="h6 pt-3 text-sm-start">Price Details (1 Item)</h1>
                        </div>
                        <ul class="list-unstyled fs-sm pb-2 border-bottom">
                            <li class="d-flex justify-content-between align-items-center"><span class="me-2">Subtotal:</span><span class="text-end">Rs. <?php echo number_format($data['info']['sub_total'],2) ?></span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center"><span class="me-2">Shipping:</span><span class="text-end"><?php echo $data['inr_format']['shipping_cost'] ?></span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center"><span class="me-2">Taxes:</span><span class="text-end">Rs.<?php echo $data['inr_format']['total_tax'] ?></span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center"><span class="me-2">Discount:</span><span class="text-end"><?php echo (($data['info']['coupon_value']>0)? "-" : "" );  ?><?php echo number_format($data['info']['coupon_value'],2) ?></span>
                            </li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center"><strong><span class="me-2">Total</strong><strong></span><span class="text-end">Rs.<?php echo number_format($data['info']['sub_total'] + $data['info']['shipping_cost']+ $data['info']['igst_amt']-$data['info']['coupon_value'],2)  ?></span></strong>
                        </div>
                        <!-- <div class="pt-2">
                    <button class="btn btn-primary d-block w-100" type="submit">Make Payment</button>
                  </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>

<script>
    $(document).ready(function() {


        var address_status = $("#addressData").data("address_status");
        var product        = $("#addressData").data("product");
        var vendor         = $("#addressData").data("vendor");
        var location       = $("#addressData").data("location_group");
        var area           = $("#addressData").data("location_area");
        var vendor_check   = $("#addressData").data("vendor_status");


        if(vendor_check!=0) {

        if (address_status == 0) {

            data = 'Delivery for ' + product + ' to ' + area + ' location is currently not available from ' + vendor + '!';

            swal(data, "", "warning");

        } else if (address_status == "area_not_available") {
            setTimeout(function() {
                new Noty({
                    text: '<strong>Delivery to ' + area + ' location is currently not available for this order</strong>!',
                    type: 'warning',
                    theme: 'relax',
                    layout: 'bottomCenter',
                    timeout: 3000
                }).show();
            }, 300);
        } else {
            if (address_status == 1) {
                // window.location = base_path + "cartdetails/orderconfirmation";
            } else if (address_status == 2) {

                data = 'Delivery for ' + product + ' to ' + area + ' location is currently not available from ' + vendor + '!';
                swal({
                    title: data,
                    text: "",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#84c341 ",
                    confirmButtonText: "Ok",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function(isConfirm) {
                    if (isConfirm) {
                        window.location = base_path + "cartdetails/address";
                    }
                });

            } else if (address_status == 3) {
                window.location = base_path + "cartdetails/address?sl=success";
            }
        }

        } else {
            window.location = base_path + "cartdetails?vs=success";
        }







        // if(address_status==0) {

        //   data = 'Delivery for '+ product +' to '+  area +' location is currently not available from '+ vendor +'!';

        //   swal(data,"", "warning");

        // } else if(address_status=="area_not_available") { 
        //    setTimeout(function() {
        //       new Noty({
        //           text: '<strong>Delivery to '+ area +' location is currently not available for this order</strong>!',
        //           type: 'warning',
        //           theme: 'relax',
        //           layout: 'bottomCenter',
        //           timeout: 3000
        //       }).show();
        //   }, 300);
        // } else if (address_status==3) {
        //      window.location = base_path + "cartdetails/address?sl=success";
        //   } 

        return false;
    });
</script>


<script type="text/javascript">
    //Shipping Restrict 

    $(".paymentMethodCheckBox").click(function() {
        var method = $(this).val();

        if(method=="cod") {
            $(".proceedToOnlinePayment").addClass("display_none");
            $(".proceedToCashOnDelivery").removeClass("display_none");
        } else {
            $(".proceedToCashOnDelivery").addClass("display_none");
            $(".proceedToOnlinePayment").removeClass("display_none");
        }
    });

    $("#cartShippingRestrict").click(function() {
        $(".ship_error").html("Please Add Shipping address to proceed");
    });

    $('.payment_method').click(function() {
        var value = $(this).val();
        $.ajax({
            type: "POST",
            url: base_path + "cartdetails/api/payment_method",
            dataType: "html",
            data: {
                result: value
            },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                if (data == 1) {
                    location.reload();
                } else {
                    $(".form-error-cart").html(data);
                }
            }
        });
        return false;
    });

    $('#cashOndelivery').click(function() {
        var value = $("#add_notes").val();
        var order_value = $("#paymentTotalAmount").val();


        data = 'Please confirm your order for cash on delivery of ₹ '+ order_value;
                swal({
                    title: data,
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#84c341 ",
                    confirmButtonText: "Confirm",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        $(".procees_to_checkout_btn").hide();
                         $.ajax({
                            type: "POST",
                            url: base_path + "cartdetails/api/cashOndelivery",
                            dataType: "html",
                            data: {
                                result: value
                            },
                            beforeSend: function() {
                                $(".page_loading").show();
                            },
                            success: function(data) {
                                $(".page_loading").hide();
                                if (data == 1) {
                                    window.location = base_path + "cartdetails/details?u=success";
                                } else {
                                    $(".form-error-cart").html(data);
                                }
                            }
                        });
                    }
                });

        return false;
    });

    $('#onlinePayment').click(function() {
        // var id = $("#shopping_order_id").val();
        // alert(id);
        $.ajax({
            type: "POST",
            url: base_path + "app/models/Online.php",
            dataType: "html",
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                if (data == 1) {
                    window.location = base_path + "cartdetails/details?u=success";
                } else {
                    $(".form-error-cart").html(data);
                }
            }
        });

        return false;
    });
</script>