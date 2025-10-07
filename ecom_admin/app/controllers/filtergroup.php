<?php

class filtergroup extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check = $user->checkPermissionPage(HIRE_PROFILE);
			$check_sitesettings = $user->checkSiteSettings(HIRE_PROFILE);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/filtergrouplist', 
					[	
						'active_menu' 				=> 'settings',
						'page_title'				=> 'Filter Group',
						'scripts'					=> 'settings',
						'user_info'					=>  $user->userInfo(),
						'csrf_add_filter_group' 	=>  $user->getCSRF("add_filter_group"),
						'csrf_edit_filter_group'	=>  $user->getCSRF("edit_filter_group"),
						'list'						=>	$user->manageFilterGroup(),
						'sitesettings'				=>	$user->filtersiteSettings(),
						'notification'   			=>  $user->getOrderNotification()
					]);
			}else{
				$this->view('home/error', 
						[	
							'active_menu' 	=>  'settings',
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
					echo $user->addFilterGroup($_POST);
				break;
				case 'update':
					echo $user->editFilterGroup($_POST);
				break;
				case 'info':
					echo $user->getFilterGroupDetails($post);
				break;
				case 'status':
					echo $user->changeFilterGroupStatus($post);
				break;
				case 'delete':
					echo $user->deleteFilterGroup($post);
				break;
				case 'restoreFilterGroup':
					echo $user->restoreFilterGroup($post);
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
					'active_menu' 	=>  'settings',
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