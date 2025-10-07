<?php

class Customersettings extends Controller
{
	
	public function index()
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Product');
			$check 				= $user->checkPermissionPage(CUSTOMERS);
			$check_sitesettings = $user->checkSiteSettings(CUSTOMERS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/customersettings', 
					[	
						'active_menu' 	=>  'customers',
						'page_title'	=>  'Manage Customer',
						'scripts'		=>	'settings',
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
			$user = $this->model('Admin');
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
