<?php require_once 'includes/top.php'; ?>
<!-- <div class="profile-banner otherpage-banner m-0">
    <img src="<?php echo $data['page_banner']!="" ? SRCIMG.$data['page_banner']['file_name'] : IMGPATH."profile-banner.jpg" ?>" alt="image" class="common-banner">
    <div class="other-banner-title">
        <p>My Orders</p>
    </div>   
</div> -->  
<div class="breadcrumbs_area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                     <!-- this menu items get in top.php (My Account Breadcrum Menus) -->
                    <?php echo $myaccount_breadcurm; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=" manageaddress">
    <div class="<?php echo (($data['cart']['cart']['total_items']!="0" )? "container-fluid" : "container" ) ?>">
        <div class="row">
          <!--   <div class="col-lg-3 col-md-3">
                <div class="contact_message content">
                    <ul class="edit-profile">
                        <li><a href="<?php echo BASEPATH ?>myaccount/edit">My Profile</a>
                        </li>
                        <li ><a href="<?php echo BASEPATH ?>myaccount/changepassword">Change Password</a>
                        </li>
                        <li ><a href="<?php echo BASEPATH ?>myaccount/manageaddress">Manage Address</a>
                        </li>
                        <li class="active"><a href="<?php echo BASEPATH ?>myaccount/myorders">My Orders</a>
                        </li>
                        <li ><a href="<?php echo BASEPATH ?>myaccount/wishlist">My Wishlist</a>
                        </li>
                        <li><a href="<?php echo BASEPATH ?>home/logout">Logout</a>
                        </li>
                    </ul>
                </div>
            </div> -->
            <!--product items-->
            <div class="<?php echo (($data['cart']['cart']['total_items']!="0" )? "col-md-8" : "col-md-10" ) ?> col-xs-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="contact_message  my_account_forms">
                            <div class="row">
                                <div class="col-md-3">
                                    <h3 class="mb-3">My Orders</h3>
                                </div>
                            </div>
                            <div >
                                <div class="col-lg-12 col-md-12">
                                    <aside class="sidebar_widget">
                                            <div class="widget_inner">
                                                <div class="widget_list widget_categories">
                                                    <form id="myOrderSearch"  >
                                                       <div class="row">
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control myorder_search_input" name="from_date" id="from_date" placeholder="From" autocomplete="off"  value="<?php echo ((isset($_GET['order_from']))? $_GET['order_from'] : "" ) ?>"> 
                                                            </div>
                                                             <div class="col-md-3">
                                                                <input type="text" class="form-control myorder_search_input" name="to_date" id="to_date" placeholder="To" autocomplete="off"  value="<?php echo ((isset($_GET['order_to']))? $_GET['order_to'] : "" ) ?>"> 

                                                            </div>
                                                            <div class="col-md-1">
                                                                <h2 class="myorder_search_or">Or</h2>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control search_order_id_input myorder_search_input" name="order_search_key" id="order_search_key" placeholder="Order Id or Product" value="<?php echo ((isset($_GET['order_search_key']))? $_GET['order_search_key'] : "" ) ?>" oninput="this.value = this.value.toUpperCase()"> 
                                                            </div>
                                                            <div class="col-md-2 myorder_search_btn">
                                                                <button type="submit" class="btn  theme-btn-dark btn-sm order_search_btn" >Search</button>
                                                            </div>
                                                            <div class="col-md-12" >
                                                                <div id="search_error">
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>                          
                                            </div>
                                        </aside>                    
                                    </div>
                                <div class="col-md-9 myorder_search_container">
                                    
                                </div>
                                <?php echo $data['list']['layout'] ?>
                            </div>
                        </div>
                        <div class="shop_toolbar t_bottom">
                            <div class="pagination">
                                <ul>
                                    <?php if($data['count']!=0 && $data['count']!=1 ) {?>
                                        <li><a href="<?php echo $data['previous'] ?>"><<</a></li>
                                            <?php echo $data['page'] ?>
                                        <li><a href="<?php echo $data['next'] ?>">>></a></li>
                                    <?php  } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <?php if ($data['cart']['cart']['total_items']!="0" ){ ?>
            <div class="col-md-4 col-xs-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="contact_message  my_account_forms">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="mb-12">Cart Items</h3>
                                </div>
                            </div>
                            <div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="widget_inner">
                                        <div class="widget_list widget_categories">
                                            <div id="cartItemLayout" >
                                                <?php echo $data['cart']['cart_layout']; ?>
                                            </div>
                                            <div class="mini_cart_table">
                                                <?php if ($data['cart']['cart']['total_items']!="0" && $data['cart']['cart']['total_items']>3 ){ ?>
                                                <div class="cart_total mt-10 emptyCart"><span></span>
                                                    <span class="price"><a class="default-btn" href="<?php echo BASEPATH ?>cartdetails">View all </a></span>
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <div class="mini_cart_footer">
                                            <?php if ($data['cart']['cart']['total_items']!="0"){ ?>
                                                <div class="cart_button">
                                                    <a href="<?php echo BASEPATH ?>product">Continue Shopping</a>
                                                </div>
                                                <?php if ($data['cart']['cart']['total_items']!="0"): ?>
                                                    <div class="cart_button emptyCart">
                                                    <a class="active" href="<?php echo BASEPATH ?>cartdetails">Checkout</a>
                                                    </div>
                                                <?php endif ?>
                                            <?php }else{ ?>
                                                <div class="cart_button">
                                                    <a href="<?php echo BASEPATH ?>product">Continue Shopping</a>
                                                </div>
                                            <?php } ?>
                                            
                                            </div>
                                        </div>                          
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">

    // Myorder Search

    $.validator.addMethod("from_date_er", function (value, elem) {
          if($("#to_date").val()!="") {
                var from = new Date( $("#from_date").val() );
                var to = new Date( $("#to_date").val() );
                return (from <= to);
          } else {
                return true;
          }
            
        });

    $.validator.addMethod("date_chek", function (value, elem) {
        if($("#from_date").val()=="" && $("#to_date").val()=="" && $("#order_search_key").val()=="" ){
            return false;
        } else {
            return true;
        }
    });

     $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null) {
         return null;
        }
        return decodeURI(results[1]) || 0;
    }

     $("#myOrderSearch").validate({
        rules: {
            from_date: {
                from_date_er: true
            },
            order_search_key: {
               date_chek : true
            }
        },
        messages: {
            from_date: {
                from_date_er: "From date must be less than to date",
            },
            order_search_key: {
                date_chek: "Enter order id or pick a date for search"
            }
        },
        errorPlacement: function(error, element) {
                $("#search_error").html();
                if (element.attr("name") == "order_search_key") {
                    error.appendTo("#search_error");
                } else if (element.attr("name") == "from_date") {
                    error.appendTo("#search_error");
                } else {
                    error.insertAfter(element);
                }
            },
        submitHandler: function(form) {

            var search_key       = $("#order_search_key").val();
            var from_date        = $("#from_date").val();
            var to_date          = $("#to_date").val();
            var url              = base_path + "myaccount/myorders"; 

            // if(search_key=="") {
            //     var search_key = $.urlParam("order_search_key");
            // }

            // if(from_date=="") {
            //     var from_date = $.urlParam("order_from");
            // }

            // if(to_date=="") {
            //     var to_date = $.urlParam("order_to");
            // }

            if(search_key) {
                var url = url + "?order_search_key=" + search_key; 
            }

            if(from_date) {
                if(search_key) {
                    var q_or_and = "&";
                } else {
                    var q_or_and = "?";
                }
                var url = url + q_or_and + "order_from=" + from_date;
            }

            if(to_date) {
                if(from_date || search_key) {
                    var q_or_and = "&";
                } else {
                    var q_or_and = "?";
                }
                var url = url + q_or_and + "order_to=" + to_date;
            }

            window.location = url;

            <?php if(1!=1)  { ?>

            // if($("#from_date").val()=="") {
            //     window.location = base_path + "myaccount/myorders?order_search_key=" + $("#order_search_key").val(); 
            // } else if($("#from_date").val()!="" && $("#to_date").val()=="") {
            //     window.location = base_path + "myaccount/myorders?order_date=" + $("#from_date").val(); 
            // } else if($("#from_date").val()!="" && $("#to_date").val()!="") {
            //     window.location = base_path + "myaccount/myorders?order_from=" + $("#from_date").val() + "&order_to=" + $("#to_date").val() ; 
            // }

            <?php } ?>
        }
    }); 


</script>

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