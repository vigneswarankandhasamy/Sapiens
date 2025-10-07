<?php
	require_once 'Model.php';
	require_once 'FileUploader.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class ExportReports extends Model
	{

	/*------------------------------------------------ 
		      Excel Export Functions For Reports  
	------------------------------------------------*/

    // Customer order and Vendor order list reports 

	function exportCustomerOrderReport($from,$to)
	{	

		$order_data = array();

		if( $from=="" )
		{
			$query = "SELECT O.id,O.total_amount,O.shipping_cost,O.coupon_value,O.order_uid,O.payment_type,O.order_status,O.payment_status,O.order_date,O.cancel_status,O.shipping_status,O.deliver_status,O.created_at,C.name,C.mobile,C.email,
                    (SELECT COUNT(id) FROM ".ORDER_ITEM_TBL." WHERE order_id=O.id ) as items,
                    (SELECT SUM(vendor_commission_amt) FROM ".ORDER_ITEM_TBL." WHERE order_id=O.id ) as total_commission,
                    (SELECT SUM(vendor_payment_charge_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id ) as total_payment_charge_amt,
                    (SELECT SUM(vendor_shipping_charge_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id ) as total_shipping_charge_amt FROM ".
                ORDER_TBL." O LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) WHERE 1 ORDER BY id  DESC";
		} else {
			$from = date("Y-m-d ",strtotime($from));
			$to = date("Y-m-d ",strtotime($to));
			$query = "SELECT O.id,O.total_amount,O.shipping_cost,O.coupon_value,O.order_uid,O.payment_type,O.order_status,O.payment_status,O.order_date,O.cancel_status,O.shipping_status,O.deliver_status,O.created_at,C.name,C.mobile,C.email,
                    (SELECT COUNT(id) FROM ".ORDER_ITEM_TBL." WHERE order_id=O.id ) as items,
                    (SELECT SUM(vendor_commission_amt) FROM ".ORDER_ITEM_TBL." WHERE order_id=O.id ) as total_commission,
                    (SELECT SUM(vendor_payment_charge_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id ) as total_payment_charge_amt,
                    (SELECT SUM(vendor_shipping_charge_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id ) as total_shipping_charge_amt FROM ".
                ORDER_TBL." O LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id)   WHERE O.order_date BETWEEN '$from' AND '$to' ORDER BY id  DESC";
		}

	    $exe = $this->exeQuery($query);

	    if(mysqli_num_rows($exe) > 0){
	    	$i=1;
	    	while ($row=mysqli_fetch_array($exe)) {
	    			$list = $this->editPagePublish($row);

    				$chack_inprocess  = $this->getDetails(ORDER_ITEM_TBL,"order_status","order_id='".$list['id']."' AND order_status='0' "); 
                    $chack_shipped    = $this->getDetails(ORDER_ITEM_TBL,"order_status","order_id='".$list['id']."' AND order_status='1' "); 
                    $chack_delivered  = $this->getDetails(ORDER_ITEM_TBL,"order_status","order_id='".$list['id']."' AND order_status='2' "); 
                    $chack_cancelled  = $this->getDetails(ORDER_ITEM_TBL,"order_status","order_id='".$list['id']."' AND order_status='3' "); 
                    $status_class     =  "text-success" ; 

                    if($chack_inprocess) {
                        $status_check = "Inprocess";
                    } elseif($chack_shipped) {
                        $status_check = "Shipped";
                    } elseif ($chack_delivered) {
                        $status_check = "Delivered";
                    } elseif ( $chack_inprocess==0 && $chack_shipped==0 && $chack_delivered==0 && $chack_cancelled) {
                        $status_check = "Returned";
                    }

                    $total_charges    =  $list['total_commission'] +  $list['total_payment_charge_amt'] +  $list['total_shipping_charge_amt'];
					
                    $element      = array();
					$element[] 	  =	$i;
					$element[] 	  =	$list['order_uid'];
					$element[] 	  =	date("d/m/Y [h:i]",strtotime($list['created_at']));
					$element[] 	  =	$list['name'];
					$element[] 	  =	$list['mobile'];
					$element[] 	  =	$list['email'];
					$element[] 	  =	$list['items'];
					$element[] 	  =	$this->inrFormat($total_charges);
					$element[] 	  = $this->inrFormat($list['total_amount']);
					$element[] 	  = $status_check;
				  	$order_data[] = $element;
				  	$i++;

	    	}
	    }
	    return $order_data;
	}

    // Vendor order and Vendor order list reports 

    function exportVendorOrderReport($from,$to)
    {   

        $order_data = array();

        if( $from=="" )
        {
            $query = "SELECT VO.id,VO.vendor_id,VO.user_id,VO.order_id,VO.order_uid,VO.shipping_cost,VO.sub_total,VO.total_tax,VO.sgst_amt,VO.cgst_amt,VO.igst_amt,VO.vendor_payment_total,VO.vendor_commission_total,VO.vendor_shipping_total,VO.total_amount,VO.total_payment,VO.order_date,VO.order_status,VO.status,VO.created_at,VE.company,VE.mobile,VE.email,
            (SELECT vendor_invoice_number FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id LIMIT 1) as invoiveNumber,
            (SELECT SUM(coupon_value) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id LIMIT 1) as couponValue,
            (SELECT COUNT(id) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id ) as items
          FROM ".VENDOR_ORDER_TBL." VO  LEFT JOIN ".VENDOR_TBL." VE ON (VO.vendor_id=VE.id) WHERE 1 ORDER BY VO.id  DESC";
        } else {
            $from = date("Y-m-d ",strtotime($from));
            $to = date("Y-m-d ",strtotime($to));
            $query = "SELECT VO.id,VO.vendor_id,VO.user_id,VO.order_id,VO.order_uid,VO.shipping_cost,VO.sub_total,VO.total_tax,VO.sgst_amt,VO.cgst_amt,VO.igst_amt,VO.vendor_payment_total,VO.vendor_commission_total,VO.vendor_shipping_total,VO.total_amount,VO.total_payment,VO.order_date,VO.order_status,VO.status,VO.created_at,VE.company,VE.mobile,VE.email,
            (SELECT vendor_invoice_number FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id LIMIT 1) as invoiveNumber,
            (SELECT SUM(coupon_value) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id LIMIT 1) as couponValue,
            (SELECT COUNT(id) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id=VO.id ) as items
          FROM ".VENDOR_ORDER_TBL." VO  LEFT JOIN ".VENDOR_TBL." VE ON (VO.vendor_id=VE.id) WHERE O.order_date BETWEEN '$from' AND '$to' ORDER BY id  DESC";
        }

        $exe = $this->exeQuery($query);

        if(mysqli_num_rows($exe) > 0){
            $i=1;
            while ($row=mysqli_fetch_array($exe)) {
                    $list = $this->editPagePublish($row);
                    $element = array();

                    $chack_inprocess  = $this->getDetails(ORDER_ITEM_TBL,"order_status","order_id='".$list['order_id']."' AND order_status='0' "); 
                    $chack_shipped    = $this->getDetails(ORDER_ITEM_TBL,"order_status","order_id='".$list['order_id']."' AND order_status='1' "); 
                    $chack_delivered  = $this->getDetails(ORDER_ITEM_TBL,"order_status","order_id='".$list['order_id']."' AND order_status='2' "); 
                    $chack_cancelled  = $this->getDetails(ORDER_ITEM_TBL,"order_status","order_id='".$list['order_id']."' AND order_status='3' "); 
                    $status_class     =  "text-success" ; 

                    if($chack_inprocess) {
                        $status_check = "Inprocess";
                    } elseif($chack_shipped) {
                        $status_check = "Shipped";
                    } elseif ($chack_delivered) {
                        $status_check = "Delivered";
                    } elseif ( $chack_inprocess==0 && $chack_shipped==0 && $chack_delivered==0 && $chack_cancelled) {
                        $status_check = "Returned";
                    }

                    $total_charges    =  $list['vendor_commission_total'] +  $list['vendor_payment_total'] +  $list['vendor_shipping_total'];
                    
                    $element[]    = $i;
                    $element[]    = $list['invoiveNumber'];
                    $element[]    = date("d/m/Y [h:i]",strtotime($list['created_at']));
                    $element[]    = $list['company'];
                    $element[]    = $list['mobile'];
                    $element[]    = $list['email'];
                    $element[]    = $list['items'];
                    $element[]    = $this->inrFormat($total_charges);
                    $element[]    = $this->inrFormat($list['total_amount']);
                    $element[]    = $status_check;
                    $order_data[] = $element;
                    $i++;

            }
        }
        return $order_data;
    }

    // Customer order and Vendor order list reports with payout details

    function exportCustomerOrderList($from="",$to="")
    {
        
        $report_data = array();

        if($from!="" && $from!=0)
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to'";
        } else {
            $date_filter = "";
        }


        $q      = "SELECT VO.id,VO.total_amount,VO.order_id,VO.order_date,VO.user_id,VO.order_uid,VO.vendor_id,VO.vendor_commission_total,VO.vendor_payment_total,
                   VO.vendor_shipping_total,VE.company,CU.name,CU.email,CU.mobile FROM ".VENDOR_ORDER_TBL." VO 
                        LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) 
                        LEFT JOIN ".CUSTOMER_TBL." CU ON (CU.id=VO.user_id) 
                   WHERE VO.order_status!='0' AND VO.order_status!='1' ".$date_filter." AND VO.vendor_id='".$_SESSION["ecom_vendor_id"]."' ORDER BY VO.id DESC";
        $exe    = $this->exeQuery($q);

        if(mysqli_num_rows($exe)>0)
        {   
            $i=1;
            while ($list = mysqli_fetch_array($exe)) {
                $totalCommission = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
                $payout_check    = $this->check_query(VENDOR_PAYOUT_INVOICE_ITEM_TBL,"id","id='".$list['id']."'");
                $payout_status   = (($payout_check==0)? "Paid" : "Due" );

                $chack_inprocess  = $this->getDetails(ORDER_ITEM_TBL,"order_status","order_id='".$list['order_id']."' AND order_status='0' "); 
                $chack_shipped    = $this->getDetails(ORDER_ITEM_TBL,"order_status","order_id='".$list['order_id']."' AND order_status='1' "); 
                $chack_delivered  = $this->getDetails(ORDER_ITEM_TBL,"order_status","order_id='".$list['order_id']."' AND order_status='2' "); 
                $chack_cancelled  = $this->getDetails(ORDER_ITEM_TBL,"order_status","order_id='".$list['order_id']."' AND order_status='3' "); 

                if($chack_inprocess) {
                    $status_check = "Inprocess";
                } elseif($chack_shipped) {
                    $status_check = "Shipped";
                } elseif ($chack_delivered) {
                    $status_check = "Delivered";
                } elseif ( $chack_inprocess==0 && $chack_shipped==0 && $chack_delivered==0 && $chack_cancelled) {
                    $status_check = "Returned";
                }

                $element        = array();
                $element[]      = $i;
                $element[]      = $list['order_uid'];
                $element[]      = $list['name'];
                $element[]      = $list['mobile'];
                $element[]      = $list['email'];
                $element[]      = date("d/m/y",strtotime($list['order_date']));
                $element[]      = $list['company'];
                $element[]      = $this->inrFormat($list['total_amount'] ,2);
                $element[]      = $this->inrFormat($totalCommission ,2);
                $element[]      = $payout_status;
                $element[]      = $status_check;
                $report_data[]  = $element;
                
                $i++;
            }

        }

        return $report_data;
    }


    // Customer order and Vendor order list reports with payout details

    function exportCustomerReturnedOrderList($from="",$to="")
    {
        
        $report_data = array();

        if($from!="" && $from!=0)
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to'";
        } else {
            $date_filter = "";
        } 

        $q  = "SELECT VI.id,VI.vendor_order_id,VI.order_status,VI.delivery_date,VI.return_reason,VI.return_date,VO.total_amount,VO.order_date,
                VO.user_id,VO.order_uid,VO.vendor_id,VO.vendor_commission_total,VO.vendor_payment_total,VO.vendor_shipping_total,VE.company,
                CU.name,CU.email,CU.mobile,R.return_reason as returnReason FROM ".VENDOR_ORDER_ITEM_TBL." VI 
                    LEFT JOIN ".VENDOR_ORDER_TBL." VO ON (VO.id=VI.vendor_order_id) 
                    LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) 
                    LEFT JOIN ".CUSTOMER_TBL." CU ON (CU.id=VO.user_id)
                    LEFT JOIN ".RETURN_REASON_TBL." R ON (VI.return_reason=R.id)
              WHERE VI.order_status='3' ".$date_filter." AND VI.vendor_id='".$_SESSION["ecom_vendor_id"]."'   ORDER BY VO.id DESC ";
        $exe    = $this->exeQuery($q);

        if(mysqli_num_rows($exe)>0)
        {   
            $i=1;
            while ($list = mysqli_fetch_array($exe)) {
                    $element        = array();
                    $element[]      = $i;
                    $element[]      = $list['name'];
                    $element[]      = date("d/m/y",strtotime($list['order_date']));
                    $element[]      = date("d/m/y",strtotime($list['delivery_date']));
                    $element[]      = $list['company'];
                    $element[]      = $this->inrFormat($list['total_amount'] ,2);
                    $element[]      = $list['returnReason'];
                    $element[]      = date("d-M-y",strtotime($list['return_date']));
                    $report_data[]  = $element;
                    
                    $i++;
            }
        }
        return $report_data;
    }

      // Customer order and Vendor order list reports 

    function exportCustomerRejectedOrderList($from="",$to="")
    {
        
        $report_data = array();

        if($from!="" && $from!=0)
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to'";
        } else {
            $date_filter = "";
        }

        $q      = "SELECT VO.id,VO.total_amount,VO.order_id,VO.order_date,VO.user_id,VO.order_uid,VO.vendor_id,VO.vendor_commission_total,
                   VO.vendor_payment_total,VO.vendor_shipping_total,VO.response_notes,VE.company,CU.name,CU.email,CU.mobile 
                   FROM ".VENDOR_ORDER_TBL." VO 
                        LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) 
                        LEFT JOIN ".CUSTOMER_TBL." CU ON (CU.id=VO.user_id) 
                   WHERE VO.order_status!='0' AND VO.order_status!='1' ".$date_filter." AND VO.vendor_id='".$_SESSION["ecom_vendor_id"]."' AND VO.vendor_response='1' AND VO.vendor_accept_status='0' ORDER BY VO.id DESC";
        $exe    = $this->exeQuery($q);

        if(mysqli_num_rows($exe)>0)
        {   
            $i=1;
            while ($list = mysqli_fetch_array($exe)) {
                $totalCommission = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];

                $element        = array();
                $element[]      = $i;
                $element[]      = $list['order_uid'];
                $element[]      = $list['name'];
                $element[]      = $list['mobile'];
                $element[]      = $list['email'];
                $element[]      = date("d/m/y",strtotime($list['order_date']));
                $element[]      = $list['company'];
                $element[]      = $this->inrFormat($list['total_amount'] ,2);
                $element[]      = $this->inrFormat($totalCommission ,2);
                $element[]      = "Rejected";
                $element[]      = $list['response_notes'];
                $report_data[]  = $element;
                
                $i++;
            }

        }

        return $report_data;
    }

   

    // Vendor payout reports 

    function exportPayoutListReport($from,$to)
    {   

        $order_data = array();

        if( $from=="" )
        {
            $query = "SELECT PA.id,PA.payout_invoice_id,PA.vendor_id,PA.net_payable,PA.total_order_value,PA.total_commission,PA.created_at,VE.company,
        (SELECT COUNT(id) FROM ".VENDOR_PAYOUT_INVOICE_ITEM_TBL." WHERE payout_id=PA.id) as total_orders
         FROM ".VENDOR_PAYOUT_INVOICE_TBL." PA LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=PA.vendor_id)  WHERE vendor_id=".$_SESSION["ecom_vendor_id"]." ";
        } else {
            $from = date("Y-m-d ",strtotime($from));
            $to = date("Y-m-d ",strtotime($to));
            $query = "SELECT PA.id,PA.payout_invoice_id,PA.vendor_id,PA.net_payable,PA.total_order_value,PA.total_commission,PA.created_at,VE.company,
        (SELECT COUNT(id) FROM ".VENDOR_PAYOUT_INVOICE_ITEM_TBL." WHERE payout_id=PA.id) as total_orders
         FROM ".VENDOR_PAYOUT_INVOICE_TBL." PA LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=PA.vendor_id)  WHERE vendor_id=".$_SESSION["ecom_vendor_id"]." AND DATE(PA.created_at) BETWEEN '$from' AND '$to' ";
        }

        $exe = $this->exeQuery($query);

        if(mysqli_num_rows($exe) > 0){
            $i=1;
            while ($row=mysqli_fetch_array($exe)) {
                    $list = $this->editPagePublish($row);

                    $payable = number_format(($list['total_order_value'] - $list['net_payable']),2);  ;

                    
                    $element      = array();
                    $element[]    = $i;
                    $element[]    = $list['payout_invoice_id'];
                    $element[]    = date("d/m/Y [h:i]",strtotime($list['created_at']));
                    $element[]    = $list['company'];
                    $element[]    = $list['total_orders'];
                    $element[]    = $list['total_order_value'];
                    $element[]    = $this->inrFormat($payable);
                    $element[]    = $this->inrFormat($list['net_payable']);
                    $element[]    = "Paid";
                    $order_data[] = $element;
                    $i++;

            }
        }
        return $order_data;
    }

    // Vendor unpaid payout reports 

    function exportUnpayoutListReport($from,$to)
    {   

        $order_data             = array();
        $q                      = "SELECT id FROM ".VENDOR_PAYOUT_INVOICE_TBL." WHERE vendor_id='".$_SESSION['ecom_vendor_id']."' ";
        $exe                    = $this->exeQuery($q);
        $paid_payouts_ids       = array();
       
        while($list = mysqli_fetch_array($exe)){
            $paid_payouts_ids[] =    $list['id'];
        }

        if(count($paid_payouts_ids)==0) 
        {
            $paid_payouts_ids[] = 0;
        }

        $q2                     = "SELECT id,vendor_order_id FROM ".VENDOR_PAYOUT_INVOICE_ITEM_TBL." WHERE payout_id IN ( '" . implode( "', '" , $paid_payouts_ids ) . "' ) ";
        $exe2                   = $this->exeQuery($q2);
        $unpaid_order_ids       = array();
        
        while($list = mysqli_fetch_array($exe2)){
            $unpaid_order_ids[]     =    $list['vendor_order_id'];
        }

        if(count($unpaid_order_ids)==0) 
        {
            $unpaid_order_ids[] = 0;
        }


        if( $from=="" )
        {
            $query  ="SELECT VO.id,VO.vendor_id,VO.vendor_payment_total,VO.vendor_shipping_total,VO.vendor_commission_total,VO.total_amount,VO.order_date,VO.order_uid,VO.order_id,VO.order_status,VE.name as vendor_name,VE.company,VE.mobile as vendor_mobile,VE.email as vendor_email,C.name as cus_name FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) LEFT JOIN ".CUSTOMER_TBL." C ON (C.id=VO.user_id) WHERE VO.order_status=2 AND VO.vendor_id='".$_SESSION['ecom_vendor_id']."' AND VO.id NOT IN ( '" . implode( "', '" , $unpaid_order_ids ) . "' )  ";
        } else {
            $from = date("Y-m-d ",strtotime($from));
            $to   = date("Y-m-d ",strtotime($to));

            $query  ="SELECT VO.id,VO.vendor_id,VO.vendor_payment_total,VO.vendor_shipping_total,VO.vendor_commission_total,VO.total_amount,VO.order_date,VO.order_uid,VO.order_id,VO.order_status,VE.name as vendor_name,VE.company,VE.mobile as vendor_mobile,VE.email as vendor_email,C.name as cus_name FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) LEFT JOIN ".CUSTOMER_TBL." C ON (C.id=VO.user_id) WHERE VO.order_status=2 AND VO.vendor_id='".$_SESSION['ecom_vendor_id']."' AND VO.id NOT IN ( '" . implode( "', '" , $unpaid_order_ids ) . "' ) AND VO.order_date BETWEEN '$from' AND '$to' ";
        }

        $exe = $this->exeQuery($query);

        if(mysqli_num_rows($exe) > 0) {
            $i=1;
            while ($row=mysqli_fetch_array($exe)) {
                    $list = $this->editPagePublish($row);

                    $commission   = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
                    $payable      = $list['total_amount'] - $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
                    
                    $element         = array();
                    $element[]    = $i;
                    $element[]    = $list['order_uid'];
                    $element[]    = date("d/m/Y [h:i]",strtotime($list['order_date']));
                    $element[]    = $list['cus_name'];
                    $element[]    = $list['total_amount'];
                    $element[]    = $this->inrFormat($commission);
                    $element[]    = $this->inrFormat($payable);
                    $element[]    = "Due";
                    $order_data[] = $element;
                    $i++;

            }
        }
        return $order_data;
    }

	
	/*-----------Dont'delete---------*/

	}


?>