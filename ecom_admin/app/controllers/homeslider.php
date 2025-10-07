<?php

class homeslider extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(HOME_SLIDERS);
			$check_sitesettings = $user->checkSiteSettings(HOME_SLIDERS); 
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/homesliderlist', 
					[	
						'active_menu' 	 => 'store',
						'page_title'	 => 'Manage Home Slider',
						'scripts'		 => 'settings',
						'user_info'		 =>  $user->userInfo(),
						'list'			 =>	 $user->manageHomeSlider(),
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
			$check 				= $user->checkPermissionPage(HOME_SLIDERS);
			$check_sitesettings = $user->checkSiteSettings(HOME_SLIDERS); 
			$info  				= $user->getDetails(HOME_BANNER_TBL,"*"," 1 ORDER BY id DESC "); 
			$sort_order = (($info==true)? $info['sort_order'] + 1 : 1 );
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/addhomeslider', 
					[	
						'active_menu' 	 		=> 'store',
						'page_title'	 		=> 'Add Home Slider',
						'scripts'		 		=> 'addblog',
						'user_info'		 		=> $user->userInfo(),
						'sort_order'     		=> $sort_order,
						'products_list'			=> $user->getProductList(),
						'sub_category_list'		=> $user->getSubcategoryList(),
						'category_list'			=> $user->getCategoryList(),
						'csrf_add_home_slider'  => $user->getCSRF("add_home_slider"),
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
			$check 				= $user->checkPermissionPage(HOME_SLIDERS);
			$details 			= $user->check_query(HOME_BANNER_TBL,"id"," id='$token' ");
			$check_sitesettings = $user->checkSiteSettings(HOME_SLIDERS); 
			if ($details==1 && $check_sitesettings==1 && $check==1) {
				$info = $user->getDetails(HOME_BANNER_TBL,"*"," id='$token'  ");
				$this->view('home/edithomeslider', 
					[	
						'active_menu' 			=> 'store',
						'page_title'			=> 'Edit Home Slider',
						'scripts'				=> 'editblog',
						'info'					=> $info,
						'products_list'			=> $user->getProductList($info['button_link']),
						'sub_category_list'		=> $user->getSubcategoryList($info['button_link']),
						'category_list'			=> $user->getCategoryList($info['button_link']),
						'token'					=> $user->encryptData($info['id']),
						'user_info'				=> $user->userInfo(),
						'csrf_edit_home_slider' => $user->getCSRF("edit_home_slider"),
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
					echo $user->addHomeSlider($_POST,$file);
				break;
				case 'update':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->editHomeSlider($_POST,$file);
				break;
				case 'status':
					echo $user->changeHomeSliderStatus($post);
				break;
				case 'delete':
					echo $user->deleteHomeSlider($post);
				break;
				case 'trash':
					echo $user->trashHomeSlider($post);
				break;
				case 'publish':
					echo $user->publishHomeSlider($post);
				break;
				case 'restore':
					echo $user->restoreHomeSlider($post);
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