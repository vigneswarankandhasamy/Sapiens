<?php require_once 'includes/top.php'; ?>

<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-xl">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title"><?php echo $data['page_title'] ?></h3>
                            <div class="nk-block-des text-soft">
                                <p>Sale analytics overview in dashboard.</p>
                            </div>
                        </div>
                        <!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <?php if($data['index_info']['total_enquiries']!=0) { ?>
                            <a href="#" class="btn btn-white btn-dim btn-outline-primary export_button" data-toggle="modal" data-target="#exportDaataModalForm"><em class="icon ni ni-download-cloud"></em><span><span class="d-none d-md-inline">Export</span> Report</span></a>
                            <?php } ?>
                            <!--  <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li>
                                                <div class="drodown">
                                                    <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-toggle="dropdown"><em class="d-none d-sm-inline icon ni ni-calender-date"></em><span><span class="d-none d-md-inline">Last</span> 30 Days</span><em class="dd-indc icon ni ni-chevron-right"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="link-list-opt no-bdr">
                                                            <li><a href="#"><span>Last 30 Days</span></a></li>
                                                            <li><a href="#"><span>Last 6 Months</span></a></li>
                                                            <li><a href="#"><span>Last 1 Years</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></a></li>
                                        </ul>
                                    </div>
                                </div> -->
                        </div>
                        <!-- .nk-block-head-content -->
                    </div>
                    <!-- .nk-block-between -->
                </div>
                <!-- .nk-block-head -->


                <div class="nk-block">
                    <div class="row gy-gs">
                        <div class="col-md-6 col-lg-4">
                            <div class="nk-wg-card  card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-iv-wg2">
                                        <div class="nk-iv-wg2-title">
                                            <h6 class="title">Today's Enquiries</em></h6>
                                        </div>
                                        <div class="nk-iv-wg2-text">
                                            <div class="nk-iv-wg2-amount">
                                                <?php echo $data['index_info']['today_enquiries']; ?> 
                                                <?php
                                                    $today  = date("d-m-Y");
                                                ?>
                                                <span class="change up"><span class=""></span><a href="<?php echo COREPATH ?>enquiry/index/<?php echo $today?>/<?php echo $today?>/all">View All</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- .card -->
                        </div>
                        <!-- .col -->
                        <div class="col-md-6 col-lg-4">
                            <div class="nk-wg-card  card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-iv-wg2">
                                        <div class="nk-iv-wg2-title">
                                            <h6 class="title">Total Enquiries </em></h6>
                                        </div>
                                        <div class="nk-iv-wg2-text">
                                            <div class="nk-iv-wg2-amount">
                                                <?php echo $data['index_info']['total_enquiries']; ?> <span class="change up"><span class=""></span><a href="<?php echo COREPATH ?>enquiry/index/0/0/all">View All</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- .card -->
                        </div>
                        <!-- .col -->
                        <div class="col-md-12 col-lg-4">
                            <div class="nk-wg-card  card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-iv-wg2">
                                        <div class="nk-iv-wg2-title">
                                            <h6 class="title">Total Projects </em></h6>
                                        </div>
                                        <div class="nk-iv-wg2-text">
                                            <div class="nk-iv-wg2-amount">
                                                <?php echo $data['project']['id']; ?> <span class="change up"><span class=""></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- .card -->
                        </div>
                        <!-- .col -->
                    </div>
                    <!-- .row -->
                </div>


                <div class="nk-block">
                    <div class="row g-gs">
                        <div class="col-lg-6 col-xxl-12">
                            <div class="card card-bordered">
                                <div class="card-inner">
                                    <div class="card-title-group align-start mb-2">
                                        <div class="card-title">
                                            <h6 class="title">Enquiry Vs Date</h6>
                                            <!-- <p>In last 30 days revenue from subscription.</p> -->
                                        </div>
                                        <div class="card-tools">
                                            
                                        </div>
                                    </div>
                                    <div class="device-status my-auto">
                                        <div class="nk-sale-data-group flex-md-nowrap g-4">
                                            <div class="nk-sale-data">
                                                <span class="amount"><?php echo $data['chart_data']['count_data']['totalEnquires'] ?><!-- <span class="change up text-success"><em class="icon ni ni-arrow-long-up"></em>4.26%</span> --></span>
                                                <span class="sub-title">Last 30 Days</span>
                                            </div>
                                        </div>
                                        <div class="device-status-ck nk-sales-ck sales-revenue">
                                            <canvas class="line-chart" id="filledLineChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="nk-block">
                    <div class="card card-bordered card-full">
                        <div class="card-inner">
                            <div class="card-title-group">
                                <div class="card-title">
                                    <h6 class="title"><span class="mr-2">Today's Enquiry list</span></h6>
                                </div>
                                <div class="card-tools">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt">
                                            <?php if($data['todays_enquiry']!="" ) { ?>
                                            <!-- <button class="btn btn-primary export_enquiry_list"><em class="icon ni ni-arrow-down"></em><span>Export</span>
                                            </button> -->
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
                                            <?php echo $data['todays_enquiry'] ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- .card -->
                </div>

                <div class="nk-block">
                    <div class="card card-bordered card-full">
                        <div class="card-inner">
                            <div class="card-title-group">
                                <div class="card-title">
                                    <h6 class="title"><span class="mr-2">Project list</span></h6>
                                </div>
                                <div class="card-tools">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt">
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




                <!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<!-- content @e -->

<!-- Modal Form -->
<div class="modal fade" tabindex="-1" id="exportDaataModalForm">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Enquiry</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form action="#" id="exportDataForm" class="form-validate is-alter">
                    <input type="hidden" id="exportData" value="enquiry">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-label" for="email-address">Export</label>
                            <div class="g-4 align-center flex-wrap">
                                <div class="g">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input exportBy" name="export" value="1" value="1" id="customRadio7" checked="">
                                        <label class="custom-control-label" for="customRadio7">Overall</label>
                                    </div>
                                </div>
                                <div class="g">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input exportBy" value="2" name="export" value="2" id="customRadio6">
                                        <label class="custom-control-label" for="customRadio6">By Date</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6 fromToDate display_block">
                            <label class="form-label" for="email-address">From</label>
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-left export_enq_model_date_input_icon">
                                    <em class="icon ni ni-calendar"></em>
                                </div>
                                <input type="text" class="form-control date-picker" autocomplete="off" id="formDate" name="from_date" placeholder="From Date" data-date-format="dd-MM-yyyy">
                            </div>
                        </div>
                        <div class="form-group col-md-6 fromToDate display_block">
                            <label class="form-label" for="email-address">To</label>
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-lef export_enq_model_date_input_icon">
                                    <em class="icon ni ni-calendar"></em>
                                </div>
                                <input type="text" class="form-control date-picker" data-date-format="dd-MM-yyyy" autocomplete="off" id="toDate" name="to_date" placeholder="To Date">
                            </div>
                        </div>

                    </div>


                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary float-right">Export</button>
                    </div>
                </form>
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
            <div class="modal-body" id="enq_Content">
                
            </div>
        </div>
    </div>
</div>

    

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
                $("#enq_Content").html(data);
                $('.enq_tr_'+id_nor).addClass('enq_readed_msg');
            }
        });

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
                if(data==1) {
                    $('.enq_tr_'+id_nor).addClass('enq_readed_msg');
                } else {
                    $('.enq_tr_'+id_nor).removeClass('enq_readed_msg');
                }
            }
        });
    });


    $(".exportBy").click(function() {
        var value = $(this).val();
        if (value == 1) {
            $(".fromToDate").addClass('display_block');
        } else {
            $(".fromToDate").removeClass('display_block');
        }
    });

    $("#exportDataForm").validate({
        rules: {
            from_date: {
                required: function(element) {
                    if ($(".exportBy").val() == 2) {
                        return true;
                    } else {
                        return 0;
                    }
                }
            },
            to_date: {
                required: function(element) {
                    if ($(".exportBy").val() == 2) {
                        return true;
                    } else {
                        return 0;
                    }
                }

            },
        },
        messages: {
            from_date: {
                required: "Please Select From Date",
            },
            to_date: {
                required: "Please Select To Date",
            }
        },
        submitHandler: function(form) {
            var content = $(form).serialize();

            var exportData = $("#exportData").val();
            // alert(exportData);
            // alert($("#formDate").val());
            // alert($("#toDate").val());

            if ($("#customRadio6").prop("checked") == true) {
                var fromDate = new Date($("#formDate").val());
                var newDate = fromDate.toString('dd-MM-yy');
                var formatted_from_date = fromDate.getDate() + "-" + (fromDate.getMonth() + 1) + "-" + fromDate.getFullYear();

                var toDate = new Date($("#toDate").val());
                var formatted_to_date = toDate.getDate() + "-" + (toDate.getMonth() + 1) + "-" + toDate.getFullYear();

                window.location = core_path + "exportData/" + exportData + "/" + formatted_from_date + "/" + formatted_to_date;
            } else {
                window.location = core_path + "exportData/" + exportData;

            }
            $("#exportDataForm")[0].reset();
            $('#exportDaataModalForm').modal('hide');
            $(".fromToDate").addClass('display_block');
            $(this).unbind('mouseenter mouseleave')
            return false;
        }
    });


    // Order list export

    $(".export_enquiry_list").click(function() { 

        var url  = core_path;

        var text_msg = "Are you sure to export today's enquiry list ?";
        html_string = $.parseHTML( text_msg );
       
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
                    window.location = core_path+"exportData/enquiry/today";
                }
            });
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
                url: core_path + "home/api/trashClassifiedProject",
                dataType: "html",
                data: { result: value },
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        //alert(data);
                        window.location = core_path + "?t=success";
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




