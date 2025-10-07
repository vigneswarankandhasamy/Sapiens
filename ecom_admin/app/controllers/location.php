<?php

class Location extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Settings');
			$check = $user->checkPermissionPage(LOCATIONS);
			$check_sitesettings = $user->checkSiteSettings(LOCATIONS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/managelocation', 
					[	
						'active_menu' 				=> 'settings',
						'page_title'				=> 'Location',
						'scripts'					=> 'settings',
						'location_menu'				=> 'location',
						'locationgroup_menu'		=>  '',
						'csrf_add_location' 		=>  $user->getCSRF("add_location"),
						'csrf_edit_location' 		=>  $user->getCSRF("edit_location"),
						'location_group' 			=>  $user->getlocationGroup(),
						'city'						=>  $user->getCitylist(),	
						'list'						=>  $user->manageLocation(),	
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
				case 'addlocation':
					echo $user->addLocation($_POST);
				break;
				case 'locationstatus':
					echo $user->changeLocationStatus($post);
				break;
				case 'locationinfo':
					echo $user->getLocationItemDetails($post);
				break;
				case 'locationupdate':
					echo $user->editLocation($_POST);
				break;
				case 'locationdelete':
					echo $user->deleteLocation($post);
				break;
				case 'locationrestore':
					echo $user->restoreLocation($post);
				break;
				case 'locationgropList':
					echo $user->locationgropList($post);
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