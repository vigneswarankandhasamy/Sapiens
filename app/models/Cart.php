<?php

require_once 'Model.php';
require_once 'app/core/classes/PHPMailerAutoload.php';
//require_once('razorpay-php/Razorpay.php');
//require_once '../app/core/classes/PHPMailerAutoload.php';
//use Razorpay\Api\Api;

class Cart extends Model
{
    
    function cartInfo()
    {
        $today   = date("Y-m-d");
        $user_id = @$_SESSION["user_session_id"];
        $cart_id = @$_SESSION["user_cart_id"];
        $query   = "SELECT COUNT(cart_id) as total_items,SUM(total_amount) as total FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe     = $this->exeQuery($query);
        $list    = mysqli_fetch_array($exe);
        return $list;   
    }

    function addressInfo()
    {
        $today    = date("Y-m-d");
        $user_id  = @$_SESSION["user_session_id"];
        $cart_id  = @$_SESSION["user_cart_id"];
        $query    = "SELECT COUNT(id) as tots FROM ".CUSTOMER_ADDRESS_TBL." WHERE user_id='".$user_id."' AND delete_status='0' ";
        $exe      = $this->exeQuery($query);
        $list     = mysqli_fetch_array($exe);
        return $list;   
    }


    /*--------------------------------------------- 
                    Cart Functions
    ----------------------------------------------*/ 

    // Sum Of Cart - Cart Items Table SUM

