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
                                    <p>Sale analytics overview in dashboard.</p>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li>
                                                <div class="drodown">
                                                    <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-toggle="dropdown"><em class="d-none d-sm-inline icon ni ni-calender-date"></em><span class="button_name"><span class="d-none d-md-inline button_name_ltr">Today</a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="link-list-opt no-bdr">
                                                            <li><a class="get_Chart_data" data-option="today_records"><span>Today</span></a></li>
                                                            <li><a class="get_Chart_data" data-option="last_seven_days"><span>Last 7 Days</span></a></li>
                                                            <li><a class="get_Chart_data" data-option="last_month"><span>Last 30 Days</span></a></li>
                                                            <li><a class="get_Chart_data" data-option="last_three_month"><span>Last 3 Months</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="nk-block">
                        <div class="row gy-gs">
                            <div class="col-md-6 col-lg-3">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                               <div class="row">
                                                    <div class="col-md-8">
                                                        <h6 class="title">Active Orders </h6>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="title filter_date_title text-right">Today's</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount "> 
                                                    <div class="card_total_order">
                                                        <?php echo (($data['card_data']['activeOrders'])? $data['card_data']['activeOrders']  : 0 ); ?>    
                                                        </div>  
                                                    <?php if($data['card_data']['activeOrders']!='0') {  ?>
                                                    <span class="change up viewall_link"><a href="<?php echo COREPATH ?>orders/vendoractiveorders">View All</a></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                               <div class="row">
                                                    <div class="col-md-8">
                                                        <h6 class="title">Pending Orders </h6>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="title filter_date_title text-right">Today's</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount "> 
                                                    <div class="card_total_order">
                                                        <?php echo (($data['card_data']['pendingOrders'])? $data['card_data']['pendingOrders']  : 0 ); ?>
                                                        </div>  
                                                    <?php if($data['card_data']['pendingOrders']!='0') {  ?>
                                                    <span class="change up viewall_link"><a href="<?php echo COREPATH ?>orders/notification">View All</a></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h6 class="title">Total rejected orders </h6>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="title filter_date_title text-right">Today's</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount "> <div class="card_order_rejected"> <?php echo $data['card_data']['vendorRejectedOrders']; ?> </div> 
                                                <?php if($data['card_data']['vendorRejectedOrders']!='0') {  ?>
                                                <span class="change up viewall_link"><a href="<?php echo COREPATH ?>orders/vendorrejectedorders">View All</a></span>
                                                 <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <h6 class="title">Instock</h6>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount"> <?php echo (($data['card_data'])? $data['card_data']['instock_count'] : 0) ?>
                                                <?php if($data['card_data']['instock_count']!='0') {  ?>
                                                <span class="change up viewall_link"><a href="<?php echo COREPATH ?>products/inventory/instock">View All</a></span>
                                                 <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <h6 class="title">Low Stock</h6>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount"> <?php echo (($data['card_data'])? $data['card_data']['low_stock_count'] : 0)?> 
                                                <?php if($data['card_data']['low_stock_count']!='0') {  ?>
                                                 <span class="change up viewall_link"><a href="<?php echo COREPATH ?>products/inventory/lowstock">View All</a></span>
                                                <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <h6 class="title">Out of Stock</h6>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount"> <?php echo (($data['card_data'])? $data['card_data']['out_of_stock_count'] : 0)?> 
                                                <?php if($data['card_data']['out_of_stock_count']!='0') {  ?>
                                                 <span class="change up viewall_link"><a href="<?php echo COREPATH ?>products/inventory/outofstock">View All</a>

                                                <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                               <div class="row">
                                                    <div class="col-md-8">
                                                        <h6 class="title">Total Sales </h6>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="title filter_date_title text-right">Today's</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount "> <div class="card_total_amount">₹ <?php echo number_format($data['card_data']['totalAmount'],2); ?> </div>
                                                <?php if($data['card_data']['totalAmount']!='0') {  ?>
                                                    <span class="change up viewall_link"><a href="<?php echo COREPATH ?>orders">View All</a>
                                                <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h6 class="title">Total Charge  </h6>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="title filter_date_title text-right">Today's</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount "> <div class="card_commission_earned"> ₹ <?php echo number_format($data['card_data']['vendorCommissionAmt']+$data['card_data']['vendorPayChargeAmt']+$data['card_data']['vendorShipChargeAmt'],2); ?>  </div>
                                                <?php if($data['card_data']['vendorCommissionAmt']+$data['card_data']['vendorPayChargeAmt']+$data['card_data']['vendorShipChargeAmt']!='0') {  ?>
                                                <span class="change up viewall_link"><a href="<?php echo COREPATH ?>orders/payouts/paid">View All</a></span>
                                                <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h6 class="title">Vendor Payout </h6>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="title filter_date_title text-right">Today's</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount "> 
                                                    <div class="card_vendor_payout"> ₹ <?php echo number_format($data['card_data']['totalAmount']-($data['card_data']['vendorCommissionAmt']+$data['card_data']['vendorPayChargeAmt']+$data['card_data']['vendorShipChargeAmt']),2); ?> </div> 
                                                <span class="change up viewall_link"><span class="">
                                                <?php if($data['card_data']['totalAmount']-($data['card_data']['vendorCommissionAmt']+$data['card_data']['vendorPayChargeAmt']+$data['card_data']['vendorShipChargeAmt'])!='0') {  ?>
                                                    </span><a href="<?php echo COREPATH ?>orders/payouts/paid">View All</a>
                                                <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                               <div class="row">
                                                    <div class="col-md-8">
                                                        <h6 class="title">Total Orders </h6>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="title filter_date_title text-right">Today's</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount "> 
                                                    <div class="card_total_order"><?php echo $data['card_data']['totalOrders']; ?></div>  
                                                    <?php if($data['card_data']['totalOrders']!='0') {  ?>
                                                    <span class="change up viewall_link"><a href="<?php echo COREPATH ?>orders">View All</a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h6 class="title">Total order returned </h6>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="title filter_date_title text-right">Today's</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount "> 
                                                    <div class="card_order_returned"> <?php echo $data['card_data']['returnedOrders']; ?> </div> 
                                                    <?php if($data['card_data']['returnedOrders']!='0') {  ?>
                                                    <span class="change up viewall_link"><a href="<?php echo COREPATH ?>orders">View All</a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  <div class="nk-block">
                        <div class="row g-gs">
                            <div class="col-lg-7">
                                <div class="card card-bordered h-100">
                                    <div class="card-inner">
                                        <div class="card-title-group pb-3 g-2">
                                            <div class="card-title card-title-sm">
                                                <h6 class="title">Total Sales :</h6>
                                                <!-- <p>How have your users, sessions, bounce rate metrics trended.</p> -->
                                            </div>
                                            <div class="card-tools shrink-0 d-none d-sm-block">
                                                <!-- <ul class="nav nav-switch-s2 nav-tabs bg-white"> -->
                                                    <!-- <li class="nav-item"><a  class="nav-link total_sale_for today_day_btn active " data-option="last_seven_days">Today</a></li> -->
                                                   <!--  <li class="nav-item"><a  class="nav-link total_sale_for 7_day_btn  active" data-option="last_seven_days">7 D</a></li>
                                                    <li class="nav-item"><a  class="nav-link total_sale_for 1_month_btn" data-option="last_month">1 M</a></li>
                                                    <li class="nav-item"><a  class="nav-link total_sale_for 3_month_btn" data-option="last_three_month">3 M</a></li>
                                                </ul> -->
                                            </div>
                                        </div>
                                        <div class="analytic-ov">
                                            <div class="analytic-data-group analytic-ov-group g-3">
                                                <div class="analytic-data analytic-ov-data">
                                                    <div class="title filter_date_title total_sale_for_title">Today's</div>
                                                    <div class="amount chart_amt totalSalesValue"> </div>
                                                    <!-- <div class="change up"><em class="icon ni ni-arrow-long-up"></em>12.37%</div> -->
                                                </div>
                                                <!-- <div class="analytic-data analytic-ov-data">
                                                    <div class="title">Sessions</div>
                                                    <div class="amount chart_amt">3.98K</div>
                                                    <div class="change up"><em class="icon ni ni-arrow-long-up"></em>47.74%</div>
                                                </div>
                                                <div class="analytic-data analytic-ov-data">
                                                    <div class="title">Users</div>
                                                    <div class="amount chart_amt">28.49%</div>
                                                    <div class="change down"><em class="icon ni ni-arrow-long-down"></em>12.37%</div>
                                                </div>
                                                <div class="analytic-data analytic-ov-data">
                                                    <div class="title">Users</div>
                                                    <div class="amount chart_amt">7m 28s</div>
                                                    <div class="change down"><em class="icon ni ni-arrow-long-down"></em>0.35%</div>
                                                </div> -->
                                            </div>
                                            <div class="analytic-ov-ck">
                                                <canvas class="analytics-line-large" id="analyticOvData"></canvas>
                                            </div>
                                            <div class="chart-label-group chart_main_lable ml-5">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="col-lg-5">
                                <div class="card card-bordered h-100">
                                    <div class="card-inner">
                                        <div class="card-title-group pb-3 g-2">
                                            <div class="card-title card-title-sm">
                                                <h6 class="title">Average Order Value :</h6>
                                                <!-- <p>How have your users, sessions, bounce rate metrics trended.</p> -->
                                            </div>
                                            <div class="card-tools shrink-0 d-none d-sm-block">
                                                <!-- <ul class="nav nav-switch-s2 nav-tabs bg-white"> -->
                                                    <!-- <li class="nav-item "><a  class="nav-link avg_sale_for today_day_btn active" data-option="last_seven_days">Today</a></li> -->
                                                    <!-- <li class="nav-item "><a  class="nav-link avg_sale_for 7_day_btn active" data-option="last_seven_days">7 D</a></li>
                                                    <li class="nav-item "><a  class="nav-link avg_sale_for 1_month_btn" data-option="last_month">1 M</a></li>
                                                    <li class="nav-item "><a  class="nav-link avg_sale_for 3_month_btn" data-option="last_three_month">3 M</a></li>
                                                </ul> -->
                                            </div>
                                        </div>
                                        <div class="analytic-ov">
                                            <div class="analytic-data-group analytic-ov-group g-3">
                                                <div class="analytic-data analytic-ov-data">
                                                    <div class="title filter_date_title avg_sale_for_title">Today's </div>
                                                    <div class="amount chart_amt avgSalesValue"> </div>
                                                    <!-- <div class="change up"><em class="icon ni ni-arrow-long-up"></em>12.37%</div> -->
                                                </div>
                                                <!-- <div class="analytic-data analytic-ov-data">
                                                    <div class="title">Sessions</div>
                                                    <div class="amount chart_amt">3.98K</div>
                                                    <div class="change up"><em class="icon ni ni-arrow-long-up"></em>47.74%</div>
                                                </div>
                                                <div class="analytic-data analytic-ov-data">
                                                    <div class="title">Users</div>
                                                    <div class="amount chart_amt">28.49%</div>
                                                    <div class="change down"><em class="icon ni ni-arrow-long-down"></em>12.37%</div>
                                                </div>
                                                <div class="analytic-data analytic-ov-data">
                                                    <div class="title">Users</div>
                                                    <div class="amount chart_amt">7m 28s</div>
                                                    <div class="change down"><em class="icon ni ni-arrow-long-down"></em>0.35%</div>
                                                </div> -->
                                            </div>
                                            <div class="analytic-ov-ck">
                                                <canvas class="analytics-line-large" id="analyticOvData2"></canvas>
                                            </div>
                                            <div class="chart-label-group chart_main_lable ml-5">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="col-lg-7">
                                <div class="card card-bordered h-100">
                                    <div class="card-inner">
                                        <div class="card-title-group pb-3 g-2">
                                            <div class="card-title card-title-sm">
                                                <h6 class="title">Total Orders :</h6>
                                                <!-- <p>How have your users, sessions, bounce rate metrics trended.</p> -->
                                            </div>
                                            <div class="card-tools shrink-0 d-none d-sm-block">
                                                <!-- <ul class="nav nav-switch-s2 nav-tabs bg-white"> -->
                                                    <!-- <li class="nav-item"><a  class="nav-link total_order_for today_day_btn active" data-option="last_seven_days">Today</a></li> -->
                                                    <!-- <li class="nav-item"><a  class="nav-link total_order_for 7_day_btn active" data-option="last_seven_days">7 D</a></li>
                                                    <li class="nav-item"><a  class="nav-link total_order_for 1_month_btn" data-option="last_month">1 M</a></li>
                                                    <li class="nav-item"><a  class="nav-link total_order_for 3_month_btn" data-option="last_three_month">3 M</a></li>
                                                </ul> -->
                                            </div>
                                        </div>
                                        <div class="analytic-ov">
                                            <div class="analytic-data-group analytic-ov-group g-3">
                                                <div class="analytic-data analytic-ov-data">
                                                    <div class="title filter_date_title total_order_for_title">Today's </div>
                                                    <div class="amount chart_amt totalOrderValue"></div>
                                                    <!-- <div class="change up"><em class="icon ni ni-arrow-long-up"></em>12.37%</div> -->
                                                </div>
                                               <!--  <div class="analytic-data analytic-ov-data">
                                                    <div class="title">Sessions</div>
                                                    <div class="amount chart_amt">3.98K</div>
                                                    <div class="change up"><em class="icon ni ni-arrow-long-up"></em>47.74%</div>
                                                </div>
                                                <div class="analytic-data analytic-ov-data">
                                                    <div class="title">Users</div>
                                                    <div class="amount chart_amt">28.49%</div>
                                                    <div class="change down"><em class="icon ni ni-arrow-long-down"></em>12.37%</div>
                                                </div>
                                                <div class="analytic-data analytic-ov-data">
                                                    <div class="title">Users</div>
                                                    <div class="amount chart_amt">7m 28s</div>
                                                    <div class="change down"><em class="icon ni ni-arrow-long-down"></em>0.35%</div>
                                                </div> -->
                                            </div>
                                            <div class="analytic-ov-ck">
                                                <canvas class="analytics-line-large" id="analyticOvData3"></canvas>
                                            </div>
                                            <div class="chart-label-group chart_main_lable ml-5">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                  </div>
                  <div class="nk-block">
                        <div class="row g-gs">
                            <div class="col-md-6 col-lg-12">
                                <div class="card card-bordered card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title"><span class="mr-2">Instock list</span></h6>
                                            </div>
                                            <div class="card-tools">
                                                <ul class="nk-block-tools g-3">
                                                    <?php if($data['InventoryList']!="") { ?>
                                                    <li class="nk-block-tools-opt">
                                                        <!-- <button  class="btn btn-primary export_cancel_order_list"><em class="icon ni ni-reports"></em><span>Export</span>
                                                            </button> -->
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-block">
                                        <div class="card card-shadow">
                                            <div class="card-inner">
                                                <table id="table_detail" class="datatable-init nk-tb-list nk-tb-ulist is-compact dataTable no-footer" data-auto-responsive="false">
                                                    <thead>
                                                        <tr class="nk-tb-item nk-tb-head">
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span></th>
                                                            <th class="nk-tb-col"><span class="sub-text">Image</span></th>
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Product </span></th>
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Category </span></th>
                                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Actual Price</span></th>
                                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Selling Price</span></th>
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Inventory </span></th>
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Stock Status </span></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php echo $data['InventoryList']['instock']; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-12">
                                <div class="card card-bordered card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title"><span class="mr-2">Low Stock list</span></h6>
                                            </div>
                                            <div class="card-tools">
                                                <ul class="nk-block-tools g-3">
                                                    <?php if($data['InventoryList']!="") { ?>
                                                    <li class="nk-block-tools-opt">
                                                        <!-- <button  class="btn btn-primary export_cancel_order_list"><em class="icon ni ni-reports"></em><span>Export</span>
                                                            </button> -->
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-block">
                                        <div class="card card-shadow">
                                            <div class="card-inner">
                                                <table id="table_detail" class="datatable-init nk-tb-list nk-tb-ulist is-compact dataTable no-footer" data-auto-responsive="false">
                                                    <thead>
                                                        <tr class="nk-tb-item nk-tb-head">
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span></th>
                                                            <th class="nk-tb-col"><span class="sub-text">Image</span></th>
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Product </span></th>
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Category </span></th>
                                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Actual Price</span></th>
                                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Selling Price</span></th>
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Inventory </span></th>
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Stock Status </span></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php echo $data['InventoryList']['low_stock']; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-12">
                                <div class="card card-bordered card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title"><span class="mr-2">Out Of Stock list</span></h6>
                                            </div>
                                            <div class="card-tools">
                                                <ul class="nk-block-tools g-3">
                                                    <?php if($data['InventoryList']!="") { ?>
                                                    <li class="nk-block-tools-opt">
                                                        <!-- <button  class="btn btn-primary export_cancel_order_list"><em class="icon ni ni-reports"></em><span>Export</span>
                                                            </button> -->
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-block">
                                        <div class="card card-shadow">
                                            <div class="card-inner">
                                                <table id="table_detail" class="datatable-init nk-tb-list nk-tb-ulist is-compact dataTable no-footer" data-auto-responsive="false">
                                                    <thead>
                                                        <tr class="nk-tb-item nk-tb-head">
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span></th>
                                                            <th class="nk-tb-col"><span class="sub-text">Image</span></th>
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Product </span></th>
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Category </span></th>
                                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Actual Price</span></th>
                                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Selling Price</span></th>
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Inventory </span></th>
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Stock Status </span></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php echo $data['InventoryList']['out_of_stock']; ?>
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
            </div>
        </div>
    </div>

<?php require_once 'includes/bottom.php'; ?> 


<script type="text/javascript">

    window.onload = () => {
         $.ajax({
            type: "POST",
            url: core_path + "home/api/getChartDatas",
            dataType: "html",
            data: { result: 'today_records' },
            beforeSend: function() {
                $(".page_loading").show();
                $(".page_loading").hide();
                
            },
            success: function(data) {
                var obj = JSON.parse(data);

                $(".chart_main_lable").html(obj.main_lable);

                // Total Sale chart
                var currentData  = arrayHandle(obj.current_data_total_sales);
                var previousData = arrayHandle(obj.previous_data_total_sales);
                var graphLable  =  obj.graph_lable ;
                
                setChartData(currentData,previousData,graphLable);
                $(".page_loading").hide();
                var totalSales = "₹" + obj.total_sales_data;
                $('.totalSalesValue').html(totalSales);

                // Average order value chart

                var currentData  = arrayHandle(obj.current_data_average_total);
                var previousData = arrayHandle(obj.previous_data_average_total);
                setAverageChartData(currentData,previousData,graphLable);
                $(".page_loading").hide();
                var avgSales = "₹" + obj.average_total_data;
                $('.avgSalesValue').html(avgSales);

                // Total order chart

                var currentData  = arrayHandle(obj.current_data_total_orders);
                var previousData = arrayHandle(obj.previous_data_total_orders);
                setTotalOrderChartData(currentData,previousData,graphLable);
                $(".page_loading").hide();
                $('.totalOrderValue').html(obj.total_orders_data);
            }
        });
    }

    $(".get_Chart_data").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        if(value=='last_month') {
            $('.button_name').html("Last 30 Days");
            $('.filter_date_title').html("Last 30 Days");
            $(".7_day_btn").removeClass("active");
            $(".1_month_btn").addClass("active");
            $(".3_month_btn").removeClass("active");

        } else if(value=='last_three_month') {
            $('.button_name').html("Last 3 Months");
            $('.filter_date_title').html("Last 3 Months");
            $(".7_day_btn").removeClass("active");
            $(".1_month_btn").removeClass("active");
            $(".3_month_btn").addClass("active");

        } else if(value=='last_seven_days') {
            $('.button_name').html("Last 7 Days");
            $('.filter_date_title').html("Last 7 Days");
            $(".7_day_btn").addClass("active");
            $(".1_month_btn").removeClass("active");
            $(".3_month_btn").removeClass("active");
        }
        else if(value=='today_records') {
            $('.button_name').html("Today");
            $('.filter_date_title').html("Today's");
            $(".7_day_btn").addClass("active");
            $(".1_month_btn").removeClass("active");
            $(".3_month_btn").removeClass("active");
        }
        $('.dropdown-menu-right').removeClass('show');

        $.ajax({
            type: "POST",
            url: core_path + "home/api/getChartDatas",
            dataType: "html",
            data: { result: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                var obj = JSON.parse(data);

                $(".chart_main_lable").html(obj.main_lable);

                 // Set card datas
                $(".card_total_order").html(obj.card_total_order);
                $(".card_order_returned").html(obj.card_order_returned);
                $(".card_order_rejected").html(obj.card_order_rejected);
                $(".card_total_amount").html("₹ " + obj.card_total_amount);
                $(".card_avg_value").html("₹ " + obj.card_avg_value);
                $(".card_commission_earned").html("₹ " + obj.card_commission_earned);
                $(".card_vendor_payout").html("₹ " + obj.card_vendor_payout);

                var graphLable  =  obj.graph_lable ;


                // Total Sale chart
                var currentData  = arrayHandle(obj.current_data_total_sales);
                var previousData = arrayHandle(obj.previous_data_total_sales);
                setChartData(currentData,previousData,graphLable);
                $(".page_loading").hide();
                var totalSales = "₹" + obj.total_sales_data;
                $('.totalSalesValue').html(totalSales);

                // Average order value chart

                var currentData  = arrayHandle(obj.current_data_average_total);
                var previousData = arrayHandle(obj.previous_data_average_total);
                setAverageChartData(currentData,previousData,graphLable);
                $(".page_loading").hide();
                var avgSales = "₹" + obj.average_total_data;
                $('.avgSalesValue').html(avgSales);

                // Total order chart

                var currentData  = arrayHandle(obj.current_data_total_orders);
                var previousData = arrayHandle(obj.previous_data_total_orders);
                setTotalOrderChartData(currentData,previousData,graphLable);
                $(".page_loading").hide();
                $('.totalOrderValue').html(obj.total_orders_data);
            }
        });
        return false;
    });
    
    /*-----------------------------------------------------
                    Total Sales Chart 
    -----------------------------------------------------*/

    var analyticOvData = {}
    var setChartData = function(currentData = [], previousData = [], lables = [] ) {
        if(!currentData.length) {
            return
        }
        if(!previousData.length) {
            return
        }

        lables = lables.split(",");
       
        analyticOvData = {
         labels: lables,
            dataUnit: 'People',
            lineTension: .1,
            datasets: [{
              label: "Current Month",
              color: "#c4cefe",
              dash: [5],
              background: "transparent",
              data : previousData,
            }, {
              label: "Current Month",
              color: "#798bff",
              dash: 0,
              background: NioApp.hexRGB('#798bff', .15),
              data :  currentData ,
            }]
      };
      lineChartInit();
    }

    $(".total_sale_for").click(function() {
        toastr.clear();
        $('.total_sale_for').removeClass("active");
        var value = $(this).data("option");
        $(this).addClass("active");
        $.ajax({
            type: "POST",
            url: core_path + "home/api/getOrderTotals",
            dataType: "html",
            data: { result: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                var obj = JSON.parse(data);
                var currentData  = arrayHandle(obj[0]);
                var previousData = arrayHandle(obj[1]);
                setChartData(currentData,previousData);
                $(".page_loading").hide();
                var totalSales = "₹" + obj[2];
                $('.totalSalesValue').html(totalSales);
            }
        });
        return false;
    });

    function arrayHandle(data) {
        data = data.replace(/(\r\n|\n|\r)/gm, "");
        data = data.replaceAll(' ', "");
        data = data.replaceAll('"', "");
        data = data.split(',');
        data = data.map(d => parseInt(d));
        return data;
    }

    /*-----------------------------------------------------
                   Average Order Chart 
    -----------------------------------------------------*/
          
    var analyticOvData2 = {} 
    var setAverageChartData = function (currentData = [] ,previousData = [], lables = [] ) {
        if(!currentData.length) {
            return
        }
        if(!previousData.length) {
            return
        }

        lables = lables.split(",");

        analyticOvData2 = {
            labels: lables,
            dataUnit: 'People',
            lineTension: .1,
            datasets: [{
              label: "Current Month",
              color: "#c4cefe",
              dash: [5],
              background: "transparent",
              data : previousData,
            }, {
              label: "Current Month",
              color: "#798bff",
              dash: 0,
              background: NioApp.hexRGB('#798bff', .15),
              data :  currentData ,
            }]
      };
      lineChartInit();
    }

    $(".avg_sale_for").click(function() {
        toastr.clear();
        $('.avg_sale_for').removeClass("active");
        var value = $(this).data("option");
        $(this).addClass("active");
        $.ajax({
            type: "POST",
            url: core_path + "home/api/getOrderAverage",
            dataType: "html",
            data: { result: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                var obj = JSON.parse(data);
                var currentData = arrayHandle(obj[0]);
                var previousData    = arrayHandle(obj[1]);
                setAverageChartData(currentData,previousData);
                $(".page_loading").hide();
                var avgSales = "₹" + obj[2];
                $('.avgSalesValue').html(avgSales);
            }
        });
        return false;
    });

    function arrayHandle(data) {
        data = data.replace(/(\r\n|\n|\r)/gm, "");
        data = data.replaceAll(' ', "");
        data = data.replaceAll('"', "");
        data = data.split(',');
        data = data.map(d => parseInt(d));
        return data;
    }

    /*-----------------------------------------------------
                    Total Orders Chart 
    -----------------------------------------------------*/

          
    var analyticOvData3 = {} 
    var setTotalOrderChartData = function (currentData = [] ,previousData = [], lables = [] ) {
        if(!currentData.length) {
            return
        }
        if(!previousData.length) {
            return
        }

        lables = lables.split(",");

        analyticOvData3 = {
             labels: lables,
            dataUnit: 'People',
            lineTension: .1,
            datasets: [{
              label: "Current Month",
              color: "#c4cefe",
              dash: [5],
              background: "transparent",
              data : previousData,
            }, {
              label: "Current Month",
              color: "#798bff",
              dash: 0,
              background: NioApp.hexRGB('#798bff', .15),
              data :  currentData ,
            }]
      };
      lineChartInit();
    }

    $(".total_order_for").click(function() {
        toastr.clear();
        $('.total_order_for').removeClass("active");
        var value = $(this).data("option");
        $(this).addClass("active");
        $.ajax({
            type: "POST",
            url: core_path + "home/api/getTotalOrders",
            dataType: "html",
            data: { result: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                var obj = JSON.parse(data);
                var currentData = arrayHandle(obj[0]);
                var previousData    = arrayHandle(obj[1]);
                setTotalOrderChartData(currentData,previousData);
                $(".page_loading").hide();
                $('.totalOrderValue').html(obj[2]);
            }
        });
        return false;
    });

    function arrayHandle(data) {
        data = data.replace(/(\r\n|\n|\r)/gm, "");
        data = data.replaceAll(' ', "");
        data = data.replaceAll('"', "");
        data = data.split(',');
        data = data.map(d => parseInt(d));
        return data;
    }
</script>