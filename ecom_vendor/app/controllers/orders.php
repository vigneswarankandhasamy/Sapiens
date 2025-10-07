<?php

class orders extends Controller
{
	
	public function index()
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;
			if ($check==1) {
					$this->view('home/manageorders', 
					[	
						'active_menu' 	=> 'orders',
						'page_title'	=> 'Manage Order',
						'scripts'		=>	'error',
						'list'			=>  $user->manageOrdersList(),
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


	public function orderdetails($token)
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;
			$details 			= $user->check_query(ORDER_TBL,"id"," id='$token' ");

			if ($check==1 && $details) {
				$order_info 			= $user->getDetails(ORDER_TBL,"*"," id='$token' ");
				$respone_status			= $user->check_query(VENDOR_ORDER_ITEM_TBL,"vendor_response,vendor_accept_status"," order_id='".$order_info['id']."' AND vendor_response='1' AND vendor_accept_status='1'  AND vendor_id='".$_SESSION['ecom_vendor_id']."'  ");

				$order_item_tot 		= $user->check_query(ORDER_ITEM_TBL,"id"," order_id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' AND vendor_accept_status='1' ");
				$order_item_info 		= $user->getDetails(ORDER_ITEM_TBL,"*"," order_id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$order_discount_info 	= $user->getDetails(ORDER_ITEM_TBL,"SUM(coupon_value) as couponValue","  order_id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$user_info 				= $user->getDetails(CUSTOMER_TBL,"*"," id=".$order_info['user_id']." ");
				$address_info 			= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id=".$order_info['order_address']." ");
				$inprocess = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='0' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$shipped   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='1' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$delivered = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='2' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$returned   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='3' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$accept_items =  $user->check_query(ORDER_ITEM_TBL,"*"," Order_id='".$order_info['id']."' AND vendor_accept_status='1' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");

				$this->view('home/orderdetails', 
					[	
						'active_menu' 		 => 'orders',
						'page_title'		 => 'Order Details',
						'order_info'		 => $order_info,
						'order_item_tot'	 => $order_item_tot,
						'order_item_info'	 => $order_item_info,
						'cus_info'			 => $user_info,
						'address_info'		 => $address_info,
						'inprocess'			 => $inprocess,
						'respone_status'	 => $respone_status,
						'shipped'			 => $shipped,
						'delivered'			 => $delivered,
						'returned'			 => $returned,
						'order_discount_info'=> $order_discount_info,
						'order_item_status'  => $user->getCSRF("order_item_status"),
						// 'ord_sts_items'      => $user->getOrderstsItems($order_info['id']),
						'order_items' 		 => $user->getOrderItems($order_info['id']),
						'orderstatus_pop_up' => $user->getOrderstsPopUpItems($order_info['id']),
						'ven_order_info'     => $user->getVendorItemToltals($order_info['id'],$accept_items),
						'scripts'			 => 'error',
						'user_info'			 => $user->userInfo(),
						'notification' 		 =>  $user->getOrderNotification(),
						'vendor_active' 	 =>  $user->CheckVendorStatus()
					]);
			}else{
				$this->view('home/error', 
						[	
							'active_menu' 	=>  '',
							'page_title'	=>	'404 - Page Not Found',
							'scripts'		=>	'error',
							'user_info'		=>	$user->userInfo()
						]);
				}
		}else{
				$this->view('home/login',[]);
		}
	}

	public function rejectedorderdetail($token)
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;
			$details 			= $user->check_query(ORDER_TBL,"id"," id='$token' ");

			if ($check==1 && $details) {
				$order_info 			= $user->getDetails(ORDER_TBL,"*"," id='$token' ");
				$respone_status			= $user->check_query(VENDOR_ORDER_ITEM_TBL,"vendor_response,vendor_accept_status"," order_id='".$order_info['id']."' AND vendor_response='1' AND vendor_accept_status='1'  AND vendor_id='".$_SESSION['ecom_vendor_id']."'  ");

				$order_item_tot 		= $user->check_query(ORDER_ITEM_TBL,"id"," order_id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' AND vendor_accept_status='0' ");
				$order_item_info 		= $user->getDetails(ORDER_ITEM_TBL,"*"," order_id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$order_discount_info 	= $user->getDetails(ORDER_ITEM_TBL,"SUM(coupon_value) as couponValue","  order_id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$user_info 				= $user->getDetails(CUSTOMER_TBL,"*"," id=".$order_info['user_id']." ");
				$address_info 			= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id=".$order_info['order_address']." ");
				$inprocess = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='0' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$shipped   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='1' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$delivered = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='2' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$returned   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='3' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$accept_items =  $user->check_query(ORDER_ITEM_TBL,"*"," Order_id='".$order_info['id']."' AND vendor_accept_status='1' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");

				$this->view('home/orderdetails', 
					[	
						'active_menu' 		 => 'orders',
						'page_title'		 => 'Order Details',
						'order_info'		 => $order_info,
						'order_item_tot'	 => $order_item_tot,
						'order_item_info'	 => $order_item_info,
						'cus_info'			 => $user_info,
						'address_info'		 => $address_info,
						'inprocess'			 => $inprocess,
						'respone_status'	 => $respone_status,
						'shipped'			 => $shipped,
						'delivered'			 => $delivered,
						'returned'			 => $returned,
						'order_discount_info'=> $order_discount_info,
						'order_item_status'  => $user->getCSRF("order_item_status"),
						// 'ord_sts_items'      => $user->getOrderstsItems($order_info['id']),
						'order_items' 		 => $user->getOrderItems($order_info['id']),
						'orderstatus_pop_up' => $user->getOrderstsPopUpItems($order_info['id']),
						'ven_order_info'     => $user->getVendorItemToltals($order_info['id'],$accept_items),
						'scripts'			 => 'error',
						'user_info'			 => $user->userInfo(),
						'notification' 		 =>  $user->getOrderNotification(),
						'vendor_active' 	 =>  $user->CheckVendorStatus()
					]);
			}else{
				$this->view('home/error', 
						[	
							'active_menu' 	=>  '',
							'page_title'	=>	'404 - Page Not Found',
							'scripts'		=>	'error',
							'user_info'		=>	$user->userInfo()
						]);
				}
		}else{
				$this->view('home/login',[]);
		}
	}

	public function previewinvoice($token)
	{	
		$user 				= $this->model('Admin');
		if(isset($_SESSION['ecom_vendor_id'])){
			$pdf 			 = $this->model('PdfExport');
	        $order_info 	 = $user->getDetails(ORDER_TBL,"*"," id='".$token."' ");
			$products   	 = $user->manageInvoiceItems($token,$_SESSION['ecom_vendor_id']);
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
		if(isset($_SESSION['ecom_vendor_id'])){
			$pdf 			 = $this->model('PdfExport');
	        $order_info 	 = $user->getDetails(ORDER_TBL,"*"," id='".$token."' ");
			$products   	 = $user->manageInvoiceItems($token,$_SESSION['ecom_vendor_id']);
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

	public function payouts($type="",$from="",$to="")
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
		
				$this->view('home/vendorpayout', 
				[	
					'active_menu' 	=> 'payoutsettings',
					'page_title'	=> 'Vendor '.ucfirst($type).' Payouts',
					'from_date'		=>  $from,
					'to_date'		=>  $to,
					'scripts'		=>	'error',
					'histroy_type'  =>  $type,
					'paid_list'		=>  $user->getVendorPaidlist($from,$to),
					'unpaid_list'	=>  $user->getVendorUnpaidlist($from,$to),
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
							'sitesettings'	=>	$user->filtersiteSettings(),
							'notification'  =>  $user->getOrderNotification(),
							'vendor_active' =>  $user->CheckVendorStatus()
						]);
				}
	}

	public function payoutInvoice($payout_id)
	{	

		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$payout_check       = $user->check_query(VENDOR_PAYOUT_INVOICE_TBL,"id","id='$payout_id'");
			if ($payout_check) {
				$user 			 = $this->model('PdfExport');
				$profile 		 = $this->model('Admin');
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
							'notification'  =>  $user->getOrderNotification(),
							'vendor_active' =>  $user->CheckVendorStatus()
						]);
			}
		}else{
				$this->view('home/login',[]);
		}
	}

	public function orderstatus($token)
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;
			$details 			= $user->check_query(ORDER_TBL,"id"," id='$token' ");
			if ($check==1 && $details) {
				$order_info 		= $user->getDetails(ORDER_TBL,"*"," id='$token' ");
				$order_item_tot 	= $user->check_query(ORDER_ITEM_TBL,"id"," order_id='$token' AND vendor_id='".$_SESSION["ecom_vendor_id"]."' ");
				$this->view('home/orderstatus', 
					[	
						'active_menu' 		 => 'orders',
						'page_title'		 => 'Order Status',
						'order_info'		 => $order_info,
						'order_item_tot'	 => $order_item_tot,
						'ord_sts_items'      => $user->getOrderstsItems($order_info['id']),
						'order_item_status'  => $user->getCSRF("order_item_status"),
						'scripts'			 => 'error',
						'user_info'			 => $user->userInfo(),
						'notification' 		 =>  $user->getOrderNotification(),
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

	public function orderInvoice($token)
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;
			
			$details 			= $user->check_query(ORDER_TBL,"id"," id='$token' ");
			if ($check==1 && $details) {
				$order_info 		= $user->getDetails(ORDER_TBL,"*"," id='$token' ");
				$user_name 			= $user->getDetails(CUSTOMER_TBL,"name"," id=".$order_info['user_id']." ");
				$address_info 		= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id=".$order_info['order_address']." ");
				$vendor_order_info  = $user->inrFormatFields($user->getVendorItemToltals($order_info['id']));
				$this->view('home/orderinvoice', 
					[	
						'active_menu' 		 => 'orders',
						'page_title'		 => 'Order Invoice',
						'order_info'		 => $order_info,
						'user_name'			 => $user_name,
						'address_info'		 => $address_info,
						'order_items' 		 => $user->getOrderItems($order_info['id']),
						'ven_order_info_inr' => $vendor_order_info,
						'ven_order_info'     => $user->getVendorItemToltals($order_info['id']),
						'scripts'			 => 'error',
						'user_info'			 => $user->userInfo(),
						'notification'       =>  $user->getOrderNotification(),
						'vendor_active' 	 =>  $user->CheckVendorStatus()
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

	public function notification($token="")
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;
			if($token=="neworders" || $token=="returnedorders" || $token=="") {
				$check_token = 1;
			} else {
				$check_token = 0;
			}

			if($token=="neworders") {
				$page_title = "New Orders";
			}else if($token=="returnedorders"){
				$page_title = "Returned Orders";
			}else{
				$page_title="";
			}

			if ($check==1 && $check_token) {
					$this->view('home/ordernotification', 
					[	
						'active_menu' 	=>  'orders',
						'page_title'	=>  'Notification',
						'scripts'		=>	'error',
						'token'			=>  $token,
						'list'			=>  $user->ordersNotificationList(),
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

	public function orderlist($token="")
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;
			if($token=="neworders" || $token=="returnedorders" || $token=="") {
				$check_token = 1;
			} else {
				$check_token = 0;
			}

			if($token=="neworders") {
				$page_title = "New Orders";
			}else if($token=="returnedorders"){
				$page_title = "Returned Orders";
			}else{
				$page_title="";
			}

			if ($check==1 && $check_token) {
					$this->view('home/ordernotification', 
					[	
						'active_menu' 	=>  'orders',
						'page_title'	=>  'Notification',
						'scripts'		=>	'error',
						'token'			=>  $token,
						'list'			=>  $user->ordersNotificationList(),
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

	public function neworderdetail($token="")
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;
			$details 			= $user->check_query(VENDOR_ORDER_TBL,"id"," id='$token'  ");
			$respone_status		= $user->check_query(VENDOR_ORDER_TBL,"vendor_response"," id='$token' AND vendor_response='0' AND vendor_id='".$_SESSION['ecom_vendor_id']."'  ");
			if ($details) {
				$v_order_info 			= $user->getDetails(VENDOR_ORDER_TBL,"*"," id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$order_info 			= $user->getDetails(ORDER_TBL,"*"," id='".$v_order_info['order_id']."' ");
				$order_item_tot 		= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"count(id) as count"," order_id='".$order_info['id']."' AND vendor_id='".$v_order_info['vendor_id']."' ");
				$order_item_info 		= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"*"," vendor_order_id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$order_discount_info 	= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"SUM(coupon_value) as couponValue","  vendor_order_id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$user_info 				= $user->getDetails(CUSTOMER_TBL,"*"," id=".$order_info['user_id']." ");
				$address_info 			= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id=".$order_info['order_address']." ");
				$vendor_order_info  	= $user->inrFormatFields($user->getVendorItemToltals($order_info['id']));
				$inprocess = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='0' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$shipped   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='1' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$delivered = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='2' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$returned   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='3' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");


				$this->view('home/neworderdetails', 
					[	
						'active_menu' 		 => 'order_notification',
						'page_title'		 => 'New Order Details',
						'order_info'		 => $order_info,
						'v_order_info'		 => $v_order_info,
						'order_item_tot'	 => $order_item_tot,
						'order_item_info'	 => $order_item_info,
						'cus_info'			 => $user_info,
						'address_info'		 => $address_info,
						'inprocess'			 => $inprocess,
						'respone_status'	 => $respone_status,
						'shipped'			 => $shipped,
						'delivered'			 => $delivered,
						'returned'			 => $returned,
						'order_discount_info'=> $order_discount_info,
						'order_item_status'  => $user->getCSRF("order_item_status"),
						'ord_sts_items'      => $user->getOrderstsItems($order_info['id']),
						'order_items' 		 => $user->getOrderItems($order_info['id']),
						'order_response_prd' => $user->getOrderResponseItems($order_info['id']),
						'ven_order_info_inr' => $vendor_order_info,
						'ven_order_info'     => $user->getVendorItemToltals($order_info['id']),
						'ord_response_sts'	 => $user->getOrderResponseDropDown(),
						'scripts'			 => 'error',
						'user_info'			 => $user->userInfo(),
						'notification' 		 => $user->getOrderNotification(),
						'vendor_active' 	 =>  $user->CheckVendorStatus()
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

	public function returnedorderdetail($token="")
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;
			$details 			= $user->check_query(VENDOR_ORDER_TBL,"id"," id='$token'  ");
			$respone_status		= $user->check_query(VENDOR_ORDER_TBL,"vendor_response"," id='$token' AND vendor_response='0' AND vendor_id='".$_SESSION['ecom_vendor_id']."'  ");
			if ($details) {
				$v_order_info 			= $user->getDetails(VENDOR_ORDER_TBL,"*"," id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$order_info 			= $user->getDetails(ORDER_TBL,"*"," id='".$v_order_info['order_id']."' ");
				$order_item_tot 		= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"count(id) as count"," order_id='".$order_info['id']."' AND vendor_id='".$v_order_info['vendor_id']."' ");
				$order_item_info 		= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"*"," vendor_order_id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$order_discount_info 	= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"SUM(coupon_value) as couponValue","  vendor_order_id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$user_info 				= $user->getDetails(CUSTOMER_TBL,"*"," id=".$order_info['user_id']." ");
				$address_info 			= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id=".$order_info['order_address']." ");
				$vendor_order_info  	= $user->inrFormatFields($user->getVendorItemToltals($order_info['id']));
				$inprocess = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='0' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$shipped   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='1' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$delivered = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='2' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$returned   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='3' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");


				$this->view('home/ordernotificationdetails', 
					[	
						'active_menu' 		 => 'order_notification',
						'page_title'		 => 'Returned Order Details',
						'order_info'		 => $order_info,
						'v_order_info'		 => $v_order_info,
						'order_item_tot'	 => $order_item_tot,
						'order_item_info'	 => $order_item_info,
						'cus_info'			 => $user_info,
						'address_info'		 => $address_info,
						'inprocess'			 => $inprocess,
						'respone_status'	 => $respone_status,
						'shipped'			 => $shipped,
						'delivered'			 => $delivered,
						'returned'			 => $returned,
						'order_discount_info'=> $order_discount_info,
						'order_item_status'  => $user->getCSRF("order_item_status"),
						'ord_sts_items'      => $user->getOrderstsItems($order_info['id']),
						'order_items' 		 => $user->getOrderItems($order_info['id']),
						'order_response_prd' => $user->getOrderResponseItems($order_info['id']),
						'ven_order_info_inr' => $vendor_order_info,
						'ven_order_info'     => $user->getVendorItemToltals($order_info['id']),
						'ord_response_sts'	 => $user->getOrderResponseDropDown(),
						'scripts'			 => 'error',
						'user_info'			 => $user->userInfo(),
						'notification' 		 => $user->getOrderNotification(),
						'vendor_active' 	 =>  $user->CheckVendorStatus()
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


	public function notificationdetail($token="")
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;
			$details 			= $user->check_query(VENDOR_ORDER_TBL,"id"," id='$token'  ");
			$respone_status		= $user->check_query(VENDOR_ORDER_TBL,"vendor_response"," id='$token' AND vendor_response='0' AND vendor_id='".$_SESSION['ecom_vendor_id']."'  ");
			if ($details) {
				$v_order_info 			= $user->getDetails(VENDOR_ORDER_TBL,"*"," id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$order_info 			= $user->getDetails(ORDER_TBL,"*"," id='".$v_order_info['order_id']."' ");
				$order_item_tot 		= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"count(id) as count"," order_id='".$order_info['id']."' AND vendor_id='".$v_order_info['vendor_id']."' ");
				$order_item_info 		= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"*"," vendor_order_id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$order_discount_info 	= $user->getDetails(VENDOR_ORDER_ITEM_TBL,"SUM(coupon_value) as couponValue","  vendor_order_id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$user_info 				= $user->getDetails(CUSTOMER_TBL,"*"," id=".$order_info['user_id']." ");
				$address_info 			= $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id=".$order_info['order_address']." ");
				$vendor_order_info  	= $user->inrFormatFields($user->getVendorItemToltals($order_info['id']));
				$inprocess = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='0' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$shipped   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='1' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$delivered = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='2' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
				$returned   = $user->check_query(ORDER_ITEM_TBL,"order_status","order_status='3' AND order_id='".$order_info['id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");


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
						'respone_status'	 => $respone_status,
						'shipped'			 => $shipped,
						'delivered'			 => $delivered,
						'returned'			 => $returned,
						'order_discount_info'=> $order_discount_info,
						'order_item_status'  => $user->getCSRF("order_item_status"),
						'ord_sts_items'      => $user->getOrderstsItems($order_info['id']),
						'order_items' 		 => $user->getOrderItems($order_info['id']),
						'order_response_prd' => $user->getOrderResponseItems($order_info['id']),
						'ven_order_info_inr' => $vendor_order_info,
						'ven_order_info'     => $user->getVendorItemToltals($order_info['id']),
						'ord_response_sts'	 => $user->getOrderResponseDropDown(),
						'scripts'			 => 'error',
						'user_info'			 => $user->userInfo(),
						'notification' 		 => $user->getOrderNotification(),
						'vendor_active' 	 =>  $user->CheckVendorStatus()
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

	public function vendorrejectedorders()
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;
			if ($check==1) {
					$this->view('home/vendorrejectedorders', 
					[	
						'active_menu' 	=> 'orders',
						'page_title'	=> 'Manage Rejected Order',
						'scripts'		=>	'error',
						'list'			=>  $user->vendorrejectedOrdersList(),
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

	public function vendoractiveorders()
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;
			if ($check==1) {
					$this->view('home/vendoractiveorders', 
					[	
						'active_menu' 		 => 'orders',
						'page_title'		 => 'Manage Rejected Order',
						'scripts'			 =>	'error',
						'order_item_status'  => $user->getCSRF("order_item_status"),
						'list'			     => $user->vendorActiveOrdersList(),
						'user_info'		     =>	$user->userInfo(),
						'notification'       => $user->getOrderNotification(),
						'vendor_active' 	 =>  $user->CheckVendorStatus()
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


	// vendor order tab start
	public function vendorordertab()
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;
			if ($check==1) {
					$this->view('home/vendorordertab', 
					[	
						'active_menu' 		 => 'orders',
						'page_title'		 => 'Manage Orders',
						'scripts'			 =>	'error',
						'order_item_status'  => $user->getCSRF("order_item_status"),
						'manageorderlist'	 => $user->manageOrdersList(),
						'list'		 		 => $user->ordersNotificationList(),
						'rejected_orders'	 => $user->vendorrejectedOrdersList(),
						'user_info'		     =>	$user->userInfo(),
						'notification'       => $user->getOrderNotification(),
						'vendor_active' 	 => $user->CheckVendorStatus()
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
	// vendor order tab end

	public function api($type)
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user  = $this->model('Admin');
			$post = @$_POST["result"];	
			switch ($type) {

				case 'changeOrderStatus':
					echo $user->changeOrderStatus($_POST);
				break;
				case 'orderItemStatusChange':
					echo $user->orderItemStatusChange($_POST);
				break;
				case 'orderNotificationResponse':
					echo $user->orderNotificationResponse($_POST);
				break;

				case 'getOrderstsPopUpItems':
				    echo $user->getOrderstsPopUpItems($post);
				break;
				
				default:
				break;
			}
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