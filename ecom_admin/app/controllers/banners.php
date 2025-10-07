<?php

class Banners extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Admin');
			$check 				= $user->checkPermissionPage(HOME_BANNER);
			$check_sitesettings = $user->checkSiteSettings(HOME_BANNER);
				if ($check==1 && $check_sitesettings==1) {
					$this->view('home/managebanners', 
					[	
						'active_menu' 	=> 'customers',
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification()

					]);
				}else{
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
				}
		}else{
			$this->view('home/login',[]);
		}
	}
	

	public function details()
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Admin');
			$check 				= $user->checkPermissionPage(HOME_BANNER);
			$check_sitesettings = $user->checkSiteSettings(HOME_BANNER);
				if ($check==1 && $check_sitesettings==1) {
					$this->view('home/customerdetails', 
						[	
							'active_menu' 	=> 'customers',
						]);
				}else{
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