<?php

//http://localhost/venpep/sapiens_ecom/myaccount/previewinvoice/5

class Myaccount	 extends Controller
{
	public function index()
	{		
		
		$user = $this->model('Front');
		$seo_info = $user->getDetails(SEO_TBL,"*","page_token='error'");
		$this->view('home/error', 
			[	
				'meta_title'  		=>  $seo_info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  	=>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description'  =>  $seo_info['meta_description'].' | '.COMPANY_NAME,
				'meta'  			=> 'dynamic',
				'cart'   			=>  $user->cartInfo(),
				'location' 			=>  $user->getLocationList(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo($page_token="error"),
				'page_title'  		=> '404 Error - Page Not Found'
				
			]);
	}

	

	public function notification($token="")
	{	
		$user = $this->model('Profile');
		$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='edit_profile' AND is_draft='0' AND status='1' ");
		if(isset($_SESSION['user_session_id'])){
			if(!isset($_SESSION['edit_profile_key'])){
				$_SESSION['edit_profile_key'] = $user->generateRandomString("40");
			}
			if(!isset($_SESSION['change_password_key'])){
				$_SESSION['change_password_key'] = $user->generateRandomString("40");
			}
		$this->view('home/notification', 
		[	
			'active_menu' 		=> 'editprofile',
			'meta_title'  		=> 'Edit Profile '.COMPANY_NAME,
			'meta_keywords'  	=> 'Edit Profile '.COMPANY_NAME,
			'meta_description'  => 'Edit Profile '.COMPANY_NAME,
			'meta' 				=> 'static',
			'cart'   			=>  $user->cartInfo(),
			'legal_pages'		=>  $user->getLegalPages(),
			'menu_items'		=>  $user->menuItems(),
			'siteinfo' 			=>  $user->siteInfo(),
			'page_banner'		=>  $page_banner,
			'location' 			=>  $user->getLocationList(),
			'user'   			=>  $user->userInfo(),
		]);
		}else{
			$user = $this->model('User');
            $this->view('home/login', 
            [ 
                'meta_title'  		=>  'Customer Login',
				'meta_keywords'  	=>  'Customer  Login',
				'meta_description'  =>  'Customer Login',
				'meta' 				=>  'static',
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo(),
				'location' 			=>  $user->getLocationList(),
				'user'   			=>  $user->userInfo()
            ]);
        }		
	}

	public function mobilemenu($token="")
	{	
		$user = $this->model('Profile');
		$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='edit_profile' AND is_draft='0' AND status='1' ");
		if(isset($_SESSION['user_session_id'])){
			if(!isset($_SESSION['edit_profile_key'])){
				$_SESSION['edit_profile_key'] = $user->generateRandomString("40");
			}
			if(!isset($_SESSION['change_password_key'])){
				$_SESSION['change_password_key'] = $user->generateRandomString("40");
			}
		$this->view('home/mobilemenu', 
		[	
			'active_menu' 		=> 'editprofile',
			'meta_title'  		=> 'Edit Profile '.COMPANY_NAME,
			'meta_keywords'  	=> 'Edit Profile '.COMPANY_NAME,
			'meta_description'  => 'Edit Profile '.COMPANY_NAME,
			'meta' 				=> 'static',
			'cart'   			=>  $user->cartInfo(),
			'legal_pages'		=>  $user->getLegalPages(),
			'menu_items'		=>  $user->menuItems(),
			'siteinfo' 			=>  $user->siteInfo(),
			'page_banner'		=>  $page_banner,
			'location' 			=>  $user->getLocationList(),
			'user'   			=>  $user->userInfo(),
		]);
		}else{
			$user = $this->model('User');
            $this->view('home/login', 
            [ 
                'meta_title'  		=>  'Customer Login',
				'meta_keywords'  	=>  'Customer  Login',
				'meta_description'  =>  'Customer Login',
				'meta' 				=>  'static',
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo(),
				'location' 			=>  $user->getLocationList(),
				'user'   			=>  $user->userInfo()
            ]);
        }		
	}
	

	public function edit($token="")
	{	
		$user = $this->model('Profile');
		$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='edit_profile' AND is_draft='0' AND status='1' ");
		if(isset($_SESSION['user_session_id'])){
			if(!isset($_SESSION['edit_profile_key'])){
				$_SESSION['edit_profile_key'] = $user->generateRandomString("40");
			}
			if(!isset($_SESSION['change_password_key'])){
				$_SESSION['change_password_key'] = $user->generateRandomString("40");
			}
		$this->view('home/editprofile', 
		[	
			'active_menu' 		=> 'editprofile',
			'meta_title'  		=> 'Edit Profile '.COMPANY_NAME,
			'meta_keywords'  	=> 'Edit Profile '.COMPANY_NAME,
			'meta_description'  => 'Edit Profile '.COMPANY_NAME,
			'meta' 				=> 'static',
			'cart'   			=>  $user->cartInfo(),
			'legal_pages'		=>  $user->getLegalPages(),
			'menu_items'		=>  $user->menuItems(),
			'siteinfo' 			=>  $user->siteInfo(),
			'page_banner'		=>  $page_banner,
			'location' 			=>  $user->getLocationList(),
			'user'   			=>  $user->userInfo(),
		]);
		}else{
			$user = $this->model('User');
            $this->view('home/login', 
            [ 
                'meta_title'  		=>  'Customer Login',
				'meta_keywords'  	=>  'Customer  Login',
				'meta_description'  =>  'Customer Login',
				'meta' 				=>  'static',
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo(),
				'location' 			=>  $user->getLocationList(),
				'user'   			=>  $user->userInfo()
            ]);
        }		
	}

	public function changepassword($token="")
	{	
		$user = $this->model('Profile');
		$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='changepassword' AND is_draft='0' AND status='1' ");
		if(isset($_SESSION['user_session_id'])){
			if(!isset($_SESSION['change_password_key'])){
				$_SESSION['change_password_key'] = $user->generateRandomString("40");
			}
			$this->view('home/changepassword', 
			[	
				'active_menu' 		=> 'changepassword',
				'meta_title'  		=> 'Change Password '.COMPANY_NAME,
				'meta_keywords'  	=> 'Change Password '.COMPANY_NAME,
				'meta_description'  => 'Change Password '.COMPANY_NAME,
				'meta' 				=> 'static',
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'page_banner'		=>  $page_banner,
				'siteinfo' 			=>  $user->siteInfo(),
				'location' 			=>  $user->getLocationList(),
				'user'   			=>  $user->userInfo()
			]);
		}else{
			$user = $this->model('User');
            $this->view('home/login', 
            [ 
                'meta_title'  		=>  'Customer Login',
				'meta_keywords'  	=>  'Customer Login',
				'meta_description'  =>  'Customer Login',
				'meta' 				=>  'static',
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo(),
				'location' 			=>  $user->getLocationListForAddress(),
				'user'   			=>  $user->userInfo()
            ]);
        }		
	}

	public function manageaddress($token="")
	{	

		if(isset($_SESSION['user_session_id'])){
			$user = $this->model('Profile');
			$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='manageaddress' AND is_draft='0' AND status='1' ");
			if(!isset($_SESSION['new_shipping_address_key'])){
				$_SESSION['new_shipping_address_key'] = $user->generateRandomString("40");
			}

			$this->view('home/manageaddress', 
			[	
				'active_menu' 		=> 'manageaddress',
				'meta_title'  		=> 'Home',
				'meta_keywords'  	=> '',
				'meta_description'  => '',
				'page_title'  		=> '',
				'meta' 				=>  'static', 
				'address'			=>  $user->manageAddress(),
				'cart'   			=>  $user->cartInfo(),
				'state_list'	    =>  $user->getStatelist(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'page_banner'		=>  $page_banner,
				'siteinfo' 			=>  $user->siteInfo(),
				'location_address'	=>  $user->getLocationListForAddress(),
				'location' 			=>  $user->getLocationList(),
				'user'   			=>  $user->userInfo()
			]);
		}else{
			$user = $this->model('User');
            $this->view('home/login', 
            [ 
                'meta_title'  		=>  'Customer Login',
				'meta_keywords'  	=>  'Customer Login',
				'meta_description'  =>  'Customer Login',
				'meta' 				=>  'static',
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo(),
				'location' 			=>  $user->getLocationList(),
				'user'   			=>  $user->userInfo()
            ]);
        }		
	}

	public function wishlist($token="")
	{	

		if(isset($_SESSION['user_session_id'])){
			$user = $this->model('Profile');
			$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='wishlist' AND is_draft='0' AND status='1' ");
			$this->view('home/managewishlist', 
			[	
				'active_menu' 		=> 'managewishlist',
				'meta_title'  		=> 'Home',
				'meta_keywords'  	=> 'Wishlist '.COMPANY_NAME,
				'meta_description'  => 'Wishlist '.COMPANY_NAME,
				'page_title'  		=> 'Wishlist '.COMPANY_NAME,
				'meta' 				=>  'static', 
				'list'				=>  $user->getProductWishList(),
				'address'			=>  $user->manageAddress(),
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'page_banner'		=>  $page_banner,
				'menu_items'		=>  $user->menuItems(),
				'location' 			=>  $user->getLocationList(),
				'siteinfo' 			=>  $user->siteInfo(),
				'user'   			=>  $user->userInfo()
			]);
		}else{
			$user = $this->model('User');
            $this->view('home/login', 
            [ 
                'meta_title'  		=>  'Customer Login',
				'meta_keywords'  	=>  'Customer Login',
				'meta_description'  =>  'Customer Login',
				'meta' 				=>  'static',
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo(),
				'location' 			=>  $user->getLocationList(),
				'user'   			=>  $user->userInfo()
            ]);
        }		
	}



	public function myorders($token="")
	{	

		if(isset($_SESSION['user_session_id'])){
			$user                 = $this->model('Profile');
			$page_banner          = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='myorders' AND is_draft='0' AND status='1' ");
			$get_page_count       = $user->getDetails(PAGINATION_TBL,"pagination_count"," status='1' AND delete_status='0' ");
			$page_amount 	      = 4; //$get_page_count['pagination_count'];
			$search_order_key     = ((isset($_GET['order_search_key']))? $_GET['order_search_key'] : "" );
			$search_date['from']  = ((isset($_GET['order_from']))? date("Y-m-d",strtotime($_GET['order_from'])) : "" );
			$search_date['to']    = ((isset($_GET['order_to']))? date("Y-m-d",strtotime($_GET['order_to'])) : "" );
			$count                = $user->myordersPaginationCount($page_amount,$search_order_key,$search_date);

			if (isset($_GET['p'])) {
				$page = @$_GET['p'];
			}else{
				$page = 1;
			}

			if(isset($_GET['order_search_key'])) {
				$page_link = BASEPATH."myaccount/myorders?order_search_key=".$_GET['order_search_key']."&"; 
			} else {
				$page_link = BASEPATH."myaccount/myorders?"; 
			}

			if(isset($_GET['order_from'])) {
				$page_link = BASEPATH."myaccount/myorders?order_from=".$_GET['order_from']."&"; 
				if(isset($_GET['order_to'])) {
					$page_link = BASEPATH."myaccount/myorders?order_from=".$_GET['order_from']."&order_to=".$_GET['order_to']."&"; 
				}
			}
			
			if ($page==1) {
				$previous = "javascript:void();";
			} else {
				$previous = $page_link."p=".($page-1);
			}

			if ($page < $count) {
				$next = $page_link."p=".($page+1);
			}else {
				$next = "javascript:void();";
			}


			$this->view('home/managemyorders', 
			[	
				'active_menu' 		=> 'managemyorders',
				'meta_title'  		=> 'Home',
				'meta_keywords'  	=> 'My Orders '.COMPANY_NAME,
				'meta_description'  => 'My Orders '.COMPANY_NAME,
				'page_title'  		=> 'My Orders '.COMPANY_NAME,
				'meta' 				=>  'static', 
				'list'				=>  $user->manageMyOrders($page,$page_amount,$search_order_key,$search_date),
				'address'			=>  $user->manageAddress(),
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'page_banner'		=>  $page_banner,
				'previous' 			=>  $previous,
				'next' 				=>  $next,
				'count'				=>  $count,
				'page' 				=>  $user->myordersPagination($page,$page_link,$page_amount,$search_order_key,$search_date),
				'menu_items'		=>  $user->menuItems(),
				'location' 			=>  $user->getLocationList(),
				'siteinfo' 			=>  $user->siteInfo(),
				'user'   			=>  $user->userInfo()
			]);
		}else{
			$user = $this->model('User');
            $this->view('home/login', 
            [ 
                'meta_title'  		=>  'Customer Login',
				'meta_keywords'  	=>  'Customer Login',
				'meta_description'  =>  'Customer Login',
				'meta' 				=>  'static',
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'location' 			=>  $user->getLocationList(),
				'siteinfo' 			=>  $user->siteInfo(),
				'user'   			=>  $user->userInfo()
            ]);
        }		
	}

	public function orderitem($token="")
	{	

		if(isset($_SESSION['user_session_id'])){
			$user = $this->model('Profile');
			$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='calculator' AND is_draft='0' AND status='1' ");

			if(!isset($_SESSION['order_return_key'])){
				$_SESSION['order_return_key'] = $user->generateRandomString("40");
			}

			$check_order      = $user->check_query(ORDER_TBL,"id"," id='".$token."' ");
	        if($check_order){

				$order_info  = $user->getDetails(ORDER_TBL,"id"," id='".$token."' ");

				$this->view('home/orderitem', 
				[	
					'active_menu' 		=> 'managemyorders',
					'meta_title'  		=> 'Home',
					'meta_keywords'  	=> 'My Orders '.COMPANY_NAME,
					'meta_description'  => 'My Orders '.COMPANY_NAME,
					'page_title'  		=> 'My Orders '.COMPANY_NAME,
					'meta' 				=>  'static', 
					'list'				=>  $user->manageMyOrderItem($order_info['id']),
					'address'			=>  $user->manageAddress(),
					'return_reasons' 	=>  $user->getReturnReason(),
					'cart'   			=>  $user->cartInfo(),
					'legal_pages'		=>  $user->getLegalPages(),
					'menu_items'		=>  $user->menuItems(),
					'siteinfo' 			=>  $user->siteInfo(),
					'location' 			=>  $user->getLocationList(),
					'user'   			=>  $user->userInfo()
				]);

			} else {
				$user = $this->model('Front');
				$seo_info = $user->getDetails(SEO_TBL,"*","page_token='error'");
				$this->view('home/error', 
					[	
						'meta_title'  		=>  $seo_info['meta_title'].' | '.COMPANY_NAME,
						'meta_keywords'  	=>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
						'meta_description'  =>  $seo_info['meta_description'].' | '.COMPANY_NAME,
						'meta'  			=> 'dynamic',
						'cart'   			=>  $user->cartInfo(),
						'location' 			=>  $user->getLocationList(),
						'legal_pages'		=>  $user->getLegalPages(),
						'menu_items'		=>  $user->menuItems(),
						'siteinfo' 			=>  $user->siteInfo($page_token="error"),
						'page_title'  		=> '404 Error - Page Not Found'
						
					]);
				}
		}else{
			$user = $this->model('User');
	        $this->view('home/login', 
	        [ 
	            'meta_title'  		=>  'Customer Login',
				'meta_keywords'  	=>  'Customer Login',
				'meta_description'  =>  'Customer Login',
				'meta' 				=>  'static',
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo(),
				'location' 			=>  $user->getLocationList(),
				'user'   			=>  $user->userInfo()
	        ]);
	    }		
	}

	public function invoice($token="")
	{	
		$user = $this->model('Profile');
		$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='calculator' AND is_draft='0' AND status='1' ");
		if(isset($_SESSION['user_session_id'])){
			$check = $user->check_query(ORDER_ITEM_TBL,"id"," id='".$token."' ");
	        if($check){
	            $order_item_info = $user->getDetails(ORDER_ITEM_TBL,"*"," id='".$token."' ");
	            $order_info 	 = $user->getDetails(ORDER_TBL,"*"," id='".$order_item_info['order_id']."' ");
	            $vendor_info 	 = $user->getDetails(VENDOR_TBL,"*"," id='".$order_item_info['vendor_id']."' ");
	            $order_address 	 = $order_info['order_address'];
	            $ship_address  	 = $user->getDetails(CUSTOMER_ADDRESS_TBL,"*"," user_id='".$_SESSION['user_session_id']."' AND id='$order_address' ");
	            $cus_info        = $user->getDetails(CUSTOMER_TBL,"*","id='".$order_item_info['user_id']."' ");
	            
				$this->view('home/invoice', 
				 	[   
					    'active_menu'   	=> 'vieworderdetails',
					    'meta_title'  		=>  'Order Information',
						'meta_keywords'  	=>  'Order Information',
						'meta_description'  =>  'Order Information',
						'meta' 				=>  'static',
					    'order_item_info'	=>  $order_item_info,
					    'vendor_info'	    =>  $vendor_info,
					    'order_info'		=>  $order_info,
					    'cus_info'			=>  $cus_info,
					    'ship'				=>  $ship_address,
					    'page_title'    	=> 'View Order Details',
					    'products'			=>  $user->manageInvoiceItems($order_info['id'],$order_item_info['vendor_id']),
						'siteinfo' 			=>  $user->siteInfo($page_token="invoice"),
						'header_cart' 		=>  $user->headercartProducts(),
						'location' 			=>  $user->getLocationList(),
						'user'   			=>  $user->userInfo()
				 	]);
			}else{
				$user = $this->model('Front');
				$seo_info = $user->getDetails(SEO_TBL,"*","page_token='error'");
				$this->view('home/error', 
					[	
						'meta_title'  		=>  $seo_info['meta_title'].' | '.COMPANY_NAME,
						'meta_keywords'  	=>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
						'meta_description'  =>  $seo_info['meta_description'].' | '.COMPANY_NAME,
						'meta'  			=> 'dynamic',
						'cart'   			=>  $user->cartInfo(),
						'location' 			=>  $user->getLocationList(),
						'legal_pages'		=>  $user->getLegalPages(),
						'menu_items'		=>  $user->menuItems(),
						'siteinfo' 			=>  $user->siteInfo($page_token="error"),
						'page_title'  		=> '404 Error - Page Not Found'
						
					]);
				}
		}else{
            $this->view('home/login', 
                [ 
	                'meta_title'  		=> 'Customer Login',
					'meta_keywords'  	=> 'Customer Login',
					'meta_description'  => 'Customer Login',
	                'meta' 				=> 'static',
	                'page_title'     	=> 'Customer Login',
					'siteinfo' 			=>  $user->siteInfo($page_token="login"),
					'header_cart' 		=>  $user->headercartProducts(),
					'location' 			=>  $user->getLocationList(),
					'user'   			=>  $user->userInfo()
                ]);
        }		
	}

	public function previewinvoice($token)
	{	
		if(isset($_SESSION['user_session_id'])){
			$user 			 = $this->model('PdfExport');
			$profile 		 = $this->model('Profile');
			$details 		 = $user->check_query(ORDER_TBL,"id"," id='".$token."' ");
			$order_item_info = $user->getDetails(ORDER_ITEM_TBL,"*"," id='".$token."' ");
	        $order_info 	 = $user->getDetails(ORDER_TBL,"*"," id='".$order_item_info['order_id']."' ");
			$products   	 = $profile->manageInvoiceItems($order_info['id'],$order_item_info['vendor_id']);
			$content 		 = $user->vendorInvoicePdf($token,$products);
			// Export as PDF
			$user->preview($content);
		}else{
			$user = $this->model('Front');
			$seo_info = $user->getDetails(SEO_TBL,"*","page_token='error'");
			$this->view('home/error', 
				[	
					'meta_title'  		=>  $seo_info['meta_title'].' | '.COMPANY_NAME,
					'meta_keywords'  	=>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
					'meta_description'  =>  $seo_info['meta_description'].' | '.COMPANY_NAME,
					'meta'  			=> 'dynamic',
					'cart'   			=>  $user->cartInfo(),
					'location' 			=>  $user->getLocationList(),
					'legal_pages'		=>  $user->getLegalPages(),
					'menu_items'		=>  $user->menuItems(),
					'siteinfo' 			=>  $user->siteInfo($page_token="error"),
					'page_title'  		=> '404 Error - Page Not Found'
					
				]);
		}
	}

	public function downloadInvoice($token)
	{	
		if(isset($_SESSION['user_session_id'])){
			$user 			 = $this->model('PdfExport');
			$profile 		 = $this->model('Profile');
			$details 		 = $user->check_query(ORDER_TBL,"id"," id='".$token."' ");
			$order_item_info = $user->getDetails(ORDER_ITEM_TBL,"*"," id='".$token."' ");
	        $order_info 	 = $user->getDetails(ORDER_TBL,"*"," id='".$order_item_info['order_id']."' ");
			$products   	 = $profile->manageInvoiceItems($order_info['id'],$order_item_info['vendor_id']);
			$content 		 = $user->vendorInvoicePdf($token,$products);
			// Export as PDF
			$user->export($content);
		}else{
			$user = $this->model('Front');
			$seo_info = $user->getDetails(SEO_TBL,"*","page_token='error'");
			$this->view('home/error', 
				[	
					'meta_title'  		=>  $seo_info['meta_title'].' | '.COMPANY_NAME,
					'meta_keywords'  	=>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
					'meta_description'  =>  $seo_info['meta_description'].' | '.COMPANY_NAME,
					'meta'  			=> 'dynamic',
					'cart'   			=>  $user->cartInfo(),
					'location' 			=>  $user->getLocationList(),
					'legal_pages'		=>  $user->getLegalPages(),
					'menu_items'		=>  $user->menuItems(),
					'siteinfo' 			=>  $user->siteInfo($page_token="error"),
					'page_title'  		=> '404 Error - Page Not Found'
					
				]);
		}
	}

	public function mpreviewinvoice($token)
	{	
		$user 			 = $this->model('PdfExport');
		$profile 		 = $this->model('Profile');
		$details 		 = $user->check_query(ORDER_TBL,"id"," id='".$token."' ");
		$order_item_info = $user->getDetails(ORDER_ITEM_TBL,"*"," id='".$token."' ");
		$order_info 	 = $user->getDetails(ORDER_TBL,"*"," id='".$order_item_info['order_id']."' ");
		$products   	 = $profile->manageInvoiceItems($order_info['id'],$order_item_info['vendor_id']);
		$content 		 = $user->vendorInvoicePdf($token,$products);
		// Export as PDF
		$user->preview($content);
	}

	public function mdownloadinvoice($token)
	{	
		$user 			 = $this->model('PdfExport');
		$profile 		 = $this->model('Profile');
		$details 		 = $user->check_query(ORDER_TBL,"id"," id='".$token."' ");
		$order_item_info = $user->getDetails(ORDER_ITEM_TBL,"*"," id='".$token."' ");
		$order_info 	 = $user->getDetails(ORDER_TBL,"*"," id='".$order_item_info['order_id']."' ");
		$products   	 = $profile->manageInvoiceItems($order_info['id'],$order_item_info['vendor_id']);
		$content 		 = $user->vendorInvoicePdf($token,$products);
		// Export as PDF
		$user->export($content);
	}

	public function api($type)
	{
		if(isset($_SESSION["user_session_id"])){
			$user  = $this->model('Profile');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'editUserProfile':
					echo $user->editUserProfile($_POST);
				break;
				case 'changeUserPassword':
					echo $user->changeUserPassword($_POST);
				break;
				case 'addCartShippingAddress':
					echo $user->addCartShippingAddress($_POST);
				break;
				case 'editAddressPopup':
					echo $user->editAddressPopup($post);
				break;
				case 'editCartShippingAddress':
					echo $user->editCartShippingAddress($_POST);
				break;
				case 'getLocationsForGroup':
					$data = $user->getLocationArealist($post);
					echo json_encode($data);
				break;
				case 'make_default':
					echo $user->makeDefaultAddress($post);
				break;
				case 'deleteAddress':
					echo $user->deleteAddress($post);
				break;
				case 'retunOrderProductInfo':
					echo json_encode($user->retunOrderProductInfo($post));
				break;
				case 'orderProductReturn':
					echo $user->orderProductReturn($_POST);
				break;

				// Product Review sections 
				case 'getReviewInfo':
					echo $user->getReviewInfo($post);
				break;

				// Vendor Ratting Sections
				case 'getVendorInfo':
					echo $user->rateVendorInfo($_POST);
				break;
				case 'addVendorRating':
					echo $user->addVendorRating($_POST);
				break;
				case 'getEditVendorRateForm':
					echo $user->getEditVendorRateForm($post);
				break;
				case 'editVendorRating':
					echo $user->editVendorRating($_POST);
				break;

				default:
				break;
			}
		}
	}
	

	public function error()
	{
		
		$user = $this->model('Front');
		$seo_info = $user->getDetails(SEO_TBL,"*","page_token='error'");
		$this->view('home/error', 
			[	
				'meta_title'  		=>  $seo_info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  	=>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description'  =>  $seo_info['meta_description'].' | '.COMPANY_NAME,
				'meta'  			=> 'dynamic',
				'cart'   			=>  $user->cartInfo(),
				'location' 			=>  $user->getLocationList(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo($page_token="error"),
				'page_title'  		=> '404 Error - Page Not Found'
				
			]);
	}

}

?>
