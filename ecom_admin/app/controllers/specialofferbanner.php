<?php

class specialofferbanner extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(SPECIAL_BANNER);
			$check_sitesettings = $user->checkSiteSettings(SPECIAL_BANNER); 
			$count 		        = $user->check_query(SPECIAL_BANNER_TBL,"*","1 ORDER BY id DESC");
			if ($check==1 && $check_sitesettings==1 ) {
				$this->view('home/specialofferbannerlist', 
					[	
						'active_menu' 	 => 'store',
						'page_title'	 => 'Manage Special Offer Banner',
						'scripts'		 => 'settings',
						'user_info'		 =>  $user->userInfo(),
						'list'			 =>	 $user->manageSpecialOfferBanner(),
						'count' 		 =>  $count,
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
			$check 				= $user->checkPermissionPage(SPECIAL_BANNER);
			$check_sitesettings = $user->checkSiteSettings(SPECIAL_BANNER); 
			$info  				= $user->getDetails(SPECIAL_BANNER_TBL,"*"," 1 ORDER BY id DESC "); 
			$count 		        = $user->check_query(SPECIAL_BANNER_TBL,"*","1 ORDER BY id DESC");
			if ($check==1 && $check_sitesettings==1 && $count < 2)  {
				$this->view('home/addspecialofferbanner', 
					[	
						'active_menu' 	 		=> 'store',
						'page_title'	 		=> 'Add Special Offer Banner',
						'scripts'		 		=> 'addblog',
						'user_info'		 		=> $user->userInfo(),
						'products_list'			=> $user->getProductList(),
						'sub_category_list'		=> $user->getSubcategoryList(),
						'category_list'			=> $user->getCategoryList(),
						'csrf_add_spl_banner'   => $user->getCSRF("add_spl_banner"),
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
			$check 				= $user->checkPermissionPage(SPECIAL_BANNER);
			$details 			= $user->check_query(SPECIAL_BANNER_TBL,"id"," id='$token' ");
			$check_sitesettings = $user->checkSiteSettings(SPECIAL_BANNER); 
			if ($details==1 && $check_sitesettings==1 && $check==1) {
				$info = $user->getDetails(SPECIAL_BANNER_TBL,"*"," id='$token'  ");
				$this->view('home/editspecialofferbanner', 
					[	
						'active_menu' 			=> 'store',
						'page_title'			=> 'Edit Special Offer Banner',
						'scripts'				=> 'editblog',
						'info'					=> $info,
						'products_list'			=> $user->getProductList($info['button_link']),
						'sub_category_list'		=> $user->getSubcategoryList($info['button_link']),
						'category_list'			=> $user->getCategoryList($info['button_link']),
						'token'					=> $user->encryptData($info['id']),
						'user_info'				=> $user->userInfo(),
						'csrf_edit_spl_banner'  => $user->getCSRF("edit_spl_banner"),
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
					echo $user->addSpecialOfferBanner($_POST,$file);
				break;
				case 'update':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->editSpecialOfferBanner($_POST,$file);
				break;
				case 'status':
					echo $user->changeSpecialOfferBannerStatus($post);
				break;
				case 'delete':
					echo $user->deleteSpecialOfferBanner($post);
				break;
				case 'trash':
					echo $user->trashSpecialOfferBanner($post);
				break;
				case 'publish':
					echo $user->publishSpecialOfferBanner($post);
				break;
				case 'restore':
					echo $user->restoreSpecialOfferBanner($post);
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