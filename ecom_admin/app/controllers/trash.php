<?php

class Trash extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'User Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'users',
						'list'			=>  $user->userTrashItems(),	
						'user_info'		=>  $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function blog()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Blog Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'blog',
						'list'			=> $user->blogTrashItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function blogcategory()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Blog Category Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'blog_category',
						'list'			=> $user->blogCategoryTrashItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function homeslider()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Home Slider Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'homeslider',
						'list'			=> $user->homesliderTrashItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function offerbanner()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Offer Banner Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'offerbanner',
						'list'			=> $user->offerBannerTrashItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function brand()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Brand Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'brand',
						'list'			=> $user->brandTrashItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function legalpages()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Legal Pages Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'legalpages',
						'list'			=> $user->legalpagesTrashItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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
	public function seo()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'SEO Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'seo',
						'list'			=> $user->seoTrashItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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



	public function error()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user = $this->model('Store');
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


	public function testimonials()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Testimonials Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'testimonials',
						'list'			=> $user->testimonialTrashItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function locationcity()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'City Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'locationcity',
						'list'			=> $user->locationcityTrashItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
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


	public function locationgroup()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Location Group Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'locationgroup',
						'list'			=> $user->locationgroupTrashItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
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

	public function location()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Location Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'location',
						'list'			=>  $user->locationTrashItems(),	
						'user_info'		=>  $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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


	public function pagebanner()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Page Banner Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'pagebanner',
						'list'			=> $user->pageBannerTrashItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function state()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'State Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'state',
						'list'			=> $user->pageStateItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
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

	public function branch()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Branch Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'branch',
						'list'			=> $user->pageBranchItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function vendor()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Branch Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'vendor',
						'list'			=> $user->pageVendorItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function category()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Category Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'category',
						'list'			=> $user->pageCategoryItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function subcategory()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Subcategory Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'subcategory',
						'list'			=> $user->pageSubcategoryItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function coupon()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Coupons Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'coupon',
						'list'			=> $user->pageCouponItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function attribute()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Attribute Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'attribute',
						'list'			=> $user->pageAttributeItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function attributegroup()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Attribute Group Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'attributegroup',
						'list'			=> $user->pageAttributeGroupItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function product()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Products Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'product',
						'list'			=> $user->pageProductItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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

	public function notificationemail()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Notification Email Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'notificationemail',
						'list'			=> $user->pageNotiEmailItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
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

	public function productreview()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Products Review Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'productreview',
						'list'			=> $user->pagePrdouctReviewItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
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
	public function newsletter()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Newsletter Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'newsletter',
						'list'			=> $user->newsletterItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
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
	public function returnreason()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Return Reason Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'returnreason',
						'list'			=> $user->returnreasonItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
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
	public function returnsettings()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Return Reason Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'returnsettings',
						'list'			=> $user->returnsettingItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
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

	public function classifiedprofile()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Classified Profile Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'classifiedprofile',
						'list'			=> $user->classifiedprofileItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
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

	public function classifiedproject()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Classified Project Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'classifiedproject',
						'list'			=> $user->classifiedprojectItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
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

	public function workwithusreuest()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Work With Us Request Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'workwithusreuest',
						'list'			=> $user->workWithUsRequestItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
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

	public function productrequeststatus()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Product Request Status Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'productrequeststatus',
						'list'			=> $user->productRequestStatus(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
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

	public function orderresponsestatus()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Order Response Status Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'orderresponsestatus',
						'list'			=> $user->orderResponseStatusItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
						'notification'  => $user->getOrderNotification()
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

	public function productdisplaytag()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Product Display Tag Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'productdisplaytag',
						'list'			=> $user->productDisplayTagtems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
						'notification'  => $user->getOrderNotification()
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

	public function productunit()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('TrashRepo');
			$check = $user->checkPermissionPage(TRASH);
			$check_sitesettings = $user->checkSiteSettings(TRASH);
			if ($check==1) {
				$this->view('home/managetrash', 
					[	
						'active_menu' 	=> 'settings',
						'page_title'	=> 'Product Unit Trash',
						'scripts'		=> 'settings',
						'trash_menu'	=> 'productunit',
						'list'			=> $user->productUnitItems(),	
						'user_info'		=> $user->userInfo(),
						'sitesettings'	=> $user->filtersitesettings(),
						'notification'  => $user->getOrderNotification()
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

	/*-----------Dont'delete---------*/

}


?>