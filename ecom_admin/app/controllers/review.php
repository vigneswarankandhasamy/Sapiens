<?php

class review extends Controller
{
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('MarketingModel');
			$check 				= $user->checkPermissionPage(PRODUCT_REVIEW);
			$check_sitesettings = $user->checksitesettings(PRODUCT_REVIEW);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/managereviewlist', 
				[	
					'active_menu' 	=> 'marketing',
			        'page_title'    => 'Manage Product Reviews',
			        'meta_title'    => 'Manage Product Reviews - '.COMPANY_NAME,
			        'scripts' 		=>  'datatable',
					'user_info'		=>  $user->userInfo(),
			        'list' 			=>  $user->manageProductReview(),
					'sitesettings'	=>	$user->filtersiteSettings(),
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
			$user = $this->model('MarketingModel');
			$post = @$_POST["result"];	
			switch ($type) {
				
				case 'trashproductReview':
					echo $user->trashproductReview($post);
				break;
				case 'info':
					echo $user->getReveiwDetails($post);
				break;
				case 'approvalstatus':
					echo $user->reveiwApprovalStatus($post);
				break;
				case 'status':
					echo $user->changeReveiwStatus($post);
				break;

				case 'reviewReplay':
					echo $user->reviewReplay($_POST);
				break;

				case 'restoreProductReview':
					echo $user->restoreProductReview($post);
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