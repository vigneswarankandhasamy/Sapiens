<?php

class profile extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('AdminProfile');
			$check = $user->checkPermissionPage(DASHBOARD);
			if ($check==1) {
				$this->view('home/profile', 
					[	
						'active_menu' 	 		=> 'profile',
						'page_title'	 		=> 'My Profile',
						'scripts'		 		=> 'settings',
						'user_info'		 		=>  $user->userInfo(),
						'csrf_update_profile'  	=>  $user->getCSRF("update_profile"),
						'sitesettings'			=>	$user->filtersiteSettings(),
						'notification' 			=>  $user->getOrderNotification()
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

	public function security()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user   = $this->model('AdminProfile');
			$check  = $user->checkPermissionPage(DASHBOARD);
			$u_info = $user->userInfo();
			if($u_info['password_update']==NULL) {
				$password_update = $u_info['created_at'];
			} else {
				$password_update = $u_info['password_update'];
			}
			if ($check==1) {
				$this->view('home/security', 
					[	
						'active_menu' 	 		=> 'security',
						'page_title'	 		=> 'My Profile',
						'scripts'		 		=> 'settings',
						'user_info'		 		=>  $u_info,
						'list'			 		=>	$user->manageloginActivity("LIMIT 4"),
						'time'			 		=>  $user->to_time_ago(time() - strtotime($password_update)),
						'csrf_change_password'  =>  $user->getCSRF("change_password"),
						'sitesettings'			=>	$user->filtersiteSettings(),
						'notification' 			=>  $user->getOrderNotification()
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


	public function loginactivity()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('AdminProfile');
			$check = $user->checkPermissionPage(DASHBOARD);
			if ($check==1) {
				$this->view('home/loginactivity', 
					[	
						'active_menu' 	 => 'security',
						'page_title'	 => 'My Profile',
						'scripts'		 => 'settings',
						'user_info'		 =>  $user->userInfo(),
						'list'			 =>	 $user->manageloginActivity("LIMIT 20"),
						'sitesettings'	 =>	 $user->filtersiteSettings(),
						'notification'   =>  $user->getOrderNotification()
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




	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Settings');
			$post = @$_POST["result"];	
			switch ($type) {
				
				case 'update':
					echo $user->updateAdminProfile($_POST);
				break;

				case 'changePassword':
					echo $user->changePasswordAdmin($_POST);
				break;
				
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