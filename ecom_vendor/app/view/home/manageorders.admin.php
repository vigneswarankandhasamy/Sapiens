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
                                        <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>ordersettings">Orders</a>
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
                <div class="nk-block nk-block-lg">
                    <div class="card card-preview">
                        <div class="card-inner">
                            <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                <thead>
                                    <tr class="nk-tb-item nk-tb-head">
                                         <th class="nk-tb-col">S.No</span>
                                        </th>
                                        <th class="nk-tb-col">Invoice No</span>
                                        </th>
                                         <th class="nk-tb-col tb-col-md">Order Date</span>
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
                                    <?php echo $data['list']; ?>
                                    
                                    <!-- .nk-tb-item  -->
                                </tbody>
                            </table>
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

    $(".orderDetailsTD").click(function() { 
        var id = $(this).data("dycryprt_id");
        window.location = core_path + "orders/orderdetails/"+ id;
    });
    
    $('.orderStatusChange').click( function() {
        var id = $(this).data('order');
        var value = $(this).data('status');
        $.ajax({
            type: "POST",
            url: core_path + "orders/api/changeOrderStatus",
            dataType: "html",
            data: { order_id: id,status: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                if (data == 1) {
                    window.location = core_path + "orders?sh=success";
                } else if (data == 2){
                    window.location = core_path + "orders?p=success";
                }else {
                    toastr.clear();
                    NioApp.Toast('<h5>Error Occured</h5>', 'error', {
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
    });

</script>

<?php if (isset($_GET['sh'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Order shipment status updated successfully !!</h5>', 'success', {
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

<?php if (isset($_GET['p'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Order payment status updated successfully  !!</h5>', 'success', {
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