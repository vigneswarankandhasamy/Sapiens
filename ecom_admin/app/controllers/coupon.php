<?php

class coupon extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(BRAND);
			$check_sitesettings = $user->checkSiteSettings(BRAND);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/couponlist', 
					[	
						'active_menu' 	 => 'productsettings',
						'page_title'	 => 'Manage Coupons',
						'scripts'		 => 'settings',
						'user_info'		 => $user->userInfo(),
						'list'			 =>	$user->manageCoupon(),
						'sitesettings'	 =>	$user->filtersiteSettings(),
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

	public function add()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(BRAND);
			$check_sitesettings = $user->checkSiteSettings(BRAND);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/addcoupon', 
					[	
						'active_menu' 	   => 'productsettings',
						'page_title'	   => 'Add Coupon',
						'scripts'		   => 'addblog',
						'location_group'   => $user->getlocationGroup(),
						'categories' 	   => $user->getCategories(),
						'products' 		   => $user->getproducts(),
						'user_info'		   => $user->userInfo(),
						'sitesettings'	   => $user->filtersiteSettings(),
						'notification'     => $user->getOrderNotification(),
						'csrf_add_coupon'  => $user->getCSRF("add_coupon")
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

	
	public function edit($token="")
	{
			
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(BRAND);
			$details 			= $user->check_query(COUPON_TBL,"id"," id='$token' ");
			$check_sitesettings = $user->checkSiteSettings(BRAND);
			if ($details==1 && $check_sitesettings==1 && $check==1) {
				$info = $user->getDetails(COUPON_TBL,"*"," id='$token'  ");
				$this->view('home/editcoupon', 
					[	
						'active_menu' 	   => 'productsettings',
						'page_title'	   => 'Edit Coupon',
						'scripts'		   => 'editblog',
						'info'			   => $info,
						'token'			   => $user->encryptData($info['id']),
						'location_group'   => $user->getlocationGroup($info['free_shipping_location']),
						'categories' 	   => $user->getCategories($info['specific_category']),
						'products' 		   => $user->getproducts($info['specific_product']),
						'user_info'		   => $user->userInfo(),
						'sitesettings'	   => $user->filtersiteSettings(),
						'notification'     => $user->getOrderNotification(),
						'csrf_edit_coupon' => $user->getCSRF("edit_coupon")
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 		=>  '',
					'page_title'		=>	'404 - Page Not Found',
					'scripts'			=>	'error',
					'user_info'			=>	$user->userInfo(),
					'sitesettings'		=>	$user->filtersiteSettings(),
					'notification'  	=>  $user->getOrderNotification()

				]);
			}
		}else{
			$this->view('home/login',[]);
		}
	}


	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Store');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'add':
					echo $user->addCoupon($_POST);
				break;
				case 'edit':
					echo $user->editCoupon($_POST);
				break;
				case 'status':
					echo $user->changeCouponStatus($post);
				break;
				case 'delete':
					echo $user->deleteCoupon($post);
				break;
				case 'trash':
					echo $user->trashCoupon($post);
				break;
				case 'publish':
					echo $user->publishCoupon($post);
				break;
				case 'restore':
					echo $user->restoreCoupon($post);
				break;
				default:
				break;
			}
		}
	}
	public function error()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user = $this->model('Admin');
			$this->view('home/error', 
				[	
					'active_menu' 		=>  '',
					'page_title'		=>	'404 - Page Not Found',
					'scripts'			=>	'error',
					'user_info'			=>	$user->userInfo(),
					'sitesettings'		=>	$user->filtersiteSettings(),
					'notification'		=>  $user->getOrderNotification()

				]);
		}else{
			$this->view('home/login',[]);
		}
	}

}


?>