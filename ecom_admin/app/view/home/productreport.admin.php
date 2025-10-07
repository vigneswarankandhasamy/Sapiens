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
                                <div class="nk-block-des">
                                    <nav>
                                        <ul class="breadcrumb breadcrumb-arrow">
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a>
                                            </li>
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>reports">Reports</a>
                                            </li>
                                            <li class="breadcrumb-item active">
                                                <?php echo $data[ 'page_title'] ?>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                    <form id="orderReportFilter">
                                        <div class="toggle-expand-content" data-content="pageMenu">
                                            <ul class="nk-block-tools g-3">
                                                <li>
                                                    <input type="text" name="valid_from" id="valid_from" autocomplete="off" class="form-control date-picker" data-date-format="dd-mm-yyyy" value='<?php echo $data['from_date']; ?>' placeholder="From Date">
                                                </li>
                                                <li>
                                                    <input type="text" name="valid_to" id="valid_to" class="form-control date-picker" data-date-format="dd-mm-yyyy" value='<?php echo $data['to_date']; ?>' autocomplete="off" placeholder="To Date">
                                                </li>
                                                <li class="sellect_vendor">
                                                    <div class="form-group sellect_vendor_drp" >
                                                       <div class="form-control-wrap">
                                                            <select class="form-select" name="vendor_id" id="vendorId" data-vendor_name="<?php echo (($data['vendor_detail']!=0)? $data['vendor_detail']['company'] : 0 ) ?>" data-search="on">
                                                                <option value='0'>Select Vendor </option>
                                                                <?php echo $data['get_vendors'] ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="nk-block-tools-opt"><button type="submit" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></button>
                                                </li>
                                            </ul>
                                            <ul>
                                            <div class="row date_error_style" >
                                                <div class="col-md-3">
                                                    <div id="fromdateError"></div>
                                                </div>
                                                <div class="col-md-3 todate_error">
                                                    <div id="todateError"></div>
                                                </div>
                                                <div class="col-md-3 vendor_select_drop">
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
                        <div class="row gy-gs">
                            <div class="col-md-6 col-lg-4">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <h6 class="title">Instock 
                                                        </h6>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount"> <?php echo (($data['card_data'])? $data['card_data']['instock_count'] : 0) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- .card -->
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <h6 class="title">Low Stock
                                                        
                                                </h6>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount"> <?php echo (($data['card_data'])? $data['card_data']['low_stock_count'] : 0)?> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- .card -->
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class=" is-s1 card card-bordered">
                                    <div class="card-inner">
                                        <div class="nk-iv-wg2">
                                            <div class="nk-iv-wg2-title">
                                                <h6 class="title">Out of Stock</h6>
                                            </div>
                                            <div class="nk-iv-wg2-text">
                                                <div class="nk-iv-wg2-amount"> <?php echo (($data['card_data'])? $data['card_data']['out_of_stock_count'] : 0)?> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="row g-gs">
                            <div class="col-md-6 col-lg-12">
                                <div class="card card-bordered card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title"><span class="mr-2">Top Products</span>
                                                <?php if($data['vendor_detail']!=0) { ?>
                                                <?php if($data['from_date']!="") { ?>
                                                    <span>( <?php echo $data['vendor_detail']['company']; ?> [<?php echo $data['from_date'];?> to <?php echo $data['to_date'];?>] )</span >
                                                <?php  } ?>
                                                <?php  } ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-block">
                                        <div class="card">
                                            <div class="card-inner">
                                                <div class="card card-bordered card-preview">
                                                    <table class="table table-tranx is-compact">
                                                        <thead>
                                                            <tr class="tb-tnx-head">
                                                                <th class="tb-tnx-id"><span class="">S.no</span></th>
                                                                <th class="tb-tnx-info">
                                                                    <span class="tb-tnx-desc d-none d-sm-inline-block">
                                                                        <span>Product Name</span>
                                                                    </span>
                                                                </th>
                                                                <th class="tb-tnx-info">
                                                                    <span >
                                                                        <span>Category </span>
                                                                    </span>
                                                                </th>
                                                                
                                                        </thead>
                                                        <tbody>
                                                                <?php echo $data['top_products']; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-12">
                                <div class="card card-bordered card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title"><span class="mr-2">Instock list</span></h6>
                                            </div>
                                            <div class="card-tools">
                                                <ul class="nk-block-tools g-3">
                                                    <?php if($data['InventoryList']!="") { ?>
                                                    <li class="nk-block-tools-opt">
                                                        <!-- <button  class="btn btn-primary export_cancel_order_list"><em class="icon ni ni-reports"></em><span>Export</span>
                                                            </button> -->
                                                    </li>
                                                    <?php } ?>
                                                </ul>
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
                                                        <?php echo $data['InventoryList']['instock']; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-12">
                                <div class="card card-bordered card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title"><span class="mr-2">Low Stock list</span></h6>
                                            </div>
                                            <div class="card-tools">
                                                <ul class="nk-block-tools g-3">
                                                    <?php if($data['InventoryList']!="") { ?>
                                                    <li class="nk-block-tools-opt">
                                                        <!-- <button  class="btn btn-primary export_cancel_order_list"><em class="icon ni ni-reports"></em><span>Export</span>
                                                            </button> -->
                                                    </li>
                                                    <?php } ?>
                                                </ul>
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
                                                        <?php echo $data['InventoryList']['low_stock']; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-12">
                                <div class="card card-bordered card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title"><span class="mr-2">Our Of Stock list</span></h6>
                                            </div>
                                            <div class="card-tools">
                                                <ul class="nk-block-tools g-3">
                                                    <?php if($data['InventoryList']!="") { ?>
                                                    <li class="nk-block-tools-opt">
                                                        <!-- <button  class="btn btn-primary export_cancel_order_list"><em class="icon ni ni-reports"></em><span>Export</span>
                                                            </button> -->
                                                    </li>
                                                    <?php } ?>
                                                </ul>
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
                                                        <?php echo $data['InventoryList']['out_of_stock']; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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

    <script type="text/javascript">

        $.validator.addMethod("from_date_er", function (value, elem) {
            var from = new Date( $("#valid_from").val().replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"))
            var to = new Date( $("#valid_to").val().replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"))
           return (from <= to);
        });
         $.validator.addMethod("check_vendor", function (value, elem) {
            return $("#vendorId").val() > 0;
        });

        $("#orderReportFilter").validate({
        rules: {
            valid_from : {
                required: true,
                from_date_er: function(element){
                    if($("#valid_from").val()!=""){
                        return true;
                    } else {
                        return false;
                    }
                },
            },
            valid_to : {
                required:true,
            },
            vendor_id: {
                check_vendor: true,
            }
        },
        messages: {
            valid_from: {
                required: "From date Can't be empty",
                from_date_er: "From date must be less than to date",
            },
            valid_to: {
                required: "To date Can't be empty",
            },
            vendor_id: {
                check_vendor: "Select vendor",
            }
        },
        errorPlacement: function(error, element) {
                if (element.attr("name") == "valid_from") {
                    error.appendTo("#fromdateError");
                } else if (element.attr("name") == "valid_to") {
                    error.appendTo("#todateError");
                } else if (element.attr("name") == "vendor_id") {
                    error.appendTo("#vendorSelectEr");
                } else {
                    error.insertAfter(element);
                }
                 
            },
        submitHandler: function(form) {
            if($("#valid_from").val()!="") {
                window.location = core_path + "reports/product/"+$("#vendorId").val()+"/"+$("#valid_from").val()+"/"+$("#valid_to").val();
            } else {
                window.location = core_path + "reports/product/"+$("#vendorId").val();
            }
            return false;
        }
    });


    </script> 




