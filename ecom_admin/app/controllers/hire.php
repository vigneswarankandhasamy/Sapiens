<?php

class hire extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Classified');
			$check = $user->checkPermissionPage(HIRE);
			$check_sitesettings = $user->checkSiteSettings(HIRE);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/contractorslist', 
					[	
						'active_menu' 	 => 'hiresettings',
						'page_title'	 => 'Manage Expert',
						'scripts'		 => 'settings',
						'user_info'		 => $user->userInfo(),
						'list'			 =>	$user->manageClassified(),
						'sitesettings'	 =>	$user->filtersiteSettings(),
						'notification'   => $user->getOrderNotification()
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 	=>  ' hiresettings ',
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

	public function add()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Classified');
			$check = $user->checkPermissionPage(HIRE);
			$check_sitesettings = $user->checkSiteSettings(HIRE);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/addcontractor', 
					[	
						'active_menu' 	   		=> 'hiresettings',
						'page_title'	   		=> 'Add Expert',
						'scripts'		   		=> 'addblog',
						'service_tags'		 	=> $user->getServiceTag(),
						'user_info'		   		=> $user->userInfo(),
						'state_list'	    	=> $user->getStatelist(),
						'classified_profile'    => $user->getClassifiedProfiles(),
						'csrf_add_classified'  	=> $user->getCSRF("add_classified"),
						'sitesettings'	  		=> $user->filtersiteSettings(),
						'notification'   		=> $user->getOrderNotification()
					]);
			} else {
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 	=>  ' hiresettings ',
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
			$user  		= $this->model('Classified');
			$check 		= $user->checkPermissionPage(HIRE);
			$check_sitesettings = $user->checkSiteSettings(HIRE);
			$details 	= $user->check_query(CONTRACTOR_TBL,"id"," id='$token' ");
			$project  	= $user->getDetails(CONTRACTOR_PROJECT_TBL,"count(*) as id"," contractor_id='".$token."' AND delete_status='0' AND status='1' ");
			if ($details==1 && $check==1 && $check_sitesettings) {
				$info = $user->getDetails(CONTRACTOR_TBL,"*"," id='$token'  ");
				$this->view('home/editcontractor', 
					[	
						'active_menu' 			=> 'hiresettings',
						'page_title'			=> 'Edit Expert',
						'scripts'				=> 'editblog',
						'info'					=>  $info,
						'project' 				=>  $project,
						'token'					=>  $user->encryptData($info['id']),
						'service_tags'		 	=> 	$user->getServiceTag($info['service_tags']),
						'classified_profile'    =>  $user->getClassifiedProfiles($info['profile_type']),
						'state_list'	    	=>  $user->getStatelist($info['state_id']),
						'user_info'				=>  $user->userInfo(),
						'csrf_edit_classified' 	=>  $user->getCSRF("edit_classified"),
						'sitesettings'			=>	$user->filtersiteSettings(),
						'notification'          =>  $user->getOrderNotification()
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 	=>  ' hiresettings ',
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
			$user  = $this->model('Classified');
			$check = $user->checkPermissionPage(HIRE);
			$check_sitesettings = $user->checkSiteSettings(HIRE);
			$details = $user->check_query(CONTRACTOR_TBL,"id"," token='$token' ");
			if ($details==1 && $check==1 && $check_sitesettings==1) {
				$info = $user->getDetails(CONTRACTOR_TBL,"*"," token='$token'  ");

				$profile = explode(",",$info['profile_type']);
	  			$profile_types = "";

	  			foreach ($profile as $key => $value) {
	    			$profile_date   = $user->getDetails(CONTRACTOR_PROFILE_TBL,"*","id='".$value."' ");
	    			$Comma          = (($key==0)? "" : ",");
	  				$profile_types .= $Comma." ".$user->unHyphenize($profile_date['token']);
	  			}

				$this->view('home/contractordetails', 
					[	
						'active_menu' 			=> 'hiresettings',
						'page_title'			=> 'Classified Details',
						'scripts'				=> 'editblog',
						'info'					=>  $info,
						'token'					=>  $user->encryptData($info['id']),
						'profile_types'			=>  $profile_types,
						'service_tags'		 	=> 	$user->getServiceTag($info['service_tags']),
						'user_info'				=>  $user->userInfo(),
						'enquirylist' 	 		=>  $user->manageEnquiry($info['id']),
						'detailsviewlist' 		=>  $user->manageDetailsViewedList($info['id']),
						'sitesettings'			=>	$user->filtersiteSettings(),
						'notification'  	 	=>  $user->getOrderNotification()
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 	=>  ' hiresettings ',
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

	public function servicetags()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Classified');
			$check 				= $user->checkPermissionPage(SERVICE_TAGS);
			$check_sitesettings = $user->checkSiteSettings(SERVICE_TAGS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/servicetaglist', 
					[	
						'active_menu' 		 	=> 'hiresettings',
						'page_title'		 	=> 'Manage Service Tags',
						'scripts'			 	=> 'settings',
						'user_info'			 	=> $user->userInfo(),
						'csrf_add_servicetag'  	=> $user->getCSRF("add_servicetag"),
						'csrf_edit_servicetag' 	=> $user->getCSRF("edit_servicetag"),
						'list'				 	=> $user->manageServiceTags(),
						'sitesettings'	 	 	=> $user->filtersiteSettings(),
						'notification'   		=> $user->getOrderNotification()
					]);
			}else{
				$this->view('home/error', 
					[	
						'active_menu' 	=>  'settings',
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

	public function projects()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Classified');
			$check 				= $user->checkPermissionPage(HIRE_PROJECT);
			$check_sitesettings = $user->checkSiteSettings(HIRE_PROJECT);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/projectlist', 
					[	
						'active_menu' 		 	=> 'hiresettings',
						'page_title'		 	=> 'Manage Expert Projects',
						'scripts'			 	=> 'settings',
						'user_info'			 	=> $user->userInfo(),
						'csrf_add_servicetag'  	=> $user->getCSRF("add_servicetag"),
						'csrf_edit_servicetag' 	=> $user->getCSRF("edit_servicetag"),
						'projects_list'		    => $user->manageClassifiedProjects(),
						'sitesettings'	 	 	=> $user->filtersiteSettings(),
						'notification'   		=> $user->getOrderNotification()
					]);
			}else{
				$this->view('home/error', 
					[	
						'active_menu' 	=>  'hiresettings',
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

	public function contactviewed()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Classified');
			$check 				= $user->checkPermissionPage(HIRE_CONTACT_VIEWED);
			$check_sitesettings = $user->checkSiteSettings(HIRE_CONTACT_VIEWED);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/hireviewedcontacts', 
				[	
					'active_menu' 	=> 'hiresettings',
			        'page_title'    => 'Manage Expert Viewed Contacts',
			        'meta_title'    => 'Manage Expert Viewed Contacts - '.COMPANY_NAME,
			        'scripts' 		=>  'datatable',
					'user_info'		=>  $user->userInfo(),
					'sitesettings'	=>	$user->filtersiteSettings(),
					'notification'  =>  $user->getOrderNotification(),
			        'list' 			=>  $user->manageHireViewedContacts(),
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

	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Classified');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'add':
					$upload = new FileUploader();
					$file 	= $upload->uploadFile($_FILES, $_POST);
					echo 	$user->addClassified($_POST,$file);
				break;
				case 'update':
					$upload = new FileUploader();
					$file 	= $upload->uploadFile($_FILES, $_POST);
					echo 	$user->editClassified($_POST,$file);
				break;
				case 'sendInvite':
					echo $user->sendInvite($post);
				break;
				case 'profileVerified':
					echo $user->profileVerified($post);
				break;
				case 'status':
					echo $user->changeClassifiedStatus($post);
				break;
				case 'delete':
					echo $user->deleteClassified($post);
				break;
				case 'trash':
					echo $user->trashClassified($post);
				break;
				case 'publish':
					echo $user->publishClassified($post);
				break;
				case 'restore':
					echo $user->restoreClassified($post);
				break;
				case 'addServiceTag':
					echo $user->addServiceTag($_POST);
				break;
				case 'updateServiceTag':
					echo $user->editServiceTag($_POST);
				break;
				case 'infoServiceTag':
					echo $user->getServiceTagDetails($post);
				break;
				case 'statusServiceTag':
					echo $user->changeServiceTagStatus($post);
				break;
				case 'deleteServiceTag':
					echo $user->deleteServiceTag($post);
				break;
				case 'restoreCategory':
					echo $user->restoreServiceTag($post);
				break;
				case 'info':
					echo $user->getEnquiryDetails($post);
				break;

				case 'calssifiedProjectStatus':
					echo $user->changeClassifiedProjectStatus($post);
				break;

				case 'calssifiedProjectInfo':
					echo $user->getClassifiedProjectDetails($post);
				break;
				case 'trashClassifiedProject':
					echo $user->trashClassifiedProject($post);
				break;
				case 'restoreClassifiedProject':
					echo $user->restoreClassifiedProject($post);
				break;

				case 'calssifiedProjectVisibleStatus':
					echo $user->calssifiedProjectVisibleStatus($post);
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
					'active_menu' 	=>  ' hiresettings ',
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