<?php

class vendorsettings extends Controller
{
	
	public function index()
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(VENDORS_MENU);
			$check_sitesettings = $user->checkSiteSettings(VENDORS_MENU);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/vendorsettings', 
					[	
						'active_menu' 	=>  'vendorsettings',
						'page_title'	=>  'Manage Vendors',
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
