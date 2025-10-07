<?php

class Locationcity extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(LOCATIONS);
			$check_sitesettings = $user->checkSiteSettings(LOCATIONS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/managelocationcity', 
					[	
						'active_menu' 				=> 'settings',
						'page_title'				=> 'City',
						'scripts'					=> 'settings',
						'locationgroup_menu'		=> 'locationcity',
						'location_menu'				=> '',
						'csrf_add_location_group' 	=>  $user->getCSRF("add_location_group"),
						'csrf_edit_location_group' 	=>  $user->getCSRF("edit_location_group"),
						'list'						=>  $user->manageLocationCity(),	
						'state'						=>  $user->getStatelist(),	
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
					echo $user->addLocationCity($_POST);
				break;
				case 'update':
					echo $user->editLocationCity($_POST);
				break;
				case 'info':
					echo $user -> getLocationCityItemDetails($post);
				break;
				case 'status':
					echo $user->changeLocationCityStatus($post);
				break;
				case 'delete':
					echo $user->deleteLocationCity($post);
				break;
				case 'restore':
					echo $user->restoreLocationCity($post);
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