<?php if (isset($_GET['e'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        toastr.clear();
        NioApp.Toast('<h5>Company Profile  Updated successfully !!</h5>', 'success', {
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

<?php if (isset($_GET['pa'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        toastr.clear();
        NioApp.Toast('<h5>Projected added successfully !!</h5>', 'success', {
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

<script type="text/javascript">
    // Sales vs Date chart 

    var filledLineChart = {
        labels: <?php echo $data['chart_data']['x_axis']; ?> ,
        dataUnit: 'nos',
        lineTension: .4,
        datasets: [{
            label: "Total Received",
            color: "#9d72ff",
            background: NioApp.hexRGB('#9d72ff', .4),
            data: <?php echo $data['chart_data']['y_axis_sales']; ?>
        }]
    };
</script>

<?php if (isset($_GET['t'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
setTimeout(function() {
    toastr.clear();
    NioApp.Toast('<h5>Classified project deleted successfully !!</h5>', 'success', {
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
    function lineChart(selector, set_data) {
        var $selector = selector ? $(selector) : $('.line-chart');
        $selector.each(function () {
        var $self = $(this),
          _self_id = $self.attr('id'),
          _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

        var selectCanvas = document.getElementById(_self_id).getContext("2d");
        var chart_data = [];

      for (var i = 0; i < _get_data.datasets.length; i++) {
        chart_data.push({
          label: _get_data.datasets[i].label,
          tension: _get_data.lineTension,
          backgroundColor: _get_data.datasets[i].background,
          borderWidth: 2,
          borderColor: _get_data.datasets[i].color,
          pointBorderColor: _get_data.datasets[i].color,
          pointBackgroundColor: '#fff',
          pointHoverBackgroundColor: "#fff",
          pointHoverBorderColor: _get_data.datasets[i].color,
          pointBorderWidth: 2,
          pointHoverRadius: 4,
          pointHoverBorderWidth: 2,
          pointRadius: 4,
          pointHitRadius: 4,
          data: _get_data.datasets[i].data
        });
      }

      var chart = new Chart(selectCanvas, {
        type: 'line',
        data: {
          labels: _get_data.labels,
          datasets: chart_data
        },
        options: {
          legend: {
            display: _get_data.legend ? _get_data.legend : false,
            rtl: NioApp.State.isRTL,
            labels: {
              boxWidth: 12,
              padding: 20,
              fontColor: '#6783b8'
            }
          },
          maintainAspectRatio: false,
          tooltips: {
            enabled: true,
            rtl: NioApp.State.isRTL,
            callbacks: {
              title: function title(tooltipItem, data) {
                return data['labels'][tooltipItem[0]['index']];
              },
              label: function label(tooltipItem, data) {
                return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
              }
            },
            backgroundColor: '#eff6ff',
            titleFontSize: 13,
            titleFontColor: '#6783b8',
            titleMarginBottom: 6,
            bodyFontColor: '#9eaecf',
            bodyFontSize: 12,
            bodySpacing: 4,
            yPadding: 10,
            xPadding: 10,
            footerMarginTop: 0,
            displayColors: false
          },
          scales: {
            yAxes: [{
              display: true,
              position: NioApp.State.isRTL ? "right" : "left",
              ticks: {
                beginAtZero: false,
                fontSize: 12,
                fontColor: '#9eaecf',
                padding: 10
              },
              gridLines: {
                color: NioApp.hexRGB("#526484", .2),
                tickMarkLength: 0,
                zeroLineColor: NioApp.hexRGB("#526484", .2)
              }
            }],
            xAxes: [{
              display: true,
              ticks: {
                fontSize: 12,
                fontColor: '#9eaecf',
                source: 'auto',
                padding: 5,
                reverse: NioApp.State.isRTL
              },
              gridLines: {
                color: "transparent",
                tickMarkLength: 10,
                zeroLineColor: NioApp.hexRGB("#526484", .2),
                offsetGridLines: true
              }
            }]
          }
        }
      });
    });
  } // init line chart


  lineChart();
        </script>