    function sumOfCartDetails()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(total_amount) as total FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe     = $this->exeQuery($q);
        $result  = mysqli_fetch_array($exe);
        // return number_format((float)$result['total'], 2, '.', '');
        return $result['total'];
    }

    // Sum Of Cart Subtotal - Cart Items Table SUM

    function sumOfCartSubtotalDetails()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(sub_total) as sub_Total FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe     = $this->exeQuery($q);
        $result  =  mysqli_fetch_array($exe);
        return number_format((float)$result['sub_Total'], 2, '.', '');
        // return $result['total_price'];
    }

     // Sum Of tax total - Cart Items Table SUM

    function sumOfCartTaxSubtotalDetails()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(tax_amt) as sub_Total FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe     = $this->exeQuery($q);
        $result  =  mysqli_fetch_array($exe);
        return number_format((float)$result['sub_Total'], 2, '.', '');
        // return $result['total_price'];
    }


    // Sum Of Cart id - Cart Items Table SUM

    function countOfCartid()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT COUNT(cart_id) as cart FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe     = $this->exeQuery($q);
        $result  =    mysqli_fetch_array($exe);
        return $result['cart'];
    }


    // Sum Of Cart Total Tax - Cart Items Table SUM

    function sumOfCartSGST()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(sgst) as sgst FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe     = $this->exeQuery($q);
        $result  =    mysqli_fetch_array($exe);
        return $result['sgst'];
    }


    function sumOfCartCGST()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(cgst) as cgst FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe     = $this->exeQuery($q);
        $result  =    mysqli_fetch_array($exe);
        return $result['cgst'];
    }


    function sumOfCartIGST()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(igst) as igst FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe     = $this->exeQuery($q);
        $result  =    mysqli_fetch_array($exe);
        return $result['igst'];
    }
    




    function sumOfCartSGSTAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(sgst_amt) as sgst_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return number_format((float)$result['sgst_amt'], 2, '.', '');
        // return $result['sgst_amt'];
    }

    function sumOfCartCGSTAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(cgst_amt) as cgst_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return number_format((float)$result['cgst_amt'], 2, '.', '');
        // return $result['cgst_amt'];
    }

    function sumOfCartIGSTAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(igst_amt) as igst_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        // return number_format((float)$result['igst_amt'], 2, '.', '');
        return $result['igst_amt'];
    }
    function sumOfCouponValue()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(coupon_value) as coupon_value FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        // return number_format((float)$result['igst_amt'], 2, '.', '');
        return $result['coupon_value'];
    }



    function sumOfExpressDelivery()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT COUNT(C.id) as count FROM ".CART_ITEM_TBL." C LEFT JOIN ".PRODUCT_TBL." P ON(P.id=C.product_id) WHERE C.cart_id='".$cart_id."' AND P.express_delivery='yes' ";
        $exe     =  mysql_query($q);
        $result  =    mysqli_fetch_array($exe);
        return number_format((float)$result['count'], 2, '.', '');
        // return $result['count'];
    }

     /*-----------------------------------------
            Commission & Charges total
    ------------------------------------------*/

    function sumOfVendorCommission()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(vendor_commission) as vendor_commission FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_commission'];
    }

    function sumOfVendorCommissionTax()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(vendor_commission_tax) as vendor_commission_tax FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_commission_tax'];
    }

    function sumOfVendorPaymentCharge()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(vendor_payment_charge) as vendor_payment_charge FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_payment_charge'];
    }

    function sumOfVendorPaymentTax()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(vendor_payment_tax) as vendor_payment_tax FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_payment_tax'];
    }

    function sumOfVendorShippingCharge()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(vendor_shipping_charge) as vendor_shipping_charge FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_shipping_charge'];
    }

    function sumOfVendorShippingTax()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(vendor_shipping_tax) as vendor_shipping_tax FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_shipping_tax'];
    }

    function sumOfVendorCommissionAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(vendor_commission_amt) as vendor_commission_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_commission_amt'];
    }

    function sumOfVendorCommissionTaxAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(vendor_commission_tax_amt) as vendor_commission_tax_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_commission_tax_amt'];
    }

    function sumOfVendor_paymentChargeAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(vendor_payment_charge_amt) as vendor_payment_charge_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_payment_charge_amt'];
    }

    function sumOfVendor_paymentTaxAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(vendor_payment_tax_amt) as vendor_payment_tax_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_payment_tax_amt'];
    }

    function sumOfVendorShippingChargeAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(vendor_shipping_charge_amt) as vendor_shipping_charge_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_shipping_charge_amt'];
    }

    function sumOfVendorShippingTaxAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q       = "SELECT SUM(vendor_shipping_tax_amt) as vendor_shipping_tax_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_shipping_tax_amt'];
    }


    function getShippingCost()
    {

        $cart_id            = @$_SESSION['user_cart_id'];

        //total tax cal
        $tax_cal = "SELECT SUM(shipping_value) as shipping_value, SUM(shipping_tax) as shipping_tax, SUM(shipping_tax_value) as shipping_tax_value, SUM(shipping_cost) as shipping_cost FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' ";
        $handel_exe            = $this->exeQuery($tax_cal);
        $handle_list_count     = mysqli_fetch_array($handel_exe);
        

        $result = array();
        $result['shipping_value']       = $handle_list_count['shipping_value'];
        $result['shipping_tax']         = $handle_list_count['shipping_tax'];
        $result['shipping_tax_value']   = $handle_list_count['shipping_tax_value'];
        $result['shipping_cost']        = $handle_list_count['shipping_cost'];
        $result['to']                   =  $get_total_amount;
        $result['c_st']                 = $get_coupon_value['coupon_status'];
        $result['c_va']                 = $get_coupon_value['coupon_value'];
        return $result;
    }


    /*--------------------------------------------
            Ship Address avability check   
    ---------------------------------------------*/

    function getVendorIdsInOrder($cart_id)
    {
        $q = "SELECT vendor_id FROM ".CART_ITEM_TBL." WHERE cart_id ='".$cart_id."' ";

        $exe        = $this->exeQuery($q);
        $list       = mysqli_num_rows($exe);
        $vendor_ids = array();

        if(mysqli_num_rows($exe)) {
            while ($list = mysqli_fetch_assoc($exe)) {
                $vendor_ids[] = $list['vendor_id']; 
            }
        }
        return $vendor_ids;
    }

    function shipAddressCheck($city,$location,$cart_id)
    {   
       
        $vendors = $this->getVendorIdsInOrder($cart_id);
        $run     = count($vendors)-1;

        if($city!=""&&$location!=""){
            $i=0;
            foreach ($vendors as $key => $value) {
                $query       =  "SELECT * FROM  ".VENDOR_TBL." WHERE id='".$value."' ";
                $exe         = $this->exeQuery($query);
                if(mysqli_num_rows($exe) > 0) {
                    while ($row = mysqli_fetch_array($exe)) {
                        $list = $this->editPagePublish($row);
                        $vendor_location_group =  in_array($city, explode(",", $row['delivery_locations']));
                        $vendor_location_area  =  in_array($location,  explode(",", $row['delivery_areas']));

                        if($vendor_location_group)
                        {   
                            if(!$vendor_location_area)
                            {
                                $check_address = array();
                                $query   = "SELECT C.product_id,P.product_name FROM ".CART_ITEM_TBL." C LEFT JOIN ".PRODUCT_TBL." P ON (P.id=C.product_id) WHERE C.cart_id='".$cart_id."' AND  C.vendor_id='".$value."' ";
                                $exe     = $this->exeQuery($query);
                                $row     = mysqli_fetch_array($exe);

                                $check_address['status']  = 0;
                                $check_address['product'] = $row['product_name'];
                                $check_address['vendor']  = $list['company'];
                                return $check_address;

                            }  else {
                                    
                                if($i==$run) {

                                    $check_address            = array();
                                    $check_address['status']  = 1;
                                    $check_address['product'] = 1;
                                    $check_address['vendor']  = 1;
                                    return $check_address;
                                }
                            }

                        } else {
                            
                            $check_address = array();
                            $query   = "SELECT C.product_id,P.product_name FROM ".CART_ITEM_TBL." C LEFT JOIN ".PRODUCT_TBL." P ON (P.id=C.product_id) WHERE C.cart_id='".$cart_id."' AND  C.vendor_id='".$value."' ";
                            $exe     = $this->exeQuery($query);
                            $row     = mysqli_fetch_array($exe);

                            $check_address['status']  = 2;
                            $check_address['product'] = $row['product_name'];
                            $check_address['vendor']  = $list['company'];
                            return $check_address;
                        }
                    
                    }
                }
             $i++;   
            }
        } else {
            $check_address            = array();
            $check_address['status']  = 3;
            $check_address['product'] = 3;
            $check_address['vendor']  = 3;
            return $check_address;

        }


    }



    function userCartInfo()
    { 
        $today = date("Y-m-d");
        $user_id= @$_SESSION["user_session_id"];
        $cart_id= @$_SESSION["user_cart_id"];

        // wishlist Products 

        $check      = $this->check_query(WISHLIST_TBL,"user_id","user_id='".@$_SESSION['user_session_id']."' ");

        // Cart ptoducts

        $query      = "SELECT product_id FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe        = $this->exeQuery($query);
        $list       = mysqli_num_rows($exe);
        $cart_product_ids = array();

        if(mysqli_num_rows($exe)) {
            while ($list = mysqli_fetch_assoc($exe)) {
                $cart_product_ids[] = $list['product_id']; 
            }
        }

        $query      = "SELECT COUNT(cart_id) as total_items,SUM(total_amount) as total FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe        = $this->exeQuery($query);
        $list       = mysqli_fetch_assoc($exe);

        $result                         = array();
        $result['cart']                 = $list;
        $result['cart_product_ids']     = $cart_product_ids;
        $result['cart_layout']          = $this->headercartProducts();
        $result['wishlist']             = $check;
        return $result; 
    }



    function headercartProducts()
    {
        $layout = "";
        $result = array();
        if(isset($_SESSION['user_cart_id'])) {
            $cart_id    = @$_SESSION["user_cart_id"];
            $query      = "SELECT C.id,C.user_id,C.cart_id,C.product_id,C.variant_id,C.price,P.page_url,C.variant_id, C.final_price, C.total_amount, C.qty,P.product_name FROM ".CART_ITEM_TBL." C LEFT JOIN ".PRODUCT_TBL." P ON (P.id=C.product_id) LEFT JOIN ".CART_TBL." CT ON(CT.id='".$cart_id."') WHERE C.cart_id='".$cart_id."' ORDER BY C.created_at DESC ";
            $exe = $this->exeQuery($query);
            $count      = mysqli_num_rows($exe) ;
            if (mysqli_num_rows($exe) > 0) {
                $i = 1;
                while ($row = mysqli_fetch_array($exe)) {
                    $list = $this->editPagePublish($row);
                    if($i <=3){
                        $condition = $list['variant_id']!="0" ? "variant_id='".$list['variant_id']."' " : "product_id='".$list['product_id']."' ORDER BY id ASC";
                        $pic    = ASSETS_PATH."no_img.jpg";

                        $layout .= "
                                <div class='cart-block cart-block-item clearfix'>
                                    <div class='image'>
                                        <a href=".BASEPATH." shop/details/".$list['page_url']."'>
                                        <img class='header_cart_product_width' src='".$pic."' alt='' /></a>
                                    </div>
                                    <div class='title'>
                                        <div><a href='".BASEPATH."shop/details/".$list['page_url']."'><div class='name'>".$list['product_name']."</div></a></div>
                                    </div>
                                    <div class='price'>
                                        <span class='final header_cart_product_price' >₹  ".$this->inrFormat($list['price'],2)."</span>
                                    </div>
                                    <a href='#' class='cartItemRemove' data-variant='".$list['variant_id']."' data-id='".$this->encryptData($list['id'])."'><span class='icon icon-cross icon-delete'></span></a>

                                </div>";
                            $i++;
                    }
                }
            }
            $info = $this->getDetails(CART_TBL,"*","id='".$_SESSION['user_cart_id']."'");
            $result['count']    = $count ;
            $result['layout']   = $layout ;
            $result['total']    = $info['total_amount'];
            $result['coupon']   = $info['coupon_value'];
            $result['shipping'] = $info['shipping_cost'];
            return $result;

        }
    }

    function cartItemVendorStatusCheck($cart_id='')
    {   
       $status = 1 ;
       $q      = "SELECT vendor_id FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' ";
       $exe    = $this->exeQuery($q);
       if(mysqli_num_rows($exe) > 0)
       {
            while ($list = mysqli_fetch_assoc($exe)) {
                $vendor_info = $this->getDetails(VENDOR_TBL,"status","id='".$list['vendor_id']."'");
                if($vendor_info['status']==0) {
                    return 0;
                }
            }
       }

       return $status;
    }

    function cartProducts()
    {
        $layout = "";
        $cart_id = $_SESSION["user_cart_id"];
        $user_id = @$_SESSION["user_session_id"];
        $query = "SELECT C.id,C.user_id,C.tax_price,C.cart_id,C.variant_id,C.vendor_id,C.product_id,C.price,P.max_order_qty,P.min_order_qty,P.tax_type,P.page_url,P.category_type,P.main_category_id,P.sub_category_id,VI.company,C.sgst,C.cgst,C.igst,C.sgst_amt,C.cgst_amt,C.igst_amt,C.final_price,C.sub_total,C.total_tax,C.total_amount,W.fav_status,C.qty,P.product_name,CT.shipping_status,CT.shipping_id,CT.location_group_id,PU.product_unit,V.variant_name,(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image,VP.min_qty,VP.max_qty FROM ".CART_ITEM_TBL." C 
                                LEFT JOIN ".PRODUCT_TBL." P ON (P.id=C.product_id) 
                                LEFT JOIN ".VENDOR_TBL." VI ON (C.vendor_id=VI.id) 
                                LEFT JOIN ".CART_TBL." CT ON(CT.id='".$cart_id."') 
                                LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.variant_id=C.variant_id AND W.vendor_id=C.vendor_id AND W.user_id='".@$_SESSION['user_session_id']."') 
                                LEFT JOIN ".PRODUCT_VARIANTS." V ON (C.variant_id=V.id) 
                                LEFT JOIN ".PRODUCT_UNIT_TBL." PU ON (PU.id=P.product_unit) 
                                LEFT JOIN ".VENDOR_PRODUCTS_TBL." VP ON (VP.product_id=C.product_id AND VP.vendor_id AND VP.variant_id=C.variant_id)
            WHERE C.cart_id='".$cart_id."'  GROUP BY C.id ORDER BY C.created_at DESC  ";
        $exe = $this->exeQuery($query);
        if (mysqli_num_rows($exe) > 0) {
            $i = 1;
            while ($row = mysqli_fetch_array($exe)) {
                $list = $this->editPagePublish($row);

                if($list['category_type']=='main') {
                    $cat_info = $this->getDetails(MAIN_CATEGORY_TBL,"category"," id='".$list['main_category_id']."' ");
                    $cat_name = $cat_info['category'];
                } else {
                    $cat_info = $this->getDetails(SUB_CATEGORY_TBL,"subcategory"," id='".$list['sub_category_id']."' ");
                    $cat_name = $cat_info['subcategory'];
                }

                $product_unit = (($list['product_unit']!="")? "( ".$list['product_unit']." )" : "" );

                $status_txt = (($list['fav_status']==0) ? "Move to wishlist" : "Remove From WishList");
                $status     = (($list['fav_status']!="") ? "favourite_item" : "");

                if(isset($_SESSION['user_session_id'])) {
                    $favourite = "
                         <div class='wish_tag my-3 pe-2'><a href='javascript:void();' class='addToFavourite $status'   data-option='".$this->encryptData($list['product_id'])."' data-id='".$this->encryptData($list['product_id'])."' data-vendor_id='".$list['vendor_id']."' data-variant_id='".$list['variant_id']."'><span><i class='far fa-heart'></i> ".$status_txt."</span></a></div>";
                }else{
                    $favourite = "
                         <div class='wish_tag my-3 pe-2'><a href='".BASEPATH."login'><span><i class='far fa-heart'></i> Move to wishlist</span></a></div>";
                }

                $product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;

                if($list['variant_name']!=""){
                    $name   = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;
                }else{
                    $name   = $list['variant_id']==0 ? $list['product_name'] : $list['product_name'];
                }

                if($list['tax_type']=="inclusive") { 
                    $tax_type       = "<span> Inclusive of all taxes * </span>";
                    $tax_details    = "<span>SGST : ".$list['sgst']." % ( ₹ ".$list['sgst_amt']." )</span><br>
                                       <span>CGSt : ".$list['cgst']." % ( ₹ ".$list['cgst_amt']." )</span>";
                } else { 
                    $tax_type       = "<span> Exclusive of all taxes * </span>";
                    $tax_details    = "<span>SGST : ".$list['sgst']." % ( ₹ ".$list['sgst_amt']." )</span><br>
                                        <span>CGSt : ".$list['cgst']." % ( ₹ ".$list['cgst_amt']." )</span>";
                }

                $vendor_info = $this->getDetails(VENDOR_TBL,"*","id='".$list['vendor_id']."'");

                if($vendor_info['status']=='0') {
                    $vendor_not_available = "<div class='fs-lg text-accent text-danger pt-2'>(*This vendor is not available at this moment. Please remove this item or try to other vendor)</div>";
                } else {
                    $vendor_not_available = "";
                }

                $layout .= "
                            <div class='shopping_list_items my-3'>
                                <div class='d-sm-flex justify-content-between align-items-center'>
                                    <div class='d-block d-sm-flex align-items-center text-center text-sm-start'>
                                    <a class='d-inline-block flex-shrink-0 mx-auto me-sm-4' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$product_image."' width='160' alt='Product'></a>
                                    <div class='pt-2'>
                                        <a href='".BASEPATH."product/details/".$list['page_url']."'><h3 class='h6 mb-2'> ".$name."</h3></a>
                                        ".$this->getProductSubAttributelist($list['product_id'])."
                                        <div class='fs-lg text-accent pt-2'> Category :  ".$cat_name."</div>
                                        <div class='fs-lg text-accent pt-2'> Sold By :  ".$list['company']."</div>
                                        ".$vendor_not_available."
                                        <div class='fs-lg text-accent pt-2'> ₹  ".$this->inrFormat($list['price'],2)."  (".$tax_type.")<br>".$tax_details."</div>
                                        </div> 
                                    </div>
                                  
                                    <div class='pt-2 pt-sm-0 ps-sm-3 mx-auto mx-sm-0 text-center cart_product_grid_with'>
                                        <label class='form-label' for='quantity1'>Quantity ".$product_unit."</label>      
                                        <div class='itemContainer-base-sizeAndQty'>
                                            <div class='itemComponents-base-quantity'>
                                                <input type='hidden' id='qtyincrement_$i' value='1'>    
                                                <button type='button' class='reduced items-count qtyminus decrement'
                                                data-token='".$this->encryptData($list['id'])."' data-option='".$i."'
                                                data-type='minus' data-field=''><i class='fas fa-minus'>&nbsp;</i></button>

                                                <input  type='text' name='quantity' data-old_qty='".$list['qty']."' data-max_order_qty='".$list['max_qty']."' data-min_order_qty='".$list['min_qty']."' class='form-control input-text qty  orderQuantity' id='number_$i' value='".$list['qty']."' data-token='".$this->encryptData($list['id'])."' data-option='".$i."' autocomplete='off' >

                                                <button class='increase items-count qtyplus increment' type='button'
                                                data-token='".$this->encryptData($list['id'])."' data-option='".$i."'
                                                data-type='plus' data-field=''><i class='fas fa-plus'>&nbsp;</i></button>
                
                                            </div>
                                        </div>
                                        <div class='fs-lg text-accent pt-2'>
                                            Rs. ".number_format($list['sub_total'] + $list['total_tax'],2)."
                                        </div>
                                    </div>
                                </div>     
                                <div class='justify-content-start align-items-center d-sm-flex pb-3 border-bottom'>
                                    <a href='#' title='Remove item' class='btn btn-link px-0 text-danger remove-item cartItemRemove' data-id='".$this->encryptData($list['id'])."'><i class='far fa-times-circle me-2'></i><span class='fs-sm'>Remove</span></a>
                                    <span class='pe-2 ps-2 text-muted'>|</span>                        
                             
                                    $favourite

                                </div>
                            </div> ";
                $i++;
            }
        }
        return $layout;
    }


    //Product Attribute List

    function getProductSubAttributelist($product_id)
    {
        $layout = "";
        $q = "SELECT attribute_name,attr_desc,attribute_group_id FROM ".PRODUCT_ATTRIBUTES." WHERE product_id='".$product_id."' AND status='1' " ;
        $exe = $this->exeQuery($q);
        if(mysqli_num_rows($exe)>0){
        $i=1;
            while($row = mysqli_fetch_array($exe)){
                $list = $this->editPagePublish($row);
                $layout .= "
                            <div class='fs-sm'>
                                <span class='text-muted me-2'>".ucwords($list['attribute_name']).":</span>".ucwords($list['attr_desc'])."</div>
                            ";
                $i++;
            }
        }
        return $layout;
    }

    // Update Cart Quantity

    function updateCartQuantity($item_id,$qty)
    {
        $curr           = date("Y-m-d H:i:s");
        $id             = $this->decryptData($item_id);
        $info           = $this->getDetails(CART_ITEM_TBL,"price,tax_price,variant_id,final_price,tax_amt,qty,product_id,size,min_online_amount,vendor_id"," id='".$id."' ");
        $product        = $this->getDetails(PRODUCT_TBL,"*"," id='".$info['product_id']."' ");
        $final_price    = $info['final_price'];
        $new_amount     = $final_price*$qty;
        $final_subtotal = $info['price'];
        $new_subtotal   = $final_subtotal*$qty;
        $tax_info       = $this->getDetails(TAX_CLASSES_TBL,"id,sgst,cgst,igst","id='".$product['tax_class']."' ");
        $tax_price      = $info['price'];
        
        if ($product['tax_type']=="inclusive") {
            $price_sgst_amt             = "0";
            $price_cgst_amt             = "0";
            $price_igst_amt             = "0";
        }else{
            $price_sgst             = 1+($tax_info['sgst']/100);
            $price_cgst             = 1+($tax_info['cgst']/100);
            $price_igst             = 1+($tax_info['igst']/100);
            $price_sgst_amt         = round(($tax_price*$price_sgst)-$tax_price,2);
            $price_cgst_amt         = round(($tax_price*$price_cgst)-$tax_price,2);
            $price_igst_amt         = round(($tax_price*$price_igst)-$tax_price,2);
        }

        $price      = ($info['price']+$price_sgst_amt+$price_cgst_amt);
        $sub_total  = $info['price'] * $qty;
        $tax_total  = $tax_price * $qty;

        if ($product['tax_type']=="inclusive") {
            $sgst           = 1+($tax_info['sgst']/100);
            $cgst           = 1+($tax_info['cgst']/100);
            $igst           = 1+($tax_info['igst']/100);
            $sgst_amt       = round(($tax_total*$sgst)-$tax_total,2);
            $cgst_amt       = round(($tax_total*$cgst)-$tax_total,2);
            $igst_amt       = round(($tax_total*$igst)-$tax_total,2);
        }else{
            $sgst           = 1+($tax_info['sgst']/100);
            $cgst           = 1+($tax_info['cgst']/100);
            $igst           = 1+($tax_info['igst']/100);
            $sgst_amt       = round(($tax_total*$sgst)-$tax_total,2);
            $cgst_amt       = round(($tax_total*$cgst)-$tax_total,2);
            $igst_amt       = round(($tax_total*$igst)-$tax_total,2);
        }

        // Category & Sub-category commission
        if($product['category_type']=='main') {
            $cat_info       = $this->getDetails(MAIN_CATEGORY_TBL,"*","id='".$product['main_category_id']."' ");
            $cat_tax_info   = $this->getDetails(TAX_CLASSES_TBL,"*","id='".$cat_info['vendor_commission_tax']."' ");
        } else {
            $cat_info       = $this->getDetails(SUB_CATEGORY_TBL,"*","id='".$product['sub_category_id']."' ");
            $cat_tax_info   = $this->getDetails(TAX_CLASSES_TBL,"*","id='".$cat_info['vendor_commission_tax']."' ");
        }

       // Category commision charges

        $vd_cm_per            =  1+($cat_info['vendor_commission']/100);
        $vd_cm_amt            =  round(($tax_total*$vd_cm_per)-$tax_total);
        $vd_cm_tax_igst       =  1+($cat_tax_info['igst']/100);
        $vd_cm_tax_amt        =  round(($vd_cm_amt*$vd_cm_tax_igst)-$vd_cm_amt);
        $vendor_commission    =  ($vd_cm_amt+$vd_cm_tax_amt);

        $company_profile_info = $this->getDetails(COMPANNY_INFO,"*","1");

        // Payment Gate Way Charges

        $payment_tax_info       = $this->getDetails(TAX_CLASSES_TBL,"*","id='".$company_profile_info['vendor_payment_tax']."' ");

        $vd_payment_per         =  1+($company_profile_info['vendor_payment_charges']/100);
        $vd_payment_amt         =  round(($tax_total*$vd_payment_per)-$tax_total);
        $vd_payment_igst        =  1+($payment_tax_info['igst']/100);
        $vd_payment_tax_amt     =  round(($vd_payment_amt*$vd_payment_igst)-$vd_payment_amt);
        $vendor_payment_charges =  ($vd_payment_amt+$vd_payment_tax_amt);

        // Shipping Charges

        $shipping_tax_info       = $this->getDetails(TAX_CLASSES_TBL,"*","id='".$company_profile_info['vendor_shipping_tax']."' ");

        $vd_shipping_per         =  1+($company_profile_info['vendor_shipping_charges']/100);
        $vd_shipping_amt         =  round(($tax_total*$vd_shipping_per)-$tax_total);
        $vd_shipping_igst        =  1+($shipping_tax_info['igst']/100);
        $vd_shipping_tax_amt     =  round(($vd_shipping_amt*$vd_shipping_igst)-$vd_shipping_amt);
        $vendor_shipping_charges =  ($vd_shipping_amt+$vd_shipping_tax_amt);

        // Simplified without vendor logic
        $min_qty = 1; // Default minimum quantity
        $max_qty = 10; // Default maximum quantity
        $stock = $product['stock']; // Get actual stock from product table

        $igst_amt = $sgst_amt + $cgst_amt;

        if ($product['tax_type']=="inclusive") {
            $tax_amt = $tax_total - $igst_amt;
        } else {
            $tax_amt = $tax_total ;
        }

         if ($qty>=$min_qty) {

            if ($qty<=$max_qty) {

                if ($stock>=$qty) {
                
                    // Get current cart quantity to calculate stock difference
                    $current_qty = $info['qty'];
                    $qty_difference = $qty - $current_qty;
                    $new_stock = $stock - $qty_difference;
                    
                    // Update product stock
                    $stock_update = "UPDATE ".PRODUCT_TBL." SET stock = '".$new_stock."' WHERE id = '".$info['product_id']."'";
                    $this->exeQuery($stock_update);
                
                    $q = "UPDATE ".CART_ITEM_TBL." SET      
                        qty                         = '".$qty."',
                        sub_total                   = '".$tax_amt."', 
                        total_tax                   = '".$igst_amt."',
                        final_price                 = '".$tax_total."',
                        total_amount                = '".$tax_total."',
                        sgst                        = '".$tax_info['sgst']."',
                        cgst                        = '".$tax_info['cgst']."',
                        igst                        = '".$tax_info['igst']."',
                        sgst_amt                    = '".$sgst_amt."',
                        cgst_amt                    = '".$cgst_amt."',
                        igst_amt                    = '".$igst_amt."',
                        tax_amt                     = '".$tax_amt."',   
                        vendor_commission           = '".$cat_info['vendor_commission']."',
                        vendor_commission_tax       = '".$cat_tax_info['igst']."',
                        vendor_payment_charge       = '".$company_profile_info['vendor_payment_charges']."',
                        vendor_payment_tax          = '".$payment_tax_info['igst']."',
                        vendor_shipping_charge      = '".$company_profile_info['vendor_shipping_charges']."',
                        vendor_shipping_tax         = '".$shipping_tax_info['igst']."',
                        vendor_commission_amt       = '".$vendor_commission."',
                        vendor_commission_tax_amt   = '".$vd_payment_tax_amt."',
                        vendor_payment_charge_amt   = '".$vendor_payment_charges."',
                        vendor_payment_tax_amt      = '".$vd_payment_tax_amt."', 
                        vendor_shipping_charge_amt  = '".$vendor_shipping_charges."',
                        vendor_shipping_tax_amt     = '".$vd_shipping_tax_amt."',
                        updated_at                  = '".$curr."' WHERE id ='".$id."' AND  cart_id = '".$_SESSION["user_cart_id"]."' ";
                    $exe = $this->exeQuery($q);
                    if ($exe) {
                        $new_cart_total     = $this->sumOfCartDetails();
                        $new_cart_subtotal  = $this->sumOfCartTaxSubtotalDetails();
                        // $shipping_info       = $this->getDetails(SHIPPING_COST,"min_order_value,shipping_cost"," id='1' ");
                        $final_total        = $new_cart_total;



                        // Calculate Item Shipping Cost
                        $cart_item_details  = $this->getDetails(CART_ITEM_TBL,"*","id='".$id."'");
                        $cart_id            = $cart_item_details['cart_id'];
                        $vendor_id          = $cart_item_details['vendor_id'];
                        $curr               = date("Y-m-d H:i:s");

                        if($cart_item_details['shipping_status']==1){
                            $updated_cartitem = "SELECT SUM(tax_amt) as sub_total FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' AND vendor_id='".$vendor_id."' AND shipping_status='1' ";
                            $updated_cartitem_exe       = $this->exeQuery($updated_cartitem);
                            $updated_cartitem_exelist   = mysqli_fetch_assoc($updated_cartitem_exe);

                            $updated_cartitem_ct = "SELECT COUNT(vendor_id) as vendor_item_count FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' AND vendor_id='".$vendor_id."' AND shipping_status='1' AND handling='0' ";
                            $updated_cartitem_exe_ct       = $this->exeQuery($updated_cartitem_ct);
                            $updated_cartitem_exelist_ct   = mysqli_fetch_assoc($updated_cartitem_exe_ct);

                            $item_shipping_details   = $this->getDetails(COMPANNY_INFO,"*","id='1'");

                            $item_tax_info           = $this->getDetails(TAX_CLASSES_TBL,"igst","id='".$item_shipping_details['order_shipping_tax']."' ");

                            if($item_shipping_details['maximum_order_value']<=$updated_cartitem_exelist['sub_total']){
                                $item_shipping_value     = "0";
                                $item_shipping_tax       = "0"; 
                                $item_shipping_tax_value = "0";
                                $item_shipping_cost      = "0";
                            }else{
                                
                                $item_shipping_tax       = $item_tax_info['igst'];
                                $item_shipping_value     = $item_shipping_details['single_vendor_shipping_cost']/$updated_cartitem_exelist_ct['vendor_item_count'];
                                 
                                $item_shipping_tax_cal   = 1+($item_shipping_tax/100);
                                $item_shipping_tax_amt   = round(($item_shipping_value*$item_shipping_tax_cal)-$item_shipping_value,2);

                                $item_shipping_tax_value = $item_shipping_value-$item_shipping_tax_amt;

                                $item_shipping_cost      = $item_shipping_value;

                                $item_shipping_tax_val   = $item_shipping_value-$item_shipping_tax_value;
                            }

                            $is_qu = "UPDATE ".CART_ITEM_TBL." SET 
                            shipping_value              = '".$item_shipping_tax_value."',
                            shipping_tax                = '".$item_shipping_tax."',
                            shipping_tax_value          = '".$item_shipping_tax_val."',
                            shipping_cost               = '".$item_shipping_cost."',
                            updated_at                  = '".$curr."' WHERE cart_id='".$cart_id."' AND vendor_id='".$vendor_id."' AND shipping_status='1' AND handling='0' ";
                            $is_result  = $this->exeQuery($is_qu);
                        }
                        // Calculate Item Shipping Cost End


                        // Calculate Shipping Cost
                        $shipping_cost = $this->getShippingCost();

                        $update_q = "UPDATE ".CART_TBL." SET 
                            sub_total                   = '".$new_cart_subtotal."',
                            total_amount                = '".$final_total."',
                            shipping_value              = '".$shipping_cost['shipping_value']."',
                            shipping_tax                = '".$shipping_cost['shipping_tax']."',
                            shipping_tax_value          = '".$shipping_cost['shipping_tax_value']."',
                            shipping_cost               = '".$shipping_cost['shipping_cost']."',
                            sgst                        = '".$this->sumOfCartSGST()."',
                            cgst                        = '".$this->sumOfCartCGST()."',
                            igst                        = '".$this->sumOfCartIGST()."',
                            sgst_amt                    = '".$this->sumOfCartSGSTAmt()."',
                            cgst_amt                    = '".$this->sumOfCartCGSTAmt()."',
                            igst_amt                    = '".$this->sumOfCartIGSTAmt()."',
                            total_tax                   = '".$this->sumOfCartIGSTAmt()."',
                            vendor_commission           = '".$this->sumOfVendorCommission()."',
                            vendor_commission_tax       = '".$this->sumOfVendorCommissionTax()."',
                            vendor_payment_charge       = '".$this->sumOfVendorPaymentCharge()."',
                            vendor_payment_tax          = '".$this->sumOfVendorPaymentTax()."',
                            vendor_shipping_charge      = '".$this->sumOfVendorShippingCharge()."',
                            vendor_shipping_tax         = '".$this->sumOfVendorShippingTax()."',
                            vendor_commission_amt       = '".$this->sumOfVendorCommissionAmt()."',
                            vendor_commission_tax_amt   = '".$this->sumOfVendorCommissionTaxAmt()."',
                            vendor_payment_charge_amt   = '".$this->sumOfVendor_paymentChargeAmt()."',
                            vendor_payment_tax_amt      = '".$this->sumOfVendor_paymentTaxAmt()."', 
                            vendor_shipping_charge_amt  = '".$this->sumOfVendorShippingChargeAmt()."',
                            vendor_shipping_tax_amt     = '".$this->sumOfVendorShippingTaxAmt()."',
                            updated_at                  = '".$curr."' WHERE id = '".$_SESSION["user_cart_id"]."' ";
                        $update_exe     = $this->exeQuery($update_q);
                        $cart_info      = $this->getDetails(CART_TBL,"coupon_code","id='".$_SESSION["user_cart_id"]."' ");
                        
                        if($cart_info['coupon_code']!=NULL) {
                            $update_coupon = $this->updateCartCoupon($cart_info['coupon_code']);
                            if(!$update_coupon) {
                                $this->removeCoupon();
                            }    
                        }
                        
                        return 1;
                    }

                }else{
                    return "The current Item have only " .$stock. " quantity available";
                }

            }else{
                return  "Maximum Quantity Should Be ".$max_qty;
            }

        } else {
            return  "Minimum Quantity Should Be ".$min_qty;
        }
        // }else{
        //  return "The current Item have only " .$product['stock']. " quantity";
        // }
            
    }

    // Update Cart Quantity

    function decrementCartQuantity($item_id,$qty)
    {
        $curr               = date("Y-m-d H:i:s");
        $id                 = $this->decryptData($item_id);
        $info               = $this->getDetails(CART_ITEM_TBL,"price,tax_price,variant_id,final_price,tax_amt,qty,product_id,size,min_online_amount,vendor_id"," id='".$id."' ");
        $product            = $this->getDetails(PRODUCT_TBL,"*"," id='".$info['product_id']."' ");
        $final_price        = $info['final_price'];
        $new_amount         = $final_price*$qty;
        $final_subtotal     = $info['price'];
        $new_subtotal       = $final_subtotal*$qty;
        $final_totaltax     = $info['tax_amt'];
        $new_totaltax       = $final_totaltax*$qty;
        $tax_price          = $info['price'];
        $tax_info           = $this->getDetails(TAX_CLASSES_TBL,"id,sgst,cgst,igst","id='".$product['tax_class']."' ");
    
        if ($product['tax_type']=="inclusive") {
            $price_sgst_amt         = "0";
            $price_cgst_amt         = "0";
            $price_igst_amt         = "0";
        }else{
            $price_sgst             = 1+($tax_info['sgst']/100);
            $price_cgst             = 1+($tax_info['cgst']/100);
            $price_igst             = 1+($tax_info['igst']/100);
            $price_sgst_amt         = round(($tax_price*$price_sgst)-$tax_price,2);
            $price_cgst_amt         = round(($tax_price*$price_cgst)-$tax_price,2);
            $price_igst_amt         = round(($tax_price*$price_igst)-$tax_price,2);
        }

        $price      = ($info['price']+$price_sgst_amt+$price_cgst_amt);
        $sub_total  = $info['price'] * $qty;
        $tax_total  = $tax_price * $qty;

        if ($product['tax_type']=="inclusive") {
            $sgst           = 1+($tax_info['sgst']/100);
            $cgst           = 1+($tax_info['cgst']/100);
            $igst           = 1+($tax_info['igst']/100);
            $sgst_amt       = round(($tax_total*$sgst)-$tax_total,2);
            $cgst_amt       = round(($tax_total*$cgst)-$tax_total,2);
            $igst_amt       = round(($tax_total*$igst)-$tax_total,2);
            
        }else{
            $sgst           = 1+($tax_info['sgst']/100);
            $cgst           = 1+($tax_info['cgst']/100);
            $igst           = 1+($tax_info['igst']/100);
            $sgst_amt       = round(($tax_total*$sgst)-$tax_total,2);
            $cgst_amt       = round(($tax_total*$cgst)-$tax_total,2);
            $igst_amt       = round(($tax_total*$igst)-$tax_total,2);
        }

        // Category & Sub-category commission
        if($product['category_type']=='main') {
            $cat_info       = $this->getDetails(MAIN_CATEGORY_TBL,"*","id='".$product['main_category_id']."' ");
            $cat_tax_info   = $this->getDetails(TAX_CLASSES_TBL,"*","id='".$cat_info['vendor_commission_tax']."' ");
        } else {
            $cat_info       = $this->getDetails(SUB_CATEGORY_TBL,"*","id='".$product['sub_category_id']."' ");
            $cat_tax_info   = $this->getDetails(TAX_CLASSES_TBL,"*","id='".$cat_info['vendor_commission_tax']."' ");
        }

        // Category commision charges

        $vd_cm_per            =  1+($cat_info['vendor_commission']/100);
        $vd_cm_amt            =  round(($tax_total*$vd_cm_per)-$tax_total);
        $vd_cm_tax_igst       =  1+($cat_tax_info['igst']/100);
        $vd_cm_tax_amt        =  round(($vd_cm_amt*$vd_cm_tax_igst)-$vd_cm_amt);
        $vendor_commission    =  ($vd_cm_amt+$vd_cm_tax_amt);

        $company_profile_info = $this->getDetails(COMPANNY_INFO,"*","1");

        // Payment Gate Way Charges

        $payment_tax_info       = $this->getDetails(TAX_CLASSES_TBL,"*","id='".$company_profile_info['vendor_payment_tax']."' ");

        $vd_payment_per         =  1+($company_profile_info['vendor_payment_charges']/100);
        $vd_payment_amt         =  round(($tax_total*$vd_payment_per)-$tax_total);
        $vd_payment_igst        =  1+($payment_tax_info['igst']/100);
        $vd_payment_tax_amt     =  round(($vd_payment_amt*$vd_payment_igst)-$vd_payment_amt);
        $vendor_payment_charges =  ($vd_payment_amt+$vd_payment_tax_amt);

        // Shipping Charges

        $shipping_tax_info       = $this->getDetails(TAX_CLASSES_TBL,"*","id='".$company_profile_info['vendor_shipping_tax']."' ");

        $vd_shipping_per         =  1+($company_profile_info['vendor_shipping_charges']/100);
        $vd_shipping_amt         =  round(($tax_total*$vd_shipping_per)-$tax_total);
        $vd_shipping_igst        =  1+($shipping_tax_info['igst']/100);
        $vd_shipping_tax_amt     =  round(($vd_shipping_amt*$vd_shipping_igst)-$vd_shipping_amt);
        $vendor_shipping_charges =  ($vd_shipping_amt+$vd_shipping_tax_amt);


        $vendor_variant = $this->getDetails(VENDOR_PRODUCTS_TBL,"*", " product_id='".$info['product_id']."' AND variant_id='".$info['variant_id']."' AND vendor_id='".$info['vendor_id']."' ");

        $igst_amt = $sgst_amt + $cgst_amt;

        if ($product['tax_type']=="inclusive") {
            $tax_amt = $tax_total - $igst_amt;
        } else {
            $tax_amt = $tax_total ;
        }


        if ($qty>=$vendor_variant['min_qty']) {

            if ($qty<=$vendor_variant['max_qty']) {

                if ($vendor_variant['stock']>=$qty) {

                    $q = "UPDATE ".CART_ITEM_TBL." SET      
                            qty                         = '".$qty."',
                            sub_total                   = '".$tax_amt."',
                            total_tax                   = '".$igst_amt."',
                            total_amount                = '".$sub_total."',
                            final_price                 = '".$sub_total."',
                            tax_amt                     = '".$tax_amt."',    
                            sgst                        = '".$tax_info['sgst']."',
                            cgst                        = '".$tax_info['cgst']."',
                            igst                        = '".$tax_info['igst']."',
                            vendor_commission           = '".$cat_info['vendor_commission']."',
                            vendor_commission_tax       = '".$cat_tax_info['igst']."',
                            vendor_payment_charge       = '".$company_profile_info['vendor_payment_charges']."',
                            vendor_payment_tax          = '".$payment_tax_info['igst']."',
                            vendor_shipping_charge      = '".$company_profile_info['vendor_shipping_charges']."',
                            vendor_shipping_tax         = '".$shipping_tax_info['igst']."',
                            vendor_commission_amt       = '".$vendor_commission."',
                            vendor_commission_tax_amt   = '".$vd_payment_tax_amt."',
                            vendor_payment_charge_amt   = '".$vendor_payment_charges."',
                            vendor_payment_tax_amt      = '".$vd_payment_tax_amt."', 
                            vendor_shipping_charge_amt  = '".$vendor_shipping_charges."',
                            vendor_shipping_tax_amt     = '".$vd_shipping_tax_amt."',
                            sgst_amt                    = '".$sgst_amt."',
                            cgst_amt                    = '".$cgst_amt."',
                            igst_amt                    = '".$igst_amt."',
                            updated_at                  = '".$curr."' WHERE id ='".$id."' AND  cart_id = '".$_SESSION["user_cart_id"]."' ";
                    $exe = $this->exeQuery($q);
                    if ($exe) {
                        $new_cart_total     = $this->sumOfCartDetails();
                        $new_cart_subtotal  = $this->sumOfCartTaxSubtotalDetails();
                        $final_total        = $new_cart_total;



                        // Calculate Item Shipping Cost
                        $cart_item_details  = $this->getDetails(CART_ITEM_TBL,"*","id='".$id."'");
                        $cart_id            = $cart_item_details['cart_id'];
                        $vendor_id          = $cart_item_details['vendor_id'];
                        $curr               = date("Y-m-d H:i:s");

                        if($cart_item_details['shipping_status']==1){
                            $updated_cartitem = "SELECT SUM(tax_amt) as sub_total FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' AND vendor_id='".$vendor_id."' AND shipping_status='1' ";
                            $updated_cartitem_exe       = $this->exeQuery($updated_cartitem);
                            $updated_cartitem_exelist   = mysqli_fetch_assoc($updated_cartitem_exe);

                            $updated_cartitem_ct = "SELECT COUNT(vendor_id) as vendor_item_count FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' AND vendor_id='".$vendor_id."' AND shipping_status='1' AND handling='0' ";
                            $updated_cartitem_exe_ct       = $this->exeQuery($updated_cartitem_ct);
                            $updated_cartitem_exelist_ct   = mysqli_fetch_assoc($updated_cartitem_exe_ct);

                            $item_shipping_details   = $this->getDetails(COMPANNY_INFO,"*","id='1'");

                            $item_tax_info           = $this->getDetails(TAX_CLASSES_TBL,"igst","id='".$item_shipping_details['order_shipping_tax']."' ");

                            if($item_shipping_details['maximum_order_value']<=$updated_cartitem_exelist['sub_total']){
                                $item_shipping_value     = "0";
                                $item_shipping_tax       = "0"; 
                                $item_shipping_tax_value = "0";
                                $item_shipping_cost      = "0";
                            }else{
                                
                                $item_shipping_tax       = $item_tax_info['igst'];
                                $item_shipping_value     = $item_shipping_details['single_vendor_shipping_cost']/$updated_cartitem_exelist_ct['vendor_item_count'];
                                 
                                $item_shipping_tax_cal   = 1+($item_shipping_tax/100);
                                $item_shipping_tax_amt   = round(($item_shipping_value*$item_shipping_tax_cal)-$item_shipping_value,2);

                                $item_shipping_tax_value = $item_shipping_value-$item_shipping_tax_amt;

                                $item_shipping_cost      = $item_shipping_value;

                                $item_shipping_tax_val   = $item_shipping_value-$item_shipping_tax_value;
                            }

                            $is_qu = "UPDATE ".CART_ITEM_TBL." SET 
                            shipping_value              = '".$item_shipping_tax_value."',
                            shipping_tax                = '".$item_shipping_tax."',
                            shipping_tax_value          = '".$item_shipping_tax_val."',
                            shipping_cost               = '".$item_shipping_cost."',
                            updated_at                  = '".$curr."' WHERE cart_id='".$cart_id."' AND vendor_id='".$vendor_id."' AND shipping_status='1' AND handling='0' ";
                            $is_result  = $this->exeQuery($is_qu);
                        }
                        // Calculate Item Shipping Cost End




                        // Calculate Shipping Cost
                        $shipping_cost = $this->getShippingCost();

                        $update_q = "UPDATE ".CART_TBL." SET 
                            sub_total                   = '".$new_cart_subtotal."',
                            total_amount                = '".$final_total."',
                            shipping_value              = '".$shipping_cost['shipping_value']."',
                            shipping_tax                = '".$shipping_cost['shipping_tax']."',
                            shipping_tax_value          = '".$shipping_cost['shipping_tax_value']."',
                            shipping_cost               = '".$shipping_cost['shipping_cost']."',
                            sgst                        = '".$this->sumOfCartSGST()."',
                            cgst                        = '".$this->sumOfCartCGST()."',
                            igst                        = '".$this->sumOfCartIGST()."',
                            total_tax                   = '".$this->sumOfCartIGSTAmt()."',
                            sgst_amt                    = '".$this->sumOfCartSGSTAmt()."',
                            cgst_amt                    = '".$this->sumOfCartCGSTAmt()."',
                            igst_amt                    = '".$this->sumOfCartIGSTAmt()."',
                            vendor_commission           = '".$this->sumOfVendorCommission()."',
                            vendor_commission_tax       = '".$this->sumOfVendorCommissionTax()."',
                            vendor_payment_charge       = '".$this->sumOfVendorPaymentCharge()."',
                            vendor_payment_tax          = '".$this->sumOfVendorPaymentTax()."',
                            vendor_shipping_charge      = '".$this->sumOfVendorShippingCharge()."',
                            vendor_shipping_tax         = '".$this->sumOfVendorShippingTax()."',
                            vendor_commission_amt       = '".$this->sumOfVendorCommissionAmt()."',
                            vendor_commission_tax_amt   = '".$this->sumOfVendorCommissionTaxAmt()."',
                            vendor_payment_charge_amt   = '".$this->sumOfVendor_paymentChargeAmt()."',
                            vendor_payment_tax_amt      = '".$this->sumOfVendor_paymentTaxAmt()."', 
                            vendor_shipping_charge_amt  = '".$this->sumOfVendorShippingChargeAmt()."',
                            vendor_shipping_tax_amt     = '".$this->sumOfVendorShippingTaxAmt()."',
                            updated_at                  = '".$curr."' WHERE id = '".$_SESSION["user_cart_id"]."' ";
                        $update_exe    = $this->exeQuery($update_q);
                        $cart_info     = $this->getDetails(CART_TBL,"coupon_code","id='".$_SESSION["user_cart_id"]."' ");
                       
                        if($cart_info['coupon_code']!=NULL) {
                            $update_coupon = $this->updateCartCoupon($cart_info['coupon_code']); 
                             if(!$update_coupon) {
                                $this->removeCoupon();
                            }   
                        }
                        
                        return 1;
                        //return "1`".$new_amount."`".$new_cart_total."`".$new_cart_subtotal;
                    }
                 }else{
                    return "The current Item have only " .$vendor_variant['stock']. " quantity So, Choose Other Vendors";
                }

            }else{
                return  "Maximum Quantity Should Be ".$vendor_variant['max_qty'];
            }

        }else{
            return  "Minimum Quantity Should Be ".$vendor_variant['min_qty'];
        }       
        
    }

    // Remove From Cart

    function cartItemRemove($item='')
    {       
        $id = $this->decryptData($item);

        $cart_item_details  = $this->getDetails(CART_ITEM_TBL,"*","id='".$id."'");
        $cart_id            = $cart_item_details['cart_id'];
        $vendor_id          = $cart_item_details['vendor_id'];
        $curr               = date("Y-m-d H:i:s");


        // Calculate Item Shipping Cost
            if($cart_item_details['shipping_status']==1){
                $updated_cartitem = "SELECT SUM(tax_amt) as sub_total FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' AND vendor_id='".$vendor_id."' AND shipping_status='1' AND id!='".$id."' ";
                $updated_cartitem_exe       = $this->exeQuery($updated_cartitem);
                $updated_cartitem_exelist   = mysqli_fetch_assoc($updated_cartitem_exe);

                $updated_cartitem_ct = "SELECT COUNT(vendor_id) as vendor_item_count FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' AND vendor_id='".$vendor_id."' AND shipping_status='1' AND handling='0' AND id!='".$id."' ";
                $updated_cartitem_exe_ct       = $this->exeQuery($updated_cartitem_ct);
                $updated_cartitem_exelist_ct   = mysqli_fetch_assoc($updated_cartitem_exe_ct);

                $item_shipping_details   = $this->getDetails(COMPANNY_INFO,"*","id='1'");

                $item_tax_info           = $this->getDetails(TAX_CLASSES_TBL,"igst","id='".$item_shipping_details['order_shipping_tax']."' ");

                if($item_shipping_details['maximum_order_value']<=$updated_cartitem_exelist['sub_total']){
                    $item_shipping_value     = "0";
                    $item_shipping_tax       = "0"; 
                    $item_shipping_tax_value = "0";
                    $item_shipping_cost      = "0";
                }else{
                    
                    $item_shipping_tax       = $item_tax_info['igst'];
                    $item_shipping_value     = $item_shipping_details['single_vendor_shipping_cost']/$updated_cartitem_exelist_ct['vendor_item_count'];
                     
                    $item_shipping_tax_cal   = 1+($item_shipping_tax/100);
                    $item_shipping_tax_amt   = round(($item_shipping_value*$item_shipping_tax_cal)-$item_shipping_value,2);

                    $item_shipping_tax_value = $item_shipping_value-$item_shipping_tax_amt;

                    $item_shipping_cost      = $item_shipping_value;

                    $item_shipping_tax_val   = $item_shipping_value-$item_shipping_tax_value;
                }

                $is_qu = "UPDATE ".CART_ITEM_TBL." SET 
                shipping_value              = '".$item_shipping_tax_value."',
                shipping_tax                = '".$item_shipping_tax."',
                shipping_tax_value          = '".$item_shipping_tax_val."',
                shipping_cost               = '".$item_shipping_cost."',
                updated_at                  = '".$curr."' WHERE cart_id='".$cart_id."' AND vendor_id='".$vendor_id."' AND shipping_status='1' AND handling='0' AND id!='".$id."' ";
                $is_result  = $this->exeQuery($is_qu);
            }
            // Calculate Item Shipping Cost End


        $q  = "DELETE FROM ".CART_ITEM_TBL." WHERE id='".$id."' ";
        $exe = $this->exeQuery($q);
        if ($exe) {

            $final_total = $this->sumOfCartDetails();
            $curr        = date("Y-m-d H:i:s");
            $order       = $this->getDetails(CART_TBL,"id,coupon_id,coupon_value,coupon_code,coupon_status"," id='".$_SESSION["user_cart_id"]."' ");

            // Calculate Shipping Cost
            $shipping_cost = $this->getShippingCost();

            $update_cart = "UPDATE  ".CART_TBL." SET 
                sub_total                   = '".$this->sumOfCartSubtotalDetails()."',
                total_tax                   = '',
                sgst_amt                    = '".$this->sumOfCartSGSTAmt()."',
                cgst_amt                    = '".$this->sumOfCartCGSTAmt()."',
                igst_amt                    = '".$this->sumOfCartIGSTAmt()."',
                total_tax                   = '".$this->sumOfCartIGSTAmt()."',
                vendor_commission           = '".$this->sumOfVendorCommission()."',
                vendor_commission_tax       = '".$this->sumOfVendorCommissionTax()."',
                vendor_payment_charge       = '".$this->sumOfVendorPaymentCharge()."',
                vendor_payment_tax          = '".$this->sumOfVendorPaymentTax()."',
                vendor_shipping_charge      = '".$this->sumOfVendorShippingCharge()."',
                vendor_shipping_tax         = '".$this->sumOfVendorShippingTax()."',
                vendor_commission_amt       = '".$this->sumOfVendorCommissionAmt()."',
                vendor_commission_tax_amt   = '".$this->sumOfVendorCommissionTaxAmt()."',
                vendor_payment_charge_amt   = '".$this->sumOfVendor_paymentChargeAmt()."',
                vendor_payment_tax_amt      = '".$this->sumOfVendor_paymentTaxAmt()."', 
                vendor_shipping_charge_amt  = '".$this->sumOfVendorShippingChargeAmt()."',
                vendor_shipping_tax_amt     = '".$this->sumOfVendorShippingTaxAmt()."',
                total_amount                = '".$final_total."',
                shipping_value              = '".$shipping_cost['shipping_value']."',
                shipping_tax                = '".$shipping_cost['shipping_tax']."',
                shipping_tax_value          = '".$shipping_cost['shipping_tax_value']."',
                shipping_cost               = '".$shipping_cost['shipping_cost']."',
                updated_at                  = '".$curr."' WHERE id='".$_SESSION['user_cart_id']."' ";

                $exe_cart       = $this->exeQuery($update_cart);
            if($order['coupon_id']!=0) {
                $update_coupon  = $this->updateCartCoupon($order['coupon_code']);
                if(!$update_coupon) {
                    $this->removeCoupon();
                }
            }

            $check          = $this->lastItemCartRemoval();
            $info           = $this->getDetails(CART_TBL,"*"," id='".@$_SESSION['user_cart_id']."' ");
            if (isset($info['shipping_id'])) {
                $this->makeDefaultShippingAddress($this->encryptData($info['shipping_id']));
            }
            return 1;

        }else{
            return  "Unexpected Error Occurred";
        }
    }

    // General Coupon Check

    function updateCartCoupon($coupon_code)
    {
        $cart_id    = $_SESSION['user_cart_id'];
        $user_id    = $_SESSION['user_session_id'];
        $curr       = date("Y-m-d H:i:s");
        $info       = $this->getDetails(COUPON_TBL,"id,type,discount_value,max_discount_price,min_purchase_amt,coupon_code,end_date,end_time,start_date,start_time,applies_to,specific_category,specific_product","coupon_code='".$coupon_code."' ");
        $cart_info  = $this->getDetails(CART_TBL,"id,total_amount,sub_total,total_tax","id='".$cart_id."'");

        $start_time = date("Y-m-d H:i:s",strtotime($info['start_date']." ".$info['start_time']));
        $end_time   = date("Y-m-d H:i:s",strtotime($info['end_date']." ".$info['end_time'])); 
        $today      = date("Y-m-d H:i:s");

        if($today >= $start_time) {
            if ($today <= $end_time) {
                if($info['applies_to']=='a_t_all_products') {
                    $cart_value = $cart_info['sub_total'] + $cart_info['total_tax'];
                    if ( $cart_value >= $info['min_purchase_amt']) {

                            $offer_value = $info['type'] == "c_t_fixed_amount" ? $info['discount_value'] :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$cart_value,$info['max_discount_price']): '' );
                            $q = "UPDATE ".CART_TBL." SET 
                                coupon_id       = '".$info['id']."',
                                coupon_code     = '".$coupon_code."',
                                coupon_type     = '".$info['type']."',
                                coupon_value    = '".$offer_value."',
                                coupon_status   = '1',
                                updated_at      = '".$curr."' 
                                WHERE  id       = '".$cart_id."' ";
                            $exe = $this->exeQuery($q);


                            $query_2 = "SELECT * FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' " ;
                            $exe_2   = $this->exeQuery($query_2);
                            if(@mysqli_num_rows($exe_2)>0){
                                $i=0;
                                while($list = mysqli_fetch_array($exe_2)){

                                    $product_price = $list['sub_total'] + $list['total_tax'];

                                    $offer_value = $info['type'] == "c_t_fixed_amount" ?  $this->productPerecentageValue($cart_value,$product_price,$info['discount_value']) :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$product_price,$info['max_discount_price']): '' );

                                    $q_3 = "UPDATE ".CART_ITEM_TBL." SET 
                                    coupon_id       = '".$info['id']."',
                                    coupon_value    = '".$offer_value."',
                                    coupon_status   = '1',
                                    updated_at      = '".$curr."' 
                                    WHERE  id       = '".$list['id']."' ";
                                    $exe = $this->exeQuery($q_3);
                                }
                            }
                        return 1;
                    } else {
                        return false ;
                    }
                } elseif($info['applies_to'] == 'a_t_specific_category') {
                    $products_in_coupon = $this->productWithCatId($info['specific_category']);

                    $check_list_products = $this->getDetails(CART_ITEM_TBL," id "," cart_id='".$cart_id."' AND product_id IN (" . implode(',', array_map('intval',$products_in_coupon)). ") ");

                    $total_value = $this->getDetails(CART_ITEM_TBL," SUM(sub_total) as subTotal , SUM(total_tax) as totalTax  "," cart_id='".$cart_id."' AND product_id IN (" . implode(',', array_map('intval',$products_in_coupon)). ") ");

                    $coupon_limit_prize = $total_value['subTotal'] + $total_value['totalTax'];

                    if($check_list_products) {

                        if ($coupon_limit_prize >= $info['min_purchase_amt']) {

                            $offer_value = $info['type'] == "c_t_fixed_amount" ? $info['discount_value'] :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$coupon_limit_prize,$info['max_discount_price']): '' );
                            $q = "UPDATE ".CART_TBL." SET 
                                coupon_id       = '".$info['id']."',
                                coupon_code     = '".$coupon_code."',
                                coupon_type     = '".$info['type']."',
                                coupon_value    = '".$offer_value."',
                                coupon_status   = '1',
                                updated_at      = '".$curr."' 
                                WHERE  id       = '".$cart_id."' ";
                            $exe = $this->exeQuery($q);

                            $query_2 = "SELECT * FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' AND product_id IN ( ".implode(',', array_map('intval',$products_in_coupon))." ) " ;

                            $exe_2   = $this->exeQuery($query_2);
                            if(@mysqli_num_rows($exe_2)>0){
                                $i=0;
                                while($list = mysqli_fetch_array($exe_2)){

                                    $product_price = $list['sub_total'] + $list['total_tax'];
                                    
                                    $offer_value = $info['type'] == "c_t_fixed_amount" ?  $this->productPerecentageValue($coupon_limit_prize,$product_price,$info['discount_value']) :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$product_price,$info['max_discount_price']): '' );


                                    $q_3 = "UPDATE ".CART_ITEM_TBL." SET 
                                    coupon_id       = '".$info['id']."',
                                    coupon_value    = '".$offer_value."',
                                    coupon_status   = '1',
                                    updated_at      = '".$curr."' 
                                    WHERE  id       = '".$list['id']."' ";
                                    $exe = $this->exeQuery($q_3);
                                }
                            }
                            return 1;

                        } else {
                           return false;
                        }
                    } else {
                        return false;
                    }

                } elseif($info['applies_to'] == 'a_t_specific_product') {

                    $check_list_products = $this->getDetails(CART_ITEM_TBL,"id"," cart_id='".$cart_id."' AND product_id IN (" . $info['specific_product']. ") ");

                    $total_value = $this->getDetails(CART_ITEM_TBL," SUM(sub_total) as subTotal , SUM(total_tax) as totalTax "," cart_id='".$cart_id."' AND product_id IN (" . $info['specific_product']. ") ");

                    $coupon_limit_prize = $total_value['subTotal'] + $total_value['totalTax'];

                    if($check_list_products) {
                        if ($coupon_limit_prize >= $info['min_purchase_amt']) {

                            $offer_value = $info['type'] == "c_t_fixed_amount" ? $info['discount_value'] :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'], $coupon_limit_prize,$info['max_discount_price']): '' );

                            $q = "UPDATE ".CART_TBL." SET 
                                coupon_id       = '".$info['id']."',
                                coupon_code     = '".$coupon_code."',
                                coupon_type     = '".$info['type']."',
                                coupon_value    = '".$offer_value."',
                                coupon_status   = '1',
                                updated_at      = '".$curr."' 
                                WHERE  id       = '".$cart_id."' ";
                            $exe = $this->exeQuery($q);

                            $cat_product_value = $this->getDetails(CART_ITEM_TBL,"SUM(total_amount) as total_value"," cart_id='".$cart_id."' AND product_id IN ( ".$info['specific_product']." ) ");

                            $query_2 = "SELECT * FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' AND product_id IN ( ".$info['specific_product']." ) " ;

                            $exe_2   = $this->exeQuery($query_2);
                            if(@mysqli_num_rows($exe_2)>0){
                                $i=0;
                                while($list = mysqli_fetch_array($exe_2)){

                                    $product_price = $list['sub_total'] + $list['total_tax'];

                                    $offer_value = $info['type'] == "c_t_fixed_amount" ?  $this->productPerecentageValue($coupon_limit_prize,$product_price,$info['discount_value']) :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$product_price,$info['max_discount_price']): '' );


                                    $q_3 = "UPDATE ".CART_ITEM_TBL." SET 
                                    coupon_id       = '".$info['id']."',
                                    coupon_value    = '".$offer_value."',
                                    coupon_status   = '1',
                                    updated_at      = '".$curr."' 
                                    WHERE  id       = '".$list['id']."' ";
                                    $exe = $this->exeQuery($q_3);
                                }
                            }
                            return 1;
                        } else {
                            return false ;
                        }
                    } else {
                        return false;
                    }
                }
                return false;
            } else {
                return false;
            }
        } else {
            return false;
        }
        
    }

    // Check the Last item Remove on the cart

    function lastItemCartRemoval()
    {
        $cart_id = $_SESSION["user_cart_id"];
        $check = $this->check_query(CART_ITEM_TBL,"id", "cart_id='".$cart_id."' ");
        if ($check==0) {
            $delete = $this->deleteRow(CART_TBL,"id='".$cart_id."' ");
            unset($_SESSION['user_cart_id']);
        }
    }

    function couponlist()
    {   
        $layout = "";
        $today = date("Y-m-d");
        $query = "SELECT id,coupon_code,usage_limits,usage_limit_value,end_date,per_user_limit,per_user_limit_value,end_time,start_date,start_time,end_date,end_time FROM ".COUPON_TBL." WHERE status='1' AND delete_status='0' AND type!='3'  AND end_date>='".$today."' ";
        $exe = $this->exeQuery($query);
        if (mysqli_num_rows($exe) > 0) {
            while ($row = mysqli_fetch_array($exe)) {
                $list = $this->editPagePublish($row);

                $coupon_filter = $this->couponFilter($list['id']);

                $check_coupon  = $this->getDetails(ORDER_TBL,"count(*) as couponcount"," coupon_id='".$list['id']."' AND coupon_code='".$list['coupon_code']."' ");

                $user_coupon_count = $this->getDetails(ORDER_TBL,"count(*) as user_couponcount"," coupon_id='".$list['id']."' AND coupon_code='".$list['coupon_code']."' AND user_id='".$_SESSION['user_session_id']."' ");

                if($list['per_user_limit']==1) {
                    $per_user_limit = $user_coupon_count['user_couponcount'] < $list['per_user_limit_value'];
                } else {
                    $per_user_limit = true;
                }

                $start_time = date("Y-m-d H:i:s",strtotime($list['start_date']." ".$list['start_time']));
                $end_time   = date("Y-m-d H:i:s",strtotime($list['end_date']." ".$list['end_time'])); 
                $today      = date("Y-m-d H:i:s");

                if($today >= $start_time) {
                    if ($today <= $end_time) {
                        if($coupon_filter){
                            if($per_user_limit) {
                                if($list['usage_limits']==1) {
                                    if ($check_coupon['couponcount'] < $list['usage_limit_value']) {
                                        $layout .= "
                                                <div class='row gx-2 gy-2 mt-2'>
                                                    <div class='col-sm-9'>
                                                      <p class='offer-code'>CODE: ".$list['coupon_code']."</p>
                                                      <label class='form-label  text-muted'>Expires on : ".date("d M Y",strtotime($list['end_date']))."</label>
                                                    </div>                  
                                                    <div class='col-sm-3'>
                                                      <a href='javascript:void()' data-coupon='".$list['coupon_code']."' class='btn btn-primary d-block w-100 couponcodechecklist couponCodeCheckValid btn-primary rounded-pill'>Apply</a>
                                                    </div>
                                                </div>
                
                                                ";
                                    } 
                                } else {
                                    $layout .= "
                                            <div class='row gx-2 gy-2 mt-2'>
                                                <div class='col-sm-9'>
                                                  <p class='offer-code'>CODE: ".$list['coupon_code']."</p>
                                                  <label class='form-label  text-muted'>Expires on : ".date("d M Y",strtotime($list['end_date']))."</label>
                                                </div>                  
                                                <div class='col-sm-3'>
                                                  <a href='javascript:void()' data-coupon='".$list['coupon_code']."' class='btn btn-primary d-block w-100 couponcodechecklist couponCodeCheckValid btn-primary rounded-pill'>Apply</a>
                                                </div>
                                            </div>
                
                                            ";
                                }
                            }
                        }
                    }
                }
            }
        }
        return $layout;
    }

    function couponFilter($coupon_id)
    { 
        $today = date("Y-m-d");
        $user_id= @$_SESSION["user_session_id"];
        $cart_id= @$_SESSION["user_cart_id"];

        // Cart ptoducts
        $query      = "SELECT product_id FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe        = $this->exeQuery($query);
        $list       = mysqli_num_rows($exe);
        $cart_product_ids = array();

        if(mysqli_num_rows($exe)) {
            while ($list = mysqli_fetch_assoc($exe)) {
                $cart_product_ids[] = $list['product_id']; 
            }
        }

        // Category ids
        $c_query      = "SELECT category_type,main_category_id,sub_category_id FROM ".PRODUCT_TBL." WHERE id IN ( ".implode(",", array_map('intval', $cart_product_ids))." ) ";
        $c_exe        = $this->exeQuery($c_query);
        $c_list       = mysqli_num_rows($c_exe);
        $cart_category_ids = array();

        if(mysqli_num_rows($c_exe)) {
            while ($list = mysqli_fetch_assoc($c_exe)) {

                if($list['category_type']=="main") {
                    $cart_category_ids[] = $list['main_category_id']; 
                } else {
                    $cat_info = $this->getDetails(SUB_CATEGORY_TBL,"category_id"," id='".$list['sub_category_id']."' ");
                    $cart_category_ids[] = $cat_info['category_id'];
                }
            }
        }

        // Coupon info

        $cupon_info  = $this->getDetails(COUPON_TBL,"*","id='".$coupon_id."'");

        if($cupon_info['applies_to']=='a_t_all_products') {
            $condition_true = 1;
        } elseif($cupon_info['applies_to']=='a_t_specific_category') {
            $coupon_categories = explode(",", $cupon_info['specific_category']);
            foreach ($cart_category_ids as $key => $value) {
                $category_prsent[] = in_array($value, $coupon_categories);
            }
            $condition_true = array_sum($category_prsent) > 0 ;
        } elseif($cupon_info['applies_to']=='a_t_specific_product') {
            $coupon_products = explode(",", $cupon_info['specific_product']);
            foreach ($cart_product_ids as $key => $value) {
                $product_prsent[] = in_array($value, $coupon_products);
            }
            $condition_true = array_sum($product_prsent) > 0 ;
        }

        return $condition_true;
    }

    // Coupon Check

    function couponCodeCheck($data="")
    {   
        $today = date("Y-m-d");
        if (isset($_SESSION['user_session_id'])) {
            $check_gen      = $this -> check_query(COUPON_TBL,"id"," coupon_code ='".$data['coupon_code']."' AND status='1' AND end_date>='".$today."' ");

            $check_coupon   = $this->getDetails(ORDER_TBL,"count(*) as couponcount","coupon_code='".$data['coupon_code']."' ");

            $get_coupon     = $this -> getDetails(COUPON_TBL,"*"," coupon_code ='".$data['coupon_code']."' AND status='1' AND end_date>='".$today."' ");

            if($get_coupon['usage_limits']==1) {
                $check_useage =  $check_coupon['couponcount'] < $get_coupon['usage_limits'];
            } else {
                $check_useage = true;
            }

            if ($check_gen==1 && $check_useage) {
                $general = $this->generalCouponCheck($data['coupon_code']);
                return $general;
            }else{
                return "The Coupon Code is InValid";
            }
        }else{
            return "Login And Add coupon Code";
        }
    }

    function couponCodeCheckValid($data)
    {   
        if (isset($_SESSION['user_session_id'])) {
            
            $check  = $this -> check_query(COUPON_TBL,"id"," coupon_code ='".$data."' AND status='1' ");
            if ($check==1) {
                $general = $this->generalCouponCheck($data);
                return $general;
            }else{
                return "The Coupon Code is InValid";
            }
        }else{
            return "Login to Add coupon Code";
        }
    }

    // General Coupon Check

    function generalCouponCheck($coupon_code)
    {
        $cart_id    = $_SESSION['user_cart_id'];
        $user_id    = $_SESSION['user_session_id'];
        $curr       = date("Y-m-d H:i:s");
        $info       = $this->getDetails(COUPON_TBL,"id,type,discount_value,max_discount_price,min_purchase_amt,coupon_code,end_date,end_time,start_date,start_time,per_user_limit,per_user_limit_value,applies_to,specific_category,specific_product","coupon_code='".$coupon_code."' ");
        $cart_info  = $this->getDetails(CART_TBL,"id,total_amount,sub_total,total_tax","id='".$cart_id."'");

        $start_time = date("Y-m-d H:i:s",strtotime($info['start_date']." ".$info['start_time']));
        $end_time   = date("Y-m-d H:i:s",strtotime($info['end_date']." ".$info['end_time'])); 
        $today      = date("Y-m-d H:i:s");

        $user_coupon_count = $this->getDetails(ORDER_TBL,"count(*) as user_couponcount"," coupon_id='".$info['id']."' AND coupon_code='".$info['coupon_code']."' AND user_id='".$_SESSION['user_session_id']."' ");

        if($info['per_user_limit']==1) {
            $per_user_limit = $user_coupon_count['user_couponcount'] < $info['per_user_limit_value'];
        } else {
            $per_user_limit = true;
        }

        if($today >= $start_time) {
            if ($today <= $end_time) {
                if($per_user_limit) {
                    if($info['applies_to']=='a_t_all_products') {
                        $cart_value = $cart_info['sub_total'] + $cart_info['total_tax'];
                        if ( $cart_value >= $info['min_purchase_amt']) {

                            $offer_value = $info['type'] == "c_t_fixed_amount" ? $info['discount_value'] :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$cart_value,$info['max_discount_price']): '' );
                            
                            // Calculate Shipping Cost
                            $shipping_total_val = $cart_value - $offer_value;
                            $shipping_cost = $this->getShippingCost();
                            
                            $q = "UPDATE ".CART_TBL." SET 
                                coupon_id           = '".$info['id']."',
                                coupon_code         = '".$coupon_code."',
                                coupon_type         = '".$info['type']."',
                                coupon_value        = '".$offer_value."',
                                coupon_status       = '1',
                                shipping_value      = '".$shipping_cost['shipping_value']."',
                                shipping_tax        = '".$shipping_cost['shipping_tax']."',
                                shipping_tax_value  = '".$shipping_cost['shipping_tax_value']."',
                                shipping_cost       = '".$shipping_cost['shipping_cost']."',
                                updated_at          = '".$curr."' 
                                WHERE  id           = '".$cart_id."' ";
                            $exe = $this->exeQuery($q);


                            $query_2 = "SELECT * FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' " ;
                            $exe_2   = $this->exeQuery($query_2);
                            if(@mysqli_num_rows($exe_2)>0){
                                $i=0;
                                while($list = mysqli_fetch_array($exe_2)){

                                    $product_price = $list['sub_total'] + $list['total_tax'];

                                    $offer_value = $info['type'] == "c_t_fixed_amount" ?  $this->productPerecentageValue($cart_value,$product_price,$info['discount_value']) :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$product_price,$info['max_discount_price']): '' );

                                    $q_3 = "UPDATE ".CART_ITEM_TBL." SET 
                                    coupon_id       = '".$info['id']."',
                                    coupon_value    = '".$offer_value."',
                                    coupon_status   = '1',
                                    updated_at      = '".$curr."' 
                                    WHERE  id       = '".$list['id']."' ";
                                    $exe = $this->exeQuery($q_3);
                                }
                            }
                            return 1;
                        } else {
                            return "The Coupon Applicable for orders above ₹ ".$info['min_purchase_amt']."" ;
                        }
                    
                    } elseif($info['applies_to'] == 'a_t_specific_category') {

                        $products_in_coupon = $this->productWithCatId($info['specific_category']);

                        $total_value = $this->getDetails(CART_ITEM_TBL," SUM(sub_total) as subTotal , SUM(total_tax) as totalTax  "," cart_id='".$cart_id."' AND product_id IN (" . implode(',', array_map('intval',$products_in_coupon)). ") ");

                        $coupon_limit_prize = $total_value['subTotal'] + $total_value['totalTax'];

                        if ($coupon_limit_prize >= $info['min_purchase_amt']) {

                            $offer_value = $info['type'] == "c_t_fixed_amount" ? $info['discount_value'] :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$coupon_limit_prize,$info['max_discount_price']): '' );

                            // Calculate Shipping Cost
                            $shipping_total_val = $coupon_limit_prize - $offer_value;
                            $shipping_cost = $this->getShippingCost();

                            $q = "UPDATE ".CART_TBL." SET 
                                coupon_id           = '".$info['id']."',
                                coupon_code         = '".$coupon_code."',
                                coupon_type         = '".$info['type']."',
                                coupon_value        = '".$offer_value."',
                                coupon_status       = '1',
                                shipping_value      = '".$shipping_cost['shipping_value']."',
                                shipping_tax        = '".$shipping_cost['shipping_tax']."',
                                shipping_tax_value  = '".$shipping_cost['shipping_tax_value']."',
                                shipping_cost       = '".$shipping_cost['shipping_cost']."',
                                updated_at          = '".$curr."' 
                                WHERE  id           = '".$cart_id."' ";
                            $exe = $this->exeQuery($q);

                            $query_2 = "SELECT * FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' AND product_id IN ( ".implode(',', array_map('intval',$products_in_coupon))." ) " ;

                            $exe_2   = $this->exeQuery($query_2);
                            if(@mysqli_num_rows($exe_2)>0){
                                $i=0;
                                while($list = mysqli_fetch_array($exe_2)){

                                    $product_price = $list['sub_total'] + $list['total_tax'];
                                    
                                    $offer_value = $info['type'] == "c_t_fixed_amount" ?  $this->productPerecentageValue($coupon_limit_prize,$product_price,$info['discount_value']) :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$product_price): '' );

                                    $q_3 = "UPDATE ".CART_ITEM_TBL." SET 
                                    coupon_id       = '".$info['id']."',
                                    coupon_value    = '".$offer_value."',
                                    coupon_status   = '1',
                                    updated_at      = '".$curr."' 
                                    WHERE  id       = '".$list['id']."' ";
                                    $exe = $this->exeQuery($q_3);
                                }
                            }

                        } else {
                            $coupon_category_names = array();
                            $q = "SELECT category FROM  ".MAIN_CATEGORY_TBL." WHERE id IN (".$info['specific_category'].") ";
                            $exe = $this->exeQuery($q);
                             if(@mysqli_num_rows($exe)>0) {
                                $i=0;
                                while($list = mysqli_fetch_array($exe)){
                                    $coupon_category_names[] = $list['category'];
                                }
                            }

                            $coupon_category_names = implode(",", $coupon_category_names);

                            return "The ".$info['coupon_code']." Coupon Applicable for orders above ₹ ".$info['min_purchase_amt']." in ".$coupon_category_names." category products " ;
                        }

                    } elseif($info['applies_to'] == 'a_t_specific_product') {

                        $total_value = $this->getDetails(CART_ITEM_TBL," SUM(sub_total) as subTotal , SUM(total_tax) as totalTax "," cart_id='".$cart_id."' AND product_id IN (" . $info['specific_product']. ") ");

                        $coupon_limit_prize = $total_value['subTotal'] + $total_value['totalTax'];

                        if ($coupon_limit_prize >= $info['min_purchase_amt']) {

                            $offer_value = $info['type'] == "c_t_fixed_amount" ? $info['discount_value'] :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'], $coupon_limit_prize,$info['max_discount_price']): '' );

                            // Calculate Shipping Cost
                            $shipping_total_val = $coupon_limit_prize - $offer_value;
                            $shipping_cost = $this->getShippingCost();

                            $q = "UPDATE ".CART_TBL." SET 
                                coupon_id           = '".$info['id']."',
                                coupon_code         = '".$coupon_code."',
                                coupon_type         = '".$info['type']."',
                                coupon_value        = '".$offer_value."',
                                coupon_status       = '1',
                                shipping_value      = '".$shipping_cost['shipping_value']."',
                                shipping_tax        = '".$shipping_cost['shipping_tax']."',
                                shipping_tax_value  = '".$shipping_cost['shipping_tax_value']."',
                                shipping_cost       = '".$shipping_cost['shipping_cost']."',
                                updated_at          = '".$curr."' 
                                WHERE  id           = '".$cart_id."' ";
                            $exe = $this->exeQuery($q);

                            $cat_product_value = $this->getDetails(CART_ITEM_TBL,"SUM(total_amount) as total_value"," cart_id='".$cart_id."' AND product_id IN ( ".$info['specific_product']." ) ");

                            $query_2 = "SELECT * FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' AND product_id IN ( ".$info['specific_product']." ) " ;

                            $exe_2   = $this->exeQuery($query_2);
                            if(@mysqli_num_rows($exe_2)>0){
                                $i=0;
                                while($list = mysqli_fetch_array($exe_2)){

                                    $product_price = $list['sub_total'] + $list['total_tax'];

                                    $offer_value = $info['type'] == "c_t_fixed_amount" ?  $this->productPerecentageValue($coupon_limit_prize,$product_price,$info['discount_value']) :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$product_price,$info['max_discount_price']): '' );


                                    $q_3 = "UPDATE ".CART_ITEM_TBL." SET 
                                    coupon_id       = '".$info['id']."',
                                    coupon_value    = '".$offer_value."',
                                    coupon_status   = '1',
                                    updated_at      = '".$curr."' 
                                    WHERE  id       = '".$list['id']."' ";
                                    $exe = $this->exeQuery($q_3);
                                }
                            }

                        } else {
                            $coupon_product_names = array();
                            $q = "SELECT product_name FROM  ".PRODUCT_TBL." WHERE id IN (".$info['specific_product'].") ";
                            $exe = $this->exeQuery($q);
                             if(@mysqli_num_rows($exe)>0) {
                                $i=0;
                                while($list = mysqli_fetch_array($exe)){
                                    $coupon_product_names[] = $list['product_name'];
                                }
                            }

                            $coupon_product_names = implode(",", $coupon_product_names);

                            return "The ".$info['coupon_code']." Coupon Applicable for orders above ₹ ".$info['min_purchase_amt']." for follwing products  ".$coupon_product_names." " ;
                        }

                    }
                } else {
                   return "The coupon code you entred has already been redeemed ";
                }
            } else {
                return "Coupon Expired";
            }
        } else {
            return "Invalid Coupon";
        }
        return 1;
    }


    function productPerecentageValue($total,$product_price,$discoun_value)
    {
        $percentage = (($product_price - $total) / $total) * 100;

        $percentage = abs($percentage) - 100;

        $offer_value = (( (abs($percentage)/100) + 1) * $discoun_value) - $discoun_value;

        return $offer_value ;
    }

    function productWithCatId($coupon_categories)
    { 
        $today = date("Y-m-d");
        $user_id= @$_SESSION["user_session_id"];
        $cart_id= @$_SESSION["user_cart_id"];

         // Cart ptoducts
        $query      = "SELECT product_id FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe        = $this->exeQuery($query);
        $list       = mysqli_num_rows($exe);
        $cart_product_ids = array();

        if(mysqli_num_rows($exe)) {
            while ($list = mysqli_fetch_assoc($exe)) {
                $cart_product_ids[] = $list['product_id']; 
            }
        }

        // Category ids
        $c_query      = "SELECT id,category_type,main_category_id,sub_category_id FROM ".PRODUCT_TBL." WHERE id IN ( ".implode(",", array_map('intval', $cart_product_ids))." ) ";
        $c_exe        = $this->exeQuery($c_query);
        $c_list       = mysqli_num_rows($c_exe);
        
        $cart_product_with_cat_id = array();

        if(mysqli_num_rows($c_exe)) {
            while ($list = mysqli_fetch_assoc($c_exe)) {

                    if($list['category_type']=="main") {
                        $cart_product_with_cat_id[$list['id']] = $list['main_category_id']; 
                    } else {
                        $cat_info = $this->getDetails(SUB_CATEGORY_TBL,"category_id"," id='".$list['sub_category_id']."' ");
                        $cart_product_with_cat_id[$list['id']] = $cat_info['category_id'];
                    }
            }
        }

        $cart_product_with_cat_id = array_unique($cart_product_with_cat_id);


        $coupon_categories = explode(",", $coupon_categories);


        $cat_prd_id = array();

        foreach ($cart_product_with_cat_id as $key => $value) {
            if(in_array($value, $coupon_categories)) {
                $cat_prd_id[$value] = $key;
            }
        }

       if(count($cat_prd_id)==0) 
        {
            $cat_prd_id[] = 0 ;
        }
        
        return $cat_prd_id;
    }

    function calcOfferValue($value,$total,$max_discount_price)
    {
        $amount      = (($value*$total)/100);
        $offer_price = (($amount <= $max_discount_price)? $amount : $max_discount_price );
        return $offer_price;
    }

    // Get State list

    function getStatelist($current="")
    {
        $layout = "";
        $q      = "SELECT * FROM ".STATE_TBL." WHERE status='1' AND delete_status='0' " ;
        $query = $this->exeQuery($q);
        if(@mysqli_num_rows($query)>0){
            $i=0;
            while($list = mysqli_fetch_array($query)){
                $selected = (($list['id']==$current) ? 'selected' : '');
                $selected = (($selected=="selected")? $selected : (($list['id']==31) ? 'selected' : '') );
                $layout.= "<option value='".$list['id']."' $selected>".$list['name']."</option>";
                $i++;
            }
        }
        return $layout;
    }

    // Get City List

    function getCitylist($current="")
    {
        $layout = "";
        $q = "SELECT * FROM ".LOCATION_TBL." WHERE delete_status='0' AND status='1' ORDER BY id ASC " ;
        $query = $this->exeQuery($q);

        if(@mysqli_num_rows($query)>0){
            $i=0;
            while($list = mysqli_fetch_array($query)){
                $selected = (($list['id']==$current) ? 'selected' : '');
                $layout.= "<option value='".$list['id']."' $selected>".$list['location']."</option>";
                $i++;
            }
        }
        
        return $layout;
    }

    function manageCartNewShippingAddress($shipping_id="")
    {
        $layout = "";
        if (isset($_SESSION["user_session_id"])){
            $user_id = $_SESSION['user_session_id'];
            $query = "SELECT * FROM ".CUSTOMER_ADDRESS_TBL."  WHERE  status='1' AND delete_status='0' AND user_id='".$user_id."' ORDER BY id DESC ";
            $exe = $this->exeQuery($query);

            if (mysqli_num_rows($exe) > 0) {
                $i = 1;
                while ($row = mysqli_fetch_array($exe)) {
                    $list = $this->editPagePublish($row);
                    $address    = (($list['id']==$shipping_id) ? "checked" : "");
                    $default    = (($list['id']==$shipping_id) ? "default" : "");
                    $active     = (($list['id']==$shipping_id ||$list['default_address']==1) ? "active_cart_address" : "");
                    $select     = (($list['id']==$shipping_id) ? "Selected" : "Select");

                    $gst_name_data = "";
                    $gstin_number_data = "";

                    if($list['gst_name']!="") {
                        $gst_name_data = " <div class='col-sm-12 m-0'>
                                                <p class='mb-0'>GST Name : ".$this->publishContent($list['gst_name'])." .</p>
                                              </div>";
                    }
                    if($list['gstin_number']!="") {
                        $gstin_number_data =" <div class='col-sm-12 m-0'>
                                                <p class='mb-0'>GSTIN Number : ".$this->publishContent($list['gstin_number'])." .</p>
                                              </div>";
                    }

                    $select_check = (($list['id']==$shipping_id) ? "
                        <a class='ps-2 text-success' href='javascript:void();'><span><i class='fas fa-check-circle me-2'></i> Select</span></a>" : " <a class='ps-2 text-muted' href='#'><span><i class='far fa-check-circle me-2'></i> Select</span></a>");
                    
                    $layout .="
                            <div class='card p-3  $active cart_address_bottom' data-action='select' id='deleteadd_".$list['id']."'>  
                              <div class='col-sm-12 d-sm-flex justify-content-end align-items-center $default'>
                                <div class='selection_address ps-2 text-muted' active_address_".$list['id']." id='makedefault_".$list['id']."'><span class='make_default_shipping' data-option ='".$list['id']."' data-id='".$this->encryptData($list['id'])."'>".$select_check."
                                 </span></div>

                              </div>
                              <div class='row gx-2 gy-2'>
                                <div class='col-sm-12'>
                                    <p class='h6 font-bold'>".$list['user_name']."</p>
                                </div>
                                <div class='col-sm-12 m-0'>
                                    <p class='mb-0'>".$this->publishContent($list['address']).",".$list['landmark']." , ".$list['area_name'].",".$list['city'].", ".$list['state_name']." - ".$list['pincode']."</p>
                                </div>                    
                                <div class='col-sm-12 m-0'>
                                  <p class='mb-0'>Mobile: Ph (+91) - ".$list['mobile']."</p>
                                </div>
                                $gst_name_data
                                $gstin_number_data
                                <div class='col-sm-12 d-sm-flex justify-content-start align-items-center m-0'>

                                  <button class='btn btn-sm ps-2 text-danger removeShippingAddress'  data-id='".$this->encryptData($list['id'])."'><i class='far fa-times-circle me-2'></i>Remove</button>

                                 <button class='btn btn-sm ps-2 text-primary editAddress' data-action='showModal' data-option='".$this->encryptData($list['id'])."'><i class='fas fa-pen-square me-2'></i>Edit</button>
                              
                                </div>
                              </div>
                            </div>
                                ";
                    $i++;
                    }
            }       
        return $layout;
        }
    }


   function ManageOrderItems($order_id="")
    {
        $layout = "";
        $query = "SELECT I.id,I.sub_total,I.total_tax,I.product_id,I.tax_type,I.variant_id,I.order_id,O.order_address,I.price as prize,I.tax_amt,I.final_price,I.qty,I.size,I.total_amount,I.sgst,I.cgst,I.igst,I.sgst_amt,I.cgst_amt,I.igst_amt,O.order_uid,P.product_name,V.variant_name,P.category_type,P.main_category_id,P.sub_category_id,VI.company,VI.state_name,VI.state_id,PU.product_unit,P.selling_price as price 
                  FROM ".ORDER_ITEM_TBL." I LEFT JOIN ".PRODUCT_TBL." P ON(P.id=I.product_id) 
                                            LEFT JOIN ".ORDER_TBL." O ON (O.id=I.order_id) 
                                            LEFT JOIN ".VENDOR_TBL." VI ON (I.vendor_id=VI.id) 
                                            LEFT JOIN ".PRODUCT_VARIANTS." V ON (I.variant_id=V.id) 
                                            LEFT JOIN ".PRODUCT_UNIT_TBL." PU ON (PU.id=P.product_unit)  
                  WHERE I.order_id='".$order_id."' ORDER BY I.qty*I.final_price DESC ";
        $exe = $this->exeQuery($query);
        if (mysqli_num_rows($exe) > 0) {
            $i = 1;
            while ($row = mysqli_fetch_array($exe)) {
                $list = $this->editPagePublish($row);
                if ($list['order_address']!="") {
                    $address = $this->getDetails(CUSTOMER_ADDRESS_TBL,"state_name","id='".$list['order_address']."'");
                    if ($address['state_name']==$list['state_name']) {
                        $tax = "<p>SGST : ".$list['sgst']."% (₹ ".$list['sgst_amt'].")</p>
                                <p>CGST : ".$list['cgst']."% (₹ ".$list['cgst_amt'].")</p>";
                    }else{
                        $tax = "<p>IGST : ".$list['igst']."% (₹ ".$list['igst_amt'].")</p>";
                    }
                }else{
                    $tax = "<p>SGST : ".$list['sgst']."% (₹ ".$list['sgst_amt'].")</p>
                            <p>CGST : ".$list['cgst']."% (₹ ".$list['cgst_amt'].")</p>";
                }

                if($list['tax_type']=="inclusive") { 
                      $tax_info="<p class='f_size_large color_dark'> (Inclusive of all taxes *)</p>";
                } else { 
                      $tax_info="<p class='f_size_large color_dark'> (Exclusive of all taxes *)</p>";
                } 

                if($list['category_type']=='main') {
                    $cat_info = $this->getDetails(MAIN_CATEGORY_TBL,"category"," id='".$list['main_category_id']."' ");
                    $cat_name = $cat_info['category'];
                } else {
                    $cat_info = $this->getDetails(SUB_CATEGORY_TBL,"subcategory"," id='".$list['sub_category_id']."' ");
                    $cat_name = $cat_info['subcategory'];
                }

                $product_unit = (($list['product_unit']!="")? "( ".$list['product_unit']." )" : "" );


                $name   = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

                $layout .="
                    <tr>
                        <td>".$list['order_uid']."</td>
                        <td>
                            <a href='#'' class=' color_dark d_inline_b
                                m_bottom_5'>".$this->publishContent($name)."</a><br>
                            ".$tax_info."".$tax."
                            <p class='f_size_large color_dark'>Category :  ".$cat_name."</p>
                            <p class='f_size_large color_dark'>Sold By :  ".$list['company']."</p>
                        </td>
                        <td>
                            <p class='f_size_large color_dark'>₹  ".$list['prize']."</p>
                        </td>
                        <td>".$list['qty']." ".$product_unit."</td>

                        <td>
                            <p class='color_dark f_size_large float-end' >₹  ".$this->inrFormat($list['sub_total']+$list['total_tax'],2)."</p>
                        </td>
                    </tr>";
                $i++;
            }
        }
    return $layout;
    }

  

    // Remove Coupon From Cart

    function removeCoupon()
    {
        $curr    = date("Y-m-d H:i:s");
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = $_SESSION['user_cart_id'];

        // Calculate Shipping Cost
        $shipping_total_val = $this->getDetails(CART_TBL,"total_amount","id='".$cart_id."' ");
        $shipping_cost      = $this->getShippingCost();

        $q = "UPDATE ".CART_TBL." SET 
                        coupon_code         = NULL,
                        coupon_id           = '0',
                        coupon_type         = NULL,
                        coupon_value        = NULL,
                        coupon_status       = '0',
                        shipping_value      = '".$shipping_cost['shipping_value']."',
                        shipping_tax        = '".$shipping_cost['shipping_tax']."',
                        shipping_tax_value  = '".$shipping_cost['shipping_tax_value']."',
                        shipping_cost       = '".$shipping_cost['shipping_cost']."',
                        updated_at          = '".$curr."' 
                        WHERE id  = '".$cart_id."' AND user_id='".$user_id."' ";
        $exe = $this->exeQuery($q);

        $query_2 = "SELECT * FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' AND user_id='".$user_id."' " ;
        $exe_2   = $this->exeQuery($query_2);
        if(@mysqli_num_rows($exe_2)>0){
            $i=0;
            while($list = mysqli_fetch_array($exe_2)){

                $q_3 = "UPDATE ".CART_ITEM_TBL." SET 
                coupon_id       = NULL,
                coupon_value    = NULL,
                coupon_status   = '0',
                updated_at      = '".$curr."' 
                WHERE  id       = '".$list['id']."' ";
                $exe = $this->exeQuery($q_3);
            }
        }

        if ($exe) {
            return 1;
        }else{
            return  "Unexpected Error Occurred";
        }
    }   


    // Make Default User Address

    function addToFavourite($id='')
    {   
        $user_id    = @$_SESSION['user_session_id'];
        $product_id = $this->decryptData($id);
        $curr       = date("Y-m-d H:i:s");
        $info       = $this->getDetails(WISHLIST_TBL,"id,fav_status","product_id='".$product_id."' AND user_id='".$user_id."' ");   
        if (isset($info['fav_status'])) {
            $q  = "DELETE FROM ".WISHLIST_TBL." WHERE product_id = '".$product_id."' AND user_id='".$user_id."' ";
            $exe = $this->exeQuery($q);
            return 1;
        }else{
            $q = "INSERT INTO ".WISHLIST_TBL." SET 
                user_id             = '".$user_id."',   
                product_id          = '".$product_id."',    
                fav_status          = '1',
                created_date        = '".$curr."',
                status              = '1',
                created_at          = '".$curr."',
                updated_at          = '".$curr."'  ";
        $exe = $this->exeQuery($q);
        return 1;
        }
    }


    // Remove From Cart

    function removeMyFav($id='')
    {       
        $q = "DELETE FROM ".WISHLIST_TBL." WHERE id='".$id."' ";
        $exe = $this->exeQuery($q);
        if ($exe) {
            return 1;
        }else{
            return  "Unexpected Error Occurred";
        }
    }   


    function addCartShippingAddress($data)
    {   
        $layout = "";
        if(isset($_SESSION['new_shipping_address_key'])){
            if($this->cleanString($data['fkey']) == $_SESSION['new_shipping_address_key']){
                $curr        = date("Y-m-d H:i:s");
                $user_id     = $_SESSION['user_session_id'];
                $check       = $this->check_query(CUSTOMER_ADDRESS_TBL,"id","user_id= '".$user_id."' AND delete_status='0' ");
                $default     = $check==0 ? "1": "0";
                $state       = $this->getDetails(STATE_TBL,"name","id='".$data['state_id']."'");
                $city        = $this->getDetails(LOCATIONGROUP_TBL,"group_name","id='".$data['city']."'");
                $location    = $this->getDetails(LOCATION_TBL,"location","id='".$data['location_area']."'");
                $cus_name    = $this->hyphenize($data['name']);
                $check_token = $this->check_query(CUSTOMER_ADDRESS_TBL,"id"," token='".$data['name']."' ");
                if ($check_token==0) {
                      $token = $cus_name;
                }else{
                      $token = $cus_name."-".$this->generateRandomString("5");
                }
            
                $query = "INSERT INTO ".CUSTOMER_ADDRESS_TBL." SET 
                        token           = '".$token."',
                        user_id         = '".$user_id."',
                        user_name       = '".$this->cleanString($data['name'])."',
                        mobile          = '".$this->cleanString($data['mobile'])."',
                        address         = '".$this->cleanString($data['address'])."',
                        landmark        = '".$this->cleanString($data['landmark'])."',
                        city            = '".($city['group_name'])."',
                        city_id         = '".($data['city'])."',
                        area_name       = '".($location['location'])."',
                        area_id         = '".($data['location_area'])."',
                        state_id        = '".$data['state_id']."',
                        state_name      = '".$state['name']."',
                        pincode         = '".$this->cleanString($data['pincode'])."',
                        gst_name        = '".$this->cleanString($data['gst_name'])."',
                        gstin_number    = '".$this->cleanString($data['gstin_number'])."',
                        delete_status   = '0',
                        default_address = '".$default."',
                        status          = '1',
                        created_at      = '".$curr."',
                        updated_at      = '".$curr."' ";
                $last_id = $this->lastInserID($query);
                
                if($last_id){
                    if (isset($_SESSION['user_cart_id'])) {
                        $check_add = $this->check_query(CUSTOMER_ADDRESS_TBL,"id","user_id= '".$user_id."' AND delete_status='0' ");
                        if ($check_add==1) {
                            $qe = "UPDATE ".CART_TBL." SET 
                                shipping_id     = '".$last_id."',   
                                updated_at      = '".$curr."' 
                                WHERE id        = '".$_SESSION['user_cart_id']."' ";
                            $qu = $this->exeQuery($qe);
                        } 
                           
                    }
                    unset($_SESSION['new_shipping_address_key']);
                    return 1;
                }else{
                    return "Sorry!! Unexpected Error Occurred. Please try again.";
                }
                
            }else{
                return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
            }
        }else{
            return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
        }
    }

    function makeDefaultShippingAddress($id='')
    {    
        $curr       = date("Y-m-d H:i:s");
        $address_id = $this->decryptData($id);
        $cart_id    = @$_SESSION['user_cart_id'];
        $user_id    = @$_SESSION['user_session_id'];
        $check      = $this->check_query(CUSTOMER_ADDRESS_TBL,"id"," id='".$address_id."' AND  user_id='".$user_id."' AND delete_status='0' ");

        if ($check==1) {
            $info        = $this->getDetails(CUSTOMER_ADDRESS_TBL,"id,city"," id='".$address_id."' AND  user_id='".$user_id."' AND delete_status='0' ");

            $q     = "UPDATE ".CUSTOMER_ADDRESS_TBL." SET 
                      default_address     = '0',  
                      updated_at          = '".$curr."' 
                      WHERE user_id       = '".$user_id."' ";
            $result         = $this->exeQuery($q);

            $q1    = "UPDATE ".CUSTOMER_ADDRESS_TBL." SET
                      default_address     = '1',
                      updated_at          = '".$curr."' 
                      WHERE id            = '".$address_id."' AND user_id='".$user_id."'  ";
            $exe1 = $this->exeQuery($q1);


            $query = "UPDATE ".CART_TBL." SET
                      delivery_id         = '".$address_id."',
                      shipping_id         = '".$address_id."',
                      shipping_status     = '1',
                      updated_at          = '".$curr."'
                      WHERE id            = '".$cart_id."' ";
            $exe = $this->exeQuery($query);
            if ($exe) {
                return 1;
            }else{
                return  "Sorry Unexpected Error Occurred Try again";
            }
        }else{
            return  "Sorry the current delivery address does not Exits. Please try again.";
        }
    }

    /*----------------------------------------------
                Location List 
    ----------------------------------------------*/
    function getLocationlist($current="")
    {   
        $layout_group = "";
        $layout_array = array();
        $q = "SELECT LG.id,LG.token,LG.group_name,LG.state_id,LG.status,LG.delete_status,LA.location,LA.group_id FROM ".LOCATIONGROUP_TBL." LG LEFT JOIN ".LOCATION_TBL." LA ON (LA.group_id=LG.id) WHERE  LG.delete_status='0' AND LG.status='1' AND LA.group_id=LG.id AND LG.status='1'  GROUP BY LG.id ASC  " ;
        $query = $this->exeQuery($q);   
        if(mysqli_num_rows($query) > 0){
            $i=1;
            while($details = mysqli_fetch_array($query)){
                $list           = $this->editPagePublish($details);
                $layout_array[] = $this->getLocationArealist($list['id']);  
                $selected       = (($list['id']==$current) ? 'selected' : '');
                $layout_group  .= "<option value='".$list['id']."' data-state_id='".$list['state_id']."' $selected>".$list['group_name']."</option>";
                $i++;
            }
        }
        $result['group_layout']    = $layout_group;
        $result['location_layout'] = $layout_array;

        return $result;
    }

    // Get locations for selected Location group

    function getLocationArealist($group_id,$current="")
    {   
        $layout     = "";

        $location_group = $this->getDetails(LOCATIONGROUP_TBL,"*"," id='".$group_id."' ");
        $q = "SELECT L.id,L.token,L.location,L.pincode,L.longitude,L.latitude,L.group_id,L.status,L.delete_status,G.id as group_id,G.city_id FROM ".LOCATION_TBL." L LEFT JOIN ".GROUP_TBL." G ON (G.id=L.group_id) WHERE G.city_id='".$location_group['id']."'  AND L.delete_status='0' AND L.status='1' ORDER BY L.id ASC";
        $query = $this->exeQuery($q);   
        if(mysqli_num_rows($query) > 0){
            $layout       .= "<option value=''>Select Area</option>";
            $i=1;
            while($details = mysqli_fetch_array($query)){
                $list      = $this->editPagePublish($details); 
                $selected  = (($list['id']==$current) ? 'selected' : '');
                $layout   .= "<option value='".$list['id']."' data-pincode='".$list['pincode']."'  $selected>".$list['location']."</option>";
                $i++;
            }
        }
        return $layout;
    }

    function editAddress($id='')
    {   
        $_SESSION['new_shipping_address_key'] = $this->generateRandomString("40");
        $add_id         = $this->decryptData($id);
        $info           = $this->getDetails(CUSTOMER_ADDRESS_TBL,"*","id='".$add_id."'");
        $info           = $this->editPagePublish($info);
        $state_dr       = $this->getStatelist($info['state_id']);
        $locartion_dr   = $this->getLocationlist($info['city_id'],$info['area_id']);
        $location_area  = $this->getLocationArealist($info['city_id'],$info['area_id']);
        $layout = "
                <input type='hidden' value='".$_SESSION['new_shipping_address_key']."' name='fkey'>
                <input type='hidden' value='".$id."' name='token' id='token'>
                  <div class='row gx-2 gy-2'>
                      <div class='col-sm-6'>
                          <label class='form-label' for='co-fn'>Name <span class='text-danger'>*</span></label>
                          <input class='form-control' type='text' value='".$info['user_name']."' name='name' id='name' placeholder='Name'>
                          <div class='invalid-feedback'>Please enter your first name!</div>
                      </div>
                      <div class='col-sm-6'>
                          <label class='form-label' for='co-ln'>Phone number <span class='text-danger'>*</span></label>
                          <input class='form-control' value='".$info['mobile']."' name='mobile' id='mobile' type='text' maxlength='10' placeholder='Number'>
                          <div class='invalid-feedback'>Please enter your phone number!</div>
                      </div>
                  </div>
                  <div class='d-sm-flex'>
                      <h1 class='h6 pt-3 text-sm-start'>Address</h1>
                  </div>
                  <div class='row gx-2 gy-2'>
                      <div class='col-sm-12'>
                        <label class='form-label' for='co-address'>Address <span class='text-danger'>*</span></label>
                        <input class='form-control' value='".$info['address']."' name='address' id='address' type='text' row='3' placeholder='Address (House No, Building, Street, Area)*'>
                        <div class='invalid-feedback'>Please enter your address!</div>
                      </div>
                      <div class='col-sm-6'>
                        <label class='form-label' for='co-fn'>Landmark <span class='text-danger'>*</span></label>
                        <input class='form-control bg-image-none' id='landmark' name='landmark' type='text' value='".$info['landmark']."' placeholder='Landmark'>
                      </div>
                      <div class='col-sm-6'>
                        <label class='form-label' for='co-fn'>Select City <span class='text-danger'>*</span></label>
                            <div class='form-control-wrap'>
                                <select class='form-select' name='city' id='edit_city' data-search='on'>
                                    <option value=' '>Select City</option>
                                    ".$locartion_dr['group_layout']."
                                </select>
                            </div>
                      </div>
                       <input type='hidden' name='location_area' id='edit_location_area' value='".$info['area_id']."'>
                        <div class='col-md-12'>
                            <div class='form-group address_model Edit_Location_area_dropdown'>
                                <label class='form-label' for='co-fn'>Select Area</label>
                                <div class='form-control-wrap'>
                                    <select class='form-select edit_area_selected Edit_location_area_input_field' name='area' id='edit_area_select' data-search='on' >
                                        ".$location_area."
                                    </select>
                                </div>
                            </div>
                        </div>
                      <div class='col-sm-6'>
                            <label class='form-label' for='co-fn'>Select State <span class='text-danger'>*</span></label>
                            <div class='form-control-wrap'>
                                <select class='form-select' name='state_id' id='edit_state_id' data-search='on'>
                                    <option value=''>Select State</option>
                                    ".$state_dr."
                                </select>
                            </div>
                        </div>
                      <div class='col-sm-6'>
                        <label class='form-label' for='co-fn'>Pincode</label>
                        <input class='form-control bg-image-none address_from_pincode_field_bc_color' value='".$info['pincode']."' id='edit_pincode' name='pincode' type='text' placeholder='Pincode' readonly='readonly'  >
                      </div> 
                  </div> 
                  <div class='d-sm-flex'>
                    <h1 class='h6 pt-3 text-sm-start'>Address</h1>
                  </div>                
                  <div class='row gx-2 gy-2'>
                        <div class='col-sm-6'>
                          <label class='form-label' for='co-fn'>GST Name </label> 
                          <input class='form-control bg-image-none' id='gst_name' value='".$info['gst_name']."' name='gst_name' type='text' placeholder='GST Name'>
                        </div>
                        <div class='col-sm-6'>
                          <label class='form-label' for='co-fn'>GSTIN Number</label>
                          <input class='form-control bg-image-none' type='text' value='".$info['gstin_number']."' name='gstin_number' id='gstin_number' placeholder='GSTIN Number'>
                        </div>
                  </div>
                  <div class='pt-2'>
                    <button class='btn btn-primary d-block w-100' type='submit'>Update Address</button>
                  </div>
              </div>
            ";
        return $layout;
    }   


    function editAddressPopup($id='')
    {   
        $_SESSION['new_shipping_address_key'] = $this->generateRandomString("40");
        $add_id     = $this->decryptData($id);
        $info       = $this->getDetails(CUSTOMER_ADDRESS_TBL,"*","id='".$add_id."'");
        $info       = $this->editPagePublish($info);
        $layout     = '
         <div class="modal-body">
            <input type="hidden" value="'.$_SESSION['new_shipping_address_key'].'" name="fkey">
            <input type="hidden" value="'.$id.'" name="token" id="token">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name :</label>
                        <input type="text" name="name" id="name" value="'.$info["user_name"].'" class="form-control" placeholder="First name: *">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Mobile :</label>
                        <input type="text" name="mobile" id="mobile" value="'.$info["mobile"].'" class="form-control" placeholder="Phone: *">
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <label for="address">Address :</label>
                        <textarea type="text" name="address" id="address" class="form-control" placeholder="Address: *">'.$info["address"].'</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="landmark">Landmark :</label>
                        <input type="text" name="landmark" id="landmark" value="'.$info["landmark"].'" class="form-control" placeholder="landmark: *">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="city">City :</label>
                        <input type="text" name="city" id="city" value="'.$info["city"].'" class="form-control" placeholder="city: *">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="state">State :</label>
                        <input type="text" name="state" id="state" value="'.$info["state"].'" class="form-control" placeholder="state: *">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pincode">Pincode :</label>
                        <input type="text" name="pincode" required id="pincode" value="'.$info["pincode"].'" class="form-control" placeholder="pincode: *">
                    </div>
                </div>
               </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            
            </div>';
        return $layout;
    }

    // // Remove cart shipping address

    // function removeShippingAddress($id='')
    // {    
    //  $address_id     = $this->decryptData($id);
    //  $curr       = date("Y-m-d H:i:s");
    //  $user_id    = $_SESSION['user_session_id'];
        
    //      $q  = "UPDATE ".CUSTOMER_ADDRESS_TBL." SET
    //          delete_status       = '1',
    //          updated_at          = ''".$curr."' 
    //          WHERE id            = '".$a."ddress_id' AND user_id='".$user_id."'  ";
    //      $exe = $this->exeQuery($q);
    //      if ($exe) {
    //          return 1;
    //      }else{
    //          return  "Unexpected Error Occurred";
    //      }
    // }

    // Remove cart shipping address

    function removeShippingAddress($result)
    {   
        $layout  = "";
        $id      = $this->decryptData($result);
        $curr    = date("Y-m-d H:i:s");
        $user_id = $_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];

        // Check atleast one shippinf address

        $check_shp_add = $this->getDetails(CUSTOMER_ADDRESS_TBL,"id","delete_status='0' AND user_id='".$user_id."' AND id!='".$id."' ORDER BY id ASC LIMIT 1 " );


        if($check_shp_add!=NUll) {

            $query  = "UPDATE ".CUSTOMER_ADDRESS_TBL." SET
                        default_address = '1',
                        updated_at = '".$curr."' WHERE id='".$check_shp_add['id']."' "; 
            $exe    = $this->exeQuery($query);

            // Make remainig one address should be default

            $query1 = "UPDATE ".CART_TBL." SET 
                delivery_id     = '".$check_shp_add['id']."', 
                shipping_id     = '".$check_shp_add['id']."',
                shipping_status = '1'
                WHERE id        ='".$cart_id."' ";
            $exe1  = $this->exeQuery($query1);


            $query2  = "UPDATE ".CUSTOMER_ADDRESS_TBL." SET
                        delete_status = '1',
                        updated_at    = '".$curr."' WHERE id='".$id."' "; 
            $exe2    = $this->exeQuery($query2);

            if($exe2){
                return "0`"."Address has been removed";
            }else{
                return "1`"."Sorry!! Unexpected Error Occurred. Please try again.";
            }   
        } else {
            return "2`"."User must have at least one shipping address.";
        }
    }

    function editCartShippingAddress($data)
    {   

        $layout = "";
        if(isset($_SESSION['new_shipping_address_key'])){
            if($this->cleanString($data['fkey']) == $_SESSION['new_shipping_address_key']){
                    $curr       = date("Y-m-d H:i:s");
                    $user_id    = $_SESSION['user_session_id'];
                    $address_id = $this->decryptData($data['token']);
                    $check      = $this->check_query(CUSTOMER_ADDRESS_TBL,"id","user_id= '".$user_id."' AND delete_status='0' ");
                    $default    = $check==0 ? "1": "0";
                    $state      = $this->getDetails(STATE_TBL,"name","id='".$data['state_id']."'");
                    $city       = $this->getDetails(LOCATIONGROUP_TBL,"group_name","id='".$data['city']."'");
                    $location   = $this->getDetails(LOCATION_TBL,"location","id='".$data['location_area']."'");
                    $check      = $this->check_query(CUSTOMER_ADDRESS_TBL,"id","user_id= '".$user_id."' AND id='".$address_id."' AND delete_status='0' ");
                    $data       = $this->cleanStringData($data);

                    $cus_name    = $this->hyphenize($data['name']);
                    $check_token = $this->check_query(CUSTOMER_ADDRESS_TBL,"id"," token='".$data['name']."' AND id!='".$address_id."' ");
                    if ($check_token==0) {
                          $token = $cus_name;
                    }else{
                          $token = $cus_name."-".$this->generateRandomString("5");
                    }

                    if($check){
                        $query = "UPDATE ".CUSTOMER_ADDRESS_TBL." SET 
                                token           = '".$token."',
                                user_id         = '".$user_id."',
                                user_name       = '".$this->cleanString($data['name'])."',
                                mobile          = '".$this->cleanString($data['mobile'])."',
                                address         = '".$this->cleanString($data['address'])."',
                                city            = '".$this->cleanString($city['group_name'])."',
                                city_id         = '".($data['city'])."',
                                area_name       = '".($location['location'])."',
                                area_id         = '".$this->cleanString($data['location_area'])."',
                                state_id        = '".$data['state_id']."',
                                state_name      = '".$state['name']."',
                                pincode         = '".$this->cleanString($data['pincode'])."',
                                gst_name        = '".$this->cleanString($data['gst_name'])."',
                                gstin_number    = '".$this->cleanString($data['gstin_number'])."',
                                landmark        = '".$this->cleanString($data['landmark'])."',
                                delete_status   = '0',
                                default_address = '".$default."',
                                status          = '1',
                                created_at      = '".$curr."',
                                updated_at      = '".$curr."'
                                WHERE id        = '".$address_id."' ";
                        $exe = $this->exeQuery($query);
                        // $last_id = mysqli_insert_id();
                        if($exe){
                            // if (isset($_SESSION['user_insert_id'])) {
                            //  $check_add = $this->check_query(CUSTOMER_ADDRESS_TBL,"id","user_id= '".$user_id."' AND delete_status='0' ");
                            //  if ($check_add==1) {
                            //      $qe = "UPDATE ".CART_TBL." SET 
                            //          shipping_id     = '".$last_id."',   
                            //          updated_at      = ''".$curr."' 
                            //          WHERE id        = '".$_SESSION['user_insert_id']."' ";
                            //      $qe = $this->exeQuery($qu);
                            //  }
                            // }
                            unset($_SESSION['new_shipping_address_key']);
                            return 1;
                        }else{
                            return "Sorry!! Unexpected Error Occurred. Please try again.";
                        }
                    }else{
                            return "Sorry!! Unexpected Error Occurred. Please try again.1";
                    }
                
            }else{
                return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
            }
        }else{
            return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
        }
    }


    function payment_method($value="")
    {        
        $curr = date("Y-m-d H:i:s");
        $q = "UPDATE  ".CART_TBL." SET 
            payment_method      = '".$value."',
            updated_at          = '".$curr."' WHERE id='".$_SESSION["user_cart_id"]."' ";
        $exe = $this->exeQuery($q);
        if ($exe) {
            return 1;
        }else{
            return  "Unexpected Error Occurred";
        }
    }

    function cashOndelivery($data="")
    {
        $user_id        = $_SESSION['user_session_id'];
        $cart_id        = @$_SESSION['user_cart_id'];
        $curr           = date("Y-m-d H:i:s");  
        $today          = date("Y-m-d");
        $info           = $this -> getDetails(ORDER_TBL, "order_no"," 1 ORDER BY order_no DESC LIMIT 1");
        $order_no       = (($info==NULL)? 1 : $info['order_no']+1 );
        $cart_info      = $this->getDetails(CART_TBL,"*","id='".$cart_id."' ");    
        $order_uid      = 'Z'.str_pad($order_no, 5,0,STR_PAD_LEFT);
        $total_amount   = $cart_info['sub_total'] + $cart_info['total_tax'];

        // Update Coupon Data

        if($cart_info['coupon_id']!=0)
        {
            $update_coupon = " 
                coupon_id     = '".$cart_info['coupon_id']."',
                coupon_value  = '".$cart_info['coupon_value']."',
                coupon_code   = '".$cart_info['coupon_code']."',
                coupon_type   = '".$cart_info['coupon_type']."',
                coupon_status = '".$cart_info['coupon_status']."',
            ";
        }else{
            $update_coupon  = "";
        }


        $qu = "INSERT INTO ".ORDER_TBL." SET 
                user_id                     = '".$user_id."',
                shipping_type               = '".$cart_info['shipping_type']."', 
                shipping_value              = '".$cart_info['shipping_value']."',
                shipping_tax                = '".$cart_info['shipping_tax']."',
                shipping_tax_value          = '".$cart_info['shipping_tax_value']."',
                shipping_cost               = '".$cart_info['shipping_cost']."',
                sub_total                   = '".$cart_info['sub_total']."', 
                total_tax                   = '".$cart_info['total_tax']."', 
                sgst                        = '".$cart_info['sgst']."',
                cgst                        = '".$cart_info['cgst']."',
                igst                        = '".$cart_info['igst']."',
                sgst_amt                    = '".$cart_info['sgst_amt']."',
                cgst_amt                    = '".$cart_info['cgst_amt']."',
                igst_amt                    = '".$cart_info['igst_amt']."',
                total_amount                = '".$total_amount."',    
                order_address               = '".$cart_info['shipping_id']."',
                vendor_commission           = '".$cart_info['vendor_commission']."',
                vendor_commission_tax       = '".$cart_info['vendor_commission_tax']."',    
                vendor_payment_charge       = '".$cart_info['vendor_payment_charge']."',    
                vendor_payment_tax          = '".$cart_info['vendor_payment_tax']."',
                vendor_shipping_charge      = '".$cart_info['vendor_shipping_charge']."',    
                vendor_shipping_tax         = '".$cart_info['vendor_shipping_tax']."',
                vendor_commission_amt       = '".$cart_info['vendor_commission_amt']."',    
                vendor_commission_tax_amt   = '".$cart_info['vendor_commission_tax_amt']."',        
                vendor_payment_charge_amt   = '".$cart_info['vendor_payment_charge_amt']."',        
                vendor_payment_tax_amt      = '".$cart_info['vendor_payment_tax_amt']."',    
                vendor_shipping_charge_amt  = '".$cart_info['vendor_shipping_charge_amt']."',        
                vendor_shipping_tax_amt     = '".$cart_info['vendor_shipping_tax_amt']."',    
                payment_type                = 'cod',  
                online_payment              = '".$cart_info['total_amount']."',
                order_date                  = '".$today."',
                notes                       = NULL,
                order_status                = '0',     
                status                      = '1',
                order_device                = 'website',
                ".$update_coupon."
                created_at                  = '".$curr."',
                updated_at                  = '".$curr."' ";
            $order_id     = $this->lastInserID($qu);
            $_SESSION['cart_order_id'] = $order_id;
            $query = "SELECT * FROM ".CART_ITEM_TBL." WHERE cart_id= '".$cart_id."' ";
            $result = $this->exeQuery($query);
            if(mysqli_num_rows($result) > 0){
                $i = 1;
                $q="";
                while($list = mysqli_fetch_array($result)){
                    $product= $this->getDetails(VENDOR_PRODUCTS_TBL,"id,stock,product_id","product_id='".$list['product_id']."' AND variant_id='".$list['variant_id']."' AND vendor_id='".$list['vendor_id']."' "); 
                    $stock  = $product['stock'] - $list['qty'];

                    // Update Coupon Data


                    if($list['coupon_id']!=0)
                    {
                        $update_coupon     = " 
                            coupon_id     = '".$list['coupon_id']."',
                            coupon_value  = '".$list['coupon_value']."',
                            coupon_code   = '".$list['coupon_code']."',
                            coupon_type   = '".$list['coupon_type']."',
                            coupon_status = '".$list['coupon_status']."',
                        ";
                    }else{
                        $update_coupon = "";
                    }

                    $q = "INSERT INTO ".ORDER_ITEM_TBL." SET 
                        user_id                     = '".$user_id."',    
                        product_id                  = '".$list['product_id']."',
                        variant_id                  = '".$list['variant_id']."',
                        category_id                 = '".$list['category_id']."',
                        sub_category_id             = '".$list['sub_category_id']."',
                        vendor_id                   = '".$list['vendor_id']."',
                        cart_id                     = '".$list['cart_id']."',
                        tax_amt                     = '".$list['tax_amt']."',
                        order_id                    = '".$order_id."',
                        total_amount                = '".$list['total_amount']."',
                        vendor_commission           = '".$list['vendor_commission']."',
                        vendor_commission_tax       = '".$list['vendor_commission_tax']."',    
                        vendor_payment_charge       = '".$list['vendor_payment_charge']."',    
                        vendor_payment_tax          = '".$list['vendor_payment_tax']."',
                        vendor_shipping_charge      = '".$list['vendor_shipping_charge']."',    
                        vendor_shipping_tax         = '".$list['vendor_shipping_tax']."',
                        vendor_commission_amt       = '".$list['vendor_commission_amt']."',    
                        vendor_commission_tax_amt   = '".$list['vendor_commission_tax_amt']."',        
                        vendor_payment_charge_amt   = '".$list['vendor_payment_charge_amt']."',        
                        vendor_payment_tax_amt      = '".$list['vendor_payment_tax_amt']."',    
                        vendor_shipping_charge_amt  = '".$list['vendor_shipping_charge_amt']."',        
                        vendor_shipping_tax_amt     = '".$list['vendor_shipping_tax_amt']."',      
                        vendor_invoice_number       = '".$list['vendor_invoice_number']."',
                        price                       = '".$list['price']."',
                        tax_price                   = '".$list['tax_price']."',
                        tax_type                    = '".$list['tax_type']."',
                        final_price                 = '".$list['final_price']."',   
                        qty                         = '".$list['qty']."',        
                        size                        = '".$list['size']."',
                        sub_total                   = '".$list['sub_total']."',
                        total_tax                   = '".$list['total_tax']."',
                        sgst                        = '".$list['sgst']."',
                        cgst                        = '".$list['cgst']."',
                        igst                        = '".$list['igst']."',
                        sgst_amt                    = '".$list['sgst_amt']."',
                        cgst_amt                    = '".$list['cgst_amt']."',
                        igst_amt                    = '".$list['igst_amt']."',
                        shipping_charge_status      = '".$list['shipping_status']."',
                        handling                    = '".$list['handling']."',
                        handling_amount             = '".$list['handling_amount']."',
                        shipping_value              = '".$list['shipping_value']."',
                        shipping_tax                = '".$list['shipping_tax']."',
                        shipping_tax_value          = '".$list['shipping_tax_value']."',
                        shipping_cost               = '".$list['shipping_cost']."',
                        status                      = '1',
                        vendor_response             = '0',
                        vendor_accept_status        = '0',  
                        ".$update_coupon."
                        created_at                  = '".$curr."',
                        updated_at                  = '".$curr."' ";
                    $exe        = $this->exeQuery($q);
                    $product= $this->getDetails(VENDOR_PRODUCTS_TBL,"id,stock,product_id","product_id='".$list['product_id']."' AND variant_id='".$list['variant_id']."' AND vendor_id='".$list['vendor_id']."' "); 
                    $stock      = $product['stock'] - $list['qty'];
                    $que        = "UPDATE ".VENDOR_PRODUCTS_TBL." SET  stock ='".$stock."' WHERE product_id='".$list['product_id']."' AND vendor_id='".$list['vendor_id']."' ";
                    $exe = $this->exeQuery($que);
                }
            }
            $update_cart_status   = $this->changeCartOrderStatus();
            $update_order_status  = $this->updateOrderStatus();
            if ($update_order_status) {
                unset($_SESSION['cart_order_id']);
                unset($_SESSION['user_cart_id']);
                return 1;
            }else{
                return  "Unexpected Error Occurred";
            }
    }

    function updateOrderStatus()
    {
        $order_id       = $_SESSION['cart_order_id'];
        $cart_id        = $_SESSION['user_cart_id'];
        $user_id        = $_SESSION['user_session_id'];
        $info           = $this -> getDetails(ORDER_TBL, "id,order_no"," 1 ORDER BY order_no DESC LIMIT 1");
        $order_no       = $info['order_no']+1;
        $order_uid      = 'Z'.str_pad($order_no, 5,0,STR_PAD_LEFT);
        $q = "UPDATE ".ORDER_TBL." SET  payment_status ='1',order_no='".$order_no."',order_uid='".$order_uid."'  WHERE id='".$order_id."' ";
        $exe = $this->exeQuery($q);
        if ($exe) {

            $email_info     = $this->getDetails(CUSTOMER_TBL,'id,name,email,token', " id ='".$user_id."' ");
            $sender         = COMPANY_NAME;
            $sender_mail    = NO_REPLY;
            $subject        = "Order ".$order_uid." has been placed on ".date("d.m.Y");
            $receiver       = $this->cleanString($email_info['email']);
            $message        = $this->customerInvoice($order_id);
            $send_mail      = $this->send_mail($sender_mail,$receiver,$subject,$message);
            $order_vendors  = $this->getOrderVendors($order_id);
            $vendor_Orders  = $this->groupVendorOrders($order_id);
            return 1;
        }
    }

    
    // Grouping of vendors for the order

    function groupVendorOrders($order_id){
        $vendor_grouping= array();
        $query="SELECT vendor_id FROM ".ORDER_ITEM_TBL." WHERE order_id='".$order_id."' GROUP BY vendor_id ";
        $exe = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0){
            while($list = mysqli_fetch_array($exe)){
               $create_order = $this->createVendorOrder($order_id,$list['vendor_id']);
            }
        }
    }

    // Create Vendor Orders

    function createVendorOrder($order_id,$vendor_id){
        $user_id        = $_SESSION['user_session_id'];
        $cart_id        = @$_SESSION['user_cart_id'];
        $curr           = date("Y-m-d H:i:s");  
        $today          = date("Y-m-d");
        $info           = $this -> getDetails(ORDER_TBL, "*"," id='".$order_id."'");
        $q= "INSERT INTO ".VENDOR_ORDER_TBL." SET 
            vendor_id           = '".$vendor_id."',
            order_id            = '".$order_id."',
            order_address       = '".$info['order_address']."',
            order_uid           = '".$info['order_uid']."',
            user_id             = '".$user_id."',
            order_date          = '".$today."',
            notes               = '".$info['notes']."',
            order_status        = '0',  
            status              = '1',
            order_device        = 'website',
            created_at          = '".$curr."',
            updated_at          = '".$curr."' ";
        $vendor_order_id = $this->lastInserID($q);
        
        // Insert Order items
        $commission_per_array      = array();
        $vendors_payment_array     = array();
        $vendors_shipping_array    = array();
        $vendors_commission_array  = array();
        $sub_total_array           = array();
        $total_tax_array           = array();
        $total_amount_array        = array();
        $sgst_amt_array            = array();
        $cgst_amt_array            = array();
        $igst_amt_array            = array();

        $oq = "SELECT * FROM ".ORDER_ITEM_TBL." WHERE order_id='".$order_id."' AND vendor_id='".$vendor_id."' ";
        $oq_exe = $this->exeQuery($oq);
        if(mysqli_num_rows($oq_exe) > 0){
            while($list = mysqli_fetch_array($oq_exe)){      

                $commission_per_array[]     = $list['vendor_commission'];                    
                $vendors_payment_array[]    = $list['vendor_payment_charge_amt']; 
                $vendors_shipping_array[]   = $list['vendor_shipping_charge_amt'];
                $vendors_commission_array[] = $list['vendor_commission_amt'];
                $sub_total_array[]          = $list['sub_total'];
                $total_tax_array[]          = $list['total_tax'];
                $total_amount_array[]       = $list['total_amount'];
                $sgst_amt_array[]           = $list['sgst_amt'];
                $cgst_amt_array[]           = $list['cgst_amt'];
                $igst_amt_array[]           = $list['igst_amt'];

                $info           = $this->getDetails(VENDOR_ORDER_TBL,"id"," 1 ORDER BY id DESC LIMIT 1");
                $invoice_no     = $info['id'];
                $invoice_uid    = 'V00'.str_pad($invoice_no, 3,0,STR_PAD_LEFT);

                $cart_q = "UPDATE ".CART_ITEM_TBL." SET
                        vendor_invoice_number = '".$invoice_uid."'
                        WHERE cart_id = '".$list['cart_id']."' AND vendor_id='".$list['vendor_id']."' ";
                $cart_exe = $this->exeQuery($cart_q); 

                $cart_q = "UPDATE ".ORDER_ITEM_TBL." SET
                        vendor_invoice_number = '".$invoice_uid."'
                        WHERE order_id ='".$order_id."' AND vendor_id='".$list['vendor_id']."' ";
                $cart_exe = $this->exeQuery($cart_q); 

                $iq = "INSERT INTO ".VENDOR_ORDER_ITEM_TBL." SET
                        user_id                     = '".$user_id."', 
                        vendor_order_id             = '".$vendor_order_id."',
                        product_id                  = '".$list['product_id']."',
                        variant_id                  = '".$list['variant_id']."',
                        category_id                 = '".$list['category_id']."',
                        sub_category_id             = '".$list['sub_category_id']."',
                        vendor_id                   = '".$list['vendor_id']."',
                        order_id                    = '".$order_id."',
                        cart_id                     = '".$list['cart_id']."',
                        order_item_id               = '".$list['id']."',
                        coupon_id                   = '".$list['coupon_id']."',
                        coupon_value                = '".$list['coupon_value']."',  
                        coupon_status               = '".$list['coupon_status']."', 
                        total_amount                = '".$list['total_amount']."',
                        vendor_commission           = '".$list['vendor_commission']."',
                        vendor_commission_tax       = '".$list['vendor_commission_tax']."',    
                        vendor_payment_charge       = '".$list['vendor_payment_charge']."',    
                        vendor_payment_tax          = '".$list['vendor_payment_tax']."',
                        vendor_shipping_charge      = '".$list['vendor_shipping_charge']."',    
                        vendor_shipping_tax         = '".$list['vendor_shipping_tax']."',
                        vendor_commission_amt       = '".$list['vendor_commission_amt']."',    
                        vendor_commission_tax_amt   = '".$list['vendor_commission_tax_amt']."',        
                        vendor_payment_charge_amt   = '".$list['vendor_payment_charge_amt']."',        
                        vendor_payment_tax_amt      = '".$list['vendor_payment_tax_amt']."',    
                        vendor_shipping_charge_amt  = '".$list['vendor_shipping_charge_amt']."',        
                        vendor_shipping_tax_amt     = '".$list['vendor_shipping_tax_amt']."',      
                        vendor_invoice_number       = '".$invoice_uid."',
                        price                       = '".$list['price']."',
                        tax_price                   = '".$list['tax_price']."',
                        tax_amt                     = '".$list['tax_amt']."',
                        tax_type                    = '".$list['tax_type']."',
                        final_price                 = '".$list['final_price']."',   
                        qty                         = '".$list['qty']."',        
                        size                        = '".$list['size']."',
                        sub_total                   = '".$list['sub_total']."',
                        total_tax                   = '".$list['total_tax']."',
                        sgst                        = '".$list['sgst']."',
                        cgst                        = '".$list['cgst']."',
                        igst                        = '".$list['igst']."',
                        sgst_amt                    = '".$list['sgst_amt']."',
                        cgst_amt                    = '".$list['cgst_amt']."',
                        igst_amt                    = '".$list['igst_amt']."',
                        status                      = '1',
                        created_at                  = '".$curr."',
                        updated_at                  = '".$curr."' ";
                $q_exe = $this->exeQuery($iq); 

            }
           
        }
        $payment    = array_sum($vendors_payment_array);
        $shipping   = array_sum($vendors_shipping_array);
        $commission = array_sum($vendors_commission_array);
        $sub_total  = array_sum($sub_total_array);
        $total_tax  = array_sum($total_tax_array);
        $total      = array_sum($total_amount_array);
        $sgst_total = array_sum($sgst_amt_array);
        $cgst_total = array_sum($cgst_amt_array);
        $igst_total = array_sum($igst_amt_array);
        $total_amount = $sub_total + $total_tax;

        // Vendor Order commission calculation

        // $order_commission = array_sum($vendors_commission_array)/count($commission_per_array);

        // $vd_cm_per            =  1+($order_commission/100);
        // $vd_cm_amt            =  ($total*$vd_cm_per)-$total;
       




        // Update the vendor Order values
        
        $uq = "UPDATE ".VENDOR_ORDER_TBL." SET
                sub_total                   = '".$sub_total."',  
                total_tax                   = '".$total_tax."',  
                sgst_amt                    = '".$sgst_total."',
                cgst_amt                    = '".$cgst_total."',
                igst_amt                    = '".$igst_total."',
                vendor_payment_total        = '".$payment."',
                vendor_commission_total     = '".$commission."',
                vendor_shipping_total       = '".$shipping."',
                total_amount                = '".$total_amount."',       
                payment_type                = 'cod',  
                online_payment              = '".$total."'
                WHERE id='".$vendor_order_id."' ";
        $uq_exe = $this->exeQuery($uq);

        // Send Vendor Order emails

        // if ($uq_exe) {
        //     $email_info     = $this->getDetails(VENDOR_TBL,'*', " id ='".$vendor_id."' ");
        //     $sender         = COMPANY_NAME;
        //     $sender_mail    = NO_REPLY;
        //     $subject        = "Order ".$order_uid." has been placed on ".date("d.m.Y");
        //     $receiver       = $this->cleanString($email_info['email']);
        //     $message        = $this->vendorInvoice($order_id, $order_vendors[$i]);
        //     $send_mail      = $this->send_mail($sender_mail,$receiver,$subject,$message);

        //     if ($send_mail) {
        //         return 1;
        //     }else{
        //         return 0;
        //     }
        // }
        return 1;
    }
    


    function getOrderVendors($order_id)
    {   
        $ids= array();
        $query="SELECT vendor_id FROM ".ORDER_ITEM_TBL." WHERE order_id='".$order_id."' ";
        $exe = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0){
            while($list = mysqli_fetch_array($exe)){
                $ids[] =  $list['vendor_id']; 
                $ids   =  array_unique($ids);              
            }
        }
        return $ids;
    }

    function fullOnlinePayment($data,$json,$amount)
    {
        $user_id        = $_SESSION['user_session_id'];
        $cart_id        = @$_SESSION['user_cart_id']; 
        $curr           = date("Y-m-d H:i:s");  
        $today          = date("Y-m-d");
        $info           = $this -> getDetails(ORDER_TBL, "order_no"," 1 ORDER BY order_no DESC LIMIT 1");
        $order_no       = $info['order_no']+1;
        $cart_info      = $this->getDetails(CART_TBL,"*","id='".$cart_id."' ");    
        $order_uid      = 'Z'.str_pad($order_no, 5,0,STR_PAD_LEFT);

        // Update Coupon Data

        if($cart_info['coupon_id']!=0)
        {
            $update_coupon = " 
                coupon_id     = '".$cart_info['coupon_id']."',
                coupon_value  = '".$cart_info['coupon_value']."',
                coupon_code   = '".$cart_info['coupon_code']."',
                coupon_type   = '".$cart_info['coupon_type']."',
                coupon_status = '".$cart_info['coupon_status']."',
            ";
        }else{
             $update_coupon  = "";
        }

        $qu = "INSERT INTO ".ORDER_TBL." SET 
                user_id                     = '".$user_id."',
                shipping_type               = '".$cart_info['shipping_type']."', 
                shipping_value              = '".$cart_info['shipping_value']."',
                shipping_tax                = '".$cart_info['shipping_tax']."',
                shipping_tax_value          = '".$cart_info['shipping_tax_value']."',
                shipping_cost               = '".$cart_info['shipping_cost']."',
                sub_total                   = '".$cart_info['sub_total']."', 
                total_tax                   = '".$cart_info['total_tax']."', 
                sgst                        = '".$cart_info['sgst']."',
                cgst                        = '".$cart_info['cgst']."',
                igst                        = '".$cart_info['igst']."',
                sgst_amt                    = '".$cart_info['sgst_amt']."',
                cgst_amt                    = '".$cart_info['cgst_amt']."',
                igst_amt                    = '".$cart_info['igst_amt']."',
                total_amount                = '".$cart_info['total_amount']."',    
                order_address               = '".$cart_info['shipping_id']."',
                vendor_commission           = '".$cart_info['vendor_commission']."',
                vendor_commission_tax       = '".$cart_info['vendor_commission_tax']."',    
                vendor_payment_charge       = '".$cart_info['vendor_payment_charge']."',    
                vendor_payment_tax          = '".$cart_info['vendor_payment_tax']."',
                vendor_shipping_charge      = '".$cart_info['vendor_shipping_charge']."',    
                vendor_shipping_tax         = '".$cart_info['vendor_shipping_tax']."',
                vendor_commission_amt       = '".$cart_info['vendor_commission_amt']."',    
                vendor_commission_tax_amt   = '".$cart_info['vendor_commission_tax_amt']."',        
                vendor_payment_charge_amt   = '".$cart_info['vendor_payment_charge_amt']."',        
                vendor_payment_tax_amt      = '".$cart_info['vendor_payment_tax_amt']."',    
                vendor_shipping_charge_amt  = '".$cart_info['vendor_shipping_charge_amt']."',        
                vendor_shipping_tax_amt     = '".$cart_info['vendor_shipping_tax_amt']."',    
                payment_type                = 'online',  
                online_payment              = '".$cart_info['total_amount']."',
                order_date                  = '".$today."',
                order_status                = '0',     
                status                      = '1',
                ".$update_coupon."
                created_at                  = '".$curr."',
                updated_at                  = '".$curr."' ";
            $order_id     = $this->lastInserID($qu);
            $_SESSION['cart_order_id'] = $order_id;
            $gateway_info = $this->confrimOrderPayment($data,$json,$amount,$order_id);
            $query = "SELECT * FROM ".CART_ITEM_TBL." WHERE cart_id= '".$cart_id."' ";
            $result = $this->exeQuery($query);
            if(mysqli_num_rows($result) > 0){
                $i = 1;
                $q="";
                while($list = mysqli_fetch_array($result)){
                   $product= $this->getDetails(VENDOR_PRODUCTS_TBL,"id,stock,product_id","product_id='".$list['product_id']."' AND variant_id='".$list['variant_id']."' AND vendor_id='".$list['vendor_id']."' "); 
                    $stock      = $product['stock'] - $list['qty'];


                    if($list['coupon_id']!=0)
                    {
                        $update_coupon     = " 
                            coupon_id     = '".$list['coupon_id']."',
                            coupon_value  = '".$list['coupon_value']."',
                            coupon_code   = '".$list['coupon_code']."',
                            coupon_type   = '".$list['coupon_type']."',
                            coupon_status = '".$list['coupon_status']."',
                        ";
                    }else{
                        $update_coupon = "";
                    }


                    $q = "INSERT INTO ".ORDER_ITEM_TBL." SET 
                        user_id                     = '".$user_id."',    
                        product_id                  = '".$list['product_id']."',
                        variant_id                  = '".$list['variant_id']."',
                        category_id                 = '".$list['category_id']."',
                        sub_category_id             = '".$list['sub_category_id']."',
                        vendor_id                   = '".$list['vendor_id']."',
                        cart_id                     = '".$list['cart_id']."',
                        tax_amt                     = '".$list['tax_amt']."',
                        order_id                    = '".$order_id."',
                        total_amount                = '".$list['total_amount']."',
                        vendor_commission           = '".$list['vendor_commission']."',
                        vendor_commission_tax       = '".$list['vendor_commission_tax']."',    
                        vendor_payment_charge       = '".$list['vendor_payment_charge']."',    
                        vendor_payment_tax          = '".$list['vendor_payment_tax']."',
                        vendor_shipping_charge      = '".$list['vendor_shipping_charge']."',    
                        vendor_shipping_tax         = '".$list['vendor_shipping_tax']."',
                        vendor_commission_amt       = '".$list['vendor_commission_amt']."',    
                        vendor_commission_tax_amt   = '".$list['vendor_commission_tax_amt']."',        
                        vendor_payment_charge_amt   = '".$list['vendor_payment_charge_amt']."',        
                        vendor_payment_tax_amt      = '".$list['vendor_payment_tax_amt']."',    
                        vendor_shipping_charge_amt  = '".$list['vendor_shipping_charge_amt']."',        
                        vendor_shipping_tax_amt     = '".$list['vendor_shipping_tax_amt']."',      
                        vendor_invoice_number       = '".$list['vendor_invoice_number']."',
                        price                       = '".$list['price']."',
                        tax_price                   = '".$list['tax_price']."',
                        tax_type                    = '".$list['tax_type']."',
                        final_price                 = '".$list['final_price']."',   
                        qty                         = '".$list['qty']."',        
                        size                        = '".$list['size']."',
                        sub_total                   = '".$list['sub_total']."',
                        total_tax                   = '".$list['total_tax']."',
                        sgst                        = '".$list['sgst']."',
                        cgst                        = '".$list['cgst']."',
                        igst                        = '".$list['igst']."',
                        sgst_amt                    = '".$list['sgst_amt']."',
                        cgst_amt                    = '".$list['cgst_amt']."',
                        igst_amt                    = '".$list['igst_amt']."',
                        shipping_charge_status      = '".$list['shipping_status']."',
                        handling                    = '".$list['handling']."',
                        handling_amount             = '".$list['handling_amount']."',
                        shipping_value              = '".$list['shipping_value']."',
                        shipping_tax                = '".$list['shipping_tax']."',
                        shipping_tax_value          = '".$list['shipping_tax_value']."',
                        shipping_cost               = '".$list['shipping_cost']."',
                        status                      = '1',
                        vendor_response             = '0',
                        vendor_accept_status        = '0',  
                        ".$update_coupon."
                        created_at                  = '".$curr."',
                        updated_at                  = '".$curr."' ";
                    $exe = $this->exeQuery($q);
                    $product= $this->getDetails(VENDOR_PRODUCTS_TBL,"id,stock,product_id","product_id='".$list['product_id']."' AND variant_id='".$list['variant_id']."' AND vendor_id='".$list['vendor_id']."' "); 
                    $stock      = $product['stock'] - $list['qty'];
                    $que        = "UPDATE ".VENDOR_PRODUCTS_TBL." SET  stock ='".$stock."' WHERE product_id='".$list['product_id']."' AND vendor_id='".$list['vendor_id']."' ";
                    $exe = $this->exeQuery($que);
                    }
            }   
            $update_cart_status     = $this->changeCartOrderStatus();
            $update_order_status    = $this->updateOrderStatus();
            if ($update_order_status) {
                unset($_SESSION['cart_order_id']);
                unset($_SESSION['user_cart_id']);
                return 1;
            }else{
                return  "Unexpected Error Occurred";
            }
    }



    function confrimOrderPayment($data,$json,$amount,$order_id)
    {
        $amount_rs  = ($amount/100);
        $cart_id    = @$_SESSION['user_cart_id'];
        $user_id    = $_SESSION['user_session_id'];
        $curr       = date("Y-m-d H:i:s");
        $today      = date("Y-m-d");
        $q = "INSERT INTO ".ORDER_RAZORPAY_PAYMENT_TBL." SET 
                cart_id             = '".$cart_id."',
                new_order_id        = '".$order_id."',
                amount              = '".$amount_rs."',
                payment_date        = '".$today."',
                payment_id          = '".$data['id']."',
                entity              = '".$data['entity']."',
                currency            = '".$data['currency']."',
                status              = '".$data['status']."',
                order_id            = '".$data['order_id']."',
                method              = '".$data['method']."',
                amount_refunded     = '".$data['amount_refunded']."',
                refund_status       = '".$data['refund_status']."',
                captured            = '".$data['captured']."',
                card_id             = '".$data['card_id']."',
                bank                = '".$data['bank']."',
                wallet              = '".$data['wallet']."',
                vpa                 = '".$data['vpa']."',
                email               = '".$data['email']."',
                contact             = '".$data['contact']."',
                shopping_order_id   = '".$data['notes']['shopping_order_id']."',
                fee                 = '".$data['fee']."',
                service_tax         = '0',
                error_code          = '".$data['error_code']."',
                error_description   = '".$data['error_description']."',
                response_created_at = '".$data['created_at']."',
                json_data           = '".$json."',
                payment_status      = '1',
                created_at          = '".$curr."',
                updated_at          = '".$curr."'  ";
        //  return $q;
        $exe = $this->exeQuery($q);
        if ($exe) {
            return 1;
        }else{
            return 0;
        }
    }


    function changeCartOrderStatus($wallet="")
    {
        $cart_id        = @$_SESSION['user_cart_id'];
        $q = "UPDATE ".CART_TBL." SET  order_status ='1' WHERE id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        if ($exe) {
            return 1;
        }
    }



                    





}
?>