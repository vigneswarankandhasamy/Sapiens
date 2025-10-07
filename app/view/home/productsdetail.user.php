<?php require_once 'includes/top.php'; ?>
<style>
.product-detail-container .jq-star-svg,
.product-detail-container .jq-star {
	width: 20px !important;
	height: 20px !important;
}

.variant-options {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-top: 0.5rem;
}

.variant-options .size-option {
    padding: 0.5rem 1rem;
    border: 2px solid #ddd;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #fff;
    text-align: center;
    min-width: 40px;
    font-weight: 500;
}

.variant-options .size-option:hover {
    border-color: #e74c3c;
    background: #f8f9fa;
}

.variant-options .size-option.active {
    border-color: #e74c3c;
    background: #e74c3c;
    color: white;
}

.variant-options .color-option {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    position: relative;
}

.variant-options .color-option:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.variant-options .color-option.active {
    border-color: #e74c3c;
    transform: scale(1.1);
    /* box-shadow: 0 0 0 2px #fff, 0 0 0 4px #e74c3c; */
    box-shadow: 0 0 0 2px #e74c3c;
}

.variant-options .color-option.active::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-weight: bold;
    font-size: 14px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

/* Action Buttons Styling */
.action-buttons {
    margin: 20px 0;
    padding: 15px 0;
}

