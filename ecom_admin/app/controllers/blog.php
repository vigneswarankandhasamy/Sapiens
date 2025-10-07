<?php

class blog extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(BLOG);
			$check_sitesettings = $user->checkSiteSettings(BLOG);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/bloglist', 
					[	
						'active_menu' 	 => 'store',
						'page_title'	 => 'Manage Blog',
						'scripts'		 => 'settings',
						'user_info'		 =>  $user->userInfo(),
						'list'			 =>	 $user->manageBlog(),
						'sitesettings'	 =>	 $user->filtersiteSettings(),
						'notification'   =>  $user->getOrderNotification()

					]);
			}else{
				$this->view('home/error', 
					[	
						'active_menu' 	=>  '',
						'page_title'	=>	'404 - Page Not Found',
						'scripts'		=>	'error',
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	 $user->filtersiteSettings(),
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
			$check 				= $user->checkPermissionPage(BLOG);
			$check_sitesettings = $user->checkSiteSettings(BLOG);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/addblog', 
					[	
						'active_menu' 	 => 'store',
						'page_title'	 => 'Add Blog',
						'scripts'		 => 'addblog',
						'user_info'		 => $user->userInfo(),
						'category'		 => $user->getblogCategory(),
						'csrf_add_blog'  => $user->getCSRF("add_blog"),
						'sitesettings'	 =>	$user->filtersiteSettings(),
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

	
	public function edit($token="")
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(BLOG);
			$check_sitesettings = $user->checkSiteSettings(BLOG);
			$details 			= $user->check_query(BLOG_TBL,"id"," id='$token' ");
			if ($details==1 && $check_sitesettings==1 && $check==1) {
				$info = $user->getDetails(BLOG_TBL,"*"," id='$token'  ");
				$this->view('home/editblog', 
					[	
						'active_menu' 	=> 'store',
						'page_title'	=> 'Edit Blog',
						'scripts'		=> 'editblog',
						'info'			=> $info,
						'token'			=> $user->encryptData($info['id']),
						'category'		=> $user->getblogCategory($info['category_id']),
						'user_info'		=> $user->userInfo(),
						'csrf_edit_blog'=> $user->getCSRF("edit_blog"),
						'sitesettings'	=>	 $user->filtersiteSettings(),
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
					'sitesettings'	=>	 $user->filtersiteSettings(),
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
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(BLOG_CATEGORIES);
			$check_sitesettings = $user->checkSiteSettings(BLOG_CATEGORIES);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/blogcategorylist', 
					[	
						'active_menu' 		 => 'store',
						'page_title'		 => 'Manage Blog Category',
						'scripts'			 => 'settings',
						'user_info'			 => $user->userInfo(),
						'csrf_add_blog_cat'  => $user->getCSRF("add_blog_category"),
						'csrf_edit_blog_cat' => $user->getCSRF("edit_blog_category"),
						'list'				 =>	$user->manageBlogCategory(),
						'sitesettings'	 	 =>	 $user->filtersiteSettings(),
						'notification' 		 =>  $user->getOrderNotification()

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

	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Store');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'add':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->addBlog($_POST,$file);
				break;
				case 'update':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->editBlog($_POST,$file);
				break;
				case 'status':
					echo $user->changeBlogStatus($post);
				break;
				case 'delete':
					echo $user->deleteBlog($post);
				break;
				case 'trash':
					echo $user->trashBlog($post);
				break;
				case 'publish':
					echo $user->publishBlog($post);
				break;
				case 'restore':
					echo $user->restoreBlog($post);
				break;
				case 'addBlogCategory':
					echo $user->addBlogCategory($_POST);
				break;
				case 'updateBlogCategory':
					echo $user->editBlogCategory($_POST);
				break;
				case 'infoBlogCategory':
					echo $user->getBlogCategoryDetails($post);
				break;
				case 'statusBlogCategory':
					echo $user->changeBlogCategoryStatus($post);
				break;
				case 'deleteBlogCategory':
					echo $user->deleteBlogCategory($post);
				break;
				case 'restoreCategory':
					echo $user->restoreBlogCategory($post);
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