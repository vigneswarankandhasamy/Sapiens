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
                    </div>
                </div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link new_orders_tab  active" data-toggle="tab" href="#tabItem1">New Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link returned_orders_tab " data-toggle="tab" href="#tabItem2">Returned Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rejected_orders_tab " data-toggle="tab" href="#tabItem3">Rejected Orders</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane new_orders_pane active" id="tabItem1">
                        <div class="nk-block nk-block-lg">
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
                    </div>
                    <div class="tab-pane returned_orders_pane" id="tabItem2">
                        <div class="nk-block nk-block-lg">
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
                    </div>
                    <div class="tab-pane rejected_orders_pane" id="tabItem3">
                        <div class="nk-block nk-block-lg">
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
                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Replace Status</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $data['r_o_list']; ?>
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

    $(".new_order_detail").click(function() { 
        var id = $(this).data("option");
        window.location = core_path + "orders/neworderdetail/"+ id;
    });

    $(".return_order_detail").click(function() { 
        var id = $(this).data("option");
        window.location = core_path + "orders/returnedorderdetail/"+ id;
    });

    $(".open_rejected_order_details").click(function() { 
        var id = $(this).data("option");
        window.location = core_path + "orders/rejectedorderdetail/"+ id;
    });


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
            window.location = core_path + "orders/index/"+$("#valid_from").val()+"/"+$("#valid_to").val();
            return false;
        }
    });


    $(".export_order_list").click(function() { 

        var from = $("#valid_from").val();
        var to   = $("#valid_to").val();
        var url  = core_path;

        if(from=="") {
            var text_msg = "Are you sure to export overall customer order list ?";
            html_string = $.parseHTML( text_msg );
        } else {
            var text_msg = "Are you sure to export <br> customer order list from <br> " + from + " to " + to +" ?";
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
                   if(from!="") {
                        var fromDate = new Date($("#valid_from").val());
                        var newDate = fromDate.toString('dd-MM-yy');
                        var formatted_from_date = fromDate.getDate() + "-" + (fromDate.getMonth() + 1) + "-" + fromDate.getFullYear();

                        var toDate = new Date($("#valid_to").val());
                        var formatted_to_date = toDate.getDate() + "-" + (toDate.getMonth() + 1) + "-" + toDate.getFullYear();

                        window.location = core_path + "exportData/customerorderreport" + "/" + formatted_from_date + "/" + formatted_to_date + "/overall";
                    } else {
                        window.location = core_path + "exportData/customerorderreport"+ "/overall";
                    } 
                }
            });
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

<?php if (isset($_GET['order_replased'])): ?>
<script type="text/javascript" charset="utf-8" async defer>

$(".nav-link").removeClass("active");
$(".tab-pane").removeClass("active");

$(".rejected_orders_tab").addClass("active");
$(".rejected_orders_pane").addClass("active");

setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Order Replaced Successfully !!</h5>', 'success', {
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