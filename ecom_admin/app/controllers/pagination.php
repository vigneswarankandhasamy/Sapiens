<?php

class pagination extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Settings');
			$check 		        = $user->checkPermissionPage(PAGINATION); 
			$check_sitesettings = $user->checkSiteSettings(PAGINATION); 
			$tbl_data           = $user->getDetails(PAGINATION_TBL,"COUNT(id) as count","1");
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/paginationlist', 
					[	
						'active_menu' 				=> 'settings',
						'page_title'				=> 'Pagination',
						'scripts'					=> 'settings',
						'tbl_data'					=>  $tbl_data,
						'user_info'					=>  $user->userInfo(),
						'csrf_add_pagination' 	    =>  $user->getCSRF("add_pagination"),
						'csrf_edit_pagination'	    =>  $user->getCSRF("edit_pagination"),
						'list'						=>	$user->managePagination(),
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
					echo $user->addPagination($_POST);
				break;
				case 'update':
					echo $user->editPagination($_POST);
				break;
				case 'info':
					echo $user->getPaginationDetails($post);
				break;
				case 'status':
					echo $user->changePaginationStatus($post);
				break;
				case 'delete':
					echo $user->deletePagination($post);
				break;
				case 'restore':
					echo $user->restorePagination($post);
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