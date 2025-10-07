<?php require_once 'includes/top.php'; ?>

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

                <div class="widget_inner">
                    <div class="widget_list widget_filter">
                        <h2 class="text-capitalize">Customer Ratings</h2>
                        <div id="rating_ecom_filter" class="ecom_filter_type visible ">
                            <div class="filter_wrap ">
                                <div class="filter_list_wrap">
                                    <ul class="filter_list " id="rating-search">
                                        <?php echo $data['rating_filter'] ?> 
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
                        
                        <div class="widget_inner">
                            <div class="widget_list widget_filter">
                                <h2 class="text-capitalize">Customer Ratings</h2>
                                <div id="rating_ecom_filter" class="ecom_filter_type visible ">
                                    <div class="filter_wrap ">
                                        <div class="filter_list_wrap">
                                            <ul class="filter_list " id="rating-search">
                                                <?php echo $data['rating_filter'] ?> 
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>                          
                        </div>

                        <?php echo $data['filter_list'] ?>
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
                        <h3><?php echo $data['main_cat_info']['category']; ?> - <?php echo $data['c_info']['subcategory']; ?></h3>
                    </div>
                    <!--banner area start-->
                    <?php if($data['c_info']['file_name']!="") { ?>
                        <div class="shop_banner mb-0">
                            <img src="<?php echo $data['c_info']['file_name']!="" ? SRCIMG.$data['c_info']['file_name'] : IMGPATH."shop-banner.jpg" ?>" alt="image" class="common-banner">
                        </div>
                    <?php } ?>
                   <!--  <?php if($data['page_banner']!="") { ?>
                        <section class="banner_area mb-50">
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
                    <?php } ?> -->
                    <!--banner area end-->
                    <div class="shop_toolbar_wrapper">
                        <select class="niceselect_option" name="orderby" id="sort_filter" data-token="<?php echo $data['token'] ?>">
                                <?php echo $data['sort_filter'] ?>
                        </select>
                        <div class="page_amount">
                            <p>Showing <?php echo $data['list']['start_from'] ?>–<?php echo $data['list']['start_to'] ?> of <?php echo $data['total_records'] ?> results</p>
                        </div>
                    </div>
                    <div class="row shop_wrapper g-4">
                        <?php echo $data['list']['layout'] ?>                       
                    </div>
                    <?php if($data['page_count']!=0 && $data['page_count']!=1 ) {?>
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
       
        url = url + q_or_and + "brands=" + values;

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

        window.location.href = "<?php echo BASEPATH ?>product/category/"+ token + price_filter + "page_amount=" + page_amount + page;
       
      });
    });

    // Add to wish List

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
                            window.location = location.protocol + '//' + location.host + location.pathname + "?s=success";
                        } else {
                            window.location = location.protocol + '//' + location.host + location.pathname + "?r=success";
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