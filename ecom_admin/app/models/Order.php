<?php
require_once 'Model.php';
require_once 'config/config.php';
require_once 'app/core/classes/PHPMailerAutoload.php';

class Order extends Model
{	
	/*--------------------------------------------
        		 Manage Order List
    ---------------------------------------------*/

    function manageOrdersList($from,$to)
    {
        $layout ="";

        if($from!="" && $from!=0 ) 
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND O.order_date BETWEEN '$from' AND '$to' ";
        } else {
            $date_filter = "";
        }

        $query  ="SELECT O.id,O.sub_total,O.total_tax,O.total_amount,O.shipping_cost,O.coupon_value,O.order_uid,O.payment_type,O.order_status,O.payment_status,O.order_date,O.cancel_status,O.shipping_status,O.deliver_status,O.created_at,C.name,C.mobile,C.email,
                (SELECT COUNT(id) FROM ".ORDER_ITEM_TBL." WHERE order_id=O.id ) as items,
                (SELECT SUM(vendor_commission_amt) FROM ".ORDER_ITEM_TBL." WHERE order_id=O.id ) as total_commission,
                (SELECT SUM(vendor_payment_charge_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id ) as total_payment_charge_amt,
                (SELECT SUM(vendor_shipping_charge_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id ) as total_shipping_charge_amt 
            FROM ".ORDER_TBL." O LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) WHERE 1 ".$date_filter." ORDER BY O.id  DESC";
        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $data_staus       = (($list['order_status']==0) ? 1 :  2 );
                    $status_btn_title = (($list['order_status']==0) ? "Mark as Shipped" :  (($list['order_status']==1) ? "Mark as Paid" : "Send Invoice") );
                    $status_btn       = (($list['order_status']==0) ? "<em class='icon ni ni-truck'></em>" :  (($list['order_status']==1) ? "<em class='icon ni                     ni-money'></em>" : "<em class='icon ni ni-report-profit'></em>") ); 

                    $list['total_commission']          = (int) $list['total_commission'];
                    $list['total_payment_charge_amt']  = (int) $list['total_payment_charge_amt'];
                    $list['total_shipping_charge_amt'] = (int) $list['total_shipping_charge_amt'];


                    $total_charges    =  $list['total_commission'] +  $list['total_payment_charge_amt'] +  $list['total_shipping_charge_amt'];

                    if($list['coupon_value']!="" && $list['coupon_value']!=NUll) {
                        $coupon_value = $list['coupon_value'];
                    } else {
                        $coupon_value = 0;
                    }

                    $layout .="
                                <tr class='nk-tb-item open_enq_model' data-option='".$list['id']."'>
                                        <td class='nk-tb-col '>
                                         ".$i."
                                        </td>
                                        <td class='nk-tb-col'>
                                            <span class='tb-lead'><a href='".COREPATH."orders/orderdetails/".$list['id']."'>".$list['order_uid']."</a></span>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>".date('d/m/Y',strtotime($list['created_at']))."</span>
                                        </td>
                                        <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>".$list['items']." <span class='currency'>".(($list['items'] > 1 )? 'Items' : 'Item')."</span></span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($total_charges)." </span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat((int)$list['sub_total'] + (int)$list['total_tax'] - (int)$coupon_value )." </span>
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                           ".$this->getOrderStatus($list['id'])."
                                        </td>
                                    </tr>

                              ";
                $i++;
                }
        }
        return $layout;
    }

    function getOrderStatus($order_id) 
    {   
        $layout = "";
        $query  = "SELECT * FROM  ".ORDER_ITEM_TBL." WHERE order_id='".$order_id."' ";
        $exe    = $this->exeQuery($query);

        if(mysqli_num_rows($exe) > 0) 
        {
            while ($list = mysqli_fetch_array($exe)) {
                if($list['order_status']==0) {
                    $status_msg   = "Inprocess";
                    $status_class = "text-warning";
                } elseif($list['order_status']==1) {
                    $status_msg   = "Shipped";
                    $status_class = "text-warning";
                } elseif ($list['order_status']==2) {
                    $status_msg   = "Delivered";
                    $status_class = "text-success";
                } elseif ($list['order_status']==3) {
                    $status_msg   = "Returned";
                    $status_class = "text-danger";
                }

                if($list['vendor_response']==1 && $list['vendor_accept_status']==0)
                {
                    $status_msg   = "Rejected";
                    $status_class = "text-danger";
                } elseif ($list['vendor_response']==0) {
                    $status_msg   = "Not Viewed";
                    $status_class = "text-warning";
                }

                $layout .= "<span class='tb-status status_list $status_class'>".$status_msg."</span>";
            }
        }

        return $layout;

    }

    function manageVendorOrdersList($from="",$to="")
    {
        $layout ="";

        if($from!="" && $from!=0 ) 
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to' ";
        } else {
            $date_filter = "";
        }

        $query  ="SELECT VO.id,VO.vendor_id,VO.user_id,VO.order_id,VO.order_uid,VO.shipping_cost,VO.sub_total,VO.total_tax,VO.sgst_amt,VO.cgst_amt,VO.igst_amt,VO.vendor_payment_total,VO.vendor_commission_total,VO.vendor_shipping_total,VO.total_amount,VO.total_payment,VO.order_date,VO.order_status,VO.status,VO.vendor_response,VO.vendor_accept_status,VE.company,VE.mobile,VE.email,
            (SELECT vendor_invoice_number FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id LIMIT 1) as invoiveNumber,
            (SELECT SUM(coupon_value) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id LIMIT 1) as couponValue,
            (SELECT COUNT(id) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id ) as items
          FROM ".VENDOR_ORDER_TBL." VO  LEFT JOIN ".VENDOR_TBL." VE ON (VO.vendor_id=VE.id) WHERE 1 ".$date_filter."  ORDER BY VO.id  DESC";
        $exe    = $this->exeQuery($query);

        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);
                    
                    $check_inprocess  = $this->check_query(VENDOR_ORDER_ITEM_TBL,"order_status","id='".$list['id']."' AND order_status='0' "); 
                    $check_shipped    = $this->check_query(VENDOR_ORDER_ITEM_TBL,"order_status","id='".$list['id']."' AND order_status='1' "); 
                    $check_delivered  = $this->check_query(VENDOR_ORDER_ITEM_TBL,"order_status","id='".$list['id']."' AND order_status='2' "); 
                    $check_returned   = $this->check_query(VENDOR_ORDER_ITEM_TBL,"order_status","id='".$list['id']."' AND order_status='3' "); 

                    if($check_inprocess) {
                        $status_check = "Inprocess";
                        $status_class = "text-warning";
                    } elseif($check_shipped) {
                        $status_check = "Shipped";
                        $status_class = "text-warning";
                    } elseif ($check_delivered) {
                        $status_check = "Delivered";
                        $status_class = "text-success";
                    } elseif ($check_returned) {
                        $status_check = "Returned";
                        $status_class = "text-danger";
                    }

                    $data_staus       = (($list['order_status']==0) ? 1 :  2 );
                    $status           = (($list['order_status']==0) ? "Inprocess" : (($list['order_status']==1) ? "Shipped" : "Delivered") ); 
                    $status_btn_title = (($list['order_status']==0) ? "Mark as Shipped" :  (($list['order_status']==1) ? "Mark as Paid" : "Send Invoice") );
                    $total_charges    = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'] ; 



                    $response       = (($list['vendor_response']==0) ? "Not Viewed" :  (($list['vendor_accept_status']==0) ? "Rejected" :  "Accepted" ) );
                    $response_class = (($list['vendor_response']==0) ? "text-warning" :  (($list['vendor_accept_status']==0) ? "text-danger" :  "text-success" ) );

                    if($list['vendor_response']==1 && $list['vendor_accept_status']== 0)
                    {
                        $status_check = "Rejected";
                        $status_class = "text-danger";
                    }


                    $layout .="
                                <tr class='nk-tb-item open_enq_model' data-option='".$list['id']."'>
                                        <td class='nk-tb-col '>
                                         ".$i."
                                        </td>
                                        <td class='nk-tb-col'>
                                         <span class='tb-lead'><a href='".COREPATH."orders/vendororderdetails/".$list['id']."'>".$list['invoiveNumber']."</a></span>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>".date('d/m/Y',strtotime($list['order_date']))."</span>
                                        </td>
                                        <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['company']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>".$list['items']." <span class='currency'>".(($list['items'] > 1 )? 'Items' : 'Item')."</span></span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($total_charges)." </span>
                                           
                                        </td>
                                         <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($list['sub_total'] + $list['igst_amt'] - $list['couponValue'] )." </span>
                                           
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                            <span class='tb-status $response_class'>$response</span>
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                            ".$this->getVendorOrderStatus($list['id'])."
                                        </td>
                                    </tr>

                              ";
                $i++;
                }
        }
        return $layout;
    }

    function getVendorOrderStatus($order_id) 
    {   
        $layout = "";
        $query  = "SELECT * FROM  ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id='".$order_id."' ";
        $exe    = $this->exeQuery($query);

        if(mysqli_num_rows($exe) > 0) 
        {
            while ($list = mysqli_fetch_array($exe)) {
                if($list['order_status']==0) {
                    $status_msg   = "Inprocess";
                    $status_class = "text-warning";
                } elseif($list['order_status']==1) {
                    $status_msg   = "Shipped";
                    $status_class = "text-warning";
                } elseif ($list['order_status']==2) {
                    $status_msg   = "Delivered";
                    $status_class = "text-success";
                } elseif ($list['order_status']==3) {
                    $status_msg   = "Returned";
                    $status_class = "text-danger";
                }

                if($list['vendor_response']==1 && $list['vendor_accept_status']==0)
                {
                    $status_msg   = "Rejected";
                    $status_class = "text-danger";
                } elseif ($list['vendor_response']==0) {
                    $status_msg   = "Not Viewed";
                    $status_class = "text-warning";
                }

                $layout .= "<span class='tb-status status_list $status_class'>".$status_msg."</span>";
            }
        }

        return $layout;

    }

    // vendor rejected Order list (Not In Use)
    function vendorRejectedOrdersList($from="",$to="")
    {
        $layout ="";

        if($from!="" && $from!=0 ) 
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to' ";
        } else {
            $date_filter = "";
        }

        $query  ="SELECT VO.id,VO.vendor_id,VO.user_id,VO.order_id,VO.order_uid,VO.shipping_cost,VO.sub_total,VO.total_tax,VO.sgst_amt,VO.cgst_amt,VO.igst_amt,VO.vendor_payment_total,VO.vendor_commission_total,VO.vendor_shipping_total,VO.total_amount,VO.total_payment,VO.order_date,VO.order_status,VO.status,VO.vendor_response,VO.vendor_accept_status,VO.response_notes,VE.company,VE.mobile,VE.email,
            (SELECT vendor_invoice_number FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id LIMIT 1) as invoiveNumber,
            (SELECT SUM(coupon_value) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id LIMIT 1) as couponValue,
            (SELECT COUNT(id) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id ) as items
          FROM ".VENDOR_ORDER_TBL." VO  LEFT JOIN ".VENDOR_TBL." VE ON (VO.vendor_id=VE.id) WHERE 1 AND VO.vendor_response='1'  AND VO.vendor_accept_status='0' ".$date_filter." ORDER BY VO.id  DESC";
        $exe    = $this->exeQuery($query);

        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list           = $this->editPagePublish($rows);
                    $total_charges  = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'] ; 
                    $response       = (($list['vendor_response']==0) ? "Not Viewed" :  (($list['vendor_accept_status']==0) ? "Rejected" :  "Accepted" ) );
                    $response_class = (($list['vendor_response']==0) ? "text-warning" :  (($list['vendor_accept_status']==0) ? "text-danger" :  "text-success" ) );
                   
                    $layout .="
                                <tr class='nk-tb-item open_enq_model' data-option='".$list['id']."'>
                                        <td class='nk-tb-col '>
                                         ".$i."
                                        </td>
                                        <td class='nk-tb-col'>
                                         <span class='tb-lead'><a href='".COREPATH."orders/vendororderdetails/".$list['id']."'>".$list['invoiveNumber']."</a></span>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>".date('d/m/Y',strtotime($list['order_date']))."</span>
                                        </td>
                                        <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['company']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>".$list['items']." <span class='currency'>".(($list['items'] > 1 )? 'Items' : 'Item')."</span></span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($total_charges)." </span>
                                           
                                        </td>
                                         <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($list['sub_total'] + $list['igst_amt'] - $list['couponValue'] )." </span>
                                           
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                            <span class='tb-status $response_class'>".$response."</span>
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                            <span class='tb-status'>".$list['response_notes']."</span>
                                        </td>
                                        <td class='nk-tb-col nk-tb-col-tools'>
                                            <ul class='nk-tb-actions gx-1'>
                                                <li>
                                                    <div class='drodown mr-n1'>
                                                        <a href='#' class='dropdown-toggle btn btn-icon btn-trigger' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>

                              ";
                $i++;
                }
        }
        return $layout;
    }

    function getOrderItems($order_id)
    {
        $layout ="";
        $query  ="SELECT O.id,O.user_id,O.variant_id,O.order_id,O.cart_id,O.product_id,O.coupon_value,O.vendor_id,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.qty,O.sub_total,O.total_tax,O.sgst,O.order_status,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.total_amount,O.vendor_response,O.vendor_accept_status,O.response_notes,O.return_status,O.return_reason,O.return_comment,P.product_uid,P.product_name,P.page_url,V.variant_name,O.vendor_id,O.vendor_invoice_number,VD.state_id,VD.state_name,VD.company,OD.order_address,RS.response_status as response_status_msg,RR.return_reason  as return_reason_msg,PU.product_unit
            FROM ".ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) 
                                      LEFT JOIN ".VENDOR_TBL." VD ON (VD.id=O.vendor_id) 
                                      LEFT JOIN ".ORDER_TBL." OD ON (OD.id=O.order_id) 
                                      LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) 
                                      LEFT JOIN ".ORDER_RESPONSE_STATUS_TBL." RS ON (RS.id=O.response_status) 
                                      LEFT JOIN ".RETURN_REASON_TBL." RR ON (RR.id=O.return_reason) 
                                      LEFT JOIN ".PRODUCT_UNIT_TBL." PU ON (PU.id=P.product_unit)  
            WHERE O.order_id=$order_id   ORDER BY  O.vendor_invoice_number ASC";
        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);
                    $info   = $this->getDetails(VENDOR_TBL,"company","id='".$list['vendor_id']."' ");
                    $name   = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

                    $billing_address = $this->getDetails(CUSTOMER_ADDRESS_TBL,"state_id","id=".$list['order_address']." ");

                    if($billing_address['state_id']==$list['state_id']) {
                        $tax_info = "<p>SGST : ".$list['sgst']."% (₹  ".$this->inrFormat($list['sgst_amt']).")</p>
                                        <p>CGST : ".$list['sgst']."% (₹  ".$this->inrFormat($list['cgst_amt']).")</p>";
                    } else {
                        $tax_info = "<p>IGST : ".$list['igst']."% (₹  ".$this->inrFormat($list['igst_amt']).")</p>";
                    }

                    if($list['tax_type']=="inclusive") 
                    {
                        $taxMsg = "<p>( Inclusive of all taxes * )</p>";
                    } else {
                        $taxMsg = "<p>( Exclusive of all taxes * )</p>";
                    }

                    if($list['return_comment']!="") {
                        $return_comment = "<div>Comment : ".$list['return_comment']."</div>";
                    } else {
                        $return_comment = "";
                    }

                    $status_btn_title = (($list['order_status']==0) ? "<span class='text-warning'>Inprocess</span>" :  (($list['order_status']==1) ? "<span class='text-warning'>Shipped</span>" :  (($list['order_status']==2) ? "<span class='text-success'>Delivered</span>" :  "<span class='text-danger'>Returned</span><div>Reason : ".$list['return_reason_msg']." ".$return_comment ." </div>") ) );

                    if($list['vendor_response']==1 && $list['vendor_accept_status']==0)
                    {
                        $status_btn_title = "<span class='text-danger'>Rejected</span>";
                        $response_notes   = "<div>
                                            ( ".$list['response_status_msg']." )
                                            <div>" ;
                        $rejected_price   = "text-danger";                                            
                    } elseif($list['vendor_response']== 0 )  {
                        $status_btn_title = "<span class='text-warning'>Not Seen</span>";
                        $response_notes   = "" ;
                        $rejected_price   = "";                        
                    } else {
                        $response_notes = "";
                        $rejected_price   = "";                        
                    }

                    
                    $vendor_order_info = $this->getDetails(VENDOR_ORDER_ITEM_TBL,"vendor_order_id,order_id","order_item_id='".$list['id']."'");
                    $vendor_invoice_link = "<p>vendor invoice: <a href='".COREPATH."orders/vendororderdetails/".$vendor_order_info['vendor_order_id']."'>".$list['vendor_invoice_number']."</a> / <a href='".COREPATH."orders/previewcustomerinvoice/".$list['id']."' target='_blank'>View Invoice</a> </p>";
                    

                    $product_unit = (($list['product_unit']!="")? "( ".$list['product_unit']." )" : "" );

                    $rating_check = $this->check_query(VENDOR_RATTING_TBL,"id","product_id='".$list['product_id']."' AND vendor_id='".$list['vendor_id']."' AND added_by='".$list['user_id']."' AND order_id='".$list['order_id']."' ");

                    if($rating_check) {

                        $rating_info   = $this->getDetails(VENDOR_RATTING_TBL,"*","product_id='".$list['product_id']."' AND vendor_id='".$list['vendor_id']."' AND added_by='".$list['user_id']."' AND order_id='".$list['order_id']."' ");

                        $sold_by_and_ratings = "
                                        <div class='star_rating star_tbl_list'>
                                            <span class='my-rating-7 ms-2 star_tbl_list'>
                                                <p class='sold_by_info' >Sold by: ".$list['company']."</p>
                                            </span>
                                            <input type='hidden' class='rating_data' name='star_ratings' value='".$rating_info['star_ratings']."' id='rating_data'>
                                            <span class='star_rating_point'>( ".$rating_info['star_ratings']." / 5 )</span>
                                        </div>";

                    } else {
                        $sold_by_and_ratings = "<p>Sold by: ".$list['company']."</p>";
                    }

                    $layout .="
                                <tr>
                                    <td>".$i."</td>
                                    <td><a href='".BASEPATH."product/details/".$list['page_url']."' target='_blank'>".$name."</a>
                                        ".$taxMsg."
                                        ".$tax_info."
                                        ".$sold_by_and_ratings."
                                        ".$vendor_invoice_link."
                                    </td>
                                    <td>".$status_btn_title."
                                        ".$response_notes."
                                    </td>
                                    <td class='$rejected_price'>₹ ".$this->inrFormat($list['price'])."</td>
                                    <td class='$rejected_price'>".$list['qty']." ".$product_unit."</td>
                                    <td class='$rejected_price'>₹ ".$this->inrFormat($list['sub_total']+$list['total_tax'])."</td>
                                </tr>             
                              ";
                $i++;
                }
        }
        return $layout;
    }

    function changeOrderStatus($data)
    {
        $order_id = $this->decryptData($data['order_id']);
        $query = "UPDATE ".ORDER_TBL." SET order_status='".$data['status']."' WHERE id='$order_id' ";
        if($data['status'] ==1){
            $result =1;
        }else{
            $result =2;
        }
        $up_exe = $this->exeQuery($query);
        if($up_exe){
            return $result;
        }
    }

    function orderItemStatusChange($data)
	{	
		$validate_csrf             = $this->validateCSRF($data);
		$curr 			           = date("Y-m-d H:i:s");

		if ($validate_csrf=="success") {

			for ($i=1; $i <= $data['total_item'] ; $i++) {

				$item_id           = $this->decryptData($data['item_id_'.$i]);
				$remarks           = $this->cleanString($data['remarks_'.$i]);
				$remarks           = "";

				if( $data['order_status_'.$i] == 1 ) {
					$remarks       = ",shipping_remarks='".$data['remarks_'.$i]."',shipping_status='1' ";
				} elseif ( $data['order_status_'.$i] == 2) {
					$delevery_date = (( $data['delivery_date_'.$i] == "" ) ? ",delivery_date='".$curr."' " : "" );
					$remarks       = ",delivery_remarks='".$data['remarks_'.$i]."' ".$delevery_date." ,  deliver_status='1' ";
				} elseif ( $data['order_status_'.$i] == 3 ) {
					$remarks       = ",cancel_comment='".$data['remarks_'.$i]."' , cancel_status='1' ";
				}

				// Update order item status in order item table

				$update_item_status  = "UPDATE ".ORDER_ITEM_TBL." SET 
											order_status = '".$data['order_status_'.$i]."' ".$remarks." ,
											updated_at   = '".$curr."' 
										WHERE id='".$item_id."' ";
				$up_exe              = $this->exeQuery($update_item_status);

				$item_details        = $this->getDetails(ORDER_ITEM_TBL,"*"," id='".$item_id."' ");


				// Update order status in order table based on order items status

				$item_pending_to_deliver  = $this->check_query(ORDER_ITEM_TBL,"*","(order_status ='0' OR order_status='1') AND vendor_response='1' AND vendor_accept_status='1' AND order_id='".$item_details['order_id']."' AND id!='".$item_id."'    ");

				if( $item_pending_to_deliver == 0 ) { 
					if( $data['order_status_'.$i] == 3 ) {
						
						$check_other_items  = $this->check_query(ORDER_ITEM_TBL,"id"," (order_status='0' OR order_status='1' OR order_status='2') AND vendor_response='1' AND vendor_accept_status='1' AND order_id='".$item_details['order_id']."' AND  id!='".$item_id."'    ");
						
						if( $check_other_items == 0 ) {
							
							$query3         = " UPDATE ".ORDER_TBL." SET 
													order_status = '".$data['order_status_'.$i]."',
													updated_at   = '".$curr."' 
												WHERE id='".$item_details['order_id']."' ";
							$exe3           = $this->exeQuery($query3);
						} 

					} elseif($data['order_status_'.$i]!=3) {
						
						$check_other_items2 = $this->check_query(ORDER_ITEM_TBL,"id","( order_status='0' OR order_status='1' ) AND vendor_response='1' AND vendor_accept_status='1' AND order_id='".$item_details['order_id']."'  AND id!='".$item_id."' ");
						
						if($check_other_items2==0) {
							
							$query3         = " UPDATE ".ORDER_TBL." SET 
													order_status = '".$data['order_status_'.$i]."',
													updated_at   = '".$curr."' 
												WHERE id='".$item_details['order_id']."'";
							$exe3           = $this->exeQuery($query3);

						}
					}
				}

				// Update order item status in vendor order item table

				$query2                      = "UPDATE ".VENDOR_ORDER_ITEM_TBL." SET 
													order_status = '".$data['order_status_'.$i]."' 
													".$remarks.",
													updated_at   = '".$curr."' 
												WHERE order_id='".$item_details['order_id']."' AND vendor_id='".$item_details['vendor_id']."' AND product_id='".$item_details['product_id']."' ";
				$exe2                        = $this->exeQuery($query2);


				// Update order  status in vendor order table based on vendor order items status

				$item_pending_to_deliver_ve  = $this->check_query(VENDOR_ORDER_ITEM_TBL,"id","(order_status='0' OR order_status='1') AND order_id='".$item_details['order_id']."' AND vendor_response='1' AND vendor_accept_status='1' AND vendor_id='".$item_details['vendor_id']."' AND order_item_id!='".$item_id."'  ");

				if( $item_pending_to_deliver_ve == 0 ) { 

					if( $data['order_status_'.$i] == 3 ) {

						$check_otherItems             = $this->check_query(VENDOR_ORDER_ITEM_TBL,"id"," (order_status='0' OR order_status='1' OR order_status='2') AND vendor_response='1' AND vendor_accept_status='1' AND order_id='".$item_details['order_id']."' AND vendor_id='".$item_details['vendor_id']."'    ");

						if( $check_otherItems == 0 ) {

							if( $data['order_status_'.$i] == 2 ) {

								$update_delivery_date = ",delivery_date='".date("Y-m-d",strtotime($curr))."',deliver_status='1' ";

							} else {

								$update_delivery_date = "";

							}

							$query4                   = "UPDATE ".VENDOR_ORDER_TBL." SET 
															order_status='".$data['order_status_'.$i]."' 
															".$update_delivery_date.",
															updated_at   = '".$curr."' 
														WHERE order_id='".$item_details['order_id']."' AND vendor_id='".$item_details['vendor_id']."' ";
							$exe4                     = $this->exeQuery($query4);

						} 
					} elseif( $data['order_status_'.$i] != 3 ) {

						$check_otherItems2            = $this->check_query(VENDOR_ORDER_ITEM_TBL,"id"," (order_status='0' OR order_status='1') AND vendor_response='1' AND vendor_accept_status='1' AND order_id='".$item_details['order_id']."' AND vendor_id='".$item_details['vendor_id']."' AND order_item_id!='".$item_id."'  ");

						if( $check_otherItems2 == 0 ) {

							if( $data['order_status_'.$i] == 2 ) {

								$update_delivery_date = ",delivery_date='".date("Y-m-d",strtotime($curr))."',deliver_status='1' ";

							} else {

								$update_delivery_date = "";

							}

							$query4                   = "UPDATE ".VENDOR_ORDER_TBL." SET 
															order_status  = '".$data['order_status_'.$i]."'
															".$update_delivery_date.",
															updated_at    = '".$curr."' 
														 WHERE order_id   = '".$item_details['order_id']."' AND vendor_id='".$item_details['vendor_id']."' ";
							$exe4                     = $this->exeQuery($query4);
						}
					}
				}

				if( $data['order_status_'.$i] == 1 ){
					$result =1;
				}else{
					$result =2;
				}
			}

			}else{
				return $validate_csrf;
			}
		return 1;
	}

    /** Vendor Order Invoice Customer Shipping amount could not be added
     * That why im comment and adding the Customer Shipping cost after this function below
     *  **/

    /*function getVendorOrderItems($order_id,$vendor_id)
    {
        $layout     ="";
        $query      ="SELECT O.id,O.user_id,O.order_id,O.order_status,O.variant_id,O.vendor_invoice_number,O.vendor_id,O.cart_id,O.product_id,O.coupon_value,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.qty,O.sub_total,O.vendor_commission_tax_amt,O.vendor_payment_tax_amt,O.vendor_shipping_tax_amt,O.vendor_commission_tax,O.vendor_payment_tax,O.vendor_shipping_tax,O.return_comment,O.total_tax,O.sgst,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.vendor_payment_charge,O.vendor_payment_charge_amt,O.vendor_shipping_charge,O.vendor_shipping_charge_amt,O.total_amount,O.vendor_commission,O.vendor_commission_amt,O.vendor_response,O.vendor_accept_status,O.response_notes,P.product_uid,P.product_name,P.page_url,V.variant_name,VD.state_id,VD.state_name,OD.order_address,RS.response_status as response_status_msg,RR.return_reason  as return_reason_msg,PU.product_unit 
                    FROM ".ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) 
                                              LEFT JOIN ".VENDOR_TBL." VD ON (VD.id=O.vendor_id) 
                                              LEFT JOIN ".ORDER_TBL." OD ON (OD.id=O.order_id) 
                                              LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) 
                                              LEFT JOIN ".ORDER_RESPONSE_STATUS_TBL." RS ON (RS.id=O.response_status) 
                                              LEFT JOIN ".RETURN_REASON_TBL." RR ON (RR.id=O.return_reason) 
                                              LEFT JOIN ".PRODUCT_UNIT_TBL." PU ON (PU.id=P.product_unit)  
                    WHERE O.order_id=$order_id AND O.vendor_id='".$vendor_id."' ORDER BY O.price*O.qty  DESC";
        $exe        = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list       = $this->editPagePublish($rows);
                    $name       = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;
                    $info   = $this->getDetails(VENDOR_TBL,"company","id='".$list['vendor_id']."' ");

                    $status_btn_title = (($list['order_status']==0) ? "<span class='text-warning'>Inprocess</span>" :  (($list['order_status']==1) ? "<span class='text-warning'>Shipped</span>" :  (($list['order_status']==2) ? "<span class='text-success'>Paid</span>" :  "<span class='text-danger'>Returned</span>") ) );


                    $billing_address = $this->getDetails(CUSTOMER_ADDRESS_TBL,"state_id","id=".$list['order_address']." ");

                    if($billing_address['state_id']==$list['state_id']) {
                        $tax_info = "<p>SGST  <span>: ".intval($list['sgst'])."% (₹  ".$this->inrFormat($list['sgst_amt']).")</span></p>
                                            <p>CGST <span>: ".intval($list['cgst'])."% (₹  ".$this->inrFormat($list['cgst_amt']).")</span></p>";
                    } else {
                        $tax_info = "<p>IGST <span>: ".intval($list['igst'])."% (₹  ".$this->inrFormat($list['igst_amt']).")</span></p>";
                    }

                    if($list['return_comment']!="") {
                        $return_comment = "<div>Comment : ".$list['return_comment']."</div>";
                    } else {
                        $return_comment = "";
                    }

                     $status_btn_title = (($list['order_status']==0) ? "<span class='text-warning'>Inprocess</span>" :  (($list['order_status']==1) ? "<span class='text-warning'>Shipped</span>" :  (($list['order_status']==2) ? "<span class='text-success'>Paid</span>" :  "<span class='text-danger'>Returned</span><div>Reason : ".$list['return_reason_msg']." ".$return_comment ." </div>") ) );

                    if($list['vendor_response']==1 && $list['vendor_accept_status']==0)
                    {
                        $status_btn_title = "<span class='text-danger'>Rejected</span>";
                        $response_notes   = "<div>
                                            ( ".$list['response_status_msg']." )
                                            <div>" ;
                        $rejected_price   = "text-danger";                                            
                    } elseif($list['vendor_response']== 0 )  {
                        $status_btn_title = "<span class='text-warning'>Not Seen</span>";
                        $response_notes   = "" ;
                        $rejected_price   = "";                        
                    } else {
                        $response_notes = "";
                        $rejected_price   = "";                        
                    }

                    if($list['tax_type']=="inclusive") 
                    {
                        $taxMsg = "<p>( Inclusive of all taxes * )</p>";
                    } else {
                        $taxMsg = "<p>( Exclusive of all taxes * )</p>";
                    }

                    // <a href='".COREPATH."orders/vendororderdetails/".$list['order_id']."'>".$list['vendor_invoice_number']."</a> 

                    if($list['vendor_response']=='1' && $list['vendor_accept_status']==1)
                    {
                        $vendor_invoice_link = "<p>vendor invoice: <a href='".COREPATH."orders/previewvendorinvoice/".$list['id']."' target='_blank'>View Invoice</a> </p>";
                    } else {
                        $vendor_invoice_link = "";
                    }

                    $product_unit = (($list['product_unit']!="")? "( ".$list['product_unit']." )" : "" );

                    $rating_check = $this->check_query(VENDOR_RATTING_TBL,"id","product_id='".$list['product_id']."' AND vendor_id='".$list['vendor_id']."' AND added_by='".$list['user_id']."' AND order_id='".$list['order_id']."' ");

                    if($rating_check) {

                        $rating_info   = $this->getDetails(VENDOR_RATTING_TBL,"*","product_id='".$list['product_id']."' AND vendor_id='".$list['vendor_id']."' AND added_by='".$list['user_id']."' AND order_id='".$list['order_id']."'  ");

                        $sold_by_and_ratings = "
                                        <div class='star_rating star_tbl_list'>
                                            <span class='my-rating-7 ms-2 star_tbl_list'>
                                                <p class='sold_by_info' >Sold by: ".$info['company']."</p>
                                            </span>
                                            <input type='hidden' class='rating_data' name='star_ratings' value='".$rating_info['star_ratings']."' id='rating_data'>
                                            <span class='star_rating_point'>( ".$rating_info['star_ratings']." / 5 )</span>
                                        </div>";

                    } else {
                        $sold_by_and_ratings = "<p>Sold by: ".$info['company']."</p>";
                    }



                    $layout     .="
                                    <tr class='marginBottom'>
                                        <td>$i</td>
                                        <td><a href='".BASEPATH."product/details/".$list['page_url']."' target='_blank'>".$name."</a>
                                            ".$taxMsg."
                                            ".$tax_info."
                                            ".$sold_by_and_ratings."
                                            ".$vendor_invoice_link."
                                        </td> 
                                        <td>$status_btn_title
                                            $response_notes
                                        </td>
                                        <td class='$rejected_price'>₹ ".$this->inrFormat($list['price'])."</td>
                                        <td class='$rejected_price'>".$list['qty']." ".$product_unit."</td>
                                        <td class='$rejected_price'>₹ ".$this->inrFormat($list['sub_total']+$list['total_tax'])."</td>
                                    </tr>
                                ";
                    $i++;
                }
        }

        return $layout;
    }*/

    function getVendorOrderItems($order_id,$vendor_id)
    {
        $layout     ="";
        $query      ="SELECT O.id,O.user_id,O.order_id,O.order_status,O.variant_id,O.vendor_invoice_number,O.vendor_id,O.cart_id,O.product_id,O.coupon_value,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.qty,O.shipping_cost,O.sub_total,O.vendor_commission_tax_amt,O.vendor_payment_tax_amt,O.vendor_shipping_tax_amt,O.vendor_commission_tax,O.vendor_payment_tax,O.vendor_shipping_tax,O.return_comment,O.total_tax,O.sgst,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.vendor_payment_charge,O.vendor_payment_charge_amt,O.vendor_shipping_charge,O.vendor_shipping_charge_amt,O.total_amount,O.vendor_commission,O.vendor_commission_amt,O.vendor_response,O.vendor_accept_status,O.response_notes,P.product_uid,P.product_name,P.page_url,V.variant_name,VD.state_id,VD.state_name,OD.order_address,RS.response_status as response_status_msg,RR.return_reason  as return_reason_msg,PU.product_unit 
                    FROM ".ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) 
                                              LEFT JOIN ".VENDOR_TBL." VD ON (VD.id=O.vendor_id) 
                                              LEFT JOIN ".ORDER_TBL." OD ON (OD.id=O.order_id) 
                                              LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) 
                                              LEFT JOIN ".ORDER_RESPONSE_STATUS_TBL." RS ON (RS.id=O.response_status) 
                                              LEFT JOIN ".RETURN_REASON_TBL." RR ON (RR.id=O.return_reason) 
                                              LEFT JOIN ".PRODUCT_UNIT_TBL." PU ON (PU.id=P.product_unit)  
                    WHERE O.order_id=$order_id AND O.vendor_id='".$vendor_id."' ORDER BY O.price*O.qty  DESC";
        $exe        = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list       = $this->editPagePublish($rows);
                    $name       = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;
                    $info   = $this->getDetails(VENDOR_TBL,"company","id='".$list['vendor_id']."' ");

                    $status_btn_title = (($list['order_status']==0) ? "<span class='text-warning'>Inprocess</span>" :  (($list['order_status']==1) ? "<span class='text-warning'>Shipped</span>" :  (($list['order_status']==2) ? "<span class='text-success'>Paid</span>" :  "<span class='text-danger'>Returned</span>") ) );


                    $billing_address = $this->getDetails(CUSTOMER_ADDRESS_TBL,"state_id","id=".$list['order_address']." ");

                    if($billing_address['state_id']==$list['state_id']) {
                        $tax_info = "<p>SGST  <span>: ".intval($list['sgst'])."% (₹  ".$this->inrFormat($list['sgst_amt']).")</span></p>
                                            <p>CGST <span>: ".intval($list['cgst'])."% (₹  ".$this->inrFormat($list['cgst_amt']).")</span></p>";
                    } else {
                        $tax_info = "<p>IGST <span>: ".intval($list['igst'])."% (₹  ".$this->inrFormat($list['igst_amt']).")</span></p>";
                    }

                    if($list['return_comment']!="") {
                        $return_comment = "<div>Comment : ".$list['return_comment']."</div>";
                    } else {
                        $return_comment = "";
                    }

                     $status_btn_title = (($list['order_status']==0) ? "<span class='text-warning'>Inprocess</span>" :  (($list['order_status']==1) ? "<span class='text-warning'>Shipped</span>" :  (($list['order_status']==2) ? "<span class='text-success'>Paid</span>" :  "<span class='text-danger'>Returned</span><div>Reason : ".$list['return_reason_msg']." ".$return_comment ." </div>") ) );

                    if($list['vendor_response']==1 && $list['vendor_accept_status']==0)
                    {
                        $status_btn_title = "<span class='text-danger'>Rejected</span>";
                        $response_notes   = "<div>
                                            ( ".$list['response_status_msg']." )
                                            <div>" ;
                        $rejected_price   = "text-danger";                                            
                    } elseif($list['vendor_response']== 0 )  {
                        $status_btn_title = "<span class='text-warning'>Not Seen</span>";
                        $response_notes   = "" ;
                        $rejected_price   = "";                        
                    } else {
                        $response_notes = "";
                        $rejected_price   = "";                        
                    }

                    if($list['tax_type']=="inclusive") 
                    {
                        $taxMsg = "<p>( Inclusive of all taxes * )</p>";
                    } else {
                        $taxMsg = "<p>( Exclusive of all taxes * )</p>";
                    }

                    // <a href='".COREPATH."orders/vendororderdetails/".$list['order_id']."'>".$list['vendor_invoice_number']."</a> 

                    if($list['vendor_response']=='1' && $list['vendor_accept_status']==1)
                    {
                        $vendor_invoice_link = "<p>vendor invoice: <a href='".COREPATH."orders/previewvendorinvoice/".$list['id']."' target='_blank'>View Invoice</a> </p>";
                    } else {
                        $vendor_invoice_link = "";
                    }

                    $product_unit = (($list['product_unit']!="")? "( ".$list['product_unit']." )" : "" );

                    $rating_check = $this->check_query(VENDOR_RATTING_TBL,"id","product_id='".$list['product_id']."' AND vendor_id='".$list['vendor_id']."' AND added_by='".$list['user_id']."' AND order_id='".$list['order_id']."' ");

                    if($rating_check) {

                        $rating_info   = $this->getDetails(VENDOR_RATTING_TBL,"*","product_id='".$list['product_id']."' AND vendor_id='".$list['vendor_id']."' AND added_by='".$list['user_id']."' AND order_id='".$list['order_id']."'  ");

                        $sold_by_and_ratings = "
                                        <div class='star_rating star_tbl_list'>
                                            <span class='my-rating-7 ms-2 star_tbl_list'>
                                                <p class='sold_by_info' >Sold by: ".$info['company']."</p>
                                            </span>
                                            <input type='hidden' class='rating_data' name='star_ratings' value='".$rating_info['star_ratings']."' id='rating_data'>
                                            <span class='star_rating_point'>( ".$rating_info['star_ratings']." / 5 )</span>
                                        </div>";

                    } else {
                        $sold_by_and_ratings = "<p>Sold by: ".$info['company']."</p>";
                    }



                    $layout     .="
                                    <tr class='marginBottom'>
                                        <td>$i</td>
                                        <td><a href='".BASEPATH."product/details/".$list['page_url']."' target='_blank'>".$name."</a>
                                            ".$taxMsg."
                                            ".$tax_info."
                                            ".$sold_by_and_ratings."
                                            ".$vendor_invoice_link."
                                        </td> 
                                        <td>$status_btn_title
                                            $response_notes
                                        </td>
                                        <td class='$rejected_price'>₹ ".$this->inrFormat($list['price'])."</td>
                                        <td class='$rejected_price'>".$list['qty']." ".$product_unit."</td>
                                        <td class='$rejected_price'>₹ ".$this->inrFormat($list['sub_total']+$list['total_tax'])."</td>
                                    </tr>
                                ";
                    $i++;
                }
        }

        return $layout;
    }

    function vendorPayoutRecords($data)
    {   


        if(isset($data['vendor_selected_invoice'])) 
        {
            $curr    = date("Y-m-d H:i:s");
            $query   = "SELECT id,order_id,order_uid,
                    (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE id IN (" . implode(',', array_map('intval',$data['vendor_selected_invoice'])). ") ) as total_order_value,
                    (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE id IN (" . implode(',', array_map('intval',$data['vendor_selected_invoice'])). ") ) as total_commission,
                    (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE id IN (" . implode(',', array_map('intval',$data['vendor_selected_invoice'])). ") ) as total_payment_total,
                     (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE id IN (" . implode(',', array_map('intval',$data['vendor_selected_invoice'])). ") ) as total_shipping_total
                     FROM ".VENDOR_ORDER_TBL." WHERE 1 ORDER BY id DESC LIMIT 1  ";
            $exe     = $this->exeQuery($query);
        
            $get_last_id         = $this->getDetails(VENDOR_PAYOUT_INVOICE_TBL,"id","1 ORDER BY id DESC");
            $pay_lt_id           = ((@$get_last_id['id'])? $get_last_id['id']  :  0 ) + 1 ;
            $payout_invoice_id   = 'PY'.str_pad($pay_lt_id, 5,0,STR_PAD_LEFT);
                    
            if(mysqli_num_rows($exe) > 0){
                while($list = mysqli_fetch_array($exe)){
                    $i=1;
                        if($i==1) {
                            $total_commission = $list['total_commission'] + $list['total_payment_total'] + $list['total_shipping_total'];
                            $net_payable      = $list['total_order_value'] - $total_commission;

                            $q = "INSERT INTO ".VENDOR_PAYOUT_INVOICE_TBL." SET
                                total_order_value   = '".$list['total_order_value']."',
                                payout_invoice_id   = '".$payout_invoice_id."',
                                vendor_id           = '".$data['vendor_id']."',
                                total_commission    = '".$total_commission."',
                                net_payable         = '".$net_payable."',
                                order_id            = '".$list['order_id']."',                
                                order_uid           = '".$list['order_uid']."',    
                                created_at          = '$curr',
                                updated_at          = '$curr'
                             ";          
                            $last_id = $this->lastInserID($q);
                        }

                    $i++;
                }

                $query1         = "SELECT * FROM ".VENDOR_ORDER_TBL." WHERE id IN (" . implode(',', array_map('intval',$data['vendor_selected_invoice'])). ")  "; 
                $exe2           = $this->exeQuery($query1);

                if(mysqli_num_rows($exe2) > 0) {
                    while($list = mysqli_fetch_array($exe2)){
                           
                           $q   = "INSERT INTO ".VENDOR_PAYOUT_INVOICE_ITEM_TBL." SET
                                vendor_order_id     = '".$list['id']."',
                                payout_id           = '".$last_id."',
                                created_at          = '$curr',
                                updated_at          = '$curr'
                           ";          
                           $exe     = $this->exeQuery($q);
                    }
                }
                return 1;
            }
           
        } else {
            return 0;
        }
    }

    function getOrderVendors($order_id)
    {
        $ids= array();
        $query="SELECT vendor_id FROM ".ORDER_ITEM_TBL." WHERE order_id='$order_id' ORDER BY vendor_invoice_number ASC ";
        
        $exe = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0){
            while($list = mysqli_fetch_array($exe)){
                $ids[] =  $list['vendor_id']; 
                $ids   =  array_unique($ids);              
            }
        }
        return $ids;
    }

    function getVendorItemToltals($order_id,$vendor_id)
    {
        $query      = "SELECT vendor_invoice_number,
                              SUM(tax_amt) as subTotal, 
                              SUM(final_price) as finalPrice,
                              SUM(sgst) as SCST,
                              SUM(cgst) as CGST,
                              SUM(igst) as IGST,
                              SUM(sgst_amt) as SGST_AMT,
                              SUM(cgst_amt) as CGST_AMT,
                              SUM(igst_amt) as IGST_AMT,
                              SUM(total_amount) as totalAMT,
                              SUM(total_tax) as totalTax, 
                              SUM(coupon_value) as couponValue, 
                              SUM(vendor_commission) as vendorCommission, 
                              SUM(vendor_commission_tax) as vendorCommissionTax, 
                              SUM(vendor_payment_charge) as vendorPaymentCharge, 
                              SUM(vendor_payment_tax) as vendorPaymenTax, 
                              SUM(vendor_shipping_charge) as vendorShippingCharge, 
                              SUM(vendor_shipping_tax) as vendorShippingTax, 
                              SUM(vendor_commission_amt) as vendorCommissionAmt, 
                              SUM(vendor_commission_tax_amt) as vendorCommissionTaxAmt, 
                              SUM(vendor_payment_charge_amt) as vendorPaymentChargeAmt, 
                              SUM(vendor_payment_tax_amt) as vendorPaymentTaxAmt, 
                              SUM(vendor_shipping_charge_amt) as vendorShippingChargeAmt, 
                              SUM(vendor_shipping_tax_amt) as vendorShippingTaxAmt
                              FROM ".ORDER_ITEM_TBL." WHERE 1 AND vendor_id='".$vendor_id."' AND  order_id='".$order_id."' ";
        $exe        = $this->exeQuery($query);
        $result     = mysqli_fetch_array($exe);
        return $result ;
    }

    function getVendorInvoices($order_id)
    {
        $layout        = "";
        $order_vendors =  $this->getOrderVendors($order_id);
        $query         =  "SELECT * FROM ".ORDER_TBL." WHERE id='".$order_id."'  ";
        $exe           = $this->exeQuery($query);
        $order_info    = mysqli_fetch_array($exe);

        if(count($order_vendors) > 0){
                $i = 1;
                for ($i=0; $i < count($order_vendors) ; $i++) { 
                    $ven_order_info    =  $this->getVendorItemToltals($order_id,$order_vendors[$i]);
                    $order_info        =  $this->getDetails(ORDER_TBL,"*","id='".$order_id."'");
                    $accept_items      =  $this->check_query(ORDER_ITEM_TBL,"*"," Order_id='".$order_id."' AND vendor_accept_status='1' AND vendor_id='".$order_vendors[$i]."' ");
                    $order_item_info   =  $this->getOrderItemToltals($order_id,$order_vendors[$i],$accept_items);
                    $total             = $order_item_info['totalAMT'];
                    $total_charges     = $order_item_info['vendorCommissionAmt'] + $order_item_info['vendorPaymentChargeAmt'] + $order_item_info['vendorShippingChargeAmt'];
                    $coupon = ($order_info['coupon_code']!="") ? "(Coupon Code : ".$order_info['coupon_code']." Applied)" : "" ; 

                    $layout .="<div class='nk-block'>
                            <div class='row gy-5'>
                                <div class='col-12'>
                                   
                                    <div class='card card-bordered'>
                                        <div class='card-inner'>
                                            <table class='table table-bordered is-compact'>
                                                <thead class='details'>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Product Name</th>
                                                        <th>Status</th>
                                                        <th>Price</th>
                                                        <th>Qty</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody class='details'>
                                                ".$this->getVendorOrderItems($order_id,$order_vendors[$i])."
                                                    <tr>
                                                        <td class='text-right' colspan='5'>Sub Total:</td>
                                                        <td class='text-right'>₹ ".$this->inrFormat($order_item_info['subTotal']+$order_item_info['totalTax'] )."</td>
                                                    </tr>
                                                    <tr>
                                                        <td class='text-right' colspan='5'>Discount $coupon :</td>
                                                        <td class='text-right'>₹ ".$this->inrFormat((($order_item_info['couponValue']!='') ? $order_item_info['couponValue'] : 0.00) )."</td>
                                                    </tr>
                                                    <tr>
                                                        <td class='text-right'
                                                        colspan='5'>Shipping Cost:</td>
                                                        <td class='text-right'>₹ ".$this->inrFormat((($order_item_info['shipping_cost']!='') ? $order_item_info['shipping_cost'] : 0.00) )."
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class='text-right' colspan='5'>Total:</td>
                                                        <td class='text-right'>₹ ".$this->inrFormat($order_item_info['subTotal'] + $order_item_info['shipping_cost'] + $order_item_info['IGST_AMT'] - $order_item_info['couponValue'])."</td>
                                                    </tr>
                                                    <tr>
                                                        <td class='text-left' colspan='6'>Commission & Charges :</td>
                                                    </tr>
                                                    <tr>
                                                        <td class='text-right' colspan='5'>Sapiens Commission:</td>
                                                        <td class='text-right'>₹ ".$this->inrFormat($order_item_info['vendorCommissionAmt'])."</td>
                                                    </tr>
                                                    <tr>
                                                        <td class='text-right' colspan='5'>Payment Gateway Charge:</td>
                                                        <td class='text-right'>₹ ".$this->inrFormat($order_item_info['vendorPaymentChargeAmt'])."</td>
                                                    </tr>
                                                    <tr>
                                                        <td class='text-right' colspan='5'>Shipping Charge:</td>
                                                        <td class='text-right'>₹ ".$this->inrFormat($order_item_info['vendorShippingChargeAmt'])."</td>
                                                    </tr>
                                                   
                                                     <tr>
                                                        <td class='text-right' colspan='5'>Sub Total:</td>
                                                        <td class='text-right'>₹ ".$this->inrFormat($order_item_info['subTotal'] + $order_item_info['IGST_AMT']-$order_item_info['couponValue'],2)."</td>
                                                    </tr>

                                                    <tr>
                                                        <td class='text-right' colspan='5'>Total Charge:</td>
                                                        <td class='text-right'>- ₹ ".$this->inrFormat($total_charges,2)."</td>
                                                    </tr>
                                                    <tr>    
                                                        <td class='text-right' colspan='5'>Vendor Payable:</td>
                                                        <td class='text-right'> ₹ ".$this->inrFormat($order_item_info['subTotal'] + $order_item_info['IGST_AMT'] - $order_item_info['couponValue'] - $total_charges,2)."</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>";
                }
        }
        return $layout;
    }

        /**
            This function could not include Handling amout functionality
            That why im commenting this function and overwrite this code after
        **/

    /*function getOrderItemToltals($order_id,$vendor_id,$accept_items)
    {   
        if($accept_items) {
            $condition = "AND vendor_accept_status='1'";    
        } else {
            $condition = "";                
        }
        $query      = "SELECT vendor_invoice_number,
                              SUM(tax_amt) as subTotal, 
                              SUM(final_price) as finalPrice,
                              SUM(sgst) as SCST,
                              SUM(cgst) as CGST,
                              SUM(igst) as IGST,
                              SUM(sgst_amt) as SGST_AMT,
                              SUM(cgst_amt) as CGST_AMT,
                              SUM(igst_amt) as IGST_AMT,
                              SUM(total_amount) as totalAMT,
                              SUM(total_tax) as totalTax, 
                              SUM(coupon_value) as couponValue, 
                              SUM(vendor_commission) as vendorCommission, 
                              SUM(vendor_commission_tax) as vendorCommissionTax, 
                              SUM(vendor_payment_charge) as vendorPaymentCharge, 
                              SUM(vendor_payment_tax) as vendorPaymenTax, 
                              SUM(vendor_shipping_charge) as vendorShippingCharge, 
                              SUM(vendor_shipping_tax) as vendorShippingTax, 
                              SUM(vendor_commission_amt) as vendorCommissionAmt, 
                              SUM(vendor_commission_tax_amt) as vendorCommissionTaxAmt, 
                              SUM(vendor_payment_charge_amt) as vendorPaymentChargeAmt, 
                              SUM(vendor_payment_tax_amt) as vendorPaymentTaxAmt, 
                              SUM(vendor_shipping_charge_amt) as vendorShippingChargeAmt, 
                              SUM(vendor_shipping_tax_amt) as vendorShippingTaxAmt 
                              FROM ".ORDER_ITEM_TBL." WHERE 1 $condition AND vendor_id='".$vendor_id."' AND  order_id='".$order_id."' ";
                              
        $exe        = $this->exeQuery($query);
        $result     = mysqli_fetch_array($exe);
        return $result ;
    }*/

    function getOrderItemToltals($order_id,$vendor_id,$accept_items)
    {   
        if($accept_items) {
            $condition = "AND vendor_accept_status='1'";    
        } else {
            $condition = "";                
        }
        $query      = "SELECT vendor_invoice_number,
                              SUM(tax_amt) as subTotal, 
                              SUM(final_price) as finalPrice,
                              SUM(sgst) as SCST,
                              SUM(cgst) as CGST,
                              SUM(igst) as IGST,
                              SUM(sgst_amt) as SGST_AMT,
                              SUM(cgst_amt) as CGST_AMT,
                              SUM(igst_amt) as IGST_AMT,
                              SUM(total_amount) as totalAMT,
                              SUM(total_tax) as totalTax, 
                              SUM(coupon_value) as couponValue, 
                              SUM(vendor_commission) as vendorCommission, 
                              SUM(vendor_commission_tax) as vendorCommissionTax, 
                              SUM(vendor_payment_charge) as vendorPaymentCharge, 
                              SUM(vendor_payment_tax) as vendorPaymenTax, 
                              SUM(vendor_shipping_charge) as vendorShippingCharge, 
                              SUM(vendor_shipping_tax) as vendorShippingTax, 
                              SUM(vendor_commission_amt) as vendorCommissionAmt, 
                              SUM(vendor_commission_tax_amt) as vendorCommissionTaxAmt, 
                              SUM(vendor_payment_charge_amt) as vendorPaymentChargeAmt, 
                              SUM(vendor_payment_tax_amt) as vendorPaymentTaxAmt, 
                              SUM(vendor_shipping_charge_amt) as vendorShippingChargeAmt, 
                              SUM(vendor_shipping_tax_amt) as vendorShippingTaxAmt,
                              shipping_cost 
                              FROM ".ORDER_ITEM_TBL." WHERE 1 $condition AND vendor_id='".$vendor_id."' AND  order_id='".$order_id."' ";
                              
        $exe        = $this->exeQuery($query);
        $result     = mysqli_fetch_array($exe);
        return $result ;
    }

    function getVendOrderInvoices($order_id,$vendor_id="")
    {
        $layout            = "";
        $order_vendors     =  $this->getOrderVendors($order_id);
         $accept_items     =  $this->check_query(ORDER_ITEM_TBL,"*"," Order_id='".$order_id."' AND vendor_accept_status='1' AND vendor_id='".$vendor_id."' ");
        $ven_order_info    =  $this->getOrderItemToltals($order_id,$vendor_id,$accept_items);
        $order_info        =  $this->getDetails(ORDER_TBL,"*","id='".$order_id."'");
        $total             =  $ven_order_info['totalAMT'];
        $total_charges     =  $ven_order_info['vendorCommissionAmt'] + $ven_order_info['vendorPaymentChargeAmt'] + $ven_order_info['vendorShippingChargeAmt'];
        $coupon            = ($order_info['coupon_code']!="") ? "(Coupon Code : ".$order_info['coupon_code']." Applied)" : "" ; 

        $layout .="<div class='nk-block'>
                <div class='row gy-5'>
                    <div class='col-12'>
                        <div class='card card-bordered'>
                            <div class='card-inner'>
                                <table class='table table-bordered is-compact'>
                                    <thead class='details'>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>Status</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class='details'>
                                    ".$this->getVendorOrderItems($order_id,$vendor_id)."
                                        <tr>
                                            <td class='text-right' colspan='5'>Sub Total:</td>
                                            <td class='text-right'>₹ ".$this->inrFormat($ven_order_info['subTotal']+$ven_order_info['totalTax'])."</td>
                                        </tr>
                                        <tr>
                                            <td class='text-right' colspan='5'>Discount $coupon :</td>
                                            <td class='text-right'>₹ ".$this->inrFormat((($order_info['coupon_value']!='') ? $ven_order_info['couponValue'] : 0.00) )."</td>
                                        </tr>
                                        <tr>
                                            <td class='text-right' colspan='5'>Shipping Cost:</td>
                                            <td class='text-right'>₹ ".$this->inrFormat((($order_info['shipping_cost']!='') ? $ven_order_info['shipping_cost'] : 0.00) )."</td>
                                        </tr>
                                        <tr>
                                            <td class='text-right' colspan='5'>Total:</td>
                                            <td class='text-right'>₹ ".$this->inrFormat( $ven_order_info['subTotal']+ $ven_order_info['shipping_cost'] + $ven_order_info['IGST_AMT']-$ven_order_info['couponValue'])."</td>
                                        </tr>
                                        <tr>
                                            <td class='text-left' colspan='6'>Commission & Charges :</td>
                                        </tr>
                                        <tr>
                                            <td class='text-right' colspan='5'>Sapiens Commission:</td>
                                            <td class='text-right'>₹ ".$this->inrFormat($ven_order_info['vendorCommissionAmt'])."</td>
                                        </tr>
                                        <tr>
                                            <td class='text-right' colspan='5'>Payment Gateway Charge:</td>
                                            <td class='text-right'>₹ ".$this->inrFormat($ven_order_info['vendorPaymentChargeAmt'])."</td>
                                        </tr>
                                        <tr>
                                            <td class='text-right' colspan='5'>Shipping Charge:</td>
                                            <td class='text-right'>₹ ".$this->inrFormat($ven_order_info['vendorShippingChargeAmt'])."</td>
                                        </tr>
                                       
                                         <tr>
                                            <td class='text-right' colspan='5'>Sub Total:</td>
                                            <td class='text-right'>₹ ".$this->inrFormat($ven_order_info['subTotal']+ $ven_order_info['IGST_AMT']- $ven_order_info['couponValue'],2)."</td>
                                        </tr>

                                        <tr>
                                            <td class='text-right' colspan='5'>Total Charge:</td>
                                            <td class='text-right'>- ₹ ".$this->inrFormat($total_charges,2)."</td>
                                        </tr>
                                        <tr>    
                                            <td class='text-right' colspan='5'>Vendor Payable:</td>
                                            <td class='text-right'> ₹ ".$this->inrFormat($ven_order_info['subTotal']+ $ven_order_info['IGST_AMT'] - $ven_order_info['couponValue'] - $total_charges,2)."</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";

        return $layout;
    }

    /*-----------------------------------------------------
        Manage Customer Abandoned checkouts
    ------------------------------------------------------*/

    function manageAbandonedCheckouts($from,$to)
    {

        $layout ="";

        if($from!="" && $from!=0 ) 
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND O.order_date BETWEEN '$from' AND '$to' ";
        } else {
            $date_filter = "";
        }

        $query  ="SELECT O.id,O.sub_total,O.total_tax,O.total_amount,O.shipping_cost,O.coupon_value,O.order_status,O.shipping_status,O.created_at,C.name,C.mobile,C.email,
                    (SELECT COUNT(id) FROM ".CART_ITEM_TBL." WHERE cart_id=O.id ) as items,
                    (SELECT COUNT(id) FROM ".ORDER_ITEM_TBL." WHERE cart_id=O.id ) as order_placed,
                    (SELECT SUM(vendor_commission_amt) FROM ".CART_ITEM_TBL." WHERE cart_id=O.id ) as total_commission,
                    (SELECT SUM(vendor_payment_charge_amt) FROM ".CART_ITEM_TBL." OI WHERE cart_id=O.id ) as total_payment_charge_amt,
                    (SELECT SUM(vendor_shipping_charge_amt) FROM ".CART_ITEM_TBL." OI WHERE cart_id=O.id ) as total_shipping_charge_amt 
                FROM ".CART_TBL." O LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) WHERE 1 AND user_id!=0 ORDER BY O.id  DESC";
        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $total_charges    = (int) $list['total_commission'] + (int) $list['total_payment_charge_amt'] + (int) $list['total_shipping_charge_amt'];

                    if($list['coupon_value']!="" && $list['coupon_value']!=NUll) {
                        $coupon_value = $list['coupon_value'];
                    } else {
                        $coupon_value = 0;
                    }

                    $check_if_order_placed = $this->check_query(ORDER_ITEM_TBL,"cart_id","cart_id='".$list['id']."'");

                    if($check_if_order_placed==0) {

                    $layout .="
                                <tr class='nk-tb-item open_enq_model' data-option='".$list['id']."'>
                                        <td class='nk-tb-col '>
                                         ".$i."
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>".date('d/m/Y',strtotime($list['created_at']))."</span>
                                        </td>
                                        <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>".$list['items']." <span class='currency'>".(($list['items'] > 1 )? 'Items' : 'Item')."</span></span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($total_charges)." </span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat((int)$list['sub_total'] + (int)$list['total_tax'] - (int)$coupon_value )." </span>
                                        </td>
                                    </tr>

                              ";
                    }
                $i++;
                }
        }
        return $layout; 
    }

    function getCartItems($cart_id)
    {
        $layout ="";
        $query  ="SELECT O.id,O.user_id,O.variant_id,O.cart_id,O.product_id,O.coupon_value,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.final_price,O.qty,O.sub_total,O.total_tax,O.sgst,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.total_amount,P.product_uid,P.product_name,P.page_url,P.tax_type,V.variant_name
            FROM ".CART_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) 
                                      LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) 
            WHERE O.cart_id=$cart_id   ORDER BY  O.id ASC";
        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);
                    $name   = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

                    if($list['tax_type']=="inclusive") 
                    {
                        $taxMsg = "<p>( Inclusive of all taxes * )</p>";
                    } else {
                        $taxMsg = "<p>( Exclusive of all taxes * )</p>";
                    }

                    $layout .="
                                <tr>
                                    <td>".$i."</td>
                                    <td><a href='".BASEPATH."product/details/".$list['page_url']."' target='_blank'>".$name."</a>
                                        ".$taxMsg."
                                    </td>
                                    <td >₹ ".$this->inrFormat($list['price'])."</td>
                                    <td >".$list['qty']."</td>
                                    <td >₹ ".$this->inrFormat($list['sub_total']+$list['total_tax'])."</td>
                                </tr>             
                              ";
                $i++;
                }
        }
        return $layout;
    }

    /*-----------------------------------------------------
        Manage User Wishlist
    ------------------------------------------------------*/

    function manageUserWishlist($from,$to)
    {

        $layout ="";

        if($from!="" && $from!=0 ) 
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND O.order_date BETWEEN '$from' AND '$to' ";
        } else {
            $date_filter = "";
        }

        $query  = "SELECT W.id,W.user_id,W.product_id,W.variant_id,W.vendor_id,W.created_at,C.name,C.email,C.mobile FROM ".WISHLIST_TBL." W LEFT JOIN ".CUSTOMER_TBL." C ON (C.id=W.user_id)  WHERE W.fav_status='1' AND W.status='1' ";
        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $product_info = $this->getDetails(PRODUCT_TBL,"*","id='".$list['product_id']."'");

                    if($list['variant_id']!='0') {
                        $variant_details = $this->getDetails(PRODUCT_VARIANTS,"*","id='".$list['variant_id']."'");
                        $variant_info    = "<span>Variant : ".$variant_details['variant_name']."</span><br>" ;
                    } else {
                        $variant_info = "" ;
                    }

                    if($list['vendor_id']!='0') {
                        $vendor_detais = $this->getDetails(VENDOR_TBL,"company,email,mobile","Id='".$list['vendor_id']."'");                        
                        $vendor_info   = "<span class='tb-lead'>".$vendor_detais['company']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                          <span><em class='icon ni ni-mail'></em> ".$vendor_detais['email']."</span><br>
                                          <span><em class='icon ni ni-mobile'></em> ".$vendor_detais['mobile']."</span>" ;
                    } else {
                        $vendor_info = "<span class='tb-lead'>Sapiens<span class='dot dot-success d-md-none ml-1'></span></span>" ;
                    }

                    $layout .="
                                <tr class='nk-tb-item open_enq_model' data-option='".$list['id']."'>
                                        <td class='nk-tb-col '>
                                         ".$i."
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                            <span>".date('d/m/Y',strtotime($list['created_at']))."</span>
                                        </td>
                                        
                                        <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col tb-col-md'
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$product_info['product_name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    ".$variant_info."
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col tb-col-mb' data-order=''>".$vendor_info."
                                        </td>
                                    </tr>

                              ";
                $i++;
                }
        }
        return $layout; 
    }

    // Get Customer wishlist total (Not In Use)

    function getCustomerWishlistTotal($user_id) 
    {      
        $total = 0;
        $query = "SELECT * FROM ".WISHLIST_TBL." WHERE user_id='".$user_id."' ";
        $exe   = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0 )
        {
            while ($list = mysqli_fetch_assoc($exe)) {
                if($list['vendor_id']) {
                    $product = $this->getDetails(VENDOR_PRODUCTS_TBL,"selling_price"," product_id='".$list['product_id']."' AND variant_id='".$list['variant_id']."' AND vendor_id='".$list['vendor_id']."' ");
                    $total   = $total + $product['selling_price'];
                } else {
                    if($list['variant_id']!=0) {
                        $product = $this->getDetails(PRODUCT_VARIANTS,"selling_price","product_id='".$list['product_id']."' AND id='".$list['variant_id']."' ");
                        $total   = $total + $product['selling_price'];
                    } else {
                        $product = $this->getDetails(PRODUCT_TBL,"actual_price","id='".$list['product_id']."'");
                        $total   = $total + $product['selling_price'];
                    }
                }
            }
            return $total;
        }
    }

    /*-----------------------------------------------------
        Vendor Payouts For Last and this week
    ------------------------------------------------------*/

    // Check vendor have paid orders 

    function checkVendorInPayout($vendor_id="")
    {   
        $q      = "SELECT VE.id,VP.vendor_id FROM ".VENDOR_TBL." VE LEFT JOIN ".VENDOR_PAYOUT_INVOICE_TBL." VP ON (VE.Id=VP.vendor_id) " ;
        $query = $this->exeQuery($q);
        $result = 0;
        if(@mysqli_num_rows($query)>0){
            $result = 1;
        }
        return $result;
    }

    // Get vendor list For Report filter DroupDown
 
    function getPayoutVendors($current="", $type)
    {   
        $layout = "";

        $q      = "SELECT VO.id,VO.vendor_id,VE.id as vendorID,VE.name,VE.company,VE.mobile,VE.email FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) WHERE VO.order_status='2' AND deliver_status='1' GROUP BY vendor_id  DESC" ;

        $query = $this->exeQuery($q);
        if(@mysqli_num_rows($query)>0){
            $i=0;
            while($list = mysqli_fetch_array($query)){
                $check_vendor_in_payout_query = $this->check_query(VENDOR_PAYOUT_INVOICE_TBL," vendor_id ", " vendor_id='".$list['vendorID']."' " );
                $check_vendor_in_payout       = (($type=="unpaid")? 1 : $check_vendor_in_payout_query );

                if($check_vendor_in_payout) {
                    $selected = (($list['vendorID']==$current) ? 'selected' : '');
                    $layout  .= "<option value='".$list['vendorID']."' $selected>".$list['company']."</option>";
                    $i++;
                }
            }
        }
        return $layout;
    }

    // Get vendor drp list
 
    function getVendorListForReplace($vendor_order_id="")
    {       
        $result = array();  
        $layout = "";
        $vendor_for_each_product_layout = "";
        $v_order_info = $this->getDetails(VENDOR_ORDER_TBL,"*","id='".$vendor_order_id."'");
        $order_info   = $this->getDetails(ORDER_TBL,"*","id='".$v_order_info['order_id']."'");
        $rejected_items = $this->getRejectedOrderItems($v_order_info['id']);
        $get_order_location_vendors_ids = $this->getVendorsForThisOrderLocation($order_info['order_address'],$v_order_info['vendor_id']);

        $vendor_ids = array();

        foreach ($rejected_items['product_ids'] as $key => $value) 
        {   
            $variant_check = "";

            if($rejected_items['variants_ids'][$key]!=0 && $rejected_items['variants_ids'][$key]!="") {
                $variant_check = "AND VP.variant_id='".$rejected_items['variants_ids'][$key]."' ";
            }

            if(count($get_order_location_vendors_ids) > 0) {
               
                $get_min_order_value = $this->getDetails(VENDOR_ORDER_ITEM_TBL,"qty","vendor_order_id='".$v_order_info['id']."' AND product_id='".$value."' AND variant_id='".$rejected_items['variants_ids'][$key]."' AND vendor_id='".$v_order_info['vendor_id']."' ");
                $min_val = $get_min_order_value['qty'];
                $query = "SELECT VP.id,VP.vendor_id,VP.selling_price,VE.id as vendorID,VE.company FROM ".VENDOR_PRODUCTS_TBL."  VP LEFT JOIN ".VENDOR_TBL." VE ON (VE.Id=VP.vendor_id) WHERE VP.product_id='".$value."' AND VP.stock >= $min_val AND VP.status='1' AND VP.vendor_id IN (" . implode(',', array_map('intval',$get_order_location_vendors_ids)) .") ".$variant_check." GROUP BY VP.vendor_id ORDER BY VP.selling_price ASC";
                $exe    = $this->exeQuery($query);

                if(mysqli_num_rows($exe) > 0){
                    $i=1;
                    while ($row = mysqli_fetch_array($exe)) {
                        $list  = $this->editPagePublish($row);
                            $vendor_ids[] .= $list['vendorID'];
                        $i++;
                    }
                } 
            }

        }

        if(count($vendor_ids) > 0) {
            $q     = "SELECT * FROM  ".VENDOR_TBL." WHERE delete_status='0' AND is_draft='0' AND status='1' AND id IN (" . implode(',', array_map('intval',array_unique($vendor_ids))) .") " ;
            $query = $this->exeQuery($q);
            if(@mysqli_num_rows($query)>0){
                $i=0;
                while($list = mysqli_fetch_array($query)){
                    $layout.= "<option value='".$list['id']."' >".$list['company']."</option>";
                    $i++;
                }
            }
        } 


        foreach ($rejected_items['product_ids'] as $key => $value) 
        {   
            $vendor_for_each_product_layout.= 
                "<div class='card card-shadow each_vendor_layout display_none'>
                    <div class='card-inner'>
                        <h5 class='card-title'>Replace Vendor</h5>
                        <div class='row'>
                            <div class='form-group col-md-6 '>
                                <label class='form-label'> Select Vendor <em>*</em> </label>
                                <div class='form-control-wrap'>
                                    <select class='form-select form-control selecte_diffrent_vendor selecte_vendor' data-product_id='".$value."' data-variant_id='".$rejected_items['variants_ids'][$key]."' data-vendor_condition='different_vendor' data-search='on' id='replace_vendor_id_".$value."_".$rejected_items['variants_ids'][$key]."' name='replace_vendor_id_".$value."_".$rejected_items['variants_ids'][$key]."' required>
                                        <option value='not_selected' >Select Vendor</option>
                                        ".$layout."
                                    </select>
                                    <div class='error display_none' id='replace_vendor_error_".$value."_".$rejected_items['variants_ids'][$key]."' >Select Vendor</div>
                                </div>
                            </div>
                            <div class='form-group col-md-6 '>
                                <label class='form-label'> Product </label>
                                <span name='product_name'  id='product_name' class='form-control' >".$this->getProductName($value,$rejected_items['variants_ids'][$key])."</span>
                            </div>
                        </div>
                        <div class='row replase_product_price_details_".$value."_".$rejected_items['variants_ids'][$key]." display_none'>
                        </div>
                    </div>
                </div>";
        }



        $result['vendor_drop_down_list'] = $layout;
        $result['each_vendor_layout']    = $vendor_for_each_product_layout;

        return $result;
    }

    function getProductName($product_id,$variant_id)
    {
        $product_info = $this->getDetails(PRODUCT_TBL,"product_name","id='".$product_id."' ");
        $variant_info = $this->getDetails(PRODUCT_VARIANTS,"variant_name","id='".$variant_id."' ");
        return  (($variant_id==0) ? $product_info['product_name'] : $product_info['product_name']." - ".$variant_info['variant_name']) ;
    }

    function getRejectedOrderItems($vendor_order_id)
    {
        $ids           = array();
        $product_ids   = array();
        $variants_ids  = array();
        $query = "SELECT * FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id='".$vendor_order_id."' AND vendor_response='1' AND vendor_accept_status='0' ";
        $exe   = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            while ($list = mysqli_fetch_assoc($exe)) {
                $ids[] = $list['id'];
                $product_ids[$list['id']] = $list['product_id'];
                $variants_ids[$list['id']] = $list['variant_id'];
                $order_item_id[$list['id']] = $list['order_item_id'];
            }
        }
        $result['ids'] = $ids;
        $result['order_item_id'] = $order_item_id;
        $result['product_ids']   = $product_ids;
        $result['variants_ids']  = $variants_ids;
        return $result;
    }

    function getReplacedOrderDetails($vendor_order_id)
    {
        $result = array();
        $layout = "";
        $check_replace_status = $this->check_query(VENDOR_ORDER_ITEM_TBL,"*"," vendor_order_id='".$vendor_order_id."' AND replace_order_id!='0' AND replace_vendor_order_id!='0' ");

        if($check_replace_status) {
            $vendor_orders   = $this->getReplacedOrderItemVendorIds($vendor_order_id);
            $query = "SELECT * FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id='".$vendor_order_id."' AND replace_vendor_order_id!='0' ";
            $exe   = $this->exeQuery($query);
            if(mysqli_num_rows($exe) > 0){
                if($vendor_orders['count']==1) {
                    $new_vendor_order_info = $this->getDetails(VENDOR_ORDER_TBL,"*","id='".$vendor_orders['vendor_order_id']."'");
                    $replace_vendor_info   = $this->getDetails(VENDOR_TBL,"*","id='".$new_vendor_order_info['vendor_id']."'");
                    $layout .=" 
                         <div class='card card-shadow same_vendor_layout'>
                                <div class='card-inner'>
                                    <h5 class='card-title'>Replace Vendor</h5>
                                    <div class='row'>
                                        <div class='form-group col-md-6'>
                                            <label class='form-label'> Vendor  
                                            </label>
                                            <span name='product_name'  id='product_name' class='form-control'  >".$replace_vendor_info['company']."</span>
                                        </div>
                                        <div class='form-group col-md-6'></div>
                                        <div class='form-group col-md-6'>
                                            <label class='form-label'> Mobile 
                                            </label>
                                            <span name='product_name'  id='product_name' class='form-control'  >".$replace_vendor_info['mobile']."</span>
                                        </div>
                                        <div class='form-group col-md-6'>
                                            <label class='form-label'> Email 
                                            </label>
                                            <span name='category' id='category' class='form-control' >".$replace_vendor_info['email']."</span>
                                        </div>";
                }
                
                while ($list = mysqli_fetch_assoc($exe)) {
                    $new_order_item_info = $this->getDetails(VENDOR_ORDER_ITEM_TBL,"*","id='".$list['replace_vendor_order_item_id']."'");

                    $product_info = $this->getDetails(PRODUCT_TBL,"*","id='".$new_order_item_info['product_id']."'");
                    $variant_info = $this->getDetails(PRODUCT_VARIANTS,"*","id='".$new_order_item_info['variant_id']."'");

                    $name   = $new_order_item_info['variant_id']==0 ? $product_info['product_name'] : $product_info['product_name']." - ".$variant_info['variant_name'] ;
                    if($vendor_orders['count']==1) {
                    
                        $layout .="
                        <div class='form-group col-md-6'>
                            <label class='form-label'> Product 
                            </label>
                            <span name='product_name'  id='product_name' class='form-control'  >".$name."</span>
                        </div>
                        <div class='form-group col-md-2'>
                            <label class='form-label'> Selling Price 
                            </label>
                            <span name='category' id='category' class='form-control' >".$new_order_item_info['price']."</span>
                        </div>
                        <div class='form-group col-md-2'>
                            <label class='form-label'> Qty 
                            </label>
                            <span name='qty' id='qty' class='form-control'>".$new_order_item_info['qty']."</span>
                        </div>
                        <div class='form-group col-md-2'>
                            <label class='form-label'> Price 
                            </label>
                            <span name='price' id='price' class='form-control'  >".($new_order_item_info['qty'] * $new_order_item_info['price'])."</span>
                        </div>";
                    } else {
                        $new_order_item_info = $this->getDetails(VENDOR_ORDER_ITEM_TBL,"*","id='".$list['replace_vendor_order_item_id']."'");
                        $replace_vendor_info = $this->getDetails(VENDOR_TBL,"*","id='".$new_order_item_info['vendor_id']."'");

                        $layout .= " 
                            <div class='card card-shadow same_vendor_layout'>
                                <div class='card-inner'>
                                    <h5 class='card-title'>Replace Vendor</h5>
                                    <div class='row'>
                                        <div class='form-group col-md-6'>
                                            <label class='form-label'> Vendor  
                                            </label>
                                            <span name='product_name'  id='product_name' class='form-control'  >".$replace_vendor_info['company']."</span>
                                        </div>
                                        <div class='form-group col-md-6'></div>
                                        <div class='form-group col-md-6'>
                                            <label class='form-label'> Mobile 
                                            </label>
                                            <span name='product_name'  id='product_name' class='form-control'  >".$replace_vendor_info['mobile']."</span>
                                        </div>
                                        <div class='form-group col-md-6'>
                                            <label class='form-label'> Email 
                                            </label>
                                            <span name='category' id='category' class='form-control' >".$replace_vendor_info['email']."</span>
                                        </div>
                                        <div class='form-group col-md-6'>
                                            <label class='form-label'> Product 
                                            </label>
                                            <span name='product_name'  id='product_name' class='form-control'  >".$name."</span>
                                        </div>
                                        <div class='form-group col-md-2'>
                                            <label class='form-label'> Selling Price 
                                            </label>
                                            <span name='category' id='category' class='form-control' >".$new_order_item_info['price']."</span>
                                        </div>
                                        <div class='form-group col-md-2'>
                                            <label class='form-label'> Qty 
                                            </label>
                                            <span name='qty' id='qty' class='form-control'>".$new_order_item_info['qty']."</span>
                                        </div>
                                        <div class='form-group col-md-2'>
                                            <label class='form-label'> Price 
                                            </label>
                                            <span name='price' id='price' class='form-control'  >".($new_order_item_info['qty'] * $new_order_item_info['price'])."</span>
                                        </div>
                                    </div>
                                </div>
                            </div>";

                    }

                }

                if($vendor_orders['count']==1)
                {
                    $layout .='</div></div></div>';
                }
            }
        }

        $result['order_replaced'] = $check_replace_status;
        $result['layout'] = $layout;
        return $result;

    } 

    function getReplacedOrderItemVendorIds($vendor_order_id) 
    {
        $result = array();
        $vendor_order_ids   = array();
        $query = "SELECT * FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id='".$vendor_order_id."' ";
        $exe   = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0)
        {   
            while ($list = mysqli_fetch_assoc($exe) ) {
                if($list['replace_vendor_order_id']!=0) {
                    $vendor_order_ids[] = $list['replace_vendor_order_id'];
                }
            }
        }

        $result['count'] = count(array_unique($vendor_order_ids));
        
        if(count(array_unique($vendor_order_ids)) == 1)
        {   
            $result['vendor_order_id'] = $vendor_order_ids[0]; 
        }        

        return $result;
    }

    function getVendorsForThisOrderLocation($address_id,$current_vendor_id)
    {   
        $address_info       = $this->getDetails(CUSTOMER_ADDRESS_TBL,"*","Id='".$address_id."'");
        $location_group_id  = $address_info['city_id'];       
        $location_area_id   = $address_info['area_id'];    
        $query       =  "SELECT * FROM  ".VENDOR_TBL." WHERE delete_status='0' AND id!='".$current_vendor_id."' AND is_draft='0' AND status='1'";
        $exe         = $this->exeQuery($query);
        $vendor_id   = array();
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
            while ($row = mysqli_fetch_array($exe)) {
                
                $vendor_location_group =  in_array($location_group_id, explode(",", $row['delivery_locations']));
                $vendor_location_area  =  in_array($location_area_id,  explode(",", $row['delivery_areas']));

                if($vendor_location_group==true && $vendor_location_area==true)
                {
                    $vendor_id[] = $row['id'];             

                }
            }
        }   
        return $vendor_id;
    }

    // Get Product Price details for replce vendor 

    function replaceVendorPriceDetails($data)
    {   
        $layout            = "";
        $vendor_type       = $data['vendor_type'];
        $product_id        = $data['product_id'];
        $variant_id        = $data['variant_id'];
        $replace_vendor_id = $data['vendor_id'];
        $vendor_order_id   = $data['vendor_order_id'];

        $rejected_items    = $this->getRejectedOrderItems($vendor_order_id);

        $replace_vendor_info = $this->getDetails(VENDOR_TBL,"*","id='".$replace_vendor_id."'");

        $vendor_order_item_ids = implode(",", $rejected_items['ids']);

        $layout .=" <input type='hidden' name='vendor_order_item_ids' value='".$vendor_order_item_ids."'>
                    <input type='hidden' name='replace_vendor_id' value='".$replace_vendor_id."'>
                    <div class='form-group col-md-6'>
                        <label class='form-label'> Mobile 
                        </label>
                        <span name='product_name'  id='product_name' class='form-control'  >".$replace_vendor_info['mobile']."</span>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='form-label'> Email 
                        </label>
                        <span name='category' id='category' class='form-control' >".$replace_vendor_info['email']."</span>
                    </div>
                  ";

        foreach ($rejected_items['product_ids'] as $key => $value) 
        {   

            if($vendor_type=='same_vendor') {
                $order_item_info    = $this->getDetails(VENDOR_ORDER_ITEM_TBL,"*","vendor_order_id='".$vendor_order_id."' AND product_id='".$value."' AND variant_id='".$rejected_items['variants_ids'][$key]."' ");
                $variant_check = "";

                if($rejected_items['variants_ids'][$key]!=0 && $rejected_items['variants_ids'][$key]!="") {
                    $variant_check = "AND VP.variant_id='".$rejected_items['variants_ids'][$key]."' ";
                }
                   
                $query = "SELECT VP.id,VP.vendor_id,VP.variant_id,VP.selling_price,VE.id as vendorID,VE.company,P.product_uid,P.product_name,P.page_url,P.category_type,P.main_category_id,P.sub_category_id,V.variant_name FROM ".VENDOR_PRODUCTS_TBL."  VP 
                                        LEFT JOIN ".VENDOR_TBL." VE ON (VE.Id=VP.vendor_id)
                                        LEFT JOIN ".PRODUCT_TBL." P ON (P.id=VP.product_id)
                                        LEFT JOIN ".PRODUCT_VARIANTS." V ON (VP.variant_id=V.id) 
                    WHERE VP.product_id='".$value."' AND VP.status='1' AND VP.vendor_id='".$replace_vendor_id."' ".$variant_check." GROUP BY VP.vendor_id ORDER BY VP.selling_price ASC";
                
                $exe    = $this->exeQuery($query);

                if(mysqli_num_rows($exe) > 0){

                    while ($row = mysqli_fetch_array($exe)) {
                        $list   = $this->editPagePublish($row);

                        $name   = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

                        if($list['category_type']=="main")
                        {
                            $cat        = $this->getDetails(MAIN_CATEGORY_TBL,"id,category"," id='".$list['main_category_id']."' ");
                            $category   = $cat['category'];
                        } else {
                            $cat        = $this->getDetails(SUB_CATEGORY_TBL,"id,subcategory"," id='".$list['sub_category_id']."' ");
                            $category   = $cat['subcategory'];
                        }
                            $layout .="
                            <div class='form-group col-md-6'>
                                <label class='form-label'> Product 
                                </label>
                                <span name='product_name'  id='product_name' class='form-control'  >".$name."</span>
                            </div>
                            <div class='form-group col-md-2'>
                                <label class='form-label'> Selling Price 
                                </label>
                                <span name='category' id='category' class='form-control' >".$list['selling_price']."</span>
                            </div>
                            <div class='form-group col-md-2'>
                                <label class='form-label'> Qty 
                                </label>
                                <span name='qty' id='qty' class='form-control'>".$order_item_info['qty']."</span>
                            </div>
                            <div class='form-group col-md-2'>
                                <label class='form-label'> Price 
                                </label>
                                <span name='price' id='price' class='form-control'  >".($order_item_info['qty'] * $list['selling_price'])."</span>
                            </div>";
                    }
                }
            } elseif($value==$product_id && $rejected_items['variants_ids'][$key]==$variant_id) {
                $order_item_info    = $this->getDetails(VENDOR_ORDER_ITEM_TBL,"*","vendor_order_id='".$vendor_order_id."' AND product_id='".$value."' AND variant_id='".$rejected_items['variants_ids'][$key]."' ");
                $variant_check = "";

                if($rejected_items['variants_ids'][$key]!=0 && $rejected_items['variants_ids'][$key]!="") {
                    $variant_check = "AND VP.variant_id='".$rejected_items['variants_ids'][$key]."' ";
                }
                   
                $query = "SELECT VP.id,VP.vendor_id,VP.variant_id,VP.selling_price,VE.id as vendorID,VE.company,P.product_uid,P.product_name,P.page_url,P.category_type,P.main_category_id,P.sub_category_id,V.variant_name FROM ".VENDOR_PRODUCTS_TBL."  VP 
                                        LEFT JOIN ".VENDOR_TBL." VE ON (VE.Id=VP.vendor_id)
                                        LEFT JOIN ".PRODUCT_TBL." P ON (P.id=VP.product_id)
                                        LEFT JOIN ".PRODUCT_VARIANTS." V ON (VP.variant_id=V.id) 
                    WHERE VP.product_id='".$value."' AND VP.status='1' AND VP.vendor_id='".$replace_vendor_id."' ".$variant_check." GROUP BY VP.vendor_id ORDER BY VP.selling_price ASC";
                
                $exe    = $this->exeQuery($query);

                if(mysqli_num_rows($exe) > 0){

                    while ($row = mysqli_fetch_array($exe)) {
                        $list   = $this->editPagePublish($row);

                        $name   = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

                        if($list['category_type']=="main")
                        {
                            $cat        = $this->getDetails(MAIN_CATEGORY_TBL,"id,category"," id='".$list['main_category_id']."' ");
                            $category   = $cat['category'];
                        } else {
                            $cat        = $this->getDetails(SUB_CATEGORY_TBL,"id,subcategory"," id='".$list['sub_category_id']."' ");
                            $category   = $cat['subcategory'];
                        }
                            $layout .="
                            <div class='form-group col-md-6'>
                                <label class='form-label'> Product 
                                </label>
                                <span name='product_name'  id='product_name' class='form-control'  >".$name."</span>
                            </div>
                            <div class='form-group col-md-2'>
                                <label class='form-label'> Selling Price 
                                </label>
                                <span name='category' id='category' class='form-control' >".$list['selling_price']."</span>
                            </div>
                            <div class='form-group col-md-2'>
                                <label class='form-label'> Qty 
                                </label>
                                <span name='qty' id='qty' class='form-control'>".$order_item_info['qty']."</span>
                            </div>
                            <div class='form-group col-md-2'>
                                <label class='form-label'> Price 
                                </label>
                                <span name='price' id='price' class='form-control'  >".($order_item_info['qty'] * $list['selling_price'])."</span>
                            </div>";
                    }
                }
            } 
        }
      
        return $layout;
    }

    // Cancel Rejected Orders

    function cancellRejectedOrder($data)
    {      
        $vendor_order_id       = $data['vendor_order_id'];
        $vendororder_data      = $this->getRejectedOrderItems($vendor_order_id);
        $vendor_order_item_ids = implode(",", $vendororder_data['ids']);
        $order_item_ids        = implode(",", $vendororder_data['order_item_id']);

        $query = "UPDATE ".VENDOR_ORDER_ITEM_TBL." SET 
                    cancel_status = '1'
                  WHERE id IN (".$vendor_order_item_ids.")
                    ";
        $exe   = $this->exeQuery($query);

        if($exe) {
            $query2 = "UPDATE ".ORDER_ITEM_TBL." SET
                         cancel_status = '1'
                       WHERE id IN (".$order_item_ids.")
                        ";
            $exe2    = $this->exeQuery($query2);

            if($exe2)
            {
                $update_notification_mail = $this->cancellRejectedOrderMail($vendor_order_id);
                return 1;
            }
        }
    }

    function cancellRejectedOrderMail($vendor_order_id)
    {
        $info        = $this->getDetails(VENDOR_ORDER_TBL, "*","id='".$vendor_order_id."'");
        $v_info      = $this->getDetails(VENDOR_TBL, "*","id='".$info['vendor_id']."'");
        $email_info  = $this->getDetails(CUSTOMER_TBL,'id,name,email,token', " id ='".$info['user_id']."' ");
        $sender      = COMPANY_NAME;
        $sender_mail = NO_REPLY;
        $subject     = "Order was cancelled by admin - Order Id ".$info['order_uid'];
        $receiver    = $this->cleanString($email_info['email']);
        $message     = $this->cancellRejectedOrderTemplate($vendor_order_id);
        $send_mail   = $this->send_mail($sender_mail,$receiver,$subject,$message);
        return 1;
    }

    // Datas for card in payout page

    function getVendorDataCounts($vendor_id,$report_for="") 
    {   

        $result = array();

        if ( $report_for == "today" ) {
            
            $data_for       = "AND DATE(VO.delivery_date) = CURDATE()";
            $data_count_for = "AND DATE(delivery_date) = CURDATE()";

        } elseif ( $report_for == "yesterday" ) {

            $data_for       = "AND DATE(VO.delivery_date) = CURDATE() -1 ";
            $data_count_for = "AND DATE(delivery_date) = CURDATE() -1 ";

        } elseif ( $report_for == "oldest") {
            
            $data_for       = "AND DATE(VO.delivery_date) < CURDATE() -2";
            $data_count_for = "AND DATE(delivery_date) < CURDATE() -2";

        } else {
            $data_for       = "";
            $data_count_for = "";
        }

        $q  = "SELECT VO.id,VO.vendor_id,VO.delivery_date,VO.order_id,VO.order_date,VE.id as vendorId,VE.name,VE.mobile,VE.email,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id  ".$data_count_for.") as totalOrders,
                    (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id ".$data_count_for.") as totalAmount,
                    (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id  ".$data_count_for.") as totalPayment,
                    (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id  ".$data_count_for.") as totalShipping,
                    (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id ".$data_count_for.")  as totalCommission
                 FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) WHERE VO.order_status='2' AND vendor_id='$vendor_id' ".$data_for."  GROUP BY VO.vendor_id  DESC";
        $exe = $this->exeQuery($q);
        $list = mysqli_fetch_array($exe);
        return $list;
    }

    // Vendor Today order list

    function manageVendorTodayOrders($vendor_id="")
    {
        $layout ="";
        
        $condition = "";
        if($vendor_id!="") {
            $condition = "AND VO.vendor_id='".$vendor_id."' ";
        }

        $query  ="SELECT VO.id,VO.vendor_id,VO.order_id,VO.order_date,VO.delivery_date,VE.id as vendorId,VE.name,VE.company,VE.mobile,VE.email,
            (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) = CURDATE()  ) as totalOrders,
            (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) = CURDATE() ) as totalAmount,
            (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) = CURDATE()  ) as totalPayment,
            (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) = CURDATE()  ) as totalShipping,
            (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) = CURDATE() )  as totalCommission
         FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) WHERE VO.order_status='2' AND  DATE(VO.delivery_date) = CURDATE() ".$condition." GROUP BY vendor_id  DESC";


        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list               = $this->editPagePublish($rows);
                    $vendor_payouts_ids = $this->getVendorPayoutsRecordIds($list['vendor_id']);
                    $unpaid_orders      = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as unpaid_count ","vendor_id=".$list['vendor_id']." AND id NOT IN (" . implode(',', array_map('intval',$vendor_payouts_ids)). ") AND DATE(delivery_date) = CURDATE()  ");
                    $payable            = $list['totalAmount']- $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];
                    $commission         = $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];
                    $layout .="
                                <tr class='nk-tb-item open_payout_details' data-option='".$list['vendor_id']."' data-period='today'>
                                        <td class='nk-tb-col'>
                                            <span class='tb-lead'><a href=''>".$i."</a></span>
                                        </td>
                                         <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['company']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>".$list['totalOrders']."</span>
                                        </td>
                                       
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>₹ ".number_format($list['totalAmount'],2)."</span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$commission." </span>
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                            <span>".$unpaid_orders['unpaid_count']." </span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".number_format($payable,2) ." </span>
                                        </td>
                                    </tr>
                              ";
                $i++;
                }
        }
        return $layout;
    }

    // Vendor Yesterday order list

    function manageVendorYesterdayOrders($vendor_id="")
    {
        $layout ="";

        $condition = "";
        if($vendor_id!="") {
            $condition = "AND VO.vendor_id='".$vendor_id."' ";
        }

        $query  ="SELECT VO.id,VO.vendor_id,VO.order_id,VO.order_date,VO.delivery_date,VE.id as vendorId,VE.name,VE.company,VE.mobile,VE.email,
            (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) = CURDATE()-1  ) as totalOrders,
            (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) = CURDATE()-1 ) as totalAmount,
            (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) = CURDATE()-1  ) as totalPayment,
            (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) = CURDATE()-1  ) as totalShipping,
            (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) = CURDATE()-1 )  as totalCommission
         FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) WHERE VO.order_status='2' AND  DATE(VO.delivery_date) = CURDATE()-1 ".$condition." GROUP BY vendor_id  DESC";

        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $vendor_payouts_ids = $this->getVendorPayoutsRecordIds($list['vendor_id']);
                    $unpaid_orders = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as unpaid_count ","vendor_id=".$list['vendor_id']." AND id NOT IN (" . implode(',', array_map('intval',$vendor_payouts_ids)). ") AND DATE(delivery_date) = CURDATE()-1 ");

                    $payable = $list['totalAmount']- $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];
                    $commission = $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];

                    $layout .="
                                <tr class='nk-tb-item open_payout_details' data-option='".$list['vendor_id']."'  data-period='yesterday'>
                                        <td class='nk-tb-col'>
                                            <span class='tb-lead'><a href=''>".$i."</a></span>
                                        </td>
                                         <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['company']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>".$list['totalOrders']."</span>
                                        </td>
                                       
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>₹ ".number_format($list['totalAmount'],2)."</span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$commission." </span>
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                            <span>".$unpaid_orders['unpaid_count']." </span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".number_format($payable,2) ." </span>
                                        </td>
                                    </tr>

                              ";
                $i++;
                }
        }
        return $layout;
    }

    // Vendor oldest order list

    function manageVendorOldestOrders($vendor_id="")
    {
        $layout ="";

        $condition = "";

        if($vendor_id!="") {
            $condition = "AND VO.vendor_id='".$vendor_id."' ";
        }
        
        $query  ="SELECT VO.id,VO.vendor_id,VO.order_id,VO.order_date,VO.delivery_date,VE.id as vendorId,VE.name,VE.company,VE.mobile,VE.email,
            (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) < CURDATE()-2  ) as totalOrders,
            (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) < CURDATE()-2 ) as totalAmount,
            (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) < CURDATE()-2  ) as totalPayment,
            (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) < CURDATE()-2  ) as totalShipping,
            (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' AND vendor_id=VO.vendor_id AND DATE(delivery_date) < CURDATE()-2 )  as totalCommission
         FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) WHERE VO.order_status='2' AND  DATE(VO.delivery_date) < CURDATE()-2  ".$condition." GROUP BY vendor_id  DESC";

        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list    = $this->editPagePublish($rows);

                    $vendor_payouts_ids = $this->getVendorPayoutsRecordIds($list['vendor_id']);
                    $unpaid_orders      = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as unpaid_count ","vendor_id=".$list['vendor_id']." AND id NOT IN (" . implode(',', array_map('intval',$vendor_payouts_ids)). ") AND DATE(delivery_date) < CURDATE()-2 ");

                    $payable            = $list['totalAmount'] - $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];
                    $commission         = $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];

                    $layout .="
                                <tr class='nk-tb-item open_payout_details' data-option='".$list['vendor_id']."' data-period='oldest'>
                                        <td class='nk-tb-col'>
                                            <span class='tb-lead'><a href=''>".$i."</a></span>
                                        </td>
                                         <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['company']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>".$list['totalOrders']."</span>
                                        </td>
                                       
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>₹ ".number_format($list['totalAmount'],2)."</span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$commission." </span>
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                            <span>".$unpaid_orders['unpaid_count']." </span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".number_format($payable,2) ." </span>
                                        </td>
                                    </tr>

                              ";
                $i++;
                }
        }
        return $layout;
    } 

    // Vendor lastweek order list (Not In Use)

    function manageVendorLastWeekOrders()
    {
        $layout ="";
        $query  ="SELECT VO.id,VO.vendor_id,VO.order_id,VO.order_date,VE.id as vendorId,VE.name,VE.company,VE.mobile,VE.email,
            (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND DATE(order_date) = CURDATE()  ) as totalOrders,
            (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND DATE(order_date) = CURDATE() ) as totalAmount,
            (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND DATE(order_date) = CURDATE()  ) as totalPayment,
            (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND DATE(order_date) = CURDATE()  ) as totalShipping,
            (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND DATE(order_date) = CURDATE() )  as totalCommission
         FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) WHERE  DATE(VO.order_date) = CURDATE()  GROUP BY vendor_id  DESC";

        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $vendor_payouts_ids = $this->getVendorPayoutsRecordIds($list['vendor_id']);
                    $unpaid_orders = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as unpaid_count ","vendor_id=".$list['vendor_id']." AND id NOT IN (" . implode(',', array_map('intval',$vendor_payouts_ids)). ") AND  YEARWEEK(order_date) = YEARWEEK(NOW() - INTERVAL 1 WEEK) ");

                    $payable = $list['totalAmount']- $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];
                    $commission = $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];

                    $layout .="
                                <tr class='nk-tb-item'>
                                        <td class='nk-tb-col'>
                                            <span class='tb-lead'><a href=''>".$i."</a></span>
                                        </td>
                                         <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['company']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>".$list['totalOrders']."</span>
                                        </td>
                                       
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>₹ ".number_format($list['totalAmount'],2)."</span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$commission." </span>
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                            <span>".$unpaid_orders['unpaid_count']." </span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".number_format($payable,2) ." </span>
                                        </td>
                                       
                                        <td class='nk-tb-col nk-tb-col-tools'>
                                            <ul class='nk-tb-actions gx-1'>
                                                <li class='nk-tb-action-hidden'><a href='".COREPATH."orders/vendorLastWeekPayouts/".$list['vendorId']."' class='btn btn-icon btn-trigger btn-tooltip' title='Pay'><em class='icon ni ni-wallet-out'></em></a>
                                                </li>
                                                <li>
                                                    <div class='drodown mr-n1'>
                                                        <a href='#' class='dropdown-toggle btn btn-icon btn-trigger' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a>
                                                        <div class='dropdown-menu dropdown-menu-right'>
                                                            <ul class='link-list-opt no-bdr'>
                                                                <li><a href='".COREPATH."orders/vendorLastWeekPayouts/".$list['vendorId']."'><em class='icon ni ni-wallet-out'></em><span>Pay</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>

                              ";
                $i++;
                }
        }
        return $layout;
    } 

    // Vendor thisweek order list (Not In Use)

    function manageVendorThisWeekOrders()
    {
        $layout ="";
        $query  ="SELECT VO.id,VO.vendor_id,VO.order_id,VO.order_date,VE.name,VE.company,VE.mobile,VE.email,
            (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND YEARWEEK(order_date) = YEARWEEK(NOW()) ) as totalOrders,
            (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND YEARWEEK(order_date) = YEARWEEK(NOW())) as totalAmount,
            (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND YEARWEEK(order_date) = YEARWEEK(NOW()) ) as totalPayment,
            (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND YEARWEEK(order_date) = YEARWEEK(NOW()) ) as totalShipping,
            (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND YEARWEEK(order_date) = YEARWEEK(NOW()))  as totalCommission
         FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) WHERE VO.order_status='2' AND YEARWEEK(VO.order_date) = YEARWEEK(NOW()) GROUP BY vendor_id  DESC";
        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $payable = $list['totalAmount']- $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];
                    $commission = $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];

                    $vendor_payouts_ids = $this->getVendorPayoutsRecordIds($list['vendor_id']);
                    $unpaid_orders = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as unpaid_count ","vendor_id=".$list['vendor_id']." AND id NOT IN (" . implode(',', array_map('intval',$vendor_payouts_ids)). ") AND YEARWEEK(order_date) = YEARWEEK(NOW()) ");
                    $layout .="
                                <tr class='nk-tb-item'>
                                        <td class='nk-tb-col'>
                                            <span class='tb-lead'><a href=''>".$i."</a></span>
                                        </td>
                                         <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['company']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>".$list['totalOrders']."</span>
                                        </td>
                                       
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>₹ ".number_format($list['totalAmount'],2)."</span>
                                        </td>

                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$commission." </span>
                                           
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                            <span>".$unpaid_orders['unpaid_count']." </span>
                                        </td>
                                         <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".number_format($payable,2) ." </span>
                                           
                                        </td>
                                         
                                       
                                        <td class='nk-tb-col nk-tb-col-tools'>
                                            <ul class='nk-tb-actions gx-1'>
                                                <li class='nk-tb-action-hidden'><a href='".COREPATH."orders/vendorpayout/".$list['vendor_id']."' class='btn btn-icon btn-trigger btn-tooltip' title='Pay'><em class='icon ni ni-wallet-out'></em></a>
                                                </li>
                                                <li>
                                                    <div class='drodown mr-n1'>
                                                        <a href='#' class='dropdown-toggle btn btn-icon btn-trigger' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a>
                                                        <div class='dropdown-menu dropdown-menu-right'>
                                                            <ul class='link-list-opt no-bdr'>
                                                                <li><a href='".COREPATH."orders/vendorpayout/".$list['vendor_id']."'><em class='icon ni ni-wallet-out'></em><span>Pay</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>

                              ";
                $i++;
                }
        }
        return $layout;
    }

    // Id's of paid vendor payouts

    function getVendorPayoutsRecordIds($vendor_id="")
    {
        $layout     = "";

        $q          = "SELECT id FROM ".VENDOR_PAYOUT_INVOICE_TBL." WHERE vendor_id='$vendor_id' ";
        $exe        = $this->exeQuery($q);
        $ids        = array();
        while($list = mysqli_fetch_array($exe)){
            $ids[]  = $list['id'];
        }


        $q                = "SELECT vendor_order_id FROM ".VENDOR_PAYOUT_INVOICE_ITEM_TBL." WHERE payout_id IN ( '" . implode( "', '" , $ids ) . "' ) ";
        $exe              = $this->exeQuery($q);
        $vendor_order_ids = array();
       
        while($list = mysqli_fetch_array($exe)){
            $vendor_order_ids[] = $list['vendor_order_id'];
        }

        if(count($vendor_order_ids)==0) 
        {
            $vendor_order_ids[] = 0;
        }
        
        return $vendor_order_ids;
    }

    // Get Unpaid list of vendor payouts

    function getVendorUnpaidlist($vendor_id,$report_for="")
    {
        $layout ="";


        if ( $report_for == "today" ) {
            
            $data_for       = "AND DATE(VO.delivery_date) = CURDATE()";
        } elseif ( $report_for == "yesterday" ) {

            $data_for       = "AND DATE(VO.delivery_date) = CURDATE() -1 ";
        } elseif ( $report_for == "oldest") {
            
            $data_for       = "AND DATE(VO.delivery_date) < CURDATE() -2";
        } else {
            $data_for = "";
            if(@$report_for['from']!="") {
                $data_for = "AND DATE(VO.order_date) BETWEEN '".date("Y-m-d",strtotime($report_for['from']))."' AND '".date("Y-m-d",strtotime($report_for['to']))."' ";
            }
        } 

        $vendor_payouts_ids = $this->getVendorPayoutsRecordIds($vendor_id);

        $query  ="SELECT VO.id,VO.vendor_id,VO.vendor_payment_total,VO.vendor_shipping_total,VO.vendor_commission_total,VO.total_amount,VO.order_date,VO.order_uid,VO.order_id,VE.name as vendor_name,VE.company,VE.mobile as vendor_mobile,VE.email as vendor_email,C.name as cus_name FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) LEFT JOIN ".CUSTOMER_TBL." C ON (C.id=VO.user_id) WHERE VO.order_status='2' AND vendor_id='$vendor_id' AND VO.id NOT IN ( '" . implode( "', '" , $vendor_payouts_ids ) . "' ) $data_for ";  
        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $commission = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
                    $payable = $list['total_amount'] - $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];

                    $layout .="
                                <tr class='tb-tnx-item'>
                                    <td class='tb-tnx-id'>
                                            <div class='custom-control custom-checkbox custom-control-sm'>
                                            <input type='hidden' name='vendor_id' value=".$list['vendor_id'].">
                                            <input type='checkbox' class='custom-control-input' name='vendor_selected_invoice[]' id='customCheck".$i."' value=".$list['id'].">
                                            <label class='custom-control-label' for='customCheck".$i."'></label>
                                        </div>
                                    </td>
                                     <td class='tb-tnx-info'>
                                        <div class='tb-tnx-total'>
                                           <span class='amount'>".$list['order_uid']."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                            <span class='amount'>".date("d-M-Y",strtotime($list['order_date']))."</span>
                                        </div>
                                    </td>
                                   
                                    <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>".$list['company']."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                           <span class='amount'>".$list['cus_name']."</span>
                                        </div>
                                    </td>
                                     <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>₹ ".$this->inrFormat($list['total_amount'])."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                           <span class='amount'>₹ ".$this->inrFormat($commission)."</span>
                                        </div>
                                    </td>
                                    <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>₹ ".$this->inrFormat($payable)."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                            <span class='badge badge-dot badge-warning'>Due</span>
                                        </div>
                                    </td>
                                </tr>
                              ";
                $i++;
                }
        } else {
            $layout .= "<td colspan='5' class='text-center' >No Records</td>";
        }
        return $layout;
    }

    // Get Unpaid list of vendor payouts

    function getVendorUnpaidPayoutlist($vendor_id,$report_for="")
    {
        $layout ="";


        if ( $report_for == "today" ) {
            
            $data_for       = "AND DATE(VO.delivery_date) = CURDATE()";
        } elseif ( $report_for == "yesterday" ) {

            $data_for       = "AND DATE(VO.delivery_date) = CURDATE() -1 ";
        } elseif ( $report_for == "oldest") {
            
            $data_for       = "AND DATE(VO.delivery_date) < CURDATE() -2";
        } else {
            $data_for = "";
            if(@$report_for['from']!="") {
                $data_for = "AND DATE(VO.order_date) BETWEEN '".date("Y-m-d",strtotime($report_for['from']))."' AND '".date("Y-m-d",strtotime($report_for['to']))."' ";
            }
        } 

        $vendor_payouts_ids = $this->getVendorPayoutsRecordIds($vendor_id);

        $query  ="SELECT VO.id,VO.vendor_id,VO.vendor_payment_total,VO.vendor_shipping_total,VO.vendor_commission_total,VO.total_amount,VO.order_date,VO.order_uid,VO.order_id,VE.name as vendor_name,VE.company,VE.mobile as vendor_mobile,VE.email as vendor_email,C.name as cus_name FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) LEFT JOIN ".CUSTOMER_TBL." C ON (C.id=VO.user_id) WHERE VO.order_status='2' AND vendor_id='$vendor_id' AND VO.id NOT IN ( '" . implode( "', '" , $vendor_payouts_ids ) . "' ) $data_for ";  
        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $commission = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
                    $payable = $list['total_amount'] - $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];

                    $layout .="
                                <tr class='tb-tnx-item special_tbl_td_hover open_payout_details' data-option='".$list['vendor_id']."'>
                                    <td class='tb-tnx-id'>
                                           ".$i.".
                                        </div>
                                    </td>
                                     <td class='tb-tnx-info'>
                                        <div class='tb-tnx-total'>
                                           <span class='amount'>".$list['order_uid']."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                            <span class='amount'>".date("d-M-Y",strtotime($list['order_date']))."</span>
                                        </div>
                                    </td>
                                   
                                    <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>".$list['company']."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                           <span class='amount'>".$list['cus_name']."</span>
                                        </div>
                                    </td>
                                     <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>₹ ".$this->inrFormat($list['total_amount'])."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                           <span class='amount'>₹ ".$this->inrFormat($commission)."</span>
                                        </div>
                                    </td>
                                    <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>₹ ".$this->inrFormat($payable)."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                            <span class='badge badge-dot badge-warning'>Due</span>
                                        </div>
                                    </td>
                                </tr>
                              ";
                $i++;
                }
        } else {
            $layout .= "<td colspan='5' class='text-center' >No Records</td>";
        }
        return $layout;
    }

    // Get vendor order ids for last and this week

    function getVendorOrderIds($vendor_id="",$report_for)
    {
        if ( $report_for == "today" ) {
            
            $data_for       = "AND DATE(order_date) = CURDATE()";
        } elseif ( $report_for == "yesterday" ) {

            $data_for       = "AND DATE(order_date) = CURDATE() -1 ";
        } elseif ( $report_for == "oldest") {
            
            $data_for       = "AND DATE(order_date) < CURDATE() -2";
        } else {
            $data_for = "";
            if(@$report_for['from']!="") {
                $data_for = "AND DATE(order_date) BETWEEN '".date("Y-m-d",strtotime($report_for['from']))."' AND '".date("Y-m-d",strtotime($report_for['to']))."' ";
            }
        }

        $query  ="SELECT id FROM ".VENDOR_ORDER_TBL." WHERE vendor_id='".$vendor_id."' ";


        $exe = $this->exeQuery($query);
        $vendor_order_ids = array();

        while($list = mysqli_fetch_array($exe)){
            $vendor_order_ids[] =    $list['id'];
        }
        if(count($vendor_order_ids)==0) 
        {
           $vendor_order_ids[] = 0;
        }
        
        return $vendor_order_ids;
    }

    // get payout ids for orders

    function getOrderPayOuts($order_ids='',$report_for)
    {   

        $data_for = "";


        // if ( $report_for == "today" ) {
            
        //     $data_for       = "AND DATE(created_at) = CURDATE()";
        // } elseif ( $report_for == "yesterday" ) {

        //     $data_for       = "AND DATE(created_at) = CURDATE() -1 ";
        // } elseif ( $report_for == "oldest") {
            
        //     $data_for       = "AND DATE(created_at) < CURDATE() -2";
        // } else {
        //     $data_for = "";
        //     if(@$report_for['from']!="") {
        //         $data_for = " AND DATE(created_at) BETWEEN '".date("Y-m-d",strtotime($report_for['from']))."' AND '".date("Y-m-d",strtotime($report_for['to']))."' ";
        //     }
        // }


        $query = "SELECT payout_id FROM ".VENDOR_PAYOUT_INVOICE_ITEM_TBL." WHERE vendor_order_id  IN ( '" . implode( "', '" , $order_ids ) . "' ) ".$data_for." GROUP BY payout_id     ";


        $exe = $this->exeQuery($query);
        $payout_ids = array();

        while($list = mysqli_fetch_array($exe)){
            $payout_ids[] =    $list['payout_id'];
        }
        if(count($payout_ids)==0) 
        {
           $payout_ids[] = 0;
        }
        
        return $payout_ids;
    }

    //  Get paid list of vendor payouts

    function getVendorPaidlist($vendor_id,$report_for="")
    {   
        $layout         = "";
        $ven_ord_ids    = $this->getVendorOrderIds($vendor_id,$report_for);
        $get_payout_ids = $this->getOrderPayOuts($ven_ord_ids,$report_for);


        $query  ="SELECT PA.id,PA.payout_invoice_id,PA.vendor_id,PA.net_payable,PA.total_order_value,PA.total_commission,PA.created_at,VE.company,
        (SELECT COUNT(id) FROM ".VENDOR_PAYOUT_INVOICE_ITEM_TBL." WHERE payout_id=PA.id ) as total_orders
         FROM ".VENDOR_PAYOUT_INVOICE_TBL." PA LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=PA.vendor_id)  WHERE vendor_id=".$vendor_id." AND PA.id IN ( '" . implode( "', '" , $get_payout_ids ) . "' ) ";

        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {

                    $list   = $this->editPagePublish($rows);

                    $payable = $list['total_order_value'] - $list['net_payable'] ;

                    $layout .="
                                <tr class='tb-tnx-item open_payout_invoice' data-option='".$list['id']."'>
                                    <td class='tb-tnx-id'>
                                            ".$i."
                                        </div>
                                    </td>
                                     <td class='tb-tnx-info'>
                                        <div class='tb-tnx-desc payout_list_invoiceid_column'  >
                                            <span class='title'>".$list['payout_invoice_id']."</span>

                                        </div>
                                    </td>
                                    <td class='tb-tnx-amount'>
                                           ".date("d-M-Y",strtotime($list['created_at']))."
                                    </td>

                                   
                                    <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>".$list['company']."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                           <span class='amount'>".$list['total_orders']."</span>
                                        </div>
                                    </td>
                                     <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>₹ ".$this->inrFormat($list['total_order_value'])."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                           <span class='amount'>₹ ".number_format($payable,2)." </span>
                                        </div>
                                    </td>
                                    <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>₹ ".number_format($list['net_payable'],2)."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                            <span class='badge badge-dot badge-success'>Paid</span>
                                        </div>
                                    </td>
                                    <td class='tb-tnx-id'>
                                        <a href='".COREPATH."orders/payoutInvoice/".$list['id']."' target='_blank'  class='btn btn-sm btn-success payout_list_action_invoice_btn' ><em class='icon ni ni-file'></em><span>Invoice</span></a>
                                    </td>
                                </tr>
                              ";
                $i++;
                }
        } else {
            $layout .= "<td colspan='6' class='text-center' >No Records</td>";
        }
        return $layout;
    }

    // Get Order ids in paid payouts 

    function getPayoutorderRecordIds($payout_id="")
    {
        $q = "SELECT vendor_order_id FROM ".VENDOR_PAYOUT_INVOICE_ITEM_TBL." WHERE payout_id='".$payout_id."' ";
        $exe = $this->exeQuery($q);
        $vendor_order_ids = array();
        while($list = mysqli_fetch_array($exe)){
        $vendor_order_ids[] =    $list['vendor_order_id'];
        }
        if(count($vendor_order_ids)==0) 
        {
            $vendor_order_ids[] = 0;
        }
        
        return $vendor_order_ids;
    }

    // Vendor payout list for invoice 

    function manageVendorPayouts($payout_id="")
    {
       $layout ="";
       $payout_info = $this->getDetails(VENDOR_PAYOUT_INVOICE_TBL,"*"," id='".$payout_id."' ");
       $vendor_order_ids = $this->getPayoutorderRecordIds($payout_id);
        

       $query  ="SELECT VO.id,VO.vendor_id,VO.vendor_payment_total,VO.vendor_shipping_total,VO.vendor_commission_total,VO.total_amount,VO.order_date,VO.order_uid,VO.order_id,VE.name as vendor_name,VE.mobile as vendor_mobile,VE.email as vendor_email,C.name as cus_name,VI.vendor_commission,VI.vendor_commission_tax,VI.vendor_payment_charge,VI.vendor_payment_tax,VI.vendor_shipping_charge,VI.vendor_shipping_tax,
             (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id  ) as totalOrders,
             (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) ) as totalAmount,
             (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) ) as totalPayment,
             (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) ) as totalShipping,
             (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) )  as totalCommission,
             (SELECT SUM(vendor_commission_tax_amt) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_id=VO.vendor_id AND vendor_order_id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) )  as vendor_commission_tax_amt,
             (SELECT SUM(vendor_payment_tax_amt) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_id=VO.vendor_id AND vendor_order_id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) )  as vendor_payment_tax_amt,
             (SELECT SUM(vendor_shipping_tax_amt) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_id=VO.vendor_id AND vendor_order_id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) )  as vendor_shipping_tax_amt
          FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) LEFT JOIN ".CUSTOMER_TBL." C ON (C.id=VO.user_id) LEFT JOIN ".VENDOR_ORDER_ITEM_TBL." VI ON (VI.vendor_order_id=VO.id) WHERE VO.vendor_id='".$payout_info['vendor_id']."' AND VO.id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) LIMIT 1 ";


        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $layout .='
                     '.$this->manageVendorPayoutList($payout_id).'
                     ';
                     $net_payable = $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];
                     $payable     = $list["totalAmount"] - $net_payable;

                     $commission_wo_tax = $list['totalCommission'] - $list['vendor_commission_tax_amt'];
                     $payment_wo_tax    = $list['totalPayment'] - $list['vendor_payment_tax_amt'];
                     $shipping_wo_tax   = $list['totalShipping'] - $list['vendor_shipping_tax_amt'];

                    $layout .='
                        <tr style="background: #F5F5F5;">
                            <td colspan="6" style="text-align: right;border-top: 1px solid #222;"><strong>Sub Total</strong></td>
                            <td style="text-align: right;border-top: 1px solid #222;">Rs. '.number_format($list["totalAmount"],2).'</td>
                        </tr>
                        <tr style="background: #F5F5F5;">
                            <td colspan="6" style="text-align: right;"><strong>Sapiens Commission </strong> <br>  ('.$list['vendor_commission'].' % Commission + '.$list['vendor_commission_tax'].' % Tax) </td>
                            <td style="text-align: right;">Rs. '.number_format($list['totalCommission'],2).'<br> ('.$this->inrFormat($commission_wo_tax).' + '.$this->inrFormat($list['vendor_commission_tax_amt']).') </td>
                        </tr>

                        <tr style="background: #F5F5F5;">
                            <td colspan="6" style="text-align: right;"><strong>Payment Gateway Charge</strong> <br>  ('.$list['vendor_payment_charge'].' % Charge + '.$list['vendor_payment_tax'].' % Tax) </td>
                            <td style="text-align: right;">Rs. '.number_format($list['totalPayment'],2).'<br> ('.$this->inrFormat($payment_wo_tax).' + '.$this->inrFormat($list['vendor_payment_tax_amt']).') </td>
                        </tr>

                        <tr style="background: #F5F5F5;">
                            <td colspan="6" style="text-align: right;"><strong>Shipping Charge</strong> <br>  ('.$list['vendor_shipping_charge'].' % Charge + '.$list['vendor_shipping_tax'].' % Tax) </td>
                            <td style="text-align: right;">Rs. '.number_format($list['totalShipping'],2).'<br> ('.$this->inrFormat($shipping_wo_tax).' + '.$this->inrFormat($list['vendor_shipping_tax_amt']).') </td>
                        </tr>

                        <tr style="background: #F5F5F5;">
                            <td colspan="4" style="border-bottom: 1px solid #222;border-top: 1px solid #5D6975;">Total Amount in Words: <strong>'.$this->amountInWords($payable).'</strong></td>
                            <td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;"><strong>Vendor Payable</strong></td>
                            <td  style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;;"><strong>Rs. '.number_format($payable,2).'</strong></td>
                        </tr>'
                           ;
                $i++;
                }
        } else {
            $layout .= "<td colspan='5' class='text-center' >No Records</td>";
        }
        return $layout;
    }

    // Vendor payout list for invoice 

    function manageVendorPayoutList($payout_id="")
    {
       $layout ="";
       $payout_info = $this->getDetails(VENDOR_PAYOUT_INVOICE_TBL,"*"," id='".$payout_id."' ");
       $vendor_order_ids = $this->getPayoutorderRecordIds($payout_id);
        

        $query  ="SELECT VO.id,VO.vendor_id,VO.vendor_payment_total,VO.vendor_shipping_total,VO.vendor_commission_total,VO.total_amount,VO.order_date,VO.order_uid,VO.order_id,VE.name as vendor_name,VE.mobile as vendor_mobile,VE.email as vendor_email,C.name as cus_name,
             (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id  ) as totalOrders,
             (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id ) as totalAmount,
             (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id  ) as totalPayment,
             (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id  ) as totalShipping,
             (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id )  as totalCommission
          FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) LEFT JOIN ".CUSTOMER_TBL." C ON (C.id=VO.user_id) WHERE vendor_id='".$payout_info['vendor_id']."' AND VO.id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) ";

        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $payable = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
                    $net_payable =$list['total_amount']- $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];

                    $layout .='
                        <tr>
                            <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$list['order_uid'].'</td>
                            <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: left;">'.date("d-m-Y",strtotime($list['order_date'])).'</td>
                            <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$list['cus_name'].'</td>
                            <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">Rs.'.number_format($list['total_amount'],2).'</td>
                            <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;"> Rs. '.number_format($payable,2) .'</td>
                            <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">Rs.'.number_format($net_payable,2).'</td>
                            <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: right;">Paid</td>
                            
                        </tr>';
                $i++;
                }
        } else {
            $layout .= "<td colspan='5' class='text-center' >No Records</td>";
        }
        return $layout;
    }

    /*-------------------------------------------
            Function For Payout Histroy
    --------------------------------------------*/

    // List of paid payouts

    function getPaidHistroy()
    {
        $layout ="";

        $query  ="SELECT PA.id,PA.payout_invoice_id,PA.vendor_id,PA.net_payable,PA.total_order_value,PA.total_commission,PA.created_at,VE.company,
        (SELECT COUNT(id) FROM ".VENDOR_PAYOUT_INVOICE_ITEM_TBL." WHERE payout_id=PA.id ) as total_orders
         FROM ".VENDOR_PAYOUT_INVOICE_TBL." PA LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=PA.vendor_id)  WHERE 1 ";

        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {

                    $list   = $this->editPagePublish($rows);

                    $payable = $list['total_order_value'] - $list['net_payable'] ;

                    $layout .="
                                <tr class='tb-tnx-item special_tbl_td_hover open_payout_invoice' data-option='".$list['id']."'>
                                    <td class='tb-tnx-id'>
                                            ".$i.". 
                                        </div>
                                    </td>
                                     <td class='tb-tnx-info'>
                                        <div class='tb-tnx-desc payout_list_invoiceid_column' >
                                            <span class='title'>".$list['payout_invoice_id']."</span>

                                        </div>
                                    </td>
                                    <td class='tb-tnx-amount'>
                                           ".date("d-M-Y",strtotime($list['created_at']))."
                                    </td>

                                   
                                    <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>".$list['company']."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                           <span class='amount'>".$list['total_orders']."</span>
                                        </div>
                                    </td>
                                     <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>₹ ".$list['total_order_value']."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                           <span class='amount'>₹ ".$this->inrFormat($payable,2)."</span>
                                        </div>
                                    </td>
                                    <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>₹ ".$this->inrFormat($list['net_payable'],2)."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                            <span class='badge badge-dot badge-success'>Paid</span>
                                        </div>
                                    </td>
                                    <td class='tb-tnx-id'>
                                            <a href='".COREPATH."orders/payoutInvoice/".$list['id']."' target='_blank'  class='btn btn-sm btn-success payout_list_action_invoice_btn' ><em class='icon ni ni-file'></em><span>Invoice</span></a>
                                    </td>
                                </tr>
                              ";
                $i++;
                }
        } else {
            $layout .= "<td colspan='6' class='text-center' >No Records</td>";
        }
        return $layout;
    }

    // Id's of paid payouts

    function getVendorPaidRecordIds()
    {
        $layout = "";

        $q = "SELECT id FROM ".VENDOR_PAYOUT_INVOICE_TBL." WHERE 1 ";
        $exe = $this->exeQuery($q);
        $paid_payouts_ids = array();
        while($list = mysqli_fetch_array($exe)){
        $paid_payouts_ids[] =    $list['id'];
        }

        if(count($paid_payouts_ids)==0) 
        {
            $paid_payouts_ids[] = 0;
        }

        $q2 = "SELECT id,vendor_order_id FROM ".VENDOR_PAYOUT_INVOICE_ITEM_TBL." WHERE payout_id IN ( '" . implode( "', '" , $paid_payouts_ids ) . "' ) ";
        $exe2 = $this->exeQuery($q2);
        $unpaid_order_ids = array();
        while($list = mysqli_fetch_array($exe2)){
        $unpaid_order_ids[] =    $list['vendor_order_id'];
        }

        if(count($unpaid_order_ids)==0) 
        {
            $unpaid_order_ids[] = 0;
        }
        return $unpaid_order_ids;
    }

    // List of unpaid payouts 

    function getUnpaidlist()
    {
        $layout ="";

        $paid_payouts_ids = $this->getVendorPaidRecordIds();
        $query  ="SELECT VO.id,VO.vendor_id,VO.vendor_payment_total,VO.vendor_shipping_total,VO.vendor_commission_total,VO.total_amount,VO.order_date,VO.order_uid,VO.order_id,VO.order_status,VE.name as vendor_name,VE.company,VE.mobile as vendor_mobile,VE.email as vendor_email,C.name as cus_name FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) LEFT JOIN ".CUSTOMER_TBL." C ON (C.id=VO.user_id) WHERE VO.order_status=2 AND VO.id NOT IN ( '" . implode( "', '" , $paid_payouts_ids ) . "' )  ";  

        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $commission = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
                    $payable = $list['total_amount'] - $commission;

                    $layout .="
                                <tr class='tb-tnx-item special_tbl_td_hover open_payout_details' data-option='".$list['vendor_id']."'>
                                    <td class='tb-tnx-id'>
                                            ".$i.".
                                        </div>
                                    </td>
                                     <td class='tb-tnx-info'>
                                        <div class='tb-tnx-total'>
                                           <span class='amount'>".$list['order_uid']."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                            <span class='amount'>".date("d-M-Y",strtotime($list['order_date']))."</span>
                                        </div>
                                    </td>
                                    <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>".$list['company']."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                           <span class='amount'>".$list['cus_name']."</span>
                                        </div>
                                    </td>
                                     <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>₹ ".$list['total_amount']."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                           <span class='amount'>₹ ".$this->inrFormat($commission)."</span>
                                        </div>
                                    </td>
                                    <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>₹ ".$this->inrFormat($payable)."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                            <span class='badge badge-dot badge-warning'>Due</span>
                                        </div>
                                    </td>
                                </tr>
                              ";
                $i++;
                }
        } else {
            $layout .= "<td colspan='5' class='text-center' >No Records</td>";
        }
        return $layout;
    }

    // Payout History Short

    function vendorShort($input="")
    {   
        $layout             = array();
        $report_for         = array();
        $report_for['from'] = $input['sort_from'];
        $report_for['to']   = $input['sort_to'];

        if($input['payout_list']=="paid") 
        {
            $paid_list = $this->getVendorPaidlist($input['vendor_id'],$report_for);
        } else {
            $paid_list = $this->getVendorUnpaidPayoutlist($input['vendor_id'],$report_for);
        }

        $v_info              = $this->getDetails(VENDOR_TBL,"*","id='".$input['vendor_id']."' ");
        $layout['v_info']    = $v_info;
        $layout['paid_list'] = $paid_list;
        $layout['from']      = $input['sort_from'];
        $layout['to']        = $input['sort_to'];

        return json_encode($layout);
    }

	function ordersStatusList($from="",$to="")
    {
        $result = array();
        $result['new_orders']    = "";
        $result['return_orders'] = "";
        $result['rejected_orders'] = "";
        $result['empty'] = "";

        if($from!="" && $from!=0 ) 
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to' ";
        } else {
            $date_filter = "";
        }

        $query  ="SELECT VO.id,VO.vendor_id,VO.user_id,VO.order_id,VO.order_uid,VO.shipping_cost,VO.sub_total,VO.total_tax,VO.sgst_amt,VO.cgst_amt,VO.igst_amt,VO.vendor_payment_total,VO.vendor_commission_total,VO.vendor_shipping_total,VO.total_amount,VO.total_payment,VO.order_date,VO.order_status,VO.status,VO.vendor_response,VO.vendor_accept_status,VO.response_notes,VO.return_status,VO.return_reason,VO.return_comment,VE.company,VE.mobile as v_mobile,VE.email as v_email,C.name as c_name,C.mobile as c_mobile, C.email as c_email,
            (SELECT vendor_invoice_number FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id LIMIT 1) as invoiveNumber,
            (SELECT SUM(coupon_value) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id ) as couponValue,
            (SELECT SUM(coupon_value) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id AND return_status='1' ) as return_couponValue,
            (SELECT COUNT(id) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id ) as items,
            (SELECT COUNT(id) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id AND return_status='1' ) as return_items,
            (SELECT SUM(sub_total) + SUM(total_tax) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id AND return_status='1' ) as return_total,
            (SELECT SUM(vendor_commission_amt) +  SUM(vendor_payment_charge_amt) +
                     SUM(vendor_shipping_charge_amt)  FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id AND return_status='1' ) as return_charge_total
          FROM ".VENDOR_ORDER_TBL." VO  LEFT JOIN ".VENDOR_TBL." VE ON (VO.vendor_id=VE.id) 
                                        LEFT JOIN ".CUSTOMER_TBL." C ON (C.Id=VO.user_id) 
                                        LEFT JOIN ".RETURN_REASON_TBL." RR ON (RR.id=VO.return_reason) 
          WHERE 1  ".$date_filter." ORDER BY VO.id  DESC";
        $exe    = $this->exeQuery($query);

        if(mysqli_num_rows($exe) > 0) {
            $i=1;
            $j=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $total_charges  = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'] ; 

                    $total          =  $this->inrFormat($list['sub_total'] + $list['igst_amt'] - $list['couponValue'] );

                    $response       = (($list['vendor_response']==0) ? "Not Seen" :  (($list['vendor_accept_status']==0) ? "Rejected" :  "Accepted" ) );
                    $response_class = (($list['vendor_response']==0) ? "text-warning" :  (($list['vendor_accept_status']==0) ? "text-danger" :  "text-success" ) );

                    if($list['return_status']==1) {
                        $layout = "return_orders";
                        $list['items'] = $list['return_items'];
                        $total_charges = $list['return_charge_total'];
                        $total         =  $this->inrFormat($list['return_total'] );

                    } elseif ($list['vendor_response']=='1' && $list['vendor_accept_status']=='0') {
                        $layout = "rejected_orders";
                    } elseif ($list['vendor_response']==0 && $list['vendor_accept_status']==0) {
                        $layout = "new_orders";
                    } else {
                        $layout = "empty" ;
                    }

                    if($list['return_status']==1) {
                        $status_td = "<span class='tb-status text-danger'>Returned</span>";
                        $s_no =  $j++;
                    } else {
                        $status_td = "<span class='tb-status $response_class'>$response</span>";                        
                        $s_no =  $i++;
                    }

                    $result[$layout] .="
                                <tr class='nk-tb-item open_enq_model' data-page='".$layout."' data-option='".$list['order_id']."'>
                                        <td class='nk-tb-col '>
                                        ".$s_no."
                                        </td>
                                        <td class='nk-tb-col'>
                                         <span class='tb-lead'><a href='".COREPATH."orders/vendororderdetails/".$list['id']."'>".$list['invoiveNumber']."</a></span>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>".date('d/m/Y',strtotime($list['order_date']))."</span>
                                        </td>
                                        <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['c_name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['c_email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['c_mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['company']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['v_email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['v_mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>".$list['items']." <span class='currency'>".(($list['items'] > 1 )? 'Items' : 'Item')."</span></span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($total_charges)." </span>
                                           
                                        </td>
                                         <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$total." </span>
                                           
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                          ".$status_td."  
                                        </td>
                                    </tr>

                              ";
                $i++;
                }
        }

        return $result;
    }

    function rejectedOrdersList()
    {
        $layout = "";
        $query  = "SELECT O.id,O.user_id,O.vendor_order_id,O.variant_id,O.vendor_invoice_number,O.replace_vendor_order_item_id,O.vendor_id,O.order_status,O.cart_id,O.product_id,O.coupon_value,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.qty,O.sub_total,O.igst_amt,O.vendor_commission_tax_amt,O.vendor_payment_tax_amt,O.vendor_shipping_tax_amt,O.vendor_commission_tax,O.vendor_payment_tax,O.vendor_shipping_tax,O.total_tax,O.sgst,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.vendor_payment_charge,O.vendor_payment_charge_amt,O.vendor_shipping_charge,O.vendor_shipping_charge_amt,O.vendor_commission,O.vendor_commission_amt,O.total_amount,O.shipping_remarks,O.delivery_date,O.delivery_remarks,O.cancel_comment,O.vendor_response,O.vendor_accept_status,P.product_uid,P.product_name,P.page_url,P.category_type,P.main_category_id,P.sub_category_id,V.variant_name,OD.order_uid,OD.created_at,OD.order_date,VE.company,VE.mobile as v_mobile,VE.email as v_email,C.name as c_name,C.mobile as c_mobile, C.email as c_email,
                (SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
                (SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image,
                (SELECT vendor_invoice_number FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=O.vendor_order_id LIMIT 1) as invoiveNumber,
                (SELECT SUM(coupon_value) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=O.vendor_order_id LIMIT 1) as couponValue,
                (SELECT COUNT(id) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=O.vendor_order_id AND vendor_accept_status='0' ) as items
            FROM ".VENDOR_ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) 
                    LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) 
                    LEFT JOIN ".ORDER_TBL." OD ON (O.order_id=OD.id) 
                    LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) 
                    LEFT JOIN ".VENDOR_TBL." VE ON (O.vendor_id=VE.id) 
            WHERE 1  AND O.vendor_response='1' AND O.vendor_accept_status='0' GROUP BY vendor_order_id ORDER BY invoiveNumber DESC";
        $exe    = $this->exeQuery($query);
         if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $total_charges    = $list['vendor_payment_charge']  + $list['vendor_payment_charge_amt'] + $list['vendor_shipping_charge'] + $list['vendor_shipping_charge_amt'] + $list['vendor_commission'] + $list['vendor_commission_amt'] ; 

                    $response       = (($list['vendor_response']==0) ? "Not Seen" :  (($list['vendor_accept_status']==0) ? "Rejected" :  "Accepted" ) );
                    $response_class = (($list['vendor_response']==0) ? "text-warning" :  (($list['vendor_accept_status']==0) ? "text-danger" :  "text-success" ) );

                    $check_replace_status = $this->check_query(VENDOR_ORDER_ITEM_TBL,"*"," vendor_order_id='".$list['vendor_order_id']."' AND replace_order_id!='0' AND replace_vendor_order_id!='0' ");

                    $check_cancel_status = $this->check_query(VENDOR_ORDER_ITEM_TBL,"*"," vendor_order_id='".$list['vendor_order_id']."' AND cancel_status='1' ");

                    $replace_status = (($check_replace_status)? "<span class='tb-status text-success'>Order Replaced</span>" : "<span class='tb-status text-warning'>Pending</span>" );

                    $replace_status = (($check_cancel_status)? "<span class='tb-status text-danger'>Cancelled</span>" :  $replace_status );

                    $layout .="
                                <tr class='nk-tb-item open_rejected_order_details'  data-option='".$list['vendor_order_id']."'>
                                        <td class='nk-tb-col '>
                                        ".$i."
                                        </td>
                                        <td class='nk-tb-col'>
                                         <span class='tb-lead'><a href='".COREPATH."orders/vendororderdetails/".$list['vendor_order_id']."'>".$list['invoiveNumber']."</a></span>
                                         ".$this->getRejectedInvoiceNumbers($list['vendor_order_id'])."
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>".date('d/m/Y',strtotime($list['order_date']))."</span>
                                        </td>
                                        <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['c_name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['c_email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['c_mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['company']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['v_email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['v_mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>".$list['items']." <span class='currency'>".(($list['items'] > 1 )? 'Items' : 'Item')."</span></span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($total_charges)." </span>
                                        </td>
                                         <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($list['sub_total'] + $list['igst_amt'] - $list['couponValue'] )." </span>
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                          <span class='tb-status text-danger'>Rejected</span>  
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                        ".$replace_status."
                                        </td>
                                    </tr>

                              ";
                $i++;
                }
        }

        return $layout;
    }

    function getRejectedInvoiceNumbers($vendor_order_id) 
    {   
        $layout = "";
        $query  = "SELECT id,replace_vendor_order_item_id FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id='".$vendor_order_id."' AND replace_vendor_order_id!='0' GROUP BY replace_vendor_order_id  ";
        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $layout .= "<span class='display_flex'>Replace Invoice </span>";
            while ($list = mysqli_fetch_assoc($exe)) {
                $get_info = $this->getDetails(VENDOR_ORDER_ITEM_TBL,"id,vendor_order_id,vendor_invoice_number","id='".$list['replace_vendor_order_item_id']."'");
                $layout .="<span class='display_flex tb-lead'><a href='".COREPATH."orders/notificationdetail/".$get_info['vendor_order_id']."'>".$get_info['vendor_invoice_number']."</a></span>";
            }
        }
        return $layout;
    }

    function getVendorRejectedItems($vendor_order_id,$vendor_id)
    {
        $layout     ="";
        $query      ="SELECT O.id,O.user_id,O.variant_id,O.vendor_invoice_number,O.vendor_id,O.order_status,O.cart_id,O.product_id,O.coupon_value,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.qty,O.sub_total,O.igst_amt,O.vendor_commission_tax_amt,O.vendor_payment_tax_amt,O.vendor_shipping_tax_amt,O.vendor_commission_tax,O.vendor_payment_tax,O.vendor_shipping_tax,O.total_tax,O.sgst,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.vendor_payment_charge,O.vendor_payment_charge_amt,O.vendor_shipping_charge,O.vendor_shipping_charge_amt,O.total_amount,O.vendor_commission,O.vendor_commission_amt,O.shipping_remarks,O.delivery_date,O.delivery_remarks,O.cancel_comment,P.product_uid,P.product_name,P.page_url,P.category_type,P.main_category_id,P.sub_category_id,V.variant_name,OD.order_uid,OD.created_at,C.name,C.mobile,C.email,O.vendor_commission_amt as total_commission,O.vendor_payment_charge_amt as total_payment_charge_amt,O.vendor_shipping_charge_amt as total_shipping_charge_amt,O.total_amount as vendor_total_amt,
                (SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
                (SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image
            FROM ".VENDOR_ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) LEFT JOIN ".ORDER_TBL." OD ON (O.order_id=OD.id) LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) WHERE O.vendor_order_id=$vendor_order_id  AND O.vendor_response='1' AND O.vendor_accept_status='0' ORDER BY O.price*O.qty  DESC";
        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list    = $this->editPagePublish($rows);
                    $name    = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

                    if($list['category_type']=="main")
                    {
                        $cat        = $this->getDetails(MAIN_CATEGORY_TBL,"id,category"," id='".$list['main_category_id']."' ");
                        $category   = $cat['category'];
                    } else {
                        $cat        = $this->getDetails(SUB_CATEGORY_TBL,"id,subcategory"," id='".$list['sub_category_id']."' ");
                        $category   = $cat['subcategory'];
                    }

                    $layout .="
                            <div class='form-group col-md-6'>
                                <label class='form-label'> Product 
                                </label>
                                <span name='product_name'  id='product_name' class='form-control'  >".$name."</span>
                            </div>
                            <div class='form-group col-md-2'>
                                <label class='form-label'> Selling Price 
                                </label>
                                <span name='category' id='category' class='form-control' >".$list['price']."</span>
                            </div>
                            <div class='form-group col-md-2'>
                                <label class='form-label'> Qty 
                                </label>
                                <span name='qty' id='qty' class='form-control'>".$list['qty']."</span>
                            </div>
                            <div class='form-group col-md-2'>
                                <label class='form-label'> Price 
                                </label>
                                <span name='price' id='price' class='form-control'  >".$list['final_price']."</span>
                            </div>";
                $i++;
                }
        }
        return $layout;
    }

    /*--------------------------------------------
          Manage Order Notification List
    --------------------------------------------*/
    function ordersNotificationList($from="",$to="")
    {
        $result = array();
        $result['new_orders']    = "";
        $result['return_orders'] = "";

        if($from!="" && $from!=0 ) 
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to' ";
        } else {
            $date_filter = "";
        }

        $query  ="SELECT VO.id,VO.vendor_id,VO.user_id,VO.order_id,VO.order_uid,VO.shipping_cost,VO.sub_total,VO.total_tax,VO.sgst_amt,VO.cgst_amt,VO.igst_amt,VO.vendor_payment_total,VO.vendor_commission_total,VO.vendor_shipping_total,VO.total_amount,VO.total_payment,VO.order_date,VO.order_status,VO.status,VO.vendor_response,VO.vendor_accept_status,VO.response_notes,VO.return_status,VO.return_reason,VO.return_comment,VE.company,VE.mobile as v_mobile,VE.email as v_email,C.name as c_name,C.mobile as c_mobile, C.email as c_email,
            (SELECT vendor_invoice_number FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id LIMIT 1) as invoiveNumber,
            (SELECT SUM(coupon_value) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id LIMIT 1) as couponValue,
            (SELECT COUNT(id) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id ) as items
          FROM ".VENDOR_ORDER_TBL." VO  LEFT JOIN ".VENDOR_TBL." VE ON (VO.vendor_id=VE.id) 
                                        LEFT JOIN ".CUSTOMER_TBL." C ON (C.Id=VO.user_id) 
                                        LEFT JOIN ".RETURN_REASON_TBL." RR ON (RR.id=VO.return_reason) 
          WHERE 1 AND (VO.vendor_response='0' OR  VO.return_status='1') ".$date_filter." ORDER BY VO.id  DESC";
        $exe    = $this->exeQuery($query);

        if(mysqli_num_rows($exe) > 0) {
            $i=1;
            $j=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list             = $this->editPagePublish($rows);
                    $total_charges    = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'] ; 
                    $response         = (($list['vendor_response']==0) ? "Not Seen" :  (($list['vendor_accept_status']==0) ? "Rejected" :  "Accepted" ) );
                    $response_class   = (($list['vendor_response']==0) ? "text-warning" :  (($list['vendor_accept_status']==0) ? "text-danger" :  "text-success" ) );
                    $layout           = (($list['return_status']==1)? "return_orders" : "new_orders" );
                    $tr_link          = (($list['return_status']==1)? "return_order_detail" : "new_order_detail" );

                    if($list['return_status']==1) {
                        $status_td    = "<span class='tb-status text-danger'>Returned</span>";
                        $s_no         =  $j++;
                    } else {
                        $status_td = "<span class='tb-status $response_class'>$response</span>";                        
                        $s_no         =  $i++;
                    }

                    $result[$layout] .="
                                <tr class='nk-tb-item $tr_link' data-option='".$list['id']."'>
                                        <td class='nk-tb-col '>
                                        ".$s_no."
                                        </td>
                                        <td class='nk-tb-col'>
                                         <span class='tb-lead'><a href='".COREPATH."orders/vendororderdetails/".$list['id']."'>".$list['invoiveNumber']."</a></span>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>".date('d/m/Y',strtotime($list['order_date']))."</span>
                                        </td>
                                        <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['c_name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['c_email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['c_mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['company']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['v_email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['v_mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>".$list['items']." <span class='currency'>".(($list['items'] > 1 )? 'Items' : 'Item')."</span></span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($total_charges)." </span>
                                           
                                        </td>
                                         <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($list['sub_total'] + $list['igst_amt'] - $list['couponValue'] )." </span>
                                           
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                          ".$status_td."  
                                        </td>
                                    </tr>

                              ";
                $i++;
                }
        }
        return $result;
    }

    // Vendor response changes

    function orderNotificationResponse($data)
    {   
        
        $check_response            = $this->check_query(VENDOR_ORDER_TBL,"vendor_response","vendor_response='0' AND id='".$data['vendor_order_id']."' ");
        if($check_response) {
            $response_status       = ($data['order_response_status_id']=='')? " response_status = NULL, " :  " response_status = '".$data['order_response_status_id']."', " ;
            $curr                  = date("Y-m-d H:i:s");
            $order_details         = $this->getDetails(VENDOR_ORDER_TBL,"*","id='".$data['vendor_order_id']."'  "); 
            
            $query                 = "SELECT * FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id = '".$order_details['id']."' AND order_id='".$order_details['order_id']."'  ";
            $exe                   = $this->exeQuery($query);

            $vendor_order_item_ids = array();

            if(mysqli_num_rows($exe) > 0) {
                while ($list = mysqli_fetch_assoc($exe)) {
                    $vendor_order_item_ids[] = $list['order_item_id'];
                }
            }

            foreach ($vendor_order_item_ids as $key => $value) {

                $query = "UPDATE ".VENDOR_ORDER_ITEM_TBL." SET 
                            vendor_response      = '1',
                            vendor_accept_status = '".$data['item_response_'.$value]."',
                            $response_status
                            updated_at           = '".$curr ."'
                          WHERE vendor_order_id  ='".$data['vendor_order_id']."' AND order_item_id='".$value."' ";
                $exe  = $this->exeQuery($query);
                
            }

            $check_inprocess_item = $this->check_query(VENDOR_ORDER_ITEM_TBL,"vendor_response,vendor_accept_status","vendor_order_id='".$order_details['id']."' AND vendor_response='1' AND vendor_accept_status='1' ");

            $query = "UPDATE ".VENDOR_ORDER_TBL." SET 
                        vendor_response      = '1',
                        vendor_accept_status = '".$check_inprocess_item."'
                      WHERE id='".$data['vendor_order_id']."' ";
            $exe  = $this->exeQuery($query);

            $rejected_items = array();

            foreach ($vendor_order_item_ids as $key => $value) {

                $rejected_items[] = $data['item_response_'.$value];

                $query = "UPDATE ".ORDER_ITEM_TBL." SET 
                        vendor_response      = '1',
                        vendor_accept_status = '".$data['item_response_'.$value]."',
                        $response_status
                        updated_at           = '".$curr ."'
                        WHERE id ='".$value."' ";
                $exe  = $this->exeQuery($query);
            }

            if($exe) {

                $check_rejected_items = in_array(0, $rejected_items);

                if($check_rejected_items) {
                    return 1;
                } else {
                    return 2;
                }

            } else {
                return "Sorry!! Unexpected Error Occurred. Please try again.";
            }

        } else {
            return "Sorry!! order response status already changed.";
        }
    }

    // Get product request status master dropdown

    function getOrderResponseDropDown($current="")
    {
        $layout = "";
        $q = "SELECT id,response_status FROM ".ORDER_RESPONSE_STATUS_TBL." WHERE status='1' AND delete_status='0' " ;
        $query = $this->exeQuery($q);
        if(@mysqli_num_rows($query)>0){
            $i=0;
            while($list = mysqli_fetch_array($query)){
                $selected = (($list['id']==$current) ? 'selected' : '');
                $layout.= "<option value='".$list['id']."' $selected>".$list['response_status']."</option>";
                $i++;
            }
        }
        return $layout;
    }

    function getOrderResponseItems($order_id,$vendor_id)
    {
        $layout     ="";
        $query      ="SELECT O.id,O.user_id,O.variant_id,O.vendor_invoice_number,O.vendor_id,O.order_status,O.cart_id,O.product_id,O.coupon_value,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.qty,O.sub_total,O.igst_amt,O.vendor_commission_tax_amt,O.vendor_payment_tax_amt,O.vendor_shipping_tax_amt,O.vendor_commission_tax,O.vendor_payment_tax,O.vendor_shipping_tax,O.total_tax,O.sgst,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.vendor_payment_charge,O.vendor_payment_charge_amt,O.vendor_shipping_charge,O.vendor_shipping_charge_amt,O.total_amount,O.vendor_commission,O.vendor_commission_amt,O.shipping_remarks,O.delivery_date,O.delivery_remarks,O.cancel_comment,P.product_uid,P.product_name,P.page_url,P.category_type,P.main_category_id,P.sub_category_id,V.variant_name,OD.order_uid,OD.created_at,C.name,C.mobile,C.email,O.vendor_commission_amt as total_commission,O.vendor_payment_charge_amt as total_payment_charge_amt,O.vendor_shipping_charge_amt as total_shipping_charge_amt,O.total_amount as vendor_total_amt,
                (SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
                (SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image
            FROM ".ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) LEFT JOIN ".ORDER_TBL." OD ON (O.order_id=OD.id) LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) WHERE O.order_id=$order_id AND O.vendor_id='".$vendor_id."'  ORDER BY O.price*O.qty  DESC";
        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $name             = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

                    if($list['category_type']=="main")
                    {
                        $cat        = $this->getDetails(MAIN_CATEGORY_TBL,"id,category"," id='".$list['main_category_id']."' ");
                        $category   = $cat['category'];
                    } else {
                        $cat        = $this->getDetails(SUB_CATEGORY_TBL,"id,subcategory"," id='".$list['sub_category_id']."' ");
                        $category   = $cat['subcategory'];
                    }

                    $layout .="<div class='profile-ud-list'>
                                <div class='profile-ud-item'>
                                    <div class='profile-ud wider enq_name_field'>
                                        <span class='profile-ud-label'>".$name."</span>
                                    </div>
                                </div>
                                <div class='profile-ud-item'>
                                    <div class='profile-ud wider enq_name_field'>
                                        <div class='custom-control custom-switch'>
                                            <input type='hidden' id='item_response_".$list['id']."' name='item_response_".$list['id']."' value='1' >
                                            <input type='checkbox' class='custom-control-input custom-control-sm order_response_status'  id='order_response_status_".$list['id']."' data-item_id='".$list['id']."' checked='' >
                                            <label class='custom-control-label response_title_".$list['id']."' for='order_response_status_".$list['id']."'>Accept</label>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                $i++;
                }
        }
        return $layout;
    }

    // Replace rejected Order

	function replaceOrder($data) 
	{     

		$validate_csrf = $this->validateCSRF($data);
		if($validate_csrf=='success') {
            $same_vendor_or_not        = $data['same_vendor_or_not'];
			$current_order_id   	   = $data['current_order_id'];
			$current_order_uid  	   = $data['current_order_uid'];
			$current_vendor_order_id   = $data['current_vendor_order_id'];
			$replace_vendor_id         = $data['replace_vendor_id'];
			$vendor_order_item_ids     = $data['vendor_order_item_ids'];
            $current_order_info        = $this->getDetails(ORDER_TBL,"*","id='".$current_order_id."'");
            $current_vendor_order_info = $this->getDetails(VENDOR_ORDER_TBL,"*","id='".$current_vendor_order_id."'");

			$new_items_id_array = array();
			$today        = date("Y-m-d");
			$curr         = date("Y-m-d H:i:s");

			// Create New Order 
			
			$rejected_order_item = explode(",", $vendor_order_item_ids);

			foreach ($rejected_order_item as $key => $value) {
                
                $item_info = $this->getDetails(VENDOR_ORDER_ITEM_TBL,"*","id='".$value."'");

                if($same_vendor_or_not==0) {
                    $input_name        = "replace_vendor_id_".$item_info['product_id']."_".$item_info['variant_id'];
                    $replace_vendor_id = $data[$input_name];
                } else {
                    $replace_vendor_id = $replace_vendor_id;
                }

				$product_info   = $this->getDetails(PRODUCT_TBL,"*","id='".$item_info['product_id']."' ");
				$tax_info    	= $this->getDetails(TAX_CLASSES_TBL,"*","id='".$product_info['tax_class']."' ");
				$quantity		= $item_info['qty'];
				$price 			= $product_info['selling_price'];
				$variant_id  	= "0";
				$option_info  	=  0;
				$vendor_id      =  0;

				if($item_info['variant_id']!=0) {

					$vendor_variant = $this->getDetails(VENDOR_PRODUCTS_TBL,"*", " product_id='".$item_info['product_id']."' AND variant_id='".$item_info['variant_id']."' AND vendor_id='".$replace_vendor_id."'  ");
					if($vendor_variant) {
						$price 		 = $vendor_variant['selling_price']; 
						$variant_id  = $vendor_variant['variant_id'];
						$vendor_id   = $vendor_variant['vendor_id'];
					} 
					
				} else {

					$vendor_variant = $this->getDetails(VENDOR_PRODUCTS_TBL,"*", " product_id='".$item_info['product_id']."' AND vendor_id='".$replace_vendor_id."' ");
					if($vendor_variant) {
						$price 		    = $vendor_variant['selling_price']; 
						$variant_id     = $vendor_variant['variant_id'];
						$vendor_id      = $vendor_variant['vendor_id'];
					} 
				} 

				$vendor_variant = $this->getDetails(VENDOR_PRODUCTS_TBL,"*", " product_id='".$item_info['product_id']."' AND vendor_id='".$replace_vendor_id."'  ORDER BY selling_price ASC LIMIT 1 ");

   				if ($product_info['tax_type']=="inclusive") {
					$price_sgst_amt 			= "0";
					$price_cgst_amt 			= "0";
					$price_igst_amt 			= "0";
				} else { 
					$price_sgst_amt 			= "0";
					$price_cgst_amt 			= "0";
					$price_igst_amt 			= "0";
					$price_sgst 			= 1+($tax_info['sgst']/100);
					$price_cgst 			= 1+($tax_info['cgst']/100);
					$price_igst 			= 1+($tax_info['igst']/100);
					$price_sgst_amt   		= round(($price*$price_sgst)-$price,2);
					$price_cgst_amt   		= round(($price*$price_cgst)-$price,2);
					$price_igst_amt   		= round(($price*$price_igst)-$price,2);
				}

				$price_plus_gst = $price + $price_sgst_amt + $price_cgst_amt;
    			$sub_total      = $price * $quantity;
				$tax_total 		= $price * $quantity;


				if ($product_info['tax_type']=="inclusive") {
					$sgst 			= 1+($tax_info['sgst']/100);
					$cgst 			= 1+($tax_info['cgst']/100);
					$igst 			= 1+($tax_info['igst']/100);
					$sgst_amt   	= round(($tax_total*$sgst)-$tax_total,2);
					$cgst_amt   	= round(($tax_total*$cgst)-$tax_total,2);
					$igst_amt   	= round(($tax_total*$igst)-$tax_total,2);
				} else {
					$sgst 			= 1+($tax_info['sgst']/100);
					$cgst 			= 1+($tax_info['cgst']/100);
					$igst 			= 1+($tax_info['igst']/100);
					$sgst_amt   	= round(($tax_total*$sgst)-$tax_total,2);
					$cgst_amt   	= round(($tax_total*$cgst)-$tax_total,2);
					$igst_amt   	= round(($tax_total*$igst)-$tax_total,2);
				}

				// Category & Sub-category commission
				if($product_info['category_type']=='main') {
					$cat_info   	= $this->getDetails(MAIN_CATEGORY_TBL,"*","id='".$product_info['main_category_id']."' ");
					$cat_tax_info   = $this->getDetails(TAX_CLASSES_TBL,"*","id='".$cat_info['vendor_commission_tax']."' ");
				} else {
					$cat_info   	= $this->getDetails(SUB_CATEGORY_TBL,"*","id='".$product_info['sub_category_id']."' ");
					$cat_tax_info   = $this->getDetails(TAX_CLASSES_TBL,"*","id='".$cat_info['vendor_commission_tax']."' ");
				}

				// for Sapiens Commission

				if($product_info['tax_type']=="inclusive") {
					$vendor_cal_price = $tax_total;
				} else {
					$vendor_cal_price = $tax_total + $cgst_amt + $sgst_amt;
				}

				// Category commision charges

				$vd_cm_per 		      =  1+($cat_info['vendor_commission']/100);
				$vd_cm_amt   	      =  ($vendor_cal_price*$vd_cm_per)-$vendor_cal_price;
				$vd_cm_tax_igst       =  1+($cat_tax_info['igst']/100);
				$vd_cm_tax_amt        =  ($vd_cm_amt*$vd_cm_tax_igst)-$vd_cm_amt;
				$vendor_commission    =  ($vd_cm_amt+$vd_cm_tax_amt);


				$company_profile_info = $this->getDetails(COMPANNY_INFO,"*","1");

				// Payment Gate Way Charges

				$payment_tax_info  	    = $this->getDetails(TAX_CLASSES_TBL,"*","id='".$company_profile_info['vendor_payment_tax']."' ");

				$vd_payment_per 	    =  1+($company_profile_info['vendor_payment_charges']/100);
				$vd_payment_amt   	    =  ($vendor_cal_price*$vd_payment_per)-$vendor_cal_price;
				$vd_payment_igst        =  1+($payment_tax_info['igst']/100);
				$vd_payment_tax_amt     =  ($vd_payment_amt*$vd_payment_igst)-$vd_payment_amt;
				$vendor_payment_charges =  ($vd_payment_amt+$vd_payment_tax_amt);

				// Shipping Charges

				$shipping_tax_info       = $this->getDetails(TAX_CLASSES_TBL,"*","id='".$company_profile_info['vendor_shipping_tax']."' ");

				$vd_shipping_per 	     = 1+($company_profile_info['vendor_shipping_charges']/100);
				$vd_shipping_amt   	     = ($vendor_cal_price*$vd_shipping_per)-$vendor_cal_price;
				$vd_shipping_igst        = 1+($shipping_tax_info['igst']/100);
				$vd_shipping_tax_amt     = ($vd_shipping_amt*$vd_shipping_igst)-$vd_shipping_amt;
				$vendor_shipping_charges = ($vd_shipping_amt+$vd_shipping_tax_amt);

				$variant_check = ($variant_id!=0) ? "AND variant_id='".$variant_id."'" : "" ;

    			$igst_amt = $sgst_amt + $cgst_amt;

    			if ($product_info['tax_type']=="inclusive") {
    				$tax_amt = $tax_total - $igst_amt;
    			} else {
    				$tax_amt = $tax_total ;
    			}

    			if($item_info['coupon_id']!=0)
                {
                     $update_coupon     = " 
                        coupon_id     = '".$item_info['coupon_id']."',
                        coupon_value  = '".$item_info['coupon_value']."',
                        coupon_code   = '".$item_info['coupon_code']."',
                        coupon_type   = '".$item_info['coupon_type']."',
                        coupon_status = '".$item_info['coupon_status']."',
                    ";
                } else {
                	$update_coupon = "";
                }

				$order_item_query = "INSERT INTO ".ORDER_ITEM_TBL." SET
                    user_id 					= '".$item_info['user_id']."',	
					cart_id 					= '".$item_info["cart_id"]."',
					product_id 					= '".$item_info['product_id']."',
					category_id 				= '".$item_info['category_id']."',
					sub_category_id 			= '".$item_info['sub_category_id']."',
					tax_type 					= '".$item_info['tax_type']."',
					qty 						= '".$item_info['qty']."',
					variant_id 					= '".$item_info['variant_id']."',
					final_price     			= '".$sub_total."',
					total_amount    			= '".$tax_total."',
					sub_total					= '".$tax_amt."',
					vendor_id 					= '".$replace_vendor_id."',
					price 						= '".$price."',
					tax_amt 					= '".$tax_amt."',	
					tax_price					= '".$price_plus_gst."',
					vendor_commission 			= '".$cat_info['vendor_commission']."',
					vendor_commission_tax   	= '".$cat_tax_info['igst']."',
					vendor_payment_charge 		= '".$company_profile_info['vendor_payment_charges']."',
					vendor_payment_tax 			= '".$payment_tax_info['igst']."',
					vendor_shipping_charge 		= '".$company_profile_info['vendor_shipping_charges']."',
					vendor_shipping_tax 		= '".$shipping_tax_info['igst']."',
					vendor_commission_amt 		= '".$vendor_commission."',
					vendor_commission_tax_amt   = '".$vd_cm_tax_amt."',
					vendor_payment_charge_amt   = '".$vendor_payment_charges."',
					vendor_payment_tax_amt 		= '".$vd_payment_tax_amt."',
					vendor_shipping_charge_amt 	= '".$vendor_shipping_charges."',
					vendor_shipping_tax_amt 	= '".$vd_shipping_tax_amt."',
					".$update_coupon."
					total_tax    				= '".$igst_amt."',
					sgst_amt					= '".$sgst_amt."',
					cgst_amt 					= '".$cgst_amt."',
					igst_amt 					= '".$igst_amt."',
					sgst						= '".$tax_info['sgst']."',
					cgst 						= '".$tax_info['cgst']."',
					igst 						= '".$tax_info['igst']."',
                    replaced_order              = '1',
					status						= '1',
					created_at 					= '".$curr."',
					updated_at 					= '".$curr."' ";
				$new_items_id_array[$value] = $this->lastInserID($order_item_query);


 
				// Revoke current vendor stock reduction

				$product= $this->getDetails(VENDOR_PRODUCTS_TBL,"id,stock,product_id","product_id='".$item_info['product_id']."' AND variant_id='".$item_info['variant_id']."' AND vendor_id='".$item_info['vendor_id']."' "); 
	            $stock      = $product['stock'] + $item_info['qty'];
	            $que_1        = "UPDATE ".VENDOR_PRODUCTS_TBL." SET  stock ='".$stock."' WHERE product_id='".$item_info['product_id']."' AND vendor_id='".$item_info['vendor_id']."' ";
	            $exe_1 = $this->exeQuery($que_1);

	            // Update replace vendor stock

				$product= $this->getDetails(VENDOR_PRODUCTS_TBL,"id,stock,product_id","product_id='".$item_info['product_id']."' AND variant_id='".$item_info['variant_id']."' AND vendor_id='".$item_info['vendor_id']."' "); 
	            $stock      = $product['stock'] + $item_info['qty'];
	            $que_2        = "UPDATE ".VENDOR_PRODUCTS_TBL." SET  stock ='".$stock."' WHERE product_id='".$item_info['product_id']."' AND vendor_id='".$item_info['vendor_id']."' ";
	            $exe_2 = $this->exeQuery($que_2);
			}

			$new_items_id = implode(",", $new_items_id_array);
			$updated_cart = "SELECT COUNT(cart_id) as total_items,
									SUM(final_price) as total,
									SUM(tax_amt) as sub_total,
									SUM(sgst) as sgst,
									SUM(cgst) as cgst,
									SUM(igst) as igst,
									SUM(igst_amt) as igst_amt,
									SUM(sgst_amt) as sgst_amt,
									SUM(cgst_amt) as cgst_amt,
									SUM(vendor_commission) as vendor_commission,
									SUM(vendor_commission_tax) as vendor_commission_tax,
									SUM(vendor_payment_charge) as vendor_payment_charge,
									SUM(vendor_payment_tax) as vendor_payment_tax,
									SUM(vendor_shipping_charge) as vendor_shipping_charge,
									SUM(vendor_shipping_tax) as vendor_shipping_tax,
									SUM(vendor_commission_amt) as vendor_commission_amt,
									SUM(vendor_commission_tax_amt) as vendor_commission_tax_amt,
									SUM(vendor_payment_charge_amt) as vendor_payment_charge_amt,
									SUM(vendor_payment_tax_amt) as vendor_payment_tax_amt,
									SUM(vendor_shipping_charge_amt) as vendor_shipping_charge_amt,
									SUM(vendor_shipping_tax_amt) as vendor_shipping_tax_amt,
									SUM(coupon_value) as coupon_value
							FROM ".ORDER_ITEM_TBL." WHERE id IN (".$new_items_id.") ";


			$updated_cart_exe = $this->exeQuery($updated_cart);
			$list 		      = mysqli_fetch_assoc($updated_cart_exe);

			if($current_order_info['coupon_id']!=0)
            {
                 $update_cart_coupon     = " 
                    coupon_id     = '".$current_order_info['coupon_id']."',
                    coupon_code   = '".$current_order_info['coupon_code']."',
                    coupon_type   = '".$current_order_info['coupon_type']."',
                    coupon_status = '".$current_order_info['coupon_status']."',
                ";
            } else {
            	$update_cart_coupon = "";
            }

			$order_tbl_query = "INSERT INTO ".ORDER_TBL." SET 
                user_id                     = '".$current_order_info['user_id']."',
                shipping_type               = '".$current_order_info['shipping_type']."', 
                shipping_cost               = '".$current_order_info['shipping_cost']."',
				sub_total 					= '".$list['sub_total']."',
				total_tax					= '".$list['igst_amt']."',
				total_amount    			= '".$list['total']."',
                order_address               = '".$current_order_info['order_address']."',
				shipping_status 			= '1',
				sgst						= '".$list['sgst']."',
				cgst 						= '".$list['cgst']."',
				igst 						= '".$list['igst']."',
				igst_amt 					= '".$list['igst_amt']."',
				sgst_amt					= '".$list['sgst_amt']."',
				cgst_amt 					= '".$list['cgst_amt']."',
				vendor_commission 			= '".$list['vendor_commission']."',
				vendor_commission_tax   	= '".$list['vendor_commission_tax']."',
				vendor_payment_charge 		= '".$list['vendor_payment_charge']."',
				vendor_payment_tax 			= '".$list['vendor_payment_tax']."',
				vendor_shipping_charge 		= '".$list['vendor_shipping_charge']."',
				vendor_shipping_tax 		= '".$list['vendor_shipping_tax']."',
				vendor_commission_amt 		= '".$list['vendor_commission_amt']."',
				vendor_commission_tax_amt   = '".$list['vendor_commission_tax_amt']."',
				vendor_payment_charge_amt   = '".$list['vendor_payment_charge_amt']."',
				vendor_payment_tax_amt 		= '".$list['vendor_payment_tax_amt']."',
				vendor_shipping_charge_amt 	= '".$list['vendor_shipping_charge_amt']."',
				vendor_shipping_tax_amt 	= '".$list['vendor_shipping_tax_amt']."',
				".$update_cart_coupon."
				coupon_value				= '".$list['coupon_value']."',
                payment_type                = '".$current_order_info['payment_type']."',  
                order_date                  = '".$today."',
                order_status                = '0',     
                replaced_order              = '1',
                status                      = '1',
                created_at                  = '".$curr."',
				updated_at 					= '".$curr."' ";
			$new_order_id 	= $this->lastInserID($order_tbl_query);

			// Update order id in order_item_tbl

			$update_order_id_query = "UPDATE ".ORDER_ITEM_TBL." SET
				  Order_id = ".$new_order_id."
				  WHERE id IN (".$new_items_id.") 
				 ";
            $update_order_id_query = $this->exeQuery($update_order_id_query);

            // Update replace data in order item tbl

            foreach ($rejected_order_item as $key => $value) {
                $current_order_item_detail = $this->getDetails(VENDOR_ORDER_ITEM_TBL,"*","id='".$value."'");

                $update_order_item ="UPDATE ".ORDER_ITEM_TBL." SET 
                    replace_order_id      = '".$new_order_id."',
                    replace_order_item_id = '".$new_items_id_array[$value]."'
                    WHERE id='".$current_order_item_detail['order_item_id']."'
                    ";
                $update_order_item_exe = $this->exeQuery($update_order_item);
            }

			$update_order_status   = $this->updateOrderStatus($new_order_id,$current_order_info,$current_vendor_order_info);

		} else {
			return $validate_csrf;
		}
        return 1;
	}

	function updateOrderStatus($order_id,$current_order_info,$current_vendor_order_info)
    {
        $info           = $this -> getDetails(ORDER_TBL, "id,order_no"," 1 ORDER BY order_no DESC LIMIT 1");
        $order_no       = $info['order_no']+1;
        $order_uid      = 'Z'.str_pad($order_no, 5,0,STR_PAD_LEFT);
        $q = "UPDATE ".ORDER_TBL." SET  payment_status ='1',order_no='".$order_no."',order_uid='".$order_uid."'  WHERE id='".$order_id."' ";
        $exe = $this->exeQuery($q);

        // Update replace data in order tbl
        $new_order_info = $this->getDetails(ORDER_TBL,"*","id='".$order_id."'");

        $update_order_q = "UPDATE ".ORDER_TBL." SET 
            replace_order_id      = '".$order_id."',
            replace_order_uid     = '".$new_order_info['order_uid']."'
            WHERE id='".$current_order_info['id']."'
            ";

        $update_order_exe = $this->exeQuery($update_order_q);

        if ($exe) {
            $email_info     = $this->getDetails(CUSTOMER_TBL,'id,name,email,token', " id ='".$current_order_info['user_id']."' ");
            $sender         = COMPANY_NAME;
            $sender_mail    = NO_REPLY;
            $subject        = "Admin has replace a new order for your rejected order  - Rejected Order Id ".$current_order_info['order_uid'];
            $receiver       = $this->cleanString($email_info['email']);
            $message        = $this->replacedOrderInvoice($order_id);
            $send_mail      = $this->send_mail($sender_mail,$receiver,$subject,$message);
            $vendor_Orders  = $this->groupVendorOrders($order_id,$current_order_info,$current_vendor_order_info);
            return 1;
        }
    }

    // Grouping of vendors for the order

    function groupVendorOrders($order_id,$current_order_info,$current_vendor_order_info){
        $vendor_grouping= array();
        $query="SELECT vendor_id FROM ".ORDER_ITEM_TBL." WHERE order_id='".$order_id."' GROUP BY vendor_id ";
        $exe = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0){
            while($list = mysqli_fetch_array($exe)){
               $create_order = $this->createVendorOrder($order_id,$list['vendor_id'],$current_order_info,$current_vendor_order_info);
            }
        }

    }

    // Create Vendor Orders

    function createVendorOrder($order_id,$vendor_id,$current_order_info,$current_vendor_order_info){
        $curr           = date("Y-m-d H:i:s");  
        $today          = date("Y-m-d");
        $info           = $this -> getDetails(ORDER_TBL, "*"," id='".$order_id."'");
        $user_id        = $info['user_id'];
        $q= "INSERT INTO ".VENDOR_ORDER_TBL." SET 
            vendor_id               = '".$vendor_id."',
            order_id                = '".$order_id."',
            order_address           = '".$info['order_address']."',
            order_uid               = '".$info['order_uid']."',
            user_id                 = '".$user_id."',
            order_date              = '".$today."',
            notes                   = '".$info['notes']."',
            order_status            = '0',  
            status                  = '1',
            order_device            = 'website',
            created_at              = '".$curr."',
            updated_at              = '".$curr."' ";
        $vendor_order_id = $this->lastInserID($q);

        // Update Rplace data in vendor order tbl 

        $update_vendor_order_q= "UPDATE ".VENDOR_ORDER_TBL." SET 
            replace_order_id        = '".$info['id']."',
            replace_order_uid       = '".$info['order_uid']."'
            WHERE id = '".$current_vendor_order_info['id']."'
            ";
        $exe = $this->exeQuery($update_vendor_order_q);
        
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

        $last_item_id = array();
    
        if(mysqli_num_rows($oq_exe) > 0){
            while($list = mysqli_fetch_array($oq_exe))
            {      

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

                // Update replace invoice number in order_item_tbl

                $update_invoice_q = " UPDATE ".ORDER_ITEM_TBL." SET
                                        replace_vendor_invoice_number = '".$invoice_uid."'
                                      WHERE order_id='".$current_order_info['id']."' 
                                         AND product_id='".$list['product_id']."' 
                                         AND variant_id='".$list['variant_id']."'
                                   ";
                $update_invoice_exe = $this->exeQuery($update_invoice_q);

                $iq = "INSERT INTO ".VENDOR_ORDER_ITEM_TBL." SET
                        user_id                       = '".$user_id."', 
                        vendor_order_id               = '".$vendor_order_id."',
                        product_id                    = '".$list['product_id']."',
                        variant_id                    = '".$list['variant_id']."',
                        category_id                   = '".$list['category_id']."',
                        sub_category_id               = '".$list['sub_category_id']."',
                        vendor_id                     = '".$list['vendor_id']."',
                        order_id                      = '".$order_id."',
                        cart_id                       = '".$list['cart_id']."',
                        order_item_id                 = '".$list['id']."',
                        coupon_id                     = '".$list['coupon_id']."',
                        coupon_value                  = '".$list['coupon_value']."',  
                        coupon_status                 = '".$list['coupon_status']."', 
                        total_amount                  = '".$list['total_amount']."',
                        vendor_commission             = '".$list['vendor_commission']."',
                        vendor_commission_tax         = '".$list['vendor_commission_tax']."',    
                        vendor_payment_charge         = '".$list['vendor_payment_charge']."',    
                        vendor_payment_tax            = '".$list['vendor_payment_tax']."',
                        vendor_shipping_charge        = '".$list['vendor_shipping_charge']."',    
                        vendor_shipping_tax           = '".$list['vendor_shipping_tax']."',
                        vendor_commission_amt         = '".$list['vendor_commission_amt']."',    
                        vendor_commission_tax_amt     = '".$list['vendor_commission_tax_amt']."',        
                        vendor_payment_charge_amt     = '".$list['vendor_payment_charge_amt']."',        
                        vendor_payment_tax_amt        = '".$list['vendor_payment_tax_amt']."',    
                        vendor_shipping_charge_amt    = '".$list['vendor_shipping_charge_amt']."',        
                        vendor_shipping_tax_amt       = '".$list['vendor_shipping_tax_amt']."',      
                        vendor_invoice_number         = '".$invoice_uid."',
                        price                         = '".$list['price']."',
                        tax_price                     = '".$list['tax_price']."',
                        tax_amt                       = '".$list['tax_amt']."',
                        tax_type                      = '".$list['tax_type']."',
                        final_price                   = '".$list['final_price']."',   
                        qty                           = '".$list['qty']."',        
                        size                          = '".$list['size']."',
                        sub_total                     = '".$list['sub_total']."',
                        total_tax                     = '".$list['total_tax']."',
                        sgst                          = '".$list['sgst']."',
                        cgst                          = '".$list['cgst']."',
                        igst                          = '".$list['igst']."',
                        sgst_amt                      = '".$list['sgst_amt']."',
                        cgst_amt                      = '".$list['cgst_amt']."',
                        igst_amt                      = '".$list['igst_amt']."',
                        status                        = '1',
                        created_at                    = '".$curr."',
                        updated_at                    = '".$curr."' ";
                $last_item_id = $this->lastInserID($iq); 

                $current_item_info = $this->getDetails(VENDOR_ORDER_ITEM_TBL,"*","vendor_order_id='".$current_vendor_order_info['id']."' AND product_id='".$list['product_id']."' AND variant_id='".$list['variant_id']."' ");

                $new_order_item_info = $this->getDetails(VENDOR_ORDER_ITEM_TBL,"*","id='".$last_item_id."'");
                $update_ord_item_q = " UPDATE ".VENDOR_ORDER_ITEM_TBL." SET
                                        replace_order_id = '".$new_order_item_info['order_id']."',
                                        replace_vendor_order_id = '".$new_order_item_info['vendor_order_id']."',
                                        replace_vendor_order_item_id = '".$new_order_item_info['id']."',
                                        replace_vendor_invoice_number = '".$new_order_item_info['vendor_invoice_number']."'
                                      WHERE id='".$current_item_info['id']."' 
                                   ";
                $update_ord_item_exe = $this->exeQuery($update_ord_item_q);
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

        return $last_item_id;
    }



    function getOrderstsPopUpItems($order_id)
	{
		$layout 	="";
		$query  	="SELECT O.id,O.user_id,O.variant_id,O.order_status,O.cart_id,O.product_id,O.coupon_value,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.qty,O.sub_total,O.igst_amt,O.total_tax,O.sgst,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.total_amount,O.shipping_remarks,O.delivery_date,O.delivery_remarks,O.cancel_comment,O.return_status,P.product_uid,P.product_name,P.page_url,P.category_type,P.main_category_id,P.sub_category_id,V.variant_name,OD.order_uid,OD.created_at,C.name,C.mobile,C.email,O.total_amount as vendor_total_amt,PU.product_unit,RR.return_reason as return_reason_msg,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image
			FROM ".ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) 
									  LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) 
									  LEFT JOIN ".ORDER_TBL." OD ON (O.order_id=OD.id) 
									  LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) 
                                      LEFT JOIN ".RETURN_REASON_TBL." RR ON (RR.id=O.return_reason) 
									  LEFT JOIN ".PRODUCT_UNIT_TBL." PU ON (PU.id=P.product_unit)  
			WHERE O.order_id=$order_id ORDER BY O.price*O.qty  DESC";
		$exe 	= $this->exeQuery($query);
		if(mysqli_num_rows($exe) > 0) {
			$i=1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list 	= $this->editPagePublish($rows);

					$data_status       = (($list['order_status']==0) ? 1 :  (($list['order_status']==1) ? 2 :  3 ) );
					$status_class 	  = (($list['order_status']==2) ? "text-success" :  (($list['order_status']==3) ? "text-danger" :  "text-warning" ) ); 
					$status 		  = (($list['order_status']==0) ? "Inprocess" : (($list['order_status']==1) ? "Shipped" : (($list['order_status']==2) ? "Delivered" :   (($list['return_status']==1) ? "Returned" :  "Cancelled" ))) ); 
					$status_btn_title = (($list['order_status']==0) ? "Inprocess" :  (($list['order_status']==1) ? "Shipped" :  (($list['order_status']==2) ? "Delivered" :  "Cancelled") ) );
					$status_btn 	  = (($list['order_status']==0) ? "<em class='icon ni ni-truck'></em>" :  (($list['order_status']==1) ? "<em class='icon ni 					ni-money'></em>" : "<em class='icon ni ni-trash'></em>") ); 
					$total_charges    = (int) $list['total_commission'] + (int) $list['total_payment_charge_amt'] + (int) $list['total_shipping_charge_amt'] ; 
					$name             = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;


					$remarks_head     = (($list['order_status']==0) ? "Shipping Remarks" : (($list['order_status']==1) ? "Delivery Remarks" : (($list['order_status']==2) ? "Cancel Remarks" : "")) ); 


					if ($list['default_product_image']!="") {
						$product_image = $list['default_product_image']!='' ? ADMIN_UPLOADS.$list['default_product_image'] : ASSETS_PATH."no_img.jpg" ;# code...
					}else{
						$product_image = $list['product_image']!='' ? ADMIN_UPLOADS.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
					}

					 if($list['category_type']=="main")
		        	{
		        		$cat  		= $this->getDetails(MAIN_CATEGORY_TBL,"id,category"," id='".$list['main_category_id']."' ");
		        		$category 	= $cat['category'];
		        	} else {
		        		$cat  		= $this->getDetails(SUB_CATEGORY_TBL,"id,subcategory"," id='".$list['sub_category_id']."' ");
		        		$category 	= $cat['subcategory'];
		        	}

		        	$update_info    = "";

		        	if($list['order_status']==1) {
		        		$update_info = $list['shipping_remarks'];
		        	} elseif ($list['order_status']==2) {
		        		$update_info = $list['delivery_remarks'];
		        	} elseif ($list['order_status']==3) {
		        		$update_info = $list['cancel_comment'];
		        	} 
		        	if ($list['return_status']==1) {
		        		$update_info = $list["return_reason_msg"];
		        	}

		        	$status_dropdown  = "";
                    $arrow 			  = "<em class='icon ni ni-chevron-down'></em>";
                    $td_cen           = "";
                    $drop_btn         = "btn-primary btn-sm";


		        	if($list['order_status']==0) {
                        $status_dropdown  .= "
                                            <li><a  class='status_action' data-status='".$i."' data-change='1' data-action='Mark as Shipped'>Mark as Shipped</a></li>
                                            ";               
                    }

                    if($list['order_status']==1) {
                    
                        $status_dropdown  .= "
                                            <li><a  class='status_action' data-status='".$i."' data-change='2' data-action='Mark as Delivered'>Mark as Delivered</a></li>
                                            
                                             ";
                    }
                    if($list['order_status']==false) {
                    	$status_dropdown .= "";
                    }

                    // <li><a  class='status_action' data-status='".$i."' data-change='3' data-action='Cancel Order'>Cancel Order</a></li><li><a  class='status_action' data-status='".$i."' data-change='3' data-action='Cancel Order'>Cancel Order</a></li><li><a  class='status_action' data-status='".$i."' data-change='3' data-action='Cancel Order'>Cancel Order</a></li>

                    // for hide cancel order

                    if($list['order_status']==2) {
                    	$status_dropdown .= "";
                    	$arrow   		  ="";
                    	$td_cen			  = "td_cen";
                    	$drop_btn         = "btn-success btn-sm";
                    }

                    if($list['order_status']==3) {
                    	$status_dropdown .= "";
                    	$arrow   		  ="";
                    	$td_cen			  = "td_cen";
                    	$drop_btn         = "btn-danger btn-sm";
                    }

                    $remarks_td = "";
                    $up_info    = "display_block";

                    if($list['order_status']!=0) {
                    	$remarks_td .= " <div class='last_update remarks_info_".$i."'>
                                   <span class='info_hd'>$status <span class='lt_up'> (Last Update)</span></span>
                                   <span class='up_info'>$update_info<span>
                                </div>";
                        $up_info    = "display_none";
                    }

					$order_item_tot = $this->check_query(ORDER_ITEM_TBL,"id"," order_id='".$list['order_id']."' ");

					$product_unit = (($list['product_unit']!="")? "( ".$list['product_unit']." )" : "" );


					$layout .=" <input type='hidden' id='orderId' value='".$list['order_id']."'>
                        		<input type='hidden' id='totalItems' name='total_item' value='".$order_item_tot."'>
								<tr class='nk-tb-item '>
                                <td>
                                    ".$i."
                        			<input type='hidden' name='item_id_".$i."' value='".$this->encryptData($list['id'])."'>
                                </td>
                                <td>
                                    <img src='".$product_image."' width=50 />
                                </td>
                                <td >
                                    <span class='text-primary'>".$name."</span><br>
                                    <span class='info_hd'>Category : ".$category." QTY : ".$list['qty']." ".$product_unit."</span>
                                   
                                </td>
                                <td >
                                    <span class='info_hd'>Order Date : ".date("d-m-Y",strtotime($list['created_at'])) ." </span>
                                  
                                </td>
                                <td >
                                  <span class='tb-status $status_class'>$status</span>
                                </td>
                                <td class='remark_td' >
                                $remarks_td
                                <div class='text_remarks_".$i." $up_info'>
                                  <textarea class='form-control' name='remarks_".$i."'></textarea>
                                </div>
                                </td>
                                <td class='status_btn_col td_cen' >
                                   
                                    <div class='dropdown'>
                                        <a href='#' class='btn $drop_btn' data-toggle='dropdown'><span class='status_btn_ne_".$i." sts_btn' >$status_btn_title</span>$arrow</a>
                                        <input type='hidden' name='order_status_".$i."' id='order_status_".$i."' value='".$list['order_status']."'>
                                        <input type='hidden' name='delivery_date_".$i."' id='delivery_date_".$i."' value='".$list['delivery_date']."'>
                                        <div class='dropdown-menu dropdown-menu-right dropdown-menu-auto mt-1'>
                                            <ul class='link-list-plain'>
                                            	$status_dropdown
                                                
                                            </ul>
                                        </div>
                                    </div>
                                                   
                                </td>
                                
                            </tr>
							  	";
				$i++;
				}
		}
		return $layout;
	}


    function manageInvoiceItems($order_id="")
    {    
        $layout = "";
        $query = "SELECT I.id,I.qty,I.product_id,I.order_id,I.variant_id,I.price as prize,I.total_tax,I.shipping_cost,I.sub_total,I.final_price,I.qty,I.size,I.total_amount,I.sgst,I.cgst,I.igst,I.sgst_amt,I.cgst_amt,O.coupon_code,O.order_address,O.coupon_id,I.coupon_value,O.order_uid,P.product_name,V.variant_name,I.vendor_commission,I.vendor_commission_tax,I.vendor_payment_charge,I.vendor_payment_tax,I.vendor_shipping_charge,I.vendor_shipping_tax,SUM(I.vendor_commission_amt) as totalCommission,SUM(I.vendor_payment_charge_amt) as totalPayment,SUM(I.vendor_shipping_charge_amt) as totalShipping,SUM(I.vendor_commission_tax_amt) as vendor_commission_tax_amt,SUM(I.vendor_payment_tax_amt) as vendor_payment_tax_amt,SUM(I.vendor_shipping_tax_amt) as vendor_shipping_tax_amt,SUM(I.igst_amt) as totalTax,SUM(I.tax_amt) as subTotal,SUM(I.coupon_value) as couponTotal,SUM(I.total_amount) as totalAmount
        		FROM  ".ORDER_ITEM_TBL." I LEFT JOIN ".PRODUCT_TBL." P ON(P.id=I.product_id) 
        								   LEFT JOIN ".ORDER_TBL." O ON (O.id=I.order_id) 
        								   LEFT JOIN ".PRODUCT_VARIANTS." V ON (I.variant_id=V.id)  
				WHERE I.vendor_accept_status='1' AND I.order_id='".$order_id."' ORDER BY I.price*I.qty LIMIT 1 ";
		$exe = $this->exeQuery($query);
	 	if(mysqli_num_rows($exe)>0){
            $i = 1;
            while ($details = mysqli_fetch_array($exe)) {
		    	$list  = $this->editPagePublish($details);

                $total_tax = $list['totalTax'] * $list['qty'] ;
				$name      = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;
				$coupon    = $list['coupon_id']!=0 ? $list['coupon_code'] : "-";
				$coupon_value    = $list['coupon_id']!=0 ? $list['couponTotal'] : "0.00";
                $coupon_value_int   = (($list['coupon_value']!="")? $list['couponTotal'] : 0 );

                $layout .='
					 '.$this->getInvoiceItems($order_id).'
					 ';
				if($list['coupon_id']!=""){
					$coupon_info = "( Coupon Code : ".$coupon." )"; 
				} else {
					$coupon_info = ""; 
				}

				if($list['shipping_cost']!=""){
					$shipping_info = $list['shipping_cost']; 
				} else {
					$shipping_info = "0"; 
				}
				
				$net_payable = $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];
                $payable     = $list["totalAmount"] - $net_payable;
                $commission_wo_tax = $list['totalCommission'] - $list['vendor_commission_tax_amt'];
                $payment_wo_tax    = $list['totalPayment'] - $list['vendor_payment_tax_amt'];
                $shipping_wo_tax   = $list['totalShipping'] - $list['vendor_shipping_tax_amt'];

                $layout .= '<tr style="background: #F5F5F5;">
	                            <td colspan="5" style="text-align: right;border-top: 1px solid #222;"><strong>Subtotal</strong></td>
	                            <td colspan="2" style="text-align: right;border-top: 1px solid #222;">Rs. '.number_format($list["subTotal"] + $list["totalTax"] ,2).'</td>
	                        </tr>
	                        <tr style="background: #F5F5F5;">
	                            <td colspan="5" style="text-align: right;"><strong>Discount</strong> <br> '.$coupon_info.' </td>
	                            <td colspan="2" style="text-align: right;">Rs. '.$this->inrFormat($coupon_value_int,2).'</td>
	                        </tr>
	                        <tr style="background: #F5F5F5;">
	                        	<td colspan="5" style="text-align: right;"><strong>Shipping Cost: </strong></td>
	                            <td colspan="2" style="text-align: right;">Rs. '.$this->inrFormat($shipping_info,2).'</td>
	                        </tr>
	                        <tr style="background: #F5F5F5;">
                                <td colspan="5" style="text-align: right;"><strong> Total Amount</strong></td>
                                <td colspan="2" style="text-align: right;"><strong>Rs. '.number_format($list["subTotal"] + $list["totalTax"] + $list['shipping_cost'] - $coupon_value_int,2).'</strong></td>
                            </tr>

                            <tr style="background: #F5F5F5;">
                                <td colspan="3" style="border-bottom: 1px solid #222;border-top: 1px solid #5D6975;">Total Amount in Words: <strong>'.$this->amountInWords($list["subTotal"] + $list["totalTax"] + $list['shipping_cost'] - $coupon_value_int).'</strong></td>
                                <td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;"><strong>Payable</strong></td>
                                <td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;;"><strong>Rs. '.number_format($list["subTotal"] + $list["totalTax"] + $list['shipping_cost'] - $coupon_value_int,2).'</strong></td>
                                '.$charges.'
                            </tr>';
				
            $i++;
            }
        }
        return $layout;
    }

     function getInvoiceItems($order_id="")
    {    
        $layout = "";
        $query = "SELECT I.id,I.qty,I.product_id,I.order_id,I.variant_id,I.price as prize,I.total_tax,I.sub_total,I.final_price,I.qty,I.size,I.total_amount,I.sgst,I.cgst,I.igst,I.sgst_amt,I.cgst_amt,I.igst_amt,I.tax_type,I.vendor_commission_amt,I.vendor_payment_charge_amt,I.vendor_shipping_charge_amt,O.order_address,O.order_uid,P.product_name,V.variant_name,O.order_address,PU.product_unit 
        		 FROM  ".ORDER_ITEM_TBL." I LEFT JOIN ".PRODUCT_TBL." P ON(P.id=I.product_id) 
        		 							LEFT JOIN ".ORDER_TBL." O ON (O.id=I.order_id) 
        		 							LEFT JOIN ".PRODUCT_VARIANTS." V ON (I.variant_id=V.id)  
										    LEFT JOIN ".PRODUCT_UNIT_TBL." PU ON (PU.id=P.product_unit)  
        		 WHERE I.order_id='".$order_id."' AND I.vendor_accept_status='1' ORDER BY I.price*I.qty   DESC ";
		$exe = $this->exeQuery($query);
	 	if(mysqli_num_rows($exe)>0){
            $i = 1;
            while ($details = mysqli_fetch_array($exe)) {
		    	$list  = $this->editPagePublish($details);
		    	$background = ($i %2 == 0)? "background: #F5F5F5;": "";

                $total_tax = $list['total_tax'] * $list['qty'] ;
                $igst_Amt  = $list['igst_amt'];
				$name      = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

				$billing_address = $this->getDetails(CUSTOMER_ADDRESS_TBL,"state_id","id=".$list['order_address']." ");

				if($billing_address['state_id']==$list['state_id']) {
					$tax_info = $list['sgst']."% SGST<br/>".$list['cgst']."% CGST";
				} else {
					$tax_info = $list['igst']."% IGST";
				}

				$product_unit = (($list['product_unit']!="")? "( ".$list['product_unit']." )" : "" );

                $layout .='
                		<tr>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$i.'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: left;">'.$this->publishContent($name).'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">Rs.'.number_format($list['prize'],2).'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$this->publishContent($list['qty']).' '.$product_unit.'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$tax_info.'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">Rs.'.number_format($igst_Amt,2).'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: right;">Rs.'.number_format($list['sub_total'],2).'</td>
						</tr>
					 
			          ';
            $i++;
            }
        }
        return $layout;
    }

/*-----------Dont'delete---------*/

}


?>




