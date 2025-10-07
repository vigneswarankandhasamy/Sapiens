<?php

class category extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Product');
			$check 				= $user->checkPermissionPage(CATEGORY);
			$check_sitesettings = $user->checkSiteSettings(CATEGORY);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/categorylist', 
					[	
						'active_menu' 	 => 'productsettings',
						'page_title'	 => 'Manage Category',
						'scripts'		 => 'settings',
						'user_info'		 => $user->userInfo(),
						'list'			 =>	$user->manageCategory(),
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
					'notification'  => $user->getOrderNotification()
				]);
			}
		}else{
			$this->view('home/login',[]);
		}
	}

	public function add()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Product');
			$check 				= $user->checkPermissionPage(CATEGORY);
			$check_sitesettings = $user->checkSiteSettings(CATEGORY);
			$info  = $user->getDetails(MAIN_CATEGORY_TBL,"*"," 1 ORDER BY id DESC "); 
			$sort_order = (($info==true)? $info['sort_order'] + 1 : 1 );
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/addcategory', 
					[	
						'active_menu' 	     	=> 'productsettings',
						'page_title'	     	=> 'Add Category',
						'scripts'		     	=> 'addblog',
						'user_info'		     	=> $user->userInfo(),
						'sort_order'         	=> $sort_order,
						'csrf_add_category'  	=> $user->getCSRF("add_category"),
						'vendor_commission_tax'	=> $user->getVendorCommissionTax(),
						'sitesettings'	     	=> $user->filtersiteSettings(),
						'notification'   		=> $user->getOrderNotification()
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
					'notification'   => $user->getOrderNotification()
				]);
			}
		}else{
			$this->view('home/login',[]);
		}
	}

	
	public function edit($token="")
	{
			
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Product');
			$check 				= $user->checkPermissionPage(CATEGORY);
			$check_sitesettings = $user->checkSiteSettings(CATEGORY);
			$details 			= $user->check_query(MAIN_CATEGORY_TBL,"id"," id='$token' ");
			if ($details==1 && $check_sitesettings==1 && $check==1) {
				$info = $user->getDetails(MAIN_CATEGORY_TBL,"*"," id='$token'  ");
				$this->view('home/editcategory', 
					[	
						'active_menu' 		 	=> 'productsettings',
						'page_title'		 	=> 'Edit Category',
						'scripts'			 	=> 'editblog',
						'info'				 	=> $info,
						'token'				 	=> $user->encryptData($info['id']),
						'vendor_commission_tax'	=> $user->getVendorCommissionTax($info['vendor_commission_tax']),
						'user_info'		     	=> $user->userInfo(),
						'csrf_edit_category' 	=> $user->getCSRF("edit_category"),
						'sitesettings'	 	 	=> $user->filtersiteSettings(),
						'notification'   		=> $user->getOrderNotification()
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
					'notification'  => $user->getOrderNotification()
				]);
			}
		}else{
			$this->view('home/login',[]);
		}
	}


	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Product');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'add':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->addCategory($_POST,$file);
				break;
				case 'update':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->editCategory($_POST,$file);
				break;
				case 'status':
					echo $user->changeCategoryStatus($post);
				break;
				case 'delete':
					echo $user->deleteCategory($post);
				break;
				case 'trash':
					echo $user->trashCategory($post);
				break;
				case 'publish':
					echo $user->publishCategory($post);
				break;
				case 'restore':
					echo $user->restoreCategory($post);
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
					'notification'  => $user->getOrderNotification()
				]);
		}else{
			$this->view('home/login',[]);
		}
	}

}


?>