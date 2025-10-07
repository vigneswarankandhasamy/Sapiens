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
                                            <th class="nk-tb-col tb-col-mb" width="25%"><span class="sub-text">Product </span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Comment</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Approval Status</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Visibility</span></th>
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

    <!-- Review Detail Pop-Up Model -->
        <div class="modal fade" tabindex="-1" id="openReviewDetail">
            <div class="modal-dialog modal-lg" role="document">
                <form id="viewReview" method="POST">
                    <div class="modal-content">
                        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                            <em class="icon ni ni-cross"></em>
                        </a>
                        <div class="modal-header modal-header-sm">
                            <h5 class="modal-title">Review Details</h5>
                        </div>
                        <div class="modal-body modal-body-md model_pt">
                            <div id="contactContent">
                                
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <!-- Model Pop-up End -->


<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    // Open Review Detail Pop-up

    $(".open_review_detail").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "review/api/info",
            dataType: "html",
            data: {
                result: value
            },
            beforeSend: function() {
            },
            success: function(data) {
                $("#contactContent").html(data);
                $('#openReviewDetail').modal('show');
            }
        });

    });

    // Close modal

    $("body").on("click", ".close_review_detail", function(){
        $('#openReviewDetail').modal('hide');
    });

    // Review Replay

    $("#viewReview").validate({
        rules: {
            review_replay: {
                required: true
            },
           
        },
        messages: {
            review_replay: {
                required: "Please Enter replay message",
            },
           
        },
        submitHandler: function(form) {
            var formname = document.getElementById("viewReview");
            var formData = new FormData(formname);
            $.ajax({
                url: core_path + "review/api/reviewReplay",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "review?a=success";
                    } else {
                        $(".form-error").show();
                        $(".form-error").html(data);
                        toastr.clear();
                        NioApp.Toast('<h5>' + data + '</h5>', 'error', {
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
        }
    });

     // Save Form

    function saveForm() {
        
    }

    // Approval & Pending Status For Review

    $(".changeReviewApproval").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "review/api/approvalstatus",
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

    // Active & Inactive Status For Review

    $(".changeReviewStatus").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "review/api/status",
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
    
    // Trash Enquiry

    $(".trashproductReview").click(function(e) {
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
                    url: core_path + "review/api/trashproductReview",
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
                                position: 'top-right', 
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

    $(".openFormPanel").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        var formclass = $(this).data("formclass");
        var form = $(this).data("form");
        $.ajax({
            type: "POST",
            url: core_path + "review/api/info",
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
</script>

<?php if (isset($_GET['t'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Review moved to trash successfully !!</h5>', 'success', {
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

<?php if (isset($_GET['a'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Replay Message added to reveiw successfully !!</h5>', 'success', {
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

