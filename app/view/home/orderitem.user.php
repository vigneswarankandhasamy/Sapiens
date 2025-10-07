<?php require_once 'includes/top.php'; ?>
<!-- <div class="profile-banner otherpage-banner m-0">
    <img src="<?php echo IMGPATH ?>profile-banner.jpg" alt="">
    <div class="other-banner-title">
        <p>My Orders</p>
    </div>   
</div> -->  
<div class="breadcrumbs_area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <!-- <ul>
                        <li><a href="<?php echo BASEPATH ?>">home</a></li>
                        <li><a href="<?php echo BASEPATH ?>myaccount/myorders">My Orders</a></li>
                        <li><a > Order Items</a></li>
                    </ul> -->
                    <?php echo $myaccount_breadcurm; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contact_area manageaddress">
    <div class="container">
        <div class="row">
            <!-- <div class="col-lg-3 col-md-3">
                <div class="contact_message content">
                    <ul class="edit-profile">
                        <li><a href="<?php echo BASEPATH ?>myaccount/edit">My Profile</a>
                        </li>
                        <li ><a href="<?php echo BASEPATH ?>myaccount/changepassword">Change Password</a>
                        </li>
                        <li ><a href="<?php echo BASEPATH ?>myaccount/manageaddress">Manage Address</a>
                        </li>
                        <li class="active"><a href="<?php echo BASEPATH ?>myaccount/myorders">My Orders</a>
                        </li>
                        <li ><a href="<?php echo BASEPATH ?>myaccount/wishlist">My Wishlist</a>
                        </li>
                        <li><a href="<?php echo BASEPATH ?>home/logout">Logout</a>
                        </li>
                    </ul>
                </div>
            </div> -->
            <!--product items-->
            <div class="col-md-12 col-xs-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="my_account_forms">
                            <h2>Order Item Status</h2>
                                    <?php echo $data['list'] ?>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="return_form" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ">Return Order </h4>
            </div>
            <div class="address-form-error red"></div>
            <form id="orderProductReturn" name="orderProductReturn" method="POST" action="#" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- <h5 class="shipping_warp"></h5> -->
                    <input type="hidden" value="<?php echo $_SESSION['order_return_key'] ?>" name="fkey" id="fkey">
                    <input type="hidden"  name="token" id="token">
                    <div class="row">
                      <h4 class="form-label modal-title"> Product Details</h4>
                        <div class="col-md-12">

                            <div class="row"><div class="col-md-3"><span class="span_tag">Product Name</span></div><div class="col-md-9">: <span class="info_tag" id="product_name_tag"> </span></div></div>
                            <div class="row"><div class="col-md-3"><span class="span_tag">Quantity</span></div><div class="col-md-9">: <span class="info_tag" id="quantity_tag"> </span></div></div>
                            <div class="row"><div class="col-md-3"><span class="span_tag">Price</span></div><div class="col-md-9">: ₹ <span class="info_tag" id="price_tag"> </span></div></div>
                            <div class="row"><div class="col-md-3"><span class="span_tag">Sold By</span></div><div class="col-md-9">: <span class="info_tag" id="vendor_tag"> </span></div></div>
                        </div>
                    </div>
                    <div class="row">

                        <h4 class="form-label modal-title return_reason_heading"> Return Reason</h4>
                        <div class="col-md-12">
                           <div class="form-group address_model">
                                <label for="city">Select Reason <span class="text-danger">*</span></label><br>
                                <div class="form-control-wrap">
                                    <select class="form-select" name="return_reason" id="return_reason" data-search="on" >
                                        <option value=''>Select Reason</option>
                                       <?php echo $data['return_reasons']; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group address_model" >
                                <label for="city">Return Reamrks
                                </label>
                                <br>
                                <textarea type="text" name="return_remarks" id="return_remarks" rows="4" class="form-control" placeholder="Type a return reason hear"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group address_model" >
                                <label for="city"></label>
                                <input type="checkbox" id="conditions_check" class="larger" name="conditions_check" value="1"> <label for="conditions_check"> I agree to <a href="<?php echo BASEPATH ?>pages/details/return-policy" target="_blank" class="legalpage_link" >Return policy </a> & <a href="<?php echo BASEPATH ?>pages/details/terms-conditions" class="legalpage_link" target="_blank" > Terms & conditions.</a> <span class="text-danger">*</span></label>
                                <div id="errorToShow"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary btn rounded-pill" data-bs-dismiss="modal" onclick="orderProductReturn.reset();">Cancel</button>
                    <button type="submit" class="btn btn-hero rounded-pill">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Product Review -->
