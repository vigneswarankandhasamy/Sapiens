<?php
require_once 'Model.php';
require_once 'config/config.php';
require_once 'app/core/classes/PHPMailerAutoload.php';

class Admin extends Model
{

    //----------- Migrations -------------//

    function inrFormat($num) {
        $value = number_format((float)$num, 2, '.', '');
        return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $value);
    }

    function inrFormatFields($data) {
      $response = array();
      foreach ($data as $key => $value) {
       $response[$key] = $this->inrFormat($value);
      }
      return $response;
    }

    //  Migration List

    function migrationList()
    {
        $layout = "";
        $q = "SELECT * FROM ".MIGRATION_TBL." WHERE 1 ";
        $exe = $this->exeQuery($q); 
        if(mysqli_num_rows($exe) > 0){
        $i=1;
            while($rows = mysqli_fetch_array($exe)){
                $list   = $this->editPagePublish($rows);
                $layout .= "
                    <tr class='nk-tb-item'>
                        <td class='nk-tb-col'>
                            ".$list['id']."
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            <span class='text-primary'>".$list['name']."</span>
                        </td>
                        <td class='nk-tb-col tb-col-mb'>
                             ".$list['type']."
                        </td>
                        <td class='nk-tb-col tb-col-mb'>
                             ".$list['sql_query']."
                        </td>
                        <td class='nk-tb-col tb-col-mb'>
                             ".$list['remarks']."
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            ".date("d/m/Y", strtotime($list['created_at']))."
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            <button class='btn btn-success btn-sm' ><em class='icon ni ni-upload-cloud'></em></button>
                        </td>
                    </tr>";
                $i++;
            }
        } 
        return $layout;
    }

    /*-------------------------------------------
            Dashboard Chart Functions 
    --------------------------------------------*/

    // Chart datas for  1.Sales Chart 2.Order Chart 3.Total orders Chart

    function getChartDatas($input)
    {
        $result                 = array();
        $current_date           = date("Y-m-d");
        $last_seven_days        = date("Y-m-d", strtotime("-7 days", strtotime($current_date)));
        $previous_7_days        = date("Y-m-d", strtotime("-7 days", strtotime($last_seven_days)));
        $last_30_days           = date("Y-m-d", strtotime("-30 days", strtotime($current_date)));
        $last_30_days_end       = date("Y-m-d", strtotime("-30 days", strtotime($last_30_days)));
        $last_3_month_start     = date("Y-m-d", strtotime("-90 days", strtotime($current_date)));
        $last_3_month_end       = date("Y-m-d", strtotime("-90 days", strtotime($last_3_month_start)));

        if($input=='today_records') {
            $current_data           = "AND DATE(order_date)='$current_date' ";
            $rejected_date_filter   = "AND DATE(updated_at)='$current_date' ";
            $get_order_totals       = $this->getDetails(ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_data");
            $get_total_orders_count = $this->getDetails(ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data");

            for ($i = 0; $i <= 2; $i++) 
            {
               $d             = (($i==0)? 'am' : (($i==1)? 'pm' : (($i==2)? 'am' : "") )  ) ;
               $main_lable[]  = "<div class='chart-label'>12 $d</div>" ;
               $graph_lable[] = '12 '.$d;
            }
            
            // Total Sales Chart Current date datas

            $q_h = "SELECT SUM(total_amount) as orders_at_12am,
              (SELECT SUM(total_amount)  FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:01 AM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."') as orders_at_12am_to_12pm,
              (SELECT SUM(total_amount)  FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 11:59 PM"))."') as orders_at_12pm_to_12am
              FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND created_at='".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 AM"))."' "; 

            $exe_h         = $this->exeQuery($q_h);
            $list_h        = mysqli_fetch_array($exe_h);
            $total_sales   = array();
            $total_sales[] = (($list_h['orders_at_12am'])? $list_h['orders_at_12am'] : 0);
            $total_sales[] = (($list_h['orders_at_12am_to_12pm'])? $list_h['orders_at_12am_to_12pm'] : 0);
            $total_sales[] = (($list_h['orders_at_12pm_to_12am'])? $list_h['orders_at_12pm_to_12am'] : 0);

            if($total_sales[2]==0) {
                unset($total_sales[2]);
            }

            $result["current_data_total_sales"] = implode(",",$total_sales);

            // Average Chart current date datas

            $count_q = "SELECT COUNT(id) as orders_at_12am,
              (SELECT COUNT(id)  FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:01 AM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."') as orders_at_12am_to_12pm,
              (SELECT COUNT(id)  FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 11:59 PM"))."') as orders_at_12pm_to_12am
              FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND created_at='".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 AM"))."' "; 

            $value_q = "SELECT SUM(total_amount) as orders_at_12am,
              (SELECT SUM(total_amount)  FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:01 AM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."') as orders_at_12am_to_12pm,
              (SELECT SUM(total_amount)  FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 11:59 PM"))."') as orders_at_12pm_to_12am
              FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND created_at='".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 AM"))."' ";

            $count_exe       = $this->exeQuery($count_q);
            $count_list      = mysqli_fetch_array($count_exe);
            $value_exe       = $this->exeQuery($value_q);
            $value_list      = mysqli_fetch_array($value_exe);
            $total_average   = array();
            $total_average[] = (($count_list['orders_at_12am'])? $value_list['orders_at_12am']/$count_list['orders_at_12am'] : 0);
            $total_average[] = (($count_list['orders_at_12am_to_12pm'])? $value_list['orders_at_12am_to_12pm']/$count_list['orders_at_12am_to_12pm'] : 0);
            $total_average[] = (($count_list['orders_at_12pm_to_12am'])? $value_list['orders_at_12pm_to_12am']/$count_list['orders_at_12pm_to_12am'] : 0);

            if($total_average[2]==0) {
                unset($total_average[2]);
            }

            $result["current_data_average_total"] = implode(",",$total_average);

            $count = $count_list['orders_at_12am'] + $count_list['orders_at_12am_to_12pm'] + $count_list['orders_at_12pm_to_12am'];

            $result["average_total_data"] = (($get_order_totals['totalValues']==0)? 0 : $this->inrFormat($get_order_totals['totalValues']/$count) );

            $total_orders   = array();
            $total_orders[] = (($count_list['orders_at_12am'])? $count_list['orders_at_12am'] : 0);
            $total_orders[] = (($count_list['orders_at_12am_to_12pm'])? $count_list['orders_at_12am_to_12pm'] : 0);
            $total_orders[] = (($count_list['orders_at_12pm_to_12am'])? $count_list['orders_at_12pm_to_12am'] : 0);

            if($total_orders[2]==0) {
                unset($total_orders[2]);
            }

            $result["current_data_total_orders"] = implode(",",$total_orders);

        } else {

            if($input=='last_seven_days') {

                $from            = $last_seven_days;
                $to              = $current_date;

                for ($i = 0; $i <= 6; $i++) 
                {
                   $main_lable[] = "<div class='chart-label'>".date("d M", strtotime( date( 'Y-m-d' )." -$i days"))."</div>" ;
                }
            } elseif($input=='last_month') {

                $from            = $last_30_days;
                $to              = $current_date;

                for ($i = 0; $i <= 2; $i++) 
                {   
                   $d            = (($i==0)? 0 : (($i==1)? 15 : (($i==2)? 30 : "") )  ) ;
                   $main_lable[] = "<div class='chart-label'>".date("d M, Y", strtotime( date( 'Y-m-d' )." -$d days"))."</div>" ;
                }

            } elseif($input=='last_three_month') {

                $from            = $last_3_month_start;
                $to              = $current_date;

                for ($i = 0; $i <= 2; $i++) 
                {
                   $main_lable[] = "<div class='chart-label'>".date("F, Y", strtotime( date( 'Y-m-01' )." -$i months"))."</div>" ;
                }

            }

            $current_data                         = "AND DATE(order_date) BETWEEN '$from' AND '$to' ";
            $rejected_date_filter                 = "AND DATE(updated_at) BETWEEN '$from' AND '$to' ";
            $get_order_totals                     = $this->getDetails(ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_data");
            $get_total_orders_count               = $this->getDetails(ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data");

            // Date functions
            $current                              = strtotime($from);
            $date2                                = strtotime($to);
            $stepVal                              = '+1 day';

            // Results values
            $daily_total_sales                    = array();
            $daily_total_average                  = array();
            $daily_total_order                    = array();
            $graph_lable                          = array();
            while( $current <= $date2 ) {
                $q_date                           = date("Y-m-d",$current);
                $graph_lable[]                    = '"'.date("d-M", $current).'"';
                $current                          = strtotime($stepVal, $current);
                $q                                = "SELECT COUNT(id) as daily_total_order, SUM(total_amount) as dailyTotal 
                                                     FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1'  AND DATE(order_date) = '".$q_date."'  ";
                $exe                              =  $this->exeQuery($q);
                $list                             = mysqli_fetch_array($exe);
                $daily_total_sales[]              = (($list['dailyTotal']=="")? 0 : $list['dailyTotal'] );
                $daily_total_average[]            = (($list['daily_total_order']==0)? 0 : $list['dailyTotal']/$list['daily_total_order'] );
                $daily_total_order[]              = (($list['daily_total_order']==0)? 0 : $list['daily_total_order'] );
            }

            $result["current_data_total_sales"]   = implode(",",$daily_total_sales);
            $result["current_data_average_total"] = implode(",",$daily_total_average);
            $result["current_data_total_orders"]  = implode(",",$daily_total_order);
            $result["average_total_data"]         = (($get_order_totals['totalValues']==0)? 0 : $this->inrFormat($get_order_totals['totalValues']/$get_total_orders_count['totalOrders']) );

        }

        $result['main_lable']  = array_reverse($main_lable);
        $result['graph_lable'] = implode(",",$graph_lable);

        // Card Box Datas 

        // (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE vendor_response=='0' AND vendor_accept_status=='0' AND DATE(order_date)=CURDATE() ) as notSOrders,

        $q  = "SELECT id,
                (SELECT COUNT(id) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_data.") as card_totalOrders,
                (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE vendor_response='0' AND vendor_accept_status='0' ".$current_data." ) as notSeenOrders,
                (SELECT COUNT(id) FROM ".ORDER_TBL." WHERE order_status='3' ".$current_data.") as card_returnedOrders,
                (SELECT SUM(total_amount) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_data." ) as card_totalAmount,
                (SELECT SUM(vendor_commission_amt) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_data.") as card_vendorCommissionAmt,
                (SELECT SUM(vendor_payment_charge_amt) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_data.") as card_vendorPayChargeAmt,
                (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE vendor_response='1' AND vendor_accept_status='0'  ".$rejected_date_filter."   ) as card_order_rejected,
                (SELECT SUM(vendor_shipping_charge_amt) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_data.") as card_vendorShipChargeAmt
               FROM ".ORDER_TBL."  WHERE 1 ";


        $exe         = $this->exeQuery($q);
        $card_data   = mysqli_fetch_array($exe);

        $commission  = (($card_data)? $card_data['card_vendorCommissionAmt'] + $card_data['card_vendorPayChargeAmt'] + $card_data['card_vendorShipChargeAmt']  :  0);

        $payout      = (($card_data)? $card_data['card_totalAmount'] - ($card_data['card_vendorCommissionAmt'] + $card_data['card_vendorPayChargeAmt'] 
                       + $card_data['card_vendorShipChargeAmt']) : 0);

        $avg_ord_val = (($card_data)? $this->inrFormat((($card_data['card_totalOrders']!=0)? $card_data['card_totalAmount'] / $card_data['card_totalOrders'] : 0 )) : 0);
        
        $result['card_total_order']              = (($card_data)? $card_data['card_totalOrders'] : 0);
        $result['card_total_not_seen_order']     = (($card_data)? $card_data['notSeenOrders'] : 0);
        $result['card_order_returned']           = (($card_data)? $card_data['card_returnedOrders'] : 0);
        $result['card_order_rejected']           = (($card_data)? $card_data['card_order_rejected'] : 0);
        $result['card_total_amount']             = (($card_data)? $this->inrFormat($card_data['card_totalAmount']) : 0);
        $result['card_commission_earned']        = $this->inrFormat($commission);
        $result['card_vendor_payout']            = $this->inrFormat($payout);
        $result['card_avg_value']                = $avg_ord_val;
        $result["total_sales_data"]              = $this->inrFormat($get_order_totals['totalValues']);
        $result["total_orders_data"]             = $get_total_orders_count['totalOrders'];
        $result["previous_data_total_sales"]     = "";
        $result["previous_data_average_total"]   = "";
        $result["previous_data_total_orders"]    = "";

        return json_encode($result);
    }

    // Data for Order Total values

    function getOrderTotals($input)
    {
        $result = array();

        $current_date           = date("Y-m-d");
        $last_seven_days        = date("Y-m-d", strtotime("-7 days", strtotime($current_date)));
        $previous_7_days        = date("Y-m-d", strtotime("-7 days", strtotime($last_seven_days)));
        $last_30_days_start     = date("Y-m-d", strtotime("-30 days", strtotime($current_date)));
        $last_30_days_end       = date("Y-m-d", strtotime("-30 days", strtotime($last_30_days_start)));
        $last_3_month_start     = date("Y-m-d", strtotime("-90 days", strtotime($current_date)));
        $last_3_month_end       = date("Y-m-d", strtotime("-90 days", strtotime($last_3_month_start)));
        
        if($input=='today_records') {
            $current_data           = "AND DATE(order_date)='$current_date' ";
            $previous_data          = "AND DATE(order_date)='".date("Y-m-d", strtotime("-1 days", strtotime($current_date)))."' ";
            $get_order_totals       = $this->getDetails(ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_data");
            $get_total_orders_count = $this->getDetails(ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data");

             for ($i = 0; $i <= 6; $i++) 
            {
               $main_lable[] = "<div class='chart-label'>".date("d M", strtotime( date( 'Y-m-d' )." -$i days"))."</div>" ;
            }

        } elseif($input=='last_seven_days') {
            $current_data           = "AND DATE(order_date) BETWEEN '$last_30_days_start' AND '$current_date' ";
            $previous_data          = "AND DATE(order_date) BETWEEN '$last_30_days_end' AND '$last_30_days_start' ";
            $get_order_totals       = $this->getDetails(ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_data");
            $get_total_orders_count = $this->getDetails(ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data");

             for ($i = 0; $i <= 6; $i++) 
            {
               $main_lable[] = "<div class='chart-label'>".date("d M", strtotime( date( 'Y-m-d' )." -$i days"))."</div>" ;
            }

        } elseif($input=='last_month') {
            $current_data           = "AND DATE(order_date) BETWEEN '$last_seven_days' AND '$current_date' ";
            $previous_data          = "AND DATE(order_date) BETWEEN '$previous_7_days' AND '$last_seven_days' ";
            $get_order_totals       = $this->getDetails(ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_data");
            $get_total_orders_count = $this->getDetails(ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data");

            for ($i = 0; $i <= 2; $i++) 
            {   
               $d = (($i==0)? 0 : (($i==1)? 15 : (($i==2)? 30 : "") )  ) ;
               $main_lable[] = "<div class='chart-label'>".date("d M, Y", strtotime( date( 'Y-m-d' )." -$d days"))."</div>" ;
            }

        }  elseif($input=='last_three_month') {
            $current_data           = "AND DATE(order_date) BETWEEN '$last_3_month_start' AND '$current_date' ";
            $previous_data          = "AND DATE(order_date) BETWEEN '$last_3_month_end' AND '$last_3_month_start' ";
            $get_order_totals       = $this->getDetails(ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_data");
            $get_total_orders_count = $this->getDetails(ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data");

            for ($i = 0; $i <= 2; $i++) 
            {
               $main_lable[] = "<div class='chart-label'>".date("F, Y", strtotime( date( 'Y-m-01' )." -$i months"))."</div>" ;
            }
        } 

        $result['main_lable'] = array_reverse($main_lable);

        $q        = "SELECT total_amount FROM ".ORDER_TBL." WHERE deliver_status='0' $current_data  ORDER BY order_date ASC ";
        $exe      = $this->exeQuery($q);

        $current_data_total_sales = array();

        while($list = mysqli_fetch_array($exe)){
            $current_data_total_sales[] =   $list['total_amount'];
        }

        $current_data = implode(",", $current_data_total_sales);
        $result[0]    = $current_data;

        $q    = "SELECT total_amount FROM ".ORDER_TBL." WHERE deliver_status='0' $previous_data   ORDER BY order_date ASC ";
        $exe  = $this->exeQuery($q);

        $previous_data_records = array();

        while($list = mysqli_fetch_array($exe)){
            $previous_data_records[] =  $list['total_amount'];
        }
        
        $previous_data = implode(",", $previous_data_records);
        $result[1]     = $previous_data;
        $result[2]     = number_format($get_order_totals['totalValues'],2);

        return json_encode($result);
    }

    // Data for Average orders chart

    function getOrderAverage($input)
    {
        $result = array();

        $current_date           = date("Y-m-d");
        $last_seven_days        = date("Y-m-d", strtotime("-7 days", strtotime($current_date)));
        $previous_7_days        = date("Y-m-d", strtotime("-7 days", strtotime($last_seven_days)));
        $last_30_days_start     = date("Y-m-d", strtotime("-30 days", strtotime($current_date)));
        $last_30_days_end       = date("Y-m-d", strtotime("-30 days", strtotime($last_30_days_start)));
        $last_3_month_start     = date("Y-m-d", strtotime("-90 days", strtotime($current_date)));
        $last_3_month_end       = date("Y-m-d", strtotime("-90 days", strtotime($last_3_month_start)));

        if($input=='today_records') {
            $current_data           = "AND DATE(order_date)='$current_date' ";
            $previous_data          = "AND DATE(order_date)='".date("Y-m-d", strtotime("-1 days", strtotime($current_date)))."' ";
            $get_order_totals       = $this->getDetails(ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_data");
            $get_total_orders_count = $this->getDetails(ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data");

             for ($i = 0; $i <= 6; $i++) 
            {
               $main_lable[] = "<div class='chart-label'>".date("d M", strtotime( date( 'Y-m-d' )." -$i days"))."</div>" ;
            }

        } elseif($input=='last_seven_days') {
            $current_data           = "AND DATE(order_date) BETWEEN '$last_30_days_start' AND '$current_date' ";
            $previous_data          = "AND DATE(order_date) BETWEEN '$last_30_days_end' AND '$last_30_days_start' ";
            $get_order_totals       = $this->getDetails(ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_data");
            $get_total_orders_count = $this->getDetails(ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data");

             for ($i = 0; $i <= 6; $i++) 
            {
               $main_lable[] = "<div class='chart-label'>".date("d M", strtotime( date( 'Y-m-d' )." -$i days"))."</div>" ;
            }

        } elseif($input=='last_month') {
            $current_data           = "AND DATE(order_date) BETWEEN '$last_seven_days' AND '$current_date' ";
            $previous_data          = "AND DATE(order_date) BETWEEN '$previous_7_days' AND '$last_seven_days' ";
            $get_order_totals       = $this->getDetails(ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_data");
            $get_total_orders_count = $this->getDetails(ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data");

            for ($i = 0; $i <= 2; $i++) 
            {   
               $d = (($i==0)? 0 : (($i==1)? 15 : (($i==2)? 30 : "") )  ) ;
               $main_lable[] = "<div class='chart-label'>".date("d M, Y", strtotime( date( 'Y-m-d' )." -$d days"))."</div>" ;
            }

        }  elseif($input=='last_three_month') {
            $current_data           = "AND DATE(order_date) BETWEEN '$last_3_month_start' AND '$current_date' ";
            $previous_data          = "AND DATE(order_date) BETWEEN '$last_3_month_end' AND '$last_3_month_start' ";
            $get_order_totals       = $this->getDetails(ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_data");
            $get_total_orders_count = $this->getDetails(ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data");

            for ($i = 0; $i <= 2; $i++) 
            {
               $main_lable[] = "<div class='chart-label'>".date("F, Y", strtotime( date( 'Y-m-01' )." -$i months"))."</div>" ;
            }
        } 

        $result['main_lable'] = array_reverse($main_lable);

        $q     = "SELECT total_amount FROM ".ORDER_TBL." WHERE deliver_status='0' $current_data  ORDER BY order_date ASC ";
        $exe   = $this->exeQuery($q);
        $current_data_total_sales = array();

        $count = mysqli_num_rows($exe)==0 ? 1: mysqli_num_rows($exe);

        while($list = mysqli_fetch_array($exe)){
            $current_data_total_sales[] = $list['total_amount']/$count;
        }

        $current_data = implode(",", $current_data_total_sales);
        $result[0]    = $current_data;

        $q = "SELECT total_amount FROM ".ORDER_TBL." WHERE deliver_status='0' $previous_data   ORDER BY order_date ASC ";
        $exe               = $this->exeQuery($q);
        $previous_data_records   = array();

        while($list = mysqli_fetch_array($exe)){
            $previous_data_records[] = $list['total_amount']/$count;
        }

        $previous_data = implode(",", $previous_data_records);
        $result[1]     = $previous_data;
        $result[2]     = number_format($get_order_totals['totalValues']/$count,2);

        return json_encode($result);
    }

    // Data for Total orders (count) chart

    function getTotalOrders($input)
    {
        $result = array();

        $current_date           = date("Y-m-d");
        $last_seven_days        = date("Y-m-d", strtotime("-7 days", strtotime($current_date)));
        $previous_7_days        = date("Y-m-d", strtotime("-7 days", strtotime($last_seven_days)));
        $last_30_days_start     = date("Y-m-d", strtotime("-30 days", strtotime($current_date)));
        $last_30_days_end       = date("Y-m-d", strtotime("-30 days", strtotime($last_30_days_start)));
        $last_3_month_start     = date("Y-m-d", strtotime("-90 days", strtotime($current_date)));
        $last_3_month_end       = date("Y-m-d", strtotime("-90 days", strtotime($last_3_month_start)));

        if($input=='today_records') {
            $current_data           = "AND DATE(order_date)='$current_date' ";
            $previous_data          = "AND DATE(order_date)='".date("Y-m-d", strtotime("-1 days", strtotime($current_date)))."' ";
            $get_order_totals       = $this->getDetails(ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_data");
            $get_total_orders_count = $this->getDetails(ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data");

             for ($i = 0; $i <= 2; $i++) 
            {
               $main_lable[] = "<div class='chart-label'>".date("d M", strtotime( date( 'Y-m-d' )." -$i days"))."</div>" ;
            }

        } elseif($input=='last_seven_days') {
            $current_data           = "AND DATE(order_date) BETWEEN '$last_30_days_start' AND '$current_date' ";
            $previous_data          = "AND DATE(order_date) BETWEEN '$last_30_days_end' AND '$last_30_days_start' ";
            $get_order_totals       = $this->getDetails(ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_data");
            $get_total_orders_count = $this->getDetails(ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data");

             for ($i = 0; $i <= 6; $i++) 
            {
               $main_lable[] = "<div class='chart-label'>".date("d M", strtotime( date( 'Y-m-d' )." -$i days"))."</div>" ;
            }

        } elseif($input=='last_month') {
            $current_data           = "AND DATE(order_date) BETWEEN '$last_seven_days' AND '$current_date' ";
            $previous_data          = "AND DATE(order_date) BETWEEN '$previous_7_days' AND '$last_seven_days' ";
            $get_order_totals       = $this->getDetails(ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_data");
            $get_total_orders_count = $this->getDetails(ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data");

            for ($i = 0; $i <= 2; $i++) 
            {   
               $d = (($i==0)? 0 : (($i==1)? 15 : (($i==2)? 30 : "") )  ) ;
               $main_lable[] = "<div class='chart-label'>".date("d M, Y", strtotime( date( 'Y-m-d' )." -$d days"))."</div>" ;
            }

        }  elseif($input=='last_three_month') {
            $current_data           = "AND DATE(order_date) BETWEEN '$last_3_month_start' AND '$current_date' ";
            $previous_data          = "AND DATE(order_date) BETWEEN '$last_3_month_end' AND '$last_3_month_start' ";
            $get_order_totals       = $this->getDetails(ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_data");
            $get_total_orders_count = $this->getDetails(ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data");

            for ($i = 0; $i <= 2; $i++) 
            {
               $main_lable[] = "<div class='chart-label'>".date("F, Y", strtotime( date( 'Y-m-01' )." -$i months"))."</div>" ;
            }
        } 

        $result['main_lable'] = array_reverse($main_lable);


        $q = "SELECT date(order_date), COUNT(id) as totalOrders, SUM(total_amount) as orderDailyTotal FROM ".ORDER_TBL." WHERE  deliver_status='0' $current_data GROUP BY 1 ";
        $exe   = $this->exeQuery($q);
        $current_data_total_sales = array();
        $count = mysqli_num_rows($exe)==0 ? 1: mysqli_num_rows($exe);

        while($list = mysqli_fetch_array($exe)){
            $current_data_total_sales[] = $list['totalOrders'];
        }

        $current_data = implode(",", $current_data_total_sales);
        $result[0]=$current_data;

        $q   = "SELECT date(order_date), COUNT(id) as totalOrders, SUM(total_amount) as orderDailyTotal FROM ".ORDER_TBL." WHERE  deliver_status='0' $previous_data GROUP BY 1 ";
        $exe = $this->exeQuery($q);
        $previous_data_records   = array();

        while($list = mysqli_fetch_array($exe)){
            $previous_data_records[] =    $list['totalOrders'];
        }
        
        $previous_data = implode(",", $previous_data_records);
        $result[1]     = $previous_data;
        $result[2]     = $get_total_orders_count['totalOrders'];

        return json_encode($result);
    }

    /*-------------------------------------
            Order Invoice for admin
    -------------------------------------*/

    // Get  total values of orders 

    function manageInvoiceItems($order_id="",$vendor_id="",$invoice_for="")
    {
        $layout = "";
        $query = "SELECT I.id,I.qty,I.product_id,I.order_id,I.vendor_id,I.variant_id,I.price as prize,I.total_tax,I.sub_total,I.final_price,I.qty,I.size,I.total_amount,I.sgst,I.cgst,I.igst,I.sgst_amt,I.cgst_amt,O.coupon_code,O.order_address,O.coupon_id,I.coupon_value,O.order_uid,P.product_name,V.variant_name,VD.state_id,VD.state_name,I.vendor_commission,I.vendor_commission_tax,I.vendor_payment_charge,I.vendor_payment_tax,I.vendor_shipping_charge,I.vendor_shipping_tax,O.shipping_value,O.shipping_tax_value,O.shipping_tax,O.shipping_cost,
                    SUM(I.igst_amt) as totalTax,
                    SUM(I.tax_amt) as subTotal,
                    SUM(I.total_amount) as totalAmount,
                    SUM(I.coupon_value) as couopnValue,
                    SUM(I.vendor_commission_amt) as totalCommission,
                    SUM(I.vendor_payment_charge_amt) as totalPayment,
                    SUM(I.vendor_shipping_charge_amt) as totalShipping,
                    SUM(I.vendor_commission_tax_amt) as vendor_commission_tax_amt,
                    SUM(I.vendor_payment_tax_amt) as vendor_payment_tax_amt,
                    SUM(I.vendor_shipping_tax_amt) as vendor_shipping_tax_amt
            FROM  ".ORDER_ITEM_TBL." I 
                    LEFT JOIN ".PRODUCT_TBL." P ON(P.id=I.product_id) 
                    LEFT JOIN ".ORDER_TBL." O ON (O.id=I.order_id) 
                    LEFT JOIN ".PRODUCT_VARIANTS." V ON (I.variant_id=V.id) 
                    LEFT JOIN ".VENDOR_TBL." VD ON (VD.id=I.vendor_id) 
            WHERE I.order_id='$order_id' AND I.vendor_id='$vendor_id' AND I.vendor_accept_status='1' ORDER BY I.price*I.qty LIMIT 1 ";

        $exe = $this->exeQuery($query);

        if(mysqli_num_rows($exe)>0){
            $i = 1;
            while ($details = mysqli_fetch_array($exe)) {
                $list       = $this->editPagePublish($details);

                $total_tax          = $list['totalTax'] * $list['qty'] ;
                $name               = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;
                $coupon             = $list['coupon_id']!=0 ? $list['coupon_code'] : "-";
                $coupon_value       = $list['coupon_id']!=0 ? $list['couopnValue'] : "0.00";
                $coupon_value_int   = (($list['coupon_value']!="")? $list['coupon_value'] : 0 );

                $layout .='
                     '.$this->getInvoiceItems($order_id,$vendor_id,$invoice_for).'
                     ';
                if($list['coupon_id']!="" && $list['coupon_id']!=0){
                    $coupon_info = "( Coupon Code : ".$coupon." )"; 
                } else {
                    $coupon_info = ""; 
                }
                
                $layout .='
                        <tr style="background: #F5F5F5;">
                            <td colspan="5" style="text-align: right;border-top: 1px solid #222;"><strong>Subtotal</strong></td>
                            <td colspan="2" style="text-align: right;border-top: 1px solid #222;">Rs. '.number_format($list["subTotal"] + $list["totalTax"] ,2).'</td>
                        </tr>
                        <tr style="background: #F5F5F5;">
                            <td colspan="5" style="text-align: right;"><strong>Discount</strong> <br> '.$coupon_info.' </td>
                            <td colspan="2" style="text-align: right;">Rs. '.$this->inrFormat( $coupon_value_int ,2).'</td>
                        </tr>
                        ';

                if($invoice_for=="vendor_invoice") {

                    $net_payable       = $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];
                    $payable           = $list["totalAmount"] - $net_payable;
                    $commission_wo_tax = $list['totalCommission'] - $list['vendor_commission_tax_amt'];
                    $payment_wo_tax    = $list['totalPayment'] - $list['vendor_payment_tax_amt'];
                    $shipping_wo_tax   = $list['totalShipping'] - $list['vendor_shipping_tax_amt'];

                    $layout .= '<tr style="background: #F5F5F5;">
                                    <td colspan="5" style="text-align: right;"><strong>Zupply Commission </strong> <br>  ('.$list['vendor_commission'].' % Commission + '.$list['vendor_commission_tax'].' % Tax) </td>
                                    <td colspan="2" style="text-align: right;">Rs. '.number_format($list['totalCommission'],2).'<br> ('.$this->inrFormat($commission_wo_tax).' + '.$this->inrFormat($list['vendor_commission_tax_amt']).') </td>
                                </tr>

                                <tr style="background: #F5F5F5;">
                                    <td colspan="5" style="text-align: right;"><strong>Payment Gateway Charge</strong> <br>  ('.$list['vendor_payment_charge'].' % Charge + '.$list['vendor_payment_tax'].' % Tax) </td>
                                    <td colspan="2" style="text-align: right;">Rs. '.number_format($list['totalPayment'],2).'<br> ('.$this->inrFormat($payment_wo_tax).' + '.$this->inrFormat($list['vendor_payment_tax_amt']).') </td>
                                </tr>

                                <tr style="background: #F5F5F5;">
                                    <td colspan="5" style="text-align: right;"><strong>Shipping Charge</strong> <br>  ('.$list['vendor_shipping_charge'].' % Charge + '.$list['vendor_shipping_tax'].' % Tax) </td>
                                    <td colspan="2" style="text-align: right;">Rs. '.number_format($list['totalShipping'],2).'<br> ('.$this->inrFormat($shipping_wo_tax).' + '.$this->inrFormat($list['vendor_shipping_tax_amt']).') </td>
                                </tr>
                                <tr style="background: #F5F5F5;">
                                    <td colspan="5" style="text-align: right;"><strong>GRAND TOTAL</strong></td>
                                    <td colspan="2" style="text-align: right;"><strong>Rs. '.number_format($list["subTotal"] + $list["totalTax"]-$coupon_value_int,2).'</strong></td>
                                </tr>
                                <tr style="background: #F5F5F5;">
                                    <td colspan="5" style="text-align: right;"><strong>Total Charge</strong></td>
                                    <td colspan="2" style="text-align: right;"><strong>Rs. - '.number_format($list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'],2).' </strong></td>
                                </tr>
                                <tr style="background: #F5F5F5;">
                                    <td colspan="3" style="border-bottom: 1px solid #222;border-top: 1px solid #5D6975;">Total Amount in Words: <strong>'.$this->amountInWords($list["subTotal"] + $list["totalTax"]-$coupon_value_int - $net_payable).'</strong></td>
                                    <td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;"><strong>Vendor Payable</strong></td>
                                    <td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;;"><strong>Rs. '.number_format($list["subTotal"] + $list["totalTax"]-$coupon_value_int - $net_payable,2).'</strong></td>
                                </tr>';
                } 


                if($invoice_for!="vendor_invoice") {
                    $layout .='
                        <tr style="background: #F5F5F5;">
                            <td colspan="5" style="text-align: right;"><strong>Shipment Charges</strong> (shipping charge : Rs.'.number_format($list["shipping_value"],2).' + SGST : '.($list["shipping_tax"]/2).'% (Rs.'.number_format($list['shipping_tax_value']/2,2).') + CGST : '.($list["shipping_tax"]/2).'% (Rs.'.number_format($list['shipping_tax_value']/2,2).'))</td>

                            <td colspan="2" style="text-align: right;">Rs. '.number_format($list["shipping_cost"],2).'</td>
                        </tr>
                        <tr style="background: #F5F5F5;">
                            <td colspan="3" style="border-bottom: 1px solid #222;border-top: 1px solid #5D6975;">Total Amount in Words: <strong>'.$this->amountInWords($list["subTotal"] + $list["shipping_cost"] + $list["totalTax"]-$coupon_value_int).'</strong></td>
                            <td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;"><strong>GRAND TOTAL</strong></td>
                            <td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;;"><strong>Rs. '.number_format($list["subTotal"] + $list["shipping_cost"] + $list["totalTax"]-$coupon_value_int,2).'</strong></td>
                        </tr>
                        ';  
                }
                

            $i++;
            }
        }
        return $layout;
    }

    // Get order items in a order

    function getInvoiceItems($order_id="",$vendor_id="")
    {
        $layout = "";
        $query = "SELECT I.id,I.qty,I.product_id,I.order_id,I.variant_id,I.price as prize,I.total_tax,I.sub_total,I.final_price,I.qty,I.size,I.total_amount,I.sgst,I.cgst,I.igst,I.sgst_amt,I.cgst_amt,I.igst_amt,I.tax_type,O.order_address,O.order_uid,P.product_name,V.variant_name,O.order_address,I.vendor_id,VD.state_id,VD.state_name,PU.product_unit 
                FROM  ".ORDER_ITEM_TBL." I LEFT JOIN ".PRODUCT_TBL." P ON(P.id=I.product_id) 
                                           LEFT JOIN ".VENDOR_TBL." VD ON (VD.id=I.vendor_id) 
                                           LEFT JOIN ".ORDER_TBL." O ON (O.id=I.order_id) 
                                           LEFT JOIN ".PRODUCT_VARIANTS." V ON (I.variant_id=V.id)  
                                           LEFT JOIN ".PRODUCT_UNIT_TBL." PU ON (PU.id=P.product_unit)  
                WHERE I.vendor_accept_status='1' AND I.order_id='$order_id' AND I.vendor_id='$vendor_id' ORDER BY I.price*I.qty   DESC ";
        $exe = $this->exeQuery($query);
        if(mysqli_num_rows($exe)>0){
            $i = 1;
            while ($details = mysqli_fetch_array($exe)) {
                $list  = $this->editPagePublish($details);
                $background = ($i %2 == 0)? "background: #F5F5F5;": "";

                $total_tax = $list['total_tax'] * $list['qty'] ;
                $igst_Amt  = number_format($list['igst_amt'],2);
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
                        <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$this->publishContent($list['qty']).' '.$product_unit.' </td>
                        <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$tax_info.'</td>
                        <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">Rs.'.$igst_Amt.'</td>
                        <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: right;">Rs.'.number_format($list['sub_total'],2).'</td>
                        </tr>
                     
                      ';
            $i++;
            }
        }
        return $layout;
    }

    // Datas for card box in order reports page

    function getOrderReportCardData() 
    {
        $result = array();
        $q  = "SELECT id,
                    (SELECT COUNT(id) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND DATE(order_date)=CURDATE() ) as totalOrders,
                    (SELECT COUNT(id) FROM ".ORDER_TBL." WHERE order_status='3' AND DATE(order_date)=CURDATE() ) as returnedOrders,
                    (SELECT SUM(total_amount) FROM ".ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND DATE(order_date)=CURDATE()  ) as totalAmount
               FROM ".ORDER_TBL."  WHERE 1 ";
        $exe  = $this->exeQuery($q);
        $list = mysqli_fetch_array($exe);
        return $list;
    }


/*-----------Dont'delete---------*/

}


?>