.action-buttons .btn {
    /* margin: 5px; */
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.action-buttons .btn i {
    font-size: 14px;
}

/* Disabled variant options when item is in cart */
.product-options.disabled .variant-options .size-option,
.product-options.disabled .variant-options .color-option {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.product-options.disabled .variant-options .size-option.active,
.product-options.disabled .variant-options .color-option.active {
    opacity: 1;
    cursor: default;
    pointer-events: none;
}

.product-options.disabled .option-label {
    color: #999;
    position: relative;
}

.product-options.disabled .option-label::after {
    content: " (Selected)";
    color: #28a745;
    font-weight: bold;
}

/* Tab functionality */
.product-tabs {
    margin-top: 30px;
}

.tab-buttons {
    display: flex;
    border-bottom: 2px solid #e9ecef;
    margin-bottom: 20px;
}

.tab-button {
    background: none;
    border: none;
    padding: 12px 20px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    color: #666;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
}

.tab-button:hover {
    color: #333;
    background-color: #f8f9fa;
}

.tab-button.active {
    color: #e74c3c;
    border-bottom-color: #e74c3c;
    background-color: #fff;
}

.tab-content-wrapper {
    position: relative;
    min-height: 300px;
}

.tab-content {
    display: none; /* Hide all by default */
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    position: relative; /* Keep it in normal layout flow */
    padding: 20px 0;
}

.tab-content.active {
    display: block; /* Show only the active one */
    opacity: 1;
    visibility: visible;
}

</style>
    <div class="nav-backdrop" id="navBackdrop"></div>

    <div class="container product-detail-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?php echo BASEPATH ?>">Home</a> / <a href="<?php echo BASEPATH ?>product">Shop</a> / <span id="productName"><?php echo $data['info']['product_name'];?></span>
        </div>

        <!-- Product Detail Grid -->
        <div class="product-detail-grid">
            <!-- Product Images -->
            <div class="product-images">
                <div class="main-image" id="mainImage">
                    <img src="<?php echo $data['product_image'] ?>" alt="<?php echo $data['info']['product_name'];?>" id="mainImg">
                </div>
                <div class="thumbnail-images">
                    <?php echo $data['ProductImageList']; ?>
                </div>
            </div>

            <div class="product-info">
                <form action="#" id="addToCart">
                    <input type="hidden" name="session_token" id="session_token" value="<?php echo $data['token'] ?>">
                    <h1 id="productTitle"><?php echo $data['info']['product_name'];?></h1>
                    <div class="product_rating">
                        <ul>
                            <span class="my-rating-9"></span>
                            <span class="live-rating"></span>
                            <?php echo $data['product_review_count']; ?>
                            <li class="review"><a href="#reviews"> (<?php echo $data['overall_review_count'] != 0 ? $data['overall_review_count'] : '' ?> customer reviews ) </a></li>
                        </ul>
                    </div>

                    <div class="product-price">
                        <span class="current-price current_price">Rs.<?php echo $data['inr_format']['selling_price'] ?></span>
                        <span class="original-price old_price">Rs.<?php echo $data['inr_format']['actual_price'] ?></span>
                        <?php if($data['info']['tax_type']=="inclusive" ) { ?>
                            <span class="discount-badge"> Inclusive of all taxes *</span>
                        <?php } else { ?>
                            <span class="discount-badge"> Exclusive of all taxes *</span>
                        <?php } ?>
                    </div>
                    <?php if($data['info']['short_description']!="" ) { ?>
                    <div class="product-description">
                        <p><?php echo $data['info']['short_description'] ?> </p>
                    </div>
                    <?php } ?>

                    <div class="product-options <?php echo (@$data['cart_check']==1) ? 'disabled' : ''; ?>">
                        <?php 
                        if($data['info']['has_variants'] == 1) {
                            echo $data['product_variants']; 
                        }
                        ?>
                    </div>
                    

                    <!-- <div class="quantity-selector">
                        <label class="option-label">Quantity:</label>
                        <div class="quantity-controls">
                            <button class="quantity-btn button_dec qtyminus" type="button" field='quantity'>-</i></button>
                            <input value="1" type="text" name="quantity" id="order_qty" class="quantity-input qty_input" autocomplete="off" />
                            <button class="quantity-btn button_inc qtyplus" type="button" field='quantity'>+</i></button>
                        </div>
                    </div>     -->




                    <div class="product_variant quantity">
                        <?php if(@$_SESSION['user_session_id']) { ?>
                            <?php if(@$data['cart_check']==0) { ?>
                                <div class="quantity-selector">
                                    <label class="option-label">Quantity:</label>
                                    <div class="quantity-controls">
                                        <button class="quantity-btn button_dec qtyminus" type="button" field='quantity'>-</i></button>
                                        <input value="1" type="text" name="quantity" id="order_qty" class="quantity-input qty_input" autocomplete="off" />
                                        <button class="quantity-btn button_inc qtyplus" type="button" field='quantity'>+</i></button>
                                    </div>
                                </div> 
                            <?php } else { ?>
                                <div class="quantity-selector">
                                    <label class="option-label">Quantity:</label>
                                    <div class="quantity-controls">
                                        <input type="hidden" id='cart_item_id' value="<?php echo $data['cart_data']['id'] ?>" >
                                        <button class="quantity-btn button_dec qtyminus" type="button" field='quantity'>-</button>
                                        <input value="<?php echo $data['cart_data']['qty'] ?>" type="text" name="quantity" id="order_qty" class="quantity-input qty_input" autocomplete="off">
                                        <button class="quantity-btn button_inc qtyplus" type="button" field='quantity'>+</button>
                                    </div>
                                    <span class="unit-label"><?php echo  (($data['info']['product_unit']!=0)? $data['product_unit']['product_unit'] : "" );  ?></span>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <label></label>
                        <?php } ?>
                    </div>







                    <div class="action-buttons d-flex flex-wrap gap-2">
                        <?php if(@$_SESSION['user_session_id']) { ?>
                            <?php if(@$data['cart_check']==0) { ?>
                                <button class="btn btn-primary btn-lg add-to-cart-btn" type="submit">
                                    <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                </button>
                            <?php } else { ?>
                                <button class="btn btn-primary btn-lg add-to-cart-btn update_product_qty" type="button">
                                    <i class="fas fa-sync-alt me-2"></i> Update Cart
                                </button>
                            <?php } ?>


                            <!-- <button class="wishlist-btn-custom">
                                <i class="fas fa-heart me-2"></i> Wishlist
                            </button> -->


                            <button class="wishlist-btn-custom addToWishList" data-variant_id="<?php echo $data['variant_id_fw'] ?>" data-id="<?php echo $data['token'] ?>" title="<?php echo $data['wishlist_status'] ?>">
                                <i class="fas fa-heart me-2"></i>
                                <?php echo $data['wishlist_status'] ?>
                            </button>



                            <button class="wishlist-btn-custom" id="openReviewPopup">
                                <i class="fas fa-star me-2"></i> Add Reviews
                            </button>
                        <?php } else { ?>
                            <a href="<?php echo BASEPATH ?>login" class="btn btn-primary btn-lg" style="text-decoration:none;">
                                Login For Add To Cart
                            </a>
                        <?php } ?>
                    </div>

                    <div class="product-features">
                        <div class="feature" onclick="window.location='<?php echo BASEPATH ?>pages/details/payment-policy'">
                            <i class="fas fa-indian-rupee-sign"></i>
                            <span>Payment Policy</span>
                        </div>

                        <div class="feature" onclick="window.location='<?php echo BASEPATH ?>pages/details/return-policy'">
                            <i class="fas fa-undo"></i>
                            <span>Return Policy</span>
                        </div>

                        <div class="feature" onclick="window.location='<?php echo BASEPATH ?>pages/details/delivery-details'">
                            <i class="fas fa-shipping-fast"></i>
                            <span>Delivery Policy</span>
                        </div>

                        <!-- Popup Modal -->
                        <div id="reviewPopup" class="popup">
                        <div class="popup-content">
                            <span class="close" id="closeReviewPopup">&times;</span>
                            <h2>Add Your Review</h2>
                            <form id="reviewForm">
                            <label for="name">Your Name:</label>
                            <input type="text" id="name" name="name" required>

                            <label for="rating">Rating:</label>
                            <select id="rating" name="rating" required>
                                <option value="5">⭐⭐⭐⭐⭐ - Excellent</option>
                                <option value="4">⭐⭐⭐⭐ - Good</option>
                                <option value="3">⭐⭐⭐ - Average</option>
                                <option value="2">⭐⭐ - Poor</option>
                                <option value="1">⭐ - Very Bad</option>
                            </select>

                            <label for="comment">Comment:</label>
                            <textarea id="comment" name="comment" rows="4" required></textarea>

                            <button type="submit" class="submit-btn" style="background-color: #e74c3c;">Submit Review</button>
                            </form>
                        </div>
                        </div>

        

                    </div>
                </form>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="product-tabs">
            <div class="tab-buttons">
                <button class="tab-button active" data-tab="description">Description</button>
                <button class="tab-button" data-tab="size-chart" style="white-space: nowrap; ">Size Chart</button>
                <button class="tab-button" data-tab="reviews">Reviews</button>
            </div>

            <div class="tab-content-wrapper">
                <div class="tab-content active tab-content-custom" id="description">
                    <?php echo $data['info']['description'] ?>
                </div>

                <div class="tab-content" id="size-chart">
                    <?php echo $data['attributes']; ?>
                </div>

                <div class="tab-content" id="reviews">
                <div class="reviews-section">
                    <div class="review-summary">
                        <div class="overall-rating">
                            <div class="rating-number">4.8</div>
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <div>124 Reviews</div>
                        </div>
                        <div class="rating-breakdown">
                            <div class="rating-bar">
                                <span>5 stars</span>
                                <div class="rating-bar-fill">
                                    <div class="rating-bar-inner" style="width: 75%"></div>
                                </div>
                                <span>75%</span>
                            </div>
                            <div class="rating-bar">
                                <span>4 stars</span>
                                <div class="rating-bar-fill">
                                    <div class="rating-bar-inner" style="width: 15%"></div>
                                </div>
                                <span>15%</span>
                            </div>
                            <div class="rating-bar">
                                <span>3 stars</span>
                                <div class="rating-bar-fill">
                                    <div class="rating-bar-inner" style="width: 7%"></div>
                                </div>
                                <span>7%</span>
                            </div>
                            <div class="rating-bar">
                                <span>2 stars</span>
                                <div class="rating-bar-fill">
                                    <div class="rating-bar-inner" style="width: 2%"></div>
                                </div>
                                <span>2%</span>
                            </div>
                            <div class="rating-bar">
                                <span>1 star</span>
                                <div class="rating-bar-fill">
                                    <div class="rating-bar-inner" style="width: 1%"></div>
                                </div>
                                <span>1%</span>
                            </div>
                        </div>
                    </div>

                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-avatar">JD</div>
                            <div class="reviewer-info">
                                <h4>John Doe</h4>
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="review-date">March 15, 2024</div>
                            </div>
                        </div>
                        <div class="review-text">
                            <p>Excellent quality shirt! The fabric is soft and comfortable, and the fit is perfect. I've washed it several times and it still looks brand new. Highly recommend!</p>
                        </div>
                    </div>

                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-avatar">MS</div>
                            <div class="reviewer-info">
                                <h4>Mike Smith</h4>
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <div class="review-date">March 10, 2024</div>
                            </div>
                        </div>
                        <div class="review-text">
                            <p>Great shirt for the price. The material is good quality and the sizing is accurate. Only minor complaint is that it wrinkles a bit more than expected, but overall very satisfied.</p>
                        </div>
                    </div>

                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-avatar">RJ</div>
                            <div class="reviewer-info">
                                <h4>Robert Johnson</h4>
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="review-date">March 5, 2024</div>
                            </div>
                        </div>
                        <div class="review-text">
                            <p>Perfect for both casual and business casual occasions. The fit is modern and flattering. Fast shipping and excellent customer service. Will definitely order more colors!</p>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Unique Products Section -->
    <?php if($data['releated_products']) { ?>
    <section class="unique-products-section">
        <div class="unique-products-container">
            <h2 class="unique-products-title">Related Products</h2>
            <div class="unique-products-grid">
                <?php echo $data['releated_products']; ?>
            </div>
        </div>
    </section>
    <?php } ?>

<!--Frequently Bought Together end-->
<?php require_once 'includes/bottom.php'; ?>
<script type="text/javascript">

    $(".share-product").click(function() {
        $('.popup-sharehire').not($(this).next( ".popup-sharehire" )).each(function(){
            $(this).removeClass("active");
        });     
        $(this).next( ".popup-sharehire" ).toggleClass( "active" );
    });

    // Vriants Management

    $('.variant_change').click(function() {
        var option = $(this).data('option');
        var variant_id = $(this).data('variant');
        var token = $(this).data('token');
        var values = [];

        $('input[name="variant[]"]:checked').each(function() {
            values.push(this.value);
        });

        var trimed = implodeArray(values);
        var token_up = trimed.replace(" ", "-");
        var token = token_up.toLowerCase();
        var url = location.protocol + '//' + location.host + location.pathname;

        if (values[values.length - 1]) {
            window.location = url + "?v=" + token;
        }
    });

    // Update Cart product Qty

    $(".update_product_qty").click(function() {
        var new_qty = $("#order_qty").val();
        var cart_item_id = $("#cart_item_id").val();

         $.ajax({
                type: "POST",
                url: base_path + "cartdetails/api/updateNewPrdQty",
                dataType: "html",
                data: { prd_qty   : new_qty,
                        cart_item : cart_item_id   
                      },
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();

                    if (data == 1) {
                        if ($.urlParam('v')) {
                            var alter_url = "?v=" + $.urlParam('v') + "&s=success";
                        } else {
                            var alter_url = "?qs=success";
                        }
                        window.location = location.protocol + '//' + location.host + location.pathname + alter_url;
                    } else {
                        // swal(data, "", "warning");
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>'+ data +'</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    }
                }
            });
        
    });

    function implodeArray(array) {
        separator = '-';
        implodedArray = '';
        for (let i = 0; i < array.length; i++) {
            implodedArray += array[i];
            if (i != array.length - 1) {
                implodedArray += separator;
            }
        }
        return (implodedArray);
    }

    $('#order_qty').change(function() {
        var max_val = 10; // Default maximum quantity
        var min_val = 1; // Default minimum quantity
        var order_qty = parseInt($("#order_qty").val());

        var cart_check = parseInt(<?php echo $data['cart_check'] ?>);

        if(cart_check) {
            var cart_qty = parseInt(<?php echo ((isset($data['cart_data']['qty']))? $data['cart_data']['qty'] : 0 ) ?>);

            if(order_qty == cart_qty )
            {
                $(".update_product_qty").addClass("display_none");
            } else {
                $(".update_product_qty").removeClass("display_none");
            }
        }

        if (order_qty <= max_val) {
            if (min_val > order_qty) {
                // swal("Minimum Quantity Should Be " + min_val, "", "warning");
                setTimeout(function() {
                    new Noty({
                        text: '<strong>Minimum Quantity Should Be '+ min_val +'</strong>!',
                        type: 'warning',
                        theme: 'relax',
                        layout: 'bottomCenter',
                        timeout: 3000
                    }).show();
                }, 300);
                $('#order_qty').val(min_val);
            }
        } else {
            // swal("Maximum Quantity Should Be " + max_val, "", "warning");
            setTimeout(function() {
                new Noty({
                    text: '<strong>Maximum Quantity Should Be '+ max_val +'</strong>!',
                    type: 'warning',
                    theme: 'relax',
                    layout: 'bottomCenter',
                    timeout: 3000
                }).show();
            }, 300);
            $('#order_qty').val(max_val);
        }
        return false;

    });


    $('.qtyplus').click(function(e) {
        e.preventDefault();

        var max_val = 10; // Default maximum quantity
        var order_qty = parseInt($("#order_qty").val()) + 1;

        var cart_check = parseInt(<?php echo $data['cart_check'] ?>);
    
        if(cart_check) {
            var cart_qty = parseInt(<?php echo ((isset($data['cart_data']['qty']))? $data['cart_data']['qty'] : 0 ) ?>);

            if(order_qty == cart_qty )
            {
                $(".update_product_qty").addClass("display_none");
            } else {
                $(".update_product_qty").removeClass("display_none");
            }
        }

        if (order_qty <= max_val) {
            fieldName = $(this).attr('field');
            var currentVal = parseInt($('input[name=' + fieldName + ']').val());
            if (!isNaN(currentVal)) {
                var value = currentVal + 1;
                $('input[name=' + fieldName + ']').val(currentVal + 1);
                $("#qty").val(value);
            } else {
                $('input[name=' + fieldName + ']').val('1');
                $("#qty").val(1);
            }
        } else {
            setTimeout(function() {
                new Noty({
                    text: '<strong>Maximum Quantity Should Be '+ max_val +'</strong>!',
                    type: 'warning',
                    theme: 'relax',
                    layout: 'bottomCenter',
                    timeout: 3000
                }).show();
            }, 300);
        }
    });


    $(".qtyminus").click(function(e) {
        e.preventDefault();

        var min_val = 1; // Default minimum quantity
        var order_qty = parseInt($("#order_qty").val()) - 1;


        var cart_check = parseInt(<?php echo $data['cart_check'] ?>);

        if(cart_check) {
            var cart_qty = parseInt(<?php echo ((isset($data['cart_data']['qty']))? $data['cart_data']['qty'] : 0 ) ?>);

            if(order_qty == cart_qty )
            {
                $(".update_product_qty").addClass("display_none");
            } else {
                $(".update_product_qty").removeClass("display_none");
            }
        }

        if (order_qty >= min_val) {
            fieldName = $(this).attr('field');
            var currentVal = parseInt($('input[name=' + fieldName + ']').val());
            if (!isNaN(currentVal) && currentVal > 1) {
                var value = currentVal - 1;
                $('input[name=' + fieldName + ']').val(currentVal - 1);
                $("#qty").val(value);
            } else {
                $('input[name=' + fieldName + ']').val('1');
                $("#qty").val(1);
            }
        } else {
            setTimeout(function() {
                new Noty({
                    text: '<strong>Minimum Quantity Should Be '+ min_val +'</strong>!',
                    type: 'warning',
                    theme: 'relax',
                    layout: 'bottomCenter',
                    timeout: 3000
                }).show();
            }, 300);
        }
    });


    $.urlParam = function(name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results == null) {
            return null;
        }
        return decodeURI(results[1]) || 0;
    }

    $('.addToWishList').click(function() {

        var id = $(this).data("id");
        var variant_id = $(this).data("variant_id");

        $.ajax({
            type: "POST",
            url: base_path + "product/api/addToWishList",
            dataType: "html",
            data: {
                product_id: id,
                variant_id: variant_id
            },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                data = data.split("`");
                if (data[0] == 1) {

                    if ($.urlParam('v')) {
                        var alter_url = "?v=" + $.urlParam('v') + "&c=success";
                    } else {
                        var alter_url = "?c=success";
                    }
                    window.location = location.protocol + '//' + location.host + location.pathname + alter_url;
                } else if (data[0] == 2) {
                    window.location = base_path + "login";
                } else {

                    if ($.urlParam('v')) {
                        var alter_url = "?v=" + $.urlParam('v') + "&r=success";
                    } else {
                        var alter_url = "?r=success";
                    }
                    window.location = location.protocol + '//' + location.host + location.pathname + alter_url;
                }
            }
        });
        return false;
    });

    $("#addToCart").validate({
        submitHandler: function(form) {
            var content = $(form).serialize();
            console.log('Form data being sent:', content);
            $.ajax({
                type: "POST",
                url: base_path + "product/api/addToCart",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    data = data.split("`");
                    if (data[0] == 1) {

                        if ($.urlParam('v')) {
                            var alter_url = "?v=" + $.urlParam('v') + "&s=success";
                        } else {
                            var alter_url = "?s=success";
                        }
                        window.location = location.protocol + '//' + location.host + location.pathname + alter_url;
                    } else if (data[0] == 0) {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>' + data[1] + '</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    } else if (data[0] == 2) {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>' + data[1] + '</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    } else {
                        // swal(data[0], "", "warning");
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>' + data[0] + '</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    }
                }
            });
            return false;
        }
    });

    // Add Product  Review 

    $("#addProductReview").validate({
        submitHandler: function(form) {
            var content = $(form).serialize();
            $.ajax({
                type: "POST",
                url: base_path + "product/api/addProductReview",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    data_arr = data.split("`");
                    if (data == 1) {
                        window.location = window.location.href + "?pr=success";
                    } else if (data_arr[0] == 0) {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>' + data_arr[1] + '</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    } else if (data_arr[0] == 2) {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>' + data_arr[1] + '</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    } else {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>' + data + '</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    }

                }
            });
            return false;
        }
    });

    // Edit Product Review

    $('.editProductReview').click(function() {
        var review_id  = $(this).data('review_id');
        var product_id = $(this).data('product_id');
        $("#edit_review_id").val(review_id);
        $("#edit_product_id").val(product_id);

        $.ajax({
            type: "POST",
            url : base_path + "myaccount/api/getReviewInfo",
            data: { 
                    result  : review_id,
                  },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                data = $.parseJSON(data);

                $(".star-rating_edit").starRating({
                    totalStars: 5,
                    starShape: 'rounded',
                    starSize: 20,
                    emptyColor: '#ddd',
                    hoverColor: '#ffc107',
                    activeColor: '#ffc107',
                    initialRating: data['star_ratings'],
                    useGradient: false,
                    useFullStars: true,
                    disableAfterRate: false,
                    onHover: function(currentIndex, currentRating, $el) {
                        //$('.live-rating').text(currentIndex);
                    },
                    onLeave: function(currentIndex, currentRating, $el) {
                        //$('.live-rating').text(currentRating);
                    },
                    callback: function(currentRating, $el) {
                        //alert('rated '+currentRating);
                        if(currentRating) {
                            var currentRating = currentRating;
                        } else {
                            var currentRating = data['star_ratings'];
                        }

                        $("#edit_product_rating_input").val(currentRating);
                    }
                });

                $("#edit_rating_input").val();
                $("textarea#edit_review_comment").val(data['comment']);                
                $("#edit_product_review").modal("show");
            }
        });
        return false;

    });

    $("#editProductReview").validate({
        submitHandler: function(form) {
            var content = $(form).serialize();
            $.ajax({
                type: "POST",
                url: base_path + "product/api/editProductReview",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    data_arr = data.split("`");
                    if (data == 1) {
                        window.location = window.location.href + "?pre=success";
                    } else  {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>' + data + '</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    }
                }
            });
            return false;
        }
    });

