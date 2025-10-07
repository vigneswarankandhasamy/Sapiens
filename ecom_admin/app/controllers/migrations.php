<?php

class Migrations extends Controller
{
	
	public function index()
	{		
		if(isset($_SESSION["ecom_admin_id"])) {
			$check 				= $user->checkPermissionPage(MIGRATIONS);
			$check_sitesettings = $user->checkSiteSettings("migrations");
			if ($check==1 && $check_sitesettings==1) {
			$user = $this->model('Admin');
				$this->view('home/migrationslist', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=>	'Migrations',
						'scripts'		=>	'dashboard',
						'user_info'		=>	$user->userInfo(),
						'list'			=>	$user->migrationList(),
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

	public function add()
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$check 				= $user->checkPermissionPage(MIGRATIONS);
			$check_sitesettings = $user->checkSiteSettings("migrations");
			if ($check==1 && $check_sitesettings==1) {
				$user = $this->model('Admin');
				if(!isset($_SESSION['add_migrations'])){
					$_SESSION['add_migrations'] = $user->generateRandomString("40");
				}
				$this->view('home/addmigrations', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Add Migration',
						'scripts'		=> 'addmigrations',
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
