<?php require_once 'includes/top.php'; ?>
<!-- <div class="calc-banner otherpage-banner m-0">
    <img src="<?php echo $data['page_banner']!="" ? SRCIMG.$data['page_banner']['file_name'] : IMGPATH."calculator-banner.jpg" ?>" alt="image" class="common-banner">
    <div class="other-banner-title">
        <p>Calculator</p> -->
        <!-- <button type="button" class="btn btn-sm banner-btn rounded-pill"><a href="<?php echo BASEPATH ?>calculator/plastering">View more</a></button> -->
    <!-- </div>     
</div> -->
<style>
table {
  border-collapse: collapse;
  width: 100%;
  border:solid;border-width:thin;
}

th, td {
  text-align: left;
  padding: 8px;
  border:solid;border-width:thin;
}
tr:nth-child(even) {background-color: #f2f2f2;}

.card-body {
    flex: 1 1 auto;
    padding: 8px !important;
}

</style>
<div class="breadcrumbs_area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="<?php echo BASEPATH ?>calculator/brickwork">Brick Work</a></li>
                        <li><a href="<?php echo BASEPATH ?>calculator/plastering">Plastering</a></li>
                        <li><a href="<?php echo BASEPATH ?>calculator/concrete">Concrete</a></li>
                        <li><a href="<?php echo BASEPATH ?>calculator/painting" class="breadcrumb_active">Painting</a></li>
                        <li><a href="<?php echo BASEPATH ?>calculator/floring">Flooring</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contact_area calc">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card list_wrap">
                    <h3>Painting Calculation</h3>
                    <div class="row">
                        <div class="col-md-7 col-xs-12 col-sm-12">
                            <form method="POST" id="wallFinishCalculation" action="#">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="inputEmail3" class="col-form-label">Unit</label>
                                        <select class="form-select" name="unit" id="unit">
                                            <option value="meter_or_cm">Meter/CM</option>
                                            <option value="feet_or_inch">Feet/Inch</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="inputEmail3" class="col-form-label">Carpet Area </label>
                                        <div class="input-group">
                                            <div class="input-group-area"> 
                                                <input type="number"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==7) return false;"onpaste="let pasteData = event.clipboardData.getData('text'); if(pasteData){pasteData.replace(/[^0-9]*/g,'');} " class="form-control" name="carpet_area" id="carpet_area" placeholder="Sq.meter">
                                            </div>
                                            <div class="input-group-icon carpet_area">Sq.meter</div>
                                        </div>
                                        <div id="carpet_areaError">
                                        </div>
                                        <input type="hidden" name="carpet_area_vs_dr_wid_ar"min="0"   id="carpet_area_vs_dr_wid_ar" value="0" >
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="inputEmail3" class="col-form-label">No. of Doors</label>
                                        <div class="input-group">
                                            <div class="input-group-area">
                                                <input type="number"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==7)  return false;"onpaste="let pasteData = event.clipboardData.getData('text'); if(pasteData){pasteData.replace(/[^0-9]*/g,'');} "  class="form-control" name="no_of_doors"min="0"   id="no_of_doors" placeholder="Nos">
                                            </div>
                                            <div class="input-group-icon no_of_doors">Nos</div>
                                        </div>
                                        <div id="no_of_doorsError">
                                        </div>
                                    </div>
                                    <div class="col with_decimal">
                                        <label for="inputEmail3" class="col-form-label">Door Width</label>
                                        <div class="input-group">
                                            <div class="input-group-area">
                                                <input type="number"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==7)  return false;" class="form-control" name="door_width" min="0"  id="door_width" placeholder="meter">
                                            </div>
                                            <div class="input-group-icon door_width">meter</div>
                                        </div>
                                        <div id="door_widthError">
                                        </div>
                                    </div>
                                    <div class="col with_decimal">
                                        <label for="inputEmail3" class="col-form-label">Door Height</label>
                                        <div class="input-group">
                                            <div class="input-group-area">
                                                <input type="number"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==7)  return false;" class="form-control" name="door_hight" min="0"  id="door_hight" placeholder="meter">
                                            </div>
                                            <div class="input-group-icon door_hight">meter</div>
                                        </div>
                                        <div id="door_hightError">
                                        </div>
                                    </div>
                                    <div class="col without_delimal display_none">
                                        <label for="inputEmail3" class="col-form-label">Door Width</label>
                                        <div class="input-group">
                                            <div class="input-group-area">
                                                <input type="number"  class="form-control" name="wod_door_width" min="0"  id="wod_door_width"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==7)  return false;"  placeholder="feet">
                                            </div>
                                            <div class="input-group-icon wod_door_width">feet</div>
                                        </div>
                                        <div id="wod_door_widthError">
                                        </div>
                                    </div>
                                    <div class="col without_delimal display_none">
                                        <label for="inputEmail3" class="col-form-label">Door Height</label>
                                        <div class="input-group">
                                            <div class="input-group-area">
                                                <input type="number"  class="form-control" name="wod_door_hight" min="0"  id="wod_door_hight"   pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==7)  return false;"  placeholder="feet">
                                            </div>
                                            <div class="input-group-icon wod_door_hight">feet</div>
                                        </div>
                                        <div id="wod_door_hightError">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="inputEmail3" class="col-form-label">No. of Windows </label>
                                        <div class="input-group">
                                            <div class="input-group-area">
                                                <input type="number"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==7) return false;" class="form-control" name="no_of_windows" min="0"  id="no_of_windows" placeholder="Nos">
                                            </div>
                                            <div class="input-group-icon no_of_windows">Nos</div>
                                        </div>
                                        <div id="no_of_windowsError">
                                        </div>
                                    </div>
                                    <div class="col with_decimal">
                                        <label for="inputEmail3" class="col-form-label">Window Width</label>
                                        <div class="input-group">
                                            <div class="input-group-area">
                                                <input type="number"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==7) return false;" class="form-control" name="window_width" min="0"  id="window_width" placeholder="meter">
                                            </div>
                                            <div class="input-group-icon window_width">meter</div>
                                        </div>
                                        <div id="window_widthError">
                                        </div>
                                    </div>
                                    <div class="col with_decimal">
                                        <label for="inputEmail3" class="col-form-label">Window Height</label>
                                        <div class="input-group">
                                            <div class="input-group-area">
                                                <input type="number"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==7) return false;" class="form-control" name="window_hight" min="0"  id="window_hight" placeholder="meter">
                                            </div>
                                            <div class="input-group-icon window_hight">meter</div>
                                        </div>
                                        <div id="window_hightError">
                                        </div>
                                    </div>
                                    <div class="col without_delimal display_none">
                                        <label for="inputEmail3" class="col-form-label">Window Width</label>
                                         <div class="input-group">
                                            <div class="input-group-area">
                                                <input type="number"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==7) return false;" class="form-control" name="wod_window_width" min="0"  id="wod_window_width"  placeholder="feet">
                                            </div>
                                            <div class="input-group-icon wod_window_width">feet</div>
                                        </div>
                                        <div id="wod_window_widthError">
                                        </div>
                                    </div>
                                    <div class="col without_delimal display_none">
                                        <label for="inputEmail3" class="col-form-label">Window Height</label>
                                         <div class="input-group">
                                            <div class="input-group-area">
                                                <input type="number"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==7) return false;" class="form-control" name="wod_window_hight" min="0"  id="wod_window_hight"  placeholder="feet">
                                            </div>
                                            <div class="input-group-icon wod_window_hight">feet</div>
                                        </div>
                                        <div id="wod_window_hightError">
                                        </div>
                                    </div>
                                </div>
                                <div class="calculator_error_msg">
                                </div>
                                <button type="submit" class="btn button">Calculate</button>
                            </form>
                        </div>
                        <div class="col-md-5 col-xs-12 col-sm-12 display_none" id="result_column">
                            <div class="card border-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h4 class="card-title">Result</h4>
                                        </div>
                                        <div class="col-lg-10 calulation_result_tbl clearfix clear">
                                        <table id='tableData' class='tableData'></table><br>
                                            <div class="chart" style="width: 65%">
                                                <canvas class="d-block" id="calcwall"></canvas>
                                            </div>
                                            <p class="approx_value"> *approximate value</p>
                                        </div>                                           
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-lg-12 mt-5">
                <div class="section_title">
                    <h2><span> <strong>Related</strong>Products</span></h2>
                </div>
                <div class="product_carousel product_column5 owl-carousel calc-products">
                    <?php echo $data['related_products'] ?>
                </div>
            </div> 
        </div>
    </div>