<div class="modal fade " id="product_review" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ">Rate & Review Product</h4>
            </div>
            <div class="review-form-error red"></div>
            <div class="review_form_body">
                <form action='#' id='addProductReview'>
                    <div class="modal-body">
                        <div class='comment_title'>
                            <h4>Add a review </h4>
                            <p>Your email address will not be published. Required fields are marked </p>
                        </div>
                        <div class='row'>
                            <div class='col-lg-12'>
                                    <input type="hidden" name="add_review_product_id" id="addReviewProductId" >
                                    <input type="hidden" name="add_review_order_id" id="addReviewOrderId" >
                                    <div class='product_rating mb-10'>
                                        <h4>Your rating</h4>
                                        <span class='star-rating_add_product_review'></span>
                                        <!--<span class='live-rating'></span>-->
                                        <input type='hidden' name='star_ratings' class='add_review_rating_input' id="addReviewStarRating" value='5' id='rating_input'>
                                    </div>
                                    <div class='product_review_form'>
                                        <div class='row'>
                                            <div class='col-12 your_commend'>
                                                <label for='review_comment'>Your comment </label>
                                                <textarea name='comment' name='comment' placeholder='Your comment' id='review_comment'></textarea>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type='button' class='btn btn-secondary rounded-pill' data-bs-dismiss='modal' onclick="addProductReview.reset()">Cancel</button>
                        <button type='submit' class='btn btn-hero rounded-pill'>Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Review -->
<div class="modal fade " id="edit_product_review" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ">Edit Rate & Review</h4>
            </div>
            <div class="review-form-error red"></div>
            <div class="review_form_body">
                <form action='#' id='editProductReview'>
                    <div class="modal-body">
                        <div class='comment_title'>
                            <h4>Edit Review </h4>
                            <p>Your email address will not be published. Required fields are marked </p>
                        </div>
                        <div class='row'>
                            <div class='col-lg-12'>
                                    <input type="hidden" name="edit_review_id" id="editReviewId" >
                                    <div class='product_rating mb-10'>
                                        <h4>Your rating</h4>
                                        <span class='star-rating_edit_product_review'></span>
                                        <input type='hidden' name='star_ratings' class='edit_review_rating_input' id="editReviewStarRating">
                                    </div>
                                    <div class='product_review_form'>
                                        <input type='hidden' name='edit_review_id' id='edit_review_id' value=''>
                                        <input type='hidden' name='edit_product_id' id='edit_product_id' value=''>
                                        <div class='row'>
                                            <div class='col-12 your_commend'>
                                                <label for='review_comment'>Your comment </label>
                                                <textarea name='comment' name='comment' placeholder='Your comment' id='edit_review_comment'></textarea>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type='button' class='btn btn-secondary rounded-pill' data-bs-dismiss='modal'>Cancel</button>
                        <button type='submit' class='btn btn-hero rounded-pill'>Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Vendor Rating Modal -->
<div class="modal fade " id="addVendorRatingModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ">Rate a vendor</h4>
            </div>
            <div class="review-form-error red"></div>
            <div class="review_form_body">
                <form action='#' id='addVendorRating'>
                    <input type="hidden" name="vendor_id" id="addRatingVendorId"  value="">
                    <input type="hidden" name="product_id" id="addRatingProductId"  value="">
                    <input type="hidden" name="order_id" id="addRatingOrderId"  value="">
                    <div class="modal-body">
                         <div class="row">
                          <h4 class="form-label modal-title"> Vendor Details</h4>
                            <div class="col-md-12">
                                <div class="row"><div class="col-md-3"><span class="span_tag">Vendor</span></div><div class="col-md-9">: <span class="info_tag" id="vendor_company"> </span></div></div>
                                <div class="row"><div class="col-md-3"><span class="span_tag">Location</span></div><div class="col-md-9">: <span class="info_tag" id="venodr_location"> </span></div></div>
                                <div class="row"><div class="col-md-3"><span class="span_tag">Product</span></div><div class="col-md-9">:  <span class="info_tag" id="customer_product"> </span></div></div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-lg-12'>
                                    <div class='product_rating mt-10'>
                                        <h4>Your rating</h4>
                                        <span class='star-rating'></span>
                                        <input type='hidden' name='star_ratings' class='rating_input' value='5' id='rating_input'>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" onclick="orderProductReturn.reset();">Cancel</button>
                        <button type="submit" class="btn btn-hero rounded-pill">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Vendor Rating Modal -->
