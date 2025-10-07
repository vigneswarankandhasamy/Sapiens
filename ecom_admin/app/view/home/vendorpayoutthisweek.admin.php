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
                                        <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>orders">Orders</a>
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
                                    <form method="post" class="form-validate is-alter" id="vendorOrderSort" >
                                        <div class="toggle-expand-content" data-content="pageMenu">
                                            <ul class="nk-block-tools g-3">
                                                <li class="date_input">
                                                    <input type="text" name="valid_from"  id="valid_from" autocomplete="off" class="form-control date-picker" data-date-format="dd-mm-yyyy" placeholder="From Date">
                                                </li>
                                                <li class="date_input">
                                                    <input type="text" name="validity_to" id="validity_to" class="form-control date-picker" data-date-format="dd-mm-yyyy" autocomplete="off" placeholder="To Date" >
                                                </li>
                                                <li class="sellect_vendor">
                                                    <div class="form-group sellect_vendor_drp" >
                                                        <div class="form-control-wrap">
                                                            <select class="form-select" name="vendor_id" data-search="on">
                                                               <?php echo $data['get_vendors'] ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="nk-block-tools-opt"><button type="submit"  class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></button></li>
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
                    <div class="card card-preview">
                        <div class="card-inner">
                            <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                <thead>
                                    <tr class="nk-tb-item nk-tb-head">
                                        <th class="nk-tb-col">S.No</span>
                                        </th>
                                         <th class="nk-tb-col"><span class="sub-text">Vendor Name</span>
                                        </th>
                                         <th class="nk-tb-col tb-col-md">Total orders</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">Order value</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Total commission</span>
                                        </th>
                                         <th class="nk-tb-col tb-col-md">Unpaid orders</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Payable</span>
                                        </th>
                                        <th class="nk-tb-col nk-tb-col-tools text-right">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="vendorTBL">
                                    <?php echo $data['list']; ?>
                                    
                                    <?php if(1 != 1) { ?>
                                    <!--  <tr class='nk-tb-item'>
                                        <td class='nk-tb-col'>
                                            <span class='tb-lead'><a href=''>2</a></span>
                                        </td>
                                         <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>Angelica Ramos<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> vendor@gmail.com</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> 8495875621</span>
                                                </div>
                                            </div>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>28</span>
                                        </td>
                                       
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>₹ 16,000<span class='currency'> INR</span></span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ 3,401 </span>
                                           
                                        </td>
                                         <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ 2,709 </span>
                                           
                                        </td>
                                       
                                        <td class='nk-tb-col nk-tb-col-tools'>
                                            <ul class='nk-tb-actions gx-1'>
                                                <li class='nk-tb-action-hidden'><a href="<?php echo COREPATH ?>orders/vendorpayout" class='btn btn-icon btn-trigger btn-tooltip' title='Pay'><em class="icon ni ni-wallet-out"></em></a>
                                                </li>
                                                <li>
                                                    <div class='drodown mr-n1'>
                                                        <a href='#' class='dropdown-toggle btn btn-icon btn-trigger' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a>
                                                        <div class='dropdown-menu dropdown-menu-right'>
                                                            <ul class='link-list-opt no-bdr'>
                                                                <li><a href="<?php echo COREPATH ?>orders/vendorpayout"><em class="icon ni ni-wallet-out"></em><span>Pay</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr class='nk-tb-item'>
                                        <td class='nk-tb-col'>
                                            <span class='tb-lead'><a href=''>3</a></span>
                                        </td>
                                         <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>Bradley Greer<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> vendor@gmail.com</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> 8495875621</span>
                                                </div>
                                            </div>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>108</span>
                                        </td>
                                       
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>₹ 1,56,000<span class='currency'> INR</span></span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ 46,000 </span>
                                           
                                        </td>
                                         <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ 44,689 </span>
                                           
                                        </td>
                                       
                                        <td class='nk-tb-col nk-tb-col-tools'>
                                            <ul class='nk-tb-actions gx-1'>
                                                <li class='nk-tb-action-hidden'><a href="<?php echo COREPATH ?>orders/vendorpayout" class='btn btn-icon btn-trigger btn-tooltip' title='Pay'><em class="icon ni ni-wallet-out"></em></a>
                                                </li>
                                                <li>
                                                    <div class='drodown mr-n1'>
                                                        <a href='#' class='dropdown-toggle btn btn-icon btn-trigger' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a>
                                                        <div class='dropdown-menu dropdown-menu-right'>
                                                            <ul class='link-list-opt no-bdr'>
                                                                <li><a href="<?php echo COREPATH ?>orders/vendorpayout"><em class="icon ni ni-wallet-out"></em><span>Pay</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr class='nk-tb-item'>
                                        <td class='nk-tb-col'>
                                            <span class='tb-lead'><a href=''>4</a></span>
                                        </td>
                                         <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>Brenden Wagner<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> vendor@gmail.com</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> 8495875621</span>
                                                </div>
                                            </div>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>6</span>
                                        </td>
                                       
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>₹ 6,000<span class='currency'> INR</span></span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ 2,000 </span>
                                           
                                        </td>
                                         <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ 1,689 </span>
                                           
                                        </td>
                                       
                                        <td class='nk-tb-col nk-tb-col-tools'>
                                            <ul class='nk-tb-actions gx-1'>
                                                <li class='nk-tb-action-hidden'><a href="<?php echo COREPATH ?>orders/vendorpayout" class='btn btn-icon btn-trigger btn-tooltip' title='Pay'><em class="icon ni ni-wallet-out"></em></a>
                                                </li>
                                                <li>
                                                    <div class='drodown mr-n1'>
                                                        <a href='#' class='dropdown-toggle btn btn-icon btn-trigger' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a>
                                                        <div class='dropdown-menu dropdown-menu-right'>
                                                            <ul class='link-list-opt no-bdr'>
                                                                <li><a href="<?php echo COREPATH ?>orders/vendorpayout"><em class="icon ni ni-wallet-out"></em><span>Pay</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr class='nk-tb-item'>
                                        <td class='nk-tb-col'>
                                            <span class='tb-lead'><a href=''>5</a></span>
                                        </td>
                                         <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>Bruno Nash<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> vendor@gmail.com</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> 8495875621</span>
                                                </div>
                                            </div>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>46</span>
                                        </td>
                                       
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>₹ 46,000<span class='currency'> INR</span></span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ 6,000 </span>
                                           
                                        </td>
                                         <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ 5,689 </span>
                                           
                                        </td>
                                       
                                        <td class='nk-tb-col nk-tb-col-tools'>
                                            <ul class='nk-tb-actions gx-1'>
                                                <li class='nk-tb-action-hidden'><a href="<?php echo COREPATH ?>orders/vendorpayout" class='btn btn-icon btn-trigger btn-tooltip' title='Pay'><em class="icon ni ni-wallet-out"></em></a>
                                                </li>
                                                <li>
                                                    <div class='drodown mr-n1'>
                                                        <a href='#' class='dropdown-toggle btn btn-icon btn-trigger' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a>
                                                        <div class='dropdown-menu dropdown-menu-right'>
                                                            <ul class='link-list-opt no-bdr'>
                                                                <li><a href="<?php echo COREPATH ?>orders/vendorpayout"><em class="icon ni ni-wallet-out"></em><span>Pay</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr> -->
                                    <?php  } ?>

                                    
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
    
    $("#vendorOrderSort").validate({
        rules: {
            valid_from: {
                required: true
            },
            validity_to: {
                required: true
            },
        },
        messages: {
            valid_from: {
                required: "",
            },
            validity_to: {
                required: "",
            },
        },
        submitHandler: function(form) {
            saveForm();
           
            return false;
        }
    });

    function  saveForm() {
         var formname = document.getElementById("vendorOrderSort");
            var formData = new FormData(formname);
            $.ajax({
                url: core_path + "vendor/api/vendorShort",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".vendorTBL").html();
                    $(".vendorTBL").html(data);
                    $(".page_loading").hide();
                }
            });
    }

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