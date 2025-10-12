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
    
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                             <li><a href="<?php echo BASEPATH ?>">home</a></li>
                             <li><a href="<?php echo BASEPATH ?>">Brand</a></li>
                            <li><a href="#"><?php echo $data['brand_info']['brand_name']; ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->
    <!--shop  area start-->
    <div class="shop_area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shop-listingbtn mb-3">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-12 category-top">
                                    <?php echo $data['shop_category'] ?> 
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
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
                        <aside class="sidebar_widget">
                            <div class="widget_inner">
                                <div class="widget_list widget_categories">
                                    <h2 class="text-capitalize">Brands</h2>
                                    <ul>
                                    <?php echo $data['brands'] ?> 
                                    </ul>
                                </div>                         
                            </div>
                        </aside>
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
                        <h3><?php echo $data['brand_info']['brand_name']; ?></h3>
                    </div>
                    <div class="shop_banner mb-0">
                        <?php if($data['shop_banner']) { ?>
                        <img src="<?php echo SRCIMG.$data['shop_banner']['file_name'] ?>" alt="">
                        <?php } else { ?>
                            <!-- <img src="./lib/images/no_product.jpg" alt=""> -->
                        <?php }  ?>
                    </div>
                    <div class="shop_toolbar_wrapper">
                        <select class="niceselect_option" name="orderby" id="short">
                                <option <?php echo (isset($_GET['sortby']))? (($_GET['sortby']=="asc")? "selected" : "") : "" ?>  
                                value="sortby=asc">Product Name: A - Z </option>
                                <option <?php echo (isset($_GET['sortby']))? (($_GET['sortby']=="desc")? "selected" : "") : "" ?>  
                                value="sortby=desc">Product Name: Z - A</a></option>
                                <option <?php echo (isset($_GET['sortby']))? (($_GET['sortby']=="hightolow")? "selected" : "") : "" ?> 
                                value="sortby=lowtohigh">Sort by price: low to high</option>
                                <option <?php echo (isset($_GET['sortby']))? (($_GET['sortby']=="lowtohigh")? "selected" : "") : "" ?> 
                                value="sortby=hightolow">Sort by price: high to low</option>
                        </select>
                        <div class="page_amount">
                            <p>Showing <?php echo $data['list']['start_from'] ?>–<?php echo $data['list']['start_to'] ?> of <?php echo $data['list']['totals'] ?> results</p>
                        </div>
                        <!-- <div class="page_amount">
                            <select name="page_amount" id="pageAmount">
                                <option <?php echo (isset($_GET['page_amount']))? (($_GET['page_amount']=="0-10")? "selected" : "") : "" ?> value="0-10">0-10</option>
                                <option <?php echo (isset($_GET['page_amount']))? (($_GET['page_amount']=="0-20")? "selected" : "") : "" ?> value="0-20">0-20</option>
                                <option <?php echo (isset($_GET['page_amount']))? (($_GET['page_amount']=="0-30")? "selected" : "") : "" ?> value="0-30">0-30</option>
                                <option <?php echo (isset($_GET['page_amount']))? (($_GET['page_amount']=="show_all")? "selected" : "") : "" ?> value="show_all">Show All</option>
                            </select>
                        </div> -->
                    </div>
                    <div class="row shop_wrapper g-4">
                        <?php echo $data['list']['layout'] ?>                       
                    </div>
                    <?php if($data['list']['total_records'] > 0 && $data['count']['total_pages'] > 1) {?>
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

    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null) {
         return null;
        }
        return decodeURI(results[1]) || 0;
    }

    $('#filter_Price').click(function() {

        var filter_amount = $(".amount").val(); 

        if ($.urlParam('sortby')) 
        {   
            window.location.href  = location.protocol + '//' + location.host + location.pathname+"?price="+filter_amount+"&sortby="+$.urlParam('sortby');
        } else {
            window.location.href  = location.protocol + '//' + location.host + location.pathname+"?price="+filter_amount;
        }
        return false;
    });

    //sortby select option start
    $(document).ready(function() {
      $('#short').change(function() {
        var selectOption = this.value;
        var token = "<?php echo $data['token'] ?>";

        var price_filter = "?";
        if ($.urlParam('price')) 
        {   
            var price_filter = "?price="+$.urlParam('price')+"&";
        }
        var page ="";

        if($.urlParam('p')) {
            var page = "&p="+$.urlParam('p');
        }


        if(selectOption == 1){
            window.location.href = "<?php echo BASEPATH ?>product/brand/"+ token + price_filter +"sortby=asc" + page;
        }else if(selectOption == 2){
            window.location.href = "<?php echo BASEPATH ?>product/brand/"+ token + price_filter +"sortby=desc" + page;
        }else if(selectOption == 3){
            window.location.href = "<?php echo BASEPATH ?>product/brand/"+ token + price_filter +"sortby=lowtohigh" + page;
        }else if(selectOption == 4){
            window.location.href = "<?php echo BASEPATH ?>product/brand/"+ token + price_filter +"sortby=hightolow" + page;
        }else{
            window.location.href = "<?php echo BASEPATH ?>product";
        }
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