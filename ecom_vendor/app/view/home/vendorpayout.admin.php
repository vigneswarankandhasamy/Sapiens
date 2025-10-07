<?php require_once 'includes/top.php'; ?> 
    
<div class="nk-content nk-content-fluid non-printable">
    <div class="container-xl wide-xl">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title"><?php echo $data['page_title'] ?></h3>
                            <div class="nk-block-des">
                                <nav>
                                    <ul class="breadcrumb breadcrumb-arrow">
                                        <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a>
                                        </li>
                                        <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>payoutsettings">Payouts</a>
                                        </li>
                                        <li class="breadcrumb-item active">
                                            <?php echo $data['page_title'] ?>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                <form id="vendorPayoutInvoice">
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li>
                                                <input type="text" name="valid_from" id="valid_from" autocomplete="off" class="form-control date-picker" data-date-format="dd-mm-yyyy" value="<?php echo $data['from_date']; ?>" placeholder="From Date">
                                            </li>
                                            <li>
                                                <input type="text" name="valid_to" id="valid_to" class="form-control date-picker" data-date-format="dd-mm-yyyy" value="<?php echo $data['to_date']; ?>" autocomplete="off" placeholder="To Date">
                                            </li>
                                            <li class="nk-block-tools-opt">
                                                <button type="submit" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span>
                                                </button>
                                            </li>
                                        </ul>
                                        <ul>
                                            <div class="row payout_from_error" >
                                                <div class="col-md-4">
                                                    <div id="fromdateerror"></div>
                                                </div>
                                                <div class="col-md-4 todate_error">
                                                    <div id="todateerror"></div>
                                                </div>
                                            </div>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if($data['histroy_type']=='paid') { ?>
                <div class="card card-bordered card-full">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title"><span class="mr-2">Payout list</span></h6>
                            </div>
                            <div class="card-tools">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <?php if($data['paid_list']!="" ) { ?>
                                        <button class="btn btn-primary export_paid_invoice_list"><em class="icon ni ni-arrow-down"></em><span>Export</span>
                                        </button>
                                        <?php } ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card card-preview">
                            <div class="card-inner">
                                <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                    <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th class="nk-tb-col nk-tb-col-check">
                                                S.No
                                            </th>
                                            <th class="nk-tb-col"><span class="sub-text">Payout Invoice No</span>
                                            </th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Date</span>
                                            </th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Vendor</span>
                                            </th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Total Orders</span>
                                            </th>
                                            <th class="nk-tb-col tb-col-lg"><span class="sub-text">Amount</span>
                                            </th>
                                            <th class="nk-tb-col tb-col-lg"><span class="sub-text">Total Charge</span>
                                            </th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Net Payable</span>
                                            </th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span>
                                            </th>
                                            <th class="nk-tb-col nk-tb-col-tools text-right">
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
                </div>
                <?php } else { ?>
                <div class="card card-bordered card-full">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title"><span class="mr-2">Unpaid Payout list</span></h6>
                            </div>
                            <div class="card-tools">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <?php if($data['unpaid_list']!="<td colspan='5' style='text-align: center;'>No Records</td>" ) { ?>
                                        <button class="btn btn-primary export_unpaid_invoice_list"><em class="icon ni ni-arrow-down"></em><span>Export</span>
                                        </button>
                                        <?php } ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card card-preview">
                            <div class="card-inner">
                                <div class="card card-bordered card-preview">
                                    <table class="table table-tranx">
                                        <thead>
                                            <tr class="tb-tnx-head">
                                                <th class="tb-tnx-id"><span class="">#</span>
                                                </th>
                                                <th class="tb-tnx-info">
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
                                        <tbody class="PayoutList">
                                            <?php echo $data['unpaid_list'] ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
    

<?php require_once 'includes/bottom.php'; ?>


<script type="text/javascript">
    
    $(".vendorInvoiceTd").click(function() { 
        var id = $(this).data("option");
        var url = core_path + "orders/payoutInvoice/"+ id;
        window.open(url, '_blank');
    });

    // Paid payout list export

    $(".export_paid_invoice_list").click(function() { 

        var from = $("#valid_from").val();
        var to   = $("#valid_to").val();
        var url  = core_path;

        if(from=="") {
            var text_msg = "Are you sure to export overall payout list ?";
            html_string = $.parseHTML( text_msg );
        } else {
            var text_msg = "Are you sure to export <br> payout list from <br> " + from + " to " + to +" ?";
            html_string = $.parseHTML( text_msg );
        }

         toastr.clear();
         Swal.fire({
                title: text_msg,
                text: "",
                icon: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.value) {
                    

                    if(from=="") {
                        window.location = core_path+"exportData/payoutlistreport";
                    } else {
                        window.location = core_path+"exportData/payoutlistreport/"+from+"/"+to ;
                    }  
                }
            });
    });


     // Unpaid payout list export

    $(".export_unpaid_invoice_list").click(function() { 

        var from = $("#valid_from").val();
        var to   = $("#valid_to").val();
        var url  = core_path;

        if(from=="") {
            var text_msg = "Are you sure to export overall unpaid payout list ?";
            html_string = $.parseHTML( text_msg );
        } else {
            var text_msg = "Are you sure to export <br> unpaid payout list from <br> " + from + " to " + to +" ?";
            html_string = $.parseHTML( text_msg );
        }

         toastr.clear();
         Swal.fire({
                title: text_msg,
                text: "",
                icon: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.value) {
                    

                    if(from=="") {
                        window.location = core_path+"exportData/unpayoutlistreport";
                    } else {
                        window.location = core_path+"exportData/unpayoutlistreport/"+from+"/"+to ;
                    }  
                }
            });
    });



    $.validator.addMethod("from_date_er", function (value, elem) {
        var from = new Date( $("#valid_from").val().replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"))
        var to   = new Date( $("#valid_to").val().replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"))
        return (from <= to);
    });


    $("#vendorPayoutInvoice").validate({
        rules: {
            valid_from: {
                required: true,
            },
            valid_to: {
                required: true
            }
        },
        messages: {
            valid_from: {
                required: "From date Can't be empty",
                from_date_er: "From date must be less than to date",
            },
            valid_to: {
                required: "To date Can't be empty",
            }
        },
        errorPlacement: function(error, element) {
                if (element.attr("name") == "valid_from") {
                    error.appendTo("#fromdateerror");
                } else if (element.attr("name") == "valid_to") {
                    error.appendTo("#todateerror");
                } else {
                    error.insertAfter(element);
                }
                 
            },
        submitHandler: function(form) {
            var histroy_type = "<?php echo $data['histroy_type']  ?>"
            window.location = core_path + "orders/payouts/"+histroy_type+"/"+$("#valid_from").val()+"/"+$("#valid_to").val();
            return false;
        }
    });

</script>

