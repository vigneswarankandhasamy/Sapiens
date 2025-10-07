<?php

class legalpages extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(LEGAL_PAGES);
			$check_sitesettings = $user->checkSiteSettings(LEGAL_PAGES);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/legalpageslist', 
					[	
						'active_menu' 	 => 'store',
						'page_title'	 => 'Manage Legel Pages',
						'scripts'		 => 'settings',
						'user_info'		 =>  $user->userInfo(),
						'list'			 =>  $user->manageLegelPages(),
						'sitesettings'	 =>  $user->filtersiteSettings(),
						'notification'   =>  $user->getOrderNotification()
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

	public function add()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(LEGAL_PAGES);
			$check_sitesettings = $user->checkSiteSettings(LEGAL_PAGES);
			$info  				= $user->getDetails(LEGAL_PAGE_TBL,"*"," 1 ORDER BY id DESC "); 
			$sort_order 		= (($info==true)? $info['sort_order'] + 1 : 1 );
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/addlegalpage', 
					[	
						'active_menu' 	 	  => 'store',
						'page_title'	 	  => 'Add Legel Page',
						'scripts'		 	  => 'addblog',
						'sort_order'     	  => $sort_order,
						'user_info'			  => $user->userInfo(),
						'csrf_add_legalpage'  => $user->getCSRF("add_legalpage"),
						'sitesettings'		  => $user->filtersiteSettings(),
						'notification'  	  =>  $user->getOrderNotification()
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

	
	public function edit($token="")
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(LEGAL_PAGES);
			$details 			= $user->check_query(LEGAL_PAGE_TBL,"id"," id='$token' ");
			$check_sitesettings = $user->checkSiteSettings(LEGAL_PAGES);
			if ($details==1 && $check_sitesettings==1 && $check==1) {
				$info = $user->getDetails(LEGAL_PAGE_TBL,"*"," id='$token'  ");
				$this->view('home/editlegalpage', 
					[	
						'active_menu' 		  => 'store',
						'page_title'		  => 'Edit Legel Page',
						'scripts'			  => 'addblog',
						'info'				  => $info,
						'token'				  => $user->encryptData($info['id']),
						'user_info'			  => $user->userInfo(),
						'csrf_edit_legalpage' => $user->getCSRF("edit_legalpage"),
						'sitesettings'		  => $user->filtersiteSettings(),
						'notification'  	  => $user->getOrderNotification()
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
			$user  = $this->model('Store');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'add':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->addLegelPage($_POST,$file);
				break;
				case 'update':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->editLegelPage($_POST,$file);
				break;
				case 'status':
					echo $user->changeLegelPageStatus($post);
				break;
				case 'delete':
					echo $user->deleteLegelPage($post);
				break;
				case 'trash':
					echo $user->trashLegelPage($post);
				break;
				case 'publish':
					echo $user->publishLegelPage($post);
				break;
				case 'restore':
					echo $user->restoreLegelPage($post);
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