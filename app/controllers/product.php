<?php

class Product extends Controller
{
 
public function index($token="")
{		
	$user = $this->model('Products');

	$filter_url 	          ="";
	$sort_by 	  	          ="";
	$price_sort  	          ="";

	//print_r($filter_array);

	if(isset($_GET['sortby'])){
		$sort_by 	          = @$_GET['sortby'];
		$filter_url           = "sortby=".$sort_by."";
		$page_link 	          = BASEPATH."product?".$filter_url."&"; 
	}

	$min_price 		          = $user->getDetails(PRODUCT_TBL,"selling_price","1 ORDER BY selling_price ASC");
	$max_price 		          = $user->getDetails(PRODUCT_TBL,"selling_price","1 ORDER BY selling_price DESC");
	$vendor_min_price         = $user->getDetails(VENDOR_PRODUCTS_TBL,"selling_price","1 ORDER BY selling_price ASC");

	if($min_price['selling_price'] > $vendor_min_price['selling_price'] ) {
		$min_price['selling_price'] = $vendor_min_price['selling_price'];
	}

	if(isset($_GET['price'])){
		$get_price  		  = explode('-', $_GET['price']);
		$filter_from 		  = $get_price[0];
		$filter_to 			  = $get_price[1];
		$price_filter  		  = array();
		$price_filter['from'] = $filter_from; 
		$price_filter['to']   = $filter_to;
		$price_sort           = 'price';

		if(isset($_GET['sortby'])){
			$filter_url 	  = "price=".$filter_from."-".$filter_to."&".$filter_url; 
			$page_link 	      = BASEPATH."product?".$filter_url."&"; 

	    } else {
			$filter_url 	  = "price=".$filter_from."-".$filter_to; 
			$page_link 	      = BASEPATH."product?price=".$filter_from."-".$filter_to."&";

	    }

	    if(isset($_GET['page_amount'])) {
			$page_link        = $page_link."&page_amount=".$_GET['page_amount']."";
		} 
	} else {

		$filter_from 	      = $min_price['selling_price'];
		$filter_to 		      = $max_price['selling_price'];
		$price_filter         = ""; 

		if(!isset($_GET['sortby'])){
			$page_link 	      = BASEPATH."product?"; 
		} else {              
			$page_link        = BASEPATH."product?".$filter_url."&"; 
		}

		if(isset($_GET['page_amount'])) {
			$page_link        = $page_link."page_amount=".$_GET['page_amount']."&";
		}
	}

	if(isset($_GET['page_amount'])) {

		if( $_GET['page_amount'] == "show_all" ) {
		 	$page_amount      = "show_all";
		 } else {
		 	$page_amount      = explode("-", $_GET['page_amount']);
			$page_amount      = $page_amount[1];
		 }	

	} else {
		$get_page_count       = $user->getDetails(PAGINATION_TBL,"pagination_count"," status='1' AND delete_status='0' ");
		$page_amount 	      = $get_page_count['pagination_count'];
	}


	if(isset($_GET['brands'])) {
		$brands               = $_GET['brands'];
		$page_link            = $page_link."brands=".$_GET['brands']."&";
	} else {
		$brands               = "";
	}


	if (isset($_GET['p'])) {
		$page = @$_GET['p'];
	}else{
		$page = 1;
	}


	$server 		= $_SERVER['QUERY_STRING'];
	$filter_array 	= $user->convertUrlToArray($server,$page);
	unset($filter_array['url']);

	$count = $user->productsPaginationCount($brands,$price_filter,$page_amount,$filter_array);

	if ($page==1) {
		$previous = "javascript:void();";
	} else {
		$server_p 		= $_SERVER['QUERY_STRING'];
		$prev_array 	= $user->convertUrlToArray($server_p,$page);

		unset($prev_array['url']);
		unset($prev_array['p']);

		$url            = $user->build_http_query($prev_array);
		$url_starting   = count($prev_array)>0 ? "?".$url."&" : "?";
		$previous       = BASEPATH."product/".$url_starting."p=".($page-1);
	}

	if ($page < $count['total_pages']) {
		$server_n 		= $_SERVER['QUERY_STRING'];
		$next_array 	= $user->convertUrlToArray($server_n,$page);

		unset($next_array['url']);
		unset($next_array['p']);
		
		$url            = $user->build_http_query($next_array);
		$url_starting   = count($next_array)>0 ? "?".$url."&" : "?";
		$next           = BASEPATH."product/".$url_starting."p=".($page+1);
	}else {
		$next = "javascript:void();";
	}

	$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='product' AND is_draft='0' AND status='1' ");
	$seo_info    = $user->getDetails(SEO_TBL,"*","page_token='product'");

	$this->view('home/products', 
	[	
		'active_menu' 		=> 'products',
		'meta_title'  		=>  $seo_info['meta_title'].' | '.COMPANY_NAME,
		'meta_keywords'  	=>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
		'meta_description' 	=>  $seo_info['meta_description'].' | '.COMPANY_NAME,
		'meta'  			=> 'dynamic',
		'list'		    	=>  $user->manageProductlist($page,$brands,$sort_by,$price_sort,$filter_from,$filter_to,$page_amount,$filter_array),
		'shop_category'		=>  $user->manageShopCategorylist(),
		'category'	   		=>  $user->manageCategorylist(),
		'brands'	   		=>  $user->getShopBrandlist($page_link,$filter_array),
		'rating_filter'		=>  $user->getSubcatgoryRating(),
		'cart'   			=>  $user->cartInfo(),
		'legal_pages'		=>  $user->getLegalPages(),
		'menu_items'		=>  $user->menuItems(),
		'siteinfo' 			=>  $user->siteInfo($page_token="shopproducts"),
		'previous' 			=>  $previous,
		'next' 				=>  $next,
		'count'				=>  $count,
		'page_banner'		=>  $user->getPageBanner($page_token="product"),
		'page' 				=>  $user->productsPagination($page,$page_link,$price_filter,$page_amount,$brands,$filter_array),
		'sort_filter'		=>	$user->shopProductSortingFilters($server,$page),
		'location'    		=>  $user->getLocationList(),
		'page_title'  		=>  COMPANY_NAME,
		'min_price'			=>  $min_price['selling_price'],
		'max_price'			=>  $max_price['selling_price'],
	]);
	

}

public function category($token)
{		

	$user  = $this->model('Products');
	$check = $user->check_query(MAIN_CATEGORY_TBL,"id"," page_url='".$token."' AND delete_status='0' AND status='1' AND is_draft='0' ");
	if($check) {

		$c_info  = $user->getDetails(MAIN_CATEGORY_TBL,"*"," page_url='".$token."' AND delete_status='0' AND status='1' AND is_draft='0' ");
		$token   = $c_info['page_url'];

		$filter_url 	="";
		$sort_by 	  	="";
		$price_sort  	='price';

		if(isset($_GET['sortby'])){
			$sort_by 	 = @$_GET['sortby'];
			$filter_url  = "sortby=".$sort_by."";
			$page_link 	 = BASEPATH."product/category/".$token."?".$filter_url."&"; 
		} 

		$min_price 		  = $user->getDetails(PRODUCT_TBL,"selling_price","1 ORDER BY selling_price ASC");
		$max_price 		  = $user->getDetails(PRODUCT_TBL,"selling_price","1 ORDER BY selling_price DESC");
		$vendor_min_price = $user->getDetails(VENDOR_PRODUCTS_TBL,"selling_price","1 ORDER BY selling_price ASC");

		if($min_price['selling_price'] > $vendor_min_price['selling_price'] ) {
			$min_price['selling_price'] = $vendor_min_price['selling_price'];
		}

		
		if(isset($_GET['price'])){
			$get_price  		  = explode('-', $_GET['price']);
			$filter_from 		  = $get_price[0];
			$filter_to 			  = $get_price[1];
			$price_filter  		  = array();
			$price_filter['from'] = $filter_from; 
			$price_filter['to']   = $filter_to;
			$price_sort           = 'price';

			if(isset($_GET['sortby'])){
				$filter_url 	= "price=".$filter_from."-".$filter_to."&".$filter_url; 
				$page_link 	    = BASEPATH."product/category/".$token."?".$filter_url."&"; 

		    } else {
				$filter_url 	= "price=".$filter_from."-".$filter_to; 
				$page_link 	    = BASEPATH."product/category/".$token."?price=".$filter_from."-".$filter_to."&";

		    }

		    if(isset($_GET['page_amount'])) {
				$page_link = $page_link."&page_amount=".$_GET['page_amount']."";
			} 
		} else {
			$filter_from 	= $min_price['selling_price'];
			$filter_to 		= $max_price['selling_price'];
			$price_filter   = ""; 
			if(!isset($_GET['sortby'])){
				$page_link 	= BASEPATH."product/category/".$token."?"; 
			} else {
				$page_link 	= BASEPATH."product/category/".$token."?".$filter_url."&"; 
			} 

			if(isset($_GET['page_amount'])) {
				$page_link = $page_link."page_amount=".$_GET['page_amount']."&";
			}
		}

		if(isset($_GET['page_amount'])) {
			if($_GET['page_amount']=="show_all") {
			 	$page_amount = "show_all";
			 } else {
			 	$page_amount = explode("-", $_GET['page_amount']);
				$page_amount = $page_amount[1];
			 }	
		} else {
			$get_page_count = $user->getDetails(PAGINATION_TBL,"pagination_count"," status='1' AND delete_status='0' ");
			$page_amount 	= $get_page_count['pagination_count'];
		}

		if(isset($_GET['brands'])) {
			$brands    = $_GET['brands'];
			// $page_link = $page_link."brands=".$_GET['brands']."&";
		} else {
			$brands    = "";
		}


		if (isset($_GET['p'])) {
			$page = @$_GET['p'];
		}else{
			$page = 1;
		}
		
		$server 		= $_SERVER['QUERY_STRING'];
		$filter_array 	= $user->convertUrlToArray($server,$page);
		unset($filter_array['url']);

		$count = $user->maincategoryPaginationCount($brands,$category="main",$c_info['id'],$price_filter,$page_amount,$filter_array);

		if ($page==1) {
			$previous = "javascript:void();";
		}else {
			$server_p 		= $_SERVER['QUERY_STRING'];
			$prev_array 	= $user->convertUrlToArray($server_p,$page);
			unset($prev_array['url']);
			unset($prev_array['p']);
			$url = $user->build_http_query($prev_array);
			$url_starting = count($prev_array)>0 ? "?".$url."&" : "?";
			$previous = BASEPATH."product/category/".$token.$url_starting."p=".($page-1);
		}

		if ($page < $count['total_pages']) {
			$server_n 		= $_SERVER['QUERY_STRING'];
			$next_array 	= $user->convertUrlToArray($server_n,$page);
			unset($next_array['url']);
			unset($next_array['p']);
			$url = $user->build_http_query($next_array);
			$url_starting = count($next_array)>0 ? "?".$url."&" : "?";
			$next = BASEPATH."product/category/".$token.$url_starting."p=".($page+1);
		}else {
			$next = "javascript:void();";
		}

		$seo_info    = $user->getDetails(SEO_TBL,"*","page_token='".$token."'");

		$this->view('home/categoryproducts', 
		[	
			'active_menu' 		=> 'products',
			'meta_title'  		=>  $c_info['meta_title'].' | '.COMPANY_NAME,
			'meta_keywords'  	=>  $c_info['meta_keyword'].' | '.COMPANY_NAME,
			'meta_description' 	=>  $c_info['meta_description'].' | '.COMPANY_NAME,
			'meta'  			=> 'dynamic',
			'list'				=>  $user->manageMaincategoryProductlist($page,$brands,$category="main",$c_info['id'],$sort_by,$price_sort,$filter_from,$filter_to,$page_amount,$filter_array),
			'category'			=>  $user->manageCategorylist(),
			'brands'			=>  $user->getCatgoryBrands("main",$c_info['id'],$page_link,$filter_array),
			'rating_filter'		=>  $user->getSubcatgoryRating(),
			'cart'   			=>  $user->cartInfo(),
			'legal_pages'		=>  $user->getLegalPages(),
			'menu_items'		=>  $user->menuItems(),
			'siteinfo' 			=>  $user->siteInfo($page_token=$token),
			'previous' 			=>  $previous,
			'shop_category'		=>  $user->manageShopSubCategorylist($c_info['id']),
			'c_info'			=>  $c_info,
			'page_count'		=>  $count['total_pages'],
			'total_records'		=>  $count['total_records'],
			'next' 	       		=>  $next,
			'count'	       		=>  $count,
			'token'	       		=>  $token,
			'min_price'			=>  $min_price,
			'max_price' 		=>  $max_price,
			// 'page_banner'		=>  $user->getCategoryAndSubCategoryPageBanner($page_token=$token),
			'location'      	=>  $user->getLocationList(),
			'page' 				=>  $user->maincategoryPagination($page,$c_info['id'],"category",$page_link,$price_filter,$page_amount,$brands,$filter_array,$token),
			'sort_filter'		=>	$user->mainCategorySortingFilters($server,$page),
			'page_title'  		=>  COMPANY_NAME,
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

}

public function subcategory($token)
{		

	$user  			= $this->model('Products');
	$check 			= $user->check_query(SUB_CATEGORY_TBL,"id"," page_url='".$token."' ");
	$c_info  		= $user->getDetails(SUB_CATEGORY_TBL,"*"," page_url='".$token."' ");
	$main_cat_info 	= $user->getDetails(MAIN_CATEGORY_TBL,"*","id='".$c_info['category_id']."'");
	if($check) {

	$filter_url 	="";
	$sort_by 	  	="";
	$price_sort  	='price';



	if(isset($_GET['sortby'])){
		$sort_by 	 = @$_GET['sortby'];
		$filter_url  = "sortby=".$sort_by."";
		$page_link 	 = BASEPATH."product/subcategory/".$token."?".$filter_url."&"; 
	} 

	$min_price 		= $user->getDetails(PRODUCT_TBL,"selling_price","1 ORDER BY selling_price ASC");
	$max_price 		= $user->getDetails(PRODUCT_TBL,"selling_price","1 ORDER BY selling_price DESC");
	
	if(isset($_GET['price'])){
		$get_price  		  = explode('-', $_GET['price']);
		$filter_from 		  = $get_price[0];
		$filter_to 			  = $get_price[1];
		$price_filter  		  = array();
		$price_filter['from'] = $filter_from; 
		$price_filter['to']   = $filter_to;
		$price_sort           = 'price';

		if(isset($_GET['sortby'])){
			$filter_url 	= "price=".$filter_from."-".$filter_to."&".$filter_url; 
			$page_link 	    = BASEPATH."product/subcategory/".$token."?".$filter_url."&"; 

	    } else {
			$filter_url 	= "price=".$filter_from."-".$filter_to; 
			$page_link 	    = BASEPATH."product/subcategory/".$token."?price=".$filter_from."-".$filter_to."&";

	    }

	    if(isset($_GET['page_amount'])) {
			$page_link = $page_link."&page_amount=".$_GET['page_amount']."";
		} 
	} else {
		$filter_from 	= $min_price['selling_price'];
		$filter_to 		= $max_price['selling_price'];
		$price_filter   = ""; 
		if(!isset($_GET['sortby'])){
			$page_link 	= BASEPATH."product/subcategory/".$token."?"; 
		} else {
			$page_link 	= BASEPATH."product/subcategory/".$token."?".$filter_url."&"; 
		}  
		
		if(isset($_GET['page_amount'])) {
			$page_link = $page_link."&page_amount=".$_GET['page_amount']."";
		} 
	}

	if(isset($_GET['page_amount'])) {
		if($_GET['page_amount']=="show_all") {
		 	$page_amount = "show_all";
		} else {
		 	$page_amount = explode("-", $_GET['page_amount']);
			$page_amount = $page_amount[1];
		}	
	} else {
		$get_page_count = $user->getDetails(PAGINATION_TBL,"pagination_count"," status='1' AND delete_status='0' ");
		$page_amount 	= $get_page_count['pagination_count'];
	}

	if(isset($_GET['brands'])) {
		$brands    = $_GET['brands'];
	} else {
		$brands    = "";
	}

	
	if (isset($_GET['p'])) {
		$page = @$_GET['p'];
	}else{
		$page = 1;
	}
	
	$server 		= $_SERVER['QUERY_STRING'];
	$filter_array 	= $user->convertUrlToArray($server,$page);
	unset($filter_array['url']);

	$count = $user->subcategoryPaginationCount($c_info['id'],$page_amount,$filter_array);

	if ($page==1) {
		$previous = "javascript:void();";
	}else {
		$server_p 		= $_SERVER['QUERY_STRING'];
		$prev_array 	= $user->convertUrlToArray($server_p,$page);
		unset($prev_array['url']);
		unset($prev_array['p']);
		$url = $user->build_http_query($prev_array);
		$url_starting = count($prev_array)>0 ? "?".$url."&" : "?";
		$previous = BASEPATH."product/subcategory/".$token.$url_starting."p=".($page-1);
	}

	if ($page < $count['total_pages']) {
		$server_n 		= $_SERVER['QUERY_STRING'];
		$next_array 	= $user->convertUrlToArray($server_n,$page);
		unset($next_array['url']);
		unset($next_array['p']);
		$url = $user->build_http_query($next_array);
		$url_starting = count($next_array)>0 ? "?".$url."&" : "?";
		$next = BASEPATH."product/subcategory/".$token.$url_starting."p=".($page+1);
	}else {
		$next = "javascript:void();";
	}
	
	$seo_info    = $user->getDetails(SEO_TBL,"*","page_token='".$token."'");


	$this->view('home/subcategoryproducts', 
	[	
		'active_menu' 		=> 'products',
		'meta_title'  		=>  $c_info['meta_title'].' | '.COMPANY_NAME,
		'meta_keywords'  	=>  $c_info['meta_keyword'].' | '.COMPANY_NAME,
		'meta_description' 	=>  $c_info['meta_description'].' | '.COMPANY_NAME,
		'meta'  			=> 'dynamic',
		'list'				=>  $user->manageSubcategoryProductlist($page,$c_info['id'],$sort_by,$price_sort,$filter_from,$filter_to,$page_amount,$filter_array),
		'category'			=>  $user->manageCategorylist(),
		'brands'			=>  $user->getCatgoryBrands("sub",$c_info['id'],$page_link,$filter_array),
		'rating_filter'		=>  $user->getSubcatgoryRating(),
		'cart'   			=>  $user->cartInfo(),
		'shop_category'		=>  $user->manageShopSubCategorylist($main_cat_info['id'],$c_info['id']),
		'legal_pages'		=>  $user->getLegalPages(),
		'menu_items'		=>  $user->menuItems(),
		'siteinfo' 			=>  $user->siteInfo($page_token=$token),
		'previous' 			=>  $previous,
		'next' 				=>  $next,
		'main_cat_info'		=>  $main_cat_info,
		'c_info'			=>  $c_info,
		'page_count'		=>  $count['total_pages'],
		'total_records'		=>  $count['total_records'],
		'token'				=>  $token,
		// 'page_banner'		=>  $user->getCategoryAndSubCategoryPageBanner($page_token=$token),
		'location'      	=>  $user->getLocationList(),
		'page' 				=>  $user->subcategoryPagination($page,$c_info['id'],"subcategory",$page_link,$price_filter,$page_amount,$brands,$filter_array,$token),
		'filter_list'		=>	$user->subcategoryFilter($filter_array,$c_info['id']),
		'sort_filter'		=>	$user->subCategorySortingFilters($server,$page),
		'page_title'  		=>  COMPANY_NAME,
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

}



public function brand($brand_token)
{		

	$user  = $this->model('Products');
	$check = $user->check_query(BRAND_TBL,"id"," page_url='".$brand_token."' ");
	$b_info  = $user->getDetails(BRAND_TBL,"*"," page_url='".$brand_token."' ");

	if($check){

		if(isset($_GET['sortby'])){
			$sort_by 	= @$_GET['sortby'];
		} elseif (isset($_GET['price'])) {
			$sort_by = 'price';
		} else {
			$sort_by 	= "";
		}

		$min_price 		= $user->getDetails(PRODUCT_TBL,"selling_price","1 ORDER BY selling_price ASC");
		$max_price 		= $user->getDetails(PRODUCT_TBL,"selling_price","1 ORDER BY selling_price DESC");

		if(isset($_GET['price'])){
			$get_price  = explode('-', $_GET['price']);
			$from 		= $get_price[0];
			$to 		= $get_price[1];
			$price_filter  		  = array();
			$price_filter['from'] = $from; 
			$price_filter['to']   = $to; 
			$price_link 		  = "?price=".$from."-".$to."";

			if(isset($_GET['page_amount'])) {
				$page_link = $page_link."&page_amount=".$_GET['page_amount']."";
			}

		}else{
			$from 		= $min_price['selling_price'];
			$to 		= $max_price['selling_price'];
			$price_filter   = ""; 

			if(isset($_GET['page_amount'])) {
				$page_link = $page_link."&page_amount=".$_GET['page_amount']."";
			}

		}

		if(isset($_GET['page_amount'])) {
			if($_GET['page_amount']=="show_all") {
			 	$page_amount = "show_all";
			 } else {
			 	$page_amount = explode("-", $_GET['page_amount']);
				$page_amount = $page_amount[1];
			 }	
		} else {
			$get_page_count = $user->getDetails(PAGINATION_TBL,"pagination_count"," status='1' AND delete_status='0' ");
			$page_amount 	= $get_page_count['pagination_count'];
		}


		$count = $user->productsPaginationCount($b_info['id'],"","",$price_filter,$page_amount);
		$shop_banner = $user->getDetails(PAGE_BANNER_TBL,"page_name,file_name","page_name='Shop AND is_draft='0' AND status='1''");

		if(isset($_GET['price'])){
			$page_link = BASEPATH."product/brand/".$brand_token.$price_link."&";
		} else {
			$page_link = BASEPATH."product/brand/".$brand_token."?";
		}

		if (isset($_GET['p'])) {
			$page = @$_GET['p'];
		} else {
			$page = 1;
		}

		if ($page==1) {
			$previous = "javascript:void();";
		} else {
			$previous = $page_link."p=".($page-1);
		}

		if ($page < $count) {
			$next = $page_link."p=".($page+1);			
		} else {
			$next = "javascript:void();";
		}

		$seo_info    = $user->getDetails(SEO_TBL,"*","page_token='brandproducts'");
		$this->view('home/brandproducts', 
		[	
			'active_menu' 	=> 'products',
			'meta_title'  		=>  $seo_info['meta_title'].' | '.COMPANY_NAME,
			'meta_keywords'  	=>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
			'meta_description' 	=>  $seo_info['meta_description'].' | '.COMPANY_NAME,
			'meta'  			=> 'dynamic',
			'list'			=>  $user->manageProductlist($page,$b_info['id'],$category="",$c_info="",$sort_by,$price_sort="",$from,$to,$page_amount),
			'category'		=>  $user->manageCategorylist(),
			'brand_info' 	=>  $b_info,
			'shop_category'	=>  $user->manageShopCategorylist(),
			'brands'		=>  $user->manageBrandlist(),
			'cart'   		=>  $user->cartInfo(),
			'legal_pages'	=>  $user->getLegalPages(),
			'menu_items'	=>  $user->menuItems(),
			'siteinfo' 		=>  $user->siteInfo($page_token="brandproducts"),
			'shop_banner'	=>  $shop_banner,
			'previous' 		=>  $previous,
			'next' 			=>  $next,
			'count'			=>  $count,
			'token'			=>  $brand_token,
			'location'      =>  $user->getLocationList(),
			'page' 			=>  $user->productsPagination($page,$b_info['id'],"brand",$page_link,$price_filter,$page_amount),
			'page_title'  	=>  COMPANY_NAME,
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

}

public function details($token, $vendor_id="")
{		
	$user  		   = $this->model('Products');
	$product_check = $user->check_query(PRODUCT_TBL,"id"," page_url='".$token."' AND delete_status='0' AND is_draft='0' AND status='1' ");	
	if($product_check){
		$user_id	   = (isset($_SESSION['user_session_id'])) ? $_SESSION['user_session_id'] : 0 ;
		$info 		   = $user->getDetails(PRODUCT_TBL,"*"," page_url='".$token."' ");
		
		$sub_cat_check = $user->check_query(SUB_CATEGORY_TBL,"id"," id='".$info['sub_category_id']."' AND delete_status='0' AND is_draft='0' AND status='1' ");

		if($sub_cat_check) {
			$sub_cat_info  = $user->getDetails(SUB_CATEGORY_TBL,"*"," id='".$info['sub_category_id']."' ");
			$cat_check 	   = $user->check_query(MAIN_CATEGORY_TBL,"id"," id='".$sub_cat_info['category_id']."' AND delete_status='0' AND is_draft='0' AND status='1' ");

			if($cat_check) {

				$review_check =  $user->reviewAndRatingConditionCheck($info['id']);

				if($info['has_variants'] ) {
					if(@$_GET['v']) {
						$variant_info 			= $user->getDetails(PRODUCT_VARIANTS,"*","token='".$_GET['v']."'");
						$info['selling_price']	= $variant_info['selling_price'];
						$info['actual_price']	= $variant_info['actual_price'];
					} else {
						$variant_info 			= $user->getDetails(PRODUCT_VARIANTS,"*","product_id='".$info['id']."' ORDER BY id ASC LIMIT 1");
						$info['selling_price']	= $variant_info['selling_price'];
						$info['actual_price']	= $variant_info['actual_price'];
					}
					
				}
			
				$b_info     = $user->getDetails(BRAND_TBL,"*"," id='".$info['brand_id']."' ");
				$inr_format = $user->inrFormatFields($info);

				if($info['category_type']=="main")
				{
					$cat  		= $user->getDetails(MAIN_CATEGORY_TBL,"id,category"," id='".$info['main_category_id']."' ");
					$category 	= $cat['category'];
				} else {
					$cat  		= $user->getDetails(SUB_CATEGORY_TBL,"id,subcategory"," id='".$info['sub_category_id']."' ");
					$category 	= $cat['subcategory'];
				}
				
				$single_img		 	= $user->getDetails(MEDIA_TBL,"file_name"," item_id='".$info['id']."' AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1 ");
				
				if($single_img>0){
					$product_image 	= $single_img['file_name']!='' ? SRCIMG.$single_img['file_name'] : ASSETS_PATH."no_img.jpg";
				}else{
					$product_image 	= ASSETS_PATH."no_img.jpg";
				}

				$variant = "";
				$selected_variant =0; 

				if($info['has_variants']==1) {
					$variant 		  = $user->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$info['id']."'  ORDER BY id ASC LIMIT 1 ");
					$selected_variant = $variant; 
				}

				if(isset($_GET['v'])) {
					$variant 		  = $user->getDetails(PRODUCT_VARIANTS,"*"," token='".$_GET['v']."'");
					$selected_variant = $variant; 
				}

				// Simplified without vendor concept
				$vendor_price_details = array(
					'min_qty' => 1,
					'max_qty' => 10,
					'vendor_id' => 0
				);
				$vendor_company = array();
				$other_vendors = array();
				$initial_vendor_star_rating = 0;

				// variant_id_fw -> vendor id for wishlist check

				$variant_check = "";
				$variant_id_fw = "";

				if($variant!="") {
					$variant_check = "AND variant_id='".$variant['id']."'  ";
					$variant_id_fw = $variant['id'];
				}

				$cart_id= @$_SESSION["user_cart_id"];
				$vendor_check = "";
				$vendor_id_fw = 0;

				$in_wishlist	  = $user->getDetails(WISHLIST_TBL,"*"," product_id='".$info['id']."' AND user_id='".$user_id."' ".$variant_check." ");
				$wishlist_status  = (($in_wishlist) ? "Remove From Wishlist" : "Add to Wishlist");

				// Check if any variant of this product is in cart
				$cart_check 	  = $user->check_query(CART_ITEM_TBL,"*","product_id='".$info['id']."' AND cart_id='".$cart_id."' ");

				$cart_data        = $user->getDetails(CART_ITEM_TBL,"*","product_id='".$info['id']."' AND cart_id='".$cart_id."' ");
				
				// If item is in cart, get the variant from cart data and set it as selected
				if($cart_data && $cart_data['variant_id'] != '0') {
					$variant = $user->getDetails(PRODUCT_VARIANTS,"*","id='".$cart_data['variant_id']."'");
					$selected_variant = $variant;
				}

				$product_unit  	  = $user->getDetails(PRODUCT_UNIT_TBL,"*","id='".$info['product_unit']."'");

				$overall_review_count = $user->check_query(REVIEW_TBL,"id"," product_id='".$info['id']."' AND approval_status='1' AND del_status='0' AND status='1' ");

				$this->view('home/productsdetail',
				[   
					'active_menu' 	       =>'productsdetail',
					'meta_title'  		   => $info['meta_title'].' | '.COMPANY_NAME,
					'meta_keywords'  	   => $info['meta_keyword'].' | '.COMPANY_NAME,
					'meta_description' 	   => $info['meta_description'].' | '.COMPANY_NAME,
					'meta'  			   => 'dynamic',
					'info' 		           => $info,
					'b_info'			   => $b_info,
					'inr_format'	       => $inr_format,
					'category' 		       => $category,
					'wishlist_status'      => $wishlist_status,
					'token'		           => $user->encryptData($info['id']),
					'releated_products'    => $user->getReleatedproductsList($info['id']),
					'bought_products'      => $user->getBoughtTogetherProductsList($info['id']),
					'attributes'	       => $user->getProductAttributelist($info['id']),
					'product_image'	       => $product_image,
					'vendor_price_details' => $vendor_price_details,
					'product_unit' 		   => $product_unit,
					'variant'			   => $variant,
					'vendor_id_fw'		   => $vendor_id_fw,
					'variant_id_fw'		   => $variant_id_fw,
					'cart_check'		   => $cart_check,
					'cart_data'		 	   => $cart_data,
					'vendors_price_list'   => $user->getVendorPriceTable($info['id']),
					'product_variants'     => $user->getProductVariants($info['id'],$variant),
					'ProductImageList' 	   => $user->getProductImageList($info['id']),
					'product_reviews'	   => $user->getProductReviews($info['id']),
					'product_review_count' => $user->getProductReviewsCount($info['id']),
					'overall_review_count' => $overall_review_count,
					'cart'   		       => $user->cartInfo(),
					'legal_pages'		   => $user->getLegalPages(),
					'menu_items'	       => $user->menuItems(),
					'location'      	   => $user->getLocationList(),
					'siteinfo' 		  	   => $user->siteInfo($page_token="productdetails"),
					'initial_vendor_star_rating' => $initial_vendor_star_rating,
					'pae_title'  	       => COMPANY_NAME,

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

public function api($type)
{
	$user  = $this->model('Products');
	$post = @$_POST["result"];	
	switch ($type) {

	case 'addToCart':
	echo $user->addToCart($_POST);
	break;
	case 'addToWishList':
	echo $user->addToWishList($_POST);
	break;
	case 'removeCartItem':
	echo $user->removeCartItem($post);
	break;
	case 'addProductReview':
	echo $user->addProductReview($_POST);
	break;
	case 'editProductReview':
	echo $user->editProductReview($_POST);
	break;
	case 'getVendorRattingInfo':
	echo $user->getVendorRattingInfo($_POST);
	break;
	case 'addMultiVendorRatting':
		echo $user->addMultiVendorRatting($_POST);
	break;
	case 'getVendorRattingInfoFroEdit':
	echo $user->getVendorRattingInfoFroEdit($_POST);
	break;
	case 'editMultiVendorRatting':
		echo $user->editMultiVendorRatting($_POST);
	break;
	default:
	break;

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
