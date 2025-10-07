<?php require_once 'header.php'; ?>
<?php if($data['address']!="" ) : ?>
<?php if (isset($_SESSION['user_session_id'])): ?>
<section class="view_details">
  <div class="container-fluid">
      <div class="">
        <div class="col-lg-8 col-sm-12 p-0">
            <div class="d-flex justify-content-between align-items-center pt-3 mt-1">
                <h1 class="h6 pt-3 text-sm-start">Select Delivery Address</h1>
                    <!-- Button trigger modal --> 
                    <button type="button" class="btn btn-outline-primary btn-sm ps-2 add_new_address add_new_address_btn rounded-pill" data-bs-toggle="modal" data-bs-target="#exampleModal" >
                    <i class="fas fa-plus-circle me-2" ></i>Add New Address
                    </button>
                
                <!-- Modal -->
                <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="">            
                        <div class="d-sm-flex">                      
                            <h1 class="h6 text-sm-start w-100">Contact Details</h1>
                        </div>
                            <form id="editCartShippingAddress" method="POST" action="#" enctype="multipart/form-data">

                        </form>
                        </div>
                    </div>
                    </div>
                </div>
                </div>                      
            </div>
        </div>
        <div class="row mt-4 g-4 mb-5">
            <div class="col-lg-8 col-sm-12">
                <div class="rounded-3 shadow p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h6 pt-3 text-sm-start text-muted">Default Address</h3>
                    <?php if ($data[ 'count'][ 'tots']==0): ?>
                    <p class="error">Please Add Shipping Address</p>
                    <?php endif ?>
                    <p class="error" id="show_delivery_error"></p>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add New Address</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <form id="addCartShippingAddress" method="POST" action="#" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $_SESSION['new_shipping_address_key'] ?>" name="fkey" id="fkey">
                            <div class="">            
                            <div class="d-sm-flex">                      
                                <h1 class="h6 text-sm-start w-100">Contact Details</h1>
                            </div>
                            <div class="row gx-2 gy-2">
                                <div class="col-sm-6">
                                    <label class="form-label" for="co-fn">Name <span class="text-danger">*</span></label>
                                    <input class="form-control" name="name" id="name" type="text" placeholder="Name">
                                    <div class="invalid-feedback">Please enter your first name!</div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="co-ln">Phone number <span class="text-danger">*</span></label>
                                    <input class="form-control" name="mobile" id="mobile" type="text" placeholder="Number" maxlength="10">
                                    <div class="invalid-feedback">Please enter your phone number!</div>
                                </div>
                            </div>
                            <div class="d-sm-flex">
                                <h1 class="h6 pt-3 text-sm-start">Address</h1>
                            </div>
                            <div class="row gx-2 gy-2">
                                <div class="col-sm-12">
                                    <label class="form-label" for="co-address">Address <span class="text-danger">*</span></label>
                                    <input class="form-control" name="address" id="address" type="text" row="3" placeholder="Address (House No, Building, Street, Area)*">
                                    <div class="invalid-feedback">Please enter your address!</div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="co-fn">Landmark <span class="text-danger">*</span></label>
                                    <input class="form-control bg-image-none" id="landmark" name="landmark" type="text" placeholder="Landmark">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="co-fn">Select City <span class="text-danger">*</span></label>
                                    <select class="form-select" name="city" id="city" data-search="on">
                                        <option value=''>Select City</option>
                                        <?php echo $data['location']['group_layout']; ?>
                                    </select>
                                </div>

                                    <input type="hidden" name="location_area" id="location_area">
                                    <div class="col-sm-12 address_model chennai_drp Location_area_dropdown display_none">
                                        <div class="form-group ">
                                            <label class="form-label" for="co-fn">Select Area <span class="text-danger">*</span></label>
                                            <div class="form-control-wrap">
                                                <select class="form-select area_selected location_area_input_field" name="area" id="area_select" data-search="on" >
                                                    <option value=''>Select Area</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                    <label class="form-label" for="co-fn">Select State <span class="text-danger">*</span></label>
                                    <div class="form-control-wrap">
                                        <select class="form-select" name="state_id" id="state_id" data-search="on">
                                            <option value=''>Select State</option>
                                            <?php echo $data['state_list']; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="co-fn">Pincode</label>
                                    <input class="form-control bg-image-none address_from_pincode_field_bc_color" id="pincode" type="text" name="pincode" readonly="readonly"  placeholder="Pincode">
                                </div>      
                            </div>
                            <div class="d-sm-flex">
                                    <h1 class="h6 pt-3 text-sm-start">GST Details</h1>
                                </div>
                                <div class="row gx-2 gy-2">

                                    <div class="col-sm-6">
                                        <label class="form-label" for="co-fn">GST Name </label>
                                        <input class="form-control bg-image-none" id="gst_name" name="gst_name" type="text" placeholder="GST Name">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label" for="co-fn">GSTIN Number</label>
                                        <input class="form-control bg-image-none" type="text" name="gstin_number" id="gstin_number" placeholder="GSTIN Number">
                                    </div>
                                </div>
                            <div class="pt-2">
                                    <button class="btn btn-primary d-block w-100 rounded-pill" type="submit">Add Address</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="address-view">
                <?php echo $data['address'] ?>
                </div>
            </div>
            </div>
            <input type="hidden" id="vendorStatusCheck" value="<?php echo $data['vendor_status_check'] ?>">
            <div class="col-lg-4 col-sm-12">
                <div class="rounded-3 shadow p-4 mb-lg-0 mb-md-0 mb-5">
                    <div class="d-sm-flex">
                        <h1 class="h6 pt-3 text-sm-start">Price Details (<?php echo $data['header_cart'][ 'count']?> Item)</h1>
                    </div>
                    <ul class="list-unstyled fs-sm pb-2 border-bottom">
                        <li class="d-flex justify-content-between align-items-center"><span class="me-2">Subtotal:</span><span class="text-end">Rs.<?php echo number_format($data['info']['sub_total'],2) ?> </span></li>
                        <li class="d-flex justify-content-between align-items-center"><span class="me-2">Shipping:</span><span class="text-end"><?php echo $data['inr_format']['shipping_cost'] ?></span></li>
                        <li class="d-flex justify-content-between align-items-center"><span class="me-2">Taxes:</span><span class="text-end">Rs.<?php echo $data['inr_format']['total_tax'] ?></span></li>
                        <li class="d-flex justify-content-between align-items-center"><span class="me-2">Discount:</span><span class="text-end"><?php echo (($data['info']['coupon_value']>0)? "-" : "" );  ?><?php echo number_format($data['info']['coupon_value'],2) ?></span></li>
                    </ul>
                    <div class="d-flex justify-content-between align-items-center"><strong><span class="me-2">Total</span></strong><strong><span class="text-end">Rs.<?php echo number_format($data['info']['sub_total'] + $data['info']['shipping_cost']+ $data['info']['igst_amt']-$data['info']['coupon_value'],2)  ?></span></strong></div>                
                    <div class="pt-2">
                        <?php if (isset($_SESSION["user_session_id"])){ ?>
                            <?php if ($data['vendor_status_check']) { ?>
                            <div class="btn btn-primary d-block w-100 rounded-pill" data-address_status="<?php echo $data['check_address']['status'] ?>" data-product="<?php echo $data['check_address']['product'] ?>" data-vendor="<?php echo $data['check_address']['vendor'] ?>" data-location_group="<?php echo $data['ship_address_info']['city'] ?>" data-location_area="<?php echo $data['ship_address_info']['area_name'] ?>" id="placeOrderButton"  >Make Payment</div> 
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</section>
<?php endif ?>
<?php endif ?>
<section class="add_details">
    <div class="container-fluid">
        <div class="">
            <div class="row mt-4 mb-5">
              <?php if($data['address']=="" ) : ?>
                <form id="addCartShippingAddress" method="POST" action="#" enctype="multipart/form-data">
                <input type="hidden" value="<?php echo $_SESSION['new_shipping_address_key'] ?>" name="fkey" id="fkey">
                <div class="col-lg-8 col-sm-12 p-4 rounded-3 shadow">
                    <div class="d-sm-flex">                      
                      <h1 class="h6 pt-3 text-sm-start w-75">Contact Details</h1>
                    </div>
                    <div class="row gx-2 gy-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="co-fn">Name <span class="text-danger">*</span></label>
                            <input class="form-control" name="name" id="name" type="text" placeholder="Name">
                            <div class="invalid-feedback">Please enter your first name!</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="co-ln">Phone number <span class="text-danger">*</span></label>
                            <input class="form-control" name="mobile" id="mobile" type="text" placeholder="Number" maxlength="10">
                            <div class="invalid-feedback">Please enter your phone number!</div>
                        </div>
                    </div>
                    <div class="d-sm-flex">
                        <h1 class="h6 pt-3 text-sm-start">Address</h1>
                    </div>
                    <div class="row gx-2 gy-2">
                        <div class="col-sm-12">
                          <label class="form-label" for="co-address">Address <span class="text-danger">*</span></label>
                          <input class="form-control" name="address" id="address" type="text" row="3" placeholder="Address (House No, Building, Street, Area)*">
                          <div class="invalid-feedback">Please enter your address!</div>
                        </div>
                        <div class="col-sm-6">
                          <label class="form-label" for="co-fn">Landmark <span class="text-danger">*</span></label>
                          <input class="form-control bg-image-none" id="landmark" name="landmark" type="text" placeholder="Landmark">
                        </div>
                        <div class="col-sm-6 ">
                          <label class="form-label" for="co-fn">City</label>
                          <div class="form-control-wrap">
                              <select class="form-select" name="city" id="city" data-search="on">
                                 <option value=''>Select City</option>
                                 <?php echo $data['location']['group_layout']; ?>
                              </select>
                          </div>
                        </div>
                        <input type="hidden" name="location_area" id="location_area">
                        <div class="col-sm-12 address_model chennai_drp Location_area_dropdown display_none" >
                            <div class="form-group ">
                                <label class="form-label" for="co-fn">Select Area <span class="text-danger">*</span></label><br>
                                <div class="form-control-wrap">
                                    <select class="form-select area_selected location_area_input_field" name="area" id="area_select" data-search="on" >
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                          <label class="form-label" for="co-fn">Select State <span class="text-danger">*</span></label>
                          <div class="form-control-wrap">
                              <select class="form-select" name="state_id" id="state_id" data-search="on">
                                 <option value=''>Select State</option>
                                 <?php echo $data['state_list']; ?>
                              </select>
                          </div>      
                        </div>
                        <div class="col-sm-6">
                          <label class="form-label" for="co-fn">Pincode</label>
                          <input class="form-control bg-image-none address_from_pincode_field_bc_color" id="pincode" type="text" name="pincode" readonly="readonly"  placeholder="Pincode">
                        </div>                  
                    </div>
                    <div class="d-sm-flex">
                      <h1 class="h6 pt-3 text-sm-start">GST Details</h1>
                    </div>
                    <div class="row gx-2 gy-2">
                        <div class="col-sm-6">
                          <label class="form-label" for="city">GST Name</label>
                            <input type="text" name="gst_name" id="gst_name" class="form-control" placeholder="GST Name">
                        </div>
                        <div class="col-sm-6">
                           <label class="form-label" for="city">GSTIN Number</label>
                            <input type="text" name="gstin_number" id="gstin_number" class="form-control" placeholder="GSTIN Number">
                        </div>           
                        <div class="pt-2">
                          <button class="btn btn-primary d-block w-100 rounded-pill" type="submit">Add Address</button>
                        </div>
                    </div>
                </div>
                </form>
              <?php endif ?>

                
            </div>
        </div>
    </div>
</section>
<?php require_once 'footer.php'; ?>

<script>
    var modal = document.getElementById("shipping_address_popup");
    var btn = document.getElementById("myBtn");
    var btn1 = document.getElementById("myBtn1");
    var span = document.getElementsByClassName("modal-base-cancelIcon")[0];

    btn.onclick = function() {
        modal.style.display = "block";
    }
    btn1.onclick = function() {
        modal.style.display = "block";
    }
    span.onclick = function() {
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

<script>
    $(document).ready(function() {

        var vendor_check   = $("#vendorStatusCheck").val();

        if(vendor_check==0) {
            window.location = base_path + "cartdetails?vs=success";
        }

        return false;
    });
</script>




<script type="text/javascript">
    $(document).ready(function() {
        $('.inputV2-base-input').focus(function() {
            $(this).parent().addClass('inputV2-base-selected inputV2-base-top');
        });
        $('.inputV2-base-input').blur(function() {
            let elem = $(this);
            !elem.val().trim().length && elem.parent().removeClass('inputV2-base-selected inputV2-base-top');
        });
        let elems = document.querySelectorAll('.inputV2-base-input');
        let len = elems.length;
        for (let i = 0; i < len; i++) {
            let parentElem = $(elems[i]).parent();
            !elems[i].value.trim().length ? parentElem.removeClass('inputV2-base-selected inputV2-base-top') : parentElem.addClass('inputV2-base-selected inputV2-base-top');
        }

    });

     $("body").on("change", "#edit_city", function(){
          var id = $(this).val();
            $.ajax({
                type: "POST",
                url: base_path + "cartdetails/api/getLocationsForGroup",
                dataType: "html",
                data: {result : id},
                beforeSend: function () {
                },
                success: function (data) {
                    var parsed_array=JSON.parse(data);
                    $(".Edit_Location_area_dropdown").show();
                    $(".Edit_location_area_input_field").html(parsed_array);
                    var state_id = $("#edit_city").find(':selected').data('state_id');
                    $("#edit_state_id").css("pointer-events","none");
                    $("#edit_state_id").val(state_id);

                }
            });
        return false;
    });



    $("body").on("change", ".edit_area_selected", function(){
        $("#edit_location_area").val($(this).val());
        return false;
    });


    $("#city").change(function() {
        var id = $(this).val();
            $.ajax({
                type: "POST",
                url: base_path + "cartdetails/api/getLocationsForGroup",
                dataType: "html",
                data: {result : id},
                beforeSend: function () {
                },
                success: function (data) {
                    var parsed_array=JSON.parse(data);
                    $(".Location_area_dropdown").show();
                    $(".location_area_input_field").html(parsed_array);
                    $("#state_id").css("pointer-events","none");
                    var state_id = $("#city").find(':selected').data('state_id');
                    $("#state_id").val(state_id);

                }
            });
        return false;
        
    });

    $(".area_selected").change(function() {
        $("#location_area").val($(this).val());
        return false;
    });

    $("#area_select").change(function() {
        var pincode = $(this).find(':selected').data('pincode');
        $("#pincode").val(pincode);
    });
    $("#edit_area_select").change(function() {
        var pincode = $(this).find(':selected').data('pincode');
        $("#pincode").val(pincode);
    });

    $("body").on("change", "#edit_area_select", function() {
        var id = $(this).find(':selected').data('pincode');
        $("#edit_pincode").val(id);
    });

    $("#addCartShippingAddress").validate({
      rules: {
          name: {
              required: true
          },
          mobile: {
              required: true,
              digits: true,
              maxlength: 10,
              minlength: 10
          },
          landmark: {
              required: true,
          },
          address: {
              required: true,
          },
          city: {
              required: true,
          },
          state_id: {
              required: true,
          },
          pincode: {
              required: true,
          },
          area: {
                required: true,
          }
      },
      messages: {
         
          name: {
              required: "Name cannot be empty",
          },
          mobile: {
              required: "Mobile cannot be empty",
              maxlength: "Please enter valid 10 digit mobile number",
              minlength: "Please enter valid 10 digit mobile number"
          },
          landmark: {
              required: "Landmark cannot be empty",
          },
          address: {
              required: "Address cannot be empty",
          },
          city: {
              required: "City cannot be empty",
          },
          state_id: {
              required: "State cannot be empty",
          },
          pincode: {
              required: "Pincode cannot be empty",
          },
          area: {
              required: "Area cannot be empty",
          },
      },
      submitHandler: function(form) {
          var content = $(form).serialize();
          var type = $("#registerAt").val();
          $.ajax({
              type: "POST",
              url: base_path + "cartdetails/api/addCartShippingAddress",
              dataType: "html",
              data: content,
              beforeSend: function() {
                  $(".page_loading").show();
              },
              success: function(data) {
                  $(".page_loading").hide();
                  console.log(data);
                  data = data.split("`");
                  if (data == 1) {
                          window.location = base_path + "cartdetails/address?a=success";
                  } else {
                      $(".form-error").html(data);
                  }
              }
          });
          return false;
      }
  });



  $('.editAddress').click(function() {
      $('#editAddressModal').modal({ backdrop: 'static', keyboard: false });
      var id = $(this).data("option");
      $.ajax({
          type: "POST",
          url: base_path + "cartdetails/api/editAddress",
          dataType: "html",
          data: { result: id },
          beforeSend: function() {
              $(".page_loading").show();
          },
          success: function(data) {
              $(".page_loading").hide();
              $("#editCartShippingAddress").html(data);
              $('#editAddressModal').modal('show');
              $(function() {
                $(".inputV2-base-inputRow").addClass('inputV2-base-selected inputV2-base-top');
                $('.inputV2-base-inputRow').on('click', function() {
                    $('.inputV2-base-inputRow').removeClass('inputV2-base-selected inputV2-base-top');
                    $(".inputV2-base-inputRow").addClass('inputV2-base-selected inputV2-base-top');
                });
            });
          }
      })
      return false;
  });

  $('#placeOrderButton').click(function() {

      var address_status  = $(this).data("address_status");
      var product         = $(this).data("product");
      var vendor          = $(this).data("vendor");
      var location        = $(this).data("location_group");
      var area            = $(this).data("location_area");


      if(address_status==0) {

        data = 'Delivery for '+ product +' to '+  area +' location is currently not available from '+ vendor +'!';

        swal(data,"", "warning");

      } else if(address_status=="area_not_available") { 
         setTimeout(function() {
            new Noty({
                text: '<strong>Delivery to '+ area +' location is currently not available for this order</strong>!',
                type: 'warning',
                theme: 'relax',
                layout: 'bottomCenter',
                timeout: 3000
            }).show();
        }, 300);
      } else {
        if(address_status==1)
        { 
          window.location = base_path + "cartdetails/orderconfirmation";
        } else if(address_status==2) {

            data = 'Delivery for '+ product +' to '+  area +' location is currently not available from '+ vendor +'!';
            swal(data,"", "warning");
        } else if (address_status==3) {
           setTimeout(function() {
          new Noty({
              text: '<strong>Please Select Shiping Address !</strong>',
              type: 'warning',
              theme: 'relax',
              layout: 'bottomCenter',
              timeout: 3000
              }).show();
          }, 1500);
        }
      }

      return false;
  });


   // Make User Address default

  $('.make_default_shipping').click(function() {
      var id = $(this).data("id");
      var option = $(this).data("option");
      var delivery_type = $("input[name='shipping_type']:checked").val();
      $.ajax({
          type: "POST",
          url: base_path + "cartdetails/api/makeDefaultShippingAddress",
          dataType: "html",
          data: { result: id, shipping_type: delivery_type },
          beforeSend: function() {
              $(".page_loading").show();
          },
          success: function(data) {
              $(".page_loading").hide();
              if (data == 1) {
                  location.reload();
              } else {
                  $(".address_error").html(data);
              }
          }
      })
      return false;
  });


$("#editCartShippingAddress").validate({
      rules: {
          name: {
              required: true
          },
          mobile: {
              required: true,
              digits: true,
              maxlength: 10,
              minlength: 10
          },
          landmark: {
              required: true,
          },
          address: {
              required: true,
          },
          city: {
              required: true,
          },
          state_id: {
              required: true,
          },
          pincode: {
              required: true,
          },
          area: {
                required: true,
          }
      },
      messages: {
         
          name: {
              required: "Name cannot be empty",
          },
          mobile: {
              required: "Mobile cannot be empty",
              maxlength: "Please enter valid 10 digit mobile number",
              minlength: "Please enter valid 10 digit mobile number"
          },
          landmark: {
              required: "Landmark cannot be empty",
          },
          address: {
              required: "Address cannot be empty",
          },
          city: {
              required: "City cannot be empty",
          },
          state_id: {
              required: "State cannot be empty",
          },
          pincode: {
              required: "Pincode cannot be empty",
          },
          area: {
              required: "Area cannot be empty",
          },
      },
      submitHandler: function(form) {
          var content = $(form).serialize();
          var type = $("#registerAt").val();
          $.ajax({
              type: "POST",
              url: base_path + "cartdetails/api/editCartShippingAddress",
              dataType: "html",
              data: content,
              beforeSend: function() {
                  $(".page_loading").show();
              },
              success: function(data) {
                  $(".page_loading").hide();
                  console.log(data);
                  data = data.split("`");
                  if (data == 1) {
                          window.location = base_path + "cartdetails/address?e=success";
                  } else {
                      $(".form-error").html(data);
                  }
              }
          });
          return false;
      }
  });

  //Remove Address

    $(".removeShippingAddress").click(function() {

      var id = $(this).data("id");

      $.ajax({
          type: "POST",
          url: base_path + "cartdetails/api/removeShippingAddress",
          dataType: "html",
          data: { result: id },
          beforeSend: function() {
              $(".page_loading").show();
          },
           success: function(data) {
              $(".page_loading").hide();
              data = data.split("`");
              if (data[0] == 0) {
                  $(".page_loading").hide();
                  window.location = base_path + "cartdetails/address?ad=success";
              } else if(data[0] == 1) {
                 setTimeout(function() {
                            new Noty({
                                text: '<strong>' + data[1] + '</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
              } else if(data[0] == 2)  {
                  swal(data[1],"", "warning");
              }
          }
      })
      return false;
  });

    $("#closeaddaddress").click(function() {
      
      $('#shipping_address_popup').find('form')[0].reset();
      $("#shipping_address_popup").hide();

      
  });

</script>

<?php if (isset($_GET['ad'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: 'Address has been removed Successfully!',
        type: 'success',
        theme: 'relax',
        layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>

<?php if (isset($_GET['ul'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: '<strong>Delivery to <?php echo $data['ship_address_info']['city']; ?> location is currently not available for this order</strong>',
        type: 'warning',
        theme: 'relax',
        layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>

<?php if (isset($_GET['ua'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: '<strong>Delivery to <?php echo $data['ship_address_info']['area_name']; ?> location is currently not available for this order</strong>',
        type: 'warning',
        theme: 'relax',
        layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>

<?php if (isset($_GET['sl'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: '<strong>Please Select Shiping Address !</strong>',
        type: 'warning',
        theme: 'relax',
        layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>
<?php if (isset($_GET[ 'a'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: 'Address Added Successfully !!</strong>!',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1000);
    history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>

<?php if (isset($_GET[ 'e'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: 'Address Updated Successfully !!</strong>!',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1000);
    history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>