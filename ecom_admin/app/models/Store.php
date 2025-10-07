<?php
	require_once 'Model.php';
	require_once 'FileUploader.php';
	require_once 'MultipleFileUploader.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class Store extends Model
	{

		/*--------------------------------------------- 
					  Blog Management
		----------------------------------------------*/

		// Manage Blog 

		function manageBlog() 
		{
			$layout = "";
	    	$q = "SELECT B.id,B.page_url,B.title,B.category_id,B.file_name,B.delete_status,B.status,C.category, B.is_draft FROM ".BLOG_TBL." B LEFT JOIN ".BLOG_CATEGORY_TBL." C ON (C.id=B.category_id) WHERE 1 AND B.delete_status='0' ORDER BY B.id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					// Draft Status
					$draft_status = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row = (($list['is_draft']==1) ? "draft_item" : ""); 


					if ($list['is_draft']==1) {
						$draft_action = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishBlog' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class = 'deleteBlog';
						$delete_class_hover = 'Delete Blog';

					}else{
						$draft_action = "<div class='tb-tnx-status'>
	                            	<span class='badge badge-dot text-success cursor_pointer'> Published </span>
	                            </div>";
	                    $delete_class = 'trashBlog';
	                    $delete_class_hover = 'Trash Blog';
	                    
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

					$td_data = " data-data_id='".$list['id']."' data-data_link='blog/edit' ";

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
                                <span class='text-primary'>".$this->publishContent($list['title'])."</span>
	                        </td>
	                        <td class='nk-tb-col td_click' $td_data>
                                ".$list['category']."
                            </td>
                            <td class='nk-tb-col td_click' $td_data>
                               	$draft_action
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeBlogStatus' data-option='".$this->encryptData($list['id'])."' value='0'  id='status_".$i."' name='save_as_draft' $status_c>
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

		// Add Blog 

		function addBlog($input,$image)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$data     = $this->cleanStringData($input);
				$page_url = $this->hyphenize($data['page_url']);
				$admin_id = $_SESSION["ecom_admin_id"];
				$curr 	  = date("Y-m-d H:i:s");
				$save_as_draft = isset($data['save_as_draft']) ? 1 : 0;

				// Create New / Get category

				$category_id = $this->addBlogCategoryInline($data['blog_category']);

				// Date
				$blog_date_array = explode("/", $input['blog_date']);
				$blog_date = $blog_date_array[2]."-".$blog_date_array[1]."-".$blog_date_array[0];

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
				
				$check_token = $this->check_query(BLOG_TBL,"id"," page_url='".$page_url."' ");
				if($check_token==0) {	
					if ($file_status=="ok") {
						$query = "INSERT INTO ".BLOG_TBL." SET 
								page_url 				= '".$page_url."',
								title 					= '".$data['title_name']."',
								category_id 			= '".$category_id."',
								blog_date 				= '".$blog_date."',
								short_description 		= '".$data['short_description']."',
								description 			= '".$data['description']."',
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
				} else {
					$info = $this->getDetails(BLOG_TBL,"delete_status"," page_url='".$page_url."' ");
					if ($info['delete_status']==1) {
						return "The entered blog title already present on the trash. Kindly restore it from the trash section.";
					}else{
						return "The entered blog title already exists. ";
					}
				}
				
				if($exe){
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Add Blog Category Inline

		function addBlogCategoryInline($input)
		{
			$token = $this->uniqueToken($input);
			$check_token = $this->check_query(BLOG_CATEGORY_TBL,"id"," token='$input' OR id ='$input' ");
			if ($check_token==0) {
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$query = "INSERT INTO ".BLOG_CATEGORY_TBL." SET 
							token 			= '".$this->hyphenize($token)."',
							category 		= '".$this->cleanString($input)."',
							status			= '1',
							added_by 		= '$admin_id',	
							created_at 		= '$curr',
							updated_at 		= '$curr' ";
				$last_id 	= $this->lastInserID($query);
				return $last_id;
			}else{
				return $input;
			}
		}

		// Get Blog Category to map 

		function getblogCategory($current="")
	  	{
	  		$layout = "";
	  		$q = "SELECT id,token,category FROM ".BLOG_CATEGORY_TBL." WHERE status='1' AND delete_status='0' " ;
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

		// Edit Blog 

		function editBlog($input,$image)
		{ 
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$data = $this->cleanStringData($input);
				$id =	$this->decryptData($data['session_token']);

				

				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;

				// Create New / Get category
				$category_id = $this->addBlogCategoryInline($data['blog_category']);

				// Date
				$blog_date_array = explode("/", $input['blog_date']);
				$blog_date = $blog_date_array[2]."-".$blog_date_array[1]."-".$blog_date_array[0];

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
						$info = $this->getDetails(BLOG_TBL,"file_name"," id='$id' ");
						if ($info['file_name']!='') {
							unlink("./resource/uploads/".$info['file_name']);
						}
					}else{
						$file_status = "ok";
						$image_q = "";
					}	

					$page_url    = $this->hyphenize($data['page_url']);
					$check_token = $this->check_query(BLOG_TBL,"id"," page_url='".$page_url."' AND id!='$id' ");

					if (isset($data['save_as_draft'])==1) {
						if ($check_token==0) {
							$token_q = "page_url    = '".$page_url."', 
										title   	= '".$data['title_name']."',
										";
						}else{
							$check_token == 1;
						}
							
					}else{
						$token_q = '';
					}

					if($check_token==0) { 
						if ($file_status=="ok") {
							 $query = "UPDATE ".BLOG_TBL." SET 
							 	   ".$token_q."
									category_id 			= '".$category_id."',
									blog_date 				= '".$blog_date."',
									short_description 		= '".$data['short_description']."',
									description 			= '".$data['description']."',
									is_draft 				= '".$save_as_draft."'
									".$image_q." 
									, meta_title 				= '".$data['meta_title']."',
									meta_description 		= '".$data['meta_description']."',
									meta_keyword 			= '".$data['meta_keyword']."', 
									updated_at 				= '$curr' WHERE id='$id' ";
							$exe 	= $this->exeQuery($query);
						}else{
							return $image['message'];
						}
					} else {
						$info = $this->getDetails(BLOG_TBL,"delete_status"," page_url='".$page_url."'  ");
						if ($info['delete_status']==1) {
							return "The entered blog title already present on the trash. Kindly restore it from the trash section.";
						}else{
							return "The entered blog title already exists. ";
						}
					}
				if($exe){
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Change Blog  Status

		function changeBlogStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(BLOG_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".BLOG_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".BLOG_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update Blog delete status

		function deleteBlog($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(BLOG_TBL,"file_name"," id ='$data' AND is_draft='1' ");
			// Remove Image
			if ($info['file_name']!='') {
				unlink("./resource/uploads/".$info['file_name']);
			}
			$delete = $this -> deleteRow(BLOG_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}

		// Trash Blog

		function trashBlog($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".BLOG_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Publish Blog

		function publishBlog($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".BLOG_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		// Restore Bolg

		function restoreBlog($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".BLOG_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					 Blog Category Management
		----------------------------------------------*/

		// Manage Blog Category

		function manageBlogCategory() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".BLOG_CATEGORY_TBL." WHERE delete_status='0'  ORDER BY id DESC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					$td_data = " data-formclass='edit_blog_category' data-form='addBlogCategory' data-option='".$this->encryptData($list['id'])."' ";

		    		$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col openEditBlogCategory' $td_data>
                                ".$i."
                            </td>
                            <td class='nk-tb-col openEditBlogCategory' $td_data>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col tb-col-md openEditBlogCategory' $td_data>
                                <span class='text-primary'>".$this->publishContent($list['category'])."</span>
	                        </td>    	                            
                            <td class='nk-tb-col tb-col-md'>
                            <div class='custom-control custom-switch'>
                                <input type='checkbox' class='custom-control-input changeBlogCategoryStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' name='save_as_draft' $status_c>
                                <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                            </div>
                        	</td>
                            <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                    <li class='nk-tb-action-hidden'>
                                        <button class='btn btn-trigger btn-icon deleteBlogCategory' data-formclass='edit_blog_category' data-form='editBlogCategory' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
                                    </li>
                                </ul>
                        	</td>
                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}

		// Add Blog Category

		function addBlogCategory($input)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$data = $this->cleanStringData($input);
				$token = $this->uniqueToken($data['category_name']);
				$check_token = $this->check_query(BLOG_CATEGORY_TBL,"id"," token='$token' ");
				if ($check_token==0) {
					$admin_id 		= $_SESSION["ecom_admin_id"];
					$curr 			= date("Y-m-d H:i:s");
					$query = "INSERT INTO ".BLOG_CATEGORY_TBL." SET 
								token 			= '".$this->hyphenize($token)."',
								category 		= '".$data['category_name']."',
								status			='1',
								added_by 		= '$admin_id',	
								created_at 		= '$curr',
								updated_at 		= '$curr' ";
					$exe 	= $this->exeQuery($query);
					if($exe){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}	
				}else{
					$info = $this->getDetails(BLOG_CATEGORY_TBL,"id,delete_status"," token='$token' ");
					if ($info['delete_status']==1) {
						return "The entered category already present on the trash. Kindly restore it from the trash section.";
					}else{
						return "The entered category already exists. ";
					}
				}
			}else{
				return $validate_csrf;
			}
		}

		// Get Blog Category Details

		function getBlogCategoryDetails($data)
		{	
			$layout = "";
			$id     = $this->decryptData($data);
			$query  = "SELECT * FROM  ".BLOG_CATEGORY_TBL."  WHERE id='$id' ";
			$exe 	= $this->exeQuery($query);
			if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$layout .= "<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' id='token'>
				                <div class='form-group'>
				                    <label class='form-label'>Blog Category Name
				                        <en>*</en>
				                    </label>
				                    <input type='text' name='category_name' value='".$list['category']."'  id='category_name'  class='form-control' placeholder='Enter Blog Category Name' required>
				                </div>
					            <div class='form-error'>
					            </div>
					            <div class='form-group'>
					                <p class='float-right model_pt'>
					                    <button type='button' class='btn btn-light close_modal' data-modal_id='editBlogCategoryModal'>Cancel</button>
					                    <button type='submit' class='btn btn-primary'>Submit</button>
					                </p>
					            </div>";
				}
			}
			return $layout;
		}

		// Edit Blog Category

		function editBlogCategory($input)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$curr 		= date("Y-m-d H:i:s");
				$id 		=	$this->decryptData($input['token']);
				$query 		= "UPDATE ".BLOG_CATEGORY_TBL." SET 
							category = '".$this->cleanString($input['category_name'])."',
							updated_at = '$curr' WHERE id='$id' ";
				$exe 		= $this->exeQuery($query);
				if($exe){
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Change Blog Category Status

		function changeBlogCategoryStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(BLOG_CATEGORY_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".BLOG_CATEGORY_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".BLOG_CATEGORY_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update Blog Category delete status

		function deleteBlogCategory($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(BLOG_CATEGORY_TBL,"delete_status"," id ='$data' ");
			$query = "UPDATE ".BLOG_CATEGORY_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Bolg Category

		function restoreBlogCategory($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".BLOG_CATEGORY_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					 Home Slider Management
		----------------------------------------------*/

		// Manage Home Slider

		function manageHomeSlider() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".HOME_BANNER_TBL."  WHERE 1 AND delete_status='0' ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					// Draft Status
					$draft_status = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row = (($list['is_draft']==1) ? "draft_item" : ""); 

					if ($list['is_draft']==1) {
						$draft_action = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishHomeSlider' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class = 'deleteHomeSlider';
						$delete_class_hover = 'Delete Home Slider';

					}else{
						$draft_action = "<div class='tb-tnx-status'>
	                            	<span class='badge badge-dot text-success cursor_pointer'> Published </span>
	                            </div>";
	                    $delete_class = 'trashHomeSlider';
	                    $delete_class_hover = 'Trash Home Slider';
	                    
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

					$td_data = " data-data_id='".$list['id']."' data-data_link='homeslider/edit' ";

		    		$layout .= "
	    				<tr class='nk-tb-item'>
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
                                <span class='text-primary'>".$this->publishContent($list['title'])."</span>
	                        </td>
	                        <td class='nk-tb-col td_click' $td_data>
                                ".$list['message']."
                            </td>
                            <td class='nk-tb-col td_click' $td_data>
                               	$draft_action
                            </td>
	                         <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeSliderStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
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

		// Add Home Slider

		function addHomeSlider($input,$image)
		{
			$validate_csrf = $this->validateCSRF($input);
			$data = $this->editPagePublish($input);
			if($validate_csrf=='success') {
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft = isset($input['save_as_draft']) ? 1 : 0;
				if ($image['is_uploaded']) {
					$file_status = $image['status'];
					$image_q = "
						, file_name 	= '".$image['file_name']."',
						file_type 		= '".$image['file_type']."',
						file_size 		= '".$image['file_size']."',
						file_alt_name 	= '".$input['image_alt_name']."'
					";
				}else{
					$file_status = "ok";
					$image_q = "";
				}

				$button_link 	= ($data['button_type'] == "main_category") ? $data['main_category_id'] : (($data['button_type'] == "sub_category")  ? $data['sub_category_id'] : $data['product']);

				$query = "INSERT INTO ".HOME_BANNER_TBL." SET 
						title 			= '".$this->cleanString($data['title_name'])."',
						message 		= '".$this->cleanString($data['message'])."',
						button_type     = '".$this->cleanString($data['button_type'])."',
						button_link 	= '".$button_link."',
						button_name 	= '".$this->cleanString($data['bname'])."',
						sort_order 		= '".$this->cleanString($data['sort_order'])."',
						is_draft 		= '".$save_as_draft."'
						".$image_q." 
						,status			= '1',
						created_at 		= '$curr',
						updated_at 		= '$curr' ";
				$exe 	= $this->exeQuery($query);
				if($exe){
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Edit Home Slider

		function editHomeSlider($input,$image)
		{ 
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$curr 			= date("Y-m-d H:i:s");
				$id =	$this->decryptData($input['session_token']);
				$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;
					if ($image['is_uploaded']) {
						$file_status = $image['status'];
						$image_q = "
							, file_name 	= '".$image['file_name']."',
							file_type 		= '".$image['file_type']."',
							file_size 		= '".$image['file_size']."',
							file_alt_name 	= '".$input['image_alt_name']."'
						";

						// Remove previous image
						$info = $this->getDetails(HOME_BANNER_TBL,"file_name"," id='$id' ");
						if ($info['file_name']!='') {
							unlink("./resource/uploads/".$info['file_name']);
						}
					}else{
						$file_status = "ok";
						$image_q = "";
					}
					if ($file_status=="ok") {

					$button_link 	= ($input['button_type'] == "main_category") ? $input['main_category_id'] : (($input['button_type'] == "sub_category")  ? $input['sub_category_id'] : $input['product']);

					$query = "UPDATE ".HOME_BANNER_TBL." SET 
								title 			= '".$input['title_name']."',
								message 		= '".$input['message']."',
								button_type     = '".$this->cleanString($input['button_type'])."',
								button_link 	= '".$button_link."',
								button_name 	= '".$this->cleanString($input['bname'])."',
								sort_order 		= '".$this->cleanString($input['sort_order'])."',
								is_draft 		= '".$save_as_draft."'
								".$image_q." 
								,updated_at 		= '$curr' 
								WHERE id='$id' ";
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
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}	


		// Change Home Slider  Status

		function changeHomeSliderStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(HOME_BANNER_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".HOME_BANNER_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".HOME_BANNER_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Publish Home Slider

		function publishHomeSlider($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".HOME_BANNER_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		

		// Update Blog delete status

		function deleteHomeSlider($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(HOME_BANNER_TBL,"file_name"," id ='$data' AND is_draft='1' ");
			// Remove Image
			if ($info['file_name']!='') {
				unlink("./resource/uploads/".$info['file_name']);
			}
			$delete = $this -> deleteRow(HOME_BANNER_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}

		// Trash Blog

		function trashHomeSlider($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".HOME_BANNER_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Restore Home Slider

		function restoreHomeSlider($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(HOME_BANNER_TBL,"delete_status,is_draft"," id ='$data' ");
			$query = "UPDATE ".HOME_BANNER_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					 Offer Banner Management
		----------------------------------------------*/

		// Manage Offer Banner

		function manageOfferBanners() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".OFFER_BANNER_TBL."  WHERE 1 AND delete_status='0' ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					// Draft Status
					$draft_status = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row = (($list['is_draft']==1) ? "draft_item" : ""); 

					if ($list['is_draft']==1) {
						$draft_action = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishOfferBanner' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class = 'deleteOfferBanner';
						$delete_class_hover = 'Delete Offer Banner';

					}else{
						$draft_action = "<div class='tb-tnx-status'>
	                            	<span class='badge badge-dot text-success cursor_pointer'> Published </span>
	                            </div>";
	                    $delete_class = 'trashOfferBanner';
	                    $delete_class_hover = 'Trash Offer Banner';
	                    
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

					$td_data = " data-data_id='".$list['id']."' data-data_link='offerbanner/edit' ";

		    		$layout .= "
	    				<tr class='nk-tb-item'>
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
                                <span class='text-primary'>".$this->publishContent($list['title'])."</span>
	                        </td>
	                        <td class='nk-tb-col td_click' $td_data>
                                ".$list['message']."
                            </td>
                            <td class='nk-tb-col td_click' $td_data>
                               	$draft_action
                            </td>
	                         <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeOfferBannerStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                                </div>
	                        </td>
                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}

		// Add Offer Banner

		function addOfferBanner($input,$image)
		{	
			$validate_csrf = $this->validateCSRF($input);
			$data = $this->editPagePublish($input);
			if($validate_csrf=='success') {
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft = isset($input['save_as_draft']) ? 1 : 0;
				if ($image['is_uploaded']) {
					$file_status = $image['status'];
					$image_q = "
						, file_name 	= '".$image['file_name']."',
						file_type 		= '".$image['file_type']."',
						file_size 		= '".$image['file_size']."',
						file_alt_name 	= '".$input['image_alt_name']."'
					";
				}else{
					$file_status = "ok";
					$image_q = "";
				}

				$button_link 	= ($data['button_type'] == "main_category") ? $data['main_category_id'] : (($data['button_type'] == "sub_category")  ? $data['sub_category_id'] : $data['product']);

				$query = "INSERT INTO ".OFFER_BANNER_TBL." SET 
						title 			= '".$this->cleanString($data['title_name'])."',
						message 		= '".$this->cleanString($data['message'])."',
						button_type     = '".$this->cleanString($data['button_type'])."',
						button_link 	= '".$button_link."',
						button_name 	= '".$this->cleanString($data['bname'])."',
						is_draft 		= '".$save_as_draft."'
						".$image_q." 
						,status			= '1',
						created_at 		= '$curr',
						updated_at 		= '$curr' ";
				$exe 	= $this->exeQuery($query);
				if($exe){
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Edit Offer Banner

		function editOfferBanner($input,$image)
		{ 
			
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$curr 			= date("Y-m-d H:i:s");
				$id =	$this->decryptData($input['session_token']);
				$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;
					if ($image['is_uploaded']) {
						$file_status = $image['status'];
						$image_q = "
							, file_name 	= '".$image['file_name']."',
							file_type 		= '".$image['file_type']."',
							file_size 		= '".$image['file_size']."',
							file_alt_name 	= '".$input['image_alt_name']."'
						";

						// Remove previous image
						$info = $this->getDetails(OFFER_BANNER_TBL,"file_name"," id='$id' ");
						if ($info['file_name']!='') {
							unlink("./resource/uploads/".$info['file_name']);
						}
					}else{
						$file_status = "ok";
						$image_q = "";
					}
					if ($file_status=="ok") {

					$button_link 	= ($input['button_type'] == "main_category") ? $input['main_category_id'] : (($input['button_type'] == "sub_category")  ? $input['sub_category_id'] : $input['product']);

					$query = "UPDATE ".OFFER_BANNER_TBL." SET 
								title 			= '".$input['title_name']."',
								message 		= '".$input['message']."',
								button_type     = '".$this->cleanString($input['button_type'])."',
								button_link 	= '".$button_link."',
								button_name 	= '".$this->cleanString($input['bname'])."',
								is_draft 		= '".$save_as_draft."'
								".$image_q." 
								,updated_at 		= '$curr' 
								WHERE id='$id' ";
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
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}	


		// Change Offer Banner  Status

		function changeOfferBannerStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(OFFER_BANNER_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".OFFER_BANNER_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".OFFER_BANNER_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Publish Offer Banner

		function publishOfferBanner($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".OFFER_BANNER_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		

		// Update Blog delete status

		function deleteOfferBanner($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(OFFER_BANNER_TBL,"file_name"," id ='$data' AND is_draft='1' ");
			// Remove Image
			if ($info['file_name']!='') {
				unlink("./resource/uploads/".$info['file_name']);
			}
			$delete = $this -> deleteRow(OFFER_BANNER_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}

		// Trash Blog

		function trashOfferBanner($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".OFFER_BANNER_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Restore Offer Banner

		function restoreOfferBanner($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(OFFER_BANNER_TBL,"delete_status,is_draft"," id ='$data' ");
			$query = "UPDATE ".OFFER_BANNER_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*------------------------------------------------- 
				Special Offer Banner Management
		---------------------------------------------------*/

		// Manage Special Offer Banner

		function manageSpecialOfferBanner() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".SPECIAL_BANNER_TBL."  WHERE 1 AND delete_status='0' ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					// Draft Status
					$draft_status = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row = (($list['is_draft']==1) ? "draft_item" : ""); 

					if ($list['is_draft']==1) {
						$draft_action = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishSpecialOfferBanner' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class = 'deleteSpecialOfferBanner';
						$delete_class_hover = 'Delete Special Offer Banner';

					}else{
						$draft_action = "<div class='tb-tnx-status'>
	                            	<span class='badge badge-dot text-success cursor_pointer'> Published </span>
	                            </div>";
	                    $delete_class = 'trashSpecialOfferBanner';
	                    $delete_class_hover = 'Trash Special Offer Banner';
	                    
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

					$td_data = " data-data_id='".$list['id']."' data-data_link='specialofferbanner/edit' ";

		    		$layout .= "
	    				<tr class='nk-tb-item'>
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
                                <span class='text-primary'>".$this->publishContent($list['title'])."</span>
	                        </td>
	                        <td class='nk-tb-col td_click' $td_data>
                                ".$list['message']."
                            </td>
                            <td class='nk-tb-col td_click' $td_data>
                               	$draft_action
                            </td>
	                         <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeSliderStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                                </div>
	                        </td>
                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}

		// Add Special Offer Banner

		function addSpecialOfferBanner($input,$image)
		{
			$validate_csrf = $this->validateCSRF($input);
			$data = $this->editPagePublish($input);
			if($validate_csrf=='success') {
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft = isset($input['save_as_draft']) ? 1 : 0;
				if ($image['is_uploaded']) {
					$file_status = $image['status'];
					$image_q = "
						, file_name 	= '".$image['file_name']."',
						file_type 		= '".$image['file_type']."',
						file_size 		= '".$image['file_size']."',
						file_alt_name 	= '".$input['image_alt_name']."'
					";
				}else{
					$file_status = "ok";
					$image_q = "";
				}

				$button_link 	= ($data['button_type'] == "main_category") ? $data['main_category_id'] : (($data['button_type'] == "sub_category")  ? $data['sub_category_id'] : $data['product']);

				$query = "INSERT INTO ".SPECIAL_BANNER_TBL." SET 
						title 			= '".$this->cleanString($data['title_name'])."',
						message 		= '".$this->cleanString($data['message'])."',
						button_type     = '".$this->cleanString($data['button_type'])."',
						button_link 	= '".$button_link."',
						button_name 	= '".$this->cleanString($data['bname'])."',
						is_draft 		= '".$save_as_draft."'
						".$image_q." 
						,status			= '1',
						created_at 		= '$curr',
						updated_at 		= '$curr' ";
				$exe 	= $this->exeQuery($query);
				if($exe){
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Edit Special Offer Banner

		function editSpecialOfferBanner($input,$image)
		{ 
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$curr 			= date("Y-m-d H:i:s");
				$id =	$this->decryptData($input['session_token']);
				$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;
					if ($image['is_uploaded']) {
						$file_status = $image['status'];
						$image_q = "
							, file_name 	= '".$image['file_name']."',
							file_type 		= '".$image['file_type']."',
							file_size 		= '".$image['file_size']."',
							file_alt_name 	= '".$input['image_alt_name']."'
						";

						// Remove previous image
						$info = $this->getDetails(SPECIAL_BANNER_TBL,"file_name"," id='$id' ");
						if ($info['file_name']!='') {
							unlink("./resource/uploads/".$info['file_name']);
						}
					}else{
						$file_status = "ok";
						$image_q = "";
					}
					if ($file_status=="ok") {

					$button_link 	= ($input['button_type'] == "main_category") ? $input['main_category_id'] : (($input['button_type'] == "sub_category")  ? $input['sub_category_id'] : $input['product']);

					$query = "UPDATE ".SPECIAL_BANNER_TBL." SET 
								title 			= '".$input['title_name']."',
								message 		= '".$input['message']."',
								button_type     = '".$this->cleanString($input['button_type'])."',
								button_link 	= '".$button_link."',
								button_name 	= '".$this->cleanString($input['bname'])."',
								is_draft 		= '".$save_as_draft."'
								".$image_q." 
								,updated_at 		= '$curr' 
								WHERE id='$id' ";
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
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}	


		// Change Special Offer Banner  Status

		function changeSpecialOfferBannerStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(SPECIAL_BANNER_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".SPECIAL_BANNER_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".SPECIAL_BANNER_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		/*--------------------------------------------- 
					  Brand Management
		----------------------------------------------*/

		// Manage Brand 

		function manageBrand() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".BRAND_TBL." WHERE 1 AND delete_status='0' ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					// Draft Status
					$draft_status = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row = (($list['is_draft']==1) ? "draft_item" : ""); 


					if ($list['is_draft']==1) {
						$draft_action = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishBrand' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class = 'deleteBrand';
						$delete_class_hover = 'Delete Brand';

					}else{
						$draft_action = "<div class='tb-tnx-status'>
	                            	<span class='badge badge-dot text-success cursor_pointer'> Published </span>
	                            </div>";
	                    $delete_class = 'trashBrand';
	                    $delete_class_hover = 'Trash Brand';
	                    
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

					$td_data = " data-data_id='".$list['id']."' data-data_link='brand/edit' ";
					
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
                                <span class='text-primary'>".$this->publishContent($list['brand_name'])."</span>
	                        </td>
	                        <td class='nk-tb-col td_click' $td_data>
                               	$draft_action
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeBrandStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
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

		// Add Brand 

		function addBrand($input,$image)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$data = $this->cleanStringData($input);
				$page_url = $this->hyphenize($data['page_url']);
				$check_token = $this->check_query(BRAND_TBL,"id"," page_url='".$page_url."' ");
				if ($check_token==0) {
					$token = $page_url;
				}else{
					$token = $page_url."-".$this->generateRandomString("5");
				}
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft = isset($data['save_as_draft']) ? 1 : 0;

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
						$query = "INSERT INTO ".BRAND_TBL." SET 
								page_url 				= '".$token."',
								brand_name				= '".$data['title_name']."',
								description 			= '".$data['description']."',
								sort_order 				= '".$data['sort_order']."'
								".$image_q." 
								, meta_title 			= '".$data['meta_title']."',
								meta_description 		= '".$data['meta_description']."',
								meta_keyword 			= '".$data['meta_keyword']."',
								is_draft 				= '".$save_as_draft."',
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
			}else{
				return $validate_csrf;
			}
		}

		// Edit Brand 

		function editBrand($input,$image)
		{ 
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$data = $this->cleanStringData($input);
				$id =	$this->decryptData($data['session_token']);

				if (isset($data['save_as_draft'])==1) {
					$page_url = $this->hyphenize($data['page_url']);
					$check_token = $this->check_query(BRAND_TBL,"id"," page_url='".$page_url."' AND id!='$id' ");
					if ($check_token==0) {
						$token = $page_url;
					}else{
						$token = $page_url."-".$this->generateRandomString("5");
					}
					$token_q = "page_url  = '".$token."', ";
				}else{
					$token_q = '';
				}

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
						$info = $this->getDetails(BRAND_TBL,"file_name"," id='$id' ");
						if ($info['file_name']!='') {
							unlink("./resource/uploads/".$info['file_name']);
						}
					}else{
						$file_status = "ok";
						$image_q = "";
					}	

					if ($file_status=="ok") {
						 $query = "UPDATE ".BRAND_TBL." SET 
								".$token_q."
								brand_name				= '".$data['title_name']."',
								description 			= '".$data['description']."',
								sort_order 				= '".$data['sort_order']."'
								".$image_q." 
								, meta_title 			= '".$data['meta_title']."',
								meta_description 		= '".$data['meta_description']."',
								meta_keyword 			= '".$data['meta_keyword']."',
								is_draft 				= '".$save_as_draft."',
								status					= '1',
								added_by 				= '$admin_id',	
								created_at 				= '$curr',
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
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Change Brand  Status

		function changeBrandStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(BRAND_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".BRAND_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".BRAND_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update Brand delete status

		function deleteBrand($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(BRAND_TBL,"file_name"," id ='$data' AND is_draft='1' ");
			// Remove Image
			if ($info['file_name']!='') {
				unlink("./resource/uploads/".$info['file_name']);
			}
			$delete = $this -> deleteRow(BRAND_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}

		// Trash Brand

		function trashBrand($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".BRAND_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Publish Brand

		function publishBrand($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".BRAND_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		// Restore Bolg

		function restoreBrand($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".BRAND_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					  Legal Page Management
		----------------------------------------------*/

		// Manage Legal Page 

		
		function manageLegelPages() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".LEGAL_PAGE_TBL." WHERE 1 AND delete_status='0' ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					// Draft Status
					$draft_status = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row = (($list['is_draft']==1) ? "draft_item" : ""); 


					if ($list['is_draft']==1) {
						$draft_action = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishLegalPage' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class = 'deleteLegalPage';
						$delete_class_hover = 'Delete Legal Page';

					}else{
						$draft_action = "<div class='tb-tnx-status'>
	                            	<span class='badge badge-dot text-success cursor_pointer'> Published </span>
	                            </div>";
	                    $delete_class = 'trashLegalPage';
	                    $delete_class_hover = 'Trash Legal Page';
	                    
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

					$td_data = " data-data_id='".$list['id']."' data-data_link='legalpages/edit' ";

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
                                <span class='text-primary'>".$this->publishContent($list['title'])."</span>
	                        </td>
	                        <td class='nk-tb-col td_click' $td_data>
                               	$draft_action
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeLegalPageStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
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

		// Add Legal Page 

		function addLegelPage($input,$image)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$data = $this->cleanStringData($input);
				$page_token = $this->hyphenize($data['page_url']);
				$check_token = $this->check_query(LEGAL_PAGE_TBL,"id"," page_url='".$page_token."' ");
				if ($check_token==0) {
					$page_url = $page_token;
				}else{
					$page_url = $page_token."-".$this->generateRandomString("5");
				}
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft = isset($data['save_as_draft']) ? 1 : 0;

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
					$query = "INSERT INTO ".LEGAL_PAGE_TBL." SET 
							page_url 				= '".$page_url."',
							sort_order 				= '".$data['sort_order']."',
							title 					= '".$this->cleanString($data['title_name'])."',
							content 				= '".$this->cleanString($data['description'])."',
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
			}else{
				return $validate_csrf;
			}
		}

		// Edit Legal Page 

		function editLegelPage($input,$image)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$data = $this->cleanStringData($input);
				$id =	$this->decryptData($data['session_token']);

				if (isset($data['save_as_draft'])==1) {
					$page_token = $this->hyphenize($data['page_url']);
					$check_token = $this->check_query(LEGAL_PAGE_TBL,"id"," page_url='".$page_token."' AND id!='$id' ");
					if ($check_token==0) {
						$page_url = $page_token;
					}else{
						$page_url = $page_token."-".$this->generateRandomString("5");
					}
					$token_q = "page_url 					= '".$page_url."', ";
				}else{
					$token_q = '';
				}

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
					$info = $this->getDetails(LEGAL_PAGE_TBL,"file_name"," id='$id' ");
					if ($info['file_name']!='') {
						unlink("./resource/uploads/".$info['file_name']);
					}
				}else{
					$file_status = "ok";
					$image_q = "";
				}	

				if ($file_status=="ok") {
					 	$query = "UPDATE ".LEGAL_PAGE_TBL." SET 
							".$token_q."
							sort_order 				= '".$data['sort_order']."',
							title 					= '".$this->cleanString($data['title_name'])."',
							content 				= '".$this->cleanString($data['description'])."',
							is_draft 				= '".$save_as_draft."'
							".$image_q." 
							, meta_title 				= '".$data['meta_title']."',
							meta_description 		= '".$data['meta_description']."',
							meta_keyword 			= '".$data['meta_keyword']."',
							status					= '1',
							added_by 				= '$admin_id',	
							created_at 				= '$curr',
							updated_at 				= '$curr' WHERE id='$id' ";
					$exe 	= $this->exeQuery($query);
					if($exe){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}
				}else{
					return $image['message'];
				}
				
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Change Legal Page  Status

		function changeLegelPageStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(LEGAL_PAGE_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".LEGAL_PAGE_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".LEGAL_PAGE_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update Legal Page delete status

		function deleteLegelPage($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(LEGAL_PAGE_TBL,"file_name"," id ='$data' AND is_draft='1' ");
			// Remove Image
			if ($info['file_name']!='') {
				unlink("./resource/uploads/".$info['file_name']);
			}
			$delete = $this -> deleteRow(LEGAL_PAGE_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}

		// Trash Legal Page

		function trashLegelPage($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".LEGAL_PAGE_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Publish Legal Page

		function publishLegelPage($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".LEGAL_PAGE_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		// Restore Legal Page

		function restoreLegelPage($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".LEGAL_PAGE_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					  SEO Content Management
		----------------------------------------------*/

		// Manage SEO Content 
		
		function manageSeoContents() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".SEO_TBL." WHERE 1 AND delete_status='0' ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					// Draft Status
					$draft_status = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row = (($list['is_draft']==1) ? "draft_item" : ""); 


					if ($list['is_draft']==1) {
						$draft_action = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishSeoContent' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class = 'deleteSeoContent';
						$delete_class_hover = 'Delete SEO Content';

					}else{
						$draft_action = "<div class='tb-tnx-status'>
	                            	<span class='badge badge-dot text-success cursor_pointer'> Published </span>
	                            </div>";
	                    $delete_class = 'trashSeoContent';
	                    $delete_class_hover = 'Trash SEO Content';
	                    
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

					$td_data = " data-data_id='".$list['id']."' data-data_link='seo/edit' ";
					
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
                                <span class='text-primary'>".$this->publishContent($list['page_title'])."</span>
	                        </td>
	                         <td class='nk-tb-col td_click' $td_data>
                               	$draft_action
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeSeoContentStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
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

		// Add SEO Content 

		function addSeoContent($input,$image)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$data = $this->cleanStringData($input);
				$page_url = $this->hyphenize($data['page_url']);
				$check_token = $this->check_query(SEO_TBL,"id"," page_url='".$page_url."' ");
				if ($check_token==0) {
					$token = $page_url;
				}else{
					$token = $page_url."-".$this->generateRandomString("5");
				}
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft = isset($data['save_as_draft']) ? 1 : 0;

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
					$query = "INSERT INTO ".SEO_TBL." SET 
							page_url 				= '".$token."',
							page_token 				= '".$this->hyphenize($data['page_token'])."',
							page_title 				= '".$data['title_name']."',
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
					// var_dump($query);
				}else{
					return $image['message'];
				}
				
				if($exe){
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Edit SEO Content 

		function editSeoContent($input,$image)
		{ 
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$data = $this->cleanStringData($input);
				$id =	$this->decryptData($data['session_token']);

				if (isset($data['save_as_draft'])==1) {
					$page_url = $this->hyphenize($data['page_url']);
					$check_token = $this->check_query(SEO_TBL,"id"," page_url='".$page_url."' AND id!='$id' ");
					if ($check_token==0) {
						$token = $page_url;
					}else{
						$token = $page_url."-".$this->generateRandomString("5");
					}
					$token_q = "page_url 					= '".$token."', ";
				}else{
					$token_q = '';
				}

				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;

				// Add Images

				if ($image['is_uploaded']) {
					$file_status = $image['status'];
					$image_q = "
						, file_name 	= '".$image['file_name']."',
						file_type 		= '".$image['file_type']."',
						file_size 		= '".$image['file_size']."'
					";

					// Remove previous image
					$info = $this->getDetails(SEO_TBL,"file_name"," id='$id' ");
					if ($info['file_name']!='') {
						unlink("./resource/uploads/".$info['file_name']);
					}
				}else{
					$file_status = "ok";
					$image_q = "";
				}	

				if ($file_status=="ok") {
					 $query = "UPDATE ".SEO_TBL." SET 
							".$token_q."
							page_token 				= '".$this->hyphenize($data['page_token'])."',
							page_title 				= '".$data['title_name']."',
							is_draft 				= '".$save_as_draft."'
							".$image_q." 
							, meta_title 			= '".$data['meta_title']."',
							meta_description 		= '".$data['meta_description']."',
							meta_keyword 			= '".$data['meta_keyword']."',
							status					= '1',
							added_by 				= '$admin_id',
							file_alt_name 			= '".$data['image_alt_name']."',	
							created_at 				= '$curr',
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
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Change SEO Content  Status

		function changeSeoContentStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(SEO_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".SEO_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".SEO_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update SEO Content delete status

		function deleteSeoContent($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(SEO_TBL,"file_name"," id ='$data' AND is_draft='1' ");
			// Remove Image
			if ($info['file_name']!='') {
				unlink("./resource/uploads/".$info['file_name']);
			}
			$delete = $this -> deleteRow(SEO_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}

		// Trash SEO Content

		function trashSeoContent($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".SEO_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Publish SEO Content

		function publishSeoContent($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".SEO_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		// Restore SEO Content

		function restoreSeoContent($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".SEO_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}


		// Manage Testimonials 

		function manageTestimonials() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".TESTIMONIALS_TBL." WHERE 1 AND delete_status='0' ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					// Draft Status
					$draft_status = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row = (($list['is_draft']==1) ? "draft_item" : ""); 


					if ($list['is_draft']==1) {
						$draft_action = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishTestimonial' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class = 'deleteBlog';
						$delete_class_hover = 'Delete Testimonial';

					}else{
						$draft_action = "<div class='tb-tnx-status'>
	                            	<span class='badge badge-dot text-success cursor_pointer'> Published </span>
	                            </div>";
	                    $delete_class = 'trashTestimonial';
	                    $delete_class_hover = 'Trash Testimonial';
	                    
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
		    		
		    		$td_data = " data-data_id='".$list['id']."' data-data_link='testimonials/edit' ";

		    		$layout .= "
	    				<tr class='nk-tb-item $is_draft_row'>
	    					<td class='nk-tb-col td_click' $td_data>
                                ".$i."
                            </td>
                            <td class='nk-tb-col td_click' $td_data>
                               <img src='".$image."' width='50' class='img-thumbnail'/>
                            </td>
                            <td class='nk-tb-col tb-col-md td_click' $td_data>
                                <span class='text-primary'>".$this->publishContent($list['name'])."</span>
	                        </td>
	                        <td class='nk-tb-col td_click' $td_data>
                                ".$list['designation']."
                            </td>
                            <td class='nk-tb-col td_click' $td_data>
                               	$draft_action
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeTestimonialStatus' data-option='".$this->encryptData($list['id'])."' value='0'  id='status_".$i."' name='save_as_draft' $status_c>
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


		// Add Testimonials 
		function addTestimonials($input,$image)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$data = $this->cleanStringData($input);
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft = isset($data['save_as_draft']) ? 1 : 0;


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
						$query = "INSERT INTO ".TESTIMONIALS_TBL." SET 
								name 					= '".$data['name']."',
								designation				= '".$data['designation']."',
								description 			= '".$data['description']."',
								sort_order				= '".$data['sort_order']."',
								is_draft 				= '".$save_as_draft."'
								".$image_q." 
								,
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
			}else{
				return $validate_csrf;
			}
		}


		// Edit Testimonial 

		function editTestimonials($input,$image)
		{ 
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$data = $this->cleanStringData($input);
				$id =	$this->decryptData($data['session_token']);
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;


					// Add Images

					if ($image['is_uploaded']) {
						$file_status = $image['status'];
						$image_q = "
							, file_name 	= '".$image['file_name']."',
							file_type 		= '".$image['file_type']."',
							file_size 		= '".$image['file_size']."'
						";

						// Remove previous image
						$info = $this->getDetails(TESTIMONIALS_TBL,"file_name"," id='$id' ");
						if ($info['file_name']!='') {
							unlink("./resource/uploads/".$info['file_name']);
						}
					}else{
						$file_status = "ok";
						$image_q = "";
					}	

					if ($file_status=="ok") {
						$query = "UPDATE ".TESTIMONIALS_TBL." SET 
								name 					= '".$data['name']."',
								designation				= '".$data['designation']."',
								description 			= '".$data['description']."',
								sort_order				= '".$data['sort_order']."',
								file_alt_name 			= '".$data['image_alt_name']."',
								is_draft 				= '".$save_as_draft."'
								".$image_q." 
								,
								status					= '1',
								added_by 				= '$admin_id',	
								created_at 				= '$curr',
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
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Change Testimonial  Status

		function changeTestimonialStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(TESTIMONIALS_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".TESTIMONIALS_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".TESTIMONIALS_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}


		// Trash Testimonial

		function trashTestimonial($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".TESTIMONIALS_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}


		// Publish Testimonial

		function publishTestimonial($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".TESTIMONIALS_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}


		// Restore Testimonial

		function restoreTestimonial($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".TESTIMONIALS_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}


		/*--------------------------------------------- 
					 Page Banner Management
		----------------------------------------------*/

		// Manage Page Banner

		function managePageBanners() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".PAGE_BANNER_TBL."  WHERE 1 AND delete_status='0' ORDER BY id DESC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					// Draft Status
					$draft_status = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row = (($list['is_draft']==1) ? "draft_item" : ""); 

					if ($list['is_draft']==1) {
						$draft_action = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishPageBanner' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class = 'deletePageBanner';
						$delete_class_hover = 'Delete Page Banner';

					}else{
						$draft_action = "<div class='tb-tnx-status'>
	                            	<span class='badge badge-dot text-success cursor_pointer'> Published </span>
	                            </div>";
	                    $delete_class = 'trashPageBanner';
	                    $delete_class_hover = 'Trash Page Banner';
	                    
					}


					$image = (($list['file_name']=='') ? ASSETS_PATH.'file_upload.jpg'  : UPLOADS.$list['file_name']);

					$td_data = " data-data_id='".$list['id']."' data-data_link='pagebanner/edit' ";

		    		$layout .= "
	    				<tr class='nk-tb-item'>
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
                                <span class='text-primary'>".$this->publishContent($list['page_name'])."</span>
	                        </td>
	                         <td class='nk-tb-col td_click' $td_data>
                               	$draft_action
                            </td>
	                         <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changePageBannerStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
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

		// Add Page Banner

		function addPageBanner($input,$image)
		{	
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$data 		= $this->cleanStringData($input);
				$admin_id 	= $_SESSION["ecom_admin_id"];
				$curr 		= date("Y-m-d H:i:s");
				$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;
				$pop_up 			= isset($data['pop_up']) ? 1 : 0;
				$category_list 		= isset($data['category_list']) ? 1 : 0;
				$visibility_date 	= isset($data['visibility_date']) ? 1 : 0;
				if ($image['is_uploaded']) {
					$file_status = $image['status'];
					$image_q = "
						,file_name 	= '".$image['file_name']."',
						file_type 		= '".$image['file_type']."',
						file_size 		= '".$image['file_size']."',
						file_alt_name 	= '".$data['image_alt_name']."'
					";
				}else{
					$file_status = "ok";
					$image_q = "";
				}

				$button_link 	= ($data['button_type'] == "main_category") ? $data['main_category_id'] : (($data['button_type'] == "sub_category")  ? $data['sub_category_id'] : $data['product']);

				$query = "INSERT INTO ".PAGE_BANNER_TBL." SET 
				        title 		    = '".$data['title_name']."',
						button_name 	= '".$data['bname']."',
						page_name 		= '".$data['page_name']."',
						page_token 		= '".$this->hyphenize($data['page_token'])."',
						button_type	    = '".$data['button_type']."',
						button_link 	= '".$button_link."',
						message 		= '".$data['message']."',
						is_draft 		= '".$save_as_draft."'
						".$image_q." 
						,status			= '1',
						created_at 		= '$curr',
						updated_at 		= '$curr' ";
				$exe 	= $this->exeQuery($query);
				if($exe){
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Edit Offer Banner

		function editPageBanner($input,$image)
		{ 
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$data 				= $this->cleanStringData($input);
				$curr 				= date("Y-m-d H:i:s");
				$id    				=	$this->decryptData($input['session_token']);
				$save_as_draft 	    = isset($data['save_as_draft']) ? 1 : 0;
					if ($image['is_uploaded']) {
						$file_status = $image['status'];
						$image_q 	 = "
							, file_name 	= '".$image['file_name']."',
							file_type 		= '".$image['file_type']."',
							file_size 		= '".$image['file_size']."'
							
						";
						// Remove previous image
						$info = $this->getDetails(PAGE_BANNER_TBL,"file_name"," id='$id' ");
						if ($info['file_name']!='') {
							unlink("./resource/uploads/".$info['file_name']);
						}
					}else{
						$file_status = "ok";
						$image_q = "";
					}
					if ($file_status=="ok") {

						$button_link 	= ($data['button_type'] == "main_category") ? $data['main_category_id'] : (($data['button_type'] == "sub_category")  ? $data['sub_category_id'] : $data['product']);

					$query = "UPDATE ".PAGE_BANNER_TBL." SET 
					            title		    = '".$data['title_name']."',
								button_name 	= '".$data['bname']."',
								page_name		= '".$data['page_name']."',
								page_token 		= '".$data['page_token']."',
								button_type		= '".$data['button_type']."',
								button_link 	= '".$button_link."',
								file_alt_name 	= '".$data['image_alt_name']."',
								is_draft 		= '".$save_as_draft."'
								".$image_q." 
								,status			= '1',
								created_at 		= '$curr',
								updated_at 		= '$curr' 
								WHERE id='$id' ";
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
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}	


		// Change Offer Banner  Status

		function changePageBannerStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(PAGE_BANNER_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".PAGE_BANNER_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".PAGE_BANNER_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Publish Offer Banner

		function publishPageBanner($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".PAGE_BANNER_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		

		// Update Page Banner delete status

		function deletePageBanner($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(PAGE_BANNER_TBL,"file_name"," id ='$data' AND is_draft='1' ");
			// Remove Image
			if ($info['file_name']!='') {
				unlink("./resource/uploads/".$info['file_name']);
			}
			$delete = $this -> deleteRow(PAGE_BANNER_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}

		// Trash Page Banner

		function trashPageBanner($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".PAGE_BANNER_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Restore Page Banner

		function restorePageBanner($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(PAGE_BANNER_TBL,"delete_status,is_draft"," id ='$data' ");
			$query = "UPDATE ".PAGE_BANNER_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					  Coupon Management
		----------------------------------------------*/

		// Manage Coupon 

		function manageCoupon() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".COUPON_TBL." WHERE 1 AND delete_status='0' ORDER BY id DESC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					// Draft Status
					$draft_status = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row = (($list['is_draft']==1) ? "draft_item" : ""); 


					if ($list['is_draft']==1) {
						$draft_action = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishCoupon' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class = 'deleteCoupon';
						$delete_class_hover = 'Delete Coupon';

					}else{
						$draft_action = "<div class='tb-tnx-status'>
	                            	<span class='badge badge-dot text-success cursor_pointer'> Published </span>
	                            </div>";
	                    $delete_class = 'trashCoupon';
	                    $delete_class_hover = 'Trash Coupon';
	                    
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

					// Coupon type
					if($list['type']=='c_t_percentage'){
						$couponType = "Percentage";
					}elseif($list['type']=='c_t_fixed_amount'){
						$couponType = "Fixed Amount";
					}else{
						$couponType = "Free Shipping";
					}

					// Coupon Applies For
					if($list['applies_to']=='a_t_all_products'){
						$couponFor = "All Products";
					}elseif($list['applies_to']=='a_t_specific_category'){
						$couponFor = "Specific Category";
					}else{
						$couponFor = "Specific Product";
					}

					$td_data = "data-data_id='".$list['id']."'  data-data_link='coupon/edit'";



		    		$layout .= "
	    				<tr class='nk-tb-item $is_draft_row'>
	    					<td class='nk-tb-col td_click' $td_data>
                                ".$i."
                            </td>
                            <td class='nk-tb-col td_click' $td_data>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col tb-col-md td_click' $td_data>
                                <span class='text-primary'>".$this->publishContent($list['coupon_code'])."</span>
	                        </td>
	                        <td class='nk-tb-col tb-col-md td_click' $td_data>
                                <span >".$couponType."</span>
	                        </td>
	                        <td class='nk-tb-col tb-col-md td_click' $td_data>
                                <span >".$couponFor."</span>
	                        </td>
	                        <td class='nk-tb-col tb-col-md td_click' $td_data>
                                <span >".date("d-m-Y",strtotime($list['start_date']))."</span>
	                        </td>
	                        <td class='nk-tb-col'>
                               	$draft_action
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeCouponStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
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

		// Get Location Group Name to map 

		function getlocationGroup($current="")
	  	{
	  		$layout = "";

	  		if($current!="" || $current!=0) {
				$array_ids = explode(',',$current);
			} else {
				$array_ids = array();
			}

	  		$q = "SELECT id,token,group_name FROM ".LOCATIONGROUP_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = ((in_array($list['id'], $array_ids)) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['group_name']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}


	  	// Get Categories to map 

		function getCategories($current="")
	  	{
	  		$layout = "";

	  		if($current!="" || $current!=0) {
				$array_ids = explode(',',$current);
			} else {
				$array_ids = array();
			}

	  		$q = "SELECT id,category FROM ".MAIN_CATEGORY_TBL." WHERE status='1' AND delete_status='0' AND is_draft='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = ((in_array($list['id'], $array_ids)) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['category']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get products to map 

		function getproducts($current="")
	  	{
	  		$layout = "";

	  		if($current!="" || $current!=0) {
				$array_ids = explode(',',$current);
			} else {
				$array_ids = array();
			}

	  		$q = "SELECT id,product_name FROM ".PRODUCT_TBL." WHERE status='1' AND delete_status='0' AND is_draft='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = ((in_array($list['id'], $array_ids)) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['product_name']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

		// Add Coupon 

		function addCoupon($input)
		{	
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf == 'success') {
				$coupon_code = $this->hyphenize($input['coupon_code']);
				$check_token = $this->check_query(COUPON_TBL,"id"," token='".$coupon_code."' ");
				if ($check_token == 0){
					// if ($check_token == 0) {
						$token = $coupon_code;
					// }else{
					// 	$token = $coupon_code."-".$this->generateRandomString("5");
					// }

					$admin_id 		= $_SESSION["ecom_admin_id"];
					$curr 			= date("Y-m-d H:i:s");
					$save_as_draft  = isset($input['save_as_draft']) ? 1 : 0;

					$shippping_location_groups = "";

					if ($input['coupon_type'] == 'c_t_free_shipping') {
						$location_groups = $input['shippping_location_groups'];
						$count_location = count($location_groups);
						if ($count_location>0) {
							for ($i=0; $i < $count_location; $i++) {
								$comma = (($i>0) ? "," : "");
								$shippping_location_groups .= $comma.$location_groups[$i];
							}
						}
					} 

					$specific_category = "";

					if ($input['applies_to'] == 'a_t_specific_category') {
						$input_category = $input['specific_category'];
						$count_category = count($input_category);
						if ($count_category>0) {
							for ($i=0; $i < $count_category; $i++) {
								$comma = (($i>0) ? "," : "");
								$specific_category .= $comma.$input_category[$i];
							}
						}
					}

					$specific_product = "";

					if ($input['applies_to'] == 'a_t_specific_product') {
						$input_product = $input['specific_product'];
						$count_product = count($input_product);
						if ($count_product>0) {
							for ($i=0; $i < $count_product; $i++) {
								$comma = (($i>0) ? "," : "");
								$specific_product .= $comma.$input_product[$i];
							}
						}
					} 

					if($input['min_requirements'] == 1) {
						$min_purchase_amt = $this->cleanString($input['min_purchase_amt']);
					} else {
						$min_purchase_amt = "";
					}
	 
					if($input['usage_limit'] == 1) {
						$usage_limit_value = $this->cleanString($input['usage_limit_value']);
					} else {
						$usage_limit_value = "0";
					}
					
					if($input['per_user_limit']==1) {
						$per_user_limit_value = $this->cleanString($input['per_user_limit_value']);
					} else {
						$per_user_limit_value = "0";
					}

					if($input['coupon_type'] == 'c_t_percentage' || $input['coupon_type'] == 'c_t_fixed_amount' ) {
						$discount_value = $this->cleanString($input['discount_value']);
					} else {
						$discount_value = "";
					}

					if($input['coupon_type'] == 'c_t_percentage') {
						$max_discount_price = $this->cleanString($input['max_discount_price']);
					} else {
						$max_discount_price = 0;
					}

					// Coupon Start/End Time

					$start_time = date("H:i:s",strtotime($input['start_time'])) ;
					$end_time   = date("H:i:s",strtotime($input['end_time'])) ;

					$query = "INSERT INTO ".COUPON_TBL." SET 
							token 					= '".$token."',
							coupon_code				= '".$this->cleanString($input['coupon_code'])."',
							type 					= '".$this->cleanString($input['coupon_type'])."',
							free_shipping_location  = '".$shippping_location_groups."',
							discount_value 			= '".$discount_value."',
							max_discount_price 		= '".$max_discount_price."',
							applies_to 				= '".$this->cleanString($input['applies_to'])."',
							specific_category		= '".$specific_category."',
							specific_product		= '".$specific_product."',
							min_requirements 		= '".$this->cleanString($input['min_requirements'])."',
							min_purchase_amt 		= '".$min_purchase_amt."',
							per_user_limit			= '".$this->cleanString($input['per_user_limit'])."',
							per_user_limit_value    = '".$per_user_limit_value."',
							usage_limits 			= '".$this->cleanString($input['usage_limit'])."',
							usage_limit_value 		= '".$usage_limit_value."',
							start_date 				= '".date("Y-m-d",strtotime($input['start_date']))."',
							start_time 				= '".$start_time."',
							end_date 				= '".date("Y-m-d",strtotime($input['end_date']))."',
							end_time 				= '".$end_time."',
							is_draft 				= '".$save_as_draft."',
							status					= '1',
							added_by 				= '$admin_id',	
							created_at 				= '$curr',
							updated_at 				= '$curr' ";

					$exe 	= $this->exeQuery($query);

					if($exe){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}
				}else{
					return "The entered coupon already exists.";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Edit Coupon 

		function editCoupon($input)
		{ 
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$id 			= $this->decryptData($input['session_token']);
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft  = isset($input['save_as_draft']) ? 1 : 0;

				if (isset($input['save_as_draft'])==1) {
					$coupon_code = $this->hyphenize($input['coupon_code']);
					$check_token = $this->check_query(COUPON_TBL,"id"," token='".$coupon_code."' AND id!='$id' ");
					//if ($check_token==0) {
					$token = $coupon_code;
					// }else{
					// 	$token = $coupon_code."-".$this->generateRandomString("5");
					// }
					$token_q = "token  = '".$token."', ";
				}else{
					$coupon_code = $this->hyphenize($input['coupon_code']);
					$check_token = $this->check_query(COUPON_TBL,"id"," token='".$coupon_code."' AND id!='$id' ");
					$token_q = '';
				}

				if ($check_token == 0){
					if($input['min_requirements']==1) {
						$min_purchase_amt = $this->cleanString($input['min_purchase_amt']);
					} else {
						$min_purchase_amt="";
					}
					
					if($input['usage_limit']==1) {
						$usage_limit_value = $this->cleanString($input['usage_limit_value']);
					} else {
						$usage_limit_value="0";
					}

					if($input['per_user_limit']==1) {
						$per_user_limit_value = $this->cleanString($input['per_user_limit_value']);
					} else {
						$per_user_limit_value = "0";
					} 

					if($input['coupon_type'] == 'c_t_percentage') {
						$max_discount_price = $this->cleanString($input['max_discount_price']);
					} else {
						$max_discount_price = 0;
					}

					if($input['coupon_type']=='c_t_percentage' || $input['coupon_type']=='c_t_fixed_amount' ) {
						$discount_value = $this->cleanString($input['discount_value']);
					} else {
						$divscount_value = "";
					}

					$shippping_location_groups = "";

					if ($input['coupon_type']=='c_t_free_shipping') {
						$location_groups = $input['shippping_location_groups'];
						$count_location = count($location_groups);
						if ($count_location>0) {
							for ($i=0; $i < $count_location; $i++) {
								$comma = (($i>0) ? "," : "");
								$shippping_location_groups .= $comma.$location_groups[$i];
							}
						}
					}

					$specific_category = "";

					if ($input['applies_to']=='a_t_specific_category') {
						$input_category = $input['specific_category'];
						$count_category = count($input_category);
						if ($count_category>0) {
							for ($i=0; $i < $count_category; $i++) {
								$comma = (($i>0) ? "," : "");
								$specific_category .= $comma.$input_category[$i];
							}
						}
					}

					$specific_product = "";

					if ($input['applies_to']=='a_t_specific_product') {
						$input_product = $input['specific_product'];
						$count_product = count($input_product);
						if ($count_product>0) {
							for ($i=0; $i < $count_product; $i++) {
								$comma = (($i>0) ? "," : "");
								$specific_product .= $comma.$input_product[$i];
							}
						}
					}

					// Coupon Start/End Time

					$start_time =date("H:i:s",strtotime($input['start_time'])) ;
					$end_time   =date("H:i:s",strtotime($input['end_time'])) ;

					$query = "UPDATE ".COUPON_TBL." SET 
							".$token_q."
							coupon_code				= '".$this->cleanString($input['coupon_code'])."',
							type 					= '".$this->cleanString($input['coupon_type'])."',
							free_shipping_location  = '".$shippping_location_groups."',
							discount_value 			= '".$discount_value."',
							max_discount_price 		= '".$max_discount_price."',
							applies_to 				= '".$this->cleanString($input['applies_to'])."',
							specific_category		= '".$specific_category."',
							specific_product		= '".$specific_product."',
							min_requirements 		= '".$this->cleanString($input['min_requirements'])."',
							min_purchase_amt 		= '".$min_purchase_amt."',
							per_user_limit			= '".$this->cleanString($input['per_user_limit'])."',
							per_user_limit_value    = '".$per_user_limit_value."',
							usage_limits 			= '".$this->cleanString($input['usage_limit'])."',
							usage_limit_value 		= '".$usage_limit_value."',
							start_date 				= '".date("Y-m-d",strtotime($input['start_date']))."',
							start_time 				= '".$start_time."',
							end_date 				= '".date("Y-m-d",strtotime($input['end_date']))."',
							end_time 				= '".$end_time."',
							is_draft 				= '".$save_as_draft."',
							status					= '1',
							added_by 				= '$admin_id',	
							updated_at 				= '$curr' WHERE id='$id' ";
					$exe 	= $this->exeQuery($query);
						
					if($exe){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}
				}else{
					return "The entered coupon already exists.";
				}
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Change Coupon  Status

		function changeCouponStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(COUPON_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".COUPON_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".COUPON_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update Coupon delete status

		function deleteCoupon($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(COUPON_TBL,"file_name"," id ='$data' AND is_draft='1' ");
			// Remove Image
			if ($info['file_name']!='') {
				unlink("./resource/uploads/".$info['file_name']);
			}
			$delete = $this -> deleteRow(COUPON_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}

		// Trash Coupon

		function trashCoupon($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".COUPON_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Publish Coupon

		function publishCoupon($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".COUPON_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		// Restore Bolg

		function restoreCoupon($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".COUPON_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}
		

		/*--------------------------------------------- 
					Product  Sub Category
		----------------------------------------------*/

		// Manage Sub Category 

		function manageSubCategory() 
		{
			$layout = "";
	    	$q 		= "SELECT * FROM ".SUB_CATEGORY_TBL." WHERE 1 AND delete_status='0' ORDER BY id ASC" ;
 		    $exe 	= $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status 		= (($list['status']==1) ? "On" : "Off"); 
		    		$status_c 		= (($list['status']==1) ? "checked" : " ");
					$status_class 	= (($list['status']==1) ? "text-success" : "text-danger");

					// Draft Status
					$draft_status 		= (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c 	= (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row 		= (($list['is_draft']==1) ? "draft_item" : ""); 


					if ($list['is_draft']==1) {
						$draft_action 		= "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishSubcategory' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class 		= 'deleteSubcategory';
						$delete_class_hover = 'Delete Subcategory';

					}else{
						$draft_action 		= "<div class='tb-tnx-status'>
					                            	<span class='badge badge-dot text-success cursor_pointer'> Published </span>
					                            </div>";
	                    $delete_class 		= 'trashSubcategory';
	                    $delete_class_hover = 'Trash Subcategory';
	                    
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

					$td_data = " data-data_id='".$list['id']."' data-data_link='subcategory/edit' ";

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
                                <span class='text-primary'>".$this->publishContent($list['subcategory'])."</span>
	                        </td>
	                        <td class='nk-tb-col tb-col-md td_click' $td_data>
                                <span class='text-primary'>".$this->publishContent($list['category_id'])."</span>
	                        </td>
	                        <td class='nk-tb-col'>
                               	$draft_action
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeSubcategoryStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
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

		// Subcategory Vendor Commission Get Tax List

		function getVendorCommissionTax($current="")
	  	{
	  		$layout = "";
	  		$q 		= "SELECT id,tax_class FROM ".TAX_CLASSES_TBL." WHERE status='1' AND delete_status='0' " ;
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

	  	// Get Filter Group Drop Down List

		function getFilterGroupsDropDown($current="")
	  	{
	  		$layout = "";
	  		$q = "SELECT id,filter_group_name FROM ".FILTER_GROUP_MASTER_TBL." WHERE status='1' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['filter_group_name']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get Filter Group checkbox List

		function getFilterGroupCheckBox($sub_category_id="")
	  	{	
	  		$group_ids = array();

	  		if($sub_category_id!="") {
	  			$q1  = "SELECT id,group_id FROM ".FILTER_GROUP_VS_SUB_CATEGORY_TBL." WHERE sub_category_id='".$sub_category_id."' ";
	  			$exe = $this->exeQuery($q1);
	  			if(mysqli_num_rows($exe) > 0) {
	  				while ($list = mysqli_fetch_assoc($exe)) {
	  					$group_ids[] = $list['group_id'] ;
	  				}
	  			}
	  		}

	  		$layout = "";
	  		$q 		= "SELECT * FROM ".FILTER_GROUP_MASTER_TBL." WHERE status='1' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = ((in_array($list['id'],$group_ids)) ? 'checked' : '');
					$layout.= "<div class='form-group col-md-2'>
									<div class='custom-control custom-control-sm custom-checkbox'>
									    <input type='checkbox' class='custom-control-input' value='".$list['id']."' name='filter_group[]' id='customCheck".$i."' $selected>
									    <label class='custom-control-label' for='customCheck".$i."'> <label class='form-label'>".$list['filter_group_name']."
									    </label></label>
									 </div>
								</div>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

		// Add Subcategory 

		function addSubcategory($data,$image)
		{	
			$validate_csrf = $this->validateCSRF($data);
			if($validate_csrf=='success') {
				$subcategory_token = $this->hyphenize($data['title_name']);
				$check_token 	   = $this->check_query(SUB_CATEGORY_TBL,"id"," page_url='".$subcategory_token."' ");

				if ($check_token==0) {

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
						$query = "INSERT INTO ".SUB_CATEGORY_TBL." SET 
								page_url				= '".$subcategory_token."',
								subcategory 			= '".$this->cleanString($data['title_name'])."',
								sort_order 				= '".$this->cleanString($data['sort_order'])."',
								category_id 			= '".$this->cleanString($data['category_id'])."',
								description 			= '".$this->cleanString($data['description'])."',
								vendor_commission 		= '".$this->cleanString($data['vendor_commission'])."',
								vendor_commission_tax	= '".$this->cleanString($data['vendor_commission_tax'])."',
								is_draft 				= '".$save_as_draft."'
								".$image_q." 
								, meta_title 			= '".$this->cleanString($data['meta_title'])."',
								meta_description 		= '".$this->cleanString($data['meta_description'])."',
								meta_keyword 			= '".$this->cleanString($data['meta_keyword'])."',
								status					= '1',
								added_by 				= '$admin_id',	
								created_at 				= '$curr',
								updated_at 				= '$curr' ";
						$last_sub_cat_id 	= $this->lastInserID($query);

						if($last_sub_cat_id) {
							if(isset($data['filter_group'])) {
								foreach ($data['filter_group'] as $key => $value) {
									$filter_query = "INSERT INTO ".FILTER_GROUP_VS_SUB_CATEGORY_TBL." SET
												group_id        = '".$value."',
												sub_category_id = '".$last_sub_cat_id."'  ";
									$exe = $this->exeQuery($filter_query);
								}
							}
						}

						if($last_sub_cat_id){
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}	

					}else{
						return $image['message'];
					}

				}else{
					$check_in_trash = $this->check_query(SUB_CATEGORY_TBL,"id"," page_url='".$subcategory_token."' AND delete_status='1' "); 
					
					if($check_in_trash) {
						return "The entered sub category already present on the trash. Kindly restore it from the trash section.";
					} else {
						return "The entered sub category already exists.";
					}
				}
				
				
			}else{
				return $validate_csrf;
			}
		}

		// Get Main Category to map 

		function getmainCategory($current="")
	  	{
	  		$layout = "";
	  		$q 		= "SELECT id,page_url,category FROM ".MAIN_CATEGORY_TBL." WHERE  is_draft='0' AND status='1' AND delete_status='0' " ;
	  		$query 	= $this->exeQuery($q);
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
	  	
		// Edit Subcategory 

		function editSubcategory($data,$image)
		{ 
			$validate_csrf = $this->validateCSRF($data);
			if($validate_csrf=='success'){
				$id  			  =	$this->decryptData($data['session_token']);
				$subcategory_name = $this->hyphenize($data['title_name']);
				$check_token      = $this->check_query(SUB_CATEGORY_TBL,"id"," page_url='".$subcategory_name."' AND id!='$id' ");

				if ($check_token) {
					$check_in_trash = $this->check_query(SUB_CATEGORY_TBL,"id"," page_url='".$subcategory_name."' AND id!='$id' AND delete_status='1' "); 
				
					if($check_in_trash) {
						return "The entered sub category already present on the trash. Kindly restore it from the trash section.";
					} else {
						return "The entered sub category already exists.";
					}
				}

				if (isset($data['save_as_draft'])==1) {
					$token_q = "page_url  = '".$subcategory_name."', ";
				}else{
					$token_q = '';
				}

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
						$info = $this->getDetails(SUB_CATEGORY_TBL,"file_name"," id='$id' ");
						if ($info['file_name']!='') {
							unlink("./resource/uploads/".$info['file_name']);
						}
					}else{
						$file_status = "ok";
						$image_q = "";
					}	

					if ($file_status=="ok") {
						 $query="UPDATE ".SUB_CATEGORY_TBL." SET 
								".$token_q."
								subcategory 			= '".$this->cleanString($data['title_name'])."',
								sort_order 				= '".$this->cleanString($data['sort_order'])."',
								category_id 			= '".$this->cleanString($data['category_id'])."',
								description 			= '".$this->cleanString($data['description'])."',
								vendor_commission 		= '".$this->cleanString($data['vendor_commission'])."',
								vendor_commission_tax 	= '".$this->cleanString($data['vendor_commission_tax'])."',
								is_draft 				= '".$save_as_draft."'
								".$image_q." 
								, meta_title 			= '".$this->cleanString($data['meta_title'])."',
								meta_description 		= '".$this->cleanString($data['meta_description'])."',
								meta_keyword 			= '".$this->cleanString($data['meta_keyword'])."',
								status					= '1',
								added_by 				= '$admin_id',	
								updated_at 				= '$curr' WHERE id='$id' ";
						$exe = $this->exeQuery($query);

						if($exe) {

							if(isset($data['filter_group'])) {
								$filter_delete_query = "DELETE FROM ".FILTER_GROUP_VS_SUB_CATEGORY_TBL." WHERE sub_category_id='".$id."' ";
								$filter_delete_exe   = $this->exeQuery($filter_delete_query);
								foreach ($data['filter_group'] as $key => $value) {
									$filter_query = "INSERT INTO ".FILTER_GROUP_VS_SUB_CATEGORY_TBL." SET
										group_id       = '".$value."',
										sub_category_id =  '".$id."'  ";
									$exe = $this->exeQuery($filter_query);
								}
							}
							
						}

					}else{
						return $image['message'];
					}
				if($exe){
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Change Subcategory  Status

		function changeSubcategoryStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(SUB_CATEGORY_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".SUB_CATEGORY_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".SUB_CATEGORY_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Trash Subcategory

		function trashSubcategory($data)
		{
			$data 	= $this->decryptData($data);
			$curr 	= date("Y-m-d H:i:s");
			$query 	= "UPDATE ".SUB_CATEGORY_TBL." SET delete_status='1',updated_at='$curr' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Update Subcategory delete status

		function deleteSubcategory($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(SUB_CATEGORY_TBL,"file_name"," id ='$data' AND is_draft='1' ");
			// Remove Image
			if ($info['file_name']!='') {
				unlink("./resource/uploads/".$info['file_name']);
			}
			$delete = $this -> deleteRow(SUB_CATEGORY_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}
		
		// Publish Subcategory

		function publishSubcategory($data)
		{
			$data 	= $this->decryptData($data);
			$query 	= "UPDATE ".SUB_CATEGORY_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		// Restore Subcategory

		function restoreSubcategory($data)
		{	
			$data  	= $this->decryptData($data);
			$query 	= "UPDATE ".SUB_CATEGORY_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Select Product

		function getProductList($current="")
		{
			$layout ="";
			$query 	= "SELECT id,product_name FROM ".PRODUCT_TBL." WHERE status='1' AND is_draft='0' AND delete_status='0' ";
			$exe    = $this->exeQuery($query);
			if(mysqli_num_rows($exe) > 0){
				while ($list = mysqli_fetch_array($exe)) {
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['product_name']."</option>";
				}
			}
			return $layout;
		}

		// Select Sub Category List

		function getSubCategoryList($current="")
		{
			$layout ="";
			$query  = "SELECT * FROM ".SUB_CATEGORY_TBL."  WHERE status='1' AND delete_status='0' AND is_draft='0'";
			$exe    = $this->exeQuery($query);
			if(mysqli_num_rows($exe) > 0){
				while ($list = mysqli_fetch_array($exe)) {
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['subcategory']."</option>";
				}
			}
			return $layout;
		}

		// Select Main Category List

		function getCategoryList($current="")
		{
			$layout ="";
			$query = "SELECT * FROM ".MAIN_CATEGORY_TBL."  WHERE status='1' AND delete_status='0' AND is_draft='0'";
			$exe    = $this->exeQuery($query);
			if(mysqli_num_rows($exe) > 0){
				while ($list = mysqli_fetch_array($exe)) {
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['category']."</option>";
				}
			}
			return $layout;
		}

		
	/*-----------Dont'delete---------*/

	}


?>




