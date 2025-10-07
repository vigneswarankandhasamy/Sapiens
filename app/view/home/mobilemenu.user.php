<?php require_once 'includes/top.php'; ?>

<div class="pb-5 pt-0 d-block d-xl-none d-lg-block">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-9 col-12">
                <div class="align-items-center bg-grey d-flex gap-2 justify-content-center mb-4 p-2 rounded-3">
                    <div><i class="fa fa-user"></i></div>
                    <h5 class="mb-0 text-dark">Antony Abishek</h5>
                </div>
                <div class="parent-element">
                    <div class="mb-2 pb-2 border-b">
                        <div class="mini_cart_wrapper d-flex justify-content-between align-items-center">
                            <a href="javascript:void(0)" class="h-25px">
                                <span class="shopping-cart">
                                    <div class="d-flex gap-2">
                                        <div class="fs-14"><i class="fa fa-cart-plus"></i></div>
                                        <p class="fs-14 mb-0">Your Cart List</p>
                                    </div>
                                </span>
                            </a>
                            <?php if($data['cart']['cart']['total_items']!="0") { ?>
                                <span class="cart_quantity" id="count_cart"><?php echo $data['cart']['cart']['total_items']!="0" ? $data['cart']['cart']['total_items'] : "" ?></span>
                            <?php } ?>
                        </div> 
                    </div>
                    <div class="mb-2 pb-2 border-b">
                        <div class="d-flex gap-2">
                            <div><i class="fa fa-bell"></i></div>
                            <div>
                                <a href="<?php echo BASEPATH ?>myaccount/notification">Notification</a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2 pb-2 border-b">
                        <div class="d-flex gap-2">
                            <div><i class="fa fa-shipping-fast"></i></div>
                            <div>
                                <a href="<?php echo BASEPATH ?>myaccount/myorders">Your Orders</a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2 pb-2 border-b">
                        <div class="d-flex gap-2">
                            <div><i class="fa fa-heart"></i></div>
                            <div>
                                <a href="<?php echo BASEPATH ?>myaccount/wishlist">Your Wishlist</a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2 pb-2 border-b">
                        <div class="d-flex gap-2">
                            <div><i class="fa fa-map-marker-alt"></i></div>
                            <div>
                                <a href="<?php echo BASEPATH ?>myaccount/manageaddress">Your Address</a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2 pb-2 border-b">
                        <div class="d-flex gap-2">
                            <div><i class="fa fa-lock"></i></div>
                            <div>
                                <a href="<?php echo BASEPATH ?>myaccount/edit">Login and Security</a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2 pb-2 border-b">
                        <div class="d-flex gap-2">
                            <div><i class="fa fa-power-off"></i></div>
                            <div>
                                <a href="<?php echo BASEPATH ?>home/logout">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/bottom.php'; ?>