<?php

class pagebanner extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(PAGE_BANNER);
			$check_sitesettings = $user->checkSiteSettings(PAGE_BANNER);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/pagebannerlist', 
					[	
						'active_menu' 	 =>  'store',
						'page_title'	 =>  'Manage Page Banners',
						'scripts'		 =>  'settings',
						'user_info'		 =>  $user->userInfo(),
						'list'			 =>	 $user->managePageBanners(),
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

	public function add()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(PAGE_BANNER);
			$check_sitesettings = $user->checkSiteSettings(PAGE_BANNER);
			if ($check==1 && $check_sitesettings==1 ) {
				$this->view('home/addpagebanner', 
					[	
						'active_menu' 	 		=> 'store',
						'page_title'	 		=> 'Add Page Banner',
						'scripts'		 		=> 'addblog',
						'products_list'			=> $user->getProductList(),
						'sub_category_list'		=> $user->getSubcategoryList(),
						'category_list'			=> $user->getCategoryList(),
						'user_info'		 		=> $user->userInfo(),
						'csrf_add_page_banner'  => $user->getCSRF("add_page_banner"),
						'sitesettings'			=> $user->filtersiteSettings(),
						'notification'  		=> $user->getOrderNotification()
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
			$check 				= $user->checkPermissionPage(PAGE_BANNER);
			$details 			= $user->check_query(PAGE_BANNER_TBL,"id"," id='$token' ");
			$check_sitesettings = $user->checkSiteSettings(PAGE_BANNER);
			if ($details==1 && $check_sitesettings==1 && $check==1 ) {
				$info = $user->getDetails(PAGE_BANNER_TBL,"*"," id='$token'  ");
				$this->view('home/editpagebanner', 
					[	
						'active_menu' 			=> 'store',
						'page_title'			=> 'Edit Page Banner',
						'scripts'				=> 'editblog',
						'info'					=> $info,
						'products_list'			=> $user->getProductList($info['button_link']),
						'sub_category_list'		=> $user->getSubcategoryList($info['button_link']),
						'category_list'			=> $user->getCategoryList($info['button_link']),
						'token'					=> $user->encryptData($info['id']),
						'user_info'				=> $user->userInfo(),
						'csrf_edit_page_banner' => $user->getCSRF("edit_page_banner"),
						'sitesettings'			=> $user->filtersiteSettings(),
						'notification'  		=> $user->getOrderNotification()
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
					echo $user->addPageBanner($_POST,$file);
				break;
				case 'update':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->editPageBanner($_POST,$file);
				break;
				case 'status':
					echo $user->changePageBannerStatus($post);
				break;
				case 'delete':
					echo $user->deletePageBanner($post);
				break;
				case 'trash':
					echo $user->trashPageBanner($post);
				break;
				case 'publish':
					echo $user->publishPageBanner($post);
				break;
				case 'restore':
					echo $user->restorePageBanner($post);
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