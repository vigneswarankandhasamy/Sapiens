<?php require_once 'includes/top.php'; ?>
<?php if(1!=1) { ?>
<!-- <div class="profile-banner otherpage-banner m-0">
    <img src="<?php echo IMGPATH ?>profile-banner.jpg" alt="">
    <div class="other-banner-title">
        <p>Order Details</p>
        <button type="button" class="btn btn-sm banner-btn rounded-pill"><a href="<?php echo BASEPATH ?>">View more</a></button>
    </div>   
</div>   -->
<?php } ?>
<section class="breadcrumb-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-content">
                   
                </div>
            </div>
        </div>
    </div>
</section>
<section class="order_details_section">
    <div class="container">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="cardbox">
                    <div class="row">
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 m_xs_bottom_30">
                            <h4 class="table_title">Order Information</h4>
                            <?php $shipping=(($data['info']['shipping_cost']=='0') ? 'Free' : 'Rs. '.$data['info']['shipping_cost'] ); ?>
                            <!--order info tables-->
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td>Order Number</td>
                                    <td>
                                        <?php echo $data['info']['order_uid'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Order Date</td>
                                    <td>
                                        <?php echo date("d M Y ", strtotime($data['info']['order_date'])) ?>
                                    </td>
                                </tr>
                               
                                <tr>
                                    <td>Payment Type</td>
                                    <td>
                                        <?php echo ucwords($data['info']['payment_type']) ?>
                                    </td>
                                </tr>

                                 <tr>
                                    <td>Status</td>
                                    <td>
                                        <?php echo ucwords(($data['info']['order_status']==0) ? "Inprocess" : (($data['info']['order_status']==1) ? "Shipped" : "Delivered")) ?>
                                    </td>
                                </tr>

                                 <tr>
                                    <td>Total items</td>
                                    <td>
                                        <?php echo $data['items'] ?>
                                    </td>
                                </tr>
                               
                                
                                <tr>
                                    <td>Total</td>
                                    <td>
                                        Rs.
                                        <?php echo  number_format((float)$data['info']['sub_total']+ (float)$data['info']['total_tax'] + (float)$data['info']['shipping_cost'] - (float)$data['info']['coupon_value'],2)  ?>
                                    </td>
                                </tr>
                                <?php if($data['info']['notes']!="") { ?>
                                    <tr >
                                        <td>
                                            <p>Notes</p>
                                        </td>
                                        <td class="order_details_note_input">
                                            <?php echo $data['info']['notes'] ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div> 
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <h4 class="table_title">Ship To</h4>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td>Name</td>
                                    <td>
                                        <?php echo $data['ship']['user_name'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Mobile</td>
                                    <td>
                                        <?php echo $data['ship']['mobile'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Address </td>
                                    <td>
                                        <?php echo $data['ship']['address'] ?>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>Landmark</td>
                                    <td>
                                        <?php echo $data['ship']['landmark'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>City</td>
                                    <td>
                                        <?php echo $data['ship']['city'] ?>
                                    </td>
                                </tr>
                                 <?php if($data['ship']['gst_name']!="") { ?>
                                <tr>
                                    <td>GST Name</td>
                                    <td>
                                        <?php echo $data['ship']['gst_name'] ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if($data['ship']['gstin_number']!="") { ?>
                                <tr>
                                    <td>GSTIN Number</td>
                                    <td>
                                        <?php echo $data['ship']['gstin_number'] ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                    <div class="divider divider--md"></div>
                    <div class="producr_order_details">
                        <section>
                            <h4 class="table_title">Order Items</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="f_size_large">
                                        <th width="5%">ID</th>
                                        <th width="8%">Product Name</th>
                                        <th width="5%">Price</th>
                                        <th width="5%">Qty</th>
                                        <th width="6%">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $data['order_items'] ?>


                                    <?php

                                        $total_amount_string = str_replace(",", "", $data['info']['total_amount']); 
                                        $totalAmount         = intval($total_amount_string);

                                        $sgst_amt_string = str_replace(",", "", $data['info']['sgst_amt']); 
                                        $sgstAmount         = intval($sgst_amt_string);

                                        $cgst_amt_string = str_replace(",", "", $data['info']['cgst_amt']); 
                                        $cgstAmount         = intval($cgst_amt_string);

                                    ?>
                                    
                                    <tr>
                                        <td colspan="4">
                                            <p>Sub Total </p>
                                        </td>
                                        <td colspan="1" class="color_dark">
                                            <p class="float-end">
                                                <?php echo number_format($data['info']['sub_total']+$data['info']['total_tax'] ,2)  ?>
                                            </p>
                                        </td>
                                    </tr>
                                   <!--  <tr>
                                        <td colspan="4">
                                            <p>Total Tax </p>
                                        </td>
                                        <td colspan="1" class="color_dark">
                                            <p class="float-end">
                                                <?php echo  $data['info']['igst_amt'] ?>
                                            </p>
                                        </td>
                                    </tr> -->
                                     <!-- <tr>
                                        <td colspan="4">
                                            <p>Net Amount</p>
                                        </td>
                                        <td colspan="1" class="color_dark">
                                            <p class="float-end">
                                                <?php echo number_format($data['info']['sub_total'] + $data['info']['igst_amt'],2)  ?>
                                            </p>
                                        </td>
                                    </tr> -->

                                    <tr>
                                        <td colspan="4">
                                            <p>Shipping Charges (shipping charge : ₹ <?php echo number_format($data['info']['shipping_value'],2) ?> + SGST : <?php echo $data['info']['shipping_tax']/2 ?>% (₹ <?php echo number_format($data['info']['shipping_tax_value']/2,2) ?>)+ CGST: <?php echo $data['info']['shipping_tax']/2 ?>% (₹ <?php echo number_format($data['info']['shipping_tax_value']/2,2) ?>))</p>
                                        </td>
                                        <td colspan="1" class="color_dark">
                                            <p class="float-end">
                                                <?php echo number_format($data['info']['shipping_cost'],2) ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <p>Discount <?php if($data['info']['coupon_code']!="") {?>(Coupon Code : <?php echo $data['info']['coupon_code']; ?> Applied) <?php }?></p>
                                        </td>
                                        <td colspan="1" class="color_dark">
                                            <p class="float-end">
                                                <?php echo number_format((float)$data['info']['coupon_value'],2) ?>
                                            </p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="4">
                                            <p>Total (₹)</p>
                                        </td>
                                        <td colspan="1" class="color_dark">
                                            <p class="float-end"><strong>₹</strong>
                                                <?php echo number_format((float)$data['info']['sub_total'] + (float)$data['info']['shipping_cost'] - (float)$data['info']['coupon_value'] + (float)$data['info']['igst_amt'],2)  ?>
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>
                    </div>
                    <div class="order_right">
            <a href="<?php echo BASEPATH ?>" class="button order_more_btn rounded-pill" >Order More</a>
        </div>
                </div>
            </div>
        </div>
        
    </div>
</section>
    <?php require_once 'includes/bottom.php'; ?>
 <?php if (isset($_GET['u'])): ?>
    <script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Thank you for your order with us!</strong>!',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
    </script>
    <?php endif ?>