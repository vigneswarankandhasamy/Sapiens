<?php require_once 'includes/top.php'; ?>

    <!-- content @s -->
     <div class="nk-content nk-content-fluid">
        <div class="container-fluid wide-md">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    
                    <div class="nk-block">
                        <form method="post"  class="form-validate is-alter" id="addMigration">
                            <input type="hidden" name="fkey" value="<?php echo $_SESSION['add_migrations'] ?>">
                            <div class="form_submit_bar">
                                <div class="container wide-md">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>migrations"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                            <h3><?php echo $data['page_title'] ?></h3>
                                        </div>
                                        <div class="col-md-6">
                                            <p>
                                                <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>migrations"> Cancel</button>
                                                <button class="btn btn-success" type="submit"><em class="icon ni ni-check-thick"></em> Save </button>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-shadow">
                                <div class="card-inner">
                                    <h5 class="card-title">General Info</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Added By <em>*</em></label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="name" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Query Type <em>*</em></label>
                                                <div class="form-control-wrap">
                                                    <select class="form-control" required="" name="type">
                                                        <option value=""> Select Query type</option>
                                                        <option value="new"> New Table</option>
                                                        <option value="alter"> Alter Table</option>
                                                        <option value="insert"> Insert Data</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <br>
                                            <div class="form-group">
                                                <label class="form-label">SQL Query <em>*</em></label>
                                                <div class="form-control-wrap">
                                                    <textarea class="form-control" rows="10" required="" name="sql_query"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Remarks</label>
                                                <div class="form-control-wrap">
                                                    <textarea class="form-control" name="remarks"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="default-01"></label>
                                                <div class="summernote-editor"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card card-shadow">
                                <div class="card-inner">
                                    <h5 class="card-title">General Info</h5>
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <div class="mt-lg-100">
                                                
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    
       
    
    
    
    
    <!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
    
    
    /*-----------------------------------------------------------
                            Migrations 
    ------------------------------------------------------------*/

    // Add Migrations

    $("#addMigration").validate({
        rules: {

        },
        messages: {
            added_by: {
                required: "Please fill this information",
            },
        },
        submitHandler: function(form) {
            var content = $("#addMigration").serialize();
            $.ajax({
                type: "POST",
                url: core_path + "resource/ajax_redirect.php?page=addMigration",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = core_path + "migrations?a=success";
                    } else {
                        $(".form-error").html(data);
                    }
                }
            });
            return false;
        }
    });
    
</script>
