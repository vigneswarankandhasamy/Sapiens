<?php

class paymentsettings extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user   			= $this->model('Settings');
			$check  			= $user->checkPermissionPage(BLOG);
			$cash_info 			= $user->getDetails(PAYMENT_SETTINGS_TBL,"*"," id='1'  ");
			$razorpay_info 		= $user->getDetails(PAYMENT_SETTINGS_TBL,"*"," id='2'  ");
			$count  			= $user->check_query(PAYMENT_SETTINGS_TBL,"*","1");
			$check_sitesettings = $user->checkSiteSettings("paymentsettings"); 
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/managepaymentsetting', 
					[	
						'active_menu' 		 			=> 'settings',
						'page_title'		 			=> 'Payment Settings',
						'scripts'			 			=> 'addpayment',
						'user_info'			 			=>  $user->userInfo(),
						'payment_menu'					=> 'payment_settings',
						'count'							=>  $count,
						'cash_info'						=>	$cash_info,
						'razorpay_info'					=>  $razorpay_info,
						'csrf_edit_payment_settings' 	=>  $user->getCSRF("edit_payment_settings"),
						'sitesettings'					=>	$user->filtersiteSettings(),
						'notification'  				=>  $user->getOrderNotification()
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
			$user  = $this->model('Settings');
			$post  = @$_POST["result"];	
			switch ($type) {
				case 'updatePaymentSettings':
					echo $user->editPayment($_POST);
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