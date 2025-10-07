<?php

class seo extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(SEO);
			$check_sitesettings = $user->checksitesettings(SEO);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/seolist', 
					[	
						'active_menu' 	 => 'store',
						'page_title'	 => 'Manage SEO',
						'scripts'		 => 'settings',
						'user_info'		 =>  $user->userInfo(),
						'list'			 =>	 $user->manageSeoContents(),
						'sitesettings'	 =>	 $user->filtersitesettings(),
						'notification'   =>  $user->getOrderNotification()
					]);
			}else{
				$this->view('home/error', 
					[	
						'active_menu' 	=>  '',
						'page_title'	=>	'404 - Page Not Found',
						'scripts'		=>	'error',
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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
			$check 				= $user->checkPermissionPage(SEO);
			$check_sitesettings = $user->checksitesettings(SEO);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/addseo', 
					[	
						'active_menu' 	 => 'store',
						'page_title'	 => 'Add SEO',
						'scripts'		 => 'addblog',
						'user_info'		 => $user->userInfo(),
						'csrf_add_seo'   => $user->getCSRF("add_seo"),
						'sitesettings'	 =>	$user->filtersitesettings(),
						'notification'  =>  $user->getOrderNotification()
					]);
			}else{
				$this->view('home/error', 
					[	
						'active_menu' 	=>  '',
						'page_title'	=>	'404 - Page Not Found',
						'scripts'		=>	'error',
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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
			$check 				= $user->checkPermissionPage(SEO);
			$details 			= $user->check_query(SEO_TBL,"id"," id='$token' ");
			$check_sitesettings = $user->checksitesettings(SEO);
			if ($details==1 && $check_sitesettings==1 && $check==1) {
				$info = $user->getDetails(SEO_TBL,"*"," id='$token'  ");
				$this->view('home/editseo', 
					[	
						'active_menu' 	=> 'store',
						'page_title'	=> 'Edit SEO',
						'scripts'		=> 'addblog',
						'info'			=> $info,
						'token'			=> $user->encryptData($info['id']),
						'user_info'		=> $user->userInfo(),
						'csrf_edit_seo' => $user->getCSRF("edit_seo"),
						'sitesettings'	=> $user->filtersitesettings(),
						'notification'  => $user->getOrderNotification()
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 	=>  '',
					'page_title'	=>	'404 - Page Not Found',
					'scripts'		=>	'error',
					'user_info'		=>	$user->userInfo(),
					'sitesettings'	=>	$user->filtersitesettings(),
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
					echo $user->addSeoContent($_POST,$file);
				break;
				case 'update':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->editSeoContent($_POST,$file);
				break;
				case 'status':
					echo $user->changeSeoContentStatus($post);
				break;
				case 'delete':
					echo $user->deleteSeoContent($post);
				break;
				case 'trash':
					echo $user->trashSeoContent($post);
				break;
				case 'publish':
					echo $user->publishSeoContent($post);
				break;
				case 'restore':
					echo $user->restoreSeoContent($post);
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
					'sitesettings'	=>	$user->filtersitesettings(),
					'notification'  =>  $user->getOrderNotification()
				]);
		}else{
			$this->view('home/login',[]);
		}
	}

}


?>