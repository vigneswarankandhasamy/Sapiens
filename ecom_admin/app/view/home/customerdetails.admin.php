<?php require_once 'includes/top.php'; ?>
    <!-- main header @e -->
    <!-- content @s -->
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between g-3">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Customer / <strong class="text-primary small"><?php echo $data['info']['name'] ?></strong></h3>
                            </div>
                            <div class="nk-block-head-content">
                                <a href="<?php echo COREPATH ?>customers" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                                <a href="<?php echo COREPATH ?>customers" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
                            </div>
                        </div>
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-aside-wrap">
                                <div class="card-content">
                                    <ul class="nav nav-tabs nav-tabs-mb-icon nav-tabs-card">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#"><em class="icon ni ni-user-circle"></em><span>Personal</span></a>
                                        </li>
                                    </ul><!-- .nav-tabs -->
                                    <div class="card-inner">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <h5 class="title">Personal Information</h5>
                                            </div><!-- .nk-block-head -->

                                            <div class="profile-ud-list">
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Name</span>
                                                        <span class="profile-ud-value"><?php echo $data['info']['name'] ?></span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Mobile Number</span>
                                                        <span class="profile-ud-value"><?php echo $data['info']['mobile'] ?></span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Email Address</span>
                                                        <span class="profile-ud-value"><?php echo $data['info']['email'] ?></span>
                                                    </div>
                                                </div>
                                                 
                                            </div><!-- .profile-ud-list -->
                                        </div><!-- .nk-block -->
                                        <div class="nk-block">
                                            <div class="nk-block-head nk-block-head-line">
                                                <h6 class="title overline-title text-base">Additional Information</h6>
                                            </div><!-- .nk-block-head -->
                                            <div class="profile-ud-list">
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Address</span>
                                                        <span class="profile-ud-value"><?php echo (($data['address_info']!="") ? $data['address_info']['address'] : 'Nill');  ?></span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Landmark</span>
                                                        <span class="profile-ud-value"><?php echo (($data['address_info']!="") ? $data['address_info']['landmark'] : 'Nill');  ?></span>
                                                    </div>
                                                </div>
                                                 <?php if($data['address_info']['gst_name']!="") { ?>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label">GST Name</span>
                                                            <span class="profile-ud-value"><?php echo $data['address_info']['gst_name']; ?></span>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php if($data['address_info']['gstin_number']!="") { ?>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label">GSTIN Number</span>
                                                            <span class="profile-ud-value"><?php echo $data['address_info']['gstin_number']; ?></span>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">City</span>
                                                        <span class="profile-ud-value"><?php echo (($data['address_info']!="") ? $data['address_info']['city'] : 'Nill');  ?></span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Registered Date</span>
                                                        <span class="profile-ud-value"><?php echo date("d-m-Y ",strtotime($data['info']['registration_date']));  ?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-ud-item">
                                                    <!-- active status change start-->
                                                    <?php
                                                        $status         = (($data['info']['status']==1) ? "Active" : "Inactive"); 
                                                        $status_c       = (($data['info']['status']==1) ? "checked" : " ");
                                                        $status_class   = (($data['info']['status']==1) ? "text-success" : "text-danger");
                                                    ?>
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Status</span>
                                                        <span class="profile-ud-value">
                                                        <div class='custom-control custom-switch custom-control-sm'>
                                                            <input type='checkbox' class='custom-control-input changeCustomerStatus' data-option='<?php echo $data['info']['id'] ?>' value='0' id='status' name='save_as_draft' <?php echo $status_c ?>>
                                                            <label class='custom-control-label ' for='status'><span class='<?php echo $status_class ?>'><?php echo $status ?> </span></label>
                                                        </div>
                                                        </span>
                                                    </div>

                                                    <!-- active status change end --> 
                                                </div>


                                            </div><!-- .profile-ud-list -->
                                        </div><!-- .nk-block -->
                                    </div><!-- .card-inner -->
                                </div><!-- .card-content -->
                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    <!-- content @e --> 

    <!-- Order History list end -->

     <div class="nk-content nk-content-fluid">
        <div class="container-fluid wide-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <div class="nk-block-head">
                                    <h5 class="title">Order History</h5>
                                </div>
                            <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                <thead>
                                    <tr class="nk-tb-item nk-tb-head">
                                        <th class="nk-tb-col">Order ID</span>
                                        </th>
                                         <th class="nk-tb-col tb-col-md">Order Date</span>
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

     <!-- Review History list start -->
    <div class="nk-content nk-content-fluid">
        <div class="container-fluid wide-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <div class="nk-block-head">
                                    <h5 class="title">Product Reviews History</h5>
                                </div>
                                <table class="datatable-init nk-tb-list nk-tb-ulist is-compact" data-auto-responsive="false">
                                    <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Date</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Product Name</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Comment</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Approval Status</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Status </span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php echo $data['reviews']; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Review History list end -->

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
    // Open order details page 

    $(".open_order_details_page").click(function() {
        var item_id = $(this).data("option");
        window.location = core_path + "orders/orderdetails/" + item_id;
    });

    // Open Review list page 

    $(".open_review_list_page").click(function() {
        window.location = core_path + "review";
    });

    // Active & Inactive Status For User

    $(".changeCustomerStatus").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        // alert(value);
        $.ajax({
            type: "POST",
            url: core_path + "customers/api/status",
            dataType: "html",
            data: { result: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                if (data == 1) {
                    //alert(data);
                    location.reload();
                } else {
                    toastr.clear();
                    NioApp.Toast('<h5>'+data+'</h5>', 'error', {
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
        return false;
    });
</script>