<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-md">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                    <div class="form_submit_bar">
                        <div class="container wide-md">
                            <div class="row">
                                <div class="col-md-6">
                                    <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>enquiry/contact"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                    <h3><?php echo $data['page_title'] ?></h3>
                                </div>
                                <div class="col-md-6">
                                    <div class="submit_button_wrap">
                                        
                                        <button class="btn btn-danger" onclick="window.close();" type="submit"><em class="icon ni ni-cross-sm"></em> Close</button>
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
                                        <h5 class="card-title">General Info</h5>
                                        
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                                <div class="form-group col-md-3">
                                   <label class="card-title">Name</label>
                                </div>
                                <div class="form-group col-md-3" id="analytics_textarea">
                                    :  <?php echo $data['info']['name'] ?>                                     
                                </div>
                            </div>
                             <div class="row">
                                <div class="form-group col-md-3">
                                   <label class="card-title">Email</label>
                                </div>
                                <div class="form-group col-md-3" id="analytics_textarea">
                                    :  <?php echo $data['info']['email'] ?>                                     
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                   <label class="card-title">Mobile</label>
                                </div>
                                <div class="form-group col-md-3" id="analytics_textarea">
                                    :  <?php echo $data['info']['mobile'] ?>                                     
                                </div>
                            </div>
                          
                        </div>
                    </div>
                    <div class="card card-shadow">
                        <div class="card-inner">
                             <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-control-wrap">
                                        <h5 class="card-title">Enquiry Details</h5>
                                        
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                                <div class="form-group col-md-3">
                                   <label class="card-title">Subject</label>
                                </div>
                                <div class="form-group col-md-3" id="analytics_textarea">
                                    :  <?php echo $data['info']['subject'] ?>                                     
                                </div>
                            </div>
                             <div class="row">
                                <div class="form-group col-md-3">
                                   <label class="card-title">Message</label>
                                </div>
                                <div class="form-group col-md-3" id="analytics_textarea">
                                    :  <?php echo $data['info']['message'] ?>                                     
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>

<!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>