<div class="modal fade " id="editVendorRatingModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ">Edit Rate a vendor</h4>
            </div>
            <div class="rating-form-error red"></div>
                <form action='#' id='editVendorRating'>
                    <div class="rating_form_body">
                       
                    </div>
                </form>
        </div>
    </div>
</div>

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    /*---------------------------------------------
            Review Click/Add/Edit Functions 
    ---------------------------------------------*/

    // Product  Review functions
   
    $('.addProductReview').click(function() {
        var product_id = $(this).data('product_id');
        var order_id   = $(this).data('order_id');
        $("#addReviewProductId").val(product_id);
        $("#addReviewOrderId").val(order_id);

        $(".star-rating_add_product_review").starRating({
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
                $(".add_review_rating_input").val(currentRating);
            }
        });


        $("#product_review").modal("show");
    });

    // Add Product Review
    $("#addProductReview").validate({
        submitHandler: function(form) {
            var content = $(form).serialize();
            $.ajax({
                type: "POST",
                url: base_path + "product/api/addProductReview",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    data_arr = data.split("`");
                    if (data == 1) {
                        window.location = window.location.href + "?pr=success";
                    } else  {
                        //console.log(data);
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>' + data + '</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    }
                }
            });
            return false;
        }
    });

    $('.editProductReview').click(function() {
        var review_id  = $(this).data('review_id');
        $("#edit_review_id").val(review_id);
        $.ajax({
            type: "POST",
            url : base_path + "myaccount/api/getReviewInfo",
            data: { 
                    result  : review_id,
                  },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                data = $.parseJSON(data);

                var initStarRating = function() {
                    $(".star-rating_edit_product_review").starRating({
                        totalStars: 5,
                        starShape: 'rounded',
                        starSize: 20,
                        emptyColor: '#ddd',
                        hoverColor: '#ffc107',
                        activeColor: '#ffc107',
                        initialRating: data['star_ratings'],
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
                            //console.log(currentRating, $el)
                            if(currentRating) {
                                var currentRating = currentRating;
                            } else {
                                var currentRating = data['star_ratings'];
                            }

                            $("#editReviewStarRating").val(currentRating);
                        }
                    });

   
                }

                initStarRating();
                 
                $("#editReviewStarRating").val(data['star_ratings']);
                $("textarea#edit_review_comment").val(data['comment']);                
                $("#edit_product_review").modal("show");
            }
        });
        return false;

    });

    // Edit Product Review
    $("#editProductReview").validate({
        submitHandler: function(form) {
            var content = $(form).serialize();
            $.ajax({
                type: "POST",
                url: base_path + "product/api/editProductReview",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    data_arr = data.split("`");
                    if (data == 1) {
                        window.location = window.location.href + "?pr=success";
                    } else  {
                        //console.log(data);
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>' + data + '</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    }
                }
            });
            return false;
        }
    });

    /*---------------------------------------------
        Vendor Rating Click/Add/Edit Functions 
    ---------------------------------------------*/

    $('.addVendorRating').click(function() {
        var order_id  = $(this).data('order_id');
        var vendor_id  = $(this).data('vendor_id');
        var product_id = $(this).data('product_id');
        $.ajax({
            type: "POST",
            url: base_path + "myaccount/api/getVendorInfo",
            data: { 
                    vendor_id  : vendor_id,
                    product_id : product_id,
                  },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                var data = $.parseJSON(data);
                $("#addRatingVendorId").val(vendor_id);
                $("#addRatingProductId").val(product_id);
                $("#addRatingOrderId").val(order_id);
                $("#vendor_company").html(data['vendor_info']['company']);
                $("#venodr_location").html(data['vendor_info']['city']);
                $("#customer_product").html(data['product_info']['product_name']);
                $("#addVendorRatingModal").modal("show");
            }
        });
        return false;
    });

    $("#addVendorRating").validate({
        submitHandler: function(form) {
            var content = $(form).serialize();
            $.ajax({
                type: "POST",
                url: base_path + "myaccount/api/addVendorRating",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    data_arr = data.split("`");
                    if (data == 1) {
                        window.location = window.location.href + "?vra=success";
                    } else  {
                        //console.log(data);
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>' + data_arr[1] + '</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    }
                }
            });
            return false;
        }
    });

    $('.editVendorRating').click(function() {
        var rating_id  = $(this).data('rating_id');
        $.ajax({
            type: "POST",
            url: base_path + "myaccount/api/getEditVendorRateForm",
            data: { result  : rating_id },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                var data = $.parseJSON(data);
                $(".rating_form_body").html(data['layout']);
                $(".star-rating_vendor_edit").starRating({
                    totalStars: 5,
                    starShape: 'rounded',
                    starSize: 20,
                    emptyColor: '#ddd',
                    hoverColor: '#ffc107',
                    activeColor: '#ffc107',
                    initialRating: data['rating_info']['star_ratings'],
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

                        if(currentRating) {
                            var currentRating = currentRating;
                        } else {
                            var currentRating = data['rating_info']['star_ratings'];
                        }

                        $("#edit_vendor_rating_input").val(currentRating);
                    }
                });
                $("#editVendorRatingModal").modal("show");
            }
        });
        return false;
    });

    $("#editVendorRating").validate({
        submitHandler: function(form) {
            var content = $(form).serialize();
            $.ajax({
                type: "POST",
                url: base_path + "myaccount/api/editVendorRating",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    //console.log(data);
                    data_arr = data.split("`");
                    if (data == 1) {
                        window.location = window.location.href + "?pr=success";
                    } else  {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>' + data_arr[1] + '</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    }
                }
            });
            return false;
        }
    });

    /*---------------------------------------------
                Order Return Functions 
    ---------------------------------------------*/

    // order return functions
   
    $('.return_order').click(function() {
        var order_item = $(this).data('order_item');
        $.ajax({
            type: "POST",
            url: base_path + "myaccount/api/retunOrderProductInfo",
            dataType: "html",
            data: { result: order_item },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                var json = $.parseJSON(data);
                var total_amt = parseFloat(json['sub_total']) + parseFloat(json['total_tax']);
                $("#token").val(order_item);
                $("#product_name_tag").html(json['product_name']);
                $("#quantity_tag").html(json['qty']);
                $("#price_tag").html(total_amt);
                $("#vendor_tag").html(json['company']);
                $("#return_form").modal("show");
            }
        });
        return false;
    });

    // Return From Submition

    $("#orderProductReturn").validate({
        rules: {
              return_reason: {
                  required: true,
              },
              return_remarks: {
                required: true,
              },
              conditions_check: {
                  required: true
              }
          },
        messages: {
             
              return_reason: {
                  required: "Return reason cannot be empty",
              },
              return_remarks: {
                  required: "Remarks cannot be empty",
              },
              conditions_check: {
                  required: "Please indicate that you have read and agree to the Return Policy and Terms and Conditions",
              }
          },
          errorPlacement: function(error, element) {
                if (element.attr("name") == "conditions_check") {
                    error.appendTo("#errorToShow");
                } else {
                    error.insertAfter(element);
                }
            },
      submitHandler: function(form) {
          var content = $(form).serialize();
          $.ajax({
              type: "POST",
              url: base_path + "myaccount/api/orderProductReturn",
              dataType: "html",
              data: content,
              beforeSend: function() {
                  $(".page_loading").show();
              },
              success: function(data) {
                  $(".page_loading").hide();
                  data = data.split("`");
                  
                  if (data == 1) {
                         swal({
                                title: 'Your return request is being process. we will get back to you soon!',
                                text: "",
                                type: "success",
                                showCancelButton: false,
                                confirmButtonColor: "#84c341 ",
                                confirmButtonText: "Ok",
                                closeOnConfirm: true,
                                closeOnCancel: false
                            }, function(isConfirm) {
                                if (isConfirm) {
                                     location.reload()
                                }
                            });
                  } else {
                      setTimeout(function() {
                        new Noty({
                            text: '<strong>'+data+'</strong>!',
                            type: 'warning',
                            theme: 'relax',
                            layout: 'bottomCenter',
                            timeout: 3000
                        }).show();
                    }, 1500);
                  }

              }
          });
          return false;
      }
    });


</script>


 <?php if (isset($_GET['rs'])): ?>
    <script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Your return request is being process. we will get back to you soon!</strong>!',
            type: 'warning',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
    </script>
    <?php endif ?>

<!-- star rating script start -->
<script type="text/javascript">

    function editStarRatingReferesh() {
        alert("This function");
    }

    $(".edit_rating_refresh").on("click",function() {
        // alert("this function");
    });
    
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
            $(".rating_input").val(currentRating);
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

<?php if (isset($_GET['pr'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Review Submitted Successfully!</strong>',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>

<?php if (isset($_GET['vra'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Vendor Rating Submitted Successfully!</strong>',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>



