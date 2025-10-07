    <?php require_once 'includes/top.php'; ?>
    <!-- Backdrop (ensure only one exists on page) -->
    <div class="nav-backdrop" id="navBackdrop"></div>
    <!-- <div class="nav-backdrop"></div> -->

    <div class="container shop-container">
        <div class="shop-header">
            <h1 class="shop-title" id="shopTitle">All Products</h1>
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
                    <div class="filter-options">
                        <?php echo $data['rating_filter'] ?> 
                    </div>
                </div>

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
                        Showing 1-12 of 148 products
                    </div>
                    <div class="sort-dropdown">
                        <select class="sort-select" name="orderby" id="sort_filter" data-token="<?php echo $data['token'] ?>">
                            <?php echo $data['sort_filter'] ?>
                        </select>
                    </div>
                </div>

                <div class="products-grid">
                    <?php echo $data['list']['layout'] ?>
                    
                    <!-- <div class="product-card" >
                        <div class="product-image min-img-card">
                            <img src="<?php echo IMGPATH ?>/IMG_0283.jpg" alt="Slim Fit Dark Jeans" onclick="quickView(2)">
                            <span class="product-badge">Popular</span>
                            <div class="product-actions">
                            <button class="action-btn wishlist-action" onclick="toggleWishlist(2)">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="action-btn quick-view" onclick="quickView(1)">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            </div>
                        </div>
                        <div class="product-info" onclick="quickView(2)">
                            <h3 class="product-title">Slim Fit Dark Jeans</h3>
                            <div class="product-price">
                            <span class="current-price">$79.99</span>
                            <span class="original-price">$99.99</span>
                            </div>
                        </div>
                    </div> -->

                    <!-- <div class="product-card">
                        <div class="product-image min-img-card">
                            <img src="<?php echo IMGPATH ?>/IMG_0283.jpg" alt="Slim Fit Dark Jeans" onclick="quickView(2)">
                            <span class="product-badge">Popular</span>
                            <div class="product-actions">
                            <button class="action-btn wishlist-action" onclick="toggleWishlist(2)">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="action-btn quick-view" onclick="quickView(1)">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            </div>
                        </div>
                        <div class="product-info" onclick="quickView(2)">
                            <h3 class="product-title">Slim Fit Dark Jeans</h3>
                            <div class="product-price">
                            <span class="current-price">$79.99</span>
                            <span class="original-price">$99.99</span>
                            </div>
                        </div>
                    </div>

                    <div class="product-card">
                        <div class="product-image min-img-card">
                            <img src="<?php echo IMGPATH ?>/IMG_0283.jpg" alt="Slim Fit Dark Jeans" onclick="quickView(2)">
                            <span class="product-badge">Popular</span>
                            <div class="product-actions">
                            <button class="action-btn wishlist-action" onclick="toggleWishlist(2)">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="action-btn quick-view" onclick="quickView(1)">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            </div>
                        </div>
                        <div class="product-info" onclick="quickView(2)">
                            <h3 class="product-title">Slim Fit Dark Jeans</h3>
                            <div class="product-price">
                            <span class="current-price">$79.99</span>
                            <span class="original-price">$99.99</span>
                            </div>
                        </div>
                    </div>


                    <div class="product-card">
                        <div class="product-image min-img-card">
                            <img src="<?php echo IMGPATH ?>/IMG_0283.jpg" alt="Slim Fit Dark Jeans" onclick="quickView(2)">
                            <span class="product-badge">Popular</span>
                            <div class="product-actions">
                            <button class="action-btn wishlist-action" onclick="toggleWishlist(2)">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="action-btn quick-view" onclick="quickView(1)">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            </div>
                        </div>
                        <div class="product-info" onclick="quickView(2)">
                            <h3 class="product-title">Slim Fit Dark Jeans</h3>
                            <div class="product-price">
                            <span class="current-price">$79.99</span>
                            <span class="original-price">$99.99</span>
                            </div>
                        </div>
                    </div>

                    <div class="product-card">
                        <div class="product-image min-img-card">
                            <img src="<?php echo IMGPATH ?>/IMG_0283.jpg" alt="Slim Fit Dark Jeans" onclick="quickView(2)">
                            <span class="product-badge">Popular</span>
                            <div class="product-actions">
                            <button class="action-btn wishlist-action" onclick="toggleWishlist(2)">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="action-btn quick-view" onclick="quickView(1)">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            </div>
                        </div>
                        <div class="product-info" onclick="quickView(2)">
                            <h3 class="product-title">Slim Fit Dark Jeans</h3>
                            <div class="product-price">
                            <span class="current-price">$79.99</span>
                            <span class="original-price">$99.99</span>
                            </div>
                        </div>
                    </div>

                    <div class="product-card">
                        <div class="product-image min-img-card">
                            <img src="<?php echo IMGPATH ?>/IMG_0283.jpg" alt="Slim Fit Dark Jeans" onclick="quickView(2)">
                            <span class="product-badge">Popular</span>
                            <div class="product-actions">
                            <button class="action-btn wishlist-action" onclick="toggleWishlist(2)">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="action-btn quick-view" onclick="quickView(1)">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            </div>
                        </div>
                        <div class="product-info" onclick="quickView(2)">
                            <h3 class="product-title">Slim Fit Dark Jeans</h3>
                            <div class="product-price">
                            <span class="current-price">$79.99</span>
                            <span class="original-price">$99.99</span>
                            </div>
                        </div>
                    </div>

                    <div class="product-card">
                        <div class="product-image min-img-card">
                            <img src="<?php echo IMGPATH ?>/IMG_0283.jpg" alt="Slim Fit Dark Jeans" onclick="quickView(2)">
                            <span class="product-badge">Popular</span>
                            <div class="product-actions">
                            <button class="action-btn wishlist-action" onclick="toggleWishlist(2)">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="action-btn quick-view" onclick="quickView(1)">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            </div>
                        </div>
                        <div class="product-info" onclick="quickView(2)">
                            <h3 class="product-title">Slim Fit Dark Jeans</h3>
                            <div class="product-price">
                            <span class="current-price">$79.99</span>
                            <span class="original-price">$99.99</span>
                            </div>
                        </div>
                    </div>

                    <div class="product-card">
                        <div class="product-image min-img-card">
                            <img src="<?php echo IMGPATH ?>/IMG_0283.jpg" alt="Slim Fit Dark Jeans" onclick="quickView(2)">
                            <span class="product-badge">Popular</span>
                            <div class="product-actions">
                            <button class="action-btn wishlist-action" onclick="toggleWishlist(2)">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="action-btn quick-view" onclick="quickView(1)">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            </div>
                        </div>
                        <div class="product-info" onclick="quickView(2)">
                            <h3 class="product-title">Slim Fit Dark Jeans</h3>
                            <div class="product-price">
                            <span class="current-price">$79.99</span>
                            <span class="original-price">$99.99</span>
                            </div>
                        </div>
                    </div>

                    <div class="product-card">
                        <div class="product-image min-img-card">
                            <img src="<?php echo IMGPATH ?>/IMG_0283.jpg" alt="Slim Fit Dark Jeans" onclick="quickView(2)">
                            <span class="product-badge">Popular</span>
                            <div class="product-actions">
                            <button class="action-btn wishlist-action" onclick="toggleWishlist(2)">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="action-btn quick-view" onclick="quickView(1)">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            </div>
                        </div>
                        <div class="product-info" onclick="quickView(2)">
                            <h3 class="product-title">Slim Fit Dark Jeans</h3>
                            <div class="product-price">
                            <span class="current-price">$79.99</span>
                            <span class="original-price">$99.99</span>
                            </div>
                        </div>
                    </div> -->
                </div>

                <div class="pagination">
                    <button class="page-btn" onclick="changePage(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">4</button>
                    <button class="page-btn">5</button>
                    <button class="page-btn" onclick="changePage(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!--call to action end-->
    <?php require_once 'includes/bottom.php'; ?>

    <script type="text/javascript" src="<?php echo PLUGINS ?>filter/filter.js"></script>

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
        window.location.href = "<?php echo BASEPATH ?>product/"+ token +value ;
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

        window.location.href = "<?php echo BASEPATH ?>product/category/"+ token + price_filter + "page_amount=" + page_amount + page;
       
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