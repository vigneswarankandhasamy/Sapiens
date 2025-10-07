<?php

class Cartdetails extends Controller
{
	public function index()
	{		

		$user = $this->model('Cart');
		if(isset($_SESSION["user_session_id"])){
			if(isset($_SESSION['user_cart_id'])){
	        	$info = $user->getDetails(CART_TBL,"*"," id='".$_SESSION['user_cart_id']."' ");
				$inr_format  = $user->inrFormatFields($info);
	        	$payment_total   	= $info['total_amount']-$info['coupon_value'];
	        	$check_address 		= $user->check_query(CUSTOMER_ADDRESS_TBL,"id","user_id = '".@$_SESSION['user_session_id']."' AND id= '".$info['shipping_id']."' AND delete_status='0' ");
	        	$count 				= $user->check_query(CART_ITEM_TBL,"id","user_id = '".@$_SESSION['user_session_id']."' AND cart_id='".$_SESSION['user_cart_id']."' ");
				$seo_info = $user->getDetails(SEO_TBL,"*","page_token='cartdetails'");

				$this->view('home/cart/index',
					[	
						'active_menu' 		  => 'index',
						'meta_title'  		  =>  $seo_info['meta_title'].' | '.COMPANY_NAME,
						'meta_keywords'  	  =>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
						'meta_description' 	  =>  $seo_info['meta_description'].' | '.COMPANY_NAME,
						'meta'  			  => 'dynamic',
						'info'				  =>  $info,
						'inr_format'		  =>  $inr_format,	
						'payment_total' 	  =>  $payment_total,
						'items'				  =>  $count,
						'products' 			  =>  $user->cartProducts(), 
						'cart'   			  =>  $user->cartInfo(),
						'coupons'			  =>  $user->couponlist(),
						'header_cart' 		  =>  $user->headercartProducts(),
						'vendor_status_check' =>  $user->cartItemVendorStatusCheck($info['id']),
						'legal_pages'		  =>  $user->getLegalPages(),
						'menu_items'		  =>  $user->menuItems(),
						'siteinfo' 			  =>  $user->siteInfo($page_token="cartdetails"),
						'location' 			  =>  $user->getLocationList(),
						'user'   			  =>  $user->userInfo()	
					]);
			}else{	
				$user = $this->model('Front');
			  	$this->view('home/emptycart', 
				    [ 
				     	'meta_title'  		=>  'Your shopping cart is empty.',
						'meta_keywords'  	=>  'Your shopping cart is empty.',
						'meta_description'  =>  'Your shopping cart is empty.',
						'meta' 				=>  'static',
						'cart'   			=>  $user->cartInfo(),
						'legal_pages'		=>  $user->getLegalPages(),
						'menu_items'		=>  $user->menuItems(),
						'siteinfo' 			=>  $user->siteInfo(),
						'location' 			=>  $user->getLocationList(),
						'user'   			=>  $user->userInfo()	
				    ]);
			}	
				
		} else {

			$user = $this->model('User');
			$this->view('home/login', 
			[	
				'active_menu' 		=> 'login',
				'meta_title'  		=> 'User Login',
				'meta_keywords'  	=> 'User Login',
				'meta_description'  => 'User Login',
				'meta' 				=>  'static',
				'cart'   			=>  $user->cartInfo(),
				'location' 			=>  $user->getLocationList(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo()
				
			]);

		}
	}	


	// address page
	public function address()
	{		
		$user    = $this->model('Cart');
		if(isset($_SESSION["user_session_id"])){
			if(isset($_SESSION['user_cart_id'])){
				if(!isset($_SESSION['new_shipping_address_key'])){
					$_SESSION['new_shipping_address_key'] = $user->generateRandomString("40");
				}
				if(!isset($_SESSION['online_method_key'])){
	                $_SESSION['online_method_key'] = $user->generateRandomString("40");
	            }
	            $info 			= $user->getDetails(CART_TBL,"*"," id='".$_SESSION['user_cart_id']."' ");
				$inr_format  	= $user->inrFormatFields($info);
	        	$payment_total  = $info['total_amount']-$info['coupon_value'] + $info['shipping_cost'];

	        	$ship_address_info 	= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*","user_id = '".@$_SESSION['user_session_id']."' AND id= '".$info['shipping_id']."' AND delete_status='0' ");

	        	if($ship_address_info!=NULL){
		        	$check_address = $user->shipAddressCheck($ship_address_info['city_id'],$ship_address_info['area_id'],$_SESSION['user_cart_id']);
	        	} else {
		        	$check_address = $user->shipAddressCheck("","",$_SESSION['user_cart_id']);
	        	}
	        	
		        $count = $user->check_query(CART_ITEM_TBL,"id","user_id = '".@$_SESSION['user_session_id']."' AND cart_id='".$_SESSION['user_cart_id']."' ");
				$seo_info = $user->getDetails(SEO_TBL,"*","page_token='cartaddress'");

				$this->view('home/cart/address', 
					[	
						'active_menu' 		  => 'address',
						'meta_title'  		  =>  $seo_info['meta_title'].' | '.COMPANY_NAME,
						'meta_keywords'  	  =>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
						'meta_description' 	  =>  $seo_info['meta_description'].' | '.COMPANY_NAME,
						'meta'  			  => 'dynamic',
						'info'				  =>  $info,	
						'inr_format'		  =>  $inr_format,	
						'items'				  =>  $count,
						'payment_total' 	  =>  $payment_total,
						'count' 			  =>  $user->addressInfo(),
						'check_address'		  =>  $check_address,
						'items'				  =>  $count,
						'state_list' 		  =>  $user->getStatelist(),
						'location' 			  =>  $user->getLocationlist(),
						'header_cart' 		  =>  $user->headercartProducts(),
						'legal_pages'		  =>  $user->getLegalPages(),
						'menu_items'		  =>  $user->menuItems(),
						'siteinfo' 			  =>  $user->siteInfo($page_token="cartaddress"),
						'vendor_status_check' =>  $user->cartItemVendorStatusCheck($info['id']),
						'ship_address_info'   =>  $ship_address_info,
						'address' 			  =>  $user->manageCartNewShippingAddress($info['shipping_id']),
						'products' 		      =>  $user->cartProducts(),
						'cart'   			  =>  $user->cartInfo(),
						'user'   			  =>  $user->userInfo()	
					]);
			}else{	
				$user = $this->model('Front');
			  	$this->view('home/emptycart', 
				    [ 
				     	'meta_title'  		=>  '404 Error - Page Not Found',
						'meta_keywords'  	=>  '404 Error - Page Not Found',
						'meta_description'  =>  '404 Error - Page Not Found',
						'meta' 				=>  'static',
						'cart'   			=>  $user->cartInfo(),
						'legal_pages'		=>  $user->getLegalPages(),
						'menu_items'		=>  $user->menuItems(),
						'siteinfo' 			=>  $user->siteInfo(),
						'location' 			=>  $user->getLocationList(),
						'user'   			=>  $user->userInfo()	
				    ]);
			}
		} else {

			$user = $this->model('User');
			$this->view('home/login', 
			[	
				'active_menu' 		=> 'login',
				'meta_title'  		=> 'User Login',
				'meta_keywords'  	=> 'User Login',
				'meta_description'  => 'User Login',
				'meta' 				=>  'static',
				'cart'   			=>  $user->cartInfo(),
				'location' 			=>  $user->getLocationList(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo()
				
			]);

		}	
			
	}
	
	//  Order Confirmation
	public function orderconfirmation()
		{		
			$user = $this->model('Cart');
			if(isset($_SESSION["user_session_id"])){
				if(isset($_SESSION['user_cart_id'])){
					if(!isset($_SESSION['new_shipping_address_key'])){
						$_SESSION['new_shipping_address_key'] = $user->generateRandomString("40");
					}
					if(!isset($_SESSION['online_method_key'])){
						$_SESSION['online_method_key'] = $user->generateRandomString("40");
					}
					$info 			= $user->getDetails(CART_TBL,"*"," id='".$_SESSION['user_cart_id']."' ");
					$inr_format  	= $user->inrFormatFields($info);
					$payment_total  = $info['sub_total']+$info['total_tax']-$info['coupon_value'] + $info['shipping_cost'];
					$check_address 		= $user->check_query(CUSTOMER_ADDRESS_TBL,"id,city_id","user_id = '".@$_SESSION['user_session_id']."' AND id= '".$info['shipping_id']."' AND delete_status='0' ");
		        	$ship_address_info 	= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*","user_id = '".@$_SESSION['user_session_id']."' AND id= '".$info['shipping_id']."' AND delete_status='0' ");

		        	$check_address = $user->shipAddressCheck($ship_address_info['city_id'],$ship_address_info['area_id'],$_SESSION['user_cart_id']);

					$seo_info = $user->getDetails(SEO_TBL,"*","page_token='orderconfirmation'");
 						$this->view('home/cart/payment',
						[	
							'active_menu' 		  => 'orderconfirm',
							'meta_title'  		  =>  $seo_info['meta_title'].' | '.COMPANY_NAME,
							'meta_keywords'  	  =>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
							'meta_description' 	  =>  $seo_info['meta_description'].' | '.COMPANY_NAME,
							'meta'  			  => 'dynamic',
							'info'				  =>  $info,	
							'inr_format'		  =>  $inr_format,	
							'payment_total' 	  =>  $payment_total,
							'count' 			  =>  $user->addressInfo(),
							'check_address'		  =>  $check_address,
							'address' 			  =>  $user->manageCartNewShippingAddress($info['shipping_id']),
							'vendor_status_check' =>  $user->cartItemVendorStatusCheck($info['id']),
							'ship_address_info'   =>  $ship_address_info,
							'products' 			  =>  $user->cartProducts(),
							'cart'   			  =>  $user->cartInfo(),
							'legal_pages'		  =>  $user->getLegalPages(),
							'location' 			  =>  $user->getLocationList(),
							'menu_items'		  =>  $user->menuItems(),
							'siteinfo' 			  =>  $user->siteInfo($page_token="orderconfirmation"),
							'header_cart' 		  =>  $user->headercartProducts(),
							'user'   			  =>  $user->userInfo()	
						]);

				}else{	
					$user = $this->model('Front');
					$this->view('home/emptycart', 
						[ 
							'meta_title'  		  =>  $seo_info['meta_title'].' | '.COMPANY_NAME,
							'meta_keywords'  	  =>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
							'meta_description' 	  =>  $seo_info['meta_description'].' | '.COMPANY_NAME,
							'meta' 				=>  'static',
							'cart'   			=>  $user->cartInfo(),
							'location' 			=>  $user->getLocationList(),
							'legal_pages'		=>  $user->getLegalPages(),
							'menu_items'		=>  $user->menuItems(),
							'siteinfo' 			=>  $user->siteInfo(),
							'header_cart' 		=>  $user->headercartProducts(),
							'user'   			=>  $user->userInfo()	
						]);
				}
			} else {

				$user = $this->model('User');
				$this->view('home/login', 
				[	
					'active_menu' 		=> 'login',
					'meta_title'  		=> 'User Login',
					'meta_keywords'  	=> 'User Login',
					'meta_description'  => 'User Login',
					'meta' 				=>  'static',
					'cart'   			=>  $user->cartInfo(),
					'location' 			=>  $user->getLocationList(),
					'legal_pages'		=>  $user->getLegalPages(),
					'menu_items'		=>  $user->menuItems(),
					'siteinfo' 			=>  $user->siteInfo()
					
				]);

			}	
					
		}

	public function details()
    {
        $user = $this->model('Cart');
        if(isset($_SESSION['user_session_id'])){
        	$info          = $user->getDetails(ORDER_TBL,"*"," user_id='".$_SESSION['user_session_id']."' ORDER BY id DESC LIMIT 1 ");
        	$items         = $user->check_query(ORDER_ITEM_TBL,"id", " order_id='".$info['id']."'  ");
        	$order_address = $info['order_address'];
        	$ship_address  = $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," user_id='".$_SESSION['user_session_id']."' AND id='$order_address' ");

			$seo_info = $user->getDetails(SEO_TBL,"*","page_token='orderdetails'");
            $this->view('home/orderdetails', 
                [   
                    'active_menu'   	=> 'orderdetails',
                    'meta_title'  		=>  $seo_info['meta_title'].' | '.COMPANY_NAME,
					'meta_keywords'  	=>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
					'meta_description' 	=>  $seo_info['meta_description'].' | '.COMPANY_NAME,
					'meta'  			=> 'dynamic',
                    'info'				=>  $info,
                    'items'				=>  $items,
                    'ship'				=>  $ship_address,
                    'order_items'		=>  $user->ManageOrderItems($info['id']),
					'cart'   			=>  $user->userCartInfo(),
					'location' 			=>  $user->getLocationList(),
					'legal_pages'		=>  $user->getLegalPages(),
					'menu_items'		=>  $user->menuItems(),
					'siteinfo' 			=>  $user->siteInfo($page_token="orderdetails"),
                    'user'          	=>  $user->userInfo($_SESSION["user_session_id"])
                ]);
        }else{
        	$user = $this->model('User');
              $this->view('home/login', 
                [ 
                    'page_title'     	=> 'Customer Login',
					'legal_pages'		=>  $user->getLegalPages(),
					'location' 			=>  $user->getLocationList(),
					'menu_items'		=>  $user->menuItems(),
					'siteinfo' 			=>  $user->siteInfo(),
					'cart'   		 	=>  $user->cartInfo()
                ]);
        }
    }

    public function online()
	{	
		if(isset($_SESSION['user_session_id'])){
			$user 	= $this->model('Online');
			$user->export();
		}else{
			$user = $this->model('User');
        	$this->view('home/login', 
            [ 
                'page_title'     	=> 'Customer Login',
				'legal_pages'		=>  $user->getLegalPages(),
				'location' 			=>  $user->getLocationList(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo(),
				'cart'   		 	=>  $user->cartInfo()
            ]);
		}
	}

    
    public function api($type)
	{
			$user  = $this->model('Cart');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'updateCartQuantity':
					$new_qty = @$_REQUEST['qty'];
					echo $user -> updateCartQuantity($post,$new_qty);
				break;
				case 'decrementCartQuantity':
					$new_qty = @$_REQUEST['qty'];
					echo $user -> decrementCartQuantity($post,$new_qty);
				break;

				case 'updateNewPrdQty':
					$new_qty      = $_POST['prd_qty'];
					$cart_item_id = $user->encryptData($_POST['cart_item']);
					echo $user -> updateCartQuantity($cart_item_id,$new_qty);
				break;
				case 'cartQuantityValue':
					$new_qty = @$_REQUEST['qty'];
					echo $user -> cartQuantityValue($post,$new_qty);
				break;
				case 'cartItemRemove':
					echo $user -> cartItemRemove($post);
				break;
				case 'couponCodeCheck':
					echo $user -> couponCodeCheck($_POST);
			    break;

			    case 'couponCodeCheckValid':
					echo $user -> couponCodeCheckValid($post);
			    break;
				case 'removeCoupon':
					echo $user -> removeCoupon($post);
				break;
				case 'addToFavourite':
					echo $user -> addToFavourite($post);
				break;
				case 'getLocationsForGroup':
					$data = $user->getLocationArealist($post);
					echo json_encode($data);
				break;
				case 'removeMyFav':
					echo $user -> removeMyFav($post);
				break;
				case 'removeShippingAddress':
					echo $user -> removeShippingAddress($post);
				break;
				case 'editAddress':
					echo $user -> editAddress($post);
				break;

				case 'editAddressPopup':
					echo $user -> editAddressPopup($post);
				break;

				case 'editCartShippingAddress':
					echo $user -> editCartShippingAddress($_POST);
				break;
				case 'addCartShippingAddress':
					echo $user -> addCartShippingAddress($_POST);
				break;
				case 'makeDefaultShippingAddress':
					echo $user -> makeDefaultShippingAddress($post);
				break;

				case 'payment_method':
					echo $user -> payment_method($post);
				break;
				
				case 'cashOndelivery':
					echo $user -> cashOndelivery($post);
				break;

				case 'onlinepayment':
					echo $user -> razorPayment($post);
				break;

				// case 'vendorInvoice':
				// echo $user -> vendorInvoice(1,1);
			 	// break;
			  	// venpep/zupply_ecom/cartdetails/api/vendorInvoice

				default:
				break;
		}
	}
	
	public function error()
	{
		$user = $this->model('User');
		$this->view('home/error', 
			[	
				'meta_title'  		=>  '404 Error - Page Not Found',
				'meta_keywords'  	=>  '404 Error - Page Not Found',
				'meta_description'  =>  '404 Error - Page Not Found',
				'meta'				=> 'static',
				'cart'   			=>  $user->cartInfo(),
				'location' 			=>  $user->getLocationList(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo(),
				'page_title'  		=>  '404 Error - Page Not Found'
			]);
	}






}
?>