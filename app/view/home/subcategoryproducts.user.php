<?php require_once 'includes/top.php'; ?>
    <!-- Backdrop (ensure only one exists on page) -->
    <div class="nav-backdrop" id="navBackdrop"></div>
    <!-- <div class="nav-backdrop"></div> -->

    <div class="container shop-container">
        <div class="shop-header">
            <h1 class="shop-title" id="shopTitle"><?php echo $data['main_cat_info']['category']; ?> - <?php echo $data['c_info']['subcategory']; ?></h1>
        </div>

        <button class="mobile-filter-toggle" style="margin-left: 95px;width: 50%;text-align: center;" onclick="toggleMobileFilters()">
            <i class="fas fa-filter"></i> Filters
        </button>

        <div class="shop-content">
            <!-- Filters Sidebar -->
            <div class="filters-sidebar" id="filtersSidebar">
                <div class="filter-section">
                    <h3 class="filter-title">Categories</h3>
                    <div class="filter-options">
                        <?php echo $data['category'] ?> 
                    </div>
                </div>

                <div class="filter-section">
                    <h3 class="filter-title">Customer Ratings</h3>
                    <div id="rating_ecom_filter" class="ecom_filter_type visible">
                        <div class="filter-options">
                            <?php echo $data['rating_filter'] ?> 
                        </div>
                    </div>
                </div>

                <?php echo $data['filter_list'] ?>

                <div class="filter-section">
                    <h3 class="filter-title">Price Range</h3>
                    <form class="clearfix"> 
                        <div class="slider-range"></div>
                        <input type="text" name="price" class="amount" />
                        <button id="filter_Price">Filter</button>
                    </form>
                </div>
                

                <button class="clear-filters" onclick="clearAllFilters()">
                    Clear All Filters
                </button>
            </div>

            <!-- Products Section -->
            <div class="products-section" style="padding: 12px !important;">
                <div class="products-header">
                    <div class="results-count" id="resultsCount">
                        <!-- Showing 1-12 of 148 products -->
                        <p>Showing <?php echo $data['list']['start_from'] ?>–<?php echo $data['list']['start_to'] ?> of <?php echo $data['list']['total_records'] ?> results</p>
                    </div>
                    <div class="sort-dropdown">
                        <select class="sort-select" name="orderby" id="sort_filter" data-token="<?php echo $data['token'] ?>">
                            <?php echo $data['sort_filter'] ?>
                        </select>
                    </div>
                </div>

                <div class="products-grid">
                    <?php echo $data['list']['layout'] ?>
                </div>

                <?php if($data['total_records'] > 0 && $data['count']['total_pages'] > 1) {?>
                    <div class="pagination-container">
                        <div class="pagination">
                            <ul>
                                <li><a href="<?php echo $data['previous'] ?>" class="page-btn prev-btn">
                                    <i class="fas fa-chevron-left"></i>
                                </a></li>
                                <?php echo $data['page'] ?>
                                <li><a href="<?php echo $data['next'] ?>" class="page-btn next-btn">
                                    <i class="fas fa-chevron-right"></i>
                                </a></li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>


    <!--call to action end-->
    <?php require_once 'includes/bottom.php'; ?>

    <script type="text/javascript" src="<?php echo PLUGINS ?>filter/filter.js"></script>

    <style>
    .pagination-container {
        display: flex;
        justify-content: center;
        margin: 30px 0;
    }
    
    .pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex-wrap: nowrap;
    }
    
    /* Override any existing pagination styles */
    .pagination ul {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        list-style: none;
        margin: 0;
        padding: 0;
        flex-wrap: nowrap;
    }
    
    .pagination li {
        margin: 0;
    }
    
    .pagination li a {
        width: 40px;
        height: 40px;
        border: 1px solid #e0e0e0;
        background: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 14px;
        color: #333;
        text-decoration: none;
    }
    
    .pagination li a:hover {
        border-color: #ccc;
        background: #f8f8f8;
    }
    
    .pagination li.current a {
        background: #e74c3c;
        border-color: #e74c3c;
        color: white;
    }
    
    .pagination li.current a:hover {
        background: #c0392b;
        border-color: #c0392b;
    }
    
    .pagination li a.prev-btn,
    .pagination li a.next-btn {
        font-size: 12px;
    }
    
    /* Dots/Ellipsis styling */
    .pagination li.dots {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        margin: 0;
        padding: 0;
    }
    
     .pagination li.dots span {
         font-size: 14px;
         color: #666;
         font-weight: bold;
         line-height: 1;
         vertical-align: middle;
         display: inline-block;
     }

     /* Filter Styles */
     .filter-section {
         margin-bottom: 20px;
         padding: 15px;
         background: white;
         border-radius: 8px;
         box-shadow: 0 2px 4px rgba(0,0,0,0.1);
     }

     .filter-title {
         font-size: 16px;
         font-weight: bold;
         color: #333;
         margin-bottom: 15px;
         margin-top: 0;
         text-transform: uppercase;
     }

     .filter-options {
         display: flex;
         flex-direction: column;
         gap: 8px;
     }

     .filter-option {
         display: flex;
         align-items: center;
         cursor: pointer;
         padding: 5px 0;
         font-size: 14px;
         color: #333;
     }

     .filter-option input[type="checkbox"] {
         margin-right: 8px;
         width: 16px;
         height: 16px;
         cursor: pointer;
         accent-color: #e74c3c;
     }

     .filter-option span {
         font-size: 14px;
         color: #333;
         font-weight: normal;
         text-transform: uppercase;
     }

     .filter-option:hover {
         background-color: #f8f9fa;
         border-radius: 4px;
         padding: 5px 8px;
         margin: 0 -8px;
     }
     </style>

    <script type="text/javascript">

    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null) {
         return null;
        }
        return decodeURI(results[1]) || 0;
    }

    // Brand filter Actions

    $('.brands_filter_btn').click(function() {
        var url = "";
        var price_filter = $.urlParam('price');
        var page_number  = $.urlParam('p');
        var sortby       = $.urlParam('sortby');
        var brands       = $.urlParam('brands');

        if (price_filter) {   
            var url = url + "?price="+$.urlParam('price');
        }

        if(page_number) {
            if(price_filter || sortby || brands) 
            {
               var q_or_and  = "&";
            } else {
                q_or_and     = "?";
            }
            var url = url + q_or_and + "p="+$.urlParam('p');
        }

        if(sortby) {   
            if(price_filter || page_number || brands) 
            {
               var q_or_and  = "&";
            } else {
                q_or_and     = "?";
            }
            var url = url + q_or_and + "sortby="+$.urlParam('sortby');
        }

        var values = new Array();
        $.each($("input[name='brand_ids[]']:checked"), function() {
          values.push($(this).val());
        });

        if(price_filter || page_number || sortby) {
           var q_or_and  = "&";
        } else {
            q_or_and     = "?";
        }

       if(values!="") {
            url = url + q_or_and + "brands=" + values;
       }
        window.location.href =  location.protocol + '//' + location.host + location.pathname + url;
    });

    // Price Filter actions

    $('#filter_Price').click(function() {

        var filter_amount = $(".amount").val(); 

        var sortby       = $.urlParam('sortby');
        var brands       = $.urlParam('brands');
        var page_number  = $.urlParam('p');

        if (sortby || brands || page_number  ) 
        {   
            var filter_amount = $(".amount").val(); 
            
            var url = "";
            
            if(sortby) {   
                var url = url + "&sortby="+$.urlParam('sortby');
            }

            if(brands) {   
                var url = url + "&brands="+$.urlParam('brands');
            }

            if(page_number) {
                var url = url + "&p="+$.urlParam('p');
            }

            window.location.href  = location.protocol + '//' + location.host + location.pathname+"?price="+filter_amount+url;
            
        } else {
            window.location.href  = location.protocol + '//' + location.host + location.pathname+"?price="+filter_amount;
        }
        return false;
    });

    $(document).on("change", "#sort_filter", function() {
        var value = $(this).val();
        var token = $(this).data("token");
        window.location.href = "<?php echo BASEPATH ?>product/subcategory/"+ token +value ;
    });

    //sortby select option 

    $(document).ready(function() {
      $('#short').change(function() {
        var selectOption  = $(this).val(); 
        
        var price_filter  = $.urlParam('price');
        var brands        = $.urlParam('brands');
        var page_number   = $.urlParam('p');

        url = "";

        if (price_filter) {   
            var url = url + "?price="+$.urlParam('price');
        }

        if(page_number) {
            if(price_filter || brands)  {
               var q_or_and  = "&";
            } else {
                q_or_and     = "?";
            }

            var url = url + q_or_and + "p="+$.urlParam('p');
        }

        if(brands) {   
            if(price_filter || page_number )  {
               var q_or_and  = "&";
            } else {
                q_or_and     = "?";
            }
            var url = url + q_or_and + "brands="+$.urlParam('brands');
        }

        if(price_filter || page_number || brands) 
        {
           var q_or_and  = "&";
        } else {
            q_or_and     = "?";
        }

        url = url + q_or_and + selectOption;
       
        window.location.href  = location.protocol + '//' + location.host + location.pathname+ url;
       
      });

       $('#pageAmount').change(function() {
        var page_amount = $(this).val(); 
        var token = $(this).data("token"); 

        var price_filter = "?";
        if ($.urlParam('price')) 
        {   
            var price_filter = "?price="+$.urlParam('price')+"&";
        }
        var page ="";

        if($.urlParam('sortby')) {
            var page = "&sortby="+$.urlParam('sortby');
        }

        if($.urlParam('p')) {
            var page = "&p="+$.urlParam('p');
        }

        window.location.href = "<?php echo BASEPATH ?>product/subcategory/"+ token + price_filter + "page_amount=" + page_amount + page;
       
      });
    });


    $('.addToWishList').click(function() {
        var id          = $(this).data("id");
        var vendor_id   = $(this).data("vendor_id");
        var variant_id  = $(this).data("variant_id");

        $.ajax({
                type: "POST",
                url: base_path + "product/api/addToWishList",
                dataType: "html",
                data: { 
                        product_id  : id,
                        vendor_id   : vendor_id,
                        variant_id  : variant_id
                      },
                    beforeSend: function () {
                        $(".page_loading").show();
                    },
                    success: function (data) {
                        $(".page_loading").hide();
                        data  = data.split("`");
                        if (data[0] == 1) {
                            window.location = base_path + "product?s=success";
                        } else {
                            window.location = base_path + "product?r=success";
                        }
                    }
                });
        return false;
    });

    
    // Clear All Filters Function
    function clearAllFilters() {
        // Clear all filter checkboxes
        $('.filter_option').prop('checked', false);
        
        // Clear price filter input
        $('.amount').val('');
        
        // Clear sort dropdown to default
        $('#sort_filter').val('');
        
        // Redirect to clean URL without any filter parameters
        window.location.href = window.location.pathname;
    }
        
    </script>

    <?php if (isset($_GET['s'])): ?>
    <script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Product has been added to wishlist!</strong>!',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
    </script>
    <?php endif ?>

    <?php if (isset($_GET['r'])): ?>
    <script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Product has been Removed from wishlist!</strong>!',
            type: 'warning',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
    </script>
    <?php endif ?>

     <?php if (isset($_GET['c'])): ?>
    <script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Product has been added to the cart!</strong>!',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
    </script>
    <?php endif ?>