</script>

<script type="text/javascript">

    // Add Vendor Ratting btn click
   
    $('.addVendorRattingBtn').click(function() {
        var product_id  = $(this).data('product_id');
        var order_id    = $(this).data('order_id');
        $.ajax({
            type: "POST",
            url: base_path + "product/api/getVendorRattingInfo",
            data: { 
                    product_id : product_id,
                    order_id   : order_id

                  },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                var data = $.parseJSON(data);
                $(".add_vendor_rating_modal_body").html(data['layout']);
                $("#add_rating_product_id").val(product_id);
                $("#add_rating_order_id").val(order_id);
                $("#addVendorRattingModal").modal("show");
                return false;
            }
        });
        return false;
    });

    $('.editVendorRattingBtn').click(function() {
        var product_id  = $(this).data('product_id');
        var order_id    = $(this).data('order_id');
        $.ajax({
            type: "POST",
            url: base_path + "product/api/getVendorRattingInfoFroEdit",
            data: { 
                    product_id : product_id,
                    order_id   : order_id,
                  },
            beforeSend: function() {
                $(".page_loading").show();
            },
            success: function(data) {
                $(".page_loading").hide();
                var data = $.parseJSON(data);
                $(".edit_vendor_rating_modal_body").html(data['layout']);
                $("#edit_rating_product_id").val(product_id);
                $("#edit_rating_order_id").val(order_id);
                $("#editVendorRattingModal").modal("show");
                return false;
            }
        });
        return false;
    });

    // Add vendor Ratting

    $("#addVendorRatting").validate({
        submitHandler: function(form) {
            var content = $(form).serialize();
            $.ajax({
                type: "POST",
                url: base_path + "product/api/addMultiVendorRatting",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    console.log(data);
                    data_arr = data.split("`");
                    if (data == 1) {
                        window.location = window.location.href + "?vr=success";
                    } else  {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>' + data + '</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    }
                }
            });
            return false;
        }
    });

     // Edit vendor Ratting

    $("#editVendorRatting").validate({
        submitHandler: function(form) {
            var content = $(form).serialize();
            $.ajax({
                type: "POST",
                url: base_path + "product/api/editMultiVendorRatting",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    data_arr = data.split("`");
                    if (data == 1) {
                        window.location = window.location.href + "?vru=success";
                    } else  {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>' + data + '</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    }
                }
            });
            return false;
        }
    });


