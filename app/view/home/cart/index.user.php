<?php require_once 'header.php'; ?>
<section class="cart_page">
    <div class="container-fluid">
        <div class="row g-4 mt-4 mb-5 pb-lg-0 pb-md-0 pb-5">
            <div class="col-lg-8 col-sm-12">
                <div class="p-4 rounded-3 shadow">
                    <div class="d-flex justify-content-between align-items-center">                      
                        <h1 class="h6 text-sm-start">My Shopping Bag ( <?php echo $data['header_cart']['count']?> Item)</h1>
                        <h5 class="h6 text-end pt-2"><span>Total:</span><span> <?php echo 'Rs '.number_format($data['info']['sub_total']+$data['info']['total_tax'],2) ?></span></h5>
                    </div>
                    <?php echo $data['products'] ?>
                <!-- <div class="shopping_list_items my-2">
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <div class="d-block d-sm-flex align-items-center text-center text-sm-start">
                        <a class="d-inline-block flex-shrink-0 mx-auto me-sm-4" href="#"><img src="https://cartzilla.createx.studio/img/shop/cart/01.jpg" width="160" alt="Product"></a>
                        <div class="pt-2">
                            <h3 class="h6 mb-2">Women Colorblock Sneakers</h3>
                            <div class="fs-sm"><span class="text-muted me-2">Size:</span>8.5</div>
                            <div class="fs-sm"><span class="text-muted me-2">Color:</span>White &amp; Blue</div>
                            <div class="fs-lg text-accent pt-2">$154.<small>00</small></div>
                        </div> 
                        </div>
                        <div class="pt-2 pt-sm-0 ps-sm-3 mx-auto mx-sm-0 text-center text-sm-start" style="max-width: 9rem;">
                        <label class="form-label" for="quantity1">Quantity</label>
                        <input class="form-control" type="number" id="quantity1" min="1" value="1">
                        </div>
                    </div>     
                    <div class="justify-content-start align-items-center d-sm-flex pb-3 border-bottom">
                    <button class="btn btn-link px-0 text-danger" type="button"><i class="far fa-times-circle me-2"></i><span class="fs-sm">Remove</span></button>
                    <span class="pe-2 ps-2 text-muted">|</span>                        
                    <div class="wish_tag my-3 pe-2"><a href=""><span><i class="far fa-heart"></i> Move to wishlist</span></a></div>
                    </div>
                </div>                    
                <div class="shopping_list_items my-2">
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <div class="d-block d-sm-flex align-items-center text-center text-sm-start"><a class="d-inline-block flex-shrink-0 mx-auto me-sm-4" href="#"><img src="https://cartzilla.createx.studio/img/shop/cart/01.jpg" width="160" alt="Product"></a>
                        <div class="pt-2">
                            <h3 class="h6 mb-2">Women Colorblock Sneakers</h3>
                            <div class="fs-sm"><span class="text-muted me-2">Size:</span>8.5</div>
                            <div class="fs-sm"><span class="text-muted me-2">Color:</span>White &amp; Blue</div>
                            <div class="fs-lg text-accent pt-2">$154.<small>00</small></div>
                        </div>
                        </div>
                        <div class="pt-2 pt-sm-0 ps-sm-3 mx-auto mx-sm-0 text-center text-sm-start" style="max-width: 9rem;">
                        <label class="form-label" for="quantity1">Quantity</label>
                        <input class="form-control" type="number" id="quantity1" min="1" value="1">
                        </div>
                    </div>     
                    <div class="justify-content-start align-items-center d-sm-flex pb-3">
                    <button class="btn btn-link px-0 text-danger" type="button"><i class="far fa-times-circle me-2"></i><span class="fs-sm">Remove</span></button>
                    <span class="pe-2 ps-2 text-muted">|</span>
                    <div class="wish_tag my-3 wish_selected"><a href="" class="text-primary"><span class="text-primary"><i class="fas fa-heart text-primary"></i> Move to wishlist</span></a></div>                                    
                    </div>
                </div> -->
                
                </div>
            </div>
            
            <div class="col-lg-4 col-sm-12">
                <?php if ($data['info']['coupon_status']==1) { ?>
                <div class="form-error"></div>
                <div class="rounded-3 shadow p-3 my-3 mt-0 applied_coupon">
                <p class="mb-0"> <span><?php echo $data['info']['coupon_code'] ?></span> Coupon Added Successfully</p>
                <div class="remove">
                    <a href="#" class="removeCoupon" data-bs-toggle="tooltip" title="Remove Coupon"><i class="fas fa-times-circle"></i></a>
                </div>
            </div>
            <input type="hidden" name="" id="myBtn1">
            <?php }else{ ?>
                <div class="rounded-3 shadow p-3 my-3 mt-0">
                <h2 class="h6 pb-2">Do you have a coupon code?</h2>
                <div class="d-flex">
                    <!-- <input class="form-control bg-image-none me-3 coupon_id" type="text" placeholder="Coupon code"> -->
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-outline-primary apply_id btn-sm ps-2 rounded-pill" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Apply Coupon
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Apply Coupon</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="">
                            <div class="row gx-2 gy-2 pb-3 bg-light border-bottom">
                                <form id="couponCodeCheck" name="couponCodeCheck" action="#" method="post">
                                <div class="col-sm-12 d-flex">
                                    <input class="form-control w-75" type="text" id="coupon_code" name="coupon_code" placeholder="Enter Coupon Code">
                                    <button type="submit" id="applyCoupon" class="btn btn-primary btn-sm ps-2 ms-2 w-25 rounded-pill">
                                        Apply
                                    </button>                                        
                                </div>
                                <div>
                                    <label for="coupon_code" class="error"></label>
                                </div>
                                    </form>
                            </div>
                            <div class="coupon_error">
                                <p id="cart_message"></p>
                            </div>

                            <?php echo $data['coupons'] ?>


                            <!--  <div class="row gx-4 gy-4 border-bottom mt-2">
                                <div class="col-sm-9">
                                    <p class="offer-code">CODE: CBE50</p>
                                    <label class="form-label text-muted">Expires on : 28 Feb 2021</label>
                                </div>                  
                                <div class="col-sm-3">
                                    <button class="btn btn-primary d-block w-100" type="submit">Apply</button>
                                </div>
                            </div>
                            <div class="row gx-4 gy-4 mt-2">
                                <div class="col-sm-9">
                                    <p class="offer-code">CODE: CBE100</p>
                                    <label class="form-label text-muted">Expires on : 28 Feb 2021</label>
                                </div>                  
                                <div class="col-sm-3">
                                    <button class="btn btn-primary d-block w-100" type="submit">Apply</button>
                                </div>
                            </div> -->

                            </div>
                        </div>
                        </div>
                    </div>
                    </div>                       
                </div>
                </div>
                <?php } ?>
                <div class="rounded-3 shadow p-3 mb-lg-0 mb-md-0 mb-5">
                <ul class="list-unstyled fs-sm pb-2 border-bottom">
                    <li class="d-flex justify-content-between align-items-center"><span class="me-2">Subtotal:</span><span class="text-end">Rs. <?php echo number_format($data['info']['sub_total'],2) ?></span></li>
                    <li class="d-flex justify-content-between align-items-center"><span class="me-2">Shipping:</span><span class="text-end"><?php echo $data['info']['shipping_cost'] ?></span></li>
                    <li class="d-flex justify-content-between align-items-center"><span class="me-2">Taxes:</span><span class="text-end">Rs.<?php  echo number_format($data['info']['total_tax'],2)  ?></span></li>
                    <li class="d-flex justify-content-between align-items-center"><span class="me-2">Discount:</span><span class="text-end"><?php echo (($data['info']['coupon_value']>0)? "-" : "" );  ?><?php echo number_format($data['info']['coupon_value'],2) ?></span></li>
                </ul>
                <div class="d-flex justify-content-between align-items-center"><strong><span class="me-2">Total</span></strong><strong><span class="text-end">Rs.<?php echo number_format($data['info']['sub_total'] + $data['info']['shipping_cost']+ $data['info']['igst_amt']-$data['info']['coupon_value'],2)  ?></span></strong></div>

                <?php if (isset($_SESSION['user_session_id'])) { ?>
                <?php if ($data['vendor_status_check']) { ?>
                <div class="pt-2">
                    <button class="btn btn-primary d-block w-100 rounded-pill" onclick="location='<?php echo BASEPATH ?>cartdetails/address'" type="button">Place Order</button>
                </div>
                <?php } ?>
                <?php }else{ ?>
                <div class="pt-2">
                    <button class="btn btn-primary d-block w-100 rounded-pill" onclick="location='<?php echo BASEPATH ?>login'" type="button">Login to Proceed</button>
                </div>
                <?php } ?>

                </div>
            </div>

        </div>
    </div>
