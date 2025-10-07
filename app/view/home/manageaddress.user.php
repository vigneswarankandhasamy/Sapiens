<?php require_once 'includes/top.php'; ?>
<!-- <div class="profile-banner otherpage-banner m-0">
    <img src="<?php echo $data['page_banner']!="" ? SRCIMG.$data['page_banner']['file_name'] : IMGPATH."profile-banner.jpg" ?>" alt="image" class="common-banner">
    <div class="other-banner-title">
        <p>Manage Address</p>
    </div>   
</div> -->  
<div class="breadcrumbs_area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <!-- this menu items get in top.php (My Account Breadcrum Menus) -->
                    <?php echo $myaccount_breadcurm; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contact_area manageaddress">
    <div class="container-lg">
        <div class="row">
            <!--product items-->
            <div class="col-md-12 col-xs-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="list_wrap">
                              <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="address_item add">
                                        <div class="buttons"><a href="javascript:void();" data-bs-toggle="modal" data-bs-target="#add_address"><i class="fas fa-plus"></i></a></div>
                                        <h5>Add new Address</h5>
                                    </div>
                                </div>
                                 <?php echo $data['address'] ?> 
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>


<div class="modal fade address_wrap_modal" id="add_address" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ">Add Shipping Address </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="address-form-error red"></div>
            <form id="addCartShippingAddress" method="POST" action="#" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- <h5 class="shipping_warp"></h5> -->
                    <input type="hidden" value="<?php echo $_SESSION['new_shipping_address_key'] ?>" name="fkey" id="fkey">
                    <input type="hidden" value="address" name="registerAt" id="registerAt">
                    <div class="row">
                      <h4 class="form-label modal-title"> Address Details
                                        </h4>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group address_model" >
                                <label for="name">Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="name" placeholder="Name" class="form-control" >
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group address_model" >
                                <label for="mobile">Mobile <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="mobile" id="mobile" placeholder="Mobile Number" class="form-control" >
                            </div> 
                        </div>
                    </div>
                    <div class="form-group address_model" >
                        <label for="address">Address <span class="text-danger">*</span>
                        </label>
                        <textarea type="text" name="address" id="address" class="form-control" placeholder="Address"></textarea>
                    </div>
                     <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group address_model" >
                                <label for="landmark">Landmark <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="landmark" id="landmark" class="form-control" placeholder="Landmark" >
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group address_model">
                                <label for="city">Select City <span class="text-danger">*</span></label><br>
                                <div class="form-control-wrap">
                                    <select class="form-select" name="city" id="city" data-search="on" >
                                        <option value=''>Select City</option>
                                       <?php echo $data['location_address']['group_layout']; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="location_area" id="location_area">
                        <div class="col-md-12">
                            <div class="form-group address_model Location_area_dropdown display_none ">
                                <label for="city">Select Area <span class="text-danger">*</span></label><br>
                                <div class="form-control-wrap">
                                    <select class="form-select area_selected location_area_dropdown" name="area" id="area_selected" data-search="on" >
                                        <option value=''>Select Area</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group address_model">
                                <label for="city">Select State <span class="text-danger">*</span></label><br>
                                <div class="form-control-wrap">
                                    <select class="form-select" name="state_id" id="state_id" data-search="on" >
                                        <option value=''>Select State</option>
                                       <?php echo $data['state_list']; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group address_model" >
                                <label for="picode">Pincode <span class="text-danger">*</span>
                                </label>
                                <br>
                                <input type="text" name="pincode" id="pincode" placeholder="Pincode" class="form-control address_from_pincode_field_bc_color" readonly="readonly"  >
                            </div>
                        </div>

                        <h4 class="form-label modal-title gst_details_margin"> GST Details</h4>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group address_model" >
                                <label for="city">GST Name
                                </label>
                                <br>
                                <input type="text" name="gst_name" id="gst_name" class="form-control" placeholder="GST Name" >
                            </div>
                        </div>
                         <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group address_model" >
                                <label for="city">GSTIN Number 
                                </label>
                                <br>
                                <input type="text" name="gstin_number" id="gstin_number" class="form-control" placeholder="GSTIN Number" >
                            </div>
                        </div>
                        <button type="submit" class="btn btn-hero mx-auto w-50 mt-3 rounded-pill">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade editAddressModal address_wrap_modal"  role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ">Edit Shipping Address </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="address-form-error red"></div>
            <form method="POST" accept-charset="utf-8" id="editCartShippingAddress">
                  
            </form>
        </div>
    </div>
