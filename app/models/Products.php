<?php

require_once 'Model.php';
require_once 'app/core/classes/PHPMailerAutoload.php';

class Products extends Model
{	
	// select Location
	function selectLocationList($input="")
	{		
		$token 		= $this->decryptData($input['result']);
		$check 		= $this ->check_query(LOCATION_TBL,"id,token"," id='".$token."' AND status='1' ");

		if($check !=0){
			$locinfo 	  = $this -> getDetails(LOCATION_TBL,"*"," id='".$token."' ");
			$loc_grp_info = $this -> getDetails(GROUP_TBL,"*"," id='".$locinfo['group_id']."' ");
			$loc_city_info = $this -> getDetails(LOCATIONGROUP_TBL,"*"," id='".$loc_grp_info['city_id']."' ");

        	$_SESSION["location_group_id"] 	= $loc_city_info['id'];
        	$_SESSION["location_area_id"] 	= $locinfo['id'];
        	$_SESSION['group_name'] 	    = $loc_city_info['group_name'];
        	$_SESSION['area_name'] 	        = $locinfo['location'];
         	$this->setVendorDetails();  // Set Vendor Id's array in session 
         	return $token;  	
        }else{
        	return $this->errorMsg("Sorry This Location Not Available");
        }
	}

	// Select Pincode
	function selectPincode($input="")
	{		
		$token 		= $this->decryptData($input);
		$check 		= $this ->check_query(LOCATION_TBL,"id,token"," id='".$token."' AND status='1' ");

		if($check !=0){
			$locinfo 	   = $this -> getDetails(LOCATION_TBL,"*"," id='".$token."' ");
			$loc_grp_info  = $this -> getDetails(GROUP_TBL,"*"," id='".$locinfo['group_id']."' ");
			$loc_city_info = $this -> getDetails(LOCATIONGROUP_TBL,"*"," id='".$loc_grp_info['city_id']."' ");

        	$_SESSION["location_group_id"] 	= $loc_city_info['id'];
        	$_SESSION["location_area_id"] 	= $locinfo['id'];
        	$_SESSION['group_name'] 	    = $loc_city_info['group_name'];
        	$_SESSION['area_name'] 	        = $locinfo['location'];
         	$this->setVendorDetails();  // Set Vendor Id's array in session 
         	return $token;  	
        }else{
        	return $this->errorMsg("Sorry This Location Not Available");
        }
	}

	// Set Vendors ID'sArray in session

	function setVendorDetails()
    {    
        $location_group_id = @$_SESSION['location_group_id'];
        $location_area_id  = @$_SESSION['location_area_id'];
        $vendors_id        = array();
        $query                = "SELECT * FROM  ".VENDOR_TBL." WHERE delete_status='0' AND active_status='1' AND is_draft='0' AND status='1' ";
        $exe               = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
            while ($row = mysqli_fetch_array($exe)) {
                
                $vendor_location_group = in_array($location_group_id, explode(",", $row['delivery_locations']));
                $vendor_location_area  = in_array($location_area_id,  explode(",", $row['delivery_areas']));
                $today                 = strtotime(date("Y/m/d"));
                $valid_to              = strtotime(date("Y/m/d",strtotime($row['valid_to'])));
               
                /*if( $valid_to >= $today ) {
                    if($vendor_location_group == true && $vendor_location_area == true)
                    {
                        $vendors_id[]  = $row['id'];                
                    }
                }*/

                if($vendor_location_group == true && $vendor_location_area == true)
                {
                    $vendors_id[]  = $row['id'];                
                }
                
            }
        }
          
