<?php
ob_start();
//These should be commented out in production
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
session_start();
require_once '../global/global-config.php';
require_once '../app/models/Model.php';
require_once('../app/razorpay-php/Razorpay.php');
//require_once '../app/core/user_ajaxcontroller.php';
require_once '../app/models/Cart.php';
require_once '../app/core/classes/PHPMailerAutoload.php';
require_once '../config/config.php';
//$this = new Ajaxcontroller();
$route 		= new Cart();


use Razorpay\Api\Api;

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
	$info 			= $route->getDetails(CART_TBL,"sub_total,total_tax,total_amount,coupon_value","id='$user_cart_id'");
	$amount_in_ps 	= ($info['sub_total']+$info['total_tax']-$info['coupon_value'])*100;

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
		header("location: ".BASEPATH."cartdetails/details",  true,  301 );  
	    exit;
	}else{
		//echo"<script>window.location='".BASEPATH."'</script>";
		header("location: ".BASEPATH."cartdetails/details",  true,  301 );  
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
ob_end_flush();
?>