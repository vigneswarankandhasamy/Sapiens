<?php require_once 'includes/top.php'; ?>
 <!-- main header @e -->
            <!-- content @s -->
            <div class="nk-content nk-content-lg nk-content-fluid">
                <div class="container-xl wide-lg">
                    <div class="nk-content-inner">
                        <div class="nk-content-body">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <div class="nk-block-head-sub"><a class="back-to" href="<?php echo COREPATH ?>profile/security"><em class="icon ni ni-arrow-left"></em><span>My Profile</span></a></div>
                                    <h2 class="nk-block-title fw-normal">Login Activity</h2>
                                    <div class="nk-block-des">
                                        <p>Here is your last 20 login activities log. <span class="text-soft"><em class="icon ni ni-info"></em></span></p>
                                    </div>
                                </div>
                            </div><!-- .nk-block-head -->
                            <div class="nk-block">
                                <div class="nk-block-title-group mb-3">
                                    <h6 class="nk-block-title title">Activity on your account</h6>
                                    <a href="<?php echo COREPATH ?>profile/loginactivity#" class="link link-danger">Clear log</a>
                                </div>
                                 <div class="card card-bordered">
                                    <table class="table table-ulogs">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="tb-col-os"><span class="overline-title">Browser <span class="d-sm-none">/ IP</span></span></th>
                                                <th class="tb-col-ip"><span class="overline-title">IP</span></th>
                                                <th class="tb-col-time"><span class="overline-title">Time</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php echo $data['list']; ?>
                                        </tbody>
                                    </table>
                                </div><!-- .card -->
                            </div><!-- .nk-block -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- content @e -->
            <!-- footer @s -->



<?php require_once 'includes/bottom.php'; ?>

<?php if (isset($_GET[ 'u'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        toastr.clear();
        NioApp.Toast('<h5>Password changed successfully !!</h5>', 'success', {
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

