<?php

class products extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 		= $this->model('Admin');
			// $check = $user->checkPermissionPage(DASHBOARD);
			$check = 1;
			if ($check==1) {
				$this->view('home/assignproduct', 
					[	
						'active_menu' 		=> 'products',
						'page_title'		=> 'Products',
						'scripts'			=> 'dashboard',
						'list'				=> $user->manageProducts("all",""),
						'csrf_add_product' 	=> $user->getCSRF("add_product"),
						'user_info'			=> $user->userInfo(),
						'notification'  	=>  $user->getOrderNotification(),
						'vendor_active' 	=>  $user->CheckVendorStatus()
					]);
			}else{

			}
		}else{
			$this->view('home/login',[]);
		}
		
	}

	public function category($token)
	{
		if(isset($_SESSION["ecom_vendor_id"])){

			$user 		= $this->model('Admin');
			// $check = $user->checkPermissionPage(DASHBOARD);
			$check = $user->check_query(MAIN_CATEGORY_TBL,"id"," page_url='$token' ");
			if ($check) {
				$info = $user->getDetails(MAIN_CATEGORY_TBL,"id,category"," page_url='$token' ");
				$this->view('home/assignproduct', 
					[	
						'active_menu' 		=> 'product_variants',
						'page_title'		=> $info['category'],
						'scripts'			=> 'dashboard',
						'list'				=> $user->manageProducts("category",$info['id']),
						'csrf_add_product' 	=> $user->getCSRF("add_product"),
						'user_info'			=> $user->userInfo(),
						'notification'  	=>  $user->getOrderNotification(),
						'vendor_active' 	=>  $user->CheckVendorStatus()
					]);
			}else{

			}
		}else{
			$this->view('home/login',[]);
		}
		
	}

	public function subcategory($token)
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 		= $this->model('Admin');
			$check = $user->check_query(SUB_CATEGORY_TBL,"id"," page_url='$token' ");
			if ($check) {
			$info = $user->getDetails(SUB_CATEGORY_TBL,"id,subcategory"," page_url='$token' ");
				$this->view('home/assignproduct', 
					[	
						'active_menu' 		=> 'product_variants',
						'page_title'		=> $info['subcategory'],
						'scripts'			=> 'dashboard',
						'list'				=> $user->manageProducts("subcategory",$info['id']),
						'csrf_add_product' 	=> $user->getCSRF("add_product"),
						'user_info'			=> $user->userInfo(),
						'notification'  	=>  $user->getOrderNotification(),
						'vendor_active' 	=>  $user->CheckVendorStatus()
					]);
			}else{

			}
		}else{
			$this->view('home/login',[]);
		}
		
	}

	public function add()
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 		= $this->model('Admin');
			// $check = $user->checkPermissionPage(DASHBOARD);
			$check = 1;
			if ($check==1) {
				$this->view('home/assigncategory', 
					[	
						'active_menu' 	=> 'add_category',
						'page_title'	=> 'Add Category',
						'scripts'		=> 'dashboard',
						'permissions'   =>  $user->permissionDataMaster(),
						'csrf_add_user' =>  $user->getCSRF("add_user"),
						'user_info'		=> 	$user->userInfo(),
						'notification'  =>  $user->getOrderNotification(),
						'vendor_active' =>  $user->CheckVendorStatus()
					]);
			}else{

			}
		}else{
			$this->view('home/login',[]);
		}
		
	}

	public function catalogue()
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 		= $this->model('Admin');
			// $check = $user->checkPermissionPage(DASHBOARD);
			$check = 1;
			if ($check==1) {
				$this->view('home/productvariants', 
					[	
						'active_menu' 	=> 'catalogue',
						'page_title'	=> 'Catalogue',
						'scripts'		=> 'dashboard',
						'c_product'		=> $user->getCategoryProducts(),
						'permissions'   => $user->permissionDataMaster(),
						'csrf_add_user' => $user->getCSRF("add_user"),
						'user_info'		=> $user->userInfo(),
						'notification'  => $user->getOrderNotification(),
						'vendor_active' =>  $user->CheckVendorStatus()
					]);
			}else{

			}
		}else{
			$this->view('home/login',[]);
		}

	}

	public function inventory($token="")
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 		= $this->model('Admin');
			// $check = $user->checkPermissionPage(DASHBOARD);
			$check = 1;
			if ($check==1) {
				$this->view('home/inventory', 
					[	
						'active_menu' 	=> 'inventorysettings',
						'page_title'	=>  $token,
						'scripts'		=> 'inventory',
						'InventoryList' =>  $user->manageInventoryList($token),
						'user_info'		=> 	$user->userInfo(),
						'notification'  =>  $user->getOrderNotification(),
						'vendor_active' =>  $user->CheckVendorStatus()
					]);
			}else{
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
			}
		}else{
			$this->view('home/login',[]);
		}

	}

	public function inventorystock($product_id="")
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 		= $this->model('Admin');
			// $check = $user->checkPermissionPage(DASHBOARD);
			$check = 1;
			if ($check==1) {

				$page_link = COREPATH."products/inventorystock/".$product_id;

				// Number of items per page 

				$item_per_page = 6;
				$count 		   = $user->inventoryPaginationCount($product_id,$item_per_page);

				if(isset($_GET['p'])) {
					$page = @$_GET['p'];
				} else {
					$page = 1;
				}
				if($page == 1) {
					$previous = "javascript:void();";
				} else {
					$previous = $page_link."?p=".($page-1); 
				}

				$pagination = $user->manageInventoryPagination($page,$page_link,$product_id,$item_per_page);
				$total_page = $pagination['total_page'];

				if ($page == $total_page) {
					$next = "javascript:void();";
				}else {
					$next = $page_link."?p=".($page+1);	
				}

				$this->view('home/inventorystock', 
					[	
						'active_menu' 	 => 'inventorysettings',
						'page_title'	 => 'Manage Inventory Stock',
						'scripts'		 => 'inventory',
						'previous'       => $previous,
						'item_per_page'  => $item_per_page,
						'next'			 => $next,
						'count' 		 => $count,
						'inventory_list' => $user->getProductInventoryList($product_id,$page,$item_per_page),
						'page' 			 => $pagination,
						'user_info'		 => $user->userInfo(),
						'notification'   => $user->getOrderNotification(),
						'vendor_active'  =>  $user->CheckVendorStatus()
					]);
			}else{
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
			}
		}else{
			$this->view('home/login',[]);
		}

	}

	public function request()
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 		= $this->model('Admin');
			$check 		= 1; //  $check = $user->checkPermissionPage(DASHBOARD);
			if ($check==1) {
				$this->view('home/productrequest', 
					[	
						'active_menu' 			=> 'inventorysettings',
						'page_title'			=> 'Product Request',
						'scripts'				=> 'product_request',
						'list'					=>	$user->manageProductRequest(),
						'user_info'				=> 	$user->userInfo(),
						'csrf_add_prd_request' 	=>  $user->getCSRF("add_prd_request"),
						'csrf_edit_prd_request' =>  $user->getCSRF("edit_prd_request"),
						'notification'  		=>  $user->getOrderNotification(),
						'vendor_active' 		=>  $user->CheckVendorStatus()
					]);
			}else{
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
			}

		} else {
			$this->view('home/login',[]);
		}
	}

	public function addrequest()
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 		= $this->model('Admin');
			$check 		= 1; //  $check = $user->checkPermissionPage(DASHBOARD);
			if ($check==1) {
				$this->view('home/addproductrequest', 
					[	
						'active_menu' 			=> 'inventorysettings',
						'page_title'			=> 'Product Request',
						'scripts'				=> 'product_request',
						'csrf_add_prd_request' 	=> $user->getCSRF("add_prd_request"),
						'list'					=>	$user->manageProductRequest(),
						'user_info'				=> 	$user->userInfo(),
						'notification'  		=>  $user->getOrderNotification(),
						'vendor_active' 		=>  $user->CheckVendorStatus()
					]);
			}else{
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
			}

		} else {
			$this->view('home/login',[]);
		}
	} 
	public function editrequest($token="")
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user  				= $this->model('Admin');
			$check 				= 1; //$user->checkPermissionPage(SEO_CONTENT);
			$details 			= $user->check_query(VENDOR_PRODUCT_REQUEST_TBL,"id"," id='$token' ");
			if ($details==1) {
				$info = $user->getDetails(VENDOR_PRODUCT_REQUEST_TBL,"*"," id='$token'  ");
				$this->view('home/editproductrequest', 
					[	
						'active_menu' 			=> 'inventorysettings',
						'page_title'			=> 'Product Request',
						'scripts'				=> 'product_request',
						'info'					=> $info,
						'token'					=> $user->encryptData($info['id']),
						'user_info'				=> $user->userInfo(),
						'csrf_edit_prd_request' => $user->getCSRF("edit_prd_request"),
						'notification'  		=>  $user->getOrderNotification(),
						'vendor_active' 		=>  $user->CheckVendorStatus()
					]);
			}else{
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
			}
		}else{
			$this->view('home/login',[]);
		}
	}

	public function api($type)
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user  = $this->model('Admin');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'assignCategory':
					echo $user->assignCategory($_POST);
				break;
				case 'assignProducts':
					echo $user->assignProducts($_POST);
				break;
				case 'productStatus':
					echo $user->changeProductStatus($post);
				break;
				case 'addProductRequest':
				    echo $user->addProductRequest($_POST);
				break;
				case 'editProductRequest':
				    echo $user->editProductRequest($_POST);
				break;
				case 'getProductInventoryinfo':
				    echo $user->getProductInventoryinfo($post);
				break;
				case 'updateProductInventoryinfo':
				    echo $user->updateProductInventoryinfo($_POST);
				break;
				case 'getproductRequestDetails':
					echo $user->getproductRequestDetails($post);
				break;
				case 'getproductRequestData':
				    echo $user->getproductRequestData($post);
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
					'user_info'		=>	$user->userInfo()
				]);
		}else{
			$this->view('home/login',[]);
		}
	}

	


}

?>
