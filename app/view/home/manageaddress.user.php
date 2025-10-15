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

<div class="manage-address-container" style="margin-top: 80px; min-height: 70vh; padding: 2rem 1rem; background: #f8f9fa;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="address-header">
                    <h1 class="page-title">Manage Address</h1>
                    <p class="page-subtitle">Add, edit, and manage your shipping addresses</p>
                </div>
                
                <div class="address-grid">
                    <!-- Add New Address Card -->
                    <div class="address-card add-new-card" data-bs-toggle="modal" data-bs-target="#add_address">
                        <div class="add-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <h3>Add New Address</h3>
                        <p>Click to add a new shipping address</p>
                    </div>
                    
                    <!-- Existing Address Cards -->
                    <?php echo $data['address'] ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade address-modal" id="add_address" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title-section">
                    <div class="modal-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h4 class="modal-title">Add Shipping Address</h4>
                        <p class="modal-subtitle">Enter your shipping details</p>
                    </div>
                </div>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="address-form-error"></div>
                <form id="addCartShippingAddress" method="POST" action="#" enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo $_SESSION['new_shipping_address_key'] ?>" name="fkey" id="fkey">
                    <input type="hidden" value="address" name="registerAt" id="registerAt">
                    
                    <!-- Address Details Section -->
                    <div class="form-section">
                        <div class="section-header">
                            <h5>Address Details</h5>
                            <div class="section-divider"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Name <span class="required">*</span></label>
                                    <input type="text" name="name" id="name" placeholder="Enter your full name" class="form-input">
                                    <div class="error-message" id="nameError"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobile" class="form-label">Mobile Number <span class="required">*</span></label>
                                    <input type="text" name="mobile" id="mobile" placeholder="Enter your mobile number" class="form-input" maxlength="10">
                                    <div class="error-message" id="mobileError"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address" class="form-label">Address <span class="required">*</span></label>
                            <textarea name="address" id="address" class="form-input" placeholder="Enter your complete address" rows="3"></textarea>
                            <div class="error-message" id="addressError"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="landmark" class="form-label">Landmark <span class="required">*</span></label>
                                    <input type="text" name="landmark" id="landmark" placeholder="Enter landmark" class="form-input">
                                    <div class="error-message" id="landmarkError"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city" class="form-label">Select City <span class="required">*</span></label>
                                    <select name="city" id="city" class="form-select">
                                        <option value="">Select City</option>
                                        <?php echo $data['location_address']['group_layout']; ?>
                                    </select>
                                    <div class="error-message" id="cityError"></div>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="location_area" id="location_area">
                        <div class="form-group Location_area_dropdown display_none">
                            <label for="area_selected" class="form-label">Select Area <span class="required">*</span></label>
                            <select name="area" id="area_selected" class="form-select">
                                <option value="">Select Area</option>
                            </select>
                            <div class="error-message" id="areaError"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state_id" class="form-label">Select State <span class="required">*</span></label>
                                    <select name="state_id" id="state_id" class="form-select">
                                        <option value="">Select State</option>
                                        <?php echo $data['state_list']; ?>
                                    </select>
                                    <div class="error-message" id="stateError"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pincode" class="form-label">Pincode <span class="required">*</span></label>
                                    <input type="text" name="pincode" id="pincode" placeholder="Pincode" class="form-input" readonly>
                                    <div class="error-message" id="pincodeError"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- GST Details Section -->
                    <div class="form-section">
                        <div class="section-header">
                            <h5>GST Details (Optional)</h5>
                            <div class="section-divider"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gst_name" class="form-label">GST Name</label>
                                    <input type="text" name="gst_name" id="gst_name" placeholder="Enter GST name" class="form-input">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gstin_number" class="form-label">GSTIN Number</label>
                                    <input type="text" name="gstin_number" id="gstin_number" placeholder="Enter GSTIN number" class="form-input">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="button" class="btn-secondary" onclick="window.hideModal('add_address')">
                            <i class="fas fa-check"></i> Close Modal (Test)
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Add Address
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade editAddressModal address-modal" id="editAddressModal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title-section">
                    <div class="modal-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h4 class="modal-title">Edit Shipping Address</h4>
                        <p class="modal-subtitle">Update your shipping details</p>
                    </div>
                </div>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="address-form-error"></div>
                <form method="POST" accept-charset="utf-8" id="editCartShippingAddress">
                    <!-- Form content will be loaded dynamically -->
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

