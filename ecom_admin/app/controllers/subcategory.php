<?php

class Subcategory extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			// $user  				= $this->model('Product');
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(SUB_CATEGORY);
			$check_sitesettings = $user->checkSiteSettings(SUB_CATEGORY);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/subcategorylist', 
					[	
						'active_menu' 	 => 'productsettings',
						'page_title'	 => 'Manage Subcategory',
						'scripts'		 => 'settings',
						'user_info'		 => $user->userInfo(),
						'list'			 =>	$user->manageSubCategory(),
						'sitesettings'	 =>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification()
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
			$check 				= $user->checkPermissionPage(SUB_CATEGORY);
			$check_sitesettings = $user->checkSiteSettings(SUB_CATEGORY);
			$info  				= $user->getDetails(SUB_CATEGORY_TBL,"*"," 1 ORDER BY id DESC "); 
			$sort_order = (($info==true)? $info['sort_order'] + 1 : 1 );
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/addsubcategory', 
					[	
						'active_menu' 	     	=> 'productsettings',
						'page_title'	     	=> 'Add Subcategory',
						'scripts'		     	=> 'addblog',
						'user_info'		     	=> $user->userInfo(),
						'sort_order'         	=> $sort_order,
						'csrf_add_subcategory'  => $user->getCSRF("add_subcategory"),
						'filter_groups'			=> $user->getFilterGroupCheckBox(),
						'vendor_commission_tax'	=> $user->getVendorCommissionTax(),
						'category'		 		=> $user->getmainCategory(),
						'sitesettings'	     	=> $user->filtersiteSettings(),
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
			$check 				= $user->checkPermissionPage(SUB_CATEGORY);
			$details 			= $user->check_query(SUB_CATEGORY_TBL,"id"," id='$token' ");
			$check_sitesettings = $user->checkSiteSettings(SUB_CATEGORY);
			if ($details==1 && $check_sitesettings==1 && $check==1) {
				$info 			 = $user->getDetails(SUB_CATEGORY_TBL,"*"," id='$token'  ");
				$this->view('home/editsubcategory', 
					[	
						'active_menu' 		 	=> 'productsettings',
						'page_title'		 	=> 'Edit Subcategory',
						'scripts'			 	=> 'editblog',
						'info'				 	=> $info,
						'token'				 	=> $user->encryptData($info['id']),
						'category'				=> $user->getmainCategory($info['category_id']),
						'vendor_commission_tax'	=> $user->getVendorCommissionTax($info['vendor_commission_tax']),
						'filter_groups'			=> $user->getFilterGroupCheckBox($info['id']),
						'user_info'		     	=> $user->userInfo(),
						'csrf_edit_subcategory' => $user->getCSRF("edit_subcategory"),
						'sitesettings'	 		=> $user->filtersiteSettings(),
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
					$file 	= $upload->uploadFile($_FILES, $_POST);
					echo $user->addSubcategory($_POST,$file);
				break;
				case 'update':
					$upload = new FileUploader();
					$file 	= $upload->uploadFile($_FILES, $_POST);
					echo $user->editSubcategory($_POST,$file);
				break;
				case 'status':
					echo $user->changeSubcategoryStatus($post);
				break;
				case 'delete':
					echo $user->deleteSubcategory($post);
				break;
				case 'trash':
					echo $user->trashSubcategory($post);
				break;
				case 'publish':
					echo $user->publishSubcategory($post);
				break;
				case 'restore':
					echo $user->restoreSubcategory($post);
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