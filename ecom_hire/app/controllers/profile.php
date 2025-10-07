<?php

class profile extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_contractor_id"])){
			$user  = $this->model('AdminProfile');
			// $check = $user->checkPermissionPage(DASHBOARD);
			$check = 1;
			if ($check==1) {
				$this->view('home/profile', 
					[	
						'active_menu' 	 		=> 'profile',
						'page_title'	 		=> 'My Profile',
						'scripts'		 		=> 'settings',
						'user_info'		 		=>  $user->userInfo(),
						'csrf_update_profile'  	=> $user->getCSRF("update_profile"),
					]);
			}else{
				$this->view('home/error', 
					[	
						'active_menu' 	=>  '',
						'page_title'	=>	'404 - Page Not Found',
						'scripts'		=>	'error',
						'user_info'		=>	$user->userInfo()
					]);
			}
		}else{
			$this->view('home/login',[]);
		}
	}

	public function company()
	{

		if(isset($_SESSION["ecom_contractor_id"])){
			$user  		= $this->model('AdminProfile');
			// $check = $user->checkPermissionPage(BRAND);
			$check 		= 1;
			$details 	= $user->check_query(CONTRACTOR_TBL,"id"," id='".$_SESSION["ecom_contractor_id"]."' ");
			
			$project  	= $user->getDetails(CONTRACTOR_PROJECT_TBL,"count(*) as id"," contractor_id='".$_SESSION["ecom_contractor_id"]."' AND delete_status='0' AND status='1' ");

			if ($details==1) {
				$info = $user->getDetails(CONTRACTOR_TBL,"*"," id='".$_SESSION["ecom_contractor_id"]."'  ");
				$this->view('home/companyprofile', 
					[	
						'active_menu' 			=> 'modules',
						'page_title'			=> 'Update Company Profile',
						'scripts'				=> 'editblog',
						'info'					=>  $info,
						'project' 				=>  $project,
						'token'					=>  $user->encryptData($info['id']),
						'classified_profile'    =>  $user->getClassifiedProfiles($info['profile_type']),
						'state_list'	    	=>  $user->getStatelist($info['state_id']),
						'service_tags'		 	=> 	$user->getServiceTag($info['service_tags']),
						'user_info'				=>  $user->userInfo(),
						'csrf_edit_company' 	=>  $user->getCSRF("edit_company"),
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 	=>  ' modules ',
					'page_title'	=>	'404 - Page Not Found',
					'scripts'		=>	'error',
					'user_info'		=>	$user->userInfo(),
				]);
			}
		}else{
			$this->view('home/login',[]);
		}
	}

	public function security()
	{
		if(isset($_SESSION["ecom_contractor_id"])){
			$user  	= $this->model('AdminProfile');
			// $check = $user->checkPermissionPage(DASHBOARD);
			$check 	= 1;
			$u_info = $user->userInfo();
			if($u_info['password_update']==NULL) {
				$password_update = $u_info['created_at'];
			} else {
				$password_update = $u_info['password_update'];
			}
			if ($check==1) {
				$this->view('home/security', 
					[	
						'active_menu' 	 		=> 'security',
						'page_title'	 		=> 'My Profile',
						'scripts'		 		=> 'settings',
						'user_info'		 		=>  $u_info,
						'list'			 		=>	$user->manageloginActivity("LIMIT 4"),
						'time'			 		=> 	$user->to_time_ago(time() - strtotime($password_update)),
						'csrf_change_password'  => 	$user->getCSRF("change_password"),
					]);
			}else{
				$this->view('home/error', 
					[	
						'active_menu' 	=>  '',
						'page_title'	=>	'404 - Page Not Found',
						'scripts'		=>	'error',
						'user_info'		=>	$user->userInfo()
					]);
			}
		}else{
			$this->view('home/login',[]);
		}
	}


	public function loginactivity()
	{
		if(isset($_SESSION["ecom_contractor_id"])){
			$user  = $this->model('AdminProfile');
			// $check = $user->checkPermissionPage(DASHBOARD);
			$check = 1;
			if ($check==1) {
				$this->view('home/loginactivity', 
					[	
						'active_menu' 	 => 'security',
						'page_title'	 => 'My Profile',
						'scripts'		 => 'settings',
						'user_info'		 =>  $user->userInfo(),
						'list'			 =>	$user->manageloginActivity("LIMIT 20"),
					]);
			}else{
				$this->view('home/error', 
					[	
						'active_menu' 	=>  '',
						'page_title'	=>	'404 - Page Not Found',
						'scripts'		=>	'error',
						'user_info'		=>	$user->userInfo()
					]);
			}
		}else{
			$this->view('home/login',[]);
		}
	}


	public function api($type)
	{
		if(isset($_SESSION["ecom_contractor_id"])){
			$user  	= $this->model('AdminProfile');
			$post 	= @$_POST["result"];	
			switch ($type) {
				
				case 'update':
					echo $user->updateAdminProfile($_POST);
				break;

				case 'changePassword':
					echo $user->changePasswordAdmin($_POST);
				break;

				case 'updateCompany':
					$upload = new FileUploader();
					$file 	= $upload->uploadFile($_FILES, $_POST , $path="admin_resource");
					echo 	$user->updateCompany($_POST,$file);
				break;

				case 'updateProfileInfo' :
					echo $user->updateProfileInfo($_POST);
				break;
				case 'updateContactInfo' :
					echo $user->updateContactInfo($_POST);
				break;
				
				break;
			}
		}
	}

	public function error()
	{
		if(isset($_SESSION["ecom_contractor_id"])){
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