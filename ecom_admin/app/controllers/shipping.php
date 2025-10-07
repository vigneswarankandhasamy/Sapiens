<?php

class shipping extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Settings');
			$check = $user->checkPermissionPage(SHIPPING_DELIVERY);
			$check_sitesettings = $user->checkSiteSettings(SHIPPING_DELIVERY);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/manageshippingdelivery', 
					[	
						'active_menu' 				=> 'settings',
						'page_title'				=> 'Shipping & Delivery',
						'scripts'					=> 'settings',
						'list'						=>  $user->manageShippingDelivery(),	
						'user_info'					=>  $user->userInfo(),
						'csrf_edit_shipping'		=>	$user->getCSRF('csrf_edit_shipping'),
						'csrf_edit_pincode_status' 	=>  $user->getCSRF("edit_pincode_status"),
						'sitesettings'				=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification()
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
				
				case 'updatePincode':
					echo $user->updatePincode($_POST);
				break;
				case 'pincodeDeliveryStatus':
					echo $user->updatePincodeDeliveryStatus($_POST);
				break;
				case 'getPincodeInfo':
					echo json_encode($user-> getPincodeInfo($post));
				break;
				case 'getPincodeAccordion':
					echo json_encode($user-> getPincodeAccordion($post));
				break;
				case 'activeState':
					echo $user-> activeState($post);
				break;
				case 'activeCity':
					echo $user-> activeCity($post);
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