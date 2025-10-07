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
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <?php if(1 != 1) { ?>
                                            <!-- <li class="date_input">
                                                <input type="text" name="valid_from"  id="valid_from" autocomplete="off" class="form-control date-picker" data-date-format="dd-mm-yyyy" placeholder="From Date">
                                            </li>
                                            <li class="date_input">
                                                <input type="text" name="validity_to" id="validity_to" class="form-control date-picker" data-date-format="dd-mm-yyyy" autocomplete="off" placeholder="To Date" >
                                            </li> -->
                                            <?php  } ?>
                                            <li class="sellect_vendor">
                                                <div class="form-group sellect_vendor_drp">
                                                    <div class="form-control-wrap">
                                                        <select class="form-select" name="vendor_id" id="vendorId" data-search="on">
                                                            <option value=" ">Select Vendor </option>
                                                           <?php echo $data['get_vendors'] ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="nk-block-tools-opt"><button class="btn btn-primary" id="vendorOrderSort"><em class="icon ni ni-reports"></em><span>Reports</span></button></li>

                                        </ul>
                                      <div class="form-group form-error  display_none">Select Vendor</div>

                                    </div>
                            </div>
                        </div>
                        <!-- .nk-block-head-content -->
                    </div>
                    <!-- .nk-block-between -->
                </div>
                
                <!-- .nk-block-head -->
                <div class="nk-block nk-block-lg">
                    <div class="card card-preview">
                        <div class="card-inner">

                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabItem1">Today</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabItem2">Yesterday</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabItem3">Oldest</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabItem1">
                                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th class="nk-tb-col">S.No
                                                </th>
                                                 <th class="nk-tb-col"><span class="sub-text">Vendor</span>
                                                </th>
                                                 <th class="nk-tb-col tb-col-md">Total orders
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Order value</span>
                                                </th>
                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Total Charge</span>
                                                </th>
                                                 <th class="nk-tb-col tb-col-md">Unpaid orders
                                                </th>
                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Payable</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="vendorTBL">
                                            <?php echo $data['today_list']; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tabItem2">
                                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th class="nk-tb-col">S.No
                                                </th>
                                                 <th class="nk-tb-col"><span class="sub-text">Vendor</span>
                                                </th>
                                                 <th class="nk-tb-col tb-col-md">Total orders
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Order value</span>
                                                </th>
                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Total Charge</span>
                                                </th>
                                                 <th class="nk-tb-col tb-col-md">Unpaid orders
                                                </th>
                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Payable</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="vendorTBL">
                                            <?php echo $data['yesterday_list']; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tabItem3">
                                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th class="nk-tb-col">S.No
                                                </th>
                                                 <th class="nk-tb-col"><span class="sub-text">Vendor</span>
                                                </th>
                                                 <th class="nk-tb-col tb-col-md">Total orders
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Order value</span>
                                                </th>
                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Total Charge</span>
                                                </th>
                                                 <th class="nk-tb-col tb-col-md">Unpaid orders
                                                </th>
                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Payable</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="vendorTBL">
                                            <?php echo $data['oldest_list']; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- .card-preview -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
    // Open payout details page 

    $(".open_payout_details").click(function() {
        var item_id = $(this).data("option");
        var period  = $(this).data("period");
        window.location = core_path + "orders/vendorpayout/" + period + "/" + item_id;
    });
    
</script>

<script type="text/javascript">

     $("#vendorOrderSort").click(function() {
        if($("#vendorId").val()!=" ") {
            $(".form-error").hide();
            window.location = core_path + "orders/vendorpayouts/"+$("#vendorId").val();
        } else {
            $(".form-error").show();
        }
    });
    

</script>


<?php if (isset($_GET['sh'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Orderr shipment status updated successfully !!</h5>', 'success', {
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

<?php if (isset($_GET['p'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Order payment status updated successfully  !!</h5>', 'success', {
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