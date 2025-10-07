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
                            <div class="nk-block-des text-soft">
                                <p>Filter analytics overview in reports.</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                <form id="orderReportFilter">
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li>
                                                <input type="text" name="valid_from" id="valid_from" autocomplete="off" class="form-control date-picker" data-date-format="dd-mm-yyyy" value="<?php echo $data['from_date']; ?>" placeholder="From Date">
                                            </li>
                                            <li>
                                                <input type="text" name="valid_to" id="valid_to" class="form-control date-picker" data-date-format="dd-mm-yyyy" value="<?php echo $data['to_date']; ?>" autocomplete="off" placeholder="To Date">
                                            </li>
                                            <input type="hidden" name="vendor_id" id="vendorId" data-vendor_name="<?php echo $data['vendor_detail']['company'];?>" value="<?php echo $data['vendor_detail']['id'];?>">
                                            <li class="nk-block-tools-opt">
                                                <button type="submit" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span>
                                                </button>
                                            </li>
                                        </ul>
                                        <ul>
                                            <div class="row" style="color: #e85347; font-size: 11px;font-style: italic;">
                                                <div class="col-md-4">
                                                    <div id="fromdateerror"></div>
                                                </div>
                                                <div class="col-md-4" style="margin-left: 18px;">
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
                <?php $report_type = ($data['from_date']=="")? "Today's" : "" ?>
                <div class="nk-block">
                    <div class="row gy-gs">
                      <!--   <div class="col-md-6 col-lg-4">
                            <div class=" is-s1 card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-iv-wg2">
                                        <div class="nk-iv-wg2-title">
                                            <h6 class="title">Today's orders </h6>
                                        </div>
                                        <div class="nk-iv-wg2-text">
                                            <div class="nk-iv-wg2-amount">
                                                <?php echo $data['card_data']['todaysOrdersCount']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class=" is-s1 card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-iv-wg2">
                                        <div class="nk-iv-wg2-title">
                                            <h6 class="title">Today's Sales </h6>
                                        </div>
                                        <div class="nk-iv-wg2-text">
                                            <div class="nk-iv-wg2-amount">₹
                                                <?php echo number_format($data['card_data']['todaysSalesAmt'],2); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class=" is-s1 card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-iv-wg2">
                                        <div class="nk-iv-wg2-title">
                                            <h6 class="title">Today's Commission Earned </h6>
                                        </div>
                                        <div class="nk-iv-wg2-text">
                                            <div class="nk-iv-wg2-amount">₹
                                                <?php echo number_format($data['card_data']['todaysCommissionAmt']+$data['card_data']['todaysPayChargeAmt']+$data['card_data']['todaysShipChargeAmt'],2); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-md-6 col-lg-4">
                            <div class=" is-s1 card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-iv-wg2">
                                        <div class="nk-iv-wg2-title">
                                            <h6 class="title"><?php echo $report_type; ?> Orders </h6>
                                        </div>
                                        <div class="nk-iv-wg2-text">
                                            <div class="nk-iv-wg2-amount">
                                                <?php echo $data['card_data']['totalOrders']; ?>
                                                <span class="change up"><span class=""></span><a href="<?php echo COREPATH ?>orders">View All</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class=" is-s1 card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-iv-wg2">
                                        <div class="nk-iv-wg2-title">
                                            <h6 class="title"><?php echo $report_type; ?> Sales </h6>
                                        </div>
                                        <div class="nk-iv-wg2-text">
                                            <div class="nk-iv-wg2-amount">₹
                                                <?php echo number_format($data['card_data']['totalAmount'],2); ?>
                                                <span class="change up"><span class=""></span><a href="<?php echo COREPATH ?>orders">View All</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class=" is-s1 card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-iv-wg2">
                                        <div class="nk-iv-wg2-title">
                                            <h6 class="title"><?php echo $report_type; ?> Average Order value </h6>
                                        </div>
                                        <div class="nk-iv-wg2-text">
                                            <div class="nk-iv-wg2-amount">₹
                                                <?php echo number_format((($data['card_data']['totalOrders']!=0)? $data['card_data']['totalAmount']/ $data['card_data']['totalOrders'] : 0 ),2); ?>
                                                <span class="change up"><span class=""></span><a href="<?php echo COREPATH ?>orders">View All</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class=" is-s1 card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-iv-wg2">
                                        <div class="nk-iv-wg2-title">
                                            <h6 class="title"><?php echo $report_type; ?> Total Charge </h6>
                                        </div>
                                        <div class="nk-iv-wg2-text">
                                            <div class="nk-iv-wg2-amount">₹
                                                <?php echo number_format($data['card_data']['vendorCommissionAmt']+$data['card_data']['vendorPayChargeAmt']+$data['card_data']['vendorShipChargeAmt'],2); ?>
                                                <span class="change up"><span class=""></span><a href="<?php echo COREPATH ?>orders/payouts">View All</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class=" is-s1 card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-iv-wg2">
                                        <div class="nk-iv-wg2-title">
                                            <h6 class="title"><?php echo $report_type; ?> Vendor Payout </h6>
                                        </div>
                                        <div class="nk-iv-wg2-text">
                                            <div class="nk-iv-wg2-amount">₹
                                                <?php echo number_format($data['card_data']['totalAmount']-($data['card_data']['vendorCommissionAmt']+$data['card_data']['vendorPayChargeAmt']+$data['card_data']['vendorShipChargeAmt']),2); ?>
                                                <span class="change up"><span class=""></span><a href="<?php echo COREPATH ?>orders/payouts">View All</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class=" is-s1 card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-iv-wg2">
                                        <div class="nk-iv-wg2-title">
                                            <h6 class="title"><?php echo $report_type; ?> Order returned </h6>
                                        </div>
                                        <div class="nk-iv-wg2-text">
                                            <div class="nk-iv-wg2-amount">
                                                <?php echo $data['card_data']['returnedOrders']; ?>
                                                <span class="change up"><span class=""></span><a href="<?php echo COREPATH ?>orders/vendorordertab">View All</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-6 col-lg-4">
                            <div class=" is-s1 card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-iv-wg2">
                                        <div class="nk-iv-wg2-title">
                                            <h6 class="title"><?php echo $report_type; ?> Rejected Orders  </h6>
                                        </div>
                                        <div class="nk-iv-wg2-text">
                                            <div class="nk-iv-wg2-amount">
                                                <?php echo $data['card_data']['vendorRejectedOrders']; ?>
                                                <span class="change up"><span class=""></span><a href="<?php echo COREPATH ?>orders/vendorrejectedorders">View All</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nk-block">
                    <div class="row gy-gs">
                        <div class="col-lg-6 col-lg-6">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title card-title-sm">
                                            <h6 class="title">Sales Value Vs Date </h6>
                                        </div>
                                    </div>
                                    <div class="device-status my-auto">
                                        <div class="nk-sale-data-group flex-md-nowrap g-4">
                                            <?php if($data['from_date']=="" ) { ?>
                                            <div class="nk-sale-data">
                                                <span class="amount">₹ <?php echo number_format($data['chart_data']['count_data']['totalAmount'],2); ?></span>
                                                <span class="sub-title">Today</span>
                                            </div>
                                            <?php } elseif($data['from_date']!="" ) { ?>
                                            <div class="nk-sale-data">
                                                <span class="amount">₹ <?php echo number_format($data['chart_data']['count_data']['totalAmount'],2); ?></span>
                                                <span class="sub-title"><?php echo date("d-M-y",strtotime($data['from_date'])); ?> to <?php echo date("d-M-y",strtotime($data['to_date'])); ?></span>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="device-status-ck">
                                            <canvas class="line-chart" id="filledLineChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title card-title-sm">
                                            <h6 class="title">Sales Value Vs Platform</h6>
                                        </div>
                                    </div>

                                    <?php $total_values=$data['chart_data']['count_data']['website'] + $data['chart_data']['count_data']['android'] +$data['chart_data']['count_data']['ios']; $website_precentage=( ($data['chart_data']['count_data']['website']!=0)? ($data[
                                    'chart_data']['count_data']['website']/ $total_values)*100 : 0); $android_precentage=( ($data['chart_data']['count_data']['android']!=0)? ($data['chart_data']['count_data']['android']/ $total_values)*100 : 0); $ios_precentage=(
                                    ($data['chart_data']['count_data']['ios']!=0)? ($data['chart_data']['count_data']['ios']/ $total_values)*100 : 0); ?>

                                    <div class="device-status my-auto">
                                        <div class="device-status-ck">
                                            <canvas class="analytics-doughnut" id="deviceStatusDataChart"></canvas>
                                        </div>
                                        <div class="device-status-group">
                                            <div class="device-status-data">
                                                <em data-color="#798bff" class="icon ni ni-monitor"></em>
                                                <div class="title">Website</div>
                                                <div class="amount">
                                                    <?php echo number_format($website_precentage,2); ?> %</div>
                                            </div>
                                            <div class="device-status-data">
                                                <em data-color="#baaeff" class="icon ni ni-mobile"></em>
                                                <div class="title">Android</div>
                                                <div class="amount">
                                                    <?php echo number_format($android_precentage,2); ?> %</div>
                                            </div>
                                            <div class="device-status-data">
                                                <em data-color="#7de1f8" class="icon ni ni-mobile"></em>
                                                <div class="title">iOS </div>
                                                <div class="amount">
                                                    <?php echo number_format($ios_precentage,2); ?> %</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-12">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title"><span class="mr-2">Top Products</span></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-block">
                                    <div class="card card-shadow">
                                        <div class="card-inner">
                                            <div class="card card-bordered card-preview">
                                                <table class="table table-tranx is-compact">
                                                    <thead>
                                                        <tr class="tb-tnx-head">
                                                            <th class="tb-tnx-id"><span class="">S.no</span>
                                                            </th>
                                                            <th class="tb-tnx-info">
                                                                <span class="tb-tnx-desc d-none d-sm-inline-block">
                                                                        <span>Product Name</span>
                                                                </span>
                                                            </th>
                                                            <th class="tb-tnx-info">
                                                                <span>
                                                                        <span>Category </span>
                                                                </span>
                                                            </th>

                                                    </thead>
                                                    <tbody>
                                                        <?php echo $data['top_products']; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nk-block">
                    <div class="row gy-gs">
                        <div class="col-sm-6 col-lg-12">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title"><span class="mr-2">Order list</span></h6>
                                        </div>
                                        <div class="card-tools">
                                            <ul class="nk-block-tools g-3">
                                                <li class="nk-block-tools-opt">
                                                    <?php if($data['order_list']!="") { ?>
                                                        <button class="btn btn-primary export_order_list"><em class="icon ni ni-reports"></em><span>Export</span>
                                                        </button>
                                                    <?php } ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-block">
                                    <div class="card card-shadow">
                                        <div class="card-inner">
                                            <table class="datatable-init nk-tb-list nk-tb-ulist is-compact" data-auto-responsive="false">
                                                <thead>
                                                    <tr class="nk-tb-item nk-tb-head">
                                                        <th class="nk-tb-col"><span>S.No</span>
                                                        </th>
                                                        <th class="nk-tb-col tb-col-sm"><span>Customer Name</span>
                                                        </th>
                                                        <th class="nk-tb-col tb-col-md"><span>Order Date</span>
                                                        </th>
                                                        <th class="nk-tb-col tb-col-lg"><span>Vendor Name</span>
                                                        </th>
                                                        <th class="nk-tb-col"><span>Amount</span>
                                                        </th>
                                                        <th class="nk-tb-col"><span>Total Charge</span>
                                                        </th>
                                                        <th class="nk-tb-col"><span class="d-none d-sm-inline">Payout</span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php echo $data['order_list']; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- .card -->
                        </div>
                        <div class="col-sm-6 col-lg-12">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title"><span class="mr-2">Cancelled order list</span></h6>
                                        </div>
                                        <div class="card-tools">
                                            <ul class="nk-block-tools g-3">
                                                <li class="nk-block-tools-opt">
                                                    <?php if($data['returned_list']!="") { ?>
                                                        <button class="btn btn-primary export_cancel_order_list"><em class="icon ni ni-reports"></em><span>Export</span>
                                                        </button>
                                                    <?php } ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-block">
                                    <div class="card card-shadow">
                                        <div class="card-inner">
                                            <table class="datatable-init nk-tb-list nk-tb-ulist is-compact" data-auto-responsive="false">
                                                <thead>
                                                    <tr class="nk-tb-item nk-tb-head">
                                                        <th class="nk-tb-col"><span>S.No</span>
                                                        </th>
                                                        <th class="nk-tb-col tb-col-sm"><span>Customer Name</span>
                                                        </th>
                                                        <th class="nk-tb-col tb-col-md"><span>Order Date</span>
                                                        </th>
                                                        <th class="nk-tb-col tb-col-md"><span>Deliverd Date</span>
                                                        </th>
                                                        <th class="nk-tb-col tb-col-lg"><span>Vendor Name</span>
                                                        </th>
                                                        <th class="nk-tb-col"><span>Amount</span>
                                                        </th>
                                                        <th class="nk-tb-col"><span>Cancel Reason</span>
                                                        </th>
                                                        <th class="nk-tb-col"><span class="d-none d-sm-inline">Cancellation date</span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php echo $data['returned_list']; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- .card -->
                                </div>
                                <!-- .col -->
                            </div>
                        </div>
                         <div class="col-sm-6 col-lg-12">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title"><span class="mr-2">Rejected Order list</span></h6>
                                        </div>
                                        <div class="card-tools">
                                            <ul class="nk-block-tools g-3">
                                                <li class="nk-block-tools-opt">
                                                    <?php if($data['rejected_list']!="") { ?>
                                                        <button class="btn btn-primary export_rejected_order_list"><em class="icon ni ni-reports"></em><span>Export</span>
                                                        </button>
                                                    <?php } ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-block">
                                    <div class="card card-shadow">
                                        <div class="card-inner">
                                            <table class="datatable-init nk-tb-list nk-tb-ulist is-compact" data-auto-responsive="false">
                                                <thead>
                                                    <tr class="nk-tb-item nk-tb-head">
                                                        <th class="nk-tb-col"><span>S.No</span>
                                                        </th>
                                                        <th class="nk-tb-col tb-col-sm"><span>Customer Name</span>
                                                        </th>
                                                        <th class="nk-tb-col tb-col-md"><span>Order Date</span>
                                                        </th>
                                                        <th class="nk-tb-col tb-col-lg"><span>Vendor Name</span>
                                                        </th>
                                                        <th class="nk-tb-col"><span>Amount</span>
                                                        </th>
                                                        <th class="nk-tb-col"><span>Total Charge</span>
                                                        </th>
                                                        <th class="nk-tb-col"><span class="d-none d-sm-inline">Response</span>
                                                        </th>
                                                        <th class="nk-tb-col"><span class="d-none d-sm-inline">Response Message</span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php echo $data['rejected_list']; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- .card -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content @e -->

<?php require_once 'includes/bottom.php'; ?> 


    <script type="text/javascript">

        // Open Order Details 

        $(".open_order_details").click(function() {
            var item_id = $(this).data('option');
            window.location = core_path + "orders/orderdetails/" + item_id;
        });

         // Open Order Details 

        $(".rejected_order_details").click(function() {
            var item_id = $(this).data('option');
            window.location = core_path + "orders/notificationdetail/" + item_id;
        });

        // Order list export

        $(".export_order_list").click(function() { 

            var from   = $("#valid_from").val();
            var to     = $("#valid_to").val();
            var url    = core_path;

            if(from=="") {
                var text_msg = "Are you sure to export overall order list ?";
                html_string  = $.parseHTML( text_msg );
                var url      = core_path +"exportData/orderlistreport";
            } else if(from!="") {
                var text_msg = "Are you sure to export <br> order list from <br> " + from + " to " + to +" ?";
                html_string  = $.parseHTML( text_msg );
                var url      = core_path +"exportData/orderlistreport/"+from+"/"+to ;
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
                        window.location = url; 
                    }
                });
        });

         // Rejected Order list export

        $(".export_rejected_order_list").click(function() { 

            var from   = $("#valid_from").val();
            var to     = $("#valid_to").val();
            var url    = core_path;

            if(from=="") {
                var text_msg = "Are you sure to export overall rejected order list ?";
                html_string  = $.parseHTML( text_msg );
                var url      = core_path +"exportData/rejectedorderlistreport";
            } else if(from!="") {
                var text_msg = "Are you sure to export <br> rejected order list from <br> " + from + " to " + to +" ?";
                html_string  = $.parseHTML( text_msg );
                var url      = core_path +"exportData/rejectedorderlistreport/"+from+"/"+to ;
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
                        window.location = url; 
                    }
                });
        });

        // Cancel order list export
        
        $(".export_cancel_order_list").click(function() { 

            var from   = $("#valid_from").val();
            var to     = $("#valid_to").val();
            var vendor = $("#vendorId").val();
            var v_name = $("#vendorId").data('vendor_name');

            if(from=="") {
                var text_msg = "Are you sure to export overall cancel order list ?";
                html_string  = $.parseHTML( text_msg );
                var url      = core_path +"exportData/cancelledorderlistreport";
            } else if(from!="") {
                var text_msg = "Are you sure to export <br> cancel order list from <br> " + from + " to " + to +" ?";
                html_string  = $.parseHTML( text_msg );
                var url      = core_path +"exportData/cancelledorderlistreport/"+from+"/"+to ;
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
                        window.location = url;
                    }
                });
        });
        
    </script>

 <!-- For page actions -->

    <script type="text/javascript">

         $.validator.addMethod("from_date_er", function (value, elem) {
            var from = new Date( $("#valid_from").val().replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"))
            var to = new Date( $("#valid_to").val().replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"))
           return (from <= to);
        });


        $("#orderReportFilter").validate({
        rules: {
            valid_from: {
                required: true,
                from_date_er: true,
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
             window.location = core_path + "reports/index/"+$("#valid_from").val()+"/"+$("#valid_to").val();
            return false;
        }
    });


    </script>

    <!-- For Chart Actions -->

    <?php if($data['chart_data']['sales_platform_true']) { ?>

       <script type="text/javascript">
        // sales plat form  
        var deviceStatusDataChart = {
            labels: ["Website", "Android", "IOS"],
            dataUnit: 'Order',
            legend: false,
            datasets: [{
              borderColor: "#fff",
              background: ["#9cabff", "#b8acff", "#7de1f8"],
              data: <?php echo $data['chart_data']['sales_platform_data']; ?>
            }]
        };
        </script>

        <?php } else { ?>

        <script type="text/javascript">
        // sales plat form  
        var deviceStatusDataChart = {
            labels: ["No Records", "Android", "IOS"],
            dataUnit: '',
            legend: false,
            datasets: [{
              borderColor: "#fff",
              background: ["#f44336", "#b8acff", "#7de1f8"],
              data: [1,0,0]
            }]
        };
        </script>

    <?php }  ?>

    <script type="text/javascript">

        // Sales vs Date chart 

        var filledLineChart = {
            labels: <?php echo $data['chart_data']['x_axis']; ?>,
            dataUnit: 'INR',
            lineTension: .4,
            datasets: [{
              label: "Total Received",
              color: "#9d72ff",
              background: NioApp.hexRGB('#9d72ff', .4),
              data: <?php echo $data['chart_data']['y_axis_sales']; ?>
            }]
        };

        function lineChart(selector, set_data) {
            var $selector = selector ? $(selector) : $('.line-chart');
            $selector.each(function () {
            var $self = $(this),
              _self_id = $self.attr('id'),
              _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

            var selectCanvas = document.getElementById(_self_id).getContext("2d");
            var chart_data = [];

          for (var i = 0; i < _get_data.datasets.length; i++) {
            chart_data.push({
              label: _get_data.datasets[i].label,
              tension: _get_data.lineTension,
              backgroundColor: _get_data.datasets[i].background,
              borderWidth: 2,
              borderColor: _get_data.datasets[i].color,
              pointBorderColor: _get_data.datasets[i].color,
              pointBackgroundColor: '#fff',
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: _get_data.datasets[i].color,
              pointBorderWidth: 2,
              pointHoverRadius: 4,
              pointHoverBorderWidth: 2,
              pointRadius: 4,
              pointHitRadius: 4,
              data: _get_data.datasets[i].data
            });
          }

          var chart = new Chart(selectCanvas, {
            type: 'line',
            data: {
              labels: _get_data.labels,
              datasets: chart_data
            },
            options: {
              legend: {
                display: _get_data.legend ? _get_data.legend : false,
                rtl: NioApp.State.isRTL,
                labels: {
                  boxWidth: 12,
                  padding: 20,
                  fontColor: '#6783b8'
                }
              },
              maintainAspectRatio: false,
              tooltips: {
                enabled: true,
                rtl: NioApp.State.isRTL,
                callbacks: {
                  title: function title(tooltipItem, data) {
                    return data['labels'][tooltipItem[0]['index']];
                  },
                  label: function label(tooltipItem, data) {
                    return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
                  }
                },
                backgroundColor: '#eff6ff',
                titleFontSize: 13,
                titleFontColor: '#6783b8',
                titleMarginBottom: 6,
                bodyFontColor: '#9eaecf',
                bodyFontSize: 12,
                bodySpacing: 4,
                yPadding: 10,
                xPadding: 10,
                footerMarginTop: 0,
                displayColors: false
              },
              scales: {
                yAxes: [{
                  display: true,
                  position: NioApp.State.isRTL ? "right" : "left",
                  ticks: {
                    beginAtZero: false,
                    fontSize: 12,
                    fontColor: '#9eaecf',
                    padding: 10
                  },
                  gridLines: {
                    color: NioApp.hexRGB("#526484", .2),
                    tickMarkLength: 0,
                    zeroLineColor: NioApp.hexRGB("#526484", .2)
                  }
                }],
                xAxes: [{
                  display: true,
                  ticks: {
                    fontSize: 12,
                    fontColor: '#9eaecf',
                    source: 'auto',
                    padding: 5,
                    reverse: NioApp.State.isRTL
                  },
                  gridLines: {
                    color: "transparent",
                    tickMarkLength: 10,
                    zeroLineColor: NioApp.hexRGB("#526484", .2),
                    offsetGridLines: true
                  }
                }]
              }
            }
          });
            });
        } // init line chart
        lineChart();
    </script> 


