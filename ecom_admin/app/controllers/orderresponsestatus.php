<?php

class orderresponsestatus extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 		        = $user->checkPermissionPage(ORDER_RESPONSE_STATUS); 
			$check_sitesettings = $user->checkSiteSettings(ORDER_RESPONSE_STATUS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/orderresponsestatuslist', 
					[	
						'active_menu' 				=> 'settings',
						'page_title'				=> 'Order Response Status',
						'scripts'					=> 'settings',
						'user_info'					=>  $user->userInfo(),
						'csrf_add_ord_respone_sts' 	=>  $user->getCSRF("add_ord_respone_sts"),
						'csrf_edit_ord_respone_sts'	=>  $user->getCSRF("edit_ord_respone_sts"),
						'list'						=>	$user->manageOrderResponseStatus(),
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
					echo $user->addOrderResponseStatus($_POST);
				break;
				case 'update':
					echo $user->editOrderResponseStatus($_POST);
				break;
				case 'info':
					echo $user->getOrderResponseStatusDetails($post);
				break;
				case 'status':
					echo $user->changeOrderResponseStatusStatus($post);
				break;
				case 'delete':
					echo $user->deleteOrderResponseStatus($post);
				break;
				case 'restore':
					echo $user->restoreOrderResponseStatus($post);
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