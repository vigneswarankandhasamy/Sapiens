<?php

class offerbanner extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(OFFER_BANNER);
			$check_sitesettings = $user->checkSiteSettings(OFFER_BANNER);
			$count  			= $user->check_query(OFFER_BANNER_TBL,"*"," 1 ORDER BY id DESC"); 
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/offerbannerlist', 
					[	
						'active_menu' 	 => 'store',
						'page_title'	 => 'Manage Offer Banners',
						'scripts'		 => 'settings',
						'user_info'		 =>  $user->userInfo(),
						'list'			 =>	 $user->manageOfferBanners(),
						'count'			 =>  $count,
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
			$check 				= $user->checkPermissionPage(OFFER_BANNER);
			$check_sitesettings = $user->checkSiteSettings(OFFER_BANNER);
			$count  				= $user->check_query(OFFER_BANNER_TBL,"*"," 1 ORDER BY id DESC"); 
			if ($check==1 && $check_sitesettings==1 && $count==0 ) {
				$this->view('home/addofferbanner', 
					[	
						'active_menu' 	 		=> 'store',
						'page_title'	 		=> 'Add Offer Banner',
						'scripts'		 		=> 'addblog',
						'products_list'			=> $user->getProductList(),
						'sub_category_list'		=> $user->getSubcategoryList(),
						'category_list'			=> $user->getCategoryList(),
						'user_info'		 		=> $user->userInfo(),
						'count'					=> $count,
						'csrf_add_home_slider'  => $user->getCSRF("add_home_slider"),
						'sitesettings'			=> $user->filtersiteSettings(),
						'notification' 			=> $user->getOrderNotification()
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
			$check 				= $user->checkPermissionPage(OFFER_BANNER);
			$details 			= $user->check_query(OFFER_BANNER_TBL,"id"," id='$token' ");
			$check_sitesettings = $user->checkSiteSettings(OFFER_BANNER);
			if ($details==1 && $check_sitesettings==1 && $check==1) {
				$info = $user->getDetails(OFFER_BANNER_TBL,"*"," id='$token'  ");
				$this->view('home/editofferbanner', 
					[	
						'active_menu' 			=> 'store',
						'page_title'			=> 'Edit Offer Banner',
						'scripts'				=> 'editblog',
						'info'					=> $info,
						'products_list'			=> $user->getProductList($info['button_link']),
						'sub_category_list'		=> $user->getSubcategoryList($info['button_link']),
						'category_list'			=> $user->getCategoryList($info['button_link']),
						'token'					=> $user->encryptData($info['id']),
						'user_info'				=> $user->userInfo(),
						'csrf_edit_home_slider'	=> $user->getCSRF("edit_home_slider"),
						'sitesettings'			=>	$user->filtersiteSettings(),
						'notification'  		=>  $user->getOrderNotification()
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
					echo $user->addOfferBanner($_POST,$file);
				break;
				case 'update':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->editOfferBanner($_POST,$file);
				break;
				case 'status':
					echo $user->changeOfferBannerStatus($post);
				break;
				case 'delete':
					echo $user->deleteOfferBanner($post);
				break;
				case 'trash':
					echo $user->trashOfferBanner($post);
				break;
				case 'publish':
					echo $user->publishOfferBanner($post);
				break;
				case 'restore':
					echo $user->restoreOfferBanner($post);
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