</section>
<?php require_once 'footer.php'; ?>



<script>

  function delay(callback, ms) {
  var timer = 0;
  return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
  }



  $('.orderQuantity').keyup(delay(function (e) {

      var id        = $(this).data("token");
      var max_val   = $(this).data("max_order_qty");
      var min_val   = $(this).data("min_order_qty");
      var old_qty   = $(this).data("old_qty");
      var order_qty = parseInt($(this).val()) ;

      if(!isNaN(order_qty)) {
        if(order_qty <= max_val) {
          if(min_val > order_qty) {
              swal("Minimum Quantity Should Be "+ min_val ,"", "warning");
              $(this).val(old_qty);
          } else {

             $.ajax({
                type: "POST",
                url: base_path + "cartdetails/api/updateCartQuantity",
                dataType: "html",
                data: { result: id, qty: order_qty },
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();

                    if (data == 1) {
                        location.reload();
                    } else {
                        swal(data,"", "warning");
                    }
                }
            });

          }
        } else {
            swal("Maximum Quantity Should Be "+ max_val ,"", "warning");
            $(this).val(old_qty);
        } 
     } else {
        $(this).val(old_qty);
     }


     return false;
  }, 750));


  // This button will increment the value
  $('.qtyplus').click(function(e) {
      // Stop acting like a button
      e.preventDefault();
      // var qty = $("#botMinimumQty").val();
      // Get the field name
      fieldName = $(this).attr('field');
      // Get its current value
      var currentVal = parseInt($('input[name=' + fieldName + ']').val());
      // If is not undefined
      if (!isNaN(currentVal)) {
          // Increment
          var value = currentVal + 1;
          $('input[name=' + fieldName + ']').val(currentVal + 1);
          $("#qty").val(value);
      } else {
          // Otherwise put a 0 there
          $('input[name=' + fieldName + ']').val('1');
          $("#qty").val(1);
      }
  });

  // This button will decrement the value till 0
  $(".qtyminus").click(function(e) {
      // Stop acting like a button
      e.preventDefault();
      // var qty = $("#botMinimumQty").val();
      // Get the field name
      fieldName = $(this).attr('field');
      // Get its current value
      var currentVal = parseInt($('input[name=' + fieldName + ']').val());
      // If it isn't undefined or its greater than 0
      if (!isNaN(currentVal) && currentVal > 1) {
          // Decrement one
          var value = currentVal - 1;
          $('input[name=' + fieldName + ']').val(currentVal - 1);
          $("#qty").val(value);
      } else {
          // Otherwise put a 0 there
          $('input[name=' + fieldName + ']').val('1');
          $("#qty").val(1);
      }
  });

  // Coupon Code Check

  $("#couponCodeCheck").validate({
      rules: {
          coupon_code: {
              required: true
          },
      },
      messages: {
          coupon_code: {
              required: "Please Enter Coupon Code",
          },
      },
      submitHandler: function(form) {
          var content = $(form).serialize();
          $.ajax({
              type: "POST",
              url: base_path + "cartdetails/api/couponCodeCheck",
              dataType: "html",
              data: content,
              beforeSend: function() {
                  $(".cart_loader").show();
              },
              success: function(data) {
                  var data = data.split("`");
                  if (data[0] == 1) {
                      $(".cart_loader").hide();
                      window.location = base_path + "cartdetails?c=success";
                  } else {
                      $(".cart_loader").hide();
                      $("#cart_message").html(data[0]);
                  }
              }
          });
          return false;
      }
  });


