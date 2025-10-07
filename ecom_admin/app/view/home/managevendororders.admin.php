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
                                <form id="orderReportFilter">
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li>
                                                <input type="text" name="valid_from" id="valid_from" autocomplete="off" class="form-control date-picker" data-date-format="dd-mm-yyyy" value="<?php echo $data['from_date']; ?>" placeholder="From Date">
                                            </li>
                                            <li>
                                                <input type="text" name="valid_to" id="valid_to" class="form-control date-picker" data-date-format="dd-mm-yyyy" value="<?php echo $data['to_date']; ?>" autocomplete="off" placeholder="To Date">
                                            </li>
                                            <li class="nk-block-tools-opt"><button type="submit" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></button>
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
                        <!-- .nk-block-head-content -->
                    </div>
                    <!-- .nk-block-between -->
                </div>
                <!-- .nk-block-head -->
                 <div class="nk-block nk-block-lg">
                    <div class="card card-bordered card-full">
                        <div class="card-inner">
                            <div class="card-title-group">
                                <div class="card-title">
                                    <h6 class="title"><span class="mr-2">Vendor Order list</span></h6>
                                </div>
                                <div class="card-tools">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt">
                                            <?php if($data['list']!="") { ?>
                                            <button  class="btn btn-primary export_order_list"><em class="icon ni ni-arrow-down"></em><span>Export</span>
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
                                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                <thead>
                                    <tr class="nk-tb-item nk-tb-head">
                                        <th class="nk-tb-col"><span>S.No</span>
                                        </th>
                                        <th class="nk-tb-col"><span>Vendor Invoice No</span>
                                        </th>
                                         <th class="nk-tb-col tb-col-md"><span>Order Date</span>
                                        </th>
                                        <th class="nk-tb-col"><span class="sub-text">Vendor Name</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">Total Items</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Total Charge</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Total</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Response</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span>
                                        </th>
                                        <?php if(1 != 1) { ?>
                                        <!-- <th class="nk-tb-col nk-tb-col-tools text-right"> -->
                                            <!-- <ul class="nk-tb-actions gx-1 my-n1">
                                                <li>
                                                    <div class="drodown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger mr-n1" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <ul class="link-list-opt no-bdr">
                                                                <li><a href="#"><em class="icon ni ni-edit"></em><span>Update Status</span></a>
                                                                </li>
                                                                <li><a href="#"><em class="icon ni ni-truck"></em><span>Mark as Delivered</span></a>
                                                                </li>
                                                                <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a>
                                                                </li>
                                                                <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a>
                                                                </li>
                                                                <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Orders</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul> -->
                                        <!-- </th> -->
                                        <?php  } ?>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php echo $data['list']; ?>
                                    
                                    <!-- .nk-tb-item  -->
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

    $(".open_enq_model").click(function() {
        var value = $(this).data("option");
        window.location = core_path + "orders/vendororderdetails/" + value;
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
            window.location = core_path + "orders/vendorOrders/"+$("#valid_from").val()+"/"+$("#valid_to").val();
            return false;
        }
    });


    $(".export_order_list").click(function() { 

        var from = "<?php echo date("Y-M-d", strtotime($data['from_date'])) ?> ";
        var to   = "<?php echo date("Y-M-d", strtotime($data['to_date'])) ?> ";
        var url  = core_path;

        if(from=="") {
            var text_msg = "Are you sure to export overall vendor  order list ?";
            html_string = $.parseHTML( text_msg );
        } else {
            var text_msg = "Are you sure to export <br> vendor order list from <br> " + from + " to " + to +" ?";
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
                        var fromDate = new Date(from);
                        var newDate = fromDate.toString('dd-MM-yy');
                        var formatted_from_date = fromDate.getDate() + "-" + (fromDate.getMonth() + 1) + "-" + fromDate.getFullYear();

                        var toDate = new Date(to);
                        var formatted_to_date = toDate.getDate() + "-" + (toDate.getMonth() + 1) + "-" + toDate.getFullYear();

                        window.location = core_path + "exportData/vendororderreport" + "/" + formatted_from_date + "/" + formatted_to_date + "/overall";
                    } else {
                        window.location = core_path + "exportData/vendororderreport"+ "/overall";
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