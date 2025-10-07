<?php require_once 'includes/top.php'; ?> 

    <!-- content @s -->
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title"><?php echo $data['page_title'] ?></h3>
                                    <div class="nk-block-des">
                                        <nav>
                                           <ul class="breadcrumb breadcrumb-arrow">
                                                <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a></li>
                                                <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>orders">Orders</a></li>
                                                <li class="breadcrumb-item active"><?php echo $data['page_title'] ?></li>
                                            </ul>
                                        </nav>
                                    </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="pageMenu">
                                            <ul class="nk-block-tools g-3">

                                               <?php if(1 != 1) { ?>
                                               <!--  <li class="nk-block-tools-opt"><a href="<?php echo COREPATH ?>orders/previewcustomerinvoice/<?php echo $data['cart_info']['id'] ?>" class="btn btn-success" target="_blank"><em class="icon ni ni-file"></em><span>Order Invoice</span></a>
                                                </li> -->
                                               <?php  } ?>
                                               
                                            </ul>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="row gy-5">
                            <div class="col-lg-5">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title title">Customer Information</h5>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="card card-bordered">
                                    <ul class="data-list is-compact">
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Name</div>
                                                <div class="data-value"><?php echo $data['cart_user_info']['name']; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Mobile Number</div>
                                                <div class="data-value"><?php echo $data['cart_user_info']['mobile']; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Email</div>
                                                <div class="data-value"><?php echo $data['cart_user_info']['email']; ?></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                           
                            </div><!-- .col -->
                        </div><!-- .row -->
                    </div>

                    <div class="nk-block">
                        <div class="row gy-5">
                            <div class="col-12">
                                 <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title title">Cart Details</h5>
                                    </div>
                                </div>
                                <div class="card card-bordered">
                                    <div class="card-inner">
                                        <table class="table table-bordered is-compact">
                                            <thead class="details">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product Name</th>
                                                    <th>Price</th>
                                                    <th>Qty</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="details">
                                            <?php echo $data["cart_items"]; ?>
                                                <tr>
                                                    <td class="text-right" colspan="4">Sub Total:</td>
                                                <td class="text-right">₹ <?php echo  number_format($data['cart_total'],2)?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="4">Discount  <?php if($data['cart_info']['coupon_code']!="") {?>(Coupon Code : <?php echo $data['cart_info']['coupon_code']; ?> Applied) <?php }?> :</td>
                                                    <td class="text-right">₹ <?php echo number_format($data['coupon_total'],2);?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="4">Shipment Fee:</td>
                                                    <td class="text-right">₹ <?php echo  $data['inr_format']['shipping_cost'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="4">Shipping Type:</td>
                                                    <td class="text-right">Standard</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="4">Total:</td>
                                                    <td class="text-right">₹  <?php echo number_format($data['cart_total'] + $data['cart_info']['shipping_cost'] - $data['coupon_total'],2)  ?>
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    <!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>