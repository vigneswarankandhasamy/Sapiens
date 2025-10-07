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
                                        <h3 class="nk-block-title page-title">Vendor / <strong class="text-primary small"><?php echo $data['info']['company'] ?></strong></h3>
                                    </div>
                                    <div class="nk-block-head-content">

                                        <a class="btn btn-primary btn-sm sendInvite " href="<?php echo COREPATH ?>vendor/edit/<?php echo $data['info']['id']; ?>" data-id="<?php echo $data['token']; ?>" >Edit</a>

                                        <button class="btn btn-info btn-sm sendInvite sendCredentials"  data-token="<?php echo $data['info']['token'] ?>" data-id="<?php echo $data['token']; ?>">Credentials</button>

                                        <button class="btn btn-warning btn-sm sendInvite sendInviteMail send_invite_btn" data-token="<?php echo $data['info']['token'] ?>" data-id="<?php echo $data['token']; ?>" ><?php echo (($data['info']['invite_status']==1) ? "Resend Invite" : "Send Invite"); ?></button>


                                        <a href="<?php echo COREPATH ?>vendor" class="btn btn-outline-light bg-white btn-sm d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                                        <a href="<?php echo COREPATH ?>vendor" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
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
                                                                <span class="profile-ud-label">Company Name</span>
                                                                <span class="profile-ud-value"><?php echo $data['info']['company'] ?></span>
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
                                                                <span class="profile-ud-value"><?php echo (($data['info']['address']!="") ? $data['info']['address'] : 'Nill');  ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-ud-item">
                                                            <div class="profile-ud wider">
                                                                <span class="profile-ud-label">Created Date</span>
                                                                <span class="profile-ud-value"><?php echo date("d-M-Y  ",strtotime($data['info']['created_at']));  ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-ud-item">
                                                            <div class="profile-ud wider">
                                                                <span class="profile-ud-label">Valid Form</span>
                                                                <span class="profile-ud-value"><?php echo date('d-M-Y',strtotime($data['info']['valid_from']))  ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-ud-item">
                                                            <div class="profile-ud wider">
                                                                <span class="profile-ud-label">Valid Ends</span>
                                                                <span class="profile-ud-value"><?php echo date('d-M-Y',strtotime($data['info']['valid_to']))  ?></span>
                                                            </div>
                                                        </div>
                                                       
                                                    </div><!-- .profile-ud-list -->
                                                </div><!-- .nk-block -->
                                            </div><!-- .card-inner -->
                                        </div><!-- .card-content -->
                                       <!-- .card-aside -->
                                    </div><!-- .card-aside-wrap -->
                                </div><!-- .card -->
                            </div><!-- .nk-block -->

                     <div class="nk-block">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h5 class="nk-block-title title">Assigned Location</h5>
                            </div>
                        </div>
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <table class="datatable-init nk-tb-list nk-tb-ulist is-compact" data-auto-responsive="false">
                                    <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Location Group</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">City </span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $data['locatiomlist'] ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div>
                      <div class="nk-block">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h5 class="nk-block-title title">Assigned Products</h5>
                            </div>
                        </div>
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <table class="datatable-init nk-tb-list nk-tb-ulist is-compact" data-auto-responsive="false">
                                    <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Image</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Product </span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Category </span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Actual Price</span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Selling Price</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Inventory </span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $data['productlist'] ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div>
                     <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title title">Vendor Orders</h5>
                                    </div>
                                </div>
                    <div class="card card-preview">
                        <div class="card-inner">
                            <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                <thead>
                                    <tr class="nk-tb-item nk-tb-head">
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

    <!-- Enquiry list start -->
    
    <!-- Enquiry list end -->

    <!--  side bar details view -->
    <div class="form_panel_warp view_enquiry_class" >
         <form id="viewEnquiry" method="POST">
            <div class="form_panel_head">
                <h6 class="mb-0">Enquiry Details</h6><a class="nk-demo-close toggle btn btn-icon btn-trigger revarse mr-n2 active closeFormPanel" data-formclass='view_enquiry_class' data-form='viewEnquiry'  href="javascript:void();"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="form_panel_content" id="contactContent">
               
            </div>
            <div class="form_panel_footer">
                <div class="row">
                    <div class="col-md-12">
                        <p class="pull-right">
                            <button type="button" class="btn btn-light closeFormPanel" data-form='viewEnquiry' data-formclass="view_enquiry_class"> Cancel</button>
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="form_panel_overlay" ></div>

<?php require_once 'includes/bottom.php'; ?>



<script type="text/javascript">

     $(".open_enq_model").click(function() {
        var value = $(this).data("option");
        window.location = core_path + "orders/vendororderdetails/" + value;
    });

    $(".openFormPanel").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        var formclass = $(this).data("formclass");
        var form = $(this).data("form");
        $.ajax({
            type: "POST",
            url: core_path + "contractor/api/info",
            dataType: "html",
            data: { result: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                $("#contactContent").html(data);
            }
        });
        return false;
    });

    // Send Credentials to notification emails

    $(".sendCredentials").click(function() {
        toastr.clear();
        var value = $(this).data("id");
        var token = $(this).data("token");
        $.ajax({
            type: "POST",
            url: core_path + "vendor/api/sendCredentials",
            dataType: "html",
            data: { result: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                if (data == 1) {
                    window.location = core_path + "vendor/details/"+token+"?i=success";
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

    // Send Invite 

    $(".sendInviteMail").click(function() {
        toastr.clear();
        var value = $(this).data("id");
        var token = $(this).data("token");
        $.ajax({
            type: "POST",
            url: core_path + "vendor/api/sendInvite",
            dataType: "html",
            data: { result: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                if (data == 1) {
                    //alert(data);
                    window.location = core_path + "vendor/details/"+token+"?i=success";
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

<?php if (isset($_GET['i'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Vendor Invite sent successfully !!</h5>', 'success', {
        position: 'bottom-center', 
        ui: 'is-light',
        "progressBar": true,
        "showDuration": "300",
        "hideDuration": "200",
        "timeOut": "4000"
    }); 
}, 1500);
history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>