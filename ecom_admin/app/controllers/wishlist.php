<?php

class wishlist extends Controller
{
	
	public function index($from="",$to="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(CUSTOMER_WISHLIST);
			$check_sitesettings = $user->checkSiteSettings(CUSTOMER_WISHLIST);
			if ($check==1 && $check_sitesettings==1) {
					$this->view('home/wishlist', 
					[	
						'active_menu' 	=> 'customers',
						'page_title'	=> 'Customer Wishlist',
						'scripts'		=>	'error',
						'from_date'		=>  $from,
						'to_date'		=>  $to,
						'list'			=>  $user->manageUserWishlist($from,$to),
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification()
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

	public function details($token)
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(CUSTOMER_WISHLIST);
			$check_sitesettings = $user->checkSiteSettings(CUSTOMER_WISHLIST);
			$details 			= $user->check_query(CUSTOMER_TBL,"id"," id='$token' ");
			if ($check==1 && $check_sitesettings==1 && $details) {
				$customer_info  = $user->getDetails(CUSTOMER_TBL,"*"," id='$token' ");
			
				$this->view('home/wishlistdetails', 
					[	
						'active_menu' 			=> 'customers',
						'page_title'			=> 'Customer Wishlist Details',
						'customer_info'			=> $customer_info,
						'wishlist_items'		=> $user->getCartItems($cart_info['id']),
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
