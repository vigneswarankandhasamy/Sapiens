<?php

class testimonials extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(MANAGE_TESTIMONIALS);
			$check_sitesettings = $user->checkSiteSettings(MANAGE_TESTIMONIALS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/testimonials', 
					[	
						'active_menu' 	 => 'store',
						'page_title'	 => 'Manage Testimonials',
						'scripts'		 => 'settings',
						'user_info'		 =>  $user->userInfo(),
						'list'			 =>	 $user->manageTestimonials(),
						'sitesettings'	 =>	 $user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification()
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

	public function add()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Store');
			$check 				= $user->checkPermissionPage(MANAGE_TESTIMONIALS);
			$check_sitesettings = $user->checkSiteSettings(MANAGE_TESTIMONIALS);
			$info  				= $user->getDetails(TESTIMONIALS_TBL,"*"," 1 ORDER BY id DESC "); 
			$sort_order 		= (($info==true)? $info['sort_order'] + 1 : 1 );
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/addtestimonials', 
					[	
						'active_menu' 	 		=> 'store',
						'page_title'	 		=> 'Add Testimonials',
						'scripts'		 		=> 'addtestimonials',
						'user_info'		 		=> $user->userInfo(),
						'sort_order'     		=> $sort_order,
						'csrf_add_testimonials' => $user->getCSRF("add_testimonials"),
						'sitesettings'			=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification()
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
			$check 				= $user->checkPermissionPage(MANAGE_TESTIMONIALS);
			$details 			= $user->check_query(TESTIMONIALS_TBL,"id"," id='$token' ");
			$check_sitesettings = $user->checkSiteSettings(MANAGE_TESTIMONIALS);
			if ($details==1 && $check_sitesettings==1 && $check==1) {
				$info = $user->getDetails(TESTIMONIALS_TBL,"*"," id='$token'  ");
				$this->view('home/edittestimonials', 
					[	
						'active_menu' 			=> 'store',
						'page_title'			=> 'Edit Testimonials',
						'scripts'				=> 'edittestimonials',
						'info'					=> $info,
						'token'					=> $user->encryptData($info['id']),
						'user_info'				=> $user->userInfo(),
						'csrf_edit_testimonials'=> $user->getCSRF("edit_testimonials"),
						'sitesettings'			=>	$user->filtersiteSettings(),
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

	

	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Store');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'add':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->addTestimonials($_POST,$file);
				break;
				case 'update':
					$upload = new FileUploader();
					$file = $upload->uploadFile($_FILES, $_POST);
					echo $user->editTestimonials($_POST,$file);
				break;
				case 'status':
					echo $user->changeTestimonialStatus($post);
				break;
				case 'delete':
					echo $user->deleteTestimonial($post);
				break;
				case 'trash':
					echo $user->trashTestimonial($post);
				break;
				case 'publish':
					echo $user->publishTestimonial($post);
				break;
				case 'restore':
					echo $user->restoreTestimonial($post);
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