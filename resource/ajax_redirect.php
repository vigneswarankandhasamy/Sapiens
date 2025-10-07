<?php 
session_start();
//require_once '../config/config.php';
require_once '../app/core/ajaxcontroller.php';
$route = new Ajaxcontroller();

$page = @$_REQUEST["page"];
$data = @$_REQUEST["element"];
$term = @$_REQUEST["term"];
$post = @$_POST["result"];


switch($page){

	/*------------------------
		 	Search
	--------------------------*/
	
	case 'searchProperty':
	    $type 		= $_POST['type'];
	    $status 	= $_POST['status'];
	    $location 	= $_POST['location'];
	    $price 		= $_POST['price'];
	    echo $type."`".$status."`".$location."`".$price;	
	break;	


	// Contact Form

	case 'userLogin':
		echo $route -> userLogin($_POST);
	break;

	case 'addToCart':
		echo $route -> addToCart($post,$_POST['price']);
	break;

	case 'homeAddToCart':
		echo $route -> homeAddToCart($post);
	break;

	case 'removeCartItem':
		echo $route -> removeCartItem($post);
	break;

	case 'addEnquiry':
		echo $route -> addEnquiry($_POST);
	break;

	case 'addWishlist':
		echo $route -> addWishlist($post);
	break;

	case 'removeWishlist':
		echo $route -> removeWishlist($post);
	break;

	case 'addReview':
		echo $route -> addReview($_POST);
	break;

	case 'viewedproducts':
		echo $route -> viewedproducts($post);
	break;

	case 'contactUs':
		echo $route -> contactUs($_POST);
	break;


	case 'filter_product_submit':
		$keyword 		= (($_POST['filter_keyword']!="") ? "&filter_keyword=".$_POST['filter_keyword'] : ""); 
		$keyword_type 	= (($_POST['filter_keyword_type']!="") ? "&filter_keyword_type=".$_POST['filter_keyword_type'] : ""); 
		$cat 			= (($_POST['filter_category']!="") ? "&filter_category=".$_POST['filter_category'] : "");
		$material 			= (($_POST['filter_material_type']!="") ? "&filter_material_type=".$_POST['filter_material_type'] : "");
		$product 			= (($_POST['filter_product_type']!="") ? "&filter_product_type=".$_POST['filter_product_type'] : "");
		$design 			= (($_POST['filter_design_type']!="") ? "&filter_design_type=".$_POST['filter_design_type'] : "");
		$finish 			= (($_POST['filter_finish_type']!="") ? "&filter_finish_type=".$_POST['filter_finish_type'] : "");


		$location 	= (($_POST['filter_location']!="") ? "&filter_location=".$_POST['filter_location'] : ""); 
		$year 		= (($_POST['filter_year']!="") ? "&filter_year=".$_POST['filter_year'] : "");
		$type 		= (($_POST['filter_type']!="") ? "&filter_type=".$_POST['filter_type'] : "");
		$min_price 	= (($_POST['filter_min_price']!="") ? "&filter_min_price=".$_POST['filter_min_price'] : "");
		$max_price 	= (($_POST['filter_filter_max_price']!="") ? "&filter_filter_max_price=".$_POST['filter_filter_max_price'] : "");
		echo $keyword.$keyword_type.$cat.$material.$product.$design.$finish.$location.$year.$type.$min_price.$max_price;
	break;


	
	/*-------------------------------------------------------------------------------------
								Test Mail
	---------------------------------------------------------------------------------------*/

	case 'sendMail':
		$sender = COMPANY_NAME;
		$sender_mail = "no_reply";
		$receiver = "koshik@webykart.com";
		$subject = "Reset Password Mail";
		$email_temp = "test1234567879";
		echo $route->send_mail($sender,$sender_mail,$receiver,$subject,$email_temp,$bcc="");
	break;


}
?>