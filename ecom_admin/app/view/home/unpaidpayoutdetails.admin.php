<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-xl">
        <div class="nk-content-inner">
            <form id="vendorPayoutRecords" class="form-validate is-alter" method="post">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="nk-block-between">
                            <div class="form_submit_bar">
                                <div class="container wide-lg">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>orders/payouthistory/unpaid"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                            <h3><?php echo $data['page_title'] ?> / <?php echo $data['vendor_info']['company'] ?></h3>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="submit_button_wrap">
                                                <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>orders/vendorpayouts"> Cancel</button>
                                                <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Pay</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- .nk-block-between -->
                    </div>
                    <!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="row gy-gs">
                            <div class="col-md-6 col-lg-4">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <h6 class="title">Total Sales
                                                    
                                            </h6>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount">
                                                   ₹ <?php echo number_format($data['data_counts']['totalAmount'],2); ?> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- .card -->
                            </div>
                            <!-- .col -->
                             <div class="col-md-6 col-lg-4">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <h6 class="title">Total orders 
                                                    </h6>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount">
                                                    <?php echo $data['data_counts']['totalOrders'] ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- .card -->
                            </div>
                            
                            <!-- .col -->
                            <div class="col-md-6 col-lg-4">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <h6 class="title">Vendor Payout
                                                    
                                                </h6>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount">
                                                    ₹ <?php echo number_format($data['data_counts']['totalCommission']+$data['data_counts']['totalPayment']+$data['data_counts']['totalShipping'],2); ?> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- .card -->
                            </div>
                            <!-- .col -->
                            <div class="col-md-6 col-lg-4">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <h6 class="title">Total Charge 
                                                    
                                                </h6>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount">
                                                    ₹ <?php echo number_format($data['data_counts']['totalCommission'],2); ?> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- .card -->
                            </div>
                            
                            <!-- .col -->
                            <div class="col-md-6 col-lg-4">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <h6 class="title">Total Payment cost
                                                    
                                                </h6>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount">
                                                    ₹  <?php echo number_format($data['data_counts']['totalPayment'],2); ?> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- .card -->
                            </div>
                            <!-- .col -->
                            <div class="col-md-6 col-lg-4">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <h6 class="title">Total Shipping cost 
                                                    </h6>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount">
                                                    ₹ <?php echo number_format($data['data_counts']['totalShipping'],2); ?> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- .card -->
                            </div>
                            <!-- .col -->
                        </div>
                    </div>
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h5 class="nk-block-title title">Unpaid Invoice List</h5>
                            </div>
                        </div>
                        <div class="card card-preview">
                            <div class="card-inner">
                                <div class="card card-bordered card-preview">
                                    <table class="table table-tranx">
                                        <thead>
                                           <tr class="tb-tnx-head">
                                                <th class="tb-tnx-id"><span class="">#</span>
                                                </th>
                                                <th class="tb-tnx-info" >
                                                    <span class="tb-tnx-total">Order Invoice No</span>
                                                    <span class="tb-tnx-status d-none d-md-inline-block">Order Date</span>
                                                </th>
                                                 <th class="tb-tnx-amount">
                                                    <span class="tb-tnx-total">Vendor</span>
                                                    <span class="tb-tnx-status d-none d-md-inline-block">Customer</span>
                                                </th>
                                                
                                                <th class="tb-tnx-amount">
                                                    <span class="tb-tnx-total">Amount</span>
                                                    <span class="tb-tnx-status d-none d-md-inline-block">Total Charge</span>
                                                </th>
                                                <th class="tb-tnx-amount">
                                                    <span class="tb-tnx-total">Net payable</span>
                                                    <span class="tb-tnx-status d-none d-md-inline-block">Status</span>
                                                </th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <?php echo $data['list'] ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- .card-preview -->
                    </div>
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h5 class="nk-block-title title">Paid Invoice List</h5>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                    <!-- <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                             <li class="nk-block-tools-opt"><a href="<?php echo COREPATH ?>orders/payoutInvoice/<?php echo $data['vendor_id'] ?>" class="btn btn-success"><em class="icon ni ni-file"></em><span>Payout Invoice</span></a>
                                        </ul>
                                    </div> -->
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div>
                            </div>
                        </div>
                        <div class="card card-preview">
                            <div class="card-inner">
                                <div class="card card-bordered card-preview">
                                    <table class="table table-tranx">
                                        <thead>
                                            <tr class="tb-tnx-head">
                                                <th class="tb-tnx-id"><span class="">#</span>
                                                </th>
                                                <th class="tb-tnx-info" >
                                                    <span class="tb-tnx-desc d-none d-sm-inline-block payout_invoice_column"  >
                                                            <span>Payout Invoice No</span>
                                                    </span>
                                                </th>
                                                 <th class="tb-tnx-id"><span class="">Paid Date</span>
                                                </th>
                                                 <th class="tb-tnx-amount">
                                                    <span class="tb-tnx-total">Vendor</span>
                                                    <span class="tb-tnx-status d-none d-md-inline-block">Total Orders</span>
                                                </th>
                                                
                                                <th class="tb-tnx-amount">
                                                    <span class="tb-tnx-total">Amount</span>
                                                    <span class="tb-tnx-status d-none d-md-inline-block">Total Charge</span>
                                                </th>
                                                <th class="tb-tnx-amount">
                                                    <span class="tb-tnx-total">Net payable</span>
                                                    <span class="tb-tnx-status d-none d-md-inline-block">Status</span>
                                                </th>
                                                 <th class="tb-tnx-id"><span class="">Action</span>
                                                </th>
                                            </tr>

                                        </thead>



                                        <tbody>
                                            <?php echo $data['paid_list'] ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- .card-preview -->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
    // Open Vendor Payout invoice 

    $(".open_payout_invoice").click(function() {
        var item_id = $(this).data('option');
        window.open(core_path+"orders/payoutInvoice/"+item_id,"_blank")
    });

    // Add  Product

    $("#vendorPayoutRecords").validate({
        rules: {
        },
        messages: {
        },
        submitHandler: function(form) {
            toastr.clear();
             Swal.fire({
                    title: "Are you sure to make payout for <?php echo $data['vendor_info']['company'] ?> ?",
                    text: "",
                    icon: 'warning',
                    showCloseButton: true,
                    showCancelButton: true,
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
        var formname  = document.getElementById("vendorPayoutRecords");
        var formData  = new FormData(formname);
        var vendor_id = "<?php echo $data['vendor_id'] ?>";
        var period    = "<?php echo $data['period'] ?>";
        $.ajax({
            url: core_path + "orders/api/vendorPayoutRecords",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                console.log(data);
                $(".page_loading").hide();
                $(".form-error").html(data);
                if (data == 1) {
                    window.location = core_path + "orders/unpaidpayoutdetails/" + period + "/" + vendor_id + "?p=success";
                } else if(data == 0) {
                    data = 'Please select at least one order to payout';
                    toastr.clear();
                    NioApp.Toast('<h5>' + data + '</h5>', 'error', {
                        position: 'bottom-center',
                        ui: 'is-light',
                        "progressBar": true,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "4000"
                    });
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

<?php if (isset($_GET['p'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        toastr.clear();
        NioApp.Toast('<h5>Payout Status Updated Successfully !!</h5>', 'success', {
            position: 'top-right',
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