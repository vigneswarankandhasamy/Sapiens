<?php
	require_once 'Model.php';
	require_once 'FileUploader.php';
	require_once 'MultipleFileUploader.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class Product extends Model
	{

		/*--------------------------------------------- 
					Product  Category Management
		----------------------------------------------*/

		// Manage Category 

		function manageCategory() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".MAIN_CATEGORY_TBL." WHERE 1 AND delete_status='0' ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($rows = mysqli_fetch_array($exe)){
 		    		$list = $this->editPagePublish($rows);
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					// Draft Status
					$draft_status = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row = (($list['is_draft']==1) ? "draft_item" : ""); 


					if ($list['is_draft']==1) {
						$draft_action = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishCategory' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class = 'deleteCategory';
						$delete_class_hover = 'Delete Category';

					}else{
						$draft_action = "<div class='tb-tnx-status'>
	                            	<span class='badge badge-dot text-success cursor_pointer cursor_pointer'> Published </span>
	                            </div>";
	                    $delete_class = 'trashCategory';
	                    $delete_class_hover = 'Trash Category';
	                    
					}

					if ($list['is_draft']==0 && $list['status']==1) {
						$preview = "<li class='nk-tb-action-hidden'>
                                        <a href='javascript:void();' class='btn btn-trigger btn-icon' data-toggle='tooltip' data-placement='top' title='View On Website'>
                                            <em class='icon ni ni-eye-fill'></em>
                                        </a>
                                    </li>";
					}else{
						$preview = "";
					}

					$image = (($list['file_name']=='') ? ASSETS_PATH.'file_upload.jpg'  : UPLOADS.$list['file_name']);

					$td_data = "data-data_id='".$list['id']."' data-data_link='category/edit' " ;

		    		$layout .= "
	    				<tr class='nk-tb-item $is_draft_row'>
	    					<td class='nk-tb-col td_click' $td_data>
                                ".$i."
                            </td>
                            <td class='nk-tb-col td_click' $td_data>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col td_click' $td_data>
                               <img src='".$image."' width='50' class='img-thumbnail'/>
                            </td>
                            <td class='nk-tb-col tb-col-md td_click' $td_data>
                                <span class='text-primary'>".$this->publishContent($list['category'])."</span>
	                        </td>
	                        <td class='nk-tb-col td_click' $td_data>
                               	$draft_action
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeCategoryStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                                </div>
	                        </td>
	                       
                            <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                    <li class='nk-tb-action-hidden'>
                                        <a href='javascript:void();' class='btn btn-trigger btn-icon $delete_class' data-toggle='tooltip' data-placement='top' title='$delete_class_hover' data-option='".$this->encryptData($list['id'])."' >
                                            <em class='icon ni ni-trash-fill'></em>
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}

		// Category Vendor Commission Get Tax List

		function getVendorCommissionTax($current="")
	  	{
	  		$layout = "";
	  		$q 		= "SELECT id,tax_class FROM ".TAX_CLASSES_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($rows = mysqli_fetch_array($query)){
					$list = $this->editPagePublish($rows);
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['tax_class']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

		// Add Category 

		function addCategory($input,$image)
		{
			$validate_csrf    	= $this->validateCSRF($input);
			if($validate_csrf == 'success') {
				$data 		  	= $this->cleanStringData($input);
				$category_token = $this->hyphenize($data['title_name']);
				$check_token  	= $this->check_query(MAIN_CATEGORY_TBL,"id"," page_url='".$category_token."' ");
				if ($check_token == 0) {

					$admin_id 		= $_SESSION["ecom_admin_id"];
					$curr 			= date("Y-m-d H:i:s");
					$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;

					// Add Images

					if ($image['is_uploaded']) {
						$file_status = $image['status'];
						$image_q = "
							, file_name 	= '".$image['file_name']."',
							file_type 		= '".$image['file_type']."',
							file_size 		= '".$image['file_size']."',
							file_alt_name 	= '".$data['image_alt_name']."'
						";
					}else{
						$file_status = "ok";
						$image_q = "";
					}	

					if ($file_status=="ok") {
						$query = "INSERT INTO ".MAIN_CATEGORY_TBL." SET 
								page_url 				= '".$category_token."',
								category 				= '".$data['title_name']."',
								sort_order 				= '".$data['sort_order']."',
								description 			= '".$data['description']."',
								vendor_commission 		= '".$data['vendor_commission']."',
								vendor_commission_tax 	= '".$data['vendor_commission_tax']."',
								is_draft 				= '".$save_as_draft."'
								".$image_q." 
								, meta_title 			= '".$data['meta_title']."',
								meta_description 		= '".$data['meta_description']."',
								meta_keyword 			= '".$data['meta_keyword']."',
								status					= '1',
								added_by 				= '$admin_id',	
								created_at 				= '$curr',
								updated_at 				= '$curr' ";
						$exe 	= $this->exeQuery($query);
					}else{
						return $image['message'];
					}
				
					if($exe){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}	

				} else {

					$check_in_trash = $this->check_query(MAIN_CATEGORY_TBL,"id"," page_url='".$category_token."' AND delete_status='1' "); 
					
					if($check_in_trash) {
						return "The entered category already present on the trash. Kindly restore it from the trash section.";
					} else {
						return "The entered category already exists.";
					}

				}
			}else{
				return $validate_csrf;
			}
		}

		// Edit Category 

		function editCategory($input,$image)
		{ 
			$validate_csrf		= $this->validateCSRF($input);
			if($validate_csrf  =='success'){
				$data 			= $this->cleanStringData($input);
				$id 			=	$this->decryptData($data['session_token']);

				$category_name	= $this->hyphenize($data['title_name']);
				$check_token	= $this->check_query(MAIN_CATEGORY_TBL,"id"," page_url='".$category_name."' AND id!='".$id."' ");

				$check_title	= $this->check_query(MAIN_CATEGORY_TBL,"id","category LIKE '%".$data['title_name']."%' AND id!='".$id."' ");

				if (isset($data['save_as_draft'])==1) {
					if ($check_token) {
						$check_in_trash = $this->check_query(MAIN_CATEGORY_TBL,"id"," page_url='".$category_name."' AND id!='".$id."' AND delete_status='1' "); 
					
						if($check_in_trash) {
							return "The entered category already present on the trash. Kindly restore it from the trash section.";
						} else {
							return "The entered category already exists.";
						}
					}

					$token_q = "page_url  = '".$category_name."', ";
				}else{
					$token_q = '';
				}

				if ($check_token == 0 && $check_title == 0) {
					$admin_id 		= $_SESSION["ecom_admin_id"];
					$curr 			= date("Y-m-d H:i:s");
					$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;

					// Add Images

					if ($image['is_uploaded']) {
						$file_status = $image['status'];
						$image_q = "
							, file_name 	= '".$image['file_name']."',
							file_type 		= '".$image['file_type']."',
							file_size 		= '".$image['file_size']."',
							file_alt_name 	= '".$data['image_alt_name']."'
						";

						// Remove previous image
						$info = $this->getDetails(MAIN_CATEGORY_TBL,"file_name"," id='$id' ");
						if ($info['file_name']!='') {
							unlink("./resource/uploads/".$info['file_name']);
						}
					}else{
						$file_status = "ok";
						$image_q = "";
					}	

					if ($file_status=="ok") {
						 $query = "UPDATE ".MAIN_CATEGORY_TBL." SET 
								".$token_q."
								category 				= '".$data['title_name']."',
								sort_order 				= '".$data['sort_order']."',
								description 			= '".$data['description']."',
								vendor_commission 		= '".$data['vendor_commission']."',
								vendor_commission_tax 	= '".$data['vendor_commission_tax']."',
								is_draft 				= '".$save_as_draft."'
								".$image_q." 
								, meta_title 			= '".$data['meta_title']."',
								meta_description 		= '".$data['meta_description']."',
								meta_keyword 			= '".$data['meta_keyword']."',
								status					= '1',
								added_by 				= '$admin_id',	
								updated_at 				= '$curr' WHERE id='$id' ";
						$exe 	= $this->exeQuery($query);
					}else{
						return $image['message'];
					}
					if($exe){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}
				}else{

					$check_token	= $this->check_query(MAIN_CATEGORY_TBL,"id"," page_url='".$category_name."' AND delete_status='1' AND id!='".$id."' ");

					$check_title	= $this->check_query(MAIN_CATEGORY_TBL,"id","category LIKE '%".$data['title_name']."%' AND delete_status='1' AND id!='".$id."' ");
					
					if($check_token && $check_title) {
						return "The entered category already present on the trash. Kindly restore it from the trash section.";
					} else {
						return "The entered category already exists.";
					}
				}
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Change Category  Status

		function changeCategoryStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(MAIN_CATEGORY_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".MAIN_CATEGORY_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".MAIN_CATEGORY_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update Category delete status

		function deleteCategory($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(MAIN_CATEGORY_TBL,"file_name"," id ='$data' AND is_draft='1' ");
			// Remove Image
			if ($info['file_name']!='') {
				unlink("./resource/uploads/".$info['file_name']);
			}
			$delete = $this -> deleteRow(MAIN_CATEGORY_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}

		// Trash Category

		function trashCategory($data)
		{
			$data = $this->decryptData($data);
			$curr 			= date("Y-m-d H:i:s");
			$query = "UPDATE ".MAIN_CATEGORY_TBL." SET delete_status='1',updated_at='$curr' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Publish Category

		function publishCategory($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".MAIN_CATEGORY_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		// Restore Bolg

		function restoreCategory($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".MAIN_CATEGORY_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*---------------------------------------------------
						Product Management - Add
		---------------------------------------------------*/

		// Get Main Category to map 

		function getMainCategory($current="")
	  	{
	  		$layout = "";
	  		$q = "SELECT id,page_url,category FROM ".MAIN_CATEGORY_TBL." WHERE status='1' AND is_draft='0' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['category']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get Sub Category to map

		function getSubCategory($current="")
	  	{
	  		$layout = "";
	  		$q = "SELECT id,page_url,subcategory FROM ".SUB_CATEGORY_TBL." WHERE status='1' AND is_draft='0' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['subcategory']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get Tax class to map 

		function getTaxClass($current="")
	  	{
	  		$layout = "";
	  		$q = "SELECT id,token,tax_class FROM ".TAX_CLASSES_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['tax_class']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get Brand List 

		function getBrandList($current="")
	  	{
	  		$layout = "";
	  		$q = "SELECT id,brand_name FROM ".BRAND_TBL." WHERE status='1' AND is_draft='0' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['brand_name']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get Product unit List 

		function getProductUnits($current="")
	  	{
	  		$layout = "";
	  		$q = "SELECT id,product_unit FROM ".PRODUCT_UNIT_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['product_unit']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get Display Tag List 

		function getProductDisplayTag($current="")
	  	{
	  		$layout = "";
	  		$q = "SELECT id,display_tag FROM ".PRODUCT_DISPLAY_TAG." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['display_tag']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get Return Duration

		function getReturnDuration($current="")
	  	{
	  		$layout = "";
	  		$q = "SELECT * FROM ".RETURN_SETTINGS_TBL." WHERE status='1' AND delete_status='0' ORDER BY return_setting DESC " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){

		    		if($list['return_setting']=='days') {
						$return_duration = $list['days']. " Day";
					} else {
						if($list['minutes']=="" || $list['minutes']==0  ) {
							$return_duration = $list['hours']." Hour";	
						} elseif($list['hours']==0) {
							$return_duration = $list['minutes']." Minutes";
						} else {
							$return_duration = $list['hours']." Hour ".$list['minutes']." Minutes";
						}
					}

					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$return_duration."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get filter masters

	  	function getFilterMasterList($sub_category_id="",$product_id="")
	  	{		
	  		$layout    = "";
	  		$group_ids = array();

	  		if($product_id!="") {

	  			$filter_ids = array();

	  			$check_if_mapped_with_filter = $this->check_query(FILTER_VS_PRODUCT_TBL,"id","product_id='".$product_id."' ");

	  			if($check_if_mapped_with_filter) {

	  				$filter_query = "SELECT filter_id FROM ".FILTER_VS_PRODUCT_TBL." WHERE product_id='".$product_id."' ";
	  				$filter_exe   = $this->exeQuery($filter_query);

	  				if(mysqli_num_rows($filter_exe) > 0) {
	  					while ($list = mysqli_fetch_assoc($filter_exe)) {
	  						$filter_ids[] = $list['filter_id'];
	  					}
	  				}

	  			} 

	  		} else {
	  			$filter_ids = array();
	  		}

  			$q_1  = "SELECT F.id,F.group_id,F.sub_category_id,FG.id as filter_group_id,FG.filter_group_name,FG.token FROM ".FILTER_GROUP_VS_SUB_CATEGORY_TBL." F LEFT JOIN ".FILTER_GROUP_MASTER_TBL." FG ON (FG.id=F.group_id) WHERE F.sub_category_id='".$sub_category_id."' ORDER BY F.group_id ASC ";
  			$exe_1 = $this->exeQuery($q_1);
  			if(mysqli_num_rows($exe_1) > 0) {
  				while ($list_1 = mysqli_fetch_assoc($exe_1)) {
  					$empty_filter_group = $this->check_query(FILTER_MASTER_TBL,"id"," filter_group_id='".$list_1['filter_group_id']."' AND status='1' ");
  					if($empty_filter_group) {
						$layout    .= "<div class='form-group col-md-12'>
										<lable class='form-label display_flex'>".$list_1['filter_group_name']."</lable>";
		  					$q 		= "SELECT id,filter_value,status FROM ".FILTER_MASTER_TBL." WHERE filter_group_id='".$list_1['group_id']."' AND status='1' " ;
					  		$query  = $this->exeQuery($q);
						    if(@mysqli_num_rows($query)>0){
						    	$i=0;
						    	while($list   = mysqli_fetch_array($query)) {

						    		if($product_id!="" && count($filter_ids) > 0) {
						    			$selected = ((in_array($list['id'], $filter_ids))? "checked" : "" );
						    		} else {
						    			$selected = (($i==0)? "checked" : "" );
						    		}

									$layout  .= " 
												<div class='custom-control custom-control-sm custom-radio col-md-2'>
												   	<input type='radio' class='custom-control-input ".$list_1['token']."' value='".$list['id']."' name='filter[".$list_1['filter_group_id']."]' id='".$list_1['filter_group_id']."_".$i."' $selected >
												    <label class='custom-control-label' for='".$list_1['filter_group_id']."_".$i."'> 
												    	<label class='form-label'>".$list['filter_value']."</label>
												    </label>
												</div>";
					                $i++;
						    	}
						    }
				    	$layout.= "</div>";
					}
  				}
  			}

	  		
	  		
		    return $layout;
	  	}


	  	// Product List - Auto Complete

		function productAutoComplete($string) 
		{
			$a_json = array();
			$string_decode = urldecode($string);
			$q = "SELECT  P.id,P.product_name, P.category_type, S.subcategory, M.category FROM ".PRODUCT_TBL." P LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=P.main_category_id) LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=P.sub_category_id) WHERE P.product_name LIKE '$string_decode%' AND P.status='1'  AND P.is_draft='0' AND P.delete_status='0'  ORDER BY P.product_name ASC LIMIT 4";
			$exe = $this->exeQuery($q);
		    if(@mysqli_num_rows($exe)>0){
				while ($list = mysqli_fetch_assoc($exe)) {
					$category = (($list['category_type']=="sub") ? $list['subcategory'] : $list['category']  );
					$category_type = (($list['category_type']=="sub") ? "Sub Category" : "Main Category"  );

					$a_json[] = array(
						'product_id'    	=> $list['id'],
						'category'			=> $category." ( ".$category_type." )",
						'name'      		=> strip_tags(html_entity_decode($this->publishContent(ucwords($list['product_name'])), ENT_QUOTES, 'UTF-8')),
					); 
				}
			}
			return $a_json;
		}

		// Attributes list - Auto Complete

		function attributesAutocomplete($string)
		{
			$a_json = array();
			$string_decode = urldecode($string);
			$q = "SELECT A.id,A.attribute_name,A.attri_group_id, G.attribute_group  FROM ".ATTRIBUTE_TBL." A  LEFT JOIN ".ATTRIBUTE_GROUP_TBL." G ON (A.attri_group_id=G.id) WHERE A.delete_status='0' AND A.attribute_name LIKE '$string_decode%' AND A.status='1' LIMIT 5";
			$exe = $this->exeQuery($q);
		    if(@mysqli_num_rows($exe)>0){
				while ($list = mysqli_fetch_assoc($exe)) {
					$a_json[] = array(
						'attribute_id'    	=> $list['id'],
						'name'            	=> strip_tags(html_entity_decode($this->publishContent($list['attribute_name']), ENT_QUOTES, 'UTF-8')),
						'attribute_group' 	=> $this->publishContent($list['attribute_group']),
						'attribute_group_id' => $list['attri_group_id']
					); 
				}
			}
			return $a_json;
		}

	  	// Product List

		function manageProducts() 
		{
			$layout = "";
	    	$q = "SELECT P.id,P.product_name,P.category_type, P.main_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,P.has_variants, P.stock, T.tax_class as taxClass , M.category, S.subcategory,
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image, 
	    		(SELECT count(id) as total_count FROM ".PRODUCT_VARIANTS." WHERE product_id=P.id AND delete_status=0 ) as variant_count,
	    		(SELECT sum(stock) as total_stock FROM ".PRODUCT_VARIANTS." WHERE product_id=P.id AND delete_status=0 ) as variant_stock,
	    		(SELECT sum(stock) as vendor_stock FROM ".VENDOR_PRODUCTS_TBL." WHERE product_id=P.id ) as vendorstock

	    	  FROM ".PRODUCT_TBL." P 
	    	  LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id)
	    	  LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=P.main_category_id)
	    	  LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=P.sub_category_id)
	    	  WHERE P.delete_status='0' ORDER BY P.id DESC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($rows = mysqli_fetch_array($exe)){
 		    		$list = $this->editPagePublish($rows);
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					// Draft Status
					$draft_status = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row = (($list['is_draft']==1) ? "draft_item" : ""); 

					if ($list['is_draft']==1) {
						$draft_action = "<button type='button' data-option='".$this->encryptData($list['id'])."' data-link='products' class='btn btn-warning btn-dim btn-sm publishProduct' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class = 'deleteProduct';
						$delete_class_hover = 'Delete Product';

					}else{
						$draft_action = "<div class='tb-tnx-status'>
	                            	<span class='badge badge-dot text-success cursor_pointer'> Published </span>
	                            </div>";
	                    $delete_class = 'trashProduct';
	                    $delete_class_hover = 'Trash Product';
	                    
					}

					if ($list['is_draft']==0 && $list['status']==1) {
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
						$product_image = $list['default_product_image']!='' ? UPLOADS.$list['default_product_image'] : ASSETS_PATH."no_img.png" ;# code...
					}else{
						$product_image = $list['product_image']!='' ? UPLOADS.$list['product_image'] : ASSETS_PATH."no_img.png" ;
					}

					// Stock
					$stock = $list['has_variants']==1 ? $list['variant_stock']." in stock for  ".$list['variant_count']." variants" : $list['stock']." in stock ";

					//vendor stock
					$vendor_stock_check = ($list['vendorstock']==NULL)? '0' : $list['vendorstock'];

					$vendor_stock = $list['has_variants']==1 ? $vendor_stock_check." in stock for  ".$list['variant_count']." variants" : $vendor_stock_check." in stock ";

					//$list['vendorstock']

					$td_data = "data-data_id='".$list['id']."' data-data_link='products/edit'  ";

		    		$layout .= "
	    				<tr class='nk-tb-item $is_draft_row'>
	    					<td class='nk-tb-col td_click' $td_data>
                                ".$i."
                            </td>
                            <td class='nk-tb-col td_click' $td_data >
                                <img src='".$product_image."' width=50 />
                            </td>
                            <td class='nk-tb-col tb-col-md td_click' $td_data >
                                <span class='text-primary'>".$this->publishContent($list['product_name'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md td_click' $td_data >
                                ".$this->publishContent($category_name)."
	                        </td>
	                        <td class='nk-tb-col tb-col-md td_click' $td_data >
                               ".$stock."
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeProductStatus'  data-link='products' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                                </div>
	                        </td>
	                        <td class='nk-tb-col'>
                               	$draft_action
                            </td>
                            <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                    <li class='nk-tb-action-hidden'>
                                        <a href='javascript:void();' class='btn btn-trigger btn-icon $delete_class'  data-link='products' data-toggle='tooltip' data-placement='top' title='$delete_class_hover' data-option='".$this->encryptData($list['id'])."' >
                                            <em class='icon ni ni-trash-fill'></em>
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}

		// Add Product 

		function addProduct($input,$files)
		{	
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				//$data 			= $this->cleanStringData($input);
				$data 			= $input;
				$product_name	= $this->hyphenize($data['title_name']);
				$check_token 	= $this->check_query(PRODUCT_TBL,"id"," page_url='".$data['page_url']."' ");
				$info        	= $this->getDetails(PRODUCT_TBL, "id"," 1 ORDER BY id DESC LIMIT 1");

				if ($check_token==0) {
					$page_url = $data['page_url'];
				}else{
					$page_url = $data['page_url']."-".$this->generateRandomString("5");
				}

				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft  = isset($data['save_as_draft']) ? 1 : 0;
				if($info==""){
					$product_no    	= 1;
				}else{
					$product_no    	= $info['id']+1;
				}
				
            	$product_uid   	= 'P'.str_pad($product_no, 5,0,STR_PAD_LEFT);

            	$category_type = $data['category_type'];
            	if ($category_type=="sub") {
            		$category_map = "
            			category_type 			= '".$category_type."',
            			sub_category_id			= '".$data['sub_category_id']."',";
            	}else{
            		$category_map = "
            			category_type 			= '".$category_type."',
						main_category_id		= '".$data['main_category_id']."',";
            	}

            	$has_variants 	= ((isset($data['has_variants'])) ? 1 : 0 );
            	$track_stock 	= ((isset($data['track_stock'])) ? 1 : 0 );
            	$sell_out_of_stock = ((isset($data['sell_out_of_stock'])) ? 1 : 0);
            	$product_tags = "";
            	foreach ($data['product_tags'] as $key => $value) {
            		$product_tags .= $value." = '1', ";
            	}

            	$actual_price = $data['actual_price']=="" ? 0 : $data['actual_price']; 
            	$stock 		= $data['stock']=="" ? 0 : $data['stock']; 
            	$min_order_qty = $data['min_order_qty']=="" ? 0 : $data['min_order_qty'];
            	$max_order_qty = $data['max_order_qty']=="" ? 0 : $data['max_order_qty'];

            	if($data['display_tag']!="0") {
            		$display_tag_inputs =	"display_tag			    = '".$this->cleanString($data['display_tag'])."',
						 					display_tag_start_date  = '".date("Y-m-d",strtotime($data['display_tag_start_date']))."',
						 					display_tag_end_date    = '".date("Y-m-d",strtotime($data['display_tag_end_date']))."',";
            	} else {
            		$display_tag_inputs = "";
            	}

				$query = "INSERT INTO ".PRODUCT_TBL." SET 
						product_uid 			= '$product_uid',
						product_name			= '".$this->cleanString($data['title_name'])."',
						$category_map
						short_description		= '".$this->cleanString($data['short_description'])."',
						description				= '".$this->cleanString($data['description'])."',
						product_unit			= '".$this->cleanString($data['product_unit'])."',
						selling_price 			= '".$data['selling_price']."',
						actual_price 			= '".$actual_price."',
						tax_class				= '".$data['tax_class']."',
						tax_type 				= '".$data['tax_type']."',
						brand_id 				= '".$data['brand_id']."',
						stock 					= '".$stock."',
						min_order_qty 		  	= '".$data['min_order_qty']."',
						max_order_qty 		  	= '".$data['max_order_qty']."',
						sku						= '".$this->cleanString($data['sku'])."',
						page_url				= '".$page_url."',
						meta_title				= '".$this->cleanString($data['meta_title'])."',
						meta_description		= '".$this->cleanString($data['meta_description'])."',
						meta_keyword			= '".$this->cleanString($data['meta_keyword'])."',
						".$display_tag_inputs."
						has_variants 			= '".$has_variants."',
						track_stock 			= '".$track_stock."',
						sell_out_of_stock 		= '".$sell_out_of_stock."',
						has_return_duration     = '0',
						return_duration  	    = '0',
						shipping_status  	    = '0',
						handling  	    		= '0',
						handling_amount  	    = '0',
						$product_tags
						is_draft 				= '".$save_as_draft."',
						status					= '1',
						added_by 				= '$admin_id',	
						created_at 				= '$curr',
						updated_at 				= '$curr' ";
				$last_id 	= $this->lastInserID($query);


				if(isset($input['filter'])) {
	            	foreach ($input['filter'] as $key => $value) {
	            		$filter_query = "INSERT INTO ".FILTER_VS_PRODUCT_TBL." SET
	            							filter_group_id = '".$key."',
	            							filter_id 		= '".$value."',
	            							sub_category_id = '".$data['sub_category_id']."',
	            							product_id 		= '".$last_id."' ";
	            		$filter_exe   = $this->exeQuery($filter_query);
	            	}
	            }

			
				if($last_id){

					// Add Images
					if ($files['is_uploaded']) {
						$upload = $this->uploadMediaFiles($files['images'],"product",$last_id);
					}

					// Add Variants
					if ($has_variants==1) {
						$variants = $data['variants'];
						$add = $this->addVariants($variants, $last_id, $save_as_draft);
						$save_options = $this->saveVariantOptions($data['options'],$last_id);
					}

					// Related items
					if (isset($data['related_items'])) {
						$related = $this->saveRelatedProducts($data['related_items'], $last_id);
					}

					// Product Attributes
					if (isset($data['product_attribute'])) {
						$attributes = $this->saveAttributes($data['product_attribute'], $last_id);
					}

					// Save Product Vs Category
					
					$save_main = $this->productVsMainCategory($data,$last_id);
					$save_sub = $this->productVsSubCategory($data,$last_id);

				
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Save Variant Options

		function saveVariantOptions($options,$product_id)
		{
			$curr 	= date("Y-m-d H:i:s");
			foreach ($options as $key => $value) {
				$token = $this->uniqueToken($value['title']);
				$variants = $value['value'];
				$q = "INSERT INTO ".PRODUCT_OPTIONS." SET
					product_id 		= '".$product_id."',
					token			= '".$token."',
					option_title 	= '".$value['title']."'
				";
				$option_id = $this->lastInserID($q);

				// Save Variants
				foreach ($variants as $key => $each) {
					$v_token = $this->uniqueToken($each);
					$vq = "INSERT INTO ".PRODUCT_OPTION_VARIANTS." SET
						product_id 		= '".$product_id."',
						option_id 		= '".$option_id."',
						token			= '".$v_token."',
						variant_title	= '".$each."'
					";
					$exe = $this->exeQuery($vq);
				}
			}
		}

		// Add Variants

		function addVariants($data,$product_id,$is_draft)
		{
			$curr 	= date("Y-m-d H:i:s");
			foreach ($data as $key => $value) {
				$stock = (($value['stock']=='') ? 0 : $value['stock']);


				$trimed = str_replace('/','',$value['variant_name']);
				$token  = $this->hyphenize($trimed);
				
				$q = "INSERT INTO ".PRODUCT_VARIANTS." SET
					product_id 		= '".$product_id."',
					token			= '".$token."',
					variant_name 	= '".$this->cleanString($value['variant_name'])."',
					selling_price	= '".$value['selling_price']."',
					actual_price	= '".$value['actual_price']."',
					stock			= '".$stock."',
					sku				= '".$value['sku']."',
					status 			= '1',
					is_draft 		= '$is_draft',
					created_at 		= '$curr',
					updated_at 		= '$curr'
				";
				$exe = $this->exeQuery($q);
			}
		}

		// Related Items

		function saveRelatedProducts($data,$product_id)
		{
			$curr 	= date("Y-m-d H:i:s");
			foreach ($data as $key => $value) {
				$related_item_id = $value['item_id'];
				$item_type = $value['item_type'];
				$q = "INSERT INTO ".RELATED_PRODUCTS." SET
					product_id 		= '".$product_id."',
					related_item_id	= '".$related_item_id."',
					type 			= '".$item_type."'
				";
				$exe = $this->exeQuery($q);
			}
		}

		// Add Attributes

		function saveAttributes($attributes,$product_id)
		{
			if (count($attributes)>0) {
				foreach ($attributes as $each) {
					if ($each['attribute_id']!=""&&$each['product_attribute_description']!=""&&$each['attribute_group_id']) {
						$q = "INSERT INTO ".PRODUCT_ATTRIBUTES." SET product_id='$product_id', attribute_id = '".$each['attribute_id']."' , attribute_name = '".$each['name']."',attribute_group_id = '".$each['attribute_group_id']."', attr_desc = '".$each['product_attribute_description']."', status='1'  ";
						$exe = $this->exeQuery($q);
					}
				}	
			}
		}

		// Save Additional Main Categories

		function productVsMainCategory($data,$product_id)
		{	
			$category_type = $data['category_type'];
			$main_category_id = @$data['main_category_id'];	
			$main_category_array = @$data['add_main_category'];

			if ($category_type=="main") {
				// Save Parent Category
				$query = "INSERT INTO ".PRODUCT_VS_MAIN_CATEGORY." SET
								product_id = '$product_id',
								main_category_id = '$main_category_id',
								type = 'parent'
							 ";
				$exe = $this->exeQuery($query);
			}
			

			if (@count($main_category_array)>0) {
				foreach ($main_category_array as $key => $value) {
					if ($value!=$main_category_id) {
						$q = "INSERT INTO ".PRODUCT_VS_MAIN_CATEGORY." SET
							product_id = '$product_id',
							main_category_id = '$value',
							type = 'child'
						 ";
						 $lexe = $this->exeQuery($q);
					}
				}
			}
		}

		// Save Additional Sub Categories

		function productVsSubCategory($data,$product_id)
		{
			$category_type = $data['category_type'];
			$sub_category_id = $data['sub_category_id'];	
			$sub_category_array = @$data['add_sub_category'];

			if ($category_type=="sub") {
				// Save Parent Category
				$query = "INSERT INTO ".PRODUCT_VS_SUB_CATEGORY." SET
								product_id = '$product_id',
								sub_category_id = '$sub_category_id',
								type = 'parent'
							 ";
				$exe = $this->exeQuery($query);
			}

			if (@count($sub_category_array)>0) {
				foreach ($sub_category_array as $key => $value) {
					if ($value!=$sub_category_id) {
						$q = "INSERT INTO ".PRODUCT_VS_SUB_CATEGORY." SET
							product_id = '$product_id',
							sub_category_id = '$value',
							type = 'child'
						 ";
						 $lexe = $this->exeQuery($q);
					}
				}
			}
		}

		/*---------------------------------------------------
					Product Management - Edit
		---------------------------------------------------*/

		// Get Product Details

		function productDetails($product_id)
		{
			$q = "SELECT * FROM ".PRODUCT_TBL." WHERE id='$product_id' ";;
			$exe = $this->exeQuery($q);
			return mysqli_fetch_assoc($exe);
		}

		// Edit Product - Get Attributes

		function getProductAttributes($product_id)
		{
			$html = "";
			$q = "SELECT * FROM ".PRODUCT_ATTRIBUTES." WHERE product_id='$product_id' ORDER BY id ASC ";
 		    $exe    = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($rows = mysqli_fetch_array($exe)){
 		    		$list = $this->editPagePublish($rows);
 		    		$html .= '<tr id="attribute-row'.$i.'" class="attribute_row" data-option="'.$i.'">';
			        $html .= '  <td>
			        			<div class="autocomplete_item">
				        			<input type="text" name="product_attribute['.$i.'][name]" placeholder="" class="form-control editAttr" value="'.$list['attribute_name'].'" data-option="'.$i.'" required/>
				        			<input type="hidden" name="product_attribute['.$i.'][attribute_id]" data-option="'.$i.'" class="product_attribute" value="'.$list['attribute_id'].'" />
				        			<input type="hidden" name="product_attribute['.$i.'][attribute_group_id]" value="'.$list['attribute_group_id'].'" />
				        			<p class="text-danger" id="error_'.$i.'"></p>
				        			</div>
				        		</td>';
			        $html .= '  <td>';
			        $html .= '<input type="text" name="product_attribute['.$i.'][product_attribute_description]" rows="1" placeholder="Enter Attribute Description" value="'.$list['attr_desc'].'" class="form-control" required />';
			        $html .= '  </td>';
			        $html .= '  <td><button type="button" onclick="$(\'#attribute-row'.$i.'\').remove();" class="btn btn-trigger btn-icon remove_variant_option " > <em class="icon ni ni-trash" ></em>  </button></td>';
			        $html .= '</tr>';
		    		$i++;
		    	}
 		    }
 		    return $html;
		}

		// Edit Product - Get Related items

		function getProductRelatedItems($product_id)
		{
			$html = "";
			$q = "SELECT R.related_item_id, R.type, P.product_name FROM ".RELATED_PRODUCTS." R LEFT JOIN ".PRODUCT_TBL." P ON (P.id=R.related_item_id) WHERE R.product_id='$product_id' ORDER BY R.id ASC ";
 		    $exe    = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($rows = mysqli_fetch_array($exe)){
 		    		$list = $this->editPagePublish($rows);
 		    		$select_related = $list['type']=="related" ? "selected" : "";
 		    		$select_frequent = $list['type']=="bought_together" ? "selected" : "";


 		    		$html .= '<tr id="related_items_'.$list['related_item_id'].'">
 		    				<td> '.$list['product_name'].'
 		    				<input type="hidden" name="related_items['.$list['related_item_id'].'][item_id]" value="'.$list['related_item_id'].'"/></td>
 		    				<td>
 		    					<select class="form-select" name="related_items['.$list['related_item_id'].'][item_type]">
 		    						<option value="related" '.$select_related.' >Related Items</option>
 		    						<option value="bought_together" '.$select_frequent.'>Bought Together</option>
 		    					</select>
 		    				</td>
 		    				<td><button class="btn btn-icon remove_related" data-option="'.$list['related_item_id'].'"><em class="icon ni ni-trash-fill"></em></button></td>
 		    				</tr>';
		    		$i++;
		    	}
 		    }
 		    return $html;
		}

		// Edit Product - Addtional Category list

		function getEditAdditionalMainCategory($product_id)
		{
			$result =  array();
			$q = "SELECT id, main_category_id FROM ".PRODUCT_VS_MAIN_CATEGORY." WHERE product_id='$product_id' AND type='child' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$result[] = $list['main_category_id'];
	                $i++;
		    	}
		    }
		    return $result;
		}

		// Edit Product - Get Main Category to map 

		function getMainCategoryMap($product_id)
	  	{
	  		$current_array = $this->getEditAdditionalMainCategory($product_id);
	  		$layout = "";
	  		$q = "SELECT id,category FROM ".MAIN_CATEGORY_TBL." WHERE status='1' AND is_draft='0' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($rows = mysqli_fetch_array($query)){
		    		$list = $this->editPagePublish($rows);
					$selected = ((in_array($list['id'], $current_array)) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['category']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Edit Product - Addtional Sub Category list

		function getEditAdditionalSubCategory($product_id)
		{
			$result =  array();
			$q = "SELECT id, sub_category_id FROM ".PRODUCT_VS_SUB_CATEGORY." WHERE product_id='$product_id' AND type='child' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$result[] = $list['sub_category_id'];
	                $i++;
		    	}
		    }
		    return $result;
		}

	  	// Get Sub Category to map

		function getSubCategoryMap($product_id)
	  	{
	  		$current_array = $this->getEditAdditionalSubCategory($product_id);
	  		$layout = "";
	  		$q = "SELECT id,subcategory FROM ".SUB_CATEGORY_TBL." WHERE status='1' AND is_draft='0' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($rows = mysqli_fetch_array($query)){
		    		$list = $this->editPagePublish($rows);
					$selected = ((in_array($list['id'], $current_array)) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['subcategory']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Edit product - Ger Options Tag list

	  	function getOptionsTagEdit($product_id)
	  	{
	  		$layout = "";
	  		$q = "SELECT * FROM ".PRODUCT_OPTIONS." WHERE product_id= '$product_id' ORDER BY id ASC ";
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($rows = mysqli_fetch_array($query)){
		    		$list = $this->editPagePublish($rows);
		    		$layout .= '<div class="row" id="option_row_'.$list['id'].'">';
				    $layout .= '<div class="col-md-3"><div class="form-group"><label class="form-label">Option <em>*</em></label>';
				    $layout .= '<div class="form-control-wrap">';
				    $layout .= '<select class="form-control form-control-lg variant_option_select  option_title_'.$i.'" id="option_title_'.$i.'" data-option="'.$i.'" data-search="on"  disabled>
				    	<input type="hidden" name="options['.$i.'][title]" value="'.$list['option_title'].'" />';
				    $layout .= '<option value="'.$list['option_title'].'">'.$list['option_title'].'</option>';
				    $layout .= '  </select><p class="help_text">* Type to add option</p><p class="text-danger" id="option_error_'.$i.'"></p></div> </div> </div>';
				    $layout .= ' <div class="col-md-9"><div class="form-group"><label class="form-label">Enter Variants</label>';
				    $layout .= '<select multiple="multiple" data-role="tagsinput" name="options['.$i.'][value][]" data-option="'.$i.'"  class="form-control tags_input option_variant_'.$i.'">
				    			'.$this->getOptionItemsTagsEdit($list['id'],$product_id).'
				    			</select>
				    			<p class="text-danger" id="variant_error_'.$i.'"></p>';
				    $layout .= ' </div></div>';
				    $layout .= '</div>';
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Edit product - Ger Options value for option tags list

	  	function getOptionItemsTagsEdit($option_id,$product_id)
	  	{
	  		$layout = "";
	  		$q = "SELECT variant_title FROM ".PRODUCT_OPTION_VARIANTS." WHERE option_id= '$option_id' AND product_id='$product_id' ORDER BY id ASC ";
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($rows = mysqli_fetch_array($query)){
		    		$list = $this->editPagePublish($rows);
		    		$layout .= "<option value='".$this->publishContent($list['variant_title'])."' selected=''>".$this->publishContent($list['variant_title'])."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Edit product - Ger Options preview list

	  	function getOptionsPreviewEdit($product_id)
	  	{
	  		$layout = "";
	  		$q = "SELECT * FROM ".PRODUCT_OPTIONS." WHERE product_id= '$product_id' ORDER BY id ASC ";
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($rows = mysqli_fetch_array($query)){
		    		$list = $this->editPagePublish($rows);
		    		$layout .= '<tr>
		    						<td>'.$list['option_title'].'</td>
		    						<td>'.$this->getOptionItemsPreviewEdit($list['id']).'</td>
		    					</tr>';
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Edit product - Ger Options value for preview list

	  	function getOptionItemsPreviewEdit($option_id)
	  	{
	  		$layout = "";
	  		$q = "SELECT variant_title FROM ".PRODUCT_OPTION_VARIANTS." WHERE option_id= '$option_id' ORDER BY id ASC ";
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($rows = mysqli_fetch_array($query)){
		    		$list = $this->editPagePublish($rows);
		    		$comma = $i==0 ? "" : ", ";
		    		$layout .= $comma.$list['variant_title'];
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Edit product - Ger Variants list

	  	function getProductVariantsEdit($product_id)
	  	{
	  		$layout = "";
	  		$q = "SELECT * FROM ".PRODUCT_VARIANTS." WHERE product_id= '$product_id' ORDER BY id ASC ";
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($rows = mysqli_fetch_array($query)){
		    		$list = $this->editPagePublish($rows);
		    		$checked = $list['status']==1 ? 'checked' : '';
		    		$layout .= '<tr id="variant_row_'.$list['token'].'" class="variant_list_row" data-option="'.$list['token'].'">';
				    $layout .= '<td><p id="item_value_'.$i.'">'.$list['variant_name'].'</p>';
				    $layout .= '<input type="hidden" name="variants['.$i.'][token]" value="'.$list['token'].'" > 
				    			<input type="hidden" name="variants['.$i.'][variant_name]" value="'.$list['variant_name'].'">
				    			<input type="hidden" name="variants['.$i.'][variant_id]" value="'.$list['id'].'">
				    			 </td>';
				    $layout .= '<td><input type="number" min="1" name="variants['.$i.'][selling_price]" value="'.$list['selling_price'].'" class="form-control" placeholder="" required></td>';
				    $layout .= '<td><input type="number" min="1" name="variants['.$i.'][actual_price]" value="'.$list['selling_price'].'"  class="form-control" placeholder="" ></td>';
				    $layout .= '<td><input type="number" min="0" name="variants['.$i.'][stock]" value="'.$list['stock'].'" class="form-control" placeholder="" ></td>';
				    $layout .= '<td><input type="text" name="variants['.$i.'][sku]" value="'.$list['sku'].'" class="form-control" placeholder=""></td>';
				    $layout .= '<td>
				    				<div class="custom-control custom-switch" data-toggle="tooltip" data-placement="top" title="Change Availability" >
                                        <input type="checkbox" class="custom-control-input"  value="1"  id="variant_status_'.$i.'" name="variants['.$i.'][status]" '.$checked.'>
                                        <label class="custom-control-label" for="variant_status_'.$i.'"> </label>
                                    </div>
                                    <button type="button" class="btn btn-trigger btn-icon variantImageAssign" data-product="'.$list['product_id'].'" data-variant="'.$list['variant_name'].'" data-option="'.$list['id'].'" data-toggle="tooltip" data-placement="top" title="Assign Images"  ><em class="icon ni ni-camera"></em></button>
                                </td>
                                </tr>';
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

		// Edit Product 

		function editProduct($input,$files)
		{ 	

			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$data = $input; //$this->cleanStringData($input);
				$id =	$this->decryptData($data['session_token']);

				$info = $this->getDetails(PRODUCT_TBL,"has_variants"," id='$id' ");

				$check_token 	= $this->check_query(PRODUCT_TBL,"id"," page_url='".$data['page_url']."' AND id!='$id' ");
				if ($check_token==0) {
					$page_url = $data['page_url'];
				}else{
					$page_url = $data['page_url']."-".$this->generateRandomString("5");
				}
				$token_q = "page_url  = '".$page_url."', ";
				

				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;
				
				$category_type = $data['category_type'];
            	if ($category_type=="sub") {
            		$category_map = "
            			category_type 			= '".$category_type."',
            			sub_category_id			= '".$data['sub_category_id']."',
            			main_category_id 		= 0, ";
            	}else{
            		$category_map = "
            			category_type 			= '".$category_type."',
						main_category_id		= '".$data['main_category_id']."',
						sub_category_id			= 0,";
            	}


            	$has_variants 	= ((isset($data['has_variants'])) ? 1 : 0 );
            	$track_stock 	= ((isset($data['track_stock'])) ? 1 : 0 );
            	$sell_out_of_stock = ((isset($data['sell_out_of_stock'])) ? 1 : 0);


            	if (isset($data['save_as_draft'])==1) {
            		$has_variants_q = " has_variants 			= '".$has_variants."', ";
            	}else{
            		$has_variants_q = "";
            	}

            	// Clear Previous Tags
            	$clear_tags = "UPDATE ".PRODUCT_TBL." SET 
            		new_arrival=0,
            		hot_deals =0,
            		featured_product =0,
            		best_seller=0 WHERE id='$id'
            	";
            	$exe_clear_tags =  $this->exeQuery($clear_tags);

            	// Tags Query
            	$product_tags = "";
            	foreach ($data['product_tags'] as $key => $value) {
            		$product_tags .= $value." = '1', ";
            	}

            	$actual_price = $data['actual_price']=="" ? 0 : $data['actual_price']; 
            	$stock 		= $data['stock']=="" ? 0 : $data['stock']; 
            	$min_order_qty = $data['min_order_qty']=="" ? 0 : $data['min_order_qty'];
            	$max_order_qty = $data['max_order_qty']=="" ? 0 : $data['max_order_qty'];

            	if($data['display_tag']!="0") {
            		$display_tag_inputs =	"display_tag			    = '".$this->cleanString($data['display_tag'])."',
						 					display_tag_start_date  	= '".date("Y-m-d")."',
						 					display_tag_end_date    	= '".date("Y-m-d",strtotime($data['display_tag_end_date']))."',";
            	} else {
            		$display_tag_inputs = "";
            	}


				$query = "UPDATE ".PRODUCT_TBL." SET 
						".$token_q."
						$category_map
						product_name			= '".$this->cleanString($data['title_name'])."',
						short_description		= '".$this->cleanString($data['short_description'])."',
						description				= '".$this->cleanString($data['description'])."',

						product_unit			= '".$this->cleanString($data['product_unit'])."',
						selling_price 			= '".$data['selling_price']."',
						actual_price 			= '".$actual_price."',
						tax_class				= '".$data['tax_class']."',
						tax_type 				= '".$data['tax_type']."',
						brand_id 				= '".$data['brand_id']."',
						stock 					= '".$stock."',
						min_order_qty 		  	= '".$data['min_order_qty']."',
						max_order_qty 		  	= '".$data['max_order_qty']."',
						sku						= '".$this->cleanString($data['sku'])."',
						page_url				= '".$page_url."',
						meta_title				= '".$this->cleanString($data['meta_title'])."',
						meta_description		= '".$this->cleanString($data['meta_description'])."',
						meta_keyword			= '".$this->cleanString($data['meta_keyword'])."',
						".$display_tag_inputs."
						".$has_variants_q."
						track_stock 			= '".$track_stock."',
						sell_out_of_stock 		= '".$sell_out_of_stock."',
						has_return_duration     = '0',
						return_duration  	    = '0',
						shipping_status  	    = '0',
						handling  	    		= '0',
						handling_amount  	    = '0',
						".$product_tags."
						is_draft 				='".$save_as_draft."',
						status					= '1',
						added_by 				= '$admin_id',	
						updated_at 				= '$curr' 
						WHERE id='$id' ";


				$exe 	= $this->exeQuery($query);


				if(isset($input['filter'])) {

					$filter_delete_query = "DELETE FROM ".FILTER_VS_PRODUCT_TBL." WHERE product_id='".$id."' ";
					$filter_delete_exe   = $this->exeQuery($filter_delete_query);

	            	foreach ($input['filter'] as $key => $value) {
	            		$filter_query = "INSERT INTO ".FILTER_VS_PRODUCT_TBL." SET
	            							filter_group_id = '".$key."',
	            							filter_id 		= '".$value."',
	            							sub_category_id = '".$data['sub_category_id']."',
	            							product_id 		= '".$id."' ";
	            		$filter_exe   = $this->exeQuery($filter_query);


	            	}

	            	if (count($input['filter']) > 0) {
	            		$filter_ids = implode(",", $input['filter']).",";
	            		
	            		$update_query = "UPDATE ".FILTER_VS_PRODUCT_TBL." SET
	            						 filter_ids           = '".$filter_ids."'
	            						 WHERE product_id     = '".$id."' 
	            						 AND sub_category_id  = '".$data['sub_category_id']."' ";
	            		$update_exe   = $this->exeQuery($update_query);
	            	}

	            }




				// Updates in vendor product tbl

				if ($category_type=="sub") {
            		$category_map = "
            			sub_category_id			= '".$data['sub_category_id']."',
            			main_category_id 		= '0' ";
            	}else{
            		$category_map = "
						main_category_id		= '".$data['main_category_id']."',
						sub_category_id			= '0'";
            	}

				$ve_query = "UPDATE ".VENDOR_PRODUCTS_TBL." SET 
						$category_map,
						selling_price 			= '".$data['selling_price']."',
						".$has_variants_q."
						status					= '1'
						WHERE product_id='$id' ";
				$ve_exe 	= $this->exeQuery($ve_query);

		
				if($exe){

					// Add Images
					if ($files['is_uploaded']) {
						$upload = $this->uploadMediaFiles($files['images'],"product",$id);
					}

					// Add/ Edit Variants

					if (isset($data['save_as_draft'])==1) {

						if ($has_variants==1) {
							$variants = $data['variants'];
							$add = $this->editAddVariants($variants, $id, $save_as_draft);
							$save_options = $this->editSaveVariantOptions($data['options'],$id);
						}
					}else{
						if ($info['has_variants']==1) {
							$variants = $data['variants'];
							$update = $this->editAddVariants($variants, $id, $save_as_draft);
							$save_options = $this->editSaveVariantOptions($data['options'],$id);
						}
					}

					

					// Related items
					if (isset($data['related_items'])) {
						$clear_related = $this->deleteRow(RELATED_PRODUCTS," product_id='$id' ");
						$related = $this->saveRelatedProducts($data['related_items'], $id);
					}

					// Product Attributes
					if (isset($data['product_attribute'])) {
						$clear_related = $this->deleteRow(PRODUCT_ATTRIBUTES," product_id='$id' ");
						$attributes = $this->saveAttributes($data['product_attribute'], $id);
					}

					// Save Product Vs Category
					$clear_main = $this->deleteRow(PRODUCT_VS_MAIN_CATEGORY," product_id='$id' ");
					$clear_sub = $this->deleteRow(PRODUCT_VS_SUB_CATEGORY," product_id='$id' ");
					
					$save_main = $this->productVsMainCategory($data,$id);
					$save_sub = $this->productVsSubCategory($data,$id);

					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Edit product - Add Variants

		function editAddVariants($data,$product_id,$is_draft)
		{
			$curr 	= date("Y-m-d H:i:s");
			foreach ($data as $key => $value) {
				$stock = (($value['stock']=='') ? 0 : $value['stock']);

				$trimed = str_replace('/','',$value['variant_name']);
				$token  = $this->hyphenize($trimed);

				if (isset($value['variant_id'])) {
					$status = isset($value['status']) ? 1 : 0;
					 $q = "UPDATE ".PRODUCT_VARIANTS." SET
						token			= '".$token."',
						variant_name 	= '".$this->cleanString($value['variant_name'])."',
						selling_price	= '".$value['selling_price']."',
						actual_price	= '".$value['actual_price']."',
						stock			= '".$stock."',
						sku				= '".$value['sku']."',
						status 			= '".$status."',
						is_draft 		= '$is_draft',
						updated_at 		= '$curr' WHERE id='".$value['variant_id']."'
					";
				}else{
					$q = "INSERT INTO ".PRODUCT_VARIANTS." SET
						product_id 		= '".$product_id."',
						token			= '".$token."',
						variant_name 	= '".$this->cleanString($value['variant_name'])."',
						selling_price	= '".$value['selling_price']."',
						actual_price	= '".$value['actual_price']."',
						stock			= '".$stock."',
						sku				= '".$value['sku']."',
						status 			= '1',
						is_draft 		= '$is_draft',
						created_at 		= '$curr',
						updated_at 		= '$curr'
					";
				}
				
				$exe = $this->exeQuery($q);
			}
		}

		// Edi - Save Variant Options

		function editSaveVariantOptions($options,$product_id)
		{
			$curr 	= date("Y-m-d H:i:s");
			foreach ($options as $key => $value) {
				$variants = $value['value'];
				
				$option_info = $this->getDetails(PRODUCT_OPTIONS,"id"," product_id='$product_id' AND option_title='".$value['title']."' ");

				// Save Variants
				foreach ($variants as $key => $each) {
					$v_token = $this->uniqueToken($each);
					$check = $this->check_query(PRODUCT_OPTION_VARIANTS,"id", " token='$v_token' AND product_id = '".$product_id."'  ");
					if ($check==0) {
						$vq = "INSERT INTO ".PRODUCT_OPTION_VARIANTS." SET
							product_id 		= '".$product_id."',
							option_id 		= '".$option_info['id']."',
							token			= '".$v_token."',
							variant_title	= '".$this->cleanString($each)."'
						";
						$exe = $this->exeQuery($vq);
					}
				}
			}
		}

		// Edit Product - Get Product Images

		function getProductImage($product_id)
		{
			$layout = "";
			$q = "SELECT * FROM ".MEDIA_TBL." WHERE item_id='$product_id' AND item_type='product' AND delete_status=0 ORDER BY id ASC ";
 		    $exe    = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($rows = mysqli_fetch_array($exe)){
 		    		$list = $this->editPagePublish($rows);
 		    		$default_icon = $list['is_default']==1 ?  '<em class="icon ni ni-check-circle"></em>' : '<em class="icon ni ni-circle"></em>';
 		    		$default_text = $list['is_default']==1 ?  'Current default Image' : 'Make default Image';
 		    		$default_tag = $list['is_default']==1 ?  '<h5 class="default_tag" id="default_tag_'.$list['id'].'" data-option="'.$list['id'].'"><em class="icon ni ni-check-circle"></em> Default Image </h5>' : '';

 		    		$layout .= ' <div class="image_list" id="image_item_'.$list['id'].'" style=\'background-image: url("'.UPLOADS.$list['file_name'].'"); background-repeat: no-repeat; background-position: center top; background-size: cover;\' >
                                <div class="image_actions">
                                	<h6 id="image_icon_'.$this->encryptData($list['id']).'" class="product_default_image" data-option="'.$this->encryptData($list['id']).'" data-toggle="tooltip" data-placement="top" title="'.$default_text.'" data-product="'.$list['item_id'].'" data-element="'.$list['id'].'">'.$default_icon.'</h6>
                                    <p>
                                    	<a href="javascript:void();" id="image_action_'.$list['id'].'" data-option="'.$this->encryptData($list['id']).'"  data-filename="'.$list['file_name'].'" data-element="'.$list['id'].'"  data-alt="'.$list['file_alt_name'].'"  data-image="'.UPLOADS.$list['file_name'].'">
                                    		<em class="icon ni ni-expand"></em>
                                    	</a>
                                    </p>
                                </div>
                                '.$default_tag.'
                            </div>';
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}

		// Edit - Product Image info

		function productImageInfo($data)
		{
			$image_id = $this->decryptData($data);
			$image_info  = $this->getDetails(MEDIA_TBL,"*"," id= '$image_id' ");

			$result = array();
			$result['id'] = $image_info['id'];
			$result['file_name'] = $image_info['file_name'];
			$result['file_alt_name'] = $image_info['file_alt_name'];
			$result['image_path'] = UPLOADS.$image_info['file_name'];
			return $result;
		}

		// Edit Product Image

		function editProductImage($data)
		{
			$image_id = $this->decryptData($data['image_id']);

			$image_info  = $this->getDetails(MEDIA_TBL,"*"," id= '$image_id' ");
			$product_info = $this->getDetails(PRODUCT_TBL,"is_draft"," id='".$image_info['item_id']."' ");

			$result = array();
			

			if ($product_info['is_draft']==1 && $data['image_name']!='') {
				$file_name_array = explode(".", $image_info['file_name']);
				$file_extension = end($file_name_array);


				$new_name = $this->hyphenize($data['image_name']).".".$file_extension;
				$thumb_file_name = "thumb-".$new_name;

				if (file_exists("./resource/uploads/".$new_name)) {
					
					$edited_file_name = $this->hyphenize($data['image_name'])."-".$this->generateRandomString("5").".".$file_extension;
					$edit_thumb_file_name = "thumb-".$edited_file_name;
					rename('./resource/uploads/'.$image_info['file_name'], './resource/uploads/'.$edited_file_name);
					rename('./resource/uploads/thumbnail/thumb-'.$image_info['file_name'], './resource/uploads/thumbnail/'.$edit_thumb_file_name);

					$file_query = " file_name = '$edited_file_name', ";
					$result['new_name'] = $edited_file_name;
					$result['new_path'] = UPLOADS.$edited_file_name;


				}else{
					rename('./resource/uploads/'.$image_info['file_name'], './resource/uploads/'.$new_name);
					rename('./resource/uploads/thumbnail/thumb-'.$image_info['file_name'], './resource/uploads/thumbnail/'.$thumb_file_name);
					$file_query = " file_name = '$new_name',  ";

					$result['new_name'] = $new_name;
					$result['new_path'] = UPLOADS.$new_name;

				}

			}else{
				$file_query = "";
				$result['new_name'] = "";
			}

			$q = "UPDATE ".MEDIA_TBL." SET
					$file_query
					file_alt_name = '".$data['alt_name']."'
					WHERE id = '$image_id'
				";
			$exe = $this->exeQuery($q);

			$result['status']= "ok";
			return $result;
		}

		// Delete product image

		function deleteProductImage($data)
		{
			$data = $this->decryptData($data);
			$curr 			= date("Y-m-d H:i:s");
			$query = "UPDATE ".MEDIA_TBL." SET delete_status='1', updated_at='$curr' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Make product image Default

		function makeImageDefault($image_id,$product_id)
		{
			$data 	= $this->decryptData($image_id);
			$curr 	= date("Y-m-d H:i:s");

			// Clear other default image

			$clear_q = "UPDATE ".MEDIA_TBL." SET is_default='0'  WHERE item_id='$product_id' AND item_type='product' ";
			$clear_old = $this->exeQuery($clear_q);

			// Update 
			$query = "UPDATE ".MEDIA_TBL." SET is_default='1', updated_at='$curr' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Change Product  Status

		function changeProductStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(PRODUCT_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".PRODUCT_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".PRODUCT_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update Product delete status

		function deleteProduct($data)
		{	
			$data = $this->decryptData($data);
			
			$delete = $this -> deleteRow(PRODUCT_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}

		// Trash Product

		function trashProduct($data)
		{
			$data = $this->decryptData($data);
			$curr 			= date("Y-m-d H:i:s");
			$query = "UPDATE ".PRODUCT_TBL." SET delete_status='1',updated_at='$curr' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Publish Product

		function publishProduct($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".PRODUCT_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		// Restore Bolg

		function restoreProduct($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".PRODUCT_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					Attribute Group 
		----------------------------------------------*/ 	

		function manageAttributeGroup()
 	  	{
 	  		$layout = "";
	    	$q 	    = "SELECT id,attribute_group,sort_order,status FROM ".ATTRIBUTE_GROUP_TBL." WHERE delete_status='0' ORDER BY id ASC" ;
 		    $exe    = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($rows = mysqli_fetch_array($exe)){
 		    		$list = $this->editPagePublish($rows);
 		    		$status 	  = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c 	  = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					$td_data = "data-formclass='edit_AttributeGroup_class' data-form='editAttributeGroup' data-option='".$this->encryptData($list['id'])."'"; 

		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditAttributeGroup' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditAttributeGroup' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditAttributeGroup' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['attribute_group'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-mb openEditAttributeGroup' $td_data>
	                                ".$list['sort_order']."
 	                            </td>
 	                            
 	                            <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeAttributeGroupStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' name='save_as_draft' $status_c>
                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                                </div>
	                        	</td>
 	                            <td class='nk-tb-col nk-tb-col-tools'>
	                                <ul class='nk-tb-actions gx-1'>
	                                    <li class='nk-tb-action-hidden'>
	                                        <button class='btn btn-trigger btn-icon deleteAttributeGroup' data-formclass='edit_AttributeGroup_class' data-form='editAttributeGroup' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
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

 		// Change Location Group Status

		function changeAttributeGroupStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(ATTRIBUTE_GROUP_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".ATTRIBUTE_GROUP_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".ATTRIBUTE_GROUP_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

 		// Add Location Group

		function addAttributeGroup($input)
		{
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");

				$attributeGroup = $this->hyphenize($data['attribute_group_name']);
				$check_token = $this->check_query(ATTRIBUTE_GROUP_TBL,"id"," token='".$attributeGroup."' ");

				if ($check_token==0) {
					$token = $attributeGroup;
				}else{
					$token = $attributeGroup."-".$this->generateRandomString("5");
				}

				$query          = "INSERT INTO ".ATTRIBUTE_GROUP_TBL." SET 
							token 			= '".$token."',
							attribute_group = '".$data['attribute_group_name']."',
							sort_order 		= '".$data['sort_order']."',
							status			='1',
							added_by		= '$admin_id',	
							created_at 		= '$curr',
							updated_at 		= '$curr' ";
				$exe 	= $this->exeQuery($query);
				if($exe){
					$this->unSetCSRF($data['csrf_key']);
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Get Location Group item Details

		function getAttributeGroupItemDetails($data){
			$layout           = "";
			$locationGroup_id = $this->decryptData($data);
			$query 			  = "SELECT * FROM  ".ATTRIBUTE_GROUP_TBL."  WHERE id='$locationGroup_id' ";
			$exe 	          = $this->exeQuery($query);

			if (mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$layout .= "<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' id='token'>
				                <div class='form-group'>
				                    <label class='form-label'>Attribute Group Name
				                        <en>*</en>
				                    </label>
				                    <input type='text' name='attribute_group_name' value='".$list['attribute_group']."' id='edit_attribute_group_name'  class='form-control' placeholder='Enter Attribute Group Name' required>
				                </div>
				                <div class='form-group'>
				                    <label class='form-label'>Sort Order<en>*</en> </label>
				                    <input type='number' name='sort_order' value='".$list['sort_order']."' id='edit_sort_order' class='form-control' placeholder='Enter Sort Order' required>
				                </div>
				                <div class='form-group'>
				                    <p class='float-right model_pt'>
				                        <button type='button' class='btn btn-light close_modal' data-modal_id='editAttributeGroupModal'>Cancel</button>
				                        <button type='submit' class='btn btn-primary'>Submit</button>
				                    </p>
				                </div>";
				}
			} else {
				return "Sorry!! Unexpected Error Occurred. Please try again.";
			}
			return $layout;
		}

		// Edit Location Group

		function editAttributeGroup($data)
		{
			if(isset($_SESSION[$data['csrf_key']])){
				if($this->cleanString($data['csrf_token']) == $_SESSION[$data['csrf_key']]){
					$admin_id 				= $_SESSION["ecom_admin_id"];
					$attributrGroup_id 		= $this->decryptData($data["token"]);
					$curr 					= date("Y-m-d H:i:s");
					
					$attributeGroup = $this->hyphenize($data['attribute_group_name']);
					$check_token = $this->check_query(ATTRIBUTE_GROUP_TBL,"id"," token='".$attributeGroup."' AND id!='$attributrGroup_id' ");
					
					if ($check_token==0) {
						$token = $attributeGroup;
					}else{
						$token = $attributeGroup."-".$this->generateRandomString("5");
					}

					$token_q = "token  = '".$token."', ";

					$query  = "UPDATE ".ATTRIBUTE_GROUP_TBL." SET 
								".$token_q."
								attribute_group 	= '".$this->cleanString($data['attribute_group_name'])."',
								sort_order 			= '".$this->cleanString($data['sort_order'])."',
								updated_at 		    = '$curr' WHERE id='$attributrGroup_id' ";

					$exe 	= $this->exeQuery($query);
					if($exe){
						$this->unSetCSRF($data['csrf_key']);
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}	
				}else{
					return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
				}
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Update the delete Location Group status

		function deleteAttributeGroup($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(ATTRIBUTE_GROUP_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".ATTRIBUTE_GROUP_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".ATTRIBUTE_GROUP_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Location Group

		function restoreAttributeGroup($data)
		{	
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".ATTRIBUTE_GROUP_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}
		
		/*--------------------------------------------- 
					Attribute
		----------------------------------------------*/ 	

		function manageAttribute()
 	  	{
 	  		$layout = "";
	    	$q      = "SELECT L.id,L.attri_group_id,L.attribute_name,L.sort_order,L.status,G.attribute_group as groupname FROM ".ATTRIBUTE_TBL." L LEFT JOIN ".ATTRIBUTE_GROUP_TBL." G ON (G.id=L.attri_group_id) WHERE L.delete_status='0' ORDER BY L.id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($rows = mysqli_fetch_array($exe)){
 		    		$list = $this->editPagePublish($rows);
 		    		$status       = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c     = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					$td_data      = "data-formclass='edit_Attribute_class' data-form='editAttribute' data-option='".$this->encryptData($list['id'])."'";

		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditAttribute' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditAttribute' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditAttribute' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['attribute_name'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-mb openEditAttribute' $td_data>
	                                ".$list['groupname']."
 	                            </td>
 	                               <td class='nk-tb-col openEditAttribute' $td_data>
	                                ".$list['sort_order']."
	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeAttributeStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' name='save_as_draft' $status_c>
                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                                </div>
	                        	</td>
 	                            <td class='nk-tb-col nk-tb-col-tools'>
	                                <ul class='nk-tb-actions gx-1'>
	                                    <li class='nk-tb-action-hidden'>
	                                        <button class='btn btn-trigger btn-icon deleteAttribute' data-formclass='edit_Attribute_class' data-form='editAttribute' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
	                                    </li>
	                                </ul>
                            	</td>
	                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
 		}	

 		// Change Location Status

		function changeAttributeStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(ATTRIBUTE_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".ATTRIBUTE_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".ATTRIBUTE_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

 		// Add Location

		function addAttribute($input)
		{	
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");

				$attribute = $this->hyphenize($data['attribute_name']);
				$check_token = $this->check_query(ATTRIBUTE_TBL,"id"," token='".$attribute."' ");

				if ($check_token==0) {
					$token = $attribute;
				}else{
					$token = $attribute."-".$this->generateRandomString("5");
				}

				$query = "INSERT INTO ".ATTRIBUTE_TBL." SET 
							token 				= '".$token."',
							attribute_name 		= '".$data['attribute_name']."',
							attri_group_id		= '".$this->cleanString($data['group_id'])."',
							sort_order 			= '".$this->cleanString($data['sort_order'])."',
							status				= '1',
							added_by			= '$admin_id',	
							created_at 			= '$curr',
							updated_at 			= '$curr' ";
				$exe 	= $this->exeQuery($query);
				if($exe){
					$this->unSetCSRF($data['csrf_key']);
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Get Location item Details

		function getAttributeItemDetails($data)
		{
			$layout       = "";
			$attribute_id = $this->decryptData($data);
			$query 		  = "SELECT * FROM  ".ATTRIBUTE_TBL."  WHERE id='$attribute_id' ";
			$info         = $this->getDetails(ATTRIBUTE_TBL,"*"," id='".$attribute_id."' ");
			$atr_grp_drp  = $this->getAttributeGroup($info['attri_group_id']);
			$exe 		  = $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$layout .= "    
									<input type='hidden' name='token' id='token' value='".$this->encryptData($list['id'])."'>
						            <div class='form-group'>
						                <label class='form-label'>Attribute Name
						                    <en>*</en>
						                </label>
						                <input type='text' name='attribute_name' id='edit_attribute_name'  value='".$list['attribute_name']."' class='form-control' placeholder='Enter Location' required>
						            </div>
						            <div class='form-group'>
						                <label class='form-label'>Attribute Group<en>*</en> </label>
						                <div class='form-control-wrap'>
						                    <select class='form-control form-control-lg  edit_location'  id='group_id' name='group_id' required>
						                       	".$atr_grp_drp."
						                    </select>
						                </div>
						            </div>
						            <div class='form-group'>
						                <label class='form-label'>Sort Order
						                    <en>*</en>
						                </label>
						                <input type='text' name='sort_order' id='edit_sort_order' value='".$list['sort_order']."' class='form-control' placeholder='Enter Sort Order' required>
						            </div>
						            <div class='form-group'>
						                <p class='float-right model_pt'>
						                    <button type='button' class='btn btn-light close_modal' data-modal_id='editAttributeModal'>Cancel</button>
						                    <button type='submit' class='btn btn-primary'>Submit</button>
						                </p>
						            </div>";
				}

			}  else {
				return "Sorry!! Unexpected Error Occurred. Please try again.";
			}

			return $layout;
		}

		// Edit Location

		function editAttribute($data)
		{
			if(isset($_SESSION[$data['csrf_key']])){
				if($this->cleanString($data['csrf_token']) == $_SESSION[$data['csrf_key']]){
					$admin_id 				= $_SESSION["ecom_admin_id"];
					$attribute_id 			= $this->decryptData($data["token"]);
					$curr 					= date("Y-m-d H:i:s");

					$attribute = $this->hyphenize($data['attribute_name']);
					$check_token = $this->check_query(ATTRIBUTE_TBL,"id"," token='".$attribute."' AND id!='$attribute_id' ");
					
					if ($check_token==0) {
						$token = $attribute;
					}else{
						$token = $attribute."-".$this->generateRandomString("5");
					}

					$token_q = "token  = '".$token."', ";


					$query = "UPDATE ".ATTRIBUTE_TBL." SET 
								".$token_q."
								attribute_name 		= '".$this->cleanString($data['attribute_name'])."',
								attri_group_id		= '".$this->cleanString($data['group_id'])."',
								sort_order 			= '".$this->cleanString($data['sort_order'])."',
								updated_at 			= '$curr' WHERE id='$attribute_id' ";
					$exe 	= $this->exeQuery($query);
					if($exe){
						$this->unSetCSRF($data['csrf_key']);
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}	
				}else{
					return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
				}
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Update the delete Location status

		function deleteAttribute($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(ATTRIBUTE_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".ATTRIBUTE_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".ATTRIBUTE_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Location

		function restoreAttribute($data)
		{	
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".ATTRIBUTE_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Get Location Group Name to map 

		function getAttributeGroup($current="")
	  	{
	  		$layout = "";
	  		$q      = "SELECT id,token,attribute_group FROM ".ATTRIBUTE_GROUP_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['attribute_group']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	/*-------------------------------------------
				Product Request
    -------------------------------------------*/

	// Inventory Product List design
	function  manageInventoryList($vendor_id="",$list_for="") 
	{
		if($vendor_id!="") 
		{
			$vendor_id = $vendor_id;
			$empty_list = "";
		} else {
			$vendor_id = 0;
			$empty_list = "AND VP.vendor_id='0'";
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

		$layout = "";

    	$q = "SELECT  P.id,P.product_name,P.has_variants,VP.selling_price as vendor_sell_price,VP.max_qty,VP.min_qty,P.actual_price,P.selling_price,P.category_type, P.sku, P.main_category_id,P.sub_category_id,P.tax_class,P.delete_status,P.is_draft, P.stock,P.min_order_qty,P.max_order_qty,T.tax_class as taxClass , M.id as mainCatId, M.category,S.id as subCatId, S.subcategory,VP.stock as vendorStock,VP.id as vendor_assigned_id,
    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image,
    		(SELECT SUM(stock) FROM ".VENDOR_PRODUCTS_TBL." WHERE product_id=P.id AND vendor_id='".$vendor_id."') as t_stock_in_tis_prd 
    	  FROM ".PRODUCT_TBL." P LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id)
    	  	LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=P.main_category_id)
    	  	LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=P.sub_category_id)
    	  	LEFT JOIN ".VENDOR_PRODUCTS_TBL." VP ON (P.id=VP.product_id AND vendor_id='".$vendor_id."')
    	  WHERE P.is_draft='0' $empty_list AND P.delete_status='0' AND P.status='1' $stock_check order BY P.id DESC" ;

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
                           ".$list['vendorStock']." in stock
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


  	// Get vendor list For Report filter DroupDown
 
    function getVendors($current="")
    {   
        $layout = "";
        $q      = "SELECT VO.id,VO.vendor_id,VE.id as vendorID,VE.name,VE.company,VE.mobile,VE.email FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) WHERE 1 GROUP BY vendor_id  DESC" ;
        $query = $this->exeQuery($q);
        if(@mysqli_num_rows($query)>0){
            $i=0;
            while($list = mysqli_fetch_array($query)){
                $selected = (($list['vendorID']==$current) ? 'selected' : '');
                $layout.= "<option value='".$list['vendorID']."' $selected>".$list['company']."</option>";
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

		$q = "SELECT VP.id,VP.vendor_id,VP.product_name,VP.description,VP.status,VP.created_at,VP.request_status,V.company,V.email,V.mobile,RS.request_status as request_status_name FROM ".VENDOR_PRODUCT_REQUEST_TBL." VP 
		LEFT JOIN ".VENDOR_TBL." V ON (VP.vendor_id=V.id) LEFT JOIN ".PRODUCT_REQUEST_STATUS_TBL." RS ON (RS.id=VP.request_status)  WHERE 1 ORDER BY id DESC " ;

	    $exe = $this->exeQuery($q);
	    if(mysqli_num_rows($exe)>0){
	    	$i=1;
	    	while($rows = mysqli_fetch_array($exe)){
	    		$list 			  = $this->editPagePublish($rows);
			 	$request_status   = (($list['request_status']==0)? "<span class='badge badge-dot badge-dot-xs badge-warning'>Inprocess</span>" : "<span class='badge  badge-dot-xs badge-info'>".$list['request_status_name']."</span>" );

			$layout .= "
				<tr class='nk-tb-item open_data_model' data-option='".$this->encryptData($list['id'])."'>
					<td class='nk-tb-col'>
	                    ".$i."
	                </td>
	                <td class='nk-tb-col tb-col-md'>
                        <span class='tb-lead'>".$list['company']."<span class='dot dot-success d-md-none ml-1'></span></span>
                        <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                        <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
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

    // Get product request status master dropdown

	function getproductRequestDropDown($current="")
  	{
  		$layout = "";
  		$q = "SELECT id,request_status FROM ".PRODUCT_REQUEST_STATUS_TBL." WHERE status='1' AND delete_status='0' " ;
  		$query = $this->exeQuery($q);
	    if(@mysqli_num_rows($query)>0){
	    	$i=0;
	    	while($list = mysqli_fetch_array($query)){
				$selected = (($list['id']==$current) ? 'selected' : '');
				$layout.= "<option value='".$list['id']."' $selected>".$list['request_status']."</option>";
                $i++;
	    	}
	    }
	    return $layout;
	}

    // Get productRequestDetails

	function getproductRequestDetails($data)
	{
		$layout = "";
		$id 	= $this->decryptData($data);
		$info 	= $this->getDetails(VENDOR_PRODUCT_REQUEST_TBL,"*"," id='$id' ");
		$v_info	= $this->getDetails(VENDOR_TBL,"*"," id='".$info['vendor_id']."' ");
		$info 	= $this->editPagePublish($info);
		$v_info = $this->editPagePublish($v_info);
		$request_status_info 	= $this->getDetails(PRODUCT_REQUEST_STATUS_TBL,"*"," id='".$info['request_status']."' ");

		$status = (($info['status']==1) ? "Approved" : "Pending"); 
		$status_class = (($info['status']==1) ? "text-success" : "text-warning");

		if(!$info['request_status']) {

			$request_status_form = "<input type='hidden' name='token' value='".$this->encryptData($info['id'])."'>
	                    <div class='form-group '>
		                    <div class='form-control-wrap'>
		                        <select class='form-control form-control-md' data-search='on'  name='request_status_id' required>
		                            ".$this->getproductRequestDropDown()."
		                        </select>
		                    </div>
		                </div>
	                    <p class='float-right model_pt'>
                            <button type='button' class='btn btn-light close_prd_req_sts_modal' data-form='viewReview' data-formclass='view_contact_class'> Cancel</button>
                            <button type='submit' class='btn btn-primary ' data-form='viewReview' data-formclass='view_contact_class'> Submit</button>
                        </p>";
		} else {

        	// $request_status_form = "<span class='badge badge-outline-secondary badge-md'>".$request_status_info['request_status']."</span>";

        	$request_status_form = "<input type='hidden' name='token' value='".$this->encryptData($info['id'])."'>
	                    <div class='form-group '>
		                    <div class='form-control-wrap'>
		                        <select class='form-control form-control-md' data-search='on'  name='request_status_id' required>
		                            ".$this->getproductRequestDropDown($info['request_status'])."
		                        </select>
		                    </div>
		                </div>
	                    <p class='float-right model_pt'>
                            <button type='button' class='btn btn-light close_prd_req_sts_modal' data-form='viewReview' data-formclass='view_contact_class'> Cancel</button>
                            <button type='submit' class='btn btn-primary ' data-form='viewReview' data-formclass='view_contact_class'> Update</button>
                        </p>";


		}

		

		$layout .="

		<div class='nk-block'>
	                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                            <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>Vendor Name</span>
	                                <span class='profile-ud-value enq_name_align'>".$v_info['name']."</span>
	                            </div>
	                        </div>
	                    </div>
	                     <div class='profile-ud-list'>
	                       <div class='profile-ud-item '>
	                            <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>Mobile Number</span>
	                                <span class='profile-ud-value enq_name_align'>".$v_info['mobile']."</span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                             <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>Email Address</span>
	                                <span class='profile-ud-value enq_name_align'>".$v_info['email']."</span>
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
	                        <h6 class='title overline-title text-base'>Description</h6>
	                    </div>
	                    <div class='enq_message'>
	                        <p>".$info['description']."</p>
	                    </div>
	                </div>
	                <div class='nk-block nk_block_pt'>
	                    <div class='nk-block-head nk-block-head-line'>
	                        <h6 class='title overline-title text-base'>Product Request Status</h6>
	                    </div>
	                    ".$request_status_form."
	                </div>
					";
		return $layout;
	}

	// Update Product Request Status update 

	function productRequestStatusupdate($data)
	{	
		$id   = $this->decryptData($data['token']);
		$query  = "UPDATE ".VENDOR_PRODUCT_REQUEST_TBL." SET request_status='".$data['request_status_id']."' WHERE id='".$id."' ";
		$up_exe = $this->exeQuery($query);
		if($up_exe){
			return 1;
		}
	}
			
	/*-----------Dont'delete---------*/

}?>




