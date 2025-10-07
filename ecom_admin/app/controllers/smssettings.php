<?php

class smssettings extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user   			= $this->model('Settings');
			$check  			= $user->checkPermissionPage(BLOG);
			$count  			= $user->check_query(SMS_SETTINGS_TBL,"*","1");
			$info    			= $user->getDetails(SMS_SETTINGS_TBL,"*"," id='1'  ");
			$check_sitesettings = $user->checksitesettings("smssettings"); 
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/managesmssetting', 
					[	
						'active_menu' 		 		=> 'settings',
						'page_title'		 		=> 'SMS Settings',
						'scripts'			 		=> 'settings',
						'user_info'			 		=>  $user->userInfo(),
						'sms_menu'					=> 'sms_settings',
						'count'						=>  $count,
						'info'						=>  $info,
						'csrf_edit_sms_settings' 	=>  $user->getCSRF("edit_sms_settings"),
						'sitesettings'				=>	$user->filtersiteSettings(),
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

	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Settings');
			$post  = @$_POST["result"];	
			switch ($type) {
				case 'updateSmsSettings':
					echo $user->editSms($_POST);
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