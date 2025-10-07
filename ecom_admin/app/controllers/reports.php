<?php

class Reports extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user               = $this->model('Report');
			$check 		        = $user->checkPermissionPage(REPORTS); 
			$check_sitesettings = $user->checksitesettings(REPORTS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/report', 
					[	
						'active_menu' 	=>  'reports',
						'page_title'	=>  'Reports',
						'scripts'		=>  'dashboard',
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersiteSettings(),
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

	public function order($from="",$to="")
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user               = $this->model('Report');
			$check 		        = $user->checkPermissionPage(ORDER_REPORT); 
			$check_sitesettings = $user->checksitesettings(ORDER_REPORT);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/orderreport', 
					[	
						'active_menu' 	=>  'reports',
						'page_title'	=>  'Reports',
						'scripts'		=>  'dashboard',
						'from_date'		=>  $from,
						'to_date'		=>  $to,
						'card_data'		=>  $user->getOrderReportCardData($from,$to),
						'chart_data'	=>  $user->getOrderReportChartData($from,$to),
						'top_products'	=>  $user->getTopProductList($from,$to),
						'top_vendors'	=>  $user->getTopVendorList($from,$to),
						'order_list'	=>  $user->getCustomerOrderList($from,$to),
						'returned_list'	=>  $user->getCustomerReturnedOrderList($from,$to),
						'rejected_list'	=>  $user->getVendorRejectedOrderList($from,$to),
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersiteSettings(),
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

	public function vendor($vendor="",$from="",$to="")
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user               = $this->model('Report');
			$check 		        = $user->checkPermissionPage(VENDOR_REPORT); 
			$check_sitesettings = $user->checksitesettings(VENDOR_REPORT);
			$vendor_check       = (($vendor=="")? 1 : $user->check_query(VENDOR_TBL,"id","id='".$vendor."'") );
			if ($check==1 && $check_sitesettings==1 && $vendor_check) {
				$vendor_detail       = (($vendor=="")? 0 : $user->getDetails(VENDOR_TBL,"*","id='".$vendor."'") );
				$this->view('home/vendorreport', 
					[	
						'active_menu' 	=>  'reports',
						'page_title'	=>  'Vendor Reports',
						'scripts'		=>  'dashboard',
						'from_date'		=>  $from,
						'to_date'		=>  $to,
						'vendor_detail' =>  $vendor_detail,
						'get_vendors'   =>  $user->getVendors($vendor),
						'card_data'		=>  $user->getVendorOrderReportCardData($vendor,$from,$to),
						'chart_data'	=>  $user->getVendorOrderReportChartData($vendor,$from,$to),
						'top_products'	=>  $user->getTopProductList($from,$to,$vendor),
						'top_vendors'	=>  $user->getTopVendorList($from,$to),
						'order_list'	=>  $user->getCustomerOrderList($from,$to,$vendor),
						'returned_list'	=>  $user->getCustomerReturnedOrderList($from,$to,$vendor),
						'rejected_list'	=>  $user->getVendorRejectedOrderList($from,$to,$vendor),
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersiteSettings(),
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

	public function product($vendor="",$from="",$to="")
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user               = $this->model('Report');
			$check 		        = $user->checkPermissionPage(PRODUCT_REPORT); 
			$check_sitesettings = $user->checksitesettings(PRODUCT_REPORT);
			$vendor_check       = (($vendor=="")? 1 : $user->check_query(VENDOR_TBL,"id","id='".$vendor."'") );
			if ($check==1 && $check_sitesettings==1 && $vendor_check) {
				$vendor_detail       = (($vendor=="")? 0 : $user->getDetails(VENDOR_TBL,"*","id='".$vendor."'") );
				$this->view('home/productreport', 
					[	
						'active_menu' 	=>  'reports',
						'page_title'	=>  'Products Report',
						'scripts'		=>  'dashboard',
						'from_date'		=>  $from,
						'to_date'		=>  $to,
						'vendor_detail' =>  $vendor_detail,
						'get_vendors'   =>  $user->getVendors($vendor),
						'card_data'		=>  $user->getProductReportCardData($vendor),
						'top_products'	=>  $user->getTopProductList($from,$to,$vendor),
						'InventoryList'	=>  $user->getVendorStockList($vendor),
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersiteSettings(),
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
