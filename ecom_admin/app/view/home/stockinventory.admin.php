<?php require_once 'includes/top.php'; ?> 

    <div class="nk-content nk-content-fluid">
        <div class="container-fluid wide-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <?php
                                $filter_token = $data['page_title'];
                                $page_title = (
                                        ($data['page_title']=="all")? "All" : 
                                        (($data['page_title']=="instock")? "Instock" :  
                                        (($data['page_title']=="lowstock")? "Low Stock" :  
                                        (($data['page_title']=="outofstock")? "Out Of Stock" : 
                                        (($data['page_title']=="")? "All" : "" ) )  ) ) );
                            ?>
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title"> <?php echo (($page_title=="All")? "Manage Inventory" : $page_title ) ?></h3>
                                <div class="nk-block-des">
                                    <nav>
                                        <ul class="breadcrumb breadcrumb-arrow">
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a></li>
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>productsettings">Products</a></li>
                                             <?php if($data['page_title']!="") { ?>
                                                <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>inventorysettings">Stock Inventory</a></li>
                                                <li class="breadcrumb-item active"><?php echo $page_title ?></li>
                                            <?php } else { ?>
                                                <li class="breadcrumb-item active">Stock Inventory</li>
                                            <?php } ?>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                    <form id="stockInventoryFilter">
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="sellect_vendor">
                                                <div class="form-group sellect_vendor_drp" >
                                                   <div class="form-control-wrap">
                                                        <select class="form-select vendorIdSelect" name="vendor_id" id="vendorId" data-search="on">
                                                            <option value='0'>Select Vendor </option>
                                                            <?php echo $data['get_vendors'] ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="drodown">
                                                    <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-toggle="dropdown"><em class="icon ni ni-filter-alt"></em><span class="button_name"><span class="d-none d-md-inline button_name_ltr"><?php echo $page_title ?></span></span></a>
                                                    <input type="hidden" id="toDay" value="<?php echo date("d-M-y") ?>">
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="link-list-opt no-bdr">
                                                            <input type="hidden" name="stoct_list" id='stockList' value="<?php echo $filter_token ?>">
                                                            <li><a class="filter_table_list" data-option="all" ><span>All</span></a></li>
                                                            <li><a class="filter_table_list" data-option="instock" ><span>Instock</span></a></li>
                                                            <li><a class="filter_table_list" data-option="lowstock" ><span>Low Stock</span></a></li>
                                                            <li><a class="filter_table_list" data-option="outofstock" ><span>Out Of Stock</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="nk-block-tools-opt"><button class="btn btn-primary"><em class="icon ni ni-filter"></em><span>Filter</span></button></li>
                                        </ul>
                                        <ul>
                                            <div class="row date_error_style" >
                                                <div class="col-md-3">
                                                    <div id="vendorSelectEr"></div>
                                                </div>
                                            </div>
                                        </ul>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <table id="table_detail" class="datatable-init nk-tb-list nk-tb-ulist is-compact dataTable no-footer" data-auto-responsive="false">
                                    <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Image</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Product </span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Category </span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Actual Price</span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Selling Price</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Inventory </span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Stock Status </span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $data['InventoryList'] ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    $("#vendorId").on("change",function() {
        var value = $("#vendorId").val() > 0;
        if(value) {
            $("#vendorSelectEr").addClass("display_none");
        } else {
            $("#vendorSelectEr").removeClass("display_none");
        }
    });
        
    $(".filter_table_list").click(function() {
        toastr.clear();
        var value = $(this).data("option");
        $("#stockList").val(value);
       
        if(value=='all') {
            $('.button_name').html("All");

        } else if(value=='instock') {
            $('.button_name').html("Instock");

        } else if(value=='lowstock') {
            $('.button_name').html("Low Stock");
        }
        else if(value=='outofstock') {
            $('.button_name').html("Out Of Stock");
        }

        $('.dropdown-menu-right').removeClass('show');

        return false;
    });

    $.validator.addMethod("check_vendor", function (value, elem) {
        return $("#vendorId").val() > 0;
    });

    $("#stockInventoryFilter").validate({
        rules: {
            vendor_id: {
                check_vendor: true,
            }
        },
        messages: {
            vendor_id: {
                check_vendor: "Select vendor",
            }
        },
        errorPlacement: function(error, element) {
                if (element.attr("name") == "vendor_id") {
                    error.appendTo("#vendorSelectEr");
                } else {
                    error.insertAfter(element);
                }
                 
            },
        submitHandler: function(form) {
                window.location = core_path + "products/stockinventor/"+$("#vendorId").val()+"/"+$("#stockList").val();
            return false;
        }
    });

</script>

