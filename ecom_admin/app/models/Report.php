<?php
	require_once 'Model.php';
	require_once 'FileUploader.php';
	require_once 'MultipleFileUploader.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class Report extends Model
	{

	// Datas for card box in order reports page

    function getOrderReportCardData($from="",$to="",$vendor="") 
    {
        $result = array();



        if($from!="" && $from!=0 ) 
        {	
        	$from = date("Y-m-d",strtotime($from));
    		$to   = date("Y-m-d",strtotime($to));
        	$date_filter = "AND order_date BETWEEN '$from' AND '$to' ";
        } else {
            $current_date    = date("Y-m-d");
            $last_seven_days = date("Y-m-d", strtotime("-7 days", strtotime($current_date)));
            $date_filter = "AND DATE(order_date)=CURDATE()";// BETWEEN '$last_seven_days' AND '$current_date' ";
        }

        $q  = "SELECT id,
                    (SELECT COUNT(id) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$date_filter.") as totalOrders,
                    (SELECT COUNT(id) FROM ".ORDER_TBL." WHERE order_status='3' ".$date_filter.") as returnedOrders,
                    (SELECT SUM(total_amount) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$date_filter." ) as totalAmount,
                    (SELECT SUM(vendor_commission_amt) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$date_filter.") as vendorCommissionAmt,
                    (SELECT SUM(vendor_payment_charge_amt) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$date_filter.") as vendorPayChargeAmt,
                    (SELECT SUM(vendor_shipping_charge_amt) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$date_filter.") as vendorShipChargeAmt
               FROM ".ORDER_TBL."  WHERE 1 ";
        $exe = $this->exeQuery($q);
        $list = mysqli_fetch_array($exe);
        return $list;
    }

    // Datas for chart in order reports page

    function getOrderReportChartData($from="",$to="",$vendor="") 
    {
        $result = array();

        if($from!="" &&$from!=0) 
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND order_date BETWEEN '$from' AND '$to' ";
            $date_filter_customer = "AND registration_date BETWEEN '$from' AND '$to' ";

            // For sales vs order chart

            $dates                = array();
            $daily_total          = array();
            $daily_total_cs_count = array();
            $current              = strtotime($from);
            $date2                = strtotime($to);
            $stepVal              = '+1 day';
                
            while( $current <= $date2 ) {
                $dates[] ='"'.date("d-M", $current).'"';
                $q_date  = date("Y-m-d",$current);
                $current = strtotime($stepVal, $current);
                $q       = "SELECT SUM(total_amount) as dailyTotal, 
                                (SELECT COUNT(id) FROM ".CUSTOMER_TBL." WHERE DATE(registration_date) = '".$q_date."') as dailyCustomerCount 
                            FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND DATE(order_date) = '".$q_date."' ";
                $exe     =  $this->exeQuery($q);
                $list    = mysqli_fetch_array($exe);
                $daily_total[] = (($list['dailyTotal']=="")? 0 : $list['dailyTotal'] );
                $daily_total_cs_count[] = (($list['dailyCustomerCount']=="")? 0 : $list['dailyCustomerCount'] );
            }

           $x_axis       = "[". implode(",",$dates) ."]" ;
           $y_axis_sales = "[". implode(",",$daily_total) ."]" ;
           $y_axis_cus   = "[". implode(",",$daily_total_cs_count) ."]" ;

        } else {
            $date_filter = "AND DATE(order_date)=CURDATE()";
            $date_filter_customer = "AND DATE(registration_date)=CURDATE()";

            // For sales vs order chart

            $months = array();
            $monthly_order_totals = array();
            $monthly_customer_totals = array();
            $now = date('Y-F');
            for($x = 11; $x >= 0; $x--) {
                $ym = date('M', strtotime($now . " -$x month"));
                $y  = date('Y', strtotime($now . " -$x month"));
                $m  = date('m', strtotime($now . " -$x month"));
                $months[$x+1] = '"'.$ym.'"';

                $q= "SELECT SUM(total_amount) as monthlyOrderTotal,
                        (SELECT COUNT(id) FROM ".CUSTOMER_TBL." WHERE order_status!='0' AND order_status!='1' AND MONTH(registration_date) = '".$m."' AND YEAR(registration_date) = '".$y."' ".$date_filter_customer.") as monthlyCustomerCount
                    FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND MONTH(order_date) = '".$m."' AND YEAR(order_date) = '".$y."' ";
                
                $exe  =  $this->exeQuery($q);
                $list = mysqli_fetch_array($exe);
                $monthly_order_totals[$m]    = (($list['monthlyOrderTotal']=="")? 0 : $list['monthlyOrderTotal'] );
                $monthly_customer_totals[$m] = (($list['monthlyCustomerCount']=="")? 0 : $list['monthlyCustomerCount'] );
            }


            $q_h = "SELECT SUM(total_amount) as orders_at_12am,
                  (SELECT SUM(total_amount)  FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:01 AM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."') as orders_at_12am_to_12pm,
                  (SELECT SUM(total_amount)  FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 11:59 PM"))."') as orders_at_12pm_to_12am
                  FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND created_at='".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 AM"))."' "; 
            $exe_h = $this->exeQuery($q_h);
            $list_h = mysqli_fetch_array($exe_h);

            $y_axis_data = array();
            $y_axis_data[] = (($list_h['orders_at_12am'])? $list_h['orders_at_12am'] : 0);
            $y_axis_data[] = (($list_h['orders_at_12am_to_12pm'])? $list_h['orders_at_12am_to_12pm'] : 0);
            $y_axis_data[] = (($list_h['orders_at_12pm_to_12am'])? $list_h['orders_at_12pm_to_12am'] : 0);
            if($y_axis_data[2]==0) {
                unset($y_axis_data[2]);
            }

            // $x_axis       = "[". implode(",",$months) ."]" ;
            $x_axis        = '["12 am","12 pm", "12am"]' ;
            $y_axis_sales = "[". implode(",",$y_axis_data) ."]" ;
            // $y_axis_sales = '["'.(($list_h['orders_at_12am'])? $list_h['orders_at_12am'] : 0).'","'.(($list_h['orders_at_12am_to_12pm'])? $list_h['orders_at_12am_to_12pm'] : 0).'", "'.(($list_h['orders_at_12pm_to_12am'])? $list_h['orders_at_12pm_to_12am'] : 0).'"]' ;
            $y_axis_cus   = "[". implode(",",$monthly_customer_totals) ."]" ;
        }


        $q  = "SELECT id,SUM(total_amount) as overAllamount,
                    (SELECT COUNT(id) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$date_filter.") as totalOrders,
                    (SELECT COUNT(id) FROM ".ORDER_TBL." WHERE order_status='1' ".$date_filter.") as inprocess,
                    (SELECT COUNT(id) FROM ".ORDER_TBL." WHERE order_status='2' ".$date_filter.") as delivered,
                    (SELECT COUNT(id) FROM ".ORDER_TBL." WHERE order_status='3' ".$date_filter.") as returnedOrders,
                    (SELECT COUNT(id) FROM ".ORDER_TBL." WHERE order_status='4' ".$date_filter.") as cancelledOrders,
                    (SELECT COUNT(id) FROM ".ORDER_TBL." WHERE order_device='website' ".$date_filter.") as website,
                    (SELECT COUNT(id) FROM ".ORDER_TBL." WHERE order_device='android' ".$date_filter.") as android,
                    (SELECT COUNT(id) FROM ".ORDER_TBL." WHERE order_device='ios' ".$date_filter.") as ios,
                    (SELECT COUNT(id) FROM ".CUSTOMER_TBL." WHERE 1 ".$date_filter_customer.") as totalCustomerCount,
                    (SELECT COUNT(id) FROM ".CUSTOMER_TBL." WHERE 1) as overallCustomerCount,
                    (SELECT SUM(total_amount) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$date_filter." ) as totalAmount,
                    (SELECT SUM(vendor_commission_amt) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$date_filter.") as vendorCommissionAmt,
                    (SELECT SUM(vendor_payment_charge_amt) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$date_filter.") as vendorPayChargeAmt,
                    (SELECT SUM(vendor_shipping_charge_amt) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$date_filter.") as vendorShipChargeAmt
               FROM ".ORDER_TBL."  WHERE 1 ";
        $exe = $this->exeQuery($q);
        $list = mysqli_fetch_array($exe);

        $pie_chart_data_ar          = array($list['delivered'],$list['inprocess'],$list['cancelledOrders'],$list['returnedOrders']);
        $order_status_chart_data = "[". implode(",",$pie_chart_data_ar) ."]" ;

        $pie_chart_data2_ar     = array($list['website'],$list['android'],$list['ios']);
        $sales_platform_data = "[". implode(",",$pie_chart_data2_ar) ."]" ;

        $result['count_data']               = $list; 
        $result['x_axis']                   = $x_axis; 
        $result['y_axis_sales']             = $y_axis_sales; 
        $result['y_axis_cus']               = $y_axis_cus;
        $result['order_status_chart_true']  = array_sum($pie_chart_data_ar);
        $result['order_status_chart_data']  = $order_status_chart_data;
        $result['sales_platform_true']      = array_sum($pie_chart_data2_ar);
        $result['sales_platform_data']      = $sales_platform_data;

        return $result;
    }

    // Datas for card box in order reports page

    function getVendorOrderReportCardData($vendor="",$from="",$to="") 
    {
        $result = array();

        if($from!="" && $to!="") 
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND order_date BETWEEN '$from' AND '$to' ";
        } else {
            $date_filter = "AND DATE(order_date)= CURDATE()";
        }

        if($vendor!="") 
        {
            $vendor_check = "AND  vendor_id='".$vendor."' ";
        } else {
            $vendor_check = "";
        }

        $q  = "SELECT id,SUM(total_amount) as totalAmount,SUM(vendor_commission_total) as vendorCommissionAmt,SUM(vendor_payment_total) as   
                    vendorPayChargeAmt,SUM(vendor_shipping_total) as vendorShipChargeAmt,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." ".$date_filter." ) as totalOrders,
                     (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE vendor_response='1' AND vendor_accept_status='0' AND DATE(updated_at)=CURDATE() ) as vendorRejectedOrders,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='3' ".$vendor_check." ".$date_filter." ) as returnedOrders
               FROM ".VENDOR_ORDER_TBL."  WHERE order_status!='0' AND order_status!='1' ".$vendor_check." ".$date_filter." ";
        $exe = $this->exeQuery($q);
        $list = mysqli_fetch_array($exe);
        return $list;
    }

    // Datas for chart in order reports page

    function getVendorOrderReportChartData($vendor="",$from="",$to="") 
    {
        $result = array();

        if($vendor!="") 
        {
            $vendor_check = "AND  vendor_id='".$vendor."' ";
        } else {
            $vendor_check = "";
        }

        if($from!="" && $from!=0) 
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND order_date BETWEEN '$from' AND '$to' ";

            // For sales vs order chart

            $dates                = array();
            $daily_total          = array();
            $current              = strtotime($from);
            $date2                = strtotime($to);
            $stepVal              = '+1 day';
                
            while( $current <= $date2 ) {
                $dates[] ='"'.date("d-M", $current).'"';
                $q_date  = date("Y-m-d",$current);
                $current = strtotime($stepVal, $current);
                $q       = "SELECT SUM(total_amount) as dailyTotal FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND 
                            DATE(order_date) = '".$q_date."' ".$vendor_check." ";
                $exe     =  $this->exeQuery($q);
                $list    = mysqli_fetch_array($exe);
                $daily_total[] = (($list['dailyTotal']=="")? 0 : $list['dailyTotal'] );
            }

           $x_axis       = "[". implode(",",$dates) ."]" ;
           $y_axis_sales = "[". implode(",",$daily_total) ."]" ;

        } else {
            $date_filter = "AND DATE(order_date)=CURDATE()";

            // For sales vs order chart

            $months = array();
            $monthly_order_totals = array();

            $now = date('Y-F');
            for($x = 11; $x >= 0; $x--) {
                $ym = date('M', strtotime($now . " -$x month"));
                $y  = date('Y', strtotime($now . " -$x month"));
                $m  = date('m', strtotime($now . " -$x month"));
                $months[$x+1] = '"'.$ym.'"';

                $q= "SELECT SUM(total_amount) as monthlyOrderTotal FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND MONTH(order_date) = '".$m."' AND YEAR(order_date) = '".$y."' ".$vendor_check." ";

                $exe  =  $this->exeQuery($q);
                $list = mysqli_fetch_array($exe);
                $monthly_order_totals[$m]    = (($list['monthlyOrderTotal']=="")? 0 : $list['monthlyOrderTotal'] );
            }

            $q_h = "SELECT SUM(total_amount) as orders_at_12am,
              (SELECT SUM(total_amount)  FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:01 AM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."') as orders_at_12am_to_12pm,
              (SELECT SUM(total_amount)  FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 11:59 PM"))."') as orders_at_12pm_to_12am
              FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at='".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 AM"))."' "; 

            $exe_h = $this->exeQuery($q_h);
            $list_h = mysqli_fetch_array($exe_h);

            $y_axis_data = array();
            $y_axis_data[] = (($list_h['orders_at_12am'])? $list_h['orders_at_12am'] : 0);
            $y_axis_data[] = (($list_h['orders_at_12am_to_12pm'])? $list_h['orders_at_12am_to_12pm'] : 0);
            $y_axis_data[] = (($list_h['orders_at_12pm_to_12am'])? $list_h['orders_at_12pm_to_12am'] : 0);
            if($y_axis_data[2]==0) {
                unset($y_axis_data[2]);
            }

            $x_axis       = '["12 am","12 pm", "12 am"]' ;
            $y_axis_sales = "[". implode(",",$y_axis_data) ."]" ;

            // $y_axis_sales = '["'.(($list_h['orders_at_12am'])? $list_h['orders_at_12am'] : 0).'","'.(($list_h['orders_at_12am_to_12pm'])? $list_h['orders_at_12am_to_12pm'] : 0).'", "'.(($list_h['orders_at_12pm_to_12am'])? $list_h['orders_at_12pm_to_12am'] : 0).'"]' ;

           
        }

        

        $q  = "SELECT id,SUM(total_amount) as totalAmount,SUM(vendor_commission_total) as vendorCommissionAmt,SUM(vendor_payment_total) as   
                    vendorPayChargeAmt,SUM(vendor_shipping_total) as vendorShipChargeAmt,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='1' ".$vendor_check." ".$date_filter.") as inprocess,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' ".$vendor_check." ".$date_filter.") as delivered,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='3' ".$vendor_check." ".$date_filter.") as returnedOrders,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='4' ".$vendor_check." ".$date_filter.") as cancelledOrders,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_device='website' ".$vendor_check." ".$date_filter.") as website,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_device='android' ".$vendor_check." ".$date_filter.") as android,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_device='ios' ".$vendor_check." ".$date_filter.") as ios,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." ".$date_filter." ) as totalOrders,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='3' ".$vendor_check." ".$date_filter." ) as returnedOrders,
                    (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." ".$date_filter.") as overAllamount
               FROM ".VENDOR_ORDER_TBL."  WHERE order_status!='0' AND order_status!='1' ".$vendor_check." ".$date_filter." ";
        $exe = $this->exeQuery($q);
        $list = mysqli_fetch_array($exe);



        
        

        $pie_chart_data_ar = array($list['website'],$list['android'],$list['ios']);
        $pie_chart_data = "[". implode(",",$pie_chart_data_ar) ."]" ;

        $result['count_data']               = $list; 
        $result['x_axis']                   = $x_axis; 
        $result['y_axis_sales']             = $y_axis_sales; 
        $result['sales_platform_true']      = array_sum($pie_chart_data_ar);
        $result['sales_platform_data']      = $pie_chart_data;

        return $result;
    }

     // Datas for card box in product reports page

    function getProductReportCardData($vendor="") 
    {
        $result = array();

        if($vendor!="") 
        {
            $vendor_check        = "AND  VP.vendor_id='".$vendor."' ";
            $vendor_select_check = "vendor_id='".$vendor."' ";
        } else {
            $vendor_check        = "AND  VP.vendor_id='0' ";
            $vendor_select_check = "vendor_id='0' ";
        }

        $q  = "SELECT VP.id,VP.min_qty,VP.max_qty,VP.status,VP.stock,P.min_order_qty,P.max_order_qty,
                   (SELECT count(id) FROM ".VENDOR_PRODUCTS_TBL." WHERE ".$vendor_select_check." AND stock > max_qty  ) as instock_count,
                   (SELECT count(id) FROM ".VENDOR_PRODUCTS_TBL." WHERE ".$vendor_select_check." AND stock < max_qty AND stock > min_qty ) as low_stock_count,
                   (SELECT count(id) FROM ".VENDOR_PRODUCTS_TBL." WHERE ".$vendor_select_check." AND stock < min_qty ) as out_of_stock_count
              FROM ".VENDOR_PRODUCTS_TBL." VP LEFT JOIN ".PRODUCT_TBL." P ON (VP.product_id=P.id) WHERE 1 ".$vendor_check." ";

        $exe  = $this->exeQuery($q);
        $list = mysqli_fetch_array($exe);

        return $list;
    }

    // Get vendor list For Report filter DroupDown
 
    function getVendors($current="")
    {   
        $layout = "";
        $q      = "SELECT VO.id,VO.vendor_id,VE.id as vendorID,VE.company,VE.mobile,VE.email FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) WHERE 1 GROUP BY vendor_id  DESC" ;
        $query = $this->exeQuery($q);
        if(@mysqli_num_rows($query)>0){
            $i=0;
            while($list = mysqli_fetch_array($query)){
                $selected = (($list['vendorID']==$current) ? 'selected' : '');
                $layout.= "<option value='".$list['vendorID']."' $selected>".$list['company']."</option>";
                $i++;
            }
        }
        return $layout;
    }

     // Get top vendors

    function getTopProductList($from="",$to="",$vendor="")
    {
        $layout = "";

        if($from!="")
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));   
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to' ";
        } else {
            $date_filter = "AND DATE(VO.order_date)=CURDATE()";
        }

        if($vendor!="") 
        {
            $vendor_check = "AND VI.vendor_id='".$vendor."' ";
        } else {
            $vendor_check = "AND VI.vendor_id='0' ";
        }

        $q   = "SELECT VI.id,VI.product_id, COUNT(VI.product_id) AS MOST_FREQUENT,P.product_name,P.category_type,P.main_category_id,VO.order_date,
                   P.sub_category_id FROM ".VENDOR_ORDER_ITEM_TBL." VI LEFT JOIN ".PRODUCT_TBL." P ON (P.id=VI.product_id) LEFT JOIN ".ORDER_TBL." 
                   VO ON (VO.id=VI.order_id) WHERE VI.order_status='2' ".$date_filter." ".$vendor_check." GROUP BY VI.product_id ORDER BY COUNT(VI.product_id) DESC
                   LIMIT 5";
        $exe = $this->exeQuery($q);

        if(mysqli_num_rows($exe)>0)
        {   
            $i=1;
            while ($list = mysqli_fetch_array($exe)) {

                if($list['category_type']=="main") 
                {
                    $category_detail = $this->getDetails(MAIN_CATEGORY_TBL,"category","id='".$list['main_category_id']."'");
                    $category        = $category_detail['category'];
                } else {
                    $category_detail = $this->getDetails(SUB_CATEGORY_TBL,"subcategory","id='".$list['sub_category_id']."'");
                    $category        = $category_detail['subcategory'];
                }

                 $layout  .="
                            <tr class='tb-tnx-item'>
                                    <td class='tb-tnx-id'>
                                        <a href='#'><span>".$i."</span></a>
                                    </td>
                                    <td class='tb-tnx-info'>
                                        <div class='tb-tnx-total'>
                                            <span class='title'>".$list['product_name']."</span>
                                        </div>
                                    </td>
                                    <td class='tb-tnx-info'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>".ucfirst($category)."</span>
                                        </div>
                                    </td>
                                </tr>";

                // $layout  .="
                //     <tr class='nk-tb-item'>
                //         <td class='nk-tb-col'>
                //             <span class='tb-lead'><a href='#'>".$i."</a></span>
                //         </td>
                //         <td class='nk-tb-col tb-col-sm'>
                //             <div class='user-card'>
                //                 <div class='user-name'>
                //                     <span class='tb-lead'>".$list['product_name']."</span>
                //                 </div>
                //             </div>
                //         </td>
                //         <td class='nk-tb-col tb-col-md'>
                //             <span class='tb-sub'>".$category."</span>
                //         </td>
                //     </tr> ";
            $i++;
            } 

        } else {
                $layout .= "
                            <tr class='tb-tnx-item text-center'>
                                    <td class='tb-tnx-id' colspan='6'>
                                            <span class='title ' >No Records</span>
                                    </td>
                            ";

        }

        return $layout;
    }

    function getVendorProductsuniqe($vendor_id)
    {   
        $ids    = array();
        $id     = $vendor_id;
        $q      = "SELECT product_id FROM ".VENDOR_PRODUCTS_TBL." WHERE vendor_id='".$id."' ORDER BY id ASC" ;
        $query  = $this->exeQuery($q);
        if(mysqli_num_rows($query)>0){
            $i=1;
            while($row = mysqli_fetch_array($query)) {
                $list  = $this->editPagePublish($row);
                $ids[] = $list['product_id'];
            }
        }
        return $ids;
    }

    // Inventory Product List design
    function  getVendorStockList($vendor_id) 
    {
        $unique     = array_unique($this->getVendorProductsuniqe($vendor_id));
        if(count($unique)==0) 
        {
            $unique[] = 0;
        }
        $layout                 = array();
        $layout['out_of_stock'] = "";
        $layout['low_stock']    = "";
        $layout['instock']      = "";

        $q = "SELECT  P.id,P.product_name,P.has_variants,VP.selling_price as vendor_sell_price,P.actual_price,P.selling_price,P.category_type, P.sku, P.main_category_id,P.sub_category_id,P.tax_class,P.delete_status,P.is_draft, P.stock,P.min_order_qty,P.max_order_qty,T.tax_class as taxClass , M.id as mainCatId, M.category,S.id as subCatId, S.subcategory,VP.stock as vendorStock,VP.id as vendor_assigned_id,VP.max_qty,VP.min_qty,
            (SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
            (SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image,
            (SELECT SUM(stock) FROM ".VENDOR_PRODUCTS_TBL." WHERE product_id=P.id AND vendor_id='".$vendor_id."') as t_stock_in_tis_prd 
          FROM ".PRODUCT_TBL." P LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id)
            LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=P.main_category_id)
            LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=P.sub_category_id)
            LEFT JOIN ".VENDOR_PRODUCTS_TBL." VP ON (P.id=VP.product_id AND vendor_id='".$vendor_id."')
          WHERE P.is_draft='0' AND P.delete_status='0' AND VP.id!='' AND P.status='1'  GROUP BY P.id DESC" ;

        $exe = $this->exeQuery($q);
         if(mysqli_num_rows($exe)>0){
            $i=1;
            $j=1;
            while($rows = mysqli_fetch_array($exe)){
                $list = $this->editPagePublish($rows);

                // Category
                $category_name = (($list['category_type']=="sub") ? $list['subcategory'] : $list['category'] );

                // Product Image
                if ($list['default_product_image']!="") {
                    $product_image = $list['default_product_image']!='' ? UPLOADS.$list['default_product_image'] : ASSETS_PATH."no_img.jpg" ;# code...
                }else{
                    $product_image = $list['product_image']!='' ? UPLOADS.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
                }

                $has_variants = ($list['has_variants']!=0)? 1 : 0 ;

                if($list['vendorStock'] >= $list['max_qty'])  {
                    $stock_sts      =  "Instock";
                    $stock_sts_cls  =  "text-success";
                    $stock_layout   =  "instock";
                } elseif ($list['vendorStock'] < $list['max_qty'] && $list['vendorStock'] > $list['min_qty']   ) {
                    $stock_sts      =  "Low Stock";
                    $stock_sts_cls  =  "text-warning";
                    $stock_layout   =  "low_stock";
                } elseif ($list['vendorStock'] < $list['min_qty']) {
                    $stock_sts      =  "Out Of Stock";
                    $stock_sts_cls  =  "text-danger";
                    $stock_layout   =  "out_of_stock";
                } elseif ($list['vendorStock'] == $list['min_qty']) {
                    $stock_sts      =  "Low Stock";
                    $stock_sts_cls  =  "text-warning";
                    $stock_layout   =  "low_stock";
                }

                $layout[$stock_layout] .= "
                    <tr class='nk-tb-item open_inventory_stock' data-option='".$list['id']."'>
                        <td class='nk-tb-col'>
                            ".$i."
                        </td>
                        <td class='nk-tb-col'>
                            <img src='".$product_image."' width=50 />
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            <span class='text-primary'>".$list['product_name']."</span>
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            ".$this->publishContent($category_name)."
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                           ₹ ".$this->inrFormat($list['actual_price'])."
                        </td>
                       <td class='nk-tb-col tb-col-md'>
                           ₹ ".$this->inrFormat($list['vendor_sell_price'])."
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                           ".$list['t_stock_in_tis_prd']." in stock
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                           <span class='badge badge-dot $stock_sts_cls cursor_pointer'>$stock_sts</span>
                        </td>
                      
                    </tr> ";


                
                $i++;
            }
        }
        return $layout;
    }

    // Get top vendors

    function getTopVendorList($from="",$to="")
    {
        $layout = "";

        if($from!="")
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to'";
        } else {
            $date_filter = "AND DATE(VO.order_date)=CURDATE()";
        }

        $q      = " SELECT VO.id,VO.total_amount,VO.order_date,VE.company,SUM(VO.total_amount) as totalOrderValue1,SUM(VO.vendor_commission_total) as 
                     totalCommissionValue,SUM(VO.vendor_payment_total) as totalPaymentValue, SUM(VO.vendor_shipping_total) as totalShippingValue,
                     (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND vendor_id=VE.id) as totalOrders,
                     (SELECT SUM(total_commission) FROM ".VENDOR_PAYOUT_INVOICE_TBL." WHERE  vendor_id=VE.id) as totalPayuotCommission
                    FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) WHERE VO.order_status!='0' AND VO.order_status!='1' ".$date_filter." GROUP BY VO.vendor_id ORDER BY VO.total_amount DESC ";
        $exe    = $this->exeQuery($q);

        if(mysqli_num_rows($exe)>0)
        {   
            $i=1;
            while ($list = mysqli_fetch_array($exe)) {
                $totalCommission = $list['totalCommissionValue'] + $list['totalPaymentValue'] + $list['totalShippingValue'];
                // $layout  .="
                //     <tr class='nk-tb-item'>
                //         <td class='nk-tb-col'>
                //             <span class='tb-lead'><a href='#'>".$i."</a></span>
                //         </td>
                //         <td class='nk-tb-col tb-col-sm'>
                //             <div class='user-card'>
                //                 <div class='user-name'>
                //                     <span class='tb-lead'>".$list['company']."</span>
                //                 </div>
                //             </div>
                //         </td>
                //         <td class='nk-tb-col tb-col-md'>
                //             <span class='tb-sub'>".$list['totalOrders']."</span>
                //         </td>
                //         <td class='nk-tb-col tb-col-lg'>
                //             <span class='tb-sub tb-amount'>₹ ".$this->inrFormat((($list['totalOrderValue1']!="")? $list['totalOrderValue1'] : 0),2)."</span>
                //         </td>
                //         <td class='nk-tb-col'>
                //             <span class='tb-sub tb-amount'>₹ ".$this->inrFormat($totalCommission ,2)."</span>
                //         </td>
                //         <td class='nk-tb-col'>
                //             <span class='tb-sub tb-amount text-success'>₹ ".$this->inrFormat($list['totalPayuotCommission'] ,2)."</span>
                //         </td>
                //         <td class='nk-tb-col nk-tb-col-tools'>
                //             <ul class='nk-tb-actions gx-1'>
                                
                //                 <li>
                //                     <div class='drodown'>
                //                         <a href='#' class='dropdown-toggle btn btn-icon btn-trigger' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a>
                //                         <div class='dropdown-menu dropdown-menu-right'>
                //                             <ul class='link-list-opt no-bdr'>
                //                                 <li><a href='#'><em class='icon ni ni-focus'></em><span>Quick View</span></a>
                //                                 </li>
                                               
                //                             </ul>
                //                         </div>
                //                     </div>
                //                 </li>
                //             </ul>
                //         </td>
                //     </tr> ";

                $layout .="
                <tr class='tb-tnx-item'>
                                    <td class='tb-tnx-id'>
                                            ".$i."
                                        </div>
                                    </td>
                                     <td class='tb-tnx-info'>
                                        <div class='tb-tnx-total'>
                                           <span class='amount'>".$list['company']."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                            <span class='amount'>".$list['totalOrders']."</span>
                                        </div>
                                    </td>
                                    <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>₹ ".$this->inrFormat((($list['totalOrderValue1']!="")? $list['totalOrderValue1'] : 0),2)."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                           <span class='amount'>₹ ".$this->inrFormat($totalCommission ,2)."</span>
                                        </div>
                                    </td>
                                     <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='tb-sub tb-amount text-success'>₹ ".$this->inrFormat($list['totalPayuotCommission'] ,2)."</span>
                                        </div>
                                    </td>
                                    
                                </tr>";
                    $i++;
            }

        } else {
                $layout .= "
                            <tr class='tb-tnx-item text-center'>
                                    <td class='tb-tnx-id' colspan='6'>
                                            <span class='title ' >No Records</span>
                                    </td>
                            ";

            }

        return $layout;
    }

    // Get Customer Order list

    function getCustomerOrderList($from="",$to="",$vendor="")
    {
        $layout = "";
        
        if($from!="" && $from!=0)
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to'";
        } else {
            $date_filter = "AND DATE(VO.order_date)=CURDATE()";
        }

        if($vendor!="") 
        {
            $vendor_check = "AND VO.vendor_id='".$vendor."' ";
        } else {
            $vendor_check = "";
        }

        $q      = "SELECT VO.id,VO.total_amount,VO.order_date,VO.user_id,VO.order_uid,VO.vendor_id,VO.order_id,VO.vendor_commission_total,VO.vendor_payment_total,VO.vendor_shipping_total,VE.company,CU.name,CU.email,CU.mobile FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) LEFT JOIN ".CUSTOMER_TBL." CU ON (CU.id=VO.user_id) WHERE VO.order_status!='0' AND VO.order_status!='1' ".$date_filter." ".$vendor_check." ORDER BY VO.id DESC";
        $exe    = $this->exeQuery($q);

        if(mysqli_num_rows($exe)>0)
        {   
            $i=1;
            while ($list = mysqli_fetch_array($exe)) {
                $totalCommission = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
                $payout_check    = $this->check_query(VENDOR_PAYOUT_INVOICE_ITEM_TBL,"id","id='".$list['id']."'");
                $payout_status   = (($payout_check==0)? "<span class='badge badge-dot badge-dot-xs badge-success'>Paid</span>" : "<span class='badge badge-dot badge-dot-xs badge-warning'>Due</span>" );
                    $layout  .="                   
                        <tr class='nk-tb-item open_order_details' data-option='".$list['order_id']."'>
                            <td class='nk-tb-col'>
                                <span class='tb-lead'><a href='#'>".$i."</a></span>
                            </td>
                            <td class='nk-tb-col tb-col-sm'>
                               <div class='user-card'>
                                    <div class='user-info'>
                                        <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                        <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                        <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                    </div>
                                </div>
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='tb-sub'>".date("d-M-y",strtotime($list['order_date']))."</span>
                            </td>
                            <td class='nk-tb-col tb-col-lg'>
                               <span class='tb-lead'>".$list['company']."</span>
                            </td>
                            <td class='nk-tb-col'>
                                <span class='tb-sub tb-amount'>₹ ".$this->inrFormat($list['total_amount'] ,2)."</span>
                            </td>
                            <td class='nk-tb-col'>
                                <span class='tb-sub tb-amount'>₹ ".$this->inrFormat($totalCommission ,2)."</span>
                            </td>
                            <td class='nk-tb-col'>
                                ".$payout_status."
                            </td>
                        </tr> ";
                $i++;
            }
        }
        return $layout;
    }

    // Get Customer Returned Order list

    function getCustomerReturnedOrderList($from="",$to="",$vendor="")
    {
        $layout = "";

        if($from!="" && $from!=0)
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to'";
        } else {
            $date_filter = "AND DATE(VO.order_date)=CURDATE()";
        } 

        if($vendor!="") 
        {
            $vendor_check = "AND VI.vendor_id='".$vendor."' ";
        } else {
            $vendor_check = "";
        }

        $q      = "SELECT VI.id,VI.vendor_order_id,VI.order_status,VI.delivery_date,VI.return_reason,VI.return_date,VO.total_amount,VO.order_date,VO.user_id,VO.order_uid,VO.order_id,VO.vendor_id,VO.vendor_commission_total,VO.vendor_payment_total,VO.vendor_shipping_total,VE.company,CU.name,CU.email,CU.mobile,R.return_reason as returnReason FROM ".VENDOR_ORDER_ITEM_TBL." VI 
                    LEFT JOIN ".VENDOR_ORDER_TBL." VO ON (VO.id=VI.vendor_order_id) 
                    LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) 
                    LEFT JOIN ".CUSTOMER_TBL." CU ON (CU.id=VO.user_id)
                    LEFT JOIN ".RETURN_REASON_TBL." R ON (VI.return_reason=R.id)
            WHERE VI.order_status='3' ".$date_filter." ".$vendor_check."  ORDER BY VO.id DESC ";
        $exe    = $this->exeQuery($q);

        if(mysqli_num_rows($exe)>0)
        {   
            $i=1;
            while ($list = mysqli_fetch_array($exe)) {
                $totalCommission = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
                $layout  .="
                    <tr class='nk-tb-item open_order_details' data-option='".$list['order_id']."'>
                        <td class='nk-tb-col'>
                            <span class='tb-lead'><a href='#'>".$i."</a></span>
                        </td>
                        <td class='nk-tb-col tb-col-sm'>
                           <div class='user-card'>
                                <div class='user-info'>
                                    <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                </div>
                            </div>
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            <span class='tb-sub'>".date("d-M-y",strtotime($list['order_date']))."</span>
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            <span class='tb-sub'>".date("d-M-y",strtotime($list['delivery_date']))."</span>
                        </td>
                        <td class='nk-tb-col tb-col-lg'>
                           <span class='tb-lead'>".$list['company']."</span>
                        </td>
                        <td class='nk-tb-col'>
                            <span class='tb-sub tb-amount'>₹ ".$this->inrFormat($list['total_amount'] ,2)."</span>
                        </td>
                        <td class='nk-tb-col'>
                            <span class='tb-sub tb-amount'>".$list['returnReason']."</span>
                        </td>
                        <td class='nk-tb-col'>
                           <span class='tb-sub'>".date("d-M-y",strtotime($list['return_date']))."</span>
                        </td>
                    </tr> ";
                $i++;
            }
        }
        return $layout;
    }

    // Get Vendor rejected order list

    function getVendorRejectedOrderList($from="",$to="",$vendor_id="")
    {
        $layout    = "";

        if($from!="")
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));   
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to' ";
        } else {
            $date_filter = "AND DATE(VO.order_date)=CURDATE()";
        }

        if($vendor_id!="") {
            $vendor_filter = "AND VO.vendor_id='".$vendor_id."'";
        } else {
            $vendor_filter = "";
        }

        $q = "SELECT VO.id,VO.order_id,VO.total_amount,VO.order_date,VO.user_id,VO.order_uid,VO.vendor_id,VO.vendor_commission_total,VO.vendor_payment_total,VO.vendor_shipping_total,VO.vendor_response,VO.vendor_accept_status,VO.response_notes,VO.response_status,VO.updated_at,VE.company,CU.name,CU.email,CU.mobile,RS.response_status as response_status_text  FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) 
                                    LEFT JOIN ".CUSTOMER_TBL." CU ON (CU.id=VO.user_id) 
                                    LEFT JOIN ".ORDER_RESPONSE_STATUS_TBL." RS ON (RS.id=VO.response_status) 
            WHERE   VO.vendor_response='1' AND VO.vendor_accept_status='0' ".$vendor_filter." ".$date_filter." ORDER BY VO.id DESC";
        $exe    = $this->exeQuery($q);

        if(mysqli_num_rows($exe)>0)
        {   
            $i=1;
            while ($list = mysqli_fetch_array($exe)) {
                $totalCommission = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
 
                $layout  .="
                    <tr class='nk-tb-item open_rejected_order_details' data-option='".$list['id']."'>
                        <td class='nk-tb-col'>
                            <span class='tb-lead'><a href='#'>".$i."</a></span>
                        </td>
                        <td class='nk-tb-col tb-col-sm'>
                           <div class='user-card'>
                                <div class='user-info'>
                                    <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                </div>
                            </div>
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            <span class='tb-sub'>".date("d-M-y",strtotime($list['order_date']))."</span>
                        </td>
                        <td class='nk-tb-col tb-col-lg'>
                           <span class='tb-lead'>".$list['company']."</span>
                        </td>
                        <td class='nk-tb-col'>
                            <span class='tb-sub tb-amount'>₹ ".$this->inrFormat($list['total_amount'] ,2)."</span>
                        </td>
                        <td class='nk-tb-col'>
                            <span class='tb-sub tb-amount'>₹ ".$this->inrFormat($totalCommission ,2)."</span>
                        </td>
                        <td class='nk-tb-col'>
                            <span class='tb-status status_list text-danger'>".$list['response_status_text']."</span>
                        </td>
                         <td class='nk-tb-col'>
                            <span class='tb-sub tb-amount'> ".date("d-M-y",strtotime($list['updated_at']))."</span>
                        </td>
                    </tr> ";
                    $i++;
            }

        }

        return $layout;
    }
	
	/*-----------Dont'delete---------*/

}?>




