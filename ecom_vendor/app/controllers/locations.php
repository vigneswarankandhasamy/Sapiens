<?php

class locations extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 		= $this->model('Admin');
			// $check = $user->checkPermissionPage(DASHBOARD);
			$check = 1;

			if ($check==1) {
				$this->view('home/deliverylocations', 
					[	
						'active_menu' 	      => 'locations',
						'page_title'	      => 'Vendor Locations',
						'scripts'		      => 'dashboard',
						'locations'   	      => $user->locationsDatas(),
						'csrf_add_locations'  => $user->getCSRF("add_locations"),
						'user_info'		      => $user->userInfo(),
						'notification'  	  =>  $user->getOrderNotification(),
						'vendor_active' 	  =>  $user->CheckVendorStatus()
					]);
			}else{

			}
		}else{
			$this->view('home/login',[]);
		}
		
	}

	public function api($type)
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user  = $this->model('Admin');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'vendorDeliveryLoction':
					echo $user->updateVendorLocations($_POST);	
				break;

				default:
				break;
			}
		}
	}

	public function error()
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user = $this->model('Admin');
			$this->view('home/error', 
				[	
					'active_menu' 	=>  '',
					'page_title'	=>	'404 - Page Not Found',
					'scripts'		=>	'error',
					'user_info'		=>	$user->userInfo()
				]);
		}else{
			$this->view('home/login',[]);
		}
	}

}

?>
