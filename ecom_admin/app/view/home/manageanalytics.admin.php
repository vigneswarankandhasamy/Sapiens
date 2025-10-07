<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="updateAnalytics" enctype="multipart/form-data">
                        <?php echo $data['csrf_update_analytics'] ?>
                        <div class="form_submit_bar">
                            <div class="container wide-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>settings"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="submit_button_wrap">
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>settings"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-error"></div>
                         <div class="card card-shadow">
                            <div class="card-inner">
                                 <div class="row mb-2">
                                    <div class="col-md-12">
                                        <div class="form-control-wrap">
                                            <h5 class="card-title">Google Analytics</h5>
                                            <div class="custom-control-lg custom-switch settings_switch">
                                                <input type="checkbox" class="custom-control-input" id="analytics_id_switch" name="analytics_id_switch" value="1" checked="">
                                                <label class="custom-control-label fw-bold" for="analytics_id_switch" id="analytics_id_switch_text"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <p>Google Remarketing lets you follow up with people who have already visited your website, and deliver ad content specifically targeted to the interests they expressed during those previous visits.</p>
                                    </div>
                                    <div class="form-group col-md-6" id="analytics_textarea">
                                        <div class="form-control-wrap">
                                            <textarea class="form-control" rows="1"  name="google_analytics_id" placeholder="Google Analytics ID"><?php echo $data['info']['google_analytics_id']; ?></textarea>
                                        </div>
                                        <span class="help_text">Enter your Google Analytics<a href="http://support.google.com/analytics/bin/answer.py?hl=en&answer=1032385" target="_blank"> Web Property ID</a> (UA-XXXXX-YY) if you have one</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                    <h5 class="card-title">Google Ads Tag </h5>
                                    <p>Follow up your Google advertising campaigns and boost their results with the help of Google Ads Tags. Add <br> tags to your store and view which ads are the most sales driving ones. Learn more about<a href="https://support.google.com/google-ads/answer/6095821" target="_blank"> how to set up <br> conversion tracking in Google</a>.</p>
                                    <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-label" >Add Google Tags to your store
                                    </label>
                                    <div class="paragraphList" >
                                    <ol start="1">
                                        <li>Log in to your<a href="https://ads.google.com/intl/en_US/home/" target="_blank"> Google Ads account </a> and go to Tools & Settings > Measurement > Conversions, use + button and choose <b>Website</b>.</li>
                                        <li>Create a new conversion action for <b>Purchase Category</b>.</li>
                                        <li>Select <b>Install the tag yourself</b>, copy the <b>Global site tag</b> and paste it into the corresponding field on this page.</li>
                                        <li>Copy the <b>Event snippet</b> and paste it into the corresponding field on this page.</li>
                                    </ol>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                     <div class="form-control-wrap">
                                        <textarea class="form-control" rows="1"  name="global_site_tag" placeholder="Google Ads Tag"><?php echo $data['info']['global_site_tag']; ?></textarea>
                                    </div>
                                    <span class="help_text">Enter your Global site tag</span>
                                     <div class="form-control-wrap mt-4">
                                        <textarea class="form-control" rows="1"  name="event_snippet"><?php echo $data['info']['event_snippet']; ?></textarea>
                                    </div>
                                    <span class="help_text">Enter your Event snippet to track how many clients reached "Thank you for your order" page</span>
                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="card card-shadow">
                            <div class="card-inner">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-control-wrap">
                                           <h5 class="card-title">GDPR cookie consent banner</h5>
                                            <div class="custom-control-lg custom-switch settings_switch">
                                                <input type="checkbox" class="custom-control-input" id="gdpr_cookies" name="gdpr_cookies" value="1" <?php echo (($data['info']['gdpr_cookies']==1)? 'checked' : '' );  ?>>
                                                <label class="custom-control-label fw-bold" for="gdpr_cookies" id="gdpr_text"><?php echo (($data['info']['gdpr_cookies']==1)? 'ENABLED' : 'DISABLED' ); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label" >Request visitors' approval for on-site activity tracking<br> with cookies
                                        </label>
                                        <p>Displays a dialog box on your site which explicitly asks if your visitors are okay with their activity being tracked. Visitors which opt out of being tracked do not count in statistics gathered by Google Analytics and Facebook Pixel.</p>
                                    </div>
                                    <div class="form-group col-md-6">
                                            
                                            <div class="form-control-wrap gdpr_text gdpr_banner_top"  id="elementsToOperateOn">
                                                <input type="text" name="gdpr_title" id="gdpr_title" class="form-control" placeholder="GDPR Title" value="<?php echo $data['info']['gdpr_title']; ?>"><br>
                                                <textarea class="form-control" rows="1"  name="gdpr_content" placeholder="GDPR Content" id="gdpr_content"><?php echo $data['info']['gdpr_content']; ?></textarea>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                 </div>
            </div>
                <!-- .nk-block -->
        </div>
    </div>
</div>

<!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>

<?php if (isset($_GET['a'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Analytics info updated successfully !!</h5>', 'success', {
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

<script type="text/javascript">

    // Add Blog

    $("#updateAnalytics").validate({
        rules: {
            google_analytics_id: {
                required: true
            }
        },
        messages: {
            google_analytics_id: {
                required: "Please Enter google analytics id",
            }

        },
        submitHandler: function(form) {
            toastr.clear();
                Swal.fire({
                    title: "Are you sure to save this changes?",
                    text: "So that changes will be reflected in your sites !!",
                    icon: 'warning',
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if (result.value) {
                        saveForm();
                    }
                });
           
            return false;
        }
    });

    // Save Form

    function saveForm() {
        var formname = document.getElementById("updateAnalytics");
        var formData = new FormData(formname);
        $.ajax({
            url: core_path + "analytics/api/update",
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
                    window.location = core_path + "analytics?a=success";
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
    }

    // GDPR  Textarea enable and disable
    $(document).ready(function() {
      handleStatusChanged();
    });
    function handleStatusChanged() {
        $('#gdpr_cookies').on('change', function () {
          toggleStatus();   
        });
    }
    function toggleStatus() {
        if ($('#gdpr_cookies').is(':checked')) {
            $('#elementsToOperateOn :input').removeAttr('DISABLED');
            $('#gdpr_text').text('ENABLED');
        } else {
            $('#elementsToOperateOn :input').attr('DISABLED', true);
            $('#gdpr_text').text('DISABLED');
        }   
    }

    // Analytics  Textarea ENABLED and DISABLED
    $(document).ready(function() {
        $('#analytics_id_switch_text').text('ENABLED');
        handleAnalyticsChanged();
    });
    function handleAnalyticsChanged() {
        $('#analytics_id_switch').on('change', function () {
          toggleAnalytics();   
        });
    }
    function toggleAnalytics() {
        if ($('#analytics_id_switch').is(':checked')) {
           $('#analytics_textarea :input').removeAttr('DISABLED');
            $('#analytics_id_switch_text').text('ENABLED');
        } else {
            $('#analytics_textarea :input').attr('DISABLED', true);
            $('#analytics_id_switch_text').text('DISABLED');
        }   
    }

</script>