<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="editCoupon" action="#">
                        <input type="hidden" name="session_token" id="session_token" value="<?php echo $data['token'] ?>">
                        <?php echo $data[ 'csrf_edit_coupon'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>coupon"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="submit_button_wrap">
                                            <?php if (($data['info']['is_draft']==1)): ?>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"  value="1"  id="draft_button" name="save_as_draft" <?php echo (($data['info']['is_draft']==1) ? 'checked' : '') ?>>
                                                    <label class="custom-control-label" for="draft_button">Save as draft</label>
                                                </div>
                                            <?php endif ?>
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>coupon"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> <?php echo (($data['info']['is_draft']==1) ? "Save as Draft" : "Update") ?></button> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-error"></div>
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Coupon Info</h5>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="form-label"> Coupon Code
                                            <em>*</em>
                                        </label>
                                        <input type="text" name="coupon_code" id="coupon_code" value="<?php echo $data['info']['coupon_code'] ?>" class="form-control" placeholder="Enter Coupon Code">
                                        <p class="help_text">Customer will enter this code at checkout.</p>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="form-label"> Type
                                            <!-- <em>*</em> -->
                                        </label>
                                        <div class="g-4 align-center flex-wrap">
                                            <div class="g">
                                                <div class="custom-control custom-control-sm custom-radio">
                                                    <input type="radio" class="custom-control-input coupon_type" name="coupon_type" id="customRadio7" value="c_t_percentage"  <?php echo (($data['info']['type']=='c_t_percentage')? 'checked' : '' ); ?>>
                                                    <label class="custom-control-label" for="customRadio7">Percentage</label>
                                                </div>
                                            </div>
                                            <div class="g">
                                                <div class="custom-control custom-control-sm custom-radio">
                                                    <input type="radio" class="custom-control-input coupon_type" name="coupon_type" id="customRadio6" value="c_t_fixed_amount" <?php echo (($data['info']['type']=='c_t_fixed_amount')? 'checked' : '' ); ?>>
                                                    <label class="custom-control-label" for="customRadio6">Fixed Amount</label>
                                                </div>
                                            </div>
                                            <?php if(1 != 1) { ?>
                                            <!--  <div class="g">
                                                <div class="custom-control custom-control-sm custom-radio">
                                                    <input type="radio" class="custom-control-input coupon_type" name="coupon_type" id="customRadio5" value="c_t_free_shipping" <?php echo (($data['info']['type']=='c_t_free_shipping')? 'checked' : '' ); ?>>
                                                    <label class="custom-control-label" for="customRadio5">Free Shipping</label>
                                                </div>
                                            </div> -->
                                            <?php  } ?>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 discount_value <?php echo (($data['info']['type']=='c_t_free_shipping')? "display_none" : '' ); ?>" >
                                        <label class="form-label">Discount Value <em>*</em>
                                        </label>
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-right input_top_margin" >
                                                <em class="icon ni ni-sign-inr inr_sign <?php echo (($data['info']['type']=='c_t_fixed_amount')? '' : "display_none" ); ?>" ></em> <em class="icon ni ni-percent percentage_sign  <?php echo (($data['info']['type']=='c_t_percentage')? '' : "display_none" ); ?>"></em>
                                            </div>
                                            <input type="text" class="form-control" id="discount_value" value="<?php echo $data['info']['discount_value'] ?>" name="discount_value" placeholder="Enter Discount Value">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 max_discount_price_input <?php echo (($data['info']['type']=='c_t_percentage')? '' : "display_none" ); ?> ">
                                        <label class="form-label">Maximum Discount Price  <em>*</em>
                                        </label>
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-right input_top_margin" >
                                                <em class="icon ni ni-sign-inr inr_sign"></em> 
                                            </div>
                                            <input type="text" class="form-control" id="max_discount_price" name="max_discount_price" value="<?php echo (($data['info']['max_discount_price']==0)? "" : $data['info']['max_discount_price']) ?>" placeholder="Maximum Discount Price">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-8 location_group <?php echo (($data['info']['type']=='c_t_free_shipping')? '' : "display_none" ); ?>" >
                                        <label class="form-label">Location Groups
                                            <en>*</en>
                                        </label>
                                        <div class="form-control-wrap">
                                            <select class="form-select" multiple="multiple" name="shippping_location_groups[]" data-placeholder="Select Location Groups"  required>
                                                <?php echo $data[ 'location_group']; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Coupon Settings </h5>
                                <div class="form-group ">
                                    <label class="form-label"> Applies To
                                        <!-- <em>*</em> -->
                                    </label>
                                    <div class="g-4 align-center flex-wrap">
                                        <div class="g">
                                            <div class="custom-control custom-control-sm custom-radio">
                                                <input type="radio" class="custom-control-input applies_to" name="applies_to" id="customRadio1" value="a_t_all_products" <?php echo (($data['info']['applies_to']=='a_t_all_products')? 'checked' : '' ); ?>>
                                                <label class="custom-control-label" for="customRadio1">All Products</label>
                                            </div>
                                        </div>
                                        <div class="g">
                                            <div class="custom-control custom-control-sm custom-radio">
                                                <input type="radio" class="custom-control-input applies_to" name="applies_to" id="customRadio2" value="a_t_specific_category" <?php echo (($data['info']['applies_to']=='a_t_specific_category')? 'checked' : '' ); ?>>
                                                <label class="custom-control-label" for="customRadio2">Specific Category</label>
                                            </div>
                                        </div>
                                        <div class="g">
                                            <div class="custom-control custom-control-sm custom-radio">
                                                <input type="radio" class="custom-control-input applies_to" name="applies_to" id="customRadio3" value="a_t_specific_product" <?php echo (($data['info']['applies_to']=='a_t_specific_product')? 'checked' : '' ); ?>>
                                                <label class="custom-control-label" for="customRadio3">Specific Product</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group specific_category <?php echo (($data['info']['applies_to']=='a_t_specific_category')? '' : "display_none" ); ?>" >
                                    <label class="form-label">Select Category
                                        <en>*</en>
                                    </label>
                                    <div class="form-control-wrap">
                                        <select class="form-select" multiple="multiple" name="specific_category[]" id="specific_category" data-placeholder="Select Location Groups" required>
                                            <?php echo $data[ 'categories']; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group specific_product <?php echo (($data['info']['applies_to']=='a_t_specific_product')? '' : "display_none" ); ?>" >
                                    <label class="form-label">Select Product
                                        <en>*</en>
                                    </label>
                                    <div class="form-control-wrap">
                                        <select class="form-select" multiple="multiple" name="specific_product[]" id="specific_product" data-placeholder="Select Location Groups" required>
                                            <?php echo $data[ 'products']; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Minimum Requirments<!-- <em>*</em> -->
                                    </label>
                                    <div class="g-4 align-center flex-wrap">
                                        <div class="g">
                                            <div class="custom-control custom-control-sm custom-radio">
                                                <input type="radio" class="custom-control-input min_requirements" name="min_requirements" id="minReq1" value="0" <?php echo (($data['info']['min_requirements']==0)? 'checked' : '' ); ?>>
                                                <label class="custom-control-label" for="minReq1">None</label>
                                            </div>
                                        </div>
                                        <div class="g">
                                            <div class="custom-control custom-control-sm custom-radio">
                                                <input type="radio" class="custom-control-input min_requirements" name="min_requirements" id="minReq2" value="1" <?php echo (($data['info']['min_requirements']==1)? 'checked' : '' ); ?>>
                                                <label class="custom-control-label" for="minReq2">Minimum purchase amount (₹)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group min_purchase <?php echo (($data['info']['min_requirements']==1)? '' : "display_none" ); ?>" >
                                    <label class="form-label">Minimum Purchase Amount<em>*</em>
                                    </label>
                                    <input type="text" name="min_purchase_amt" id="min_purchase_amt" value="<?php echo $data['info']['min_purchase_amt'] ?>"  class="form-control" autocomplete="off" placeholder="Enter Minimum purchase amount">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Limit per customer<!-- <em>*</em> -->
                                    </label>
                                    <div class="g-4 align-center flex-wrap">
                                        <div class="g">
                                            <div class="custom-control custom-control-sm custom-radio">
                                                <input type="radio" class="custom-control-input per_user_limit" name="per_user_limit" id="customerEGB1" value="0"  <?php echo (($data['info']['per_user_limit']==0)? 'checked' : '' ); ?>>
                                                <label class="custom-control-label" for="customerEGB1">No</label>
                                            </div>
                                        </div>
                                        <div class="g">
                                            <div class="custom-control custom-control-sm custom-radio">
                                                <input type="radio" class="custom-control-input per_user_limit" name="per_user_limit" id="customerEGB2" value="1" <?php echo (($data['info']['per_user_limit']==1)? 'checked' : '' ); ?>>
                                                <label class="custom-control-label" for="customerEGB2">Yes</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group per_user_limit_input <?php echo (($data['info']['per_user_limit']==1)? '' : "display_none" ); ?>">
                                    <label class="form-label">Limit per customer value<em>*</em>
                                    </label>
                                    <input type="text" name="per_user_limit_value" id="per_user_limit_value" class="form-control" autocomplete="off" placeholder="Enter Usage limit" value="<?php echo $data['info']['per_user_limit_value'] ?>" >
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Usage Limits<!-- <em>*</em> -->
                                    </label>
                                    <div class="g-4 align-center flex-wrap">
                                        <div class="g">
                                            <div class="custom-control custom-control-sm custom-radio">
                                                <input type="radio" class="custom-control-input usage_limit" name="usage_limit" id="usageLimit1" value="0" <?php echo (($data['info']['usage_limits']==0)? 'checked' : '' ); ?>>
                                                <label class="custom-control-label" for="usageLimit1">No</label>
                                            </div>
                                        </div>
                                        <div class="g">
                                            <div class="custom-control custom-control-sm custom-radio">
                                                <input type="radio" class="custom-control-input usage_limit" name="usage_limit" id="usageLimit2" value="1" <?php echo (($data['info']['usage_limits']==1)? 'checked' : '' ); ?>>
                                                <label class="custom-control-label" for="usageLimit2">Yes</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group usage_limit_input <?php echo (($data['info']['usage_limits']==1)? '' : "display_none" ); ?>" >
                                    <label class="form-label">Limit value<em>*</em>
                                    </label>
                                    <input type="text" name="usage_limit_value" id="usage_limit_value" value="<?php echo $data['info']['usage_limit_value'] ?>" class="form-control" autocomplete="off" placeholder="Enter Usage limit">
                                </div>
                            </div>
                        </div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Coupon Active Date</h5>
                                <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-label">Start Date <em>*</em>
                                    </label> 
                                    <div class="form-control-wrap">
                                        <div class="form-icon form-icon-left input_top_margin" >
                                            <em class="icon ni ni-calendar"></em>
                                        </div>
                                        <input type="text" name="start_date"  id="start_date" value="<?php echo date("d-m-Y",strtotime($data['info']['start_date'])) ?>" autocomplete="off" class="form-control date-picker" data-date-format="dd-mm-yyyy" placeholder="Start Date">
                                    </div>
                                </div>
                                 <div class="form-group col-md-6">
                                    <label class="form-label">Start Time <em>*</em></label>
                                    <div class="form-control-wrap">
                                        <input type="text" name="start_time"  id="start_time" value="<?php echo $data['info']['start_time'] ?>" autocomplete="off" class="form-control time-picker" placeholder="Start Time">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label">End Date <em>*</em>
                                    </label> 
                                    <div class="form-control-wrap">
                                        <div class="form-icon form-icon-left input_top_margin" >
                                            <em class="icon ni ni-calendar"></em>
                                        </div>
                                        <input type="text" name="end_date"  id="end_date" value="<?php echo date("d-m-Y",strtotime($data['info']['end_date'])) ?>" autocomplete="off" class="form-control date-picker" data-date-format="dd-mm-yyyy" placeholder="End Date">
                                    </div>
                                </div>
                                 <div class="form-group col-md-6">
                                    <label class="form-label">End Time <em>*</em></label>
                                    <div class="form-control-wrap">
                                        <input type="text" name="end_time"  id="end_time" value="<?php echo $data['info']['end_time'] ?>" autocomplete="off" class="form-control time-picker" placeholder="End Time">
                                    </div>
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
    // Discount Value 

    $(".coupon_type").click(function() {
        var value = $(this).val();
        if (value == 'c_t_fixed_amount') {
            $(".discount_value").removeClass('display_none');
            $(".inr_sign").removeClass('display_none');
            $("#discount_value-error").addClass('display_none');
            $("#discount_value").removeClass("error");
            $(".percentage_sign").addClass('display_none');
            $(".location_group").addClass('display_none');
            $(".max_discount_price_input").addClass("display_none");
        } else if (value == 'c_t_percentage') {
            $(".discount_value").removeClass('display_none');
            $(".percentage_sign").removeClass('display_none');
            $(".inr_sign").addClass('display_none');
            $(".location_group").addClass('display_none');
            $(".max_discount_price_input").removeClass("display_none");
        } else if (value == 'c_t_free_shipping') {
            $(".discount_value").addClass('display_none');
            $(".percentage_sign").addClass('display_none');
            $(".location_group").removeClass('display_none');
        } else {
            $(".inr_sign").addClass('display_none');
            $(".percentage_sign").addClass('display_none');
            $(".location_group").removeClass('display_none');
        }
    });

    // Applis to

    $(".applies_to").click(function() {
        var value = $(this).val();
        if (value == 'a_t_all_products') {
            $(".specific_category").addClass('display_none');
            $(".specific_product").addClass('display_none');
            $("#specific_category").val();
            $("#specific_product").val();
        } else if (value == 'a_t_specific_category') {
            $(".specific_category").removeClass('display_none');
            $(".specific_product").addClass('display_none');
            $("#specific_product").val();
        } else if (value == 'a_t_specific_product') {
            $(".specific_category").addClass('display_none');
            $(".specific_product").removeClass('display_none');
            $("#specific_category").val();
        } else {
            $(".specific_category").addClass('display_none');
            $(".specific_product").addClass('display_none');
        }
    });

    // Minimun Requirments 

    $(".min_requirements").click(function() {
        var value = $(this).val();
        if (value == 0) {
            $(".min_purchase").addClass('display_none');
        } else if (value == 1) {
            $(".min_purchase").removeClass('display_none');
        } else {
            $(".min_purchase").addClass('display_none');
        }
    });

    // Usage Limits 

    $(".usage_limit").click(function() {
        var value = $(this).val();
        if (value == 0) {
            $(".usage_limit_input").addClass('display_none');
        } else if (value == 1) {
            $(".usage_limit_input").removeClass('display_none');
        } else {
            $(".usage_limit_input").addClass('display_none');
        }
    });

    // Per Customer Limit value

    $(".per_user_limit").click(function() {
        var value = $(this).val();
        if (value == 0) {
            $(".per_user_limit_input").addClass('display_none');
        } else if (value == 1) {
            $(".per_user_limit_input").removeClass('display_none');
        } else {
            $(".per_user_limit_input").addClass('display_none');
        }
    });

    $.validator.addMethod("discount_percentage", function (value, elem) {
        if($("#customRadio7").prop("checked")){
            return ($("#discount_value").val() <= 100);
        } else {
            return true;
        }
    });

    // Add
    $("#editCoupon").validate({
       rules: {
            coupon_code: {
                required: true
            },
            discount_value : {
                 required: function(element){
                    if($(".coupon_type").val()=='c_t_percentage'){
                        return true;
                    } else if($(".coupon_type").val()=='c_t_fixed_amount'){
                        return true;
                    } else {
                        return 0;
                    }
                },
                digits : true,
                discount_percentage: true,
            },

            max_discount_price : {
                 required: function(element){
                    if($(".coupon_type").val()=='c_t_percentage'){
                        return true;
                    } else {
                        return 0;
                    }
                },
                digits: true,
            },

            // shippping_location_groups : {
            //     required: function(element){
            //         if($(".coupon_type").val()==3){
            //             return true;
            //         } else {
            //             return 0;
            //         }
            //     }

            // },
            min_purchase_amt : {
                 required: function(element){
                    if($(".min_requirements").val()==1){
                        return true;
                    } else {
                        return 0;
                    }
                },
                digits : true,
            },
            per_user_limit_value : {
                 required: function(element){
                    if($(".per_user_limit").val()==1){
                        return true;
                    } else {
                        return 0;
                    }
                },
                digits : true,
            },
            usage_limit_value : {
                 required: function(element){
                    if($(".usage_limit").val()==1){
                        return true;
                    } else {
                        return 0;
                    }
                },
                digits : true,
            },

            start_date: {
                required: true
            },
            start_time: {
                required: true
            },
            end_date: {
                required: true
            },
            end_time: {
                required: true
            },

        },
        messages: {
            coupon_code: {
                required: "Please Enter Coupon code",
            },
            discount_value: {
                required: "Please Enter Discount Value",
                digits  : "Please Enter Valid Discount Percentage ",
                discount_percentage: "Discount Percentage can't be more than 100",
            },
           
            min_purchase_amt: {
                required: "Please Enter Minimum purchase amount",
                digits  : "Please Enter Valid Price Amount ",
            },
            per_user_limit_value: {
                required: "Please Enter User Limit",
                digits  : "Please Enter Valid Limit Value ",
            },
            usage_limit_value: {
                required: "Please Enter Usage Limit",
                digits  : "Please Enter Valid Limit Value ",
            },
            start_date: {
                required: "Please Enter start date "
            },
            start_time: {
                required: "Please Enter start time "
            },
            end_date: {
                required: "Please Enter end date "
            },
            end_time: {
                required: "Please Enter end time "
            },
            max_discount_price: {
                required: "Please Enter Maximum Discount Price ",
                digits  : "Please Enter valid Price"
            }
            // shippping_location_groups: {
            //     required: "Please Select Shipping Location Groups "
            // }
          
        },
        submitHandler: function(form) {
            toastr.clear();
            if ($("#draft_button").prop("checked") == false) {
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
            } else {
                saveForm();
            }
            return false;
        }
    });

    

    // Save Form

    function saveForm() {
        var formname = document.getElementById("editCoupon");
        var formData = new FormData(formname);

        $.ajax({
            url: core_path + "coupon/api/edit",
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
                    window.location = core_path + "coupon?a=success";
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