</script>

<script>
    function onChangeHandler({
        target
    }) {

        var elems = target.parentNode.querySelectorAll('input');
        var len = elems.length;
        for (let i = 0; i < len; i++) {
            elems[i].classList.remove('checked');
            elems[i].checked && elems[i].click();
        }
        target.classList.add('checked');
        target.click();

    }

    function init() {
        var elems = document.querySelectorAll("[data-type='variant']");
        elems.forEach(ele => {
            ele.addEventListener('change', onChangeHandler)
        })
    }

    init();
</script>


<!-- star rating script start -->
<script type="text/javascript">
    $(".star-rating").starRating({
        totalStars: 5,
        starShape: 'rounded',
        starSize: 20,
        emptyColor: '#ddd',
        hoverColor: '#ffc107',
        activeColor: '#ffc107',
        initialRating: 5,
        useGradient: false,
        useFullStars: true,
        disableAfterRate: false,
        onHover: function(currentIndex, currentRating, $el) {
            //$('.live-rating').text(currentIndex);
        },
        onLeave: function(currentIndex, currentRating, $el) {
            //$('.live-rating').text(currentRating);
        },
        callback: function(currentRating, $el) {
            //alert('rated '+currentRating);
            $(".rating_input").val(currentRating);
        }
    });

    $(".star-rating_add_review").starRating({
        totalStars: 5,
        starShape: 'rounded',
        starSize: 20,
        emptyColor: '#ddd',
        hoverColor: '#ffc107',
        activeColor: '#ffc107',
        initialRating: 5,
        useGradient: false,
        useFullStars: true,
        disableAfterRate: false,
        onHover: function(currentIndex, currentRating, $el) {
            //$('.live-rating').text(currentIndex);
        },
        onLeave: function(currentIndex, currentRating, $el) {
            //$('.live-rating').text(currentRating);
        },
        callback: function(currentRating, $el) {
            //alert('rated '+currentRating);
            $("#rating_input").val(currentRating);
        }
    });


    $(".yellow-rating").starRating({
        totalStars: 5,
        starShape: 'rounded',
        starSize: 20,
        emptyColor: '#ccc',
        hoverColor: '#ffc107',
        activeColor: '#ffc107',
        useGradient: false,
        readOnly: true
    });

    var setRating = function() {
        Array.from($('.rating_data')).forEach((ele, index) => {
            let star_elem = $(".my-rating-7")[index];

            $(star_elem).starRating({
                readOnly: true,
                totalStars: 5,
                starShape: 'rounded',
                starSize: 20,
                emptyColor: '#ddd',
                hoverColor: '#ffc107',
                activeColor: '#ffc107',
                initialRating: ele.value,
                useGradient: false,
                disableAfterRate: false,
                callback: function(currentRating, $el) {
                    //alert('rated '+currentRating);
                    $("#rating_data").val(currentRating);
                }
            });
        })
    }

    setRating();

    var setRatingCount = function() {
        var tot = $('#total_cot').val();
        $(".my-rating-9").starRating({
            readOnly: true,
            initialRating: $('#total_cot').val(),
            starShape: 'rounded',
            starSize: 20,
            emptyColor: '#ddd',
            hoverColor: '#ffc107',
            activeColor: '#ffc107',
            disableAfterRate: false
        });
        $('.live-rating').text(tot);
    }

    setRatingCount();

     var setRatingCount = function() {

        var vendor     = $(".vendor_rating").data("vendor_id");
        var vendor_cls = ".vendor_rating_" + vendor;
        $(".vendor_rating").starRating({
            readOnly: true,
            initialRating: $(".vendor_rating").data("rating"),
            starShape: 'rounded',
            starSize: 20,
            emptyColor: '#ddd',
            hoverColor: '#ffc107',
            activeColor: '#ffc107',
            disableAfterRate: false
        });
    }

    setRatingCount();

    var setRating = function() {
        Array.from($('.rating_data')).forEach((ele, index) => {
            let star_elem = $(".my-rating-7")[index];

            $(star_elem).starRating({
                readOnly: true,
                totalStars: 5,
                starShape: 'rounded',
                starSize: 20,
                emptyColor: '#ddd',
                hoverColor: '#ffc107',
                activeColor: '#ffc107',
                initialRating: ele.value,
                useGradient: false,
                disableAfterRate: false,
                callback: function(currentRating, $el) {
                    //alert('rated '+currentRating);
                    $("#rating_data").val(currentRating);
                }
            });
        })
    }

    setRating();

