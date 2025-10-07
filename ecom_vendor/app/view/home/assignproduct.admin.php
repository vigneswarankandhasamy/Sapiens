<?php require_once 'includes/top.php'; ?> 

   <!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="assignProducts" enctype="multipart/form-data">
                        <?php echo $data[ 'csrf_add_product'] ?> 
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>products/catalogue"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3>Select Products under - <?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="submit_button_wrap">
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>products/catalogue"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <pre class="form-error"></pre>
                        <div class="nk-block">
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <table class="table table_middle bg-white table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Product</th>
                                            <th scope="col">Actual Price</th>
                                            <th scope="col">Selling price</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Availability</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $data['list'] ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div>

                    </form>
                </div>
                <!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
    // Add  Product

    $("#assignProducts").validate({
        rules: {
           
        },
        messages: {
           
        },
         submitHandler: function(form) {
            toastr.clear();
            saveForm();
            return false;
        }
      
    });

    // Save Form

    function saveForm() {
        var formname = document.getElementById("assignProducts");
        var formData = new FormData(formname);
            $.ajax({
                url: core_path + "products/api/assignProducts",
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
                    $(".form-error").html(data);
                    if (data == 1) {
                        window.location = core_path + "products/catalogue?p=success";
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

<?php if (isset($_GET['e'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Product  Updated successfully !!</h5>', 'success', {
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