        $_SESSION['vendors_at_this_location'] = $vendors_id;
    }
	

	//  Get Vendor Based on  Location

	function getVendorForThisLocation($location_id)
	{
		$query = "SELECT * FROM ".VENDOR_TBL." WHERE vendor_location='".$location_id."' ";
		$exe   = $this->exeQuery($query);
		return $query;

	}

	// Search Items 

	function searchitems($data)
	{	
		$location_id    =  isset($_SESSION['group_id']); 
		$vendors 		= $this->getVendorForThisLocation($location_id);

		$data = strtolower( $data );
		$data = trim(preg_replace('/\s+/',' ', $data));

		// Results 
		$result 		= array();
		$products 		= array();
		$category 		= array();
		$sub_category 	= array();
		$layout = "";
		if($data!="") {
			$p_query  	= "SELECT * FROM ".PRODUCT_TBL." WHERE product_name LIKE '%".$data."%' ";
			$p_exe 		= $this->exeQuery($p_query);
		    if(mysqli_num_rows($p_exe)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($p_exe)) {
		    		$list  = $this->editPagePublish($row);
		    		$products[$list['page_url']] = $list['product_name'];
		    		$i++;
		    	}
		    }
		    $c_query  	= "SELECT * FROM ".MAIN_CATEGORY_TBL." WHERE category LIKE '%".$data."%' ";
			$c_exe 		= $this->exeQuery($c_query);
		    if(mysqli_num_rows($c_exe)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($c_exe)) {
		    		$list  = $this->editPagePublish($row);
		    		$category[$list['page_url']] = $list['category'];
		    		$i++;
		    	}
		    }
		    $sc_query  	= "SELECT * FROM ".SUB_CATEGORY_TBL." WHERE subcategory LIKE '%".$data."%' ";
			$sc_exe 		= $this->exeQuery($sc_query);
		    if(mysqli_num_rows($sc_exe)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($sc_exe)) {
		    		$list  = $this->editPagePublish($row);
		    		$sub_category[$list['page_url']] = $list['subcategory'];
		    		$i++;
		    	}
		    }
		}

		if(count($products)>0) {
			$layout .="<div class='menu_title'><strong>Products</strong></div>";
				foreach ($products as $key => $value) {
					$layout.="<a href='".BASEPATH."product/details/".$key."'><div class='menu_items'>".$value."</div></a>";
				}
		}
		if(count($category)>0) {
			$layout .="<div class='menu_title'><strong>Category</strong></div>";
				foreach ($category as $key => $value) {
					$layout.="<a href='".BASEPATH."product/category/".$key."'><div class='menu_items'>".$value."</div></a>";
				}
		}
		if(count($sub_category)>0) {
			$layout .="<div class='menu_title'><strong>Sub Category</strong></div>";
				foreach ($sub_category as $key => $value) {
					$layout.="<a href='".BASEPATH."product/subcategory/".$key."'><div class='menu_items'>".$value."</div></a>";
				}
		}

		if($layout=="") {
			$layout .= "<div class='menu_items'>No Items</div>";
		}

	    return  $layout;
	}

	//subcategory Pagination Count

	function subcategoryPaginationCount($cat_id,$page_amount,$filter_array=[])
	{
		$layout 		="";
		$condition 		= "";

		$sql = "SELECT P.id
			FROM ".PRODUCT_TBL." P 
			LEFT JOIN ".REVIEW_TBL." RT ON(RT.product_id=P.id) 
			WHERE P.sub_category_id='".$cat_id."' AND P.delete_status='0' AND P.is_draft='0' AND P.status='1'  " ;
		
			$sql.= $this->filterQuery($filter_array,$cat_id); 

		//echo $sql;
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records
		$num_rec_per_page = (($page_amount=="show_all")? mysqli_num_rows($rs_result) : $page_amount );
		$total_pages = ceil($total_records / $num_rec_per_page);

		$result = array();
		$result['total_pages']= $total_pages;
		$result['total_records']= $total_records;

		return $result;

	}

	//subcategory Pagination

	function subcategoryPagination($current="",$cat_id,$view_page,$view_page_link,$price_filter,$page_amount,$brands,$filter_array=[],$category_token)
	{
		unset($filter_array['p']);
		$url = $this->build_http_query($filter_array);
		$url_starting = count($filter_array)>0 ? "?".$url."&" : "?";

		//echo $url_starting;

		$layout ="";
		$condition = "";
		
		$sql = "SELECT P.id	
			FROM ".PRODUCT_TBL." P 
			LEFT JOIN ".REVIEW_TBL." RT ON(RT.product_id=P.id)
			WHERE P.sub_category_id='".$cat_id."' AND P.delete_status='0' AND P.is_draft='0' AND P.status='1'  " ;

		$sql.= $this->filterQuery($filter_array,$cat_id);

		//echo $sql;

		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = @mysqli_num_rows($rs_result);  //count number of records


		$total_pages = ceil($total_records / (($page_amount=="show_all")? mysqli_num_rows($rs_result) : $page_amount ));
		$page = $current;
		$limit= 6;
		if ($total_pages >=1 && $page <= $total_pages){
			$counter = 1;
			$link = "";
			$dot_link = "<li><a href='javascript:void();' >...</a></li>";

			if ($page >  ($limit/2)){
				$layout .= "<li><a href='".BASEPATH."product/subcategory/".$category_token.$url_starting."p=1' >1</a></li>";
			}
			for ($i=$page; $i<=$total_pages;$i++){
				$current_page = (($i==$current) ? "current" : "");
	            if($counter < $limit) {
	            	$layout .= "<li class='".$current_page."'><a href='".BASEPATH."product/subcategory/".$category_token.$url_starting."p=".$i."'>".$i."</a></li>";
	            }
	            $counter++;
	        }
	        if ($page < $total_pages - ($limit/2)){
	        	$current_page = (($i==$current) ? "current" : "");
	        	$layout .= $dot_link."<li class='".$current_page."'><a href='".BASEPATH."product/subcategory/".$category_token.$url_starting."p=".($i-1)."'>".($i-1)."</a></li>"; 
	        }
		}
		return $layout;
	}

	//Product Pagination Count

	function productsPaginationCount($brands,$price_filter,$page_amount,$filter_array=[])
	{ 	
		$layout ="";
		$condition = "";
  		
  		$sql = "SELECT P.id
			FROM ".PRODUCT_TBL." P 
			LEFT JOIN ".REVIEW_TBL." RT ON(RT.product_id=P.id) 
			WHERE P.delete_status='0' AND P.is_draft='0' AND P.status='1'  " ;
		
		$sql.= $this->shopFilterQuery($filter_array); 

		//echo $sql;

		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = @mysqli_num_rows($rs_result);  //count number of records
		$num_rec_per_page = (($page_amount=="show_all")? mysqli_num_rows($rs_result) : $page_amount );
		$total_pages = ceil($total_records / $num_rec_per_page);
		
		$result = array();
		$result['total_pages']= $total_pages;
		$result['total_records']= $total_records;

		return $result;
	}

	//Product Pagination

	function productsPagination($current="",$view_page_link,$price_filter,$page_amount,$brands,$filter_array=[])
	{
		unset($filter_array['p']);
		$url = $this->build_http_query($filter_array);
		$url_starting = count($filter_array)>0 ? "?".$url."&" : "?";

		$layout ="";
		$condition = "";


  		$sql = "SELECT P.id	
			FROM ".PRODUCT_TBL." P 
			LEFT JOIN ".REVIEW_TBL." RT ON(RT.product_id=P.id)
			WHERE P.delete_status='0' AND P.is_draft='0' AND P.status='1'  " ;

		$sql.= $this->shopFilterQuery($filter_array);

		//echo $sql;

		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = @mysqli_num_rows($rs_result);  //count number of records


		$total_pages = ceil($total_records / (($page_amount=="show_all")? mysqli_num_rows($rs_result) : $page_amount ));
		$page = $current;
		$limit= 6;
		if ($total_pages >=1 && $page <= $total_pages){
			$counter = 1;
			$link = "";
			$dot_link = "<li><a href='javascript:void();' >...</a></li>";

			if ($page >  ($limit/2)){
				$layout .= "<li><a href='".BASEPATH."product/".$url_starting."p=1' >1</a></li>";
			}
			for ($i=$page; $i<=$total_pages;$i++){
				$current_page = (($i==$current) ? "current" : "");
	            if($counter < $limit) {
	            	$layout .= "<li class='".$current_page."'><a href='".BASEPATH."product/".$url_starting."p=".$i."'>".$i."</a></li>";
	            }
	            $counter++;
	        }
	        if ($page < $total_pages - ($limit/2)){
	        	$current_page = (($i==$current) ? "current" : "");
	        	$layout .= $dot_link."<li class='".$current_page."'><a href='".BASEPATH."product/".$url_starting."p=".($i-1)."'>".($i-1)."</a></li>"; 
	        }
		}
		return $layout;

	}


	// Product List

	function manageProductlist($page="",$brands="",$sort_by="",$price_sort="",$from="",$to="",$page_amount="",$filter_array=[])
	{		
		$layout         = "";
  		$condition 		= "";
  		$total_products = "";
  		
		$q = "SELECT P.id,P.page_url,P.sku,P.has_variants,P.product_name,P.category_type,P.main_category_id,P.sub_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,T.tax_class as taxClass ,C.category,C.page_url as cat_url ,W.fav_status,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url ,PV.id as p_variant_id,P.display_tag,P.display_tag_end_date,DT.display_tag as display_tag_title,DT.status as tag_status,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id DESC LIMIT 1) as secondary_img
			FROM ".PRODUCT_TBL." P 
					LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id) 
					LEFT JOIN ".REVIEW_TBL." RT ON(RT.product_id=P.id)
					LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id)   
					LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=SC.category_id) 
					LEFT JOIN ".PRODUCT_VARIANTS." PV ON (P.id=PV.product_id ) 
					LEFT JOIN ".PRODUCT_DISPLAY_TAG." DT ON (P.display_tag=DT.id)  
					LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.user_id='".@$_SESSION['user_session_id']."') 
			WHERE P.delete_status='0' AND P.is_draft='0' AND P.status='1' AND SC.status='1' AND C.status='1' " ;

		$q .= $this->shopFilterQuery($filter_array);
			
	    if ($sort_by=='lowtohigh') {
	        $q .= "GROUP BY P.id ORDER BY CAST(P.selling_price AS DECIMAL(10,2)) ASC";
	    }elseif($sort_by=='hightolow'){
	    	$q .= "GROUP BY P.id ORDER BY CAST(P.selling_price AS DECIMAL(10,2)) DESC";
	  	}elseif($sort_by=='asc'){
	      	$q .= "GROUP BY P.id ORDER BY P.product_name ASC";
	  	}elseif($sort_by=='desc'){
	      	$q .= "GROUP BY P.id ORDER BY P.product_name DESC";
	  	} else {
	  		$q .= "GROUP BY P.id ORDER BY P.product_name ASC";
	  	}

    	if($page_amount!="show_all") {
    		$start_from = ($page-1)*$page_amount;
  			$page_count = $page_amount;
       		$q .= "  LIMIT $start_from, $page_count";
    	} else {
    		$start_from = 0;
    	}

        $q_t_p = $this->exeQuery($t_p);
        $total_products = mysqli_num_rows($q_t_p);
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
			$i=1;
			while($row = mysqli_fetch_array($exe))  {

				$list     = $this->editPagePublish($row);
				

				if ($list['selling_price'] >= $from && $list['selling_price'] <= $to) {
					
					$wishlist_text 	 = (($list['fav_status']=="") ? "Add to wishlist" : "Remove from wishlist");
					$status 		 = (($list['fav_status']!="") ? "favourite_item" : "");
					

					// Get Product Price

					$variant    = "";
					$variant_id = "";

					if($list['has_variants']==1) {
						$variant  = $this->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$list['id']."'  ORDER BY id ASC LIMIT 1 ");
						$variant_id = $variant['id'];
					}


					if(isset($_SESSION['user_session_id'])) {
						$wishlist = "<li class='wishlist '><a href='".BASEPATH."login' class='addToWishList $status'   data-option='".$this->encryptData($list['id'])."' data-id='".$this->encryptData($list['id'])."' data-variant_id='".$variant_id."' title='".$wishlist_text."'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";

					}else{
						$wishlist = "<li class='wishlist'><a href='".BASEPATH."login' title='Login to add Wishlist'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";
					}


					$cartInfo = $this->cartInfo();
					$cart_products = $cartInfo['cart_product_ids'];

					if(in_array($list['id'], $cart_products)) {
						$add_to_cart ="Already in cart";
					} else {
						$add_to_cart ="Add to Cart";
					}

					if($list['display_tag']!=0 && $list['display_tag_end_date'] && $list['tag_status']==1) {
						$today    = date("Y-m-d");
						$end_date = date("Y-m-d",strtotime($list['display_tag_end_date']));
						if($end_date >= $today) {
							$display_tag = "<span class='product-badge'>".$list['display_tag_title']."</span>";
						} else {
							$display_tag = "";
						}
					} else {
						$display_tag = "";
					}

					$product_price = $this->getProductPrice($list['id'],$variant);
					
					$product_image     = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;

					$secondary_image   = $list['secondary_img']!='' ? SRCIMG.$list['secondary_img'] : ASSETS_PATH."no_img.jpg" ;

					$product_category = $list['category_type']=="main" ? "<a href='".BASEPATH."product/category/".$list['cat_url']."'>".$list['category']."</a>" : "<a href='".BASEPATH."product/subcategory/".$list['sub_cat_url']."'>".$list['subcategory']."</a>" ;
					
					// echo "<pre>";
					// print_r($list);
					// echo "</pre>";

					$layout .= "
						<div class='product-card' style='display: block !important;'>
							<a href='".BASEPATH."product/details/".$list['page_url']."'>
							<div class='product-image min-img-card'>
								<img src='".$product_image."' alt='".$list['product_name']."'>
								".$display_tag."
								<div class='product-actions'>
								<button class='action-btn wishlist-action'>
									<i class='fas fa-heart'></i>
								</button>
								<button class='action-btn quick-view'>
									<i class='fas fa-shopping-cart'></i>
								</button>
								</div>
							</div>
							<div class='product-info'>
								<h3 class='product-title'>".$list['product_name']."</h3>
								<div class='product-price'>
								<span class='current-price'>Rs.".$this->inrFormat($product_price['selling_price'])."</span>
								<span class='original-price'>Rs.".$this->inrFormat($list['actual_price'])."</span>
								</div>
							</div>
							</a>
						</div>";
					$i++;
				}
			}
		} else {
			$layout = "<div class='cart_content '>No Records Found !!</div>";
		}
	 	$result = array();
		$result['layout'] 	  = $layout;
		$result['start_from'] = (mysqli_num_rows($exe)!=0)? $start_from + 1 : 0;
		$result['start_to']   = $start_from + mysqli_num_rows($exe);
		return $result;
	}

	//shop product filter sort
	function shopProductSortingFilters($filter_url,$current_page)
	{

		$filter_array 	= $this->convertUrlToArray($filter_url,$current_page);
		$selected = $filter_array['sortby'];
		unset($filter_array['url']);
		unset($filter_array['sortby']);

		$url = $this->build_http_query($filter_array);
		$url_starting = count($filter_array)>0 ? "?".$url."&" : "?";

		$enable_selected = $selected=='asc' ? "selected" : "";

		$layout = '<option value="'.$url_starting.'sortby=asc" '.($selected=='asc' ? "selected" : "").' >Product Name: A - Z </option>
                   <option value="'.$url_starting.'sortby=desc" '.($selected=='desc' ? "selected" : "").' >Product Name: Z - A</a></option>
                   <option value="'.$url_starting.'sortby=lowtohigh" '.($selected=='lowtohigh' ? "selected" : "").'>Sort by price: Low to High</option>
                   <option value="'.$url_starting.'sortby=hightolow" '.($selected=='hightolow' ? "selected" : "").' >Sort by price: High to Low</option>';

        return $layout;
	}

	//Product Pagination Count

	function maincategoryPaginationCount($brands,$category,$cat_id,$price_filter,$page_amount,$filter_array=[])
	{ 	
		$layout ="";
		$condition = "";

  		if($category=='main') {
			$get_subcategories = $this->getSubCategoryList($cat_id);
  			$condition = "AND sub_category_id IN (" . implode(',', array_map('intval',$get_subcategories)). ") $price_filter ";
  		}
  		
  		$sql = "SELECT P.id
			FROM ".PRODUCT_TBL." P 
			LEFT JOIN ".REVIEW_TBL." RT ON(RT.product_id=P.id) 
			WHERE P.delete_status='0' $condition AND P.is_draft='0' AND P.status='1'  " ;
		
		$sql.= $this->maincatFilterQuery($filter_array,$cat_id); 

		//echo $sql;

		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = @mysqli_num_rows($rs_result);  //count number of records
		$num_rec_per_page = (($page_amount=="show_all")? mysqli_num_rows($rs_result) : $page_amount );
		$total_pages = ceil($total_records / $num_rec_per_page);
		
		$result = array();
		$result['total_pages']= $total_pages;
		$result['total_records']= $total_records;

		return $result;
	}

	//Product Pagination

	function maincategoryPagination($current="",$id,$view_page,$view_page_link,$price_filter,$page_amount,$brands,$filter_array=[],$category_token)
	{
		unset($filter_array['p']);
		$url = $this->build_http_query($filter_array);
		$url_starting = count($filter_array)>0 ? "?".$url."&" : "?";

		$layout ="";
		$condition = "";


		
		if($view_page=="category") {
			$get_subcategories = $this->getSubCategoryList($id);
  			$condition = "AND sub_category_id IN (" . implode(',', array_map('intval',$get_subcategories)). ") $price_filter ";
  		}


  		$sql = "SELECT P.id	
			FROM ".PRODUCT_TBL." P 
			LEFT JOIN ".REVIEW_TBL." RT ON(RT.product_id=P.id)
			WHERE P.delete_status='0' $condition AND P.is_draft='0' AND P.status='1'  " ;

		$sql.= $this->maincatFilterQuery($filter_array,$cat_id);

		//echo $sql;

		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = @mysqli_num_rows($rs_result);  //count number of records


		$total_pages = ceil($total_records / (($page_amount=="show_all")? mysqli_num_rows($rs_result) : $page_amount ));
		$page = $current;
		$limit= 6;
		if ($total_pages >=1 && $page <= $total_pages){
			$counter = 1;
			$link = "";
			$dot_link = "<li><a href='javascript:void();' >...</a></li>";

			if ($page >  ($limit/2)){
				$layout .= "<li><a href='".BASEPATH."product/category/".$category_token.$url_starting."p=1' >1</a></li>";
			}
			for ($i=$page; $i<=$total_pages;$i++){
				$current_page = (($i==$current) ? "current" : "");
	            if($counter < $limit) {
	            	$layout .= "<li class='".$current_page."'><a href='".BASEPATH."product/category/".$category_token.$url_starting."p=".$i."'>".$i."</a></li>";
	            }
	            $counter++;
	        }
	        if ($page < $total_pages - ($limit/2)){
	        	$current_page = (($i==$current) ? "current" : "");
	        	$layout .= $dot_link."<li class='".$current_page."'><a href='".BASEPATH."product/category/".$category_token.$url_starting."p=".($i-1)."'>".($i-1)."</a></li>"; 
	        }
		}
		return $layout;

	}


	// Product List

	function manageMaincategoryProductlist($page="",$brands="",$category="",$cat_id="",$sort_by="",$price_sort="",$from="",$to="",$page_amount="",$filter_array=[])
	{		
		$layout 		= "";
  		$condition 		= "";
  		$total_products = "";
		$vendor_ids     = ((count($_SESSION['vendors_at_this_location']) > 0 )? implode(',', array_map('intval',$_SESSION['vendors_at_this_location'])) : 0 ); 
		

  		if($category=='main') {
			$get_subcategories = $this->getSubCategoryList($cat_id);
  			$condition 		   = "AND P.sub_category_id IN (" . implode(',', array_map('intval',$get_subcategories)). ") ";
  		}
  		
		$q = "SELECT P.id,P.page_url,P.sku,P.has_variants,P.product_name,P.category_type,P.main_category_id,P.sub_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,T.tax_class as taxClass ,C.category,C.page_url as cat_url ,W.fav_status,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url ,PV.id as p_variant_id,P.display_tag,P.display_tag_end_date,DT.display_tag as display_tag_title,DT.status as tag_status,
				(SELECT selling_price FROM ".VENDOR_PRODUCTS_TBL." WHERE product_id=P.id AND PV.id='1'  ORDER BY id ASC LIMIT 1) as product_vendor_price,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id DESC LIMIT 1) as secondary_img,
				(SELECT selling_price FROM ".VENDOR_PRODUCTS_TBL." WHERE product_id=P.id  AND stock >= min_qty AND status='1' AND vendor_id IN (" . $vendor_ids .") AND (variant_id='0' OR variant_id='1')  ORDER BY selling_price ASC LIMIT 1) as vendor_selling_price 
			FROM ".PRODUCT_TBL." P 
					LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id) 
					LEFT JOIN ".REVIEW_TBL." RT ON(RT.product_id=P.id)
					LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id)   
					LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=SC.category_id) 
					LEFT JOIN ".PRODUCT_VARIANTS." PV ON (P.id=PV.product_id ) 
					LEFT JOIN ".PRODUCT_DISPLAY_TAG." DT ON (P.display_tag=DT.id)  
					LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.user_id='".@$_SESSION['user_session_id']."') 
			WHERE P.delete_status='0' AND P.is_draft='0' $condition AND P.status='1' AND SC.status='1' AND C.status='1' " ;

			$q .= $this->maincatFilterQuery($filter_array,$cat_id);
			
			//echo $q;
        // Sort By function
	    if ($sort_by=='lowtohigh') {
	        $q .= "GROUP BY P.id ORDER BY CAST(P.selling_price AS DECIMAL(10,2)) ASC";
	    }elseif($sort_by=='hightolow'){
	    	$q .= "GROUP BY P.id ORDER BY CAST(P.selling_price AS DECIMAL(10,2)) DESC";
	  	}elseif($sort_by=='asc'){
	      	$q .= "GROUP BY P.id ORDER BY P.product_name ASC";
	  	}elseif($sort_by=='desc'){
	      	$q .= "GROUP BY P.id ORDER BY P.product_name DESC";
	  	} else {
	  		$q .= "GROUP BY P.id ORDER BY P.product_name ASC";
	  	}

    	if($page_amount!="show_all") {
    		$start_from = ($page-1)*$page_amount;
  			$page_count = $page_amount;
       		$q .= "  LIMIT $start_from, $page_count";
    	} else {
    		$start_from = 0;
    	}

        $q_t_p = $this->exeQuery($t_p);
        $total_products = mysqli_num_rows($q_t_p);
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	 		    	$i=1;
	 		    	while($row = mysqli_fetch_array($exe))  {

	 		    		$list     = $this->editPagePublish($row);

	 		    		$vr_price = (($list['vendor_selling_price']!="")? $list['vendor_selling_price'] : $list['selling_price']  );

 						if ($vr_price >= $from && $vr_price <= $to) {
 							
 							$wishlist_text 	 = (($list['fav_status']=="") ? "Add to wishlist" : "Remove from wishlist");
							$status 		 = (($list['fav_status']!="") ? "favourite_item" : "");
	    					

	    					// Get Product Price

				            $variant    = "";
				            $variant_id = "";

				            if($list['has_variants']==1) {
								$variant  = $this->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$list['id']."'  ORDER BY id ASC LIMIT 1 ");
								$variant_id = $variant['id'];
							}

							$product_price = $this->getProductPrice($list['id'],$variant);

							if(isset($product_price['vendor_id']))
							{
								$vendor_id = $product_price['vendor_id'];
							} else {
								$vendor_id = "Sapiens";
							}

							if(isset($_SESSION['user_session_id'])) {
								$wishlist = "<li class='wishlist '><a href='".BASEPATH."login' class='addToWishList $status'   data-option='".$this->encryptData($list['id'])."' data-id='".$this->encryptData($list['id'])."' data-vendor_id='".$vendor_id."' data-variant_id='".$variant_id."' title='".$wishlist_text."'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";

							}else{
								$wishlist = "<li class='wishlist'><a href='".BASEPATH."login' title='Login to add Wishlist'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";
							}


				            $cartInfo = $this->cartInfo();
				            $cart_products = $cartInfo['cart_product_ids'];

				            if(in_array($list['id'], $cart_products)) {
				            	$add_to_cart ="Already in cart";
				            } else {
				            	$add_to_cart ="Add to Cart";
				            }

				            if($list['display_tag']!=0 && $list['display_tag_end_date'] && $list['tag_status']==1) {
								$today    = date("Y-m-d");
								$end_date = date("Y-m-d",strtotime($list['display_tag_end_date']));
								if($end_date >= $today) {
									$display_tag = "<div class='label_product display_tag'>
											".$list['display_tag_title']."
										</div>";
								} else {
									$display_tag = "";
								}
							} else {
								$display_tag = "";
							}

				            $product_image    = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;

				            $secondary_image   = $list['secondary_img']!='' ? SRCIMG.$list['secondary_img'] : ASSETS_PATH."no_img.jpg" ;

			            	$product_category = $list['category_type']=="main" ? "<a href='".BASEPATH."product/category/".$list['cat_url']."'>".$list['category']."</a>" : "<a href='".BASEPATH."product/subcategory/".$list['sub_cat_url']."'>".$list['subcategory']."</a>" ;
		 		    		$layout .= "
		 		    		 	<div class='col-lg-4 col-xl-3 col-md-6 col-12'>
		                            <div class='single_product single_product_list_width_hight' >
		                                <div class='product_name grid_name'>
		                                    <h3><a href='".BASEPATH."product/details/".$list['page_url']."' title='".$list['product_name']."'>".$list['product_name']."</a></h3>
		                                    <p class='manufacture_product'>$product_category</p>
		                                </div>
		                                <div class='product_thumb'>
		                                    <a class='primary_img product_wish_list_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$product_image."' alt='".$list['product_name']."' title='".$list['product_name']."'></a>
		                                    <a class='secondary_img product_wish_list_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$secondary_image."' alt='".$list['product_name']."' title='".$list['product_name']."'></a>
		                                    ".$display_tag."
		                                </div>	                                    
										<div class='action_links'>
											<ul>
												".$wishlist."
											</ul>
										</div>
		                                <div class='product_content grid_content'>
		                                    <div class='content_inner'>
		                                        <div class='product_footer d-flex align-items-center'>
		                                            <div class='price_box'>
		                                                <span class='current_price'>Rs.".number_format((($list['vendor_selling_price'])? $list['vendor_selling_price'] : $list['selling_price']))."</span>
														<span class='old_price'>Rs.".number_format($list['actual_price'])."</span>
		                                            </div>
		                                            <div class='add_to_cart'>
		                                                <a href='".BASEPATH."product/details/".$list['page_url']."' class='addToCart_pending' data-quantity='1'   data-option='".$this->encryptData($list['id'])."' title='".$add_to_cart."'><span class='lnr lnr-cart'></span></a>
		                                            </div>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>";
		                    $i++;
 						}
			    	}
	 		    } else {
					$layout = "<div class='cart_content '>No Records Found !!</div>";
				}
	 	$result = array();
		$result['layout'] 	  = $layout;
		$result['start_from'] = (mysqli_num_rows($exe)!=0)? $start_from + 1 : 0;
		$result['start_to']   = $start_from + mysqli_num_rows($exe);
		return $result;
	}


		// Sub Category 

	function mainCategorySortingFilters($filter_url,$current_page)
	{

		$filter_array 	= $this->convertUrlToArray($filter_url,$current_page);
		$selected = $filter_array['sortby'];
		unset($filter_array['url']);
		unset($filter_array['sortby']);

		$url = $this->build_http_query($filter_array);
		$url_starting = count($filter_array)>0 ? "?".$url."&" : "?";

		$enable_selected = $selected=='asc' ? "selected" : "";

		$layout = '<option value="'.$url_starting.'sortby=asc" '.($selected=='asc' ? "selected" : "").' >Product Name: A - Z </option>
                   <option value="'.$url_starting.'sortby=desc" '.($selected=='desc' ? "selected" : "").' >Product Name: Z - A</a></option>
                   <option value="'.$url_starting.'sortby=lowtohigh" '.($selected=='lowtohigh' ? "selected" : "").'>Sort by price: Low to High</option>
                   <option value="'.$url_starting.'sortby=hightolow" '.($selected=='hightolow' ? "selected" : "").' >Sort by price: High to Low</option>';

        return $layout;
	}

	// Sub Category Product List

	function manageSubcategoryProductlist($page="",$cat_id="",$sort_by="",$price_sort="",$from="",$to="",$page_amount="",$filter_array=[])
	{		
		$layout 		= "";
  		$condition 		= "";
  		$total_products = "";
		$vendor_ids     = ((count($_SESSION['vendors_at_this_location']) > 0 )? implode(',', array_map('intval',$_SESSION['vendors_at_this_location'])) : 0 ); 

		$q = "SELECT P.id,P.page_url,P.sku,P.has_variants,P.product_name,P.category_type,P.main_category_id,P.sub_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,T.tax_class as taxClass ,C.category,C.page_url as cat_url ,W.fav_status,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url ,PV.id as p_variant_id,P.display_tag,P.display_tag_end_date,DT.display_tag as display_tag_title,DT.status as tag_status,
			(SELECT selling_price FROM ".VENDOR_PRODUCTS_TBL." WHERE product_id=P.id AND PV.id='1'  ORDER BY id ASC LIMIT 1) as product_vendor_price,
			(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image,
			(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id DESC LIMIT 1) as secondary_img,
			(SELECT selling_price FROM ".VENDOR_PRODUCTS_TBL." WHERE product_id=P.id  AND stock >= min_qty AND status='1' AND vendor_id IN (" .$vendor_ids .") AND (variant_id='0' OR variant_id='1')  ORDER BY selling_price ASC LIMIT 1) as vendor_selling_price 
		FROM ".PRODUCT_TBL." P 
				LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id)
				LEFT JOIN ".REVIEW_TBL." RT ON(RT.product_id=P.id) 
				LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id)   
				LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=SC.category_id) 
				LEFT JOIN ".PRODUCT_VARIANTS." PV ON (P.id=PV.product_id ) 
				LEFT JOIN ".PRODUCT_DISPLAY_TAG." DT ON (P.display_tag=DT.id)  
				LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.user_id='".@$_SESSION['user_session_id']."') 
		WHERE P.sub_category_id='".$cat_id."' AND P.delete_status='0' AND P.is_draft='0' AND P.status='1' AND SC.status='1' AND C.status='1' " ;

		$q .= $this->filterQuery($filter_array,$cat_id);

		// Sort By function
	    if ($sort_by=='lowtohigh') {
	        $q .= "GROUP BY P.id ORDER BY CAST(P.selling_price AS DECIMAL(10,2)) ASC";
	    }elseif($sort_by=='hightolow'){
	    	$q .= "GROUP BY P.id ORDER BY CAST(P.selling_price AS DECIMAL(10,2)) DESC";
	  	}elseif($sort_by=='asc'){
	      	$q .= "GROUP BY P.id ORDER BY P.product_name ASC";
	  	}elseif($sort_by=='desc'){
	      	$q .= "GROUP BY P.id ORDER BY P.product_name DESC";
	  	} else {
	  		$q .= "GROUP BY P.id ORDER BY P.product_name ASC";
	  	}

    	if($page_amount!="show_all") {
    		$start_from = ($page-1)*$page_amount;
  			$page_count = $page_amount;
       		$q .= "  LIMIT $start_from, $page_count";
    	} else {
    		$start_from = 0;
    	}


    	//echo $q;
        $q_t_p = $this->exeQuery($q);
        $total_products = mysqli_num_rows($q_t_p);

        //echo $q;
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	 		    	$i=1;
	 		    	while($row = mysqli_fetch_array($exe))  {

	 		    		$list     = $this->editPagePublish($row);

	 		    		$vr_price = (($list['vendor_selling_price']!="")? $list['vendor_selling_price'] : $list['selling_price']  );

 						if ($vr_price >= $from && $vr_price <= $to) {
 							
 							$wishlist_text 	 = (($list['fav_status']=="") ? "Add to wishlist" : "Remove from wishlist");
							$status 		 = (($list['fav_status']!="") ? "favourite_item" : "");
	    					

	    					// Get Product Price

				            $variant    = "";
				            $variant_id = "";

				            if($list['has_variants']==1) {
								$variant  = $this->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$list['id']."'  ORDER BY id ASC LIMIT 1 ");
								$variant_id = $variant['id'];
							}

							$product_price = $this->getProductPrice($list['id'],$variant);

							if(isset($product_price['vendor_id']))
							{
								$vendor_id = $product_price['vendor_id'];
							} else {
								$vendor_id = "Sapiens";
							}

							if(isset($_SESSION['user_session_id'])) {
								$wishlist = "<li class='wishlist '><a href='".BASEPATH."login' class='addToWishList $status'   data-option='".$this->encryptData($list['id'])."' data-id='".$this->encryptData($list['id'])."' data-vendor_id='".$vendor_id."' data-variant_id='".$variant_id."' title='".$wishlist_text."'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";

							}else{
								$wishlist = "<li class='wishlist'><a href='".BASEPATH."login' title='Login to add Wishlist'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";
							}


				            $cartInfo = $this->cartInfo();
				            $cart_products = $cartInfo['cart_product_ids'];

				            if(in_array($list['id'], $cart_products)) {
				            	$add_to_cart ="Already in cart";
				            } else {
				            	$add_to_cart ="Add to Cart";
				            }

				            if($list['display_tag']!=0 && $list['display_tag_end_date'] && $list['tag_status']==1) {
								$today    = date("Y-m-d");
								$end_date = date("Y-m-d",strtotime($list['display_tag_end_date']));
								if($end_date >= $today) {
									$display_tag = "<div class='label_product display_tag'>
											".$list['display_tag_title']."
										</div>";
								} else {
									$display_tag = "";
								}
							} else {
								$display_tag = "";
							}

				            $product_image    = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;

				            $secondary_image   = $list['secondary_img']!='' ? SRCIMG.$list['secondary_img'] : ASSETS_PATH."no_img.jpg" ;

			            	$product_category = $list['category_type']=="main" ? "<a href='".BASEPATH."product/category/".$list['cat_url']."'>".$list['category']."</a>" : "<a href='".BASEPATH."product/subcategory/".$list['sub_cat_url']."'>".$list['subcategory']."</a>" ;
		 		    		$layout .= "
		 		    		 	<div class='col-lg-4 col-xl-3 col-md-6 col-12'>
		                            <div class='single_product single_product_list_width_hight' >
		                                <div class='product_name grid_name'>
		                                    <h3><a href='".BASEPATH."product/details/".$list['page_url']."' title='".$list['product_name']."'>".$list['product_name']."</a></h3>
		                                    <p class='manufacture_product'>$product_category</p>
		                                </div>
		                                <div class='product_thumb'>
		                                    <a class='primary_img product_wish_list_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$product_image."' alt='".$list['product_name']."' title='".$list['product_name']."'></a>
		                                	<a class='secondary_img product_wish_list_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$secondary_image."' alt='".$list['product_name']."' title='".$list['product_name']."'></a>
		                                    ".$display_tag."
		                                </div>	                                    
										<div class='action_links'>
											<ul>
												".$wishlist."
											</ul>
										</div>
		                                <div class='product_content grid_content'>
		                                    <div class='content_inner'>
		                                        <div class='product_footer d-flex align-items-center'>
		                                            <div class='price_box'>
		                                                <span class='current_price'>Rs.".number_format((($list['vendor_selling_price'])? $list['vendor_selling_price'] : $list['selling_price']))."</span>
														<span class='old_price'>Rs.".number_format($list['actual_price'])."</span>
		                                            </div>
		                                            <div class='add_to_cart'>
		                                                <a href='".BASEPATH."product/details/".$list['page_url']."' class='addToCart_pending' data-quantity='1'   data-option='".$this->encryptData($list['id'])."' title='".$add_to_cart."'><span class='lnr lnr-cart'></span></a>
		                                            </div>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>";
		                    $i++;
 						}
			    	}
	 		    } else {
					$layout = "<div class='cart_content '>No Records Found !!</div>";
				}
	 	$result = array();
		$result['layout'] 	  = $layout;
		$result['start_from'] = (mysqli_num_rows($exe)!=0)? $start_from + 1 : 0;
		$result['start_to']   = $start_from + mysqli_num_rows($exe);
		return $result;
	}

	// Sub Category 

	function subCategorySortingFilters($filter_url,$current_page)
	{

		$filter_array 	= $this->convertUrlToArray($filter_url,$current_page);
		$selected = $filter_array['sortby'];
		unset($filter_array['url']);
		unset($filter_array['sortby']);

		$url = $this->build_http_query($filter_array);
		$url_starting = count($filter_array)>0 ? "?".$url."&" : "?";

		$enable_selected = $selected=='asc' ? "selected" : "";

		$layout = '<option value="'.$url_starting.'sortby=asc" '.($selected=='asc' ? "selected" : "").' >Product Name: A - Z </option>
                   <option value="'.$url_starting.'sortby=desc" '.($selected=='desc' ? "selected" : "").' >Product Name: Z - A</a></option>
                   <option value="'.$url_starting.'sortby=lowtohigh" '.($selected=='lowtohigh' ? "selected" : "").'>Sort by price: Low to High</option>
                   <option value="'.$url_starting.'sortby=hightolow" '.($selected=='hightolow' ? "selected" : "").' >Sort by price: High to Low</option>';

        return $layout;
	}

	// Product Image List

	function getProductImageList($product_id)
	{
		$layout = "";
		$q = "SELECT file_name as product_image FROM ".MEDIA_TBL." WHERE item_id='".$product_id."' AND item_type='product' AND delete_status='0'";
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
    	$i=1;
	    	while($row = mysqli_fetch_array($exe)){
	    		$list  = $this->editPagePublish($row);
	    		$product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
				$product_name = $list['file_name']!='' ? SRCIMG.$list['file_name'] : "product image" ;
				
				// Add active class to first thumbnail
				$active_class = ($i == 1) ? ' active' : '';
				
	    		$layout .= "
							<div class='thumbnail".$active_class."' data-image='".$product_image."'>
								<img src='".$product_image."' alt='".$product_name."'>
							</div>
							";
	        	$i++;
			}
	 	}
	 	return $layout;
	}

	//Product Attribute List

	function getProductAttributelist($product_id)
	{
		
		$layout = "";
		$q = "SELECT A.attribute_name,A.attr_desc,A.attribute_group_id,G.attribute_group  FROM ".PRODUCT_ATTRIBUTES." A LEFT JOIN ".ATTRIBUTE_GROUP_TBL." G ON(G.id=A.attribute_group_id) WHERE A.product_id='".$product_id."' AND A.status='1' AND G.delete_status='0' AND G.status='1' GROUP BY A.attribute_group_id" ;
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
    	$i=1;
	    	while($row = mysqli_fetch_array($exe)){
	    		$list  = $this->editPagePublish($row);
	    		$layout .= "
	    					<h4 class='attribute_heading'>".ucwords($list['attribute_group'])."</h4>
	    					<table class='table table-bordered'>
		    				   	<tbody>
									".$this->getProductSubAttributelist($list['attribute_group_id'],$product_id)."
								</tbody>
							</table>";
	        	$i++;
			}
	 	}
	 	return $layout;
	}

	//Product Sub Attribute List

	function getProductSubAttributelist($attribute_group_id="",$product_id)
	{
		$layout = "";
		$q = "SELECT attribute_name,attr_desc,attribute_group_id FROM ".PRODUCT_ATTRIBUTES." WHERE product_id='".$product_id."' AND attribute_group_id='".$attribute_group_id."' AND status='1' " ;
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
    	$i=1;
	    	while($row = mysqli_fetch_array($exe)){
	    		$list  = $this->editPagePublish($row);
	    		$layout .= "
		    				   	<tr>
								    <td class='first_child'>".ucwords($list['attribute_name'])."</td>
								    <td>".ucwords($list['attr_desc'])."</td>
								</tr>
							";
	        	$i++;
			}
	 	}
	 	return $layout;
	}

	function getSubCategoryList($category="")
	{
		$layout = "";

		$q = "SELECT id FROM ".SUB_CATEGORY_TBL." WHERE category_id='".$category."' AND delete_status='0' AND status='1' AND is_draft='0' ";
		$exe = $this->exeQuery($q);
		$ids = array();
		while($list = mysqli_fetch_array($exe)){
		$ids[] =	$list['id'];
		}
		$get_related_products = "";
		
		return $ids;
	}


	// Main Category List For shop header
	function manageShopCategorylist()
	{
		$layout = "";
		$q = "SELECT * FROM ".MAIN_CATEGORY_TBL."  WHERE 1 AND is_draft='0' AND delete_status='0' ORDER BY category ASC";
		$exe = $this->exeQuery($q);
	 		if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($row = mysqli_fetch_array($exe)){
		    		$list  = $this->editPagePublish($row);
					$sub_categories         = $this->getSubCategoryList($list['id']);
					$category_products      = $this->check_query(PRODUCT_TBL,"*"," main_category_id='".$list['id']."' AND category_type='main' AND delete_status='0' AND status='1' AND is_draft='0'  ");
					$sub_category_products  = $this->check_query(PRODUCT_TBL,"*"," sub_category_id IN (" . implode(',', array_map('intval',$sub_categories)). ") AND category_type='sub' AND delete_status='0' AND status='1' AND is_draft='0'   ");

					if($category_products || $sub_category_products ) {
						$layout .= "
                                <a href='".BASEPATH."product/category/".$list['page_url']."' class='btn filter-categories category_list category_list_padding' data-id='".$list['id']."' >".$list['category']."</a>
	 		    		";
					}
		    	}
 		    }
	 	return $layout;
	}

	function manageShopSubCategorylist($category_id,$current="")
	{
		$layout = "";
		$q = "SELECT * FROM ".SUB_CATEGORY_TBL."  WHERE category_id='".$category_id."' AND is_draft='0' AND delete_status='0' ORDER BY subcategory ASC";
		$exe = $this->exeQuery($q);
	 		if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($row = mysqli_fetch_array($exe)){
    				$list  = $this->editPagePublish($row);
					$sub_category_products      = $this->check_query(PRODUCT_TBL,"*"," sub_category_id='".$list['id']."' AND category_type='sub' AND delete_status='0' AND status='1' AND is_draft='0'  ");
					$active = (($list['id']==$current)? "active" : "" );
					if ($sub_category_products) {
						$layout .= "
								<a href='".BASEPATH."product/subcategory/".$list['page_url']."' class='btn filter-categories category_list category_list_padding $active' data-id='".$list['id']."' >".$list['subcategory']."</a>
 		    		 			
 		    			";
					}
		    	}
 		    }
	 	return $layout;
	}

	// Main Category List For filter bar
	function manageCategorylist()
	{
		$layout = "";
		$q = "SELECT * FROM ".MAIN_CATEGORY_TBL."  WHERE 1 AND is_draft='0' AND delete_status='0' ORDER BY category ASC";
		$exe = $this->exeQuery($q);
	 		if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($row = mysqli_fetch_array($exe)){
		    		$list  = $this->editPagePublish($row);
					$sub_categories         = $this->getSubCategoryList($list['id']);
					$category_products      = $this->check_query(PRODUCT_TBL,"*"," main_category_id='".$list['id']."' AND category_type='main' AND delete_status='0' AND status='1' AND is_draft='0'  ");
					$sub_category_products  = $this->check_query(PRODUCT_TBL,"*"," sub_category_id IN (" . implode(',', array_map('intval',$sub_categories)). ") AND category_type='sub' AND delete_status='0' AND status='1' AND is_draft='0'   ");

					if($category_products || $sub_category_products ) {
						$layout .= "
									<label class='filter-option'>
										<span><a href='".BASEPATH."product/category/".$list['page_url']."' data-id='".$list['id']."'>".$list['category']."</a></span>
									</label>
	 		    		";
					}
		    	}
 		    }
	 	return $layout;
	}


	function manageSubCategorylist($category_id)
	{
		$layout = "";
		$q = "SELECT * FROM ".SUB_CATEGORY_TBL."  WHERE category_id='".$category_id."' AND is_draft='0' AND delete_status='0' ORDER BY subcategory ASC";
		$exe = $this->exeQuery($q);
	 		if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($row = mysqli_fetch_array($exe)){
    				$list  = $this->editPagePublish($row);
					$sub_category_products      = $this->check_query(PRODUCT_TBL,"*"," sub_category_id='".$list['id']."' AND category_type='sub' AND delete_status='0' AND status='1' AND is_draft='0'  ");
					if ($sub_category_products) {
						$layout .= "
 		    		 			<li class='subcat_list_$category_id subcat_list_sc  ' data-option='".$category_id."'>
                                    <a href='".BASEPATH."product/subcategory/".$list['page_url']."' >".$list['subcategory']."</a>
                                </li>
 		    			";
					}
		    	}
 		    }
	 	return $layout;
	}
	//Brand List For filter bar
	function manageBrandlist()
	{
		$layout = "";
		$q = "SELECT * FROM ".BRAND_TBL."  WHERE 1 AND is_draft='0' AND delete_status='0' ORDER BY brand_name ASC";
		$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	 		    	$i=1;
	 		    	while($row = mysqli_fetch_array($exe)){
	    				$list  = $this->editPagePublish($row);
	    				$is_empty  = $this->getDetails(PRODUCT_TBL,'id'," brand_id='".$list['id']."' AND delete_status='0' AND status='1' AND is_draft='0'  ");
	 		    		if($is_empty) {

	 		    			if(isset($_GET['brands'])) {
								$selected_ids = explode(",", $_GET['brands']);
								$checked     = (in_array($list['id'], $selected_ids))? "checked" : "" ;
							} else {
								$checked = "";
							}

	 		    			$layout .= "
	 		    		 			<div class='brand_lists'>
			 		    			<input type='checkbox' id='brand_id_".$list['id']."' data-option='".$list['id']."' class='brand_filter_".$list['id']." brand_list_checkbox brand_filter' name='brand_ids[]' value='".$list['id']."'  $checked>
									 <label class='brand_list_label' for='brand_id_".$list['id']."'>".$list['brand_name']."</label>									 
			 		    			</div>
	 		    			";
	 		    		}
			    	}
	 		    }
	 	return $layout;

	}

	/*----------------------------------------------
				Manage Page Banner
	----------------------------------------------*/

	function getPageBanner($token)
  	{
  		$layout = "";
		$q = "SELECT *  FROM ".PAGE_BANNER_TBL." WHERE page_token='".$token."' AND delete_status='0' AND status='1' AND is_draft='0' " ;
	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
	    		$list 	   = $this->editPagePublish($details);
	    		$pic 	   = $list['file_name']=="" ? ASSETS_PATH."file_upload.jpg" : SRCIMG.$list['file_name'];


	    		if($list['button_type']=="product") 
	    		{	
	    			$product_info    = $this->getDetails(PRODUCT_TBL,"*","id='".$list['button_link']."'");  
					$banner_category = $product_info['product_name'];
					$btn_link        = BASEPATH."product/details/".$product_info['page_url'];
	    		} elseif ($list['button_type']=="main_category") {
					$category_info    = $this->getDetails(MAIN_CATEGORY_TBL,"*","id='".$list['button_link']."'");  
					$banner_category  = $category_info['category'];
					$btn_link        = BASEPATH."product/category/".$category_info['page_url'];
	    		} else {
					$sub_category_info = $this->getDetails(SUB_CATEGORY_TBL,"*","id='".$list['button_link']."'");  
					$banner_category   = $sub_category_info['subcategory'];
					$btn_link        = BASEPATH."product/subcategory/".$sub_category_info['page_url'];
	    		}


	    		$layout .= "
					<div style='background-image: url(".$pic.");' class='offer_banner_widh_hight'>
					<div class='overlay_banner_last'></div>
					<div class='banner_text'>
						<h2>".$list['title']."</h2>
						<p>".$list['message']."</p>
						<a class='rounded-pill' href='".$btn_link."'>".$list['button_name']."</a>
					</div></div>";
	    		$i++;
	    	}
	    }
	    return $layout;
  	}

	

	//<a href='".BASEPATH."product/brand/".$list['page_url']."' class='category_list_padding'></a>

	function getBrandIdsFroCat($list_for="",$id="")
	{	
		$ids = array();

		if($list_for=="main") {

			$sub_category_ids = $this->getSubCategoryList($id); 

			if(count($sub_category_ids) > 0) {
				$get_sub_cat = "OR sub_category_id IN (".implode(",", $sub_category_ids).")";
			} else {
				$get_sub_cat = "";
			}

			$condition = "AND main_category_id='".$id."' $get_sub_cat  ";			
		} else {
			$condition = "AND category_type='".$list_for."' AND sub_category_id='".$id."' ";
		}

		$query = "SELECT brand_id FROM ".PRODUCT_TBL." WHERE brand_id!='0' AND is_draft='0' AND status='1' AND delete_status='0' ".$condition."  ";
		$exe   = $this->exeQuery($query);
		if(mysqli_num_rows($exe) > 0)
		{
			while ($list = mysqli_fetch_assoc($exe)) {
				$ids[] = $list['brand_id'];
			}
		}

		if(count($ids)) {
			return implode(",", $ids);
		} else {
			return "";
		}
	}

	// Releated Products

	function getReleatedproductsList($current="")
	{
		$layout = "";

		$q = "SELECT related_item_id FROM ".RELATED_PRODUCTS." WHERE product_id='".$current."' AND type='related' ";
		$exe = $this->exeQuery($q);
		$ids = array();
		while($list = mysqli_fetch_array($exe)){
		$ids[] =	$list['related_item_id'];
		}
		if(count($ids)==0) 
		{
			$ids[] = 0 ;
		}
		
	    $get_related_products = "AND P.id IN (" . implode(',', array_map('intval',$ids)). ")";

		$q = "SELECT P.id,P.page_url,P.has_variants,P.sku,P.product_name,P.category_type,P.main_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,T.tax_class as taxClass ,C.category,C.page_url as cat_url ,W.fav_status,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url ,  P.display_tag,P.display_tag_end_date,DT.display_tag as display_tag_title,DT.status as tag_status,
			(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image  
			FROM ".PRODUCT_TBL." P LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id) 
								   LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id)  
								   LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=SC.category_id)   
								   LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.user_id='".@$_SESSION['user_session_id']."') 
								   LEFT JOIN ".PRODUCT_DISPLAY_TAG." DT ON (P.display_tag=DT.id) 
			WHERE 1 AND P.is_draft='0' AND P.delete_status='0' $get_related_products   AND P.status='1' AND SC.status='1' AND C.status='1' GROUP BY P.id DESC " ;
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	 		    	$i=1;
	 		    	while($row = mysqli_fetch_array($exe)){
	    				$list  = $this->editPagePublish($row);
			            $wishlist_text 	 = (($list['fav_status']=="") ? "Add to wishlist" : "Remove from wishlist");
						$status 		 = (($list['fav_status']!="") ? "favourite_item" : "");

	 		    		// Get Product Price
						$variant    = "";
			            $variant_id = "";

			            if($list['has_variants']==1) {
							$variant  = $this->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$list['id']."'  ORDER BY id ASC LIMIT 1 ");
							$variant_id = $variant['id'];
						}

						$product_price = $this->getProductPrice($list['id'],$variant);


						if(isset($_SESSION['user_session_id'])) {
							$wishlist = "<li class='wishlist '><a href='".BASEPATH."login' class='addToWishList $status'   data-option='".$this->encryptData($list['id'])."' data-id='".$this->encryptData($list['id'])."' data-variant_id='".$variant_id."' title='".$wishlist_text."'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";

						}else{
							$wishlist = "<li class='wishlist'><a href='".BASEPATH."login' title='Login to add Wishlist'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";
						}

			            $cartInfo = $this->cartInfo();
			            $cart_products = $cartInfo['cart_product_ids'];

			            if(in_array($list['id'], $cart_products)) {
			            	$add_to_cart ="Already in cart";
			            } else {
			            	$add_to_cart ="Add to Cart";
			            }

			            if($list['display_tag']!=0 && $list['display_tag_end_date'] && $list['tag_status']==1) {
							$today    = date("Y-m-d");
							$end_date = date("Y-m-d",strtotime($list['display_tag_end_date']));
							if($end_date >= $today) {
								$display_tag = "<span class='product-badge'>".$list['display_tag_title']."</span>";
							} else {
								$display_tag = "";
							}
						} else {
							$display_tag = "";
						}


			            $product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;

		            	$product_category = $list['category_type']=="main" ? "<a href='".BASEPATH."product/category/".$list['cat_url']."'>".$list['category']."</a>" : "<a href='".BASEPATH."product/subcategory/".$list['sub_cat_url']."'>".$list['subcategory']."</a>" ;
	 		    		
                        $layout .= "<div class='unique-product-card'>
										<a href='".BASEPATH."product/details/".$list['page_url']."'>
											<div class='unique-product-image'>
												<img src='".$product_image."' alt='".$list['product_name']."'>
												".$display_tag."
												<div class='unique-product-actions'>
													<button class='unique-action-btn'>
													<i class='fas fa-heart'></i>
													</button>
													<button class='unique-action-btn'>
													<i class='fas fa-eye'></i>
													</button>
												</div>
											</div>
											<div class='unique-product-info'>
												<h3 class='unique-product-title-text'>".$list['product_name']."</h3>
												<div class='unique-product-price'>
													<span class='unique-current-price'>Rs.".$this->inrFormat($product_price['selling_price'])."</span>
													<span class='unique-original-price'>Rs.".$this->inrFormat($list['actual_price'])."</span>
												</div>
											</div>
										</a>
									</div>";

	                    $i++;
			    	}
	 		    }
	 	return $layout;
		
	}

	// Bought Together Products

	function getBoughtTogetherProductsList($current="")
	{
		$layout = "";

		$q = "SELECT related_item_id FROM ".RELATED_PRODUCTS." WHERE product_id='".$current."' AND type='bought_together' ";
		$exe = $this->exeQuery($q);
		$ids = array();
		while($list = mysqli_fetch_array($exe)){
		$ids[] =	$list['related_item_id'];
		}
		if(count($ids)==0) 
		{
			$ids[] = 0 ;
		}
	    
	    $get_bought_products = "AND P.id IN (" . implode(',', array_map('intval',$ids)). ")";

		$q="SELECT P.id,P.page_url,P.has_variants,P.sku,P.product_name,P.category_type,P.main_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,T.tax_class as taxClass ,C.category,C.page_url as cat_url ,W.fav_status,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url,P.display_tag,P.display_tag_end_date,DT.display_tag as display_tag_title,DT.status as tag_status,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image  
			FROM ".PRODUCT_TBL." P LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id) 
								   LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id)  
								   LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=SC.category_id)   
								   LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.user_id='".@$_SESSION['user_session_id']."') 
								   LEFT JOIN ".PRODUCT_DISPLAY_TAG." DT ON (P.display_tag=DT.id) 
			WHERE 1 AND P.is_draft='0' AND P.delete_status='0'   $get_bought_products  AND P.status='1' AND SC.status='1' AND C.status='1' GROUP BY P.id DESC " ;

	 	$exe= $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	 		    	$i=1;
	 		    	while($row = mysqli_fetch_array($exe)){
	 		    		
	    				$list  = $this->editPagePublish($row);

	    				$wishlist_text 	 = (($list['fav_status']=="") ? "Add to wishlist" : "Remove from wishlist");
						$status 		 = (($list['fav_status']!="") ? "favourite_item" : "");

	 		    		// Get Product Price
						$variant    = "";
			            $variant_id = "";

			            if($list['has_variants']==1) {
							$variant  = $this->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$list['id']."'  ORDER BY id ASC LIMIT 1 ");
							$variant_id = $variant['id'];
						}

						$product_price = $this->getProductPrice($list['id'],$variant);

						if(isset($product_price['vendor_id']))
						{
							$vendor_id = $product_price['vendor_id'];
						} else {
							$vendor_id = "Sapiens";
						}

						if(isset($_SESSION['user_session_id'])) {
							$wishlist = "<li class='wishlist '><a href='".BASEPATH."login' class='addToWishList $status'   data-option='".$this->encryptData($list['id'])."' data-id='".$this->encryptData($list['id'])."' data-vendor_id='".$vendor_id."' data-variant_id='".$variant_id."' title='".$wishlist_text."'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";

						}else{
							$wishlist = "<li class='wishlist'><a href='".BASEPATH."login' title='Login to add Wishlist'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";
						}

			            $cartInfo = $this->cartInfo();
			            $cart_products = $cartInfo['cart_product_ids'];

			            if(in_array($list['id'], $cart_products)) {
			            	$add_to_cart ="Already in cart";
			            } else {
			            	$add_to_cart ="Add to Cart";
			            }

			            if($list['display_tag']!=0 && $list['display_tag_end_date'] && $list['tag_status']==1) {
							$today    = date("Y-m-d");
							$end_date = date("Y-m-d",strtotime($list['display_tag_end_date']));
							if($end_date >= $today) {
								$display_tag = "<div class='label_product display_tag'>
										".$list['display_tag_title']."
									</div>";
							} else {
								$display_tag = "";
							}
						} else {
							$display_tag = "";
						}


			            $product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
		            	$product_category = $list['category_type']=="main" ? "<a href='".BASEPATH."product/category/".$list['cat_url']."'>".$list['category']."</a>" : "<a href='".BASEPATH."product/subcategory/".$list['sub_cat_url']."'>".$list['subcategory']."</a>" ;
	 		    		$layout .= "
	 		    		 	<div class='single_product_list'> ";
	                            $layout .= "<div class='single_product single_product_list_width_hight' >
	                                <div class='product_name grid_name'>
	                                    <h3><a href='".BASEPATH."product/details/".$list['page_url']."' title='".$list['product_name']."'>".$list['product_name']."</a></h3>
	                                    <p class='manufacture_product'>$product_category</p>
	                                </div>
	                                <div class='product_thumb'>
	                                    <a class='primary_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$product_image."' alt='".$list['product_name']."' class='our_product_lis_img_size' title='".$list['product_name']."'></a>	                                   	                                
	                                    <a class='secondary_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$product_image."' alt='".$list['product_name']."' class='our_product_lis_img_size' title='".$list['product_name']."'></a>
	                                    ".$display_tag."	                                   	                                
	                                </div>	                                    
									<div class='action_links'>
										<ul>
											".$wishlist."
										</ul>
									</div>
	                                <div class='product_content grid_content'>
	                                    <div class='content_inner'>
	                                        <div class='product_footer d-flex align-items-center'>
	                                            <div class='price_box'>
	                                                <span class='current_price'>Rs.".number_format($product_price['selling_price'])."</span>
													<span class='old_price'>Rs.".number_format($list['actual_price'])."</span>
	                                            </div>
	                                            <div class='add_to_cart'>
	                                                <a href='".BASEPATH."product/details/".$list['page_url']."' class='addToCart_pending'   data-option='".$this->encryptData($list['id'])."' title='".$add_to_cart."'><span class='lnr lnr-cart'></span></a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>";
	                    $layout .= "</div>";
	                    $i++;
			    	}
	 		    }
	 	return $layout;
		
	}
	

	// Product Reviews

	function getProductReviews($product_id)
	{
		$result 	 = array();
		$user_review = "";
		$all_reveiws = "";
		$reviewed 	 = $this->check_query(REVIEW_TBL,"id"," product_id='".$product_id."'  AND added_by='".@$_SESSION['user_session_id']."'");
		$q 			 = "SELECT R.id,R.product_id,R.order_id,R.name,R.email,R.admin_replay,R.comment,R.star_ratings,R.replay_at,R.approval_status,R.del_status,R.status,R.added_by,R.created_at,R.updated_at,C.id as customer_id,C.name as cus_name  FROM ".REVIEW_TBL." R LEFT JOIN ".CUSTOMER_TBL." C ON(R.added_by=C.id)  WHERE R.product_id='".$product_id."' AND R.del_status='0' AND R.status='1' ORDER BY R.id='".@$_SESSION['user_session_id']."' DESC" ;
	 	$exe 		 = $this->exeQuery($q);

	 	$vendor_id 	 = "";

	 	$user_review_approval = 0;

	 	if(mysqli_num_rows($exe)>0) {
	 		$i=1;
		    while($row = mysqli_fetch_array($exe)) {
				$list  = $this->editPagePublish($row);

				if($list['added_by']==$_SESSION['user_session_id']) {
					$user_review_approval = 1;
				}

				$admin_replay = "
								<div class='comment_text admin_replay_msg_box'>
									<div class='reviews_meta '>
										<p><strong>Sapiens </strong>- ". date("F j, Y", strtotime($list['replay_at']))."</p>
										<span>".$list['admin_replay'] ."</span>
									</div>
								</div>";

				if($list['admin_replay']=="" || $list['admin_replay']==NULL) {
					$admin_replay = "";
				}

				if($_SESSION['user_session_id'] == $list['customer_id']) {
					$edit_btn = "<a type='button' data-review_id='".$this->encryptData($list['id'])."' class='btn btn-hero btn-sm rounded-pill px-3 float-end editProductReview'>Edit</a>";
				} else {
					$edit_btn = "";
				}

				if($list['added_by']==$_SESSION['user_session_id']) {

					$vendor_rating_info = $this->getVendorRatingsForReview($list['order_id'],$list['product_id'],$list['added_by']);
				} else {
					$vendor_rating_info = "";
				}

				if($list['added_by'] == $_SESSION['user_session_id']) {
					$reveiw_title = "<p><strong>Product Review</strong> ".$edit_btn."</p>";
				} else {
					$reveiw_title = "";
				}



				if($list['approval_status']==1) {

					$product_review_info    = "
									        ".$reveiw_title."    
											<div class='reviews_meta'>
									            <div class='star_rating'>
										           <span class='my-rating-7'></span>
										           <input type='hidden' class='rating_data' name='star_ratings' value='".$list['star_ratings']."' id='rating_data'>
										        </div>
									            <p><span>".$list['comment']."</span></p>
									            ".$admin_replay."
									        </div>";
				} else {
					$product_review_info = "";
				}

				if($product_review_info!="" || $vendor_rating_info!="") {

					if($list['added_by'] == $_SESSION['user_session_id']) {
						$user_review .= "
			    					<div class='reviews_comment_box col-lg-8'>
									    <div class='comment_thmb'>
									        <img src='".BASEPATH."lib/images/img/blog/comment2.jpg' alt=''>
									    </div>
									    <div class='comment_text'>
									    <p><strong>".$list['cus_name'] ."</strong> - ". date("F j, Y", strtotime($list['created_at']))."</p>
									    ".$product_review_info."					        
									    ".$vendor_rating_info."
									    </div>
									</div>";
					} else {

 		    			$all_reveiws .= "
			    					<div class='reviews_comment_box col-lg-8'>
									    <div class='comment_thmb'>
									        <img src='".BASEPATH."lib/images/img/blog/comment2.jpg' alt=''>
									    </div>
									    <div class='comment_text'>
									    <p><strong>".$list['cus_name'] ."</strong> - ". date("F j, Y", strtotime($list['created_at']))."</p>
									    ".$product_review_info."					        
									    ".$vendor_rating_info."
									    </div>
									</div>";
					}
                    $i++;
                }
	    	}
	 	}

	 	$all_reveiws .= $this->getVendorRatingsWithoutReview($product_id,$_SESSION['user_session_id']);

	 	$layout = $user_review . $all_reveiws;

	 	$result['layout']               = $layout;
	 	$result['review_rating_forms']  = $this->getReviewAndRattingForm($product_id);
	 	$result['review_count']         = $this->check_query(REVIEW_TBL,"id","approval_status='1' AND product_id='".$product_id."' " );

	 	return $result;
	}

	// Get Vendor Ratings fro review

	function getVendorRatingsForReview($order_id='',$product_id,$user_id)
	{
		$layout = "";
		$query  = "SELECT * FROM ".VENDOR_RATTING_TBL." WHERE order_id='".$order_id."' AND product_id='".$product_id."' AND  added_by='".$_SESSION['user_session_id']."'";
		$exe    = $this->exeQuery($query);

		if(mysqli_num_rows($exe) > 0) {


			if($_SESSION['user_session_id'] == $user_id) {

				$check_rated_info = $this->check_query(VENDOR_RATTING_TBL,"id"," product_id='".$product_id."' AND order_id ='".$order_id."' AND added_by='".@$_SESSION['user_session_id']."' ");

				if($check_rated_info) {
					$edit_vendor_btn = "<a type='button' data-order_id='".$this->encryptData($order_id)."'  data-product_id='".$this->encryptData($product_id)."' class='btn btn-hero btn-sm  rounded-pill px-3  editVendorRattingBtn float-end'>Edit Vendor Rating</a>";
				} else {
					$edit_vendor_btn = "";
				}

			} else {
				$edit_vendor_btn = "";
			}

			$layout .= "
				<div class='vendor_rating_section'>
		        	<p><strong>Vendor Ratings</strong>".$edit_vendor_btn."</p>
			        <div class='reviews_meta'>
				        <div class='row'>";

			while ($list 	  = mysqli_fetch_assoc($exe)) {
				$vendor_info  = $this->getDetails(VENDOR_TBL,"company","id='".$list['vendor_id']."'");
				$layout      .= "
								<div class='star_rating'>
					                ".$vendor_info['company']."
					                <span class='my-rating-7 float-end'></span>
									<input type='hidden' class='rating_data' name='star_ratings' value='".$list['star_ratings']."' id='rating_data'>
					            </div>";
			}

			$layout .= "	</div>
				    </div>
			     </div>";
		}

		

		return $layout;
	}


	// Get Vendor Ratings fro review

	function getVendorRatingsWithoutReview($product_id,$user_id)
	{	
		$layout      = "";

		$reveiw_info = $this->reviewAndRatingConditionCheck($product_id);

		foreach ($reveiw_info['review_pending_order_ids'] as $key => $value) {
			
			$query  = "SELECT * FROM ".VENDOR_RATTING_TBL." WHERE order_id='".$value."' AND product_id='".$product_id."' AND  added_by='".$_SESSION['user_session_id']."'";
			$exe    = $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0) {

				if($_SESSION['user_session_id'] == $user_id) {

					$check_rated_info = $this->check_query(VENDOR_RATTING_TBL,"id"," product_id='".$product_id."' AND order_id ='".$value."' AND added_by='".@$_SESSION['user_session_id']."' ");

					if($check_rated_info) {
						$edit_vendor_btn = "<a type='button' data-order_id='".$this->encryptData($value)."'  data-product_id='".$this->encryptData($product_id)."' class='btn btn-hero btn-sm  rounded-pill px-3  editVendorRattingBtn float-end'>Edit Vendor Rating</a>";
					} else {
						$edit_vendor_btn = "";
					}

				} else {
					$edit_vendor_btn = "";
				}

				$rating_info = $this->getDetails(VENDOR_RATTING_TBL,"created_at","order_id='".$value."' AND product_id='".$product_id."' AND  added_by='".$_SESSION['user_session_id']."'");
				$user_info   = $this->getDetails(CUSTOMER_TBL,"*","Id='".$_SESSION['user_session_id']."' ");

				$layout .= "

				<div class='reviews_comment_box col-lg-8'>
						    <div class='comment_thmb'>
						        <img src='".BASEPATH."lib/images/img/blog/comment2.jpg' alt=''>
						    </div>
						    <div class='comment_text'>
						    <p><strong>".$user_info['name']."</strong> - ". date("F j, Y", strtotime($rating_info['created_at']))."</p>

					<div class='vendor_rating_section'>
			        	<p><strong>Vendor Ratings</strong>".$edit_vendor_btn."</p>
				        <div class='reviews_meta'>
					        <div class='row'>";

				while ($list 	  = mysqli_fetch_assoc($exe)) {
					$vendor_info  = $this->getDetails(VENDOR_TBL,"company","id='".$list['vendor_id']."'");
					$layout      .= "
									<div class='star_rating vendor_star_rating'>
						                <span class='my-rating-7'>  ".$vendor_info['company']."</span>
										<input type='hidden' class='rating_data' name='star_ratings' value='".$list['star_ratings']."' id='rating_data'>
						            </div>";
				}

				$layout .= "	</div>
					    </div>
				     </div>
				     </div>
						</div>";
			}

		}

		

		return $layout;
	}

	// reveiew conditions rework 

	function reviewAndRatingConditionCheck($product_id)
	{
		
		$order_ids = array();
		$user_id   = @$_SESSION['user_session_id'];
		$q         = "SELECT order_id FROM ".ORDER_ITEM_TBL." WHERE product_id='".$product_id."' AND user_id='".$user_id."' AND order_status='2' ";
		$exe 	   = $this->exeQuery($q);
 
		if(mysqli_num_rows($exe) > 0) {
			while($list = mysqli_fetch_assoc($exe)){
				$order_ids[] = $list['order_id'];
			}
		}

		$order_ids_unique = array_unique($order_ids);

		if(count($order_ids_unique) > 0) {
			$reveiewd_ids = array();
			$q 		= "SELECT order_id FROM ".REVIEW_TBL." WHERE product_id='".$product_id."' AND added_by='".$user_id."'  ";
			$exe 	= $this->exeQuery($q);
 
			if(mysqli_num_rows($exe) > 0) {
				while($list = mysqli_fetch_assoc($exe)){
					$reveiewd_ids[] = $list['order_id'];
				}
			}

			$review_pending_order_ids = array_diff($order_ids_unique, $reveiewd_ids);
		} else {
			$review_pending_order_ids = array();
		}

		if(count($order_ids_unique) > 0) {
			$rated_ids = array();
			$query     = "SELECT order_id FROM ".VENDOR_RATTING_TBL." WHERE product_id='".$product_id."' AND added_by='".$user_id."'  ";
			$exe 	   = $this->exeQuery($query);
 
			if(mysqli_num_rows($exe) > 0) {
				while($list = mysqli_fetch_assoc($exe)){
					$rated_ids[] = $list['order_id'];
				}
			}

			$rating_pending_order_ids = array_diff($order_ids_unique, $rated_ids);
		} else {
			$rating_pending_order_ids = array();
		}


		// Deliverd product vendors 


		if (count($rating_pending_order_ids) > 0) {
			
			$query ="SELECT order_id,vendor_id from ".ORDER_ITEM_TBL." WHERE order_id IN (".implode(',', array_map('intval',$rating_pending_order_ids)).") AND product_id='".$product_id."' AND order_status='2'  ";
			$exe = $this->exeQuery($query);
			$vendor_ids = array();
			while($list = mysqli_fetch_array($exe)){
				$vendor_ids[$list['order_id']][]  = $list['vendor_id'];
			}

		} else {
			$vendor_ids = array();
		}
		

		$result = array();
		$result['review_pending_order_ids']  = array_values($review_pending_order_ids);
		$result['rating_pending_order_ids']  = array_values($rating_pending_order_ids);
		$result['rating_pending_venodr_ids'] = $vendor_ids;

		return $result;

	}

	// Product Review & Ratting Froms

	function getReviewAndRattingForm($product_id)
	{		
		$layout      = "";
		$review_info = $this->reviewAndRatingConditionCheck($product_id);

		if(@$_SESSION['user_session_id']) {

			if(count($review_info['rating_pending_order_ids'])) {
				$order_id    		= $review_info['rating_pending_order_ids'][0];
				$order_info  		= $this->getDetails(ORDER_TBL,"*","id='".$order_id."'");
				
				$vendor_rating_form = "<div class='col-lg-6'>
											<form action='#' id='addProductReview'>
			                                    <div class='comment_title vendor_rating_title'>
			                                        <h4>Add Vendor Ratings for order ".$order_info['order_uid']." </h4>
			                                    </div>
			                            		<div class='product_review_form'>
			                                        <button type='button'data-order_id='".$this->encryptData($order_id)."' data-product_id='".$this->encryptData($product_id)."' class='rounded-pill addVendorRattingBtn mt-0 ps-3 pe-3'>Add Rating</button>
			                                    </div>
			                             	</form>
		                             	</div>";
			} else {

				$query 			 = "SELECT * FROM ".VENDOR_RATTING_TBL." WHERE product_id='".$product_id."' AND added_by='".@$_SESSION['user_session_id']."' ";
				$exe   			 = $this->exeQuery($query);
				
				if(mysqli_num_rows($exe) > 0) {
					while ($list = mysqli_fetch_assoc($exe)) {
						
						$vendor_info  = $this->getDetails(VENDOR_TBL,"company","id='".$list['vendor_id']."'");

						if($_SESSION['user_session_id'] == $list['added_by']) {
							$edit_btn = "<a type='button' data-vendor_id='".$this->encryptData($list['vendor_id'])."' data-product_id='".$this->encryptData($list['product_id'])."' class='btn btn-hero btn-sm float-end editVendorRating'>Edit</a>";
						} else {
							$edit_btn = "";
						}
					}
				}
			}


			if(count($review_info['review_pending_order_ids'])) {
				$order_id    = $review_info['review_pending_order_ids'][0];
				$order_info  = $this->getDetails(ORDER_TBL,"*","id='".$order_id."'");
				$review_form = "<div class='col-lg-6'>
									<form action='#' id='addProductReview'>
	                                    <input type='hidden' name='add_review_product_id'  value='".$this->encryptData($product_id)."'>
										<input type='hidden' name='add_review_order_id' value='".$this->encryptData($order_id)."'>
	                                    <div class='comment_title'>
	                                        <h4>Add a product review for order ".$order_info['order_uid']." </h4>
	                                        <p>Your email address will not be published. Required fields are marked </p>
	                                    </div>
	                                    <div class='product_rating'>
	                                        <h4>Your rating</h4>
	                                        <span class='star-rating_add_review'></span>
	                                        <input type='hidden' name='star_ratings' value='5' id='rating_input'>
	                                    </div>
	                                    <div class='product_review_form'>
	                                        <div class='row'>
	                                            <div class='col-12 your_commend'>
	                                                <label for='review_comment'>Your comment </label>
	                                                <textarea name='comment' name='comment' placeholder='Your comment' id='review_comment'></textarea>
	                                            </div>
	                                        </div>
	                                        <button type='submit' class='rounded-pill'>Submit</button>
	                                    </div>
	                                </form>
	                            </div>";
			}  else {
				$review_form = "";
			}

			$layout .= "<div class='row'>
                            ".$review_form."
                            ".$vendor_rating_form."
                		</div>";

			if($review_form == "" && $vendor_rating_form == "") {
					$layout = "";					
			}
 				
		}

		return $layout;
	} 

	// Not in use

	// function getProductReviewsCount($product_id)
	// {
	// 	$layout = "";
	// 	// $q = "SELECT R.id,R.product_id,R.name,R.email,R.comment, sum(R.star_ratings)as rating_count, count(R.star_ratings)as rating_tot, R.star_ratings,R.approval_status,R.del_status,R.status,R.added_by,R.created_at,R.updated_at,C.name as cus_name  FROM ".REVIEW_TBL." R LEFT JOIN ".CUSTOMER_TBL." C ON(R.added_by=C.id)  WHERE R.product_id='".$product_id."' AND R.approval_status='1' AND R.del_status='0' AND R.status='1' ORDER BY R.id DESC " ;

	// 	echo $q = "SELECT R.id,R.product_id,R.name,R.email,R.comment, sum(R.star_ratings)as rating_count, count(R.star_ratings)as rating_tot, R.star_ratings,R.approval_status,R.del_status,R.status,R.added_by,R.created_at,R.updated_at, 
	// 		(SELECT COUNT(star_ratings) as rating FROM ".REVIEW_TBL." WHERE star_ratings = 1) as onestarcount,
	// 		(SELECT COUNT(star_ratings) as rating FROM ".REVIEW_TBL." WHERE star_ratings = 2) as twostarcount,
	// 		(SELECT COUNT(star_ratings) as rating FROM ".REVIEW_TBL." WHERE star_ratings = 3) as threestarcount,
	// 		(SELECT COUNT(star_ratings) as rating FROM ".REVIEW_TBL." WHERE star_ratings = 4) as fourstarcount,
	// 		(SELECT COUNT(star_ratings) as rating FROM ".REVIEW_TBL." WHERE star_ratings = 5) as fivestarcount FROM ".REVIEW_TBL." R WHERE R.product_id='".$product_id."' AND R.approval_status='1' AND R.del_status='0' AND R.status='1' ORDER BY R.id DESC " ;

	//  	$exe = $this->exeQuery($q);
	//  	if(mysqli_num_rows($exe)>0){
	//  		    	$i=1;
	//  		    	while($row = mysqli_fetch_array($exe)){
	//     				$list  = $this->editPagePublish($row);
	    			
	//     				$total_count_val = (5*$list['fivestarcount'] + 4*$list['fourstarcount'] + 3*$list['threestarcount'] + 2*$list['twostarcount'] + 1*$list['onestarcount']) / ($list['fivestarcount']+$list['fourstarcount']+$list['threestarcount']+$list['twostarcount']+$list['onestarcount']);

	//     				$total_count = round($total_count_val,2);

	//  		    		$layout .= "
	// 							<input type='hidden' value='".$total_count."' id='total_cot'>
	// 						";
	//                     $i++;
	// 		    	}
	//  		    }

	//  	return $layout;
	// }

	function getProductReviewsCount($product_id)
	{
		$layout = "";

		$q = "SELECT R.id, R.product_id, R.name, R.email, R.comment, SUM(R.star_ratings) AS rating_count,
					COUNT(R.star_ratings) AS rating_tot, R.star_ratings, R.approval_status, R.del_status, R.status, R.added_by, R.created_at, R.updated_at,
					(SELECT COUNT(*) FROM ".REVIEW_TBL." 
						WHERE product_id = '".$product_id."' 
						AND star_ratings = 1 
						AND approval_status='1' 
						AND del_status='0' 
						AND status='1') AS onestarcount,
					(SELECT COUNT(*) FROM ".REVIEW_TBL." 
						WHERE product_id = '".$product_id."' 
						AND star_ratings = 2 
						AND approval_status='1' 
						AND del_status='0' 
						AND status='1') AS twostarcount,
					(SELECT COUNT(*) FROM ".REVIEW_TBL." 
						WHERE product_id = '".$product_id."' 
						AND star_ratings = 3 
						AND approval_status='1' 
						AND del_status='0' 
						AND status='1') AS threestarcount,
					(SELECT COUNT(*) FROM ".REVIEW_TBL." 
						WHERE product_id = '".$product_id."' 
						AND star_ratings = 4 
						AND approval_status='1' 
						AND del_status='0' 
						AND status='1') AS fourstarcount,
					(SELECT COUNT(*) FROM ".REVIEW_TBL." 
						WHERE product_id = '".$product_id."' 
						AND star_ratings = 5 
						AND approval_status='1' 
						AND del_status='0' 
						AND status='1') AS fivestarcount
				FROM ".REVIEW_TBL." R WHERE R.product_id = '".$product_id."' AND R.approval_status='1' AND R.del_status='0' AND R.status='1' GROUP BY R.id, R.product_id, R.name, R.email, R.comment, R.star_ratings, R.approval_status, R.del_status, R.status, R.added_by, R.created_at, R.updated_at ORDER BY R.id DESC";

		$exe = $this->exeQuery($q);

		if (mysqli_num_rows($exe) > 0) {
			$i = 1;
			while ($row = mysqli_fetch_array($exe)) {
				$list = $this->editPagePublish($row);

				$denominator = ($list['fivestarcount'] + $list['fourstarcount'] + $list['threestarcount'] + $list['twostarcount'] + $list['onestarcount']);
				$total_count_val = 0;
				if ($denominator > 0) {
					$total_count_val = (5 * $list['fivestarcount'] + 
										4 * $list['fourstarcount'] + 
										3 * $list['threestarcount'] + 
										2 * $list['twostarcount'] + 
										1 * $list['onestarcount']) / $denominator;
				}

				$total_count = round($total_count_val, 2);

				$layout .= "
					<input type='hidden' value='".$total_count."' id='total_cot'>
				";
				$i++;
			}
		}

		return $layout;
	}

	function getVendorPrdPrice($product_id,$variant)
	{	
		// Condition for get product price based on variant
		$variant_check = "";

		if($variant!="") {
			$variant_check          = "AND variant_id='".$variant['id']."' ";
		}


		// Get lowest price for this product 
		
		if(count($_SESSION['vendors_at_this_location']) > 0) {
			
			$query = "SELECT VP.id,VP.vendor_id,VP.min_qty,VP.min_qty,VP.max_qty,VP.selling_price,VP.stock,V.status FROM ".VENDOR_PRODUCTS_TBL." VP LEFT JOIN ".VENDOR_TBL." V ON (V.id=VP.vendor_id) WHERE VP.product_id='".$product_id."'  AND VP.stock >= VP.min_qty AND V.status='1' AND VP.vendor_id IN (".implode(',', array_map('intval',$_SESSION['vendors_at_this_location'])).") ".$variant_check." ORDER BY VP.selling_price ASC LIMIT 1 ";

			$exe                    = $this->exeQuery($query);
			$result                 = mysqli_fetch_array($exe);

			if(isset($result['vendor_id'])){
				$pass_exe           = $this->exeQuery($query);
				$result             = mysqli_fetch_array($pass_exe);
			}else{
				$q 	                = "SELECT * FROM ".PRODUCT_TBL." WHERE id='".$product_id."'   ";
				$pass_exe           = $this->exeQuery($q);
				$result             = mysqli_fetch_array($pass_exe);
				$result['min_qty']  = $result['min_order_qty'];
				$result['max_qty']  = $result['max_order_qty'];
			}


			$result['vendor_stock'] = 1;

		} else {
			$q 	 = "SELECT * FROM ".PRODUCT_TBL." WHERE id='".$product_id."'   ";
			$pass_exe               = $this->exeQuery($q);
			$result                 = mysqli_fetch_array($pass_exe);
			$result['min_qty']      = $result['min_order_qty'];
			$result['max_qty']      = $result['max_order_qty'];

		}

		return  $result;
	}


	//getVendorProduct

	function getVendorPrd($product_id, $vendor_id,$variant)
	{	
		// Condition for get product price based on variant

		$variant_check = "";

		if($variant!="") {
			$variant_check = "AND variant_id='".$variant['id']."' ";
		}

		$query = "SELECT VP.id,VP.vendor_id,VP.min_qty,VP.max_qty,VP.selling_price,VP.stock,V.status FROM ".VENDOR_PRODUCTS_TBL." VP LEFT JOIN ".VENDOR_TBL." V ON (V.id=VP.vendor_id) WHERE V.status='1' AND VP.stock >= VP.min_qty AND VP.product_id='".$product_id."' ".$variant_check."  AND VP.vendor_id='".$vendor_id."' ";
		$exe    = $this->exeQuery($query);
		$result = mysqli_fetch_array($exe);

		$result['vendor_stock'] = 1;

		if(mysqli_num_rows($exe)==0)
		{	
			$prd_info = $this->getDetails(PRODUCT_TBL,"*","id='".$product_id."'");
			$result['vendor_stock'] = 0;

			if($prd_info['has_variants'] ) {
				if(@$_GET['v']) {
					$variant_info = $this->getDetails(PRODUCT_VARIANTS,"*","token='".$_GET['v']."'");
					$result['selling_price'] = $variant_info['selling_price'];
					$result['actual_price']  = $variant_info['actual_price'];
				} else {
					$variant_info = $this->getDetails(PRODUCT_VARIANTS,"*","product_id='".$result['id']."' ORDER BY id ASC LIMIT 1");
					$result['selling_price'] = $variant_info['selling_price'];
					$result['actual_price']  = $variant_info['actual_price'];
				}
				
			}
		}

		return  $result;
	}


	//getVendorProduct

	function getVendorPrdCompany($product_id, $vendor_id)
	{
		$query = "SELECT id,company FROM ".VENDOR_TBL." WHERE id='".$vendor_id."' ";
		$exe    = $this->exeQuery($query);
		$result = mysqli_fetch_array($exe);
		return  $result;
	}

	// getVendorCompany

	function getVendorCompany($product_id,$variant)
	{	

		// Condition for get product price based on variant

		$variant_check = "";

		if($variant!="") {
			$variant_check = "AND variant_id='".$variant['id']."' ";
		}


		if(count($_SESSION['vendors_at_this_location']) > 0) {
			$query = "SELECT VP.id,VP.vendor_id,VP.min_qty,VP.selling_price,VP.stock,V.status FROM ".VENDOR_PRODUCTS_TBL." VP LEFT JOIN ".VENDOR_TBL." V ON (V.id=VP.vendor_id) WHERE V.status=1 AND VP.product_id='".$product_id."' AND VP.stock >= VP.min_qty AND VP.vendor_id IN (".implode(',', array_map('intval',$_SESSION['vendors_at_this_location'])).") ".$variant_check." ORDER BY VP.selling_price ASC LIMIT 1 ";
			$exe  			= $this->exeQuery($query);
			$result   		=    mysqli_fetch_array($exe);
			if(isset($result['vendor_id'])){
				$vendor_name 	= $this->getVendorNameForProduct($result['vendor_id']);
			}else{
				$vendor_name['company']    = "";
			}
			return $vendor_name;
		} else {
			$vendor_name['company'] ="Sapiens";
			return $vendor_name;
		}
	}

	// getVendorCompany pass id
	function getVendorNameForProduct($vendor_id){
		$query = "SELECT id,company FROM ".VENDOR_TBL." WHERE id='".$vendor_id."' ";
		$exe    = $this->exeQuery($query);
		$result = mysqli_fetch_array($exe);
		return $result;
	}

	// get other vendor list 

	function getOtherVendorPrd($product_id, $vendor_id, $variant,$selected_variant)
	{ 
		$layout = "";
		$result = array();


		// Condition for get product price based on variant

		$variant_check = "";

		if($variant!="") {
			$variant_check = "AND variant_id='".$variant['id']."' ";
		}

		$variant_url = "";

		if($selected_variant!="") {
			$variant_url = "?v=".$selected_variant['token'];
		}

		if(count($_SESSION['vendors_at_this_location']) > 0) {
			$query = "SELECT VP.id,VP.vendor_id,VP.min_qty,VP.selling_price,VP.stock,V.status FROM ".VENDOR_PRODUCTS_TBL." VP LEFT JOIN ".VENDOR_TBL." V ON (V.id=VP.vendor_id) WHERE VP.vendor_id!='".implode(',', array_map('intval',$_SESSION['vendors_at_this_location']))."' AND VP.stock >= VP.min_qty AND VP.product_id='".$product_id."' AND V.status='1' AND VP.vendor_id IN (".$vendor_ids.") ".$variant_check." GROUP BY VP.vendor_id ORDER BY VP.selling_price ASC";
			$exe    = $this->exeQuery($query);
			if(mysqli_num_rows($exe)>0){
			$i=1;
			$option_arr  = array();
				while ($row = mysqli_fetch_array($exe)) {
					$product_info = $this->getDetails(PRODUCT_TBL,"*","id='".$product_id."' ");
		    		$list  = $this->editPagePublish($row);
		    		
						$layout .= "<div class='".$list['id']." other_vendor_prd vendor-recommended'><img src='".BASEPATH."lib/images/blog-product4.jpg' alt='' ><a href='".BASEPATH."product/details/".$product_info['page_url']."/".$list['vendor_id']."".$variant_url."'> ".$this->getOtherVendorCompany($list['vendor_id'])."Price : ".$list['selling_price']."</a></div>";
					
					$i++;
				}
				$result['option']  = $option_arr;
				return $layout;
			} else {
				return $layout;
			}
		}
	}


	function getOtherVendorCompany($vendor_id)
	{
		$layout = "<div class='other_vendor_cmpny d-flex' >";
		$q = "SELECT V.id,V.company, 
				(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 1 AND vendor_id='".$vendor_id."' ) as onestarcount,
				(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 2 AND vendor_id='".$vendor_id."' ) as twostarcount,
				(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 3 AND vendor_id='".$vendor_id."' ) as threestarcount,
				(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 4 AND vendor_id='".$vendor_id."' ) as fourstarcount,
				(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 5 AND vendor_id='".$vendor_id."' ) as fivestarcount 
			  FROM ".VENDOR_TBL." V WHERE V.id='".$vendor_id."' ";
		$exe = $this->exeQuery($q);
		if(mysqli_num_rows($exe)>0){
			$i=1;
			$option_arr  = array();
				while ($row   = mysqli_fetch_array($exe)) {
	    			$list     = $this->editPagePublish($row);

	    			$totl_stat_count = (5*$list['fivestarcount'] + 4*$list['fourstarcount'] + 3*$list['threestarcount'] + 2*$list['twostarcount'] + 1*$list['onestarcount']) / ($list['fivestarcount']+$list['fourstarcount']+$list['threestarcount']+$list['twostarcount']+$list['onestarcount']);

					$layout .= "
					<p class='mb-1'>".$list['company']."</p>
						<span class='my-rating-7 ms-2'></span>
						<input type='hidden' class='rating_data' name='star_ratings' value='".$totl_stat_count."' id='rating_data'>
					";
					$i++;
				}
				$layout .="</div>";
				return $layout;
		} else {
			return $layout;
		}

	}

	function getVendorOverAllStarRating($vendor_id)
	{
		
		$layout = "";
		$q = "SELECT V.id,V.company, 
				(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 1 AND vendor_id='".$vendor_id."') as onestarcount,
				(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 2 AND vendor_id='".$vendor_id."') as twostarcount,
				(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 3 AND vendor_id='".$vendor_id."') as threestarcount,
				(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 4 AND vendor_id='".$vendor_id."') as fourstarcount,
				(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 5 AND vendor_id='".$vendor_id."') as fivestarcount 
			  FROM ".VENDOR_TBL." V WHERE V.id='".$vendor_id."' ";
		$exe = $this->exeQuery($q);
		if(mysqli_num_rows($exe)>0){
			$i=1;
			$option_arr  = array();
				while ($row   = mysqli_fetch_array($exe)) {
	    			$list     = $this->editPagePublish($row);

	    			$totl_stat_count = (5*$list['fivestarcount'] + 4*$list['fourstarcount'] + 3*$list['threestarcount'] + 2*$list['twostarcount'] + 1*$list['onestarcount']) / ($list['fivestarcount']+$list['fourstarcount']+$list['threestarcount']+$list['twostarcount']+$list['onestarcount']);

					$layout .= "
						<span class='my-rating-7 ms-2'></span>
						<input type='hidden' class='rating_data' name='star_ratings' value='".$totl_stat_count."' id='rating_data'>
					";
					$i++;
				}
				return $layout;
		} else {
			return $layout;
		}
	}

	//getOtherVendorsList

	function getOtherVendorsList($product_id,$vendor_id,$variant,$selected_variant)
	{ 
		$layout            = "";
		$result            = array();

		// Condition for get product price based on variant

		$variant_check     = "";

		if($variant!="") {
			$variant_check = "AND variant_id='".$variant['id']."' ";
		}

		$variant_url       = "";

		if($selected_variant!="") {
			$variant_url   = "?v=".$selected_variant['token'];
		}

		if(count($_SESSION['vendors_at_this_location']) > 0) {
			
			$get_min_order_value = $this->getDetails(PRODUCT_TBL,"min_order_qty","id='".$product_id."'");
			$min_val             = $get_min_order_value['min_order_qty'];
			$query               = "SELECT id,vendor_id,selling_price FROM ".VENDOR_PRODUCTS_TBL." WHERE vendor_id!='".$vendor_id."' AND stock >= $min_val AND product_id='".$product_id."' AND status='1' AND vendor_id IN (".implode(',', array_map('intval',$_SESSION['vendors_at_this_location'])).") ".$variant_check." GROUP BY vendor_id ORDER BY selling_price ASC";
			$exe                 = $this->exeQuery($query);
			
			if(mysqli_num_rows($exe) > 0 ) 
			{
				$i          = 1;
				$option_arr = array();
					
					while ($row = mysqli_fetch_array($exe)) {

						$product_info = $this->getDetails(PRODUCT_TBL,"*","id='".$product_id."' ");
			    		$list         = $this->editPagePublish($row);
			    		
							$layout  .= "<div class='".$list['id']." other_vendor_prd vendor-recommended'><img src='".BASEPATH."lib/images/blog-product4.jpg' alt='' ><a href='".BASEPATH."product/details/".$product_info['page_url']."/".$list['vendor_id']."".$variant_url."'> ".$this->getOtherVendorCompany($list['vendor_id'])." Price : ".$list['selling_price']."</a></div>";
						
						$i++;
					}

					$result['option']  = $option_arr;
					return $layout;

			} else {

				return $layout;

			}
		}
	}

	// Vendor Price Table 

	function getVendorPriceTable($product_id)
	{
		$layout = "";
		if(count($_SESSION['vendors_at_this_location']) > 0) {
			$query = "SELECT id,vendor_id,selling_price FROM ".VENDOR_PRODUCTS_TBL." WHERE product_id='".$product_id."' AND status='1' AND vendor_id IN (" . implode(',', array_map('intval',$_SESSION['vendors_at_this_location'])) .") ORDER BY selling_price ASC";
			$exe    = $this->exeQuery($query);
			if(mysqli_num_rows($exe)>0){
			$i=1;
				$layout .= " <thead>
                                    <tr>
                                        <th></th>
                                        <th colspan='2' text='center' >Venpep</th>
                                        <th colspan='2' text='center' >Tbybe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>Variants</td><td>PPC</td><td>PSC</td><td>PPC</td><td>PSC</td><td>PPC</td><td>PSC</td></tr>
                                    <tr>
                                        <td>25 KG</td><td>₹ 199</td><td>299</td><td>₹ 199</td><td>₹ 299</td><td>₹ 199</td><td>₹ 299</td>
                                    </tr>
                                    <tr>
                                        <td>35 KG</td><td>₹ 199</td><td>299</td><td>₹ 199</td><td>₹ 299</td><td>₹ 199</td><td>₹ 299</td>
                                    </tr>
                                </tbody>";

				while ($row = mysqli_fetch_array($exe)) {
		    		$list  = $this->editPagePublish($row);
		    		if($i!=1){
						$layout .= "<div  class='".$list['id']." other_vendor_prd vendor-recommended'> ".$this->getOtherVendorName($list['vendor_id'])."Price : ".$list['selling_price']."</div>";
					}
					$i++;
				}
			}
		}
		return $layout;
	}

	//getOtherVendorsName
	function getOtherVendorName($option_id)
	{
		$layout = "<div class='other_vendor_cmpny' >";
		$q = "SELECT  * FROM ".VENDOR_TBL." WHERE id='".$option_id."' ";
		$exe = $this->exeQuery($q);
		if(mysqli_num_rows($exe)>0){
			$i=1;
			$option_arr  = array();
				while ($row = mysqli_fetch_array($exe)) {
	    			$list  = $this->editPagePublish($row);
					$layout .= "
					<p>".$list['company']."</p>
					";
					$i++;
				}
				$layout .="</div>";
				return $layout;
		} else {
			return $layout;
		}

	}

	// Get Selected Variant Options

	function getSelectedOptionIds($token) 
	{	
		$option_ids = array();
		$token = explode("/", str_replace(" ","", $token));
		foreach ($token as $key => $value) {
			$q = "SELECT id FROM ".PRODUCT_OPTION_VARIANTS." WHERE token='".$value."'";
			$exe = $this->exeQuery($q);
			while ($row = mysqli_fetch_array($exe)) {
	    		$list  = $this->editPagePublish($row);
	    		$option_ids[] = $list['id'];
			}
		}
		return $option_ids;
	}

	// Get Selected Option IDs from Cart Variant Data
	function getSelectedOptionIdsFromCart($variant_id) 
	{	
		$option_ids = array();
		if($variant_id && $variant_id != '0') {
			$q = "SELECT id FROM ".PRODUCT_OPTION_VARIANTS." WHERE id='".$variant_id."'";
			$exe = $this->exeQuery($q);
			if(mysqli_num_rows($exe)>0){
				$row = mysqli_fetch_array($exe);
				$option_ids[] = $row['id'];
			}
		}
		return $option_ids;
	}


	// Manage Product Variants & Options  
	
	function getProductVariants($product_id,$variant)
	{
		$layout = "";
		$result = array();
		$option_ids = array();
		
		// If no variant is selected, get the first variant of each option as default
		if($variant=="" || !isset($variant['variant_name'])) {
			$q = "SELECT  * FROM ".PRODUCT_OPTIONS." WHERE product_id='".$product_id."'   ";
			$exe = $this->exeQuery($q);
			if(mysqli_num_rows($exe)>0){
				while ($row = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($row);
					// Get the first variant for this option
					$first_variant_q = "SELECT id FROM ".PRODUCT_OPTION_VARIANTS." WHERE option_id='".$list['id']."' ORDER BY id ASC LIMIT 1";
					$first_variant_exe = $this->exeQuery($first_variant_q);
					if(mysqli_num_rows($first_variant_exe)>0){
						$first_variant_row = mysqli_fetch_array($first_variant_exe);
						$option_ids[] = $first_variant_row['id'];
					}
				}
			}
		} else {
			// Check if this is cart variant data (has id but no variant_name)
			if(isset($variant['id']) && !isset($variant['variant_name'])) {
				$option_ids = $this->getSelectedOptionIdsFromCart($variant['id']);
			} else {
				$option_ids = $this->getSelectedOptionIds($variant['variant_name']);
			}
		}
		
		$q = "SELECT  * FROM ".PRODUCT_OPTIONS." WHERE product_id='".$product_id."'   ";
		$exe = $this->exeQuery($q);

		if(mysqli_num_rows($exe)>0){
		$i=1;
		$option_arr  = array();
			while ($row = mysqli_fetch_array($exe)) {
	    		$list  = $this->editPagePublish($row);
				$layout .= "<div class='option-group'>
								<label class='option-label'>".$list['option_title'].":</label>
								<div class='variant-options'>".$this->getOptionVariants($list['id'],$list['option_title'],$option_ids)."</div>
							</div>";
			}
			$result['option']  = $option_arr;
			return $layout;
		} else {
			return $layout;
		}
	}

	function getOptionVariants($option_id,$option,$option_ids)
	{
		$layout = "";
		$q = "SELECT  * FROM ".PRODUCT_OPTION_VARIANTS." WHERE option_id='".$option_id."' ";
		$exe = $this->exeQuery($q);
		if(mysqli_num_rows($exe)>0){
			$i=1;
			$option_arr  = array();
				while ($row = mysqli_fetch_array($exe)) {
	    			$list  = $this->editPagePublish($row);
	    			$check_inarray = in_array($list['id'], $option_ids);
					$active_class = ($check_inarray==1)? ' active' : '';
					
					// Check if this is a color variant
					$is_color = (stripos($option, 'color') !== false || stripos($option, 'colour') !== false);
					
					if($is_color) {
						// Generate color swatches
						$color_value = strtolower($list['variant_title']);
						$color_style = $this->getColorStyle($color_value);
						$layout .= "<div class='color-option".$active_class."' data-color='".$color_value."' data-variant='".$list['id']."' data-option='".$option_id."' data-token='".$list['token']."' style='".$color_style."' title='".$list['variant_title']."'></div>";
					} else {
						// Generate size or other option buttons
						$layout .= "<div class='size-option".$active_class."' data-size='".$list['variant_title']."' data-variant='".$list['id']."' data-option='".$option_id."' data-token='".$list['token']."'>".$list['variant_title']."</div>";
					}
					$i++;
				}
				return $layout;
		} else {
			return $layout;
		}
	}
	
	// Helper function to get color styles
	function getColorStyle($color_value) {
		$color_map = [
			'red' => 'background: #dc3545;',
			'blue' => 'background: #007bff;',
			'green' => 'background: #28a745;',
			'yellow' => 'background: #ffc107;',
			'orange' => 'background: #fd7e14;',
			'purple' => 'background: #6f42c1;',
			'pink' => 'background: #e83e8c;',
			'black' => 'background: #000000;',
			'white' => 'background: #ffffff; border: 1px solid #ddd;',
			'gray' => 'background: #6c757d;',
			'grey' => 'background: #6c757d;',
			'brown' => 'background: #8b4513;',
			'navy' => 'background: #000080;',
			'teal' => 'background: #20c997;',
			'cyan' => 'background: #17a2b8;'
		];
		
		$color_value = strtolower(trim($color_value));
		return isset($color_map[$color_value]) ? $color_map[$color_value] : 'background: #f8f9fa; border: 1px solid #ddd;';
	}

	/*--------------------------------------------- 
					Cart Functions
	----------------------------------------------*/ 

	// Sum Of Cart - Cart Items Table SUM

    function sumOfCartDetails()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(total_amount) as total FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return number_format((float)$result['total'], 2, '.', '');
        // return $result['total'];
    }

	// Sum Of Cart Subtotal - Cart Items Table SUM

    function sumOfCartSubtotalDetails()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(sub_total) as total_price FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =  mysqli_fetch_array($exe);
        return number_format((float)$result['total_price'], 2, '.', '');
        // return $result['total_price'];
    }


    // Sum Of Cart id - Cart Items Table SUM

    function countOfCartid()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT COUNT(cart_id) as cart FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['cart'];
    }


    // Sum Of Cart Total Tax - Cart Items Table SUM

    function sumOfCartSGST()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(sgst) as sgst FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['sgst'];
    }


    function sumOfCartCGST()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(cgst) as cgst FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['cgst'];
    }


    function sumOfCartIGST()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(igst) as igst FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['igst'];
    }
	




	function sumOfCartSGSTAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(sgst_amt) as sgst_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return number_format((float)$result['sgst_amt'], 2, '.', '');
        // return $result['sgst_amt'];
    }

    function sumOfCartCGSTAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(cgst_amt) as cgst_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return number_format((float)$result['cgst_amt'], 2, '.', '');
        // return $result['cgst_amt'];
    }

    function sumOfCartIGSTAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(igst_amt) as igst_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        // return number_format((float)$result['igst_amt'], 2, '.', '');
        return $result['igst_amt'];
    }

    /*-----------------------------------------
			Commission & Charges total
    ------------------------------------------*/

    function sumOfVendorCommission()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(vendor_commission) as vendor_commission FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_commission'];
    }

    function sumOfVendorCommissionTax()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(vendor_commission_tax) as vendor_commission_tax FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_commission_tax'];
    }

    function sumOfVendorPaymentCharge()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(vendor_payment_charge) as vendor_payment_charge FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_payment_charge'];
    }

    function sumOfVendorPaymentTax()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(vendor_payment_tax) as vendor_payment_tax FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_payment_tax'];
    }

    function sumOfVendorShippingCharge()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(vendor_shipping_charge) as vendor_shipping_charge FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_shipping_charge'];
    }

    function sumOfVendorShippingTax()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(vendor_shipping_tax) as vendor_shipping_tax FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_shipping_tax'];
    }

    function sumOfVendorCommissionAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(vendor_commission_amt) as vendor_commission_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_commission_amt'];
    }

    function sumOfVendorCommissionTaxAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(vendor_commission_tax_amt) as vendor_commission_tax_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_commission_tax_amt'];
    }

    function sumOfVendor_paymentChargeAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(vendor_payment_charge_amt) as vendor_payment_charge_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_payment_charge_amt'];
    }

    function sumOfVendor_paymentTaxAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(vendor_payment_tax_amt) as vendor_payment_tax_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_payment_tax_amt'];
    }

    function sumOfVendorShippingChargeAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(vendor_shipping_charge_amt) as vendor_shipping_charge_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_shipping_charge_amt'];
    }

    function sumOfVendorShippingTaxAmt()
    {    
        $user_id = @$_SESSION['user_session_id'];
        $cart_id = @$_SESSION['user_cart_id'];
        $q      = "SELECT SUM(vendor_shipping_tax_amt) as vendor_shipping_tax_amt FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe = $this->exeQuery($q);
        $result   =    mysqli_fetch_array($exe);
        return $result['vendor_shipping_tax_amt'];
    }

    function getShippingCost()
    {
    
        $cart_id            = @$_SESSION['user_cart_id'];

        //total tax cal
		$tax_cal = "SELECT SUM(shipping_value) as shipping_value, SUM(shipping_tax) as shipping_tax, SUM(shipping_tax_value) as shipping_tax_value, SUM(shipping_cost) as shipping_cost FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' ";
		$handel_exe            = $this->exeQuery($tax_cal);
		$handle_list_count     = mysqli_fetch_array($handel_exe);
		

        $result = array();
        $result['shipping_value']       = $handle_list_count['shipping_value'];
        $result['shipping_tax']         = $handle_list_count['shipping_tax'];
        $result['shipping_tax_value']   = $handle_list_count['shipping_tax_value'];
        $result['shipping_cost']        = $handle_list_count['shipping_cost'];
        $result['to']                   =  $get_total_amount;
        $result['c_st']                 = $get_coupon_value['coupon_status'];
        $result['c_va']                 = $get_coupon_value['coupon_value'];
        return $result;
    }


	/*--------------------------------------------- 
				Cart Management
	----------------------------------------------*/ 

	// Add to Cart

	function addToCart($input='')
	{	
		// Simplified without vendor concept
			$today 		= date("Y-m-d");
			if (isset($input['session_token'])){ 
				$product_id = $this->decryptData($input['session_token']);
			}else{
				$product_id = $this->decryptData($input['result']);
			}
			if (isset($_SESSION["user_session_id"])){ 
				$user_id 	= $_SESSION['user_session_id'];
			}else{
				$user_id   	= '0';
			}

			$cart_id        = @$_SESSION['user_cart_id'];
			$curr  		 	= date("Y-m-d H:i:s");
			$product_info   = $this->getDetails(PRODUCT_TBL,"*","id='".$product_id."' ");
			$tax_info    	= $this->getDetails(TAX_CLASSES_TBL,"*","id='".$product_info['tax_class']."' ");
			$quantity		= $input['quantity'];
			$price 			= $product_info['selling_price'];
			$variant_id  	= "0";
			$option_info  	=  0;

			if($product_info['has_variants']==1) {
			if(isset($input['variant']) && is_array($input['variant']) && count($input['variant']) > 0) {
				// Debug: Log the variant data
				error_log('Variant data received: ' . print_r($input['variant'], true));
				
				// Join variant tokens with " /" as expected by the database
					$option_token_raw 	= implode(" /", $input['variant']);
					$trimed 			= str_replace('-','',$option_token_raw);
					$option_token		= $this->hyphenize($trimed);
				
				// Debug: Log the processed token
				error_log('Processed variant token: ' . $option_token);

					$option_info 	= $this->getDetails(PRODUCT_VARIANTS,"*"," token='".$option_token."'  ");
				if($option_info) {
					$price 		 = $option_info['selling_price']; 
					$variant_id  = $option_info['id'];
						
					} else {
					// Debug: Log when variant not found
					error_log('Variant not found for token: ' . $option_token);
						return "0"."`Selected Variant not available " ;
					}
				}else {
				// Debug: Log when no variants are selected
				error_log('No variants selected for product with variants. Input: ' . print_r($input, true));
					return "redirect"."`".$product_info['page_url']."" ;
				}
		}

		// Simplified quantity validation
		if ($quantity >= 1 && $quantity <= 10) {
			// Calculate tax
			$sub_total = $price * $quantity;
			$tax_total = $sub_total;
			
			// Calculate tax amounts
			$sgst_amt = 0;
			$cgst_amt = 0;
			$igst_amt = 0;
			
			if ($product_info['tax_type'] != "inclusive") {
				$sgst = 1 + ($tax_info['sgst']/100);
				$cgst = 1 + ($tax_info['cgst']/100);
				$igst = 1 + ($tax_info['igst']/100);
				$sgst_amt = round(($tax_total * $sgst) - $tax_total, 2);
				$cgst_amt = round(($tax_total * $cgst) - $tax_total, 2);
				$igst_amt = round(($tax_total * $igst) - $tax_total, 2);
			}
			
			$tax_amt = $tax_total;
			$variant_check = ($variant_id != 0) ? "AND variant_id='".$variant_id."'" : "";
			
			// Check if item already exists in cart
			$check_id = $this->check_query(CART_ITEM_TBL, "id", "product_id='".$product_id."' AND cart_id='".$cart_id."' ".$variant_check);
			
			if ($check_id == 0) {
				// Create cart if doesn't exist
				if (!isset($_SESSION["user_cart_id"])) {
					$qu = "INSERT INTO ".CART_TBL." SET 
						user_id = '".$user_id."',
						status = '1',
						created_at = '".$curr."',
						updated_at = '".$curr."'";
					$result = $this->lastInserID($qu);
					$_SESSION["user_cart_id"] = $result;
					$cart_id = $result;
				}
				
				// Insert cart item
									$query = "INSERT INTO ".CART_ITEM_TBL." SET
					user_id = '".$user_id."',
					cart_id = '".$cart_id."',
					product_id = '".$product_id."',
					category_id = '".$product_info['main_category_id']."',
					sub_category_id = '".$product_info['sub_category_id']."',
					tax_type = '".$product_info['tax_type']."',
					qty = '".$quantity."',
					variant_id = '".$variant_id."',
					final_price = '".$sub_total."',
					total_amount = '".$tax_total."',
					sub_total = '".$tax_amt."',
					price = '".$price."',
					tax_amt = '".$tax_amt."',
					total_tax = '".$igst_amt."',
					sgst_amt = '".$sgst_amt."',
					cgst_amt = '".$cgst_amt."',
					igst_amt = '".$igst_amt."',
					sgst = '".$tax_info['sgst']."',
					cgst = '".$tax_info['cgst']."',
					igst = '".$tax_info['igst']."',
					status = '1',
					created_at = '".$curr."',
					updated_at = '".$curr."'";
				
				$exe = $this->exeQuery($query);

									if ($exe) {
					// Update product stock
					$new_stock = $product_info['stock'] - $quantity;
					$stock_update = "UPDATE ".PRODUCT_TBL." SET stock = '".$new_stock."' WHERE id = '".$product_id."'";
					$this->exeQuery($stock_update);
					
					// Update cart totals
					$updated_cart = "SELECT COUNT(cart_id) as total_items, SUM(final_price) as total, SUM(tax_amt) as sub_total FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."'";
					$exe = $this->exeQuery($updated_cart);
					$list = mysqli_fetch_assoc($exe);

										$qu = "UPDATE ".CART_TBL." SET 
						sub_total = '".$list['sub_total']."',
						total_amount = '".$list['total']."',
						updated_at = '".$curr."' WHERE id='".$cart_id."'";
					$result = $this->exeQuery($qu);

										return "1"."`".$product_info['page_url']."";
				} else {
										return "0"."`Error Occurred";
							}
						} else {
				return "0"."`Item already exists in cart";
			}
    	} else {
			return "0"."`Invalid quantity";
    	}
	}

	function couponCodeCheckValid($data)
    {   
        if (isset($_SESSION['user_session_id'])) {
            
            $check  = $this -> check_query(COUPON_TBL,"id"," coupon_code ='".$data."' AND status='1' ");
            if ($check==1) {
                $general = $this->generalCouponCheck($data);
                return $general;
            }else{
                return "The Coupon Code is InValid";
            }
        }else{
            return "Login to Add coupon Code";
        }
    }

    function calcOfferValue($value,$total)
    {
        $amount = (($value*$total)/100);
        return $amount;
    }


    // General Coupon Check

    function generalCouponCheck($coupon_code)
    {
        $cart_id    = $_SESSION['user_cart_id'];
        $user_id    = $_SESSION['user_session_id'];
        $curr       = date("Y-m-d H:i:s");
        $info       = $this->getDetails(COUPON_TBL,"id,type,discount_value,min_purchase_amt,coupon_code,end_date,end_time,start_date,start_time,per_user_limit,per_user_limit_value,applies_to,specific_category,specific_product","coupon_code='".$coupon_code."' ");
        $cart_info  = $this->getDetails(CART_TBL,"id,total_amount,sub_total,total_tax","id='".$cart_id."'");

        $start_time = date("Y-m-d H:i:s",strtotime($info['start_date']." ".$info['start_time']));
        $end_time   = date("Y-m-d H:i:s",strtotime($info['end_date']." ".$info['end_time'])); 
        $today      = date("Y-m-d H:i:s");

        $user_coupon_count = $this->getDetails(ORDER_TBL,"count(*) as user_couponcount"," coupon_id='".$info['id']."' AND coupon_code='".$info['coupon_code']."' AND user_id='".$_SESSION['user_session_id']."' ");

        if($info['per_user_limit']==1) {
            $per_user_limit = $user_coupon_count['user_couponcount'] < $info['per_user_limit_value'];
        } else {
            $per_user_limit = true;
        }

        if($today >= $start_time) {
            if ($today <= $end_time) {
                if($per_user_limit) {
                    if($info['applies_to']=='a_t_all_products') {
                        $cart_value = $cart_info['sub_total'] + $cart_info['total_tax'];
                        if ( $cart_value >= $info['min_purchase_amt']) {

                            $offer_value = $info['type'] == "c_t_fixed_amount" ? $info['discount_value'] :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$cart_value): '' );

                            // Calculate Shipping Cost
                            $shipping_total_val = $cart_value - $offer_value;
                            $shipping_cost = $this->getShippingCost();

                            $q = "UPDATE ".CART_TBL." SET 
                                coupon_id       = '".$info['id']."',
                                coupon_code     = '".$coupon_code."',
                                coupon_type     = '".$info['type']."',
                                coupon_value    = '".$offer_value."',
                                coupon_status   = '1',
                                shipping_value      = '".$shipping_cost['shipping_value']."',
                                shipping_tax        = '".$shipping_cost['shipping_tax']."',
                                shipping_tax_value  = '".$shipping_cost['shipping_tax_value']."',
                                shipping_cost       = '".$shipping_cost['shipping_cost']."',
                                updated_at      = '".$curr."' 
                                WHERE  id       = '".$cart_id."' ";
                            $exe = $this->exeQuery($q);


                            $query_2 = "SELECT * FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' " ;
                            $exe_2   = $this->exeQuery($query_2);
                            if(@mysqli_num_rows($exe_2)>0){
                                $i=0;
                                while($list = mysqli_fetch_array($exe_2)){

                                    $product_price = $list['sub_total'] + $list['total_tax'];

                                    $offer_value = $info['type'] == "c_t_fixed_amount" ?  $this->productPerecentageValue($cart_value,$product_price,$info['discount_value']) :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$product_price): '' );

                                    $q_3 = "UPDATE ".CART_ITEM_TBL." SET 
                                    coupon_id       = '".$info['id']."',
                                    coupon_value    = '".$offer_value."',
                                    coupon_status   = '1',
                                    updated_at      = '".$curr."' 
                                    WHERE  id       = '".$list['id']."' ";
                                    $exe = $this->exeQuery($q_3);
                                }
                            }
                            return 1;
                        } else {
                            return "The Coupon Applicable for orders above ₹ ".$info['min_purchase_amt']."" ;
                        }
                    
                    } elseif($info['applies_to'] == 'a_t_specific_category') {

                        $products_in_coupon = $this->productWithCatId($info['specific_category']);

                        $total_value = $this->getDetails(CART_ITEM_TBL," SUM(sub_total) as subTotal , SUM(total_tax) as totalTax  "," cart_id='".$cart_id."' AND product_id IN (" . implode(',', array_map('intval',$products_in_coupon)). ") ");

                        $coupon_limit_prize = $total_value['subTotal'] + $total_value['totalTax'];

                        if ($coupon_limit_prize >= $info['min_purchase_amt']) {

                            $offer_value = $info['type'] == "c_t_fixed_amount" ? $info['discount_value'] :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$coupon_limit_prize): '' );

                            // Calculate Shipping Cost
                            $shipping_total_val = $coupon_limit_prize - $offer_value;
                            $shipping_cost = $this->getShippingCost();

                            $q = "UPDATE ".CART_TBL." SET 
                                coupon_id       = '".$info['id']."',
                                coupon_code     = '".$coupon_code."',
                                coupon_type     = '".$info['type']."',
                                coupon_value    = '".$offer_value."',
                                coupon_status   = '1',
                                shipping_value      = '".$shipping_cost['shipping_value']."',
                                shipping_tax        = '".$shipping_cost['shipping_tax']."',
                                shipping_tax_value  = '".$shipping_cost['shipping_tax_value']."',
                                shipping_cost       = '".$shipping_cost['shipping_cost']."',
                                updated_at      = '".$curr."' 
                                WHERE  id       = '".$cart_id."' ";
                            $exe = $this->exeQuery($q);

                            $query_2 = "SELECT * FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' AND product_id IN ( ".implode(',', array_map('intval',$products_in_coupon))." ) " ;

                            $exe_2   = $this->exeQuery($query_2);
                            if(@mysqli_num_rows($exe_2)>0){
                                $i=0;
                                while($list = mysqli_fetch_array($exe_2)){

                                    $product_price = $list['sub_total'] + $list['total_tax'];
                                    
                                    $offer_value = $info['type'] == "c_t_fixed_amount" ?  $this->productPerecentageValue($coupon_limit_prize,$product_price,$info['discount_value']) :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$product_price): '' );


                                    $q_3 = "UPDATE ".CART_ITEM_TBL." SET 
                                    coupon_id       = '".$info['id']."',
                                    coupon_value    = '".$offer_value."',
                                    coupon_status   = '1',
                                    updated_at      = '".$curr."' 
                                    WHERE  id       = '".$list['id']."' ";
                                    $exe = $this->exeQuery($q_3);
                                }
                            }

                        } else {
                            $coupon_category_names = array();
                            $q = "SELECT category FROM  ".MAIN_CATEGORY_TBL." WHERE id IN (".$info['specific_category'].") ";
                            $exe = $this->exeQuery($q);
                             if(@mysqli_num_rows($exe)>0) {
                                $i=0;
                                while($list = mysqli_fetch_array($exe)){
                                    $coupon_category_names[] = $list['category'];
                                }
                            }

                            $coupon_category_names = implode(",", $coupon_category_names);

                            return "The ".$info['coupon_code']." Coupon Applicable for orders above ₹ ".$info['min_purchase_amt']." in ".$coupon_category_names." category products " ;
                        }

                    } elseif($info['applies_to'] == 'a_t_specific_product') {

                        $total_value = $this->getDetails(CART_ITEM_TBL," SUM(sub_total) as subTotal , SUM(total_tax) as totalTax "," cart_id='".$cart_id."' AND product_id IN (" . $info['specific_product']. ") ");

                        $coupon_limit_prize = $total_value['subTotal'] + $total_value['totalTax'];

                        if ($coupon_limit_prize >= $info['min_purchase_amt']) {

                            $offer_value = $info['type'] == "c_t_fixed_amount" ? $info['discount_value'] :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'], $coupon_limit_prize): '' );

                            // Calculate Shipping Cost
                            $shipping_total_val = $coupon_limit_prize - $offer_value;
                            $shipping_cost = $this->getShippingCost();

                            $q = "UPDATE ".CART_TBL." SET 
                                coupon_id       = '".$info['id']."',
                                coupon_code     = '".$coupon_code."',
                                coupon_type     = '".$info['type']."',
                                coupon_value    = '".$offer_value."',
                                coupon_status   = '1',
                                shipping_value      = '".$shipping_cost['shipping_value']."',
                                shipping_tax        = '".$shipping_cost['shipping_tax']."',
                                shipping_tax_value  = '".$shipping_cost['shipping_tax_value']."',
                                shipping_cost       = '".$shipping_cost['shipping_cost']."',
                                updated_at      = '".$curr."' 
                                WHERE  id       = '".$cart_id."' ";
                            $exe = $this->exeQuery($q);

                            $cat_product_value = $this->getDetails(CART_ITEM_TBL,"SUM(total_amount) as total_value"," cart_id='".$cart_id."' AND product_id IN ( ".$info['specific_product']." ) ");

                            $query_2 = "SELECT * FROM ".CART_ITEM_TBL." WHERE cart_id='".$cart_id."' AND product_id IN ( ".$info['specific_product']." ) " ;

                            $exe_2   = $this->exeQuery($query_2);
                            if(@mysqli_num_rows($exe_2)>0){
                                $i=0;
                                while($list = mysqli_fetch_array($exe_2)){

                                    $product_price = $list['sub_total'] + $list['total_tax'];

                                    $offer_value = $info['type'] == "c_t_fixed_amount" ?  $this->productPerecentageValue($coupon_limit_prize,$product_price,$info['discount_value']) :  (($info['type'] == "c_t_percentage")? $this->calcOfferValue($info['discount_value'],$product_price): '' );


                                    $q_3 = "UPDATE ".CART_ITEM_TBL." SET 
                                    coupon_id       = '".$info['id']."',
                                    coupon_value    = '".$offer_value."',
                                    coupon_status   = '1',
                                    updated_at      = '".$curr."' 
                                    WHERE  id       = '".$list['id']."' ";
                                    $exe = $this->exeQuery($q_3);
                                }
                            }

                        } else {
                            $coupon_product_names = array();
                            $q = "SELECT product_name FROM  ".PRODUCT_TBL." WHERE id IN (".$info['specific_product'].") ";
                            $exe = $this->exeQuery($q);
                             if(@mysqli_num_rows($exe)>0) {
                                $i=0;
                                while($list = mysqli_fetch_array($exe)){
                                    $coupon_product_names[] = $list['product_name'];
                                }
                            }

                            $coupon_product_names = implode(",", $coupon_product_names);

                            return "The ".$info['coupon_code']." Coupon Applicable for orders above ₹ ".$info['min_purchase_amt']." for follwing products  ".$coupon_product_names." " ;
                        }

                    }
                } else {
                   return "The coupon code you entred has already been redeemed ";
                }
            } else {
                return "Coupon Expired";
            }
        } else {
            return "Invalid Coupon";
        }
        return 1;
    }

    function productPerecentageValue($total,$product_price,$discoun_value)
    {
        $percentage = (($product_price - $total) / $total) * 100;

        $percentage = abs($percentage) - 100;

        $offer_value = (( (abs($percentage)/100) + 1) * $discoun_value) - $discoun_value;

        return $offer_value ;
    }

    function productWithCatId($coupon_categories)
    { 
        $today = date("Y-m-d");
        $user_id= @$_SESSION["user_session_id"];
        $cart_id= @$_SESSION["user_cart_id"];

         // Cart ptoducts
        $query      = "SELECT product_id FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
        $exe        = $this->exeQuery($query);
        $list       = mysqli_num_rows($exe);
        $cart_product_ids = array();

        if(mysqli_num_rows($exe)) {
            while ($list = mysqli_fetch_assoc($exe)) {
                $cart_product_ids[] = $list['product_id']; 
            }
        }

        // Category ids
        $c_query      = "SELECT id,category_type,main_category_id,sub_category_id FROM ".PRODUCT_TBL." WHERE id IN ( ".implode(",", array_map('intval', $cart_product_ids))." ) ";
        $c_exe        = $this->exeQuery($c_query);
        $c_list       = mysqli_num_rows($c_exe);
        
        $cart_product_with_cat_id = array();

        if(mysqli_num_rows($c_exe)) {
            while ($list = mysqli_fetch_assoc($c_exe)) {

                    if($list['category_type']=="main") {
                        $cart_product_with_cat_id[$list['id']] = $list['main_category_id']; 
                    } else {
                        $cat_info = $this->getDetails(SUB_CATEGORY_TBL,"category_id"," id='".$list['sub_category_id']."' ");
                        $cart_product_with_cat_id[$list['id']] = $cat_info['category_id'];
                    }
            }
        }

        $cart_product_with_cat_id = array_unique($cart_product_with_cat_id);


        $coupon_categories = explode(",", $coupon_categories);


        $cat_prd_id = array();

        foreach ($cart_product_with_cat_id as $key => $value) {
            if(in_array($value, $coupon_categories)) {
                $cat_prd_id[$value] = $key;
            }
        }

       if(count($cat_prd_id)==0) 
        {
            $cat_prd_id[] = 0 ;
        }
        
        return $cat_prd_id;
    }

	// Remove Cart Itenms

	function removeCartItem($input='')
	{	

		$user_id 	= @$_SESSION['user_session_id'];

		if(@$_SESSION['user_session_id'])
		{
			$del_condition = "user_id='".$_SESSION['user_session_id']."'";

		} else {
			$del_condition = "cart_id='".$_SESSION['user_cart_id']."'";

		}
		
		$product_id 	= $this->decryptData($input['result']);
		$variant_id 	= $input['variant'];
		$variant_check 	= ($variant_id!=0) ? "AND variant_id='".$variant_id."'" : "" ;
		$curr  			= date("Y-m-d H:i:s");
		$info 			= $this->getDetails(CART_ITEM_TBL,"id","product_id='".$product_id."' AND user_id='".$user_id."' $variant_check ");	
		if ($info) {
			$q 	= "DELETE FROM ".CART_ITEM_TBL." WHERE product_id = '".$product_id."' AND user_id='".$user_id."' $variant_check ";
			$exe = $this->exeQuery($q);
			$check 	= $this->check_query(CART_ITEM_TBL,"user_id","$del_condition");
			$get_cart = $this->cartInfo();
			if($check==true){
					$final_total = $this->sumOfCartDetails();
					$cart_id = $_SESSION['user_cart_id'];
					$updated_cart = "SELECT COUNT(cart_id) as total_items,SUM(final_price) as total FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."'";
					$exe 		= $this->exeQuery($updated_cart);
					$list 		= mysqli_fetch_assoc($exe);

					$qu = "UPDATE ".CART_TBL." SET 
					sub_total		            = '".$this->sumOfCartSubtotalDetails()."',
					sgst_amt		            = '".$this->sumOfCartSGSTAmt()."',
					cgst_amt 		            = '".$this->sumOfCartCGSTAmt()."',
					igst_amt 		            = '".$this->sumOfCartIGSTAmt()."',
					total_tax		            = '".$this->sumOfCartIGSTAmt()."',
	                vendor_commission           = '".$this->sumOfVendorCommission()."',
	                vendor_commission_tax       = '".$this->sumOfVendorCommissionTax()."',
	                vendor_payment_charge       = '".$this->sumOfVendorPaymentCharge()."',
	                vendor_payment_tax          = '".$this->sumOfVendorPaymentTax()."',
	                vendor_shipping_charge      = '".$this->sumOfVendorShippingCharge()."',
	                vendor_shipping_tax         = '".$this->sumOfVendorShippingTax()."',
	                vendor_commission_amt       = '".$this->sumOfVendorCommissionAmt()."',
	                vendor_commission_tax_amt   = '".$this->sumOfVendorCommissionTaxAmt()."',
	                vendor_payment_charge_amt   = '".$this->sumOfVendor_paymentChargeAmt()."',
	                vendor_payment_tax_amt      = '".$this->sumOfVendor_paymentTaxAmt()."', 
	                vendor_shipping_charge_amt  = '".$this->sumOfVendorShippingChargeAmt()."',
	                vendor_shipping_tax_amt     = '".$this->sumOfVendorShippingTaxAmt()."',
					total_amount                = '".$final_total."',
					coupon_value 	            = '',
					coupon_id 		            = '',
					coupon_code 	            = '',
					coupon_type 	            = '',
					coupon_status 	            = '',
					updated_at 		= '".$curr."' WHERE id='".$_SESSION['user_cart_id']."' ";
					$result 	= $this->exeQuery($qu);

				return "1"."`Item removed from the cart!"."`".$get_cart['cart_layout']."`".$get_cart['cart']['total_items']."";
			} else {
				return "1"."`Your cart is empty!"."`".$get_cart['cart_layout']."`".$get_cart['cart']['total_items']."";
			}
		}else{
			return "0"."`Error Occurred";
		}
	}

	// Add To Wishlist

	function addToWishList($input='')
	{	
		$user_id 	= @$_SESSION['user_session_id'];
		$product_id = $this->decryptData($input['product_id']);
		$curr  		= date("Y-m-d H:i:s");


		$condition  = "";

		if ($input['variant_id']!="") {
			$condition = "AND variant_id='".$input['variant_id']."' ";
		}


		$info 	= $this->getDetails(WISHLIST_TBL,"id,fav_status","product_id='".$product_id."' AND user_id='".$user_id."' ".$condition."  ");	
			if($user_id) {
				if (isset($info['fav_status'])) {
					$q 	= "DELETE FROM ".WISHLIST_TBL." WHERE product_id = '".$product_id."' AND user_id='".$user_id."' ".$condition." ";
					$exe = $this->exeQuery($q);
					return "0"."`Removed from wishlist!";
				}else{
					$q = "INSERT INTO ".WISHLIST_TBL." SET 
						user_id 			= '".$user_id."',	
						product_id	 		= '".$product_id."',	
						variant_id	 		= '".$input['variant_id']."',	
						fav_status 			= '1',
						status 				= '1',
						created_at 			= '".$curr."',
						updated_at 			= '".$curr."'  ";
					$exe 	= $this->exeQuery($q);
					$check 	= $this->check_query(WISHLIST_TBL,"id","'".$_SESSION['user_session_id']."' ");
					return "1`".$check;
				}
			} else {
				return "2"."` Please Login to add product to wish list";
			}
	}

	/*----------------------------------------------
			 Product Review management
	---------------------------------------------*/

	// Add Product Review

	function addProductReview($input)
	{	
		$user_id 	= @$_SESSION['user_session_id'];
		$product_id = $this->decryptData($input['add_review_product_id']);
		$order_id 	= $this->decryptData($input['add_review_order_id']);
		$data 		= $this->cleanStringData($input);
		$check 		= $this ->check_query(REVIEW_TBL,"id"," product_id ='".$product_id."' AND order_id='".$order_id."' AND added_by = '".$user_id."'  ");

		if ($user_id) {
			$cus_info = $this->getDetails(CUSTOMER_TBL,"id,name,email","id='".$user_id."'");
			if ($check==0) {
				$curr 			= date("Y-m-d H:i:s");
				$query = "INSERT INTO ".REVIEW_TBL." SET 
							product_id 		= '".$product_id."',
							order_id 		= '".$order_id."',
							name 			= '".$cus_info['name']."',
							email 			= '".$cus_info['email']."',
							comment 		= '".$this->unHyphenize($data['comment'])."',
							star_ratings 	= '".$data['star_ratings']."',
							status			= '1',
							added_by 		= '".$user_id."',	
							created_at 		= '".$curr."',
							updated_at 		= '".$curr."' ";
				$exe 	= $this->exeQuery($query);
				if($exe){
					 return 1;
				}else{
					 return "0"."`Sorry!! Unexpected Error Occurred. Please try again.!";
				}	
			}else{
				return"0"."`Sorry!! Unexpected Error Occurred. Please try again.!";
			}
		} else {
			return"0"."`Please login for review product";
		}
	}

	// Edit Product Reivew

	function editProductReview($input)
	{	
		$user_id 	= @$_SESSION['user_session_id'];
		$review_id  = $this->decryptData($input['edit_review_id']);
		$data 		= $this->cleanStringData($input);
		$check 		= $this ->check_query(REVIEW_TBL,"id"," id='".$review_id."' ");
			if ($user_id) {
				$cus_info = $this->getDetails(CUSTOMER_TBL,"id,name,email","id='".$user_id."' AND status='1' ");
				if ($check) {
					$curr 			= date("Y-m-d H:i:s");
					$query = "UPDATE ".REVIEW_TBL." SET 
								comment 		= '".$this->unHyphenize($data['comment'])."',
								star_ratings    = '".$data['star_ratings']."',
								approval_status = '0',
								updated_at 		= '".$curr."' 
								WHERE id='".$review_id."' ";
					$exe 	= $this->exeQuery($query);
					if($exe){
						 return 1;
					}else{
						 return "0"."`Sorry!! Unexpected Error Occurred. Please try again.!";
					}	
				}else{
					return"0"."`Sorry!! Unexpected Error Occurred. Please try again.!";
				}
			} else {
				return"0"."`Please login for review product";
			}
	}

	function getVendorRattingInfo($input)
	{	
		$layout      = "";
		$product_id  = $this->decryptData($input['product_id']);
		$order_id  	 = $this->decryptData($input['order_id']);
		$review_info = $this->reviewAndRatingConditionCheck($product_id);

		$i = 0;
		foreach ($review_info['rating_pending_venodr_ids'][$order_id] as $key => $value) {

			$vendor_info = $this->getDetails(VENDOR_TBL,"*","id='".$value."'");
			
			$layout .= "<div class='row'>
	                      <h4 class='form-label modal-title'> Vendor Details</h4>
	                        <div class='col-md-12'>
	                            <div class='row'><div class='col-md-3'><span class='span_tag'>Vendor</span></div><div class='col-md-9'>: <span class='info_tag' id='vendor_company'>".$vendor_info['company']."</span></div></div>
	                            <div class='row'><div class='col-md-3'><span class='span_tag'>Location</span></div><div class='col-md-9'>: <span class='info_tag' id='venodr_location'>".$vendor_info['city']."</span></div></div>
	                        </div>
	                    </div>
	                    <div class='row'>
	                        <div class='col-lg-12'>
	                                <div class='product_rating mt-10'>
	                                    <h4>Your rating</h4>
	                                    <span class='star-rating_".$i." vendor_star_rating_change'></span>
	                                   <input type='hidden' name='star_ratings[]' class='vendor_rating_".$i."' value='5' id='vendor_rating_".$i."'>
	                                </div>
	                        </div>
	                    </div>
	                    <script type='text/javascript'>
		                    $('.star-rating_".$i."').starRating({
		                        totalStars: 5,
		                        starShape: 'rounded',
		                        starSize: 20,
		                        emptyColor: '#ddd',
		                        hoverColor: '#ffc107',
		                        activeColor: '#ffc107',
		                        initialRating: 5,
		                        useGradient: false,
		                        useFullStars: true,
		                        disableAfterRate: false,
		                        onHover: function(currentIndex, currentRating, $el) {
		                            //$('.live-rating').text(currentIndex);
		                        },
		                        onLeave: function(currentIndex, currentRating, $el) {
		                            //$('.live-rating').text(currentRating);
		                        },
		                        callback: function(currentRating, $el) {
		                            //alert('rated '+currentRating);
		                            $('#vendor_rating_".$i."').val(currentRating);
		                        }
		                    });
	                    </script>
	                    ";
            $i++;			

            if(count($review_info['rating_pending_venodr_ids'][$order_id]) > $i) {
            	$layout .="<hr>";
            }			
			
		}

		$result 		        = array();
		$result['layout']       = $layout;
		$result['vendor_count'] = $i;

		return json_encode($result);
	}

	function getVendorRattingInfoFroEdit($input)
	{	
		$layout      = "";
		$product_id  = $this->decryptData($input['product_id']);
		$order_id  	 = $this->decryptData($input['order_id']);
		
		$query       = "SELECT * FROM ".VENDOR_RATTING_TBL." WHERE product_id='".$product_id."' AND order_id='".$order_id."' AND added_by='".@$_SESSION['user_session_id']."' ";
		$exe 		 = $this->exeQuery($query);
		$total_count = mysqli_num_rows($exe);
 		if(mysqli_num_rows($exe) > 0) {
			$i = 0;
			while ($list = mysqli_fetch_assoc($exe)) {
				$vendor_info = $this->getDetails(VENDOR_TBL,"*","id='".$list['vendor_id']."'");

				$layout .= "<div class='row'>
		                      <h4 class='form-label modal-title'> Vendor Details</h4>
		                        <div class='col-md-12'>
		                            <div class='row'><div class='col-md-3'><span class='span_tag'>Vendor</span></div><div class='col-md-9'>: <span class='info_tag' id='vendor_company'>".$vendor_info['company']."</span></div></div>
		                            <div class='row'><div class='col-md-3'><span class='span_tag'>Location</span></div><div class='col-md-9'>: <span class='info_tag' id='venodr_location'>".$vendor_info['city']."</span></div></div>
		                        </div>
		                    </div>
		                    <div class='row'>
		                        <div class='col-lg-12'>
		                                <div class='product_rating mt-10'>
		                                    <h4>Your rating</h4>
		                                    <span class='star-rating_".$i." vendor_star_rating_change'></span>
		                                   <input type='hidden' name='star_ratings[".$list['vendor_id']."][]' class='vendor_rating_".$i."' value='".$list['star_ratings']."' id='vendor_rating_".$i."'>
		                                </div>
		                        </div>
		                    </div>
		                    <script type='text/javascript'>
			                    $('.star-rating_".$i."').starRating({
			                        totalStars: 5,
			                        starShape: 'rounded',
			                        starSize: 20,
			                        emptyColor: '#ddd',
			                        hoverColor: '#ffc107',
			                        activeColor: '#ffc107',
			                        initialRating: ".$list['star_ratings'].",
			                        useGradient: false,
			                        useFullStars: true,
			                        disableAfterRate: false,
			                        onHover: function(currentIndex, currentRating, $el) {
			                            //$('.live-rating').text(currentIndex);
			                        },
			                        onLeave: function(currentIndex, currentRating, $el) {
			                            //$('.live-rating').text(currentRating);
			                        },
			                        callback: function(currentRating, $el) {
			                            //alert('rated '+currentRating);

			                        	if(currentRating) {
			                        		var currentRating = currentRating;
			                        	} else {
			                        		var currentRating = ".$list['star_ratings'].";
			                        	}

			                            $('#vendor_rating_".$i."').val(currentRating);
			                        }
			                    });
		                    </script>
		                    ";

	            if($total_count < $i) {
	            	$layout .="<hr>";
				}
				$i++;
			}		
			
		}

		$result 		        = array();
		$result['layout']       = $layout;
		$result['vendor_count'] = $i;
		return json_encode($result);
	}

	function addMultiVendorRatting($input)
	{	
		$product_id  = $this->decryptData($input['product_id']);
		$order_id    = $this->decryptData($input['order_id']);
		$review_info = $this->reviewAndRatingConditionCheck($product_id);
		$user_id 	 = @$_SESSION['user_session_id'];

		foreach ($review_info['rating_pending_venodr_ids'][$order_id] as $key => $value) {
		
			$check 		= $this ->check_query(VENDOR_RATTING_TBL,"id"," vendor_id='".$value."' AND order_id='".$order_id."' AND product_id ='".$product_id."' AND added_by = '".$user_id."'  ");
			if ($user_id) {
				$cus_info = $this->getDetails(CUSTOMER_TBL,"id,name,email","id='".$user_id."'");
				if ($check==0) {
					$curr 			= date("Y-m-d H:i:s");
					$query = "INSERT INTO ".VENDOR_RATTING_TBL." SET 
								vendor_id 		= '".$value."',
								product_id 		= '".$product_id."',
								order_id 		= '".$order_id."',
								name 			= '".$cus_info['name']."',
								email 			= '".$cus_info['email']."',
								star_ratings 	= '".$input['star_ratings'][$key]."',
								status			= '1',
								added_by 		= '".$user_id."',	
								created_at 		= '".$curr."',
								updated_at 		= '".$curr."' ";
					$exe   = $this->exeQuery($query);
					if(!$exe){
						return "0"."`Sorry!! Unexpected Error Occurred. Please try again.!";
					}	
				}else{
					return"0"."`You have already rated for this product with same vendor";
				}
			} else {
				return"0"."`Please login for review product";
			}
		}

		return 1;
		
	}

	function editMultiVendorRatting($input)
	{	
		$product_id  = $this->decryptData($input['product_id']);
		$order_id    = $this->decryptData($input['order_id']);

		foreach ($input['star_ratings'] as $key => $value) {
		
			$check 		= $this ->check_query(VENDOR_RATTING_TBL,"id"," vendor_id='".$key."' AND product_id ='".$product_id."' AND added_by = '".$_SESSION['user_session_id']."'  ");
			if (@$_SESSION['user_session_id']) {
				$cus_info = $this->getDetails(CUSTOMER_TBL,"id,name,email","id='".$_SESSION['user_session_id']."'");
				if ($check) {
					$curr  = date("Y-m-d H:i:s");
					$query = "UPDATE ".VENDOR_RATTING_TBL." SET 
								star_ratings 	= '".$input['star_ratings'][$key][0]."',
								updated_at 		= '".$curr."' 
								WHERE product_id='".$product_id."' AND vendor_id='".$key."' AND added_by='".$_SESSION['user_session_id']."' ";
					$exe   = $this->exeQuery($query);
				}else{
					return"0"."`You have already rated for this product with same vendor";
				}
			} else {
				return"0"."`Please login for review product";
			}
		}

		return 1;
	}

/*-----------Dont'delete---------*/
}?>