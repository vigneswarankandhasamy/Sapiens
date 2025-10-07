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
                                        <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a>
                                        </li>
                                        <li class="breadcrumb-item active">
                                            <?php echo $data[ 'page_title'] ?>
                                        </li>
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
                                        <!-- <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em class="icon ni ni-arrow-down"></em><span>Export</span></a>
                                        </li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- .nk-block-head-content -->
                    </div>
                    <!-- .nk-block-between -->
                </div>
                <!-- .nk-block-head -->
                <?php if($data['token']=="neworders" || $data['token']=="") { ?>
                    <?php if($data['list']['new_orders']!="") { ?>
                        <div class="nk-block nk-block-lg">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h5 class="nk-block-title title">New Orders</h5>
                                </div>
                            </div>
                            <div class="card card-preview">
                                <div class="card-inner">
                                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                 <th class="nk-tb-col"><span>S.No</span>
                                                </th>
                                                <th class="nk-tb-col"><span>Invoice No</span>
                                                </th>
                                                 <th class="nk-tb-col tb-col-md"><span>Order Date</span>
                                                </th>
                                                <th class="nk-tb-col"><span class="sub-text">Customer Info</span>
                                                </th>
                                                <th class="nk-tb-col"><span class="sub-text">Vendor Info</span>
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Purchased</span>
                                                </th>
                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Commission</span>
                                                </th>
                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Total</span>
                                                </th>
                                                 <th class="nk-tb-col tb-col-md"><span class="sub-text">Response Status</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $data['list']['new_orders']; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php if($data['token']=="returnedorders" || $data['token']=="") { ?>
                    <?php if($data['list']['return_orders']!="") { ?>
                        <div class="nk-block nk-block-lg">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h5 class="nk-block-title title">Returned Orders</h5>
                                </div>
                            </div>
                            <div class="card card-preview">
                                <div class="card-inner">
                                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                 <th class="nk-tb-col"><span>S.No</span>
                                                </th>
                                                <th class="nk-tb-col"><span>Invoice No</span>
                                                </th>
                                                 <th class="nk-tb-col tb-col-md"><span>Order Date</span>
                                                </th>
                                                <th class="nk-tb-col"><span class="sub-text">Customer Info</span>
                                                </th>
                                                <th class="nk-tb-col"><span class="sub-text">Vendor Info</span>
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Purchased</span>
                                                </th>
                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Commission</span>
                                                </th>
                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Total</span>
                                                </th>
                                                 <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $data['list']['return_orders']; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    $(".new_order_detail").click(function() { 
        var id = $(this).data("option");
        window.location = core_path + "orders/notificationdetail/"+ id;
    });

    $(".return_order_detail").click(function() { 
        var id = $(this).data("option");
        window.location = core_path + "orders/returnedorderdetail/"+ id;
    });

    $(".open_rejected_order_details").click(function() { 
        var id = $(this).data("option");
        window.location = core_path + "orders/rejectedorderdetail/"+ id;
    });
    
</script>

<?php if (isset($_GET['or'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Order response updated successfully  !!</h5>', 'success', {
        position: 'bottom-center', 
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

<?php if (isset($_GET['ore'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Sorry!! order response status already changed !!</h5>', 'error', {
        position: 'bottom-center', 
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