$(".couponCodeCheckValid").click(function() {
        var couponCode = $(this).data("coupon");
        $.ajax({
            type: "POST",
            url: base_path + "cartdetails/api/couponCodeCheckValid",
            dataType: "html",
            data: { result: couponCode},
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                $('#exampleModal').modal().hide();
                if (data == 1) {
                    window.location = base_path + "cartdetails?c=success";
                } else {
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
                            location.reload();
                        } else {
                            swal("Cancelled", "Offer is not Removed Yet", "error");
                        }
                    });
                }
            }
        });
        return false;
  });


  

  // Remove Coupon From Cart 

  $('.removeCoupon').click(function() {
      var token = 'delete';
      swal({
          title: "Are sure you to remove this Offer?",
          text: "Once removed the offer value not applicable for your order  !",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#84c341 ",
          confirmButtonText: "Yes, I am Sure !",
          closeOnConfirm: false,
          closeOnCancel: false
      }, function(isConfirm) {
          if (isConfirm) {
              $.ajax({
                  type: "POST",
                  url: base_path + "cartdetails/api/removeCoupon",
                  dataType: "html",
                  data: { result: token },
                  beforeSend: function() {
                      $(".page_loading").show();
                  },
                  success: function(data) {
                      swal.close();
                      $(".page_loading").hide();
                      if (data == 1) {
                          window.location = base_path + "cartdetails?r=success";
                      } else {
                          $(".form-error").html(data);
                      }
                  }
              })
          } else {
              swal("Cancelled", "Offer is not Removed Yet", "error");
          }
      });

      return false;
  });


  // Cart Increment

  $(document).on('click', '.increment', function() {
      var option = $(this).data("option");
      var id = $(this).data("token");
      var amount = $("#final_price_print_" + option).val();
      var current = $("#number_" + option).val();
      var inc     = $("#qtyincrement_"+option).val();
      var new_qty = parseFloat(current) + parseFloat(inc);
      $.ajax({
          type: "POST",
          url: base_path + "cartdetails/api/updateCartQuantity",
          dataType: "html",
          data: { result: id, qty: new_qty },
          beforeSend: function() {
              $(".page_loading").show();
          },
          success: function(data) {
              $(".page_loading").hide();
              if (data == 1) {
                  location.reload();
              } else {
                  swal(data,"", "warning");
              }
          }
      });
  });

  // Cart remove Qty Update

  $(".decrement").on('click', function() {
      var option = $(this).data("option");
      var id = $(this).data("token");
      var amount = $("#final_price_print_" + option).val();
      var current = $("#number_" + option).val();
      var inc     = $("#qtyincrement_"+option).val();
      if (current >= 1) {
          var new_qty = parseFloat(current) - parseFloat(inc);
      } else {
          var new_qty = 1;
      }
      $.ajax({
          type: "POST",
          url: base_path + "cartdetails/api/decrementCartQuantity",
          dataType: "html",
          data: { result: id, qty: new_qty },
          beforeSend: function() {
              $(".page_loading").show();
          },
          success: function(data) {
              $(".page_loading").hide();

              if (data == 1) {
                  location.reload();
              } else {
                  swal(data,"", "warning");
              }
          }
      });
  });

  
  $(".enterQuantity").on('change', function() {
      var option = $(this).data("option");
      var id = $(this).data("token");
      var amount = $("#final_price_print_" + option).val();
      var current = $("#number_" + option).val();
      if (current == "") {
          var new_qty = 1;
      } else {
          var new_qty = Number(current);
      }
      $.ajax({
          type: "POST",
          url: base_path + "cartdetails/api/cartQuantityValue",
          dataType: "html",
          data: { result: id, qty: new_qty },
          beforeSend: function() {
              $(".page_loading").show();
          },
          success: function(data) {
              $(".page_loading").hide();
              if (data == 1) {
                  location.reload();
              } else {
                  var final_qty = Number(new_qty) - 1;
                  $("#number_" + option).val(final_qty);
                  $(".form-error-cart").html(data);
                  $(".form-error-cart").show();
              }
          }
      });
  });

  // Remove From Cart

  $(document).on('click', '.cartItemRemove', function() {
      var id = $(this).data("id");
      $.ajax({
          type: "POST",
          url: base_path + "cartdetails/api/cartItemRemove",
          dataType: "html",
          data: { result: id },
          beforeSend: function() {
              $(".page_loading").show();
          },
          success: function(data) { 
              $(".page_loading").hide();

              if (data == 1) {
                  window.location = base_path + "cartdetails";
              } else {
                  $(".form-error-cart").html(data);
              }
          }
      });
      return false;
  });

   $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null) {
         return null;
        }
        return decodeURI(results[1]) || 0;
    }


  
  $(document).on('click', '.addToFavourite', function() {
    var id          = $(this).data("id");
        var vendor_id   = $(this).data("vendor_id");
        var variant_id  = $(this).data("variant_id");

        $.ajax({
                type: "POST",
                url: base_path + "product/api/addToWishList",
                dataType: "html",
                data: { 
                        product_id  : id,
                        vendor_id   : vendor_id,
                        variant_id  : variant_id
                      },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                data = data.split("`");
                if (data[0] == 1) {
                    var alter_url = "cartdetails?ws=success";
                    window.location = base_path + alter_url;
                } else {
                    var alter_url = "cartdetails?wr=success";
                    window.location = base_path + alter_url;
                }
            }
    })
    return false;
});

  // Remove From Cart 

