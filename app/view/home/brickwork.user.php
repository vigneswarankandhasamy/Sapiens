<?php require_once 'includes/top.php'; ?>
<!-- <div class="calc-banner otherpage-banner m-0">
    <img src="<?php echo $data['page_banner']!="" ? SRCIMG.$data['page_banner']['file_name'] : IMGPATH."calculator-banner.jpg" ?>" alt="image" class="common-banner">
    <div class="other-banner-title">
        <p>Calculator</p> -->
        <!-- <button type="button" class="btn btn-sm banner-btn rounded-pill"><a href="<?php echo BASEPATH ?>calculator/plastering">View more</a></button> -->
   <!--  </div>     
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
                        <li><a href="<?php echo BASEPATH ?>calculator/brickwork" class="breadcrumb_active">Brick Work</a></li>
                        <li><a href="<?php echo BASEPATH ?>calculator/plastering">Plastering</a></li>
                        <li><a href="<?php echo BASEPATH ?>calculator/concrete">Concrete</a></li>
                        <li><a href="<?php echo BASEPATH ?>calculator/painting">Painting</a></li>
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
                    <h4 class="fs-5">Brick Work Calculation</h4>
                    <div class="row">
                        <div class="col-md-7 col-xs-12 col-sm-12">
                                <form id="brickWorkClaculation" method="POST" action="#">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="inputEmail3" class="col-form-label">Unit</label>
                                            <select class="form-select" name="unit" id="unit">
                                            <option value="meter_or_cm">Meter/CM</option>
                                            <option value="feet_or_inch">Feet/Inch</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="inputEmail3" class="col-form-label">Ratio</label>
                                            <select class="form-select" name="ratio" id="inlineFormSelectPref">
                                                <option value="1,3">C.M (1:3)</option>
                                                <option value="1,4">C.M (1:4)</option>
                                                <option value="1,5">C.M (1:5)</option>
                                                <option value="1,6">C.M (1:6)</option>
                                                <option value="1,7">C.M (1:7)</option>
                                                <option value="1,8">C.M (1:8)</option>
                                                <option value="1,9">C.M (1:9)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="inputEmail3" class="col-form-label">Length</label>
                                        <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-area"> 
                                                            <input type="number" class="form-control" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==6) return false;" name="lengthMeterOrFeet" min="0" id="lengthMeterOrFeet" placeholder="meter">
                                                        </div>
                                                        <div class="input-group-icon lengthMeterOrFeet">meter</div>
                                                    </div>
                                                    <div id="lengthMeterOrFeetError">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-area"> 
                                                            <input type="number" class="form-control" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;" name="lengthCmOrInch" min="0" id="lengthCmOrInch" placeholder="cm">
                                                        </div>
                                                        <div class="input-group-icon lengthCmOrInch">cm</div>
                                                    </div>
                                                    <div id="lengthCmOrInchError">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for="inputEmail3" class="col-form-label">Height / Depth</label>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-area"> 
                                                            <input type="number" class="form-control" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==6) return false;" name="widthMeterOrFeet" min="0" id="widthMeterOrFeet" placeholder="meter">
                                                        </div>
                                                        <div class="input-group-icon widthMeterOrFeet">meter</div>
                                                    </div>
                                                    <div id="widthMeterOrFeetError">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-area"> 
                                                            <input type="number" class="form-control" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;" name="widthCmOrInch" min="0" id="widthCmOrInch" placeholder="cm">
                                                        </div>
                                                        <div class="input-group-icon widthCmOrInch">cm</div>
                                                    </div>
                                                    <div id="widthCmOrInchError">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <label for="inputEmail3" class="col-form-label">Wall Thickness</label>
                                            <select class="form-select" name="wallThickness" id="wallThickness">
                                            <option value="10" class="10_cm_or_inch_Wall">10 CM Wall</option>
                                            <option value="23" class="23_cm_or_inch_Wall">23 CM Wall</option>
                                            <option value="others_partition">Others Partition</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 others_partition display_none">
                                                <label for="inputEmail3" class="col-form-label">Other Partition </label>
                                                <div class="input-group">
                                                    <div class="input-group-area"> 
                                                        <input type="number" class="form-control" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;" name="otherPartition" min="0" id="otherPartition" placeholder="cm">
                                                    </div>
                                                    <div class="input-group-icon otherPartition">cm</div>
                                                </div>
                                                <div id="otherPartitionError">
                                                </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputEmail3" class="col-form-label">Size of Brick</label>
                                        <div class="col-4">
                                            <label for="inputEmail3" class="col-form-label">Length</label>
                                             <div class="input-group">
                                                <div class="input-group-area"> 
                                                    <input type="number" class="form-control" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;" name="lengthOfBrick" min="0" id="lengthOfBrick" placeholder="cm">
                                                </div>
                                                <div class="input-group-icon lengthOfBrick">cm</div>
                                            </div>
                                            <div id="lengthOfBrickError">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="inputEmail3" class="col-form-label">Width</label>
                                             <div class="input-group">
                                                <div class="input-group-area"> 
                                                    <input type="number" class="form-control" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;" name="widthOfBrick" min="0" id="widthOfBrick" placeholder="cm">
                                                </div>
                                                <div class="input-group-icon widthOfBrick">cm</div>
                                            </div>
                                            <div id="widthOfBrickError">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="inputEmail3" class="col-form-label">Height</label>
                                             <div class="input-group">
                                                <div class="input-group-area"> 
                                                    <input type="number" class="form-control" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;" name="heightOfBrick" min="0" id="heightOfBrick" placeholder="cm">
                                                </div>
                                                <div class="input-group-icon heightOfBrick">cm</div>
                                            </div>
                                            <div id="heightOfBrickError">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn button">Calculate</button>
                                </form>
                        </div>
                        <div class="col-md-5 col-xs-12 col-sm-12  display_none" id="result_column">
                            <div class="card border-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h4 class="card-title">Result</h4>
                                        </div>
                                        <div class="col-lg-10 calulation_result_tbl clearfix clear">
                                        <table id='tableData' class='tableData'></table><br>
                                            <div class="chart" style="width: 65%">
                                                <canvas class="d-block" id="calcbrickwork" ></canvas>
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
        $('#lengthMeterOrFeet').attr('placeholder', 'feet');
        $('#lengthCmOrInch').attr('placeholder', 'inch');
        $('#widthMeterOrFeet').attr('placeholder', 'feet');
        $('#widthCmOrInch').attr('placeholder', 'inch');
        $('#otherPartition').attr('placeholder', 'inch');
        $('#lengthOfBrick').attr('placeholder', 'inch');
        $('#widthOfBrick').attr('placeholder', 'inch');
        $('#heightOfBrick').attr('placeholder', 'inch');

        $('.lengthMeterOrFeet').html('feet');
        $('.lengthCmOrInch').html('inch');
        $('.widthMeterOrFeet').html('feet');
        $('.widthCmOrInch').html('inch');
        $('.otherPartition').html('inch');
        $('.lengthOfBrick').html('inch');
        $('.widthOfBrick').html('inch');
        $('.heightOfBrick').html('inch');

        $('.10_cm_or_inch_Wall').html('4 inch Wall');
        $('.23_cm_or_inch_Wall').html('9 inch Wall');
        // $('.10_cm_or_inch_Wall').val(4);
        // $('.23_cm_or_inch_Wall').val(9);

       } else {
        $('#lengthMeterOrFeet').attr('placeholder', 'meter');
        $('#lengthCmOrInch').attr('placeholder', 'cm');
        $('#widthMeterOrFeet').attr('placeholder', 'meter');
        $('#widthCmOrInch').attr('placeholder', 'cm');
        $('#otherPartition').attr('placeholder', 'cm');
        $('#lengthOfBrick').attr('placeholder', 'cm');
        $('#widthOfBrick').attr('placeholder', 'cm');
        $('#heightOfBrick').attr('placeholder', 'cm');

        $('.lengthMeterOrFeet').html('meter');
        $('.lengthCmOrInch').html('cm');
        $('.widthMeterOrFeet').html('meter');
        $('.widthCmOrInch').html('cm');
        $('.otherPartition').html('cm');
        $('.lengthOfBrick').html('cm');
        $('.widthOfBrick').html('cm');
        $('.heightOfBrick').html('cm');

        $('.10_cm_or_inch_Wall').html('10 CM Wall');
        $('.23_cm_or_inch_Wall').html('23 CM Wall');
        // $('.10_cm_or_inch_Wall').val(10);
        // $('.23_cm_or_inch_Wall').val(23);

       }

    });

    // Other Partition input Hide/Show

    $("#wallThickness").change(function() { 
       var val =  $(this).val();
       if(val=="others_partition") {
        $('.others_partition').removeClass('display_none');
       } else {
        $('.others_partition').addClass('display_none');
        $("#otherPartition").val("");
       }
    });

    // Form submiting

    $.validator.addMethod("lengthCmCheck", function (value, elem) {
        if($("#unit").val()=="meter_or_cm" ){
            if($("#lengthCmOrInch").val() <= 99 ) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    });

    $.validator.addMethod("lengthInchCheck", function (value, elem) {
        if($("#unit").val()=="feet_or_inch"){
            if($("#lengthCmOrInch").val() <= 11 ) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    });

    $.validator.addMethod("widthCmCheck", function (value, elem) {
        if($("#unit").val()=="meter_or_cm" ){
            if($("#widthCmOrInch").val() <= 99 ) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    });

    $.validator.addMethod("widthInchCheck", function (value, elem) {
        if($("#unit").val()=="feet_or_inch" ){
            if($("#widthCmOrInch").val() <= 11 ) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    });
 


   $("#brickWorkClaculation").validate({
     rules: {
        lengthMeterOrFeet: {
            required: true,
            digits: true
        },
        widthMeterOrFeet: {
            required: true,
            digits: true
        },
        lengthCmOrInch: {
            lengthCmCheck: true,
            lengthInchCheck: true,
            digits: true
        },
        widthCmOrInch: {
            widthCmCheck: true,
            widthInchCheck: true,
            digits: true
        },
        otherPartition: {
            required: function (element) {
                if($("#wallThickness").val()=="others_partition") {
                    return true;
                } else {
                    return false;
                }
            },
            max: 99,
        },
        lengthOfBrick: {
            required: true
        },
        widthOfBrick: {
            required: true
        },
        heightOfBrick: {
            required: true
        }
        
    },
    messages: {
        lengthMeterOrFeet: {
            required: "Length cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        widthMeterOrFeet: {
            required: "Width  cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        lengthCmOrInch: {
            lengthCmCheck : "Length between 0 to 99 cm",
            lengthInchCheck : "Length between 0 to 11 inch",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
         widthCmOrInch: {
            widthCmCheck : "Width between 0 to 99 cm",
            widthInchCheck : "Width between 0 to 11 inch",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        otherPartition: {
            required : "Partiation cannot be empty",
            max : "Partiation cannot be more than 99",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        lengthOfBrick: {
            required : "Length of brick cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        widthOfBrick: {
            required : "Width of brick cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        },
        heightOfBrick: {
            required : "Height of brick cannot be empty",
            digits: "Enter only numeric values decimal values are not allowed for this calculation",
        }
      
    },

    errorPlacement : function (error, element) {
        if(element.attr("name") == "lengthMeterOrFeet") {
            error.appendTo("#lengthMeterOrFeetError");
        } else if(element.attr("name") == "lengthCmOrInch") {
            error.appendTo("#lengthCmOrInchError");
        } else if(element.attr("name") == "widthMeterOrFeet") {
            error.appendTo("#widthMeterOrFeetError");
        } else if(element.attr("name") == "widthCmOrInch") {
            error.appendTo("#widthCmOrInchError");
        } else if(element.attr("name") == "otherPartition") {
            error.appendTo("#otherPartitionError");
        } else if(element.attr("name") == "lengthOfBrick") {
            error.appendTo("#lengthOfBrickError");
        } else if(element.attr("name") == "widthOfBrick") {
            error.appendTo("#widthOfBrickError");
        } else if(element.attr("name") == "heightOfBrick") {
            error.appendTo("#heightOfBrickError");
        } 
    },
    
    submitHandler: function (form) {
        var content = $(form).serialize();
        $.ajax({
            type: "POST",
            url: base_path + "calculator/api/brickWorkClaculation",
            dataType: "html",
            data: content,
            beforeSend: function () {
                $(".page_loading").show();
            },
            success: function (data) {
                $(".page_loading").hide();
                console.log(data);
                var json = $.parseJSON(data);
                $(".calulation_result").html(json['layout']);
                $("#result_column").removeClass("display_none");
                brickworkCalculationChart(json['bricks'],json['cement'],json['sand'],json['sand_cft'].toFixed(2),json['cement_graph'],json['sand_graph']);
            }
        });
        return false;
    } 
        
    });

    var chart_obj;

    function brickworkCalculationChart(brick,cement,sand,sand_cft,cement_graph,sand_graph) {
       
        var options = {
            // legend: false,
            responsive: true
        };
        
        $('#tableData').html( '<tr><th>' + 'Bricks' + '</th><td>' + brick + ' Bricks' + '</td></tr><tr><th>' + 'Cement' + '</th><td>' + cement +' Bags' + '</td></tr><tr><th>' + 'Sand' + '</th><td>' + sand + ' Tons'+ ' (' + sand_cft + 'CFT)</td></tr>' );
        let init = function() {
            chart_obj = new Chart($("#calcbrickwork"), {
                type: 'doughnut',
                tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                data: {
                    labels: [
                        `Bricks - ${brick} Bricks`,
                        `Cement - ${cement} Bags`,
                        `Sand - ${sand} Tons  (${sand_cft}`+` CFT)`
                    ],  
                    datasets: [{
                        data: [brick,cement_graph , sand_graph],
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
