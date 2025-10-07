<?php

class analytics extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(BLOG);
			$check_sitesettings = $user->checkSiteSettings("company");
			if ($check==1 && $check_sitesettings==1) {
				$info = $user->getDetails(ANALYTICS_TBL,"*"," id='1'  ");
				$this->view('home/manageanalytics', 
					[	
						'active_menu' 	 	 	=> 'settings',
						'page_title'	 	 	=> 'Tracking and Analytics',
						'scripts'		 	 	=> 'updateanalytics',
						'info'			 		=> $info,
						'user_info'		 	 	=> $user->userInfo(),
						'sitesettings'	 		=> $user->filtersiteSettings(),
						'csrf_update_analytics' => $user->getCSRF("update_analytics"),
						'notification'  		=> $user->getOrderNotification()


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
					echo $user->updateAnalytics($_POST);
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