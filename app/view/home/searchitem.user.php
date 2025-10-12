<?php require_once 'includes/top.php'; ?>
    <!--breadcrumbs area start-->
   <!--  <div class="breadcrumbs_area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                             <li><a href="<?php echo BASEPATH ?>">home</a></li>
                            <li><a href="<?php echo BASEPATH ?>product">Search Items</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!--breadcrumbs area end-->

    <!--Offcanvas menu area start-->
    <div class="off_canvars_overlay"></div>
    <div class="Offcanvas_menu d-block d-md-none d-lg-none">
        <div class="container-fluid">
            <div class="canvas_open p-0 border-0">
                <span>Filter</span>
                <a href="#"><i class="fa fs-4 fa-filter"></i></a>
            </div>
            <div class="Offcanvas_menu_wrapper">
                <div class="canvas_close">
                    <a href="#"><i class="ion-android-close"></i></a>
                </div>
                <div class="search-container">
                    <form action="#" autocomplete="off" id="searchItems-mobile" method="POST" >
                        <div class="search_box">
                            <div class="autocomplete">
                                <input id="searchitem-mobile" type="text" name="search_item" placeholder="Search entire store here ..." type="text" class="search_box_color">
                                <button type="submit"><i class="ion-ios-search-strong"></i></button>
                            </div>
                            <div id="myInputautocomplete-mobile-list" class="autocomplete-items display_none" >
                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    <aside class="sidebar_widget">
                        <div class="widget_inner">
                            <div class="widget_list widget_filter">
                                <h2 class="text-capitalize">Filter by price</h2>
                                <form class="clearfix"> 
                                    <div class="slider-range"></div>
                                    <input type="text" name="price" class="amount" />
                                    <button  id="filter_Price">Filter</button>
                                </form>
                            </div>                          
                        </div>
                    </aside>

                    <div class="widget_inner">
                        <div class="widget_list widget_filter">
                            <h2 class="text-capitalize">Brand </h2>
                            <div id="brand_ecom_filter" class="ecom_filter_type visible ">
                                <div class="filter_wrap ">
                                    <div class="filter_list_wrap">
                                        <ul class="filter_list " id="brand-search">
                                            <?php echo $data['brands'] ?> 
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>                          
                    </div>
                    
                    <aside class="sidebar_widget">
                        <div class="widget_inner">
                            <div class="widget_list widget_categories categories_list_widget">
                                <h2 class="text-capitalize">categories</h2>
                                <ul>
                                <?php echo $data['category'] ?> 
                                </ul>
                            </div>                       
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
    <!--Offcanvas menu area end-->

    <!--shop  area start-->
    <div class="shop_area mt-10">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 d-none d-md-block d-lg-block">
                    <div class="sticky-style">
                        <!--sidebar widget start-->
                        <aside class="sidebar_widget">
                            <div class="widget_inner">
                                <div class="widget_list widget_filter">
                                    <h2 class="text-capitalize">Filter by price</h2>
                                    <form class="clearfix"> 
                                        <div class="slider-range"></div>
                                        <input type="text" name="price" class="amount" />
                                        <button  id="filter_Price">Filter</button>
                                    </form>
                                </div>                          
                            </div>
                        </aside>
                        <?php if(1!=1) { ?>
                        <aside class="sidebar_widget">
                            <div class="widget_inner">
                                <div class="widget_list widget_categories">
                                    <h2 class="text-capitalize">Brands</h2>
                                    <form class="clearfix"> 
                                        <ul>
                                        <?php echo $data['brands'] ?> 
                                        </ul>
                                        <button type="button" id="brand_filter" class='brands_filter_btn '>Filter</button>
                                    </form>
                                </div>                         
                            </div>
                        </aside>
                        <?php } ?>
                        <aside class="sidebar_widget">
                            <div class="widget_inner">
                                <div class="widget_list widget_categories categories_list_widget">
                                    <h2 class="text-capitalize">categories</h2>
                                    <ul>
                                    <?php echo $data['category'] ?> 
                                    </ul>
                                </div>                       
                            </div>
                        </aside>
                        <!--sidebar widget end-->
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12">
                    <!--shop wrapper start-->
                    
                    <div class="shop_title">
                        <h1> Showing <?php echo $data['list']['start_from'] ?>–<?php echo $data['list']['start_to'] ?> of <?php echo $data['list']['totals'] ?> results for "<?php echo $data['search_key']; ?>"</h1>
                    </div>
                    <div class="shop_toolbar_wrapper">
                        <select class="niceselect_option" name="orderby" id="short">
                                <option <?php echo (isset($_GET['sortby']))? (($_GET['sortby']=="asc")? "selected" : "") : "" ?>  
                                value="sortby=asc">Product Name: A - Z </option>
                                <option <?php echo (isset($_GET['sortby']))? (($_GET['sortby']=="desc")? "selected" : "") : "" ?>  
                                value="sortby=desc">Product Name: Z - A</a></option>
                                <option <?php echo (isset($_GET['sortby']))? (($_GET['sortby']=="lowtohigh")? "selected" : "") : "" ?> 
                                value="sortby=lowtohigh">Sort by price: low to high</option>
                                <option <?php echo (isset($_GET['sortby']))? (($_GET['sortby']=="hightolow")? "selected" : "") : "" ?> 
                                value="sortby=hightolow">Sort by price: high to low</option>
                        </select>
                        <div class="page_amount">
                            <p>Showing <?php echo $data['list']['start_from'] ?>–<?php echo $data['list']['start_to'] ?> of <?php echo $data['list']['totals'] ?> results</p>
                        </div>
                    </div>
                    <div class="row shop_wrapper g-4">
                        <?php echo $data['list']['layout'] ?>                       
                    </div>
                    <?php if($data['list']['total_records'] > 0 && $data['count'] > 1) {?>
                    <div class="shop_toolbar t_bottom">
                        <div class="pagination">
                            <ul>
                                <li><a href="<?php echo $data['previous'] ?>"><<</a></li>
                                    <?php echo $data['page'] ?>
                                <li><a href="<?php echo $data['next'] ?>">>></a></li>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!--shop  area end-->

    <!--call to action end-->
    <?php require_once 'includes/bottom.php'; ?>

    <script type="text/javascript">



    $(".sortBy").click(function() {
        var sortBy = $(this).val(); 
    });


    //sortby select option start


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
        var sortby      = $.urlParam('sortby');
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
        var search_key = "<?php echo $data['search_key']; ?>";
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
                            window.location = base_path + "home/searchitems/"+search_key+"?s=success";
                        } else {
                            window.location = base_path + "home/searchitems/"+search_key+"?r=success";
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