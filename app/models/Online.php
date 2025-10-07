<?php

require_once 'Model.php';
require_once 'global/global-config.php';
require_once 'Cart.php';
require_once 'app/core/classes/PHPMailerAutoload.php';
//require_once('app/razorpay-php/Razorpay.php');
require_once('razorpay-php/Razorpay.php');
require_once 'config/config.php';

use Razorpay\Api\Api;

class Online extends Model
{

	function export(){
		$route 		= new Cart();
		
		if (isset($_SESSION['user_session_id'])) {
	
			//Use your key_id and key secret
			$api = new Api(PAYMENT_GATEWAY_CLIENT_ID, PAYMENT_GATEWAY_CLIENT_SECRECT);

			//This is submited by the checkout form
			if (isset($_POST['razorpay_payment_id']) === false)
			{
			    //die("Payment id not provided");
			    //echo"<script>window.location='".BASEPATH."'</script>";
			    header("location:".BASEPATH,  true,  302);
			    exit;

			}
			$id 			= $_POST['razorpay_payment_id'];
			$user_cart_id 	= $_SESSION['user_cart_id'];
			$info 			= $route->getDetails(CART_TBL,"sub_total,total_tax,total_amount,coupon_value,shipping_cost","id='$user_cart_id'");
			
			$amount_in_ps 	= ($info['sub_total']+$info['total_tax']+$info['shipping_cost']-$info['coupon_value'])*100;

			if (IS_MERCHANT_VENPEP=="enabled") {
				$payment 		= $api->payment->fetch($id)->capture(array('amount'=>$amount_in_ps, 'account_id' => RAZORPAY_ACCOUNT_ID ));
			}else{
				$payment 		= $api->payment->fetch($id)->capture(array('amount'=>$amount_in_ps));
			}


			// Convert Response to Arrray
			$json = (json_encode($payment->toArray()));
			$data = $payment->toArray();
			//print_r($data);
			$payment = $route->fullOnlinePayment($data,$json,$amount_in_ps);
			//echo($payment);
			//exit;
			if ($payment) {
				//echo"<script>window.location='".BASEPATH."cart/details?s=success'</script>";
				header("location: ".BASEPATH."cartdetails/details?u=success",  true,  301 );  
			    exit;
			}else{
				//echo"<script>window.location='".BASEPATH."'</script>";
				header("location: ".BASEPATH."cartdetails/details?u=success",  true,  301 );  
			    exit;
			}
			//Payment is captured, do whatever else you need to do
			// Mark order as done using the submitted hidden field
			$shopping_order_id = $_POST['shopping_order_id'];
			// markOrderAsDone($shopping_order_id);
			//echo $shopping_order_id;
		}else{
			//echo"<script>window.location='".BASEPATH."'</script>";
			header("location: ".BASEPATH."",  true,  301 );  
			exit;
		}
	}






}?> 