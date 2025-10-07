<?php

class returnreason extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(RETURN_REASONS);
			$check_sitesettings = $user->checksitesettings(RETURN_REASONS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/returnreasonlist', 
					[	
						'active_menu' 				=> 'settings',
						'page_title'				=> 'Return Reason',
						'scripts'					=> 'settings',
						'user_info'					=>  $user->userInfo(),
						'csrf_add_return_reason' 	=>  $user->getCSRF("add_return_reason"),
						'csrf_edit_return_reason'	=>  $user->getCSRF("edit_return_reason"),
						'list'						=>	$user->manageReturnReason(),
						'sitesettings'				=>	$user->filtersiteSettings(),
						'notification'			    =>  $user->getOrderNotification()
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
				case 'add':
					echo $user->addReturnReasones($_POST);
				break;
				case 'update':
					echo $user->editReturnReasones($_POST);
				break;
				case 'info':
					echo $user->getReturnReasonDetails($post);
				break;
				case 'status':
					echo $user->changeReturnReasonStatus($post);
				break;
				case 'delete':
					echo $user->deleteReturnReason($post);
				break;
				case 'restoreReturnReason':
					echo $user->restoreReturnReason($post);
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


	/*-----------Dont'delete---------*/

}


?>