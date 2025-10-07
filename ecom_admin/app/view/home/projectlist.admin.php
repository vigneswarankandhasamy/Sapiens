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
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>hiresettings">Expert</a></li>
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
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span>
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Image</span>
                                                </th>
                                                 <th class="nk-tb-col tb-col-mb"><span class="sub-text">Project Title</span>
                                                </th>
                                                <th class="nk-tb-col tb-col-mb "><span class="sub-text">Company </span>
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Added Date </span>
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Visibility </span>
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Status </span>
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Action </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $data[ 'projects_list'] ?>
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
        <div class="modal fade" tabindex="-1" id="openProjectDetail">
            <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                            <em class="icon ni ni-cross"></em>
                        </a>
                        <div class="modal-header modal-header-sm">
                            <h5 class="modal-title">Project Details</h5>
                        </div>
                        <div class="modal-body modal-body-md model_pt">
                            <div id="contactContent">
                                
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    <!-- Model Pop-up End -->


<div class="image_viewer_wrap" style="height: 60vh; width: 60%; right: 20%;">
    <div class="image_viewer_head">
        <h6 class="mb-0">Image Preview </h6>
        <a class="nk-demo-close toggle btn btn-icon btn-trigger revarse mr-n2 active closeImageViewer"  href="javascript:void();"><em class="icon ni ni-cross"></em></a>
    </div>
    <div class="image_viewer_content" id="image_viewer_content">
        <div class="container">
            <div class="row">
                <div class="offset-md-3 col-md-6">
                    <div class="image_preview" id="image_preview">
                        <p><img id="image_object" src=""></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="image_viewer_overlay" >
       
</div>


<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    $(".closeImageViewer").click(function(e) {
    $(".image_viewer_overlay").toggle();
    $(".image_viewer_wrap").toggle();
    $(".modal-backdrop").show();
    $("#openProjectDetail").show();
    return false;
});

    $(document).on('click', '.preve', function() {
        $("#openProjectDetail").hide();
        $(".modal-backdrop").hide();
        var image = $(this).children().attr('src');
        $("#image_object").attr({
                'src': image
            });
        $(".image_viewer_overlay").toggle();
        $(".image_viewer_wrap").toggle();

        var height = $("#image_viewer_content").innerHeight();
        var width = $("#image_viewer_content").innerWidth();

        $("#image_object").attr({
            'style': "max-height: " + (parseInt(height) - 40) + "px; width: auto"
        });
        $("#image_viewer_wrap").attr({
            'style': "z-index: 1050"
        });
    });

    // Open Review Detail Pop-up

    $(".open_project_detail").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "hire/api/calssifiedProjectInfo",
            dataType: "html",
            data: {
                result: value
            },
            beforeSend: function() {
            },
            success: function(data) {
                $("#contactContent").html(data);
                $('#openProjectDetail').modal('show');
            }
        });

    });


    // verified  Status For project

    $(".verifyClassifiedProject").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "hire/api/calssifiedProjectStatus",
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

    // Visible  Status For project

    $(".changeProjectVisibleStatus").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "hire/api/calssifiedProjectVisibleStatus",
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


    // Close Form Panel

    $(".closeFormPanel_cs_projest").click(function(e) {
        var formclass = $(this).data("formclass");
        var form = $(this).data("form");
        toggleFormPanel(formclass, form, type = "reset");
        location.reload();
        e.preventDefault();
        return false;
    });


     $(".trashClassifiedProject").click(function(e) {
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
                $.ajax({
                    type: "POST",
                    url: core_path + "hire/api/trashClassifiedProject",
                    dataType: "html",
                    data: { result: value },
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            //alert(data);
                            window.location = core_path + "hire/projects?t=success";
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

<?php if (isset($_GET['t'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Classified Project moved to trash successfully !!</h5>', 'success', {
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

<?php if (isset($_GET['a'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Replay Message added to reveiw successfully !!</h5>', 'success', {
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

