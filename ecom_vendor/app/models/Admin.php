<?php
	require_once 'Model.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class Admin extends Model
	{

		//--------Migrations -------------//

		// INR Formate

		function inrFormat($num) {
			$value = number_format((float)$num, 2, '.', '');
			return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $value);
		}

		function inrFormatFields($data) {
			$response = array();
				foreach ($data as $key => $value) {
					$response[$key] = $this->inrFormat($value);
				}
			return $response;
		}

		// Update Vendor Active Status

	  	function UpdateVendorStatus($data)
	  	{	
	  		$curr 	= date("Y-m-d H:i:s");
	  		$id 	= $_SESSION["ecom_vendor_id"];
	  		$q = "UPDATE ".VENDOR_TBL." SET active_status = '".$data."' WHERE id='".$id."'";
			$exe = $this->exeQuery($q);
			$active_vendor = $this->getDetails(VENDOR_TBL,"active_status","id='".$id."'");
			if($active_vendor['active_status'] == 1){
				return 1;
			}else{
				return 0;
			}
	  	}


	  	// Check Vendor Active Status

	  	function CheckVendorStatus()
	  	{	
	  		$layout = '';
	  		$vendor_id 	   = $_SESSION["ecom_vendor_id"];
			$active_vendor = $this->getDetails(VENDOR_TBL,"active_status","id='".$vendor_id."'");
			$status = (($active_vendor['active_status']==1) ? "Active" : "Inactive"); 
		    $status_c = (($active_vendor['active_status']==1) ? "checked" : " ");

            $layout .= "<div class='custom-control custom-switch'>
	                        <input type='checkbox' class='custom-control-input' id='vendor_active_switch' name='vendor_active_switch' $status_c >
	                        <label class='custom-control-label fw-bold' for='vendor_active_switch' id='vendor_active_switch_text'>$status</label>
	                    </div>"; 
	        return $layout;
	  	}

		//  Migration List

		function migrationList()
		{
			$layout = "";
			$q = "SELECT * FROM ".MIGRATION_TBL." WHERE 1 ";
			$exe = $this->exeQuery($q);	
		    if(mysqli_num_rows($exe) > 0){
		    	$i=1;
		    	while($rows = mysqli_fetch_array($exe)){
		    		$list 	= $this->editPagePublish($rows);
		    		$layout .= "
		    			<tr class='nk-tb-item'>
                            <td class='nk-tb-col'>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$list['name']."</span>
                            </td>
                            <td class='nk-tb-col tb-col-mb'>
                                 ".$list['type']."
                            </td>
                            <td class='nk-tb-col tb-col-mb'>
                                 ".$list['sql_query']."
                            </td>
                           	<td class='nk-tb-col tb-col-mb'>
                                 ".$list['remarks']."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                ".date("d/m/Y", strtotime($list['created_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm' ><em class='icon ni ni-upload-cloud'></em></button>
                            </td>
                        </tr>";
		    		$i++;
		    	}
		    } 
		    return $layout;
		}

		// Product List

		function manageProducts($product_for,$id) 
		{
			$check ="";

			if($product_for=="category") {
				$check = "AND P.main_category_id='".$id."' ";
			} elseif ($product_for=="subcategory") {
				$check = "AND P.sub_category_id='".$id."' ";
			}
			$vendor_id 	= $_SESSION["ecom_vendor_id"];

			$layout = "";
	     	$q = "SELECT P.id,P.product_name,P.actual_price,P.selling_price,P.category_type, P.sku, P.main_category_id,P.sub_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,P.has_variants, P.stock, T.tax_class as taxClass , M.category, S.subcategory, VP.id as vendor_assigned_id, VP.selling_price as vendor_selling_price, VP.stock as vendor_stock, VP.status as vendor_product_status,

	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image, 
	    		(SELECT count(id) as total_count FROM ".PRODUCT_VARIANTS." WHERE product_id=P.id AND delete_status=0 ) as variant_count,
	    		(SELECT sum(stock) as total_stock FROM ".PRODUCT_VARIANTS." WHERE product_id=P.id AND delete_status=0 ) as variant_stock

	    	  FROM ".PRODUCT_TBL." P 
	    	  LEFT JOIN  ".VENDOR_PRODUCTS_TBL." VP ON (vendor_id='".$vendor_id."' AND VP.product_id=P.id AND VP.has_variants=0)
	    	  LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id)
	    	  LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=P.main_category_id)
	    	  LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=P.sub_category_id)
	    	  WHERE P.delete_status='0' $check AND P.status='1' AND P.delete_status='0' AND P.is_draft='0'  ORDER BY P.id DESC" ;

 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($rows = mysqli_fetch_array($exe)){
 		    		$list = $this->editPagePublish($rows);
		    
				
					// Product Image
					if ($list['default_product_image']!="") {
						$product_image = $list['default_product_image']!='' ? ADMIN_UPLOADS.$list['default_product_image'] : ASSETS_PATH."no_img.jpg" ;# code...
					}else{
						$product_image = $list['product_image']!='' ? ADMIN_UPLOADS.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
					}


					$selling_price  = $list['vendor_assigned_id']!="" ? $list['vendor_selling_price']  : $list['selling_price'];
					$actual_price = $list['actual_price'];


					$stock =  $list['vendor_assigned_id']!="" ? $list['vendor_stock']  : 0;
					
					$product_name = $this->publishContent($list['product_name']);
					
					if ($list['vendor_assigned_id']!="") {
						$checked_available = $list['vendor_product_status']==1 ? 'checked' : "";
                    	$text_available = $list['vendor_product_status']==1 ? 'Available' : "Not available";
						$status = "<div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input '  value='yes'  id='status_".$i."' name='product[".$i."][available_status]' $checked_available >
	                                    <label class='custom-control-label ' for='status_".$i."'>$text_available</label>
	                                </div>";
					}else{
						$status = "<div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input '  value='1'  id='status_".$i."' name='product[".$i."][status]' checked >
	                                    <label class='custom-control-label ' for='status_".$i."'></label>
	                                </div>";
					}

					if ($list['has_variants']==0) {
						$layout .= "
		    				<tr class='nk-tb-item '>
		    					<td>
	                                ".$i."
	                                <input type='hidden' name='product[".$i."][product_id]' value='".$list['id']."' >
	                                <input type='hidden' name='product[".$i."][variant_id]' value='0' >
	                                <input type='hidden' name='product[".$i."][main_category_id]' value='".$list['main_category_id']."' >
	                                <input type='hidden' name='product[".$i."][sub_category_id]' value='".$list['sub_category_id']."' >
	                                <input type='hidden' name='product[".$i."][vendor_assigned_id]' value='".$list['vendor_assigned_id']."' >
	                            </td>
	                            <td>
	                                <img src='".$product_image."' width=50 />
	                            </td>
	                            <td >
	                                <span class='text-primary'>".$product_name."</span>
		                        </td>
	                            <td >
	                                ".$this->inrFormat($actual_price)."
		                        </td>
		                        <td >
	                               <input type='number' min='0' max='".$actual_price."' value='".$selling_price."'  class='form-control' name='product[".$i."][selling_price]' required >
		                        </td>
		                        <td >
	                               <input type='number' min='0' max='1000' value='".$stock."'  class='form-control' name='product[".$i."][stock]' required >
		                        </td>
		                        <td >
		                            $status
		                        </td>
		                        
	                        </tr>";
					}else{
						$layout .= $this->getVariantsofProduct($list['id'],$i);
					}
		    		
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}

		// Get the variants for the product

		function getVariantsofProduct($product_id,$j){
			$layout = "";
			$vendor_id 	= $_SESSION["ecom_vendor_id"];

	  		$q = "SELECT V.id as p_variant_id, P.product_name, P.main_category_id,P.sub_category_id, P.has_variants, V.variant_name, V.selling_price, V.actual_price, V.sku,  VP.id as vendor_assigned_id, VP.selling_price as vendor_selling_price, VP.stock as vendor_stock, VP.status as vendor_product_status,
	  			(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image 
	  			FROM ".PRODUCT_VARIANTS." V
	  			LEFT  JOIN  ".VENDOR_PRODUCTS_TBL." VP ON (vendor_id='".$vendor_id."' AND VP.product_id=$product_id AND variant_id=V.id)
	  			LEFT JOIN ".PRODUCT_TBL." P ON (P.id=$product_id)
	  			WHERE V.product_id= '".$product_id."' ORDER BY V.id ASC ";

	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($rows = mysqli_fetch_array($query)){
		    		$list = $this->editPagePublish($rows);

		    		// Product Image
					if ($list['default_product_image']!="") {
						$product_image = $list['default_product_image']!='' ? ADMIN_UPLOADS.$list['default_product_image'] : ASSETS_PATH."no_img.jpg" ;# code...
					}else{
						$product_image = $list['product_image']!='' ? ADMIN_UPLOADS.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
					}
		    		
					$selling_price  = $list['vendor_assigned_id']!="" ? $list['vendor_selling_price']  : $list['selling_price'];
					$actual_price = $list['actual_price'];

					$stock =  $list['vendor_assigned_id']!="" ? $list['vendor_stock']  : 0;
					$product_name = ($list['has_variants']==1)? $this->publishContent($list['product_name'])." [ ".$this->publishContent($list['variant_name'])." ]" : $this->publishContent($list['product_name']);

					

                    if ($list['vendor_assigned_id']!="") {
                    	$checked_available = $list['vendor_product_status']==1 ? 'checked' : "";
                    	$text_available = $list['vendor_product_status']==1 ? 'Available' : "Not available";

						$status = "<div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input '  value='1'  id='status_".$j.$i."' name='product[".$j.$i."][available_status]' $checked_available>
	                                    <label class='custom-control-label ' for='status_".$j.$i."'> $text_available</label>
	                                </div>";
					}else{
						$status = "<div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input '  value='1'  id='status_".$j.$i."' name='product[".$j.$i."][status]' checked>
	                                    <label class='custom-control-label ' for='status_".$j.$i."'></label>
	                                </div>";
					}

		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td>
	                                ".$j.".".$i."
	                                <input type='hidden' name='product[".$j.$i."][product_id]' value='".$product_id."' >
	                                <input type='hidden' name='product[".$j.$i."][variant_id]' value='".$list['p_variant_id']."' >
 									<input type='hidden' name='product[".$j.$i."][main_category_id]' value='".$list['main_category_id']."' >
	                                <input type='hidden' name='product[".$j.$i."][sub_category_id]' value='".$list['sub_category_id']."' >
	                                <input type='hidden' name='product[".$j.$i."][vendor_assigned_id]' value='".$list['vendor_assigned_id']."' >
	                            </td>
	                            <td>
	                                <img src='".$product_image."' width=50 />
	                            </td>
	                            <td >
	                                <span class='text-primary'>".$product_name."</span>
		                        </td>
	                            <td >
	                                ".$this->inrFormat($actual_price)."
		                        </td>
		                        <td >
	                                <input type='number' min='0' max='".$actual_price."'  value='".$selling_price."'  class='form-control' name='product[".$j.$i."][selling_price]' required >
		                        </td>
		                        <td >
	                               <input type='number' min='0' max='1000' value='".$stock."' class='form-control' name='product[".$j.$i."][stock]' required>
		                        </td>
		                        <td >
		                            $status
		                        </td>
		                        
	                        </tr>";
	                $i++;
		    	}
		    }
		    return $layout;
		}

		
		// Get the variants for the product

		function getProductInventoryList($product_id,$page,$item_per_page) {
			$layout = "";
			$vendor_id 	= $_SESSION["ecom_vendor_id"];

			$start_from = ($page-1)*$item_per_page;
  			$page_count = $item_per_page;

			$total_stock = array();

			$product_details = $this->getDetails(PRODUCT_TBL,"has_variants","id='".$product_id."'");

			if($product_details['has_variants']==1) {
				$q = "SELECT P.id,P.has_variants,P.actual_price as product_actual_price ,P.product_name,P.selling_price,P.category_type, P.main_category_id,P.sub_category_id,VP.id as vendor_assigned_id,VP.product_id,VP.variant_id,VP.sub_category_id,VP.main_category_id,VP.selling_price as vendor_selling_price,VP.stock as vendor_stock,VP.status as vendor_product_status,VP.max_qty,VP.min_qty,V.id as variantId ,V.variant_name,V.actual_price,V.selling_price as product_selling_price ,V.sku,M.category,S.subcategory,
	  					(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status='0' ORDER BY id ASC LIMIT 1) as product_image, 
	    				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status='0' AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image 
	  			  FROM ".PRODUCT_VARIANTS." V
	  			  	LEFT JOIN ".PRODUCT_TBL." P ON (V.product_id=P.id) 
	  				LEFT JOIN ".VENDOR_PRODUCTS_TBL." VP ON (P.id=VP.product_id AND VP.vendor_id='".$vendor_id."' AND V.id=VP.variant_id )
	  				LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=P.main_category_id)
	    	    	LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=P.sub_category_id)
	  			  WHERE P.id='".$product_id."'  ORDER BY P.id ASC LIMIT $start_from, $page_count ";
  			} else {
  				$q = "SELECT P.id,P.has_variants,P.actual_price as product_actual_price ,P.product_name,P.selling_price as product_selling_price,P.category_type, P.main_category_id,P.sub_category_id,VP.id as vendor_assigned_id,VP.product_id,VP.variant_id,VP.sub_category_id,VP.main_category_id,VP.selling_price as vendor_selling_price,VP.max_qty,VP.min_qty,VP.stock as vendor_stock,VP.status as vendor_product_status,V.id as variantId ,V.variant_name,V.actual_price ,V.sku,M.category,S.subcategory,
  				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status='0' ORDER BY id ASC LIMIT 1) as product_image, 
    			(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status='0' AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image 
  			    FROM ".PRODUCT_TBL." P
  				    LEFT JOIN ".VENDOR_PRODUCTS_TBL." VP ON (P.id=VP.product_id AND VP.vendor_id='".$vendor_id."')
  			  	    LEFT JOIN ".PRODUCT_VARIANTS." V ON (V.id=VP.variant_id) 
  				    LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=P.main_category_id)
    	    	    LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=P.sub_category_id)
  			    WHERE P.id='".$product_id."'  ORDER BY P.id ASC LIMIT $start_from, $page_count ";
  			}

	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0) {
		    	while($rows = mysqli_fetch_array($query)){
		    		$list = $this->editPagePublish($rows);

		    		// Product Image
					if ($list['default_product_image']!="") {
						$product_image =(( $list['default_product_image']!='')?  IMAGEKIT_SRC.$list['default_product_image']   : ASSETS_PATH."no_img.jpg" );
					}else{
						$product_image = (($list['product_image']!='')?  IMAGEKIT_SRC.$list['product_image']  : ASSETS_PATH."no_img.jpg" );
					}
		    		
					$category_name = (($list['category_type']=="sub") ? $list['subcategory'] : $list['category'] );
					
					$selling_price  = $list['vendor_assigned_id']!="" ? $list['vendor_selling_price']  : $list['product_selling_price'];

					$stock =  $list['vendor_assigned_id']!="" ? $list['vendor_stock']  : 0;
					$total_stock[] = $stock;

					$product_name = ($list['has_variants']==1)? $this->publishContent($list['product_name'])." [ ".$this->publishContent($list['variant_name'])." ]" : $this->publishContent($list['product_name']);

	               if ($list['vendor_assigned_id']!="") {
                    	$text_available = $list['vendor_product_status']==1 ? 'Available' : "Not available";
					} else {
                    	$text_available = "Not available";
					}

					if($list['vendor_product_status']==1) {
						$status = "<div class='team-status bg-success text-white' data-toggle='tooltip' data-placement='top' title='$text_available'><em class='icon ni ni-check-thick'></em></div>";
					} else {
						$status = "<div class='team-status bg-danger text-white' data-toggle='tooltip' data-placement='top' title='Not available'><em class='icon ni ni-na'></em></div>";
					}

					if($list['has_variants']==1) {
						$actual_price = $list['actual_price'];
					} else {
						$actual_price = $list['product_actual_price'];
					}

					if($list['vendor_assigned_id']!="") {
						$sell_price = $this->inrFormat($selling_price);
						$stock = $stock;
					} else {
						$sell_price = "No Stock";
						$stock = "No Stock";
					}

					if($list['has_variants']==0) {

						$layout .= "
	    				<div class='col-sm-6 col-lg-4 col-xxl-3'>
						    <div class='card card-bordered'>
						        <div class='card-inner'>
						            <div class='team'>
						                ".$status."
						                <div class='user-card user-card-s2'>
						                    <div class='user-avatar md rounded-0'>
						                        <img src='".$product_image."' class='rounded-0'  alt='' >
						                    </div>
						                    <div class='user-info'>
						                        <h6>$category_name</h6>
						                    </div>
						                </div>
						                <div class='team-details'>
						                    <p>$product_name</p>
						                </div>
						                <ul class='team-statistics'>
						                    <li><span>₹ ".$this->inrFormat($actual_price)."</span><span>Actual Price</span></li>
						                    <li><span>₹ ".$sell_price."</span><span>Selling Price</span></li>
						                    <li><span>".$stock."</span><span>Quantity</span></li>
						                </ul>
						                <ul class='team-statistics'>
						                    <li><span>".(($list['min_qty'])? $list['min_qty'] : 0)."</span><span>Minimun Order Quantity</span></li>
						                    <li><span>".(($list['max_qty'])? $list['max_qty'] : 0)."</span><span>Maximum Order Quantity</span></li>
						                </ul>
						                <div class='team-view'>
						                    <a  class='btn btn-round btn-outline-light w-150px edit_inventory_details' type='button' data-variant='' data-option='".$list['id']."' ><span>Edit Details</span></a>
						                </div>
						            </div>
						        </div>
						    </div>
						</div>";
					} else {
						$layout .= "
	    				<div class='col-sm-6 col-lg-4 col-xxl-3'>
						    <div class='card card-bordered'>
						        <div class='card-inner'>
						            <div class='team'>
						                ".$status."
						                <div class='user-card user-card-s2'>
						                    <div class='user-avatar md rounded-0'>
						                        <img src='".$product_image."' class='rounded-0'  alt='' >
						                    </div>
						                    <div class='user-info'>
						                        <h6>$category_name</h6>
						                    </div>
						                </div>
						                <div class='team-details'>
						                    <p>$product_name</p>
						                </div>
						                <ul class='team-statistics'>
						                    <li><span>₹ ".$this->inrFormat($actual_price)."</span><span>Actual Price</span></li>
						                    <li><span>₹ ".$sell_price."</span><span>Selling Price</span></li>
						                    <li><span>".$stock."</span><span>Quantity</span></li>
						                </ul>
						                <ul class='team-statistics'>
						                    <li><span>".$list['min_qty']."</span><span>Minimun Order Quantity</span></li>
						                    <li><span>".$list['max_qty']."</span><span>Maximum Order Quantity</span></li>
						                </ul>
						                <div class='team-view'>
						                    <a  class='btn btn-round btn-outline-light w-150px edit_inventory_details' type='button' data-option='".$list['id']."' data-variant='".$list['variantId']."'  ><span>Edit Details</span></a>
						                </div>
						            </div>
						        </div>
						    </div>
						</div>";
					}
					

					
		    	}
		    }
		    $result = array();
 		    $result['layout'] 	   = $layout;
 		    $result['total_stock'] = array_sum($total_stock);
 		    return $result;
		}

		// Inventory Pages Count 

		function inventoryPaginationCount($product_id="",$item_per_page)
		{
			$layout ="";

			$vendor_id 	= $_SESSION["ecom_vendor_id"];

			$variant_count 	   = array();
			$non_variant_count = array();

			$num_rec_per_page = $item_per_page;

			$q = "SELECT VP.id as vendor_assigned_id,VP.product_id,VP.variant_id,VP.sub_category_id,VP.main_category_id,VP.selling_price as vendor_selling_price,VP.stock as vendor_stock,VP.status as vendor_product_status,V.variant_name,V.actual_price ,V.sku,P.has_variants,P.actual_price as product_actual_price ,P.product_name,P.category_type,M.category,S.subcategory,
	  				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status='0' ORDER BY id ASC LIMIT 1) as product_image, 
	    			(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status='0' AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image 
	  			  FROM ".VENDOR_PRODUCTS_TBL." VP
	  			  	LEFT JOIN ".PRODUCT_VARIANTS." V ON (VP.variant_id=V.id) 
	  				LEFT JOIN ".PRODUCT_TBL." P ON (P.id=VP.product_id)
	  				LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=VP.main_category_id)
	    	    	LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=VP.sub_category_id)
	  			  WHERE VP.product_id='".$product_id."' GROUP BY V.id ASC  ";

 		    $exe = $this->exeQuery($q);

			$total_records = mysqli_num_rows($exe);  //count number of records
			$total_pages = ceil($total_records / $num_rec_per_page);
			return $total_records;
		}

		// Inventory list page pagination

		function manageInventoryPagination($current="",$page_link,$product_id,$item_per_page)
		{
			$layout ="";
			
			$num_rec_per_page = $item_per_page;

			$vendor_id 	= $_SESSION["ecom_vendor_id"];

			$sql = "SELECT VP.id as vendor_assigned_id,VP.product_id,VP.variant_id,VP.sub_category_id,VP.main_category_id,VP.selling_price as vendor_selling_price,VP.stock as vendor_stock,VP.status as vendor_product_status,V.variant_name,V.actual_price ,V.sku,P.has_variants,P.actual_price as product_actual_price ,P.product_name,P.category_type,M.category,S.subcategory,
	  				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status='0' ORDER BY id ASC LIMIT 1) as product_image, 
	    			(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status='0' AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image 
	  			  FROM ".VENDOR_PRODUCTS_TBL." VP
	  			  	LEFT JOIN ".PRODUCT_VARIANTS." V ON (VP.variant_id=V.id) 
	  				LEFT JOIN ".PRODUCT_TBL." P ON (P.id=VP.product_id)
	  				LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=VP.main_category_id)
	    	    	LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=VP.sub_category_id)
	  			  WHERE VP.product_id='".$product_id."' GROUP BY V.id ASC  ";
			$rs_result = $this->exeQuery($sql); //run the query
			$total_records = mysqli_num_rows($rs_result);  //count number of records
			$total_pages = ceil($total_records / $num_rec_per_page);
			$page = $current;
			$limit= 5;

			if ($total_pages >=1 && $page <= $total_pages){
				$counter = 1;
				$link = "";
				$dot_link = "<li class='page-item'><span class='page-link'><em class='icon ni ni-more-h'></em></span></li>";
				if ($page > ($limit/2)){
					$layout .= "<li class='page-item'><a class='page-link' href='".$page_link."?p=1'>1</a></li>".$dot_link ;
				}
				for ($i=$page; $i<=$total_pages;$i++){
					$current_page = (($i==$current) ? "active" : "");
		            if($counter < $limit)
		            	$layout .= "<li class='page-item ".$current_page."'><a class='page-link' href=".$page_link."?p=".$i.">".$i."</a></li>";
		            $counter++;
		        }
		        if ($page < $total_pages - ($limit/2)){
		        	$current_page = (($i==$current) ? "active" : "");
		        	$layout .= $dot_link."<li class='page-item ".$current_page."'><a class='page-link' href='".$page_link."?p=".$total_pages."'>".$total_pages."</a></li>"; 
		        }
			}

			$result = array();
			$result['layout']	  = $layout;
			$result['total_page'] = $total_pages;
			return $result;
		}

		// Get product Inventory Info 

		function getProductInventoryinfo($data) 
		{	
			$layout = "";

			$vendor_id 	= $_SESSION["ecom_vendor_id"];

			$p_info = $this->getDetails(PRODUCT_TBL,"has_variants","id='".$data['item_id']."'");

			if($p_info['has_variants']==1) {

				$q = "SELECT P.id,P.has_variants,P.actual_price,P.product_name,P.selling_price ,P.category_type, P.main_category_id,P.sub_category_id,VP.id as vendor_assigned_id,VP.product_id,VP.variant_id,VP.sub_category_id,VP.main_category_id,VP.selling_price as vendor_selling_price,VP.stock as vendor_stock,VP.status as vendor_product_status,V.id as variantId ,V.variant_name,V.actual_price,V.selling_price as product_selling_price,VP.max_qty,VP.min_qty,V.sku,M.category,S.subcategory
	  			  FROM ".PRODUCT_VARIANTS." V
	  			  	LEFT JOIN ".PRODUCT_TBL." P ON (V.product_id=P.id) 
	  				LEFT JOIN ".VENDOR_PRODUCTS_TBL." VP ON (P.id=VP.product_id AND VP.vendor_id='".$vendor_id."' AND V.id=VP.variant_id )
	  				LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=P.main_category_id)
	    	    	LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=P.sub_category_id)
	  			  WHERE P.id='".$data['item_id']."' AND V.id='".$data['variant_id']."'  GROUP BY P.id ASC";
			} else {
				$q = "SELECT P.id,P.product_name,P.actual_price,P.selling_price as product_selling_price,P.category_type, P.sku, P.main_category_id,P.sub_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,P.has_variants, P.stock, T.tax_class as taxClass , M.category, S.subcategory, VP.id as vendor_assigned_id, VP.selling_price as vendor_selling_price,VP.max_qty,VP.min_qty, VP.stock as vendor_stock, VP.status as vendor_product_status
		    	FROM ".PRODUCT_TBL." P 
		    	  	LEFT JOIN  ".VENDOR_PRODUCTS_TBL." VP ON (vendor_id='".$vendor_id."' AND VP.product_id=P.id )
		    	  	LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id)
		    	  	LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=P.main_category_id)
		    		LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=P.sub_category_id)
		    	WHERE P.delete_status='0' AND P.id='".$data['item_id']."' AND P.status='1' AND P.delete_status='0' AND P.is_draft='0'  ORDER BY P.id DESC" ;
			}

	    	$exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	while($rows = mysqli_fetch_array($exe)){
 		    		$list = $this->editPagePublish($rows);

					$selling_price  = $list['vendor_assigned_id']!="" ? $list['vendor_selling_price']  : $list['product_selling_price'];

					$actual_price   = $list['actual_price'];
					$stock 			= $list['vendor_assigned_id']!="" ? $list['vendor_stock']  : 0;
					$product_name   = $this->publishContent($list['product_name']);
					
					if ($list['vendor_assigned_id']!="") {
						$checked_available = $list['vendor_product_status']==1 ? 'checked' : "";
						$available_badge   = $list['vendor_product_status']==1 ? "Available" : "Not Available";;
					} else {
						$checked_available = "";
						$available_badge   = "Not Available";
					}

					if ($data['variant_id']==0) {
						$variant_id = 0;
					} else {
						$variant_id = $data['variant_id'];
					}


					$layout .= "    <input type='hidden' name='product_id' value='".$list['id']."' >
	                                <input type='hidden' name='variant_id' value='".$variant_id."' >
	                                <input type='hidden' name='main_category_id' value='".$list['main_category_id']."' >
	                                <input type='hidden' name='sub_category_id' value='".$list['sub_category_id']."' >
	                                <input type='hidden' name='vendor_assigned_id' value='".$list['vendor_assigned_id']."' >

			                        <div class='form-group'>
			                            <label class='form-label' for=''>Selling Price <em>*</em></label>
			                            <div class='form-control-wrap'>
			                                <input type='text' class='form-control' min='0' max='".$actual_price."' value='".$selling_price."' name='selling_price' required>
			                            </div>
			                        </div>
			                        <div class='form-group'>
			                            <label class='form-label' for=''>Quantity <em>*</em></label>
			                            <div class='form-control-wrap'>
			                                <input type='text' class='form-control' min='1' value='".$stock."' name='stock' required>
			                            </div>
			                        </div>
			                         <div class='form-group'>
			                            <label class='form-label' for=''>Minimum Order Quantity <em>*</em></label>
			                            <div class='form-control-wrap'>
			                                <input type='number' class='form-control'  name='min_qty' id='min_qty' value='".$list['min_qty']."' placeholder='Minimum Order Quantity'>
			                            </div>
			                        </div>
			                         <div class='form-group'>
			                            <label class='form-label' for=''>Maximum Order Quantity <em>*</em></label>
			                            <div class='form-control-wrap'>
			                                <input type='number' class='form-control'  name='max_qty' id='max_qty' value='".$list['max_qty']."' placeholder='Maximum Order Quantity'>
			                            </div>
			                        </div>
			                        <div class='form-group'>
			                            <label class='form-label'>Stock Availability</label>
			                            <ul class='custom-control-group g-3 align-center'>
			                                <li>
			                                    <div class='custom-control custom-control-sm custom-checkbox'$checked_available>
			                                        <input type='checkbox' class='custom-control-input' name='available_status' id='stockAvailability' $checked_available>
			                                        <label class='custom-control-label stock_availability_label' for='stockAvailability'>".$available_badge."</label>
			                                    </div>
			                                </li>
			                            </ul>
			                        </div>
			                        <div class='form-group'>
			                            <button type='submit' class='btn btn-lg btn-primary'>Save Informations</button>
			                        </div>
			                    ";
		    	}
 		    }
            return $layout;
		}

		// Update Product inventory

	  	function updateProductInventoryinfo($data)
	  	{	
	  		$curr 	        = date("Y-m-d H:i:s");
	  		$vendor_id 	    = $_SESSION["ecom_vendor_id"];
			$stock 			= (($data['stock']=='') ? 0 : $data['stock']);
			$selling_price 	= (($data['selling_price']=='') ? 0 : $data['selling_price']);
			$has_variants 	= $data['variant_id']!=0 ? 1 : 0;

			if ($data['vendor_assigned_id']!="") { 

				$status = isset($data['available_status']) ? 1 : 0;
			 	$q = "UPDATE ".VENDOR_PRODUCTS_TBL." SET  
					stock			= '".$stock."',
					selling_price 	= '".$selling_price."',
					min_qty 		= '".$data['min_qty']."',
					max_qty 		= '".$data['max_qty']."',
					status 			= '".$status."', 
					updated_at 		= '".$curr."' WHERE id='".$data['vendor_assigned_id']."'
				";
				$exe = $this->exeQuery($q);
			}else{
				// if (isset($data['status'])) {
				 	$q = "INSERT INTO ".VENDOR_PRODUCTS_TBL." SET
						vendor_id 		  = '".$vendor_id."',
						product_id		  = '".$data['product_id']."',
						variant_id		  = '".$data['variant_id']."',
						main_category_id  = '".$data['main_category_id']."',
						sub_category_id   = '".$data['sub_category_id']."',
						has_variants 	  = '".$has_variants."',
						stock			  = '".$stock."',
						selling_price 	  = '".$selling_price."',
						min_qty 		  = '".$data['min_qty']."',
						max_qty 		  = '".$data['max_qty']."',
						status 			  = '1',
						created_at 		  = '".$curr."',
						updated_at 		  = '".$curr."'
					";
					$exe = $this->exeQuery($q);
				// }
			}
			return 1;
	  	}

		
		
		// Get Permission Main Menu

		function permissionDataMaster()
	  	{
	  		$layout = "";
		    $q = "SELECT * FROM ".MAIN_CATEGORY_TBL." WHERE 1 AND delete_status='0' AND status='1' ORDER BY id ASC" ;
		    $query  = $this->exeQuery($q);
		    $vendor_categories = $this->getVendorCategories();
		    $vendor_sub_categories = array();
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)){
		    		$list    = $this->editPagePublish($row);
		    		$checked = (in_array($list['id'], $vendor_categories)) ? 'checked' :'';
		    		$layout .= "
		    			<div class='permission_parent'>
                    		<label class='checkbox-inline checkbox-success heading'>
                       		<input name='category[]' value='".$list['id']."' data-option='".$list['id']."' class='post_permission main_permission' id='main_".$list['id']."' type='checkbox' $checked > ".$list['category']." </label>
                        	<span class='clearfix'></span>
                        	<div class='menu permission_menu'>
                        		<ul>
                        			".$this->getSubMenuPermission($list['id'])."
                        		</ul>
                       		</div>
                        </div>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	function getVendorCategories()
	  	{	
		    $ids 	= array();
			$id 	= $_SESSION["ecom_vendor_id"];
	  		$q 		= "SELECT main_category_id FROM ".VENDOR_MAIN_CATEGORY_TBL." WHERE vendor_id='".$id."' ORDER BY id ASC" ;
		    $query  = $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)) {
		    		$list  = $this->editPagePublish($row);
		    		$ids[] = $list['main_category_id'];
		    	}
		    }
		    return $ids;
	  	}


	  	// Get Sub menu permission

	  	function getSubMenuPermission($main_id)
	  	{	
	  		$layout = "";
		    $q = "SELECT id,page_url,subcategory FROM ".SUB_CATEGORY_TBL." WHERE status='1' AND category_id='".$main_id."' AND is_draft='0' AND delete_status='0' " ;
		    $query 	= $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)){
		    		$list 	 = $this->editPagePublish($row);
		    		$vendor_sub_categories = $this->getVendorSubCategories();
		    		$checked = (in_array($list['id'], $vendor_sub_categories)) ? 'checked' :'';
		    		$layout .= "
		    			<li>
		    			 <label class='checkbox-inline checkbox-success sub_menu'>
		    			 <input name='subcategory[]' value='".$list['id']."' data-option='".$main_id."' class='post_permission  sub_menu_permission sub_permission_".$main_id."' type='checkbox' $checked>  ".$list['subcategory']."</label>
		    			 </li>
		    		";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	function getVendorSubCategories()
	  	{	
		    $ids 	= array();
			$id 	= $_SESSION["ecom_vendor_id"];
	  		$q 		= "SELECT sub_category_id FROM ".VENDOR_SUB_CATEGORY_TBL." WHERE vendor_id='".$id."' ORDER BY id ASC" ;
		    $query  = $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)) {
		    		$list  = $this->editPagePublish($row);
		    		$ids[] = $list['sub_category_id'];
		    		$i++;
		    	}
		    }
		    return $ids;
	  	}


	  	/*---------------------------------------
				  Assign Vendor products
	  	----------------------------------------*/

	  	// Select Category

	  	function assignCategory($input) 
	  	{
			$validate_csrf  = $this->validateCSRF($input);
			if ($validate_csrf=="success") {
				$vendor_id 		= $_SESSION["ecom_vendor_id"];
				$curr 			= date("Y-m-d H:i:s");
				if(isset($input['category'])) {
					$query ="DELETE FROM ".VENDOR_MAIN_CATEGORY_TBL." WHERE vendor_id='".$vendor_id."'";
					$exe 	= $this->exeQuery($query);
					$query ="DELETE FROM ".VENDOR_SUB_CATEGORY_TBL." WHERE vendor_id='".$vendor_id."'";
					$exe 	= $this->exeQuery($query);

					foreach ($input['category'] as $key => $value) {
						$query = "INSERT INTO ".VENDOR_MAIN_CATEGORY_TBL." SET 
							vendor_id	 		= '".$vendor_id."',
							main_category_id 	= '".$value."',
							status				= '1',
							created_at 			= '".$curr."',
							updated_at 			= '".$curr."' ";
						$exe 	= $this->exeQuery($query);
					}

					if(isset($input['subcategory'])) {
						foreach ($input['subcategory'] as $key => $value) {
							$sc_info = $this->getDetails(SUB_CATEGORY_TBL,"category_id","id='".$value."'");
							$query = "INSERT INTO ".VENDOR_SUB_CATEGORY_TBL." SET 
								vendor_id	 		= '".$vendor_id."',
								main_category_id 	= '".$sc_info['category_id']."',
								sub_category_id 	= '".$value."',
								status				= '1',
								created_at 			= '".$curr."',
								updated_at 			= '".$curr."' ";
							$exe 	= $this->exeQuery($query);
						}
					}

				} else {
					return "Please select at least one category or sub-category";
				}
	  		}else{
				return $validate_csrf;
			}
			return 1;
	  	}

	  
	  	function getCategoryProducts()
	  	{	

	  		$layout = "";
	  		$vendor_categories = $this->getVendorCategories();
	  		if(count($vendor_categories)==0) 
			{
				$vendor_categories[] = 0;
			}
			$check = $this->getDetails(MAIN_CATEGORY_TBL,"id","id IN (" . implode(',', array_map('intval',$vendor_categories)) . ")");
			if($check!=0) {
			    $q = "SELECT * FROM ".MAIN_CATEGORY_TBL." WHERE  id IN (" . implode(',', array_map('intval',$vendor_categories)) . ")   ORDER BY id ASC" ;
			    $query  = $this->exeQuery($q);
			    if(mysqli_num_rows($query)>0){
			    	$i=1;
			    	while($row = mysqli_fetch_array($query)){
			    		$list    	= $this->editPagePublish($row);
			    		$total_stock_data = (($this->getTotalCategoryStock($list['id'])===NULL) ? NULL :  (($this->getTotalCategoryStock($list['id'])===0) ? 0 : $this->getTotalCategoryStock($list['id']) )) ;
			    		$get_vendor_stock = (($this->getVendorCategoryStocks($list['id'])===NULL) ? NULL :  (($this->getVendorCategoryStocks($list['id'])===0) ? 0 : $this->getVendorCategoryStocks($list['id']) ));


			    		if($get_vendor_stock===NULL) {
			    			$get_vendor_stock == "need_to_add";
			    		} else {
			    			$get_vendor_stock == $get_vendor_stock;
			    		}

			    		if($get_vendor_stock < 20 && $get_vendor_stock !=0) {
			    			$stock_msg = "<strong class='text-warning'> Low Stock </strong>";
			    			$st_text_colr = "text-warning";
			    		} elseif ($get_vendor_stock === NULL) {
			    			$stock_msg = "<strong class='text-warning'>Need to add stock</strong>";
			    			$st_text_colr = "text-warning";
			    		} elseif($get_vendor_stock == 0) {
			    			$stock_msg = "<strong class='text-danger'>Out Of Stock </strong>";
			    			$st_text_colr = "text-danger";
			    		} else {
			    			$stock_msg = "<strong class='text-success'>Instock </strong>";
			    			$st_text_colr = "text-success";
			    		}

			    		if($get_vendor_stock === NULL) {
			    			$stock_status	  =  " $stock_msg";
			    		} else {
			    			$stock_status	  =  " $stock_msg : <span class='".$st_text_colr."'>".$get_vendor_stock."</span>";
			    		}

			    		$layout .= "
			    			<div class='catalogue_item'>
			    				<div class='main_category'>
			    					<h6 >".$list['category']." </h6>
			    					<p>$stock_status</p>
			    					<h2 class='add_icon'><a class='btn btn-outline-success btn-sm' href='".COREPATH."products/category/".$list['page_url']."' data-toggle='tooltip' data-placement='top' title='Add products'><em class='icon ni ni-plus' ></em></a></h2>
			    				</div>
				    			<ul class='sub_category'>
		                       		".$this->getSubCategoryProducts($list['id'])."
		                        </ul>
	                        </div>";
		                $i++;
			    	}
			    }


			    
			}
		    return $layout;
	  	}

	  	function getVendorCategoryStocks($category_id)
	  	{	
	  		$result 	= array();
	  		$vendor_id 	= $_SESSION["ecom_vendor_id"];
	  		$q1  = "SELECT SUM(stock) as cat_stock FROM ".VENDOR_PRODUCTS_TBL." WHERE vendor_id=".$vendor_id." AND main_category_id=".$category_id."   " ;
	  		$exe1  			 = $this->exeQuery($q1);
	  		$list1 			 = mysqli_fetch_assoc($exe1);
	  		$ids     = $this->getSubCategoryIds($category_id);
	  		if(count($ids)==0)
	  		{
	  			$ids[] ="-1";
	  		}
	  		$q2  = "SELECT SUM(stock) as sub_cat_stock FROM ".VENDOR_PRODUCTS_TBL." WHERE vendor_id=".$vendor_id." AND sub_category_id IN (" . implode(',', array_map('intval',$ids)) . ")    " ;
  			$exe2  			 = $this->exeQuery($q2);
	  		$list2 			 = mysqli_fetch_assoc($exe2);
	  		$result 		 = $list1['cat_stock'] ;

	  		return $result;
	  		  
	  	}

	  	function getVendorSubCategoriesAndProduct()
	  	{	
		    $ids 	= array();
			$id 	= $_SESSION["ecom_vendor_id"];
	  		$q 		= "SELECT sub_category_id FROM ".VENDOR_SUB_CATEGORY_TBL." WHERE vendor_id='".$id."' ORDER BY id ASC" ;
		    $query  = $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)) {
		    		$list  = $this->editPagePublish($row);
		    		$ids[] = $list['sub_category_id'];
		    		$i++;
		    	}
		    }
		    return $ids;
	  	}

	  	function getSubCategoryProducts($category_id)
	  	{	

	  		$layout = "";
	  		$vendor_sub_categories = $this->getVendorSubCategoriesAndProduct();
	  		if(count($vendor_sub_categories)==0)
	  		{
	  			$vendor_sub_categories[] =0;
	  		}
		    $q  = "SELECT * FROM ".SUB_CATEGORY_TBL." WHERE id IN (" . implode(',', array_map('intval',$vendor_sub_categories)) . ") AND category_id='".$category_id."'  ORDER BY id ASC" ;
		    $query                 = $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)){
		    		$list    	= $this->editPagePublish($row);
		    		$total_stock_data = (($this->getTotalSubCategoryStock($list['id'])===NULL) ? NULL : (($this->getTotalSubCategoryStock($list['id'])===NULL) ? 0 : $this->getTotalSubCategoryStock($list['id'])) ) ;
		    		$get_vendor_stock = (($this->getVendorSubCategoryStocks($list['id'])===NULL) ? NULL : (($this->getVendorSubCategoryStocks($list['id'])===NULL) ? 0 : $this->getVendorSubCategoryStocks($list['id'])));
		    		
		    		if($get_vendor_stock===NULL) {
		    			$get_vendor_stock == "need_to_add";
		    		} else {
		    			$get_vendor_stock == $get_vendor_stock;
		    		}

		    		if($get_vendor_stock < 20 && $get_vendor_stock !=0) {
		    			$stock_msg = "<strong class='text-warning'> Low Stock </strong>";
		    			$st_text_colr = "text-warning";
		    		} elseif ($get_vendor_stock === NULL) {
		    			$stock_msg = "<strong class='text-warning'>Need to add stock</strong>";
		    			$st_text_colr = "text-warning";
		    		} elseif($get_vendor_stock == 0) {
		    			$stock_msg = "<strong class='text-danger'>Out Of Stock </strong>";
		    			$st_text_colr = "text-danger";
		    		} else {
		    			$stock_msg = "<strong class='text-success'>Instock </strong>";
		    			$st_text_colr = "text-success";
		    		}

		    		if($get_vendor_stock === NULL) {
		    			$stock_status	  =  " $stock_msg";
		    		} else {
		    			$stock_status	  =  " $stock_msg : <span class='".$st_text_colr."'>".$get_vendor_stock."</span>";
		    		}

		    		if($total_stock_data!=0 || $total_stock_data!=NULL) {
		    		
		    		$layout .= "
                       		<li>
                       			<h6>".$list['subcategory']." </h6>
                       			<p>$stock_status</p>
                       			<h2  class='add_icon'><a class='btn btn-outline-success btn-sm' href='".COREPATH."products/subcategory/".$list['page_url']."' data-toggle='tooltip' data-placement='top' title='Add products' ><em class='icon ni ni-plus subcategory_product' ></em></a></h2>
                       		</li>
		    			";
		    		}
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	
	  	function getVendorSubCategoryStocks($category_id)
	  	{	
	  		$result 	= array();
	  		$vendor_id 	= $_SESSION["ecom_vendor_id"];
	  		$q  = "SELECT SUM(stock) FROM ".VENDOR_PRODUCTS_TBL." WHERE sub_category_id='".$category_id."' AND vendor_id=".$vendor_id."  " ;
  			$exe  			 = $this->exeQuery($q);
	  		$list 			 = mysqli_fetch_assoc($exe);
	  		$result 		 = $list['SUM(stock)'];
	  		return $result;
	  		  
	  	}

	  	function getTotalCategoryStock($category_id)
	  	{	
	  		$result = array();
	  		$q1  = "SELECT SUM(stock) FROM ".PRODUCT_TBL." WHERE main_category_id=".$category_id."  " ;
	  		$exe1  	 = $this->exeQuery($q1);
	  		$list1 	 = mysqli_fetch_assoc($exe1);
	  		$ids     = $this->getSubCategoryIds($category_id);
	  		if(count($ids)==0)
	  		{
	  			$ids[] ="-1";
	  		}
	  		$q2  = "SELECT SUM(stock) FROM ".PRODUCT_TBL." WHERE sub_category_id IN (" . implode(',', array_map('intval',$ids)) . ")  " ;
  			$exe2  			 = $this->exeQuery($q2);
	  		$list2 			 = mysqli_fetch_assoc($exe2);
	  		$result 		 = $list1['SUM(stock)']; //+ $list2['SUM(stock)'];
	  		return $result;
	  		  
	  	}

	  	function getTotalSubCategoryStock($category_id)
	  	{	
	  		$result = array();
	  		$q  = "SELECT SUM(stock) FROM ".PRODUCT_TBL." WHERE sub_category_id='".$category_id."'  " ;
  			$exe  			 = $this->exeQuery($q);
	  		$list 			 = mysqli_fetch_assoc($exe);
	  		$result 		 = $list['SUM(stock)'];
	  		return $result;
	  		  
	  	}


	  	function getSubCategoryIds($category_id)
	  	{	
		    $ids 	= array();
	  		$q 		= "SELECT id FROM ".SUB_CATEGORY_TBL." WHERE category_id='".$category_id."'" ;
		    $query  = $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)) {
		    		$list  = $this->editPagePublish($row);
		    		$ids[] = $list['id'];
		    		$i++;
		    	}
		    	if(count($ids)==0)
		    	{
		    		$ids[] ="-1";
		    	}
		    }
		    return $ids;
	  	}
	  	

	  	// Save Assigned products

	  	function assignProducts($data){
	  		$product_array = $data['product'];
	  		$curr 	= date("Y-m-d H:i:s");
	  		$vendor_id 	= $_SESSION["ecom_vendor_id"];
			foreach ($product_array as $key => $value) {

				$stock 			= (($value['stock']=='') ? 0 : $value['stock']);
				$selling_price 	= (($value['selling_price']=='') ? 0 : $value['selling_price']);
				$has_variants 	= $value['variant_id']!=0 ? 1 : 0;

				if ($value['vendor_assigned_id']!="") { 

					$status = isset($value['available_status']) ? 1 : 0;
				 	$q = "UPDATE ".VENDOR_PRODUCTS_TBL." SET  
						stock			= '".$stock."',
						selling_price 	= '".$selling_price."',
						status 			= '".$status."', 
						updated_at 		= '".$curr."' WHERE id='".$value['vendor_assigned_id']."'
					";
					$exe = $this->exeQuery($q);
				}else{
					if (isset($value['status'])) {
					 	$q = "INSERT INTO ".VENDOR_PRODUCTS_TBL." SET
							vendor_id 		  = '".$vendor_id."',
							product_id		  = '".$value['product_id']."',
							variant_id		  = '".$value['variant_id']."',
							main_category_id  = '".$value['main_category_id']."',
							sub_category_id   = '".$value['sub_category_id']."',
							has_variants 	  = '".$has_variants."',
							stock			  = '".$stock."',
							selling_price 	  = '".$selling_price."',
							status 			  = '1',
							created_at 		  = '".$curr."',
							updated_at 		  = '".$curr."'
						";
						$exe = $this->exeQuery($q);
					}
				}
			}
			return 1;
	  	}

	  	// Inventory Product List

		function  manageInventory() 
		{
			$check ="";
			$vendor_id 	= $_SESSION["ecom_vendor_id"];
			$layout = "";
	    	$q = "SELECT  P.id,P.product_name,P.actual_price,P.selling_price,P.category_type, P.sku, P.main_category_id,P.sub_category_id,P.tax_class,P.delete_status,P.is_draft, P.stock, T.tax_class as taxClass , M.category, S.subcategory,VP.id as vendor_assigned_id,VP.has_variants, VP.selling_price as vendor_selling_price,VP.variant_id,VP.status, VP.stock as vendor_stock, VP.status as vendor_product_status,PV.variant_name,PV.selling_price as veriant_selling_price,
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image, 
	    		(SELECT count(id) as total_count FROM ".PRODUCT_VARIANTS." WHERE product_id=P.id AND delete_status=0 ) as variant_count,
	    		(SELECT sum(stock) as total_stock FROM ".PRODUCT_VARIANTS." WHERE product_id=P.id AND delete_status=0 ) as variant_stock
	    	  FROM ".VENDOR_PRODUCTS_TBL." VP LEFT JOIN  ".PRODUCT_TBL." P ON (P.id=VP.product_id)
	    	  	LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id)
	    	  	LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=P.main_category_id)
	    	  	LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=P.sub_category_id)
	    	  	LEFT JOIN ".PRODUCT_VARIANTS." PV ON (PV.id=VP.variant_id)
	    	  WHERE VP.vendor_id='".$vendor_id."' AND VP.selling_price!='0' ORDER BY VP.product_id DESC" ;

 		    $exe = $this->exeQuery($q);
 		     if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	$j=1;
 		    	while($rows = mysqli_fetch_array($exe)){
 		    		$list = $this->editPagePublish($rows);
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");


					if ($list['status']==1) {
						$preview = "<li class='nk-tb-action-hidden'>
                                        <a href='javascript:void();' class='btn btn-trigger btn-icon' data-toggle='tooltip' data-placement='top' title='View On Website'>
                                            <em class='icon ni ni-eye-fill'></em>
                                        </a>
                                    </li>";
					}else{
						$preview = "";
					}

					// Category
					$category_name = (($list['category_type']=="sub") ? $list['subcategory'] : $list['category'] );

					// Product Image
					if ($list['default_product_image']!="") {
						$product_image = $list['default_product_image']!='' ? UPLOADS.$list['default_product_image'] : ASSETS_PATH."no_img.jpg" ;# code...
					}else{
						$product_image = $list['product_image']!='' ? UPLOADS.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
					}

					$product_name = ($list['has_variants']==1)? $this->publishContent($list['product_name'])." [ ".$this->publishContent($list['variant_name'])." ]" : $this->publishContent($list['product_name']);

					// Stock
					$stock = $list['has_variants']==1 ? $list['variant_stock']." in stock for  ".$list['variant_count']." variants" : $list['stock']." in stock ";

		    		$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                <img src='".$product_image."' width=50 />
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$product_name."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
                                ".$this->publishContent($category_name)."
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                               ".$list['actual_price']."
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                               ".$list['vendor_selling_price']."
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                               ".$list['vendor_stock']." in stock
	                        </td>
	                       
                            <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                	".$preview."
                                    <li class='nk-tb-action-hidden'>
                                        <a class='btn btn-trigger btn-icon' data-toggle='tooltip' data-placement='top' title='Update Stock' href='".COREPATH."products/edit/".$list['id']."'  ><em class='icon ni ni-pen-fill'></em></a>
                                    </li>
                                    <li>
                                        <div class='drodown'>
                                            <a href='#' class='dropdown-toggle btn btn-icon btn-trigger' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a>
                                            
                                        </div>
                                    </li>
                                </ul>
                            </td>
                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}

		function getVendorProductsuniqe()
	  	{	
		    $ids 	= array();
			$id 	= $_SESSION["ecom_vendor_id"];
	  		$q 		= "SELECT product_id FROM ".VENDOR_PRODUCTS_TBL." WHERE vendor_id='".$id."' ORDER BY id ASC" ;
		    $query  = $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)) {
		    		$list  = $this->editPagePublish($row);
		    		$ids[] = $list['product_id'];
		    	}
		    }
		    return $ids;
	  	}

		// Inventory Product List design
		function  manageInventoryList($list_for) 
		{
			$vendor_id 	= $_SESSION["ecom_vendor_id"];
			$unique     = array_unique($this->getVendorProductsuniqe());
			if(count($unique)==0) 
			{
				$unique[] = 0;
			}

			if($list_for=="instock") 
			{
				$stock_check = "AND VP.stock >= VP.max_qty ";
			} elseif ($list_for=="lowstock") {
				$stock_check = "AND VP.stock < VP.max_qty  AND VP.stock > VP.min_qty AND VP.stock != 0  OR VP.stock=VP.min_qty";
			} elseif ($list_for=="outofstock") {
				$stock_check = "AND VP.stock < VP.min_qty ";
			} else {
				$stock_check = "" ;
			}

			$sort_condition = "ORDER BY VP.stock >= VP.max_qty DESC, VP.stock < VP.max_qty  AND VP.stock > VP.min_qty AND (VP.stock != 0  OR VP.stock=VP.min_qty) DESC, VP.stock < VP.min_qty DESC";

			$layout = "";

	    	$q = "SELECT  P.id,P.product_name,P.has_variants,VP.selling_price as vendor_sell_price,VP.max_qty,VP.min_qty,P.actual_price,P.selling_price,P.category_type, P.sku, P.main_category_id,P.sub_category_id,P.tax_class,P.delete_status,P.is_draft, P.stock,P.min_order_qty,P.max_order_qty,T.tax_class as taxClass , M.id as mainCatId, M.category,S.id as subCatId, S.subcategory,VP.stock as vendorStock,VP.id as vendor_assigned_id,
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image,
	    		(SELECT SUM(stock) FROM ".VENDOR_PRODUCTS_TBL." WHERE product_id=P.id AND vendor_id='".$vendor_id."') as t_stock_in_tis_prd 
	    	  FROM ".PRODUCT_TBL." P LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id)
	    	  	LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=P.main_category_id)
	    	  	LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=P.sub_category_id)
	    	  	LEFT JOIN ".VENDOR_PRODUCTS_TBL." VP ON (P.id=VP.product_id AND vendor_id='".$vendor_id."')
	    	  WHERE P.is_draft='0' AND P.delete_status='0' AND P.status='1' $stock_check GROUP BY P.id DESC $sort_condition " ;

 		    $exe = $this->exeQuery($q);
 		     if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	$j=1;
 		    	while($rows = mysqli_fetch_array($exe)){
		    		$list = $this->editPagePublish($rows);

					// Category
					$category_name = (($list['category_type']=="sub") ? $list['subcategory'] : $list['category'] );

					// Product Image
					if ($list['default_product_image']!="") {
						$product_image = $list['default_product_image']!='' ? UPLOADS.$list['default_product_image'] : ASSETS_PATH."no_img.jpg" ;# code...
					}else{
						$product_image = $list['product_image']!='' ? UPLOADS.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
					}

					$has_variants = ($list['has_variants']!=0)? 1 : 0 ;

					if($list['vendorStock'] >= $list['max_qty'])	{
						$stock_sts      =  "Instock";
						$stock_sts_clr  =  "text-success";
					} elseif ($list['vendorStock'] < $list['max_qty'] && $list['vendorStock'] > $list['min_qty'] && $list_for!="outofstock"  ) {
						$stock_sts      =  "Low Stock";
						$stock_sts_clr  =  "text-warning";
					} elseif ($list['vendorStock'] < $list['min_qty']) {
						$stock_sts      =  "Out Of Stock";
						$stock_sts_clr  =  "text-danger";
					} elseif ($list['vendorStock'] == $list['min_qty']) {
						$stock_sts      =  "Low Stock";
						$stock_sts_clr  =  "text-warning";
					}

					if($list['vendor_assigned_id']=="") {

					$layout .= "
	    				<tr class='nk-tb-item open_inventory_stock' data-option='".$list['id']."'>
	    					<td class='nk-tb-col'>
	                            ".$i."
	                        </td>
	                        <td class='nk-tb-col'>
	                            <img src='".$product_image."' width=50 />
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <span class='text-primary'>".$list['product_name']."</span>
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            ".$this->publishContent($category_name)."
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
	                           ₹ ".$this->inrFormat($list['actual_price'])."
	                        </td>
	                       <td class='nk-tb-col tb-col-md'>
	                           New Stock
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
	                           No Stock
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
	                           <span class='badge badge-dot text-warning cursor_pointer'>Need To Add Stock</span>
	                        </td>
	                      
	                    </tr> ";


					} else {
					
					$layout .= "
	    				<tr class='nk-tb-item open_inventory_stock' data-option='".$list['id']."'>
	    					<td class='nk-tb-col'>
	                            ".$i."
	                        </td>
	                        <td class='nk-tb-col'>
	                            <img src='".$product_image."' width=50 />
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <span class='text-primary'>".$list['product_name']."</span>
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            ".$this->publishContent($category_name)."
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
	                           ₹ ".$this->inrFormat($list['actual_price'])."
	                        </td>
	                       <td class='nk-tb-col tb-col-md'>
	                           ₹ ".$this->inrFormat($list['vendor_sell_price'])."
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
	                           ".$list['t_stock_in_tis_prd']." in stock
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
	                           <span class='badge badge-dot $stock_sts_clr cursor_pointer'>$stock_sts</span>
	                        </td>
	                      
	                    </tr> ";

					}

               		
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}


		// Inventory Product Variant List design

		function getInventoryVariants($product_id,$vendor_id,$list_for,$table_id)
	  	{	
	  		if($list_for=="instock") 
			 {
			 	$stock_check = "AND VP.stock > 20";
			 } elseif ($list_for=="lowstock") {
			 	$stock_check = "AND VP.stock < 20 AND VP.stock != 0";
			 } elseif ($list_for=="outofstock") {
			 	$stock_check = "AND VP.stock = 0";
			 } else {
			 	$stock_check = "";
			 }


			$layout = "";
	    	$q = "SELECT  P.id,P.product_name,P.actual_price,P.selling_price,P.category_type, P.sku, P.main_category_id,P.sub_category_id,P.tax_class,P.delete_status,P.is_draft, P.stock, T.tax_class as taxClass ,M.id as mainCatId, M.category,S.id as subCatId, S.subcategory,VP.id as vendor_assigned_id,VP.has_variants, VP.selling_price as vendor_selling_price,VP.variant_id,VP.status, VP.stock as vendor_stock, VP.status as vendor_product_status,PV.variant_name,PV.selling_price as veriant_selling_price,
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image, 
	    		(SELECT count(id) as total_count FROM ".PRODUCT_VARIANTS." WHERE product_id=P.id AND delete_status=0 ) as variant_count,
	    		(SELECT sum(stock) as total_stock FROM ".PRODUCT_VARIANTS." WHERE product_id=P.id AND delete_status=0 ) as variant_stock

	    	  FROM ".VENDOR_PRODUCTS_TBL." VP LEFT JOIN  ".PRODUCT_TBL." P ON (P.id=VP.product_id)
	    	  LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id)
	    	  LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=P.main_category_id)
	    	  LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=P.sub_category_id)
	    	  LEFT JOIN ".PRODUCT_VARIANTS." PV ON (PV.id=VP.variant_id)
	    	  WHERE VP.vendor_id='".$vendor_id."' AND VP.selling_price!='0' AND P.id='".$product_id."' ".$stock_check." ORDER BY VP.product_id ASC" ;


 		    $exe = $this->exeQuery($q);
 		     if(mysqli_num_rows($exe)>0){
 		    	$i=$table_id;
 		    	$j=1;
 		    	while($rows = mysqli_fetch_array($exe)){
 		    		$list = $this->editPagePublish($rows);
					// Category
					$category_name = (($list['category_type']=="sub") ? $list['subcategory'] : $list['category'] );

					// Product Image
					if ($list['default_product_image']!="") {
						$product_image = $list['default_product_image']!='' ? UPLOADS.$list['default_product_image'] : ASSETS_PATH."no_img.jpg" ;# code...
					}else{
						$product_image = $list['product_image']!='' ? UPLOADS.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
					}

					$product_name = ($list['has_variants']==1)? $this->publishContent($list['product_name'])." [ ".$this->publishContent($list['variant_name'])." ]" : $this->publishContent($list['product_name']);

					// Stock
					$stock = $list['has_variants']==1 ? $list['vendor_stock']." in stock for  ".$list['vendor_stock']." variants" : $list['stock']." in stock ";
					$stock_sts      = ($list['vendor_stock']!=0)? (($list['vendor_stock'] < 20)? "Low Stock" : "Instock" ) : "Out Of Stock";
					$stock_sts_clr  = ($list['vendor_stock']!=0)? (($list['vendor_stock'] < 20)? "text-warning" : "text-success" ) : "text-danger";

		    		$layout .= "
	    				<tr class='nk-tb-item open_inventory_stock' data-option='".$list['id']."'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                <img src='".$product_image."' width=50 />
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$product_name."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
                                ".$this->publishContent($category_name)."
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                               ₹ ".$this->inrFormat($list['actual_price'])."
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                               ₹ ".$this->inrFormat($list['vendor_selling_price'])."
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                               ".$list['vendor_stock']." in stock
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                               <span class='badge badge-dot $stock_sts_clr cursor_pointer'>$stock_sts</span>
	                        </td>
	                    
                        </tr>";
		    		$i++;
		    	}
 		    }

 		    $result = array();
 		    $result['layout'] = $layout;
 		    $result['table_id'] = $i;

 		    return $result;
	  	}

		// Change Inventory Product  Status

		function changeProductStatus($data)
		{
			$data 		= $this->decryptData($data);
			$vendor_id 	= $_SESSION["ecom_vendor_id"];
			$info = $this -> getDetails(VENDOR_PRODUCTS_TBL,"status"," product_id ='".$data."' AND vendor_id='".$vendor_id."' ");
			if($info['status'] ==1){
				$query = "UPDATE ".VENDOR_PRODUCTS_TBL." SET status='0' WHERE product_id ='".$data."' AND vendor_id='".$vendor_id."' ";
			}else{
				$query = "UPDATE ".VENDOR_PRODUCTS_TBL." SET status='1' WHERE product_id ='".$data."' AND vendor_id='".$vendor_id."' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}
	
	/*--------------------------------------------
		 		Manage Order List
	---------------------------------------------*/

	function manageOrdersList()
	{
		$layout 	="";
		$vendor_id 	= $_SESSION["ecom_vendor_id"];

		$query  = "SELECT VO.id,VO.vendor_id,VO.user_id,VO.order_id,VO.order_uid,VO.sub_total,VO.total_tax,VO.sgst_amt,VO.cgst_amt,VO.igst_amt,VO.vendor_payment_total,VO.vendor_commission_total,VO.vendor_shipping_total,VO.total_amount,VO.order_status,VO.delivery_date,VO.deliver_status,VO.shipping_status,VO.cancel_status,VO.return_status,VO.status,VO.vendor_response,VO.vendor_accept_status,VO.response_notes,VO.created_at,VO.updated_at,OI.vendor_invoice_number,C.name,C.mobile,C.email, 
				(SELECT COUNT(id) FROM ".VENDOR_ORDER_ITEM_TBL." OI WHERE vendor_order_id=VO.id AND vendor_id='".$vendor_id."' ) as items, 
				(SELECT SUM(coupon_value) FROM ".VENDOR_ORDER_ITEM_TBL." OI WHERE order_id=VO.id AND vendor_id='".$vendor_id."') as couponValue
			FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".CUSTOMER_TBL." C ON(VO.user_id=C.id) LEFT JOIN ".VENDOR_ORDER_ITEM_TBL." OI ON(OI.vendor_order_id=VO.id) WHERE VO.vendor_response='1' AND VO.vendor_id='".$vendor_id."' GROUP BY OI.vendor_invoice_number ORDER BY VO.id DESC";

		$exe 	= $this->exeQuery($query);
		if(mysqli_num_rows($exe) > 0) {
			$i=1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list 	= $this->editPagePublish($rows);

					$total_charges    = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'] ; 

					$data_status      = (($list['order_status']==0) ? 1 :  2 );
					
					$status_btn_title = (($list['order_status']==0) ? "Mark as Shipped" :  (($list['order_status']==1) ? "Mark as Delivered" : "Send Invoice") );
					$status_btn 	  = (($list['order_status']==0) ? "<em class='icon ni ni-truck'></em>" :  (($list['order_status']==1) ? "<em class='icon ni 					ni-money'></em>" : "<em class='icon ni ni-report-profit'></em>") ); 

					$inprocess = $this->check_query(VENDOR_ORDER_ITEM_TBL,"order_status","order_status='0' AND vendor_order_id='".$list['id']."' AND vendor_id='".$vendor_id."' ");
					$shipped   = $this->check_query(VENDOR_ORDER_ITEM_TBL,"order_status","order_status='1' AND vendor_order_id='".$list['id']."' AND vendor_id='".$vendor_id."' ");
					$delivered = $this->check_query(VENDOR_ORDER_ITEM_TBL,"order_status","order_status='2' AND vendor_order_id='".$list['id']."' AND vendor_id='".$vendor_id."' ");
					$cancelled = $this->check_query(VENDOR_ORDER_ITEM_TBL,"order_status","order_status='3' AND vendor_order_id='".$list['id']."' AND vendor_id='".$vendor_id."' ");

				
					if($inprocess) {
						$status_class = "text-warning"; 
						$status       =  "Inprocess";
					} elseif ($shipped) {
						$status_class = "text-warning"; 
						$status       =  "Shipped";
					} elseif ($delivered) {
						$status_class = "text-success"; 
						$status       =  "Delivered";
					} elseif ($cancelled) {
						$status_class = "text-danger"; 
						$status       =  "Returned";
					}

					if($list['vendor_response'] == 0) {
						$status_class = "text-warning"; 
						$status       =  "Not Seen"; 
					} elseif($list['vendor_response'] == 1 && $list['vendor_accept_status'] == 0 ) {
						$status_class = "text-danger"; 
						$status       =  "Rejected";
					} 

					$status_dropdown = "";

					if($list['order_status']==0) {
                        $status_dropdown  .= "
                                            <li>
                                                <a class='orderStatusChange' data-status='1' data-order='".$this->encryptData($list['id'])."'><em class='icon ni ni-truck'></em><span>Mark as Shipped</span></a>
                                            </li>";                    
                    }

                    if($list['order_status']!=0) {
                    
                        $status_dropdown  .= "<li>
                                                <a  class='orderStatusChange' data-status='2' data-order='".$this->encryptData($list['id'])."'><em class='icon ni ni-money'></em><span>Mark as Delivered</span></a>
                                             </li>
                                             <li>
                                                <a href='#' class='sentOrderInvoice' data-status='3'><em class='icon ni ni-report-profit'></em><span>Send Invoice</span></a>
                                             </li>";
                    }

                    if($list['couponValue']!="" && $list['couponValue']!=NUll) {
                        $coupon_value = $list['couponValue'];
                    } else {
                        $coupon_value = 0;
                    }

                    $td_data = "data-dycryprt_id='".$list['order_id']."'";

					$layout .="
								<tr class='nk-tb-item'>
                                        <td class='nk-tb-col'>
                                         ".$i."
                                        </td>
                                        <td class='nk-tb-col orderDetailsTD' $td_data >
                                            <span class='tb-lead'><a href='".COREPATH."orders/orderdetails/".$list['order_id']."'>".$list['vendor_invoice_number']."</a></span>
                                        </td>
                                         <td class='nk-tb-col  tb-col-md orderDetailsTD' $td_data >
                                            <span>".date('d/m/Y',strtotime($list['created_at']))."</span>
                                        </td>
                                        <td class='nk-tb-col orderDetailsTD' $td_data >
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col tb-col-mb orderDetailsTD' $td_data  >
                                            <span class='tb-amount'>".$list['items']." <span class='currency'>".(($list['items'] > 1 )? 'Items' : 'Item')."</span></span>
                                        </td>
                                        <td class='nk-tb-col orderDetailsTD' $td_data >
                                            <span class='tb-amount'>₹ ".$this->inrFormat($total_charges)." </span>
                                           
                                        </td>
                                        <td class='nk-tb-col orderDetailsTD' $td_data >
                                            <span class='tb-amount'>₹ ".$this->inrFormat($list['sub_total'] + $list['total_tax'] - $coupon_value )." </span>
                                           
                                        </td>
                                        <td class='nk-tb-col tb-col-md orderDetailsTD' $td_data >
                                            ".$this->getOrderStatus($list['id'])."
                                        </td>
                                    </tr>
							  	";
				$i++;
				}
		}
		return $layout;
	}

	function getOrderStatus($order_id) 
    {   
        $layout = "";
        $query  = "SELECT * FROM  ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id='".$order_id."' ";
        $exe    = $this->exeQuery($query);

        if(mysqli_num_rows($exe) > 0) 
        {
            while ($list = mysqli_fetch_array($exe)) {
                if($list['order_status']==0) {
                    $status_msg   = "Inprocess";
                    $status_class = "text-warning";
                } elseif($list['order_status']==1) {
                    $status_msg   = "Shipped";
                    $status_class = "text-warning";
                } elseif ($list['order_status']==2) {
                    $status_msg   = "Delivered";
                    $status_class = "text-success";
                } elseif ($list['order_status']==3) {
                    $status_msg   = "Returned";
                    $status_class = "text-danger";
                }

                if($list['vendor_response']==1 && $list['vendor_accept_status']==0)
                {
                    $status_msg   = "Rejected";
                    $status_class = "text-danger";
                } elseif ($list['vendor_response']==0) {
                    $status_msg   = "Not Viewed";
                    $status_class = "text-warning";
                }

                $layout .= "<span class='tb-status status_list $status_class'>".$status_msg."</span>";
            }
        }

        return $layout;

    }

	function vendorrejectedOrdersList()
	{
		$layout 	="";
		$vendor_id 	= $_SESSION["ecom_vendor_id"];
		$query  ="SELECT O.id,O.total_amount,O.sub_total,O.igst_amt,O.igst_amt,O.shipping_cost,O.coupon_value,O.coupon_id,O.order_uid,O.payment_type,O.order_status,O.payment_status,O.order_date,O.cancel_status,O.shipping_status,O.deliver_status,O.vendor_commission_amt,O.vendor_payment_charge_amt,OI.response_status,O.vendor_shipping_charge_amt,O.created_at,C.name,C.mobile,C.email,OI.vendor_id,OI.vendor_invoice_number,VO.vendor_response,VO.vendor_accept_status,VO.response_notes,VO.id as vendor_order_id,RS.response_status as response_status_msg,
			(SELECT COUNT(id) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."' ) as items, 
			(SELECT SUM(sub_total) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as vendor_sub_total,
			(SELECT SUM(total_tax) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as vendor_total_tax,
			(SELECT SUM(vendor_commission_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as total_commission,
			(SELECT SUM(vendor_payment_charge_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as total_payment_charge_amt,
			(SELECT SUM(coupon_value) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as couponValue,
			(SELECT SUM(vendor_shipping_charge_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as total_shipping_charge_amt
			FROM ".ORDER_TBL." O LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) 
					LEFT JOIN ".ORDER_ITEM_TBL." OI ON(OI.order_id=O.id) 
					LEFT JOIN ".VENDOR_ORDER_TBL." VO ON(VO.order_id=O.id)
					LEFT JOIN ".ORDER_RESPONSE_STATUS_TBL." RS ON (RS.id=OI.response_status) 
			WHERE VO.vendor_response='1' AND VO.vendor_accept_status='0' AND OI.vendor_id='".$vendor_id."' GROUP BY vendor_invoice_number ORDER BY id DESC";
		$exe 	= $this->exeQuery($query);

		if(mysqli_num_rows($exe) > 0) {
			$i=1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list 			= $this->editPagePublish($rows);
					$total_charges  = $list['total_commission'] + $list['total_payment_charge_amt'] + $list['total_shipping_charge_amt'] ; 

					$layout .="
								<tr class='nk-tb-item rejected_order_details' data-dycryprt_id='".$list['id']."'>
                                        <td class='nk-tb-col'>
                                         ".$i."
                                        </td>
                                        <td class='nk-tb-col'  >
                                            <span class='tb-lead'><a href='".COREPATH."orders/rejectedorderdetail/".$list['id']."'>".$list['vendor_invoice_number']."</a></span>
                                        </td>
                                         <td class='nk-tb-col  tb-col-md'>
                                            <span>".date('d/m/Y',strtotime($list['created_at']))."</span>
                                        </td>
                                        <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col tb-col-mb' >
                                            <span class='tb-amount'>".$list['items']." <span class='currency'>".(($list['items'] > 1 )? 'Items' : 'Item')."</span></span>
                                        </td>
                                        <td class='nk-tb-col'>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($total_charges)." </span>
                                           
                                        </td>
                                        <td class='nk-tb-col'>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($list['vendor_sub_total'] + $list['vendor_total_tax']  )." </span>
                                           
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                            <span class='tb-status'>".$list['response_status_msg']."</span>
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                            <span class='tb-status text-danger'>Rejected</span>
                                        </td>
                                    </tr>
							  	";
				$i++;
				}
		}
		return $layout;
	}

	function vendorActiveOrdersList()
	{
		$layout 	="";
		$vendor_id 	= $_SESSION["ecom_vendor_id"];
		$query  ="SELECT O.id,O.total_amount,O.sub_total,O.igst_amt,O.igst_amt,O.shipping_cost,O.coupon_value,O.coupon_id,O.order_uid,O.payment_type,O.order_status,O.payment_status,O.order_date,O.cancel_status,O.shipping_status,O.deliver_status,O.vendor_commission_amt,O.vendor_payment_charge_amt,OI.response_status,O.vendor_shipping_charge_amt,O.created_at,C.name,C.mobile,C.email,OI.vendor_id,OI.vendor_invoice_number,VO.vendor_response,VO.vendor_accept_status,VO.response_notes,VO.id as vendor_order_id,RS.response_status as response_status_msg,
			(SELECT COUNT(id) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."' ) as items, 
			(SELECT SUM(vendor_commission_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as total_commission,
			(SELECT SUM(vendor_payment_charge_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as total_payment_charge_amt,
			(SELECT SUM(coupon_value) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as couponValue,
			(SELECT SUM(vendor_shipping_charge_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as total_shipping_charge_amt
			FROM ".ORDER_TBL." O LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) 
					LEFT JOIN ".ORDER_ITEM_TBL." OI ON(OI.order_id=O.id) 
					LEFT JOIN ".VENDOR_ORDER_TBL." VO ON(VO.order_id=O.id)
					LEFT JOIN ".ORDER_RESPONSE_STATUS_TBL." RS ON (RS.id=OI.response_status) 
			WHERE 1 AND VO.vendor_response='1' AND VO.vendor_accept_status='1' AND O.order_status < 2 AND OI.vendor_id='".$vendor_id."' GROUP BY vendor_invoice_number ORDER BY id DESC";
		$exe 	= $this->exeQuery($query);

		if(mysqli_num_rows($exe) > 0) {
			$i=1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list 			= $this->editPagePublish($rows);
					$total_charges  = $list['total_commission'] + $list['total_payment_charge_amt'] + $list['total_shipping_charge_amt'] ; 

					$layout .="
								<tr class='nk-tb-item'>
                                        <td class='nk-tb-col'>
                                         ".$i."
                                        </td>
                                        <td class='nk-tb-col orderDetailsTD' data-dycryprt_id='".$list['id']."'>
                                            <span class='tb-lead'><a href='".COREPATH."orders/orderdetails/".$list['id']."'>".$list['vendor_invoice_number']."</a></span>
                                        </td>
                                         <td class='nk-tb-col  tb-col-md orderDetailsTD' data-dycryprt_id='".$list['id']."'>
                                            <span>".date('d/m/Y',strtotime($list['created_at']))."</span>
                                        </td>
                                        <td class='nk-tb-col orderDetailsTD' data-dycryprt_id='".$list['id']."'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col tb-col-mb orderDetailsTD' data-dycryprt_id='".$list['id']."'>
                                            <span class='tb-amount'>".$list['items']." <span class='currency'>".(($list['items'] > 1 )? 'Items' : 'Item')."</span></span>
                                        </td>
                                        <td class='nk-tb-col orderDetailsTD' data-dycryprt_id='".$list['id']."'>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($total_charges)." </span>
                                           
                                        </td>
                                        <td class='nk-tb-col orderDetailsTD' data-dycryprt_id='".$list['id']."'>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($list['sub_total'] + $list['igst_amt']  )." </span>
                                           
                                        </td>
                                        <td class='nk-tb-col tb-col-md  orderDetailsTD' data-dycryprt_id='".$list['id']."'>
                                            <span class='tb-status text-danger'>".$this->getOrderStatus($list['vendor_order_id'])."</span>
                                        </td>
                                        <td class='nk-tb-col nk-tb-col-tools'>
			                                <ul class='nk-tb-actions gx-1'>
			                                    <li class='nk-tb-action-hidden'>
			                                        <button class='btn btn-trigger btn-icon order_item_status_change' data-order_id='".$list['id']."'  data-placement='top' title='Change Order Item Status' data-toggle='tooltip'  ><em class='icon ni ni-plus-fill-c'></em></button>
			                                    </li>
			                                    <li>
			                                        <div class='drodown'>
			                                            <a href='#' class='dropdown-toggle btn btn-icon btn-trigger' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a>
			                                            
			                                        </div>
			                                    </li>
			                                </ul>
			                            </td>
                                    </tr>
							  	";
				$i++;
				}
		}
		return $layout;
	}

	function getOrderResponseItems($order_id)
	{
		$layout 	="";
		$vendor_id 	= $_SESSION["ecom_vendor_id"];
		$query  	="SELECT O.id,O.user_id,O.variant_id,O.vendor_invoice_number,O.vendor_id,O.order_status,O.cart_id,O.product_id,O.coupon_value,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.qty,O.sub_total,O.igst_amt,O.vendor_commission_tax_amt,O.vendor_payment_tax_amt,O.vendor_shipping_tax_amt,O.vendor_commission_tax,O.vendor_payment_tax,O.vendor_shipping_tax,O.total_tax,O.sgst,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.vendor_payment_charge,O.vendor_payment_charge_amt,O.vendor_shipping_charge,O.vendor_shipping_charge_amt,O.total_amount,O.vendor_commission,O.vendor_commission_amt,O.shipping_remarks,O.delivery_date,O.delivery_remarks,O.cancel_comment,P.product_uid,P.product_name,P.page_url,P.category_type,P.main_category_id,P.sub_category_id,V.variant_name,OD.order_uid,OD.created_at,C.name,C.mobile,C.email,O.vendor_commission_amt as total_commission,O.vendor_payment_charge_amt as total_payment_charge_amt,O.vendor_shipping_charge_amt as total_shipping_charge_amt,O.total_amount as vendor_total_amt,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image
			FROM ".ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) LEFT JOIN ".ORDER_TBL." OD ON (O.order_id=OD.id) LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) WHERE O.order_id=$order_id AND O.vendor_id='".$vendor_id."' ORDER BY O.price*O.qty  DESC";
		$exe 	= $this->exeQuery($query);
		if(mysqli_num_rows($exe) > 0) {
			$i=1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list 	= $this->editPagePublish($rows);

					$name             = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

					if($list['category_type']=="main")
		        	{
		        		$cat  		= $this->getDetails(MAIN_CATEGORY_TBL,"id,category"," id='".$list['main_category_id']."' ");
		        		$category 	= $cat['category'];
		        	} else {
		        		$cat  		= $this->getDetails(SUB_CATEGORY_TBL,"id,subcategory"," id='".$list['sub_category_id']."' ");
		        		$category 	= $cat['subcategory'];
		        	}

					$layout .="<div class='profile-ud-list'>
                                <div class='profile-ud-item'>
                                	<div class='profile-ud wider enq_name_field'>
                                        <span class='profile-ud-label'>".$name."</span>
                                    </div>
                                </div>
                                <div class='profile-ud-item'>
                                	<div class='profile-ud wider enq_name_field'>
                                        <div class='custom-control custom-switch'>
                                        	<input type='hidden' id='item_response_".$list['id']."' name='item_response_".$list['id']."' value='1' >
                                            <input type='checkbox' class='custom-control-input custom-control-sm order_response_status'  id='order_response_status_".$list['id']."' data-item_id='".$list['id']."' checked='' >
                                            <label class='custom-control-label response_title_".$list['id']."' for='order_response_status_".$list['id']."'>Accept</label>
                                        </div>
                                    </div>
                                </div>
                            </div>";
				$i++;
				}
		}
		return $layout;
	}

	function getOrderstsPopUpItems($order_id)
	{
		$layout 	="";
		$vendor_id 	= $_SESSION["ecom_vendor_id"];
		$query  	="SELECT O.id,O.user_id,O.variant_id,O.vendor_invoice_number,O.vendor_id,O.order_status,O.cart_id,O.product_id,O.coupon_value,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.qty,O.sub_total,O.igst_amt,O.vendor_commission_tax_amt,O.vendor_payment_tax_amt,O.vendor_shipping_tax_amt,O.vendor_commission_tax,O.vendor_payment_tax,O.vendor_shipping_tax,O.total_tax,O.sgst,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.vendor_payment_charge,O.vendor_payment_charge_amt,O.vendor_shipping_charge,O.vendor_shipping_charge_amt,O.total_amount,O.vendor_commission,O.vendor_commission_amt,O.shipping_remarks,O.delivery_date,O.delivery_remarks,O.cancel_comment,O.return_status,P.product_uid,P.product_name,P.page_url,P.category_type,P.main_category_id,P.sub_category_id,V.variant_name,OD.order_uid,OD.created_at,C.name,C.mobile,C.email,O.vendor_commission_amt as total_commission,O.vendor_payment_charge_amt as total_payment_charge_amt,O.vendor_shipping_charge_amt as total_shipping_charge_amt,O.total_amount as vendor_total_amt,PU.product_unit,RR.return_reason as return_reason_msg,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image
			FROM ".ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) 
									  LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) 
									  LEFT JOIN ".ORDER_TBL." OD ON (O.order_id=OD.id) 
									  LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) 
                                      LEFT JOIN ".RETURN_REASON_TBL." RR ON (RR.id=O.return_reason) 
									  LEFT JOIN ".PRODUCT_UNIT_TBL." PU ON (PU.id=P.product_unit)  
			WHERE O.order_id=$order_id AND O.vendor_id='".$vendor_id."' AND  O.vendor_response='1' AND O.vendor_accept_status='1' ORDER BY O.price*O.qty  DESC";
		$exe 	= $this->exeQuery($query);
		if(mysqli_num_rows($exe) > 0) {
			$i=1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list 	= $this->editPagePublish($rows);

					$data_status       = (($list['order_status']==0) ? 1 :  (($list['order_status']==1) ? 2 :  3 ) );
					$status_class 	  = (($list['order_status']==2) ? "text-success" :  (($list['order_status']==3) ? "text-danger" :  "text-warning" ) ); 
					$status 		  = (($list['order_status']==0) ? "Inprocess" : (($list['order_status']==1) ? "Shipped" : (($list['order_status']==2) ? "Delivered" :   (($list['return_status']==1) ? "Returned" :  "Cancelled" ))) ); 
					$status_btn_title = (($list['order_status']==0) ? "Inprocess" :  (($list['order_status']==1) ? "Shipped" :  (($list['order_status']==2) ? "Delivered" :  "Cancelled") ) );
					$status_btn 	  = (($list['order_status']==0) ? "<em class='icon ni ni-truck'></em>" :  (($list['order_status']==1) ? "<em class='icon ni 					ni-money'></em>" : "<em class='icon ni ni-trash'></em>") ); 
					$total_charges    = (int) $list['total_commission'] + (int) $list['total_payment_charge_amt'] + (int) $list['total_shipping_charge_amt'] ; 
					$name             = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;


					$remarks_head     = (($list['order_status']==0) ? "Shipping Remarks" : (($list['order_status']==1) ? "Delivery Remarks" : (($list['order_status']==2) ? "Cancel Remarks" : "")) ); 


					if ($list['default_product_image']!="") {
						$product_image = $list['default_product_image']!='' ? ADMIN_UPLOADS.$list['default_product_image'] : ASSETS_PATH."no_img.jpg" ;# code...
					}else{
						$product_image = $list['product_image']!='' ? ADMIN_UPLOADS.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
					}

					 if($list['category_type']=="main")
		        	{
		        		$cat  		= $this->getDetails(MAIN_CATEGORY_TBL,"id,category"," id='".$list['main_category_id']."' ");
		        		$category 	= $cat['category'];
		        	} else {
		        		$cat  		= $this->getDetails(SUB_CATEGORY_TBL,"id,subcategory"," id='".$list['sub_category_id']."' ");
		        		$category 	= $cat['subcategory'];
		        	}

		        	$update_info    = "";

		        	if($list['order_status']==1) {
		        		$update_info = $list['shipping_remarks'];
		        	} elseif ($list['order_status']==2) {
		        		$update_info = $list['delivery_remarks'];
		        	} elseif ($list['order_status']==3) {
		        		$update_info = $list['cancel_comment'];
		        	} 
		        	if ($list['return_status']==1) {
		        		$update_info = $list["return_reason_msg"];
		        	}

		        	$status_dropdown  = "";
                    $arrow 			  = "<em class='icon ni ni-chevron-down'></em>";
                    $td_cen           = "";
                    $drop_btn         = "btn-primary btn-sm";


		        	if($list['order_status']==0) {
                        $status_dropdown  .= "
                                            <li><a  class='status_action' data-status='".$i."' data-change='1' data-action='Mark as Shipped'>Mark as Shipped</a></li>
                                            ";               
                    }

                    if($list['order_status']==1) {
                    
                        $status_dropdown  .= "
                                            <li><a  class='status_action' data-status='".$i."' data-change='2' data-action='Mark as Delivered'>Mark as Delivered</a></li>
                                            
                                             ";
                    }
                    if($list['order_status']==false) {
                    	$status_dropdown .= "";
                    }

                    // <li><a  class='status_action' data-status='".$i."' data-change='3' data-action='Cancel Order'>Cancel Order</a></li><li><a  class='status_action' data-status='".$i."' data-change='3' data-action='Cancel Order'>Cancel Order</a></li><li><a  class='status_action' data-status='".$i."' data-change='3' data-action='Cancel Order'>Cancel Order</a></li>

                    // for hide cancel order

                    if($list['order_status']==2) {
                    	$status_dropdown .= "";
                    	$arrow   		  ="";
                    	$td_cen			  = "td_cen";
                    	$drop_btn         = "btn-success btn-sm";
                    }

                    if($list['order_status']==3) {
                    	$status_dropdown .= "";
                    	$arrow   		  ="";
                    	$td_cen			  = "td_cen";
                    	$drop_btn         = "btn-danger btn-sm";
                    }

                    $remarks_td = "";
                    $up_info    = "display_block";

                    if($list['order_status']!=0) {
                    	$remarks_td .= " <div class='last_update remarks_info_".$i."'>
                                   <span class='info_hd'>$status <span class='lt_up'> (Last Update)</span></span>
                                   <span class='up_info'>$update_info<span>
                                </div>";
                        $up_info    = "display_none";
                    }

					$order_item_tot = $this->check_query(ORDER_ITEM_TBL,"id"," order_id='".$list['order_id']."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' AND vendor_accept_status='1' ");

					$product_unit = (($list['product_unit']!="")? "( ".$list['product_unit']." )" : "" );


					$layout .=" <input type='hidden' id='orderId' value='".$list['order_id']."'>
                        		<input type='hidden' id='totalItems' name='total_item' value='".$order_item_tot."'>
								<tr class='nk-tb-item '>
                                <td>
                                    ".$i."
                        			<input type='hidden' name='item_id_".$i."' value='".$this->encryptData($list['id'])."'>
                                </td>
                                <td>
                                    <img src='".$product_image."' width=50 />
                                </td>
                                <td >
                                    <span class='text-primary'>".$name."</span><br>
                                    <span class='info_hd'>Category : ".$category." QTY : ".$list['qty']." ".$product_unit."</span>
                                   
                                </td>
                                <td >
                                    <span class='info_hd'>Order Date : ".date("d-m-Y",strtotime($list['created_at'])) ." Invoice No : ".$list['vendor_invoice_number']." </span>
                                  
                                </td>
                                <td >
                                  <span class='tb-status $status_class'>$status</span>
                                </td>
                                <td class='remark_td' >
                                $remarks_td
                                <div class='text_remarks_".$i." $up_info'>
                                  <textarea class='form-control' name='remarks_".$i."'></textarea>
                                </div>
                                </td>
                                <td class='status_btn_col td_cen' >
                                   
                                    <div class='dropdown'>
                                        <a href='#' class='btn $drop_btn' data-toggle='dropdown'><span class='status_btn_ne_".$i." sts_btn' >$status_btn_title</span>$arrow</a>
                                        <input type='hidden' name='order_status_".$i."' id='order_status_".$i."' value='".$list['order_status']."'>
                                        <input type='hidden' name='delivery_date_".$i."' id='delivery_date_".$i."' value='".$list['delivery_date']."'>
                                        <div class='dropdown-menu dropdown-menu-right dropdown-menu-auto mt-1'>
                                            <ul class='link-list-plain'>
                                            	$status_dropdown
                                                
                                            </ul>
                                        </div>
                                    </div>
                                                   
                                </td>
                                
                            </tr>
							  	";
				$i++;
				}
		}
		return $layout;
	}

	function getOrderstsItems($order_id)
	{
		$layout 	="";
		$vendor_id 	= $_SESSION["ecom_vendor_id"];
		$query  	="SELECT O.id,O.user_id,O.variant_id,O.vendor_invoice_number,O.vendor_id,O.order_status,O.cart_id,O.product_id,O.coupon_value,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.qty,O.sub_total,O.igst_amt,O.vendor_commission_tax_amt,O.vendor_payment_tax_amt,O.vendor_shipping_tax_amt,O.vendor_commission_tax,O.vendor_payment_tax,O.vendor_shipping_tax,O.total_tax,O.sgst,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.vendor_payment_charge,O.vendor_payment_charge_amt,O.vendor_shipping_charge,O.vendor_shipping_charge_amt,O.total_amount,O.vendor_commission,O.vendor_commission_amt,O.shipping_remarks,O.delivery_date,O.delivery_remarks,O.cancel_comment,P.product_uid,P.product_name,P.page_url,P.category_type,P.main_category_id,P.sub_category_id,V.variant_name,OD.order_uid,OD.created_at,C.name,C.mobile,C.email,O.vendor_commission_amt as total_commission,O.vendor_payment_charge_amt as total_payment_charge_amt,O.vendor_shipping_charge_amt as total_shipping_charge_amt,O.total_amount as vendor_total_amt,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image
			FROM ".ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) LEFT JOIN ".ORDER_TBL." OD ON (O.order_id=OD.id) LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) WHERE O.order_id=$order_id AND O.vendor_id='".$vendor_id."' ORDER BY O.price*O.qty  DESC";
		$exe 	= $this->exeQuery($query);
		if(mysqli_num_rows($exe) > 0) {
			$i=1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list 	= $this->editPagePublish($rows);

					$data_status       = (($list['order_status']==0) ? 1 :  (($list['order_status']==1) ? 2 :  3 ) );
					$status_class 	  = (($list['order_status']==2) ? "text-success" :  (($list['order_status']==3) ? "text-danger" :  "text-warning" ) ); 
					$status 		  = (($list['order_status']==0) ? "Inprocess" : (($list['order_status']==1) ? "Shipped" : (($list['order_status']==2) ? "Delivered" : "Cancelled")) ); 
					$status_btn_title = (($list['order_status']==0) ? "Inprocess" :  (($list['order_status']==1) ? "Shipped" :  (($list['order_status']==2) ? "Delivered" :  "Cancelled") ) );
					$status_btn 	  = (($list['order_status']==0) ? "<em class='icon ni ni-truck'></em>" :  (($list['order_status']==1) ? "<em class='icon ni 					ni-money'></em>" : "<em class='icon ni ni-trash'></em>") ); 
					$total_charges    = (int) $list['total_commission'] + (int) $list['total_payment_charge_amt'] + (int) $list['total_shipping_charge_amt'] ; 
					$name             = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;


					$remarks_head     = (($list['order_status']==0) ? "Shipping Remarks" : (($list['order_status']==1) ? "Delivery Remarks" : (($list['order_status']==2) ? "Cancel Remarks" : "")) ); 


					if ($list['default_product_image']!="") {
						$product_image = $list['default_product_image']!='' ? ADMIN_UPLOADS.$list['default_product_image'] : ASSETS_PATH."no_img.jpg" ;# code...
					}else{
						$product_image = $list['product_image']!='' ? ADMIN_UPLOADS.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
					}

					if($list['category_type']=="main")
		        	{
		        		$cat  		= $this->getDetails(MAIN_CATEGORY_TBL,"id,category"," id='".$list['main_category_id']."' ");
		        		$category 	= $cat['category'];
		        	} else {
		        		$cat  		= $this->getDetails(SUB_CATEGORY_TBL,"id,subcategory"," id='".$list['sub_category_id']."' ");
		        		$category 	= $cat['subcategory'];
		        	}

		        	$update_info    = "";

		        	if($list['order_status']==1) {
		        		$update_info = $list['shipping_remarks'];
		        	} elseif ($list['order_status']==2) {
		        		$update_info = $list['delivery_remarks'];
		        	} elseif ($list['order_status']==3) {
		        		$update_info = $list['cancel_comment'];
		        	}

		        	$status_dropdown  = "";
                    $arrow 			  = "<em class='icon ni ni-chevron-down'></em>";
                    $td_cen           = "";
                    $drop_btn         = "btn-primary btn-sm";


		        	if($list['order_status']==0) {
                        $status_dropdown  .= "
                                            <li><a  class='status_action' data-status='".$i."' data-change='1' data-action='Mark as Shipped'>Mark as Shipped</a></li>
                                            ";               
                    }

                    if($list['order_status']==1) {
                    
                        $status_dropdown  .= "
                                            <li><a  class='status_action' data-status='".$i."' data-change='2' data-action='Mark as Delivered'>Mark as Delivered</a></li>
                                            
                                             ";
                    }
                    if($list['order_status']==false) {
                    	$status_dropdown .= "";
                    }

                    // <li><a  class='status_action' data-status='".$i."' data-change='3' data-action='Cancel Order'>Cancel Order</a></li><li><a  class='status_action' data-status='".$i."' data-change='3' data-action='Cancel Order'>Cancel Order</a></li><li><a  class='status_action' data-status='".$i."' data-change='3' data-action='Cancel Order'>Cancel Order</a></li>

                    // for hide cancel order

                    if($list['order_status']==2) {
                    	$status_dropdown .= "";
                    	$arrow   		  = "";
                    	$td_cen			  = "td_cen";
                    	$drop_btn         = "btn-success";
                    }

                    if($list['order_status']==3) {
                    	$status_dropdown .= "";
                    	$arrow   		  = "";
                    	$td_cen			  = "td_cen";
                    	$drop_btn         = "btn-danger";
                    }

                    $remarks_td = "";
                    $up_info    = "display_block";

                    if($list['order_status']!=0) {
                    	$remarks_td .= " <div class='last_update remarks_info_".$i."'>
                                   <span class='info_hd'>$status <span class='lt_up'> (Last Update)</span></span>
                                   <span class='up_info'>$update_info<span>
                                </div>";
                        $up_info    = "display_none";
                    }



					$layout .="
								<tr class='nk-tb-item '>
                                <td>
                                    ".$i."
                        			<input type='hidden' name='item_id_".$i."' value='".$this->encryptData($list['id'])."'>
                                </td>
                                <td>
                                    <img src='".$product_image."' width=50 />
                                </td>
                                <td >
                                    <span class='text-primary'>".$name."</span><br>
                                    <span class='info_hd'>Category : ".$category." QTY : ".$list['qty']."</span>
                                </td>
                                <td >
                                    <span class='info_hd'>Order Date : ".date("d-m-Y",strtotime($list['created_at'])) ." Invoice No : ".$list['vendor_invoice_number']." </span>
                                    
                                </td>
                                <td >
                                  <span class='tb-status $status_class'>$status</span>
                                </td>
                                <td class='remark_td' >
                                $remarks_td
                                <div class='text_remarks_".$i." $up_info'>
                                  <textarea class='form-control' name='remarks_".$i."'></textarea>
                                </div>
                                </td>
                                <td class='status_btn_col td_cen' >
                                   
                                    <div class='dropdown'>
                                        <a href='#' class='btn $drop_btn' data-toggle='dropdown'><span class='status_btn_ne_".$i." sts_btn' >$status_btn_title</span>$arrow</a>
                                        <input type='hidden' name='order_status_".$i."' id='order_status_".$i."' value='".$list['order_status']."'>
                                        <input type='hidden' name='delivery_date_".$i."' id='delivery_date_".$i."' value='".$list['delivery_date']."'>
                                        <div class='dropdown-menu dropdown-menu-right dropdown-menu-auto mt-1'>
                                            <ul class='link-list-plain'>
                                            	$status_dropdown
                                            </ul>
                                        </div>
                                    </div>
                                                   
                                </td>
                                
                            </tr>
							  	";
				$i++;
				}
		}
		return $layout;
	}

	function orderItemStatusChange($data)
	{	
		$validate_csrf             = $this->validateCSRF($data);
		$curr 			           = date("Y-m-d H:i:s");

		if ($validate_csrf=="success") {

			for ($i=1; $i <= $data['total_item'] ; $i++) {

				$item_id           = $this->decryptData($data['item_id_'.$i]);
				$remarks           = $this->cleanString($data['remarks_'.$i]);
				$remarks           = "";

				if( $data['order_status_'.$i] == 1 ) {
					$remarks       = ",shipping_remarks='".$data['remarks_'.$i]."',shipping_status='1' ";
				} elseif ( $data['order_status_'.$i] == 2) {
					$delevery_date = (( $data['delivery_date_'.$i] == "" ) ? ",delivery_date='".$curr."' " : "" );
					$remarks       = ",delivery_remarks='".$data['remarks_'.$i]."' ".$delevery_date." ,  deliver_status='1' ";
				} elseif ( $data['order_status_'.$i] == 3 ) {
					$remarks       = ",cancel_comment='".$data['remarks_'.$i]."' , cancel_status='1' ";
				}

				// Update order item status in order item table

				$update_item_status  = "UPDATE ".ORDER_ITEM_TBL." SET 
											order_status = '".$data['order_status_'.$i]."' ".$remarks." ,
											updated_at   = '".$curr."' 
										WHERE id='".$item_id."' ";
				$up_exe              = $this->exeQuery($update_item_status);

				$item_details        = $this->getDetails(ORDER_ITEM_TBL,"*"," id='".$item_id."' ");


				// Update order status in order table based on order items status

				$item_pending_to_deliver  = $this->check_query(ORDER_ITEM_TBL,"*","(order_status ='0' OR order_status='1') AND vendor_response='1' AND vendor_accept_status='1' AND order_id='".$item_details['order_id']."' AND id!='".$item_id."'    ");

				if( $item_pending_to_deliver == 0 ) { 
					if( $data['order_status_'.$i] == 3 ) {
						
						$check_other_items  = $this->check_query(ORDER_ITEM_TBL,"id"," (order_status='0' OR order_status='1' OR order_status='2') AND vendor_response='1' AND vendor_accept_status='1' AND order_id='".$item_details['order_id']."' AND  id!='".$item_id."'    ");
						
						if( $check_other_items == 0 ) {
							
							$query3         = " UPDATE ".ORDER_TBL." SET 
													order_status = '".$data['order_status_'.$i]."',
													updated_at   = '".$curr."' 
												WHERE id='".$item_details['order_id']."' ";
							$exe3           = $this->exeQuery($query3);
						} 

					} elseif($data['order_status_'.$i]!=3) {
						
						$check_other_items2 = $this->check_query(ORDER_ITEM_TBL,"id","( order_status='0' OR order_status='1' ) AND vendor_response='1' AND vendor_accept_status='1' AND order_id='".$item_details['order_id']."'  AND id!='".$item_id."' ");
						
						if($check_other_items2==0) {
							
							$query3         = " UPDATE ".ORDER_TBL." SET 
													order_status = '".$data['order_status_'.$i]."',
													updated_at   = '".$curr."' 
												WHERE id='".$item_details['order_id']."'";
							$exe3           = $this->exeQuery($query3);

						}
					}
				}

				// Update order item status in vendor order item table

				$query2                      = "UPDATE ".VENDOR_ORDER_ITEM_TBL." SET 
													order_status = '".$data['order_status_'.$i]."' 
													".$remarks.",
													updated_at   = '".$curr."' 
												WHERE order_id='".$item_details['order_id']."' AND vendor_id='".$item_details['vendor_id']."' AND product_id='".$item_details['product_id']."' ";
				$exe2                        = $this->exeQuery($query2);


				// Update order  status in vendor order table based on vendor order items status

				$item_pending_to_deliver_ve  = $this->check_query(VENDOR_ORDER_ITEM_TBL,"id","(order_status='0' OR order_status='1') AND order_id='".$item_details['order_id']."' AND vendor_response='1' AND vendor_accept_status='1' AND vendor_id='".$item_details['vendor_id']."' AND order_item_id!='".$item_id."'  ");

				if( $item_pending_to_deliver_ve == 0 ) { 

					if( $data['order_status_'.$i] == 3 ) {

						$check_otherItems             = $this->check_query(VENDOR_ORDER_ITEM_TBL,"id"," (order_status='0' OR order_status='1' OR order_status='2') AND vendor_response='1' AND vendor_accept_status='1' AND order_id='".$item_details['order_id']."' AND vendor_id='".$item_details['vendor_id']."'    ");

						if( $check_otherItems == 0 ) {

							if( $data['order_status_'.$i] == 2 ) {

								$update_delivery_date = ",delivery_date='".date("Y-m-d",strtotime($curr))."',deliver_status='1' ";

							} else {

								$update_delivery_date = "";

							}

							$query4                   = "UPDATE ".VENDOR_ORDER_TBL." SET 
															order_status='".$data['order_status_'.$i]."' 
															".$update_delivery_date.",
															updated_at   = '".$curr."' 
														WHERE order_id='".$item_details['order_id']."' AND vendor_id='".$item_details['vendor_id']."' ";
							$exe4                     = $this->exeQuery($query4);

						} 
					} elseif( $data['order_status_'.$i] != 3 ) {

						$check_otherItems2            = $this->check_query(VENDOR_ORDER_ITEM_TBL,"id"," (order_status='0' OR order_status='1') AND vendor_response='1' AND vendor_accept_status='1' AND order_id='".$item_details['order_id']."' AND vendor_id='".$item_details['vendor_id']."' AND order_item_id!='".$item_id."'  ");

						if( $check_otherItems2 == 0 ) {

							if( $data['order_status_'.$i] == 2 ) {

								$update_delivery_date = ",delivery_date='".date("Y-m-d",strtotime($curr))."',deliver_status='1' ";

							} else {

								$update_delivery_date = "";

							}

							$query4                   = "UPDATE ".VENDOR_ORDER_TBL." SET 
															order_status  = '".$data['order_status_'.$i]."'
															".$update_delivery_date.",
															updated_at    = '".$curr."' 
														 WHERE order_id   = '".$item_details['order_id']."' AND vendor_id='".$item_details['vendor_id']."' ";
							$exe4                     = $this->exeQuery($query4);
						}
					}
				}

				if( $data['order_status_'.$i] == 1 ){
					$result =1;
				}else{
					$result =2;
				}
			}

			}else{
				return $validate_csrf;
			}
		return 1;
	}

	function changeOrderStatus($data)
	{	
		$order_id = $this->decryptData($data['order_id']);
		$query = "UPDATE ".ORDER_TBL." SET order_status='".$data['status']."' WHERE id='".$order_id."' ";
		if($data['status'] ==1){
			$result =1;
		}else{
			$result =2;
		}
		$up_exe = $this->exeQuery($query);
		if($up_exe){
			return $result;
		}
	}

	/*--------------------------------------------
			Manage Order Notification List
	--------------------------------------------*/
	function ordersNotificationList()
	{
		$result 				= array();
		$result['new_orders'] 	= "";
		$result['return_orders'] = "";
		$vendor_id 	= $_SESSION["ecom_vendor_id"];
		$query  ="SELECT O.id,O.total_amount,O.sub_total,O.igst_amt,O.shipping_cost,O.coupon_value,O.coupon_id,O.order_uid,O.payment_type,O.order_status,O.payment_status,O.order_date,O.cancel_status,O.shipping_status,O.deliver_status,O.vendor_commission_amt,O.vendor_payment_charge_amt,O.vendor_shipping_charge_amt,O.created_at,C.name,C.mobile,C.email,OI.vendor_id,OI.vendor_invoice_number,VO.vendor_response,VO.id as vendorOrderId,VO.return_status,VO.return_reason,VO.return_comment,
				(SELECT COUNT(id) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."' ) as items, 
				(SELECT SUM(vendor_commission_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as total_commission,
				(SELECT SUM(vendor_payment_charge_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as total_payment_charge_amt,
				(SELECT SUM(coupon_value) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as couponValue,
				(SELECT SUM(vendor_shipping_charge_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as total_shipping_charge_amt,
				(SELECT SUM(tax_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as sub_Total,
				(SELECT SUM(igst_amt) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."') as igst_AMT,
				(SELECT SUM(coupon_value) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."' AND return_status='1' ) as return_couponValue,
	            (SELECT COUNT(id) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."' AND return_status='1' ) as return_items,
	            (SELECT SUM(sub_total) + SUM(total_tax) FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."' AND return_status='1' ) as return_total,
	            (SELECT SUM(vendor_commission_amt) +  SUM(vendor_payment_charge_amt) +
	                     SUM(vendor_shipping_charge_amt)  FROM ".ORDER_ITEM_TBL." OI WHERE order_id=O.id AND vendor_id='".$vendor_id."' AND return_status='1' ) as return_charge_total 
			FROM ".ORDER_TBL." O LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) 
								 LEFT JOIN ".VENDOR_ORDER_TBL." VO ON(VO.order_id=O.id AND  VO.vendor_id='".$vendor_id."') 
								 LEFT JOIN ".ORDER_ITEM_TBL." OI ON(OI.order_id=O.id) 
			WHERE 1 AND OI.vendor_id='".$vendor_id."' AND (VO.vendor_response='0' OR  VO.return_status='1') GROUP BY vendor_invoice_number ORDER BY id DESC";

		$exe 	= $this->exeQuery($query);
		if(mysqli_num_rows($exe) > 0) {
			$i=1;
			$j=1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list 			   = $this->editPagePublish($rows);

                    if($list['coupon_value']!="" && $list['coupon_value']!=NUll) {
                        $coupon_value  = $list['couponValue'];
                    } else {
                        $coupon_value  = 0;
                    }

                    $total_charges     = $list['total_commission'] + $list['total_payment_charge_amt'] + $list['total_shipping_charge_amt'] ;
					$total             = $this->inrFormat($list['sub_Total'] + $list['igst_AMT'] - (int) $coupon_value ); 

                    $layout            = (($list['return_status']==1)? "return_orders" : "new_orders" );
                    $layout_tr_class   = (($list['return_status']==1)? "return_order_detail" : "new_order_detail" );

                    if($list['return_status']==1) {
                        $layout        = "return_orders";
                        $list['items'] = $list['return_items'];
                        $total_charges = $list['return_charge_total'];
                        $total         =  $this->inrFormat($list['return_total'] );
                    }

                    if($list['return_status']==1) {
                        $status_td     = "<td class='nk-tb-col'><span class='tb-status text-danger'>Returned</span></td>";
                        $s_no          =  $j++;
                    } else {
                        $status_td     = "<td class='nk-tb-col'><span class='tb-status text-warning'>Not Seen</span></td>";
						$s_no          =  $i++;
                    }

					$result[$layout] .="
							<tr class='nk-tb-item orderNotificationTD $layout_tr_class' data-dycryprt_id='".$list['vendorOrderId']."'>
                                    <td class='nk-tb-col'>
                                     ".$s_no."
                                    </td>
                                    <td class='nk-tb-col >
                                        <span class='tb-lead'><a href='".COREPATH."orders/orderdetails/".$list['id']."'>".$list['vendor_invoice_number']."</a></span>
                                    </td>
                                     <td class='nk-tb-col  tb-col-md orderNotificationTD' data-dycryprt_id='".$list['id']."' >
                                        <span>".date('d/m/Y',strtotime($list['created_at']))."</span>
                                    </td>
                                    <td class='nk-tb-col >
                                        <div class='user-card'>
                                            <div class='user-info'>
                                                <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class='nk-tb-col tb-col-mb orderNotificationTD' data-dycryprt_id='".$list['id']."'  >
                                        <span class='tb-amount'>".$list['items']." <span class='currency'>".(($list['items'] > 1 )? 'Items' : 'Item')."</span></span>
                                    </td>
                                    <td class='nk-tb-col >
                                        <span class='tb-amount'>₹ ".$this->inrFormat($total_charges)." </span>
                                       
                                    </td>
                                    <td class='nk-tb-col >
                                        <span class='tb-amount'>₹ ".$total." </span>
                                       
                                    </td>
                                    ".$status_td."
                                </tr>
						  	";
				}
		}
		return $result;
	}

	// Vendor response changes

	function orderNotificationResponse($data)
	{	
		
		$check_response = $this->check_query(VENDOR_ORDER_TBL,"vendor_response","vendor_response='0' AND id='".$data['vendor_order_id']."' ");
        if($check_response) {
            $response_status = ($data['order_response_status_id']=='')? " response_status = NULL, " :  " response_status = '".$data['order_response_status_id']."', " ;
            $curr           = date("Y-m-d H:i:s");
            $order_details  = $this->getDetails(VENDOR_ORDER_TBL,"*","id='".$data['vendor_order_id']."'  "); 
            
            $query = "SELECT * FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_order_id = '".$order_details['id']."' AND order_id='".$order_details['order_id']."'  ";
            $exe   = $this->exeQuery($query);

            $order_item_ids = array();

            if(mysqli_num_rows($exe) > 0) {
                while ($list = mysqli_fetch_assoc($exe)) {
                    $order_item_ids[] = $list['order_item_id'];
                }
            }

            foreach ($order_item_ids as $key => $value) {

            	$query = "UPDATE ".VENDOR_ORDER_ITEM_TBL." SET 
                        vendor_response      = '1',
                        vendor_accept_status = '".$data['item_response_'.$value]."',
                        $response_status
                        updated_at           = '".$curr ."'
                      WHERE vendor_order_id='".$data['vendor_order_id']."' AND order_item_id='".$value."' ";
            	$exe  = $this->exeQuery($query);
            	
            }

            $check_inprocess_item = $this->check_query(VENDOR_ORDER_ITEM_TBL,"vendor_response,vendor_accept_status","vendor_order_id='".$order_details['id']."' AND vendor_response='1' AND vendor_accept_status='1' ");

            if($check_inprocess_item) {
				$check_inprocess_item_present = 1;
            } else {
				$check_inprocess_item_present = 0;            	
            }

    		$query = "UPDATE ".VENDOR_ORDER_TBL." SET 
                vendor_response      = '1',
                vendor_accept_status = '".$check_inprocess_item_present."'
              WHERE id='".$data['vendor_order_id']."' ";
    		$exe  = $this->exeQuery($query);

            $rejected_items = array();

            foreach ($order_item_ids as $key => $value) {
                
                $rejected_items[] = $data['item_response_'.$value];
            	
            	$query = "UPDATE ".ORDER_ITEM_TBL." SET 
                        vendor_response      = '1',
                        vendor_accept_status = '".$data['item_response_'.$value]."',
                        $response_status
                        updated_at           = '".$curr ."'
                      	WHERE id ='".$value."' ";
            	$exe  = $this->exeQuery($query);
            }

            if($exe) {
            	$update_notification_mail = $this->OrderStatusUpdateMail($data['vendor_order_id']);
                
                $check_rejected_items     = in_array(0, $rejected_items);

                if($check_rejected_items) {
                    return 1;
                } else {
                    return 2;
                }
                
            } else {
                return "Sorry!! Unexpected Error Occurred. Please try again.";
            }

        } else {
            return "Sorry!! order response status already changed.";
        }
	}

	function OrderStatusUpdateMail($vendor_order_id)
    {
        $info        = $this->getDetails(VENDOR_ORDER_TBL, "*","id='".$vendor_order_id."'");
        $v_info      = $this->getDetails(VENDOR_TBL, "*","id='".$info['vendor_id']."'");
        $email_info  = $this->getDetails(CUSTOMER_TBL,'id,name,email,token', " id ='".$info['user_id']."' ");
        $sender      = COMPANY_NAME;
        $sender_mail = NO_REPLY;
        $subject	 = $v_info['company']." has been updated your order status - Order Id ".$info['order_uid'];
        $receiver    = $this->cleanString($email_info['email']);
        $message     = $this->OrderStatusupdateTemplate($vendor_order_id);
        $send_mail   = $this->send_mail($sender_mail,$receiver,$subject,$message);
        return 1;
    }

	 // Get product request status master dropdown

    function getOrderResponseDropDown($current="")
    {
        $layout = "";
        $q = "SELECT id,response_status FROM ".ORDER_RESPONSE_STATUS_TBL." WHERE status='1' AND delete_status='0' " ;
        $query = $this->exeQuery($q);
        if(@mysqli_num_rows($query)>0){
            $i=0;
            while($list = mysqli_fetch_array($query)){
                $selected = (($list['id']==$current) ? 'selected' : '');
                $layout.= "<option value='".$list['id']."' $selected>".$list['response_status']."</option>";
                $i++;
            }
        }
        return $layout;
    }



	function getOrderItems($order_id)
	{	
		$layout     ="";
		$vendor_id 	= $_SESSION["ecom_vendor_id"];
		$query      ="SELECT O.id,O.user_id,O.variant_id,O.vendor_invoice_number,O.vendor_id,O.cart_id,O.product_id,O.coupon_value,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.qty,O.sub_total,O.vendor_commission_tax_amt,O.vendor_payment_tax_amt,O.vendor_shipping_tax_amt,O.vendor_commission_tax,O.vendor_payment_tax,O.vendor_shipping_tax,O.total_tax,O.sgst,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.order_status,O.return_reason,O.return_comment,O.vendor_payment_charge,O.vendor_payment_charge_amt,O.vendor_shipping_charge,O.vendor_shipping_charge_amt,O.total_amount,O.vendor_commission,O.vendor_commission_amt,O.vendor_response,O.vendor_accept_status,O.response_notes,P.product_uid,P.product_name,P.page_url,V.variant_name,OD.order_address,VD.state_id,VD.state_name,RS.response_status as response_status_msg,RR.return_reason as return_reason_msg,PU.product_unit
			FROM ".ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) 
									  LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) 
									  LEFT JOIN ".VENDOR_TBL." VD ON (VD.id=O.vendor_id) 
									  LEFT JOIN ".ORDER_TBL." OD ON (OD.id=O.order_id) 
									  LEFT JOIN ".ORDER_RESPONSE_STATUS_TBL." RS ON (RS.id=O.response_status) 
                                      LEFT JOIN ".RETURN_REASON_TBL." RR ON (RR.id=O.return_reason) 
									  LEFT JOIN ".PRODUCT_UNIT_TBL." PU ON (PU.id=P.product_unit)  
			WHERE O.order_id=$order_id AND O.vendor_id='".$vendor_id."' ORDER BY O.price*O.qty  DESC";
		$exe 	    = $this->exeQuery($query);
		if(mysqli_num_rows($exe) > 0) {
			$i=1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list 	    = $this->editPagePublish($rows);
					$info    	= $this->getDetails(VENDOR_TBL,"company","id='".$list['vendor_id']."' ");
					$name       = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

					$billing_address = $this->getDetails(CUSTOMER_ADDRESS_TBL,"state_id","id=".$list['order_address']." ");

					if($billing_address['state_id']==$list['state_id']) {
						$tax_info = "<p>SGST : ".$list['sgst']."% (₹  ".$this->inrFormat($list['sgst_amt']).")</p>
                                        <p>CGST : ".$list['sgst']."% (₹  ".$this->inrFormat($list['cgst_amt']).")</p>";
					} else {
						$tax_info = "<p>IGST : ".$list['igst']."% (₹  ".$this->inrFormat($list['igst_amt']).")</p>";
					}

					if($list['return_comment']!="") {
                        $return_comment = "<div>Comment : ".$list['return_comment']."</div>";
                    } else {
                        $return_comment = "";
                    }


					$status_btn_title = (($list['order_status']==0) ? "<span class='text-warning'>Inprocess</span>" :  (($list['order_status']==1) ? "<span class='text-warning'>Shipped</span>" :  (($list['order_status']==2) ? "<span class='text-success'>Delivered</span>" :  "<span class='text-danger'>Returned</span><div>Reason : ".$list['return_reason_msg']." ".$return_comment ." </div>") ) );

					 if($list['vendor_response']==1 && $list['vendor_accept_status']==0)
                    {
                        $status_btn_title = "<span class='text-danger'>Rejected</span>";
                        $response_notes   = "<div>
                                            ( ".$list['response_status_msg']." )
                                            <div>" ;
                        $rejected_price   = "text-danger";                                            
                    } elseif($list['vendor_response']== 0 )  {
                        $status_btn_title = "<span class='text-warning'>Not Seen</span>";
                        $response_notes   = "" ;
                        $rejected_price   = "";                        
                    } else {
                        $response_notes = "";
                        $rejected_price   = "";                        
                    }

					if($list['tax_type']=="inclusive") 
					{
						$taxMsg = "<p>( Inclusive of all taxes * )</p>";
					} else {
						$taxMsg = "<p>( Exclusive of all taxes * )</p>";
					}

					$product_unit = (($list['product_unit']!="")? "( ".$list['product_unit']." )" : "" );

                    $rating_check = $this->check_query(VENDOR_RATTING_TBL,"id","product_id='".$list['product_id']."' AND vendor_id='".$list['vendor_id']."' AND added_by='".$list['user_id']."' AND order_id='".$list['order_id']."' ");

                    if($rating_check) {

                        $rating_info   = $this->getDetails(VENDOR_RATTING_TBL,"*","product_id='".$list['product_id']."' AND vendor_id='".$list['vendor_id']."' AND added_by='".$list['user_id']."' AND order_id='".$list['order_id']."'  ");

                        $sold_by_and_ratings = "
                                        <div class='star_rating star_tbl_list'>
                                            <span class='my-rating-7 ms-2 star_tbl_list'>
                                                <p class='sold_by_info' >Sold by: ".$info['company']."</p>
                                            </span>
                                            <input type='hidden' class='rating_data' name='star_ratings' value='".$rating_info['star_ratings']."' id='rating_data'>
                                            <span class='star_rating_point'>( ".$rating_info['star_ratings']." / 5 )</span>
                                        </div>";

                    } else {
                        $sold_by_and_ratings = "<p>Sold by: ".$info['company']."</p>";
                    }

					$layout 	.="
                                    <tr class='marginBottom'>
                                        <td>$i</td>
                                        <td><a href='".BASEPATH."product/details/".$list['page_url']."' target='_blank'>".$name."</a>
                                        	".$taxMsg."
                                        	".$tax_info."                                            
                                            ".$sold_by_and_ratings."
                                        	<p>vendor invoice : <a >".$list['vendor_invoice_number']."</a></p>
                                        </td>
                                        <td>$status_btn_title 
                                        	$response_notes
                                    	</td>
                                        <td class='$rejected_price'>₹ ".$this->inrFormat($list['price'])."</td>
                                        <td class='$rejected_price'>".$list['qty']." ".$product_unit."</td>
                                        <td class='$rejected_price'>₹ ".$this->inrFormat($list['sub_total']+$list['total_tax'])."</td>
                                    </tr>
							  	";
					$i++;
				}
		}

		return $layout;
	}

	function getVendorItemToltals($order_id,$accept_items="")
	{	
		if($accept_items) {
            $condition = "AND vendor_accept_status='1'";    
        } else {
            $condition = "";                
        }

		$vendor_id 	= $_SESSION["ecom_vendor_id"];
		$query      = "SELECT vendor_invoice_number,
							  SUM(tax_amt) as subTotal, 
							  SUM(final_price) as finalPrice,
							  SUM(sgst) as SCST,
							  SUM(cgst) as CGST,
							  SUM(igst) as IGST,
							  SUM(sgst_amt) as SGST_AMT,
							  SUM(cgst_amt) as CGST_AMT,
							  SUM(igst_amt) as IGST_AMT,
							  SUM(total_amount) as totalAMT,
							  SUM(total_tax) as totalTax, 
							  SUM(coupon_value) as couponValue, 
							  SUM(vendor_commission) as vendorCommission, 
							  SUM(vendor_commission_tax) as vendorCommissionTax, 
							  SUM(vendor_payment_charge) as vendorPaymentCharge, 
							  SUM(vendor_payment_tax) as vendorPaymenTax, 
							  SUM(vendor_shipping_charge) as vendorShippingCharge, 
							  SUM(vendor_shipping_tax) as vendorShippingTax, 
							  SUM(vendor_commission_amt) as vendorCommissionAmt, 
							  SUM(vendor_commission_tax_amt) as vendorCommissionTaxAmt, 
							  SUM(vendor_payment_charge_amt) as vendorPaymentChargeAmt, 
							  SUM(vendor_payment_tax_amt) as vendorPaymentTaxAmt, 
							  SUM(vendor_shipping_charge_amt) as vendorShippingChargeAmt, 
							  SUM(vendor_shipping_tax_amt) as vendorShippingTaxAmt,
							  shipping_cost 
							  FROM ".ORDER_ITEM_TBL." WHERE 1 $condition AND  vendor_id='".$vendor_id."' AND  order_id='".$order_id."' ";
		$exe 	    = $this->exeQuery($query);
		$result     = mysqli_fetch_array($exe);
		return $result ;

	}

	function getChartDatasOld($input)
	{
		$result = array();
		$current_date          	= date("Y-m-d");
		$last_seven_days        = date("Y-m-d", strtotime("-7 days", strtotime($current_date)));
        $previous_7_days        = date("Y-m-d", strtotime("-7 days", strtotime($last_seven_days)));
		$last_30_days_start     = date("Y-m-d", strtotime("-30 days", strtotime($current_date)));
		$last_30_days_end 	    = date("Y-m-d", strtotime("-30 days", strtotime($last_30_days_start)));
		$last_3_month_start     = date("Y-m-d", strtotime("-90 days", strtotime($current_date)));
		$last_3_month_end		= date("Y-m-d", strtotime("-90 days", strtotime($last_3_month_start)));
		$last_6_month_start     = date("Y-m-d", strtotime("-180 days", strtotime($current_date)));
		$last_6_month_end   	= date("Y-m-d", strtotime("-180 days", strtotime($last_6_month_start)));

		if($input=="last_month") {
			$from_date   = strtotime($last_30_days_start);
			$to_date     = strtotime($current_date);
			$current_record 	= "AND DATE(order_date) BETWEEN '".$last_30_days_start."' AND '".$current_date."' AND  ";
			$pervious_record    = "AND DATE(order_date) BETWEEN '".$last_30_days_end."' AND '".$last_30_days_start."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_record");
			$getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");

		} elseif($input=="last_three_month") {
			$from_date   = strtotime($last_3_month_start);
			$to_date     = strtotime($current_date);
			$current_record 	= "AND DATE(order_date) BETWEEN '".$last_3_month_start."' AND '".$current_date."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$pervious_record    = "AND DATE(order_date) BETWEEN '".$last_3_month_end."' AND '".$last_3_month_start."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_record");
			$getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");

		}  elseif($input=="last_seven_days") {
			$from_date   = strtotime($last_seven_days);
			$to_date     = strtotime($current_date);
            $current_record 	= "AND DATE(order_date) BETWEEN '".$last_seven_days."'  AND '".$current_date."'  AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
            $pervious_record    = "AND DATE(order_date) BETWEEN '".$previous_7_days."'  AND '".$last_seven_days."' AND vendor_id='".$_SESSION['ecom_vendor_id']."'  ";
            $getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_record");
            $getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");
        } elseif($input=="today_records") {
        	$from_date   = strtotime($last_seven_days);
        	$to_date     = strtotime($current_date);
			$current_record 	= "AND DATE(order_date)='".$current_date."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$pervious_record    = "AND DATE(order_date)='". date("Y-m-d", strtotime("-1 days", strtotime($current_date)))."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_record");
			$getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");
		} 


		// $lables   = array();
		// $stepVal  = '+1 day';
	 	// 	while( $from_date <= $to_date ) {
  		// 		$lables[] 	='"'.date("d-M", $from_date).'"';
  		// 		$from_date	= strtotime($stepVal, $from_date);
  		// 	}
  		// $result['lables']  = $lables;

        // Card Box Datas 

        $q  = "SELECT id,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_record.") as card_totalOrders,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='3' ".$current_record.") as card_returnedOrders,
                    (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_record." ) as card_totalAmount,
                    (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_record.") as card_vendorCommissionAmt,
                    (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_record.") as card_vendorPayChargeAmt,
                    (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_record.") as card_vendorShipChargeAmt
               FROM ".VENDOR_ORDER_TBL."  WHERE 1 ";
        $exe = $this->exeQuery($q);
        $card_data = mysqli_fetch_array($exe);

        $commission  = $card_data['card_vendorCommissionAmt'] + $card_data['card_vendorPayChargeAmt'] + $card_data['card_vendorShipChargeAmt'];
        $payout      = $card_data['card_totalAmount'] - ($card_data['card_vendorCommissionAmt'] + $card_data['card_vendorPayChargeAmt'] + $card_data['card_vendorShipChargeAmt']);
        $avg_ord_val = number_format((($card_data['card_totalOrders']!=0)? $card_data['card_totalAmount'] / $card_data['card_totalOrders'] : 0),2);
        $result['card_total_order']       = $card_data['card_totalOrders'];
        $result['card_order_returned']    = $card_data['card_returnedOrders'];
        $result['card_total_amount']      = number_format($card_data['card_totalAmount'],2);
        $result['card_commission_earned'] = number_format($commission,2);
        $result['card_vendor_payout']     = number_format($payout,2);
        $result['card_avg_value']         = $avg_ord_val;

		// Total Sales Chart Functions

			$q = "SELECT total_amount FROM ".VENDOR_ORDER_TBL." WHERE deliver_status='0' $current_record   ORDER BY order_date ASC ";
			$exe = $this->exeQuery($q);
			$cm_total_sales = array();
			while($list = mysqli_fetch_array($exe)){
				$cm_total_sales[] =	$list['total_amount'];
			}
			$currentmonthData = implode(",", $cm_total_sales);
			$result["cm_total_sales"] = $currentmonthData;

			$q = "SELECT total_amount FROM ".VENDOR_ORDER_TBL." WHERE deliver_status='0' $pervious_record    ORDER BY order_date ASC ";
			$exe = $this->exeQuery($q);
			$lm_total_amount = array();
			while($list = mysqli_fetch_array($exe)){
				$lm_total_amount[] =	$list['total_amount'];
			}
			$lastmonthData = implode(",", $lm_total_amount);
			$result["lm_total_sales"] = $lastmonthData;
			$result["total_sales_data"] = number_format($getOrderTotals['totalValues'],2);

		// Average Order Chart Functions

			$q = "SELECT total_amount FROM ".VENDOR_ORDER_TBL." WHERE deliver_status='0' $current_record   ORDER BY order_date ASC ";
			$exe 			  = $this->exeQuery($q);
			$cm_avreage_total = array();
			$count 			  = mysqli_num_rows($exe);
			$count 			  = $count==0? 1: $count;
				while($list = mysqli_fetch_array($exe)){
					$cm_avreage_total[] =(($list['total_amount']==0)? 0 : number_format($list['total_amount']/$count,2) );
				}
			$currentmonthData = implode(",", $cm_avreage_total);
			$result["cm_average_total"] = $currentmonthData;

			$q = "SELECT total_amount FROM ".VENDOR_ORDER_TBL." WHERE deliver_status='0' $pervious_record AND vendor_id='".$_SESSION['ecom_vendor_id']."'   ORDER BY order_date ASC ";
			$exe 			   = $this->exeQuery($q);
			$lm_avreage_total   = array();
				while($list = mysqli_fetch_array($exe)){
					$lm_avreage_total[] = (($list['total_amount']==0)? 0 : number_format($list['total_amount']/$count,2) );
				}
			$lastmonthData = implode(",", $lm_avreage_total);
			$result["lm_average_total"] = $lastmonthData;
			$result["average_total_data"] = (($getOrderTotals['totalValues']==0)? 0 : number_format($getOrderTotals['totalValues']/$count,2) );

		// Total orders Chart Functions

			$q = "SELECT date(order_date), COUNT(id) as totalOrders, SUM(total_amount) as orderDailyTotal FROM ".VENDOR_ORDER_TBL." WHERE  deliver_status='0' $current_record GROUP BY 1 ";
			$exe 			 = $this->exeQuery($q);
			$cm_total_orders = array();
			$count 			 = mysqli_num_rows($exe);
			$count 			  = $count==0? 1: $count;
				while($list = mysqli_fetch_array($exe)){
					$cm_total_orders[] = $list['totalOrders'];
				}
			$currentmonthData = implode(",", $cm_total_orders);
			$result["cm_total_orders"] = $currentmonthData;

			$q = "SELECT date(order_date), COUNT(id) as totalOrders, SUM(total_amount) as orderDailyTotal FROM ".VENDOR_ORDER_TBL." WHERE  deliver_status='0' $pervious_record AND vendor_id='".$_SESSION['ecom_vendor_id']."' GROUP BY 1 ";
			$exe 			   = $this->exeQuery($q);
			$lm_total_orders   = array();
				while($list = mysqli_fetch_array($exe)){
					$lm_total_orders[] =	$list['totalOrders'];
				}
			$lastmonthData = implode(",", $lm_total_orders);
			$result["lm_total_orders"]   = $lastmonthData;
			$result["total_orders_data"] = $getTotalOrders['totalOrders'];

		return json_encode($result);
	}

	function getChartDatas($input)
    {
        $result    = array();
        $vendor_id = $_SESSION['ecom_vendor_id'];

        $current_date           = date("Y-m-d");
        $last_seven_days        = date("Y-m-d", strtotime("-7 days", strtotime($current_date)));
        $previous_7_days        = date("Y-m-d", strtotime("-7 days", strtotime($last_seven_days)));
        $last_30_days           = date("Y-m-d", strtotime("-30 days", strtotime($current_date)));
        $last_30_days_end       = date("Y-m-d", strtotime("-30 days", strtotime($last_30_days)));
        $last_3_month_start     = date("Y-m-d", strtotime("-90 days", strtotime($current_date)));
        $last_3_month_end       = date("Y-m-d", strtotime("-90 days", strtotime($last_3_month_start)));

        $vendor_check = "AND vendor_id='".$vendor_id."'";

        if($input=='today_records') {
            $current_data           = "AND DATE(order_date)='$current_date' ";
            $rejected_date_filter   = "AND DATE(updated_at)='$current_date' ";
            $get_order_totals       = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_data $vendor_check");
            $get_total_orders_count = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_data $vendor_check");


            for ($i = 0; $i <= 2; $i++) 
            {
               $d = (($i==0)? 'am' : (($i==1)? 'pm' : (($i==2)? 'am' : "") )  ) ;
               $main_lable[] = "<div class='chart-label'>12 $d</div>" ;
               $graph_lable[] = '12 '.$d;
            }
            
            // Total Sales Chart Current date datas

            $q_h = "SELECT SUM(total_amount) as orders_at_12am,
              (SELECT SUM(total_amount)  FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:01 AM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."') as orders_at_12am_to_12pm,
              (SELECT SUM(total_amount)  FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 11:59 PM"))."') as orders_at_12pm_to_12am
              FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at='".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 AM"))."' "; 

            $exe_h = $this->exeQuery($q_h);
            $list_h = mysqli_fetch_array($exe_h);

            $total_sales = array();
            $total_sales[] = (($list_h['orders_at_12am'])? $list_h['orders_at_12am'] : 0);
            $total_sales[] = (($list_h['orders_at_12am_to_12pm'])? $list_h['orders_at_12am_to_12pm'] : 0);
            $total_sales[] = (($list_h['orders_at_12pm_to_12am'])? $list_h['orders_at_12pm_to_12am'] : 0);
            if($total_sales[2]==0) {
                unset($total_sales[2]);
            }

            $result["current_data_total_sales"] = implode(",",$total_sales);

            // Average Chart current date datas

            $count_q = "SELECT COUNT(id) as orders_at_12am,
              (SELECT COUNT(id)  FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:01 AM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."') as orders_at_12am_to_12pm,
              (SELECT COUNT(id)  FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 11:59 PM"))."') as orders_at_12pm_to_12am
              FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at='".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 AM"))."' "; 

            $value_q = "SELECT SUM(total_amount) as orders_at_12am,
              (SELECT SUM(total_amount)  FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:01 AM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."') as orders_at_12am_to_12pm,
              (SELECT SUM(total_amount)  FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 11:59 PM"))."') as orders_at_12pm_to_12am
              FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at='".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 AM"))."' ";

            $count_exe  = $this->exeQuery($count_q);
            $count_list = mysqli_fetch_array($count_exe);

            $value_exe = $this->exeQuery($value_q);
            $value_list = mysqli_fetch_array($value_exe);

            $total_average = array();
            $total_average[] = (($count_list['orders_at_12am'])? $value_list['orders_at_12am']/$count_list['orders_at_12am'] : 0);
            $total_average[] = (($count_list['orders_at_12am_to_12pm'])? $value_list['orders_at_12am_to_12pm']/$count_list['orders_at_12am_to_12pm'] : 0);
            $total_average[] = (($count_list['orders_at_12pm_to_12am'])? $value_list['orders_at_12pm_to_12am']/$count_list['orders_at_12pm_to_12am'] : 0);
            if($total_average[2]==0) {
                unset($total_average[2]);
            }
            $result["current_data_average_total"] = implode(",",$total_average);

            $count = $count_list['orders_at_12am'] + $count_list['orders_at_12am_to_12pm'] + $count_list['orders_at_12pm_to_12am'];
            $result["average_total_data"]     = (($get_order_totals['totalValues']==0)? 0 : $this->inrFormat($get_order_totals['totalValues']/$count) );


            $total_orders = array();
            $total_orders[] = (($count_list['orders_at_12am'])? $count_list['orders_at_12am'] : 0);
            $total_orders[] = (($count_list['orders_at_12am_to_12pm'])? $count_list['orders_at_12am_to_12pm'] : 0);
            $total_orders[] = (($count_list['orders_at_12pm_to_12am'])? $count_list['orders_at_12pm_to_12am'] : 0);
            if($total_orders[2]==0) {
                unset($total_orders[2]);
            }

            $result["current_data_total_orders"] = implode(",",$total_orders);

        } else {

            if($input=='last_seven_days') {

                $from   = $last_seven_days;
                $to     = $current_date;

                for ($i = 0; $i <= 6; $i++) 
                {
                   $main_lable[] = "<div class='chart-label'>".date("d M", strtotime( date( 'Y-m-d' )." -$i days"))."</div>" ;
                }
            } elseif($input=='last_month') {

                $from   = $last_30_days;
                $to     = $current_date;

                for ($i = 0; $i <= 2; $i++) 
                {   
                   $d = (($i==0)? 0 : (($i==1)? 15 : (($i==2)? 30 : "") )  ) ;
                   $main_lable[] = "<div class='chart-label'>".date("d M, Y", strtotime( date( 'Y-m-d' )." -$d days"))."</div>" ;
                }

            } elseif($input=='last_three_month') {

                $from   = $last_3_month_start;
                $to     = $current_date;

                for ($i = 0; $i <= 2; $i++) 
                {
                   $main_lable[] = "<div class='chart-label'>".date("F, Y", strtotime( date( 'Y-m-01' )." -$i months"))."</div>" ;
                }

            }

            $current_data           = "AND DATE(order_date) BETWEEN '$from' AND '$to' ";
            $rejected_date_filter   = "AND DATE(updated_at) BETWEEN '$from' AND '$to' ";
            $get_order_totals       = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $vendor_check $current_data");
            $get_total_orders_count = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $vendor_check $current_data");

            // Date functions
            $current              = strtotime($from);
            $date2                = strtotime($to);
            $stepVal              = '+1 day';

            // Results values
            $daily_total_sales          = array();
            $daily_total_average        = array();
            $daily_total_order          = array();
            $graph_lable                = array();
            while( $current <= $date2 ) {
                $q_date         = date("Y-m-d",$current);
                $graph_lable[]  ='"'.date("d-M", $current).'"';
                $current        = strtotime($stepVal, $current);
                $q       = "SELECT COUNT(id) as daily_total_order, SUM(total_amount) as dailyTotal FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND  
                            order_status!='1' ".$vendor_check."  AND DATE(order_date) = '".$q_date."'  ";
                $exe     =  $this->exeQuery($q);
                $list    = mysqli_fetch_array($exe);
                $daily_total_sales[]   = (($list['dailyTotal']=="")? 0 : $list['dailyTotal'] );
                $daily_total_average[] = (($list['daily_total_order']==0)? 0 : $list['dailyTotal']/$list['daily_total_order'] );
                $daily_total_order[]   = (($list['daily_total_order']==0)? 0 : $list['daily_total_order'] );
            }

            $result["current_data_total_sales"]   = implode(",",$daily_total_sales);
            $result["current_data_average_total"] = implode(",",$daily_total_average);
            $result["current_data_total_orders"]  = implode(",",$daily_total_order);
            $result["average_total_data"]         = (($get_order_totals['totalValues']==0)? 0 : $this->inrFormat($get_order_totals['totalValues']/$get_total_orders_count['totalOrders']) );

        }

        $result['main_lable']  = array_reverse($main_lable);
        $result['graph_lable'] = implode(",",$graph_lable);

        // Card Box Datas 

        $q  = "SELECT id,
                (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_data." ".$vendor_check.") as card_totalOrders,
                (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='3' ".$current_data." ".$vendor_check.") as card_returnedOrders,
                (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_data." ".$vendor_check." ) as card_totalAmount,
                (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_data." ".$vendor_check.") as card_vendorCommissionAmt,
                (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_data." ".$vendor_check.") as card_vendorPayChargeAmt,
                (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE vendor_response='1' AND vendor_accept_status='0'  ".$rejected_date_filter." ".$vendor_check."  ) as card_order_rejected,
                (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$current_data." ".$vendor_check.") as card_vendorShipChargeAmt
               FROM ".VENDOR_ORDER_TBL."  WHERE 1 ";
        $exe         = $this->exeQuery($q);
        $card_data   = mysqli_fetch_array($exe);

        $commission  = (($card_data)? $card_data['card_vendorCommissionAmt'] + $card_data['card_vendorPayChargeAmt'] + $card_data['card_vendorShipChargeAmt'] : 0 );

        $payout      = (($card_data)? $card_data['card_totalAmount'] - ($card_data['card_vendorCommissionAmt'] + $card_data['card_vendorPayChargeAmt'] 
                       + $card_data['card_vendorShipChargeAmt']) : 0 );

        $avg_ord_val = (($card_data)? $this->inrFormat((($card_data['card_totalOrders']!=0)? $card_data['card_totalAmount'] / $card_data['card_totalOrders'] : 0)) : 0 );
        
        $result['card_total_order']              = (($card_data)? $card_data['card_totalOrders'] : 0 );
        $result['card_order_returned']           = (($card_data)? $card_data['card_returnedOrders'] : 0 );
        $result['card_order_rejected']           = (($card_data)? $card_data['card_order_rejected'] : 0 );
        $result['card_total_amount']             = (($card_data)? $this->inrFormat($card_data['card_totalAmount']) : 0 );
        $result['card_commission_earned']        = $this->inrFormat($commission);
        $result['card_vendor_payout']            = $this->inrFormat($payout);
        $result['card_avg_value']                = $avg_ord_val;
        $result["total_sales_data"]              = $this->inrFormat($get_order_totals['totalValues']);
        $result["total_orders_data"]             = $get_total_orders_count['totalOrders'];
        $result["previous_data_total_sales"]     = "";
        $result["previous_data_average_total"]   = "";
        $result["previous_data_total_orders"]    = "";



        return json_encode($result);
    }

	function getOrderTotals($input)
	{	
		$result = array();
		
		$current_date          	= date("Y-m-d");
		$last_seven_days        = date("Y-m-d", strtotime("-7 days", strtotime($current_date)));
        $previous_7_days        = date("Y-m-d", strtotime("-7 days", strtotime($last_seven_days)));
		$last_30_days_start     = date("Y-m-d", strtotime("-30 days", strtotime($current_date)));
		$last_30_days_end 	    = date("Y-m-d", strtotime("-30 days", strtotime($last_30_days_start)));
		$last_3_month_start     = date("Y-m-d", strtotime("-90 days", strtotime($current_date)));
		$last_3_month_end		= date("Y-m-d", strtotime("-90 days", strtotime($last_3_month_start)));
		$last_6_month_start     = date("Y-m-d", strtotime("-180 days", strtotime($current_date)));
		$last_6_month_end   	= date("Y-m-d", strtotime("-180 days", strtotime($last_6_month_start)));

		if($input=="last_month") {
			$current_record 	= "AND DATE(order_date) BETWEEN '".$last_30_days_start."' AND '".$current_date."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$pervious_record    = "AND DATE(order_date) BETWEEN '".$last_30_days_end."' AND '".$last_30_days_start."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_record");
			$getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");

		} elseif($input=="last_three_month") {
			$current_record 	= "AND DATE(order_date) BETWEEN '".$last_3_month_start."' AND '".$current_date."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$pervious_record    = "AND DATE(order_date) BETWEEN '".$last_3_month_end."' AND '".$last_3_month_start."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_record");
			$getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");

		}  elseif($input=="last_seven_days") {
            $current_record 	= "AND DATE(order_date) BETWEEN '".$last_seven_days."'  AND '".$current_date."'  AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
            $pervious_record    = "AND DATE(order_date) BETWEEN '".$previous_7_days."'  AND '".$last_seven_days."' AND vendor_id='".$_SESSION['ecom_vendor_id']."'  ";
            $getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_record");
            $getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");
        } elseif($input=="today_records") {
			$current_record 	= "AND DATE(order_date)='".$current_date."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$pervious_record    = "AND DATE(order_date)='". date("Y-m-d", strtotime("-1 days", strtotime($current_date)))."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_record");
			$getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");
		}  

		$q = "SELECT total_amount FROM ".VENDOR_ORDER_TBL." WHERE deliver_status='0' $current_record  ORDER BY order_date ASC ";
		$exe = $this->exeQuery($q);
		$cm_total_amount = array();
		while($list = mysqli_fetch_array($exe)){
			$cm_total_amount[] =	$list['total_amount'];
		}
		$currentmonthData = implode(",", $cm_total_amount);
		$result[0]=$currentmonthData;

		$q = "SELECT total_amount FROM ".VENDOR_ORDER_TBL." WHERE deliver_status='0' $pervious_record   ORDER BY order_date ASC ";
		$exe = $this->exeQuery($q);
		$lm_total_amount = array();
		while($list = mysqli_fetch_array($exe)){
			$lm_total_amount[] =	$list['total_amount'];
		}
		$lastmonthData = implode(",", $lm_total_amount);
		$result[1]     = $lastmonthData;
		$result[2]     = number_format($getOrderTotals['totalValues'],2);

		return json_encode($result);
	}

	function getOrderAverage($input)
	{	
		$result = array();
		
		$current_date          	= date("Y-m-d");
		$last_seven_days        = date("Y-m-d", strtotime("-7 days", strtotime($current_date)));
        $previous_7_days        = date("Y-m-d", strtotime("-7 days", strtotime($last_seven_days)));
		$last_30_days_start     = date("Y-m-d", strtotime("-30 days", strtotime($current_date)));
		$last_30_days_end 	    = date("Y-m-d", strtotime("-30 days", strtotime($last_30_days_start)));
		$last_3_month_start     = date("Y-m-d", strtotime("-90 days", strtotime($current_date)));
		$last_3_month_end		= date("Y-m-d", strtotime("-90 days", strtotime($last_3_month_start)));
		$last_6_month_start     = date("Y-m-d", strtotime("-180 days", strtotime($current_date)));
		$last_6_month_end   	= date("Y-m-d", strtotime("-180 days", strtotime($last_6_month_start)));

		if($input=="last_month") {
			$current_record 	= "AND DATE(order_date) BETWEEN '".$last_30_days_start."' AND '".$current_date."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$pervious_record    = "AND DATE(order_date) BETWEEN '".$last_30_days_end."' AND '".$last_30_days_start."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_record");
			$getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");

		} elseif($input=="last_three_month") {
			$current_record 	= "AND DATE(order_date) BETWEEN '".$last_3_month_start."' AND '".$current_date."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$pervious_record    = "AND DATE(order_date) BETWEEN '".$last_3_month_end."' AND '".$last_3_month_start."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_record");
			$getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");

		}  elseif($input=="last_seven_days") {
            $current_record 	= "AND DATE(order_date) BETWEEN '".$last_seven_days."'  AND '".$current_date."'  AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
            $pervious_record    = "AND DATE(order_date) BETWEEN '".$previous_7_days."'  AND '".$last_seven_days."' AND vendor_id='".$_SESSION['ecom_vendor_id']."'  ";
            $getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_record");
            $getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");
        } elseif($input=="today_records") {
			$current_record 	= "AND DATE(order_date)='".$current_date."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$pervious_record    = "AND DATE(order_date)='". date("Y-m-d", strtotime("-1 days", strtotime($current_date)))."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_record");
			$getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");
		}  


		$q = "SELECT total_amount FROM ".VENDOR_ORDER_TBL." WHERE deliver_status='0'  $current_record  ORDER BY order_date ASC ";
		$exe 			 = $this->exeQuery($q);
		$cm_total_amount = array();
		$count 			 = mysqli_num_rows($exe);
		$count 			 = $count==0 ? 1: $count;
			while($list = mysqli_fetch_array($exe)){
				$cm_total_amount[] =  (($list['total_amount']==0)? 0 : $list['total_amount']/$count );
			}
		$currentmonthData = implode(",", $cm_total_amount);
		$result[0]=$currentmonthData;

		$q = "SELECT total_amount FROM ".VENDOR_ORDER_TBL." WHERE deliver_status='0'  $pervious_record   ORDER BY order_date ASC ";
		$exe 			   = $this->exeQuery($q);
		$lm_total_amount   = array();
			while($list = mysqli_fetch_array($exe)){
				$lm_total_amount[] =	(($list['total_amount']==0)? 0 : $list['total_amount']/$count );
			}
		$lastmonthData = implode(",", $lm_total_amount);
		$result[1]     = $lastmonthData;
		$result[2]     =  (($getOrderTotals['totalValues']==0)? 0 : number_format($getOrderTotals['totalValues']/$count,2) );

		return json_encode($result);
		
	}

	function getTotalOrders($input)
	{	
		$result = array();
		
		$current_date          	= date("Y-m-d");
		$last_seven_days        = date("Y-m-d", strtotime("-7 days", strtotime($current_date)));
        $previous_7_days        = date("Y-m-d", strtotime("-7 days", strtotime($last_seven_days)));
		$last_30_days_start     = date("Y-m-d", strtotime("-30 days", strtotime($current_date)));
		$last_30_days_end 	    = date("Y-m-d", strtotime("-30 days", strtotime($last_30_days_start)));
		$last_3_month_start     = date("Y-m-d", strtotime("-90 days", strtotime($current_date)));
		$last_3_month_end		= date("Y-m-d", strtotime("-90 days", strtotime($last_3_month_start)));
		$last_6_month_start     = date("Y-m-d", strtotime("-180 days", strtotime($current_date)));
		$last_6_month_end   	= date("Y-m-d", strtotime("-180 days", strtotime($last_6_month_start)));

		if($input=="last_month") {
			$current_record 	= "AND DATE(order_date) BETWEEN '".$last_30_days_start."' AND '".$current_date."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$pervious_record    = "AND DATE(order_date) BETWEEN '".$last_30_days_end."' AND '".$last_30_days_start."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_record");
			$getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");

		} elseif($input=="last_three_month") {
			$current_record 	= "AND DATE(order_date) BETWEEN '".$last_3_month_start."' AND '".$current_date."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$pervious_record    = "AND DATE(order_date) BETWEEN '".$last_3_month_end."' AND '".$last_3_month_start."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_record");
			$getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");

		}  elseif($input=="last_seven_days") {
            $current_record 	= "AND DATE(order_date) BETWEEN '".$last_seven_days."'  AND '".$current_date."'  AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
            $pervious_record    = "AND DATE(order_date) BETWEEN '".$previous_7_days."'  AND '".$last_seven_days."' AND vendor_id='".$_SESSION['ecom_vendor_id']."'  ";
            $getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount)as totalValues","order_status!='0' AND order_status!='1' $current_record");
            $getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");
        } elseif($input=="today_records") {
			$current_record 	= "AND DATE(order_date)='".$current_date."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$pervious_record    = "AND DATE(order_date)='". date("Y-m-d", strtotime("-1 days", strtotime($current_date)))."' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
			$getOrderTotals     = $this->getDetails(VENDOR_ORDER_TBL,"SUM(total_amount) as totalValues","order_status!='0' AND order_status!='1' $current_record");
			$getTotalOrders     = $this->getDetails(VENDOR_ORDER_TBL,"COUNT(id) as totalOrders","order_status!='0' AND order_status!='1' $current_record");
		}  


		$q = "SELECT date(order_date), COUNT(id) as totalOrders, SUM(total_amount) as orderDailyTotal FROM ".VENDOR_ORDER_TBL." WHERE  deliver_status='0' $current_record GROUP BY 1 ";
		$exe 			 = $this->exeQuery($q);
		$cm_total_orders = array();
		$count 			 = mysqli_num_rows($exe);
		$count 			 = $count==0 ? 1: $count;
			while($list = mysqli_fetch_array($exe)){
				$cm_total_orders[] = $list['totalOrders'];
			}
		$currentmonthData = implode(",", $cm_total_orders);
		$result[0]=$currentmonthData;

		$q = "SELECT date(order_date), COUNT(id) as totalOrders, SUM(total_amount) as orderDailyTotal FROM ".VENDOR_ORDER_TBL." WHERE  deliver_status='0' $pervious_record GROUP BY 1 ";
		$exe 			   = $this->exeQuery($q);
		$lm_total_orders   = array();
			while($list = mysqli_fetch_array($exe)){
				$lm_total_orders[] =	$list['totalOrders'];
			}
		$lastmonthData = implode(",", $lm_total_orders);
		$result[1]     = $lastmonthData;
		$result[2]     = $getTotalOrders['totalOrders'];

		return json_encode($result);
	}

	/** 
		In Download Invoice Pdf shipping cost Could not be included that why im comment this function and alter this function after 
	**/

	/*function manageInvoiceItems($order_id="",$vendor_id="")
    {    
        $layout = "";
        $query = "SELECT I.id,I.qty,I.product_id,I.order_id,I.vendor_id,I.variant_id,I.price as prize,I.total_tax,I.sub_total,I.final_price,I.qty,I.size,I.total_amount,I.sgst,I.cgst,I.igst,I.sgst_amt,I.cgst_amt,O.coupon_code,O.order_address,O.coupon_id,I.coupon_value,O.order_uid,P.product_name,V.variant_name,VD.state_id,VD.state_name,I.vendor_commission,I.vendor_commission_tax,I.vendor_payment_charge,I.vendor_payment_tax,I.vendor_shipping_charge,I.vendor_shipping_tax,SUM(I.vendor_commission_amt) as totalCommission,SUM(I.vendor_payment_charge_amt) as totalPayment,SUM(I.vendor_shipping_charge_amt) as totalShipping,SUM(I.vendor_commission_tax_amt) as vendor_commission_tax_amt,SUM(I.vendor_payment_tax_amt) as vendor_payment_tax_amt,SUM(I.vendor_shipping_tax_amt) as vendor_shipping_tax_amt,SUM(I.igst_amt) as totalTax,SUM(I.tax_amt) as subTotal,SUM(I.coupon_value) as couponTotal,SUM(I.total_amount) as totalAmount
        		FROM  ".ORDER_ITEM_TBL." I LEFT JOIN ".PRODUCT_TBL." P ON(P.id=I.product_id) 
        								   LEFT JOIN ".ORDER_TBL." O ON (O.id=I.order_id) 
        								   LEFT JOIN ".PRODUCT_VARIANTS." V ON (I.variant_id=V.id) 
        								   LEFT JOIN ".VENDOR_TBL." VD ON (VD.id=I.vendor_id) 
				WHERE I.vendor_accept_status='1' AND I.order_id='".$order_id."' AND I.vendor_id='".$vendor_id."' ORDER BY I.price*I.qty LIMIT 1 ";
		$exe = $this->exeQuery($query);
	 	if(mysqli_num_rows($exe)>0){
            $i = 1;
            while ($details = mysqli_fetch_array($exe)) {
		    	$list  = $this->editPagePublish($details);

                $total_tax = $list['totalTax'] * $list['qty'] ;
				$name      = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;
				$coupon    = $list['coupon_id']!=0 ? $list['coupon_code'] : "-";
				$coupon_value    = $list['coupon_id']!=0 ? $list['couponTotal'] : "0.00";
                $coupon_value_int   = (($list['coupon_value']!="")? $list['couponTotal'] : 0 );

                $layout .='
					 '.$this->getInvoiceItems($order_id,$vendor_id).'
					 ';
				if($list['coupon_id']!=""){
					$coupon_info = "( Coupon Code : ".$coupon." )"; 
				} else {
					$coupon_info = ""; 
				}
				
				$net_payable = $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];
                $payable     = $list["totalAmount"] - $net_payable;
                $commission_wo_tax = $list['totalCommission'] - $list['vendor_commission_tax_amt'];
                $payment_wo_tax    = $list['totalPayment'] - $list['vendor_payment_tax_amt'];
                $shipping_wo_tax   = $list['totalShipping'] - $list['vendor_shipping_tax_amt'];

                $layout .= '<tr style="background: #F5F5F5;">
	                            <td colspan="5" style="text-align: right;border-top: 1px solid #222;"><strong>Subtotal</strong></td>
	                            <td colspan="2" style="text-align: right;border-top: 1px solid #222;">Rs. '.number_format($list["subTotal"] + $list["totalTax"] ,2).'</td>
	                        </tr>
	                        <tr style="background: #F5F5F5;">
	                            <td colspan="5" style="text-align: right;"><strong>Discount</strong> <br> '.$coupon_info.' </td>
	                            <td colspan="2" style="text-align: right;">Rs. '.$this->inrFormat($coupon_value_int,2).'</td>
	                        </tr>
	                        <tr style="background: #F5F5F5;">
                                <td colspan="5" style="text-align: right;"><strong>Zupply Commission </strong> <br>  ('.$list['vendor_commission'].' % Commission + '.$list['vendor_commission_tax'].' % Tax) </td>
                                <td colspan="2" style="text-align: right;">Rs. '.number_format($list['totalCommission'],2).'<br> ('.$this->inrFormat($commission_wo_tax).' + '.$this->inrFormat($list['vendor_commission_tax_amt']).') </td>
                            </tr>

                            <tr style="background: #F5F5F5;">
                                <td colspan="5" style="text-align: right;"><strong>Payment Gateway Charge</strong> <br>  ('.$list['vendor_payment_charge'].' % Charge + '.$list['vendor_payment_tax'].' % Tax) </td>
                                <td colspan="2" style="text-align: right;">Rs. '.number_format($list['totalPayment'],2).'<br> ('.$this->inrFormat($payment_wo_tax).' + '.$this->inrFormat($list['vendor_payment_tax_amt']).') </td>
                            </tr>

                            <tr style="background: #F5F5F5;">
                                <td colspan="5" style="text-align: right;"><strong>Shipping Charge</strong> <br>  ('.$list['vendor_shipping_charge'].' % Charge + '.$list['vendor_shipping_tax'].' % Tax) </td>
                                <td colspan="2" style="text-align: right;">Rs. '.number_format($list['totalShipping'],2).'<br> ('.$this->inrFormat($shipping_wo_tax).' + '.$this->inrFormat($list['vendor_shipping_tax_amt']).') </td>
                            </tr>
                            <tr style="background: #F5F5F5;">
                                <td colspan="5" style="text-align: right;"><strong>GRAND TOTAL</strong></td>
                                <td colspan="2" style="text-align: right;"><strong>Rs. '.number_format($list["subTotal"] + $list["totalTax"]-$coupon_value_int,2).'</strong></td>
                            </tr>
                            <tr style="background: #F5F5F5;">
                                <td colspan="5" style="text-align: right;"><strong>Total Charge</strong></td>
                                <td colspan="2" style="text-align: right;"><strong>Rs. - '.number_format($list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'],2).' </strong></td>
                            </tr>

                            <tr style="background: #F5F5F5;">
                                <td colspan="3" style="border-bottom: 1px solid #222;border-top: 1px solid #5D6975;">Total Amount in Words: <strong>'.$this->amountInWords($list["subTotal"] + $list["totalTax"]-$coupon_value_int - $net_payable).'</strong></td>
                                <td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;"><strong>Vendor Payable</strong></td>
                                <td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;;"><strong>Rs. '.number_format($list["subTotal"] + $list["totalTax"]-$coupon_value_int - $net_payable,2).'</strong></td>
                                '.$charges.'
                            </tr>';
				
            $i++;
            }
        }
        return $layout;
    }*/

    function manageInvoiceItems($order_id="",$vendor_id="")
    {    
        $layout = "";
        $query = "SELECT I.id,I.qty,I.product_id,I.order_id,I.vendor_id,I.variant_id,I.price as prize,I.total_tax,I.shipping_cost,I.sub_total,I.final_price,I.qty,I.size,I.total_amount,I.sgst,I.cgst,I.igst,I.sgst_amt,I.cgst_amt,O.coupon_code,O.order_address,O.coupon_id,I.coupon_value,O.order_uid,P.product_name,V.variant_name,VD.state_id,VD.state_name,I.vendor_commission,I.vendor_commission_tax,I.vendor_payment_charge,I.vendor_payment_tax,I.vendor_shipping_charge,I.vendor_shipping_tax,SUM(I.vendor_commission_amt) as totalCommission,SUM(I.vendor_payment_charge_amt) as totalPayment,SUM(I.vendor_shipping_charge_amt) as totalShipping,SUM(I.vendor_commission_tax_amt) as vendor_commission_tax_amt,SUM(I.vendor_payment_tax_amt) as vendor_payment_tax_amt,SUM(I.vendor_shipping_tax_amt) as vendor_shipping_tax_amt,SUM(I.igst_amt) as totalTax,SUM(I.tax_amt) as subTotal,SUM(I.coupon_value) as couponTotal,SUM(I.total_amount) as totalAmount
        		FROM  ".ORDER_ITEM_TBL." I LEFT JOIN ".PRODUCT_TBL." P ON(P.id=I.product_id) 
        								   LEFT JOIN ".ORDER_TBL." O ON (O.id=I.order_id) 
        								   LEFT JOIN ".PRODUCT_VARIANTS." V ON (I.variant_id=V.id) 
        								   LEFT JOIN ".VENDOR_TBL." VD ON (VD.id=I.vendor_id) 
				WHERE I.vendor_accept_status='1' AND I.order_id='".$order_id."' AND I.vendor_id='".$vendor_id."' ORDER BY I.price*I.qty LIMIT 1 ";
		$exe = $this->exeQuery($query);
	 	if(mysqli_num_rows($exe)>0){
            $i = 1;
            while ($details = mysqli_fetch_array($exe)) {
		    	$list  = $this->editPagePublish($details);

                $total_tax = $list['totalTax'] * $list['qty'] ;
				$name      = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;
				$coupon    = $list['coupon_id']!=0 ? $list['coupon_code'] : "-";
				$coupon_value    = $list['coupon_id']!=0 ? $list['couponTotal'] : "0.00";
                $coupon_value_int   = (($list['coupon_value']!="")? $list['couponTotal'] : 0 );

                $layout .='
					 '.$this->getInvoiceItems($order_id,$vendor_id).'
					 ';
				if($list['coupon_id']!=""){
					$coupon_info = "( Coupon Code : ".$coupon." )"; 
				} else {
					$coupon_info = ""; 
				}

				if($list['shipping_cost']!=""){
					$shipping_info = $list['shipping_cost']; 
				} else {
					$shipping_info = "0"; 
				}
				
				$net_payable = $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];
                $payable     = $list["totalAmount"] - $net_payable;
                $commission_wo_tax = $list['totalCommission'] - $list['vendor_commission_tax_amt'];
                $payment_wo_tax    = $list['totalPayment'] - $list['vendor_payment_tax_amt'];
                $shipping_wo_tax   = $list['totalShipping'] - $list['vendor_shipping_tax_amt'];

                $layout .= '<tr style="background: #F5F5F5;">
	                            <td colspan="5" style="text-align: right;border-top: 1px solid #222;"><strong>Subtotal</strong></td>
	                            <td colspan="2" style="text-align: right;border-top: 1px solid #222;">Rs. '.number_format($list["subTotal"] + $list["totalTax"] ,2).'</td>
	                        </tr>
	                        <tr style="background: #F5F5F5;">
	                            <td colspan="5" style="text-align: right;"><strong>Discount</strong> <br> '.$coupon_info.' </td>
	                            <td colspan="2" style="text-align: right;">Rs. '.$this->inrFormat($coupon_value_int,2).'</td>
	                        </tr>
	                        <tr style="background: #F5F5F5;">
	                        	<td colspan="5" style="text-align: right;"><strong>Shipping Cost: </strong></td>
	                            <td colspan="2" style="text-align: right;">Rs. '.$this->inrFormat($shipping_info,2).'</td>
	                        </tr>
	                        <tr style="background: #F5F5F5;">
                                <td colspan="5" style="text-align: right;"><strong> Total Amount</strong></td>
                                <td colspan="2" style="text-align: right;"><strong>Rs. '.number_format($list["subTotal"] + $list["totalTax"] + $list['shipping_cost'] - $coupon_value_int,2).'</strong></td>
                            </tr>

                            <tr style="background: #F5F5F5;">
                                <td colspan="3" style="border-bottom: 1px solid #222;border-top: 1px solid #5D6975;">Total Amount in Words: <strong>'.$this->amountInWords($list["subTotal"] + $list["totalTax"] + $list['shipping_cost'] - $coupon_value_int).'</strong></td>
                                <td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;"><strong>Payable</strong></td>
                                <td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;;"><strong>Rs. '.number_format($list["subTotal"] + $list["totalTax"] + $list['shipping_cost'] - $coupon_value_int,2).'</strong></td>
                                '.$charges.'
                            </tr>';
				
            $i++;
            }
        }
        return $layout;
    }

     function getInvoiceItems($order_id="",$vendor_id="")
    {    
        $layout = "";
        $query = "SELECT I.id,I.qty,I.product_id,I.order_id,I.variant_id,I.price as prize,I.total_tax,I.sub_total,I.final_price,I.qty,I.size,I.total_amount,I.sgst,I.cgst,I.igst,I.sgst_amt,I.cgst_amt,I.igst_amt,I.tax_type,I.vendor_commission_amt,I.vendor_payment_charge_amt,I.vendor_shipping_charge_amt,O.order_address,O.order_uid,P.product_name,V.variant_name,O.order_address,I.vendor_id,VD.state_id,VD.state_name,PU.product_unit 
        		 FROM  ".ORDER_ITEM_TBL." I LEFT JOIN ".PRODUCT_TBL." P ON(P.id=I.product_id) 
        		 							LEFT JOIN ".VENDOR_TBL." VD ON (VD.id=I.vendor_id) 
        		 							LEFT JOIN ".ORDER_TBL." O ON (O.id=I.order_id) 
        		 							LEFT JOIN ".PRODUCT_VARIANTS." V ON (I.variant_id=V.id)  
										    LEFT JOIN ".PRODUCT_UNIT_TBL." PU ON (PU.id=P.product_unit)  
        		 WHERE I.order_id='".$order_id."' AND I.vendor_id='".$vendor_id."' AND I.vendor_accept_status='1' ORDER BY I.price*I.qty   DESC ";
		$exe = $this->exeQuery($query);
	 	if(mysqli_num_rows($exe)>0){
            $i = 1;
            while ($details = mysqli_fetch_array($exe)) {
		    	$list  = $this->editPagePublish($details);
		    	$background = ($i %2 == 0)? "background: #F5F5F5;": "";

                $total_tax = $list['total_tax'] * $list['qty'] ;
                $igst_Amt  = $list['igst_amt'];
				$name      = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

				$billing_address = $this->getDetails(CUSTOMER_ADDRESS_TBL,"state_id","id=".$list['order_address']." ");

				if($billing_address['state_id']==$list['state_id']) {
					$tax_info = $list['sgst']."% SGST<br/>".$list['cgst']."% CGST";
				} else {
					$tax_info = $list['igst']."% IGST";
				}

				$product_unit = (($list['product_unit']!="")? "( ".$list['product_unit']." )" : "" );

                $layout .='
                		<tr>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$i.'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: left;">'.$this->publishContent($name).'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">Rs.'.number_format($list['prize'],2).'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$this->publishContent($list['qty']).' '.$product_unit.'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$tax_info.'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">Rs.'.number_format($igst_Amt,2).'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: right;">Rs.'.number_format($list['sub_total'],2).'</td>
						</tr>
					 
			          ';
            $i++;
            }
        }
        return $layout;
    }

    // Get Vendor payouts

     function getVendorPaidlist($from="",$to="")
    {
        $layout ="";

        if($from!="" && $from!=0)
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND DATE(PA.created_at) BETWEEN '$from' AND '$to'";
        } else {
            $date_filter = "";
        }


        $query  ="SELECT PA.id,PA.payout_invoice_id,PA.vendor_id,PA.net_payable,PA.total_order_value,PA.total_commission,PA.created_at,VE.company,
        (SELECT COUNT(id) FROM ".VENDOR_PAYOUT_INVOICE_ITEM_TBL." WHERE payout_id=PA.id) as total_orders
         FROM ".VENDOR_PAYOUT_INVOICE_TBL." PA LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=PA.vendor_id)  WHERE vendor_id=".$_SESSION["ecom_vendor_id"]." ".$date_filter." ";

        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $payable = number_format(($list['total_order_value'] - $list['net_payable']),2);  ;

                    $layout .="
					            <tr class='nk-tb-item'>
								    <td class='nk-tb-col nk-tb-col-check'>
								         ".$i."
								    </td>
								    <td class='nk-tb-col'>
								        <div class='user-card'>
								           
								            <div class='user-info'>
								                <span class='tb-lead'>".$list['payout_invoice_id']." <span class='dot dot-success d-md-none ml-1'></span></span>
								            </div>
								        </div>
								    </td>
								     <td class='nk-tb-col tb-col-lg vendorInvoiceTd' data-option='".$list['id']."'>
								        <span>".date("d-M-Y",strtotime($list['created_at']))."</span>
								    </td>
								    <td class='nk-tb-col tb-col-mb vendorInvoiceTd' data-option='".$list['id']."'>
								        <span class='tb-amount'>".$list['company']."</span>
								    </td>
								    <td class='nk-tb-col tb-col-md vendorInvoiceTd' data-option='".$list['id']."'>
								        <span>".$list['total_orders']."</span>
								    </td>
								    <td class='nk-tb-col tb-col-lg vendorInvoiceTd' data-option='".$list['id']."'>
								        <ul class='list-status'>
								           <span class='tb-amount'>₹ ".$list['total_order_value']." </span>
								        </ul>
								    </td>
								    <td class='nk-tb-col tb-col-lg vendorInvoiceTd' data-option='".$list['id']."'>
								       <span class='tb-amount'>₹".$payable." </span>
								    </td>
								    <td class='nk-tb-col tb-col-md vendorInvoiceTd' data-option='".$list['id']."'>
								        <span class='tb-amount'>₹ ".number_format($list['net_payable'],2)." </span>

								    </td>
								    <td class='nk-tb-col tb-col-md vendorInvoiceTd' data-option='".$list['id']."'>
								        <span class='badge badge-dot badge-success'>Delivered</span>
								    </td>

								    <td class='nk-tb-col nk-tb-col-tools'>
								        <ul class='nk-tb-actions gx-1'>
								            <li>
								                <div class='drodown'>
								                    <a href='#' class='dropdown-toggle btn btn-icon btn-trigger' target='_blank' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a>
								                    <div class='dropdown-menu dropdown-menu-right'>
								                        <ul class='link-list-opt no-bdr'>
								                            <li><a href='".COREPATH."orders/payoutInvoice/".$list['id']."'><em class='icon ni ni-file'></em><span>Payout Invoice</span></a></li>
								                        </ul>
								                    </div>
								                </div>
								            </li>
								        </ul>
								    </td>
								</tr>
                               
                              ";
                $i++;
                }
        } else {
            $layout .= "";
        }
        return $layout;
    }

     // Id's of paid payouts

    function getVendorPaidRecordIds()
    {
        $layout = "";

        $q = "SELECT id FROM ".VENDOR_PAYOUT_INVOICE_TBL." WHERE vendor_id='".$_SESSION['ecom_vendor_id']."' ";
        $exe = $this->exeQuery($q);
        $paid_payouts_ids = array();
        while($list = mysqli_fetch_array($exe)){
        $paid_payouts_ids[] =    $list['id'];
        }

        if(count($paid_payouts_ids)==0) 
        {
            $paid_payouts_ids[] = 0;
        }

        $q2 = "SELECT id,vendor_order_id FROM ".VENDOR_PAYOUT_INVOICE_ITEM_TBL." WHERE payout_id IN ( '" . implode( "', '" , $paid_payouts_ids ) . "' ) ";
        $exe2 = $this->exeQuery($q2);
        $unpaid_order_ids = array();
        while($list = mysqli_fetch_array($exe2)){
        $unpaid_order_ids[] =    $list['vendor_order_id'];
        }

        if(count($unpaid_order_ids)==0) 
        {
            $unpaid_order_ids[] = 0;
        }
        return $unpaid_order_ids;
    }

    // List of unpaid payouts 

    function getVendorUnpaidlist($from="",$to="")
    {
        $layout ="";

        if($from!="" && $from!=0)
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND DATE(VO.created_at) BETWEEN '$from' AND '$to'";
        } else {
            $date_filter = "";
        }

        $unpaid_order_ids = $this->getVendorPaidRecordIds();
        $query  ="SELECT VO.id,VO.vendor_id,VO.vendor_payment_total,VO.vendor_shipping_total,VO.vendor_commission_total,VO.total_amount,VO.order_date,VO.order_uid,VO.order_id,VO.order_status,VE.name as vendor_name,VE.company,VE.mobile as vendor_mobile,VE.email as vendor_email,C.name as cus_name FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) LEFT JOIN ".CUSTOMER_TBL." C ON (C.id=VO.user_id) WHERE VO.order_status=2 AND VO.id NOT IN ( '" . implode( "', '" , $unpaid_order_ids ) . "' ) AND VO.vendor_id='".$_SESSION['ecom_vendor_id']."' $date_filter ";  

        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $commission = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
                    $payable = $list['total_amount'] - $commission;

                    $layout .="
                                <tr class='tb-tnx-item'>
                                    <td class='tb-tnx-id'>
                                            ".$i.".
                                        </div>
                                    </td>
                                     <td class='tb-tnx-info'>
                                        <div class='tb-tnx-total'>
                                           <span class='amount'>".$list['order_uid']."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                            <span class='amount'>".date("d-M-Y",strtotime($list['order_date']))."</span>
                                        </div>
                                    </td>
                                    <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>".$list['company']."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                           <span class='amount'>".$list['cus_name']."</span>
                                        </div>
                                    </td>
                                     <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>₹ ".$list['total_amount']."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                           <span class='amount'>₹ ".$this->inrFormat($commission,2)."</span>
                                        </div>
                                    </td>
                                    <td class='tb-tnx-amount'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>₹ ".$this->inrFormat($payable,2)."</span>
                                        </div>
                                        <div class='tb-tnx-status'>
                                            <span class='badge badge-dot badge-warning'>Due</span>
                                        </div>
                                    </td>
                                </tr>
                              ";
                $i++;
                }
        } else {
            $layout .= "<td colspan='5 text-center' >No Records</td>";
        }
        return $layout;
    }

    function getPayoutorderRecordIds($payout_id="")
    {
        $q = "SELECT vendor_order_id FROM ".VENDOR_PAYOUT_INVOICE_ITEM_TBL." WHERE payout_id='".$payout_id."' ";
        $exe = $this->exeQuery($q);
        $vendor_order_ids = array();
        while($list = mysqli_fetch_array($exe)){
        $vendor_order_ids[] =    $list['vendor_order_id'];
        }
        if(count($vendor_order_ids)==0) 
        {
            $vendor_order_ids[] = 0;
        }
        
        return $vendor_order_ids;
    }

    function manageVendorPayouts($payout_id="")
    {
       $layout ="";
       $payout_info = $this->getDetails(VENDOR_PAYOUT_INVOICE_TBL,"*"," id='".$payout_id."' ");
       $vendor_order_ids = $this->getPayoutorderRecordIds($payout_id);
        

        $query  ="SELECT VO.id,VO.vendor_id,VO.vendor_payment_total,VO.vendor_shipping_total,VO.vendor_commission_total,VO.total_amount,VO.order_date,VO.order_uid,VO.order_id,VE.name as vendor_name,VE.mobile as vendor_mobile,VE.email as vendor_email,C.name as cus_name,VI.vendor_commission,VI.vendor_commission_tax,VI.vendor_payment_charge,VI.vendor_payment_tax,VI.vendor_shipping_charge,VI.vendor_shipping_tax,
             (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id  ) as totalOrders,
             (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) ) as totalAmount,
             (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) ) as totalPayment,
             (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) ) as totalShipping,
             (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id AND id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) )  as totalCommission,
             (SELECT SUM(vendor_commission_tax_amt) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_id=VO.vendor_id AND vendor_order_id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) )  as vendor_commission_tax_amt,
             (SELECT SUM(vendor_payment_tax_amt) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_id=VO.vendor_id AND vendor_order_id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) )  as vendor_payment_tax_amt,
             (SELECT SUM(vendor_shipping_tax_amt) FROM ".VENDOR_ORDER_ITEM_TBL." WHERE vendor_id=VO.vendor_id AND vendor_order_id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) )  as vendor_shipping_tax_amt
          FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) LEFT JOIN ".VENDOR_ORDER_ITEM_TBL." VI ON (VI.vendor_order_id=VO.id) LEFT JOIN ".CUSTOMER_TBL." C ON (C.id=VO.user_id) WHERE VO.vendor_id='".$payout_info['vendor_id']."' AND VO.id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) LIMIT 1 ";

        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $layout .='
					 '.$this->manageVendorPayoutList($payout_id).'
					 ';

					$net_payable = $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];
					$payable     = $list["totalAmount"] - $net_payable;


                     $commission_wo_tax = $list['totalCommission'] - $list['vendor_commission_tax_amt'];
                     $payment_wo_tax    = $list['totalPayment'] - $list['vendor_payment_tax_amt'];
                     $shipping_wo_tax   = $list['totalShipping'] - $list['vendor_shipping_tax_amt'];

                    $layout .='
                    	<tr style="background: #F5F5F5;">
							<td colspan="6" style="text-align: right;border-top: 1px solid #222;"><strong>Sub Total</strong></td>
							<td style="text-align: right;border-top: 1px solid #222;">Rs. '.number_format($list["totalAmount"],2).'</td>
			          	</tr>
			          	<tr style="background: #F5F5F5;">
							<td colspan="6" style="text-align: right;"><strong>Zupply Commission </strong> <br>  ('.$list['vendor_commission'].' % Commission + '.$list['vendor_commission_tax'].' % Tax) </td>
							<td style="text-align: right;">Rs. '.number_format($list['totalCommission'],2).'<br> ('.$this->inrFormat($commission_wo_tax).' + '.$this->inrFormat($list['vendor_commission_tax_amt']).') </td>
			         	</tr>

                        <tr style="background: #F5F5F5;">
                            <td colspan="6" style="text-align: right;"><strong>Payment Gateway Charge</strong> <br>  ('.$list['vendor_payment_charge'].' % Charge + '.$list['vendor_payment_tax'].' % Tax) </td>
                            <td style="text-align: right;">Rs. '.number_format($list['totalPayment'],2).'<br> ('.$this->inrFormat($payment_wo_tax).' + '.$this->inrFormat($list['vendor_payment_tax_amt']).') </td>
                        </tr>

                        <tr style="background: #F5F5F5;">
                            <td colspan="6" style="text-align: right;"><strong>Shipping Charge</strong> <br>  ('.$list['vendor_shipping_charge'].' % Charge + '.$list['vendor_shipping_tax'].' % Tax) </td>
                            <td style="text-align: right;">Rs. '.number_format($list['totalShipping'],2).'<br> ('.$this->inrFormat($shipping_wo_tax).' + '.$this->inrFormat($list['vendor_shipping_tax_amt']).') </td>
                        </tr>

                        <tr style="background: #F5F5F5;">
							<td colspan="4" style="border-bottom: 1px solid #222;border-top: 1px solid #5D6975;">Total Amount in Words: <strong>'.$this->amountInWords($payable).'</strong></td>
				  			<td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;"><strong>Vendor Payable</strong></td>
							<td  style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;;"><strong>Rs. '.number_format($payable,2).'</strong></td>
		          		</tr>'
                           ;
                $i++;
                }
        } else {
            $layout .= "<td colspan='5 text-center' >No Records</td>";
        }
        return $layout;
    }

    function manageVendorPayoutList($payout_id="")
    {
       $layout ="";
       $payout_info = $this->getDetails(VENDOR_PAYOUT_INVOICE_TBL,"*"," id='".$payout_id."' ");
       $vendor_order_ids = $this->getPayoutorderRecordIds($payout_id);
        

        $query  ="SELECT VO.id,VO.vendor_id,VO.vendor_payment_total,VO.vendor_shipping_total,VO.vendor_commission_total,VO.total_amount,VO.order_date,VO.order_uid,VO.order_id,VE.name as vendor_name,VE.mobile as vendor_mobile,VE.email as vendor_email,C.name as cus_name,
             (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id  ) as totalOrders,
             (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id ) as totalAmount,
             (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id  ) as totalPayment,
             (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id  ) as totalShipping,
             (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id=VO.vendor_id )  as totalCommission
          FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) LEFT JOIN ".CUSTOMER_TBL." C ON (C.id=VO.user_id) WHERE vendor_id='".$payout_info['vendor_id']."' AND VO.id  IN ( '" . implode( "', '" , $vendor_order_ids ) . "' ) ";

        $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);


      //                $net_payable = $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];
					 // $payable     = $list["totalAmount"] - $net_payable;

					$payable 	 = $list['total_amount']- $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
					$net_payable = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
                   

                    $layout .='
                    	<tr>
	                        <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$list['order_uid'].'</td>
	                        <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: left;">'.date("d-m-Y",strtotime($list['order_date'])).'</td>
	                        <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$list['cus_name'].'</td>
	                        <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">Rs.'.number_format($list['total_amount'],2).'</td>
	                        <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;"> Rs. '.number_format($net_payable,2).'</td>
	                        <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">Rs.'.number_format($payable,2).'</td>
	                        <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: right;">Paid</td>
	                        
                        </tr>';
                $i++;
                }
        } else {
            $layout .= "<td colspan='5 text-center' >No Records</td>";
        }
        return $layout;
    }

 

  	/*---------------------------------------
		   Vendor Deelivery Locations
	----------------------------------------*/

	// Get vendor location group

	function locationsDatas()
  	{
  		$layout 	  = "";
	    $vendor_info  = $this->getDetails(VENDOR_TBL,"delivery_locations,delivery_cities,delivery_location_groups", " id='".$_SESSION['ecom_vendor_id']."' ");

	    if($vendor_info['delivery_cities']!="") {

		    $q = "SELECT id,token,group_name,status,delete_status FROM ".LOCATIONGROUP_TBL." WHERE id IN (".$vendor_info['delivery_cities'].")  AND delete_status='0' AND status='1'  GROUP BY id ASC " ;
		    $query  	      = $this->exeQuery($q);
		    $vendor_locations = explode(",", $vendor_info['delivery_locations']);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)){
		    		$list    = $this->editPagePublish($row);
		    		$checked = (in_array($list['id'], $vendor_locations)) ? 'checked' :'';
		    		$layout .= "
		    			<div class='location_parent'>
	                		<label class='checkbox-inline checkbox-success heading'>
	                   		<input name='location_group[]' value='".$list['id']."' data-option='".$list['id']."' class='post_permission main_permission' id='main_".$list['id']."' type='checkbox' $checked > ".$list['group_name']." </label>
	                    	<span class='clearfix'></span>
	                    	<div class='menu location_menu' >
	                    		<ul>
	                    			".$this->getlocations($list['id'],$vendor_info['delivery_location_groups'])."
	                    		</ul>
	                   		</div>
	                    </div>";
	                $i++;
		    	}
		    }

	    } else {
	    	return 0;
	    }
	    return $layout;
  	}

  	// Get location under location group

  	function getlocations($city_id,$group_ids)
  	{	
  		$layout = "";

  		$q = "SELECT L.id,L.token,L.location,L.pincode,L.longitude,L.latitude,L.group_id,L.status,L.delete_status,LG.id as city_id,LG.group_name as city,G.group_name 
  				FROM  ".LOCATION_TBL." L LEFT JOIN ".GROUP_TBL." G ON (G.id=L.group_id)
									     LEFT JOIN ".LOCATIONGROUP_TBL." LG ON (LG.id=G.city_id)
				WHERE  L.group_id IN (".$group_ids.") AND LG.id='$city_id' AND L.status='1' AND L.delete_status='0'";

	    $query 	= $this->exeQuery($q);
	    if(mysqli_num_rows($query)>0){
	    	$i=1;
	    	while($row = mysqli_fetch_array($query)){
	    		$list 	       = $this->editPagePublish($row);
	    		$vendor_info   = $this->getDetails(VENDOR_TBL,"delivery_areas", " id='".$_SESSION['ecom_vendor_id']."' ");
	    		$vendor_areas  = explode(",", $vendor_info['delivery_areas']);
	    		$checked = (in_array($list['id'], $vendor_areas)) ? 'checked' :'';
	    		$layout .= "
	    			<li>
	    			 <label class='checkbox-inline checkbox-success sub_menu'>
	    			 <input name='location_area[]' value='".$list['id']."' data-option='".$list['city_id']."' class='post_permission  sub_menu_permission sub_permission_".$list['city_id']."' type='checkbox' $checked>  ".$list['location']."</label>
	    			 </li>
	    		";
                $i++;
	    	}
	    }
	    return $layout;
  	}

  	// Update vendor delivery location

  	function updateVendorLocations($input) 
  	{	
		$validate_csrf  = $this->validateCSRF($input);

		if ($validate_csrf=="success") {
			$vendor_id 		= $_SESSION["ecom_vendor_id"];
			$curr 			= date("Y-m-d H:i:s");
			if(isset($input['location_group'])) {
				$vendor_location_group = implode(",", $input['location_group']);
				$vendor_location_area  = implode(",", $input['location_area']);

				$q = "UPDATE ".VENDOR_TBL." SET 
					  delivery_locations = '".$vendor_location_group."',  
					  delivery_areas     = '".$vendor_location_area."',
					  updated_at = '".$curr."'  WHERE id='".$vendor_id."'";
				$exe = $this->exeQuery($q);

			} else {
				return "Please select at least one delivery Location";
			}
  		}else{
			return $validate_csrf;
		}
		return 1;
  	}

  	// Datas for card box in order reports page

    function getOrderReportCardData($from="",$to="",$home="") 
    {
        $result    = array();
        $vendor_id = $_SESSION["ecom_vendor_id"]; 

        if($from!="" && $to!="") 
        {	
        	$from = date("Y-m-d",strtotime($from));
    		$to   = date("Y-m-d",strtotime($to));
        	$date_filter = "AND DATE(order_date) BETWEEN '$from' AND '$to' ";
        	$rejected_date_filter =   "AND DATE(updated_at) BETWEEN '$from' AND '$to' " ;
        } else {
        	$current_date    = date("Y-m-d");
            $last_seven_days = date("Y-m-d", strtotime("-7 days", strtotime($current_date)));
            $date_filter = "AND DATE(order_date)=CURDATE() ";
            $rejected_date_filter = "AND DATE(updated_at)=CURDATE()";
        }

        $q  = "SELECT id as order_id, SUM(total_amount) as overAllamount,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND  vendor_id='".$vendor_id."' ".$date_filter." ) as totalOrders,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE  vendor_response='1' AND vendor_accept_status='1' AND order_status < 2 AND  vendor_id='".$vendor_id."' ".$date_filter." ) as activeOrders,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='0'  AND vendor_response='0'  AND  vendor_id='".$vendor_id."' ".$date_filter." ) as pendingOrders,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='3' AND  vendor_id='".$vendor_id."' ".$date_filter." ) as returnedOrders,
                    (SELECT count(id) FROM ".VENDOR_PRODUCTS_TBL." WHERE vendor_id='".$vendor_id."' AND stock > max_qty  ) as instock_count,
                    (SELECT count(id) FROM ".VENDOR_PRODUCTS_TBL." WHERE vendor_id='".$vendor_id."' AND stock < max_qty AND stock > min_qty ) as low_stock_count,
                    (SELECT count(id) FROM ".VENDOR_PRODUCTS_TBL." WHERE vendor_id='".$vendor_id."' AND stock < min_qty ) as out_of_stock_count,
                    (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND  vendor_id='".$vendor_id."' ".$date_filter." ) as totalAmount,
                    (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND  vendor_id='".$vendor_id."' ".$date_filter." ) as vendorCommissionAmt,
                    (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND  vendor_id='".$vendor_id."' ".$date_filter." ) as vendorPayChargeAmt,
                    (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND  vendor_id='".$vendor_id."' ".$date_filter." ) as vendorShipChargeAmt,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE vendor_response='1' AND vendor_accept_status='0' AND  vendor_id='".$vendor_id."' ".$rejected_date_filter." ) as vendorRejectedOrders,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND  vendor_id='".$vendor_id."' AND DATE(order_date)=CURDATE() ) as todaysOrdersCount,
                       (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND  vendor_id='".$vendor_id."' AND DATE(order_date)=CURDATE() ) as todaysSalesAmt,
                    (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND  vendor_id='".$vendor_id."' AND DATE(order_date)=CURDATE() ) as todaysCommissionAmt,
                    (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND  vendor_id='".$vendor_id."'  AND DATE(order_date)=CURDATE() ) as todaysPayChargeAmt,
                    (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND  vendor_id='".$vendor_id."' AND DATE(order_date)=CURDATE() ) as todaysShipChargeAmt

               FROM ".VENDOR_ORDER_TBL."  WHERE 1 ";
        $exe = $this->exeQuery($q);
        $list = mysqli_fetch_array($exe);
        return $list;
    }

     // Inventory Product List design
    function  getVendorStockList() 
    {	
    	$vendor_id  = $_SESSION['ecom_vendor_id'];
        $unique     = array_unique($this->getVendorProductsuniqe());
        if(count($unique)==0) 
        {
            $unique[] = 0;
        }
        $layout                 = array();
        $layout['out_of_stock'] = "";
        $layout['low_stock']    = "";
        $layout['instock']      = "";

        $q = "SELECT  P.id,P.product_name,P.has_variants,VP.selling_price as vendor_sell_price,P.actual_price,P.selling_price,P.category_type, P.sku, P.main_category_id,P.sub_category_id,P.tax_class,P.delete_status,P.is_draft, P.stock,P.min_order_qty,P.max_order_qty,T.tax_class as taxClass , M.id as mainCatId, M.category,S.id as subCatId, S.subcategory,VP.stock as vendorStock,VP.id as vendor_assigned_id,VP.max_qty,VP.min_qty,
            (SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
            (SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image,
            (SELECT SUM(stock) FROM ".VENDOR_PRODUCTS_TBL." WHERE product_id=P.id AND vendor_id='".$vendor_id."') as t_stock_in_tis_prd 
          FROM ".PRODUCT_TBL." P LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id)
            LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=P.main_category_id)
            LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=P.sub_category_id)
            LEFT JOIN ".VENDOR_PRODUCTS_TBL." VP ON (P.id=VP.product_id AND vendor_id='".$vendor_id."')
          WHERE P.is_draft='0' AND P.delete_status='0' AND VP.id!='' AND P.status='1'  GROUP BY P.id DESC" ;

        $exe = $this->exeQuery($q);
         if(mysqli_num_rows($exe)>0){
            $i=1;
            $j=1;
            while($rows = mysqli_fetch_array($exe)){
                $list = $this->editPagePublish($rows);

                // Category
                $category_name = (($list['category_type']=="sub") ? $list['subcategory'] : $list['category'] );

                // Product Image
                if ($list['default_product_image']!="") {
                    $product_image = $list['default_product_image']!='' ? UPLOADS.$list['default_product_image'] : ASSETS_PATH."no_img.jpg" ;# code...
                }else{
                    $product_image = $list['product_image']!='' ? UPLOADS.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
                }

                $has_variants = ($list['has_variants']!=0)? 1 : 0 ;

                if($list['vendorStock'] >= $list['max_qty'])  {
                    $stock_sts      =  "Instock";
                    $stock_sts_cls  =  "text-success";
                    $stock_layout   =  "instock";
                } elseif ($list['vendorStock'] < $list['max_qty'] && $list['vendorStock'] > $list['min_qty']   ) {
                    $stock_sts      =  "Low Stock";
                    $stock_sts_cls  =  "text-warning";
                    $stock_layout   =  "low_stock";
                } elseif ($list['vendorStock'] < $list['min_qty']) {
                    $stock_sts      =  "Out Of Stock";
                    $stock_sts_cls  =  "text-danger";
                    $stock_layout   =  "out_of_stock";
                } elseif ($list['vendorStock'] == $list['min_qty']) {
                    $stock_sts      =  "Low Stock";
                    $stock_sts_cls  =  "text-warning";
                    $stock_layout   =  "low_stock";
                }

                $layout[$stock_layout] .= "
                    <tr class='nk-tb-item open_inventory_stock' data-option='".$list['id']."'>
                        <td class='nk-tb-col'>
                            ".$i."
                        </td>
                        <td class='nk-tb-col'>
                            <img src='".$product_image."' width=50 />
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            <span class='text-primary'>".$list['product_name']."</span>
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            ".$this->publishContent($category_name)."
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                           ₹ ".$this->inrFormat($list['actual_price'])."
                        </td>
                       <td class='nk-tb-col tb-col-md'>
                           ₹ ".$this->inrFormat($list['vendor_sell_price'])."
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                           ".$list['t_stock_in_tis_prd']." in stock
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                           <span class='badge badge-dot $stock_sts_cls cursor_pointer'>$stock_sts</span>
                        </td>
                      
                    </tr> ";


                
                $i++;
            }
        }
        return $layout;
    }

    // Datas for chart in order reports page

    function getOrderReportChartData($from="",$to="") 
    {
        $result = array();
        
        $vendor_check = "AND  vendor_id='".$_SESSION["ecom_vendor_id"]."' ";

        if($from!="" && $from!=0) 
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND order_date BETWEEN '$from' AND '$to' ";

            // For sales vs order chart

            $dates                = array();
            $daily_total          = array();
            $current              = strtotime($from);
            $date2                = strtotime($to);
            $stepVal              = '+1 day';
                
            while( $current <= $date2 ) {
                $dates[] ='"'.date("d-M", $current).'"';
                $q_date  = date("Y-m-d",$current);
                $current = strtotime($stepVal, $current);
                $q       = "SELECT SUM(total_amount) as dailyTotal FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND 
                            DATE(order_date) = '".$q_date."' ".$vendor_check." ";
                $exe     =  $this->exeQuery($q);
                $list    = mysqli_fetch_array($exe);
                $daily_total[] = (($list['dailyTotal']=="")? 0 : $list['dailyTotal'] );
            }

           $x_axis       = "[". implode(",",$dates) ."]" ;
           $y_axis_sales = "[". implode(",",$daily_total) ."]" ;

        } else {
            $date_filter = "AND DATE(order_date)=CURDATE()";

            // For sales vs order chart

            $months = array();
            $monthly_order_totals = array();

            $now = date('Y-F');
            for($x = 11; $x >= 0; $x--) {
                $ym = date('M', strtotime($now . " -$x month"));
                $y  = date('Y', strtotime($now . " -$x month"));
                $m  = date('m', strtotime($now . " -$x month"));
                $months[$x+1] = '"'.$ym.'"';

                $q= "SELECT SUM(total_amount) as monthlyOrderTotal FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND MONTH(order_date) = '".$m."' AND YEAR(order_date) = '".$y."' ".$vendor_check." ";

                $exe  =  $this->exeQuery($q);
                $list = mysqli_fetch_array($exe);
                $monthly_order_totals[$m]    = (($list['monthlyOrderTotal']=="")? 0 : $list['monthlyOrderTotal'] );
            }

             $q_h = "SELECT SUM(total_amount) as orders_at_12am,
              (SELECT SUM(total_amount)  FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:01 AM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."') as orders_at_12am_to_12pm,
              (SELECT SUM(total_amount)  FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at BETWEEN '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 PM"))."' AND '".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 11:59 PM"))."') as orders_at_12pm_to_12am
              FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." AND created_at='".date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 12:00 AM"))."' "; 

            $exe_h = $this->exeQuery($q_h);
            $list_h = mysqli_fetch_array($exe_h);

            $y_axis_data = array();
            $y_axis_data[] = (($list_h['orders_at_12am'])? $list_h['orders_at_12am'] : 0);
            $y_axis_data[] = (($list_h['orders_at_12am_to_12pm'])? $list_h['orders_at_12am_to_12pm'] : 0);
            $y_axis_data[] = (($list_h['orders_at_12pm_to_12am'])? $list_h['orders_at_12pm_to_12am'] : 0);
            if($y_axis_data[2]==0) {
            	unset($y_axis_data[2]);
            }

            $x_axis        = '["12 am","12 pm", "12am"]' ;
            $y_axis_sales       = "[". implode(",",$y_axis_data) ."]" ;
            // $y_axis_sales = '["'.(($list_h['orders_at_12am'])? $list_h['orders_at_12am'] : 0).'","'.(($list_h['orders_at_12am_to_12pm'])? $list_h['orders_at_12am_to_12pm'] : 0).'", "'.(($list_h['orders_at_12pm_to_12am'])? $list_h['orders_at_12pm_to_12am'] : 0).'"]' ;

        }

        

        $q  = "SELECT id,SUM(total_amount) as totalAmount,SUM(vendor_commission_total) as vendorCommissionAmt,SUM(vendor_payment_total) as   
                    vendorPayChargeAmt,SUM(vendor_shipping_total) as vendorShipChargeAmt,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='1' ".$vendor_check." ".$date_filter.") as inprocess,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='2' ".$vendor_check." ".$date_filter.") as delivered,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='3' ".$vendor_check." ".$date_filter.") as returnedOrders,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='4' ".$vendor_check." ".$date_filter.") as cancelledOrders,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_device='website' ".$vendor_check." ".$date_filter.") as website,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_device='android' ".$vendor_check." ".$date_filter.") as android,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_device='ios' ".$vendor_check." ".$date_filter.") as ios,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." ".$date_filter." ) as totalOrders,
                    (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status='3' ".$vendor_check." ".$date_filter." ) as returnedOrders,
                    (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' ".$vendor_check." ) as overAllamount
               FROM ".VENDOR_ORDER_TBL."  WHERE order_status!='0' AND order_status!='1' ".$vendor_check." ".$date_filter." ";
        $exe = $this->exeQuery($q);
        $list = mysqli_fetch_array($exe);
        
        

        $pie_chart_data_ar = array($list['website'],$list['android'],$list['ios']);
        $pie_chart_data = "[". implode(",",$pie_chart_data_ar) ."]" ;

        $result['count_data']               = $list; 
        $result['x_axis']                   = $x_axis; 
        $result['y_axis_sales']             = $y_axis_sales; 
        $result['sales_platform_true']      = array_sum($pie_chart_data_ar); 
        $result['sales_platform_data']      = $pie_chart_data;

        return $result;
    }

    // Get top vendors

    function getTopProductList($from="",$to="")
    {
        $layout    = "";
        $vendor_id = $_SESSION["ecom_vendor_id"]; 

         if($from!="")
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));   
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to' ";
        } else {
            $date_filter = "AND DATE(VO.order_date)=CURDATE()";
        }

   		$q  = "SELECT VI.id,VI.product_id, COUNT(VI.product_id) AS MOST_FREQUENT,P.product_name,P.category_type,P.main_category_id,VO.order_date,
               P.sub_category_id FROM ".VENDOR_ORDER_ITEM_TBL." VI LEFT JOIN ".PRODUCT_TBL." P ON (P.id=VI.product_id) LEFT JOIN ".ORDER_TBL." 
               VO ON (VO.id=VI.order_id) WHERE VI.order_status='2' ".$date_filter." AND VI.vendor_id=".$vendor_id." GROUP BY VI.product_id ORDER BY COUNT(VI.product_id) DESC";
        $exe    = $this->exeQuery($q);

        if(mysqli_num_rows($exe)>0)
        {   
            $i=1;
            while ($list = mysqli_fetch_array($exe)) {

                if($list['category_type']=="main") 
                {
                    $category_detail = $this->getDetails(MAIN_CATEGORY_TBL,"category","id='".$list['main_category_id']."'");
                    $category        = $category_detail['category'];
                } else {
                    $category_detail 	  = $this->getDetails(SUB_CATEGORY_TBL,"subcategory,category_id","id='".$list['sub_category_id']."'");
                    $main_category_detail = $this->getDetails(MAIN_CATEGORY_TBL,"category","id='".$category_detail['category_id']."'");
                    $category             = $main_category_detail['category'] ." - ". $category_detail['subcategory'];
                }

                // $layout  .="
                //     <tr class='nk-tb-item'>
                //         <td class='nk-tb-col'>
                //             <span class='tb-lead'><a href='#'>".$i."</a></span>
                //         </td>
                //         <td class='nk-tb-col tb-col-sm'>
                //             <div class='user-card'>
                //                 <div class='user-name'>
                //                     <span class='tb-lead'>".$list['product_name']."</span>
                //                 </div>
                //             </div>
                //         </td>
                //         <td class='nk-tb-col tb-col-md'>
                //             <span class='tb-sub'>".$category."</span>
                //         </td>
                //     </tr> ";

                 $layout  .="
                    <tr class='tb-tnx-item'>
                                    <td class='tb-tnx-id'>
                                        <a href='#'><span>".$i."</span></a>
                                    </td>
                                    <td class='tb-tnx-info'>
                                        <div class='tb-tnx-total'>
                                            <span class='title'>".$list['product_name']."</span>
                                        </div>
                                    </td>
                                    <td class='tb-tnx-info'>
                                        <div class='tb-tnx-total'>
                                            <span class='amount'>".$category."</span>
                                        </div>
                                    </td>
                                </tr>";
            $i++;
            }

        } else {
                $layout .= "
                            <tr class='tb-tnx-item text-center' >
                                    <td class='tb-tnx-id' colspan='6'>
                                            <span class='title ' >No Records</span>
                                    </td>
                            ";

        }

        return $layout;
    }

    // Get top vendors

    function getTopVendorList()
    {
        $layout    = "";
        $vendor_id = $_SESSION["ecom_vendor_id"]; 

        $q      = " SELECT VO.id,VO.total_amount,VE.company,SUM(VO.total_amount) as totalOrderValue1,SUM(VO.vendor_commission_total) as 
                     totalCommissionValue,SUM(VO.vendor_payment_total) as totalPaymentValue, SUM(VO.vendor_shipping_total) as totalShippingValue,
                     (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE order_status!='0' AND order_status!='1' AND vendor_id=VE.id) as totalOrders,
                     (SELECT SUM(total_commission) FROM ".VENDOR_PAYOUT_INVOICE_TBL." WHERE  vendor_id=VE.id) as totalPayuotCommission
                    FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) WHERE VO.order_status!='0' AND VO.order_status!='1' GROUP BY VO.vendor_id ORDER BY VO.total_amount DESC ";
        $exe    = $this->exeQuery($q);

        if(mysqli_num_rows($exe)>0)
        {   
            $i=1;
            while ($list = mysqli_fetch_array($exe)) {
                $totalCommission = $list['totalCommissionValue'] + $list['totalPaymentValue'] + $list['totalShippingValue'];
                $layout  .="
                    <tr class='nk-tb-item'>
                        <td class='nk-tb-col'>
                            <span class='tb-lead'><a href='#'>".$i."</a></span>
                        </td>
                        <td class='nk-tb-col tb-col-sm'>
                            <div class='user-card'>
                                <div class='user-name'>
                                    <span class='tb-lead'>".$list['company']."</span>
                                </div>
                            </div>
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            <span class='tb-sub'>".$list['totalOrders']."</span>
                        </td>
                        <td class='nk-tb-col tb-col-lg'>
                            <span class='tb-sub tb-amount'>₹ ".$this->inrFormat((($list['totalOrderValue1']!="")? $list['totalOrderValue1'] : 0),2)."</span>
                        </td>
                        <td class='nk-tb-col'>
                            <span class='tb-sub tb-amount'>₹ ".$this->inrFormat($totalCommission ,2)."</span>
                        </td>
                        <td class='nk-tb-col'>
                            <span class='tb-sub tb-amount text-success'>₹ ".$this->inrFormat($list['totalPayuotCommission'] ,2)."</span>
                        </td>
                        <td class='nk-tb-col nk-tb-col-tools'>
                            <ul class='nk-tb-actions gx-1'>
                                
                                <li>
                                    <div class='drodown'>
                                        <a href='#' class='dropdown-toggle btn btn-icon btn-trigger' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a>
                                        
                                    </div>
                                </li>
                            </ul>
                        </td>
                    </tr> ";
                    $i++;
            }

        }

        return $layout;
    }

    // Get Customer Order list

    function getCustomerOrderList($from="",$to="")
    {
        $layout    = "";
        $vendor_id = $_SESSION["ecom_vendor_id"]; 

        if($from!="")
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));   
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to' ";
        } else {
            $date_filter = "AND DATE(VO.order_date)=CURDATE()";
        }

        $q      = "SELECT VO.id,VO.order_id,VO.total_amount,VO.order_date,VO.user_id,VO.order_uid,VO.vendor_id,VO.vendor_commission_total,VO.vendor_payment_total,VO.vendor_shipping_total,VE.company,CU.name,CU.email,CU.mobile FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) LEFT JOIN ".CUSTOMER_TBL." CU ON (CU.id=VO.user_id) WHERE VO.order_status!='0' AND VO.order_status!='1' AND  VO.vendor_id='".$vendor_id."' ".$date_filter." ORDER BY VO.id DESC";
        $exe    = $this->exeQuery($q);

        if(mysqli_num_rows($exe)>0)
        {   
            $i=1;
            while ($list = mysqli_fetch_array($exe)) {
                $totalCommission = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
                $payout_check    = $this->check_query(VENDOR_PAYOUT_INVOICE_ITEM_TBL,"id","id='".$list['id']."'");
                $payout_status   = (($payout_check==0)? "<span class='badge badge-dot badge-dot-xs badge-success'>Delivered</span>" : "<span class='badge badge-dot badge-dot-xs badge-warning'>Due</span>" );
 
                $layout  .="
                    <tr class='nk-tb-item open_order_details' data-option='".$list['order_id']."'>
                        <td class='nk-tb-col'>
                            <span class='tb-lead'><a href='#'>".$i."</a></span>
                        </td>
                        <td class='nk-tb-col tb-col-sm'>
                           <div class='user-card'>
                                <div class='user-info'>
                                    <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                </div>
                            </div>
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            <span class='tb-sub'>".date("d-M-y",strtotime($list['order_date']))."</span>
                        </td>
                        <td class='nk-tb-col tb-col-lg'>
                           <span class='tb-lead'>".$list['company']."</span>
                        </td>
                        <td class='nk-tb-col'>
                            <span class='tb-sub tb-amount'>₹ ".$this->inrFormat($list['total_amount'] ,2)."</span>
                        </td>
                        <td class='nk-tb-col'>
                            <span class='tb-sub tb-amount'>₹ ".$this->inrFormat($totalCommission ,2)."</span>
                        </td>
                        <td class='nk-tb-col'>
                            ".$payout_status."
                        </td>
                    </tr> ";
                    $i++;
            }

        }

        return $layout;
    }

     // Get Customer rejected Order list

    function getCustomerRejectedOrderList($from="",$to="")
    {
        $layout    = "";
        $vendor_id = $_SESSION["ecom_vendor_id"]; 

        if($from!="")
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));   
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to' ";
        } else {
            $date_filter = "AND DATE(VO.order_date)=CURDATE()";
        }

        $q      = "SELECT VO.id,VO.order_id,VO.total_amount,VO.order_date,VO.user_id,VO.order_uid,VO.vendor_id,VO.vendor_commission_total,VO.vendor_payment_total,VO.vendor_shipping_total,VO.response_notes,VE.company,CU.name,CU.email,CU.mobile FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) LEFT JOIN ".CUSTOMER_TBL." CU ON (CU.id=VO.user_id) WHERE VO.vendor_response='1' AND VO.vendor_accept_status='0' AND  VO.vendor_id='".$vendor_id."' ".$date_filter." ORDER BY VO.id DESC";
        $exe    = $this->exeQuery($q);

        if(mysqli_num_rows($exe)>0)
        {   
            $i=1;
            while ($list = mysqli_fetch_array($exe)) {
                $totalCommission = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
                $layout  .="
                    <tr class='nk-tb-item rejected_order_details' data-option='".$list['id']."'>
                        <td class='nk-tb-col'>
                            <span class='tb-lead'><a href='#'>".$i."</a></span>
                        </td>
                        <td class='nk-tb-col tb-col-sm'>
                           <div class='user-card'>
                                <div class='user-info'>
                                    <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                </div>
                            </div>
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            <span class='tb-sub'>".date("d-M-y",strtotime($list['order_date']))."</span>
                        </td>
                        <td class='nk-tb-col tb-col-lg'>
                           <span class='tb-lead'>".$list['company']."</span>
                        </td>
                        <td class='nk-tb-col'>
                            <span class='tb-sub tb-amount'>₹ ".$this->inrFormat($list['total_amount'] ,2)."</span>
                        </td>
                        <td class='nk-tb-col'>
                            <span class='tb-sub tb-amount'>₹ ".$this->inrFormat($totalCommission ,2)."</span>
                        </td>
                        <td class='nk-tb-col'>
                            <span class='badge badge-dot badge-dot-xs badge-danger'>Rejected</span>
                        </td>
                         <td class='nk-tb-col'>
                            <span class='tb-sub tb-amount'>".$list['response_notes']."</span>
                        </td>
                        <td class='nk-tb-col nk-tb-col-tools'>
                            <ul class='nk-tb-actions gx-1'>
                               
                                <li>
                                    <div class='drodown'>
                                        <a href='#' class='dropdown-toggle btn btn-icon btn-trigger' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a>
                                        
                                    </div>
                                </li>
                            </ul>
                        </td>
                    </tr> ";
                    $i++;
            }

        }

        return $layout;
    }

    // Get Customer Returned Order list

    function getCustomerReturnedOrderList($from="",$to="")
    {
        $layout    = "";

        if($from!="" && $from!=0)
        {   
            $from = date("Y-m-d",strtotime($from));
            $to   = date("Y-m-d",strtotime($to));
            $date_filter = "AND VO.order_date BETWEEN '$from' AND '$to'";
        } else {
            $date_filter = "AND DATE(VO.order_date)=CURDATE()";
        } 

        $vendor_id = $_SESSION["ecom_vendor_id"]; 

         $q      = "SELECT VI.id,VI.vendor_order_id,VI.order_status,VI.delivery_date,VI.return_reason,VI.return_date,VO.total_amount,VO.order_date,VO.user_id,VO.order_uid,VO.order_id,VO.vendor_id,VO.vendor_commission_total,VO.vendor_payment_total,VO.vendor_shipping_total,VE.company,CU.name,CU.email,CU.mobile,R.return_reason as returnReason FROM ".VENDOR_ORDER_ITEM_TBL." VI 
                    LEFT JOIN ".VENDOR_ORDER_TBL." VO ON (VO.id=VI.vendor_order_id) 
                    LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) 
                    LEFT JOIN ".CUSTOMER_TBL." CU ON (CU.id=VO.user_id)
                    LEFT JOIN ".RETURN_REASON_TBL." R ON (VI.return_reason=R.id)
            WHERE VI.order_status='3' ".$date_filter." AND VI.vendor_id=".$vendor_id."  ORDER BY VO.id DESC ";
        $exe    = $this->exeQuery($q);

        if(mysqli_num_rows($exe)>0)
        {   
            $i=1;
            while ($list = mysqli_fetch_array($exe)) {
                $totalCommission = $list['vendor_commission_total'] + $list['vendor_payment_total'] + $list['vendor_shipping_total'];
 
                $layout  .="
                    <tr class='nk-tb-item open_order_details' data-option='".$list['order_id']."'>
                        <td class='nk-tb-col'>
                            <span class='tb-lead'><a href='#'>".$i."</a></span>
                        </td>
                        <td class='nk-tb-col tb-col-sm'>
                           <div class='user-card'>
                                <div class='user-info'>
                                    <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                </div>
                            </div>
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            <span class='tb-sub'>".date("d-M-y",strtotime($list['order_date']))."</span>
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            <span class='tb-sub'>".date("d-M-y",strtotime($list['delivery_date']))."</span>
                        </td>
                        <td class='nk-tb-col tb-col-lg'>
                           <span class='tb-lead'>".$list['company']."</span>
                        </td>
                        <td class='nk-tb-col'>
                            <span class='tb-sub tb-amount'>₹ ".$this->inrFormat($list['total_amount'] ,2)."</span>
                        </td>
                        <td class='nk-tb-col'>
                            <span class='tb-sub tb-amount'>".$list['returnReason']."</span>
                        </td>
                        <td class='nk-tb-col'>
                           <span class='tb-sub'>".date("d-M-y",strtotime($list['return_date']))."</span>
                        </td>
                    </tr> ";
                    $i++;
            }

        }

        return $layout;
    }


    /*-------------------------------------------
				Product Request
    -------------------------------------------*/
    
    function manageProductRequest()
    {
		$layout = "";

		$vendor_id 	= $_SESSION["ecom_vendor_id"];

		$q = "SELECT VP.id,VP.vendor_id,VP.product_name,VP.description,VP.status,VP.created_at,VP.request_status,RS.request_status as request_status_name FROM ".VENDOR_PRODUCT_REQUEST_TBL." VP LEFT JOIN ".PRODUCT_REQUEST_STATUS_TBL." RS ON (RS.id=VP.request_status)  WHERE  vendor_id=".$vendor_id." ORDER BY id DESC " ;

	    $exe = $this->exeQuery($q);
	    if(mysqli_num_rows($exe)>0){
	    	$i=1;
	    	$j=1;
	    	while($rows = mysqli_fetch_array($exe)){
	    		$list 			  = $this->editPagePublish($rows);
			 	$request_status   = (($list['request_status']==0)? "<span class='badge badge-dot badge-dot-xs badge-warning'>Inprocess</span>" : "<span class='badge  badge-dot-xs badge-info'>".$list['request_status_name']."</span>" );

			$layout .= "
				<tr class='nk-tb-item  open_data_model' data-option='".$list['id']."'>
					<td class='nk-tb-col'>
	                    ".$i."
	                </td>
	                <td class='nk-tb-col tb-col-md'>
	                    <span class='text-primary'>".$list['product_name']."</span>
	                </td>
	                <td class='nk-tb-col tb-col-md'>
	                    ".$this->publishContent($list['description'])."
	                </td>
	                <td class='nk-tb-col tb-col-md'>
                        ".date("d/m/Y", strtotime($list['created_at']))."
                    </td>
	               <td class='nk-tb-col'>
	                    ".$request_status."
	                </td>
	            </tr>";
			$i++;
		}
	    }
	    return $layout;
    }

    function addProductRequest($input)
    {
		$validate_csrf = $this->validateCSRF($input);
		if($validate_csrf=='success') {
			$data      = $this->cleanStringData($input);
			$token 	   = $this->uniqueToken($data['product_name']);
			$vendor_id = $_SESSION['ecom_vendor_id']; 
			$check_token = $this->check_query(VENDOR_PRODUCT_REQUEST_TBL,"id"," token='$token' ");
			if ($check_token==0) {
				$curr 			= date("Y-m-d H:i:s");
				$query = "INSERT INTO ".VENDOR_PRODUCT_REQUEST_TBL." SET 
							token 			= '".$this->hyphenize($token)."',
							product_name 	= '".$data['product_name']."',
							description 	= '".$data['description']."',
							vendor_id       = '".$vendor_id."',
							status			= '0',
							created_at 		= '$curr',
							updated_at 		= '$curr' ";
				$exe 	= $this->exeQuery($query);
				if($exe){
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}	
			}else{
				return "The product name already exists. ";
			}
		}else{
			return $validate_csrf;
		}
    }

    function editProductRequest($input)
    {
		$validate_csrf = $this->validateCSRF($input);
		if($validate_csrf=='success') {
			$data      = $this->cleanStringData($input);
			$token 	   = $this->uniqueToken($data['product_name']);
			$id        = $this->decryptData($data['token']);
			$check_token = $this->check_query(VENDOR_PRODUCT_REQUEST_TBL,"id"," token='$token' AND id!='".$id."' ");
			if ($check_token==0) {
				$curr 			= date("Y-m-d H:i:s");
				$query = "UPDATE ".VENDOR_PRODUCT_REQUEST_TBL." SET 
							token 			= '".$this->hyphenize($token)."',
							product_name 	= '".$data['product_name']."',
							description 	= '".$data['description']."',
							updated_at 		= '$curr' 
							WHERE id='".$id."' ";
				$exe 	= $this->exeQuery($query);
				if($exe){
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}	
			}else{
				return "The product name already exists. ";
			}
		}else{
			return $validate_csrf;
		}
    }

    // Get product Request Details

	function getproductRequestDetails($request_id)
	{	
		$layout = "";
		$info 	= $this->getDetails(VENDOR_PRODUCT_REQUEST_TBL,"*"," id='".$request_id."' ");
		$v_info	= $this->getDetails(VENDOR_TBL,"*"," id='".$info['vendor_id']."' ");
		$info 	= $this->editPagePublish($info);
		$v_info = $this->editPagePublish($v_info);

		$status = (($info['status']==1) ? "Approved" : "Pending"); 
		$status_class = (($info['status']==1) ? "text-success" : "text-warning");


		$layout .="
					<div class='nk-block'>
	                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                            <div class='profile-ud wider'>
	                                <span class='profile-ud-label'>Vendor Name</span>
	                                <span class='profile-ud-value enq_name_align'>".$v_info['name']."</span>
	                            </div>
	                        </div>
	                        <div class='profile-ud-item'>
	                            <p class='float-right model_pt'>
                            		<button  class='btn btn-primary edit_product_request' data-option='".$info['id']."' > Edit</button>
								</p>
	                        </div>
	                    </div>
	                     <div class='profile-ud-list'>
	                       <div class='profile-ud-item '>
	                            <div class='profile-ud wider'>
	                                <span class='profile-ud-label'>Mobile Number</span>
	                                <span class='profile-ud-value'>".$v_info['mobile']."</span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                             <div class='profile-ud wider'>
	                                <span class='profile-ud-label'>Email Address</span>
	                                <span class='profile-ud-value'>".$v_info['email']."</span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                             <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>Date</span>
	                                <span class='profile-ud-value enq_name_align'>".date("d-M-Y",strtotime($info['created_at']))."</span>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <div class='nk-block'>
	                    <div class='nk-block-head nk-block-head-line'>
	                        <h6 class='title overline-title text-base'>Request</h6>
	                    </div>
	                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                            <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>Product Name</span>
	                                <span class='profile-ud-value enq_name_align'>".$info['product_name']."</span>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <div class='nk-block'>
	                    <div class='nk-block-head nk-block-head-line'>
	                        <h6 class='title overline-title text-base'>Description </h6>
	                    </div>
	                    <div class='enq_message'>
	                        <p>".$info['description']."</p>
	                    </div>
	                </div>
	               
					";
		return $layout;
	}

    // Get product Request Data

	function getproductRequestData($request_id)
	{	
		$layout = "";
		$info 	= $this->getDetails(VENDOR_PRODUCT_REQUEST_TBL,"*"," id='".$request_id."' ");
		$info['token'] = $this->encryptData($info['id']);
		return json_encode($info);
	}







	/*-----------Dont'delete---------*/

	}


?>




