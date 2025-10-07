<?php

class enquiry extends Controller
{
	public function contact()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('MarketingModel');
			$check 				= $user->checkPermissionPage(CONTACT_ENQIRY);
			$check_sitesettings = $user->checksitesettings(CONTACT_ENQIRY);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/managecontactenquiry', 
				[	
					'active_menu' 	=> 'marketing',
			        'page_title'    => 'Manage Contact Enquiry',
			        'meta_title'    => 'Manage Contact Enquiry - '.COMPANY_NAME,
			        'scripts' 		=>  'datatable',
					'user_info'		=>  $user->userInfo(),
					'sitesettings'	=>	$user->filtersiteSettings(),
					'notification'  =>  $user->getOrderNotification(),
			        'list' 			=>  $user->manageContactEnquiry(),
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
						'notification'  =>  $user->getOrderNotification(),
					]);
			}
		}else{
			$this->view('home/login',[]);
		}		
	}

	public function contactdetails($token="")
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('MarketingModel');
			$check 				= $user->checkPermissionPage(CONTACT_ENQIRY);
			$check_sitesettings = $user->checksitesettings(CONTACT_ENQIRY);
			if($check==1 && $check_sitesettings==1) {
				$check = $user->check_query(CONTACT_US_TBL,"id"," id='$token' ");
		        if($check){
		            $info = $user->getDetails(CONTACT_US_TBL,"*"," id='$token'  ");
	   		 		$this->view('home/viewcontactenquiry', 
					[	
						'active_menu' 		=> 'marketing',	
						'meta_title'		=> 'View Contact Enquiry - '.COMPANY_NAME,
						'page_title'  		=> 'View Contact Enquiry',
						'info'				=>	$info,
						'token'				=>	$user->encryptData($token),
						'sitesettings'		=>	$user->filtersiteSettings(),
						'notification'  	=>  $user->getOrderNotification(),
						'user_info'		 	=>  $user->userInfo(),
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

	public function workwithus()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('MarketingModel');
			$check 				= $user->checkPermissionPage(WORK_WITH_US_REQUEST);
			$check_sitesettings = $user->checksitesettings(WORK_WITH_US_REQUEST);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/manageworkwithus', 
				[	
					'active_menu' 	=> 'marketing',
			        'page_title'    => 'Manage Work With Us Request',
			        'meta_title'    => 'Manage Work With Us Request - '.COMPANY_NAME,
			        'scripts' 		=>  'datatable',
					'user_info'		=>  $user->userInfo(),
					'sitesettings'	=>	$user->filtersiteSettings(),
					'notification'  =>  $user->getOrderNotification(),
			        'list' 			=>  $user->manageWorkWithUsRequest(),
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
						'notification'  =>  $user->getOrderNotification(),
					]);
			}
		}else{
			$this->view('home/login',[]);
		}		
	}

	public function newsletter()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('MarketingModel');
			$check 				= $user->checkPermissionPage(NEWSLETTER);
			$check_sitesettings = $user->checksitesettings(NEWSLETTER);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/managenewsletter', 
				[	
					'active_menu' 	=>  'marketing',
			        'page_title'    =>  'Manage Newsletter',
			        'meta_title'    =>  'Manage Newsletter - '.COMPANY_NAME,
			        'scripts' 		=>  'datatable',
					'user_info'		=>  $user->userInfo(),
					'sitesettings'	=>	$user->filtersiteSettings(),
					'notification'  =>  $user->getOrderNotification(),
			        'list' 			=>  $user->manageNewsLetter()
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
			$user = $this->model('MarketingModel');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'trashContactEnquiry':
					echo $user->trashContactEnquiry($post);
				break;
				case 'trashNewsLetter':
					echo $user->trashNewsLetter($post);
				break;
				case 'info':
					echo $user->getContactDetails($post);
				break;
				case 'restoreNewsletter':
					echo $user->restoreNewsletter($post);
				break;

				case 'workWithUsInfo':
					echo $user->getWorkWithUsRequestDetails($post);
				break;
				case 'trashWorkWithUsRequest':
					echo $user->trashWorkWithUsRequest($post);
				break;
				case 'restoreWorkWithUsRequest':
					echo $user->restoreWorkWithUsRequest($post);
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