<?php

class city extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(STATE_CITY_PINCODE);
			$check_sitesettings = $user->checksitesettings(STATE_CITY_PINCODE);
			if ($check==1 ) {
				$this->view('home/citylist', 
					[	
						'active_menu' 		 => 'settings',
						'page_title'		 => 'Manage City',
						'scripts'			 => 'settings',
						'page_menu'			 => 'city',
						'states'			 => $user->getState(),
						'user_info'			 => $user->userInfo(),
						'csrf_add_city'  	 => $user->getCSRF("add_city"),
						'csrf_edit_city' 	 => $user->getCSRF("edit_city"),
						'csrf_edit_pincode_status' 	 => $user->getCSRF("edit_pincode_status"),
						'list'				 =>	$user->manageCity(),
						'sitesettings'		 =>	$user->filtersiteSettings(),
						'notification'   	 => $user->getOrderNotification()
					]);
			} else {
				$this->view('home/error', 
					[	
						'active_menu' 	=>  '',
						'page_title'	=>	'404 - Page Not Found',
						'scripts'		=>	'error',
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersiteSettings(),
						'notification'  => $user->getOrderNotification()
					]);
			}
		} else {
			$this->view('home/login',[]);
		}
	}

	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Settings');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'addCity':
					echo $user->addCity($_POST);
				break;
				case 'updateCity':
					echo $user->editCity($_POST);
				break;
				case 'statusCity':
					echo $user->changeCityStatus($post);
				break;
				case 'infoCity':
					echo $user -> getCityDetails($post);
				break;
				case 'deleteCity':
					echo $user->deleteCity($post);
				break;
				case 'trash':
					echo $user->trashCity($post);
				break;
				case 'restore':
					echo $user->restoreCity($post);
				break;
				case 'updatePincode':
					echo $user->updatePincode($_POST);
				break;
				case 'getPincodeInfo':
					echo json_encode($user-> getPincodeInfo($post));
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
					'notification'  => $user->getOrderNotification()
				]);
		}else{
			$this->view('home/login',[]);
		}
	}

}


?>