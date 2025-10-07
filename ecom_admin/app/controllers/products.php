<?php

class products extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Product');
			$check 				= $user->checkPermissionPage(PRODUCTS);
			$check_sitesettings = $user->checksitesettings(PRODUCTS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/manageproduct', 
					[	
						'active_menu' 	 => 'productsettings',
						'page_title'	 => 'Manage Products',
						'scripts'		 => 'product',
						'future_product' =>  0,
						'user_info'		 =>  $user->userInfo(),
						'list'		 	 =>	 $user->manageProducts(),
						'sitesettings'	 =>	 $user->filtersitesettings(),
						'notification'   =>  $user->getOrderNotification()
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
					[	
						'active_menu' 	=>  '',
						'page_title'	=>	'404 - Page Not Found',
						'scripts'		=>	'error',
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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
			$user  				= $this->model('Product');
			$check 				= $user->checkPermissionPage(PRODUCTS);
			$check_sitesettings = $user->checksitesettings(PRODUCTS);
			$sub_category       = $user->getDetails(SUB_CATEGORY_TBL,"id","status='1' AND is_draft='0' AND delete_status='0' ORDER BY id ASC LIMIT 1");

			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/addproduct', 
					[	
						'active_menu' 	 		=> 'productsettings',
						'page_title'	 		=> 'Add Product',
						'scripts'		 		=> 'addproduct',
						'main_category'		 	=> $user->getMainCategory(),
						'sub_category'		 	=> $user->getSubCategory(),
						'tax_class'		 		=> $user->getTaxClass(),
						'brand_list'			=> $user->getBrandList(),
						'product_units'         => $user->getProductUnits(),	
						'return_duration'   	=> $user->getReturnDuration(),	
						'filter_masters'        => $user->getFilterMasterList($sub_category['id']),
						'display_tags'			=> $user->getProductDisplaytag(),
						'user_info'		 		=> $user->userInfo(),
						'csrf_add_product'  	=> $user->getCSRF("add_product"),
						'sitesettings'			=> $user->filtersitesettings(),
						'notification'  		=> $user->getOrderNotification()
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
					[	
						'active_menu' 	=>  '',
						'page_title'	=>	'404 - Page Not Found',
						'scripts'		=>	'error',
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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
			$user  				= $this->model('Product');
			$check 				= $user->checkPermissionPage(PRODUCTS);
			$details 			= $user->check_query(PRODUCT_TBL,"id"," id='$token' ");
			$check_sitesettings = $user->checksitesettings(PRODUCTS);
			if ($details==1 && $check_sitesettings==1 && $check==1) {
				$info = $user->productDetails($token);
				$this->view('home/editproduct', 
					[	
						'active_menu' 			=> 'productsettings',
						'page_title'			=> 'Edit Product',
						'scripts'				=> 'addproduct',
						'info'					=> $info,
						'token'					=> $user->encryptData($info['id']),
						'main_category'		 	=> $user->getMainCategory($info['main_category_id']),
						'sub_category'		 	=> $user->getSubCategory($info['sub_category_id']),
						'map_main_category'		=> $user->getMainCategoryMap($info['id']),
						'map_sub_category'		=> $user->getSubCategoryMap($info['id']),
						'tax_class'		 		=> $user->getTaxClass($info['tax_class']),
						'brand_list'			=> $user->getBrandList($info['brand_id']),
						'product_units'         => $user->getProductUnits($info['product_unit']),	
						'image_list'			=> $user->getProductImage($info['id']),
						'attribute_list'		=> $user->getProductAttributes($info['id']),
						'related_list'			=> $user->getProductRelatedItems($info['id']),
						'options_list'			=> $user->getOptionsTagEdit($info['id']),
						'options_preview'		=> $user->getOptionsPreviewEdit($info['id']),
						'variant_list'			=> $user->getProductVariantsEdit($info['id']),
						'return_duration'   	=> $user->getReturnDuration($info['return_duration']),	
						'display_tags'			=> $user->getProductDisplaytag($info['display_tag']),
						'filter_masters'        => $user->getFilterMasterList($info['sub_category_id'],$info['id']),
						'user_info'				=> $user->userInfo(),
						'csrf_edit_product' 	=> $user->getCSRF("edit_product"),
						'sitesettings'			=> $user->filtersitesettings(),
						'notification'  		=> $user->getOrderNotification()
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 	=>  '',
					'page_title'	=>	'404 - Page Not Found',
					'scripts'		=>	'error',
					'user_info'		=>	$user->userInfo(),
					'sitesettings'	=>	$user->filtersitesettings(),
					'notification'  =>  $user->getOrderNotification()
				]);
			}
		}else{
			$this->view('home/login',[]);
		}
	}

	public function request()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user 		= $this->model('Product');
			$check 		= $check = $user->checkPermissionPage(PRODUCT_REQUEST);
			$check_sitesettings = $user->checksitesettings(PRODUCTS);
			if ($check==1  && $check_sitesettings==1) {
				$this->view('home/productrequest', 
					[	
						'active_menu' 	=> 'productsettings',
						'page_title'	=> 'Product Request',
						'scripts'		=> 'dashboard',
						'list'			=>	$user->manageProductRequest(),
						'user_info'		=> 	$user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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
					'notification'  =>  $user->getOrderNotification()
				]);
			}

		} else {
			$this->view('home/login',[]);
		}
	}


	public function requeststatus()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user 		= $this->model('Settings');
			$check 		= $check = $user->checkPermissionPage(PRODUCT_REQUEST_STATUS);
			$check_sitesettings = $user->checksitesettings(PRODUCTS);
			if ($check==1 && $check_sitesettings==1) {
				$this->view('home/requeststatuslist', 
					[	
						'active_menu' 				=> 'settings',
						'page_title'				=> 'Product Request Status',
						'scripts'					=> 'dashboard',
						'list'						=>	$user->manageProductRequestStatus(),
						'csrf_add_prd_req_sts' 		=>  $user->getCSRF("add_prd_req_sts"),
						'csrf_edit_prd_req_sts'		=>  $user->getCSRF("edit_prd_req_sts"),
						'user_info'					=> 	$user->userInfo(),
						'sitesettings'				=>	$user->filtersitesettings(),
						'notification'  			=>  $user->getOrderNotification()
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 	=>  '',
					'page_title'	=>	'404 - Page Not Found',
					'scripts'		=>	'error',
					'notification'  =>  $user->getOrderNotification(),
					'user_info'		=>	$user->userInfo()
				]);
			}

		} else {
			$this->view('home/login',[]);
		}
	}

	public function unit()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user 		= $this->model('Settings');
			$check      = $user->checkPermissionPage(PRODUCT_UNIT);
			$check_sitesettings = $user->checksitesettings(PRODUCTS);
			if ($check==1 && $check_sitesettings==1 ) {
				$this->view('home/productunit', 
					[	
						'active_menu' 				=> 'productsettings',
						'page_title'				=> 'Product Units',
						'scripts'					=> 'dashboard',
						'list'						=>	$user->manageProductUnit(),
						'csrf_add_prd_unit_sts'		=>  $user->getCSRF("add_prd_unit_sts"),
						'csrf_edit_prd_unit_sts'	=>  $user->getCSRF("edit_prd_unit_sts"),
						'user_info'					=> 	$user->userInfo(),
						'sitesettings'				=>	$user->filtersitesettings(),
						'notification'  			=>  $user->getOrderNotification()
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 	=>  '',
					'page_title'	=>	'404 - Page Not Found',
					'scripts'		=>	'error',
					'notification'  =>  $user->getOrderNotification(),
					'user_info'		=>	$user->userInfo()
				]);
			}

		} else {
			$this->view('home/login',[]);
		}
	}

	public function stockinventor($vendor="",$token='')
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user 		= $this->model('Product');
			$check      = $user->checkPermissionPage(STOCK_INVENTORY);
			$check_sitesettings = $user->checksitesettings(PRODUCTS);
			if ($check==1 && $check_sitesettings==1 ) {
				$this->view('home/stockinventory', 
					[	
						'active_menu' 	=> 'productsettings',
						'page_title'	=>  $token,
						'scripts'		=> 'inventory',
						'get_vendors'   =>  $user->getVendors($vendor),
						'InventoryList' =>  $user->manageInventoryList($vendor,$token),
						'user_info'		=> 	$user->userInfo(),
						'sitesettings'	=>	$user->filtersitesettings(),
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
					'notification'  =>  $user->getOrderNotification()
				]);
			}
		}else{
			$this->view('home/login',[]);
		}
	}

	public function displaytag()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user 		= $this->model('Settings');
			$check      = $user->checkPermissionPage(PRODUCT_UNIT);
			$check_sitesettings = $user->checksitesettings(PRODUCTS);
			if ($check==1 && $check_sitesettings==1 ) {
				$this->view('home/displaytag', 
					[	
						'active_menu' 				=> 'settings',
						'page_title'				=> 'Product Display Tag',
						'scripts'					=> 'dashboard',
						'list'						=>	$user->manageDisplayTag(),
						'csrf_add_display_tag'	    =>  $user->getCSRF("add_display_tag"),
						'csrf_edit_display_tag'	    =>  $user->getCSRF("edit_display_tag"),
						'user_info'					=> 	$user->userInfo(),
						'sitesettings'				=>	$user->filtersitesettings(),
						'notification'  			=>  $user->getOrderNotification()
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
				[	
					'active_menu' 	=>  '',
					'page_title'	=>	'404 - Page Not Found',
					'scripts'		=>	'error',
					'notification'  =>  $user->getOrderNotification(),
					'user_info'		=>	$user->userInfo()
				]);
			}

		} else {
			$this->view('home/login',[]);
		}
	}


	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Product');
			$settings  = $this->model('Settings');
			$post = @$_POST["result"];
			switch ($type) {
				case 'add':
					$upload = new MultiFileUploader();
					$files = $upload->uploadFile($_FILES, $_POST['title_name']);
					//print_r($_POST);
					echo $user->addProduct($_POST,$files);
				break;
				case 'edit':
					$upload = new MultiFileUploader();
					$files = $upload->uploadFile($_FILES, $_POST['title_name']);
					//print_r($_POST);
					echo $user->editProduct($_POST,$files);
				break;
				case 'imageinfo':
					echo json_encode($user->productImageInfo($post));
				break;
				case 'imageedit':
					echo json_encode($user->editProductImage($_POST));
				break;
				case 'imagedefault':
					echo $user->makeImageDefault($post,$_POST['product_id']);
				break;
				case 'imagedelete':
					echo $user->deleteProductImage($post);
				break;
				case 'variantimages':
					echo $user->makeImageDefault($post,$_POST['product_id']);
				break;
				case 'status':
					echo $user->changeProductStatus($post);
				break;
				case 'delete':
					echo $user->deleteProduct($post);
				break;
				case 'trash':
					echo $user->trashProduct($post);
				break;
				case 'publish':
					echo $user->publishProduct($post);
				break;
				case 'restore':
					echo $user->restoreProduct($post);
				break;
				case 'products_autocomplete':
					$data= $user->productAutoComplete($_GET['filter_name']);
					echo json_encode($data);
				break;
				case 'attributes_autocomplete':
					$data= $user->attributesAutocomplete($_GET['filter_name']);
					echo json_encode($data);
				break;
				case 'getproductRequestDetails':
					echo $user->getproductRequestDetails($post);
				break;
				case 'productRequestStatus' :
				 	echo $user->productRequestStatusupdate($_POST);
				break;

				// ProductRequestStatus master

				case 'addProductRequestStatus':
					echo $settings->addProductRequestStatus($_POST);
				break;
				case 'productRequestStatusMasterStatus':
					echo $settings->changeProductRequestStatusMasterStatus($post);
				break;
				case 'productRequestStatusinfo':
					echo $settings->getProductRequestStatusDetails($post);
				break;
				case 'updateProductRequestStatus':
					echo $settings->editProductRequestStatus($_POST);
				break;
				case 'deleteProductRequestStatus':
					echo $settings->deleteProductRequestStatus($post);
				break;
				case 'restoreProductRequestStatus':
					echo $settings->restoreProductRequestStatus($post);
				break;

				// ProductUnit master

				case 'addProductUnit':
					echo $settings->addProductUnit($_POST);
				break;
				case 'productUnitMasterStatus':
					echo $settings->changeProductUnitMasterStatus($post);
				break;
				case 'productUnitinfo':
					echo $settings->getProductUnitDetails($post);
				break;
				case 'updateProductUnit':
					echo $settings->editProductUnit($_POST);
				break;
				case 'deleteProductUnit':
					echo $settings->deleteProductUnit($post);
				break;
				case 'restoreProductUnit':
					echo $settings->restoreProductUnit($post);
				break;


				// DisplayTag master

				case 'addDisplayTag':
					echo $settings->addDisplayTag($_POST);
				break;
				case 'displayTagMasterStatus':
					echo $settings->changeDisplayTagMasterStatus($post);
				break;
				case 'displayTaginfo':
					echo $settings->getDisplayTagDetails($post);
				break;
				case 'updateDisplayTag':
					echo $settings->editDisplayTag($_POST);
				break;
				case 'deleteDisplayTag':
					echo $settings->deleteDisplayTag($post);
				break;
				case 'restoreDisplayTag':
					echo $settings->restoreDisplayTag($post);
				break;

				// Filter mapping
				case 'getFilterMasterList':
					$product_id = @$_POST['product_id'];
					echo $user->getFilterMasterList($post,$product_id);
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
					'sitesettings'	=>	$user->filtersitesettings(),
					'notification'  =>  $user->getOrderNotification()
				]);
		}else{
			$this->view('home/login',[]);
		}
	}

}


?>