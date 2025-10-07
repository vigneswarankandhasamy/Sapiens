<?php

class settings extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Admin');
			$check 				= $user->checkPermissionPage(SETTINGS);
			$check_sitesettings = $user->checksitesettings(SETTINGS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/managesettings', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'General Settings',
						'scripts'		=> 'settings',
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
						'sitesettings'	=>	$user->filtersitesettings(),
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