<?php

class company extends Controller
{
	
	public function profile()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(COMPANY_PROFILE);
			$check_sitesettings = $user->checkSiteSettings(COMPANY_PROFILE);
			if ($check==1 && $check_sitesettings==1) {
				$info 		= $user->getDetails(COMPANNY_INFO,"*"," id='1'  ");
				$state_info = $user->getDetails(COMPANNY_INFO,"*"," id='1'  ");
				$this->view('home/companyinfo', 
					[	
						'active_menu' 	 	 	=> 'settings',
						'page_title'	 	 	=> 'Manage Company Profile',
						'scripts'		 	 	=> 'settings',
						'info'			 	 	=> $info,
						'user_info'		 	 	=> $user->userInfo(),
						'state'		         	=> $user->getState($info['registered_state_id']),
						'payment_gateway_tax' 	=> $user->getPaymentTax($info['vendor_payment_tax']),
						'shipping_tax'		 	=> $user->getShippingTax($info['vendor_shipping_tax']),
						'order_shipping_tax'	=> $user->getOrderShippingTax($info['order_shipping_tax']),
						'sitesettings'	     	=> $user->filtersiteSettings(),
						'notification' 			=> $user->getOrderNotification(),
						'csrf_edit_profile'  	=> $user->getCSRF("edit_company_profile"),

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
	public function branch()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(COMPANY_PROFILE);
			$check_sitesettings = $user->checkSiteSettings(COMPANY_PROFILE);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/branchlist', 
					[	
						'active_menu' 	 => 'settings',
						'page_title'	 => 'Manage Branch',
						'scripts'		 => 'settings',
						'user_info'		 =>  $user->userInfo(),
						'list'			 =>	 $user->manageBranch(),
						'sitesettings'	 =>	 $user->filtersiteSettings(),
						'notification'   =>  $user->getOrderNotification()
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

	public function addbranch()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(COMPANY_PROFILE);
			$check_sitesettings = $user->checkSiteSettings(COMPANY_PROFILE);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/addbranch', 
					[	
						'active_menu' 	   => 'settings',
						'page_title'	   => 'Add Branch',
						'scripts'		   => 'add_branch',
						'state'		       => $user->getState(),
						'user_info'		   => $user->userInfo(),
						'csrf_add_branch'  => $user->getCSRF("add_branch"),
						'sitesettings'	   => $user->filtersiteSettings(),
						'notification'    =>  $user->getOrderNotification()
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

	
	public function editbranch($token="")
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(COMPANY_PROFILE);
			$details 			= $user->check_query(BRANCH_TBL,"id"," id='$token' ");
			$check_sitesettings = $user->checkSiteSettings(COMPANY_PROFILE);
			if ($details==1 && $check_sitesettings==1) {
				$info = $user->getDetails(BRANCH_TBL,"*"," id='$token'  ");
				$this->view('home/editbranch', 
					[	
						'active_menu' 	   => 'store',
						'page_title'	   => 'Edit Branch',
						'scripts'		   => 'editbranch',
						'info'			   => $info,
						'state'		       => $user->getState($info['registered_state_id']),
						'token'			   => $user->encryptData($info['id']),
						'user_info'		   => $user->userInfo(),
						'csrf_edit_branch' => $user->getCSRF("edit_branch"),
						'sitesettings'	   => $user->filtersiteSettings(),
						'notification'     =>  $user->getOrderNotification()
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 		=>  '',
					'page_title'		=>	'404 - Page Not Found',
					'scripts'			=>	'error',
					'user_info'			=>	$user->userInfo(),
					'sitesettings'		=>	$user->filtersiteSettings(),
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
				case 'updateCompanyInfo':
					$upload = new FileUploader();
					$file   = $upload->uploadCompanyLogo($_FILES, $_POST);
					echo $user->updateCompanyInfo($_POST,$file);
				break;
				case 'add':
					$upload = new FileUploader();
					$file = $upload->uploadCompanyLogo($_FILES, $_POST);
					echo $user->addBranch($_POST,$file);
				break;
				case 'update':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->editBranch($_POST,$file);
				break;
				case 'status':
					echo $user->changeBranchStatus($post);
				break;
				case 'delete':
					echo $user->deleteBranch($post);
				break;
				case 'trash':
					echo $user->trashBranch($post);
				break;
				case 'publish':
					echo $user->publishBranch($post);
				break;
				case 'restore':
					echo $user->restoreBranch($post);
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