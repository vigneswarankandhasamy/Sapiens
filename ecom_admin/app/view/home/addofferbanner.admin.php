<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="addOfferBanner" enctype="multipart/form-data">
                        <?php echo $data[ 'csrf_add_home_slider'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>offerbanner"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="submit_button_wrap">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input"  value="1"  id="draft_button" name="save_as_draft">
                                                <label class="custom-control-label" for="draft_button">Save as draft</label>
                                            </div>
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>offerbanner"> Cancel</button>
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
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Title
                                            <em>*</em>
                                        </label>
                                        <input type="text" name="title_name" id="title_name"  class="form-control" placeholder="Enter Name" required>
                                    </div>
                                    
                                     <div class="form-group col-md-6">
                                        <label class="form-label">Button Name
                                            <em>*</em>
                                        </label>
                                        <input type="text" name="bname" id="bname"  class="form-control" placeholder="Enter Button Name" required>
                                    </div>
                                     <div class="form-group col-md-6">
                                        <label class="form-label">Button Type
                                            <em>*</em>
                                        </label>
                                        <div class="g-4 align-center flex-wrap">
                                            <div class="g">
                                                <div class="custom-control custom-control-sm custom-radio">
                                                    <input type="radio" class="custom-control-input button_type" name="button_type" id="customRadio3" value="main_category" checked>
                                                    <label class="custom-control-label" for="customRadio3">Main Category</label>
                                                </div>
                                            </div>
                                            <div class="g">
                                                <div class="custom-control custom-control-sm custom-radio">
                                                    <input type="radio" class="custom-control-input button_type" name="button_type" id="customRadio2" value="sub_category">
                                                    <label class="custom-control-label" for="customRadio2">Sub Category</label>
                                                </div>
                                            </div>
                                            <div class="g">
                                                <div class="custom-control custom-control-sm custom-radio">
                                                    <input type="radio" class="custom-control-input button_type" name="button_type" id="customRadio1" value="product" >
                                                    <label class="custom-control-label" for="customRadio1">Product</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 select_main_category ">
                                        <label class="form-label">Select Main Category
                                            <en>*</en>
                                        </label>
                                        <div class="form-control-wrap">
                                            <select class="form-select"  id="main_category_id" name="main_category_id" data-placeholder="Select Main Category" data-search="on">
                                                <?php echo $data['category_list']; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 select_sub_category display_none ">
                                        <label class="form-label">Select Sub Category
                                            <en>*</en>
                                        </label>
                                        <div class="form-control-wrap">
                                            <select class="form-select"  id="sub_category_id" name="sub_category_id" data-placeholder="Select Sub Category" data-search="on">
                                                <?php echo $data['sub_category_list']; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 select_prod display_none ">
                                        <label class="form-label">Select Product
                                            <en>*</en>
                                        </label>
                                        <div class="form-control-wrap">
                                            <select class="form-select"  id="product" name="product" data-placeholder="Select Product" data-search="on">
                                                <?php echo $data['products_list']; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="form-label">Message
                                            <em>*</em>
                                        </label>
                                         <div class="form-control-wrap">
                                            <textarea class="form-control" rows="2"  name="message"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-shadow">
                            <?php if(1 != 1) { ?>
                            <!-- <div class="card-inner">
                                <h5 class="card-title">Visibility Info</h5>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <div class="custom-control custom-control-sm custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" value="1" name="pop_up" id="showInPopUp">
                                            <label class="custom-control-label form-label" for="showInPopUp">Show In POP-UP</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="custom-control custom-control-sm custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" value="1" name="category_list" id="inCategoryList">
                                            <label class="custom-control-label form-label" for="inCategoryList">Show In Category List</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="custom-control custom-control-sm custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" value="1" name="visibility_date" id="visibilityDate">
                                            <label class="custom-control-label form-label" for="visibilityDate">Custom Visibility Date</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6 customeDate display-none">
                                        <label class="form-label">From</label>
                                        <div class="form-control-wrap">
                                            <input type="text" name="from_date" id='fromDate'  autocomplete="off" class="form-control date-picker" data-date-format="dd/mm/yyyy">
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6 customeDate display-none">
                                        <label class="form-label">To</label>
                                        <div class="form-control-wrap">
                                            <input type="text" name="to_date" id='toDate'  autocomplete="off" class="form-control date-picker" data-date-format="dd/mm/yyyy">
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <?php  } ?>
                        </div>
                          <div class="card card-shadow">
                            <div class="card-inner">
                                <h5 class="card-title">Offer Banner Image </h5>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="form-label" >Image</label>
                                        <div class="form-control-wrap">
                                            <div class="custom-file">
                                                <input type="file" multiple class="custom-file-input" name="file" id="file"  >
                                                <label class="custom-file-label" for="file">Choose file
                                                    <em>*</em>
                                                </label>
                                                <input type="hidden" name="file_type" value="image" >
                                            </div>
                                            <p class="help_text"><?php echo OFFER_BANNER_IMAGE_HELP_TEXT ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label" > Image Name
                                            <em>*</em> 
                                        </label>
                                        <input type="text" name="image_name" id="image_name" class="form-control" placeholder="Enter name of the image" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label" > Image Alt Name 
                                        </label>
                                        <input type="text" name="image_alt_name" id="image_alt_name" class="form-control" placeholder="Enter alt name for the image" >
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

    // Selelct button type

    $('.button_type').on('click', function() {
        var value = $(this).val();
        if (value == "main_category") {
            $('.select_main_category').removeClass("display_none");
            $('.select_sub_category').addClass("display_none");
            $('.select_prod').addClass("display_none");
        } else if(value == "sub_category") {
            $('.select_main_category').addClass("display_none");
            $('.select_sub_category').removeClass("display_none");
            $('.select_prod').addClass("display_none");
        }else if(value == "product") {
            $('.select_main_category').addClass("display_none");
            $('.select_sub_category').addClass("display_none");
            $('.select_prod').removeClass("display_none");
        }
    });

    $("#addOfferBanner").validate({
         rules: {
            title: {
                required: true
            },
            blink: {
                required: true
            },
            message: {
                required: true
            },
            bname: {
                required: true
            }
        },
        messages: {
            title: {
                required: "Please Enter Name",
            },
            blink: {
                required: "Please Enter Button Link",
            },
            message: {
                required: "Please Enter Slider Message",
            },
            bname: {
                required: "Please Enter Button Name",
            },
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
            }else{
                saveForm();
            }
            return false;
        }
      
    });

    // Save Form

    function saveForm() {
        var formname = document.getElementById("addOfferBanner");
        var formData = new FormData(formname);
        $.ajax({
                url: core_path + "offerbanner/api/add",
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
                        window.location = core_path + "offerbanner?a=success";
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