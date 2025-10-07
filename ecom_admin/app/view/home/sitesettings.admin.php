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
                                            <li class="breadcrumb-item active"><?php echo $data['page_title'] ?></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <table class="table table-ulogs is-compact" data-auto-responsive="false">
                                <thead class="thead-light">
                                    <tr class="nk-tb-item nk-tb-head">
                                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span></th>
                                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">UID</span></th>
                                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">Controller Name</span></th>
                                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">Parent ID</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $data['list'] ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    // Active & Inactive Status For Site Controller

    $(".changeSiteSettingsStatus").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $.ajax({
            type: "POST",
            url: core_path + "sitesettings/api/status",
            dataType: "html",
            data: { result: value },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                if (data == 1) {
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

