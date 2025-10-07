<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="updateShipping" enctype="multipart/form-data">
                        <?php echo $data[ 'csrf_edit_shipping'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>settings"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if(1 != 1) { ?>
                                        <!-- <div class="submit_button_wrap">
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>settings"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Update</button>
                                        </div> -->
                                        <?php  } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-error"></div>
                        <div class="card card-shadow wide-md">
                            <div class="card-inner">
                                <?php echo $data['list'] ?>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

<div class="form_panel_warp pincode_delivery_status fixed-content">
    <form id="pincodeDeliveryStatus" method="POST">
        <?php echo $data['csrf_edit_pincode_status'] ?>
        <input type="hidden" name="token" id="token">
        <div class="form_panel_head">
            <h6 class="mb-0">Pincode Delivery Status</h6><a class="nk-demo-close toggle btn btn-icon btn-trigger revarse mr-n2 active closeFormPanel" data-formclass='pincode_delivery_status' data-form='pincodeDeliveryStatus'   href="javascript:void();"><em class="icon ni ni-cross"></em></a>
        </div>
        <div class="form_panel_content" >
            <div class="form-group">
                <h5 class="card-title city_name"> Pincode List
                    <en>*</en>
                </h5>
                <div class="form_stracture">
                    
                </div>
            </div>
        </div>
        <div class="form-error"></div>
        <div class="form_panel_footer">
            <div class="row">
                <div class="col-md-12">
                    <p class="pull-right">
                        <button type="button" class="btn btn-light closeFormPanel" data-form='pincodeDeliveryStatus' data-formclass="pincode_delivery_status"> Cancel</button>
                        <button type="submit" class="btn btn-success pull-right"><em class="icon ni ni-check-thick"></em> Save</button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- content @e -->
<div class="form_panel_overlay" ></div>

<?php require_once 'includes/bottom.php'; ?>


<?php if (isset($_GET['a'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Pincode Activity updated successfully !!</h5>', 'success', {
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

<script type="text/javascript">

    // Add State and City

    $("#updateShipping").validate({
        rules: {

        },
        submitHandler: function(form) {
            toastr.clear();
                Swal.fire({
                    title: "Are you sure to save this changes?",
                    text: "So that changes will be reflected in your sites !!",
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

    // Save Form

    function saveForm() {
        var formname = document.getElementById("updateShipping");
        var formData = new FormData(formname);
        $.ajax({
            url: core_path + "shipping/api/updatePincode",
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
                    window.location = core_path + "shipping?a=success";
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

    // state and city toggle
    $( ".toggle_state" ).on( "click", function() {
        var option = $(this).data("option");
        var value = $(this).prop('checked');

        if(value){
            var msg = 'Activated';
        } else {
            var msg = 'Deactivated';
        }
        $('.city_toggle_' + option).prop('checked', this.checked);
        $.ajax({
            type: "POST",
            url: core_path + "shipping/api/activeState",
            dataType: "html",
            data: { result: {option,value} },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                toastr.clear();
                        NioApp.Toast('<h5>State Delivery Status '+msg+'</h5>', 'success', {
                            position: 'bottom-center', 
                            ui: 'is-light',
                            "progressBar": true,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "4000"
                        });
            }
        });
    });

    // state and pincode status

     $(".toggle_city" ).on( "click", function() {
        var option = $(this).data("option");
        var value = $(this).prop('checked');

        if(value){
            var msg = 'Activated';
        } else {
            var msg = 'Deactivated';
        }
        $.ajax({
            type: "POST",
            url: core_path + "shipping/api/activeCity",
            dataType: "html",
            data: { result: {option,value} },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                toastr.clear();
                        NioApp.Toast('<h5>City Delivery Status '+msg+'</h5>', 'success', {
                            position: 'bottom-center', 
                            ui: 'is-light',
                            "progressBar": true,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "4000"
                        });
            }
        });
    });


     // Open Edit Pincode

    $(".openEditPincode").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        var formclass = $(this).data("formclass");
        var form = $(this).data("form");
        $.ajax({
            type: "POST",
            url: core_path + "shipping/api/getPincodeInfo",
            dataType: "html",
            data: { result: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                var json = $.parseJSON(data);
                $("#token").val(value);
                $(".form_stracture").html(json['html']);
                toggleFormPanel(formclass,form,type="retain");
            }
        });
        return false;
    });

    // update pincode status
    $("#pincodeDeliveryStatus").validate({
        rules: {
            
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "shipping/api/pincodeDeliveryStatus",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                       window.location = core_path + "shipping?a=success";
                    } else {
                        $(".form-error").show();
                        $(".form-error").html(data);
                        toastr.clear();
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