</div>

<!--banner area start-->
<?php if($data['page_banner']!="") { ?>
    <section class="banner_area mb-50 d-none d-lg-block d-md-block">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="single_banner banner_fullwidth">
                        <div class="banner_thumb">
                                <?php echo $data['page_banner'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>
<!--banner area end-->

<!--banner area start-->
<?php if($data['page_banner']!="") { ?>
    <section class="banner_area mb-50 d-block d-lg-none d-md-none">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="single_banner banner_fullwidth">
                        <div class="banner_thumb">
                                <?php echo $data['page_banner'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>
<!--banner area end-->

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">
    
    // Foot/Meter changes

    $("#unit").change(function() { 
       var unit_val =  $(this).val();
       
        if(unit_val=="feet_or_inch") {
            $('#carpet_area').attr('placeholder', 'Sq.feet');
            $('.carpet_area').html('Sq.feet');
            $('#window_width').attr('placeholder', 'feet');
            $('#window_hight').attr('placeholder', 'feet');
           

            $('.with_decimal').addClass('display_none');
            $('.without_delimal').removeClass('display_none');
            $('#door_width').val("");
            $('#door_hight').val("");
            $('#window_width').val("");
            $('#window_hight').val("");
        } else {
            $('#carpet_area').attr('placeholder', 'Sq.meter');
            $('.carpet_area').html('Sq.meter');
            $('#window_width').attr('placeholder', 'meter');
            $('#window_hight').attr('placeholder', 'meter');

            $('.with_decimal').removeClass('display_none');
            $('.without_delimal').addClass('display_none');
            $('#wod_door_width').val("");
            $('#wod_door_hight').val("");
            $('#wod_window_width').val("");
            $('#wod_window_hight').val("");
        }

    });

    $.validator.addMethod("check_area", function (value, elem) {
        
        if($('#unit').val()=='meter_or_cm') {
            var door_area     = $("#door_width").val() * $("#door_hight").val();
            var window_area   = $("#window_width").val() * $("#window_hight").val(); 
        } else {
            var door_area     = $("#wod_door_width").val() * $("#wod_door_hight").val();
            var window_area   = $("#wod_window_width").val() * $("#wod_window_hight").val(); 
        }


        var door_win_area = door_area + window_area;

        if($("#carpet_area").val() < door_win_area ){
            return false;
        } else {
            return true;
        }
    });

   $("#wallFinishCalculation").validate({
     ignore: "",
     rules: {
        carpet_area: {
            required: true,
            digits : true,            
        },
        no_of_doors: {
            required: true,
            digits : true,            
        },
        door_width: {
             required: function(element){
                    if($("#unit").val()=='meter_or_cm'){
                        return true;
                    } else {
                        return false;
                    }
                },
            digits : true,                
        },
        door_hight: {
             required: function(element){
                    if($("#unit").val()=='meter_or_cm'){
                        return true;
                    } else {
                        return false;
                    }
                },
            digits : true,                
        },
        wod_door_width: {
            required: function(element){
                    if($("#unit").val()=='feet_or_inch'){
                        return true;
                    } else {
                        return false;
                    }
                },
            digits : true,                
        },
        wod_door_hight: {
             required: function(element){
                    if($("#unit").val()=='feet_or_inch'){
                        return true;
                    } else {
                        return false;
                    }
                },
            digits : true,                
        },
        no_of_windows: {
            required: true,
        },
        window_width: {
             required: function(element){
                    if($("#unit").val()=='meter_or_cm'){
                        return true;
                    } else {
                        return false;
                    }
                },
            digits : true,                
        },
        window_hight: {
             required: function(element){
                    if($("#unit").val()=='meter_or_cm'){
                        return true;
                    } else {
                        return false;
                    }
                },
            digits : true,                
        },
        wod_window_width: {
            required: function(element){
                    if($("#unit").val()=='feet_or_inch'){
                        return true;
                    } else {
                        return false;
                    }
                },
            digits : true,                
        },
        wod_window_hight: {
             required: function(element){
                    if($("#unit").val()=='feet_or_inch'){
                        return true;
                    } else {
                        return false;
                    }
                },
            digits : true,                
        },
        carpet_area_vs_dr_wid_ar : {
            check_area: true,
            digits : true,            
        }
    },
    messages: {
        carpet_area: {
            required: "Carpet area cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        no_of_doors: {
            required: "No. of Doors cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        door_width: {
            required: "Door width cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        door_hight: {
            required: "Door height cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        wod_door_width: {
            required: "Door width cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        wod_door_hight: {
            required: "Door height cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        no_of_windows: {
            required: "No. of Windows cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        window_width: {
            required: "Window width cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        window_hight: {
            required: "Window height cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        wod_window_width: {
            required: "Window width cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        wod_window_hight: {
            required: "Window height cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        carpet_area_vs_dr_wid_ar: {
            check_area : "Door and Windows area not more than carpet area *",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        }
    },

    errorPlacement: function(error, element) {
        if (element.attr("name") == "carpet_area_vs_dr_wid_ar") {
            error.appendTo(".calculator_error_msg");
        } else {
            error.insertAfter(element);
        }
    },

    errorPlacement : function (error, element) {
        if(element.attr("name") == "carpet_area") {
            error.appendTo("#carpet_areaError");
        } else if(element.attr("name") == "no_of_doors") {
            error.appendTo("#no_of_doorsError");
        } else if(element.attr("name") == "door_width") {
            error.appendTo("#door_widthError");
        } else if(element.attr("name") == "door_hight") {
            error.appendTo("#door_hightError");
        } else if(element.attr("name") == "wod_door_width") {
            error.appendTo("#wod_door_widthError");
        } else if(element.attr("name") == "wod_door_hight") {
            error.appendTo("#wod_door_hightError");
        } else if(element.attr("name") == "no_of_windows") {
            error.appendTo("#no_of_windowsError");
        } else if(element.attr("name") == "window_width") {
            error.appendTo("#window_widthError");
        } else if(element.attr("name") == "window_hight") {
            error.appendTo("#window_hightError");
        } else if(element.attr("name") == "wod_window_width") {
            error.appendTo("#wod_window_widthError");
        } else if(element.attr("name") == "wod_window_hight") {
            error.appendTo("#wod_window_hightError");
        } else if(element.attr("name") == "carpet_area_vs_dr_wid_ar") {
            error.appendTo("#carpet_areaError");
        } 
    },
    
    submitHandler: function (form) {
        var content = $(form).serialize();
        $.ajax({
            type: "POST",
            url: base_path + "calculator/api/wallFinishCalculation",
            dataType: "html",
            data: content,
            beforeSend: function () {
                $(".page_loading").show();
            },
            success: function (data) {
                $(".page_loading").hide();
                var json = $.parseJSON(data);
                $(".calulation_result").html(json['layout']);
                $("#result_column").removeClass("display_none");
                wallFinishCalculationChart(json["paint"],json["primer"],json["sand"]);
            }
        });
        return false;
    } 
        
    });


    var chart_obj;

    function wallFinishCalculationChart(paint,primer,putty) {
        var options = {
            // legend: false,
            responsive: true
        };

        $('#tableData').html( '<tr><th>' + 'Paint' + '</th><td>' + paint + ' Liters</td></tr><tr><th>' + 'Primer' + '</th><td>' + primer + ' Liters</td></tr><tr><th>' + ' Putty' + '</th><td>' + putty + ' Kgs</td></tr>' );
        
        let init = function() {
            chart_obj = new Chart($("#calcwall"), {
                type: 'doughnut',
                tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                data: {
                    labels: [
                        `Paint  - ${paint}`+` Liters `,
                        `Primer - ${primer}`+` Liters`,
                        `Putty  - ${putty}`+` Kgs`,
                    ],
                    datasets: [{
                        data: [paint , primer, putty],
                        backgroundColor: [
                            "#3498DB",
                            "#ffc107",
                            "#9B59B6"
                        ],
                        hoverBackgroundColor: [
                             "#49A9EA",
                             "#f1b912",
                             "#B370CF"
                        ]
                    }],
                },
                options: { 
                    responsive: true,
                    plugins: {
                        legend: {
                           // position: 'top',
                           // fullWidth: true,
                           display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ({label}) => label
                            }
                        } 
                    }
                }
            });
        }

        chart_obj && chart_obj.destroy();

        init();

    }
</script>
