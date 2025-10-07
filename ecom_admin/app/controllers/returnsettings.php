<?php

class returnsettings extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 		        = $user->checkPermissionPage(RETURN_SETTINGS); 
			$check_sitesettings = $user->checksitesettings(RETURN_SETTINGS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/returnsettingslist', 
					[	
						'active_menu' 				=> 'settings',
						'page_title'				=> 'Return Settings',
						'scripts'					=> 'settings',
						'user_info'					=>  $user->userInfo(),
						'csrf_add_return_settings' 	=>  $user->getCSRF("add_return_settings"),
						'csrf_edit_return_settings'	=>  $user->getCSRF("edit_return_settings"),
						'list'						=>	$user->manageReturnSetting(),
						'sitesettings'				=>	$user->filtersiteSettings(),
						'notification' 				=>  $user->getOrderNotification()
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
				case 'add':
					echo $user->addReturnSetting($_POST);
				break;
				case 'update':
					echo $user->editReturnSetting($_POST);
				break;
				case 'info':
					echo json_encode($user->getReturnSettingDetails($post));
				break;
				case 'status':
					echo $user->changeReturnSettingStatus($post);
				break;
				case 'delete':
					echo $user->deleteReturnSetting($post);
				break;
				case 'restoreReturnSetting':
					echo $user->restoreReturnSetting($post);
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


	/*-----------Dont'delete---------*/

}


?>