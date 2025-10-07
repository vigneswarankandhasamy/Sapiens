<?php

class Users extends Controller
{
	
	public function index()
	{

		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(USERS);
			$check_sitesettings = $user->checkSiteSettings(USERS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/userlist', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Users',
						'scripts'		=> 'users',
						'user_info'		=>  $user->userInfo(),
						'csrf_add_tax' 	=>  $user->getCSRF("add_tax"),
						'csrf_edit_tax' =>  $user->getCSRF("edit_tax"),
						'list'			=>	$user->manageUsers(),
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

	public function add()
	{

		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(USERS);
			$check_sitesettings = $user->checkSiteSettings(USERS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/adduser', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Add User',
						'scripts'		=> 'adduser',
						'permissions'   =>  $user->permissionDataMaster(),
						'user_info'		=>  $user->userInfo(),
						'csrf_add_user' =>  $user->getCSRF("add_user"),
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

	public function edit($token="")
	{
			
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(USERS);
			$details 			= $user->check_query(USERS_TBL,"id"," id='$token' ");
			$check_sitesettings = $user->checkSiteSettings(USERS);
			if ($details==1 && $check==1 && $check_sitesettings==1) {
				$info = $user->getDetails(USERS_TBL,"*"," id='$token'  ");
				$this->view('home/edituser', 
					[	
						'active_menu' 	 => 'settings',
						'page_title'	 => 'Edit User',
						'scripts'		 => 'adduser',
						'info'			 => $info,
						'token'			 =>	$user->encryptData($info['id']),
						'permissions'    => $user->permissionDataMasterEdit($info['id']),
						'user_info'		 => $user->userInfo(),
						'csrf_edit_user' => $user->getCSRF("edit_user"),
						'sitesettings'	 =>	$user->filtersiteSettings(),
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
			$user  = $this->model('Settings');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'add':
					echo $user->addUser($_POST);
				break;
				case 'update':
					echo $user->editUser($_POST);
				break;
				case 'status':
					echo $user->changeUserStatus($post);
				break;
				case 'trash':
					echo $user->trashUser($post);
				break;
				case 'publish':
					echo $user->publishUser($post);
				break;
				case 'delete':
					echo $user->deleteUser($post);
				break;
				case 'restore':
					echo $user->restoreUser($post);
				break;
				case 'sendInvite':
					echo $user->inviteEmail($post);
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
