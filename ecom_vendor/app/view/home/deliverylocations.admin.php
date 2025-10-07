<?php require_once 'includes/top.php'; ?>

    <!-- content @s -->
     <div class="nk-content nk-content-fluid">
        <div class="container-fluid wide-md">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    
                    <div class="nk-block">
                        <form method="post"  class="form-validate is-alter" id="vendorDeliveryLoction">
                            <?php echo $data['csrf_add_locations'] ?>
                            <div class="form_submit_bar">
                                <div class="container wide-md">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                            <h3><?php echo $data['page_title'] ?></h3>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="submit_button_wrap">
                                            <!-- <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input"  value="1"  id="draft_button" name="save_as_draft">
                                                <label class="custom-control-label" for="draft_button">Save as draft</label>
                                            </div> -->
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-shadow">
                                <div class="card-inner">
                                    <!-- <h5 class="card-title">Product Varants info </h5> -->
                                    <?php if($data['locations']!="")  { ?>
                                    <div class="app-heading app-heading-small">
                                        <div class="title">
                                            <label class='checkbox-inline checkbox-success'>
                                                <input class='styled' type='checkbox' id="selectAllPost"> Select All
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <?php echo $data['locations'] ?>
                                        <span class="clearfix"></span>
                                    </div>
                                    <?php } else { ?>
                                        <span><strong>Admin does not allocate any delivery location for you. Please check after some time or contact admin</strong></span>
                                    <?php  } ?>
                                </div>
                            </div>
                            <div class="form-error display_none assigncategory_from_error"  >
                                <div class="alert alert-fill alert-danger alert-dismissible alert-icon" role="alert">
                                    <em class="icon ni ni-alert-circle"></em> 
                                    <strong><p class="form-msg"></p></strong> 
                                </div>
                            </div>
                        </form>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>


<script type="text/javascript">

    // Add User

    $("#vendorDeliveryLoction").validate({
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "locations/api/vendorDeliveryLoction",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    console.log(data);
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "locations?lu=success";
                    } else {
                        $(".form-error").css('display','block');
                        $(".form-msg").html(data);
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
    
    // Select All Categories

    $('#selectAllPost').click(function() {
        $('.post_permission').prop('checked', this.checked);
    });

    // Select all variants while selecting category

    $('.main_permission').click(function() {
        var option = $(this).data("option");
        $('.sub_permission_' + option).prop('checked', this.checked);
    });
    
    // Select category  while selecting variants

    $('.sub_menu_permission').click(function() {
        var option = $(this).data("option");
        $('#main_' + option).prop('checked', 'true');
        minimum_check(option);
    });

    function minimum_check(option) {
        var checkBoxes = document.getElementsByClassName( 'sub_permission_'+option );
        var isChecked = false;
        for (var i = 0; i < checkBoxes.length; i++) {
            if ( checkBoxes[i].checked ) {
                isChecked = true;
            };
        };
        if (! isChecked ) {
            $('#main_' + option).prop('checked', false);
        }   
    }

</script>

<?php if (isset($_GET['lu'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Delivery Location Updated successfully !</h5>', 'success', {
        position: 'bottom-center', 
        ui: 'is-light',
        "progressBar": true,
        "showDuration": "300",
        "hideDuration": "200",
        "timeOut": "4000"
    }); 
}, 1500);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>


