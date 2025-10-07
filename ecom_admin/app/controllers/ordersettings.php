<?php

class Ordersettings extends Controller
{
	
	public function index()
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(ORDERS);
			$check_sitesettings = $user->checkSiteSettings(ORDERS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/ordersettings', 
					[	
						'active_menu' 	=>  'ordersettings',
						'page_title'	=>  'Manage Orders',
						'scripts'		=>	'product',
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