</script>
<!-- star rating script end -->

<script>
$(document).ready(function() {
    // Thumbnail image click functionality
    $('.thumbnail').on('click', function() {
        $('.thumbnail').removeClass('active');
        $(this).addClass('active');

        var newImageSrc = $(this).find('img').attr('src');
        var newImageAlt = $(this).find('img').attr('alt');
        
        $('#mainImg').attr('src', newImageSrc);
        $('#mainImg').attr('alt', newImageAlt);
        
        $('#mainImg').hide().fadeIn(300);
    });
    
    $('.thumbnail:first').addClass('active');
    
    // Debug: Check if variant options exist
    console.log('Variant options found:', $('.variant-options .size-option, .variant-options .color-option').length);
    console.log('Variant options HTML:', $('.product-options').html());
    
    // Check if variants should be disabled (item in cart)
    if ($('.product-options').hasClass('disabled')) {
        console.log('Variants are disabled - item is in cart');
        // Disable all variant options
        $('.variant-options .size-option, .variant-options .color-option').off('click').css({
            'pointer-events': 'none',
            'cursor': 'not-allowed'
        });
    }
    
    // Initialize default selected variants
    $('.variant-options .size-option.active, .variant-options .color-option.active').each(function() {
        var optionGroup = $(this).closest('.option-group');
        var variantValue = $(this).data('size') || $(this).data('color');
        var variantId = $(this).data('variant');
        var token = $(this).data('token');
        
        // Remove any existing hidden input for this option group
        optionGroup.find('input[name="variant[]"]').remove();
        
        // Add hidden input for the default selected variant
        optionGroup.append('<input type="hidden" name="variant[]" value="' + token + '" data-variant-id="' + variantId + '" data-display-value="' + variantValue + '">');
        
        console.log('Default variant initialized:', {
            variantValue: variantValue,
            variantId: variantId,
            token: token
        });
    });
    
    // Variant option click functionality - using event delegation for dynamically generated content
    $(document).on('click', '.variant-options .size-option, .variant-options .color-option', function(e) {
        // Check if variant options are disabled (item in cart)
        if ($('.product-options').hasClass('disabled')) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Variant selection disabled - item is in cart');
            return false; // Prevent variant changes when item is in cart
        }
        
        var optionGroup = $(this).closest('.option-group');
        var optionId = $(this).data('option');
        
        // Remove active class from all options in the same group
        optionGroup.find('.size-option, .color-option').removeClass('active');
        
        // Add active class to clicked option
        $(this).addClass('active');
        
        // Update hidden input for form submission
        var variantValue = $(this).data('size') || $(this).data('color');
        var variantId = $(this).data('variant');
        var token = $(this).data('token');
        
        // Remove existing hidden input for this option group
        optionGroup.find('input[name="variant[]"]').remove();
        
        // Add new hidden input with the token value (not the display value)
        optionGroup.append('<input type="hidden" name="variant[]" value="' + token + '" data-variant-id="' + variantId + '" data-display-value="' + variantValue + '">');
        
        // Debug: Log variant selection
        console.log('Variant selected:', {
            optionId: optionId,
            variantId: variantId,
            variantValue: variantValue,
            token: token
        });
        
        // Trigger variant change event for any additional functionality
        $(document).trigger('variantChanged', {
            optionId: optionId,
            variantId: variantId,
            variantValue: variantValue,
            token: token
        });
    });
    
    // Tab functionality
    $('.tab-button').click(function() {
        var targetTab = $(this).data('tab');
        
        // Remove active class from all buttons and content
        $('.tab-button').removeClass('active');
        $('.tab-content').removeClass('active');
        
        // Add active class to clicked button
        $(this).addClass('active');
        
        // Show target content
        $('#' + targetTab).addClass('active');
    });
});
</script>