</div>
<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">


    $("body").on("change", "#edit_city", function(){
        var id = $(this).val();
            $.ajax({
                type: "POST",
                url: base_path + "myaccount/api/getLocationsForGroup",
                dataType: "html",
                data: {result : id},
                beforeSend: function () {
                },
                success: function (data) {
                    var parsed_array=JSON.parse(data);
                    $(".Edit_Location_area_dropdown").removeClass("display_none");
                    $(".Edit_location_area_dropdown").html(parsed_array);
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
                url: base_path + "myaccount/api/getLocationsForGroup",
                dataType: "html",
                data: {result : id},
                beforeSend: function () {
                },
                success: function (data) {
                    var parsed_array=JSON.parse(data);
                    $(".Location_area_dropdown").removeClass("display_none");
                    $(".location_area_dropdown").html(parsed_array);
                    $("#state_id").css("pointer-events","none");
                    var state_id = $("#city").find(':selected').data('state_id');
                    $("#state_id").val(state_id);
                    

                }
            });
        
    });
    $("body").on("change", "#edit_area_select", function() {
        var id = $(this).find(':selected').data('pincode');
        $("#edit_pincode").val(id);

    });
    $("#area_selected").change(function() {
        var pincode = $(this).find(':selected').data('pincode');
        $("#pincode").val(pincode);
    });


    $(".area_selected").change(function() {
        $("#location_area").val($(this).val());
        return false;
    });

  //add address
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
              }
          },
      submitHandler: function(form) {
          var content = $(form).serialize();
          var type = $("#registerAt").val();
          $.ajax({
              type: "POST",
              url: base_path + "myaccount/api/addCartShippingAddress",
              dataType: "html",
              data: content,
              beforeSend: function() {
                  $(".page_loading").show();
              },
              success: function(data) {
                console.log(data);
                  $(".page_loading").hide();
                  data = data.split("`");
                  if (data == 1) {
                      if (type == "cart") {
                          window.location = base_path + "cart/address?a=success";
                      } else {
                          window.location = base_path + "myaccount/manageaddress?a=success";
                      }
                  } else {
                      $(".address-form-error").html(data);
                  }
              }
          });
          return false;
      }
    });

  // edit Address model open

     $('.editAddressPopup').click(function() {
          $('.editAddressModal').modal({ backdrop: 'static', keyboard: false });
          var id = $(this).data("option");
          $.ajax({
              type: "POST",
              url: base_path + "myaccount/api/editAddressPopup",
              dataType: "html",
              data: { result: id },
              beforeSend: function() {
                  $(".page_loading").show();
              },
              success: function(data) {
                  $(".page_loading").hide();
                  $("#editCartShippingAddress").html(data);
                  $('.editAddressModal').modal('show');
              }
          })
          return false;
      });

 // edit address insert
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
          state: {
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
          state: {
              required: "State cannot be empty",
          },
          area: {
              required: "Area cannot be empty",
          },
          pincode: {
              required: "Pincode cannot be empty",
          }
      },  
      submitHandler: function(form) {
          var content = $(form).serialize();
          var type = $("#registerAt").val();
          $.ajax({
              type: "POST",
              url: base_path + "myaccount/api/editCartShippingAddress",
              dataType: "html",
              data: content,
              beforeSend: function() {
                  $(".page_loading").show();
              },
              success: function(data) {
                  $(".page_loading").hide();
                  data = data.split("`");
                  if (data == 1) {
                    window.location = base_path + "myaccount/manageaddress?e=success";
                  } else {
                      $(".address-form-error").html(data);
                  }
              }
          });
          return false;
      }
  });

 // Make User Address

  $('.make_default').click(function() {
      var id = $(this).data("id");
      $.ajax({
          type: "POST",
          url: base_path + "myaccount/api/make_default",
          dataType: "html",
          data: { result: id },
          beforeSend: function() {
              $(".page_loading").show();
          },
          success: function(data) {
              $(".page_loading").hide();
              if (data == 1) {
                  location.reload();
              } else {
                  $(".form-error").html(data);
              }
          }
      })
      return false;
  });

//delete address
 $('.delete_address').click(function() {
      var id = $(this).data("id");
       $.ajax({
          type: "POST",
          url: base_path + "myaccount/api/deleteAddress",
          dataType: "html",
          data: { result: id },
          beforeSend: function() {
              $(".page_loading").show();
          },
          success: function(data) {
            console.log(data);
              $(".page_loading").hide();
              data = data.split("`");
              if (data[0] == 0) {
                  $(".page_loading").hide();
                  window.location = base_path + "myaccount/manageaddress?ad=success";
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
      });
      return false;
  });


</script>


<?php if (isset($_GET['a'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: 'Address Added Successfully!',
        type: 'success',
        theme: 'relax',
        layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>

<?php if (isset($_GET['e'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: 'Address Updated Successfully!',
        type: 'success',
        theme: 'relax',
        layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>

<?php if (isset($_GET['ad'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    new Noty({
        text: 'Address has been deleted Successfully!',
        type: 'success',
        theme: 'relax',
        layout: 'bottomCenter',
        timeout: 3000
    }).show();
}, 1000);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>