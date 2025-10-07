<?php

class Tax extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 				= $user->checkPermissionPage(TAXES);
			$check_sitesettings = $user->checkSiteSettings(TAXES);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/taxlist', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Tax Class',
						'scripts'		=> 'settings',
						'user_info'		=>  $user->userInfo(),
						'csrf_add_tax' 	=>  $user->getCSRF("add_tax"),
						'csrf_edit_tax' =>  $user->getCSRF("edit_tax"),
						'list'			=>	$user->manageTaxClass(),
						'sitesettings'	=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification()
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
					echo $user->addTaxClasses($_POST);
				break;
				case 'update':
					echo $user->editTaxClasses($_POST);
				break;
				case 'info':
					echo $user->getTaxItemDetails($post);
				break;
				case 'status':
					echo $user->changeTaxStatus($post);
				break;
				case 'delete':
					echo $user->deleteTax($post);
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