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
                                        <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a>
                                        </li>
                                        <li class="breadcrumb-item active">
                                            <?php echo $data[ 'page_title'] ?>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                <form id="orderReportFilter">
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li>
                                                <input type="text" name="valid_from" id="valid_from" autocomplete="off" class="form-control date-picker" data-date-format="dd-mm-yyyy" value="<?php echo (($data['from_date']=='today')? "" : (($data['from_date']=='0')? "" : $data['from_date']))  ; ?>" placeholder="From Date">
                                            </li>
                                            <li>
                                                <input type="text" name="valid_to" id="valid_to" class="form-control date-picker" data-date-format="dd-mm-yyyy" value="<?php echo (($data['to_date']=='today')? "" : (($data['to_date']=='0')? "" : $data['to_date']))  ; ?>" autocomplete="off" placeholder="To Date">
                                            </li>
                                             <li class="sellect_vendor">
                                                    <div class="form-group sellect_vendor_drp enquiry_list_dropdown"  >
                                                       <div class="form-control-wrap">
                                                            <select class="form-select" name="read_status" id="read_status"  data-search="on">
                                                                <option value='all'  <?php echo (($data['type']=="all")? "selected" : "") ?>>All</option>
                                                                <option value='read' <?php echo (($data['type']=="read")? "selected" : "") ?>>Read</option>
                                                                <option value='unread'  <?php echo (($data['type']=="unread")? "selected" : "") ?>>Unread</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </li>
                                            <li class="nk-block-tools-opt">
                                                <button type="submit" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span>
                                                </button>
                                            </li>
                                        </ul>
                                        <ul>
                                            <div class="row form_from_to_error">
                                                <div class="col-md-4">
                                                    <div id="fromdateerror"></div>
                                                </div>
                                                <div class="col-md-4 todata_error">
                                                    <div id="todateerror"></div>
                                                </div>
                                            </div>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- .nk-block-head-content -->
                    </div>
                    <!-- .nk-block-between -->
                </div>
                <!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-bordered card-full">
                        <div class="card-inner">
                            <div class="card-title-group">
                                <div class="card-title">
                                    <h6 class="title"><span class="mr-2">Enquiry list</span></h6>
                                </div>
                                <div class="card-tools">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt">
                                            <?php if($data[ 'list']!="" ) { ?>
                                            <button class="btn btn-primary export_enquiry_list"><em class="icon ni ni-arrow-down"></em><span>Export</span>
                                            </button>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card card-shadow">
                                <div class="card-inner">
                                    <table class="datatable-init nk-tb-list nk-tb-ulist is-compact" data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span>
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">S.no</span>
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Date</span>
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Name </span>
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Email </span>
                                                </th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Mobile </span>
                                                </th>
                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Action</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $data[ 'list'] ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- .card -->
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

<!--  side bar details view -->
<!-- <div class="form_panel_warp view_enquiry_class">
    <form id="viewEnquiry" method="POST">
        <div class="form_panel_head">
            <h6 class="mb-0">Enquiry Details</h6><a class="nk-demo-close toggle btn btn-icon btn-trigger revarse mr-n2 active closeFormPanel" data-formclass='view_enquiry_class' data-form='viewEnquiry' href="javascript:void();"><em class="icon ni ni-cross"></em></a>
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
<div class="form_panel_overlay"></div> -->

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
    $(".open_enq_model").click(function() {
        var value = $(this).data("option");
        var id_nor = $(this).data("dycryprt_id");
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
                $('.enq_tr_'+id_nor).addClass('enq_readed_msg');
            }
        });

    });

    $(".close").click(function() { 
        location.reload();
    });

   
    $(".readStatusToogle").click(function() { 
        var value = $(this).data("option");
        var id_nor = $(this).data("dycryprt_id");
        $.ajax({
            type: "POST",
            url: core_path + "enquiry/api/toggleReadStatus",
            dataType: "html",
            data: {
                result: value
            },
            beforeSend: function() {
            },
            success: function(data) {
                location.reload();
            }
        });
    });

    // Trash Enquiry

    $(".trashEnquiry").click(function(e) {
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
                    url: core_path + "enquiry/api/trashEnquiry",
                    dataType: "html",
                    data: {
                        result: value
                    },
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if (data == 1) {
                            //alert(data);
                            window.location = window.location.href + "?t=success";
                        } else {
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
            url: core_path + "enquiry/api/info",
            dataType: "html",
            data: {
                result: value
            },
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

<script type="text/javascript">
    // Order list export

    $(".export_enquiry_list").click(function() {

        var from = $("#valid_from").val();
        var to   = $("#valid_to").val();
        var type = $("#read_status").val();

        if (from == "") {
            var text_msg = "Are you sure to export overall enquiry list ?";
            html_string = $.parseHTML(text_msg);
        } else {
            var text_msg = "Are you sure to export <br> enquiry list from <br> " + from + " to " + to + " ?";
            html_string = $.parseHTML(text_msg);
        }

        toastr.clear();
        Swal.fire({
            title: text_msg,
            text: "",
            icon: 'warning',
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.value) {
                if (from == "") {
                    window.location = core_path + "exportData/enquiry/0/0/" + type;
                } else {
                    window.location = core_path + "exportData/enquiry/" + from + "/" + to + "/" + type;
                }
            }
        });
    });

    $.validator.addMethod("from_date_er", function(value, elem) {
        var from = new Date($("#valid_from").val().replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"))
        var to = new Date($("#valid_to").val().replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"))
        return (from <= to);
    });


    $("#orderReportFilter").validate({
        rules: {
            valid_from: {
                required: function(element){
                    if($("#valid_to").val()!=""){
                        return true;
                    } else {
                        return false;
                    }
                },
            },
            valid_to: {
                required: function(element){
                    if($("#valid_from").val()!=""){
                        return true;
                    } else {
                        return false;
                    }
                },
            }
        },
        messages: {
            valid_from: {
                required: "From date Can't be empty",
                from_date_er: "From date must be less than to date",
            },
            valid_to: {
                required: "To date Can't be empty",
            }
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "valid_from") {
                error.appendTo("#fromdateerror");
            } else if (element.attr("name") == "valid_to") {
                error.appendTo("#todateerror");
            } else {
                error.insertAfter(element);
            }

        },
        submitHandler: function(form) {


            if($("#valid_from").val()=="") {
                window.location = core_path + "enquiry/index/0/0/" + $("#read_status").val();
            } else {
                window.location = core_path + "enquiry/index/" + $("#valid_from").val() + "/" + $("#valid_to").val() + "/" + $("#read_status").val();
            }
            
            return false;
        }
    });
</script>


<?php if (isset($_GET[ 't'])): ?>
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