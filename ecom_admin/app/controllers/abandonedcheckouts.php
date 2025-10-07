<?php

class abandonedcheckouts extends Controller
{
	
	public function index($from="",$to="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(ABANDONED_CHECKOUTS);
			$check_sitesettings = $user->checkSiteSettings(ABANDONED_CHECKOUTS);
			if ($check==1 && $check_sitesettings==1) {
					$this->view('home/abandonedcheckouts', 
					[	
						'active_menu' 	=> 'customers',
						'page_title'	=> 'Abandoned Checkouts',
						'scripts'		=>	'error',
						'from_date'		=>  $from,
						'to_date'		=>  $to,
						'list'			=>  $user->manageAbandonedCheckouts($from,$to),
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification()
					]);
				
			} else {
				$this->view('home/error', 
						[	
							'active_menu' 	=>  '',
							'page_title'	=>	'404 - Page Not Found',
							'scripts'		=>	'error',
							'user_info'		=>	$user->userInfo(),
							'sitesettings'	=>	$user->filtersiteSettings(),
							'notification'  =>  $user->getOrderNotification()
						]);
				}
		}else{
				$this->view('home/login',[]);
		}
	}

	public function details($token)
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(ABANDONED_CHECKOUTS);
			$check_sitesettings = $user->checkSiteSettings(ABANDONED_CHECKOUTS);
			$details 			= $user->check_query(CART_TBL,"id"," id='$token' ");
			if ($check==1 && $check_sitesettings==1 && $details) {
				$cart_info   	= $user->getDetails(CART_TBL,"*"," id='$token' ");
				$cart_user_info = $user->getDetails(CUSTOMER_TBL,"*"," id=".$cart_info['user_id']." ");
				$inr_format     = $user->inrFormatFields($cart_info);
				$item_total     = $user->getDetails(CART_ITEM_TBL,"SUM(sub_total + total_tax ) as total","cart_id='".$cart_info['id']."' ");
				$coupon_total   = $user->getDetails(CART_ITEM_TBL,"SUM(COALESCE(coupon_value, 0)) as total","cart_id='".$cart_info['id']."' ");
				$cart_total    = $item_total['total'];
				$coupon_total   = $coupon_total['total'];
			
				$this->view('home/abandonedcheckoutsdetails', 
					[	
						'active_menu' 			=> 'customers',
						'page_title'			=> 'Cart Details',
						'cart_info'				=> $cart_info,
						'inr_format'   			=> $inr_format,
						'cart_user_info'		=> $cart_user_info,
						'cart_total'			=> $cart_total,
						'coupon_total'			=> $coupon_total,
						'cart_items' 			=> $user->getCartItems($cart_info['id']),
						'scripts'				=> 'error',
						'user_info'				=> $user->userInfo(),
						'sitesettings'			=> $user->filtersiteSettings(),
						'notification'      	=> $user->getOrderNotification()
					]);
				
			}else{
				$this->view('home/error', 
						[	
							'active_menu' 	=>  '',
							'page_title'	=>	'404 - Page Not Found',
							'scripts'		=>	'error',
							'user_info'		=>	$user->userInfo(),
							'sitesettings'	=>	$user->filtersiteSettings(),
							'notification'  =>  $user->getOrderNotification()
						]);
				}
		}else{
				$this->view('home/login',[]);
		}
	}


	public function error()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user = $this->model('Order');
			$this->view('home/error', 
				[	
					'active_menu' 	=>  '',
					'page_title'	=>	'404 - Page Not Found',
					'scripts'		=>	'error',
					'user_info'		=>	$user->userInfo(),
					'sitesettings'	=>	$user->filtersiteSettings(),
					'notification'  =>  $user->getOrderNotification()
				]);
		}else{
			$this->view('home/login',[]);
		}
	}

}

?>
