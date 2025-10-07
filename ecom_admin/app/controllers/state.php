<?php

class state extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(STATE_CITY_PINCODE);
			$check_sitesettings = $user->checksitesettings(STATE_CITY_PINCODE);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/statelist', 
					[	
						'active_menu' 		 => 'settings',
						'page_title'		 => 'Manage State',
						'scripts'			 => 'settings',
						'page_menu'		 	 => 'state',
						'user_info'			 => $user->userInfo(),
						'csrf_add_state'  	 => $user->getCSRF("add_state"),
						'csrf_edit_state' 	 => $user->getCSRF("edit_state"),
						'list'				 =>	$user->manageState(),
						'sitesettings'		 =>	$user->filtersiteSettings(),
						'notification'  	 =>  $user->getOrderNotification()
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
				case 'addState':
					echo $user->addState($_POST);
				break;
				case 'updateState':
					echo $user->editState($_POST);
				break;
				case 'statusState':
					echo $user->changeStateStatus($post);
				break;
				case 'infoState':
					echo $user->getStateDetails($post);
				break;
				case 'deleteState':
					echo $user->deleteState($post);
				break;
				case 'trash':
					echo $user->trashState($post);
				break;
				case 'restore':
					echo $user->restoreState($post);
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