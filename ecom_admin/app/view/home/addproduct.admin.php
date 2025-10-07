<?php require_once 'includes/top.php'; ?>


<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-fluid wide-lg">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                    <form method="post" class="form-validate is-alter" id="addProduct" enctype="multipart/form-data">
                        <?php echo $data['csrf_add_product'] ?>
                        <input type="hidden" value="products/details/" id="page_token">
                        <div class="form_submit_bar">
                            <div class="container wide-lg">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><a href="javscript:void();" class="cancelSubmission" data-url="<?php echo COREPATH ?>products"><i class="icon ni ni-arrow-left"></i></a>  </h2>
                                        <h3><?php echo $data['page_title'] ?></h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="submit_button_wrap">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input"  value="1"  id="draft_button" name="save_as_draft">
                                                <label class="custom-control-label" for="draft_button">Save as draft</label>
                                            </div>
                                            <button type="button" class="btn btn-light cancelSubmission" data-url="<?php echo COREPATH ?>products"> Cancel</button>
                                            <button class="btn btn-success" id="submit_button" type="submit"><em class="icon ni ni-check-thick"></em> Save & Publish</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <pre class="form-error"></pre>
                       
                        <!-- ROW Starts -->
                        <div class="row form-group">

                            <div class="col-md-9">

                                <!-- General Info -->

                                <div class="card card-shadow">
                                    <div class="card-inner">
                                        <h5 class="card-title">General Info</h5>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label class="form-label"> Product Name <em>*</em>
                                                </label>
                                                <input type="text" name="title_name" id="title_name" class="form-control" placeholder="Enter Product Name" >
                                            </div>
                                            <div class="form-group col-md-12">
                                                    <label class="form-label">Short Description <em>*</em></label>
                                                <textarea class="form-control" rows="2"  name="short_description"></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="form-label" >Description 
                                                </label>
                                                <textarea class="summernote-editor" name="description" id="description"></textarea> 
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Variants -->

                                <div class="card card-shadow no_display" id="variant_parent">
                                    <div class="card-inner">
                                        <h5 class="card-title">Variants  </h5>

                                         <h2 class="text-right card_actions" id="variant_actions"><button type="button" id="generateVariants" class="btn btn-secondary btn-sm"> Generate Variants</button> <button type="button" class="btn btn-light btn-sm add_variant_option" id="add_variant_option"> Add Option</button></h2>

                                          <h2 class="text-right card_actions no_display" id="variant_option_edit"><button type="button" id="editVariants" class="btn btn-warning btn-sm"> Edit Variants</button> </h2>

                                        <div class="variant_parent">
                                            <div id="variant_options_wrap">
                                                <div class="row" id="option_row_1">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Option <em>*</em></label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-control form-control-lg variant_option_select option_title_1"  id="option_title_1" data-option="1" data-search="on" name="options[1][title]" required>
                                                                    <option value="Size">Size</option>
                                                                    <option value="Color">Color</option>
                                                                    <option value="Material">Material</option>
                                                                    <option value="Style">Style</option>
                                                                </select>
                                                                <p class="help_text">* Type to add option</p>
                                                                <p class="text-danger" id="option_error_1"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label class="form-label">Enter Variants</label>
                                                            <select multiple="multiple" data-role="tagsinput" data-option="1"  name="options[1][value][]" class="form-control tags_input option_variant_1">
                                                            </select>
                                                            <p class="text-danger" id="variant_error_1"></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div id="variant_options_preview_wrap" class="no_display">
                                                <table class="table table_middle bg-white table-bordered">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th scope="col" width="20%"> Options</th>
                                                            <th scope="col" width="55%">Variants </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="variant_option_preview">
                                                        
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="variant_child form-group no_display" id="variant_child"> 
                                                <h6>Variant List</h6>
                                                <table class="table table_middle bg-white table-bordered">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th scope="col" width="15%">Variant</th>
                                                            <th scope="col" width="15%">Selling Price 
                                                                <span class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cost at which the product is sold"></span>
                                                            </th>
                                                            <th scope="col" width="15%">Actual price 
                                                                <span class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="top" title="" data-original-title="To show a reduced price for the item, enter the actual cost of the product and put a lower cost at the Selling Price. "></span>
                                                            </th>
                                                            <th scope="col" width="10%">Stock</th>
                                                            <th scope="col" width="15%">SKU</th>
                                                            <th scope="col" width="5%"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="variant_list_body">
                                                        
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                
                                <!-- Category Mapping -->

                                <div class="card card-shadow">
                                    <div class="card-inner">
                                        <h5 class="card-title">Product Organization</h5>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Mapping Type
                                                    <!-- <en>*</en> -->
                                                </label>
                                                <br>

                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="submapping" name="category_type" value="sub" class="custom-control-input category_type" checked>
                                                    <label class="custom-control-label" for="submapping"> Sub Category</label>
                                                </div>
                                                 &nbsp;  &nbsp;

                                                <?php if(1 != 1) { ?> 
                                                <!--  <div class="custom-control custom-radio">
                                                    <input type="radio" id="mainmapping" name="category_type" value="main" class="custom-control-input category_type">
                                                    <label class="custom-control-label" for="mainmapping" > Main Category</label>
                                                </div> -->
                                                <?php  } ?>
                                                
                                            </div>
                                            <div class="form-group col-md-6" id="sub_category_wrap">
                                                <label class="form-label">Select Sub Category <em>*</em></label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" id="sub_category_id" name="sub_category_id" data-search="on" required>
                                                       <?php echo $data['sub_category']; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <?php if(1 != 1) { ?>
                                            <!--<div class="form-group col-md-3" id="main_category_wrap display_none" >
                                                <label class="form-label">Select Main Category <em>*</em></label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" id="main_category_id" name="main_category_id" data-search="on" required> -->
                                                        <!-- <option value=' '>Select Main Category</option> -->
                                                       <!-- <?php echo $data['main_category']; ?>
                                                    </select>
                                                </div>
                                            </div> -->
                                            <?php  } ?>

                                            <hr>
                                            <?php if(1 != 1) { ?>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Additional Main Category <span class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="top" title="" data-original-title="Select the list of main categories on which this product will be show. This will not be taken for reports only for listing the product on the multiple main categories."></span> 
                                                </label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" multiple="multiple"  name="add_main_category[]"  data-placeholder="Select Main Category" data-search="on">
                                                        <?php echo $data['main_category']; ?>
                                                    </select>
                                                    <!-- <p class="help_text">* Select multiple main categories if needed</p> -->
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label" >Additional Sub Category 
                                                    <span class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="top" title="" data-original-title="Select the list of sub categories on which this product will be show. This will not be taken for reports only for listing the product on the multiple sub categories."></span>
                                                </label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" multiple="multiple" name="add_sub_category[]" data-placeholder="Select Sub Category" data-search="on">
                                                        <?php echo $data['sub_category']; ?>
                                                    </select>
                                                    <!-- <p class="help_text">* Select multiple sub categories if needed</p> -->
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <h5 class="card-title">Product Unit</h5>
                                        <div class="row">
                                            <div class="form-group col-md-6" id="sub_category_wrap">
                                                <label class="form-label">Select Product Unit <em>*</em></label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" id="product_unit" name="product_unit" data-search="on" required>
                                                       <?php echo $data['product_units']; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                 <div class="card card-shadow">
                                    <div class="card-inner">
                                        <h5 class="card-title">Filter Organization</h5>
                                        <div class="row filter_masters_colomn">
                                            <?php echo $data['filter_masters']; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Images -->
                                
                                <div class="card card-shadow">
                                    <div class="card-inner">
                                        <h5 class="card-title">Product Images  </h5>
                                        <div class="input-images"></div>
                                        <p class="help_text"><?php echo PRODUCT_IMAGE_HELP_TEST ?></p>
                                    </div>
                                </div>

                                <!-- Attributes -->

                                <div class="card card-shadow">
                                    <div class="card-inner">
                                        <h5 class="card-title"> Attributes</h5>

                                        <h2 class="text-right card_actions"> <button type="button" onclick="addAttribute();" title="Add" class="btn btn-secondary btn-sm"> Add Attributes</button></h2>

                                        
                                        <div class="form-group ">
                                            <table class="table table_middle bg-white table-bordered" id="attribute">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th scope="col" width="35%">Attribute</th>
                                                        <th scope="col" width="55%">Value </th>
                                                        <th scope="col" width="10%">Action </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Related Products -->

                                <div class="card card-shadow">
                                    <div class="card-inner">
                                        <h5 class="card-title">Related and Bought together Products  </h5>
                                        <div class="form-group autocomplete_item">
                                            <input type="text" id="search_related_items" class="form-control" data-validation="" placeholder="Search products and add products">
                                        </div>
                                        <div class="form-group ">
                                            <table class="table table_middle bg-white table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th scope="col" width="60%"> Product</th>
                                                        <th scope="col" width="30%">Type </th>
                                                        <th scope="col" width="10%">Action </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="related_items_list">
                                                    
                                                </tbody>
                                            </table>
                                            <div class="related_product_list">
                                                <div id="location-group" class="related_product_wrap" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO -->    

                                <div class="card card-shadow">
                                    <div class="card-inner">
                                        <h5 class="card-title">SEO Listing Preview </h5> 
                                        <button type="button" id="seo_toggle" class="btn seo_toggle"><em class="icon ni ni-edit-alt"></em> &nbsp; Edit SEO contents</button>
                                        <div id="seo_preview" class="seo_preview">
                                            <h5 id="seo_title"></h5>
                                            <p id="seo_link"></p>
                                            <h6 id="seo_description"></h6>
                                        </div>
                                        <div id="seo_inputs" class="seo_inputs">
                                            <div class="form-group">
                                                <label class="form-label" >Page URL <em>*</em>
                                                </label>
                                                <input type="text" name="page_url" id="page_url" required="" class="form-control" data-validation="" placeholder="Enter Page URL">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" >Meta Title <em>*</em>
                                                </label>
                                                <input type="text" name="meta_title" id="meta_title" class="form-control" data-validation="" placeholder="Enter Meta Title">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" >Meta Description <em>*</em>
                                                </label>
                                                <textarea class="form-control" rows="5" name="meta_description" id="meta_description" placeholder="Enter Meta Description"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" >Meta Keywords <em>*</em>
                                                </label>
                                                <textarea name="meta_keyword" id="meta_keyword" rows="6" class="form-control" data-validation="" placeholder="Enter Meta Keyword"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            <!-- COL-MD-9 Ends -->
                            </div>

                            <div class="col-md-3">

                                <!-- Pricing -->
                                <div class="card card-bordered">
                                    <div class="card-inner">
                                        <h5 class="card-title">Pricing & Tax  </h5>
                                        <div class="form-group">
                                            <label class="form-label"> Selling Price  <span class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="top" title="" data-original-title="The Cost at which the product is sold"></span> <em>*</em>
                                            </label>
                                            <input type="number" name="selling_price" id="selling_price"  min="1" class="form-control" placeholder="Enter amount" >
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"> Actual Price <span class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="top" title="" data-original-title="To show a reduced price for the item, enter the actual cost of the product and put a lower cost at the Selling Price. "></span> <em>*</em>
                                            </label>
                                            <input type="number" name="actual_price" id="actual_price"  min="1" class="form-control" placeholder="Enter Actual price" >
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Select Tax <em>*</em></label>
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="tax_class" id="tax_class" data-search="on">
                                                    <!-- <option value=' '> Select Tax</option> -->
                                                   <?php echo $data['tax_class']; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"> Tax type <em>*</em></label>
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="tax_type" id="tax_type" >
                                                    <!-- <option value=' '> Select Tax Type</option> -->
                                                   <option value="inclusive">Inclusive</option>
                                                   <option value="exclusive">Exclusive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Select Brand</label>
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="brand_id" id="brand_id" data-search="on">
                                                    <!-- <option value=' '> Select Brand</option> -->
                                                    <option value="0">Not Available</option>
                                                    <?php echo $data['brand_list']; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"> Tags <span class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="top" title="" data-original-title="Map the product under the tags like New Arrival, Hot Deals, Featured item and Best Seller"></span></label>
                                            <div class="form-control-wrap">
                                                <select class="form-select" multiple="multiple"  name="product_tags[]"  data-placeholder="Select Product Tags" data-search="on">
                                                    <option value="new_arrival" selected>New Arrival</option>
                                                    <option value="hot_deals">Hot Deals</option>
                                                    <option value="featured_product">Featured Product</option>
                                                    <option value="best_seller">Best Seller </option>
                                                </select>
                                                <!-- <p class="help_text">* Select multiple main categories if needed</p> -->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Display Tag</label>
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="display_tag" id="display_tag" data-search="on">
                                                    <option value="0" selected>Select Tag</option>
                                                    <?php echo $data['display_tags']; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group display_tag_start_date display_none">
                                            <label class="form-label">Display Tag Start Date</label>
                                            <input type="hidden" name="display_tag_start_date" value="<?php echo date("d-m-Y") ?>">
                                            <span type="text"  class="form-control display_tag_start_date_input" ></span>
                                        </div>

                                         <div class="form-group">
                                            <label class="form-label">Display Tag End Date</label>
                                            <input type="text" name="display_tag_end_date" id="display_tag_end_date" class="form-control date-picker" data-date-format="dd-mm-yyyy"  autocomplete="off" placeholder="End Date">
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="has_variants" id="has_variants" value="1">
                                            <label class="custom-control-label" for="has_variants">This product has multiple options, like different sizes or colors</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Inventory -->

                                <div class="card card-bordered">
                                    <div class="card-inner">
                                        <h5 class="card-title">Inventory  </h5>
                                        <div class="form-group display_none">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="track_stock" id="track_stock" checked>
                                                <label class="custom-control-label" for="track_stock">Track Stock Quantity</label>
                                            </div>
                                        </div>
                                        <div class="form-group display_none">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="sell_out_of_stock" id="sell_out_of_stock">
                                                <label class="custom-control-label" for="sell_out_of_stock">Continue selling when out of stock</label>
                                            </div>
                                        </div>
                                        <div class="form-group display_none" id="stock_id_wrap">
                                            <label class="form-label"> Stock Quantity <em class="stock_str">*</em>
                                            </label>
                                            <input type="number" name="stock" id="stock" min="0" class="form-control" placeholder="Enter Quantity" >
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"> SKU 
                                            </label>
                                            <input type="text" name="sku"  class="form-control" placeholder="Enter SKU" >
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"> Minimum Order Quantity <em>*</em></label>
                                            <input type="text" name="min_order_qty"  class="form-control" placeholder="Enter Quantity" >
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"> Maximum Order Quantity <em>*</em></label>
                                            <input type="text" name="max_order_qty"  class="form-control" placeholder="Enter Quantity" >
                                        </div>
                                    </div>
                                </div>

                            <!-- COL-MD-3 Ends -->
                            </div>

                            <!-- ROW Ends -->
                        </div>
                        <!-- ROW Ends -->

                    </form>
                </div>
                <!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<!-- content @e -->
<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    // Filter mapping function 

    $("#sub_category_id").on("change",function() {
        var sub_cat_id = $(this).val();
            $.ajax({
                type: "POST",
                url: core_path + "products/api/getFilterMasterList",
                dataType: "html",
                data:{ result: sub_cat_id },
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    $(".filter_masters_colomn").html();
                    $(".filter_masters_colomn").html(data);
                    console.log(data);
                   
                }
            });
    });

    //  On Change Display tag 

    $("#display_tag").on("change",function() {
        var value = $(this).val();
        if(value!="0") {
            $(".display_tag_start_date").removeClass("display_none");
            var date = "<?php echo date("d-m-Y") ?>";
            $(".display_tag_start_date_input").html(date);
        } else {
            $(".display_tag_start_date").addClass("display_none");
        }
    });
    
    // Select2 Option

    $(".variant_option_select").select2({
        tags: true
    });
    
    // Image Uploader

    $('.input-images').imageUploader();

    // Add  Product

    $("#addProduct").validate({
        rules: {
           
            main_category_id : {
                 required: function(element){
                    if($("#mainmapping").prop("checked") == true){
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            sub_category_id : {
                 required: function(element){
                    if($("#submapping").prop("checked") == true){
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            short_description: {
                required: true
            },
            title_name: {
                required: true
            },
            page_url: {
                required: true
            },
            meta_title: {
                required: true
            },
            meta_description: {
                required: true
            },
            meta_keyword: {
                required: true
            },
            display_tag_end_date: {
                required : function(element) {
                    if($("#display_tag").val()=="0") {
                        return false;
                    } else {
                        return true;
                    }
                }
            },
            // min_order_qty: {
            //     required: true
            // },
            // max_order_qty: {
            //     required: true
            // },
            selling_price: {
                required: true
            },
            actual_price: {
                required: true
            },
            stock: {
                required: true,
            },
            handling_amount : {
                 required: function(element){
                    if($(".handling_amount").val()=="0") {
                        return false;
                    } else {
                        return true;
                    }
                }
            }

        },
        messages: {
            title_name: {
                required: "Please Enter Product Name",
            },
            page_url: {
                required: "Please Enter Page URL",
            },
            meta_title: {
                required: "Please Enter Meta Title",
            },
            meta_description: {
                required: "Please Enter Meta Description",
            },
            meta_keyword: {
                required: "Please Enter Meta Keyword",
            },
            tax_class: {
                required: "Please Sellect Tax Class",
            },
            tax_type: {
                required: "Please Sellect Tax Type",
            },
            brand_id: {
                required: "Please Sellect Brand",
            },
            display_tag_end_date: {
                required: "Please Enter Display Tag End Date",
            },
            main_category_id: {
                required: "Please Sellect Main Category",
            },
            sub_category_id: {
                required: "Please  Sellect Sub Category",
            },
            short_description: {
                required: "Please Enter Short Description",
            },
            // min_order_qty: {
            //     required: "Please Sellect Main Category",
            // },
            // max_order_qty: {
            //     required: "Please  Sellect Sub Category",
            // },
            selling_price: {
                required: "Please enter product selling price",
            },
            actual_price: {
                required: "Please enter product actual price",
            },
            stock: {
                required: "Please enter product stock quantity",
            },
            handling_amount: {
                required: "Please enter handling amount",
            }
        },
         submitHandler: function(form) {
            toastr.clear();

            if ($("#draft_button").prop("checked") == false) {
                Swal.fire({
                    title: "Are you sure to Save and Publish?",
                    text: "Once published the item will not be deleted permanently. However move to trash option is available !!",
                    icon: 'warning',
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if (result.value) {
                        saveForm();
                    }
                });
            }else{
                saveForm();
            }
            return false;
        }
      
    });

    // Save Form

    function saveForm() {
        var formname = document.getElementById("addProduct");
        var formData = new FormData(formname);
            $.ajax({
                url: core_path + "products/api/add",
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
                        window.location = core_path + "products?a=success";
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

    // Add Variant Image

    var variant_option_count = 1;
    var variant_count_actual = 1;

    // Return duration steeings

    $(".has_return_duration").click(function() { 
        $("#has_return_duration").val($(this).val());
        if($(this).val()==1) {
            $(".return_duration").removeClass("display_none");
        } else {
            $(".return_duration").addClass("display_none");
        }
    });


$(".shipping_status").on("change",function() {
    if($(this).val()==1) {
        $(".shipping_type_display").removeClass("display_none");
    } else {
        $(".shipping_type_display").addClass("display_none");
    }
});

$(".handling").on("change",function() {
    if($(this).val()==1) {
        $(".handling_amount_display").removeClass("display_none");
    } else {
        $(".handling_amount_display").addClass("display_none");
    }
});

</script>
 
<script src="<?php echo JSPATH ?>variant.js"></script>




