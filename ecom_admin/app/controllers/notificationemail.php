<?php

class notificationemail extends Controller
{

	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(NOTIFICATION_EMAILS);
			$check_sitesettings = $user->checkSiteSettings(NOTIFICATION_EMAILS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/notificationemaillist', 
					[	
						'active_menu' 		 => 'settings',
						'page_title'		 => 'Manage Notification Email',
						'scripts'			 => 'settings',
						'user_info'			 => $user->userInfo(),
						'csrf_add_blog_cat'  => $user->getCSRF("add_notification_email"),
						'csrf_edit_blog_cat' => $user->getCSRF("edit_notification_email"),
						'list'				 =>	$user->manageNotificationEmail(),
						'sitesettings'	 	 =>	$user->filtersiteSettings(),
						'notification'  	 =>  $user->getOrderNotification()
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
				case 'addNotificationEmail':
					echo $user->addNotificationEmail($_POST);
				break;
				case 'updateNotificationEmail':
					echo $user->editNotificationEmail($_POST);
				break;
				case 'infoNotificationEmail':
					echo $user->getNotificationEmailDetails($post);
				break;
				case 'statusNotificationEmail':
					echo $user->changeNotificationEmailStatus($post);
				break;
				case 'deleteNotificationEmail':
					echo $user->deleteNotificationEmail($post);
				break;
				case 'restore':
					echo $user->restoreNotificationEmail($post);
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