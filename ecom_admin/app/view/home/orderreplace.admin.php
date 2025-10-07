<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                    <form method="post" id="replaceCustomerOrder" class="form-validate is-alter" enctype="multipart/form-data">
                        <?php echo $data['csrf_order_replace'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php if($data['order_replace_details']['order_replaced']!=0) { ?>
                                                <h2><a   href="<?php echo COREPATH ?>orders/rejectedorderdetail/<?php echo $data['v_order_info']['id']; ?>"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <?php } else { ?>
                                            <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>orders/rejectedorderdetail/<?php echo $data['v_order_info']['id']; ?>"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <?php } ?>
                                        
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="submit_button_wrap">
                                            <?php if($data['order_replace_details']['order_replaced']!=0 || $data['check_cancel_status']==1 ) { ?>
                                                <a type="button" class="btn btn-light" href="<?php echo COREPATH ?>orders/rejectedorderdetail/<?php echo $data['v_order_info']['id']; ?>"> Back</a>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>orders/rejectedorderdetail/<?php echo $data['v_order_info']['id']; ?>"> Cancel</button>
                                                <button class="btn btn-success display_none" id="submit_button"><em class="icon ni ni-check-thick"></em> Place Order</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-error"></div>
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <div class="row">
                                    <div  class="form-group col-md-6">
                                         <h5 class="card-title">Order Details</h5>
                                    </div>
                                    <div  class="form-group col-md-6">
                                        <?php if($data['order_replace_details']['order_replaced']!=1) { ?>
                                        <?php if($data['check_cancel_status']==0 ) { ?>
                                        <button type="button" class="btn btn-danger cancelOrder float-end" data-url="<?php echo COREPATH ?>orders/rejectedorderdetail/<?php echo $data['v_order_info']['id']; ?>"> Cancel Order</button>
                                        <?php } else { ?>
                                            <span class="badge badge-outline-danger float-end">Order cancelled</span>
                                        <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Order Id
                                        </label>
                                        <span name="order_uid" id="order_uid" class="form-control" ><?php echo $data['order_info']['order_uid'] ?></span>
                                        <input type="hidden" name="current_order_uid" value="<?php echo $data['order_info']['order_uid'] ?>">
                                        <input type="hidden" name="current_order_id" value="<?php echo $data['order_info']['id'] ?>">
                                        <input type="hidden" name="current_vendor_order_id" value="<?php echo $data['v_order_info']['id'] ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Order Date
                                        </label>
                                        <span name="order_uid" id="order_uid" class="form-control" ><?php echo date("d-m-Y",strtotime($data['order_info']['created_at'])) ?></span>
                                    </div>
                                   
                                    <div class="form-group col-md-3">
                                        <label class="form-label"> Rejected Vendor
                                        </label>
                                        <span name="order_uid" id="order_uid" class="form-control" ><?php echo $data['vendor_info']['company'] ?></span>
                                    </div>
                                     <div class="form-group col-md-3">
                                        <label class="form-label"> Rejected Vendor Mobile
                                        </label>
                                        <span name="order_uid" id="order_uid" class="form-control" ><?php echo $data['vendor_info']['mobile'] ?></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label"> Rejected Vendor Emali
                                        </label>
                                        <span name="order_uid" id="order_uid" class="form-control" ><?php echo $data['vendor_info']['email'] ?></span>
                                    </div>
                                   <?php echo $data['rejected_items']; ?>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Payment Method
                                        </label>
                                        <span name="order_uid" value="<?php echo $data['order_info']['order_uid'] ?>" id="order_uid" class="form-control" ><?php echo (($data['order_info']['payment_type']=='online')? "Online" : "Cash On Delivery") ?></span>
                                    </div> 
                                    <div class="form-group col-md-3">
                                        <label class="form-label">Delivery Area 
                                        </label>
                                        <span name="order_uid" value="<?php echo $data['order_info']['order_uid'] ?>" id="order_uid" class="form-control" ><?php echo $data['d_address']['area_name'] ?></span>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-label">Delivery City 
                                        </label>
                                        <span name="order_uid" value="<?php echo $data['order_info']['order_uid'] ?>" id="order_uid" class="form-control" ><?php echo $data['d_address']['city'] ?></span>
                                    </div>
                                    <?php if($data['order_replace_details']['order_replaced']==0 ) { ?>
                                        <?php if(count($data['rejected_product_ids']['product_ids']) > 1) { ?>
                                        <div class="form-group col-md-6">
                                            <label class="form-label">If same vendor for all products</label>
                                            <div class="g-4 align-center flex-wrap">
                                                <div class="g">
                                                    <div class="custom-control custom-control-sm custom-radio">
                                                        <input type="radio" class="custom-control-input same_vendor_or_not" name="same_vendor_or_not" id="sameVendorOrNot1" value="1" checked>
                                                        <label class="custom-control-label" for="sameVendorOrNot1">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="g">
                                                    <div class="custom-control custom-control-sm custom-radio">
                                                        <input type="radio" class="custom-control-input same_vendor_or_not" name="same_vendor_or_not" id="sameVendorOrNot2" value="0">
                                                        <label class="custom-control-label" for="sameVendorOrNot2">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } else { ?>
                                            <input type="hidden" name="same_vendor_or_not" value="1">
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php if($data['order_replace_details']['order_replaced']==0 && $data['check_cancel_status']==0) { ?>
                        <div class="card card-shadow same_vendor_layout">
                            <div class="card-inner">
                                <h5 class="card-title">Replace Vendor</h5>
                                <div class="row">
                                    <div class="form-group col-md-6 ">
                                        <?php if($data['vendor_list']===0) { ?>
                                            <span><strong>No vendors available for this order</strong></span>
                                        <?php } else { ?>
                                            <div class="form-control-wrap">
                                                <label class='form-label'> Select Vendor <em>*</em> </label>
                                                <select class="form-select form-control selecte_vendor" data-product_id="all_products" data-variant_id="no_variants"  data-vendor_condition="same_vendor" data-search="on" id="replace_vendor_id" name="replace_vendor_id" required>
                                                    <option value="not_selected" >Select Vendor</option>
                                                    <?php echo $data['vendor_list']['vendor_drop_down_list']; ?>
                                                </select>
                                                <div class="error display_none" id="replace_vendor_error_all_products_no_variants">Select Vendor</div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row same_vendor_price_details replase_product_price_details_all_products_no_variants display_none">
                                    
                                </div>
                            </div>
                        </div>
                        <?php echo $data['vendor_list']['each_vendor_layout']; ?>
                        <?php } else { ?>
                           
                                        <?php echo $data['order_replace_details']['layout']; ?>
                                   
                        <?php }  ?>
                    </form>
                </div>
            </div>
            <!-- .nk-block -->
        </div>
    </div>
</div>



<!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    $(".same_vendor_or_not").click( function() {
        if($(this).val()==0) {
            $(".same_vendor_layout").addClass("display_none");
            $(".each_vendor_layout").removeClass("display_none");
            var check = checkAllProductVendorCheck(".selecte_diffrent_vendor");
            if(check) {
                $("#submit_button").removeClass("display_none");
            } else {
                $("#submit_button").addClass("display_none");
            }
        } else {
            $(".each_vendor_layout").addClass("display_none");
            $(".same_vendor_layout").removeClass("display_none");
            var check = $(".selecte_vendor").val();
            if(check!="not_selected") {
                $("#submit_button").removeClass("display_none");
            } else {
                $("#submit_button").addClass("display_none");
            }
        }
    });

    $(".cancelOrder").click(function(e) {
        // var url = $(this).data("url");
        Swal.fire({
            title: "Are you sure to cancel this order?",
            text: "The Changes made will move this order to cancelled list",
            icon: 'warning',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type:"POST",
                    url: core_path + "orders/api/cancellRejectedOrder",
                    dataType: "html",
                    data: { 
                            vendor_order_id      : <?php echo $data['v_order_info']["id"]; ?>,
                          },
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        window.location = core_path + "orders/rejectedorderdetail/"+<?php echo $data['v_order_info']["id"]; ?>+"?cs=success";
                    }

                });
            return false;
            }
        });
        e.preventDefault();
        return false;
    });


    $("body").on("change",".selecte_vendor", function() {
        
        var vendor_for         = $(this).data("product_id");
        var vendor_condition   = $(this).data("vendor_condition");
        var product_variant_id = $(this).data("variant_id");
        var value              = $(this).val();

        if($(this).val()!="not_selected") {
            $("#replace_vendor_error").hide();
            var vendor_id = $(this).val();
            $.ajax({
                type:"POST",
                url: core_path + "orders/api/replaceVendorPriceDetails",
                dataType: "html",
                data: { 
                        vendor_type    : vendor_condition,
                        product_id     : vendor_for,
                        variant_id     : product_variant_id,
                        vendor_id      : vendor_id,
                        vendor_order_id: <?php echo $data['v_order_info']["id"]; ?>,
                      },
                success: function(data) {
                        $("#replace_vendor_error_"+vendor_for+"_"+product_variant_id).addClass("display_none");
                        $(".replase_product_price_details_"+vendor_for+"_"+product_variant_id).html(data);
                        $(".replase_product_price_details_"+vendor_for+"_"+product_variant_id).removeClass("display_none");

                        if(vendor_for=="all_products") {
                           var check = $(".selecte_vendor").val();
                           if(check!="not_selected") {
                                $("#submit_button").removeClass("display_none");
                            } else {
                                $("#submit_button").addClass("display_none");
                            }
                        } else {
                           var check = checkAllProductVendorCheck(".selecte_diffrent_vendor");
                           if(check) {
                                $("#submit_button").removeClass("display_none");
                            } else {
                                $("#submit_button").addClass("display_none");
                            }
                        }

                }

            });
            return false;
        } else {
            $("#replace_vendor_error_"+vendor_for+"_"+product_variant_id).removeClass("display_none");
            $(".replase_product_price_details_"+vendor_for+"_"+product_variant_id).addClass("display_none");
            $("#submit_button").addClass("display_none");
        }
    });

    function checkAllProductVendorCheck(class_name) {
         var allValid = true;
         $(class_name).each(function () {
             if ($(this).val() == "not_selected") {
                 allValid = false;
                 return false;
             }
         });
         return allValid;
     }
    
    $.validator.addMethod("replace_vendor_check", function (value, elem) {
        if($("#replace_vendor_id").val()=="not_selected") {
            return false;
        } else {
            return true;
        }
    });

    $("#replaceCustomerOrder").validate({
        rules: {
            replace_vendor_id: {
                replace_vendor_check: true,
            }
        },
        messages: {
            replace_vendor_id: {
                replace_vendor_check: "Please select vendor",
            }
        },
        errorPlacement: function(error, element) {
            if(element.attr("name") == "replace_vendor_id") {
                error.appendTo("#replace_vendor_error");
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            toastr.clear();
            
            Swal.fire({
                title: "Are you sure to Save and Publish?",
                text: "Once published the item will not be deleted permanently. However move to trash option is available !!",
                icon: 'warning',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.value) {
                    saveForm();
                }
            });
           
            return false;
        }
    });

    function saveForm() {
        var formname = document.getElementById("replaceCustomerOrder");
        var formData = new FormData(formname);

        $.ajax({
            url: core_path + "orders/api/replaceOrder",
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
                    window.location = core_path + "orders/orderstab?order_replased=success";
                } else {
                    console.log(data);
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