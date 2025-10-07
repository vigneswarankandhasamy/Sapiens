<?php

class Locationgroup extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(LOCATIONS);
			$check_sitesettings = $user->checkSiteSettings(LOCATIONS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/managelocationgroup', 
					[	
						'active_menu' 				=> 'settings',
						'page_title'				=> 'Location Group',
						'scripts'					=> 'settings',
						'locationgroup_menu'		=> 'locationgroup',
						'location_menu'				=> '',
						'csrf_add_location_group' 	=>  $user->getCSRF("add_location_group"),
						'csrf_edit_location_group' 	=>  $user->getCSRF("edit_location_group"),
						'list'						=>  $user->manageLocationGroup(),	
						'city'						=>  $user->getCitylist(),	
						'user_info'					=>  $user->userInfo(),
						'sitesettings'				=>	$user->filtersiteSettings(),
						'notification'  			=>  $user->getOrderNotification()

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
					echo $user->addLocationGroup($_POST);
				break;
				case 'update':
					echo $user->editLocationGroup($_POST);
				break;
				case 'info':
					echo $user->getLocationGroupItemDetails($post);
				break;
				case 'status':
					echo $user->changeLocationGroupStatus($post);
				break;
				case 'delete':
					echo $user->deleteLocationGroup($post);
				break;
				case 'restore':
					echo $user->restoreLocationGroup($post);
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