$('.removeMyFav').click(function () {
    var id = $(this).data("option");
    var option = $(this).data("plus");
    $.ajax({
        type: "POST",
        url: base_path + "cartdetails/api/removeMyFav",
        dataType: "html",
        data: { result: id },
        beforeSend: function () {
            $(".page_loading").show();
        },
        success: function (data) {
            $(".page_loading").hide();
            if (data == 1) {
                location.reload();
                $("#menuRemove_" + option).remove();
            } else {
                $(".form-error").html(data);
            }
        }
    })
    return false;
});
</script>

<?php if (isset($_GET['c'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: 'Coupon Added Successfully!',
        type: 'success',
        theme: 'relax',
         layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>
<?php if (isset($_GET['r'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: 'Coupon Removed Successfully!',
        type: 'success',
        theme: 'relax',
         layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>

<?php if (isset($_GET['ws'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: 'Product has been added to the wishlist!',
        type: 'success',
        theme: 'relax',
         layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>
<?php if (isset($_GET['wr'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: 'Product has been Removed from wishlist!',
        type: 'success',
        theme: 'relax',
         layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>
<?php if (isset($_GET['vs'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
  swal({
          title: "Some product vendor in your cart not available at this moment Please remove or try to other vendor!",
          text: "",
          type: "warning",
          showCancelButton: false,
          confirmButtonColor: "#84c341 ",
          confirmButtonText: "Ok",
          closeOnConfirm: false,
          closeOnCancel: false
      });
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>