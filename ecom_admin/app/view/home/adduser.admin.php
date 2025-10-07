<?php require_once 'includes/top.php'; ?>

    <!-- content @s -->
     <div class="nk-content nk-content-fluid">
        <div class="container-fluid wide-md">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    
                    <div class="nk-block">
                        <form method="post"  class="form-validate is-alter" id="addUser">
                            <?php echo $data['csrf_add_user'] ?>
                            <div class="form_submit_bar">
                                <div class="container wide-md">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>users"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                            <h3><?php echo $data['page_title'] ?></h3>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="submit_button_wrap">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input"  value="1"  id="draft_button" name="save_as_draft">
                                                <label class="custom-control-label" for="draft_button">Save as draft</label>
                                            </div>
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>users"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Save & Publish</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-shadow">
                                <div class="card-inner">
                                    <h5 class="card-title">General Info</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group">
                                                <label class="form-label">Name
                                                    <em>*</em>
                                                </label>
                                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label class="form-label">Email  <em>*</em>
                                                </label> 
                                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <br>
                                            <div class="form-group">
                                                <label class="form-label">Mobile <em>*</em>
                                                </label> 
                                                <input type="text" name="mobile" id="mobile" class="form-control" autocomplete="off" placeholder="Enter Mobile Number" >
                                            </div>
                                        </div>
                                        <?php if(1 != 1) { ?>
                                        <!--  <div class="col-md-6">
                                            <br>
                                            <div class="form-group">
                                                <label class="form-label">Status </label>
                                                <div class="form-control-wrap">
                                                    <select class="form-control" required="" name="status">
                                                       <option value="1">Enabled </option>
                                                        <option value="0">Disabled</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div> -->
                                        <?php  } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-shadow">
                                <div class="card-inner">
                                    <h5 class="card-title">Permission Info </h5>
                                    <div class="app-heading app-heading-small">
                                        <div class="title">
                                            <label class='checkbox-inline checkbox-success'>
                                                <input class='styled' type='checkbox' id="selectAllPost"> Select All
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <?php echo $data['permissions'] ?>
                                        <span class="clearfix"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-error">
                                
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

    $("#addUser").validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true
            },
            mobile: {
                required: true,
                digits: true,
                maxlength: 10,
                minlength: 10
            },
            
        },
        messages: {
            name: {
                required: "Please Enter Name",
            },
            email: {
                required: "Please Enter Email",
            },
            mobile: {
                required: "Please Enter your Mobile Number",
                maxlength: "Please enter valid 10 digit mobile number",
                minlength: "Please enter valid 10 digit mobile number",
                digits : "Please enter a valid mobile number"
            },
            
        },
        submitHandler: function(form) {
            var content = $(form).serialize();
            toastr.clear();
            $.ajax({
                type: "POST",
                url: core_path + "users/api/add",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "users?a=success";
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

    /*=========================
        Employee Section
    ===========================*/

    // Select Post permissions

    $('#selectAllPost').click(function() {
        $('.post_permission').prop('checked', this.checked);
    });

    // Select Parent

    $('.main_permission').click(function() {
        var option = $(this).data("option");
        $('.sub_permission_' + option).prop('checked', this.checked);
    });

    $('.sub_menu_permission').click(function() {
        var option = $(this).data("option");
        let Check = $('.sub_menu_permission').prop('checked');
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