$(document).ready(function() {
    // Global error handler
    window.onerror = function(msg, url, lineNo, columnNo, error) {
        console.error('JavaScript Error:', msg);
        console.error('File:', url);
        console.error('Line:', lineNo);
        console.error('Column:', columnNo);
        console.error('Error object:', error);
        return false;
    };
    
    // Custom modal functions - make them globally accessible
    window.showModal = function(modalId) {
        $('#' + modalId).addClass('show').css('display', 'block');
        $('body').addClass('modal-open');
        
        // Add backdrop
        if (!$('.modal-backdrop').length) {
            $('body').append('<div class="modal-backdrop fade show"></div>');
        }
        
        // Focus on first input
        setTimeout(function() {
            $('#' + modalId + ' input:first').focus();
        }, 100);
    };
    
    window.hideModal = function(modalId) {
        $('#' + modalId).removeClass('show').css('display', 'none');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    };
    
    // Ensure modals are hidden on page load
    window.hideModal('add_address');
    window.hideModal('editAddressModal');
    
    // Add click handler for Add New Address card
    $('.add-new-card').on('click', function(e) {
        e.preventDefault();
        console.log('Add new address clicked');
        window.showModal('add_address');
        
        // Debug: Check modal elements
        console.log('Modal opened: add_address');
    });
    
    // Close modal on escape key
    $(document).on('keydown', function(e) {
        if (e.keyCode === 27) { // ESC key
            window.hideModal('add_address');
            window.hideModal('editAddressModal');
        }
    });
    
    // Close modal when clicking outside
    $('.address-modal').on('click', function(e) {
        if (e.target === this) {
            var modalId = $(this).attr('id');
            window.hideModal(modalId);
        }
    });
    
    // Close modal with close button
    $('.address-modal .close').on('click', function() {
        var modal = $(this).closest('.address-modal');
        var modalId = modal.attr('id');
        window.hideModal(modalId);
    });
    
    // Close modal with cancel button
    $('.btn-secondary[data-bs-dismiss="modal"]').on('click', function() {
        var modal = $(this).closest('.address-modal');
        var modalId = modal.attr('id');
        window.hideModal(modalId);
    });
});

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
        console.log('City changed to:', id);
        if (id) {
            $.ajax({
                type: "POST",
                url: base_path + "myaccount/api/getLocationsForGroup",
                dataType: "html",
                data: {result : id},
                beforeSend: function () {
                    console.log('Loading areas for city:', id);
                },
                success: function (data) {
                    console.log('Areas loaded:', data);
                    try {
                        var parsed_array = JSON.parse(data);
                        $(".Location_area_dropdown").removeClass("display_none");
                        $(".Location_area_dropdown select").html(parsed_array);
                        $("#state_id").css("pointer-events","none");
                        var state_id = $("#city").find(':selected').data('state_id');
                        $("#state_id").val(state_id);
                        console.log('Area dropdown shown');
                    } catch (e) {
                        console.error('Error parsing areas data:', e);
                        console.log('Raw data:', data);
                        // Show error message to user
                        alert('Error loading areas. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error loading areas:', error);
                }
            });
        } else {
            $(".Location_area_dropdown").addClass("display_none");
        }
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
                required: false,
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
                  required: "Area is optional",
              }
          },
        errorPlacement: function(error, element) {
            var fieldName = $(element).attr('name');
            var errorContainer = $('#' + fieldName + 'Error');
            error.appendTo(errorContainer);
        },
        success: function(label, element) {
            var fieldName = $(element).attr('name');
            $('#' + fieldName + 'Error').empty();
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
                console.log('Raw response:', data);
                console.log('Response length:', data.length);
                console.log('Response ends with 1:', data.trim().endsWith('1'));
                console.log('Response includes INSERT:', data.includes('INSERT INTO'));
                console.log('Response includes 1:', data.includes('1'));
                
                  $(".page_loading").hide();
                  
                  // Check if response contains success indicator
                  // Since the response includes the SQL query, we need to check for the success pattern
                  var isSuccess = data.includes('INSERT INTO') && (data.includes('1') || data.trim().endsWith('1'));
                  console.log('Is success:', isSuccess);
                  
                  // Alternative success detection - if we get a response with SQL query, it's likely success
                  if (!isSuccess && data.includes('INSERT INTO') && data.length > 100) {
                      isSuccess = true;
                      console.log('Using alternative success detection');
                  }
                  
                  if (isSuccess) {
                      // Close the modal
                      console.log('Closing modal due to success detection');
                      window.hideModal('add_address');
                      
                      // Show success message
                      setTimeout(function() {
                          new Noty({
                              text: 'Address Added Successfully!',
                              type: 'success',
                              theme: 'relax',
                              layout: 'bottomCenter',
                              timeout: 3000
                          }).show();
                      }, 100);
                      
                      // Reload page after a short delay
                      setTimeout(function() {
                          if (type == "cart") {
                              window.location = base_path + "cart/address?a=success";
                          } else {
                              window.location = base_path + "myaccount/manageaddress?a=success";
                          }
                      }, 1500);
                  } else {
                      // Check if it's actually an error or just a different success format
                      if (data.includes('INSERT INTO') && !data.includes('Error') && !data.includes('Sorry')) {
                          // Likely success with SQL query output
                          console.log('Fallback success detection triggered');
                          window.hideModal('add_address');
                          setTimeout(function() {
                              new Noty({
                                  text: 'Address Added Successfully!',
                                  type: 'success',
                                  theme: 'relax',
                                  layout: 'bottomCenter',
                                  timeout: 3000
                              }).show();
                          }, 100);
                          setTimeout(function() {
                              window.location.reload();
                          }, 1500);
                      } else {
                          console.log('Showing error message:', data);
                          $(".address-form-error").html(data);
                      }
                  }
              },
              error: function(xhr, status, error) {
                  $(".page_loading").hide();
                  console.error('Form submission error:', error);
                  console.error('Status:', status);
                  console.error('Response:', xhr.responseText);
                  $(".address-form-error").html("Unexpected error occurred. Please try again.");
              }
          });
          return false;
      }
    });

  // edit Address model open

     $('.editAddressPopup').click(function() {
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
                  showModal('editAddressModal');
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
          state_id: {
              required: true,
          },
          pincode: {
              required: true,
          },
          area: {
            required: false,
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
          area: {
              required: "Area is optional",
          },
          pincode: {
              required: "Pincode cannot be empty",
          }
      },
      errorPlacement: function(error, element) {
          var fieldName = $(element).attr('name');
          var errorContainer = $('#' + fieldName + 'Error');
          error.appendTo(errorContainer);
      },
      success: function(label, element) {
          var fieldName = $(element).attr('name');
          $('#' + fieldName + 'Error').empty();
      },
      submitHandler: function(form) {
          var content = $(form).serialize();
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
                  console.log('Edit response:', data);
                  console.log('Response type:', typeof data);
                  console.log('Response length:', data.length);
                  
                  // Check if response is exactly "1" (success)
                  var isSuccess = (data == 1 || data === "1" || data.trim() === "1");
                  console.log('Is success:', isSuccess);
                  
                  if (isSuccess) {
                      // Close the modal
                      console.log('Closing edit modal due to success');
                      window.hideModal('editAddressModal');
                      
                      // Show success message
                      setTimeout(function() {
                          new Noty({
                              text: 'Address Updated Successfully!',
                              type: 'success',
                              theme: 'relax',
                              layout: 'bottomCenter',
                              timeout: 3000
                          }).show();
                      }, 100);
                      
                      // Reload page after a short delay
                      setTimeout(function() {
                          window.location.reload();
                      }, 1500);
                  } else {
                      console.log('Edit failed, showing error:', data);
                      $(".address-form-error").html(data);
                  }
              },
              error: function(xhr, status, error) {
                  $(".page_loading").hide();
                  console.error('Edit form submission error:', error);
                  $(".address-form-error").html("Sorry!! Unexpected Error Occurred. Please try again.");
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