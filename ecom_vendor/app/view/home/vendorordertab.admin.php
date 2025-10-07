<?php require_once 'includes/top.php'; ?> 

<style type="text/css">
.data-item {
    position: relative;
    padding: 1rem 1.25rem;
    display: block; 
    align-items: center;
}
</style>
<!-- content @s -->
    <div class="nk-content nk-content-lg nk-content-fluid">
        <div class="container-xl wide-lg">
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
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>ordersettings">Orders</a>
                                            </li>
                                            <li class="breadcrumb-item active">
                                                <?php echo $data[ 'page_title'] ?>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabItem1">Total Order</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabItem2">New Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabItem3">Returned Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabItem4">Rejected Orders</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabItem1">
                            <div class="nk-block">
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
                                                    <th class="nk-tb-col"><span class="sub-text">Customer Name</span>
                                                    </th>
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Purchased</span>
                                                    </th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Total Charge</span>
                                                    </th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Total</span>
                                                    </th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo $data['manageorderlist']; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabItem2">
                            <div class="nk-block">
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
                                                    <th class="nk-tb-col"><span class="sub-text">Customer Name</span>
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
                                                <?php echo $data['list']['new_orders']; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabItem3">
                            <div class="nk-block">
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
                                                    <th class="nk-tb-col"><span class="sub-text">Customer Name</span>
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
                        </div>
                        <div class="tab-pane" id="tabItem4">
                            <div class="nk-block">
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
                                                    <th class="nk-tb-col"><span class="sub-text">Customer Name</span>
                                                    </th>
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Purchased</span>
                                                    </th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Commission</span>
                                                    </th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Total</span>
                                                    </th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Reject Reason</span>
                                                    </th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo $data['rejected_orders']; ?>
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
<!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
    
    $(".orderDetailsTD").click(function() { 
        var id = $(this).data("dycryprt_id");
        window.location = core_path + "orders/orderdetails/"+ id;
    });

    $(".new_order_detail").click(function() { 
        var id = $(this).data("dycryprt_id");
        window.location = core_path + "orders/neworderdetail/"+ id;
    });

    $(".return_order_detail").click(function() { 
        var id = $(this).data("dycryprt_id");
        window.location = core_path + "orders/returnedorderdetail/"+ id;
    });

    $(".rejected_order_details").click(function() { 
        var id = $(this).data("dycryprt_id");
        window.location = core_path + "orders/rejectedorderdetail/"+ id;
    });

</script>


