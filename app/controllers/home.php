<?php

class Home extends Controller
{
	
	public function index()
	{	
		$user = $this->model('Front');
		$info = $user->getDetails(SEO_TBL,"*","page_token='home'");

		$this->view('home/index', 
			[	
				'active_menu' 			=> 'home',
				'meta_title'  			=>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  		=>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description'  	=>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  				=> 'dynamic',
				'banner'				=>  $user->getHomeSlider(),
				'feature_products'  	=>  $user->getFeatureproductsList(),
				'most_view_products'    =>  $user->getMostViewproductsList(),
				'best_seller_products'  =>  $user->getBestSellerproductsList(),
				'offer_banner'  		=>  $user->getofferBanner(),
				'cart'   		        =>  $user->cartInfo(),
				'category_frame' 		=>  $user->getCategoryframe(),
				'legal_pages'			=>  $user->getLegalPages(),
				'testimonials'			=>  $user->getTestimonials(),
				'menu_items'			=>  $user->menuItems(),
				'siteinfo' 				=>  $user->siteInfo($page_token="home"),
				'location' 				=>  $user->getLocationList(),
				'page_title'  			=>  COMPANY_NAME,
			]);
	}

	public function searchitems($search_key="")
	{	
		$user = $this->model('Front');
		
		$search_key = trim(preg_replace('/\s+/',' ', $search_key));
		$search_key = str_replace( "-", ' ', $search_key);

		$filter_url 	="";
		$sort_by 	  	="";
		$price_sort  	='price';

		if(isset($_GET['sortby'])){
			$sort_by 	 = @$_GET['sortby'];
			$filter_url  = "sortby=".$sort_by."";
			$page_link 	    = BASEPATH."home/searchitems/".$search_key."/?".$filter_url."&"; 
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

		$product_with_searchkey = $user->check_query(PRODUCT_TBL,"product_name"," product_name LIKE '%".$search_key."%' AND delete_status='0' AND is_draft='0' AND status='1'");

		if($product_with_searchkey) {
			$search_condition  = "product_name LIKE '%".$search_key."%' ";
			$alter_searchh_key = $search_key;
		} else {
			$search_condition  	= "product_name LIKE '%".$search_key[0]."%' ";
			$alter_searchh_key 	= $search_key[0];
		}

		$min_price 		= $user->getDetails(PRODUCT_TBL,"selling_price"," ".$search_condition." ORDER BY selling_price ASC");
	    $max_price 		= $user->getDetails(PRODUCT_TBL,"selling_price"," ".$search_condition." ORDER BY selling_price DESC");

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
				$page_link 	    = BASEPATH."home/searchitems/".$search_key."/?".$filter_url."&"; 

		    } else {
				$filter_url 	= "price=".$filter_from."-".$filter_to; 
				$page_link 	    = BASEPATH."home/searchitems/".$search_key."/?price=".$filter_from."-".$filter_to."&";

		    }

		    if(isset($_GET['page_amount'])) {
				$page_link = $page_link."&page_amount=".$_GET['page_amount']."";
			} 
		} else {
			$filter_from 	= (($min_price)? $min_price['selling_price'] : 0 );
			$filter_to 		= (($max_price)? $max_price['selling_price'] : 0 );
			$price_filter   = ""; 
			if(!isset($_GET['sortby'])){
				$page_link 	= BASEPATH."home/searchitems/".$search_key."/?"; 
			} else {
				$page_link  = BASEPATH."home/searchitems/".$search_key."/?".$filter_url."&"; 
				if(isset($_GET['page_amount'])) {
					$page_link = $page_link."page_amount=".$_GET['page_amount']."&";
				}
			}

			if(isset($_GET['page_amount'])) {
				$page_link = $page_link."page_amount=".$_GET['page_amount']."&";
			}
		}

		if(isset($_GET['brands'])) {
			$brands    = $_GET['brands'];
			$page_link = $page_link."brands=".$_GET['brands']."&";
		} else {
			$brands    = "";
		}

		$count = $user->productsPaginationCount($brands,"","",$price_filter,$page_amount,$alter_searchh_key);
		$shop_banner = $user->getDetails(PAGE_BANNER_TBL,"page_name,file_name","page_name='Shop'");


		if (isset($_GET['p'])) {
			$page = @$_GET['p'];
		}else{
			$page = 1;
		}
		if ($page==1) {
			$previous = "javascript:void();";
		}else {
			if(isset($_GET['price'])){
				if(isset($_GET['sortby'])){
					$previous 	  = $page_link."p=".($page-1); 
			    } else {
					$previous 	  = $page_link."p=".($page-1); 
			    }
			} else {
				$previous = $page_link."p=".($page-1);
			}
		}

		if ($page < $count) {
			if(isset($_GET['price'])){
				if(isset($_GET['sortby'])){
					$next 	  = $page_link."p=".($page+1); 
			    } else {
					$next 	  = $page_link."p=".($page+1); 
			    }
			} else {
				$next = $page_link."p=".($page+1);
			}		
		}else {
			$next = "javascript:void();";
		}

		$seo_info = $user->getDetails(SEO_TBL,"*","page_token='searchitems'");

		$this->view('home/searchitem', 
		[	
			'active_menu' 		=> 'products',
			'meta_title'  		=>  $seo_info['meta_title'].' | '.COMPANY_NAME,
			'meta_keywords'  	=>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
			'meta_description'  =>  $seo_info['meta_description'].' | '.COMPANY_NAME,
			'meta'  			=> 'dynamic',
			'shop_banner'   	=>  $shop_banner,
			'list'		    	=>  $user->manageProductlist($page,$brands,"","",$sort_by,$price_sort,$filter_from,$filter_to,$page_amount,$search_key),
			'category'	   		=>  $user->manageCategorylist(),
			'brands'	   		=>  $user->getSearchItemBrandsList($search_key),
			'cart'   			=>  $user->cartInfo(),
			'legal_pages'		=>  $user->getLegalPages(),
			'menu_items'		=>  $user->menuItems(),
			'siteinfo' 			=>  $user->siteInfo($page_token="searchitems"),
			'previous' 			=>  $previous,
			'search_key'    	=>  $search_key,
			'next' 				=>  $next,
			'count'				=>  $count,
			'page' 				=>  $user->productsPagination($page,"","",$page_link,$price_filter,$page_amount,$brands,$alter_searchh_key),
			'location'    		=>  $user->getLocationList(),
			'page_title'  		=>  COMPANY_NAME,
			'min_price'			=>  $min_price['selling_price'],
			'max_price'			=>  $max_price['selling_price'],
		]);
		
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


	public function logout()
	{
		if(isset($_SESSION['user_session_id'])){
			$user = $this->model('Front');
			unset($_SESSION['user_session_id']);
			session_destroy();
		}
		echo "<script type='text/javascript'> document.location = '".BASEPATH."'; </script>";
	}


	public function api($type)
	{
		$user  = $this->model('Products');
		$post = @$_POST["result"];	
		switch ($type) {
			case 'searchitems':
				echo $user->searchitems($post);
			break;

			case 'getLocationList':
				$data = $user -> getLocationList(@$_REQUEST['filter_name']);
				echo json_encode($data);
			break;

			case 'getLocationPincodeList':
				echo $user->getLocationPincodeList($post);
			break;

			case 'selectpincode':
				echo $user->selectPincode($post);
			break;

			case 'getLocationsForGroup':
				$data = $user -> getLocationsForGroup($post);
				echo json_encode($data);
			break;

			case 'selectLocationList';
				echo $user -> selectLocationList($_POST);
			break;

			default:
			break;
		}
	}

	
}


?>