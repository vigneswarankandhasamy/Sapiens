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
                            </div>
                            <div class="nk-block-head-content">
                                <a href="<?php echo COREPATH ?>project/add" class="btn btn-primary" ><em class="icon ni ni-plus"></em> Add Project </a>
                            </div>
                        </div>
                    </div>

                    <!--Project List Show Start-->
                    <div class="nk-block">
                        <div class="card card-bordered card-full">
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
                                                    <th class="nk-tb-col tb-col-mb description_column"><span class="sub-text">Description </span>
                                                    </th>
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Added Date </span>
                                                    </th>
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Status </span>
                                                    </th>
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Action </span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo $data['projects_list'] ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- .card -->
                    </div>
                    <!--Project List Show End-->
                </div>
            </div>
        </div>
    </div>
<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
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
                url: core_path + "project/api/trashClassifiedProject",
                dataType: "html",
                data: { result: value },
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        //alert(data);
                        window.location = core_path + "project?t=success";
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


<?php if (isset($_GET['a'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Projects  added successfully !</h5>', 'success', {
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

<?php if (isset($_GET['e'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Projects  Updated successfully !</h5>', 'success', {
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


<?php if (isset($_GET['t'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Projects moved to trash successfully !</h5>', 'success', {
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

