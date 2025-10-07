<?php

class pincode extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(STATE_CITY_PINCODE);
			$check_sitesettings = $user->checksitesettings(STATE_CITY_PINCODE);
			if ($check==1 ) {
				$this->view('home/pincodelist', 
					[	
						'active_menu' 		 => 'settings',
						'page_title'		 => 'Manage Pincode',
						'scripts'			 => 'settings',
						'page_menu'			 => 'pincode',
						'cities'			 => $user->getCity(),
						'user_info'			 => $user->userInfo(),
						'csrf_add_pincode'   => $user->getCSRF("add_pincode"),
						'list'				 =>	$user->managePincode(),
						'sitesettings'		 =>	$user->filtersiteSettings(),
						'notification'  	 => $user->getOrderNotification()
					]);
			} else {
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
				case 'addPincode':
					echo $user->addPincode($_POST);
				break;
				case 'statusPincode':
					echo $user->changePincodeStatus($post);
				break;
				case 'infoPincode':
					echo json_encode($user -> getPincodeDetails($post));
				break;
				case 'deletePincode':
					echo $user->deletePincode($post);
				break;
				case 'trash':
					echo $user->trashPincode($post);
				break;
				case 'restore':
					echo $user->restorePincode($post);
				break;
				case 'updatePincode':
					echo $user->updatePincode($_POST);
				break;

				case 'getCity':
					echo $user->getCityAccordion($post);
				break;

				case 'getPincode':
					echo $user->getPincodeAccordion($post);
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