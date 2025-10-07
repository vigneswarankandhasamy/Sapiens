<?php

class brand extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(BRAND);
			$check_sitesettings = $user->checkSiteSettings(BRAND);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/brandlist', 
					[	
						'active_menu' 	 => 'productsettings',
						'page_title'	 => 'Manage Brand',
						'scripts'		 => 'settings',
						'user_info'		 => $user->userInfo(),
						'list'			 =>	$user->manageBrand(),
						'sitesettings'	 =>	$user->filtersiteSettings(),
						'notification'   => $user->getOrderNotification()

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
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(BRAND);
			$check_sitesettings = $user->checkSiteSettings(BRAND);
			$info  				= $user->getDetails(BRAND_TBL,"*"," 1 ORDER BY id DESC "); 
			$sort_order = (($info==true)? $info['sort_order'] + 1 : 1 );
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/addbrand', 
					[	
						'active_menu' 	 => 'productsettings',
						'page_title'	 => 'Add Brand',
						'scripts'		 => 'addblog',
						'user_info'		 => $user->userInfo(),
						'sort_order'     => $sort_order,
						'csrf_add_brand' => $user->getCSRF("add_brand"),
						'sitesettings'	 =>	 $user->filtersiteSettings(),
						'notification'   =>  $user->getOrderNotification()
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
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(BRAND);
			$check_sitesettings = $user->checkSiteSettings(BRAND);
			$details 			= $user->check_query(BRAND_TBL,"id"," id='$token' ");
			if ($details==1 && $check_sitesettings==1) {
				$info = $user->getDetails(BRAND_TBL,"*"," id='$token'  ");
				$this->view('home/editbrand', 
					[	
						'active_menu' 	  => 'productsettings',
						'page_title'	  => 'Edit Brand',
						'scripts'		  => 'editblog',
						'info'			  => $info,
						'token'			  => $user->encryptData($info['id']),
						'user_info'		  => $user->userInfo(),
						'csrf_edit_brand' => $user->getCSRF("edit_brand"),
						'sitesettings'	  => $user->filtersiteSettings(),
						'notification'    => $user->getOrderNotification()

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
					echo $user->addBrand($_POST,$file);
				break;
				case 'update':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->editBrand($_POST,$file);
				break;
				case 'status':
					echo $user->changeBrandStatus($post);
				break;
				case 'delete':
					echo $user->deleteBrand($post);
				break;
				case 'trash':
					echo $user->trashBrand($post);
				break;
				case 'publish':
					echo $user->publishBrand($post);
				break;
				case 'restore':
					echo $user->restoreBrand($post);
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