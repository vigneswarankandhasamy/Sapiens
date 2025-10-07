<?php

class Customers extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Customer');
			$check 				= $user->checkPermissionPage(CUSTOMERS);
			$check_sitesettings = $user->checkSiteSettings(CUSTOMERS);
				if ($check==1 && $check_sitesettings==1) {
					$this->view('home/managecustomer', 
						[	
							'active_menu' 	=> 'customers',
							'page_title'	=> 'Manage Customer',
							'scripts'		=> 'settings',
							'user_info'		=>	$user->userInfo(),
							'list'			=>	$user->manageCustomer(),
							'sitesettings'	=>	$user->filtersiteSettings(),
							'notification'  =>  $user->getOrderNotification()
						]);
				}else{
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
				}
		}else{
			$this->view('home/login',[]);
		}
	}
	
	public function details($token="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Customer');
			$check 				= $user->checkPermissionPage(CUSTOMERS);
			$check_sitesettings = $user->checkSiteSettings(CUSTOMERS);
			$check_user 		= $user->check_query(CUSTOMER_TBL,"id"," id='$token' ");
				if ($check==1 && $check_sitesettings==1 && $check_user==1) {
					$info 		= $user->getDetails(CUSTOMER_TBL,"*"," id='$token'  ");	
		            $address 	= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," user_id='$token' AND default_address='1' OR delete_status='0' AND status='1' LIMIT 1  ");
					$this->view('home/customerdetails', 
						[	
							'active_menu' 	=> 'customers',
							'page_title'	=> 'Customer Details',
							'scripts'		=> 'settings',
							'info'			=>	$info,
				        	'address_info'	=>  $address,
				        	'reviews'		=>  $user->customerViewReviews($info['id']),
							'list'			=>  $user->manageUserOrdersList($info['id']),
							'user_info'		=>	$user->userInfo(),
							'sitesettings'	=>	$user->filtersiteSettings(),
							'notification'  =>  $user->getOrderNotification()
						]);
				}else{
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
				}
		}else{
			$this->view('home/login',[]);
		}
	}


	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Customer');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'status':
					echo $user->changeCustomerStatus($post);
				break;
				case 'trash':
					echo $user->trashCustomer($post);
				break;
				case 'restore':
					echo $user->restoreCustomer($post);
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