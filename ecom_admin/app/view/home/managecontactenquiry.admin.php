<?php require_once 'includes/top.php'; ?> 

    <!-- content @s -->
    <div class="nk-content nk-content-fluid">
        <div class="container-fluid wide-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title"> <?php echo $data['page_title'] ?></h3>
                                <div class="nk-block-des">
                                    <nav>
                                        <ul class="breadcrumb breadcrumb-arrow">
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a></li>
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>marketing">Marketing</a></li>
                                            <li class="breadcrumb-item active"><?php echo $data['page_title'] ?></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <table class="datatable-init nk-tb-list nk-tb-ulist is-compact" data-auto-responsive="false">
                                    <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span></th>
                                             <th class="nk-tb-col tb-col-mb"><span class="sub-text">Date</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Name </span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Contact </span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Subject</span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Location</span></th>
                                            <th class="nk-tb-col tb-col-lg"><span class="sub-text">Action</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $data['list'] ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" id="modalForm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header">
                    <h5 class="modal-title">Enquiry Details</h5>
                </div>
                <div class="modal-body" id="contactContent">
                    
                </div>
            </div>
        </div>
    </div>
    <?php if(1 != 1) { ?>
    <!--  side bar details view -->
    <!--  <div class="form_panel_warp view_contact_class" >
         <form id="viewContact" method="POST">
            <div class="form_panel_head">
                <h6 class="mb-0">Contact Details</h6><a class="nk-demo-close toggle btn btn-icon btn-trigger revarse mr-n2 active closeFormPanel" data-formclass='view_contact_class' data-form='viewContact'  href="javascript:void();"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="form_panel_content" id="contactContent">
               
            </div>
            <div class="form_panel_footer">
                <div class="row">
                    <div class="col-md-12">
                        <p class="pull-right">
                            <button type="button" class="btn btn-light closeFormPanel" data-form='viewContact' data-formclass="view_contact_class"> Cancel</button>
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="form_panel_overlay" ></div> -->
    <?php  } ?>

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
    
    $(".open_enq_model").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "enquiry/api/info",
            dataType: "html",
            data: {
                result: value
            },
            beforeSend: function() {
            },
            success: function(data) {
                $("#contactContent").html(data);
            }
        });

    });

    // Trash Enquiry

    $(".trashContactEnquiry").click(function(e) {
        toastr.clear();
        Swal.fire({
            title: "Are you sure to move this item to trash?",
            text: "Once moved to trash shall be restored from the same.",
            icon: 'warning',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.value) {
                var value = $(this).data("option");
                var link = $(this).data("link");
                $.ajax({
                    type: "POST",
                    url: core_path + "enquiry/api/trashContactEnquiry",
                    dataType: "html",
                    data: { result: value },
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            //alert(data);
                            window.location = core_path + link  + "?t=success";
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
            }
        });
        e.preventDefault();
        return false;
    });
</script>

<?php if(1 != 1) { ?>
<script type="text/javascript">
    // $(".openFormPanel").click(function() {
    //     toastr.clear();
    //     var value = $(this).data("option");
    //     var formclass = $(this).data("formclass");
    //     var form = $(this).data("form");
    //     $.ajax({
    //         type: "POST",
    //         url: core_path + "enquiry/api/info",
    //         dataType: "html",
    //         data: { result: value },
    //         beforeSend: function() {
    //             $(".page_loading").show();
    //         },
    //         success: function(data) {
    //             $(".page_loading").hide();
    //             $("#contactContent").html(data);
    //         }
    //     });
    //     return false;
    // });
</script>
<?php  } ?>

<?php if (isset($_GET['t'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Enquiry moved to trash successfully !!</h5>', 'success', {
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

