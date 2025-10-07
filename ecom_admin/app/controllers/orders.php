<?php

class orders extends Controller
{
	public function index($from="",$to="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(CUSTOMER_ORDERS);
			$check_sitesettings = $user->checkSiteSettings(CUSTOMER_ORDERS);
			if ($check==1 && $check_sitesettings==1) {
					$this->view('home/manageorders', 
					[	
						'active_menu' 	=> 'ordersettings',
						'page_title'	=> 'Manage Customer Order',
						'scripts'		=>	'error',
						'from_date'		=>  $from,
						'to_date'		=>  $to,
						'list'			=>  $user->manageOrdersList($from,$to),
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

	public function orderdetails($token)
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(CUSTOMER_ORDERS);
			$check_sitesettings = $user->checkSiteSettings(CUSTOMER_ORDERS);
			$details 			= $user->check_query(ORDER_TBL,"id"," id='$token' ");
			if ($check==1 && $check_sitesettings==1 && $details) {
				$order_info   		= $user->getDetails(ORDER_TBL,"*"," id='$token' ");
				$order_user_info    = $user->getDetails(CUSTOMER_TBL,"*"," id=".$order_info['user_id']." ");
				$address_info 		= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id=".$order_info['order_address']." ");
				$inr_format  		= $user->inrFormatFields($order_info);
				$inprocess = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='0' AND order_id='".$order_info['id']."' ");
				$shipped   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='1' AND order_id='".$order_info['id']."' ");
				$delivered = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='2' AND order_id='".$order_info['id']."' ");
				$returned  = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='3' AND order_id='".$order_info['id']."' ");

				$item_total 		   = $user->getDetails(ORDER_ITEM_TBL,"SUM(sub_total + total_tax ) as total","order_id='".$order_info['id']."' ");
				$rejected_item_total   = $user->getDetails(ORDER_ITEM_TBL,"SUM(sub_total + total_tax ) as total","order_id='".$order_info['id']."' AND vendor_response='1' AND vendor_accept_status='0'  ");
				$coupon_total 		   = $user->getDetails(ORDER_ITEM_TBL,"SUM(COALESCE(coupon_value, 0)) as total","order_id='".$order_info['id']."' ");
				$rejected_coupon_total = $user->getDetails(ORDER_ITEM_TBL,"SUM(COALESCE(coupon_value, 0)) as total","order_id='".$order_info['id']."' AND vendor_response='1' AND vendor_accept_status='0'  ");

				$order_item_tot 	= $user->getDetails(ORDER_ITEM_TBL,"count(id) as count"," order_id='".$order_info['id']."' ");

				if(!$order_item_tot['count']) {
					$order_total  = $item_total['total'];
					$coupon_total = $coupon_total['total'];
				} else {
					$order_total  = $item_total['total'] - $rejected_item_total['total'] ;
					$coupon_total = $coupon_total['total'] - $rejected_coupon_total['total'] ;
				}

				$this->view('home/orderdetails', 
					[	
						'active_menu' 			=> 'ordersettings',
						'page_title'			=> 'Customer Order Details',
						'order_info'			=> $order_info,
						'order_item_tot'	 	=> $order_item_tot,
						'inr_format'   			=> $inr_format,
						'order_user_info'		=> $order_user_info,
						'address_info'			=> $address_info,
						'order_total'			=> $order_total,
						'coupon_total'			=> $coupon_total,
						'inprocess'				=> $inprocess,
						'shipped'				=> $shipped,
						'delivered'				=> $delivered,
						'returned'				=> $returned,
						'order_item_status'  	=> $user->getCSRF("order_item_status"),
						'order_items' 			=> $user->getOrderItems($order_info['id']),
						'orderstatus_pop_up' 	=> $user->getOrderstsPopUpItems($order_info['id']),
						'ven_order_invoice' 	=> $user->getVendorInvoices($order_info['id']),
						'scripts'				=> 'error',
						'user_info'				=> $user->userInfo(),
						'sitesettings'			=> $user->filtersiteSettings(),
						'notification'      	=> $user->getOrderNotification()
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

	public function vendorOrders($from="",$to="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(VENDOR_ORDERS);
			$check_sitesettings = $user->checkSiteSettings(VENDOR_ORDERS);
			if ($check==1 && $check_sitesettings==1) {
					$this->view('home/managevendororders', 
					[	
						'active_menu' 	=> 'ordersettings',
						'page_title'	=> 'Manage Vendor Order',
						'scripts'		=>	'error',
						'from_date'		=>  $from,
						'to_date'		=>  $to,
						'list'			=>  $user->manageVendorOrdersList($from,$to),
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

	public function vendororderdetails($token)
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(VENDOR_ORDERS);
			$check_sitesettings = $user->checkSiteSettings(VENDOR_ORDERS);
			$details 			= $user->check_query(VENDOR_ORDER_TBL,"id"," id='$token' ");
			if ($check==1 && $check_sitesettings==1 && $details) {
				$vendor_order_info  = $user->getDetails(VENDOR_ORDER_TBL,"*"," id='$token' ");
				$order_info  		= $user->getDetails(ORDER_TBL,"*"," id=".$vendor_order_info['order_id']." ");
				$respone_status		= $user->check_query(VENDOR_ORDER_ITEM_TBL,"vendor_response"," vendor_order_id='$token' AND vendor_response='1' AND vendor_accept_status='1' AND vendor_id='".$vendor_order_info['vendor_id']."' ");
				$vendor_order_item  = $user->getDetails(ORDER_ITEM_TBL,"*"," order_id='".$vendor_order_info['order_id']."' AND vendor_id='".$vendor_order_info['vendor_id']."' LIMIT 1 ");
				$order_user_info    = $user->getDetails(CUSTOMER_TBL,"*"," id=".$vendor_order_info['user_id']." ");
				$vendor_info        = $user->getDetails(VENDOR_TBL,"*"," id=".$vendor_order_info['vendor_id']." ");
				$address_info 		= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id=".$vendor_order_info['order_address']." ");
				$inr_format  		= $user->inrFormatFields($vendor_order_info);
				$inprocess = $user->check_query(VENDOR_ORDER_TBL,"order_status","order_status='0' AND order_id='".$vendor_order_info['order_id']."' ");
				$shipped   = $user->check_query(VENDOR_ORDER_TBL,"order_status","order_status='1' AND order_id='".$vendor_order_info['order_id']."' ");
				$delivered = $user->check_query(VENDOR_ORDER_TBL,"order_status","order_status='2' AND order_id='".$vendor_order_info['order_id']."' ");
				$returned = $user->check_query(VENDOR_ORDER_TBL,"order_status","order_status='3' AND order_id='".$vendor_order_info['order_id']."' ");

				$this->view('home/vendororderdetails', 
					[	
						'active_menu' 		=> 'ordersettings',
						'page_title'		=> 'Vendor Order Details',
						'order_info'		=> $order_info,
						'v_order_info'		=> $vendor_order_info,
						'inr_format'   		=> $inr_format,
						'order_user_info'	=> $order_user_info,
						'respone_status'    => $respone_status,
						'vendor_info'		=> $vendor_info,
						'vendor_order_item' => $vendor_order_item,
						'address_info'		=> $address_info,
						'inprocess'			=> $inprocess,
						'shipped'			=> $shipped,
						'delivered'			=> $delivered,
						'returned'			=> $returned,
						'ven_order_invoice' => $user->getVendOrderInvoices($vendor_order_info['order_id'],$vendor_order_info['vendor_id']),
						'scripts'			=> 'error',
						'user_info'			=> $user->userInfo(),
						'sitesettings' 		=> $user->filtersiteSettings(),
						'notification'      => $user->getOrderNotification()
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

	public function previewcustomerinvoice($token)
	{	
		if(isset($_SESSION['ecom_admin_id'])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(ORDERS);
			$check_sitesettings = $user->checkSiteSettings(ORDERS);
			if ($check==1 && $check_sitesettings==1) {
				$user 			 = $this->model('PdfExport');
				$admin           = $this->model('Admin');
				$order_item_info = $user->getDetails(ORDER_ITEM_TBL,"*"," id='$token' ");
		        $order_info 	 = $user->getDetails(ORDER_TBL,"*"," id='".$order_item_info['order_id']."' ");
				$products   	 = $admin->manageInvoiceItems($order_info['id'],$order_item_info['vendor_id'],"customer_invoice");
				$content 		 = $user->customerInvoicePdf($token,$products);
				// Export as PDF
				$user->preview($content);
			} else {
				$user = $this->model('User');
				$this->view('home/error', 
					[
						'meta' 				=>  'dynamic',
						'cart'   			=>  $user->cartInfo(),
						'legal_pages'		=>  $user->getLegalPages(),
						'menu_items'		=>  $user->menuItems(),
						'siteinfo' 			=>  $user->siteInfo($page_token="error"),
						'cart'   			=>  $user->cartInfo(),
						'user'   			=>  $user->userInfo()
					]);
			}
		}else{
				$this->view('home/login',[]);
		}
	}

	public function previewvendorinvoice($token)
	{	
		if(isset($_SESSION['ecom_admin_id'])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(ORDERS);
			$check_sitesettings = $user->checkSiteSettings(ORDERS);
			if ($check==1 && $check_sitesettings==1) {
				$user 			 = $this->model('PdfExport');
				$admin           = $this->model('Admin');
				$order_item_info = $user->getDetails(ORDER_ITEM_TBL,"*"," id='$token' ");
		        $order_info 	 = $user->getDetails(ORDER_TBL,"*"," id='".$order_item_info['order_id']."' ");
				$products   	 = $admin->manageInvoiceItems($order_info['id'],$order_item_info['vendor_id'],"vendor_invoice");

				// var_dump($products);
				// return false;
				$content 		 = $user->vendorInvoicePdf($token,$products);
				// Export as PDF
				$user->preview($content);
			}else{
				$user = $this->model('User');
				$this->view('home/error', 
					[
						'meta' 				=>  'dynamic',
						'cart'   			=>  $user->cartInfo(),
						'legal_pages'		=>  $user->getLegalPages(),
						'menu_items'		=>  $user->menuItems(),
						'siteinfo' 			=>  $user->siteInfo($page_token="error"),
						'cart'   			=>  $user->cartInfo(),
						'user'   			=>  $user->userInfo()
					]);
			}
		}else{
				$this->view('home/login',[]);
		}
	}


	public function vendorpayouts($token="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(VENDOR_PAYOUTS);
			$check_sitesettings = $user->checkSiteSettings(VENDOR_PAYOUTS);
			if($token=="") {
				$vendor_check = 1;
			} else {
				$vendor_check = $user->checkVendorInPayout($token);
			}
			if ($check==1 && $check_sitesettings==1 && $vendor_check) {
					$this->view('home/vendorpayouts', 
					[	
						'active_menu' 		=> 'vendorsettings',
						'active_sub_menu' 	=> 'today',
						'page_title'		=> 'Vendor Payouts',
						'scripts'			=>	'error',
						'today_list'		=>  $user->manageVendorTodayOrders($token),
						'yesterday_list'	=>  $user->manageVendorYesterdayOrders($token),
						'oldest_list'		=>  $user->manageVendorOldestOrders($token),
						'get_vendors'   	=>  $user->getPayoutVendors($current="",$token),
 						'user_info'			=>	$user->userInfo(),
						'sitesettings'		=>	$user->filtersiteSettings(),
						'notification'      =>  $user->getOrderNotification()
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

	public function vendorpayout($period,$vendor_id)
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(VENDOR_PAYOUTS);
			$check_sitesettings = $user->checkSiteSettings(VENDOR_PAYOUTS);
			$vendor_check       = $user->check_query(VENDOR_TBL,"id","id='$vendor_id'");
			if ($check==1 && $check_sitesettings==1 && $vendor_check) {
				$vendor_info    = $user->getDetails(VENDOR_TBL,"*","id='$vendor_id'");
					$this->view('home/vendorpayout', 
					[	
						'active_menu' 	=> 'vendorsettings',
						'page_title'	=> 'Vendor Payouts',
						'scripts'		=>	'error',
						'vendor_info'   =>  $vendor_info,
						'data_counts'   =>  $user->getVendorDataCounts($vendor_id,$period),
						'list'			=>  $user->getVendorUnpaidlist($vendor_id,$period),
						'paid_list'		=>  $user->getVendorPaidlist($vendor_id,$period),
						'period'        =>  $period,
 						'user_info'		=>	$user->userInfo(),
 						'vendor_id' 	=>  $vendor_id,
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

	public function unpaidpayoutdetails($vendor_id,$period="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(VENDOR_PAYOUTS);
			$check_sitesettings = $user->checkSiteSettings(VENDOR_PAYOUTS);
			$vendor_check       = $user->check_query(VENDOR_TBL,"id","id='$vendor_id'");
			if ($check==1 && $check_sitesettings==1 && $vendor_check) {
				$vendor_info    = $user->getDetails(VENDOR_TBL,"*","id='$vendor_id'");
					$this->view('home/unpaidpayoutdetails', 
					[	
						'active_menu' 	=> 'vendorsettings',
						'page_title'	=> 'Vendor Payouts',
						'scripts'		=>	'error',
						'vendor_info'   =>  $vendor_info,
						'data_counts'   =>  $user->getVendorDataCounts($vendor_id,$period),
						'list'			=>  $user->getVendorUnpaidlist($vendor_id,$period),
						'paid_list'		=>  $user->getVendorPaidlist($vendor_id,$period),
						'period'        =>  $period,
 						'user_info'		=>	$user->userInfo(),
 						'vendor_id' 	=>  $vendor_id,
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

	public function vendorpayoutdetails($vendor_id,$period="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(VENDOR_PAYOUTS);
			$check_sitesettings = $user->checkSiteSettings(VENDOR_PAYOUTS);
			$vendor_check       = $user->check_query(VENDOR_TBL,"id","id='$vendor_id'");
			if ($check==1 && $check_sitesettings==1 && $vendor_check) {
				$vendor_info    = $user->getDetails(VENDOR_TBL,"*","id='$vendor_id'");
					$this->view('home/vendorpayout', 
					[	
						'active_menu' 	=> 'vendorsettings',
						'page_title'	=> 'Vendor Payouts',
						'scripts'		=>	'error',
						'vendor_info'   =>  $vendor_info,
						'data_counts'   =>  $user->getVendorDataCounts($vendor_id,$period),
						'list'			=>  $user->getVendorUnpaidlist($vendor_id,$period),
						'paid_list'		=>  $user->getVendorPaidlist($vendor_id,$period),
						'period'        =>  $period,
 						'user_info'		=>	$user->userInfo(),
 						'vendor_id' 	=>  $vendor_id,
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


	public function payoutInvoice($payout_id)
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(VENDOR_PAYOUTS);
			$check_sitesettings = $user->checkSiteSettings(VENDOR_PAYOUTS);
			$payout_check       = $user->check_query(VENDOR_PAYOUT_INVOICE_TBL,"id","id='$payout_id'");
			if ($check==1 && $check_sitesettings==1 && $payout_check) {
				$user 			 = $this->model('PdfExport');
				$profile 		 = $this->model('Order');
				$payout_info   	 = $user->getDetails(VENDOR_PAYOUT_INVOICE_TBL,"*","id='$payout_id'");
				$payouts    	 = $profile->manageVendorPayouts($payout_id);
				$content 		 = $user->vendorPayoutInvoicePdf($payout_info['vendor_id'],$payouts,$payout_id);
				// Export as PDF
				$user->preview($content);
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
	
	public function payouthistory($type)
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$p_token 			= (($type=="paid")? VENDOR_PAIED_HISTORY : VENDOR_UNPAIED_HISTORY );
			$check 				= $user->checkPermissionPage($p_token);
			$check_sitesettings = $user->checkSiteSettings($p_token);
			if ($check==1 && $check_sitesettings==1 ) {
					$this->view('home/vendorpayouthistory', 
					[	
						'active_menu' 	=> 'vendorsettings',
						'page_title'	=> 'Vendor '.ucfirst($type).' Histroy',
						'scripts'		=>	'error',
						'histroy_type'  =>  $type,
 						'paid_list'		=>  $user->getPaidHistroy(),
 						'unpaid_list'   =>  $user->getUnpaidlist(),
						'get_vendors'   =>  $user->getPayoutVendors($current="",$type),
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

	public function status($token="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user = $this->model('Order');
			if($token=="new" || $token=="returned" || $token=="") {
				$check_token = 1;
			} else {
				$check_token = 0;
			}
			$p_token  = (($token=="new")? NEW_ORDERS : (($token=="returned")? RETURNED_ORDERS : ORDERS ) );
			$check 	  = $user->checkPermissionPage($p_token);
			$check_sitesettings = $user->checkSiteSettings($p_token);
			if ($check==1 && $check_token && $check_sitesettings==1) {
					$this->view('home/orderstatuslist', 
					[	
						'active_menu' 	=> 'ordersettings',
						'page_title'	=>  ucfirst($token).' Orders',
						'scripts'		=>	'error',
						'token'			=>  $token,
						'list'			=>  $user->ordersStatusList(),
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification(),
					]);
			}else{
				$this->view('home/error', 
						[	
							'active_menu' 	=>  '',
							'page_title'	=>	'404 - Page Not Found',
							'scripts'		=>	'error',
							'user_info'		=>	$user->userInfo(),
							'sitesettings'	=>	$user->filtersiteSettings(),
							'notification'  =>  $user->getOrderNotification(),
						]);
				}
		}else{
				$this->view('home/login',[]);
		}
	}
	
	public function rejected()
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(REJECTED_ORDERS);
			$check_sitesettings = $user->checkSiteSettings(REJECTED_ORDERS);
			if ($check==1 && $check_sitesettings==1) {
					$this->view('home/rejectedorderlist', 
					[	
						'active_menu' 	=> 'ordersettings',
						'page_title'	=>  'Rejected Orders',
						'scripts'		=>	'error',
						'list'			=>  $user->rejectedOrdersList(),
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification(),
					]);
			}else{
				$this->view('home/error', 
						[	
							'active_menu' 	=>  '',
							'page_title'	=>	'404 - Page Not Found',
							'scripts'		=>	'error',
							'user_info'		=>	$user->userInfo(),
							'sitesettings'	=>	$user->filtersiteSettings(),
							'notification'  =>  $user->getOrderNotification(),
						]);
				}
		}else{
				$this->view('home/login',[]);
		}
	}

	public function notification($token="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(VENDOR_ORDERS);
			if($token=="neworders" || $token=="returnedorders" || $token=="") {
				$check_token = 1;
			} else {
				$check_token = 0;
			}
			if ($check==1 && $check_token) {
					$this->view('home/ordernotification', 
					[	
						'active_menu' 	=> 'ordersettings',
						'page_title'	=> 'Notifications',
						'scripts'		=>	'error',
						'token'			=>  $token,
						'list'			=>  $user->ordersNotificationList(),
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification(),
					]);
			}else{
				$this->view('home/error', 
						[	
							'active_menu' 	=>  '',
							'page_title'	=>	'404 - Page Not Found',
							'scripts'		=>	'error',
							'user_info'		=>	$user->userInfo(),
							'sitesettings'	=>	$user->filtersiteSettings(),
							'notification'  =>  $user->getOrderNotification(),
						]);
				}
		}else{
				$this->view('home/login',[]);
		}
	}

	public function notificationdetail($token="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(VENDOR_ORDERS);
			$details 			= $user->check_query(VENDOR_ORDER_TBL,"id"," id='$token' ");
			$respone_status		= $user->check_query(VENDOR_ORDER_TBL,"vendor_response"," id='$token' AND vendor_response!='1'  ");
			if ($details) {
				$v_order_info 			= $user->getDetails(VENDOR_ORDER_TBL,"*"," id='$token' ");
				$order_info 			= $user->getDetails(ORDER_TBL,"*"," id='".$v_order_info['order_id']."' ");
				$order_item_tot 		= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"count(id) as count"," order_id='".$order_info['id']."' AND vendor_id='".$v_order_info['vendor_id']."' ");
				$order_item_info 		= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"*"," vendor_order_id='$token' ");
				$order_discount_info 	= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"SUM(coupon_value) as couponValue","  vendor_order_id='$token' ");
				$user_info 				= $user->getDetails(CUSTOMER_TBL,"*"," id=".$order_info['user_id']." ");
				$address_info 			= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id=".$order_info['order_address']." ");
				$vendor_order_info  	= $user->inrFormatFields($user->getVendorItemToltals($order_info['id'],$v_order_info['vendor_id']));
				$inprocess 	= $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='0' AND order_id='".$order_info['id']."' ");
				$shipped   	= $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='1' AND order_id='".$order_info['id']."' ");
				$delivered 	= $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='2' AND order_id='".$order_info['id']."' ");
				$returned   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='3' AND order_id='".$order_info['id']."' ");


				$this->view('home/ordernotificationdetails', 
					[	
						'active_menu' 		 => 'order_notification',
						'page_title'		 => 'Notification Details',
						'order_info'		 => $order_info,
						'v_order_info'		 => $v_order_info,
						'order_item_tot'	 => $order_item_tot,
						'order_item_info'	 => $order_item_info,
						'cus_info'			 => $user_info,
						'address_info'		 => $address_info,
						'inprocess'			 => $inprocess,
						'shipped'			 => $shipped,
						'respone_status'	 => $respone_status,
						'delivered'			 => $delivered,
						'returned'			 => $returned,
						'order_discount_info'=> $order_discount_info,
						'order_item_status'  => $user->getCSRF("order_item_status"),
						'ven_order_invoice'  => $user->getVendOrderInvoices($v_order_info['order_id'],$v_order_info['vendor_id']),
						'ven_order_info_inr' => $vendor_order_info,
						'ven_order_info'     => $user->getVendorItemToltals($order_info['id'],$v_order_info['vendor_id']),
						'order_response_prd' => $user->getOrderResponseItems($order_info['id'],$v_order_info['vendor_id']),
						'ord_response_sts'	 => $user->getOrderResponseDropDown(),
						'scripts'			 => 'error',
						'user_info'			 => $user->userInfo(),
						'sitesettings'		 =>	$user->filtersiteSettings(),
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
						'sitesettings'	=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification()
					]);
				}
		}else{
				$this->view('home/login',[]);
		}
	}

	public function replace($token="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(VENDOR_ORDERS);
			$details 			= $user->check_query(VENDOR_ORDER_TBL,"id"," id='$token' ");
			if ($details==1 && $check==1) {
				$v_order_info 	     = $user->getDetails(VENDOR_ORDER_TBL,"*"," id='$token' ");
				$order_info 	     = $user->getDetails(ORDER_TBL,"*"," id='".$v_order_info['order_id']."' ");
				$vendor_info 	     = $user->getDetails(VENDOR_TBL,"*","id='".$v_order_info['vendor_id']."'");		
				$d_address           = $user->getDetails(CUSTOMER_ADDRESS_TBL,"*","id='".$order_info['order_address']."'");
				$check_cancel_status = $user->check_query(VENDOR_ORDER_ITEM_TBL,"*"," vendor_order_id='".$v_order_info['id']."' AND cancel_status='1' ");

				$this->view('home/orderreplace', 
					[	
						'active_menu' 		  	  => 'order_notification',
						'page_title'		  	  => 'Order Replace',
						'order_info'		  	  => $order_info,
						'v_order_info'		  	  => $v_order_info,
						'vendor_info'		  	  => $vendor_info,
						'd_address'			  	  => $d_address,
						'check_cancel_status' 	  => $check_cancel_status,
						'rejected_items'      	  => $user->getVendorRejectedItems($v_order_info['id'],$v_order_info['vendor_id']),
						'vendor_list'	  		  => $user->getVendorListForReplace($v_order_info['id']),
						'rejected_product_ids'	  => $user->getRejectedOrderItems($v_order_info['id']),
						'order_replace_details'   => $user->getReplacedOrderDetails($v_order_info['id']),
 						'csrf_order_replace'  	  => $user->getCSRF("order_replace"),
						'scripts'			  	  => 'error',
						'user_info'			  	  => $user->userInfo(),
						'sitesettings'		  	  => $user->filtersiteSettings(),
						'notification' 		  	  => $user->getOrderNotification()
					]);
			} else {
				$this->view('home/error', 
					[	
						'active_menu' 	=>  '',
						'page_title'	=>	'404 - Page Not Found',
						'scripts'		=>	'error',
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersiteSettings(),
						'sitesettings'	=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification()
					]);
				}
		}else{
				$this->view('home/login',[]);
		}
	}

	public function orderstab($from="",$to="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(CUSTOMER_ORDERS);
			$check_sitesettings = $user->checkSiteSettings(CUSTOMER_ORDERS);
			if ($check==1 && $check_sitesettings==1) {
					$this->view('home/orderstab', 
					[	
						'active_menu' 	=> 'ordersettings',
						'page_title'	=> 'Manage Orders',
						'scripts'		=>	'error',
						'from_date'		=>  $from,
						'to_date'		=>  $to,
						'list'			=>  $user->ordersNotificationList(),
						'r_o_list'		=>  $user->rejectedOrdersList(),
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

	public function neworderdetail($token="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(VENDOR_ORDERS);
			$details 			= $user->check_query(VENDOR_ORDER_TBL,"id"," id='$token' ");
			$respone_status		= $user->check_query(VENDOR_ORDER_TBL,"vendor_response"," id='$token' AND vendor_response!='1'  ");
			if ($details) {
				$v_order_info 			= $user->getDetails(VENDOR_ORDER_TBL,"*"," id='$token' ");
				$order_info 			= $user->getDetails(ORDER_TBL,"*"," id='".$v_order_info['order_id']."' ");
				$order_item_tot 		= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"count(id) as count"," order_id='".$order_info['id']."' AND vendor_id='".$v_order_info['vendor_id']."' ");
				$order_item_info 		= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"*"," vendor_order_id='$token' ");
				$order_discount_info 	= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"SUM(coupon_value) as couponValue","  vendor_order_id='$token' ");
				$user_info 				= $user->getDetails(CUSTOMER_TBL,"*"," id=".$order_info['user_id']." ");
				$address_info 			= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id=".$order_info['order_address']." ");
				$vendor_order_info  	= $user->inrFormatFields($user->getVendorItemToltals($order_info['id'],$v_order_info['vendor_id']));
				$inprocess 	= $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='0' AND order_id='".$order_info['id']."' ");
				$shipped   	= $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='1' AND order_id='".$order_info['id']."' ");
				$delivered 	= $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='2' AND order_id='".$order_info['id']."' ");
				$returned   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='3' AND order_id='".$order_info['id']."' ");


				$this->view('home/neworderdetails', 
					[	
						'active_menu' 		 => 'ordersettings',
						'page_title'		 => 'New Order Details',
						'order_info'		 => $order_info,
						'v_order_info'		 => $v_order_info,
						'order_item_tot'	 => $order_item_tot,
						'order_item_info'	 => $order_item_info,
						'cus_info'			 => $user_info,
						'address_info'		 => $address_info,
						'inprocess'			 => $inprocess,
						'shipped'			 => $shipped,
						'respone_status'	 => $respone_status,
						'delivered'			 => $delivered,
						'returned'			 => $returned,
						'order_discount_info'=> $order_discount_info,
						'order_item_status'  => $user->getCSRF("order_item_status"),
						'ven_order_invoice'  => $user->getVendOrderInvoices($v_order_info['order_id'],$v_order_info['vendor_id']),
						'ven_order_info_inr' => $vendor_order_info,
						'ven_order_info'     => $user->getVendorItemToltals($order_info['id'],$v_order_info['vendor_id']),
						'order_response_prd' => $user->getOrderResponseItems($order_info['id'],$v_order_info['vendor_id']),
						'ord_response_sts'	 => $user->getOrderResponseDropDown(),
						'scripts'			 => 'error',
						'user_info'			 => $user->userInfo(),
						'sitesettings'		 =>	$user->filtersiteSettings(),
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
						'sitesettings'	=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification()
					]);
				}
		}else{
				$this->view('home/login',[]);
		}
	}

	public function returnedorderdetail($token="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(VENDOR_ORDERS);
			$details 			= $user->check_query(VENDOR_ORDER_TBL,"id"," id='$token' ");
			$respone_status		= $user->check_query(VENDOR_ORDER_TBL,"vendor_response"," id='$token' AND vendor_response!='1'  ");
			if ($details) {
				$v_order_info 			= $user->getDetails(VENDOR_ORDER_TBL,"*"," id='$token' ");
				$order_info 			= $user->getDetails(ORDER_TBL,"*"," id='".$v_order_info['order_id']."' ");
				$order_item_tot 		= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"count(id) as count"," order_id='".$order_info['id']."' AND vendor_id='".$v_order_info['vendor_id']."' ");
				$order_item_info 		= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"*"," vendor_order_id='$token' ");
				$order_discount_info 	= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"SUM(coupon_value) as couponValue","  vendor_order_id='$token' ");
				$user_info 				= $user->getDetails(CUSTOMER_TBL,"*"," id=".$order_info['user_id']." ");
				$address_info 			= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id=".$order_info['order_address']." ");
				$vendor_order_info  	= $user->inrFormatFields($user->getVendorItemToltals($order_info['id'],$v_order_info['vendor_id']));
				$inprocess 	= $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='0' AND order_id='".$order_info['id']."' ");
				$shipped   	= $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='1' AND order_id='".$order_info['id']."' ");
				$delivered 	= $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='2' AND order_id='".$order_info['id']."' ");
				$returned   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='3' AND order_id='".$order_info['id']."' ");


				$this->view('home/neworderdetails', 
					[	
						'active_menu' 		 => 'ordersettings',
						'page_title'		 => 'Returned Order Details',
						'order_info'		 => $order_info,
						'v_order_info'		 => $v_order_info,
						'order_item_tot'	 => $order_item_tot,
						'order_item_info'	 => $order_item_info,
						'cus_info'			 => $user_info,
						'address_info'		 => $address_info,
						'inprocess'			 => $inprocess,
						'shipped'			 => $shipped,
						'respone_status'	 => $respone_status,
						'delivered'			 => $delivered,
						'returned'			 => $returned,
						'order_discount_info'=> $order_discount_info,
						'order_item_status'  => $user->getCSRF("order_item_status"),
						'ven_order_invoice'  => $user->getVendOrderInvoices($v_order_info['order_id'],$v_order_info['vendor_id']),
						'ven_order_info_inr' => $vendor_order_info,
						'ven_order_info'     => $user->getVendorItemToltals($order_info['id'],$v_order_info['vendor_id']),
						'order_response_prd' => $user->getOrderResponseItems($order_info['id'],$v_order_info['vendor_id']),
						'ord_response_sts'	 => $user->getOrderResponseDropDown(),
						'scripts'			 => 'error',
						'user_info'			 => $user->userInfo(),
						'sitesettings'		 =>	$user->filtersiteSettings(),
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
						'sitesettings'	=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification()
					]);
				}
		}else{
				$this->view('home/login',[]);
		}
	}

	public function rejectedorderdetail($token="")
	{	
		if(isset($_SESSION["ecom_admin_id"])){
			$user 				= $this->model('Order');
			$check 				= $user->checkPermissionPage(VENDOR_ORDERS);
			$details 			= $user->check_query(VENDOR_ORDER_TBL,"id"," id='$token' ");
			$respone_status		= $user->check_query(VENDOR_ORDER_TBL,"vendor_response"," id='$token' AND vendor_response!='1'  ");
			if ($details) {
				$v_order_info 			= $user->getDetails(VENDOR_ORDER_TBL,"*"," id='$token' ");
				$order_info 			= $user->getDetails(ORDER_TBL,"*"," id='".$v_order_info['order_id']."' ");
				$order_item_tot 		= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"count(id) as count"," order_id='".$order_info['id']."' AND vendor_id='".$v_order_info['vendor_id']."' ");
				$order_item_info 		= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"*"," vendor_order_id='$token' ");
				$order_discount_info 	= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"SUM(coupon_value) as couponValue","  vendor_order_id='$token' ");
				$user_info 				= $user->getDetails(CUSTOMER_TBL,"*"," id=".$order_info['user_id']." ");
				$address_info 			= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id=".$order_info['order_address']." ");
				$vendor_order_info  	= $user->inrFormatFields($user->getVendorItemToltals($order_info['id'],$v_order_info['vendor_id']));
				$inprocess 	= $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='0' AND order_id='".$order_info['id']."' ");
				$shipped   	= $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='1' AND order_id='".$order_info['id']."' ");
				$delivered 	= $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='2' AND order_id='".$order_info['id']."' ");
				$returned   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='3' AND order_id='".$order_info['id']."' ");

				$check_cancel_status = $user->check_query(VENDOR_ORDER_ITEM_TBL,"id"," vendor_order_id='".$v_order_info['id']."' AND cancel_status='1' ");


				$this->view('home/neworderdetails', 
					[	
						'active_menu' 		 => 'ordersettings',
						'page_title'		 => 'Rejected Order Details',
						'order_info'		 => $order_info,
						'v_order_info'		 => $v_order_info,
						'order_item_tot'	 => $order_item_tot,
						'order_item_info'	 => $order_item_info,
						'cus_info'			 => $user_info,
						'address_info'		 => $address_info,
						'inprocess'			 => $inprocess,
						'check_cancel_status'=> $check_cancel_status,
						'shipped'			 => $shipped,
						'respone_status'	 => $respone_status,
						'delivered'			 => $delivered,
						'returned'			 => $returned,
						'order_discount_info'=> $order_discount_info,
						'order_item_status'  => $user->getCSRF("order_item_status"),
						'ven_order_invoice'  => $user->getVendOrderInvoices($v_order_info['order_id'],$v_order_info['vendor_id']),
						'ven_order_info_inr' => $vendor_order_info,
						'ven_order_info'     => $user->getVendorItemToltals($order_info['id'],$v_order_info['vendor_id']),
						'order_response_prd' => $user->getOrderResponseItems($order_info['id'],$v_order_info['vendor_id']),
						'ord_response_sts'	 => $user->getOrderResponseDropDown(),
						'scripts'			 => 'error',
						'user_info'			 => $user->userInfo(),
						'sitesettings'		 =>	$user->filtersiteSettings(),
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
						'sitesettings'	=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification()
					]);
				}
		}else{
				$this->view('home/login',[]);
		}
	}

	public function previewinvoice($token)
	{	
		$user 				= $this->model('Admin');
		if(isset($_SESSION['ecom_admin_id'])){
			$pdf 			 = $this->model('PdfExport');
	        $order_info 	 = $user->getDetails(ORDER_TBL,"*"," id='".$token."' ");
			$products   	 = $user->manageInvoiceItems($token);
			$content 		 = $user->vendorInvoicePdf($token,$products);
			// Export as PDF
			$pdf->preview($content);
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
	}

	public function downloadInvoice($token)
	{	
		$user 				= $this->model('Admin');
		if(isset($_SESSION['ecom_admin_id'])){
			$pdf 			 = $this->model('PdfExport');
	        $order_info 	 = $user->getDetails(ORDER_TBL,"*"," id='".$token."' ");
			$products   	 = $user->manageInvoiceItems($token);
			$content 		 = $user->vendorInvoicePdf($token,$products);
			// Export as PDF
			$pdf->export($content);
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
	}

	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user    = $this->model('Order');
			$orders  = $this->model('Order');
			$post = @$_POST["result"];	
			switch ($type) {

				case 'changeOrderStatus':
					echo $user->changeOrderStatus($_POST);
				break;
				case 'vendorPayoutRecords':
					echo $user->vendorPayoutRecords($_POST);
				break;
				case 'vendorShort':
					echo $user->vendorShort($_POST);
				break;
				case 'orderNotificationResponse':
					echo $user->orderNotificationResponse($_POST);
				break;
				case 'replaceVendorPriceDetails' :
					echo $user->replaceVendorPriceDetails($_POST);
				break;
				case 'cancellRejectedOrder' :
					echo $user->cancellRejectedOrder($_POST);
				break;
				case 'replaceOrder' :
					echo $orders->replaceOrder($_POST);
				break;
				case 'orderItemStatusChange':
					echo $user->orderItemStatusChange($_POST);
				break;
				default:
				break;
			}
		}
	}

	public function error()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user = $this->model('Order');
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