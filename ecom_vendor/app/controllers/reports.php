<?php

class reports extends Controller
{
	
	public function index($from="",$to="")
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;
			if ($check==1) {
					$vendor_detail  =  $user->getDetails(VENDOR_TBL,"*","id='".$_SESSION["ecom_vendor_id"]."'");
					$this->view('home/reports', 
					[	
						'active_menu' 	=> 'reports',
						'page_title'	=> 'Reports',
						'scripts'		=>	'error',
						'from_date'		=>  $from,
						'to_date'		=>  $to,
						'vendor_detail' =>  $vendor_detail,
						'card_data'		=>  $user->getOrderReportCardData($from,$to),
						'chart_data'	=>  $user->getOrderReportChartData($from,$to),
						'top_products'	=>  $user->getTopProductList($from,$to),
						'order_list'	=>  $user->getCustomerOrderList($from,$to),
						'returned_list'	=>  $user->getCustomerReturnedOrderList($from,$to),
						'rejected_list'	=>  $user->getCustomerRejectedOrderList($from,$to),
						'user_info'		=>	$user->userInfo(),
						'notification'  =>  $user->getOrderNotification(),
						'vendor_active' =>  $user->CheckVendorStatus()
					]);
				
			}else{
				$this->view('home/error', 
						[	
							'active_menu' 	=>  '',
							'page_title'	=>	'404 - Page Not Found',
							'scripts'		=>	'error',
							'user_info'		=>	$user->userInfo(),
							'notification'  =>  $user->getOrderNotification(),
							'vendor_active' =>  $user->CheckVendorStatus()
						]);
				}
		}else{
				$this->view('home/login',[]);
		}
	}

	public function error()
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user = $this->model('Admin');
			$this->view('home/error', 
				[	
					'active_menu' 	=>  '',
					'page_title'	=>	'404 - Page Not Found',
					'scripts'		=>	'error',
					'user_info'		=>	$user->userInfo(),
					'notification'  =>  $user->getOrderNotification(),
					'vendor_active' =>  $user->CheckVendorStatus()
				]);
		}else{
			$this->view('home/login',[]);
		}
	}

}


?>