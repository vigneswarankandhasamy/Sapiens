<?php

class filter extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check = $user->checkPermissionPage(HIRE_PROFILE);
			$check_sitesettings = $user->checkSiteSettings(HIRE_PROFILE);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/filterlist', 
					[	
						'active_menu' 				=> 'settings',
						'page_title'				=> 'Filter',
						'scripts'					=> 'settings',
						'user_info'					=>  $user->userInfo(),
						'csrf_add_filter_group' 	=>  $user->getCSRF("add_filter_group"),
						'csrf_edit_filter_group'	=>  $user->getCSRF("edit_filter_group"),
						'filter_groups'				=>  $user->getFilterGroupsDropDown(),
						'list'						=>	$user->manageFilter(),
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
					echo $user->addFilter($_POST);
				break;
				case 'update':
					echo $user->editFilter($_POST);
				break;
				case 'info':
					echo $user->getFilterDetails($post);
				break;
				case 'status':
					echo $user->changeFilterStatus($post);
				break;
				case 'delete':
					echo $user->deleteFilter($post);
				break;
				case 'restoreFilter':
					echo $user->restoreFilter($post);
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