<?php if (isset($_GET['s'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Product has been added to the cart!</strong>',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);

    if ($.urlParam('v')) {
        history.pushState(null, "", location.href.split("&")[0]);
    } else {
        history.pushState(null, "", location.href.split("?")[0]);
    }
</script>
<?php endif ?>

<?php if (isset($_GET['qs'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Product Quantity Updated Successfully!</strong>',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);

    if ($.urlParam('v')) {
        history.pushState(null, "", location.href.split("&")[0]);
    } else {
        history.pushState(null, "", location.href.split("?")[0]);
    }
</script>
<?php endif ?>

<?php if (isset($_GET['r'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Product has been Removed from wishlist!</strong>',
            type: 'warning',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);

    if ($.urlParam('v')) {
        history.pushState(null, "", location.href.split("&")[0]);
    } else {
        history.pushState(null, "", location.href.split("?")[0]);
    }
</script>
<?php endif ?>

<?php if (isset($_GET['c'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Product has been added to the wishlist!</strong>',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);

    if ($.urlParam('v')) {
        history.pushState(null, "", location.href.split("&")[0]);
    } else {
        history.pushState(null, "", location.href.split("?")[0]);
    }
</script>
<?php endif ?>

<?php if (isset($_GET['pr'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Review Submitted Successfully!</strong>',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>

<?php if (isset($_GET['pre'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Review updated Successfully. Your review will publish soon!</strong>',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>

<?php if (isset($_GET['vr'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Vendor Rating Submitted Successfully!</strong>',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>

<?php if (isset($_GET['vru'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Vendor Rating Updated Successfully!</strong>',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>


<?php if (isset($_GET['cv'])): ?>
<script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>This product have different variants!</strong>',
            type: 'warning',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>