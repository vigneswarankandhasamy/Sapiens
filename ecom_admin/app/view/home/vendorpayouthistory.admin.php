<?php require_once 'includes/top.php'; ?>
<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-xl">
        <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title"><?php echo $data['page_title'] ?></h3>
                            <div class="nk-block-des">
                                <nav>
                                    <ul class="breadcrumb breadcrumb-arrow">
                                        <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a></li>
                                        <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>vendorsettings">Vendors</a></li>
                                        <li class="breadcrumb-item active"><?php echo $data['page_title'] ?></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <!-- .nk-block-head-content -->
                       <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                <form method="post"  id="vendorPayoutsSort" >
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="date_input">
                                                <input type="text" name="sort_from"  id="sort_from" autocomplete="off" class="form-control date-picker" data-date-format="dd-mm-yyyy" placeholder="From Date">
                                            </li>
                                            <li class="date_input">
                                                <input type="text" name="sort_to" id="sort_to" class="form-control date-picker" data-date-format="dd-mm-yyyy" autocomplete="off" placeholder="To Date" >
                                                <?php if($data['histroy_type']=="paid") { ?>
                                                    <input type="hidden" name="payout_list" value="paid">
                                                <?php } else {?>
                                                    <input type="hidden" name="payout_list" value="unpaid">
                                                <?php } ?>
                                            </li>
                                            <li class="sellect_vendor">
                                                <div class="form-group sellect_vendor_drp" >
                                                    <div class="form-control-wrap">

                                                        <select class="form-select form-control" id="vendor_id" name="vendor_id" data-search="on">
                                                            <option value="default" >Select Vendor</option>
                                                           <?php echo $data['get_vendors'] ?>
                                                        </select>
                                                        
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="nk-block-tools-opt"><button type="submit"  class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></button></li>
                                        </ul>
                                        <div id="vendor_id_error" style="width: 150px;height: 15px;margin-left: 395px;"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- .nk-block-head-content -->
                    </div>
                    <!-- .nk-block-between -->
                </div>
                    <!-- .nk-block-head -->

                    <div class="nk-block nk-block-lg sorting_info display_none ">
                        <div class="row gy-5">
                            <div class="col-lg-5">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title title">Sorting By</h5>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="card card-bordered">
                                    <ul class="data-list is-compact">
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Vendor</div>
                                                <div class="data-value vendor_name"></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Date</div>
                                                <div class="data-value sort_date"></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">List</div>
                                                <div class="data-value">
                                                <?php if($data['histroy_type']=="unpaid") { ?>
                                                    Unpaid Invoice List
                                                <?php } else {?>
                                                    Paid Invoice List
                                                <?php } ?>
                                            </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div><!-- .card -->
                            </div>
                            </div><!-- .col -->
                        </div><!-- .row -->

                    <?php if($data['histroy_type']=="unpaid") { ?>
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
                                        <tbody class="PayoutList">
                                            <?php echo $data['unpaid_list'] ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- .card-preview -->
                    </div>
                    <?php } else {?>
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
                                                    <span class="tb-tnx-desc d-none d-sm-inline-block payout_histroy_invoice_column" >
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



                                        <tbody class="PayoutList">
                                            <?php echo $data['paid_list'] ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- .card-preview -->
                    </div>
                    <?php } ?>
                </div>
        </div>
    </div>
</div>
<!-- content @e -->



<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
    // Open payout invoice  

    $(".open_payout_invoice").click(function() {
        var item_id = $(this).data("option");
        window.open(core_path + "orders/payoutInvoice/" + item_id, '_blank');
    });

    // Unpaid list td to payout datails page

    $("body").on("click",".open_payout_details",function() {
        var item_id = $(this).data("option");
        window.location = core_path + "orders/unpaidpayoutdetails/" + item_id;
    });
</script>

<script type="text/javascript">
    $.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg !== value;
 }, "Value must not equal arg.");
    
    $("#vendorPayoutsSort").validate({
        rules: {
        sort_from : {
                 required: function(element){
                    if($("#sort_to").val()!=""){
                        return true;
                    } else {
                        return false;
                    }
                }
            },
        sort_to : {
             required: function(element){
                    if($("#sort_from").val()!=""){
                        return true;
                    } else {
                        return false;
                    }
                }

            },
        vendor_id : { valueNotEquals: "default" },

        },

        messages: {
            sort_from: {
                required: "",
            },
            sort_to: {
                required: "",
            },
            vendor_id: { valueNotEquals: "Please select vendor!" },

        },

        errorPlacement : function(error,element) {
            if(element.attr("name") == "vendor_id") {
                error.appendTo("#vendor_id_error");
            }

        },


        submitHandler: function(form) {
            saveForm();
           
            return false;
        }
    });

    function  saveForm() {
            var formname = document.getElementById("vendorPayoutsSort");
            var formData = new FormData(formname);
            $.ajax({
                url: core_path + "orders/api/vendorShort",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {

                    // console.log(data);
                    // return false;
                    
                    var obj = JSON.parse(data);
                    
                    $(".vendor_name").html(obj['v_info']['company']);

                    if(obj['from']=="") {
                        var date = 'Overall';
                    } else {
                        var date = obj['from'] +' to '+ obj['to'];
                    }
                    $(".PayoutList").html();
                    $(".PayoutList").html(obj['paid_list']);
                    $(".sort_date").html(date);
                    $(".sorting_info").show();
                    $(".page_loading").hide();
                }
            });
    }

</script>

