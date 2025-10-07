<?php

class vendor extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Vendors');
			$check = $user->checkPermissionPage(VENDORS);
			$check_sitesettings = $user->checkSiteSettings(VENDORS);
			if ($check==1) {
				$this->view('home/vendorlist', 
					[	
						'active_menu' 	 => 'vendorsettings',
						'page_title'	 => 'Manage Vendor',
						'scripts'		 => 'settings',
						'user_info'		 => $user->userInfo(),
						'list'			 =>	$user->manageVendor(),
						'sitesettings'	 =>	$user->filtersiteSettings(),
						'notification'   => $user->getOrderNotification()
					]);
			}else{

			}
		}else{
			$this->view('home/login',[]);
		}
	}

	public function add()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Vendors');
			$check = $user->checkPermissionPage(VENDORS);
			$check_sitesettings = $user->checkSiteSettings(VENDORS);
			if ($check==1) {
				$this->view('home/addvendor', 
					[	
						'active_menu' 	   => 'vendorsettings',
						'page_title'	   => 'Add Vendor',
						'scripts'		   => 'addblog',
						'user_info'		   => $user->userInfo(),
						'locations'   	   => $user->locationsDatas(),
						'state_list'	   => $user->getStatelist(),
						'csrf_add_vendor'  => $user->getCSRF("add_vendor"),
						'vendor_location'  => $user->getVendorLocation(),
						'sitesettings'	   => $user->filtersiteSettings(),
						'notification'     => $user->getOrderNotification()
					]);
			} else {
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 	=>	'vendorsettings',
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

	
	public function edit($token="")
	{
			
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Vendors');
			$check = $user->checkPermissionPage(VENDORS);
			$details = $user->check_query(VENDOR_TBL,"id"," id='$token' ");
			$check_sitesettings = $user->checkSiteSettings(VENDORS);
			if ($details==1 && $check==1) {
				$info = $user->getDetails(VENDOR_TBL,"*"," id='$token'  ");
				$this->view('home/editvendor', 
					[	
						'active_menu' 		=> 'vendorsettings',
						'page_title'		=> 'Edit Vendor',
						'scripts'			=> 'editblog',
						'info'				=>  $info,
						'token'				=>  $user->encryptData($info['id']),
						'user_info'			=>  $user->userInfo(),
						'locations'   	 	=>  $user->locationsDatas($info['id']),
						'state_list'	    =>  $user->getStatelist($info['state_id']),
						'vendor_location'   =>  $user->getVendorLocation($info['delivery_location_groups']),
						'csrf_edit_vendor' 	=>  $user->getCSRF("edit_vendor"),
						'sitesettings'		=>	$user->filtersiteSettings(),
						'notification'      =>  $user->getOrderNotification()
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 	=>	'vendorsettings',
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
			$user  = $this->model('Vendors');
			$check = $user->checkPermissionPage(VENDORS);
			$check_sitesettings = $user->checkSiteSettings(VENDORS);
			$details = $user->check_query(VENDOR_TBL,"id"," token='$token' ");
			if ($details==1) {
				$info = $user->getDetails(VENDOR_TBL,"*"," token='$token'  ");
				$this->view('home/vendordetails', 
					[	
						'active_menu' 			=> 'vendorsettings',
						'page_title'			=> 'Vendor Details',
						'scripts'				=> 'editblog',
						'info'					=>  $info,
						'token'					=>  $user->encryptData($info['id']),
						'user_info'				=>  $user->userInfo(),
						'list'					=>  $user->manageVendorOrdersList(),
						'productlist' 	 		=>  $user->manageProductList($info['id']),
						'locatiomlist' 	 		=>  $user->vendorAssignedLocations($info['id']),
						'sitesettings'			=>	$user->filtersiteSettings(),
						'notification' 			=>  $user->getOrderNotification()
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 	=>  ' vendorsettings ',
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

	public function ratings()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Vendors');
			$check = $user->checkPermissionPage(VENDORS);
			$check_sitesettings = $user->checkSiteSettings(VENDORS);
			if ($check==1) {
				$this->view('home/vendorratinglist', 
					[	
						'active_menu' 	 => 'vendorsettings',
						'page_title'	 => 'Vendor Ratings',
						'scripts'		 => 'settings',
						'user_info'		 => $user->userInfo(),
						'list'			 =>	$user->vendorRatingList(),
						'sitesettings'	 =>	$user->filtersiteSettings(),
						'notification'   => $user->getOrderNotification()
					]);
			}else{

			}
		}else{
			$this->view('home/login',[]);
		}
	}

	public function expired()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Vendors');
			$check = $user->checkPermissionPage(VENDORS);
			$check_sitesettings = $user->checkSiteSettings(VENDORS);
			if ($check==1) {
				$this->view('home/vendorexpiredplist', 
					[	
						'active_menu' 	 => 'vendorsettings',
						'page_title'	 => 'Manage Expired Vendor',
						'scripts'		 => 'settings',
						'user_info'		 => $user->userInfo(),
						'list'			 =>	$user->manageExpiredVendorList(),
						'sitesettings'	 =>	$user->filtersiteSettings(),
						'notification'   => $user->getOrderNotification()
					]);
			}else{

			}
		}else{
			$this->view('home/login',[]);
		}
	}



	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Vendors');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'add':
					echo $user->addVendor($_POST);
				break;
				case 'update':
					echo $user->editVendor($_POST);
				break;
				case 'sendInvite':
					echo $user->sendInvite($post);
				break;
				case 'sendCredentials':
					echo $user->sendCredentials($post);
				break;
				case 'status':
					echo $user->changeVendorStatus($post);
				break;
				case 'delete':
					echo $user->deleteVendor($post);
				break;
				case 'trash':
					echo $user->trashVendor($post);
				break;
				case 'publish':
					echo $user->publishVendor($post);
				break;
				case 'restore':
					echo $user->restoreVendor($post);
				break;
				case 'vendorShort':
					echo $user->vendorShort($_POST);
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
					'active_menu' 	=>	'vendorsettings',
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