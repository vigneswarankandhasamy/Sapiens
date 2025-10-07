<?php

class hireprofile extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check = $user->checkPermissionPage(HIRE_PROFILE);
			$check_sitesettings = $user->checkSiteSettings(HIRE_PROFILE);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/contractorprofilelist', 
					[	
						'active_menu' 				=> 'hiresettings',
						'page_title'				=> 'Expert Categories',
						'scripts'					=> 'settings',
						'user_info'					=>  $user->userInfo(),
						'csrf_add_cont_profile' 	=>  $user->getCSRF("add_cont_profile"),
						'csrf_edit_cont_profile'	=>  $user->getCSRF("edit_cont_profile"),
						'list'						=>	$user->manageClassifiedProfile(),
						'sitesettings'				=>	$user->filtersiteSettings(),
						'notification'   			=>  $user->getOrderNotification()
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

	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Settings');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'add':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->addClassifiedProfile($_POST,$file);
				break;
				case 'update':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->editClassifiedProfile($_POST,$file);
				break;
				case 'info':
					echo $user->getClassifiedProfileDetails($post);
				break;
				case 'status':
					echo $user->changeClassifiedProfileStatus($post);
				break;
				case 'delete':
					echo $user->deleteClassifiedProfile($post);
				break;
				case 'restoreClassifiedProfile':
					echo $user->restoreClassifiedProfile($post);
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
					'active_menu' 	=>  'hiresettings',
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