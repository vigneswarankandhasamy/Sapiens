<?php

class emailsettings extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user   			= $this->model('Settings');
			$check  			= $user->checkPermissionPage(BLOG);
			$count  			= $user->check_query(EMAIL_SETTINGS_TBL,"*","1");
			$check_sitesettings = $user->checkSiteSettings("emailsettings");
			$info 				= $user->getDetails(EMAIL_SETTINGS_TBL,"*"," id='1'  "); 
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/manageemailsetting', 
					[	
						'active_menu' 		 		=> 'settings',
						'page_title'		 		=> 'Email Settings',
						'scripts'			 		=> 'settings',
						'user_info'			 		=>  $user->userInfo(),
						'count'						=>  $count,
						'csrf_edit_email_settings' 	=>  $user->getCSRF("edit_email_settings"),
						'info'						=>  $info,
						'sitesettings'				=>	$user->filtersiteSettings(),
						'notification' 			    =>  $user->getOrderNotification()
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
			$post  = @$_POST["result"];	
			switch ($type) {
				case 'updateEmailSettings':
					echo $user->editEmail($_POST);
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