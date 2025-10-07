<?php

class Productsettings extends Controller
{
	
	public function index()
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Product');
			$check 				= $user->checkPermissionPage(PRODUCT_MENU);
			$check_sitesettings = $user->checkSiteSettings(PRODUCT_MENU);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/productsettings', 
					[	
						'active_menu' 	=>  'productsettings',
						'page_title'	=>  'Manage Products',
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
